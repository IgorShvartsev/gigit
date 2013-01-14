/**
* Calendar class
* (c)Igor Shvartsev <igor.shvartsev@gmail.com>
*
* Usage :  var calendar = new ucs.Calendar(options).getMonth();
*
* data structure returned is a json type and has following format:
*{
*   month    : "January 2013",
*   days     : ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
*   previous : {"m":"2", "y":"2013"},  //  or {}
*   next     : {"m":"4", "y":"2013"},
*   weeks    : {
*       "2012-12-30" : {"available":1, "class":"prev_days inactive"},
*       "2012-12-31" : {"available":1, "class":"prev_days inactive"},
*        ...................
*       "2013-01-01" : {"available":1, "class":"cur_days "},
*       "2013-01-02" : {"available":0, "class":"cur_days "},
*       "2013-01-03" : {"available":1, "class":"cur_days "},
*       .........................................
*       "2013-02-01" : {"available":1, "class":"next_days "},
*       "2013-02-02" : {"available":1, "class":"next_days "} 
*   }
*}
*/

ucs.language = ucs.language || {};

ucs.language.AVAILABLE    = ucs.language.AVAILABLE        || 'available';
ucs.language.UNAVAILABLE  = ucs.language.UNAVAILABLE      || 'unavailable';
ucs.language.NEXT_MONTH   = ucs.language.NEXT_MONTH       || 'Next month';
ucs.language.PREVIOUS_MONTH = ucs.language.PREVIOUS_MONTH || 'Previous month';

