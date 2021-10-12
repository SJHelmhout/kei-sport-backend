<?php

namespace App\Doctrine;

use App\Entity\ExerciseLog;
use App\Interfaces\CurrentUserQueryCollectionExtensionAbstract;

class ExerciseLogCollectionExtension extends CurrentUserQueryCollectionExtensionAbstract {

    protected function getResourceClass(): string
    {
        return ExerciseLog::class;
    }
}
