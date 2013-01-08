<form id="editform">
    
    <input type="hidden" name="id" value="<?=isset($id) ? $id : '';?>" />
    <input type="hidden" name="m"  value="<?=$model;?>" />
    <input type="hidden" name="s"  value="<?=$section?>" />
    
    <h2><?=isset($name) ? $name : '';?></h2>
    
    <div class="item">
        <label></label>
        <div class="p">
            <div class="thumb">
                <a href="<?=isset($seo) ? (base_url() . 'band/' . $seo . '.html') : '#';?>" target="_blank"><? if (isset($photo)) {?><img src="<?=$photo;?>"  alt="" /><? } else {?><img src="<?=base_url('assets/admin/images/nophoto.png');?>" alt="" /><? } ?></a>
            </div>
        </div>
    </div>
    
    <div class="item">
        <label>Active</label>
        <p>
            <input type="hidden" name="data[active]" value="<?=isset($active) ? $active : 0;?>" />
            <input type="checkbox" name="active" value="1" <?=isset($active) ? ($active ? 'checked="checked"' : '') : '';?> />
        </p>
    </div>
    
    <div class="item">
        <label>Featured</label>
        <p>
            <input type="hidden" name="data[featured]" value="<?=isset($featured) ? $featured : 0;?>" />
            <input type="checkbox" name="featured" value="1" <?=isset($featured) ? ($featured ? 'checked="checked"' : '') :'';?> />
        </p>
    </div>
    
    <div class="item">
        <label>Name</label>
        <p><input type="text" class="txt required" name="data[name]" value="<?=isset($name) ? $name : '';?>"  maxlength="60"/></p>
    </div>
    
    <div class="item">
        <label>Email</label>
        <p><input type="text" class="txt required email" name="data[email]" value="<?=isset($email) ? $email : '';?>" /></p>
    </div>
    
    <div class="item">
        <label>Seo</label>
        <p><input type="text" class="txt" name="data[seo]" value="<?=isset($seo) ? $seo : '';?>" maxlength="60" /></p>
    </div>
    
    <div class="item">
        <label>Password</label>
        <p><input type="password" class="txt <?=!isset($id) ? 'required password' : '';?>" name="data[password]" value="" minlength="8" maxlength="40" /> <? if (isset($password)) {?><span style="font-weight:bold;color:#739DF7;margin-left:15px;"><?=empty($password) && !empty($uid) ? 'FACEBOOK ACCOUNT' : $password;?></span><? } ?></p>
    </div>
    
     <div class="item">
        <label>Price</label>
        <p><input type="text" class="txt number" name="data[price]" value="<?=isset($price) ? $price : '';?>" maxlength="40" /></p>
    </div>
    
    <div class="item">
        <label>Description</label>
        <p><textarea name="data[description]"><?=isset($description) ? str_replace('<br />', "\n", $description) : '';?></textarea></p>
    </div>
    
    <div class="item">
        <label>Fanbase</label>
        <p><input type="text" class="txt number" name="data[fanbase]" value="<?=isset($fanbase) ? $fanbase : '';?>" /></p>
    </div>
    
     <div class="item">
        <label>Payment street</label>
        <p><input type="text" class="txt" name="data[payment_street]" value="<?=isset($payment_street) ? $payment_street : '';?>" maxlength="40" /></p>
    </div>
    
     <div class="item">
        <label>Payment city</label>
        <p><input type="text" class="txt" name="data[payment_city]" value="<?=isset($payment_city) ? $payment_city : '';?>" maxlength="40" /></p>
    </div>
    
    <div class="item">
        <label>Payment state</label>
        <p><input type="text" class="txt" name="data[payment_state]" value="<?=isset($payment_state) ? $payment_state : '';?>" maxlength="40" /></p>
    </div>
    
    <div class="item">
        <label>Payment ZIP</label>
        <p><input type="text" class="txt" name="data[payment_zip]" value="<?=isset($payment_zip) ? $payment_zip : '';?>" maxlength="10"/></p>
    </div>
    
    <div class="item">
        <label>PayPal email</label>
        <p><input type="text" class="txt email" name="data[paypal_email]" value="<?=isset($paypal_email) ? $paypal_email : '';?>" /></p>
    </div>
    
     <? if (isset($create_date)) { ?>
    <div class="item">
        <label>Created Date</label>
        <p><?=preg_replace('/(\d{4})-(\d{2})-(\d{2})/', '$2-$3-$1 ', $create_date);?></p>
    </div>
    <? } ?>
    
    <div class="item">
        <label></label>
        <p>
            <button type="submit"> <? if (isset($id)) { ?> Save changes <? }else{?> Create accaunt <? } ?> </button>  <img class="ajax-loader inline" src="<?=base_url('assets/admin/images/loader-20x20.gif')?>" alt="loader" />
        </p>
    </div>
</form>