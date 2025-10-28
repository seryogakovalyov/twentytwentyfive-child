<?php

declare(strict_types=1);

// Hook: intercept rendering of the core/post-terms block.
add_filter('render_block', function ($block_content, array $parsed_block, $instance) {
    if (($parsed_block['blockName'] ?? null) !== 'core/post-terms') {
        return $block_content;
    }

    if (($parsed_block['attrs']['term'] ?? null) !== 'portfolio_tag') {
        return $block_content;
    }

    if (! is_object($instance) || empty($instance->context['postId'])) {
        return $block_content;
    }

    $post_id = (int) $instance->context['postId'];
    $terms = get_the_terms($post_id, 'portfolio_tag');

    if (empty($terms) || is_wp_error($terms)) {
        return '';
    }

    $output = '';

    foreach ($terms as $term) {
        $output .= '<p class="devlog-chip">' . esc_html($term->name) . '</p>';
    }

    return $output;
}, 10, 3);
