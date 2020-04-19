<?php

function redirect_to($location)
{
    header("Location: " . $location);
    exit;
}

function is_post_request()
{
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function is_get_request()
{
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

function routes()
{
    global $session;
    $route = "home";
    $args = array();
    $args[0] = "0";
    $args[1] = "0";

    if (isset($_GET['url'])) {
        $url = $_GET['url'];
        //var_dump($url);
        $url = rtrim($url, '/');
        //var_dump($url);
        $url = filter_var($url, FILTER_SANITIZE_URL);
        //var_dump($url);
        $url = explode('/', $url);
        // var_dump($url);

        if (is_array($url)) {
            if (count($url) == 1) {
                $route  = $url[0];
            } else if (count($url) == 2) {
                $route  = $url[0];
                $args[0] = $url[1];
            } else if (count($url) >= 3) {
                $route  = $url[0];
                $args[0] = $url[1];
                $args[1] = $url[2];
            }
        }
    }


    $protected_routes = [
       'profile',
       'publish'
    ];


    if (!$session->is_logged_in()) {
        if (in_array($route, $protected_routes)) {
            $session->message("Sign in first!");
            redirect_to(PROJECT_URL . "signin");
        }
    }


    //echo "route: |" .  $route . "|<br>";
    switch ($route) {
        case 'signup':
        case 'signin':
        case 'reset':
        case 'home':
        case 'profile':
        case 'publish':
        case 'post':
        case 'confirm':
            renderView($route, $args);
            break;
        case 'logout':
            //echo "logout page";
            $session->logout();
            renderView("home", $args);
            break;
        case 'setup':
            redirect_to("config/setup.php");
            break;
        case 'theme':
            $val = intval($args[0]);
            if ($val >= 1 &&  $val <= 13) {
                set_cookie("theme", $val, time() + (86400 * 30));
            }
            redirect_to(PROJECT_URL . "home");
            break;
        case 'posts':
            renderAjax("posts", $args);
            break;
        default:
            //echo "error page";
            //error_404();
            //redirect_to("");
            renderView("error", $args);
            break;
    }
}
