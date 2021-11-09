<?php

namespace App\DataFixtures;

use App\Entity\CircuitLog;
use App\Entity\Exercise;
use App\Entity\ExerciseLog;
use App\Entity\User;
use DateInterval;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExerciseLogFixtures extends Fixture implements DependentFixtureInterface
{
    public const EXERCISE_LOGS_REFERENCE = 'exerciseLogs';

    /**
     * @inheritDoc
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ExerciseFixtures::class,
            CircuitLogFixtures::class
        ];
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $minutesToAdd = 14;
        for ($x=1; $x<11; $x++){
            $minutesToAdd--;
            $exerciseLog = new ExerciseLog();
            $startTime = new DateTime();
            $endTime = new DateTime();
//            $endTime->add(new DateInterval("PT{$minutesToAdd}i"));
            $endTime->modify("+{$minutesToAdd} minutes");
            /** @var User $user */
            $user = $this->getReference('users');
            /** @var Exercise $exercise */
            $exercise = $this->getReference('exercises');
            /** @var CircuitLog $circuitLog */
            $circuitLog = $this->getReference('circuitLogs');

            $exerciseLog
                ->setStartTime($startTime)
                ->setEndTime($endTime)
                ->setUser($user)
                ->setExercise($exercise)
                ->setCircuitLog($circuitLog)
            ;
            $this->setReference(self::EXERCISE_LOGS_REFERENCE, $exerciseLog);


            $manager->persist($exerciseLog);
            $manager->flush();
        }

    }
}