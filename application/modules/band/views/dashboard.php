<section id="main">
    <div id="mainwrap" class="clearfix grad-3 brd-lr shdw-3">
        <div id="content" class="clearfix">
            <?=partial('shared/menu', array('pagename' => 'dashboard'));?> 
            <div id="account-sidebar" class="left clearfix">
                <div class="photo">
                    <img src="<?=base_url('assets/images/'.THEME.'/nophoto.png');?>" alt="" />
                </div>
                <div class="sub">
                    <h4>Name</h4>
                    <a class="link2" href="<?=base_url('band/profile');?>">Edit profile</a>
                    <br />
                    <br />
                    <div class="box round-8">
                        <div class="top"><h2>Badges</h2></div>
                        <div class="list clearfix">
                             <div class="badges">
                                <ul>
                                    <li><?=isset($band['fanbase']) ? $band['fanbase'] : '';?> Fanbase</li>
                                    <li>LA Based</li>
                                    <li>Editor's Choise</li>
                                    <li>Top Notch Profile</li>
                                </ul>
                             </div>
                        </div>
                        <div class="bottom small">
                            Other badges available:<br />
                            <strong>Popularity</strong> - awarded when you are booked 5 times on Gigit.
                        </div>
                    </div>
                    <br />
                </div>
            </div>
            <div id="account-details" class="right">
                <h2>Gigs</h2>
                <div class="gig-details">
                    <table>
                        <thead>
                            <tr>
                            <th class="tB">Fan</th>
                            <th class="tG">Gig Date</th>
                            <th class="tL">Location</th>
                            <th class="tS">Status</th>
                            <th class="tA'">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <? foreach($bookings as $booking) {?>
                            <tr>
                                <td><?=$booking['first_name'] .' '. $booking['last_name'];?></td>
                                <td><?=$booking['gig_date'];?></td>
                                <td><?=$booking['city'];?></td>
                                <td><?=is_array($booking['status_text']) ? $booking['status_text'][1] : $booking['status_text'];?></td>
                                <td><?=$booking['status'] == BOOKINGS::STATUS_NEED_RESPONSE ? ('<a href="'.base_url('band/booking/confirm?code=' . $booking['code']) . '" class="link2">Confirm</a>'):($booking['status'] == BOOKINGS::STATUS_CONFIRMED ? ('<a href="#" class="link2 contact" data-contact="' . $booking['user_email']. '">Contact Fan</a>') : '');?></td>
                            </tr>
                            <? } ?>
                        </tbody>
                    </table>
                </div>
                <br />
                <br />
                <div id="block-band-dashboard" class="text">
                     <h2>Improve Your Profile</h2>
                     <p>Your profile page looks great! But here are a few things that you can do to get even more attention, leading to more bookings - then the sky's limit!</p>
                     <ol>
                        <li>
                            <span class="link2">Add an Instagram photo feed</span><br />
                            C’mon, we all know photos drive your fans crazy. Upload photos from your last gig or album cover.
                        </li>
                        <li>
                            <span class="link2">Add a bio</span><br />
                            You need to tell them why y’all are so awesome. This is your chance to brag! 
                        </li>
                        <li>
                            <span class="link2">Add audio tracks</span><br />
                            This is a no-brainer. Get your tracks up there - you sound amazing!
                        </li>
                     </ol>
                </div>
            </div>
        </div>
    </div>
</section>