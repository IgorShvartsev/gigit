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
        var tableId= link.parents('.data').find('.dataTable').attr('id');
        if (!tableId || tableId.indexOf('table-') == -1) {
            alert('Table should have attribute id beginning `table-` followed by db table name');
            return;
        }
        var model = tableId.replace('table-','');
        if (!chk.length) {
            alert('Nothing selected');
            return false;
        }
        switch(link.parent().attr('class')) {
            case 'tnew':
                break;
            case 'tactivate':
                $.post('data.php?req=mngactivate&m='+model, chk.serialize(), function(data){
                   if (data.error == undefined) {
                        $.each(data.result, function(i, val){
                            $('#scr-'+val).find('.MAct span').removeClass('inactive').text('active');
                        });
                        chkAll.removeAttr('checked').change();
                   } else {
                           alert(data.error);
                   }
                }, 'json');
                break;
            case 'tdeactivate':
                $.post('data.php?req=mngdeactivate&m='+model, chk.serialize(), function(data){
                   if (data.error == undefined) {
                        $.each(data.result, function(i, val){
                            $('#scr-'+val).find('.MAct span').addClass('inactive').text('inactive');
                        });
                        chkAll.removeAttr('checked').change();
                   } else {
                           alert(data.error);
                   }
                }, 'json');
                break;
            case 'tdelete':
                $.post('data.php?req=mngdelete&m='+model, chk.serialize(), function(data){
                   if (data.error == undefined) {
                        $.each(data.result, function(i, val){
                            $('#scr-'+val).remove();
                        });
                        chkAll.removeAttr('checked').change();
                        $.get(baseUrl, {}, function(data){ update(data) }, 'json');
                   } else {
                           alert(data.error);
                   }
                }, 'json');
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

    // search box
    $('.search button[name="search"]').click(function(){
        var queryString = {};
        $(this).parent().find('input').each(function(){
            var input = $(this);
            var type = input.attr('type');
            if ((type == 'text' || type == 'textarea')) {
                queryString[input.attr('name')] = input.val();
            }
            if (type == 'checkbox') {
                queryString[input.attr('name')] = input.is(':checked') ? 1 : 0;
            }
        });
        $.get('?req=mngsearch', queryString, function(data){ update(data) }, 'json');
        return false;
    });

    // pagination
    $('.pagination').delegate('a', 'click', function(){
        $.get($(this).attr('href'), {}, function(data){ update(data) }, 'json');
        return false;
    });

}

function update(data) {    
    if (typeof data == 'object') {        
        data.content == undefined || $('.dataTable tbody').empty().html(data.content);
        data.pagination == undefined || $('.pagination').empty().html(data.pagination);    
    } else {        
        alert(data);    
    }
}

function createOverlay(selector) {
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

function selectTargets(overlay, target)
{ 
    var ovr = overlay.getOverlay();
    switch(target) {
        case 'model':
            var indicator = ovr.find('.load-indicator').show();
            $.get('data.php?req=mnggettpl', {'t':'webcams'}, function(data) {
                indicator.hide();
                var div = $('<div/>').appendTo(ovr);
                div.html(data);
                div.find('button[name="cancel"]').click(function(){
                    ovr.find('.close').click();
                }).end().find('button[name="ok"]').click(function(){
                    
                });
            });
            break;
    }
    overlay.load();
    ovr.find('.close').css({'position':'absolute','top':'5px','right':'5px','cursor':'pointer','height':'35px','width':'35px','background-image':'url(images/close.png)'});
}