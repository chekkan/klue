<?php
session_start();

require_once("../../lib/AdminPage.php");

$page = new AdminPage();
$page->title = "Modules &lt; Administration";
echo $page->header("Modules");
echo "<div id=\"main_content\">";
echo "<h2>Modules</h2>";
echo "</div>"; // main_content
echo $page->side_bar();
echo "<div style=\"clear: both;\"></div>";
echo $page->footer();

?>