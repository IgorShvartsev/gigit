<tr id="row-<?php echo $id;?>">
    <td class="MChk"><input id="chk-<?php echo $id;?>" type="checkbox" name="chk[]" value="<?php echo $id;?>" /></td>
    <td><a href="<?=base_url().'band/' . $seo . '.html'?>" target="_blank"><?=$name;?></a></td>
    <td style="text-align:center"><?=$email;?></td>
    <td>$<?=$price;?></td>
    <td class="MAct"><span <?echo $active ? '' : 'class="inactive"';?>><?php echo $active? 'active' : 'inactive';?></span></td>
    <td><span><?=preg_replace('/(\d{4})-(\d{2})-(\d{2})/', '$2-$3-$1 ', $create_date);?></span></td>
    <td class="MEdit"><img id="edit" class="edt" src="<?=base_url('assets/admin/images/edit.png');?>" alt="edit" /></td>
</tr>