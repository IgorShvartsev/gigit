<section id="main">
    <div id="mainwrap" class="clearfix grad-3 brd-lr shdw-3">
        <div id="content">
             <form id="payment-form" method="post" action="<?=base_url('payment');?>">
                
                <div class="top">
                Your card will only be authorized for the booking fee now. The booking fee will be charged when
the band conﬁrms the gig.
                </div>
                
                <div class="item clearfix">
                    <label>Credit card number:</label>                
                    <p>
                        <input class="txt" type="text" name="data[card_number]" value=""  tabindex="1"   />
                    </p>
                </div>
                <div class="item clearfix">
                    <label>Expiration date:</label>                
                    <p>
                        <select name="data[expire_month]" tabindex="2">
                            <option value="">Month</option>
                        </select>
                        <select name="data[expire_year]" tabindex="3">
                            <option value="">Year</option>
                        </select>
                    </p>
                </div>
                <div class="item clearfix">
                    <label>CC Code number:</label>                
                    <p> <input class="txt" type="text" name="data[code_number]" value=""  tabindex="4" style="width:60px"  /></p>
                </div>
                <div class="item clearfix">
                    <label>Full name:</label>                
                    <p> <input class="txt" type="text" name="data[full_name]" value=""  maxlength="40" size="40" tabindex="5"   /></p>
                </div>
                <div class="item clearfix">
                    <label>Billing address:</label>                
                    <p> <input class="txt" type="text" name="data[billing_address][street1]" value="" data="Street line 1" maxlength="40" size="40" tabindex="6"   /></p>
                </div>
                <div class="item clearfix">
                    <label></label>                
                    <p> <input class="txt" type="text" name="data[billing_address][street2]" value="" data="Street line 2"  maxlength="40" size="40" tabindex="7"   /></p>
                </div>
                <div class="item clearfix">
                    <label></label>                
                    <p>
                        <input class="txt" type="text" name="data[billing_address][city]" value="" data="City"  maxlength="40" size="40" tabindex="8" />
                        <select name="data[billing_address][state]" tabindex="9">
                            <option value="">State</option>
                        </select>
                        <input class="txt" type="text" name="data[billing_address][zip]" value=""  data="ZIP"  maxlength="10" size="10" tabindex="10" style="width:50px" /> 
                    </p>
                </div>
                <div class="item clearfix">
                    <label>Phone number:</label>                
                    <p> <input class="txt" type="text" name="data[phone_number]" value=""  maxlength="40" size="40" tabindex="11"   /></p>
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