<?php
/**
*   Params:
*       $total    -   total number of items 
*       $p        -   page number
*       $perpage  -   items number per page
*/

$perpage = !$perpage ? 1 : $perpage;
$numPage = ceil($total / $perpage);

if($total > $perpage) {
?> 
  <div class="nav">
     <div class="lft"></div> 
     <div class="mdl">
    <?
        //generate previous page tag
        if ($p != 1) {
    ?>
        <? if ($p > 5) {?>
        <a id="page-1" href="<?=addQueryString(array('p' => 1));?>" title="Back to first page">.</a>
        <? } ?>
    <a id="page-<?=$p - 1;?>" class="p" href="<?=addQueryString(array('p' => ($p - 1))) ;?>" title="previous">Previous</a>
    <?
        }
        $index = 1; //page index
        while(($index <= $numPage) && ($numPage != 1)) {
            if(($index >= ($p - 4)) && ($index < ($p + 5))){
                //strong index if current page
                if ($index == $p) {
    ?>
    <a id="page-<?=$index?>" class="x" href="<?=addQueryString(array('p' => $index));?>"><?=$index; ?></a>
    <?
                }else{
    ?>
    <a id="page-<?=$index?>" href="<?=addQueryString(array('p' => $index));?>"><?=$index; ?></a>
    <?
                }
            }
            $index++;
       }

       //generate next page tag
       if ($p < $numPage) {
    ?>
    <a id="page-<?=$p + 1?>" class="n" href="<?=addQueryString(array('p' => $p + 1));?>" title="next">Next</a>
    <?
       }
    ?>
     </div>
     <div class="rgt"></div>
  </div>
  <? } ?>