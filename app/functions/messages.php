<?php

function errors_messages($errors = array())
{
  $output = '';
  if (!empty($errors)) {
    $output .= "<div class=\"alert alert-secondary\" role=\"alert\">";
    $output .= "Please correct the following errors:";
    $output .= "<ul>";
    foreach ($errors as $error) {
      $output .= "<li>" . h($error) . "</li>";
    }
    $output .= "</ul>";
    $output .= "</div>";
  }
  return $output;
}

function session_messages() {
  global $session;
  $output = "";
  $msg = $session->message();
  $output .= "<div class=\"alert alert-primary\" role=\"alert\">";
  $output .= "<p class=\"mb-0\">".h($msg)."</p>";
  $output .= "</div>";
  if(isset($msg) && $msg != '') {
    $session->clear_message();
    return $output;
  }
}
