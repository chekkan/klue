<?php
session_start();

require_once("lib/Page.php");

$page = new Page();
echo $page->header("Home");

echo $page->footer();
?>