<form id="editform">
    
    <input type="hidden" name="id" value="<?=isset($id) ? $id : '';?>" />
    <input type="hidden" name="m"  value="<?=$model;?>" />
    <input type="hidden" name="s"  value="<?=$section?>" />
    
    <div class="item">
        <label>Booking ID</label>
        <p>
            <span style="font-size:16px;color:#1F6798;">#<?=$id;?></span>
        </p>
    </div>
    
    <div class="item">
        <label>Band</label>
        <p>
            <?=$name;?> <span style="color:#999;">(ID: <?=$band_id?>)</span>
        </p>
    </div>
    
    <div class="item">
        <label>Gig date</label>
        <p>
            <?=$gig_date;?> 
        </p>
    </div>
    
    <div class="item">
        <label>Gig time</label>
        <p>
            <?=$start_time;?> - <?=$end_time;?> 
        </p>
    </div>
    
    <div class="item">
        <label>From Fan</label>
        <p>
            <? if (empty($first_name) && empty($last_name)){
            } else { 
                echo $first_name . ' ' . $last_name . ' <span style="color:#999">(ID: ' . $user_id. ')</span>';
            } ?> 
        </p>
    </div>
    
     <div class="item">
        <label>Venue type</label>
        <p>
            <?=$venue_type?>
        </p>
    </div>
    
    <div class="item">
        <label>Location</label>
        <p>
            <?=$location?>
        </p>
    </div>
    
    <div class="item">
        <label>Amplification request </label>
        <p>
            <?=$amp_request;?>
        </p>
    </div>
    
    <div class="item">
        <label>Address</label>
        <p>
            <?=$street1;?> <?=$street2;?><br />
            <?=$city;?><br />
            <?=$state;?> <?=$zip;?>
        </p>
    </div>
    
    <div class="item">
        <label>Notes to band</label>
        <p style="font-style:italic;font-size:11px;">
            <?=str_replace("\n", "<br />", $note);?>
        </p>
    </div>
    
    <div class="item">
        <label>What's the occasion?</label>
        <p style="font-style:italic;font-size:11px;">
            <?=empty($answer) ? ' - ' : str_replace("\n", "<br />", $answer1);?>
        </p>
    </div>
    
    <div class="item">
        <label>About the audience.</label>
        <p style="font-style:italic;font-size:11px;">
            <?=empty($answer) ? ' - ' : str_replace("\n", "<br />", $answer2);?>
        </p>
    </div>
    
    <div class="item">
        <label>Booking Date</label>
        <p>
            <?=$create_date;?>
        </p>
    </div>
    
    <div class="item">
        <label>Status</label>
        <p>
            <?=$status_text;?>
        </p>
    </div>
    
 <? /* ?>   
    <div class="item">
        <label>Status</label>
        <p>
            <select name="data[status]">
                <option value="0" <?=$status == 0 ? 'selected="selected"' : ''?>> Needs response </option>
                <option value="1" <?=$status == 1 ? 'selected="selected"' : ''?>> Confirmed </option>
                <option value="2" <?=$status == 2 ? 'selected="selected"' : ''?>> Completed </option>
            </select>
        </p>
    </div>
    <div class="item">
        <label></label>
        <p>
            <button type="submit"> Change status </button>  <img class="ajax-loader inline" src="<?=base_url('assets/admin/images/loader-20x20.gif')?>" alt="loader" />
        </p>
    </div>
 <? */ ?>
    <br />
    <br />
</form>