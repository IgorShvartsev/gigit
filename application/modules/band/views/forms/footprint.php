<form>
  <input type="hidden" namr="action" value="footprint" />   
  <h2>Your Sound Footprint</h2> 
  <br />
  <div class="item clearfix">
    <label>Genre:</label>
    <span class="promt">Check all you apply</span> 
    <table>
            <?
                $numCol = 4;
                $i = 0;
                $temp = array();
                foreach($genres as $genre) {
                    if ($i++ == $numCol) {
                        echo '<tr>' . implode(" ", $temp) . '</tr>';
                        $temp = array();
                        $i = 1; 
                    }
                    $temp[] = '<td><input type="checkbox" name="genre[]" value="' . $genre['id'] . '" /> ' . $genre['name'] . '</td>';
                }
                $count = count($temp);
                if ($count > 0) {;
                    if ( $count < $numCol) {
                        for ($i = $count; $i <= $numCol; $i++) {
                            $temp[] = "<td></td>";
                        }
                    }
                    echo '<tr>' . implode("\n", $temp) . '</tr>';
                } 
            ?> 
    </table>
  </div> 
  
  <div class="item clearfix">
    <label>Bands you resemble:</label>
    <p><input type="text" class="txt" name="data['tags']" value="" /></p>
  </div>   

  <div class="item clearfix">
    <label></label>
    <p>
        <button type="button" class="submit inline">Continue</button> or <span class="next" data-next="tracks">Skip this step</span>
    </p>
  </div>
  
  </form>
<script type="text/javascript">
        //alert('Ups');
</script>