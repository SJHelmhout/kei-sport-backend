<?php

namespace App\Doctrine;

use App\Entity\CircuitLog;
use App\Interfaces\CurrentUserQueryCollectionExtensionAbstract;

class CircuitLogCollectionExtension extends CurrentUserQueryCollectionExtensionAbstract {

    protected function getResourceClass(): string
    {
        return CircuitLog::class;
    }
}
