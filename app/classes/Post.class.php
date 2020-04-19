<?php

class Post extends DatabaseObject
{

    static protected $table_name = "posts";

    static protected $db_columns = ['id', 'title', 'image', 'user_id', 'created_at'];

    public $id;
    public $title;
    public $image;
    public $user_id;
    public $created_at;

    public function __construct($args = [])
    {
        $this->title = $args['title'] ?? '';
        $this->image = $args['image'] ?? '';
        $this->user_id = $args['user_id'] ?? '';
    }

    public function validate()
    {
        $this->errors = [];

        if (is_blank($this->title)) {
            $this->errors[] = "Title can't be empty";
        } elseif (!has_length($this->title, array('min' => 4, 'max' => 255))) {
            $this->errors[] = "Title must be between 2 and 255 characters.";
        }
        return $this->errors;
    }



    public function create()
    {
        return parent::create();
    }

    protected function update()
    {
        return parent::update();
    }


    public function save()
    {
        return parent::save();
    }

    public function delete()
    {
        global $session;

        if (file_exists('uploads/' . $this->image)) {
            $deleted = unlink('uploads/' . $this->image);
        }
        //delete all likes and comments associated with post
        $deleted_likes_count = Like::delete_where(['post_id LIKE ?'], [$this->id]);
        $deleted_comments_count = Comment::delete_where(['post_id LIKE ?'], [$this->id]);
        //$session->message("message:  deleted likes: ".$deleted_likes_count." deleted comments: ".$deleted_comments_count);
        return parent::delete();
    }

    static public function publish()
    {
        global $errors;
        global $session;
        global $connected_user;
        global $database;
        global $stickers;
        if (is_post_request()) {
            //var_dump($_POST);

            if (isset($_POST['submitBtn'])) {
                $title = $_POST['title'] ?? '';
                $picture_title = 'default.png';
                $stickerChosenForm = $_POST['stickerChosenForm'] ?? '0';
                $n = intval($stickerChosenForm);
                if (!($n >= 0 && $n < count($stickers)))
                    $n = 0;
                $sticker_fn = PROJECT_URL . "assets/stickers/" . $stickers[$n];
                $captured_image_filename = 'pic_' . date("Y-m-d-H-i-s") . '.png';


                if (!(intval($stickerChosenForm) >= 0 && intval($stickerChosenForm) <= count($stickers)))
                    $stickerChosenForm = '0';
                // Validations
                if (!is_blank($_POST['captured_image'])) {
                    $captured_image = $_POST['captured_image'];

                    $dataURL = str_replace('data:image/png;base64,', '', $captured_image);
                    $dataURL = str_replace(' ', '+', $dataURL);
                    $image = base64_decode($dataURL);
                    $dest = imagecreatefromstring($image);
                    $src = imagecreatefrompng($sticker_fn);
                    list($width, $height, $type) = getimagesize($sticker_fn);
                    $old_image = load_image($sticker_fn, $type);
                    $src1 = resize_image(70, 70, $old_image, $width, $height);
                    imagecopy($dest, $src1, 10, 140, 0, 0, 70, 70);
                    $res = imagepng($dest, 'uploads/' . $captured_image_filename);
                    imagedestroy($dest);
                    imagedestroy($src);
                    if ($res != false)
                        $picture_title = $captured_image_filename;
                }
                if (isset($_FILES["uploaded_picture"]) && $_POST['captured_image'] == '') {
                    $uploaded_image_arr = validate_pic($_FILES["uploaded_picture"]);
                    $errors = array_merge($errors, $uploaded_image_arr["errors"]);
                    $uploaded_img = base64_decode($uploaded_image_arr["encode"]);
                    if (is_blank($uploaded_img))
                        return;
                    $dest = imagecreatefromstring($uploaded_img);
                    $src = imagecreatefrompng($sticker_fn);
                    list($width, $height, $type) = getimagesize($sticker_fn);
                    list($img_width, $img_height, $img_type) = getimagesize($uploaded_image_arr["file"]);
                    $old_image = load_image($sticker_fn, $type);
                    $sticker_size = $img_width * 0.3;
                    $src1 = resize_image($sticker_size, $sticker_size, $old_image, $width, $height);
                    $offset = $img_width * 0.03;
                    imagecopy($dest, $src1,  $offset, $img_height - $offset - $sticker_size, 0, 0, $sticker_size, $sticker_size);
                    $res = imagepng($dest, 'uploads/' . $captured_image_filename);
                    imagedestroy($dest);
                    imagedestroy($src);
                    if ($res != false)
                        $picture_title = $captured_image_filename;
                }
                $errors = array_merge($errors, field_errors(1, "Title", $title, 4, 255));
                if ($picture_title == 'default.png') {
                    $errors[] = "Error with the image";
                }
                if (empty($errors)) {
                    $new_post = new Post([
                        "title" => $title,
                        "user_id" => $connected_user->id,
                        "image" => $picture_title
                    ]);

                    if ($new_post->save()) {
                        $inserted_id = $database->lastInsertId();
                        $message = "Post created";
                        $session->message($message);
                        redirect_to(PROJECT_URL . "post/" . $inserted_id);
                    } else {
                        $session->message("Error creating post");
                    }
                }
            }
        }
    }

