<?php

declare(strict_types=1);

namespace App\Tests\Integration\Shared\Form\Fixture;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class FakeLoginForm extends AbstractType
{
    public const USERNAME_BLANK_MESSAGE = 'Username should not be blank!';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', options: [
                'constraints' => [
                    new NotBlank(message: self::USERNAME_BLANK_MESSAGE),
                ],
            ])
            ->add('password')
        ;
    }
}
