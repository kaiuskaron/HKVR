<?php

class NewsItem
{
    public $header = '';
    public $body = '';
    public $expires = '';
    public $created = '';
    public $deleted = false;
    public $user_id = 0;
    public $image = '';
    public $author = '';

    public function __construct(array $array = null) {
        if ($array) {
            $this->header = $array['header'];
            $this->body = $array['body'];
            $this->expires = $array['expires'];
            $this->created = Date('now');
            $this->user_id = $_SESSION['user_id'];
        }
    }

    public function excerpt() {
        if (strlen($this->body) < 100) {
            return $this->body;
        } else {
            $new = wordwrap($this->body, 98);
            $new = explode("\n", $new);
            return $new[0] . '...';
        }
    }
}