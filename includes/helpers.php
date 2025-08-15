<?php
if (!defined('ABSPATH')) exit;

function aico_is_current_screen($slug) {
    if (!function_exists('get_current_screen')) return false;
    $screen = get_current_screen();
    return $screen && strpos($screen->id, $slug) !== false;
}
