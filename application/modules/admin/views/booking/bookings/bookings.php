
<div id="main-frame" class="twrap">
   <div style="overflow: hidden;">
    <form class="search" method="post" autocomplete="off">
        <ul>
            <li>
                <label></label><br />
                <input class="txt" type="text" name="search"  value=""/>
            </li>
            <li>
                <input class="submit" type="submit" name="submit" value="Search" />
            </li>
        </ul>
    </form>
    </div>
   <div class="container">
        <div class="data">
          <div class="tools"></div>
          <div class="options"></div>
          <div class="line-thin"></div>
          <table id="table-list" data-model="bookings" data-section="<?=$menu . "/" . $section;?>" class="dataTable" cellpadding="0" cellspacing="0">
            <thead>
               <tr> 
                <th class="MId">Id</th>
                <th class="MName">Band</th>
                <th class="MName">From Fan</th>
                <th class="MDate">Gig date</th>
                <th class="MLTime">Time</th>
                <th class="MLocat">Location</th>
                <th class="MDate">Date of Booking</th>
                <th class="MStatus">Status</th>
                <th>&nbsp;</th>
               </tr>
            </thead>
            <tbody>
            <script type="text/javascript">
                $.get('<?=base_url()?>admin/ajax/getall', {'m' : 'bookings', 's' : '<?=$menu . "/" . $section;?>'}, function(data){if(data.content){update(data)}}, 'json');
            </script>
            </tbody>
          </table>
          <div class="pagination"></div>
        </div>
   </div>
</div>

<div id="edit-frame" style="display:none">
</div>