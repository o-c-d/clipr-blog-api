<?php

$config = new PhpCsFixer\Config();

$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__ . '/src')
            ->append([__FILE__])
            // ->exclude('Migrations')
    )
;

return $config;
