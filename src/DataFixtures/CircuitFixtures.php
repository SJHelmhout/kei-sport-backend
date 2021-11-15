<?php

namespace App\DataFixtures;

use App\Entity\Circuit;
use App\Entity\Workout;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CircuitFixtures extends Fixture implements DependentFixtureInterface
{
    public const CIRCUITS_REFERENCE = 'circuits';


    public function load(ObjectManager $manager)
    {

        for ($x=1; $x<11; $x++){
            $circuitName = "Circuit " . $x;
            $description = "Beschrijving nummer " . $x;

            $circuit = new Circuit();
            $circuit->setName($circuitName);
            $circuit->setDescription($description);
            /** @var Workout $workout */
            $workout = $this->getReference('workouts');
            $circuit->setWorkout($workout);

            $manager->persist($circuit);
        }

        $this->setReference(self::CIRCUITS_REFERENCE, $circuit);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            WorkoutFixtures::class,
        ];
    }
}
