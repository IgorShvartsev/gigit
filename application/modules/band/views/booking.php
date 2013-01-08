<section id="main">
    <div id="mainwrap" class="clearfix grad-3 brd-lr shdw-3">
        <div id="content">
            <form id="booking-form" method="post" action="<?=base_url('band/booking/' . $band['seo'] . '.html');?>">
                <input type="hidden" name="data[band_id]" value="<?=$band['id'];?>" />
                <div class="item clearfix">
                    <div class="label">
                        <div class="thumb2 right">
                            <a href="<?=base_url() . 'band/' . $band['seo'] . '.html';?>"><? if (isset($band['photo'])) {?><img src="<?=$band['photo'];?>"  alt="" /><? } ?></a>
                        </div>
                    </div>
                    <p>
                        <span class="name"><?=$band['name']?></span><br /><br />
                        <span class="amount">$<?=price_view($band['price']);?></span><br/>
                        two 20 minutes sets
                    </p>
                </div>
                <div class="item clearfix">
                    <label>Date of gig:</label>                
                    <p class="datewrap">
                        <input class="txt date icon idate" type="text" name="data[gig_date]" value=""  tabindex="1"   />
                    </p>
                </div>
                <div class="item clearfix">
                    <label>Start time:</label>                
                    <p> <input class="txt" type="text" name="data[start_time]" value=""  tabindex="2"   /></p>
                </div>
                <div class="item clearfix">
                    <label>End time:</label>                
                    <p> <input class="txt" type="text" name="data[end_time]" value=""  tabindex="3"  /> <span class="text-lbl">2 sets of 20 minutes</span></p>
                </div>
                <div class="item clearfix">
                    <label>Venue address:</label>                
                    <p> <input class="txt" type="text" name="data[street1]" value=""  maxlength="40" size="40" tabindex="4"   /></p>
                </div>
                <div class="item clearfix">
                    <label></label>                
                    <p> <input class="txt" type="text" name="data[street2]" value=""  maxlength="40" size="40" tabindex="5"   /></p>
                </div>
                <div class="item clearfix">
                    <label>City:</label>                
                    <p> 
                        <input class="txt" type="text" name="data[city]" value=""  maxlength="40" size="40" tabindex="6" />
                        <select name="state">
                            <option value="">State</option>
                        </select>
                        <input class="txt" type="text" name="data[zip]" value=""  maxlength="10" size="10" tabindex="7" style="width:50px" />
                    </p>
                </div>
                <div class="item clearfix">
                    <label>Venue type:</label>
                    <p>
                        <input type="radio" name="data[venue_type]"  value="private" tabindex="8" /> <span class="radio-lbl">Private residence</span>
                        <input type="radio" name="data[venue_type]"  value="business" tabindex="9" /> <span class="radio-lbl">Business</span>
                    </p> 
                </div>
                <div class="item clearfix">
                    <label>Location:</label>
                    <p>
                        <input type="radio" name="data[loction]"  value="indoor"  tabindex="10" /> <span class="radio-lbl">Indoor</span>
                        <input type="radio" name="data[location]"  value="outdoor" tabindex="11" /> <span class="radio-lbl">Outdoor</span>
                    </p> 
                </div>
                 <div class="item clearfix">
                    <label>Amplification request:</label>
                    <p>
                        <input type="radio" name="data[amp_request]"  value="fully" tabindex="12" /> <span class="radio-lbl">Fully amplified</span>
                        <input type="radio" name="data[amp_request]"  value="partially" tabindex="13" /> <span class="radio-lbl">Partially amplified</span>
                        <input type="radio" name="data[amp_request]"  value="none" tabindex="14" /> <span class="radio-lbl">None (acoustic)</span>
                    </p> 
                </div>
                <div class="item clearfix">
                    <label>Notes to band:</label>                
                    <p><textarea name="data[note]" tabindex="15" style="height:150px"></textarea></p>
                </div>
                <div class="item clearfix">
                    <label>Questions from the band:</label>                
                    <p>
                        What's the occasion? <br />
                        <textarea name="data[answer1]" tabindex="16" style="height:80px"></textarea> <span class="text-lbl">please answer the question</span><br />
                        Tell me a little about the audience.<br />
                        <textarea name="data[answer1]" tabindex="17" style="height:80px"></textarea> <span class="text-lbl">please answer the question</span><br />
                    </p>
                </div>
                <div class="item clearfix">
                    <label>Terms of use:</label> 
                    <p>
                        <input type="checkbox" name="termofuse" value="1" tabindex="18" /> <span class="radio-lbl">By checking this box, I agree to the <a class="link2" href="<?=base_url('terms-of-use.html');?>" target="_blank">Terms of Use</a></span>                    
                    </p>
                 </div>
                 <div class="item clearfix">
                    <label></label>
                    <p>
                        <button class="btn submit disabled" tabindex="19">Continue to Payment</button>
                    </p>
                 </div>               
            </form>
        </div>
    </div>
</section>
<script type="text/javascript">
    $(function(){
        var frm = $('#booking-form').submit(function(){
            if (!$(this).find('input[name="termofuse"]').is(':checked')) {
                return false;
            } 
        });
        var sbm = frm.find('.submit');
        var chk = frm.find('input[name="termofuse"]').bind('set', function(){
            this.checked ? sbm.removeClass('disabled') : sbm.addClass('disabled');
        }).click(function(){
            $(this).trigger('set');
        }).trigger('set');
  
    })
</script>