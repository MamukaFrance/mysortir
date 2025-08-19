<?php

// src/MessageHandler/DelayedTaskHandler.php
namespace App\MessageHandler;

use App\Message\DelayedTask;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DelayedTaskHandler implements MessageHandlerInterface
{
    public function __invoke(DelayedTask $task)
    {
        // Ici tu peux faire ton traitement (exemple : écrire dans un fichier)
        file_put_contents(
            __DIR__ . '/../../var/log/delayed_test.log',
            date('H:i:s') . " - " . $task->contenu . PHP_EOL,
            FILE_APPEND
        );
    }
}
