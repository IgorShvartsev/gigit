
<link type="text/css" rel="stylesheet" href="<?=base_url('assets/css/ucs/calendar.css');?>" />
<div id="pcalendar"></div>

<script type="text/javascript">
        $(function(){
            var caln = new ucs.Calendar({
                name         : 'datepicker',
                id           : '<?=$band['id'];?>',
                model        : '<?=$modelCalendar;?>',
                ajaxGet      : '<?=base_url('band/ajax/getdata');?>',
                container    : '#pcalendar',
                shortDayName : true,
                css          : {wrapper : {'min-height' : '280px'}},
                onDateSelect : function(){
                    var item = $(this);
                    var day = item.data('day').replace(/(\d{4})-(\d{1,2})-(\d{1,2})/g, '$2-$3-$1');
                    $('input[name="<?=$inputName;?>"]').val(day);
                    $('#pcalendar').parents('.dialog').find('.close').click();
                }
            }).getMonth();
        })
</script>