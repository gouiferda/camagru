<?php



$page_title = "Profile";

$sidebar_title = "Themes";

$sidebar = "";
$sidebar .= get_theme_widget();

$body .= "<div class='row'> <!--main row-->";

$body .= "<div class='col-sm-4'><!--left col-->";

$body .= "<ul class='list-group mb-3' style='overflow: hidden;'>";
$body .= "<li class='list-group-item '>";
$body .= "<div class='text-center'>";
$body .= "<img src='" . PROJECT_URL . "assets/img/avatar.jpg' class='avatar img-circle img-thumbnail' alt='avatar'>";

$body .= "<br><small class='my-0'>Joined on: " . datetimeToFormat($connected_user->created_at, "Y-m-d") . "</small>";

$body .= "</div>";
$body .= "</li>";
$body .= "<li class='list-group-item text-center bg-light'>";
$body .= "<p class='text-sm my-0'>" . h($connected_user->full_name()) . "<br>@".h($connected_user->username)."</p>";
$body .= "</li>";
$body .= "</ul>";
$body .= "<div class='m-2'></div>";


$body .= "<ul class='list-group mb-3' style='overflow: hidden;'>";
$body .= "<li class='list-group-item text-center bg-light'>";
$body .= "<p class='text-sm my-0'>Statistics</p>";
$body .= "</li>";
$body .= "<li class='list-group-item d-flex justify-content-between lh-condensed'>";
$body .= "<div>";
$body .= "<p class='text-sm my-0'>Liked</p>";
//$body .= "<small class='text-muted'>Brief description</small>";
$body .= "</div>";
$body .= "<span class='text-muted'>" . Like::count_where(['user_id LIKE ?'], [$connected_user->id]) . "</span>";
$body .= "</li>";
$body .= "<li class='list-group-item d-flex justify-content-between lh-condensed'>";
$body .= "<div>";
$body .= "<p class='text-sm my-0'>Commented</p>";
//$body .= "<small class='text-muted'>Brief description</small>";
$body .= "</div>";
$body .= "<span class='text-muted'>" . Comment::count_where(['user_id LIKE ?'], [$connected_user->id]) . "</span>";
$body .= "</li>";
$body .= "</ul>";
$body .= "</div><!--end left col-->";


$body .= "<div class='col-sm-8'><!--center col-->";



switch ($args[0]) {
  case '0':
    //$body .= "<h1 class='h3 mb-3 font-weight-normal'>Profile</h1>";

    $body .= errors_messages($errors);
    $body .= session_messages();

    $body .= "<div class='row' id='posts-result'>";

    $body .= Post::get_for_user_output($connected_user->id, 9);

    $body .= "</div>";


    $posts_count = Post::count_all();
    if ($posts_count > 9)
    {
    $body .= "<div class='row'>";
    $body .= "<div class='col-sm-12'>";
    $body .= "<div class='d-flex align-items-center justify-content-center'>";
    $body .= "<button class='h4 page-link'  style='font-family: LeckerliOne;'  onclick=\"showAjaxResult('posts',load_index,'posts-result','user');load_index++;\">Load more</button>";
    $body .= "</div>";
    $body .= "</div>";
    $body .= "</div>";
    }
    $body .= "<div class='m-2'></div>";

    //var_dump($connected_user);
    break;
  case 'edit':
    $page_title = "Edit profile";
    /*

    Once connected, an user should modify his username, mail address or password.

    */

    User::save_profile();


    $body .= "<form class='form' action='" . PROJECT_URL . 'profile/edit' . "' method='post'>";

    $body .= errors_messages($errors);
    $body .= session_messages();

    $body .= "<h4 class='mb-3'>Change your information</h4>";

    $body .= "<div class='row'>";

    $body .= "<div class='col-md-6 mb-3'>";
    $body .= "<label for='firstName'>First name</label>";
    $body .= "<input type='text' class='form-control' id='firstName' value='" . $connected_user->first_name . "' name='firstname'>";
    $body .= "<div class='invalid-feedback'>";
    $body .= "Valid first name is required.";
    $body .= "</div>";
    $body .= "</div>";

    $body .= "<div class='col-md-6 mb-3'>";
    $body .= "<label for='lastName'>Last name</label>";
    $body .= "<input type='text' class='form-control' id='lastName'   name='lastname' value='" . $connected_user->last_name . "' >";
    $body .= "</div>";

    $body .= "</div><!-- end row -->";

    $body .= "<div class='row'><!--  row -->";

    $body .= "<div class='col-md-6 mb-3'>";
    $body .= "<label for='username'>Username</label>";
    $body .= "<div class='input-group'>";
    $body .= "<div class='input-group-prepend'>";
    $body .= "<span class='input-group-text'>@</span>";
    $body .= "</div>";
    $body .= "<input type='text' class='form-control' id='username' name='username' value='" . $connected_user->username . "'>";
    $body .= "</div>";
    $body .= "</div>";

    $body .= "<div class='col-md-6 mb-3'>";
    $body .= "<label for='email'>Email<span class='text-muted'></span></label>";
    $body .= "<input type='text' class='form-control' id='email' name='email' value='" . $connected_user->email . "'>";
    $body .= "</div>";

    $body .= "</div><!-- end row -->";

    $body .= "<div class='m-3'>";
    $body .= "<div class='custom-control custom-checkbox' >";
    $is_checked =  ($connected_user->receive_notifications == "1")  ? 'checked' : '';
    $body .= "<input type='checkbox' class='custom-control-input' id='same-address' " . $is_checked . "  name='receivenotifications' >";
    $body .= "<label class='custom-control-label' for='same-address'>Receive an email when someone comments or likes your pictures</label>";
    $body .= "</div>";
    $body .= "</div>";


    $body .= "<hr class='mb-4'>";
    $body .= "<h4 class='mb-3'>Change your password</h4>";

    $body .= "<div class='mb-3'>";
    $body .= "<label for='currentpassword'>Current password<span class='text-muted'></span></label>";
    $body .= "<input type='text' class='form-control' id='currentpassword' name='currentpassword'>";
    $body .= "<small class='text-muted'>Leave empty if you don't want to change it</small>";
    $body .= "</div>";

    $body .= "<div class='row'><!-- row -->";

    $body .= "<div class='col-md-6 mb-3'>";
    $body .= "<label for='newpassword'>New password<span class='text-muted'></span></label>";
    $body .= "<input type='text' class='form-control' id='newpassword' name='newpassword'>";
    $body .= "</div>";

    $body .= "<div class='col-md-6 mb-3'>";
    $body .= "<label for='repeatnewpassword'>Repeat new password<span class='text-muted'></span></label>";
    $body .= "<input type='text' class='form-control' id='repeatnewpassword'  name='repeatnewpassword'>";
    $body .= "</div>";

    $body .= "</div><!-- end row -->";

    $body .= "<br class='mb-4'>";
    $body .= "<button class='btn btn-primary btn-lg btn-block' type='submit' name='submit'>Save profile</button>";
    $body .= "<br class='m-4'>";
    $body .= "</form>";

    break;
  default:
    redirect_to(PROJECT_URL . 'profile');
    break;
}

$body .= "</div> <!--end center col-->";
$body .= "</div> <!--end row-->";

include_once("template.php");
