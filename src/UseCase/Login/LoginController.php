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
    #[Route(path: '/auth/login', name: 'app.auth.login', methods: 'POST')]
    public function __invoke(Request $request, FormRenderer $renderer): Response
    {
        $form = $this->createForm(LoginForm::class)
            ->submit($request->request->all());

        if (!$form->isValid()) {
            return new JsonResponse($renderer($form));
        }

        /**
         * @var LoginDTO $loginDTO
         */
        $loginDTO = $form->getData();
        dump($loginDTO);

        return new JsonResponse($loginDTO);
    }
}
