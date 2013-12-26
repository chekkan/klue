<?php
session_start();

require_once("lib/Event.php");
require_once("lib/Page.php");

if(isset($_GET['id'])) {
	$event_id = $_GET['id'];
	// check to see if the user is logged in
	if(!isset($_SESSION['logged_in'])) {
		header("Location: login.php");
	}
	// check if the event exists
	if(!Event::exists($event_id)) {
		die("The event you are looking for doesn't exists.");
	}
	else
	{
		// check to see if the form has been submitted
		if(isset($_POST['save'])) {
			// variable to save all the error messages
			$error_messages = array();
			// save all the form fields into variables
			$title = $_POST['title'];
			$date = $_POST['date'];
			$venue = $_POST['venue'];
			$description = $_POST['description'];
			isset($_POST['draft']) ? $draft = 1 : $draft = 0;
			
			// check to see if all the required fields are not left empty
			if(empty($title)) {
				$error_messages['title'] = "This field is required and cannot be left empty.";
			}
			if(empty($date)) {
				$error_messages['date'] = "This field is required and cannot be left empty.";
			}
			if(empty($venue)) {
				$error_messages['venue'] = "This field is required and cannot be left empty.";
			}
			
			// if there are no errors
			if(empty($error_messages)) {
				$event = new Event();
				$event->id = $event_id;
				$event->title = $title;
				$event->date = $date;
				$event->venue = $venue;
				$event->description = $description;
				$event->user_id = $_SESSION['user_id'];
				$event->draft = $draft;
				$updated = $event->update();
				if($updated) {
					header("Location: events.php?id={$event->id}");
				}
				else {
					$error_messages['main'] = "Cannot update the event. Try again later.";
				}
			}
		}
		// get the event details
		$event = Event::findById($event_id);
		$page = new Page();
		$page->title = "Edit &lt; Event &lt; Craften";
		$page->heading = "Craften";
		$page->add_css("styles/edit_event.css");
		echo $page->header();
		echo $page->breadcrumb(array("Home"=>"index.php", "Events"=>"events.php", $event->title=>"events.php?id={$event->id}"));
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']."?id={$event_id}"; ?>" method="post">
			<h2>Edit Event</h2>
			<?php if (isset($error_messages['main'])) {
				echo "<p class=\"error\">{$error_messages['main']}</p>";
			}?>
			<div class="input">
				<?php if (isset($error_messages['title'])) {
					echo "<p class=\"error\">{$error_messages['title']}</p>";
				}?>
				<label for="title">Title</label>
				<input type="text" name="title" id="title" placeholder="Event title"
					<?php
					if (isset($_POST['title'])) { echo "value=\"{$_POST['title']}\""; }
					else { echo "value=\"{$event->title}\""; }
					?>
				/>
			</div>
			<div class="input">
				<?php if (isset($error_messages['date'])) {
					echo "<p class=\"error\">{$error_messages['date']}</p>";
				}?>
				<label for="date">Date</label>
				<input type="date" name="date" id="date" placeholder="YYYY-MM-DD"
					<?php if (isset($_POST['date'])) { echo "value=\"{$_POST['date']}\""; } 
					else { echo "value=\"{$event->date}\""; } ?>
				/>
			</div>
			<div class="input">
				<?php if (isset($error_messages['venue'])) {
					echo "<p class=\"error\">{$error_messages['venue']}</p>";
				}?>
				<label for="venue">Venue</label>
				<input type="text" name="venue" id="venue" placeholder="Venue"
					<?php if (isset($_POST['venue'])) { echo "value=\"{$_POST['venue']}\""; } 
					else { echo "value=\"{$event->venue}\""; }?>
				/>
			</div>
			<div class="input">
				<?php if (isset($error_messages['description'])) {
					echo "<p class=\"error\">{$error_messages['description']}</p>";
				}?>
				<label for="description">Description</label>
				<textarea name="description" id="description" placeholder="Description"><?php 
					if (isset($_POST['description'])) { echo $_POST['description']; } 
					else { echo $event->description; }
					?></textarea>
			</div>
			<div class="input">
				<input type="checkbox" name="draft" value="true" 
					<?php if(isset($_POST['draft'])) { echo "checked=true"; } 
					else if($event->draft) { echo "checked=true"; }?>
				/> Draft
			</div>
			<div class="input">
				<input type="submit" name="save" value="Save" />
			</div>
		</form>
		<?php
		echo $page->footer();
	}	
}

?>