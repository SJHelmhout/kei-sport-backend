<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccess
{
    // src/App/EventListener/AuthenticationSuccessListener.php


    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $data['id'] = $user->getUserIdentifier();
        //TODO: route aanmaken http://keisport.sjoerd/api/users/me
        //Die herkent aan de JWTToken welke user om zijn account vraagt

        $event->setData($data);
    }

}