<div class="title">Bookings</div>
<ul>
    <li <? if ($section == 'bookings') {?>class="bgwhite current"<? } ?>>
        <? if ($section == 'bookings') {?><span>Bookings</span><? } else { ?><a href="<?=base_url('admin/booking/bookings');?>"><span>Bookings</span></a> <? } ?>
    </li>
</ul>