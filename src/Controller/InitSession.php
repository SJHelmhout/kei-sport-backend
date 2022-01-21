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

    public function __invoke(Session $data): Session
    {
        $workflow = $this->registry->get($data);

        if ($data->getStatus() !== Session::STATUS_SESSION_CREATED){
            throw new BadRequestHttpException("This session has not been created yet or has already been initialized");
        }
        $data->setStatus(Session::STATUS_SESSION_WAITING_FOR_PARTICIPANTS);
        return $data;
    }
}