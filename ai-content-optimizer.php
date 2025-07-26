<?php
/*
Plugin Name: AI Content Optimizer
Plugin URI: https://codecanyon.net/item/ai-content-optimizer/
Description: Optimize WordPress content with AI - improve SEO, readability, tone, and more.
Version: 1.0.0
Author: Sudipta Das
License: GPLv2 or later
*/

if (!defined('ABSPATH')) exit;

// Define constants
define('AICO_PATH', plugin_dir_path(__FILE__));
define('AICO_URL', plugin_dir_url(__FILE__));

// Includes
require_once AICO_PATH . 'includes/class-ai-optimizer.php';
require_once AICO_PATH . 'includes/helpers.php';

// Init
function aico_run_plugin() {
    $plugin = new AI_Content_Optimizer();
    $plugin->run();
}
aico_run_plugin();
