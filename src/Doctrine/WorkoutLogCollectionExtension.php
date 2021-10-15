<?php

namespace App\Doctrine;

use App\Entity\WorkoutLog;
use App\Interfaces\CurrentUserQueryCollectionExtensionAbstract;

class WorkoutLogCollectionExtension extends CurrentUserQueryCollectionExtensionAbstract {

    protected function getResourceClass(): string
    {
        return WorkoutLog::class;
    }
}
