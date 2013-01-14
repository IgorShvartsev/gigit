<section id="main">
    <div id="mainwrap" class="clearfix grad-3 brd-lr shdw-3">
        <div id="content">
          <div id="account-dashboard" class="clearfix">
          <? if (!$booking) { ?>
            <h2 style="text-align:center;margin-top:100px;color:#777;">Ups, seems this link is obsolete.</h2>
          <? } else { ?>
            <form id="booking-form" method="post" action="<?=base_url('band/booking/confirm');?>">
                <input type="hidden" name="code" value="<?=str_replace('"', "", strip_tags($_GET['code']));?>" />
                <br />
                <div class="title" style="font-size: 12px;">
                    Awesome! <?=empty($booking['first_name']) && empty($booking['last_name']) ? 'Fan' : ($booking['first_name'] . ' ' .$booking['last_name']);?> has requested the following Gig. Go over the details and click Confirm to lock the
Gig, get paid, and JAM ON.
                </div>
                <br />
                <br />
                <div class="item clearfix">
                    <label>Date of gig:</label>                
                    <p class="datewrap"><?=$booking['gig_date'];?></p>
                </div>
                <div class="item clearfix">
                    <label>Start time:</label>                
                    <p><?=$booking['start_time'];?></p>
                </div>
                <div class="item clearfix">
                    <label>End time:</label>                
                    <p><?=$booking['end_time'];?></p>
                </div>
                <div class="item clearfix">
                    <label>Venue address:</label>                
                    <p>
                        <?=$booking['street1'].' '. $booking['street2'];?> <br />
                        <?=$booking['city'];?>, <?=$booking['state'];?> <?=$booking['zip'];?> 
                    </p>
                </div>
                <div class="item clearfix">
                    <label>Venue type:</label>
                    <p><?=$booking['venue_type'];?></p> 
                </div>
                <div class="item clearfix">
                    <label>Location:</label>
                    <p><?=$booking['location'];?></p> 
                </div>
                 <div class="item clearfix">
                    <label>Amplification request:</label>
                    <p><?=$booking['amp_request'];?></p> 
                </div>
                <div class="item clearfix">
                    <label>Notes to the band:</label>                
                    <p><?=$booking['note'];?></p>
                </div>
                <div class="item clearfix">
                    <label></label>
                    <p>
                        <span class="small">By clicking the button below, you confirm that you will provide music services stated above.</span>
                        <button class="btn submit"> Confirm </button>
                    </p>
                </div>               
            </form>
          <? } ?>
            <br />
          </div>
        </div>
    </div>
</section>