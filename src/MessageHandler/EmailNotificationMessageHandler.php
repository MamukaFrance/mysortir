<?php

namespace App\MessageHandler;

use App\Message\EmailNotificationMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailNotificationMessageHandler implements MessageHandlerInterface
{
    public function __construct(private MailerInterface $mailer) {}

    public function __invoke(EmailNotificationMessage $message)
    {
        $email = (new Email())
            ->from('noreply@monsite.com')
            ->to($message->to)
            ->subject($message->subject)
            ->text($message->content);

        $this->mailer->send($email);
    }
}