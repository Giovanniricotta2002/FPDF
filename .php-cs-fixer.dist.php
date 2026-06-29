<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['vendor', 'doc', 'font', 'makefont', 'tutorial']);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
    ])
    ->setRiskyAllowed(false)
    ->setFinder($finder);
