<?php
/*
 * This file is a part of Relations Messenger Json Web Token Implementation.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/json-web-token
 * @link      https://dev.relmsg.ru/packages/json-web-token
 * @copyright Copyright (c) 2018-2022 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   Apache License 2.0
 * @license   https://legal.relmsg.ru/licenses/json-web-token
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__)
;

$namespace = 'Relations Messenger';
$projectTitle = 'Relations Messenger Json Web Token Implementation';
$projectName = 'json-web-token';
$currentYear = date('Y');

$header = <<<EOF
    This file is a part of {$projectTitle}.
    This package is a part of {$namespace}.

    @link      https://github.com/relmsg/{$projectName}
    @link      https://dev.relmsg.ru/packages/{$projectName}
    @copyright Copyright (c) 2018-{$currentYear} {$namespace}
    @author    Oleg Kozlov <h1karo@relmsg.ru>
    @license   Apache License 2.0
    @license   https://legal.relmsg.ru/licenses/{$projectName}

    For the full copyright and license information, please view the LICENSE
    file that was distributed with this source code.
    EOF;

$config = new PhpCsFixer\Config();
return $config
    ->setRules(
        [
            '@PSR12' => true,
            '@Symfony' => true,
            '@PhpCsFixer' => true,
            '@DoctrineAnnotation' => true,
            '@PHP80Migration:risky' => true,
            '@PHP81Migration' => true,
            'declare_strict_types' => false,
            'ordered_class_elements' => false,
            'no_superfluous_phpdoc_tags' => false,
            'strict_param' => true,
            'array_syntax' => ['syntax' => 'short'],
            'concat_space' => ['spacing' => 'one'],
            'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
            'phpdoc_tag_type' => [
                'tags' => ['inheritDoc' => 'annotation'],
            ],
            'phpdoc_tag_casing' => [
                'tags' => ['inheritDoc'],
            ],
            'phpdoc_to_comment' => [
                'ignored_tags' => ['todo', 'noinspection', 'psalm-suppress'],
            ],
            'header_comment' => [
                'header' => $header,
                'comment_type' => 'comment',
                'location' => 'after_open',
                'separate' => 'bottom',
            ],
        ]
    )
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;
