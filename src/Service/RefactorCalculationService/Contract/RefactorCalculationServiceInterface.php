<?php

namespace App\Service\RefactorCalculationService\Contract;

use App\Model\RefactorModel;

interface RefactorCalculationServiceInterface
{
    public function calculate(RefactorModel $refactorRefactorModel) : int;

}