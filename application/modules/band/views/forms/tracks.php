<form>
  <input type="hidden" namr="action" value="tracks" /> 
  <h2>Your tracks</h2> 
  <br />
  <div class="item clearfix">
    <label>Import from Soundcloud:</label>
    <p><input type="text" class="txt" name="data['tags']" value="" /></p>
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
        <button type="button" class="submit inline">Continue</button> or <span class="next" data-next="video">Skip this step</span>
    </p>
  </div>
  
  </form>
<script type="text/javascript">
        //alert('Ups');
</script>