<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'concat_space' => ['spacing' => 'one'],
        'array_syntax' => ['syntax' => 'short'],
        'braces_position' => [
            'allow_single_line_anonymous_functions' => true,
        ],
        'ordered_imports' => true,
        'trailing_comma_in_multiline' => [
            'elements' => (PHP_VERSION_ID >= 80000) ? ['arrays', 'arguments', 'parameters'] : ['arrays', 'arguments'],
        ],
        'declare_strict_types' => true,
        'single_import_per_statement' => false,
        'yoda_style' => true,
        'phpdoc_align' => false,
        'single_trait_insert_per_statement' => false,
        'global_namespace_import' => true,
        'php_unit_mock' => ['target' => 'newest'],
        'php_unit_namespaced' => ['target' => 'newest'],
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'php_unit_set_up_tear_down_visibility' => true,
    ]);
