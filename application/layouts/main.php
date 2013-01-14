<!DOCTYPE HTML>
<html lang="en">
<head>
<title><?=$title;?></title>                                               
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="<?=$meta_keywords;?>" />
    <meta name="description" content="<?=$meta_description;?>" />
    <link rel="stylesheet" href="<?=base_url('assets/css/style.css')?>" />
    <link rel="stylesheet" href="<?=base_url('assets/css/ui/jquery-ui-1.9.2.custom.min.css');?>" />
    <script type="text/javascript" src="<?=base_url('resource/js?r='.rand(1000, 100000));?>"></script> 
</head>
<body>
    <header>
        <div id="headwrap" class="clearfix">
            <div class="logo">
                <h1 class="inline"><a href="<?=base_url();?>">GIGIT</a></h1> <big>Bring the backstage to your backyard</big>
            </div>
            <?=partial('menu/menu_top');?>
            <?=partial('shared/loginout');?>
            <div class="login">
              <? if (($login = isLoggedIn()) == false) { 
                    if (!isset($registration)) { 
                        if (!bandLoggedIn()) { ?> 
                        Are you a band? <a href="<?=base_url('registration/band');?>" class="link2">Register now</a>
                    <? }
                    }
                 } else {?>
                    <?=$login['name'];?> &nbsp;&nbsp;<a href="<?=base_url('auth/logout');?>">Logout</a> <br />
                    <? if (bandLoggedIn()) { ?> <a href="<?=base_url('band/profile');?>" class="link2">Edit profile</a> <? } ?>
              <?} 
                ?>
            </div>
        </div>
    </header>
    <div id="page">
        <div id="pagewrap" class="clearfix">
            <?=$content;?>
        </div>
    </div>
    <footer>
        <div id="footerwrap" class="clearfix">
            <?=partial('menu/menu_bottom', array());?>
        </div>
        <div class="clearfix" style="height:20px;"></div>
    </footer>
</body>
</html>