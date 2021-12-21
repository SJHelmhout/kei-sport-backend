<?php

namespace App\Controller\Api\TwoFactorLogin;

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

    public function __invoke(): JsonResponse
    {
//        $user = $this->security->getUser();
//        $queryBuilder = $this->entityManager
//            ->getRepository(Session::class)
//            ->createQueryBuilder('o')
//            ->select("o")
//            ->innerJoin('o.users', 'u', 'WITH', 'u.id = :userParameterName')
//            ->setParameter('userParameterName', $user)
//            ->where("o.is_active = 1")
//        ;
//        $sessions = $queryBuilder->getQuery()->getFirstResult();
//        return new JsonResponse($sessions);
        return new JsonResponse("Lol");
    }
}