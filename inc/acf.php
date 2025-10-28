<?php

declare(strict_types=1);

add_action('acf/init', function (): void {
    if (! function_exists('acf_register_block_type')) {
        return;
    }

    acf_register_block_type([
        'name'            => 'portfolio-links',
        'title'           => __('Portfolio Links', 'twentytwentyfive-child'),
        'description'     => __('GitHub, Live, Badge', 'twentytwentyfive-child'),
        'render_template' => get_stylesheet_directory() . '/blocks/portfolio-links.php',
        'category'        => 'widgets',
        'icon'            => 'admin-links',
        'keywords'        => ['portfolio', 'links', 'github'],
        'mode'            => 'preview',
        'supports'        => [
            'align' => false,
            'jsx'   => true,
        ],
        'example'         => [
            'attributes' => [
                'mode' => 'preview',
                'data' => [
                    'badge' => 'Open Source',
                ],
            ],
        ],
    ]);
});
