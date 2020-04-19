<?php

class Comment extends DatabaseObject
{
    static protected $table_name = "comments";
    static protected $db_columns = ['id', 'post_id', 'user_id', 'comment', 'created_at'];

    public $id;
    public $post_id;
    public $user_id;
    public $comment;
    public $created_at;

    public function __construct($args = [])
    {
        $this->post_id = $args['post_id'] ?? '';
        $this->user_id = $args['user_id'] ?? '';
        $this->comment = $args['comment'] ?? '';
    }

    public function validate()
    {
        $this->errors = [];

        //check if post exists 
        if (is_blank($this->post_id)) {
            $this->errors[] = "Problem with choosing the post";
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
        return parent::delete();
    }

    static public function comment()
    {
        global $errors;
        global $session;
        global $connected_user;
        // global $database;
        global $mailer;
        if (is_post_request()) {
            if (isset($_POST['submit'])) {
                $comment = $_POST['comment'] ?? '';
                $post_id =  $_POST['post'] ?? '0';
                // Validations
                //check post id
                $post_arr = Post::get_where("id", $post_id);
                $post = Post::instantiate($post_arr);
                if ($post_arr == false) {
                    $errors[] = "Post doesn't exist";
                }
                //check comment
                $errors = array_merge($errors, field_errors(1, "Comment", $comment, 4, 255));
                if (empty($errors)) {
                    $new_comment = new Comment([
                        "comment" => $comment,
                        "user_id" => $connected_user->id,
                        "post_id" => $post_id
                    ]);
                    if ($new_comment->save()) {


                        $session->message("Comment added");
                        //send mail after comment published if preference is activated

                        $user_of_post = User::instantiate(User::get_where("id", $post->user_id));
                        $user_of_comment = User::instantiate(User::get_where("id", $connected_user->id));

                        if ($user_of_post->receive_notifications == "1") {
                            $mailer->sendTo = $user_of_post->email;
                            $mailer->subject = "New comment on your post";
                            $message = "<h4>New comment on your post</h4>";
                            if ($user_of_post->id != $user_of_comment->id) {
                                $message .= "@" . $user_of_comment->username . " has commented on your post: ";
                            } else {
                                $message .= "You have commented on your own post: ";
                            }
                            $message .= "<a href='" . PROJECT_URL . 'post/' . $post->id . "'>Link</a>";




                            $mailer->message = $message;
                            $mailer->send();
                        }
                    } else {
                        $session->message("Error adding comment");
                    }
                }
            }
        }
    }

    public static function get_for_post($post_id)
    {
        $output = "";
        $comments = Comment::get_all_where("post_id", $post_id, 0);
        if ($comments != false) {
            foreach ($comments as $row) {
                $output .= "<div class='media pt-3'>";
                $output .= "<img style='width: 50px;height: 50px;' src='" . PROJECT_URL . "assets/img/avatar.jpg' alt='' class='mr-2 rounded'>";
                $output .= "<p class='media-body pb-3 mb-0 lh-125 border-bottom border-gray'>";
                $user_commented = User::instantiate(User::get_where("id", $row["user_id"]));
                $output .= "<strong class='d-block text-gray-dark'>".h($user_commented->full_name())." @" . h($user_commented->username) . "</strong>";
                $output .= h($row["comment"]);
                $output .= "</p>";
                $output .= "</div>";
            }
        }
        return $output;
    }
}
