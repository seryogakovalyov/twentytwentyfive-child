<?php

declare(strict_types=1);

// Front-end and block assets.
add_action('wp_enqueue_scripts', function (): void {
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

// Editor styles.
add_action('enqueue_block_editor_assets', function (): void {
    wp_enqueue_style(
        'twentytwentyfive-child-editor',
        get_stylesheet_directory_uri() . '/style.css',
        [],
        wp_get_theme()->get('Version')
    );
});
