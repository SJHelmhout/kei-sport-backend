<?php

namespace App\DataFixtures;

use App\Entity\Exercise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Device;

class ExerciseFixtures extends Fixture implements FixtureGroupInterface
{

    public function load(ObjectManager $manager)
        //TODO: Insert Exercises with Devices
    {
        $exerciseNames = array(
            "Crunch",
            "Reverse Crunch",
            "Dumbbell Seated Shoulder Press",
            "Press Ups",
            "Treadmill Run",
            "Stair Climbing",
            "Bench Press",
            "Planking",
            "Rowing",
            "Side Plank"
        );
        foreach ($exerciseNames as $value){
            $exercise = new Exercise();
            $exercise->setName($value);
//            $repsOrDuration = (bool) mt_rand(0, 1);
            if (mt_rand(0, 1)){
                $exercise->setDuration(rand(10, 60));
            } else {
                $exercise->setReps(rand(5, 20));
            }
            $manager->persist($exercise);
        }


        // $product = new Product();
        // $manager->persist($product);


        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['exercises'];
    }
}
