
var url = window.url ? window.url : '/';

$(function(){ 
      init();
});

function init()
{   
    // tools
    $('.tools').find('.actions').delegate('a', 'click', function(){
        var link   = $(this);
        var chkAll = link.parents('ul').find('.tcheckbox input');
        var chk    = link.parents('.data').find('.dataTable input[name="chk[]"]:checked');
        var model  = link.parents('.data').find('.dataTable').data('model');
        var loader = link.parents('ul').find('.ajax-loader');
        if (model == undefined) {
            alert('Table html element should have attribute `data-model`');
            return;
        }
        var section  = link.parents('.data').find('.dataTable').data('section');
        if (section == undefined) {
            alert('Table html element should have attribute `data-section`');
            return;
        }
        if (!chk.length) {
            alert('Nothing selected');
            return false;
        }
        switch(link.parent().attr('class')) {

            case 'tactivate':
                loader.length ? loader.show() : '';
                $.post(url + 'admin/ajax/activate?m=' + model + '&s=' + section, chk.serialize(), function(data){
                   loader.length ? loader.hide() : ''; 
                   if (data.error == undefined) {
                        $.each(data.result, function(i, val){
                            $('#row-'+val).find('.MAct span').removeClass('inactive').text('active');
                        });
                        chkAll.removeAttr('checked').change();
                   } else {
                           alert(data.error);
                   }
                }, 'json');
                break;
                
            case 'tdeactivate':
                loader.length ? loader.show() : '';
                $.post(url + 'admin/ajax/deactivate?m=' + model + '&s=' + section, chk.serialize(), function(data){
                   loader.length ? loader.hide() : ''; 
                   if (data.error == undefined) {
                        $.each(data.result, function(i, val){
                            $('#row-'+val).find('.MAct span').addClass('inactive').text('inactive');
                        });
                        chkAll.removeAttr('checked').change();
                   } else {
                           alert(data.error);
                   }
                }, 'json');
                break;
                
            case 'tdelete':
                if (confirm('You are going to delete. Are you agree?')) {
                    loader.length ? loader.show() : '';
                    $.post(url + 'admin/ajax/delete?m=' + model + '&s=' + section, chk.serialize(), function(data){
                        loader.length ? loader.hide() : '';
                        if (data.error == undefined) {
                            $.each(data.result, function(i, val){
                                $('#row-'+val).remove();
                            });
                            chkAll.removeAttr('checked').change();
                            //$.get(baseUrl, {}, function(data){ update(data) }, 'json');
                        } else {
                            alert(data.error);
                        }
                    }, 'json');
                }
                break;
                
        }
        return false;
    }).end().find('.tcheckbox').delegate('input', 'change', function(){
        var chkAll = $(this);
        var chk = chkAll.parents('.data').find('.dataTable input[name="chk[]"]');
        chkAll.is(':checked') && chk.length ? chk.attr('checked','checked').parents('tr').addClass('checked') : chk.removeAttr('checked').parents('tr').removeClass('checked');
    }).end().parents('.data').find('.dataTable').delegate('input[name="chk[]"]', 'click', function(){
        $('.tcheckbox input').removeAttr('checked');
        var chk = $(this);
        chk.is(':checked') ? chk.parents('tr').addClass('checked') : chk.parents('tr').removeClass('checked');
    });
    
    // option tools
    $('.options').find('ul').on('click', 'a', function(){
        var link   = $(this);
        var model  = link.parents('.data').find('.dataTable').data('model');
        if (model == undefined) {
            alert('Table html element should have attribute `data-model`');
            return;
        }
        var section  = link.parents('.data').find('.dataTable').data('section');
        if (section == undefined) {
            alert('Table html element should have attribute `data-section`');
            return;
        }
        switch(link.parent().attr('class')) {
            
            case 'tnew':
                var queryString = {
                    'm' : model,
                    's' : section,
                    'id': 0
                };
                getEditForm(queryString, function(){
                    $.get(url + 'admin/ajax/getall', queryString, function(data){if(data.content){update(data)}}, 'json');
                });
                break;
        }
    })
    
    // table 
    var tbl = $('#table-list').on('click', '.edt', function(){
        var id = $(this).parents('tr').attr('id').split('-')[1];
        var queryString = {
            'm' : tbl.data('model'),
            's' : tbl.data('section'),
            'id': id
        };
        getEditForm(queryString);
    });
    
    // search box
    $('.search input[name="submit"]').click(function(){
        var queryString = {
            'm' : tbl.data('model'),
            's' : tbl.data('section'),
            'p' : 1
        };
        var input = $(this).parent().parent().find('input');
        queryString['data[search]'] = input.val()
        $.get(url + 'admin/ajax/getAll', queryString, function(data){ update(data) }, 'json');
        return false;
    });

    // pagination
    $('.pagination').delegate('a', 'click', function(){
        $.get($(this).attr('href'), {}, function(data){ update(data) }, 'json');
        return false;
    });
    
    // window
    $(window).keyup(function(e){
        if (e.which == 27) {
            $('.close-frame').click();
        }
    })

}


