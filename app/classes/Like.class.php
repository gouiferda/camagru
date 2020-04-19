<?php

class Like extends DatabaseObject
{

    static protected $table_name = "likes";

    static protected $db_columns = ['id', 'post_id', 'user_id', 'created_at'];

    public $id;
    public $post_id;
    public $user_id;
    public $created_at;

    public function __construct($args = [])
    {
        $this->post_id = $args['post_id'] ?? '';
        $this->user_id = $args['user_id'] ?? '';
    }

    public function validate()
    {
        $this->errors = [];
        if (is_blank($this->post_id)) {
            $this->errors[] = "Problem with choosing the post";
        } 
         //test if post exists
        // elseif (!has_length($this->title, array('min' => 4, 'max' => 255))) {
        //     $this->errors[] = "Title must be between 2 and 255 characters.";
        // }
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


}
