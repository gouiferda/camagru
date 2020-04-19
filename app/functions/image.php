<?php

function validate_pic($picture_file)
{
    $authorized_extensions = array('jpg', 'jpeg', 'gif', 'png', 'bmp', 'JPG', 'JPEG', 'GIF', 'PNG', 'BMP');
    $max_size = 10 * 1000000; //( in MB , the size in bytes 1000000 = 1 MB)
    $min_size = 10 * 1000; // in kb 1000 = 1 KB
    $min_width = 400;
    $min_height = 400;


    $image_returned = [
        "encode" => null,
        "file" => null,
        "errors" => []
    ];
    if (isset($picture_file)) {
        try {
            //print_r($picture_file);
            if ($picture_file['error'] == 0) {
                if ($image_info = getimagesize($picture_file['tmp_name'])) {
                    $image_width = $image_info[0];
                    $image_height = $image_info[1];
                    $picture_fileinfo = pathinfo($picture_file['name']);
                    $uploaded_extension = $picture_fileinfo['extension'];
                    if (intval($image_width) <= $min_width || intval($image_height) <= $min_height) {
                        $image_returned["errors"][] = "Check if width and height more than: " . $min_width . "px x  " . $min_height . "px";
                        return $image_returned;
                    }
                    if ((intval($picture_file['size']) >= $max_size) || (intval($picture_file['size']) < $min_size)) {
                        $image_returned["errors"][] = "Allowed image size: 10 MB max , 10 KB min";
                        return $image_returned;
                    }
                    if (!in_array($uploaded_extension, $authorized_extensions)) {
                        $image_returned["errors"][] = "Unsupported image extension";
                        return $image_returned;
                    }
                    // $msg_debug = "<br><br>width: " . $image_width . " height: " . $image_height;
                    // $msg_debug .= "<br>extension: " . $uploaded_extension . " is valid: " . in_array($uploaded_extension, $authorized_extensions);
                    // $msg_debug .= "<br>size: " . $picture_file['size'] . " max: " . $max_size;
                    // die($msg_debug);
                    $image_returned["file"] = $picture_file['tmp_name'];
                    $im = file_get_contents($picture_file['tmp_name']);
                    $imdata = base64_encode($im);
                    $image_returned["encode"] = $imdata;
                    //$image_name = "pic_" . date("Y-m-d-H-i-s") . "." . $uploaded_extension;
                    //$image_returned["name"] = $image_name;
                    //move_uploaded_file($picture_file['tmp_name'], "uploads/" . $image_name);

                } else {
                    $image_returned["errors"][] = "Please upload a valid file image";
                    return $image_returned;
                }
            } else {
                // $image_err_msg .= "quelque chose se passait mal avec l'image que vous avez essayé de télécharger.";
                $err  = $picture_file['error'];
                $msg = "";
                switch ($err) {
                    case "1":
                        //$msg = "The uploaded file exceeds the upload_max_filesize";
                        $msg = "The uploaded file exceeds the max of the website";
                        break;
                    case "2":
                        //$msg = " The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                        $msg = "The uploaded file exceeds the max of the website";
                        break;
                    case "3":
                        $msg = "The uploaded file was only partially uploaded";
                        break;
                    case "4":
                        $msg = "No file was uploaded";
                        break;
                    case "6":
                        //$msg = "Missing a temporary folder. Introduced in PHP 5.0.3";
                        $msg = "Error happened (1)";
                        break;
                    case "7":
                        //$msg = "Failed to write file to disk. Introduced in PHP 5.1.0";
                        $msg = "Error happened (2)";
                        break;
                }
                if ($msg != "") {
                    $image_returned["errors"][]  =  $msg;
                }
            }
        } catch (Exception $e) {
            $image_returned["errors"][] = "Please upload a valid file image (2)";
        }
    } else {
        $image_returned["errors"][] = "Image not set error";
    }
    return $image_returned;
}

function load_image($filename, $type)
{
    if ($type == IMAGETYPE_JPEG) {
        $image = imagecreatefromjpeg($filename);
    } elseif ($type == IMAGETYPE_PNG) {
        $image = imagecreatefrompng($filename);
    } elseif ($type == IMAGETYPE_GIF) {
        $image = imagecreatefromgif($filename);
    }
    return $image;
}

function resize_image($new_width, $new_height, $image, $width, $height)
{
    $new_image = imagecreatetruecolor($new_width, $new_height);
    imagealphablending($new_image, false);
    imagesavealpha($new_image, true);
    imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    return $new_image;
}
