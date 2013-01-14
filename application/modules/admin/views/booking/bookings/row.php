<tr id="row-<?php echo $id;?>">
    <td><?php echo $id;?></td>
    <td><a href="<?=base_url().'band/' . $seo . '.html'?>" target="_blank"><?=$name;?></a></td>
    <td style="text-align:center"><?=$first_name . ' ' . $last_name;?></td>
    <td><?=$gig_date;?></td>
    <td style="font-size:11px;color:#7788A2;"><?=$start_time .'<br />'. $end_time;?></td>
    <td><?=$city;?></td>
    <td><?=$create_date;?></td>
    <td class="<?=$status == 1? 'gr' : ($status == 3 ? 'rd' : ($status == 0 ? 'or' : ''));?>"><?=$status_text;?></td>
    <td class="MEdit"><img id="edit" class="edt" src="<?=base_url('assets/admin/images/edit.png');?>" alt="" title="View details" /></td>
</tr>