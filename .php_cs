<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('3dparty')
    ->exclude('Documentation')
    ->exclude('Resources')
    ->exclude('vendor')
    ->exclude('node_modules')
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setRules([
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => true,
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_before_return' => true,
        'braces' => true,
        'cast_spaces' => true,
        'class_definition' => true,
        'class_keyword_remove' => false, // ::class keyword gives us beter support in IDE
        'combine_consecutive_unsets' => true,
        'concat_space' => ['spacing' => 'one'],
        'declare_equal_normalize' => true,
        'declare_strict_types' => false, // Too early to adopt strict types
        'dir_constant' => true,
        'elseif' => true,
        'encoding' => true,
        'ereg_to_preg' => true,
        'full_opening_tag' => true,
        'function_declaration' => true,
        'function_typehint_space' => true,
        'general_phpdoc_annotation_remove' => false, // No use for that
        'hash_to_slash_comment' => true,
        'header_comment' => false, // We don't use common header in all our files
        'heredoc_to_nowdoc' => false, // Not sure about this one
        'include' => true,
        'indentation_type' => true,
        'line_ending' => true,
        'linebreak_after_opening_tag' => true,
        'lowercase_cast' => true,
        'lowercase_constants' => true,
        'lowercase_keywords' => true,
        'mb_str_functions' => false, // Cannot use that or it would break \Ecodev\Newsletter\Tools::decrypt()
        'method_argument_space' => true,
        'method_separation' => true,
        'modernize_types_casting' => true,
        'native_function_casing' => true,
        'new_with_braces' => true,
        'no_alias_functions' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_blank_lines_before_namespace' => false, // we want 1 blank line before namespace
        'no_closing_tag' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_consecutive_blank_lines' => ['break', 'continue', 'extra', 'return', 'throw', 'use', 'useTrait', 'curly_brace_block', 'parenthesis_brace_block', 'square_brace_block'],
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_mixed_echo_print' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_multiline_whitespace_before_semicolons' => true,
        'no_php4_constructor' => true,
        'no_short_bool_cast' => true,
        'no_short_echo_tag' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_spaces_after_function_name' => true,
        'no_spaces_around_offset' => true,
        'no_spaces_inside_parenthesis' => true,
        'no_trailing_comma_in_list_call' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_trailing_whitespace' => true,
        'no_trailing_whitespace_in_comment' => true,
        'no_unneeded_control_parentheses' => true,
        'no_unreachable_default_argument_value' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'normalize_index_brace' => true,
        'not_operator_with_space' => false, // No we prefer to keep '!' without spaces
        'not_operator_with_successor_space' => false, // idem
        'object_operator_without_whitespace' => true,
        'ordered_class_elements' => false, // We prefer to keep some freedom
        'ordered_imports' => true,
        'php_unit_construct' => true,
        'php_unit_dedicate_assert' => true,
        'php_unit_fqcn_annotation' => true,
        'php_unit_strict' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_align' => false, // Waste of time
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_indent' => true,
        'phpdoc_inline_tag' => true,
        'phpdoc_no_access' => true,
        'phpdoc_no_alias_tag' => true,
        'phpdoc_no_empty_return' => true,
        'phpdoc_no_package' => true,
        'phpdoc_order' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => false, // Nope, annotations are easy to read enough, no need to split them with blank lines
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_summary' => false, // We usually don't generate documentation so punctuation is not important
        'phpdoc_to_comment' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'phpdoc_var_without_name' => true,
        'pow_to_exponentiation' => true,
        'pre_increment' => true,
        'protected_to_private' => true,
        'psr0' => true,
        'psr4' => true,
        'random_api_migration' => true,
        'return_type_declaration' => true,
        'self_accessor' => true,
        'semicolon_after_instruction' => true,
        'short_scalar_cast' => true,
        'silenced_deprecation_error' => true,
        'simplified_null_return' => true,
        'single_blank_line_at_eof' => true,
        'single_blank_line_before_namespace' => true,
        'single_class_element_per_statement' => true,
        'single_import_per_statement' => true,
        'single_line_after_imports' => true,
        'single_quote' => true,
        'space_after_semicolon' => true,
        'standardize_not_equals' => true,
        'strict_comparison' => false, // We sometimes need to compare DateTime objects with non-strict operator
        'strict_param' => true,
        'switch_case_semicolon_to_colon' => true,
        'switch_case_space' => true,
        'ternary_operator_spaces' => true,
        'trailing_comma_in_multiline_array' => true,
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'visibility_required' => true,
        'whitespace_after_comma_in_array' => true,
    ]);
