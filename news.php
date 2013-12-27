<?php
session_start();

require_once("lib/Page.php");
require_once("lib/News.php");
require_once("lib/User.php");
require_once("lib/BlogComment.php");

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
				{$blog_user->full_name()} on 
				".date("d F Y", $news->time_posted)."
			</span>";
		echo "<p>{$news->message}</p>";
		echo "<h3>Comments</h3>";
		// load comments
		$comments = BlogComment::find_by_blog($news->id);
		if(empty($comments)) {
			echo "<p>No comments.</p>";
		}
		else {
			echo "<ul>";
			foreach ($comments as $comment) {
				// get user details
				$blog_user = User::find_by_id($comment->user_id);
				echo "<li>{$comment->message}
						<span class=\"comment_details\">
							<a href=\"profiles.php?id={$blog_user->id}\">{$blog_user->full_name()}</a> on
							{$comment->time_posted}
						</span></li>";
			}
			echo "</ul>";
		}
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']."?id={$news->id}"; ?>" method="post">
			<?php if (isset($error_messages['main'])) { 
				echo "<p class=\"error_message\">{$error_messages['main']}</p>"; } 
			?>
			<div class="input">
				<?php if (isset($error_messages['message'])) { 
					echo "<p class=\"error_message\">{$error_messages['message']}</p>"; } 
				?>
				<label for="message">Comment</label>
				<textarea name="message" id="message"></textarea>
			</div>
			<div class="input">
				<input type="submit" name="comment" value="Comment" />
			</div>
		</form>
		<?php
	}
}
else {
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
		die("No News to display.");
	}

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

	echo $page->footer();
}
?>