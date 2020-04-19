<?php

function set_cookie($name,$value,$time)
{
    setcookie($name, $value, $time, "/"); // 86400 = 1 day 
}

function get_cookie($name)
{
    if(!isset($_COOKIE[$name])) {
       return false;
    } else {
        return $_COOKIE[$name];
    }
}


