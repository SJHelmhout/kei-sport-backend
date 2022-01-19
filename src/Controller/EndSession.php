<?php

namespace App\Controller;

use App\Entity\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Workflow\Registry;

class EndSession
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function __invoke(Session $session): Session
    {
        $workflow = $this->registry->get($session);

        if ($session->getStatus() !== Session::STATUS_SESSION_STARTED){
            throw new BadRequestHttpException("This session has not been started or is already finished.");
        }
        $session->setCurrentStatus(Session::STATUS_SESSION_FINISHED);
        return $session;
    }
}