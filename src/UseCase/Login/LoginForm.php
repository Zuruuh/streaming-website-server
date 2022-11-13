<?php

declare(strict_types=1);

namespace App\UseCase\Login;

use App\Shared\Transformer\FormToDTOTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', options: [
                'constraints' => [
                    new NotBlank(message: 'validation.form.login.username.not_blank')
                ]
            ])
            ->add('password', options: [
                'constraints' => [
                    new NotBlank(message: 'validation.form.login.password.not_blank')
                ]
            ])
            ->addModelTransformer(
                new FormToDTOTransformer(
                    LoginDTO::class,
                    ['plain_password' => 'password']
                ))
        ;
    }
}
