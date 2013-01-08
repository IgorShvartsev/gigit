
$(function(){
    if (!$('link[href$="ucs/calendar.css"]').length) {
        $('<link/>',{'rel':'stylesheet', 'type':'text/css', 'href':'<?=base_url('assets/css/ucs/calendar.css')?>'}).appendTo($('head'));
    }
    var cal = new ucs.Calendar({
        name         : '<?=isset($calendar) ? $calendar['name'] : 'busy';?>',
        id           : '<?=$id;?>',
        model        : '<?=$model;?>',
        ajaxGet      : '<?=base_url('band/ajax/getdata');?>',
        ajaxSave     : '<?=base_url('band/ajax/save');?>',
        container    : '<?=isset($calendar) ? $calendar['container'] : '#ucs-calendar';?>',
        shortDayName :  <?=isset($calendar) && isset($calendar['longName']) ? 'false' : 'true';?>,
        <? if (isset($calendar) && $calendar['editmode']) { ?>
        editMode     : true,
        onEdit       : function(){
            var item = $(this);
            var active = item.hasClass('unavailable') ? 0 : 1;
            var params = {
                'data[active]'   : active,
                'data[busy_date]' : item.data('day')
            };
            cal.update(params, function(data){
                if (data.result) {
                    active ? item.addClass('unavailable').find('.st').text('unavailable') : item.removeClass('unavailable').find('.st').text('available'); 
                    return;
                }
                alert(data.error)
            });
        },
        <? } ?>
        css          : {wrapper : {'min-height' : '280px'}}
    }).getMonth();
})