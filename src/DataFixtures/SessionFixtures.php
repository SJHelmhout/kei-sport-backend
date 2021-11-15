<?php

namespace App\DataFixtures;

use App\Entity\Session;
use App\Entity\Workout;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SessionFixtures extends Fixture implements DependentFixtureInterface
{
    public const SESSION_REFERENCE = 'sessions';

    /**
     * @inheritDoc
     */
    public function getDependencies(): array
    {
        return [WorkoutFixtures::class];
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $minutesToAdd = 55;
        for ($x=1; $x<11; $x++){
            $minutesToAdd--;
            $startTime = new DateTime();
            $endTime = new DateTime();
//            $endTime->add(new \DateInterval("PT{$minutesToAdd}i"));
            $endTime->modify("+{$minutesToAdd} minutes");
            /** @var Workout $workout */
            $workout = $this->getReference('workouts');

            $session = new Session();
            $session
                ->setStartTime($startTime)
                ->setEndTime($endTime)
                ->setWorkout($workout)
                ->setIsActive(false)
            ;

            $this->setReference(self::SESSION_REFERENCE, $session);
            $manager->persist($session);
            $manager->flush();
        }
    }
}