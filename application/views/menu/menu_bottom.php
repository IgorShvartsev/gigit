<menu id="menu-bottom">
    <? if (count($static_pages) > 0) { ?>
    <ul>
        <? foreach($static_pages as $static_page) { ?>
            <li><a href="<?=base_url($static_page['seo']).'.html';?>"><?=$static_page['title'];?></a></li>
        <? } ?>
    </ul>
    <? } ?>
</menu>
