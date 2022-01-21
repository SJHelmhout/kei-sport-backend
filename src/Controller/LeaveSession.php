<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Security;

class LeaveSession
{
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager
    ){
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function __invoke(Session $session): Session
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if (!in_array($user, $session->getUsers()->toArray())) {
            throw new BadRequestHttpException("This user is not a part of this session");
        }
        $session->removeUser($user);
        $this->entityManager->flush();

        return $session;
    }
}