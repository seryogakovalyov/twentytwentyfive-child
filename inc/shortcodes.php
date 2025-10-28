<?php

declare(strict_types=1);

// Reading time helper shortcode used across query blocks.
add_shortcode('reading_time', function (): string {
    $post_id = get_the_ID();

    if (! $post_id) {
        return '';
    }

    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(wp_strip_all_tags($content));
    $minutes = max(1, (int) ceil($word_count / 200));

    return sprintf('<span class="devlog-reading-time">≈ %d min</span>', $minutes);
});
