
    <section>
        <!-- Left Sidebar -->
        
         <aside id="leftsidebar" class="sidebar" > 
            <!-- User Info -->
            <div class="user-info">
                <!-- <?php $usrdata// = $this->session->userdata('customer');?> -->
                <h4 style="color: white;">Power Of Ten</h4>
                <div class="image">
                  <!--   <img src="<?php //echo PROFILEIMGFOLDER.'uploads/mh_doctor_photo/'.$usrdata['doctor_profile_photo']; ?>" width="55" height="55" alt="User" />
 -->
                </div>
               <!--  <div class="info-container">

                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php //echo $usrdata['username']; ?></div>
                    <div class="email"><?php //echo $usrdata['email']; ?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li class="hide"><a href='<?php //echo URL."maharashtra_doctor/doc_profile" ?>'><i class="material-icons">person</i>Profile</a></li>
                            <li role="separator" class="divider"></li>
                           
                            <li><a href="<?php //echo URL."auth/logout"?>"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div> -->
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                    <li <?php if($current_page == 'homepage') { echo 'class="active"';} ?>>
                        <a href='<?php echo URL."dar_activity_mgmt/to_dashboard"; ?>'>
                            <i class="material-icons">home</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <!-- <li <?php if($main_nav == 'Masters') { echo 'class="active"';} ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">add_to_photos</i>
                            <span>Masters</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php if($current_page == 'swaero mgmt states') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."panacea_mgmt/swaero_mgmt_states"; ?>'>
                                    <i class="material-icons">content_paste</i>
                                    <span>Manage States</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'swaero_mgmt_zonal') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."panacea_mgmt/swaero_mgmt_zonal"; ?>'>
                                    <i class="material-icons">content_paste</i>
                                    <span>Manage Zonal</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'swaero_mgmt_district') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."panacea_mgmt/swaero_mgmt_district"; ?>'>
                                    <i class="material-icons">content_paste</i>
                                    <span>Manage District</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'swaero_mgmt_mandal') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."panacea_mgmt/swaero_mgmt_mandal"; ?>'>
                                    <i class="material-icons">content_paste</i>
                                    <span>Manage Mandal</span>
                                </a>
                            </li>
                             <li <?php if($current_page == 'swaero_mgmt_village') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."panacea_mgmt/swaero_mgmt_village"; ?>'>
                                    <i class="material-icons">content_paste</i>
                                    <span>Manage Village</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'swaero_mgmt_network') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."panacea_mgmt/swaero_mgmt_network"; ?>'>
                                    <i class="material-icons">content_paste</i>
                                    <span>Manage swaero Network</span>
                                </a>
                            </li>
                        </ul>
                    </li> -->
                    <!-- <li <?php if($main_nav == 'Reports') { echo 'class="active"';} ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">assignment</i>
                            <span>Reports</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php if($current_page == 'swaero_user_reports') { echo 'class="active"';}?>>
                                <a href="<?php echo URL."panacea_mgmt/swaero_user_reports"; ?>">
                                    <i class="material-icons">note_add</i>
                                    <span>Users Reports</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li <?php if($main_nav == 'Imports') { echo 'class="active"';} ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">file_download</i>
                            <span>Imports</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php if($current_page == 'swaero_user_imports') { echo 'class="active"';}?>>
                                <a href="<?php echo URL."panacea_mgmt/swaero_user_imports"; ?>">
                                    <i class="material-icons">note_add</i>
                                    <span>Import Users</span>
                                </a>
                            </li>
                        </ul>
                    </li>   
                    <li <?php if($current_page == 'send_message') { echo 'class="active"';} ?>>
                        <a href='<?php echo URL."panacea_mgmt/send_message_to_schools"; ?>'>
                            <i class="material-icons">forum</i>
                            <span>Send Message</span>
                        </a>
                    </li>
                    <li <?php if($main_nav == 'News Feed') { echo 'class="active"';} ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">format_indent_increase</i>
                            <span>News Feed</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php if($current_page == 'Add_News_Feed') { echo 'class="active"';}?>>
                                <a href="<?php echo URL."panacea_mgmt/add_news_feed_view"; ?>">
                                    
                                    <span>Add News Feed</span>
                                </a>
                            </li>                            
                            <li <?php if($current_page == 'Manage_News_Feed') { echo 'class="active"';} ?>>
                              <a href='<?php echo URL."panacea_mgmt/manage_news_feed_view"; ?>'>
                                  
                                  <span>Manage News Feed</span>
                              </a>
                            </li>
                        </ul>
                    </li>   -->   
                   

                    

                   

                    <li <?php if($current_page == 'from_screening_abnormalities') { echo 'class="active"';} ?>>
                        <a href="<?php echo URL."auth/logout"?>">
                            <i class="material-icons">lock_outline</i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
                   
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    <a href="javascript:void(0);">Havik Healthcare Technologies</a>.
                </div>
                
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->
        <aside id="rightsidebar" class="right-sidebar">
            <ul class="nav nav-tabs tab-nav-right" role="tablist">
                <li role="presentation" class="active"><a href="#skins" data-toggle="tab">SKINS</a></li>
                <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active in active" id="skins">
                    <ul class="demo-choose-skin">
                        <li data-theme="red">
                            <div class="red"></div>
                            <span>Red</span>
                        </li>
                        <li data-theme="pink">
                            <div class="pink"></div>
                            <span>Pink</span>
                        </li>
                        <li data-theme="purple">
                            <div class="purple"></div>
                            <span>Purple</span>
                        </li>
                        <li data-theme="deep-purple">
                            <div class="deep-purple"></div>
                            <span>Deep Purple</span>
                        </li>
                        <li data-theme="indigo">
                            <div class="indigo"></div>
                            <span>Indigo</span>
                        </li>
                        <li data-theme="blue">
                            <div class="blue"></div>
                            <span>Blue</span>
                        </li>
                        <li data-theme="light-blue">
                            <div class="light-blue"></div>
                            <span>Light Blue</span>
                        </li>
                        <li data-theme="cyan">
                            <div class="cyan"></div>
                            <span>Cyan</span>
                        </li>
                        <li data-theme="teal">
                            <div class="teal"></div>
                            <span>Teal</span>
                        </li>
                        <li data-theme="green" class="active">
                            <div class="green"></div>
                            <span>Green</span>
                        </li>
                        <li data-theme="light-green">
                            <div class="light-green"></div>
                            <span>Light Green</span>
                        </li>
                        <li data-theme="lime">
                            <div class="lime"></div>
                            <span>Lime</span>
                        </li>
                        <li data-theme="yellow">
                            <div class="yellow"></div>
                            <span>Yellow</span>
                        </li>
                        <li data-theme="amber">
                            <div class="amber"></div>
                            <span>Amber</span>
                        </li>
                        <li data-theme="orange">
                            <div class="orange"></div>
                            <span>Orange</span>
                        </li>
                        <li data-theme="deep-orange">
                            <div class="deep-orange"></div>
                            <span>Deep Orange</span>
                        </li>
                        <li data-theme="brown">
                            <div class="brown"></div>
                            <span>Brown</span>
                        </li>
                        <li data-theme="grey">
                            <div class="grey"></div>
                            <span>Grey</span>
                        </li>
                        <li data-theme="blue-grey">
                            <div class="blue-grey"></div>
                            <span>Blue Grey</span>
                        </li>
                        <li data-theme="black">
                            <div class="black"></div>
                            <span>Black</span>
                        </li>
                    </ul>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="settings">
                    <div class="demo-settings">
                        <p>GENERAL SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Report Panel Usage</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Email Redirect</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>SYSTEM SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Notifications</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Auto Updates</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                        <p>ACCOUNT SETTINGS</p>
                        <ul class="setting-list">
                            <li>
                                <span>Offline</span>
                                <div class="switch">
                                    <label><input type="checkbox"><span class="lever"></span></label>
                                </div>
                            </li>
                            <li>
                                <span>Location Permission</span>
                                <div class="switch">
                                    <label><input type="checkbox" checked><span class="lever"></span></label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>
        <!-- #END# Right Sidebar -->
    </section>

   
