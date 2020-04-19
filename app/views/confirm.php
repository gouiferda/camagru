<?php

if ($session->is_logged_in()) {
    unset($_SESSION['confirm_email']);
    redirect_to(PROJECT_URL . "home");
}

if ($session->confirm_email == "")
    redirect_to(PROJECT_URL . "home");

//checking current sign up user if already activated his account
if (User::is_account_activated($session->confirm_email)) {
    $session->message("Account already activated");
    unset($_SESSION['confirm_email']);
    redirect_to(PROJECT_URL . "signin");
}

$page_title = "Confirm";

$sidebar_title = "Themes";
$sidebar = "";
$sidebar .= get_theme_widget();
$body = "<div class='col-sm-10 mx-auto mb-4'>";


switch ($args[0]) {
    case "resend":
        $activation_code = User::gen_activation_code($session->confirm_email);
        $mailer->sendTo = $session->confirm_email;
        $mailer->subject = "Camagru: Resending activation link to your account";
        $message = "<h3>Resending activation link</h3>";
        $message .= "To activate your account go to this link: ";
        $message .=  "<a href='" . PROJECT_URL . 'confirm/' . $activation_code . "'>Link</a>";;
        $mailer->message = $message;
        $mailer->send();
        $body .= "<h2>Resending activation link</h2>";
        $body .= "<br>";
        $body .= "<p>";
        $body .= "Another confirmation link has been sent<br>";
        // $body .= $session->confirm_email;
        $body .= "</p>";
        $body .= "<br>";
        break;
    case "0":
        $body .= "<h2>One more step!</h2>";
        $body .= "<br>";
        $body .= "<p>";
        $body .= "Confirm your account by using the link that has been<br>";
        $body .= "sent to your email ";
        $body .= $session->confirm_email;

        if (ENV_DEPLOY_MODE == 'dev') {
            //dubugging
            $body .= "<br>";
            $body .= "<br>";
            $body .= "Or click this link (debugging):";
            $body .= "<br>";
            $body .= '<a href="' . PROJECT_URL . 'confirm/' . User::gen_activation_code($session->confirm_email) . '">Link</a>';
            $body .= "</br>";
            $body .= "</p>";
        }
        $body .= "<br>";
        $body .= "<a class='btn btn-primary btn-lg' href='" . PROJECT_URL . "confirm/resend'>Resend link</a>";
        $body .= "<br>";
        break;
    default:
        //check if param1 is an activation link of an account that has not been activated
        $result = "";
        if (!is_blank($args[0])) {
            $conditions = [];
            $parameters = [];
            $conditions[] = 'activation_code LIKE ?';
            $parameters[] = $args[0];
            $conditions[] = 'is_activated LIKE ?';
            $parameters[] = "0";
            $found_user = User::get_where_conditions($conditions, $parameters);
            // echo "<pre>";
            // print_r($found_user);
            // echo "</pre>";
            if ($found_user != false) {
                $found_user_obj = User::instantiate($found_user);
                $found_user_obj->is_activated = "1";
                $found_user_obj->activation_code = "";
                if ($found_user_obj->save()) {
                    $result = "Account activated";
                    $session->message("Account activated, you can now sign in!");
                    unset($_SESSION['confirm_email']);
                    redirect_to(PROJECT_URL . 'signin');
                } else {
                    $result = "Error activating";
                }
            } else {
                $result = "invalid activation link 1";
            }
        } else {
            $result = "invalid activation link 2";
        }
        $body .= "<p>" . $result . "</p>";
        break;
}


$body .= "</div>";

$body .= "<br class='mb-4'>";

include_once("template.php");
