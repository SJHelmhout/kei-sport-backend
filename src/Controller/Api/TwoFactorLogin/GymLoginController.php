<?php

namespace App\Controller\Api\TwoFactorLogin;

use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class GymLoginController
{
    private JWTTokenManagerInterface $jwtManager;
    private UserRepository $userRepository;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        UserRepository $userRepository
    )
    {
        $this->jwtManager = $jwtManager;
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request): JsonResponse {
        try {
            $data = json_decode($request->getContent());
            $user = $this->userRepository->loadUserByIdentifier($data->pincode);
            if ($user === null){
                throw new Exception("No matching pincode found, try again.");
            }
            return new JsonResponse([
                'token' => $this->jwtManager->create($user),
                "id" => $user->getId()
            ]);
        } catch (Exception $e) {
            return new JsonResponse($e->getMessage());
        }
    }
}