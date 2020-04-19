<?php

$page_title = "Post";

if (!isset($args[0])) {

    redirect_to(PROJECT_URL);
}
$post_arr = Post::get_where("id", $args[0]);

if ($post_arr == false) {
    $session->message("Post doesn't exist");
    redirect_to(PROJECT_URL);
}

$post = Post::instantiate($post_arr);


$user_of_post = User::instantiate(User::get_where("id", $post->user_id));


if ($session->is_logged_in()) {
    $conditions = [];
    $parameters = [];
    $conditions[] = 'user_id LIKE ?';
    $parameters[] = $connected_user->id;
    $conditions[] = 'post_id LIKE ?';
    $parameters[] = $post->id;
    $like_check = Like::get_where_conditions($conditions, $parameters);
    switch ($args[1]) {
        case 'like':
            if ($like_check == false) {
                $new_like = new Like([
                    "post_id" => $post->id,
                    "user_id" => $connected_user->id
                ]);
                $new_like->save();
                $session->message("Picture liked");
                $user_of_like = User::instantiate(User::get_where("id", $connected_user->id));
                if ($user_of_post->receive_notifications == "1" && $user_of_post->id != $user_of_like->id) {
                    $mailer->sendTo = $user_of_post->email;
                    $mailer->subject = "Someone liked your post";
                    $message = "<h3>New like</h3>";
                    $message .= "@" . $user_of_like->username . " liked your post: ";
                    $message .= "<a href='" . PROJECT_URL . 'post/' . $post->id . "'>Link</a>";
                    $mailer->message = $message;
                    $mailer->send();
                }
            } else {
                $session->message("Already liked this picture");
            }
            break;
        case 'dislike':
            if ($like_check == false) {
                $session->message("You haven't liked the picture yet");
            } else {
                $like_check_found = Like::instantiate($like_check);
                $like_check_found->delete();
                $session->message("Picture disliked");
            }
            break;
        case 'delete':
            if ($post->user_id == $connected_user->id) {
                $session->message("Picture deleted");
                $post->delete();
                redirect_to(PROJECT_URL . 'profile');
            } else {
                $session->message("You can only delete your pictures");
            }
            break;
        case 'comment':
            Comment::comment();
            break;
    }
    $like_check = Like::get_where_conditions($conditions, $parameters);
}


$sidebar = "";
$sidebar .= get_theme_widget();

$sidebar_title = "Themes";

$body .= errors_messages($errors);
$body .= session_messages();
$body .= "<div class='card mb-3'>";
$body .= "<div class='card-header'>";
$body .= "<span class=''>";
$body .= h($post->title);
$body .= "</span>";
$body .= "<span class='text-muted float-right'>Added by ".h($user_of_post->full_name())." @" . h($user_of_post->username) . " in " . datetimeToFormat($post->created_at, "Y-m-d") . "</span>";
$body .= "</div>";
$body .= "<div class='card-body'>";
$body .= "<img src='" . PROJECT_URL . "uploads/" . $post->image . "' class='rounded w-100 h-100' />";
$body .= "</div>";
$body .= "<div class='card-footer'>";




if ($session->is_logged_in()) {
    if ($like_check == false) {
        $body .= "<a class='btn btn-primary' style='font-family: LeckerliOne;font-size:20px;' href='" . PROJECT_URL . 'post/' . $post->id . "/like'>Like</a>";
    } else {
        $body .= "<a class='btn btn-primary' style='font-family: LeckerliOne;font-size:20px;' href='" . PROJECT_URL . 'post/' . $post->id . "/dislike'>Dislike</a>";
    }
    if ($post->user_id == $connected_user->id) {
        $body .= "<a class='btn btn-danger float-right' style='font-family: LeckerliOne;font-size:20px;' href='" . PROJECT_URL . 'post/' . $post->id . "/delete'>Delete</a>";
    }
}

$like_count = Like::count_where(['post_id LIKE ?'],[$post->id]);
$comments_count = Comment::count_where(['post_id LIKE ?'],[$post->id]);

$body .= "&nbsp;&nbsp;<i class='fas fa-heart'></i>&nbsp;";
$body .= $like_count;
$body .= "&nbsp;&nbsp;<i class='fas fa-comment-alt'></i>&nbsp;";
$body .= $comments_count;


$body .= "</div>";
$body .= "</div>";


$comments_count = Comment::count_where(["post_id LIKE ?"],[$post->id]);

$body .= "<div class='my-3 p-3 rounded box-shadow border border-primary'>";

$body .= "<h5 class='border-bottom pb-2 mb-0' style='font-family: LeckerliOne;font-size:20px;'>Comments</h5>";

if ($session->is_logged_in()) {
    $body .= "<form method='post' action='" . PROJECT_URL . "post/" . $post->id . "/comment'>";
    $body .= "<div class='media text-muted pt-3'>";
    $body .= "<img style='width: 50px;height: 50px;' src='" . PROJECT_URL . "assets/img/avatar.jpg' alt='' class='mr-2 rounded'>";
    $body .= "<textarea id='textarea' rows='3' class='form-control w-100' style='min-height:100px;height:100%;width:100%;border:1px solid gray;' name='comment' ></textarea>";
    $body .= "</div>";
    $body .= "<div class='m-2'></div>";
    $body .= "<input type='hidden' value='" . $post->id . "' name='post' />";
    $body .= "<button type='submit' style='font-family: LeckerliOne;font-size:20px;' name='submit' class='btn btn-primary float-right' >Post</button>";
    $body .= "<br>";
    $body .= "</form>";
    $body .= "<br class='m-4'>";
}
if ($comments_count != "0")
{
$body .= Comment::get_for_post($post->id);
}else{
    $body .= "<br><p class='pb-2 m-2 text-primary text-center' style='font-family: LeckerliOne;font-size:20px;'>There are no comments yet</p>";
}
$body .= "</div>";



include_once("template.php");
