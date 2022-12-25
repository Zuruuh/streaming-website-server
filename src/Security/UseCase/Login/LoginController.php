<?php

declare(strict_types=1);

namespace App\Security\UseCase\Login;

use App\Security\Entity\User\Password\InvalidPasswordException;
use App\Security\Persister\HttpAuthTokenPersister;
use App\Security\Token\Generator\AuthTokenGeneratorInterface;
use App\Security\Token\Persister\AuthTokenPersisterInterface;
use App\Shared\Http\ControllerInterface;
use App\Shared\Http\Form\HttpFormErrorRenderer;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

final readonly class LoginController implements ControllerInterface
{
    private const ROUTE_PATH = '/auth/login';
    public const ROUTE_NAME = 'app.auth.login';

    public function __construct(
        private FormFactoryInterface        $formFactory,
        private HttpFormErrorRenderer       $httpFormErrorRenderer,
        private UserPasswordHasherInterface $userPasswordHasher,
        private AuthTokenGeneratorInterface $authTokenGenerator,
        private AuthTokenPersisterInterface $authTokenPersister,
        private HttpAuthTokenPersister      $httpAuthTokenPersister,
    ) {}

    #[Route(path: self::ROUTE_PATH, name: self::ROUTE_NAME, methods: Request::METHOD_POST)]
    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(LoginForm::class);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $this->httpFormErrorRenderer->render($form);
        }

        /**
         * @var LoginInputDTO $dto
         */
        $dto = $form->getData();
        try {
            $token = $dto->username->user->authenticate(
                $dto->plainPassword,
                $this->userPasswordHasher,
                $this->authTokenGenerator,
                $this->authTokenPersister,
            );
        } catch (InvalidPasswordException $e) {
            $error = new FormError($e->getMessage());
            $form->get('password')->addError($error);

            return $this->httpFormErrorRenderer->render($form);
        }

        return $this->httpAuthTokenPersister->__invoke(
            $token,
            new JsonResponse(
                new LoginOutputDTO($dto->username->user)
            )
        );
    }
}
