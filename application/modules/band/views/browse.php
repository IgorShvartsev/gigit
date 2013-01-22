<section id="main">
    <div id="mainwrap" class="clearfix grad-3 brd-lr shdw-3">
        <div id="content" class="clearfix">
            <div id="browse-sidebar" class="left">
                <form id="browseform" action="" method="get">
                    <? if (!empty($zip)) { ?>
                        <input type="hidden" name="zip" value="<?=$zip?>" />
                    <? } ?>
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
                                <option value="create_date" <?=$sort == 'create_date' ? 'selected="selected"' : '';?>>Newest</option> 
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
            
            <? if (count($bands) > 0) {    
                    foreach($bands as $band) { 
                        echo partial('band/shared/browse-item', array('band' => $band));  
                    }
                } else { ?>
                    <div class="no-results">
                        <h3>Nothing Found</h3>
                    </div>
            <? } ?>
            
            <?=partial('shared/pagination');?>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">new FixScroll('#browse-sidebar');</script>