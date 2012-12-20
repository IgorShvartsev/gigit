var promtDlg = null;
var popupDlg = null;
$(function(){
    
    // promt dialog
    promtDlg = new ucs.Promt({
        name: 'promt',
        overlay : {use :true}
    }, function(dlg){
        dlg.find('.close').click();
    });
    
    // popup dialog
    popupDlg = new ucs.Popup({
        name : 'popup',
        overlay : {use :true},
        draggable : false     
    }, function(dlg){
        dlg.find('.close').click();
    });
    
    $(".date").datepicker({dateFormat: 'mm/dd/yy'});
    $('input[type="text"], textarea').focus(function(){
        var el = $(this);
        if (el.attr('data') && el.attr('data') == el.val()) {
            el.val('');
        }
    }).blur(function(){
        var el = $(this);
        if (el.attr('data') && $.trim(el.val()) == '') {
           el.val(el.attr('data')); 
        }
    }).each(function(){
        var el = $(this);
        if (el.attr('data') && $.trim(el.val()) == '') {
           el.val(el.attr('data')); 
        }
    }); 
     
    
});

