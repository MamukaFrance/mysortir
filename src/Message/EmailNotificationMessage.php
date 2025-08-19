<?php

namespace App\Message;

class EmailNotificationMessage
{
    public function __construct(
        public string $to,
        public string $subject,
        public string $content
    ) {}

}