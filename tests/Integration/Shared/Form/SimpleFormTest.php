<?php

declare(strict_types=1);

namespace App\Tests\Integration\Shared\Form;

use App\Tests\TestCase\KernelTestCase;
use App\UseCase\Login\LoginForm;
use App\UseCase\Register\RegisterForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;

final class SimpleFormTest extends KernelTestCase
{
    /**
     * @var class-string<AbstractType>[] FORM_CLASSES
     */
    private const FORM_CLASSES = [
        LoginForm::class,
        RegisterForm::class,
    ];

    public function testFormConfigurationAndInstanciation(): void
    {
        $formBuilder = self::inject(FormFactoryInterface::class);

        foreach (self::FORM_CLASSES as $form) {
            $formBuilder->create($form, []);
            self::assertTrue(true);
        }
    }
}
