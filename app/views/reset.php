<?php

if ($session->is_logged_in())
    redirect_to(PROJECT_URL . "home");

$page_title = "Reset your password";

$body = "<div class='col-sm-10 mx-auto mb-4'>";

$sidebar_title = "Themes";
$sidebar = "";
$sidebar .= get_theme_widget();

switch ($args[0]) {
    case "0":
        $result = "";
        if (isset($_POST["email"])) {
            $email = $_POST["email"];
            $conditions = [];
            $parameters = [];
            $conditions[] = 'email LIKE ?';
            $parameters[] = $email;
            $found_user = User::get_where_conditions($conditions, $parameters);
            if ($found_user != false) {
                $found_user_obj = User::instantiate($found_user);
                $found_user_obj->requested_reset = "1";
                $found_user_obj->reset_code = User::gen_reset_code($email);
                if ($found_user_obj->save()) {

                    $mailer->sendTo = $email;
                    $mailer->subject = "Camagru: Reset your password";
                    $message = "<h3>Reset your password</h3>";
                    $message .= "To change the password of your account go to this link:<br>";
                    $message .= '<a href="' . PROJECT_URL . 'reset/' . User::gen_reset_code($email) . '">Link</a>';
                    $mailer->message = $message;
                    $mailer->send();
                    $result = "Email sent";
                    if (ENV_DEPLOY_MODE == 'dev') {
                        $result .= "<br>";
                        $result .= "Debugging:";
                        $result .= "<br>";
                        $result .= '<a href="' . PROJECT_URL . 'reset/' . User::gen_reset_code($email) . '">Link</a>';
                    }
                    $result .= "</br>";
                } else {
                    $result = "Error sending mail";
                }
            } else {
                $result = "Invalid email";
            }
        }
        $body .= "<form action='" . PROJECT_URL . "reset' method='post' name='resetform'>";

        $body .= "<h1 class='h3 mb-3 font-weight-normal'>Reset your password</h1>";

        $body .= errors_messages($errors);
        $body .= session_messages();

        if ($result != "")
            $body .= "<p class='text-primary'>" . $result . "</p>";

        $body .= "<label for='inputEmail' class='sr-only'>Email</label>";
        $body .= "<input type='text' id='inputEmail' class='form-control' placeholder='Email'  name='email' autofocus>";
        $body .= "<div class='m-4'></div>";

        $body .= "<button class='btn btn-lg btn-primary btn-block' type='submit' name='submit'>Send</button>";

        $body .= "</form>";
        break;
    default:
        $result = "";
        if (!is_blank($args[0])) {
            $output = '';
            $conditions = [];
            $parameters = [];
            $conditions[] = 'reset_code LIKE ?';
            $parameters[] = $args[0];
            $conditions[] = 'requested_reset LIKE ?';
            $parameters[] = "1";
            $found_user = User::get_where_conditions($conditions, $parameters);
            if ($found_user != false) {
                if (isset($_POST["newpassword"])) {
                    $newpassword = $_POST["newpassword"];

                    $errors = array_merge($errors, field_errors(0, "Password", $newpassword, 8, 20));
                    if (empty($errors)) {
                        $found_user_obj = User::instantiate($found_user);
                        $found_user_obj->password = $newpassword;
                        $found_user_obj->set_hashed_password($newpassword);
                        $found_user_obj->requested_reset = "0";
                        $found_user_obj->reset_code = "";
                        if ($found_user_obj->save()) {
                            $output = "Password changed";
                            $session->message("Password changed");
                            redirect_to(PROJECT_URL . "signin");
                        } else {
                            $output = "Password hasn't changed";
                        }
                    } else {
                        $output = "Password hasn't changed";
                    }
                }

                $new_password_example = "helloWorld10$$";
                $body .= "<br>";
                $body .= "<button onclick='resetnewpassform.newpassword.value=\"" . $new_password_example . "\";'>Autofill</button>";
                $body .= "<br><br>";

                $body .= "<form action='" . PROJECT_URL . "reset/" . $args[0] . "' method='post' name='resetnewpassform'>";

                $body .= "<h1 class='h3 mb-3 font-weight-normal'>New password</h1>";

                $body .= errors_messages($errors);
                $body .= session_messages();

                if ($output != "")
                    $body .= "<p class='text-primary'>" . $output . "</p>";

                $body .= "<label for='inputPassword' class='sr-only'>New password</label>";
                
                $field_type = (ENV_DEPLOY_MODE == 'dev') ? 'text' : 'password';
                $body .= "<input type='".$field_type."' id='inputPassword' class='form-control' placeholder='New password'  name='newpassword' autofocus>";
                $body .= "<div class='m-4'></div>";

                $body .= "<button class='btn btn-lg btn-primary btn-block' type='submit' name='submit'>Send</button>";

                $body .= "</form>";
            } else {
                $result = "Invalid reset link";
            }
        }

        if ($result != '') {
            $body .= "<p class='text-primary'>" . $result . "</p>";
        }


        break;
}





$body .= "</div>";


$body .= "<br class='mb-4'>";
include_once("template.php");
