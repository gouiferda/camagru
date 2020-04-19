<?php

/*

Gallery features
• This part is to be public and must display all the images edited by all the users,
 ordered by date of creation. It should also allow (only) a connected user to like
  them and/or comment them.
• When an image receives a new comment, the author of the image should be notified by email.
 This preference must be set as true by default but can be deactivated in user’s preferences.
• The list of images must be paginated, with at least 5 elements per page.

*/

$page_title = "Home";

$sidebar_title = "Themes";
$sidebar = "";

$sidebar .= get_theme_widget();


$body .= "<div class='row'>";
$body .= "<div class='col-lg-12'>";
$body .= errors_messages($errors);
$body .= session_messages();
$body .= "</div>";
$body .= "</div>";

$body .= "<div class='row' id='posts-result'>";
$body .= Post::get_limited_output(9);
$body .= "</div>";

$body .= "<div class='row'>";
$body .= "<div class='col-sm-12'>";
$body .= "<div class='d-flex align-items-center justify-content-center'>";

$posts_count = Post::count_all();
if ($posts_count > 9)
{
$body .= "<button class='h4 page-link'  style='font-family: LeckerliOne;' onclick=\"showAjaxResult('posts',load_index,'posts-result','all');load_index++;\">Load more</button>";
}

$body .= "</div>";
$body .= "</div>";
$body .= "</div>";
$body .= "<br class='mb-4'>";



include_once("template.php");
