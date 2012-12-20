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
                        <img src="<?=base_url('assets/images/default/play1.png');?>" alt="play" /> <span>Play track</span>
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
                <div class="likes">
                    <ul>
                        <li><?=isset($band['fanbase']) ? $band['fanbase'] : '';?> Fanbase</li>
                        <li>LA Based</li>
                        <li>Editor's Choise</li>
                        <li>Top Notch Profile</li>
                    </ul>
                </div>
                <div class="booking round-8">
                    <div class="wrap">
                        <span class="price">$<?=price_view($band['price']);?></span><br />
                        <small>For two 20 minute show</small>
                        <a id="booking-btn" class="btn" href="<?=isset($band['seo']) ? base_url('band/booking/' . $band['seo'] .'.html') : '#';?>">Gigit Now!</a>
                    </div>
                </div>
            </div>
            <div class="calendar">
            </div>
            <div class="twitter">
            </div>
            <div class="clearfix"></div>
           </div> 
        </div>
    </div>
<section id="main">