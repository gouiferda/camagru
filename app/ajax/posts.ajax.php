<?php



//var_dump($_REQUEST);

//var_dump($args);

$number_get = 1;

if (!is_blank($args[0])) {
    $val = intval($args[0]);
    if ($val >= 1) {
        $number_get = $val;
        $val = null;
    }
}

$type = "all";

if ($session->is_logged_in() && !is_blank($args[1])) {
    $val = $args[1];
    switch ($val) {
        case 'all':
            $type = "all";
            break;
        case 'user':
            $type = "user";
            break;
    }
}

$limit = $number_get * 9;

$output = "";

switch ($type) {
    case 'all':
        $output .= Post::get_limited_output($limit);
        break;
    case 'user':
        $output .= Post::get_for_user_output($connected_user->id, $limit);
        break;
    default:
        $output .= Post::get_limited_output($limit);
        break;
}




echo $output;

//echo $limit." POSTS HERE";
