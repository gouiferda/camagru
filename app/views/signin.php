<?php

if ($session->is_logged_in())
    redirect_to(PROJECT_URL . "home");

User::signin();

$page_title = "Signin";
$sidebar_title = "Themes";

$sidebar = "";
$sidebar .= get_theme_widget();

$user_example = [
    "username" => "user1",
    "password" => "helloWorld10$$"
];

//$body = "<div class='col-sm-10 mx-auto mb-4'>";


$body .= "<div class='card'>";
$body .= "<div class='card-header text-center'>";
$body .= "<span class='h5' style='font-family: LeckerliOne;'>";
$body .= "Sign in";
$body .= "</span>";
$body .= "</div>";
$body .= "<div class='card-body'>";

$body .= "<form action='" . PROJECT_URL . "signin' method='post' name='signinform'>";

//$body .= "<h1 class='h3 mb-3 font-weight-normal'>Sign in</h1>";

$body .= errors_messages($errors);
$body .= session_messages();

$body .= "<label for='inputUsername' class='sr-only'>Username</label>";
$body .= "<input type='text' id='inputUsername' class='form-control' placeholder='Username'  name='username' autofocus  autocomplete='off'>";
$body .= "<div class='m-1'></div>";
$body .= "<label for='inputPassword' class='sr-only'>Password</label>";
$field_type = (ENV_DEPLOY_MODE == 'dev') ? 'text' : 'password';
$body .= "<input type='" . $field_type . "' id='inputPassword' class='form-control' placeholder='Password' name='password' autocomplete='off'>";
$body .= "<div class='m-1'></div>";
$body .= "<div class='mb-3 text-center'>";
$body .= "<a href='" . PROJECT_URL . "reset'> Reset your password </a>";
$body .= "</div>";

$body .= "<button class='btn btn-lg btn-primary btn-block' type='submit' name='submit'>Sign in</button>";

$body .= "</form>";

if (ENV_DEPLOY_MODE == 'dev') {
    $body .= "<br>";
    $body .= "<button onclick='signinform.username.value=\"" . $user_example["username"] . "\";signinform.password.value=\"" . $user_example["password"] . "\";'>Autofill</button>";
    $body .= "&nbsp;";
    $body .= "<button onclick='signinform.reset();'>Clear</button>";
    $body .= "<br><br>";
}

$body .= "</div>";



$body .= "</div>";

$body .= "<br class='mb-4'>";

include_once("template.php");
