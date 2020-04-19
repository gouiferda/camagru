<?php


// Website path : needs work
function site_url()
{
    if (isset($_SERVER['HTTPS'])) {
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    } else {
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'];
}

define("PROJECT_URL", site_url() . '/' . ENV_PROJECT_NAME . '/');


setlocale(LC_ALL, ENV_LANG);
date_default_timezone_set(ENV_TIMEZONE);

//Themes
$themes = [
    "1"=>"themes/bootstrap",
    "2"=>"themes/minty",
    "3"=>"themes/litera",
    "4"=>"themes/cosmo",
    "5"=>"themes/darkly",
    "6"=>"themes/flaty",
    "7"=>"themes/journal",
    "8"=>"themes/lumen",
    "9"=>"themes/yeti",
    "10"=>"themes/materia",
    "11"=>"themes/cyborg",
    "12"=>"themes/superhero",
    "13"=>"themes/sketchy"
];
