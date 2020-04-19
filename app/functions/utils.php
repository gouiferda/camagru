<?php


function s($object, $object_field_to_show)
{
  if (isset($object) && is_object($object) && property_exists($object, $object_field_to_show)) {
    echo $object->$object_field_to_show;
  } else {
    echo "";
  }
}

function ss($object, $object_field_to_show)
{
  if (isset($object) && is_object($object) && property_exists($object, $object_field_to_show)) {
    return $object->$object_field_to_show;
  } else {
    //return "-";
    return "";
  }
}

function uc_f($string = "")
{
  return ucfirst(strtolower($string));
}

function time_format($time = "00:00:00")
{
  return date("H:i", strtotime($time));
}

function u($string = "")
{
  return urlencode($string);
}

function raw_u($string = "")
{
  return rawurlencode($string);
}

function h($string = "")
{
  return htmlspecialchars($string);
}


function get_sub($text, $limit)
{
  $output = "";
  if (strlen($text) > 0) {
    $output .= substr($text, 0, $limit);
    if (strlen($text) > $limit) {
      $output .= "...";
    }
  } else {
    $output .= "";
  }
  return $output;
}

function datetimeToFormat($date, $format)
{
  // format :     d/m/Y H:i:s
  $output = "";
  if ($date != "") {
    $output = date($format, strtotime($date));
  }
  return $output;
}

function generateRandomString($length = 10)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

function is_active_link($route, $link_name, $args)
{
  $output = "";

  if ($route == $link_name) {
    $output = "active";
  }
  if ($args[0] != "0") {
    if ($route . '/' . $args[0] == $link_name) {
      $output = "active";
    } else {
      $output = "";
    }
  }
  return $output;
}