    public static function get_all_output()
    {
        $output = "";
        $posts = Post::get_all();
        while ($row = $posts->fetch()) {
            //echo "<li>" . $row['name'] . "</li>";
            $output .= "<div class='col-lg-4'>";
            $output .= "<div class='card mb-4 box-shadow'>";
            $output .= "<a href='" . PROJECT_URL . 'post/' . $row["id"] . "'>";
            $output .= "<img class='card-img-top' src='" . PROJECT_URL . "uploads/" . $row["image"] . "' alt='Card image cap'>";
            $output .= "</a>";
            $output .= "<div class='card-body'>";
            $output .= "<p class='card-text'>" . h(get_sub($row["title"], 10)) . "</p>";
            $output .= "</div>";
            $output .= "</div>";
            $output .= "</div>";
        }
        return $output;
    }



    public static function get_limited_output($limit = 0)
    {
        $output = "";
        $posts = Post::get_limited($limit);
        //var_dump($posts->rowCount());
        if($posts->rowCount() == 0)
        {
            $output .= "<div class='col text-center'><p class='text-dark'>No posts yet.</p></div>";
        }
        while ($row = $posts->fetch()) {
            //echo "<li>" . $row['name'] . "</li>";
            $output .= "<div class='col-lg-4'>";
            $output .= "<div class='card mb-4 box-shadow'>";
            $output .= "<a href='" . PROJECT_URL . 'post/' . $row["id"] . "'>";
            $output .= "<img class='card-img-top' src='" . PROJECT_URL . "uploads/" . $row["image"] . "' alt='Card image cap'>";
            $output .= "</a>";
            $output .= "<div class='card-body'>";
            $output .= "<p class='card-text'>" . h(get_sub($row["title"], 10)) . "</p>";
            $output .= "</div>";
            $output .= "</div>";
            $output .= "</div>";
        }
        return $output;
    }

   

    public static function get_for_user_output($user_id, $limit=0)
    {
        $output = "";
        $posts = Post::get_all_where("user_id", $user_id, $limit);
        if($posts == false)
        {
            $output .= "<div class='col text-center'><p class='text-dark'>No posts yet.</p></div>";
        }
        if ($posts != false) {
            foreach ($posts as $row) {
                //echo "<li>" . $row['name'] . "</li>";
                $output .= "<div class='col-lg-4'>";
                $output .= "<div class='card mb-4 box-shadow'>";
                $output .= "<a href='" . PROJECT_URL . 'post/' . $row["id"] . "'>";
                $output .= "<img class='card-img-top' src='" . PROJECT_URL . "uploads/" . $row["image"] . "' alt='Card image cap'>";
                $output .= "</a>";
                $output .= "<div class='card-body'>";
                $output .= "<p class='card-text'>" . h(get_sub($row["title"], 10)) . "</p>";
                $output .= "</div>";
                $output .= "</div>";
                $output .= "</div>";
            }
        }
        return $output;
    }

  }
