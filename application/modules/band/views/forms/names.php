<form>
  <input type="hidden" namr="action" value="names" /> 
  <h2>Welcome to Gigit</h2>
  Creating an amazing Gigit page is simple and takes only a few minutes!  
  <br /><br />
  <div class="item clearfix">
    <label>Band name:</label>
    <p><input class="txt" type="text" name="data[name]" value="<?=$band['name'];?>" /></p>
  </div> 
  
  <div class="item clearfix">
    <label>Short description:</label>
    <p><textarea name="data[description]"><?=$band['description'];?></textarea></p>
  </div>   

  <div class="item clearfix">
    <label></label>
    <p>
        <button type="button" class="submit inline">Continue</button> or <span class="next" data-next="address">Skip this step</span>
    </p>
  </div>
  
  </form>
<script type="text/javascript">
        $(function(){
            
        })
</script>