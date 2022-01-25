<?php

namespace App\Controller\Api\SessionUtilities;

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

    public function __invoke(Session $data): Session
    {
        $workflow = $this->registry->get($data);

        if ($data->getStatus() !== Session::STATUS_SESSION_STARTED){
            throw new BadRequestHttpException("This session has not been started or is already finished.");
        }
        $data->setStatus(Session::STATUS_SESSION_FINISHED);
        return $data;
    }
}