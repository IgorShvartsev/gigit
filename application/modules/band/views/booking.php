<section id="main">
    <div id="mainwrap" class="clearfix grad-3 brd-lr shdw-3">
        <div id="content">
            <form id="booking-form" method="post" action="<?=base_url('band/booking/' . $band['seo'] . '.html');?>" autocomplete="off">
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
                    <label>Date of gig <sup>*</sup> :</label>                
                    <p class="datewrap">
                        <input class="txt icon idate" type="text" name="data[gig_date]" value="<?=set_value('data[gig_date]', '');?>"  tabindex="1"   />
                        <small><?=form_error('data[gig_date]');?></small>
                    </p>
                </div>
                <div class="item clearfix">
                    <label>Start time <sup>*</sup> :</label>                
                    <p> 
                        <input class="txt" type="text" name="data[start_time]" value="<?=set_value('data[start_time]', '');?>"  tabindex="2"   />
                        <small><?=form_error('data[start_time]');?></small>
                    </p>
                </div>
                <div class="item clearfix">
                    <label>End time <sup>*</sup> :</label>                
                    <p> 
                        <input class="txt" type="text" name="data[end_time]" value="<?=set_value('data[end_time]', '');?>"  tabindex="3"  /> <span class="text-lbl">2 sets of 20 minutes</span>
                        <small><?=form_error('data[end_time]');?></small>
                        </p>
                </div>
                <div class="item clearfix">
                    <label>Venue address <sup>*</sup>:</label>                
                    <p> 
                        <input class="txt" type="text" name="data[street1]" value="<?=set_value('data[street1]', '');?>"  maxlength="40" size="40" tabindex="4"   />
                        <small><?=form_error('data[street1]');?></small>
                    </p>
                </div>
                <div class="item clearfix">
                    <label></label>                
                    <p> <input class="txt" type="text" name="data[street2]" value="<?=set_value('data[street2]', '');?>"  maxlength="40" size="40" tabindex="5"   /></p>
                </div>
                <div class="item clearfix">
                    <label>City <sup>*</sup>:</label>                
                    <p> 
                        <input class="txt" type="text" name="data[city]" value="<?=set_value('data[city]', '');?>"  maxlength="40" size="40" tabindex="6" />
                        <select name="data[state]">
                            <option value="">State</option>
                            <? foreach($states as $key=>$name) {?>
                            <option value="<?=$key?>" <?=set_select('data[state]', $key);?>><?=$name?></option>
                            <? } ?>
                        </select>
                        <input class="txt" type="text" name="data[zip]" value="<?=set_value('data[zip]', '');?>" data="ZIP" maxlength="10"  size="10" tabindex="7" style="width:50px" />
                        <small><?=form_error('data[city]');?></small>
                        <small><?=form_error('data[state]');?></small>
                        <small><?=form_error('data[zip]');?></small>
                    </p>
                </div>
                <div class="item clearfix">
                    <label>Venue type <sup>*</sup> :</label>
                    <p>
                        <input type="radio" name="data[venue_type]"  value="private" tabindex="8" <?=set_radio('data[venue_type]', 'private'); ?> /> <span class="radio-lbl">Private residence</span>
                        <input type="radio" name="data[venue_type]"  value="business" tabindex="9" <?=set_radio('data[venue_type]', 'business'); ?> /> <span class="radio-lbl">Business</span>
                        <small><?=form_error('data[venue_type]');?></small>
                    </p> 
                </div>
                <div class="item clearfix">
                    <label>Location <sup>*</sup> :</label>
                    <p>
                        <input type="radio" name="data[location]"  value="indoor"  tabindex="10"  <?=set_radio('data[location]', 'indoor'); ?> /> <span class="radio-lbl">Indoor</span>
                        <input type="radio" name="data[location]"  value="outdoor" tabindex="11" <?=set_radio('data[location]', 'outdoor'); ?> /> <span class="radio-lbl">Outdoor</span>
                        <small><?=form_error('data[location]');?></small>
                    </p> 
                </div>
                 <div class="item clearfix">
                    <label>Amplification request <sup>*</sup> :</label>
                    <p>
                        <input type="radio" name="data[amp_request]"  value="fully" tabindex="12" <?=set_radio('data[amp_request]', 'fully'); ?> /> <span class="radio-lbl">Fully amplified</span>
                        <input type="radio" name="data[amp_request]"  value="partially" tabindex="13" <?=set_radio('data[amp_request]', 'partially'); ?> /> <span class="radio-lbl">Partially amplified</span>
                        <input type="radio" name="data[amp_request]"  value="none" tabindex="14"  <?=set_radio('data[amp_request]', 'none'); ?> /> <span class="radio-lbl">None (acoustic)</span>
                        <small><?=form_error('data[amp_request]');?></small>
                    </p> 
                </div>
                <div class="item clearfix">
                    <label>Notes to band:</label>                
                    <p><textarea name="data[note]" tabindex="15" style="height:150px"><?=set_value('data[note]', '');?></textarea></p>
                </div>
                <div class="item clearfix">
                    <label>Questions from the band:</label>                
                    <p>
                        What's the occasion? <br />
                        <textarea name="data[answer1]" tabindex="16" style="height:80px"><?=set_value('data[answer1]', '');?></textarea> <span class="text-lbl">please answer the question</span><br />
                        Tell me a little about the audience.<br />
                        <textarea name="data[answer2]" tabindex="17" style="height:80px"><?=set_value('data[answer2]', '');?></textarea> <span class="text-lbl">please answer the question</span><br />
                    </p>
                </div>
                <div class="item clearfix">
                    <label>Terms of use:</label> 
                    <p>
                        <input type="checkbox" name="termofuse" value="1" tabindex="18" <?=isset($_POST['termofuse']) ? 'checked="checked"' : '';?> /> <span class="radio-lbl">By checking this box, I agree to the <a class="link2" href="<?=base_url('terms-of-use.html');?>" target="_blank">Terms of Use</a></span>                    
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
<script type="text/javascript" src="<?=base_url('assets/js/jquery/jquery.timeentry.js')?>"></script>
<script type="text/javascript">
    $(function(){
        var frm = $('#booking-form').submit(function(){
            if (!$(this).find('input[name="termofuse"]').is(':checked')) {
                return false;
            }
            $('input, textarea, select', frm).each(function(){
                var el = $(this);
                if (el.attr('data') != 'udefined' && el.attr('data') == el.val()) {
                     el.val('');
                } 
            }) 
        });
        var sbm = frm.find('.submit');
        var chk = frm.find('input[name="termofuse"]').bind('set', function(){
            this.checked ? sbm.removeClass('disabled') : sbm.addClass('disabled');
        }).click(function(){
            $(this).trigger('set');
        }).trigger('set');
        
        $('input[name="data[gig_date]"]').focus(function(){
            $.get('<?=base_url('band/ajax/getdatepicker')?>', {'id':'<?=$band['id']?>' , 'iname' : 'data[gig_date]'}, function(html){
                 popupDlg.dlg.css('width','440px').find('.close').css({'visibility':'visible', 'background': '#fff'});
                 popupDlg.show(html);
            })
            return false;
        }).bind('keydown', function(){return false;});
        
        $('input[name="data[start_time]"],input[name="data[end_time]"]').timeEntry();
        
        $(window).keyup(function(e){
            if (e.which == 27) {
                $('.dialog .close').trigger('click');
            }
        });
    })
</script>