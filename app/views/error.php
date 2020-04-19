<?php

$page_title = "Error";

$body = "<div class='col-sm-10 mx-auto mb-4'>";
$body .= "<h1>Oops!</h1>";
$body .= "<p>Page not found</p>";
$body .= "<br>";
$body .= "<a class='btn btn-primary btn-lg' href='".PROJECT_URL."home'>Go home</a>";
$body .= "<br>";
$body .= "</div>";
$body .= "<br class='mb-4'>";

$sidebar = "";
$sidebar .= get_theme_widget();
$sidebar_title = "Themes";


include_once("template.php");