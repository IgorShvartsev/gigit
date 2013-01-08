<div class="title">Users</div>
<ul>
    <li <? if ($section == 'band') {?>class="bgwhite current"<? } ?>>
        <? if ($section == 'band') {?><span>Bands</span><? } else { ?><a href="<?=base_url('admin/users/band');?>"><span>Bands</span></a> <? } ?>
    </li>
    <li <? if ($section == 'fan') {?>class="bgwhite current"<? } ?>>
        <? if ($section == 'fan') {?><span>Fans</span><? } else { ?><a href="<?=base_url('admin/users/fan');?>"><span>Fans</span></a> <? } ?>
    </li>
</ul>
