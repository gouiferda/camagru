<?php

/*

At the end of the registration process, an user should confirm his account
 via a unique link sent at the email address fullfiled in the registration form.
 
• The user should then be able to connect to your application, 
using his username and his password. He also should be able to tell 
the application to send a password reinitialisation mail, if he forget his password.

*/

if ($session->is_logged_in())
    redirect_to(PROJECT_URL . "home");

User::signup();

$page_title = "Sign up";
$sidebar_title = "Themes";

$sidebar = "";
$sidebar .= get_theme_widget();

$rand_str = generateRandomString(4);
$user_example = [
    "username" => "user" . $rand_str,
    "email" => "user" . $rand_str . "@gmail.com",
    "first_name" => "user",
    "last_name" => $rand_str,
    "password" => "helloWorld10$$",
    "confirmpassword" => "helloWorld10$$"
];

//$body = "<div class='col-sm-10 mx-auto mb-4'>";
//$body .= "strong: ".is_strong_password($user_example["password"]);



$body .= "<div class='card'>";
$body .= "<div class='card-header text-center'>";
$body .= "<span class='h5' style='font-family: LeckerliOne;'>";
$body .= "Sign up";
$body .= "</span>";
$body .= "</div>";
$body .= "<div class='card-body'>";

$body .= "<form method='post' action='" . PROJECT_URL . "signup' name='signupform'>";

//$body .= "<h1 class='h3 mb-3 font-weight-normal'>Sign up</h1>";
$body .= errors_messages($errors);
$body .= session_messages();
$body .= "<label for='inputUsername' class='sr-only'>Username</label>";
$body .= "<input type='text' id='inputUsername' class='form-control' placeholder='Username' name='username' autofocus  autocomplete='off'>";
$body .= "<div class='m-1'></div>";
$body .= "<label for='inputFirstname' class='sr-only'>First name</label>";
$body .= "<input type='text' id='inputFirstname' class='form-control' placeholder='First name' name='firstname' autofocus  autocomplete='off'>";
$body .= "<div class='m-1'></div>";
$body .= "<label for='inputLastname' class='sr-only'>Last name</label>";
$body .= "<input type='text' id='inputLastname' class='form-control' placeholder='Last name' name='lastname' autofocus  autocomplete='off'>";
$body .= "<div class='m-1'></div>";
$body .= "<label for='inputEmail' class='sr-only'>Email</label>";
$body .= "<input type='text' id='inputEmail' class='form-control' placeholder='Email' name='email'  autocomplete='off'>";
$body .= "<div class='m-1'></div>";
$body .= "<label for='inputPassword' class='sr-only'>Password</label>";
$field_type = (ENV_DEPLOY_MODE == 'dev') ? 'text' : 'password';
$body .= "<input type='".$field_type."' id='inputPassword' class='form-control' placeholder='Password' name='password'  autocomplete='off'>";
$body .= "<div class='m-1'></div>";
$body .= "<label for='inputConfirmPassword' class='sr-only'>Confirm Password</label>";
$body .= "<input type='".$field_type."' id='inputConfirmPassword' class='form-control' placeholder='Confirm Password' name='confirmpassword'  autocomplete='off'>";
$body .= "<div class='m-1'></div>";
$body .= "<div class='mb-3 text-center'>";
$body .= "<a href='" . PROJECT_URL . "signin'>Sign In</a>";
$body .= "</div>";
$body .= "<button class='btn btn-lg btn-primary btn-block' type='submit' name='submit'>Sign up</button>";

$body .= "</form>";

if (ENV_DEPLOY_MODE == 'dev') {
    $body .= "<br>";
    $body .= "<button onclick='signupform.username.value=\"" . $user_example["username"] . "\";signupform.email.value=\"" . $user_example["email"] . "\";signupform.password.value=\"" . $user_example["password"] . "\";signupform.confirmpassword.value=\"" . $user_example["confirmpassword"] . "\";signupform.firstname.value=\"" . $user_example["first_name"] . "\";signupform.lastname.value=\"" . $user_example["last_name"] . "\";'>Autofill</button>";
    $body .= "&nbsp;";
    $body .= "<button onclick='signupform.reset();'>Clear</button>";
    $body .= "<br><br>";
}

$body .= "</div>";
$body .= "</div>";
//$body .= "</div>";
$body .= "<br class='mb-4'>";

include_once("template.php");
