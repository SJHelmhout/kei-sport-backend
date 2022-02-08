<?php

namespace App\Controller\Api\Visualisation\Graphs;

use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use DateTime;

class TotalWorkoutsController
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
        $today = new DateTime();
        $yesterday = $today->modify("-1 day");
        $lastMonth = $today->modify("-1 month");
        $queryBuilder = $this->entityManager
            ->getRepository(Session::class)
            ->createQueryBuilder('o')
            ->select(["COUNT(o.workout) as amount"])
            ->where("o.status = 'session_finished'")
            ->andWhere()
        ;
        $result = $queryBuilder->getQuery()->getResult();
        return new JsonResponse([$today, $yesterday, $lastMonth]);
    }

}