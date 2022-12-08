<?php

declare(strict_types=1);

namespace App\Shared\Http\Form;

use App\Shared\Form\FormErrorRenderer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class HttpFormErrorRenderer
{
    public function __construct(private readonly FormErrorRenderer $formErrorRenderer) {}

    public function render(FormInterface $form): Response
    {
        return new JsonResponse($this->formErrorRenderer->render($form), 400);
    }
}
