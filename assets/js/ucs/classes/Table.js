/**
* Table class
* @version 1.09  october 2012
* @author (c)Igor Shvartsev <igor.shvartsev@gmail.com>
*
* Usage :  var table = new ucs.Table(options).setData(data)
*
* data structure returned is a json type and has following format
* {
*    'id'         : 12,         // recordset id
*    'total'      : 100,        // total count of records
*    'page'       : 1,          // current page number
*    'perpage'    : 25,         // records per page,
*    'like'       : [],         // arrow of ids  of like records 
*    'likeFilter' : 0,          // enable showing only like records 
*    'thead'      : [
*                   {'id' : 135, 'field':'email', 'name':'Email', 'type': 'email', 'order': 2, 'required' : 1 , width':'40', 'sortable': 1},
*                   {'id' : 136, 'field': 'married', 'name':'Married', 'type': 'yesno', 'order': 3, 'required' : 0 , width':'20', 'sortable' : 1}
*                   ],
*    'tbody'      : {
*                  '321-column' : ['test@test.com', 'yes'],  // or [{'value' : 'test.com', 'data' : {'error':'', 'status':'','tip':''}}, 'Name A']
*                  '322-column' : ['test@test.com', 'no']
*                    }
* }
*
* tbody  can be simple html and has next format:
*       <tr id=tr-321>
*            <td><div class="cText"></div></td>
*            <td><div class="cText"></div></td>
*            .........
*        </tr>
*        <tr id=tr-322>
*            <td><div class="cText"></div></td>
*            <td><div class="cText"></div></td>
*            .........
*        </tr>
*        ........ 
*/

ucs.language = ucs.language || {};

ucs.language.SORTING      = ucs.language.SORTING || 'Sorting';
ucs.language.SORTING_UP   = ucs.language.SORTING_UP || 'Sorting Up';
ucs.language.SORTING_DOWN = ucs.language.SORTING_DOWN || 'Sorting Down';
ucs.language.DELETE_WARN  = ucs.language.DELETE_WARN || "You are about to delete %s. \n Are you sure?";
ucs.language.PAGE_FIRST   = ucs.language.PAGE_FIRST || "First";
ucs.language.PAGE_PREVIOUS= "&lt;"; //ucs.language.PAGE_PREVIOUS || "Previous";
ucs.language.PAGE_NEXT    = "&gt;";//ucs.language.PAGE_NEXT || "Next";
ucs.language.TOTAL        = ucs.language.TOTAL || "Total";
ucs.language.DUPLICATE    = ucs.language.DUPLICATE || "Duplicate";
ucs.language.SEARCH       = ucs.language.SEARCH || "Search";


