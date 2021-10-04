<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Device;

class DeviceFixtures extends Fixture implements FixtureGroupInterface
{

    public function load(ObjectManager $manager)
    {
        $testDevices = array(
            "Treadmill",
            "Cross Trainer",
            "Exercise Bike",
            "Indoor Cycle",
            "Rowing Machine",
            "Stair Climber",
            "Bench",
            "Weights",
            "Exercise Ball",
            "Yoga Mat"
        );
        foreach ($testDevices as $value){
            $device = new Device();
            $device->setName($value);
            $manager->persist($device);
        }


        // $product = new Product();
        // $manager->persist($product);


        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['devices'];
    }
}
