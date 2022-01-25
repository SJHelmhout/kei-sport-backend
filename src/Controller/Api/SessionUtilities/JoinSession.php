<?php

namespace App\Controller\Api\SessionUtilities;

use App\Entity\Session;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Security;

class JoinSession
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

    public function __invoke(Session $data): Session
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if (in_array($user, $data->getUsers()->toArray())) {
            throw new BadRequestHttpException("This user is already a part of this session");
        }

        if ($data->getStatus() !== Session::STATUS_SESSION_WAITING_FOR_PARTICIPANTS){
            throw new BadRequestException("This session has already been started or has not been initialised yet");
        }

        $data->addUser($user);
        $this->entityManager->flush();

        return $data;
    }
}