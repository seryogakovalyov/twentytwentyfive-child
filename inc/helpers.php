<?php

declare(strict_types=1);

/**
 * Render an ACF button helper.
 *
 * @param array  $button  Button field data.
 * @param array  $classes Optional CSS classes.
 * @param string $icon    Optional icon markup.
 */
function the_acf_button(array $button, array $classes = [], string $icon = ''): void
{
    if (empty($button['title'])) {
        return;
    }

    $attributes = '';

    if (! empty($button['url'])) {
        $attributes .= ' href="' . esc_url($button['url']) . '"';
    }

    if (! empty($button['target'])) {
        $attributes .= ' target="' . esc_attr($button['target']) . '" rel="noreferrer"';
    }

    if (! empty($classes)) {
        $attributes .= ' class="' . esc_attr(implode(' ', $classes)) . '"';
    }

    $button_markup = sprintf(
        '<a %s>%s %s</a>',
        trim($attributes),
        esc_html($button['title']),
        $icon
    );

    echo wp_kses(
        $button_markup,
        [
            'a' => [
                'href'   => true,
                'target' => true,
                'rel'    => true,
                'class'  => true,
            ],
            'i' => [
                'class' => true,
            ],
        ]
    );
}
