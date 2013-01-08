<form>
  <input type="hidden" namr="action" value="photos" /> 
  <h2>Photos</h2>
  <br />
  
  <div class="item clearfix">
    <label>Facebook :</label>
    <p><a href="#"><img src="<?=base_url('assets/images/' .get_theme().'/fb-connect.gif');?>" alt="fb" /></a></p>
  </div> 
  
  <div class="item clearfix">
    <label>Instagram username:</label>
    <p><input class="txt" type="text" name="data[name_instagram]" value="" /></p>
  </div>   

  <div class="item clearfix">
    <label></label>
    <p>
        <button type="button" class="submit inline">Continue</button> or <span class="next" data-next="twitter">Skip this step</span>
    </p>
  </div>
  
  </form>
<script type="text/javascript">
        //alert('Ups');
</script>