<section id="main">
    <div id="mainwrap" class="clearfix grad-3 brd-lr shdw-3">
        <div id="content">
            <div id="browse-sidebar" class="left">
                <form id="browseform" action="" method="get">
                    <ul class="browse-fields">
                        <li>
                            <label>Showing</label>
                            <select name="show">
                                <option value="all" <?=$show == 'all' ? 'selected="selected"' : '';?>>All Bands</option>
                                <option value="featured" <?=$show == 'featured' ? 'selected="selected"' : '';?>>Featured Bands</option>
                            </select>
                        </li>
                         <li>
                            <label>Sort by</label>
                            <select name="sort">
                                <option value="name" <?=$sort == 'name' ? 'selected="selected"' : '';?>>Name</option>
                                <option value="genre" <?=$sort == 'genre' ? 'selected="selected"' : '';?>>Genre</option>
                                <option value="fanbase" <?=$sort == 'fanbase' ? 'selected="selected"' : '';?>>Fanbase</option>
                            </select>
                        </li>
                    </ul>
                    <input type="hidden" name="p" value="1" />
                </form>
            </div>
            <script type="text/javascript">
                $(function(){
                   var form = document.getElementById('browseform');
                   $('select', form).change(function(){
                       $(form).submit();
                   }) 
                })
            </script>
            
            <div id="browse-result" class="right">
            
            <? 
            if (count($bands) > 0) {    
                foreach($bands as $band) { ?>  
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
            
            <? 
                }
            } else { ?>
                <div class="no-results">
                    <h3>Nothing Found</h3>
                </div>
            <?    
            } 
            ?>
            <?=partial('shared/pagination');?>
            </div>
        </div>
    </div>
</section>