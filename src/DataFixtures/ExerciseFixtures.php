<?php

namespace App\DataFixtures;

use App\Entity\Exercise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Device;

class ExerciseFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public const EXERCISES_REFERENCE = 'exercises';

    public function load(ObjectManager $manager)
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
            /** @var Device $device */
            $device = $this->getReference(DeviceFixtures::DEVICES_REFERENCE);
//            $device = $manager->find(Device::class, rand(1, 10));
            $exercise->setDevice($device);
            $manager->persist($exercise);
        }


        // $product = new Product();
        // $manager->persist($product);

        $this->addReference(self::EXERCISES_REFERENCE, $exercise);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['exercises'];
    }

    public function getDependencies(): array
    {
        return [
            DeviceFixtures::class
        ];
    }
}
