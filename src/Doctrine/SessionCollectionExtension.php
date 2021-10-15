<?php

namespace App\Doctrine;

use App\Entity\Session;
use App\Interfaces\CurrentUserQueryCollectionExtensionAbstract;

class SessionCollectionExtension extends CurrentUserQueryCollectionExtensionAbstract {

    protected function getResourceClass(): string
    {
        return Session::class;
    }
}
