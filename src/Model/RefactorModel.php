<?php

declare(strict_types=1);

namespace App\Model;

class RefactorModel
{
    private int $technicalDebt;

    private int $weightedTechnicalDebt;

    private int $featureIncompleteness;

    private int $weightedFeatureIncompleteness;

    private int $teamExpertise;

    private int $weightedTeamExpertise;

    private int $timeConstraints;

    private int $weightedTimeConstraints;

    private int $costConstraints;

    private int $weightedCostConstraints;

    private int $failureRisk;

    private int $weightedFailureRisk;

    public function __construct(
        int $technicalDebt = 1,
        int $weightedTechnicalDebt = 1,
        int $featureIncompleteness = 1,
        int $weightedFeatureIncompleteness = 1,
        int $teamExpertise = 1,
        int $weightedTeamExpertise = 1,
        int $timeConstraints = 1,
        int $weightedTimeConstraints = 1,
        int $costConstraints = 1,
        int $weightedCostConstraints = 1,
        int $failureRisk = 1,
        int $weightedFailureRisk = 1
    )
    {
        $this->technicalDebt = $technicalDebt;
        $this->weightedTechnicalDebt = $weightedTechnicalDebt;
        $this->featureIncompleteness = $featureIncompleteness;
        $this->weightedFeatureIncompleteness = $weightedFeatureIncompleteness;
        $this->teamExpertise = $teamExpertise;
        $this->weightedTeamExpertise = $weightedTeamExpertise;
        $this->timeConstraints = $timeConstraints;
        $this->weightedTimeConstraints = $weightedTimeConstraints;
        $this->costConstraints = $costConstraints;
        $this->weightedCostConstraints = $weightedCostConstraints;
        $this->failureRisk = $failureRisk;
        $this->weightedFailureRisk = $weightedFailureRisk;
    }

    public function getTechnicalDebt(): int
    {
        return $this->technicalDebt;
    }

    public function setTechnicalDebt(int $technicalDebt): void
    {
        $this->technicalDebt = $technicalDebt;
    }

    public function getWeightedTechnicalDebt(): int
    {
        return $this->weightedTechnicalDebt;
    }

    public function setWeightedTechnicalDebt(int $weightedTechnicalDebt): void
    {
        $this->weightedTechnicalDebt = $weightedTechnicalDebt;
    }

    public function getFeatureIncompleteness(): int
    {
        return $this->featureIncompleteness;
    }

    public function setFeatureIncompleteness(int $featureIncompleteness): void
    {
        $this->featureIncompleteness = $featureIncompleteness;
    }

    public function getWeightedFeatureIncompleteness(): int
    {
        return $this->weightedFeatureIncompleteness;
    }

    public function setWeightedFeatureIncompleteness(int $weightedFeatureIncompleteness): void
    {
        $this->weightedFeatureIncompleteness = $weightedFeatureIncompleteness;
    }

    public function getTeamExpertise(): int
    {
        return $this->teamExpertise;
    }

    public function setTeamExpertise(int $teamExpertise): void
    {
        $this->teamExpertise = $teamExpertise;
    }

    public function getWeightedTeamExpertise(): int
    {
        return $this->weightedTeamExpertise;
    }

    public function setWeightedTeamExpertise(int $weightedTeamExpertise): void
    {
        $this->weightedTeamExpertise = $weightedTeamExpertise;
    }

    public function getTimeConstraints(): int
    {
        return $this->timeConstraints;
    }

    public function setTimeConstraints(int $timeConstraints): void
    {
        $this->timeConstraints = $timeConstraints;
    }

    public function getWeightedTimeConstraints(): int
    {
        return $this->weightedTimeConstraints;
    }

    public function setWeightedTimeConstraints(int $weightedTimeConstraints): void
    {
        $this->weightedTimeConstraints = $weightedTimeConstraints;
    }

    public function getCostConstraints(): int
    {
        return $this->costConstraints;
    }

    public function setCostConstraints(int $costConstraints): void
    {
        $this->costConstraints = $costConstraints;
    }

    public function getWeightedCostConstraints(): int
    {
        return $this->weightedCostConstraints;
    }

    public function setWeightedCostConstraints(int $weightedCostConstraints): void
    {
        $this->weightedCostConstraints = $weightedCostConstraints;
    }

    public function getFailureRisk(): int
    {
        return $this->failureRisk;
    }

    public function setFailureRisk(int $failureRisk): void
    {
        $this->failureRisk = $failureRisk;
    }

    public function getWeightedFailureRisk(): int
    {
        return $this->weightedFailureRisk;
    }

    public function setWeightedFailureRisk(int $weightedFailureRisk): void
    {
        $this->weightedFailureRisk = $weightedFailureRisk;
    }
}