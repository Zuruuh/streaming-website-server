<?php

declare(strict_types=1);

namespace App\Security\UseCase\Login;

use App\Security\Entity\User\Contract\Query\FindUserByUsernameQueryInterface;
use App\Security\Entity\User\Password\PlainPassword;
use App\Security\Entity\User\Username\AlreadyTakenUsername;
use App\Shared\Form\ArrayToDTOTransformer;
use App\Shared\Form\ValueObjectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class LoginForm extends AbstractType
{
    public function __construct(
        private readonly FindUserByUsernameQueryInterface $findUserByUsernameQuery,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', ValueObjectType::class, [
                'value_object' => AlreadyTakenUsername::class,
                'instantiator' => fn (?string $username)
                    => AlreadyTakenUsername::fromString($username, $this->findUserByUsernameQuery)
            ])
            ->add('password', ValueObjectType::class, [
                'value_object' => PlainPassword::class,
            ])
            ->addModelTransformer(new ArrayToDTOTransformer(
                LoginInputDTO::class,
                ['plainPassword' => 'password']
            ))
        ;
    }
}
