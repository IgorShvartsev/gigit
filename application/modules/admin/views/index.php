  <div class="leftcolumn"> 
        <?=partial($menu.'/menu');?>
  </div>
  <div class="rightcolumn">
       <div id="wrap-content" class="twrap">
       <?=partial($menu . "/" . $section . "/" . (file_exists(APPPATH . 'modules/admin/views/' . $menu . '/' . $section . '/' . $item . '.php') ? $item : $section));?>     
       </div> 
  </div> 