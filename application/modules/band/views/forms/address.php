<form>
  <input type="hidden" namr="action" value="address" /> 
  <h2>Postal code & Distance</h2> 
  <br /><br />
  <div class="item clearfix">
    <label>Postal code:</label>
    <p><input class="txt" type="text" name="data[zip]" value="<?=$band['zip'];?>" /></p>
  </div> 
  
  <div class="item clearfix">
    <label>Approx Distance to leave:</label>
    <p><input class="txt" type="text" name="data[distance]" value="<?=$band['distance'];?>" /></p>
  </div>   

  <div class="item clearfix">
    <label></label>
    <p>
        <button type="button" class="submit inline">Continue</button> or <span class="next" data-next="footprint">Skip this step</span>
    </p>
  </div>
  </form>
