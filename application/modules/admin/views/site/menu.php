<div class="title">Site</div>
<ul>
    <li <? if ($section == 'dashboard') {?>class="bgwhite current"<? } ?>>
        <? if ($section == 'dashboard') {?><span>Dashboard</span><? } else { ?><a href="<?=base_url('admin/site/dashboard');?>"><span>Dashboard</span></a> <? } ?>
    </li>
    <li <? if ($section == 'pages') {?>class="bgwhite current"<? } ?>>
        <? if ($section == 'pages') {?><span>Pages</span><? } else { ?><a href="<?=base_url('admin/site/pages');?>"><span>Pages</span></a> <? } ?>
    </li>
</ul>
