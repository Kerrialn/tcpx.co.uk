<?php

declare(strict_types=1);

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\DebugBundle\DebugBundle::class => [
        'dev' => true,
    ],
    Symfony\Bundle\TwigBundle\TwigBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => [
        'dev' => true,
        'test' => true,
    ],
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\MonologBundle\MonologBundle::class => [
        'all' => true,
    ],
    Symfony\Bundle\MakerBundle\MakerBundle::class => [
        'dev' => true,
    ],
    Symfony\UX\Turbo\TurboBundle::class => [
        'all' => true,
    ],
    Symfony\UX\Typed\TypedBundle::class => [
        'all' => true,
    ],
    Symfony\UX\StimulusBundle\StimulusBundle::class => [
        'all' => true,
    ],
    Stenope\Bundle\StenopeBundle::class => [
        'all' => true,
    ],
];
