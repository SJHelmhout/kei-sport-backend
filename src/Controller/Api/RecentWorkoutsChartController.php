<?php

namespace App\Controller\Api;

use App\Entity\Workout;
use App\Entity\WorkoutLog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

class RecentWorkoutsChartController extends AbstractController
{
    protected EntityManagerInterface $entityManager;
    protected Security $security;
//
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
            ->innerJoin(
                Workout::class,
                "workout",
                Join::WITH,
                "workout.id = log.workout"
            )
            ->orderBy("log.id", "DESC")
            ->setMaxResults(5)
        ;
        $result = $queryBuilder->getQuery()->getResult();
        foreach ($result as $value){
            $difference = $value["startTime"]->diff($value["endTime"]);
            array_push($chartData, $difference->format("%i"));
            array_push($labels, $value["name"]);
        }
        $chartData = array_reverse($chartData);
        $labels = array_reverse($labels);

        return new JsonResponse([$labels, $chartData]);
    }

}