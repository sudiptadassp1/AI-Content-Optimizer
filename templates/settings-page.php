<div class="aico-wrap">
  <div class="aico-settings-card">
    <div class="aico-header">
      <div class="aico-brand">
        <span class="aico-logo">🤖</span>
        <div>
          <h1>AI Content Optimizer</h1>
          <p>Premium settings for API & preferences</p>
        </div>
      </div>
    </div>
    <div class="aico-settings-header">
        <span class="aico-icon">🔑</span>
        <h3>OpenAI API Settings</h2>
    </div>

    <form method="post" action="options.php">
        <?php settings_fields('aico_options_group'); ?>

        <label for="aico_api_key" class="aico-label">OpenAI API Key</label>
        <div class="aico-input-wrapper">
            <input type="password" id="aico_api_key" name="aico_api_key"
                   value="<?php echo esc_attr(get_option('aico_api_key')); ?>"
                   placeholder="Enter your OpenAI API key here">
            <button type="button" class="aico-toggle-visibility" aria-label="Show API Key">👁️</button>
        </div>

        <button type="submit" class="aico-save-btn">💾 Save API Key</button>
        <p class="aico-note">Your key is stored securely in WordPress. We never send it anywhere except OpenAI.</p>
    </form>
  </div>

</div>
