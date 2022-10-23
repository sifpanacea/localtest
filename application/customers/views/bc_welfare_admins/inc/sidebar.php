
    <section>
        <!-- Left Sidebar -->
        
         <aside id="leftsidebar" class="sidebar" > 
            <!-- User Info -->
            <div class="user-info">
                <?php $usrdata = $this->session->userdata('customer');?>
                <div class="image">
                  <!--   <img src="<?php //echo PROFILEIMGFOLDER.'uploads/mh_doctor_photo/'.$usrdata['doctor_profile_photo']; ?>" width="55" height="55" alt="User" />
 -->
                </div>
                <div class="info-container">

                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $usrdata['username']; ?></div>
                    <div class="email"><?php //echo $usrdata['email']; ?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li class="hide"><a href='<?php echo URL."maharashtra_doctor/doc_profile" ?>'><i class="material-icons">person</i>Profile</a></li>
                            <li role="separator" class="divider"></li>
                           
                            <li><a href="<?php echo URL."auth/logout"?>"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
        <div class="menu">
            <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>
                    <li <?php if($current_page == 'homepage') { echo 'class="active"';} ?>>
                        <a href='<?php echo URL."bc_welfare_mgmt/basic_dashboard"; ?>'>
                            <i class="material-icons">home</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                   <!-- <li <?php //if($current_page == 'cc_raised_requests') { //echo 'class="active"';} ?>>
                        <a href='<?php //echo URL."panacea_mgmt/get_schools_health_status"; ?>'>
                            <i class="material-icons">subtitles</i>
                            <span>Schools Status</span>
                        </a>
                    </li> -->
                    <li <?php if($current_page == 'cc_raised_requests') { echo 'class="active"';} ?>>
                        <a href='<?php echo URL."bc_welfare_mgmt/disease_wise_students_list"; ?>'>
                            <i class="material-icons">add_circle</i>
                            <span>Health Track Chart</span>
                        </a>
                    </li>
                     <!-- <li <?php //if($current_page == 'cc_raised_requests') { //echo 'class="active"';} ?>>
                        <a href='<?php //echo URL."panacea_mgmt/sanitation_report"; ?>'>
                            <i class="material-icons">local_florist</i>
                            <span>Sanitation Report</span>
                        </a>
                    </li>
                    <li <?php //if($main_nav == 'Requests') { //echo 'class="active"';} ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">widgets</i>
                            <span>PIE INFO</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php //if($current_page == 'raised_requests') { //echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."panacea_mgmt/chronic_pie_view"; ?>'>
                                    <i class="material-icons">content_paste</i>
                                    <span>Chronic Pie</span>
                                </a>
                            </li>
                             <li <?php //if($current_page == 'cc_raised_requests') { //echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."panacea_mgmt/hospitalized_pie_view"; ?>'>
                                    <i class="material-icons">content_paste</i>
                                    <span>Hospitalised Pie</span>
                                </a>
                            </li>
                           
                            <li <?php //if($current_page == 'raised_requests') { //echo 'class="active"';} ?>>
                                <a href="javascript:void(0);" class="menu-toggle">
                                    <i class="material-icons">donut_small</i>
                                    <span>HB PIE Info</span>
                                </a>
                                <ul class="ml-menu">
                                <li <?php //if($current_page == 'submitted_requests') { //echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."panacea_mgmt/hb_pie_view"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>HB Monthly</span>
                                </a>
                                </li>
                                <li <?php //if($current_page == 'submitted_requests') { //echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."panacea_mgmt/hb_overall_dashboard"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>HB Gender Wise</span>
                                </a>
                                </li>
                                </ul>
                            </li> 
                         
                            <li <?php //if($current_page == 'submitted_requests') { //echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."maharashtra_doctor/fetch_doctor_submitted_requests_list"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>HB Pie</span>
                                </a>
                            </li> 
                            <li <?php //if($current_page == 'raised_requests') { //echo 'class="active"';} ?>>
                                <a href="javascript:void(0);" class="menu-toggle">
                                    <i class="material-icons">donut_small</i>
                                    <span>BMI PIE Info</span>
                                </a>
                                <ul class="ml-menu">
                                <li <?php //if($current_page == 'submitted_requests') { //echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."panacea_mgmt/bmi_pie_view"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>BMI Monthly</span>
                                </a>
                                </li>
                                <li <?php //if($current_page == 'submitted_requests') { //echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."panacea_mgmt/bmi_overall_dashboard"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>BMI Gender Wise</span>
                                </a>
                                </li>
                                </ul>
                            </li>
                            <li <?php //if($current_page == 'cured_requests') { //echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."panacea_mgmt/pie_export"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>Pie Export</span>
                                </a>
                            </li>
                        </ul>
                    </li> -->

                    <li <?php if($current_page == 'Pie_Export') { echo 'class="active"';} ?>>
                        <a href='<?php echo URL."bc_welfare_mgmt/pie_export"; ?>'>
                            <i class="material-icons">note_add</i>
                            <span>Pie Export</span>
                        </a>
                    </li>

                     <li <?php if($main_nav == 'Masters') { echo 'class="active"';} ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">add_to_photos</i>
                            <span>Masters</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php if($current_page == 'Manage_States') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_states"; ?>'>
                                    <i class="material-icons">content_paste</i>
                                    <span>Manage States</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'Manage_District') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_district"; ?>'>
                                    <i class="material-icons">content_paste</i>
                                    <span>Manage District</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'Manage_Diagnostic') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_diagnostic"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>Manage Diagnostic</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'Manage_Schools') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_schools"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>Manage Schools</span>
                                </a>
                            </li>
                             <li <?php if($current_page == 'Manage_classes') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_classes"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>Manage classes</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'Manage_Sections') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_sections"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>Manage Sections</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'Manage_Health_Supervisors') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_health_supervisors"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>Manage Health Supervisors</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'Manage_Hospitals') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_hospitals"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>Manage Hospitals</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'Manage_Doctors') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_doctors"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>Manage Doctors</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'Manage_Symptoms') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_symptoms"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>Manage Symptoms</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'Manage_Employees') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_emp"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>Manage Employees</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'Manage_CC_Users') { echo 'class="active"';} ?>>
                                <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_cc"; ?>'>
                                    <i class="material-icons">note_add</i>
                                    <span>Manage CC Users</span>
                                </a>
                            </li>
                        </ul>
                    </li>                  

                    <li <?php if($main_nav == 'Reports') { echo 'class="active"';} ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">assignment</i>
                            <span>Reports</span>
                        </a>
                        <ul class="ml-menu">
                           
                            <li <?php if($current_page == 'School_Reports') { echo 'class="active"';}?>>
                                <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_school"; ?>">
                                    <i class="material-icons">note_add</i>
                                    <span>School Reports</span>
                                </a>
                            </li>
                            
                            <li <?php if($current_page == 'Hospital_Reports') { echo 'class="active"';} ?>>
                              <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_hospital"; ?>'>
                                <i class="material-icons">note_add</i>
                                  <span>Hospital Reports</span>
                              </a>
                            </li>
                             <li <?php if($current_page == 'Doctor_Reports') { echo 'class="active"';}?>>
                                <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_doctors"; ?>">
                                    <i class="material-icons">note_add</i>
                                    <span>Doctor Reports</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'Student_Reports') { echo 'class="active"';}?>>
                                <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_students_filter"; ?>">
                                    <i class="material-icons">note_add</i>
                                    <span>Student Reports</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'Reports_download') { echo 'class="active"';}?>>
                                <a href="<?php echo URL."bc_welfare_mgmt/get_reports_download"; ?>">
                                    <i class="material-icons">note_add</i>
                                    <span>Download Reports</span>
                                </a>
                            </li> 
                             <li <?php if($current_page == 'Passedouts Students') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_passedouts_students"; ?>">
                                        <i class="material-icons">note_add</i>
                                        <span>Passed outs Students</span>
                                    </a>
                                </li>
                            <li <?php if($current_page == 'Symptoms') { echo 'class="active"';}?>>
                                <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_symptom"; ?>">
                                    <i class="material-icons">note_add</i>
                                    <span>Symptoms</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'Electronic_Health_Record') { echo 'class="active"';}?>>
                                <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_ehr"; ?>">
                                    <i class="material-icons">note_add</i>
                                    <span>Electronic Health Record</span>
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
                           
                            <li <?php if($current_page == 'Diagnostics') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_imports_diagnostic"; ?>">
                                       
                                        <span>Diagnostics</span>
                                    </a>
                            </li>                            
                            <li <?php if($current_page == 'Hospitals') { echo 'class="active"';} ?>>
                              <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_imports_hospital"; ?>'>
                                 
                                  <span>Hospitals</span>
                              </a>
                            </li>
                             <li <?php if($current_page == 'Schools') { echo 'class="active"';}?>>
                                <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_imports_school"; ?>">
                                  
                                    <span>Schools</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'Health Supervisors') { echo 'class="active"';}?>>
                                <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_imports_health_supervisors"; ?>">
                                  
                                    <span>Health Supervisors</span>
                                </a>
                            </li>
                            <li <?php if($current_page == 'Import Students') { echo 'class="active"';}?>>
                                <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_imports_students"; ?>">
                                    
                                    <span>Import Students</span>
                                </a>
                            </li>
                             <li <?php if($current_page == 'Upgrade Classes') { echo 'class="active"';}?>>
                                <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_upgrade_students_classes"; ?>">
                                    
                                    <span>Upgrade Classes</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li <?php if($current_page == 'send_message') { echo 'class="active"';} ?>>
                        <a href='<?php echo URL."bc_welfare_mgmt/send_message_to_schools"; ?>'>
                            <i class="material-icons">forum</i>
                            <span>Send Message</span>
                        </a>
                    </li>

                    <li <?php if($current_page == 'parent_registration') { echo 'class="active"';} ?>>
                        <a href='<?php echo URL."bc_welfare_mgmt/get_parent_health_registration"; ?>'>
                            <i class="material-icons">recent_actors</i>
                            <span>Parent Registration</span>
                        </a>
                    </li>

                    <li <?php if($current_page == 'Request_Notes') { echo 'class="active"';}?>>
                       <a href="<?php echo URL."bc_welfare_mgmt/requests_notes"; ?>">
                           <i class="material-icons">assignment_ind</i>
                           <span>Request Notes</span>
                       </a>
                   </li>

                    <li <?php if($main_nav == 'News Feed') { echo 'class="active"';} ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">format_indent_increase</i>
                            <span>News Feed</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php if($current_page == 'Add_News_Feed') { echo 'class="active"';}?>>
                                <a href="<?php echo URL."bc_welfare_mgmt/add_news_feed_view"; ?>">
                                    
                                    <span>Add News Feed</span>
                                </a>
                            </li>                            
                            <li <?php if($current_page == 'Manage_News_Feed') { echo 'class="active"';} ?>>
                              <a href='<?php echo URL."bc_welfare_mgmt/manage_news_feed_view"; ?>'>
                                  
                                  <span>Manage News Feed</span>
                              </a>
                            </li>
                        </ul>
                    </li>
                    <li <?php if($current_page == 'from_screening_abnormalities') { echo 'class="active"';} ?>>
                        <a href="<?php echo URL."auth/logout"?>">
                            <i class="material-icons">lock_outline</i>
                            <span>Logout</span>
                        </a>
                    </li>

                    

                    <!-- <li <?php //if($current_page == 'from_screening_abnormalities') { echo 'class="active"';} ?>>
                        <a href='<?php //echo URL."panacea_mgmt/contact_numbers"; ?>'>
                            <i class="material-icons">phone_in_talk</i>
                            <span>Contacts</span>
                        </a>
                    </li> -->

                    
                   
                    <!--  <li class="hide">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_list</i>
                            <span>Reports</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php //if($current_page == 'screened_notscreened') { echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."maharashtra_doctor/screened_and_not_screened_students_list"; ?>'>
                                    <span>Screened Students List</span>
                                </a>
                            </li>


                           
                        </ul>
                    </li> -->
                    
                </ul>
            </div>

                    <!-- <li <?php //if($current_page == 'from_screening_abnormalities') { echo 'class="active"';} ?>>
                        <a href='<?php //echo URL."maharashtra_doctor/list_abnormalities_from_screening"; ?>'>
                            <i class="material-icons">local_pharmacy</i>
                            <span>Problem Wise Students Report</span>
                        </a>
                    </li>
                    <li <?php //if($current_page == 'disease_wise_students_list') { echo 'class="active"';} ?>>
                        <a href='<?php //echo URL."maharashtra_doctor/disease_wise_students_list"; ?>'>
                            <i class="material-icons">track_changes</i>
                            <span>Student Health Track Chart</span>
                        </a>
                    </li>
                    <li <?php //if($current_page == 'disease_comparission_per_month') { echo 'class="active"';} ?>>
                        <a href='<?php //echo URL."maharashtra_doctor/disease_comparission_per_month"; ?>'>
                            <i class="material-icons">compare</i>
                            <span>Disease Comparission Per month</span>
                        </a>
                    </li>
                    <li <?php //if($current_page == 'screening_information') { echo 'class="active"';} ?>>
                        <a href='<?php //echo URL."maharashtra_doctor/show_screening_information"; ?>'>
                            <i class="material-icons">add_to_photos</i>
                            <span>Screening Information</span>
                        </a>
                    </li>

                    <li <?php //if($current_page == 'max_raised_requests') { echo 'class="active"';} ?>>
                        <a href='<?php //echo URL."maharashtra_doctor/maximum_raised_requests"; ?>'>
                            <i class="material-icons">add_to_photos</i>
                            <span>Max Raised Requests</span>
                        </a>
                    </li> -->

                   
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

   
