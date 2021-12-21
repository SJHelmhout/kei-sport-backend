<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;


class FindMySessionDecorator implements OpenApiFactoryInterface
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

        $schemas['FindMySession'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'Session' => [
                    'type' => 'object',
                    'readOnly' => true,
                ],
            ],
        ]);

        $pathItem = new Model\PathItem(
            'Session', "Get the user's planned session", "", new Model\Operation(
            'getSessionItem',
            ['Session'],
            [
                '200' => [
                    'description' => 'Get the users first planned session',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Session',
                            ],
                        ],
                    ],
                ],
            ],
            'Get the users first planned session', "", new Model\ExternalDocumentation(), array(),
            ), null, null, null);

        $openApi->getPaths()->addPath('/api/sessions/find_my_session', $pathItem);
        return $openApi;
    }
}