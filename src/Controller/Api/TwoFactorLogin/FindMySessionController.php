<?php

namespace App\Controller\Api\TwoFactorLogin;

use App\Entity\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

class FindMySessionController
{
    protected EntityManagerInterface $entityManager;
    protected Security $security;

    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security
    ){
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    //TODO: Refactor where clause in DQL statement
    public function __invoke(): JsonResponse
    {
        $user = $this->security->getUser();
        $queryBuilder = $this->entityManager
            ->getRepository(Session::class)
            ->createQueryBuilder('o')
            ->select("o.id")
            ->where("o.status = 'session_created'")
            ->innerJoin('o.users', 'u', 'WITH', 'u.id = :userParameterName')
            ->setParameter('userParameterName', $user)
        ;
        $sessions = $queryBuilder->getQuery()->getResult();
        return new JsonResponse(end($sessions));
    }
}