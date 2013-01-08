<div id="main-frame" class="twrap">
   <div class="container">
        <div class="data">
          <h2>Last created Bands</h2>
          <a href="<?=base_url('admin/users/band');?>" class="more edt">Go to edit Bands</a>
          <div class="br"></div>  
          <table id="table-list" data-model="bands" data-section="<?=$menu . "/" . $section;?>" class="dataTable" cellpadding="0" cellspacing="0">
            <thead>
               <tr> 
                <th class="MName">Name</th>
                <th class="MEmail">Email</th>
                <th>Active</th>
                <th>Create Date</th>
               </tr>
            </thead>
            <tbody>
                <? foreach($bands as $band) {?>
                    <tr>
                         <td><a href="<?=base_url().'band/' . $band['seo'] . '.html'?>" target="_blank"><?=$band['name'];?></a></td>
                         <td style="text-align:center"><?=$band['email'];?></td>
                         <td class="MAct"><span <?echo $band['active'] ? '' : 'class="inactive"';?>><?php echo $band['active'] ? 'active' : 'inactive';?></span></td>
                         <td><span><?=preg_replace('/(\d{4})-(\d{2})-(\d{2})/', '$2-$3-$1 ', $band['create_date']);?></span></td>
                    </tr>
                <? }?>
            </tbody>
          </table>
          <br /> <br />
          <h2>Last created Fans</h2>
          <a href="<?=base_url('admin/users/fan');?>" class="more edt">Go to edit Fans</a>
          <div class="br"></div>  
          <table id="table-list" data-model="fans" data-section="<?=$menu . "/" . $section;?>" class="dataTable" cellpadding="0" cellspacing="0">
            <thead>
               <tr> 
                <th class="MName">First Name</th>
                <th class="MName">Last Name</th>
                <th class="MEmail">Email</th>
                <th>Active</th>
                <th>Create Date</th>
               </tr>
            </thead>
            <tbody>
                <? foreach($fans as $fan) {?>
                    <tr>
                        <td><?=$fan['first_name'];?></td>
                        <td><?=$fan['last_name'];?></td>
                        <td style="text-align:center"><?=$fan['email'];?></td>
                        <td class="MAct"><span <?echo $fan['active'] ? '' : 'class="inactive"';?>><?php echo $fan['active'] ? 'active' : 'inactive';?></span></td>
                        <td><span><?=preg_replace('/(\d{4})-(\d{2})-(\d{2})/', '$2-$3-$1 ', $fan['create_date']);?></span></td>
                    </tr>
                <? }?>
            </tbody>
          </table>
        </div>
   </div>
</div>

<div id="edit-frame" style="display:none">
</div>
         