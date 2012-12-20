/**
* Promt class
* (c)Igor Shvartsev <igor.shvartsev@gmail.com>
*/
ucs.language = ucs.language || {};

ucs.language.YES = ucs.language.YES || 'OK';
ucs.language.NO  = ucs.language.NO || 'NO';

ucs.Promt = ucs.Dialog.extend({
    callback  : null,
    construct : function( options, callback) {
        options = typeof options == 'object' ? options : {};
        options = $.extend(true, {
            cssSubmit : {display:'none'},
            cssYes :{
                  'display' : 'inline-block',
                  'text-transform' : 'none',
                  'min-width' : '65px'
            },
            cssNo  :{
                  'display' : 'inline-block',
                  'margin' : '0 30px 0 0',
                  'text-transform' : 'none',
                  'min-width' : '65px'
            },
            css :{
            },
            onSubmit : function(){}
        }, options);
        this.parent(options);
        var dlg = this.dlg;
        var formcontent = dlg.find('.formcontent');
        $('<div/>',{'class':'promt-text',css:{'padding':'10px 0 30px 0','text-align':'center'}}).appendTo(formcontent);
        var buttons = $('<div/>',{'class':'buttons', css:{'overflow':'hidden', 'text-align': 'center'}}).appendTo(formcontent);
        $('<a/>',{'class':'promt-no btn btn-mini btn-red'}).css(options.cssNo).text(options.no ? options.no : ucs.language.NO).appendTo(buttons).click(function(){
            dlg.find('.close').click();
        });
        var self = this;
        $('<a/>',{'class':'promt-yes btn btn-mini btn-green'}).css(options.cssYes).text(options.yes ? options.yes : ucs.language.YES).appendTo(buttons).click(function(){
            self.callback ? self.callback.call(this, dlg) : callback.call(this, dlg);
        });
    },
    show : function(text) {
        this.dlg.find('.promt-text').html(text);
        this.parent();
    },
    setButtonsText : function(yes, no){
        !yes || this.dlg.find('.promt-yes').text(yes);
        !no  || this.dlg.find('.promt-no').text(no);
        return this;
    },
    setButtonsCss : function(yescss, nocss){
        if (yescss && typeof yescss == 'object') {
            this.dlg.find('.promt-yes').css(yescss);
        }
        if (nocss && typeof nocss == 'object') {
            this.dlg.find('.promt-no').css(nocss);
        }
        return this;
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