<section id="main">
    <div id="mainwrap" class="clearfix grad-3 brd-lr shdw-3">
        <div id="content">
            <?=partial('shared/menu', array('pagename' => 'profile'));?> 
            <div id="account-dashboard">
                <form id="acoount-profile-form" method="post" action="">
                     <div class="item clearfix">
                        <label>Photo:</label>                
                        <p>
                            <span class="thumb inline"><img src="<?=base_url('assets/images/'.THEME.'/nophoto_t.png');?>" alt="thumb" /></span>
                            <span class="link2">Change photo</span>
                        </p>
                     </div>
                     <div class="item clearfix">
                        <label>First name:</label>                
                        <p> <input class="txt" type="text" name="data[first_name]" value=""  maxlength="40" size="40" tabindex="1" /></p>
                     </div>
                     <div class="item clearfix">
                        <label>Last name:</label>                
                        <p> <input class="txt" type="text" name="data[last_name]" value=""  maxlength="40" size="40" tabindex="2" /></p>
                     </div>
                     <div class="item clearfix">
                        <label>Zip code:</label>                
                        <p> <input class="txt" type="text" name="data[zip]" value=""  maxlength="40" size="40" tabindex="3" /></p>
                     </div>
                     <div class="item clearfix">
                         <label></label>
                         <p><button class="btn submit" tabindex="4">Save Changes</button></p>
                 </div>
                </form>
            </div> 
        </div>
    </div>
</section>