function update(data) 
{    
    if (typeof data == 'object') {        
        data.content == undefined || $('.dataTable tbody').empty().html(data.content);
        data.pagination == undefined || $('.pagination').empty().html(data.pagination);
        data.error == undefined || aler(data.error);    
    } else {        
        alert(data);    
    }
}


function getEditForm(queryString, callback)
{
     $.showOverlay($('#wrap-content'), '#fff', '0.5');
     $.get(url + 'admin/ajax/get', queryString, function(data){
             $.hideOverlay();
             if (data.error) {
                 alert(data.error);
                 return;
             }
             
             // frames
             var mF = $('#main-frame');
             var eF = $('#edit-frame');
             
             mF.hide();
             
             eF.css('position','relative').empty().show().html(data.content).append($('<div/>',{'class' : 'close-frame','title':'close','css':{'position':'absolute','top':'0','right': '0', 'width':'20px', 'height':'20px', 'cursor':'pointer', 'font-size':'20px'}}).text('×').click(function(){
                 eF.empty().hide();
                 mF.show();
             })).find('input[type="checkbox"]').click(function(){
                 var chk = $(this);
                 chk.siblings('input[name="data[' + chk.attr('name') + ']"]').val(chk.is(':checked') ? 1 : 0);
             }).end().find('form').validate({
                 errorPlacement: function(error, element) {
                    error.insertAfter(element);
                 },
                 submitHandler: function(frm) {
                     var frm = $(frm);
                     var loader = frm.find('.ajax-loader').show();
                     $.post(url + 'admin/ajax/save', frm.serialize(), function(data){
                         loader.hide();
                         if (data.error) {
                             alert(data.error)
                             return;
                         }
                         eF.find('.close-frame').click();
                         if (typeof callback == 'function') {
                             callback.call(frm, data);
                         } else {
                             mF.find('#row-' + data.result.id).replaceWith(data.content);
                         }
                     }, 'json');
                 }
             });
     }, 'json');    
}


function createOverlay(selector) 
{
   if ($('#'+selector).length) return; 
   var popup = $('<div/>',{'id': selector,css:{'display':'none','position':'absolute','width':'640px','padding':'35px','font-size':'12px','background-image':'url(images/ovrwhite.png)','height':'480px','z-index':'1000','overflow':'hidden'}}).appendTo('body');
   $('<img/>',{'class':'load-indicator','src':'images/loading.gif','css':{'border':'none','position':'absolute','bottom':'18px','left':'34px','display':'none'}}).appendTo(popup);
   if (typeof $.prototype.overlay == 'function'){
       var overlay = $('#'+selector).overlay({
            expose: {
                color: '#fff',
                loadSpeed: 200,
                opacity: 0.5
            },
            effect: 'apple', 
            api   : true,
            onClose: function(){
                this.getOverlay().find(':not(.close)').remove();
            }
        });
        return overlay;
   }else{
        alert('jquery.tools.min.js is not installed to make edit overlay possible');
        return false;;
   }
}

/* JQuery functions */

$.showOverlay = function(selector, color, opacity, zindex) {
    zindex = zindex == undefined ? 1500 : zindex;
    opacity = opacity > 1 ? 1 : opacity;
    $('#ovr-stels').remove();
    var ovr = $('<div/>', {
            'id' :'ovr-stels', 
            'css':{
                    'position':'absolute',
                    'width':'100%',
                    'height':'100%',
                    'backgroundColor':color,
                    'opacity':opacity,
                    'zIndex':zindex, 
                    'display':'none', 
                    'left' :0, 
                    'top':0}
            }
    ).appendTo(selector).show();
    var ldr = $('<div/>',{'class':'ovr-loader', 'css':{'background':'url('+url+'assets/admin/images/loading.gif) center center no-repeat','width':'50px','height':'50px'}}).css({'position':'absolute','z-index':ovr.css('z-index') + 1}).appendTo(ovr.parent());
    var pos = ovr.position();
    ldr.css({'left': pos.left + (ovr.width() - ldr.width())/2, 'top':pos.top + (ovr.height() - ldr.height())/2});
}

$.hideOverlay = function(){
    $('.ovr-loader').remove();
    $('#ovr-stels').fadeOut(200, function(){$(this).remove()});
}
