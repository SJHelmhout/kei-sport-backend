<?php

namespace App\Controller\Api\Visualisation\Graphs;

use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

class CurrentActiveSessionsController
{
    protected EntityManagerInterface $entityManager;
    protected Security $security;

    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security
    ){
        $this->entityManager = $entityManager;
        $this->security = $security;
    }
    public function __invoke(): JsonResponse
    {
        $queryBuilder = $this->entityManager
            ->getRepository(Session::class)
            ->createQueryBuilder('o')
            ->select(["COUNT(o.workout) as amount"])
            ->where("o.isActive = 1")
        ;
        $result = $queryBuilder->getQuery()->getResult();
        return new JsonResponse($result);
    }

}