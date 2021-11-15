<?php

namespace App\DataFixtures;

use App\Entity\Circuit;
use App\Entity\CircuitLog;
use App\Entity\ExerciseLog;
use App\Entity\User;
use App\Entity\WorkoutLog;
use DateInterval;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CircuitLogFixtures extends Fixture implements DependentFixtureInterface
{
    public const CIRCUIT_LOGS_REFERENCE = 'circuitLogs';


    /**
     * @inheritDoc
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CircuitFixtures::class,
            WorkoutLogFixtures::class
        ];
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $minutesToAdd = 28;
        for ($x=1; $x<11; $x++){
            $minutesToAdd--;
            $circuitLog = new CircuitLog();
            $startTime = new DateTime();
            $endTime = new DateTime();
//            $endTime->add(new DateInterval("PT{$minutesToAdd}M"));
            $endTime->modify("+{$minutesToAdd} minutes");
            /** @var User $user */
            $user = $this->getReference('users');
            /** @var Circuit $circuit */
            $circuit = $this->getReference('circuits');
            /** @var Collection $workoutLog */
            $workoutLog = $this->getReference('workoutLogs');


            $circuitLog
                ->setStartTime($startTime)
                ->setEndTime($endTime)
                ->setUser($user)
                ->setCircuit($circuit)
                ->setWorkoutLog($workoutLog)
            ;
            $this->setReference(self::CIRCUIT_LOGS_REFERENCE, $circuitLog);

            $manager->persist($circuitLog);
            $manager->flush();
        }

    }
}