ucs.Calendar = BaseClass.extend({
    
    cal       : null,
    
    settings  : {},
    
    /* CONSTRUCTOR */    
    construct : function(options) {
        var url = window.url ? window.url : '/';
        
        /* default options */
        var defaults = {
            'name'      : '',           // name of widget
            'model'     : '',           // database table 
            'id'        : 0,            // some id of database table record
            'className' : 'cdefault',   // class name
            'container' : 'body',       // container where widget will be appended
            'editMode'  : false,        // true - switches widget to edit mode
            'shortDayName' : false,     // true - cuts day names off to 3 characters length 
            'ajaxGet'   : url + 'ajax/get',  // ajax get data url 
            'ajaxSave'  : url + 'ajax/save', // ajax save data url
            'css'       : {             // css 
                'wrapper' : {'overflow':'hidden'},
                'overlay' : {'opacity':0.6},
                'loader'  : {'background':'url('+url+'assets/images/ucsCalendar/loader.gif) center center no-repeat','width':'50px','height':'50px'}
            },
            'csrf_protection' : {       // csrf protection (if used)
                'name'  : 'csrf_protection',
                'value' : ''
            },
            'onLoad'       : function(){},// callback function when calendar is loaded completely 
            'onDateSelect' : function(){},// callback function when click on selected day (if editMode is false)   
            'onEdit'       : function(){} // callback function (if editMode is true) 
        };
        
        var self = this;
         
        this.settings = $.extend(true, defaults, options);
        if(!window.ucs.Helper){
             if(window.console && console.log)
                   console.log('Helper object not exists.');
             return;
        }
        if (this.settings.model == '') {
             if(window.console && console.log)
                   console.log('Model for the Calendar object is not defined.');
             return;
        }
        var uniq = ucs.Helper.uniq(10);
        var calId = ($.trim(this.settings.name) == '' ? uniq : this.settings.name)+'-calendar';  
        if ($('#' + calId).length) {
             alert('Sorry. Clendar with Id = ' + calId + " already exists. Try another name.");
             return;
        }
        this.cal = $('<div/>',{'id': calId, 'class': 'ucs-calendar ' + this.settings.className + (this.settings.editMode ? ' editmode' :''), 'css':this.settings.css.wrapper}).css({'position':'relative'}).appendTo(this.settings.container);
        $('<div/>',{'class':'calendar-overlay', 'css':this.settings.css.overlay}).css({'display':'none', 'position':'absolute', 'z-index': 200}).appendTo(this.cal);    
    },
    
    /* ++++++++++++++++++  PUBLIC METHODS  +++++++++++++++++ */
    
    /**
    *  Get month
    *  @param int m - month
    *  @param int y - year 
    */
    getMonth : function(m, y) {
        var self = this; 
        var params = {
            'data[month]' : (m ? m : ''),
            'data[year]'  : (y ? y : '')  
        };
        self._showOverlay(); 
        $.get(self.settings.ajaxGet + '?r=' + ucs.Helper.rand(1000,10000000), self._makeRequestParams('get', self.settings.id, params), function(data){
            if ($.isPlainObject(data)) {
                self.render(data);
                self._hideOverlay();
                self.settings.onLoad.call(self);
            }
        }, 'json').error(function(){self._hideOverlay();});
        return this;
    },
    
    /**
    * Render calendar from json data
    */
    render : function(data) {
        var self = this;
        this.cal.find('.cbody').remove();
        var body = $('<div/>',{'class':'cbody', 'css':{'position':'relative'}}).appendTo(this.cal);
        $('<div/>',{'class':'cmonth-year', 'css':{'text-align':'center'}}).text(data.month).appendTo(body);
        var prev = $('<span/>',{'class':'cprev-month', 'title': ucs.language.PREVIOUS_MONTH,  'css':{'position':'absolute', 'display':'block', 'z-index': 100}}).html('<span>prev</span>').appendTo(body);
        if (data.previous.m && data.previous.y) {
             prev.removeClass('disabled').click(function(){
                self.getMonth(data.previous.m, data.previous.y);
             });
        } else {
             prev.addClass('disabled');
        }
        $('<span/>',{'class':'cnext-month', 'title': ucs.language.NEXT_MONTH, 'css':{'position':'absolute', 'display':'block', 'z-index': 100}}).html('<span>next</span>').appendTo(body).click(function(){
             self.getMonth(data.next.m, data.next.y);
        });
        var days = $('<div/>', {'class':'cdays clearfix'}).appendTo(body);
        $.each(data.days, function(i, val){
            if (self.settings.shortDayName) {
                val = val.substr(0, 3);
            }    
            $('<span/>').text(val).appendTo(days);
        })
        var weeks = $('<div/>', {'class':'cweeks clearfix'}).appendTo(body);
        $.each(data.weeks, function(i, week){
            $.each(week, function(day, val){
                var d = day.split('-');
                $('<div/>', {'class':'cday ' + val['class'] + ' ' + (!val.available ? 'unavailable' : '')}).data('day', day).html('<span class="dt">' + d[2] + '</span><span class="inf"></span><span class="st">' + (val.available ? ucs.language.AVAILABLE : ucs.language.UNAVAILABLE) + '</span>').appendTo(weeks).click(function(){
                    var item = $(this);
                    if (!self.settings.editMode || item.hasClass('inactive')) {
                        if (!item.hasClass('unavailable') && !item.hasClass('inactive')) {
                            self.settings.onDateSelect.call(this);
                        }
                        return;
                    }
                    self.settings.onEdit.call(this);
                });
            })
        })
    },
    
    /**
    * Update calendar 
    * @param object params - params to be saved on server side
    * @param function callback - server response
    */
    update : function(params, callback) {
         if ($.isPlainObject(params)) {
              $.post(this.settings.ajaxSave, this._makeRequestParams('save', this.settings.id, params), function(data){
                    if ($.isPlainObject(data) && typeof callback == 'function') {
                        callback.call(this, data);
                    }
              }, 'json');
         }
         return this;
    },  
    
    /* ++++++++++++++++++++++ PROTECTED METHODS ++++++++++++++++++++++++++ */
    
    /**
    * Makes request params 
    */
    _makeRequestParams : function(type, id, data){
        var params = {};
        if (!$.isPlainObject(data)) {
           if (window.console && console.log) {
                console.error('Data passed to request params should be an object type');
           }
           return;  
        }
        switch(type) {
            case 'get':
                params = $.extend({},{
                    'id': id, 
                    'm' : this.settings.model, 
                }, data);
                params[this.settings.csrf_protection.name] = this.settings.csrf_protection.value;
                break;
            case 'save':
                params = $.extend({},{
                    'id': id, 
                    'm' : this.settings.model
                }, data);
                params[this.settings.csrf_protection.name] = this.settings.csrf_protection.value;
                break;
        }
        return params;
    },
    
    _showOverlay  :  function() {
       var ovr = this.cal.find('.calendar-overlay').css({'width':this.cal.width(),'height':this.cal.height(),'left':0, 'top':0}).show();
       var ldr = $('<div/>',{'class':'ucs-calendar-loader','css':this.settings.css.loader}).css({'position':'absolute','z-index':ovr.css('z-index') + 1}).appendTo(ovr.parent());
       var pos = ovr.position();
       ldr.css({'left': pos.left + (ovr.width() - ldr.width())/2, 'top':pos.top + (ovr.height() - ldr.height())/2});
    },
    
    _hideOverlay  :  function() { 
       this.cal.find('.calendar-overlay').hide();
       $('.ucs-calendar-loader').remove();
    },
});  