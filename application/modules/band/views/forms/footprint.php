<form>
  <input type="hidden" name="action" value="footprint" />   
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
                    $temp[] = '<td><input type="checkbox" name="data[genres][]" value="' . $genre['id'] . '" '  . (in_array($genre['name'], $band['genres']) ? 'checked="checked"' : '') . ' /> ' . $genre['name'] . '</td>';
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
    <p><input type="text" class="txt" name="data[tags]" value="<?=implode(',', $band['tags']);?>" /></p>
  </div>   

  <div class="item clearfix">
    <label></label>
    <p>
        <button type="submit" class="submit inline">Continue</button> or <span class="next" data-next="tracks">Skip this step</span>
    </p>
  </div>
  
  </form>
  
  <script type="text/javascript">
    $(function(){
        $('input[name="data[tags]"]').autocomplete({
            source : function(request, response){
                $.getJSON('<?=base_url('band/ajax/autocomplete/tags')?>',{
                    term: extractLast( request.term )
                }, response );
            },
            search : function() {
                var term = extractLast( this.value );
                if ( term.length < 2 ) {
                    return false;
                }
            },
            focus : function() {
                // prevent value inserted on focus
                return false;
            },
            select : function( event, ui ) {
                  var terms = split( this.value );
                  terms.pop();
                  terms.push( ui.item.value );
                  terms.push( "" );
                  this.value = terms.join( ", " );
                  return false;
            }
        }).bind('keyup', function(e){
            if (e.which == $.ui.keyCode.TAB && $(this).data('autocomplete').menu.active) {
                return false;
            }
        });
        
        function split( val ) {
            return val.split( /,\s*/ );
        }
        
        function extractLast( term ) {
            return split( term ).pop();
        }
    })
  </script>