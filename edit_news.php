<?php
session_start();

require_once("lib/News.php");
require_once("lib/Page.php");

if(isset($_GET['id'])) {
	// save the variable
	$news_id = $_GET['id'];
	// check to see if the user is logged in
	if(!isset($_SESSION['logged_in'])) {
		header("Location: login.php");
	}
	//validate the id
	if(!News::exists($news_id)) {
		die("News item not found.");
	}
	else {
		// check to see if the form was submited
		if(isset($_POST['save'])) {
			//variable to store errors
			$error_messages = array();
			// save the variables
			$title = $_POST['title'];
			$message = $_POST['message'];
			$user_id = $_SESSION['user_id'];
			isset($_POST['draft']) ? $draft = 1 : $draft = 0;
			
			//validate the variables
			if(empty($title)) {
				$error_messages['title'] = "Required field cannot be left empty.";
			}
			if(empty($message)) {
				$error_messages['message'] = "Required field cannot be left empty.";
			}
			
			// if there was no errors
			if(empty($error_messages)) {
				// update the news
				$news = new News();
				$news->id = $news_id;
				$news->title = $title;
				$news->message = $message;
				$news->user_id = $user_id;
				$news->draft = $draft;
				$updated = $news->save();
				if($updated) {
					header("Location: news.php?id={$news->id}");
				}
				else {
					$error_messages['main'] = "News wasn't updated. Try again later.";
				}
			}
		}
		// get the news details
		$news = News::find_by_id($news_id);
		
		$page = new Page();
		$page->title = "Edit &lt; News &lt; Craften";
		$page->heading = "Craften";
		echo $page->header("News");
		echo $page->breadcrumb(array("Home"=>"index.php", "News"=>"news.php",
		 						$news->title=>"news.php?id={$news->id}"));
		// form to edit news
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']."?id={$news->id}"; ?>" method="post" class="form-horizontal" role="form">
			<?php if (isset($error_messages['main'])) {
				echo "<p class=\"text-danger\">{$error_messages['main']}</p>";
			}?>
			<div class="form-group">
				<label for="title" class="col-sm-2 control-label">Title</label>
				<div class="col-sm-offset-2 col-sm-10">
					<input type="text" name="title" id="title" class="form-control"
						<?php if(isset($_POST['title'])) { echo "value=\"{$_POST['title']}\""; }
						else { echo "value=\"{$news->title}\""; }?>
					/>
					<?php if (isset($error_messages['title'])) {
						echo "<p class=\"text-danger\">{$error_messages['title']}</p>";
					}?>
				</div>
			</div>
			<div class="form-control">
				<label for="message" class="col-sm-2 control-label">Message</label>
				<div class="col-sm-offset-2 col-sm-10">
					<textarea name="message" id="message" class="form-control"><?php 
						if(isset($_POST['message'])) { echo $_POST['message']; }
						else { echo $news->message; }
					?></textarea>
					<?php if (isset($error_messages['message'])) {
						echo "<p class=\"text-danger\">{$error_messages['message']}</p>";
					}?>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-2">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="draft" value="true" 
								<?php if(isset($_POST['draft'])) { echo "checked=true"; } 
								else if($news->draft) { echo "checked=true"; }?>
							/> Draft
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="submit" name="save" value="Save" class="btn btn-default" />
				</div>
			</div>
		</form>
		<?php
		echo $page->footer();
	}
}

?>