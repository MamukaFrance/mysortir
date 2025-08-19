<?php

namespace App\Service;

use App\Message\DelayedTask;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

class DelayedTaskService
{
    public function __construct(private MessageBusInterface $bus)
    {
    }

    /**
     * Planifie un message différé dans Messenger.
     *
     * @param string $contenu Contenu du message
     * @param int $delayMs Délai en millisecondes (par défaut 1h)
     * @return string
     */
    public function sendDelayedTask(string $contenu = "Hello, ce message s’exécutera après 1h", int $delayMs = 3600000): string
    {
        $this->bus->dispatch(
            new DelayedTask($contenu),
            [new DelayStamp($delayMs)]
        );

        return "Message planifié dans messenger_messages";
    }
}
