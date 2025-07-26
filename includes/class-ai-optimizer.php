<?php
if (!defined('ABSPATH')) exit;

class AI_Content_Optimizer {

    public function run() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
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

    // Register setting for API key
    public function register_settings() {
        register_setting('aico_options_group', 'aico_api_key');
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

        $api_key = get_option('aico_api_key');
        if (empty($api_key)) {
            wp_send_json_error(['message' => 'No API key set.']);
        }

        // Call AI API (OpenAI)
        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $api_key
            ],
            'body' => json_encode([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ["role" => "system", "content" => "You are an SEO and content optimization expert."],
                    ["role" => "user", "content" => "Optimize this content:\n\n" . $content]
                ],
                'max_tokens' => 300
            ]),
            'timeout' => 60
        ]);

        if (is_wp_error($response)) {
            wp_send_json_error(['message' => $response->get_error_message()]);
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (isset($data['choices'][0]['text'])) {
            wp_send_json_success(['optimized' => trim($data['choices'][0]['text'])]);
        } else {
            $error = isset($data['error']['message']) ? $data['error']['message'] : 'API error.';
            wp_send_json_error(['message' => $error]);
        }
    }
}
