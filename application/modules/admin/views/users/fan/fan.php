<div id="main-frame" class="twrap">
   <div style="overflow: hidden;">
    <form class="search" method="post">
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
          <div class="tools">
            <ul>
                <li class="tcheckbox"><input type="checkbox" name="all" /></li>
                <li class="actions">
                    <span class="tactivate"><a href="#">Activate</a></span>
                    <span> | </span>
                    <span class="tdeactivate"><a href="#">Deactivate</a></span>
                    <span> | </span>
                    <span class="tdelete"><a href="#">Delete</a></span>
                </li>
                <li>
                    <img class="ajax-loader inline" src="<?=base_url('assets/admin/images/loader-20x20.gif')?>" alt="loader" />
                </li>
            </ul>
          </div>
          <div class="options">
                <ul>
                    <li>
                        <span class="tnew"><a href="#">Add New</a></span>
                    </li>
                </ul>
          </div>
          <div class="line-thin"></div>
          <table id="table-list" data-model="fans" data-section="<?=$menu . "/" . $section;?>" class="dataTable" cellpadding="0" cellspacing="0">
            <thead>
               <tr> 
                <th class="MChk">&nbsp;</th>
                <th class="MName">First name</th>
                <th class="MName">Last name</th>
                <th class="MEmail">Email</th>
                <th>Active</th>
                <th>Create Date</th>
                <th>Edit</th>
               </tr>
            </thead>
            <tbody>
            <script type="text/javascript">
                $.get('<?=base_url()?>admin/ajax/getall', {'m' : 'fans', 's' : '<?=$menu . "/" . $section;?>'}, function(data){if(data.content){update(data)}}, 'json');
            </script>
            </tbody>
          </table>
          <div class="pagination"></div>
        </div>
   </div>
</div>

<div id="edit-frame" style="display:none">
</div>