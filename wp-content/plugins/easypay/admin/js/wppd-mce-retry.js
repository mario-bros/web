(function() {
    tinymce.PluginManager.add('wppd_retry', function(editor, url) {
        editor.addButton('wppd_retry', {
            text: false,
            image: wppd_data.plugin_url+'images/icons/retry-icon.png',
            tooltip: 'EasyPay Retry Button',
            onclick: function() {
                editor.insertContent('[EASYPAY_RETRY_BUTTON]');
            }
        });
    });
    
})();