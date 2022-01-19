<?php

namespace App\Controller;

use App\Entity\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Workflow\Registry;

class InitSession
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function __invoke(Session $session): Session
    {
        $workflow = $this->registry->get($session);
//        $workflow->can($session, Session::STATUS_SESSION_FINISHED);

        if ($session->getStatus() !== Session::STATUS_SESSION_CREATED){
            throw new BadRequestHttpException("This session has not been created yet");
        }
        $session->setCurrentStatus(Session::STATUS_SESSION_WAITING_FOR_PARTICIPANTS);
        return $session;
    }
}