/**
* Fixed scroll makes taget html block to be fixed in the boundaries of the parent block 
* while scrolling window vertically 
*
* Usage: new FixScroll('#selector')
* 
* @author  Igor Shvartsev (genryh@yahoo.com) 2013
*
*  <style>
*     .scroll-fixed{position:fixed;top:0;}
*     .scroll-absolute{position:absolute;bottom:0;}
*   </style>  
*/


FixScroll = function(selector, options)
{
    this.settings = {
        top : 0
    };
    this.el =  typeof selector == 'object' && selector.constructor === jQuery ? selector : jQuery(selector);
    if (!this.el.length) {
        return;
    }
    if (typeof options === 'object') {  
        this.settings = $.extend({}, this.settings, options);
    }
    this.container   = this.el.parent();
    this.scrollState = '';
    this.wnd         = jQuery(window);
    this.construct();
}
FixScroll.prototype = {
    construct : function(){
        this.el.css({'height':this.el.height()});
        this.container.css({'position':'relative'});
       
        this.wnd.bind("scroll resize", $.proxy(function(){
            this.adjustPosition();
        }, this));
        
        this.adjustPosition();
        return this;
    },
    adjustPosition : function(){
        var state = this.checkTop() ? "" : (this.checkBottom() ? 'absolute' : 'fixed');
        if (this.scrollState !== state) {
            this.el.removeClass('scroll-absolute').removeClass('scroll-fixed');
            if (state !== '') {
                this.el.addClass('scroll-' + state);
            }
            this.scrollState = state;
        }  
    },
    checkTop : function(){
        var top = this.wnd.scrollTop();
        return (this.container.offset().top + this.settings.top - top) > 0;
    },
    checkBottom : function(){
        var top = this.wnd.scrollTop();
        var bt  = this.container.offset().top + this.container.height() - parseInt(this.container.css('padding-bottom'));   
        if (this.scrollState == 'absolute' || this.scrollState == ''){
            return (this.container.offset().top + this.container.height() + this.settings.top - top - this.el.outerHeight()) < 0;
        } else {
            return (this.el.offset().top + this.el.outerHeight() - bt) > 0;
        }
    }
    
}