<?php
if (!defined('ABSPATH')) exit;

class AI_Content_Optimizer {

    public function run() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_aico_optimize_content', [$this, 'handle_optimize_content']);
    }

    public function register_settings() {
        register_setting('aico_options_group', 'aico_api_key', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => ''
        ]);
    }

    public function add_admin_menu() {
        add_menu_page(
            __('AI Content Optimizer', 'ai-content-optimizer'),
            __('AI Optimizer', 'ai-content-optimizer'),
            'manage_options',
            'ai-content-optimizer',
            [$this, 'render_admin_page'],
            'dashicons-lightbulb',
            80
        );

        add_submenu_page(
            'ai-content-optimizer',
            __('Settings', 'ai-content-optimizer'),
            __('Settings', 'ai-content-optimizer'),
            'manage_options',
            'ai-content-optimizer-settings',
            [$this, 'render_settings_page']
        );
    }

    public function enqueue_assets($hook) {
        $on_plugin_page = (isset($_GET['page']) && ( $_GET['page'] === 'ai-content-optimizer' || $_GET['page'] === 'ai-content-optimizer-settings'));
        if (!$on_plugin_page) return;

        wp_enqueue_style('aico-admin', AICO_URL . 'assets/css/admin.css', [], AICO_VER);
        wp_enqueue_script('aico-admin', AICO_URL . 'assets/js/admin.js', ['jquery'], AICO_VER, true);

        wp_localize_script('aico-admin', 'AICO', [
            'ajax'   => admin_url('admin-ajax.php'),
            'nonce'  => wp_create_nonce('aico_nonce'),
            'i18n'   => [
                'optimizing' => __('Optimizing your content...', 'ai-content-optimizer'),
                'copied'     => __('Copied to clipboard', 'ai-content-optimizer'),
                'empty'      => __('Please enter some content to optimize.', 'ai-content-optimizer'),
                'error'      => __('Something went wrong.', 'ai-content-optimizer'),
            ]
        ]);
    }

    public function render_settings_page() {
        include AICO_PATH . 'templates/settings-page.php';
    }

    public function render_admin_page() {
        include AICO_PATH . 'templates/admin-page.php';
    }

    public function handle_optimize_content() {
        check_ajax_referer('aico_nonce', 'nonce');

        $content = isset($_POST['content']) ? wp_kses_post(wp_unslash($_POST['content'])) : '';
        if (empty($content)) {
            wp_send_json_error(['message' => 'No content provided.']);
        }

        $api_key = get_option('aico_api_key');
        if (empty($api_key)) {
            wp_send_json_error(['message' => 'No API key set.']);
        }

        // NOTE: Keep functionality — using Chat Completions (newer) while maintaining same endpoint concept
        $body = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an expert SEO editor. Improve clarity, grammar, structure and add light SEO enhancements. Keep meaning unchanged. Return only improved text.'],
                ['role' => 'user', 'content' => "Optimize this for SEO & readability:

" . wp_strip_all_tags($content)]
            ],
            'temperature' => 0.6,
            'max_tokens' => 500
        ];

        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $api_key
            ],
            'body'    => wp_json_encode($body),
            'timeout' => 60
        ]);

        if (is_wp_error($response)) {
            wp_send_json_error(['message' => $response->get_error_message()]);
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);
        $optimized = isset($data['choices'][0]['message']['content']) ? $data['choices'][0]['message']['content'] : '';

        if (!$optimized) {
            $err = isset($data['error']['message']) ? $data['error']['message'] : 'API error.';
            wp_send_json_error(['message' => $err]);
        }

        wp_send_json_success(['optimized' => $optimized]);
    }
}
