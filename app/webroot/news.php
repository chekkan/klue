<?php
session_start();

require_once("../lib/Page.php");
require_once("../lib/Models/News.php");
require_once("../lib/Models/User.php");
require_once("../lib/Models/BlogComment.php");
require_once("../../lib/FormHelper.php");

if(isset($_GET['id'])) {
	// save it to variable
	$news_id = $_GET['id'];
	//validate id
	if(!News::exists($news_id)) {
		die("News not found.");
	}
	else {
		// check if the comment was posted
		if(isset($_POST['comment'])) {
			if(!isset($_SESSION['logged_in'])) {
				header("Location: login.php");
			}
			$error_messages = array();
			// validate the form fields
			if(empty($_POST['message'])) {
				$error_messages['message'] = "This field cannot be left empty.";
			}
			
			if(empty($error_messages)) {
				$comment = new BlogComment();
				$comment->message = $_POST['message'];
				$comment->user_id = $_SESSION['user_id'];
				$comment->blog_id = $_GET['id'];
				$comment->time_posted = date("Y-m-d H:i:s");
				$saved = $comment->save();
				if(!$saved) {
					$error_messages['main'] = "Something went wrong. Try again later.";
				}
			}
		}
		$news = News::find_by_id($news_id);
		// get the user details
		$blog_user = User::find_by_id($news->user_id);
		
		$page = new Page();
		$page->title = $news->title . "&lt; News";
		$page->heading = "Craften";
		echo $page->header("News");
		echo $page->breadcrumb(array("Home"=>"index.php", "News"=>"news.php"));

		// if the user is logged in
		if(isset($_SESSION['logged_in'])) {
			echo "<p>
					<a href=\"edit_news.php?id={$news->id}\" class=\"btn btn-default\">Edit</a>
					<a href=\"delete_news.php?id={$news->id}\" class=\"btn btn-default\">Delete</a>
				</p>";
		}
		echo "<h2>{$news->title}</h2>";
		echo "<span class=\"blog_details\">
				<a href=\"profiles.php?id={$blog_user->id}\">{$blog_user->full_name()}</a> on 
				".date("d F Y", $news->time_posted)."
			</span>";
		echo "<p>{$news->message}</p>";

		// load comments
		$comments = BlogComment::find_by_blog($news->id);
		echo "<section>
				<h2>Comments</h2>";
		$form = (isset($error_messages)) ? new FormHelper($error_messages) : new FormHelper();
		echo $form->start($_SERVER['PHP_SELF']."?id=".$news->id);
		echo $form->textarea(array("name" => "message", "id" => "message", "placeholder" => "Please make a comment"));
		echo $form->end("Comment");
		if(empty($comments)) {
			echo "<p>No comments.</p>";
		} else {
			foreach ($comments as $comment) {
				// get user details
				$blog_user = User::find_by_id($comment->user_id);
				echo "<article class=\"well\">
						<header>
							<div class=\"col-sm-6\">
								<a href=\"profiles.php?id={$blog_user->id}\">{$blog_user->full_name()}</a>
							</div>
							<div class=\"col-sm-6 text-right\">{$comment->time_posted}</div>
						</header>
						<p>{$comment->message}</p>
					</article>";
			}
		}
		echo "</section>";

		echo $page->footer();
	}
} else {
	$page = new Page();
	$page->title = "News";
	$page->heading = "Craften";
	echo $page->header("News");
	echo $page->breadcrumb(array("Home"=>"index.php"));

	echo "<h2>News</h2>";

	// if the user is logged in
	if(isset($_SESSION['logged_in'])) {
		echo "<p><a class=\"btn btn-default\" href=\"create_news.php\">Create News</a></p>";
	}

	$newses = News::find_all();

	if(!$newses) {
		echo "<p>No News to display.</p>";
	} else {
		foreach ($newses as $news) {
			// get the user details
			$blog_user = User::find_by_id($news->user_id);
			echo "<h2><a href=\"news.php?id={$news->id}\">{$news->title}</a></h2>";
			echo "<span class=\"blog_details\">
					<a href=\"profiles.php?id={$blog_user->id}\">{$blog_user->full_name()}</a> on 
					".date("d F Y", $news->time_posted)."
				</span>";
			echo "<p>{$news->get_summary()}</p>";
		}
	}

	echo $page->footer();
}
?>