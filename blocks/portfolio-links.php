<?php

/**
 * Block: Portfolio Links
 */
$post_id = $block['context']['postId'] ?? get_the_ID();
if (!$post_id) return;

$badge  = get_field('badge', $post_id);

if (!have_rows('links', $post_id) && !$badge) return;

?>
<?php while (have_rows('links', $post_id)): the_row(); ?>
    <p class="devlog-link">
        <?php if ($link = get_sub_field('link')):
            the_acf_button($link);
        endif; ?>
    </p>
<?php endwhile; ?>

<?php if ($badge): ?>
    <p class="devlog-badge"><?= esc_html($badge) ?></p>
<?php endif; ?>