<!DOCTYPE HTML>
<html lang="en">
<head>
<title><?=$title;?></title>                                               
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="<?=$meta_keywords;?>" />
    <meta name="description" content="<?=$meta_description;?>" />
    <link rel="stylesheet" href="<?=base_url('assets/css/style.css')?>" />
    <link rel="stylesheet" href="<?=base_url('assets/css/ui/jquery-ui-1.9.2.custom.min.css');?>" />
    <script type="text/javascript" src="<?=base_url('resource/js');?>"></script> 
</head>
<body>
    <header>
        <div id="headwrap" class="clearfix">
            <div class="logo">
                <h1 class="inline"><a href="<?=base_url();?>">GIGIT</a></h1> <big>Bring the backstage to your backyard</big>
            </div>
            <?=partial('menu/menu_top');?>
            <?=partial('shared/loginout');?>
            <div class="register">
              <? if (!isset($registration)) { 
                   if (!bandLoggedIn()) { ?> 
                    Are you a band? <a href="<?=base_url('socials/provider/facebook?redirect=band/registration');?>" onclick="window.open(this.href, 'auth', 'width=600,height=400'); return false;" class="link2">Register now</a>
                <? }
                } 
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