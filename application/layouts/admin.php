<!DOCTYPE html>
<html>
<head>
<title>Administration - <?=$_SERVER['SERVER_NAME'];?></title> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="en-US" />
<link href="<?=base_url('assets/admin/css/admin.css');?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?=base_url('assets/admin/css/ui/jquery-ui-1.9.2.custom.min.css');?>" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=base_url('assets/admin/js/jquery/jquery-1.7.1.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/admin/js/jquery/jquery.tools.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/admin/js/jquery/jquery.validate.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/admin/js/script.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/admin/js/admin.js');?>"></script>
</head>
<body>
<div id="wrapper" <?=isset($isLogin) ? 'style="border:0;"' : '';?>>
    <?php if (isset($isLogin)) { ?>
        <?=$content;?>
    <?php } else {
        $admin = adminLoggedIn(); 
    ?> 
    <div class="decor bgblue">
        <div class="header">
            <a href="<?=base_url('admin');?>" class="logo">GIGIT</a>
            <div class="cms">
                <h1 class="txtcolorwhite"></h1>
            </div>
            <div class="welkom"><span><?=$admin['name'];?></span></div>
            <div class="logout">
                <a href="<?=base_url('admin/auth/logout')?>" class="btn">Logout</a>
            </div>
        </div>
        <div class="menu bgmenu">
            <?=partial('topmenu');?>
        </div>
        <div class="content bgwhite">
            <?=$content;?>
        </div>
    </div>
    <?php } ?>
</div>
<div style="font-size:14px;width:300px;margin:0 auto;text-align:center;color:#888;">
        <?=date('Y');?> &copy; <?=$_SERVER['SERVER_NAME'];?>
</div>
</body>
</html>