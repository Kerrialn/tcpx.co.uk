<?php

namespace App\Service\RefactorCalculationService;

use App\Model\RefactorModel;
use App\Service\RefactorCalculationService\Contract\RefactorCalculationServiceInterface;

class RefactorCalculationService implements RefactorCalculationServiceInterface
{

    public function calculate(RefactorModel $refactorModel): int
    {
        return (
            $refactorModel->getTechnicalDebt() * $refactorModel->getWeightedTechnicalDebt() +
            $refactorModel->getFeatureIncompleteness() * $refactorModel->getWeightedFeatureIncompleteness() +
            $refactorModel->getTeamExpertise() * $refactorModel->getWeightedTeamExpertise() +
            $refactorModel->getTimeConstraints() * $refactorModel->getWeightedTimeConstraints() +
            $refactorModel->getCostConstraints() * $refactorModel->getWeightedCostConstraints() +
            $refactorModel->getFailureRisk() * $refactorModel->getWeightedFailureRisk()
        );
    }
}