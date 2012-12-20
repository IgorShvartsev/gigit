/**
*  UCS Class Library
*  Ver: 1.02  2012
*  Required jQuery 1.6 or higher 
*  Developed by Igor Shvartsev (igor.shvartsev@gmail.com) 
*/

var ucs = ucs || {}; 
/**
* Base class 
* Extended by others
* http://ejohn.org/blog/simple-javascript-inheritance/
*/
(function(){ 
    var init = false, fnTest = /xyz/.test(function(){xyz;}) ? /\bparent\b/ : /.*/;
    this.BaseClass = function(){};
    BaseClass.extend = function(prop) {
        var parent = this.prototype;
        init = true;
        var prototype = new this();
        init = false;
        // Copy the properties over onto the new prototype
        for (var name in prop) {
            // Check if we're overwriting an existing function
            prototype[name] = typeof prop[name] == "function" && typeof parent[name] == "function" && fnTest.test(prop[name]) ?
            (function(name, fn){
                return function() {
                    var tmp = this.parent;
                    this.parent = parent[name];
                    // The method only need to be bound temporarily, so we
                    // remove it when we're done executing
                    var ret = fn.apply(this, arguments);        
                    this.parent = tmp;
                    return ret;
                };
            })(name, prop[name]) :
            prop[name];
        }
        // The Dummy Class constructor
        function DummyClass() {
            // All construction is actually done in the construct method
            if ( !init && this.construct )
                this.construct.apply(this, arguments);
        }
        // Populate our constructed prototype object
        DummyClass.prototype = prototype;
        // Enforce the constructor to be what we expect
        DummyClass.prototype.constructor = DummyClass;
        // And make this class extendable
        DummyClass.extend = arguments.callee;
        return DummyClass;
   };
})();


