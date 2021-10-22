<?php

namespace App\DataFixtures;

use App\Entity\Circuit;
use App\Entity\Exercise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Device;

class CircuitFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {

        for ($x=1; $x<11; $x++){
            $circuitName = "Circuit " . $x;
            $description = "Beschrijving nummer " . $x;
//            $exercise = $this->getReference(ExerciseFixtures::EXERCISES_REFERENCE);

            $exercises = array();
            for ($y=1; $y<=rand(1,4); $y++){
                array_push($exercises, $manager->find(Exercise::class, rand(1,10)));
            }
            $circuit = new Circuit();
            $circuit->setName($circuitName);
            $circuit->setDescription($description);
            foreach ($exercises as $exercise){
                $circuit->addExercise($exercise);
            }

            $manager->persist($circuit);
        }

        // $product = new Product();
        // $manager->persist($product);


        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['circuits'];
    }

    public function getDependencies(): array
    {
        return [
            ExerciseFixtures::class,
            DeviceFixtures::class,
        ];
    }
}
