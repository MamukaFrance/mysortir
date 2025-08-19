<?php

namespace App\MessageHandler;

use App\Message\monMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NotificationMessageHandler implements MessageHandlerInterface
{
    public function __invoke(monMessage $message)
    {
        // Ici, tu peux traiter ton message
        // Exemple : log, envoyer un email, enregistrer en base, etc.
        // Pour l'exemple, on va juste afficher dans les logs :
        file_put_contents(__DIR__ . '/../../var/log/notification.log', $message->contenu . PHP_EOL, FILE_APPEND);
    }
}