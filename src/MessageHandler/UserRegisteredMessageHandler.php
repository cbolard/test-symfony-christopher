<?php

namespace App\MessageHandler;

use App\Message\UserRegisteredMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserRegisteredMessageHandler
{
    public function __invoke(UserRegisteredMessage $message): void
    {
        file_put_contents('/tmp/user_registered.log', sprintf(
            "New User: %s %s (%s)\n",
            $message->getFirstName(),
            $message->getLastName(),
            $message->getEmail()
        ), FILE_APPEND);
    }
}
