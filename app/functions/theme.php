<?php

if (!get_cookie("theme")) {
    set_cookie("theme", ENV_THEME_NUMBER, time() + (86400 * 30));
} else {
    $val = intval(get_cookie("theme"));
    if (!($val >= 1 &&  $val <= 13)) {
        set_cookie("theme", ENV_THEME_NUMBER, time() + (86400 * 30));
    }
}
$theme_chosen = intval(get_cookie("theme"));
if (!($theme_chosen >= 1 &&  $theme_chosen <= 13)) {
    $theme_chosen = ENV_THEME_NUMBER;
}
define("BOOTSTRAP", $themes[$theme_chosen] . ".min.css");
