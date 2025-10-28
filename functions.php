<?php

declare(strict_types=1);
if (! defined('ABSPATH')) {
    exit;
}


$includes = [
    'helpers.php',
    'assets.php',
    'shortcodes.php',
    'filters.php',
    'acf.php',
];


foreach ($includes as $file) {
    $path = __DIR__ . '/inc/' . $file;
    
    if (file_exists($path)) {
        require_once $path;
    }
}
