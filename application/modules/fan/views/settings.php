<section id="main">
    <div id="mainwrap" class="clearfix grad-3 brd-lr shdw-3">
        <div id="content">
            <?=partial('shared/menu', array('pagename' => 'settings'));?> 
            <div id="account-dashboard">
             <br />
             <form id="acoount-email-form" method="post" action="<?=base_url('fan/settings');?>">
                     <h2>Change Your Email Address</h2>
                     <br />
                     <input type="hidden" name="action" value="email" />
                     <div class="item clearfix">
                        <label></label>                
                        <p><span class="inf">Your current email: <strong>test@test.com</strong></span></p>
                     </div>
                     <div class="item clearfix">
                        <label>New Email Address:</label>                
                        <p> <input class="txt" type="text" name="data[email]" value=""  maxlength="40" size="40" tabindex="1" /></p>
                     </div>
                     
                     <div class="item clearfix">
                        <label>Confirm Email Address:</label>                
                        <p> <input class="txt" type="text" name="data[email_confirm]" value=""  maxlength="40" size="40" tabindex="2" /></p>
                     </div>
                     
                     <div class="item clearfix">
                         <label></label>
                         <p><button class="btn submit" tabindex="4">Change Email Address</button></p>
                     </div>
             </form>
             <br />
             <br />
             <form id="acoount-password-form" method="post" action="<?=base_url('fan/settings');?>">
                     <h2>Change Your Password</h2>
                     <br />
                     <input type="hidden" name="action" value="password" />
                     <div class="item clearfix">
                        <label>New Password:</label>                
                        <p> <input class="txt" type="text" name="data[password]" value=""  maxlength="40" size="40" tabindex="1" /></p>
                     </div>
                     
                     <div class="item clearfix">
                        <label>Confirm Password:</label>                
                        <p> <input class="txt" type="text" name="data[password_confirm]" value=""  maxlength="40" size="40" tabindex="2" /></p>
                     </div>
                     
                     <div class="item clearfix">
                         <label></label>
                         <p><button class="btn submit" tabindex="4">Change Password</button></p>
                     </div>
             </form>
            </div>  
        </div>
    </div>
</section>