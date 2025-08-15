<?php
/*
Plugin Name: AI Content Optimizer
Plugin URI: https://codecanyon.net/
Description: Optimize WordPress content with AI — SEO, readability, and tone improvements right from the editor.
Version: 1.1.0
Author: Sudipta Das
License: GPLv2 or later
Text Domain: ai-content-optimizer
*/

if ( ! defined('ABSPATH') ) exit;

define('AICO_PATH', plugin_dir_path(__FILE__));
define('AICO_URL',  plugin_dir_url(__FILE__));
define('AICO_VER', '1.1.0');

require_once AICO_PATH . 'includes/class-ai-optimizer.php';
require_once AICO_PATH . 'includes/helpers.php';

function aico_run_plugin() {
    $plugin = new AI_Content_Optimizer();
    $plugin->run();
}
add_action('plugins_loaded', 'aico_run_plugin');
