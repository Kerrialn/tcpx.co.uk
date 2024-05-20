<?php

namespace App\Controller;

use App\Form\Form\RefactorCalculatorFormType;
use App\Model\RefactorModel;
use App\Service\RefactorCalculationService\Contract\RefactorCalculationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RefactorCalculatorController extends AbstractController
{


    public function __construct(
        private RefactorCalculationServiceInterface $refactorCalculationService
    )
    {
    }

    #[Route(path: '/refactor/calculator', name: 'refactor_calculator')]
    public function show(Request $request): Response
    {
        $refactorModel = new RefactorModel();
        $refactorCalculatorForm = $this->createForm(RefactorCalculatorFormType::class, $refactorModel);
        $refactorCalculatorForm->handleRequest($request);
        if ($refactorCalculatorForm->isSubmitted() && $refactorCalculatorForm->isValid()) {

            $refactorCalculationScore = $this->refactorCalculationService->calculate($refactorModel);
            $scratchCalculationScore = $this->refactorCalculationService->calculate(new RefactorModel(featureIncompleteness: 3, weightedFeatureIncompleteness: 3, timeConstraints: 3, weightedTimeConstraints: 3, costConstraints: 3, weightedCostConstraints: 3));

            $totalScore = $refactorCalculationScore + $scratchCalculationScore;
            $refactorPercentage = ($refactorCalculationScore / $totalScore) * 100;
            $startFromScratchPercentage = ($scratchCalculationScore / $totalScore) * 100;

            return $this->render('calculator/result.html.twig', [
                'refactorModel' => $refactorModel,
                'shouldRefactor' => $refactorCalculationScore <= $scratchCalculationScore,
                'refactorCalculationScore' => $refactorCalculationScore,
                'scratchCalculationScore' => $scratchCalculationScore,
                'refactorPercentage' => $refactorPercentage,
                'startFromScratchPercentage' => $startFromScratchPercentage
            ]);
        }

        return $this->render('calculator/index.html.twig', [
            'refactorCalculatorForm' => $refactorCalculatorForm
        ]);
    }


}