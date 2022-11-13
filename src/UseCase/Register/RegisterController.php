<?php

declare(strict_types=1);

namespace App\UseCase\Register;

use App\Entity\User;
use App\Entity\User\Contracts\Persistence\UserPersisterInterface;
use App\Shared\Form\FormRenderer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

final class RegisterController extends AbstractController
{
    #[Route(path: '/auth/register', name: 'app.auth.register', methods: 'POST')]
    public function __invoke(
        Request $request,
        FormRenderer $renderer,
        ClockInterface $clock,
        UserPasswordHasherInterface $hasher,
        UserPersisterInterface $userPersister,
    ): Response {
        $form = $this->createForm(RegisterForm::class)
            ->submit($request->request->all());

        if (!$form->isValid()) {
            return new JsonResponse($renderer($form));
        }

        /**
         * @var RegisterDTO $registrationDTO
         */
        $registrationDTO = $form->getData();

        $user = new User(
            $registrationDTO->username,
            $registrationDTO->plainPassword,
            $hasher,
            $clock,
        );

        $userPersister->save($user);

        return new JsonResponse(new RegisterOutput($user));
    }
}
