<?php

namespace App\Controller\Api\Visualisation\Graphs;

use App\Entity\Workout;
use App\Entity\WorkoutLog;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

class RecentWorkoutsChartController
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
        $labels = [];
        $chartData = [];
        $queryBuilder = $this->entityManager
            ->getRepository(WorkoutLog::class)
            ->createQueryBuilder('log')
            ->select("log.startTime", "log.endTime", "workout.name")
            ->where("log.user = :user")
            ->innerJoin(
                Workout::class,
                "workout",
                Join::WITH,
                "workout.id = log.workout"
            )
            ->orderBy("log.startTime", "ASC")
        ;
        $result = $queryBuilder->getQuery()->setParameter("user", $this->security->getUser())->getResult();
        foreach ($result as $value){
            $difference = $value["startTime"]->diff($value["endTime"]);
            $minutes = $difference->h * 60;
            $minutes += $difference->i;
            $dayMonth = $value["endTime"]->format("d") . " - " . $value["endTime"]->format("m");
            array_push($chartData, $minutes);
            array_push($labels, $dayMonth);
        }

        return new JsonResponse([$labels, $chartData]);
    }

}