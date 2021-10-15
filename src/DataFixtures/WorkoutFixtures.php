<?php

namespace App\DataFixtures;

use App\Entity\Workout;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WorkoutFixtures extends Fixture
{
    public const WORKOUT_REFERENCE = 'workouts';


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

        foreach ($workoutNames as $index=>$value){
            $workout = new Workout();
            $workout->setName($value);
            $workout->setDescription("Desc #" . $index);

            $manager->persist($workout);
        }

        $this->setReference(self::WORKOUT_REFERENCE, $workout);

        $manager->flush();
    }
}
