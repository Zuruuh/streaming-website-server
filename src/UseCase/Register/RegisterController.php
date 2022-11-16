<?php

declare(strict_types=1);

namespace App\UseCase\Register;

use App\Contracts\User\Persistence\UserPersisterInterface;
use App\Entity\User;
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
    public function __construct(
        private readonly FormRenderer $renderer,
        private readonly ClockInterface $clock,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly UserPersisterInterface $userPersister,
    ) {}

    #[Route(path: '/auth/register', name: 'app.auth.register', methods: 'POST')]
    public function __invoke(Request $request): Response {
        $form = $this->createForm(RegisterForm::class)
            ->submit($request->request->all());

        if (!$form->isValid()) {
            return new JsonResponse($this->renderer->__invoke($form));
        }

        /**
         * @var RegisterInputDTO $registrationDTO
         */
        $registrationDTO = $form->getData();

        $user = new User(
            $registrationDTO->username,
            $registrationDTO->plainPassword,
            $this->hasher,
            $this->clock,
        );

        $this->userPersister->save($user);

        return new JsonResponse(new RegisterOutputDTO($user));
    }
}
