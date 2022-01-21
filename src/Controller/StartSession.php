<?php

namespace App\Controller;

use App\Entity\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Workflow\Registry;

class StartSession
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function __invoke(Session $data): Session
    {
        $workflow = $this->registry->get($data);
//        $workflow->can($session, Session::STATUS_SESSION_FINISHED);

        if ($data->getStatus() !== Session::STATUS_SESSION_WAITING_FOR_PARTICIPANTS){
            throw new BadRequestHttpException("This session has already been started / has not been activated yet / or is already finished.");
        }
        $data->setStatus(Session::STATUS_SESSION_STARTED);
        return $data;
    }
}