<?php

/*

This part should be accessible only to users that are authentified/connected and gen- tly reject all other users that attempt to access it without being successfully logged in.
This page should contain 2 sections:

• A main section containing the preview of the user’s webcam, the list of superposable images 
and a button allowing to capture a picture.
• A side section displaying thumbnails of all previous pictures taken. Your page layout 
should normally look like in Figure V.1.
• Superposable images must be selectable and the button allowing to take the pic- ture
 should be inactive (not clickable) as long as no superposable image has been selected.
• The creation of the final image (so among others the superposing of the two images)
 must be done on the server side, in PHP.
• Because not everyone has a webcam, you should allow the upload of a user image instead of
 capturing one with the webcam.
• The user should be able to delete his edited images, but only his, not other users’ creations.

*/



$page_title = "Publish";
$body = "";

$sidebar_title = "Previous posts";
$sidebar = "There are no posts";

$posts = Post::get_all_where("user_id",$connected_user->id,9);
if ($posts != false)
{
    $sidebar = "";
    $sidebar .= "<ul class='list-group text-center'>";
    foreach ($posts as $row) {
        $sidebar .= "<li class='list-group-item'>";
        $sidebar .= "<a href='" . PROJECT_URL . 'post/' . $row["id"] . "'>";
        $sidebar .= "<img  src='".PROJECT_URL. 'uploads/' . $row["image"] . "' class='rounded img-fluid'>";
        $sidebar .= "</a>";
        $sidebar .= "</li>";
    }
    $sidebar .= "</ul>";
}




$body .= "<div class='row'> <!--main row-->";

$body .= "<div class='col-sm-3'><!--left col-->";



$body .= "<div class='card mb-3'>";
$body .= "<div class='card-header text-center'>";
$body .= "<span style='font-family: LeckerliOne;font-size:20px;'>";
$body .= "Stickers";
$body .= "</span>";
$body .= "</div>";
$body .= "<div class='card-body'>";
// $body .= "<p class='card-text'>";
// $body .= "";
// $body .= "</p>";


$body .= "<ul class='list-group'>";
for ($i = 0; $i < count($stickers); $i++) {
    $body .= "<li class='list-group-item'  style='padding: 0.3rem;'>";
    $body .= "<label>";
    $checked = ($i == 0) ? 'checked' : '';
    $checked_class = ($i == 0) ? ' chosen-sticker' : '';
    $body .= "<input type='radio' name='sticker' ".$checked." id='sticker".$i."' onchange='check_chosen_sticker()'>";
    $body .= "<img id='picsticker".$i."' src='".PROJECT_URL."assets/stickers/".$stickers[$i]."' class='rounded img-fluid".$checked_class."' style='width:100%'>";
    $body .= "</label>";
    $body .= "</li>";
}
$body .= "</ul>";

$body .= "</div>";
$body .= "</div>";




$body .= "</div><!--end left col-->";
$body .= "<div class='col-sm-9'><!--center col-->";

Post::publish();


$body .= errors_messages($errors);
$body .= session_messages();

$body .= "<div class='card mb-3'>";
$body .= "<div class='card-header'>";
$body .= "<span style='font-family: LeckerliOne;font-size:20px;'>";
$body .= "Picture";
$body .= "</span>";

$body .= "<div class='float-right'>";
$body .= "<label><input type='radio' name='postType' id='radioCameraMode' value='camera' onclick='check_chosen_mode(this)'> Camera<br></label>";
$body .= "&nbsp;&nbsp;";
$body .= "<label><input type='radio' name='postType' id='radioUploadMode' value='upload' onclick='check_chosen_mode(this)'> Upload<br></label>";
$body .= "</div>";

$body .= "</div>";
$body .= "<div class='card-body text-center'>";
$body .= "<form class='form' id='publishform' action='" . PROJECT_URL . 'publish' . "' method='post' enctype='multipart/form-data'>";
$body .= "<video id='videoStream' style='display: none;'  crossorigin='anonymous'></video>";
$body .= "<canvas id='canvasCaptureEdited'  class='rounded w-100 h-100'  height='220px'></canvas>";

$body .= "<div class='btn-group' role='group'>";
$body .= "<button class='btn btn-primary btn-sm' id='btn-start' type='button' ><i class='fas fa-camera-retro'></i> Start Camera</button>";
$body .= "<button class='btn btn-secondary btn-sm' id='btn-stop' type='button' ><i class='fas fa-ban'></i> Stop Camera</button>";
$body .= "<button class='btn btn-primary btn-sm' id='btn-capture' type='button'  ><i class='fas fa-camera'></i> Capture</button>";
$body .= "</div>";

//$body .= "<br class='m-4'>";
//$body .= "<br class='m-4'>";
$body .= "<input type='file' placeholder='Picture from local' class='form-control' name='uploaded_picture' id='uploaded_picture_file' onchange='putImage()' autofocus>";
//$body .= "<br class='m-4'>";



$body .= "</div>";

$body .= "<div class='card-footer text-center'>";

$body .= "<div id='final_image' style='display: none;'>";
$body .= "<div class='alert alert-primary'>Final image</div>";
$body .= "<canvas id='canvasCaptureEditedPrev'  class='rounded w-100 h-100'  height='220px'></canvas>";
$body .= "</div>";

$body .= "<canvas id='canvasCapture'  style='display: none;' class='rounded w-100 h-100'  height='220px'></canvas>";
$body .= "<div id='picturePreviewEdited'></div>";
$body .= "<input type='hidden' name='captured_image' id='captured_image'>";



$body .="</div>";
$body .= "</div>";



$body .= "<input type='hidden'  style='display: none;' name='stickerChosenForm' id='stickerChosenForm'>";


$body .= "<input type='text' placeholder='Post title' class='form-control' name='title' id='postTitle' autofocus>";
$body .= "<br class='m-4'>";
$body .= "<button class='btn btn-primary btn-lg btn-block' type='submit' id='publish-btn' name='submitBtn'>Publish post</button>";
$body .= "<br class='m-4'>";

$body .= "</form>";


$body .= "</div> <!--end center col-->";
$body .= "</div> <!--end row-->";


include_once("template.php");
