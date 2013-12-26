<?php
session_start();

require_once("lib/Page.php");

$page = new Page();
echo $page->header();

echo $page->footer();
?>