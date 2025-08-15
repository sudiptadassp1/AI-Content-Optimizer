<div class="aico-wrap">
  <div class="aico-header">
    <div class="aico-brand">
      <span class="aico-logo">✨</span>
      <div>
        <h1>AI Content Optimizer</h1>
        <p>Boost SEO and clarity with one click</p>
      </div>
    </div>
    <div class="aico-actions">
      <a class="aico-btn aico-outline" href="<?php echo admin_url('admin.php?page=ai-content-optimizer-settings'); ?>">Settings</a>
    </div>
  </div>

  <div class="aico-grid">
    <div class="aico-card aico-animate">
      <h2>✍️ Your Content</h2>
      <div class="aico-stats"><span>Words: <b id="aico_words">0</b></span><span>Reading time: <b id="aico_read">0</b> min</span></div>
      <textarea id="aico_content" placeholder="Paste or write your content here..."></textarea>
      <div class="aico-toolbar">
        <button id="aico_optimize" class="aico-btn">⚡ Optimize</button>
        <button id="aico_clear" class="aico-btn aico-outline">Clear</button>
      </div>
      <div id="aico_progress" class="aico-progress" hidden>
        <div class="aico-spinner"></div>
        <span class="aico-progress-text">Processing with AI...</span>
      </div>
    </div>

    <div class="aico-card aico-animate">
      <h2>✅ Optimized Result</h2>
      <div id="aico_result" class="aico-result"></div>
      <div class="aico-toolbar">
        <button id="aico_copy" class="aico-btn aico-outline">Copy Result</button>
      </div>
    </div>
  </div>
</div>

<div id="aico_toast" class="aico-toast" hidden></div>
