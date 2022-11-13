<?php

declare(strict_types=1);

namespace App\Shared\Form;

use Symfony\Component\Form\FormInterface;

final class FormRenderer
{
    /**
     * @phpstan-return array<string, string|array<array-key, string|array<array-key, string>>>
     */
    public function __invoke(FormInterface $form): array
    {
        $data = ['errors' => []];
        return $this->renderRecursive($form, $data);
    }

    /**
     * @param array<string, array<string, string>> &$data
     *
     * @phpstan-return array<string, string|array<array-key, string|array<array-key, string>>>
     */
    private function renderRecursive(FormInterface $form, array &$data): array
    {
        foreach ($form->getErrors() as $error) {
            $data['errors'][] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            $data['children'][$child->getName()] = [];
            $this->renderRecursive($child, $data['children'][$child->getName()]);
        }

        return $data;
    }
}
