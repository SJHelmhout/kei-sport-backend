<?php

namespace App\Controller\Api\Visualisation\Graphs;

use App\Entity\WorkoutLog;
use App\Repository\WorkoutLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

class WorkoutProgressController
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
        $startTimes = [];
        $endTimes = [];
        $user = $this->security->getUser();
        $queryBuilder = $this->entityManager
            ->getRepository(WorkoutLog::class)
            ->createQueryBuilder("o")
            ->select("o.startTime", "o.endTime")
            ->where("o.user = :user")
            ->setParameter("user", $user)
        ;
        $result = $queryBuilder->getQuery()->getResult();
        foreach ($result as $value) {
            array_push($startTimes, $value["startTime"]);
            array_push($endTimes, $value["endTime"]);
        }
        return new JsonResponse([$startTimes, $endTimes]);
    }
}