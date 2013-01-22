
<form id="form-tracks" method="post" action="<?=base_url('band/ajax/submitform')?>" enctype="multipart/form-data">
  <input type="hidden" name="data[x]" value="1" />
  <input type="hidden" name="action" value="tracks" /> 
  <h2>Your tracks</h2> 
  <br />
  <div class="item clearfix">
    <label>Import from Soundcloud:</label>
    <p>
    <? if (isset($soundcloud)) {?>
        <img id="soundclaud-loader" src="<?=base_url('assets/images/'.THEME.'/loader-20x20.gif')?>" alt="ldr" style="display:none" />
        <div class="soundcloud track-list">
        </div>
    <? } else {?>
    <a href="<?=base_url('authsocials/provider/soundcloud?redirect=band/profile;tracks');?>" onclick="window.open(this.href, 'auth', 'width=600,height=400'); return false;"><img src="<?=base_url('assets/images/'.THEME.'/soundcloud.png');?>" alt="snd" /></a>
    <? } ?>
    </p>
  </div>
  
  <div class="item clearfix">
    <label></label>
    <p>OR</p>
  </div>   
  
  <div class="item clearfix">
    <label>Upload MP3:</label>
    <p><input type="file" class="txt" name="file[]" value="" /></p>
  </div>
  
  <div class="item clearfix">
    <label></label>
    <p><input type="file" class="txt" name="file[]" value="" /></p>
  </div>
  
  <div class="item clearfix">
    <label></label>
    <p><input type="file" class="txt" name="file[]" value="" /></p>
  </div>
  
  <div class="item clearfix">
    <label></label>
    <p>
        <button type="submit" class="submit inline">Continue</button> or <span class="next" data-next="video">Skip this step</span>
    </p>
  </div>
  
  </form>
<script type="text/javascript" src="<?=base_url('assets/js/jquery/jquery.form.js');?>"></script> 
<script type="text/javascript">
  <? if (isset($soundcloud)) { ?>
     $(function(){
         var tracklist = $('.track-list');
         
         $('#soundclaud-loader').show();
         
         $.get('<?=base_url('socials/soundcloud/gettracks');?>', {}, function(data){
             $('#soundclaud-loader').hide();
             if (data.error) {
                 location.reload();
                 return;
             }
             if (!data.result.length) {
                 tracklist.html('<div class="warn">You have no public tracks in your soundcloud account.</div>');
                 return;
             }
             $('<h4>Your track list <i>(select any 3 tracks)</i></h4>').appendTo(tracklist);
             $.each(data.result, function(i, item){
                 $('<input type="checkbox" name="data[track][]" value="' + item.value + '" ' + (item.checked ? 'checked="checked"' : '' ) + ' /> <span>' + item.title + '</span> <br />').appendTo(tracklist);
             });
         }, 'json');
         
         $('#form-tracks').ajaxForm({
             dataType : 'json',
             success  : function(data) {
                 data.result ? popupDlg.dlg.find('.next').click() : alert(data.error);
             }
         });
     }) 
  <? } ?>
</script>