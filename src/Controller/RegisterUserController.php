<?php

namespace App\Controller;

use App\Entity\User;
use App\Message\UserRegisteredMessage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterUserController
{
    #[Route('/api/register-user', name: 'api_register_user', methods: ['POST'])]
    public function __invoke(
        Request $request,
        MessageBusInterface $bus,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $user = (new User())
            ->setFirstName($data['firstName'] ?? '')
            ->setLastName($data['lastName'] ?? '')
            ->setEmail($data['email'] ?? '')
            ->setPassword($data['password'] ?? '');

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 400);
        }

        $bus->dispatch(new UserRegisteredMessage(
            $user->getFirstName(),
            $user->getLastName(),
            $user->getEmail()
        ));

        return new JsonResponse(['status' => 'user_registered'], 201);
    }
}
