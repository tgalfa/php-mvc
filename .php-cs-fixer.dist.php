<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->name('*.php')
    ->ignoreVCSIgnored(true)
    ->exclude('.git/')
    ->exclude('.vscode/')
    ->exclude('vendor/');

return (new PhpCsFixer\Config())
    // available rulesets and rules: https://github.com/FriendsOfPHP/PHP-CS-Fixer/tree/master/doc
    ->setRules([
        '@PSR12' => true,
    ])
    ->setRiskyAllowed(true)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache')
    ->setIndent("    ")
    ->setLineEnding("\n")
    ->setFinder($finder);
