<?php

declare(strict_types=1);

namespace App\UseCase\Register;

use App\Shared\Form\FormRenderer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RegisterController extends AbstractController
{
    #[Route(path: '/auth/register', name: 'app.auth.register', methods: 'POST')]
    public function __invoke(Request $request, FormRenderer $renderer): Response
    {
        $form = $this->createForm(RegisterForm::class)
            ->submit($request->request->all());

        if (!$form->isValid()) {
            return new JsonResponse($renderer($form));
        }

        return new JsonResponse($form->getData());
    }
}
