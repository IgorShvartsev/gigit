<div id="auth_container">
    <div class="auth">            
        <div class="main_in_auth">                
            <div class="auth_content">  
                <div id="login_form">
                    <h2 style="padding:10px 0;">Administration</h2> 
                    <?php echo validation_errors('<div class="error">', '</div>'); ?>  
                    <form action="<?=base_url('admin/auth/login');?>" method="post" accept-charset="utf-8" id="auth_form" enctype="multipart/form-data">
                        <label for="username">Email</label>
                        <input type="text" name="email" value="<?=set_value('email'); ?>" id="username" maxlength="20" size="20" tabindex="1" class="input"  /><br />
                        <label for="password">Password</label>
                        <input type="password" name="password" value="" id="password" maxlength="20" size="20" tabindex="2" class="input"  /><br /><br />
                        <input type="submit" name="submit" value="Login"  />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>