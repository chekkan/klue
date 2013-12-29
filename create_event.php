<?php
session_start();

require_once("lib/Page.php");
require_once("lib/Models/Event.php");

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
echo $page->header("Events");
echo $page->breadcrumb(array("Home"=>"index.php", "Events"=>"events.php"));
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" role="form" class="form-horizontal">
	<h2>Create Event</h2>
	<?php if (isset($error_messages['main'])) {
		echo "<p class=\"text-danger\">{$error_messages['main']}</p>";
	}?>
	<div class="form-group">
		<label for="title" class="col-sm-2 control-label">Title</label>
		<div class="col-sm-10">
			<input type="text" name="title" id="title" class="form-control" placeholder="Event title"
				<?php if (isset($_POST['title'])) { echo "value=\"{$_POST['title']}\""; } ?>
			/>
			<?php if (isset($error_messages['title'])) {
				echo "<p class=\"text-danger\">{$error_messages['title']}</p>";
			}?>
		</div>
	</div>
	<div class="form-group">
		<label for="date" class="col-sm-2 control-label">Date</label>
		<div class="col-sm-10">
			<input type="date" name="date" id="date" class="form-control" placeholder="YYYY-MM-DD"
				<?php if (isset($_POST['date'])) { echo "value=\"{$_POST['date']}\""; } ?>
			/>
			<?php if (isset($error_messages['date'])) {
				echo "<p class=\"text-danger\">{$error_messages['date']}</p>";
			}?>
		</div>
	</div>
	<div class="form-group">
		<label for="venue" class="col-sm-2 control-label">Venue</label>
		<div class="col-sm-10">
			<input type="text" name="venue" id="venue" class="form-control" placeholder="Venue"
				<?php if (isset($_POST['venue'])) { echo "value=\"{$_POST['venue']}\""; } ?>
			/>
			<?php if (isset($error_messages['venue'])) {
				echo "<p class=\"text-danger\">{$error_messages['venue']}</p>";
			}?>
		</div>
	</div>
	<div class="form-group">
		<label for="description" class="col-sm-2 control-label">Description</label>
		<div class="col-sm-10">
			<textarea name="description" id="description" class="form-control" placeholder="Description"><?php if (isset($_POST['description'])) { echo $_POST['description']; } ?></textarea>
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
						<?php if(isset($_POST['draft'])) { echo "checked=true "; } ?>
					/> Draft
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="submit" name="create" value="Create" class="btn btn-default" />
		</div>
	</div>
</form>

<?php
echo $page->footer();
?>