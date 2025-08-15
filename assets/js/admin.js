(function($){
  function toast(msg){
    var t = $('#aico_toast');
    if(!t.length) return;
    t.text(msg);
    t.removeAttr('hidden').addClass('show');
    setTimeout(function(){ t.removeClass('show'); }, 1800);
  }

  function recalcStats(){
    var txt = $('#aico_content').val().trim();
    var words = txt ? txt.split(/\s+/).length : 0;
    $('#aico_words').text(words);
    $('#aico_read').text(Math.max(1, Math.ceil(words/200)));
  }

  $(document).on('input', '#aico_content', recalcStats);
  $(document).on('click', '#aico_clear', function(){
    $('#aico_content').val(''); recalcStats();
    $('#aico_result').text('');
  });

  $(document).on('click', '#aico_copy', async function(){
    var text = $('#aico_result').text().trim();
    if(!text){ toast('Nothing to copy'); return; }
    try { await navigator.clipboard.writeText(text); toast(AICO.i18n.copied); } catch(e){ toast('Copy failed'); }
  });

  $(document).on('click', '#aico_optimize', function(){
    var content = $('#aico_content').val().trim();
    if(!content){ toast(AICO.i18n.empty); return; }

    $('#aico_progress').removeAttr('hidden');
    $('#aico_result').text('');

    $.post(AICO.ajax, {
      action: 'aico_optimize_content',
      nonce: AICO.nonce,
      content: content
    }).done(function(res){
      $('#aico_progress').attr('hidden', true);
      if(res && res.success && res.data && res.data.optimized){
        $('#aico_result').text(res.data.optimized);
      } else {
        toast(res && res.data && res.data.message ? res.data.message : AICO.i18n.error);
      }
    }).fail(function(){
      $('#aico_progress').attr('hidden', true);
      toast(AICO.i18n.error);
    });
  });

  // Initial
  $(function(){ recalcStats(); });
})(jQuery);


// Show/hide API Key functionality
jQuery(document).ready(function ($) {
    const toggleBtn = $('.aico-toggle-visibility');
    const apiKeyField = $('#aico_api_key');

    toggleBtn.on('click', function () {
        if (apiKeyField.attr('type') === 'password') {
            apiKeyField.attr('type', 'text');
            toggleBtn.text('🙈').attr('aria-label', 'Hide API Key');
        } else {
            apiKeyField.attr('type', 'password');
            toggleBtn.text('👁️').attr('aria-label', 'Show API Key');
        }
    });
});
