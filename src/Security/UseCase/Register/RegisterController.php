<?php

declare(strict_types=1);

namespace App\Security\UseCase\Register;

use App\Security\Entity\User\Contract\Persister\UserPersisterInterface;
use App\Shared\Http\ControllerInterface;
use App\Shared\Http\Form\HttpFormErrorRenderer;
use App\Shared\Http\HttpJsonSerializer;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

final readonly class RegisterController implements ControllerInterface
{
    private const ROUTE_PATH = '/auth/register';
    public const ROUTE_NAME = 'app.auth.register';

    public function __construct(
        private FormFactoryInterface        $formFactory,
        private HttpFormErrorRenderer       $httpFormErrorRenderer,
        private HttpJsonSerializer          $httpJsonSerializer,
        private UserPersisterInterface      $userPersister,
        private UserPasswordHasherInterface $passwordHasher,
        private ClockInterface              $clock,
    ) {}

    #[Route(path: self::ROUTE_PATH, name: self::ROUTE_NAME, methods: Request::METHOD_POST)]
    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(RegisterForm::class);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $this->httpFormErrorRenderer->render($form);
        }

        /**
         * @var RegisterInputDTO $dto
         */
        $dto = $form->getData();
        $user = $dto->toUser($this->passwordHasher, $this->clock);

        $this->userPersister->save($user);

        return $this->httpJsonSerializer->serialize(new RegisterOutputDTO($user));
    }
}
