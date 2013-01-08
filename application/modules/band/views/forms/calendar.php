<form>
  <input type="hidden" namr="action" value="calendar" /> 
  <h2>Your Availability Calendar</h2>
  <br />
  Click any days you will not be available. You can update it from your dashboard.
  <br /> <br />
  
  <div class="item clearfix">
    <div id="pcalendar"></div>
  </div>   

  <div class="item clearfix">
    <label></label>
    <p>
        <button type="button" class="submit inline">Continue</button> or <span class="next" data-next="price">Skip this step</span>
    </p>
  </div>
  
  </form>
<script type="text/javascript">
        $(function(){
            var caln = new ucs.Calendar({
                name         : 'profile',
                id           : '<?=$band['id'];?>',
                model        : '<?=$modelCalendar;?>',
                ajaxGet      : '<?=base_url('band/ajax/getdata');?>',
                ajaxSave     : '<?=base_url('band/ajax/save');?>',
                container    : '#pcalendar',
                shortDayName : true,
                editMode     : true,
                onEdit       : function(){
                    var item = $(this);
                    var active = item.hasClass('unavailable') ? 0 : 1;
                    var params = {
                        'data[active]'   : active,
                        'data[busy_date]' : item.data('day'),
                    };
                    caln.update(params, function(data){  
                        if (data.result) {
                            active ? item.addClass('unavailable').find('.st').text('unavailable') : item.removeClass('unavailable').find('.st').text('available'); 
                            return;
                        }
                        alert(data.error)
                    });
                },
                css          : {wrapper : {'min-height' : '280px'}}
            }).getMonth();
        })
</script>