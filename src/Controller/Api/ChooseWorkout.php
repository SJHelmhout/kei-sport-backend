<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Workout;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class ChooseWorkout
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        $queryBuilder = $this->entityManager
            ->createQueryBuilder()
            ->update(User::class, "u")
            ->set("u.selectedWorkout", ":workout")
            ->where("u = :user")
            ->setParameter("workout", $data->workoutToAdd)
            ->setParameter("user", $this->security->getUser())
        ;
        $queryBuilder->getQuery()->execute();
        return new JsonResponse($data);
    }

}