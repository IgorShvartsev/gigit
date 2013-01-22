<form>
  <input type="hidden" name="action" value="address" /> 
  <h2>Postal code & Distance</h2> 
  <br /><br />
  <div class="item clearfix">
    <label>Postal code:</label>
    <p><input class="txt number required" type="text" name="data[zip]" value="<?=$band['zip'];?>" maxlength="8" /></p>
  </div> 
  
  <div class="item clearfix">
    <label>Approx Distance to leave:</label>
    <p><input class="txt number required" type="text" name="data[distance]" value="<?=$band['distance'];?>" maxlength="5" /></p>
  </div>   

  <div class="item clearfix">
    <label></label>
    <p>
        <button type="sumit" class="submit inline">Continue</button> or <span class="next" data-next="footprint">Skip this step</span>
    </p>
  </div>
  </form>
