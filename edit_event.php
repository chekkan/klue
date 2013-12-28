<?php
session_start();

require_once("lib/Page.php");
require_once("lib/Models/Event.php");

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
		$event = Event::find_by_id($event_id);
		$page = new Page();
		$page->title = "Edit &lt; Event &lt; Craften";
		$page->heading = "Craften";
		$page->add_css("styles/edit_event.css");
		echo $page->header("Events");
		echo $page->breadcrumb(array("Home"=>"index.php", "Events"=>"events.php", $event->title=>"events.php?id={$event->id}"));
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']."?id={$event_id}"; ?>" method="post" role="form" class="form-horizontal">
			<h2>Edit Event</h2>
			<?php if (isset($error_messages['main'])) {
				echo "<p class=\"text-danger\">{$error_messages['main']}</p>";
			}?>
			<div class="form-group">
				<label for="title" class="col-sm-2 control-label">Title</label>
				<div class="col-sm-10">
					<input type="text" name="title" id="title" placeholder="Event title" class="form-control"
						<?php
						if (isset($_POST['title'])) { echo "value=\"{$_POST['title']}\""; }
						else { echo "value=\"{$event->title}\""; }
						?>
					/>
					<?php if (isset($error_messages['title'])) {
						echo "<p class=\"text-danger\">{$error_messages['title']}</p>";
					}?>
				</div>
			</div>
			<div class="form-group">
				<label for="date" class="col-sm-2 control-label">Date</label>
				<div class="col-sm-10">
					<input type="date" name="date" id="date" placeholder="YYYY-MM-DD" class="form-control"
						<?php if (isset($_POST['date'])) { echo "value=\"{$_POST['date']}\""; } 
						else { echo "value=\"{$event->date}\""; } ?>
					/>
					<?php if (isset($error_messages['date'])) {
						echo "<p class=\"text-danger\">{$error_messages['date']}</p>";
					}?>
				</div>
			</div>
			<div class="form-group">
				<label for="venue" class="col-sm-2 control-label">Venue</label>
				<div class="col-sm-10">
					<input type="text" name="venue" id="venue" placeholder="Venue" class="form-control"
						<?php if (isset($_POST['venue'])) { echo "value=\"{$_POST['venue']}\""; } 
						else { echo "value=\"{$event->venue}\""; }?>
					/>
					<?php if (isset($error_messages['venue'])) {
						echo "<p class=\"text-danger\">{$error_messages['venue']}</p>";
					}?>
				</div>
			</div>
			<div class="form-group">
				<label for="description" class="col-sm-2 control-label">Description</label>
				<div class="col-sm-10">
					<textarea name="description" id="description" placeholder="Description" class="form-control"><?php 
					if (isset($_POST['description'])) { echo $_POST['description']; } 
					else { echo $event->description; }
					?></textarea>
					<?php if (isset($error_messages['description'])) {
						echo "<p class=\"text-danger\">{$error_messages['description']}</p>";
					}?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="draft" value="true" 
								<?php if(isset($_POST['draft'])) { echo "checked=true"; } 
								else if($event->draft) { echo "checked=true"; }?>
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