<section id="main">
    <div id="mainwrap" class="clearfix grad-3 brd-lr shdw-3">
        <div id="content">
             <?=partial('shared/menu', array('pagename' => 'settings'));?>
             <div id="account-dashboard" class="clearfix">
                <br />
                <form id="account-settings-form" method="post" action="<?=base_url('bands/settings');?>">
                      <div class="title"> 
                             Select how you would like to get paid.
                      </div>
                      <br />
                      <div class="item clearfix">
                            <label>Payment type:</label>                
                            <p> 
                                <input type="radio" name="type" value="address" tabindex="1" /> Send me a check to this address : <br /><br />
                                <input class="txt" type="text" name="data[street1]" value="" data="Street line 1" maxlength="40" size="40" tabindex="2" />
                            </p>
                      </div>
                      <div class="item clearfix">
                            <label></label>                
                            <p> <input class="txt" type="text" name="data[street2]" value="" data="Street line 2"  maxlength="40" size="40" tabindex="3" /></p>
                      </div>
                      <div class="item clearfix">
                            <label></label>                
                            <p>
                                <input class="txt" type="text" name="data[city]" value="" data="City"  maxlength="40" size="40" tabindex="4" />
                                <select name="data[state]" tabindex="5">
                                    <option value="">State</option>
                                </select>
                                <input class="txt" type="text" name="data[zip]" value=""  data="ZIP"  maxlength="10" size="10" tabindex="6" style="width:50px" /> 
                            </p>
                      </div>
                      <div style="height:20px"></div>
                      <div class="item clearfix">
                            <label></label>
                            <p>
                                <input type="radio" name="type" value="paypal" tabindex="7" /> Send me an instant payment through PayPal <span class="small">(PayPal will charge a 2.9% fee)</span> <br /><br />
                                <input class="txt" type="text" name="data[email]" value="" data="PayPal email address" maxlength="40" size="40" tabindex="8" />
                            </p>
                      </div>
                      <div class="item clearfix">
                            <label></label>
                            <p>
                                <button class="btn submit" tabindex="9">Save Changes</button>
                            </p>
                      </div>               
                </form>
             </div> 
        </div>
    </div>
</section>