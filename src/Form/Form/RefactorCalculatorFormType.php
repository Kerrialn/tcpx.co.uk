<?php

declare(strict_types=1);

namespace App\Form\Form;

use App\Model\RefactorModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RefactorCalculatorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod(Request::METHOD_GET)
            ->add('technicalDebt', ChoiceType::class, [
                'choices' => [
                    'low' => 1,
                    'medium' => 2,
                    'high' => 3,
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'help' => 'The extent of code complexity, bugs, and outdated technologies.',
            ])
            ->add('weightedTechnicalDebt', ChoiceType::class, [
                'choices' => [
                    'low_importance' => 1,
                    'medium_importance' => 2,
                    'high_importance' => 3,
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'help' => 'The importance this factor has in your project',
            ])
            ->add('featureIncompleteness', ChoiceType::class, [
                'choices' => [
                    'low' => 1,
                    'medium' => 2,
                    'high' => 3,
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'help' => 'How close the application is to meeting current and future requirements.',
            ])
            ->add('weightedFeatureIncompleteness', ChoiceType::class, [
                'choices' => [
                    'low_importance' => 1,
                    'medium_importance' => 2,
                    'high_importance' => 3,
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'help' => 'The importance this factor has in your project',
            ])
            ->add('teamExpertise', ChoiceType::class, [
                'choices' => [
                    'low' => 1,
                    'medium' => 2,
                    'high' => 3,
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'help' => 'The team\'s familiarity and experience with the existing codebase and the technologies used.',
            ])
            ->add('weightedTeamExpertise', ChoiceType::class, [
                'choices' => [
                    'low_importance' => 1,
                    'medium_importance' => 2,
                    'high_importance' => 3,
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'help' => 'The importance this factor has in your project',
            ])
            ->add('timeConstraints', ChoiceType::class, [
                'choices' => [
                    'low' => 1,
                    'medium' => 2,
                    'high' => 3,
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'help' => 'The available time for either refactoring or developing a new application.',
            ])
            ->add('weightedTimeConstraints', ChoiceType::class, [
                'choices' => [
                    'low_importance' => 1,
                    'medium_importance' => 2,
                    'high_importance' => 3,
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'help' => 'The importance this factor has in your project',
            ])
            ->add('costConstraints', ChoiceType::class, [
                'choices' => [
                    'low' => 1,
                    'medium' => 2,
                    'high' => 3,
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'help' => 'The financial cost associated with a rebuild or refactor.',
            ])
            ->add('weightedCostConstraints', ChoiceType::class, [
                'choices' => [
                    'low_importance' => 1,
                    'medium_importance' => 2,
                    'high_importance' => 3,
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'help' => 'The importance this factor has in your project',
            ])
            ->add('failureRisk', ChoiceType::class, [
                'choices' => [
                    'low' => 1,
                    'medium' => 2,
                    'high' => 3,
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'help' => 'The level of uncertainty or exposure to potential adverse events associated with both refactoring and starting from scratch',
            ])
            ->add('weightedFailureRisk', ChoiceType::class, [
                'choices' => [
                    'low_importance' => 1,
                    'medium_importance' => 2,
                    'high_importance' => 3,
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'help' => 'The importance this factor has in your project',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RefactorModel::class,
        ]);
    }
}
