<!DOCTYPE html>
<html>
<head>
<title>Administration - <?=$_SERVER['SERVER_NAME'];?></title> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="en-US" />
<link href="<?=base_url('assets/css/admin.css');?>" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=base_url('assets/js/jquery/jquery-1.6.2.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/jquery/jquery.tools.min.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/admin/script.js');?>"></script>
<script type="text/javascript" src="<?=base_url('assets/js/admin/admin.js');?>"></script>
</head>
<body>
<div id="wrapper" <?=isset($isLogin) ? 'style="border:0;"' : '';?>>
    <?php if (isset($isLogin)) { ?>
        <?=$content;?>
    <?php } else { ?> 
    <div class="decor bgblue">
        <div class="header">
            <a href="?" class="logo"></a>
            <div class="cms">
                <h1 class="txtcolorwhite">Admin panel</h1>
            </div>
            <div class="welkom"><span>Somebody</span></div>
            <div class="logout">
                <a href="<?=base_url('admin/auth/logout')?>" class="btn">Logout</a>
            </div>
        </div>
        <div class="menu bgmenu">
        <ul>
            <li>
                <a href="<?=base_url('admin/site/dashboard');?>">Site</a>
            </li>         
        </ul>
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