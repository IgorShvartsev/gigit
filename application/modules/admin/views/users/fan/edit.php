<form id="editform">
    
    <input type="hidden" name="id" value="<?=isset($id) ? $id : '';?>" />
    <input type="hidden" name="m"  value="<?=$model;?>" />
    <input type="hidden" name="s"  value="<?=$section?>" />
    
    <h2><?=isset($name) ? $name : '';?></h2>
    
    <div class="item">
        <label></label>
        <div class="p">
            <div class="thumb">
                <a href="<?=isset($id) ? (base_url() . 'fan/' . $id) : '#';?>" target="_blank"><? if (isset($photo)) {?><img src="<?=$photo;?>"  alt="" /><? } else {?><img src="<?=base_url('assets/admin/images/nophoto.png');?>" alt="" /><? } ?></a>
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
        <label>First Name</label>
        <p><input type="text" class="txt required" name="data[first_name]" value="<?=isset($first_name) ? $first_name : '';?>"  maxlength="60"/></p>
    </div>
    
    <div class="item">
        <label>Last Name</label>
        <p><input type="text" class="txt required" name="data[last_name]" value="<?=isset($last_name) ? $last_name : '';?>"  maxlength="60"/></p>
    </div>
    
    <div class="item">
        <label>Email</label>
        <p><input type="text" class="txt required email" name="data[email]" value="<?=isset($email) ? $email : '';?>" /></p>
    </div>
    
    <div class="item">
        <label>Password</label>
        <p><input type="password" class="txt <?=!isset($id) ? 'required password' : '';?>" name="data[password]" value="" minlength="8" maxlength="40" /> <? if (isset($password)) {?><span style="font-weight:bold;color:#739DF7;margin-left:15px;"><?=empty($password) && !empty($uid) ? 'FACEBOOK ACCOUNT' : $password;?></span><? } ?></p>
    </div>
 
     <div class="item">
        <label>Street</label>
        <p><input type="text" class="txt" name="data[street]" value="<?=isset($street) ? $street : '';?>" maxlength="40" /></p>
    </div>
    
     <div class="item">
        <label>City</label>
        <p><input type="text" class="txt" name="data[city]" value="<?=isset($city) ? $city : '';?>" maxlength="40" /></p>
    </div>
    
    <div class="item">
        <label>State</label>
        <p><input type="text" class="txt" name="data[state]" value="<?=isset($state) ? $state : '';?>" maxlength="40" /></p>
    </div>
    
    <div class="item">
        <label>ZIP</label>
        <p><input type="text" class="txt" name="data[zip]" value="<?=isset($zip) ? $zip : '';?>" maxlength="10"/></p>
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