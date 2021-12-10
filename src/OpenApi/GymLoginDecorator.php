<?php
// api/src/OpenApi/JwtDecorator.php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;

final class GymLoginDecorator implements OpenApiFactoryInterface
{
    private OpenApiFactoryInterface $decorated;

    public function __construct(
        OpenApiFactoryInterface $decorated
    ) {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['GymToken'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'id' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'refreshToken' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);
        $schemas['GymCredentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'pincode' => [
                    'type' => 'string',
                    'example' => '7901',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'test',
                ],
            ],
        ]);

        $pathItem = new Model\PathItem(
            'JWT Token', "", "", null, null,
            new Model\Operation(
                'postCredentialsItem',
                 ['Token'],
                 [
                    '200' => [
                        'description' => 'Get JWT token, userID and refreshToken',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/GymToken',
                                ],
                            ],
                        ],
                    ],
                ],
                'Get JWT token to login.', "", new Model\ExternalDocumentation(), array(),
                 new Model\RequestBody(
                     'Generate new JWT Token',
                     new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/GymCredentials',
                            ],
                        ],
                    ]),
                ),
            ),
        );
        $openApi->getPaths()->addPath('/gym_login', $pathItem);

        return $openApi;
    }
}