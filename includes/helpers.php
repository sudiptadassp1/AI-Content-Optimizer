<?php
if (!defined('ABSPATH')) exit;

function aico_log($message) {
    if (WP_DEBUG === true) {
        error_log('[AICO] ' . print_r($message, true));
    }
}
