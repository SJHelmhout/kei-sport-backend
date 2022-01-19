<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\User;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Security;

class JoinSession
{
    private Security $security;
    private EntityManagerInterface $entityManager;
    private SessionRepository $sessionRepository;

    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
        SessionRepository $sessionRepository
    ){
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->sessionRepository = $sessionRepository;
    }

    public function __invoke(Session $data): Session
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if (in_array($user, $data->getUsers()->toArray())) {
            throw new BadRequestHttpException("This user is already a part of this session");
        }
        $data->addUser($user);
        $this->entityManager->flush();

        return $data;
    }
}