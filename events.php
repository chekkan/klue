<?php
session_start();

require_once("lib/Page.php");
require_once("lib/Event.php");
require_once("lib/User.php");
require_once("lib/EventComment.php");

$error_messages = array();

if(isset($_GET['id'])) {
	// save the id into its variable
	$event_id = $_GET['id'];
	// check to see if the id is a valid number
	
	// check to see if the event exists
	if(!Event::exists($event_id)) {
		die("The event you are looking for doesn't exists.");
	}
	else {
		$event = Event::findById($event_id);
	}
	// if the comment was submitted
	if(isset($_POST['comment'])) {
		// make sure the user is logged in
		if(!isset($_SESSION['logged_in'])) {
			header("Location: login.php");
		}
		$error_messages = array();
		// validate the message
		if(empty($_POST['message'])) {
			$error_messages['message'] = "Required field and cannot be left empty.";
		}
		
		if(empty($error_messages)) {
			$comment = new EventComment();
			$comment->message = $_POST['message'];
			$comment->event_id = $event_id;
			$comment->user_id = $_SESSION['user_id'];
			$comment->time_posted = date("Y-m-d H:i:s");
			$saved = $comment->save();
			if(!$saved) {
				$error_messages['main'] = "Something went wrong. Try again later.";
			}
		}
	}
	$page = new Page();
	$page->title = $event->title . " &lt; Events";
	$page->heading = "Craften";
	echo $page->header();
	echo $page->breadcrumb(array("Home"=>"index.php", "Events"=>"events.php"));
	// if the user is logged in, show link to edit the event
	if(isset($_SESSION['logged_in'])) {
		echo "<p><a href=\"edit_event.php?id={$event->id}\" class=\"button\">Edit</a>
		<a href=\"delete_event.php?id={$event->id}\" class=\"button\">Delete</a>
		</p>";
	}
	echo "<h2>{$event->title}</h2>";
	echo "<p>Date: ".date('d F Y', strtotime($event->date))."</p>";
	echo "<p>Venue: {$event->venue}</p>";
	echo "<p>{$event->description}</p>";
	// comments
	$comments = EventComment::find_by_event($event->id);
	echo "<h3>Comments</h3>";
	if(empty($comments)) {
		echo "<p>No comments.</p>";
	}
	else {
		echo "<ul>";
		foreach ($comments as $comment) {
			// get user details
			$comment_user = User::find_by_id($comment->user_id);
			echo "<li>{$comment->message}
					<span class=\"comment_details\">
						<a href=\"profiles.php?id={$comment_user->id}\">{$comment_user->full_name()}</a> on 
						{$comment->time_posted}
					</span>
				</li>";
		}
		echo "</ul>";
	}
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']."?id={$event->id}"; ?>" method="post">
		<?php if (isset($error_messages['main'])) { 
			echo "<p class=\"error_message\">{$error_messages['main']}</p>"; } 
		?>
		<div class="input">
			<?php if (isset($error_messages['message'])) { 
				echo "<p class=\"error_message\">{$error_messages['message']}</p>"; } 
			?>
			<label for="message">Comment</label>
			<textarea id="message" name="message"></textarea>
		</div>
		<div class="input">
			<input type="submit" name="comment" value="Comment" />
		</div>
	</form>
	<?php
	echo $page->footer();
}
else {
	// show all the events
	$page = new Page();
	$page->title = "Events";
	$page->heading = "Craften";
	$page->add_css("styles/events.css");
	echo $page->header();
	echo $page->breadcrumb(array("Home"=>"index.php"));
	echo "<h2>Events</h2>";
	
	// if the user is logged in
	if(isset($_SESSION['logged_in'])) {
		echo "<p><a class=\"button\" href=\"create_event.php\">Create Event</a></p>";
	}
	$events = Event::findAll();
	if(!$events) {
		die("No events to be displayed.");
	}
	echo "<ul id=\"events\">";
	foreach ($events as $event) {
		echo "<li>";
		echo "<span class=\"date_container\">";
		echo "<span class=\"date\">".date("d", strtotime($event->date))."</span> ";
		echo "<span class=\"month\">".date("M", strtotime($event->date))."</span> ";
		echo "<span class=\"year\">".date("Y", strtotime($event->date))."</span>";
		echo "</span> ";
		echo "<span class=\"event_container\">";
		echo "<span class=\"title\">
				<a href=\"events.php?id={$event->id}\">{$event->title}</a>
			</span> ";
		echo "<span class=\"venue\">{$event->venue}</span>";
		echo "</span>";
		echo "</li>";
	}
	echo "</ul>";
	echo $page->footer();
}
?>