<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Workflow\Registry;

class CreateSession
{
    private Registry $registry;
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(Registry $registry, EntityManagerInterface $entityManager, Security $security)
    {
        $this->registry = $registry;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function __invoke(Session $data): JsonResponse
    {
        $workflow = $this->registry->get($data);

        /** @var User $user */
        $user = $this->security->getUser();

        $this->entityManager->beginTransaction();

        try {
            $session = new Session();
            $session
                ->addUser($user)
                ->setWorkout($user->getSelectedWorkout())
                ->setStatus(Session::STATUS_SESSION_CREATED)
            ;

            $this->entityManager->persist($session);
            $this->entityManager->flush();
            $this->entityManager->commit();

        } catch (Exception $e){
            $this->entityManager->rollback();

            return new JsonResponse(["code" => 500], 500);
        }

        return new JsonResponse(["code" => 201], 201);
    }

}