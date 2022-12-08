<?php

declare(strict_types=1);

namespace App\Security\UseCase\Login;

use App\Shared\Http\ControllerInterface;
use App\Shared\Http\Form\HttpFormErrorRenderer;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class LoginController implements ControllerInterface
{
    private const ROUTE_PATH = '/auth/login';
    public const ROUTE_NAME = 'app.auth.login';

    public function __construct(
        private readonly FormFactoryInterface  $formFactory,
        private readonly HttpFormErrorRenderer $httpFormErrorRenderer,
    ) {}

    #[Route(path: self::ROUTE_PATH, name: self::ROUTE_NAME, methods: Request::METHOD_POST)]
    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(LoginForm::class);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $this->httpFormErrorRenderer->render($form);
        }

        return new Response();
    }
}
