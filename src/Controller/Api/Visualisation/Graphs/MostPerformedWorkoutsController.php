<?php

namespace App\Controller\Api\Visualisation\Graphs;

use App\Entity\Workout;
use App\Entity\WorkoutLog;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

class MostPerformedWorkoutsController
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
        //TODO: Verstuur ook de workout id mee en bak deze in de chart in.
        $labels = [];
        $chartData = [];
        //select workout_log.workout_id, COUNT(*) from workout_log where workout_log.user_id = 4 GROUP BY workout_log.workout_id;
        $queryBuilder = $this->entityManager
            ->getRepository(WorkoutLog::class)
            ->createQueryBuilder('log')
            ->select(["COUNT(log.workout) as amount", "w.name"])
            ->where("log.user = :user")
            ->innerJoin("log.workout", "w")
            ->groupBy("log.workout")
            ->orderBy("amount", "DESC")
            ->setMaxResults(5)
        ;
        $result = $queryBuilder->getQuery()->setParameter("user", $this->security->getUser())->getResult();
        foreach ($result as $value){
            $labels[] = $value["name"];
            $chartData[] = $value["amount"];
        }
        return new JsonResponse([$labels, $chartData]);
    }


}