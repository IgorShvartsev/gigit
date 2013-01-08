<menu id="menu-top">
    <? if (!isLoggedIn()) {?>
        <a href="<?=base_url('login');?>">Login</a>
    <? } elseif (bandLoggedIn())  {?>
        <a href="<?=base_url('band/dashboard');?>">Dashboard</a>
    <? } elseif (userLoggedIn())  {?>
        <a href="<?=base_url('fan/dashboard');?>">Dashboard</a>
    <? } ?>
</menu>
