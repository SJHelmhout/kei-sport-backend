<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model;

class ChooseWorkoutDecorator implements OpenApiFactoryInterface
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

        $schemas['AddedWorkout'] = new \ArrayObject([
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
        $schemas['AddingWorkout'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'workoutToAdd' => [
                    'type' => 'integer',
                    'example' => '91',
                ],
            ],
        ]);

        $pathItem = new Model\PathItem(
            'JWT Token', "Add a workout to the currently logged in user", "", null, null,
            new Model\Operation(
                'postWorkoutToAdd',
                ['UserEditing'],
                [
                    '200' => [
                        'description' => 'Workout added.',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/AddedWorkout',
                                ],
                            ],
                        ],
                    ],
                ],
                'Select a workout and bind this to the user.', "", new Model\ExternalDocumentation(), array(),
                new Model\RequestBody(
                    'Add workout to current user',
                    new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/AddingWorkout',
                            ],
                        ],
                    ]),
                ),
            ),
        );
        $openApi->getPaths()->addPath('/api/addWorkout', $pathItem);

        return $openApi;
    }
}