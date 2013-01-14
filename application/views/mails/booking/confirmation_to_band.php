Hi <?=isset($name) ? $name : '';?>. 
<br /><br />
You confirmed Gig booking #<?=isset($id) ? $id : '';?>.
<br /><br />
<table style="width:300px">
    <tr>
        <td><b>Gig date:</b></td>
        <td><?=isset($gig_date) ? $gig_date : '';?></td>
    </tr>
    <tr>
        <td><b>Time:</b></td>
        <td><?=isset($start_time) ? $start_time : ''?> - <?=isset($end_time) ? $end_time : '';?></td>
    </tr>
    <tr>
        <td><b>Address:</b></td>
        <td>
            <?=isset($street1) ? $street1 : '';?> <?=isset($street2) ? $street2 : '';?>,
            <?=isset($city) ? $city : '';?>, <?=isset($state) ? $state : '';?> <?=isset($zip) ? $zip : '';?> 
        </td>
    </tr>
</table> 
<br /><br />
