<?php

declare(strict_types=1);

namespace App\UseCase\Login;

use App\Shared\Form\FormRenderer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class LoginController extends AbstractController
{
    public function __construct(
        private readonly FormRenderer $renderer
    ) {}

    #[Route(path: '/auth/login', name: 'app.auth.login', methods: 'POST')]
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(LoginForm::class)
            ->submit($request->request->all());

        if (!$form->isValid()) {
            return new JsonResponse($this->renderer->__invoke($form));
        }

        /**
         * @var LoginInputDTO $loginDTO
         */
        $loginDTO = $form->getData();

        return new JsonResponse($loginDTO);
    }
}