/**
* Helper object
*/
ucs.Helper = {
    
    rand : function(min, max){
        return Math.random() * (max - min + 1) + min; 
    },
    
    uniq : function(length){
        var str = '';
        for(var i=0;i<length;i++)str += String.fromCharCode(parseInt(this.rand(0x61,0x7a)));
        return str;  
    },
    
    alignPosition : function(pos, width, height) {
        var wnd = $(window);
        var w = wnd.width();
        var h = wnd.height();
        pos.left = width+40 > w ? 40 : ((pos.left + width + 40) >= w ? w-width-40 : pos.left + 20);
        pos.top = height+30 > h ? 30 : ((pos.top + height +30) >= h ? h-height-30 : pos.top + 30);
        return pos;
    },
    
    sizeObject : function(obj) {
        var s = 0;
        for( var i in obj){s++;}
        return s;  
    },
    
    rgbToHex : function(color){
        color = color.replace(/\s/g,"");
        var aRGB = color.match(/^rgb\((\d{1,3}[%]?),(\d{1,3}[%]?),(\d{1,3}[%]?)\)$/i);
        if (aRGB) {
            color = '';
            for (var i=1;  i<=3; i++) color += Math.round((aRGB[i][aRGB[i].length-1]=="%"?2.55:1)*parseInt(aRGB[i])).toString(16).replace(/^(.)$/,'0$1');
        } else{ 
            color = color.replace(/^#?([\da-f])([\da-f])([\da-f])$/i, '$1$1$2$2$3$3');
        }
        return color;
    },
    
    getopacity : function(jqObj) {
        var ori  = jqObj.css('opacity');
        var ori2 = jqObj.css('filter');
        if (ori2) {
            ori2 = parseInt( ori2.replace(')','').replace('alpha(opacity=','') ) / 100;
            if (!isNaN(ori2) && ori2 != '') {
                ori = ori2;
            }
        }
        return ori;
    },
    
    backgroundPosition : function(jqObj){
        var pos = [];
        if (jqObj.css('background-position') == undefined || jqObj.css('background-position') == null){
            pos[0] = jqObj.css('background-position-x');
            pos[1] = jqObj.css('background-position-y');
        }else{
            pos = jqObj.css('background-position').split(' ');
        }
        return pos;
    },
    
    showOverlay : function( color, opacity, zindex ){
        zindex = zindex == undefined ? 1500 : zindex;
        $('#ovr-stels').remove();
        $('<div/>', {'id':'ovr-stels', 'css':{'position':'fixed','width':'100%','height':'100%','backgroundColor':color,'opacity': opacity && opacity > 0 && opacity < 1 ? opacity : 1, 'zIndex':zindex, 'display':'none', 'left' :0, 'top':0}}).appendTo('body').fadeIn(300);
    },
    
    hideOverlay : function(){
        $('#ovr-stels').fadeOut(200, function(){$(this).remove()});
    },
    
    enableTips : function(ajaxUrl, params,  selector, delay) {
        var tiptimer = null;
        selector = selector ? (typeof selector == 'object' ? selector : $(selector)) : $('.tip');
        $('#tipbox').remove();
        $('<div/>',{'id':'tipbox', 'class':'tipbox', 'css':{'position':'absolute','display':'none'}}).appendTo('body');
        selector.hover(
            function(){
                if (tiptimer) clearTimeout(tiptimer);
                var $el = $(this);
                var tipbox = $('#tipbox');
                if ( $el.data('type') != undefined){
                    tipbox.data('start',1);
                    if (tipbox.data('width')) tipbox.css('width',tipbox.data('width'));
                    params = params ? $.extend({'type':$el.data('type')}, params, true) :  {'type':$el.data('type')};
                    tiptimer = setTimeout(
                        function(){
                            $.get(ajaxUrl, params, function(data){
                                if($.trim(data) != '' && tipbox.data('start')){
                                    tipbox.html(data).show();
                                }
                            });
                        },
                        delay ? parseInt(delay) : 1000
                    );
                }else if($el.data('tip') != undefined && $.trim($el.data('tip')) != ''){
                    if (!tipbox.data('width')) tipbox.data('width',tipbox.width());
                    tiptimer = setTimeout(
                        function(){
                             tipbox.html($el.data('tip').replace(/\|/g, '<br />')).css('width','auto');
                             tipbox.show();      
                        },
                        delay ? parseInt(delay) : 1000
                    );
                }else{
                    return true;
                }
            },
            function(){
                if (tiptimer) clearTimeout(tiptimer);
                $('#tipbox').data('start',0).empty().hide();
            }
      ).mousemove(function(e){
            var tipbox = $('#tipbox');
            var mousex = e.pageX + 20; 
            var mousey = e.pageY + 20; 
            var tipWidth = tipbox.width(); 
            var tipHeight = tipbox.height();
        
            /* Distance of element from the right edge of viewport */
            var tipVisX = $(window).width() - (mousex + tipWidth);
            /* Distance of element from the bottom of viewport */
            var tipVisY = $(window).height() - (mousey + tipHeight);
          
            if ( tipVisX < 20 ) { /* If tooltip exceeds the X coordinate of viewport */
                mousex = e.pageX - tipWidth - 20;
            } if ( tipVisY < 20 ) { /* If tooltip exceeds the Y coordinate of viewport */
                mousey = e.pageY - tipHeight - 20;
            } 
            tipbox.css({  top: mousey, left: mousex });
            return false;
      });
    },
    
    debug : function(variable, str, html) {
        str = str == undefined ? '' : str;
        if(typeof variable == 'string') str += variable + '<br/>';
        if(typeof variable == 'object')
        {
            for(var i in variable)
            {
                str += html ? ('<b>'+i+'</b>: ' + variable[i] + '<br/>') : (i + " : " + variable[i] + "\n\n");
            }
        }
        $('#debug').html(str);
        return str;
    }, 
    
    loadScript: function( url, callback ) {
        var done = false;
        var script = $('<scr'+'ipt>').attr({src: url, async: true}).get(0);
        script.onload = script.onreadystatechange = function() {
            if ( !done && (!this.readyState || this.readyState === 'loaded' || this.readyState === 'complete') ) {
                done = true;
                // Handle memory leak in IE
                script.onload = script.onreadystatechange = null;
                if (typeof callback === 'function') {
                           callback.call( this, this );
                }
            }
        };
        $('head').append($(script));
    },
    
    in_array : function(needle, haystack, strict) {
        var found = false, key, strict = !!strict;
        for (key in haystack) {
           if ((strict && haystack[key] === needle) || (!strict && haystack[key] == needle)) {
               found = true;
               break;
            }
        }
       return found;
    }
};

/* This file is part of OWL JavaScript Utilities.

OWL JavaScript Utilities is free software: you can redistribute it and/or 
modify it under the terms of the GNU Lesser General Public License
as published by the Free Software Foundation, either version 3 of
the License, or (at your option) any later version.

OWL JavaScript Utilities is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public 
License along with OWL JavaScript Utilities.  If not, see 
<http://www.gnu.org/licenses/>.
*/

ucs.owl = (function() {

    // the re-usable constructor function used by clone().
    function Clone() {}

    // clone objects, skip other types.
    function clone(target) {
        if ( typeof target == 'object' ) {
            Clone.prototype = target;
            return new Clone();
        } else {
            return target;
        }
    }


    // Shallow Copy 
    function copy(target) {
        if (typeof target !== 'object' ) {
            return target;  // non-object have value sematics, so target is already a copy.
        } else {
            var value = target.valueOf();
            if (target != value) { 
                // the object is a standard object wrapper for a native type, say String.
                // we can make a copy by instantiating a new object around the value.
                return new target.constructor(value);
            } else {
                // ok, we have a normal object. If possible, we'll clone the original's prototype 
                // (not the original) to get an empty object with the same prototype chain as
                // the original.  If just copy the instance properties.  Otherwise, we have to 
                // copy the whole thing, property-by-property.
                if ( target instanceof target.constructor && target.constructor !== Object ) { 
                    var c = clone(target.constructor.prototype);
                
                    // give the copy all the instance properties of target.  It has the same
                    // prototype as target, so inherited properties are already there.
                    for ( var property in target) { 
                        if (target.hasOwnProperty(property)) {
                            c[property] = target[property];
                        } 
                    }
                } else {
                    var c = {};
                    for ( var property in target ) c[property] = target[property];
                }
                
                return c;
            }
        }
    }

    // Deep Copy
    var deepCopiers = [];

    function DeepCopier(config) {
        for ( var key in config ) this[key] = config[key];
    }
    DeepCopier.prototype = {
        constructor: DeepCopier,

        // determines if this DeepCopier can handle the given object.
        canCopy: function(source) { return false; },

        // starts the deep copying process by creating the copy object.  You
        // can initialize any properties you want, but you can't call recursively
        // into the DeeopCopyAlgorithm.
        create: function(source) { },

        // Completes the deep copy of the source object by populating any properties
        // that need to be recursively deep copied.  You can do this by using the
        // provided deepCopyAlgorithm instance's deepCopy() method.  This will handle
        // cyclic references for objects already deepCopied, including the source object
        // itself.  The "result" passed in is the object returned from create().
        populate: function(deepCopyAlgorithm, source, result) {}
    };

    function DeepCopyAlgorithm() {
        // copiedObjects keeps track of objects already copied by this
        // deepCopy operation, so we can correctly handle cyclic references.
        this.copiedObjects = [];
        thisPass = this;
        this.recursiveDeepCopy = function(source) {
            return thisPass.deepCopy(source);
        }
        this.depth = 0;
    }
    DeepCopyAlgorithm.prototype = {
        constructor: DeepCopyAlgorithm,

        maxDepth: 256,
            
        // add an object to the cache.  No attempt is made to filter duplicates;
        // we always check getCachedResult() before calling it.
        cacheResult: function(source, result) {
            this.copiedObjects.push([source, result]);
        },

        // Returns the cached copy of a given object, or undefined if it's an
        // object we haven't seen before.
        getCachedResult: function(source) {
            var copiedObjects = this.copiedObjects;
            var length = copiedObjects.length;
            for ( var i=0; i<length; i++ ) {
                if ( copiedObjects[i][0] === source ) {
                    return copiedObjects[i][1];
                }
            }
            return undefined;
        },
        
        // deepCopy handles the simple cases itself: non-objects and object's we've seen before.
        // For complex cases, it first identifies an appropriate DeepCopier, then calls
        // applyDeepCopier() to delegate the details of copying the object to that DeepCopier.
        deepCopy: function(source) {
            // null is a special case: it's the only value of type 'object' without properties.
            if ( source === null ) return null;

            // All non-objects use value semantics and don't need explict copying.
            if ( typeof source !== 'object' ) return source;

            var cachedResult = this.getCachedResult(source);

            // we've already seen this object during this deep copy operation
            // so can immediately return the result.  This preserves the cyclic
            // reference structure and protects us from infinite recursion.
            if ( cachedResult ) return cachedResult;

            // objects may need special handling depending on their class.  There is
            // a class of handlers call "DeepCopiers"  that know how to copy certain
            // objects.  There is also a final, generic deep copier that can handle any object.
            for ( var i=0; i<deepCopiers.length; i++ ) {
                var deepCopier = deepCopiers[i];
                if ( deepCopier.canCopy(source) ) {
                    return this.applyDeepCopier(deepCopier, source);
                }
            }
            // the generic copier can handle anything, so we should never reach this line.
            throw new Error("no DeepCopier is able to copy " + source);
        },

        // once we've identified which DeepCopier to use, we need to call it in a very
        // particular order: create, cache, populate.  This is the key to detecting cycles.
        // We also keep track of recursion depth when calling the potentially recursive
        // populate(): this is a fail-fast to prevent an infinite loop from consuming all
        // available memory and crashing or slowing down the browser.
        applyDeepCopier: function(deepCopier, source) {
            // Start by creating a stub object that represents the copy.
            var result = deepCopier.create(source);

            // we now know the deep copy of source should always be result, so if we encounter
            // source again during this deep copy we can immediately use result instead of
            // descending into it recursively.  
            this.cacheResult(source, result);

            // only DeepCopier::populate() can recursively deep copy.  So, to keep track
            // of recursion depth, we increment this shared counter before calling it,
            // and decrement it afterwards.
            this.depth++;
            if ( this.depth > this.maxDepth ) {
                throw new Error("Exceeded max recursion depth in deep copy.");
            }

            // It's now safe to let the deepCopier recursively deep copy its properties.
            deepCopier.populate(this.recursiveDeepCopy, source, result);

            this.depth--;

            return result;
        }
    };

    // entry point for deep copy.
    //   source is the object to be deep copied.
    //   maxDepth is an optional recursion limit. Defaults to 256.
    function deepCopy(source, maxDepth) {
        var deepCopyAlgorithm = new DeepCopyAlgorithm();
        if ( maxDepth ) deepCopyAlgorithm.maxDepth = maxDepth;
        return deepCopyAlgorithm.deepCopy(source);
    }

    // publicly expose the DeepCopier class.
    deepCopy.DeepCopier = DeepCopier;

    // publicly expose the list of deepCopiers.
    deepCopy.deepCopiers = deepCopiers;

    // make deepCopy() extensible by allowing others to 
    // register their own custom DeepCopiers.
    deepCopy.register = function(deepCopier) {
        if ( !(deepCopier instanceof DeepCopier) ) {
            deepCopier = new DeepCopier(deepCopier);
        }
        deepCopiers.unshift(deepCopier);
    }

    // Generic Object copier
    // the ultimate fallback DeepCopier, which tries to handle the generic case.  This
    // should work for base Objects and many user-defined classes.
    deepCopy.register({
        canCopy: function(source) { return true; },

        create: function(source) {
            if ( source instanceof source.constructor ) {
                return clone(source.constructor.prototype);
            } else {
                return {};
            }
        },

        populate: function(deepCopy, source, result) {
            for ( var key in source ) {
                if ( source.hasOwnProperty(key) ) {
                    result[key] = deepCopy(source[key]);
                }
            }
            return result;
        }
    });

    // Array copier
    deepCopy.register({
        canCopy: function(source) {
            return ( source instanceof Array );
        },

        create: function(source) {
            return new source.constructor();
        },

        populate: function(deepCopy, source, result) {
            for ( var i=0; i<source.length; i++) {
                result.push( deepCopy(source[i]) );
            }
            return result;
        }
    });

    // Date copier
    deepCopy.register({
        canCopy: function(source) {
            return ( source instanceof Date );
        },

        create: function(source) {
            return new Date(source);
        }
    });

    // HTML DOM Node

    // utility function to detect Nodes.  In particular, we're looking
    // for the cloneNode method.  The global document is also defined to
    // be a Node, but is a special case in many ways.
    function isNode(source) {
        if ( window.Node ) {
            return source instanceof Node;
        } else {
            // the document is a special Node and doesn't have many of
            // the common properties so we use an identity check instead.
            if ( source === document ) return true;
            return (
                typeof source.nodeType === 'number' &&
                source.attributes &&
                source.childNodes &&
                source.cloneNode
            );
        }
    }

    // Node copier
    deepCopy.register({
        canCopy: function(source) { return isNode(source); },

        create: function(source) {
            // there can only be one (document).
            if ( source === document ) return document;

            // start with a shallow copy.  We'll handle the deep copy of
            // its children ourselves.
            return source.cloneNode(false);
        },

        populate: function(deepCopy, source, result) {
            // we're not copying the global document, so don't have to populate it either.
            if ( source === document ) return document;

            // if this Node has children, deep copy them one-by-one.
            if ( source.childNodes && source.childNodes.length ) {
                for ( var i=0; i<source.childNodes.length; i++ ) {
                    var childCopy = deepCopy(source.childNodes[i]);
                    result.appendChild(childCopy);
                }
            }
        }
    });

    return {
        DeepCopyAlgorithm: DeepCopyAlgorithm,
        copy: copy,
        clone: clone,
        deepCopy: deepCopy
    };
})();



// jQuery Extensions


/**
 *  jQuery quick Each
 *
 *  Example:
 *  a.quickEach(function() {
 *      this; // jQuery object
 *  });
 */
jQuery.fn.quickEach = (function() {
    var jq = jQuery([1]);
    return function(c) {
        var i = -1, el, len = this.length;
        try {
            while (++i < len && (el = jq[0] = this[i]) && c.call(jq, i, el) !== false);
        } catch (e) {
            delete jq[0];
            throw e;
        }
        delete jq[0];
        return this;
    };
 }());
 
 /**
 *  jQuery inlineEdit
 * 
 *  Allows edit html element with text  directly to DB through AJAX
 */
 jQuery.fn.inlineEdit = function(settings){
     var url = window.url ? window.url : '/';
     var config = {
        'type'          : 'textarea',
        'onClick'       : function(event){},
        'onSubmit'      : function(val, event){},
        'requestParams' : {},
        'submitBtn'     : {enable: true, css:{'height':'16px', 'width':'20px','font-size':'11px','line-height':'16px','text-align':'center','top':'-18px','font-family':'Arial','border':'1px solid #aaa','background':'#5EA6FF','color':'#fff'}, text : 'ok'},
        'ajaxUrl'       : url + 'ajax/save'
    }; 
    config = jQuery.extend(true, config, settings);
    return this.each(function(){
        var mouseOver = false
        var item = jQuery(this);
        var editBox = null;
        item.mouseover(
            function(){
                if (mouseOver) return;
                mouseOver = true;
                var pos = item.offset();
                
                editBox = jQuery('<div/>',{'class':'edit-box','css':{'position':'absolute','width':item.width()+30,'height':item.height()+8,'left':(pos.left-5) + 'px',top:(pos.top - 2 + 'px')}}).appendTo('body').mouseleave(function(){
                    jQuery(this).remove();
                    mouseOver = false;
                });
                
                jQuery('<div/>',{'class':'edit-icon',
                            'css':{ position :'absolute',
                                    top      :'2px',
                                    right    :'2px',
                                    cursor   :'pointer'}})
                .appendTo(editBox)
                .click(function(){
                    var dummy = jQuery('<div/>', {'css':{
                                    position : 'absolute', 
                                    width    : editBox.width(), 
                                    height   : editBox.height(), 
                                    top      : editBox.css('top'), 
                                    left     : editBox.css('left'), 
                                    background : '#fff'}}).appendTo('body');
                    var txt = item.text().replace(/^\.\.\./, ' '); 
                    var editEl  = config.type == 'input' ? jQuery('<input/>').css({'line-heigth':(editBox.height() - 2) + 'px'}).attr('value', txt) : jQuery('<textarea/>').text(txt);
                            
                    editEl.css({
                                    position : 'absolute', 
                                    width    : editBox.width() - 4, 
                                    height   : editBox.height() - 2, 
                                    top      : 0, 
                                    left     : 0,
                                    zIndex   : 20, 
                                    padding  :'1px 2px',
                                    border   :'1px solid #aaa'})
                    .appendTo(dummy)
                    .focus()
                    .blur(function(e){
                            var val = jQuery.trim(jQuery(this).val());
                            val =  val == '' ? '...' : val.replace(/[\r\n]+/g, '');
                            var res = config.onSubmit.call(item, val, e)
                            if (!res) {
                            } else if (jQuery.isPlainObject(res)){
                                jQuery.post(config.ajaxUrl, jQuery.extend(true, config.requestParams, res), function(data){
                                    if (data.error)return;
                                    item.text(val);
                                    mouseOver = false;
                                }, 'json').error(function(){
                                    !window.console || window.console.error("Server respose failed for edit element");
                                });
                            }
                            jQuery(this).parent().remove();
                            mouseOver = false;
                    });
                    if (config.submitBtn.enable) {
                            jQuery('<div/>',{'css':config.submitBtn.css}).html(config.submitBtn.text).css({'position':'absolute','z-index':'50','cursor':'pointer'}).appendTo(dummy).click(function(){editEl.blur();}).hover(function(){jQuery(this).addClass('hover')}, function(){jQuery(this).removeClass('hover')});
                    }
                    editBox.remove();
                });
            }
        );
    });   
 };
 
 /**
 * jQuery  selectbox widget
 * 
 * Structure 
 * <div class="selbox">
 *   <div class="top"><div></div></div>
 *   <div class="sel" data-value=""><span class="dsc">Desctiption</span><span class="i"></span></div>
 *   <div style="visibility: hidden;" class="options">
 *       <div class="top"><div></div></div>
 *       <ul> 
 *           <li data-value="2" class="selected">Year</li>
 *           <li data-value="3">3 Years (save 50€)</li>       
 *       </ul>
 *       <div class="bottom"><div></div></div>
 *   </div>
 *   <div class="bottom"><div></div></div>
 * </div>
 */
 jQuery.fn.selbox = function(settings){
    var config = {
        'onselect' : function(){}
    };
    config = jQuery.extend(true, config, settings);
    return this.each(function(){
        jQuery(this).mousedown(function(e){e.stopPropagation();}).bind('init', function(){
            var selbox = jQuery(this);
            var selected = selbox.find('li.selected');
            if (!selected.length) {
                selected = selbox.find('li').eq(0);
                if (!selected.length) return false;
            }
            selbox.find('.sel').data('value', selected.data('value')).find('.dsc').text(selected.text());
                selected.addClass('selected').siblings().removeClass('selected');
            }).trigger('init').find('.sel').mousedown(function(){
                var selbox = jQuery(this).parents('.dropdown-widget');
                var options = selbox.find('.options');
                options.css('visibility') == 'hidden' ? options.css('visibility','visible') : options.css('visibility','hidden');
            }).end().find('.options li').click(function(){
                var selbox = jQuery(this).parents('.dropdown-widget');
                var selected = jQuery(this);
                selbox.find('.sel').data('value', selected.data('value')).find('.dsc').text(selected.text());
                selected.addClass('selected').siblings().removeClass('selected');
                selbox.find('.sel').trigger('mousedown');
                config.onselect.call(selbox.get(0));
            });
        });
 };
 
 /**
 * jQuery right click events
 *
 */
 jQuery.extend( jQuery.fn, {
        // right click
        rightClick: function(handler) {
            var el = jQuery(this);
            el.each( function() {
                jQuery(this).mousedown( function(e) {
                    var evt = e;
                    jQuery(this).mouseup( function() {
                        jQuery(this).unbind('mouseup');
                        if( evt.button == 2 ) {
                            handler.call( jQuery(this), evt );
                            return false;
                        } else {
                            return true;
                        }
                    });
                });
                jQuery(this)[0].oncontextmenu = function() {
                    return false;
                }
            });
            return el;
        },        
        // right mouse down
        rightMouseDown: function(handler) {
            var el = jQuery(this);
            el.each( function() {
                jQuery(this).mousedown( function(e) {
                    if( e.button == 2 ) {
                        handler.call( $(this), e );
                        return false;
                    } else {
                        return true;
                    }
                });
                jQuery(this)[0].oncontextmenu = function() {
                    return false;
                }
            });
            return el;
        },
        // right mouse up
        rightMouseUp: function(handler) {
            var el = jQuery(this);
            el.each( function() {
                jQuery(this).mouseup( function(e) {
                    if( e.button == 2 ) {
                        handler.call( jQuery(this), e );
                        return false;
                    } else {
                        return true;
                    }
                });
                jQuery(this)[0].oncontextmenu = function() {
                    return false;
                }
            });
            return el;
        },
        // no context
        noContext: function() {
            var el = jQuery(this);
            jQuery(this).each( function() {
                jQuery(this)[0].oncontextmenu = function() {
                    return false;
                }
            });
            return el;
        }
        
    });
