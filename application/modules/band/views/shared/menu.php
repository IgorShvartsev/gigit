<menu id="account-menu" class="clearfix">
    <ul>
        <li>
            <? if (isset($pagename) && $pagename == 'dashboard') { ?> 
                <span>Dashboard</span>
            <? } else { ?>
                <a href="<?=base_url('band/dashboard');?>">Dashboard</a>
            <? } ?>
        <li>
            <? if (isset($pagename) && $pagename == 'gigs') { ?> 
                <span>Gigs</span>
            <? } else { ?>
                <a href="<?=base_url('band/gigs');?>">Your Gigs</a>
            <? } ?>
        </li>
        <li>
            <? if (isset($pagename) && $pagename == 'profile') { ?> 
                <span>Profile</span>
            <? } else { ?>
                <a href="<?=base_url('band/profile');?>">Edit Profile</a>
            <? } ?>
        </li>
        <li>
            <a href="#">View Profile</a>
        </li>
        <li class="last">
            <? if (isset($pagename) && $pagename == 'settings') { ?> 
                <span>Settings</span>
            <? } else { ?>
                <a href="<?=base_url('band/settings');?>">Settings</a>
            <? } ?>
        </li>
    </ul>
</menu>