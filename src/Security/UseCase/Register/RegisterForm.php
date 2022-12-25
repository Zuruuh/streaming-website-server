<?php

declare(strict_types=1);

namespace App\Security\UseCase\Register;

use App\Security\Entity\User\Contract\Query\FindUserByEmailQueryInterface;
use App\Security\Entity\User\Contract\Query\FindUserByUsernameQueryInterface;
use App\Security\Entity\User\Email\UniqueEmail;
use App\Security\Entity\User\Password\PlainPassword;
use App\Security\Entity\User\Username\UniqueUsername;
use App\Shared\Form\ArrayToDTOTransformer;
use App\Shared\Form\ValueObjectType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class RegisterForm extends AbstractType
{
    public function __construct(
        private readonly FindUserByUsernameQueryInterface $findUserByUsernameQuery,
        private readonly FindUserByEmailQueryInterface    $findUserByEmailQuery,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', ValueObjectType::class, [
                'value_object' => UniqueUsername::class,
                'instantiator' => fn (?string $username): UniqueUsername =>
                    UniqueUsername::fromString($username, $this->findUserByUsernameQuery),
            ])
            ->add('email', ValueObjectType::class, [
                'value_object' => UniqueEmail::class,
                'instantiator' => fn (?string $email): UniqueEmail =>
                    UniqueEmail::fromString($email, $this->findUserByEmailQuery),
            ])
            ->add('password', ValueObjectType::class, [
                'value_object' => PlainPassword::class,
            ])
            ->addModelTransformer(new ArrayToDTOTransformer(
                RegisterInputDTO::class,
                ['plainPassword' => 'password']
            ))
        ;
    }
}
