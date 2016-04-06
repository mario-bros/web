(function() {
    tinymce.PluginManager.add('wppd_form', function(editor, url) {
        editor.addButton('wppd_form', {
            text: false,
            icon: 'dashicons dashicons-forms',
            tooltip: 'EasyPay Form',
            onclick: function() {
                editor.insertContent('[EASYPAY_FORM]');
            }
        });
    });
    
})();