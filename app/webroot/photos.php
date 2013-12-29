<?php
session_start();

require_once("../lib/Page.php");
require_once("../lib/Models/Photograph.php");
require_once("../lib/Models/Album.php");
require_once("../lib/Models/Comment.php");
require_once("../lib/Models/User.php");

if(isset($_GET['id'])) {

	// check if a comment was submited
	if(isset($_POST['comment'])) {
		// make sure the user is logged in.
		if(!isset($_SESSION['logged_in'])) {
			header("Location: login.php");
		}
		// make sure the photo id is valid
		
		// validate the comments
		$comment = new Comment();
		$comment->message = $_POST['message'];
		$comment->time_posted = date("Y-m-d H:i:s");
		$comment->user_id = $_SESSION['user_id'];
		$comment->photo_id = $_GET['id'];
		$comment->save();
	}

	$photo = Photograph::find_by_id($_GET['id']);
	$album = Album::find_by_id($photo->album_id);
	
	$page = new Page();
	$page->title = $album->title;
	echo $page->header("Gallery");
	echo $page->breadcrumb(array("Home"=>"index.php", "Gallery"=>"albums.php", 
							$album->title=>"albums.php?id={$album->id}"));
							
	echo "<img width=\"720px\" src=\"".$photo->image_path()."\" />";
	echo "<h3>Comments</h3>";
	
	$comments = Comment::find_by_photo($_GET['id']);
	if(empty($comments)) {
		echo "<p>No comments.</p>";
	}
	else {
		echo "<ul>";
		foreach ($comments as $comment) {
			$comment_user = User::find_by_id($comment->user_id);
			echo "<li>{$comment->message}
					<span class=\"comment_details\" style=\"display: block;\">
						<a href=\"profiles.php?id={$comment_user->id}\">{$comment_user->full_name()}</a> on 
						{$comment->time_posted}
					</span>
				</li>";
		}
		echo "</ul>";
	}
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']."?id={$photo->id}"; ?>" method="post">
		<div class="input">
			<label for="message">Comment</label>
			<textarea name="message" id="message"></textarea>
		</div>
		<div class="input">
			<input type="submit" name="comment" value="Comment" />
		</div>
	</form>
	
	<?php
	echo $page->footer();
}
else {
	header("Location: albums.php");
}
?>