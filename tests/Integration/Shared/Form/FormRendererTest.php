<?php

declare(strict_types=1);

namespace App\Tests\Integration\Shared\Form;

use App\Shared\Form\FormRenderer;
use App\Tests\Integration\Shared\Form\Fixture\FakeLoginForm;
use App\Tests\TestCase\KernelTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\VarDumper\VarDumper;

/**
 * @group debug
 */
final class FormRendererTest extends KernelTestCase
{
    private FormFactoryInterface $formFactory;

    protected function setUp(): void
    {
        $this->formFactory = self::inject(FormFactoryInterface::class);
    }

    /**
     * @dataProvider provideFormAndExpectedResults
     * @param array<string, ?string> $data
     */
    public function testFormErrorsToArrayRendering(string $expectedJson, FormInterface $form, array $data): void
    {
        $form = clone $form;
        $form->submit($data);

        $rendered = json_encode($this->render($form));
        $expectedJson = $this->sanitizeJson($expectedJson);

        self::assertEquals($expectedJson, $rendered);
    }

    private function sanitizeJson(string $json): string
    {
        return (string) json_encode(json_decode($json, true));
    }

    /**
     * @phpstan-return array<string, string|array<array-key, string|array<array-key, string>>>
     */
    private function render(FormInterface $form): array
    {
        return (new FormRenderer())($form);
    }

    /**
     * @return array<string, array{expected: string, data: array<string, ?string>, form: FormInterface}>
     */
    public function provideFormAndExpectedResults(): array
    {
        $this->setUp();
        $form = $this->formFactory->create(FakeLoginForm::class);
        $usernameMessage = FakeLoginForm::USERNAME_BLANK_MESSAGE;

        return [
            'No errors' => [
                'expected' => <<<JSON
                {
                    "errors": [],
                    "children": {
                        "username": {
                            "errors": []
                        },
                        "password": {
                            "errors": []
                        }
                    }
                }
                JSON,
                'form' => $form,
                'data' => [
                    'username' => 'test',
                    'password' => '',
                ],
            ],
            'Username should not be blank' => [
                'expected' => <<<JSON
                {
                    "errors": [],
                    "children": {
                        "username": {
                            "errors": [
                                "$usernameMessage"
                            ]
                        },
                        "password": {
                            "errors": []
                        }
                    }
                }
                JSON,
                'form' => $form,
                'data' => [
                    'username' => '',
                    'password' => 'passwd',
                ],
            ]
        ];
    }
}
