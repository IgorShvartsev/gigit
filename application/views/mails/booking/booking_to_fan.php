You booked Gig on gigit.com. 
<br /><br />
<table cellpadding="2" cellspacing="2">
    <tr>
        <td style="width:170px"><b>Booking ID :</b></td>
        <td>#<?=$id;?></td>
    </tr>
    
    <tr>
        <td><b>Band :</b></td>
        <td>
            <?=$name;?>
        </td>
    </tr>
    
    <tr>
        <td><b>Gig date :</b></td>
        <td>
            <?=$gig_date;?> 
        </td>
    </tr>
    
    <tr>
        <td><b>Gig time :</b></td>
        <td>
            <?=$start_time;?> - <?=$end_time;?> 
        </td>
    </tr>
    
    <tr>
        <td><b>Venue type :</b></td>
        <td>
            <?=$venue_type?>
        </td>
    </tr>
    
    <tr>
        <td><b>Location :</b></td>
        <td>
            <?=$location?>
        </td>
    </tr>
    
    <tr>
        <td><b>Amplification request :</b></td>
        <td>
            <?=$amp_request;?>
        </td>
    </tr>
    
    <tr>
        <td><b>Address :</b></td>
        <td>
            <?=$street1;?> <?=$street2;?><br />
            <?=$city;?><br />
            <?=$state;?> <?=$zip;?>
        </td>
    </tr>
    
    <tr>
        <td><b>Notes to band :</b></td>
        <td style="font-style:italic;font-size:11px;">
            <?=str_replace("\n", "<br />", $note);?>
        </td>
    </tr>

</table>