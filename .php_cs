<?php

date_default_timezone_set('Asia/Shanghai');
$timestamp = date('Y-m-d H:i:s O');

$header = <<<EOF
@author   Fung Wing Kit <wengee@gmail.com>
@version  $timestamp
EOF;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@PhpCsFixer' => true,
        '@PHP71Migration:risky' => true,
        '@PHP73Migration' => true,

        'header_comment' => [
            'commentType' => 'PHPDoc',
            'header' => $header,
            'separate' => 'bottom'
        ],
        'blank_line_after_opening_tag' => false,
        'binary_operator_spaces' => [
            'align_double_arrow' => true,
            'align_equals' => true,
        ],
        'phpdoc_to_comment' => false,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('/vendor/*')
            ->in(__DIR__)
    )
    ->setUsingCache(false);