ucs.Table = BaseClass.extend({
    
    settings  : {},
    
    ajaxParams : {},                            // additional ajax params for getting Table data
    
    header    : null,                           // jQuery created table header object
    
    body      : null,                           // jQuery created table body object
    
    dummyTD   : null,                           // jQuery dummy TD object
    
    lock      : false,                          // locking some ptocedures (ajax actions)

    /* CONSTRUCTOR */    
    construct : function(options, onCreateCallback) {
         var url = window.url ? window.url : '/';
         var defaults =  {
             'name'        : '',                // table name
             'model'       : '',                // database table
             'id'          : 0,                 // recordset Id
             'csrf_protection' : {              // csrf protection
                                    'name':'csrf_protection',
                                    'value': ''
                                 },      
             'cellpadding' : 0,                 // table cellpadding attribute
             'cellspacing' : 0,                 // table cellspacing attribute
             'container'   : 'body',            // DOM selector where table should be inserted
             'css'         : {                  // CSS for table elements
                                'wrapper' : {'overflow':'hidden'},
                                'top'     : {},
                                'table'   : {},
                                'scrollY' : {},
                                'bottom'  : {},
                                'pagination':{},
                                'overlay' : {},
                                'loader'  : {'background':'url('+url+'assets/images/ucsTable/loader.gif) center center no-repeat','width':'50px','height':'50px'}
                            },
             'overlay'     : true,              // makes table to be slightly disabled while ajax is running
             'editable'    : false,             // editable (not implemented yet)
             'sortableRow'    : false,          // makes rows to be sorted clicking on sorting icons in th
             'sortableColumn' : false,          // makes columns to be sorted (drag&drop)
             'searchable'  : false,             // activate search box at the top of table  
             'enableRowSelection'    : false,   // enable row selection by mouse click
             'enableColumnSelection' : false,   // enable column selection by mouse click
             'information'           : true,    // enable bottom information (duplicate, total)
             'dynamicPagination'     : false,   // enable dynamic pagination while scrolling
             'onCompleteRows': function(){},    // event when row bulding ended 
             'onAddRows'   : function(){},      // event when row dynamically added (dynamic pagination)     
             'thOnClick'   : function(event, table){}, // th callback on event Click
             'thOnDblClick': function(event, table){}, // th callback on event Double Click
             'tdOnClick'   : function(event, table){}, // td callback on event Click
             'tdOnDblClick': function(event, table){}, // td callback on event Double Click
             'thOnEdit'    : function(event, table){}, // th callback on event Edit
             'tdOnEdit'    : function(event, table){}, // td callback on event Edit
             'trOnDelete'  : function(event, table){return true}, // tr callback on event Delete
             'thOnDelete'  : function(event, table){return true}, // th callback on event Delete
             'onPagination': function(){},      // event when clicking pagina navigation
             'page'        : 1,                 // current number of page. 
             'perpage'     : 15,                // number of table rows per page. If 0 , pagination is disabled. 
             'total'       : 0,                 // total rows that table should contain 
             'ajaxSave'    : url+'ajax/save',   // server side ajax url to save edited data
             'ajaxGet'     : url+'ajax/get',    // server side ajax url to get data
             'ajaxDelete'  : url+'ajax/delete', // server side ajax url to delete data
             'ajaxSort'    : url+'ajax/sort',   // server side ajax url to sort data   
             'sortField'   : '',                // field name  sorted by 
             'sortOrder'   : 1,                 // sort order 1 - ASC, 0 - DESC
             'search'      : '',                // word to be searched initially, leave empty to disable searching    
             'like'        : [],                // array of values ( offen record IDs)
             'likeFilter'  : false,             // enable like filter 
             'groupField'  : ''                 // group by this field
         };
         
         var self = this;
         
         this.settings = $.extend(true, defaults, options);
         if(!window.ucs.Helper && window.console && console.log){
             console.log('Helper object not exists. Table Class will not work properly');
             return;
         }
         var uniq = ucs.Helper.uniq(10);
         var tableId = ($.trim(this.settings.name) == '' ? uniq : this.settings.name)+'-table';
         if ($('#' + tableId).length) {
             alert('Sorry. Table with Id = ' + tableId + " already exists. Try another name.");
             return;
         }
         var wrapper = $('<div/>',{'id': tableId, 'css':this.settings.css.wrapper}).css({'position':'relative'}).appendTo(this.settings.container);
         var top     = $('<div/>',{'class':'table-top','css':this.settings.css.top}).css({'position':'relative'}).appendTo(wrapper);
         var form    = $('<form/>').appendTo(top);
         
         // dummy TD
         this.dummyTD = $('<table/>', {
                'class'      : 'ucs-table dummy',
                'css'        : this.settings.css.table,
                'cellpadiing': this.settings.cellpadding,
                'cellspacing': this.settings.cellspacing   
         }).html('<thead><th></th></thead><tbody><tr><td></td></tr></tbody>').appendTo(wrapper).hide().find('td');
         
         // header table
         this.header = $('<table/>',{
                'id'         : tableId + '_header',
                'class'      : 'ucs-table ucs-table-header',
                'css'        : this.settings.css.table,
                'cellpadiing': this.settings.cellpadding,
                'cellspacing': this.settings.cellspacing
         }).css('width',0).appendTo($('<div/>',{'class':'scroll_header','css':{'overflow':'hidden','width':'100%'}}).appendTo(wrapper)).data('sort', {field:'', 'asc': 1});
         
         // body table
         this.body = $('<table/>',{
                'id'         : tableId + '_body',
                'class'      : 'ucs-table ucs-table-body',
                'css'        : this.settings.css.table,
                'cellpadiing': this.settings.cellpadding,
                'cellspacing': this.settings.cellspacing
         }).css('width',0).appendTo($('<div/>',{'class':'scroll_body','css':{'overflow':'auto','width':'100%'}}).css(this.settings.css.scrollY).appendTo(wrapper));
         var hdr = this.header;
         wrapper.find('.scroll_body').scroll(function(){
             $('.inline-edit-block form').children().trigger('reset');
             hdr.css('margin-left', -$(this).scrollLeft() + 'px');
             if (self.settings.dynamicPagination) {
                 var scrollBody = $(this);
                 var h = scrollBody.children().height() - scrollBody.height();
                 var curY = scrollBody.scrollTop();
                 if ( curY >= h  && self.settings.page * self.settings.perpage <= self.settings.total) {
                     self._dynamicPageLoading(self.settings.page + 1);
                 }
             }
         });
         // bottom  with pagination
         var tBottom = $('<div/>',{'class':'table-bottom', 'css':this.settings.css.bottom}).appendTo(wrapper).html('<div class="pagination"></div><div class="info"></div>');
         var inf = tBottom.find('.info');
         this.settings.information || inf.hide();
         var pgn = tBottom.find('.pagination').css(this.settings.css.pagination);
         !this.settings.dynamicPagination || pgn.hide();
         this._paginationEvents(pgn);
         $('<div/>',{'class':'table-overlay', 'css':this.settings.css.overlay}).css({'display':'none','position':'absolute'}).appendTo(wrapper);
         if (onCreateCallback && typeof onCreateCallback == 'function') {
             onCreateCallback.call(this);
         }
         // top with search box
         if (this.settings.searchable) {
             var searchWrap = $('<div/>',{'class':'search-wrap', 'css':{'float':'left'}}).appendTo(top);
             $('<input class="search" type="text" name="search" /> <button type="button">' + ucs.language.SEARCH + '</button>').appendTo(searchWrap);
             searchWrap.find('button').click(function(){
                 var search = $(this).siblings('.search');
                 if ($.trim(search.val()) != '') {
                     self.search(search.val());
                 }
             }).end().find('.search').data('backspace', false).keydown(function(e){
                 var search = $(this);
                 if (e.which == 13) {
                     search.siblings('button').click();
                     return;
                 }
                 (e.which == 8 || e.which == 46 )&& search.val().length  ? search.data('backspace', true) : $.nop;
             }).keyup(function(){
                 var search = $(this);
                 search.data('backspace') && !search.val().length ? self.search('') : $.nop; 
                 search.data('backspace', false);
             });
         }
    },
    
    
    /* ++++++++++++++++++  PUBLIC METHODS  +++++++++++++++++ */
    
    getData       : function(id, params){
        var self = this;
        self.ajaxParams = $.isPlainObject(params) ? params : {}; 
        self._showOverlay();
        $.get(self.settings.ajaxGet + '?r=' + ucs.Helper.rand(1000,10000000), self._makeRequestParams('get', id, $.extend({}, {'data[all]':1}, self.ajaxParams)), function(data){
            if ($.isPlainObject(data)) {
                self.setData(data)
                self._hideOverlay();
            }
        }, 'json').error(function(){self._hideOverlay();});
        return this;
    },
    
    setData     : function(data){
        var self = this;
        this.header.empty().append('<thead><tr class="row"></tr></thead>');
        this.body.empty().append($('<tbody/>'));
        if (typeof data != 'object') {
            if (window.console && console.log) {
                console.log('Data is not an object');
            } 
            return;
        }
        if (!data.thead || !$.isArray(data.thead)) {
            if (window.console && console.log) {
                console.log('Table can\'t be initialized. Param \'thead\' is not defined or is not an array');
            } 
            return;
        }
        
        this.settings.perpage    = data.perpage != undefined ? data.perpage : 0;
        this.settings.page       = data.page != undefined ? data.page : this.settings.page;
        this.settings.id         = data.id != undefined ? data.id : this.settings.id;
        
        // add columns
        $.each(data.thead, function(i, params){
           self.addColumn(params);
        });
        
        // add rows
        this.setRows(data);
        
        // set pagination
        this.pagination($(self.settings.container).find('.pagination')); 
        
        // finally adjust table width
        this._adjustTableWidth();
        
        this._setEvents();
        
        return this;
    },
    
    setRows    : function(data, addmode){
        var self = this;
        
        this.settings.total      = data.total != undefined ? data.total : this.settings.total;
        this.settings.like       = data.like  && $.isArray(data.like) ? data.like : [];
        this.settings.likeFilter = data.likeFilter ? parseInt(data.likeFilter) : false;
        var tbody = this.body.find('tbody');
        addmode || tbody.empty(); 
        var tb = tbody.end().parent().parent().find('.table-bottom');
        if (tb.length) {
            tb.find('.info').html(ucs.language.TOTAL + ': <span class="total">' + this.settings.total + '</span> &nbsp;&nbsp;' +(this.settings.like.length > 0 ? (ucs.language.DUPLICATE + ': <span class="duplicate">' + this.settings.like.length + '</span>') : ''));
        }
        if ( data.tbody  && $.isArray(data.tbody) && !data.tbody.length) {
            data.tbody = {};   // fixing incorrect json encoding of empty value by PHP
        } 
        if (data.tbody == undefined || !$.isPlainObject(data.tbody)) {
            if (window.console && console.log) {
                console.log('Rows can\'t be  set. Param \'tbody\' is not defined or is not an array');
            } 
            return;
        }
        if ($.isPlainObject(data.tbody)) {
            $.each(data.tbody, function(i, columns){
                self.addRow(parseInt(i), columns);
            });
        } else {
            this._sethtml(data.tbody);
        }
        this._updateRows();
        addmode ? this.settings.onAddRows.call(this) : this.settings.onCompleteRows.call(this);
        return this;
    },
    
    addRow     : function(id, columns, bUpdate, callback){    
        var self  = this;
        var tbody = this.body.find('tbody');
        var th    = this.header.find('th');
        var tr    = $('<tr/>',{'id':'tr-' + id});
        if ($.inArray(id.toString(), this.settings.like) != -1) {
            tr.addClass('like');
        }
        var cols  = '';   
        for (var i = 0; i < th.length; i++) {
            var thItem = th.eq(i);
            cols += self._addColumn($.isPlainObject(columns[i]) && columns[i].value ? columns[i].value : (typeof(columns[i])=='string' ? columns[i] : '') , thItem.data('params'), thItem.hasClass('selected'), $.isPlainObject(columns[i]) &&  columns[i].data ? columns[i].data : null );
        };
        tr.html(cols).appendTo(tbody).rightClick(function(e){
            if(self.settings.enableRowSelection) {
                var tr = $(this).addClass('selected');
                e.altKey || tr.siblings().removeClass('selected');
            }
        });
        if (bUpdate){
            this._updateRows();
        }
        return this;
    },
    
    /** 
    * add column with any params
    * next params are mandatory:  
    *          `field` (field name as in DB)
    *          `name`  (that what will be seen in the table header)
    *          `width` (column width)
    * bUpdate - if true , table width will be recalculated (is needed in case of adding new column)
    */
    addColumn  : function(params, bUpdate, callback){
        var self = this;
        var tr = this.header.find('thead tr');
        if ($.isPlainObject(params)) {
                var th  = $('<th/>')
                          .appendTo(tr)
                          .data('params',{}).attr({'rowspan':'1', 'colspan':'1'})
                          .html('<div style="position:relative"><div class="column-header" style="overflow:hidden;"><span class="column-title" style="overflow:hidden;display:block;width:100%;cursor:default;"></span></div></div>');
                var ch   = th.find('.column-header');
                var chPd = parseInt(ch.css('padding-left')) + parseInt(ch.css('padding-right'));
                var thData = th.data('params');
                if (!params.width) {
                    params.width = th.width();
                } 
                $.each(params, function(param, val) {
                     if (param == 'width') {
                         var w = val - chPd;
                         if (w < 0){
                             val = chPd
                         }    
                         ch.css('width', w > 0 ? w : 0);
                         th.css('width', val);
                         thData['cssWidth'] = val;
                         val = parseInt(val) + parseInt(th.css('border-right-width')) * 2 + parseInt(th.css('padding-left')) + parseInt(th.css('padding-right'));
                     }
                     if (param == 'id') {
                         th.attr('id','s-' + val);
                     }
                     if (param == 'name') {
                             ch.find('.column-title').html(val);
                     }
                     if (param == 'sortable' && val == 1 && self.settings.sortableRow) {
                         $('<span/>',{'class':'column-sort column-sort-idle','title':ucs.language.SORTING,'css':{'cursor':'pointer','position':'absolute','opacity':'0.3'}}).appendTo(th.find('.column-header'));
                     }
                     thData[param] = val;
                });
                th.data('params', thData);
                if (thData.field) {
                   self.body.find('tbody tr').append(self._addColumn('', thData));
                } else if (window.console && console.log) {
                    console.log('TH data doesn\'t contain param `field`');
                }
                if (bUpdate){
                    this._adjustTableWidth(); 
                    th.mousedown();
                }
        }
        return this;
    },
    
    removeColumn : function(index){
        if (typeof index == 'object') {
            index = index.prevUntil('tr').length;
        }
        this.header.find('th').eq(index).remove();
        this.body.find('tbody tr').quickEach(function(){
            this.find('td').eq(index).remove();
        });
        if ('\v'=='v') this._restoreHeadersWidth();
        this._adjustTableWidth();
    },
    
    updateColumn : function(th, params) {
        var self = this;
        if ($.isPlainObject(params)) {
            var tbody = this.body.find('tbody');
            var thead = this.header.find('tr');
            var index = th.parent('tr').find('th').index(th);
            if (!th.length) {
                window.console ? console.error('Can\t find column') : '';
            } else {
                var oldParams = th.data('params');
                $.each(params, function(param, val){
                     if (param == 'width') {
                         var ch   = th.find('.column-header');
                         var chPd = parseInt(ch.css('padding-left')) + parseInt(ch.css('padding-right'));
                         var w = val - chPd;
                         if (w < 0){
                             val = chPd;
                         }    
                         ch.css('width', w > 0 ? w : 0);
                         params[param] = parseInt(val) + parseInt(th.css('border-right-width')) * 2 + parseInt(th.css('padding-left')) + parseInt(th.css('padding-right'));
                         th.css('width', val);
                         params['cssWidth'] = val;
                     }
                     if (param == 'id') {
                         th.attr('id','s-' + val);
                     }
                     if (param == 'name') {
                         th.find('.column-title').html(val);
                     }
                     if (param == 'field') {
                         thead.find('th').quickEach(function(i){
                             if (i == index ) return;
                             var p = this.data('params');
                             if (p.field == val) {
                                 self.removeColumn(i);
                             }
                         });
                     }
                });
                th.data('params', $.extend(true, oldParams, params));
                if (params.width) {
                    var w = parseInt(params.width) - parseInt(this.dummyTD.css('padding-left'))- parseInt(this.dummyTD.css('padding-right')) - parseInt(this.dummyTD.css('border-right-width')) * 2; 
                    tbody.find('tr').quickEach(function(){
                        var cText = this.find('td').eq(index).find('.cText');
                        cText.css('width', w - parseInt(cText.css('padding-left')) - parseInt(cText.css('padding-right')) - parseInt(cText.css('border-right-width')) * 2);
                    });
                }
                this._adjustTableWidth();
            }
        }
    },
    
    pagination   : function(container) {
        if (!this.settings.perpage || isNaN(this.settings.perpage)) return;
        var nP = Math.ceil(this.settings.total/this.settings.perpage);
        var html = '<span class="perpage">1-'+ this.settings.perpage +'</span><span class="pgn-title">Page: <span>';
        if (this.settings.total > this.settings.perpage) {
            var p = this.settings.page;
            if (p != 1){
                if (p > 1) {
                    html += '<a class="first" rel="1">'+ucs.language.PAGE_FIRST+'</a>';
                }
                html += '<a class="p" rel="'+(p-1)+'">'+ucs.language.PAGE_PREVIOUS+'</a>';   
            }
            var i = 1;
            while (i <= nP && nP != 1) {
                if (i >= (p - 4) && i < (p + 5)) {
                    html += i == p ? '<a class="x" rel="'+i+'">'+i+'</a>' : '<a rel="'+i+'">'+i+'</a>';
                }
                i++;
            }
            if (p < nP) {
                html += '<a class="p" rel="'+(p+1)+'">'+ucs.language.PAGE_NEXT+'</a>'; 
            }
        }
        else {
          html += '<span>1</span>'
        }
        container.empty().html(html);
        return this;
    },
    
    getIndexByColumnField :  function(field) {
        var index = -1;
        this.header.find('th').quickEach(function(i){
            var params = this.data('params');
            if (params.field == field) {
                index = i;
                return;
            }
        });
        return index;  
    },
    
    setPerPage : function(callback){},
    
    search     : function(text, callback){
        var params = {};
        this.ajaxParams['data[search]'] = text;
        this.settings.page = 1;
        var pagination = $(this.settings.container).find('.pagination .first');
        pagination.length ? pagination.click() : this.redraw({},true);
        return this;
    },
    
    save       : function(params, callback) {
        var self = this;
        this._showOverlay();
        $.post(self.settings.ajaxSave, self._makeRequestParams('save', self.settings.id, params), function(data){self._hideOverlay(); if (callback && typeof callback == 'function') callback.call(this, data);}, 'json').error(function(){self._hideOverlay();});
    },
    
    remove     : function( id, text, params, callback) {
        var self = this;
        if (confirm(ucs.language.DELETE_WARN.replace('%s', text))) {
            this._showOverlay();
            $.post(self.settings.ajaxDelete, self._makeRequestParams('delete', id, params), function(data){self._hideOverlay(); if (callback && typeof callback == 'function') callback.call(this, data);}, 'json').error(function(){self._hideOverlay();});
        }
    },
    
    redraw     : function(params, p, callback) {
        var self = this;
        self._showOverlay();
        params = params && $.isPlainObject(params) ? params : {}; 
        var backupScrollPosLeft = this.body.parent().scrollLeft(); 
        var backupScrollPosTop = this.body.parent().scrollTop();  
        $.get(self.settings.ajaxGet + '?r=' + ucs.Helper.rand(1000,10000000), self._makeRequestParams('get', self.settings.id, $.extend({},self.ajaxParams, params)), function(data){
            if ($.isPlainObject(data)) {
                self.setRows(data);
                self._hideOverlay();
                self.body.parent().scrollLeft(backupScrollPosLeft);
                self.body.parent().scrollTop(backupScrollPosTop);
                if (p) {
                   self.pagination(self.body.parent().parent().find('.pagination'));
                } 
                typeof callback != 'function' || callback.call(self, data);
            }
        }, 'json').error(function(){self._hideOverlay();});
    },
    
    getLike    : function(){
       return this.settings.like; 
    },
    
    destroy    : function(){
        this.header.remove();
        this.body.remove();
        this.dummyTD.remove();
        delete this.settings;
        delete this.header;
        delete this.body;
        delete this.dummyTD;
        delete this.lock;
        delete this.ajaxParams;
    },
    
    
    /* ++++++++++++++++++++++ PROTECTED METHODS ++++++++++++++++++++++++++ */
    
    
    _addColumn    : function(val, params, selected, data){
        var w = '';
        var c = '';
        val = $.trim(val);
        if ( params.width != undefined) {
            w = 'width:' + (parseInt(params.width) - parseInt(this.dummyTD.css('padding-left'))  - parseInt(this.dummyTD.css('padding-right'))  - parseInt(this.dummyTD.css('border-right-width')) * 2)+ 'px;';
        }
        if (params.type == undefined) {
            params.type = 'text';
        }
        if (selected) {
            c = ' class="selected" ';
        }
        return '<td'+c+'><div class="cText' + (data && data.error && data.error != '' ? ' error' : '') + '" style="'+w+'overflow-x:hidden;padding-left:0px;padding-right:0px;padding-top:0px;padding-bottom:0px;margin-left:0;margin-right:0;border:0 dotted transparent" data-error="' + (data && data.error ? data.error.replace('"',"'").replace(/<|>/g, '') :'') + '" data-status="' + (data && data.status ? data.status.replace('"',"'").replace(/<|>/g, '') : '') + '" data-tip="' + (data && data.tip ? data.tip.replace('"', "'").replace(/<|>/g, '') : ''  ) + '" >'+(val == '' ? '&nbsp;' : val)+'</div></td>';
    },
    
     _updateRows : function(){
        var tr = this.body.find('tbody').find('tr').removeClass('odd').removeClass('even');
        tr.filter(':even').addClass('even');
        tr.filter(':odd').addClass('odd');  
    },
    
    _setEvents   : function(){
        var self = this;
        // TH
        this.header.on('hover', 'th', function(e){
           if (e.type == 'mouseenter') {
                $(this).addClass('hover').find('.column-sort').css('opacity',1);  
           } else if (e.type == 'mouseleave') {
                var cs = $(this).removeClass('hover').find('.column-sort');
                !cs.hasClass('column-sort-idle') || cs.css('opacity',0.3);  
           } 
        }).on('delete', 'th', function(e){
            if (!self.settings.thOnDelete.call(this, e, self)) return; 
        }).on('mousedown', 'th', function(e){
            if (self.settings.enableColumnSelection) {
                var th = $(this);
                var idx = th.prevUntil('tr').length;
                th.addClass('selected').siblings('th').removeClass('selected');
                self.body.find('tbody tr').quickEach(function(){
                    this.find('td').removeClass('selected').eq(idx).addClass('selected');
                });
            }
            return self.settings.thOnClick.call(this, e, self);
        }).on('click','span.column-sort', function(){
            var sortItem = $(this); 
            var th = sortItem.parents('th');
            var thData = th.data('params');   
            if (self.settings.sortField == thData.field) {
                self.settings.sortOrder = !(parseInt(self.settings.sortOrder)) ? 1 : 0;
                self.settings.sortOrder ? sortItem.removeClass('column-sort-down').addClass('column-sort-up').attr('title',ucs.language.SORTING_UP) : sortItem.removeClass('column-sort-up').addClass('column-sort-down').attr('title',ucs.language.SORTING_DOWN);
            } else {
                self.settings.sortField = thData.field;
                self.settings.sortOrder = 1;
                th.siblings().find('.column-sort').removeClass('column-sort-up').removeClass('column-sort-down').addClass('column-sort-idle').attr('title',ucs.language.SORTING);
                sortItem.removeClass('column-sort-idle').addClass('column-sort-up').attr('title',ucs.language.SORTING_UP);
            } 
            self._showOverlay(); 
            var backupScrollPosLeft = self.body.parent().scrollLeft(); 
            var backupScrollPosTop = self.body.parent().scrollTop();    
            $.get(self.settings.ajaxGet + '?r=' + ucs.Helper.rand(1000,10000000), self._makeRequestParams('get', self.settings.id, self.ajaxParams), function(data){
                if ($.isPlainObject(data)) {
                    self.setRows(data);
                    self._hideOverlay();
                    self.body.parent().scrollLeft(backupScrollPosLeft);
                    self.body.parent().scrollTop(backupScrollPosTop);
                }
            }, 'json').error(function(){self._hideOverlay();});
        }).on('dblclick', 'th', function(e){
            return self.settings.thOnDblClick.call(this, e, self);
        });;
        
        if (self.settings.editable) {
          this.header.on('edit', 'th', function(e){
              return self.settings.tdOnEdit.call(this, e, self);
          });
        } 
        // TD
        this.body.on('click','td', function(e){
            return self.settings.tdOnClick.call(this, e, self);
        }).on('hover', 'td', function(e){
            if (e.type == 'mouseenter') {
                $(this).addClass('hover'); 
            } else if (e.type == 'mouseleave') {
                $(this).removeClass('hover');
            } 
        }).on('dblclick', 'td', function(e){
            return self.settings.tdOnDblClick.call(this, e, self);
        });
        
        if (self.settings.editable) {
          this.body.on('edit', 'td', function(e){
              return self.settings.tdOnEdit.call(this, e, self);
          });
        }
        // TR
        this.body.on('hover', 'tbody tr', function(e){
            if (e.type == 'mouseenter') {
                $(this).addClass('hover'); 
            } else if (e.type == 'mouseleave') {
                $(this).removeClass('hover');
            } 
        }).on('delete', 'tbody tr', function(e){
            if (!self.settings.trOnDelete.call(this, e, self)) return; 
        });/*.on('click', 'tbody tr', function(e){
            if(self.settings.enableRowSelection) {
                var tr = $(this).addClass('selected');
                e.altKey || tr.siblings().removeClass('selected');
            }
        });*/
        
        // sortable
        this._makeHeaderSortable();
        
        this.events = true;  
    },
    
    _paginationEvents : function(pagination) {
        var self = this;
        pagination.on('click', 'a', function(e){
            var nav = $(this);
            self._showOverlay();
            $.get(self.settings.ajaxGet + '?r=' + ucs.Helper.rand(1000,10000000), self._makeRequestParams('get', self.settings.id, $.extend({} , self.ajaxParams, {'data[page]':nav.attr('rel')})), function(data){
                if ($.isPlainObject(data)) {
                    self.settings.total   = data.total ? data.total : self.settings.total;
                    self.settings.perpage = data.perpage ? data.perpage : 0;
                    self.settings.page    = data.page ? data.page : self.settings.page;
                    self.settings.id      = data.id ? data.id : self.settings.id;
                    self.setRows(data);
                    self.pagination($(self.settings.container).find('.pagination'));
                    self.settings.onPagination.call(self);
                    self._hideOverlay(); 
                }
            }, 'json').error(function(){self._hideOverlay();});
        });
    },
    
    _dynamicPageLoading : function(page) {
           var self = this; 
           if (self.lock) return;
           self.lock = true;
           self._showOverlay(); 
           $.get(self.settings.ajaxGet + '?r=' + ucs.Helper.rand(1000,10000000), self._makeRequestParams('get', self.settings.id, $.extend({} , self.ajaxParams, {'data[page]':page})), function(data){
                if ($.isPlainObject(data)) {
                    self.settings.total   = data.total ? data.total : self.settings.total;
                    self.settings.perpage = data.perpage ? data.perpage : 0;
                    self.settings.page    = data.page ? data.page : self.settings.page;
                    self.settings.id      = data.id ? data.id : self.settings.id;
                    self.setRows(data, true);
                    self.settings.onPagination.call(self);
                    self._hideOverlay(); 
                    self.lock = false;
                }
            }, 'json').error(function(){self._hideOverlay();});
    },
    
    _setHtml : function(html) {
        this.body.find('tbody').html(html);
    },
    
    _makeHeaderSortable : function(){
        var self = this;
        if (typeof $.prototype.sortable == 'function' && this.settings.sortableColumn) {
            this.header.find('tr').sortable({
                 tolerance  :   'pointer',
                 placeholder: "ui-state-highlight",
                 appendTo: self.header.parent().parent(),
                 helper     : 'clone',
                 cursor     : 'move',
                 axis       : 'x',  
                 start      : function(e, ui){
                    ui.item.data('oldIndex', ui.item.parent().find('th').index(ui.item));
                    var toptable = self.header.parent().parent().find('.table-top');
                    ui.helper.data('top', toptable.is(':visible')? toptable.height() : 0);
                    if ('\v'=='v') ui.helper.css('width', ui.item.data('params').cssWidth); 
                 },
                 sort       : function(e, ui){
                   var top  = ui.helper.data('top');
                   if (!top) { 
                       ui.helper.data('top', ui.position.top);
                       top = ui.position.top;  
                   }
                   ui.helper.css('top', top + 'px');
                 },
                 update    : function(e, ui){
                        var s = $(this).sortable("serialize");
                        var oI = ui.item.data('oldIndex');
                        var cI = ui.item.prevUntil('tr').length;
                        var length = self.header.find('th').length;
                        self.body.find('tr').quickEach(function(){
                            var tdO = this.find('td').eq(oI);
                            var tdC = this.find('td').eq(cI);
                            var clone = tdO.remove().clone();
                            cI >= oI ? clone.insertAfter(tdC) : clone.insertBefore(tdC);
                        });
                        $.post(self.settings.ajaxSort, self._makeRequestParams('sort', s, {}));
                 }     
            });
        }    
    },
    
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
                    'data[sort]'   : this.settings.sortField, 
                    'data[asc]'    : this.settings.sortOrder, 
                    'data[search]' : this.settings.search,
                    'data[group]'  : this.settings.groupField,
                    'data[in]'     : this.settings.likeFilter && $.isArray(this.settings.like) ? (this.settings.like.length == 0 ? 0 : this.settings.like.join(',')) : '', 
                    'data[page]'   : this.settings.page, 
                    'data[perpage]': this.settings.perpage
                }, data);
                params[this.settings.csrf_protection.name] = this.settings.csrf_protection.value;
                break;
            case 'sort':
                params = id +'&m=' + encodeURIComponent(this.settings.model) + '&' + this.settings.csrf_protection.name + '=' + encodeURIComponent(this.settings.csrf_protection.value);
                break;
            case 'save':
            case 'delete':
                params = $.extend({},{
                    'id':id, 
                    'm':this.settings.model
                }, data);
                params[this.settings.csrf_protection.name] = this.settings.csrf_protection.value;
                break;
        }
        return params;
    },
    
    _restoreHeadersWidth : function(){
         this.header.find('th').quickEach(function(){
            var params = this.data('params');
            this.css('width', params.cssWidth + 1);
        });
    },
    
    _adjustTableWidth : function(){
        var w = 0;
        this.header.find('th').quickEach(function(){
            var params = this.data('params');
            if (params.width != undefined) {
                w += parseInt(params.width);
            }
        });
        if (w) {
            this.header.css('width', w);
            this.body.css('width', w);
            this._alignVerticalSortButtons();
        }   
    },
    
    _getIndexColumn : function(td) {
        return td.parent('tr').find('td').index(td);
    },
    
    _alignVerticalSortButtons : function() {
        this.header.find('.column-header').quickEach(function(){
            var cH = this;
            var cS = cH.find('.column-sort');
            !cS.length || cS.css({'top': ((cH.outerHeight() - cS.height())/2) + 'px'});
        });
    },
    
    _showOverlay  :  function() {
       if (!this.settings.overlay) return; 
       var wrapper = this.header.parent().parent();
       var ovr = wrapper.find('.table-overlay').css({'width':wrapper.width(),'height':wrapper.height(),'left':0, 'top':0}).show();
       var ldr = $('<div/>',{'class':'ucs-table-loader','css':this.settings.css.loader}).css({'position':'absolute','z-index':ovr.css('z-index') + 1}).appendTo('body');
       var pos = ovr.offset();
       ldr.css({'left': pos.left + (ovr.width() - ldr.width())/2, 'top':pos.top + (ovr.height() - ldr.height())/2});
    },
    
    _hideOverlay  :  function() {
       if (!this.settings.overlay) return;  
       this.header.parent().parent().find('.table-overlay').hide();
       $('.ucs-table-loader').remove();
    },
    
    _cssToString : function(css){
        var str = '';
        if ($isPlainObject(css)) {
            for ( var i in css) {
                str += i + ':' + css[i] +';'
            }
            return str;
        } 
        return css;
    }
});
