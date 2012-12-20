/**
* Dialog class
* (c)Igor Shvartsev <igor.shvartsev@gmail.com>
*/
ucs.Dialog = BaseClass.extend({
    settings  : {},
    dlg       : null,
    construct : function(options, callback) {
        var url = window.url ? window.url : '/';
        var defaults =  {
            name        : '',
            submit      : ' Submit ',
            formHtml    : '',
           autoPosition : true,
           draggable    : true, 
            css         : {
                 'width':'400px',
                 'left': '30%',
                 'top': '20%',
                 'z-index':10000,
                 'border':'1px solid #ccc',
            },
            cssClose   : {
                 'width':'20px',
                 'height':'20px',
                 'line-height':'20px',
                 'top':'-8px',
                 'right':'-8px',
                 'background':'#222',
                 'color':'#ccc',
                 'border':'1px solid #ccc'
            },
            cssWrap   : {},
            cssSubmit : {
                  'width':'80px',
                  'padding': '6px 0',
                  'display':'block',
                  'margin' : '10px auto'
            },
            cssLoader : {
                   'background' : 'url(' + url + 'assets/images/loader-ajax.gif) no-repeat',
                   'width' : '16px',
                   'height': '11px'  
            },
            overlay : {
                  'use' :false,
                  'color': '#fff',
                  'opacity': '0.5'
            }
        };
        this.settings = $.extend(true, defaults, options);
        if(!window.ucs.Helper && window.console && console.log){
             console.log('Helper object not exists. Dialog Class will not work properly');
             return;
         }
         this.dlg  = $('<div/>',{
                'id':($.trim(this.settings.name) == '' ? ucs.Helper.uniq(10) : this.settings.name)+'-dlg',
                'class':'dialog',
                'css':$.extend(true, this.settings.css, {'display':'none','position':'fixed','padding':0})
         }).data('autoPosition', this.settings.autoPosition).appendTo($('body'));

         var box  = $('<div/>',{
                'class':this.settings.name+'box wrapper-dialog-ucs',
                'css':$.extend(true, this.settings.cssWrap, {'position':'relative'})
         }).appendTo(this.dlg);
         
         var overlay = this.settings.overlay;
        
         $('<div/>',{
             'class':'close',
             'css':$.extend(true, this.settings.cssClose, {'position':'absolute','text-align':'center','cursor':'pointer','title':'close'})
         }).text('X').appendTo(this.dlg).click(function(){
             $(this).parent().hide();
             !overlay.use || ucs.Helper.hideOverlay();
         });

         var form = $('<form/>').appendTo(box).submit(function(){
            if (typeof callback == 'function') {
                return callback.call(this);
            } else {
                alert('Callback is not defined');
                return false;
            }
         }).html('<div class="formcontent">'+this.settings.formHtml+'</div>');

         $('<input/>',{'type':'submit','name':'submit','value':' '+this.settings.submit+' ', 'css':this.settings.cssSubmit}).appendTo(form);
         
         if (typeof $.prototype.draggable == 'function' && this.settings.draggable){
                this.dlg.draggable({
                        grid  : [1,1],
                        cancel:'.' + box.attr('class')
                })
         } 
    },
    
    form : function(html) {
      this.dlg.find('.formcontent').empty().html(html);
      return this;
    },
    
    launcher  : function(selector, callback){
        var self = this;
        selector = typeof selector == 'object' ? selector : $(selector);
        if (selector.length) {
            selector.click(function(){
                if (typeof callback == 'function') {
                    callback.call(this, self.dlg);
                }
                self.show();
                return false;
            });
        } else if(window.console && console.log){
             console.error('Selector not found to launch dialog');
        }
        return this;
    },
    
    show : function(){
        var wnd = $(window);
        !this.settings.autoPosition || this.dlg.css({'left' : (wnd.width() - this.dlg.width())/2, 'top' : (wnd.height() - this.dlg.height())/2});
        !this.settings.overlay.use || ucs.Helper.showOverlay(this.settings.overlay.color, this.settings.overlay.opacity, this.settings.css['x-index'] - 1);
        this.dlg.show().find('.close').show().end().find('form').show().siblings().remove();
        return this;
    },
    hide : function(){
        this.dlg.hide();
        !this.settings.overlay.use || ucs.Helper.hideOverlay();
        return this;
    },
    
    showLoader  :  function() { 
       var ldr = $('<div/>',{'class':'ucs-dlg-loader','css':this.settings.cssLoader}).css({'position':'absolute'}).appendTo(this.dlg);
       ldr.css({'left': (this.dlg.width() - ldr.width())/2, 'top':(this.dlg.height() - ldr.height())/2});
       return this;
    },
    
    hideLoader  :  function() {
       $('.ucs-dlg-loader').remove();
       return this;
    },
    
    center      : function(top){
        var wnd = $(window);
        if (!top) {
            top = (wnd.height() - this.dlg.height())/2;
        }
        this.dlg.css({'left':(wnd.width()- this.dlg.width())/2 , 'top': top});
        return this;  
    },
    
    destroy : function(){
        this.dlg.remove();
        delete this.settings;
        delete this.dlg;
    }
});
 
