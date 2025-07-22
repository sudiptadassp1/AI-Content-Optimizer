<?php
if (!defined('ABSPATH')) exit;

class AI_Content_Optimizer {

    public function run() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'load_assets']);
        add_action('wp_ajax_aico_optimize_content', [$this, 'handle_optimize_content']);
    }

    public function add_admin_menu() {
        add_menu_page(
            'AI Content Optimizer',
            'AI Optimizer',
            'manage_options',
            'ai-content-optimizer',
            [$this, 'render_admin_page'],
            'dashicons-lightbulb',
            80
        );
    }

    public function load_assets($hook) {
        if (strpos($hook, 'ai-content-optimizer') === false) return;

        wp_enqueue_style('aico-style', AICO_URL . 'assets/css/style.css');
        wp_enqueue_script('aico-script', AICO_URL . 'assets/js/script.js', ['jquery'], false, true);

        wp_localize_script('aico-script', 'aico_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aico_nonce')
        ]);
    }

    public function render_admin_page() {
        include AICO_PATH . 'templates/admin-page.php';
    }

    public function handle_optimize_content() {
        check_ajax_referer('aico_nonce', 'nonce');

        $content = sanitize_text_field($_POST['content'] ?? '');

        // Here you'd normally call OpenAI or similar API
        $optimized = strtoupper($content); // Dummy transformation

        wp_send_json_success(['optimized' => $optimized]);
    }
}
