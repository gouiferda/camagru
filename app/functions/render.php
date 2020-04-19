<?php


function error_404()
{
  header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
  exit();
}

function error_500()
{
  header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
  exit();
}


function renderView($view, array $args)
{
  global $page_title;
  global $body;
  global $sidebar;
  global $sidebar_title;
  global $customcss;
  global $errors;
  global $session;
  global $mailer;
  global $connected_user;
  global $stickers;

  $errors = [];
  // $pages_with_attr = ["post","profile","confirm","reset"];
  // if (!isset($route_args[0]) || !in_array($view,$pages_with_attr))
  // {
  //   $route_args[0] = "0";
  // }
  $path = "app/views/" . $view . ".php";
  include_once($path);
}

function renderAjax($view, array $args)
{
  global $page_title;
  global $body;
  global $sidebar;
  global $sidebar_title;
  global $customcss;
  global $errors;
  global $session;
  global $mailer;
  global $connected_user;
  global $stickers;

  $errors = [];
  $path = "app/ajax/" . $view . ".ajax.php";
  include_once($path);
}
