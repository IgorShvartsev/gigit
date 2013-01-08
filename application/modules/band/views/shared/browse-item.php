               <div class="list-item">
                    <div class="thumb">
                        <a href="<?=base_url() . 'band/' . $band['seo'] . '.html';?>"><? if (isset($band['photo'])) {?><img src="<?=$band['photo'];?>"  alt="" /><? } ?></a>
                    </div>
                    <div class="info">
                        <h2><?=$band['name']?></h2>
                        <ul class="attrib">
                            <li class="fan">Fanbase: <?=$band['fanbase'];?></li>
                            <li class="gen">Genre: 
                                <?foreach($band['genres'] as $genre) { ?>
                                    <span class="span-bg"><?=$genre?></span> 
                                <? } ?>   
                            <li class="snd">Sounds like:
                            <? if (isset($band['tags']) && is_array($band['tags'])) { 
                                    foreach($band['tags'] as $tag) {
                            ?> 
                                <span class="span-bg"><?=$tag?></span> 
                            <? 
                                    }
                               }
                            ?>
                            </li>
                        </ul>
                        <div class="price">
                            <span class="amount">$<?=price_view($band['price']);?></span><br/>
                            two 20 minutes sets
                        </div>
                    </div>
                </div>
                
                <div class="separator"></div>