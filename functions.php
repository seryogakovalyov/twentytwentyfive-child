<?php

// Enqueue parent and child theme assets plus Prism for code highlighting.

add_action('wp_enqueue_scripts', function () {
    $theme_version = wp_get_theme()->get('Version');

    wp_enqueue_style(
        'twentytwentyfive-parent-style',
        get_template_directory_uri() . '/style.css',
        [],
        wp_get_theme('twentytwentyfive')->get('Version')
    );

    wp_enqueue_style(
        'twentytwentyfive-child-fonts',
        'https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;600;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'twentytwentyfive-child-style',
        get_stylesheet_uri(),
        ['twentytwentyfive-parent-style', 'twentytwentyfive-child-fonts'],
        $theme_version
    );

    wp_enqueue_style(
        'prism-core',
        'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism-tomorrow.min.css',
        [],
        '1.29.0'
    );

    wp_enqueue_style(
        'prism-line-numbers',
        'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/line-numbers/prism-line-numbers.min.css',
        ['prism-core'],
        '1.29.0'
    );

    wp_enqueue_script(
        'prism-core',
        'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/prism.min.js',
        [],
        '1.29.0',
        true
    );

    wp_enqueue_script(
        'prism-autoloader',
        'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/autoloader/prism-autoloader.min.js',
        ['prism-core'],
        '1.29.0',
        true
    );

    wp_enqueue_script(
        'prism-line-numbers',
        'https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/line-numbers/prism-line-numbers.min.js',
        ['prism-core'],
        '1.29.0',
        true
    );

    wp_enqueue_script(
        'twentytwentyfive-child-main',
        get_stylesheet_directory_uri() . '/assets/js/main.js',
        ['prism-core'],
        $theme_version,
        true
    );
});

add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_style('mytheme-main', get_stylesheet_directory_uri() . '/style.css', [], wp_get_theme()->get('Version'));
});

// Reading time helper shortcode used across query blocks.
add_shortcode('reading_time', function () {
    $post_id = get_the_ID();

    if (! $post_id) {
        return '';
    }
    //error_log($post_id);
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(wp_strip_all_tags($content));
    $minutes = max(1, (int) ceil($word_count / 200));

    return sprintf('<span class="devlog-reading-time">≈ %d мин</span>', $minutes);
});

// Hook: intercept rendering of the core/post-terms block
add_filter('render_block', 'devlog_replace_portfolio_tags', 10, 3);

function devlog_replace_portfolio_tags($block_content, $parsed_block, $instance)
{
    // Check if this is a post-terms block
    if ($parsed_block['blockName'] !== 'core/post-terms') {
        return $block_content;
    }

    // Check if the taxonomy is 'portfolio_tag'
    if (empty($parsed_block['attrs']['term']) || $parsed_block['attrs']['term'] !== 'portfolio_tag') {
        return $block_content;
    }

    // Get the post ID from the Query Loop context
    if (empty($instance->context['postId'])) {
        return $block_content;
    }

    $post_id = $instance->context['postId'];
    $terms = get_the_terms($post_id, 'portfolio_tag');

    if (empty($terms) || is_wp_error($terms)) {
        return '';
    }

    // Build the desired HTML output
    $output = '';
    foreach ($terms as $term) {
        $output .= '<p class="devlog-chip">' . esc_html($term->name) . '</p>';
    }
    $output .= '';

    return $output;
}

add_action('acf/init', 'devlog_acf_blocks_init');
function devlog_acf_blocks_init()
{
    if (!function_exists('acf_register_block_type')) return;

    acf_register_block_type([
        'name'              => 'portfolio-links',
        'title'             => __('Portfolio Links'),
        'description'       => __('GitHub, Live, Badge'),
        'render_template'   => get_stylesheet_directory() . '/blocks/portfolio-links.php',
        'category'          => 'widgets',
        'icon'              => 'admin-links',
        'keywords'          => ['portfolio', 'links', 'github'],
        'mode'              => 'preview',
        'supports'          => [
            'align' => false,
            'jsx'   => true,
        ],
        'example'           => [
            'attributes' => [
                'mode' => 'preview',
                'data'  => [
                    'badge'       => 'Open Source',
                ]
            ]
        ]
    ]);
}

/**
 * Acf button helper.
 *
 * @param array  $button acf button array.
 * @param array  $classes array of button classes.
 * @param string $icon button icon html.
 */
function the_acf_button(array $button, array $classes = [], string $icon = '')
{
    $attributes  = ($button['url']) ? ' href="' . $button['url'] . '"' : '';
    $attributes .= ($button['target']) ? ' target="' . $button['target'] . '" rel="noreferrer"' : '';
    $attributes .= (! empty($classes)) ? ' class="' . implode(' ', $classes) . '"' : '';

    $title = $button['title'];

    $button = sprintf(
        '<a %s>%s %s</a>',
        $attributes,
        $title,
        $icon
    );

    echo wp_kses($button, [
        'a' => [
            'href'   => true,
            'target' => true,
            'rel'    => true,
            'class'  => true,
        ],
        'i' => [
            'class' => true,
        ],
    ]);
}
