<?php
session_start();

require_once("lib/News.php");
require_once("lib/Page.php");

// if the user is not logged in
if(!isset($_SESSION['logged_in'])) {
	header("Location: login.php");
}

if(isset($_POST['post'])) {
	// variable to store error messages
	$error_messages = array();
	// store fields into appropriate variables
	$title = $_POST['title'];
	$message = $_POST['message'];
	$user_id = $_SESSION['user_id'];
	isset($_POST['draft']) ? $draft = 1 : $draft = 0;

	// validate the form
	if(empty($title)) {
		$error_messages['title'] = "Required field, cannot be empty.";
	}
	if(empty($message)) {
		$error_messages['message'] = "Required field, cannot be empty.";
	}
	
	if(empty($error_messages)) {
		// insert news
		$news = new News();
		$news->title = $title;
		$news->message = $message;
		$news->user_id = $user_id;
		$news->draft = $draft;
		$posted = $news->create();
		if($posted) {
			header("Location: news.php?id={$news->id}");
		}
		else {
			$error_messages['main'] = "News was not posted successfully. Please try again later.";
		}
	}
}

$page = new Page();
$page->title = "Create &lt; News &lt; Craften";
$page->heading = "Craften";
echo $page->header();
echo $page->breadcrumb(array("Home"=>"index.php", "News"=>"news.php"));
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<h2>Create News</h2>
	<?php if (isset($error_messages['main'])) {
		echo "<p class=\"error\">{$error_messages['main']}</p>";
	}?>
	<div class="input">
		<?php if (isset($error_messages['title'])) {
			echo "<p class=\"error\">{$error_messages['title']}</p>";
		}?>
		<label for="title">Title</label>
		<input type="text" name="title" id="title"
			<?php if(isset($_POST['title'])) { echo "value=\"{$_POST['title']}\""; } ?>
		/>
	</div>
	<div class="input">
		<?php if (isset($error_messages['message'])) {
			echo "<p class=\"error\">{$error_messages['message']}</p>";
		}?>
		<label for="message">Message</label>
		<textarea name="message" id="message"><?php 
			if(isset($_POST['message'])) { echo $_POST['message']; } 
		?></textarea>
	</div>
	<div class="input">
		<input type="checkbox" name="draft" value="true" 
			<?php if(isset($_POST['draft'])) { echo "checked=true"; } ?>
		/> Draft
	</div>
	<div class="input">
		<input type="submit" name="post" value="Post" />
	</div>
</form>
<?php
echo $page->footer();
?>