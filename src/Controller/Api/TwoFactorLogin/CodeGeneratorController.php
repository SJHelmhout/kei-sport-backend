<?php

namespace App\Controller\Api\TwoFactorLogin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

class CodeGeneratorController
{
    const UNIQUE_MAX_TRIES = 10;

    protected Security $security;
    protected EntityManagerInterface $entityManager;

    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager
    ){
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws Exception
     */
    public function getRandomCode(): string {
        $code = [];
        $keyspace = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        array_push($code, $keyspace[random_int(0, 25)]);
        array_push($code, random_int(0, 9), random_int(0, 9));
        return implode('', $code);
    }

    /**
     * @throws Exception
     */
    public function getUniqueCode(): string
    {
        for ($i = 0; $i < self::UNIQUE_MAX_TRIES; $i++){
            $code = $this->getRandomCode();
            $codeExists = $this->entityManager
                ->getRepository(User::class)
                ->findBy(["twoFactorCode" => $code])
            ;

            if (!$codeExists){
                return $code;
            }
        }
        throw new Exception("Unable to generate unique code, please try again later");
    }

    /**
     * @throws Exception
     */
    public function __invoke(): JsonResponse {
        $code = $this->getUniqueCode();
        $queryBuilder = $this->entityManager
            ->createQueryBuilder()
            ->update(User::class, "u")
            ->set("u.twoFactorCode", ":code")
            ->where("u = :user")
            ->setParameter("code", $code)
            ->setParameter("user", $this->security->getUser())
        ;
        $queryBuilder->getQuery()->execute();
        return new JsonResponse($code);
    }
}