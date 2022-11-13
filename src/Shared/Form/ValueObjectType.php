<?php

declare(strict_types=1);

namespace App\Shared\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\InvalidArgumentException;

final class ValueObjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (PreSubmitEvent $event) use ($options) {
            $value = $event->getData();

            try {
                $valueObject = new ($options['value_object'])($value);
            } catch (InvalidArgumentException $e) {
                $error = new FormError($e->getMessage());
                $event->getForm()->addError($error);

                return;
            }

            $event->setData($valueObject);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'value_object' => null,
            ])
            ->setAllowedValues('value_object', class_exists(...));
    }

    /**
     * @phpstan-return class-string<AbstractType>
     */
    public function getParent(): string
    {
        return TextType::class;
    }
}
