<?php

namespace App\DataFixtures;

use App\Entity\CircuitLog;
use App\Entity\User;
use App\Entity\Workout;
use App\Entity\WorkoutLog;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WorkoutLogFixtures extends Fixture implements DependentFixtureInterface
{
    public const WORKOUTLOG_REFERENCE = 'workoutLogs';

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $minutesToAdd = 55;
        for ($x=1; $x<11; $x++){
            $minutesToAdd--;
            $workoutLog = new WorkoutLog();
            $startTime = new DateTime();
            $endTime = new DateTime();
//            $endTime->add(new \DateInterval("PT{$minutesToAdd}M"));
            $endTime->modify("+{$minutesToAdd} minutes");
            /** @var User $user */
            $user = $this->getReference('users');
            /** @var Workout $workout */
            $workout = $this->getReference('workouts');
            $workoutLog
                ->setStartTime($startTime)
                ->setEndTime($endTime)
                ->setUser($user)
                ->setWorkout($workout)
            ;
            $this->setReference(self::WORKOUTLOG_REFERENCE, $workoutLog);
            $manager->persist($workoutLog);
            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            WorkoutFixtures::class,
        ];
    }
}