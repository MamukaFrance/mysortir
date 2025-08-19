<?php

namespace App\Message;

class DelayedTask
{
    public function __construct(
        public string $contenu
    ) {}

}