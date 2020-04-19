<?php

class Mailer
{
    public $sendTo;
    public $sendFrom;
    public $subject;
    public $message;

    public function __construct($args = [])
    {
        $this->sendTo = $args['to'] ?? '';
        $this->sendFrom = $args['from'] ?? '';
        $this->subject = $args['subject'] ?? '';
        $this->message = $args['message'] ?? '';
    }

    public function send()
    {
        $headers = 'From: ' . $this->sendFrom . "\r\n" .
          //  'Reply-To: ' . $this->sendFrom . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        return mail($this->sendTo, $this->subject, $this->message, $headers);
    }
}
