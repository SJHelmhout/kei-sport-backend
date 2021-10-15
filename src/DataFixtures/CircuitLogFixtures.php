<?php

namespace App\DataFixtures;

use App\Entity\Circuit;
use App\Entity\CircuitLog;
use App\Entity\User;
use DateInterval;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CircuitLogFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CircuitFixtures::class,
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

            $circuitLog
                ->setStartTime($startTime)
                ->setEndTime($endTime)
                ->setUser($user)
                ->setCircuit($circuit)
            ;

            $manager->persist($circuitLog);
            $manager->flush();
        }

    }
}