jQuery(document).ready(function($) {
  $('#aico_optimize').on('click', function() {
    const content = $('#aico_content').val();

    $.post(aico_ajax.ajax_url, {
      action: 'aico_optimize_content',
      content: content,
      nonce: aico_ajax.nonce
    }, function(response) {
      if (response.success) {
        $('#aico_result').html('<pre>' + response.data.optimized + '</pre>');
      } else {
        alert('Optimization failed.');
      }
    });
  });
});
