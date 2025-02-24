<?php

declare(strict_types=1);

namespace App\Service\RefactorCalculationService\Contract;

use App\Model\RefactorModel;

interface RefactorCalculationServiceInterface
{
    public function calculate(RefactorModel $refactorRefactorModel): int;
}