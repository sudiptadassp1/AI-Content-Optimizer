<form method="post" action="options.php">
    <?php settings_fields('aico_options_group'); ?>
    <label>OpenAI API Key:</label><br>
    <input type="text" name="aico_api_key" value="<?php echo esc_attr(get_option('aico_api_key')); ?>" style="width:100%">
    <?php submit_button('Save API Key'); ?>
</form>


<div class="wrap">
    <h1>AI Content Optimizer</h1>
    <p>Paste your content below and click Optimize to see AI improvements.</p>

    <textarea id="aico_content" style="width: 100%; height: 200px;"></textarea>
    <br><br>
    <button id="aico_optimize" class="button button-primary">Optimize Content</button>

    <h2>Optimized Result</h2>
    <div id="aico_result" style="background: #fff; padding: 15px; border: 1px solid #ccc;"></div>
</div>
