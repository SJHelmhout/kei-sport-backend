<?php


namespace App\EventSubscriber\Doctrine;


use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserSubscriber implements EventSubscriber
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        if (!$args->getObject() instanceof UserInterface) {
            return;
        }
        /** @var User $user */
        $user = $args->getObject();
        $this->encodePassword($user);
    }

    private function encodePassword(User $user)
    {
        if ($user->getPlainPassword() === null) {
            return;
        }
        $user->setPassword(
            $this->userPasswordHasher->hashPassword($user, $user->getPlainPassword())
        );
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        if (!$args->getObject() instanceof UserInterface) {
            return;
        }
        /** @var User $user */
        $user = $args->getObject();
        $this->encodePassword($user);
    }
}