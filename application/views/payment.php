<section id="main">
    <div id="mainwrap" class="clearfix grad-3 brd-lr shdw-3">
        <div id="content">
             <form id="payment-form" method="post" action="<?=base_url('payment');?>" autocomplete="off">
                
                <div class="top">
                Your card will only be authorized for the booking fee now. The booking fee will be charged when
the band conﬁrms the gig.
                </div>
                
                <div class="item clearfix">
                    <label>Credit card number:</label>                
                    <p>
                        <input class="txt required creditcard" type="text" name="data[number]" value="<?=$creditcard ? $creditcard['number'] : '';?>"  tabindex="1" />
                    </p>
                </div>
                
                <div class="item clearfix">
                    <label>Expiration date:</label>                
                    <p>
                        <select name="data[expire_month]" class="required" tabindex="2">
                            <option value="">Month</option>
                            <? for($i = 1; $i <= 12; $i++) {?>
                                <option value="<?=$i > 9 ? $i : ('0' . $i);?>" <?=$creditcard && $i == (int)$creditcard['expire_month'] ? 'selected="selected"' : '';?> ><?=$i > 9 ? $i : ('0' . $i);?></option>
                            <? } ?>
                        </select>
                        <select name="data[expire_year]" class="required" tabindex="3">
                            <option value="">Year</option>
                            <? for($i = 2013; $i <= 2035; $i++) {?>
                                <option value="<?=$i;?>" <?=$creditcard && $i == (int)$creditcard['expire_year'] ? 'selected="selected"' : '';?> ><?=$i;?></option>
                            <? } ?>
                        </select>
                    </p>
                </div>
                
                <div class="item clearfix">
                    <label>CC Code number:</label>                
                    <p> <input class="txt required digits" type="text" name="data[code]"  value="<?=$creditcard ? $creditcard['code'] : '';?>"  tabindex="4" style="width:60px"  /></p>
                </div>
                
                <div class="item clearfix">
                    <label>Full name:</label>                
                    <p> <input class="txt required" type="text" name="data[fullname]" value="<?=$creditcard ? $creditcard['fullname'] : '';?>"  maxlength="40" size="40" tabindex="5"   /></p>
                </div>
                
                <div class="item clearfix">
                    <label>Billing address:</label>                
                    <p> <input class="txt" type="text" name="data[street1]" value="<?=$creditcard ? $creditcard['street1'] : '';?>" data="Street line 1" maxlength="40" size="40" tabindex="6"   /></p>
                </div>
                
                <div class="item clearfix">
                    <label></label>                
                    <p> <input class="txt" type="text" name="data[street2]" value="<?=$creditcard ? $creditcard['street2'] : '';?>" data="Street line 2"  maxlength="40" size="40" tabindex="7"   /></p>
                </div>
                
                <div class="item clearfix">
                    <label></label>                
                    <p>
                        <input class="txt" type="text" name="data[city]" value="<?=$creditcard ? $creditcard['city'] : '';?>" data="City"  maxlength="40" size="40" tabindex="8" />
                        <select name="data[billing_address][state]" tabindex="9">
                            <option value="">State</option>
                            <? foreach($states as $key=>$name) {?>
                            <option value="<?=$key?>" <?=$creditcard && $key == (int)$creditcard['state'] ? 'selected="selected"' : '';?> ><?=$name?></option>
                            <? } ?>
                        </select>
                        <input class="txt" type="text" name="data[zip]" value="<?=$creditcard ? $creditcard['zip'] : '';?>"  data="ZIP"  maxlength="10" size="10" tabindex="10" style="width:50px" /> 
                    </p>
                </div>
                
                <div class="item clearfix">
                    <label>Phone number:</label>                
                    <p> <input class="txt" type="text" name="data[phone]" value="<?=$creditcard ? $creditcard['phone'] : '';?>"  maxlength="40" size="40" tabindex="11"   /></p>
                </div>
                
                <div class="item clearfix">
                    <label></label>
                    <p>
                        <button class="btn submit" tabindex="12">Finalize Your Gig</button>
                    </p>
                </div>               
             </form>
        </div>
    </div>
</section>
<script type="text/javascript" src="<?=base_url('assets/js/jquery/jquery.validate.js')?>"></script>
<script type="text/javascript">
    $(function(){
        $.validator.messages.required = 'is required';
        $('#payment-form').validate({
             errorElement: 'em',
             errorPlacement: function(error, el) {
                    if (el.attr('name') == 'data[expire_month]' || el.attr('name') == 'data[expire_year]') {
                        el.siblings('em.error').remove().end().parent().append(error);
                        return;   
                    }
                    error.insertAfter(el);
             },
             submitHandler: function(frm) {
                 $('input, textarea, select', frm).each(function(){
                    var el = $(this);
                    if (el.attr('data') != undefined && el.attr('data') == el.val()) {
                        el.val('');
                    } 
                 })
                 frm.submit(); 
             }
        });
    })
</script>