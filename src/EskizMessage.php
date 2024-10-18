<?php

namespace KurbanovDeveloper\EskizNotificationChannel;

class EskizMessage
{
    public $content;
    public $from;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function from($from)
    {
        $this->from = $from;
        return $this;
    }
}
