<?php

// run me using bin/php-cs-fixer fix $targetPath

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__])
    ->ignoreDotFiles(true)
    ->notName('deploy')
    ->exclude(['upload', 'var', 'vendor'])
    ->notName('adminer.php');

return (new PhpCsFixer\Config())
    ->setUsingCache(true)
    ->setRules(
        [
            '@PSR2' => true,
            'ordered_class_elements' => true,
            'ordered_imports' => true,
            'phpdoc_add_missing_param_annotation' => true,
            'array_syntax' => [
                'syntax' => 'short'
            ],
            'binary_operator_spaces' => ['operators' => ['|' => null]],
            'blank_line_before_statement' => [
                'statements' => ['return']
            ],
            'cast_spaces' => true,
            'concat_space' => [
                'spacing' => 'one'
            ],
            'declare_equal_normalize' => [
                'space' => 'single'
            ],
            'function_typehint_space' => true,
            'single_line_comment_style' => true,
            'include' => true,
            'linebreak_after_opening_tag' => true,
            'lowercase_cast' => true,
            'class_attributes_separation' => true,
            'general_phpdoc_tag_rename' => true,
            'native_function_casing' => true,
            'new_with_braces' => true,
            'no_blank_lines_after_class_opening' => true,
            'no_blank_lines_after_phpdoc' => true,
            'no_empty_comment' => true,
            'no_empty_phpdoc' => true,
            'no_empty_statement' => true,
            'no_extra_blank_lines' => true,
            'no_leading_import_slash' => true,
            'no_leading_namespace_whitespace' => true,
            'no_blank_lines_before_namespace' => true,
            'no_multiline_whitespace_around_double_arrow' => true,
            'multiline_whitespace_before_semicolons' => true,
            'no_short_bool_cast' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_spaces_around_offset' => true,
            'no_superfluous_phpdoc_tags' => true,
            'no_trailing_comma_in_list_call' => true,
            'no_trailing_comma_in_singleline_array' => true,
            'no_unneeded_control_parentheses' => true,
            'no_unused_imports' => true,
            'no_whitespace_before_comma_in_array' => true,
            'no_whitespace_in_blank_line' => true,
            'normalize_index_brace' => true,
            'object_operator_without_whitespace' => true,
            'phpdoc_annotation_without_dot' => true,
            'phpdoc_indent' => true,
            'phpdoc_no_access' => true,
            'phpdoc_no_alias_tag' => true,
            'phpdoc_no_empty_return' => true,
            'phpdoc_no_package' => true,
            'phpdoc_no_useless_inheritdoc' => true,
            'phpdoc_order' => true,
            'phpdoc_return_self_reference' => true,
            'phpdoc_scalar' => true,
            'phpdoc_single_line_var_spacing' => true,
            'phpdoc_trim' => true,
            'phpdoc_types' => true,
            'phpdoc_var_without_name' => false,
            'return_type_declaration' => true,
            'short_scalar_cast' => true,
            'single_quote' => true,
            'space_after_semicolon' => true,
            'standardize_not_equals' => true,
            'ternary_operator_spaces' => true,
            'trim_array_spaces' => true,
            'unary_operator_spaces' => true,
            'whitespace_after_comma_in_array' => true,
        ]
    )->setFinder($finder);
