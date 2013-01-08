/**
* Popup class
* (c)Igor Shvartsev <igor.shvartsev@gmail.com>
*/

ucs.Popup = ucs.Dialog.extend({
    callback  : null,
    construct : function( options, callback) {
        options = typeof options == 'object' ? options : {};
        options = $.extend(true, {
           cssFormContent : {'background' : '#fff', 'padding' : '10px'} 
        }, options);
        this.parent(options);
        var dlg = this.dlg;
        dlg.find('form').remove();
        var formcontent = $('<div/>', {'class' : 'formcontent', 'css' : options.cssFormContent}).appendTo(dlg.find('.wrapper-dialog-ucs'));
    },
    show : function(html) {
        this.dlg.find('.formcontent').html(html);
        this.parent();
    },
    setCallback : function(func) {
        if (typeof func == 'function') {
            this.callback = func;
        }
        return this;
    },
    close : function(){
        this.dlg.find('.close').click();
    }
});