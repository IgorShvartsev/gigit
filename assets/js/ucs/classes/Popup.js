/**
* Popup class
* (c)Igor Shvartsev <igor.shvartsev@gmail.com>
*/

ucs.Popup = ucs.Dialog.extend({
    callback  : null,
    construct : function( options, callback) {
        options = typeof options == 'object' ? options : {};
        options = $.extend(true, {
            cssSubmit : {display:'none'},
            onSubmit : function(){}
        }, options);
        this.parent(options);
        var dlg = this.dlg;
        var formcontent = dlg.find('.formcontent');
        $('<div/>',{'class':'popup-content'}).appendTo(formcontent);
    },
    show : function(html) {
        this.dlg.find('.popup-content').html(html);
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