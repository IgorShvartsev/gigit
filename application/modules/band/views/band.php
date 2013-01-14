<section id="main">
    <div id="mainwrap" class="clearfix grad-3 brd-lr shdw-3">
        <div id="content">
          <div id="band-details">
            <div id="video-preview" class="clearfix">
                <div class="frame"></div>
                <div class="items">   
                </div>
            </div>
            <div id="image-preview" class="clearfix">
                <div class="items">
                    <div>
                        <div class="item">
                            <a href="#"><img src="<?=base_url()?>" /></a>
                        </div>
                         <div class="item">
                            <a href="#"><img src="<?=base_url()?>" /></a>
                        </div>
                        <div class="item">
                            <a href="#"><img src="<?=base_url()?>" /></a>
                        </div>
                        <div class="item">
                            <a href="#"><img src="<?=base_url()?>" /></a>
                        </div>
                        <div class="item">
                            <a href="#"><img src="<?=base_url()?>" /></a>
                        </div>
                        <div class="item">
                            <a href="#"><img src="<?=base_url()?>" /></a>
                        </div>
                        <div class="item">
                            <a href="#"><img src="<?=base_url()?>" /></a>
                        </div>
                        <div class="item">
                            <a href="#"><img src="<?=base_url()?>" /></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="info clearfix">
                <div class="description">
                    <h2><?=isset($band['name']) ? $band['name'] : '';?></h2>
                    <p>
                        <?=isset($band['description'])  ? $band['description'] : '';?>
                    </p>
                    <b>Sounds like:</b><br />
                     <? if (isset($band['tags']) && is_array($band['tags'])) { 
                            foreach($band['tags'] as $tag) {
                     ?> 
                        <span class="span-bg"><?=$tag?></span> 
                     <? 
                            }
                      }
                      ?>
                </div>
                <div class="play-track round-8">
                    <div class="top">
                        <img src="<?=base_url('assets/images/' . THEME . '/play1.png');?>" alt="play" /> <span>Play track</span>
                    </div>
                    <ul class="list">
                        <?  if (isset($band['tracks'])) {
                            $i = 1;
                            foreach($band['tracks'] as $track) { ?>
                        <li class="active"><?=$i++. '. '. $track['title']?></li>
                        <?   
                            }
                        } 
                        ?>
                    </ul>
                </div>
                <div class="badges">
                    <ul>
                        <li><?=isset($band['fanbase']) ? $band['fanbase'] : '';?> Fanbase</li>
                        <li>LA Based</li>
                        <li>Editor's Choise</li>
                        <li>Top Notch Profile</li>
                    </ul>
                </div>
                <div class="booking round-8">
                    <div class="wrap">
                        <span class="price">$<?=isset($band['price']) ? price_view($band['price']) : '';?></span><br />
                        <small>For two 20 minute show</small>
                        <? if (!is_array(bandLoggedIn())) {?>
                        <a id="booking-btn" class="btn" href="<? if (userLoggedIn()) { echo isset($band['seo']) ? base_url('band/booking/' . $band['seo'] .'.html') : '#'; } else { echo isset($band['id']) ? base_url('registration/fan?band=' . $band['id']) : '#';} ?>">Gigit Now!</a>
                        <? } ?>
                    </div>
                </div>
            </div>
            <script type="text/javascript" src="<?=base_url('resource/interface_js?t=calendar&amp;md=band&amp;p=' . $jsparams . '&amp;r=' . rand(1000, 100000));?>"></script>  
            <div id="ucs-calendar" class="calendar"></div>
            <div class="twitter"></div>
            <div class="clearfix"></div>
           </div> 
        </div>
    </div>
<section id="main">
<? if (bandLoggedIn() && isset($profile)) {?>
<script type="text/javascript">
    $(function(){ 
       var formName = null;
       if (popupDlg == undefined) {
           window.console ? console.log("popupDlg object instance of Popup class is not defined") : '';
           return;
       }
        
       $(window).hashchange( function(){
            formName  = location.hash.substr(1).split('-')[0];
            $.post('/band/ajax/form/' + formName, {}, function(html){
                var wnd = $(window);
                popupDlg.dlg.find('form').remove();
                popupDlg.dlg.is(':visible') ? popupDlg.hideLoader().form(html) : popupDlg.hideLoader().show(html);
                popupDlg.dlg.css({'left' : (wnd.width() - popupDlg.dlg.width())/2, 'top' : (wnd.height() - popupDlg.dlg.height())/2});
            })
       }).hashchange();
       
       popupDlg.dlg.on('click', '.next', function(){
           var next = $(this);
           if (next.data('next') != undefined) {
               popupDlg.showLoader();
               location.hash = '#' + next.data('next');
           } else {
               window.console ? console.log("Element with class `next` doesn\'t have data-next attribute") : '';
           }
       });
    });
</script>
<? } ?>