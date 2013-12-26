<?php
session_start();

require_once("lib/Event.php");
require_once("lib/Page.php");

// make sure the user is logged in before letting them see this page
if(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == false) {
	header("Location: login.php");
}

// if the form is submitted
if(isset($_POST['create'])) {
	// error messages array variable
	$error_messages = array();
	// set all fields to their variables
	$title = $_POST['title'];
	$date = $_POST['date'];
	$venue = $_POST['venue'];
	$description = $_POST['description'];
	isset($_POST['draft']) ? $draft = 1 : $draft = 0;
	// all fields are required except description
	if(empty($title)) {
		$error_messages['title'] = "This field is required and cannot be left empty.";
	}
	if(empty($date)) {
		$error_messages['date'] = "This field is required and cannot be left empty.";
	}
	if(empty($venue)) {
		$error_messages['venue'] = "This field is required and cannot be left empty.";
	}
	
	if(empty($error_messages)) {
		$event = new Event();
		$event->title = $title;
		$event->date = $date;
		$event->venue = $venue;
		$event->description = $description;
		$event->user_id = $_SESSION['user_id'];
		$event->draft = $draft;
		$created = $event->create();
		if($created) {
			header("Location: events.php?id={$event->id}");
		}
		else {
			$error_messages['main'] = "Cannot create the event. Try again later.";
		}
	}
}

$page = new Page();
$page->title = "Create &lt; Event &lt; Craften";
$page->heading = "Craften";
echo $page->header();
echo $page->breadcrumb(array("Home"=>"index.php", "Events"=>"events.php"));
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<h2>Create Event</h2>
	<?php if (isset($error_messages['main'])) {
		echo "<p class=\"error\">{$error_messages['main']}</p>";
	}?>
	<div class="input">
		<?php if (isset($error_messages['title'])) {
			echo "<p class=\"error\">{$error_messages['title']}</p>";
		}?>
		<label for="title">Title</label>
		<input type="text" name="title" id="title" placeholder="Event title"
			<?php if (isset($_POST['title'])) { echo "value=\"{$_POST['title']}\""; } ?>
		/>
	</div>
	<div class="input">
		<?php if (isset($error_messages['date'])) {
			echo "<p class=\"error\">{$error_messages['date']}</p>";
		}?>
		<label for="date">Date</label>
		<input type="date" name="date" id="date" placeholder="YYYY-MM-DD"
			<?php if (isset($_POST['date'])) { echo "value=\"{$_POST['date']}\""; } ?>
		/>
	</div>
	<div class="input">
		<?php if (isset($error_messages['venue'])) {
			echo "<p class=\"error\">{$error_messages['venue']}</p>";
		}?>
		<label for="venue">Venue</label>
		<input type="text" name="venue" id="venue" placeholder="Venue"
			<?php if (isset($_POST['venue'])) { echo "value=\"{$_POST['venue']}\""; } ?>
		/>
	</div>
	<div class="input">
		<?php if (isset($error_messages['description'])) {
			echo "<p class=\"error\">{$error_messages['description']}</p>";
		}?>
		<label for="description">Description</label>
		<textarea name="description" id="description" placeholder="Description"><?php if (isset($_POST['description'])) { echo $_POST['description']; } ?></textarea>
	</div>
	<div class="input">
		<input type="checkbox" name="draft" value="true" 
			<?php if(isset($_POST['draft'])) { echo "checked=true "; } ?>
		/> Draft
	</div>
	<div class="input">
		<input type="submit" name="create" value="Create" />
	</div>
</form>

<?php
echo $page->footer();
?>