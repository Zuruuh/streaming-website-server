<?php

declare(strict_types=1);

namespace App\UseCase\Register;

use App\Contracts\User\Query\CheckUserWithUsernameExistsQueryInterface;
use App\Entity\User\PlainPassword;
use App\Entity\User\Username;
use App\Shared\Form\ValueObjectType;
use App\Shared\Transformer\FormToDTOTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

final class RegisterForm extends AbstractType
{
    public function __construct(
        private readonly CheckUserWithUsernameExistsQueryInterface $checkUserWithUsernameExistsQuery,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', ValueObjectType::class, [
                'value_object' => Username::class,
                'instantiator' => fn (?string $value) =>
                    new Username($value, $this->checkUserWithUsernameExistsQuery)
            ])
            ->add('password', ValueObjectType::class, [
                'value_object' => PlainPassword::class
            ])
            ->addModelTransformer(
                new FormToDTOTransformer(
                    RegisterInputDTO::class,
                    ['plain_password' => 'password'],
                )
            )
        ;
    }
}
