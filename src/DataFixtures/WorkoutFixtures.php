<?php

namespace App\DataFixtures;

use App\Entity\Circuit;
use App\Entity\Workout;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class WorkoutFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $workoutNames = array(
            "To the Maxx",
            "Focus, Listen, Lift",
            "Do not quit.",
            "Welcome to the grind",
            "Beginner Friendly",
            "Advanced Techniques",
            "Sport hard, play hard",
            "It's a good day to sport hard",
            "The KEI-Challenge",
            "KEI-Goed",
        );
//        $workoutDescriptions = array();
//        for ($x=1; $x<11, $x++;){
//            array_push($workoutDescriptions, "Desc #" . $x);
//        }
        foreach ($workoutNames as $index=>$value){
            $workout = new Workout();
            $workout->setName($value);
            $workout->setDescription("Desc #" . $index);
            $circuits = array();
            for ($y=1; $y<=rand(1,4); $y++){
                array_push($circuits, $manager->find(Circuit::class, rand(11,20)));
            }
            foreach ($circuits as $circuit){
                $workout->addCircuit($circuit);
            }
            $manager->persist($workout);
        }


        // $product = new Product();
        // $manager->persist($product);


        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['workouts'];
    }

    public function getDependencies(): array
    {
        return [
            CircuitFixtures::class,
            ExerciseFixtures::class,
            DeviceFixtures::class
        ];
    }
}
