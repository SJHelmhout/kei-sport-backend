<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;


class TwoFactorGeneratorDecorator implements OpenApiFactoryInterface
{
    private OpenApiFactoryInterface $decorated;

    public function __construct(
        OpenApiFactoryInterface $decorated
    ) {
        $this->decorated = $decorated;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['2-FA'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                '2FA-code' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);

        $pathItem = new Model\PathItem(
            '2-FA', "Get the user's 2-FA code", "", new Model\Operation(
            'getCredentialsItem',
            ['2-FA'],
            [
                '200' => [
                    'description' => 'Get the users 2-FA code',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/2-FA',
                            ],
                        ],
                    ],
                ],
            ],
            'Get 2-FA code to login at gym.', "", new Model\ExternalDocumentation(), array(),
            ), null, null, null);

        $openApi->getPaths()->addPath('/api/generate_2fa', $pathItem);
        return $openApi;
    }
}