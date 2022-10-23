<?php $current_page = "homepage"; ?>
<?php $main_nav = ""; ?>
<?php include("inc/header_bar.php"); ?>
<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">

<style type="text/css">
    .status_blink{
       
       color: rgb (0, 137, 226);
       animation: blink 3s infinite;
       padding: 10px;
       font-size: 20px;
   }
    @keyframes blink{
       0%{opacity: 1;}
       75%{opacity: 1;}
       76%{ opacity: 0;}
       100%{opacity: 0;}
}

body {
  font-family: "Lato", sans-serif;
}

.sidepanel  {
  width: 0;
  position: fixed;
  z-index: 1;
  height: 250px;
  top: 100px;
  right: 0;
  background-color: #42f2f5;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidepanel a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 15px;
  color: black;
  display: block;
  transition: 0.3s;
}


.sidepanel a:hover {
  color: red;
}

.sidepanel .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
}

.openbtn {
  font-size: 15px;
  cursor: pointer;
  background-color: white;
  color: black;
  padding: 5px 5px;
  border: none;
}

.openbtn:hover {
  background-color:white;
}

</style>
<br>
<br>
<br>
<br>



<div class="container-fluid">
     <button class="btn btn-block btn-lg bg-pink waves-effect">
        <?php if(!empty($news)): ?>
            <marquee  direction="left" onmouseover="this.stop();" onmouseout="this.start();" onclick ="">
            <?php foreach($news as $new): ?>
           <img src="<?php echo IMG; ?>/Panacea_small.png"><span class="label bg-green"><?php echo $new['username']; ?></span><?php echo $new['news_feed']; ?> <a href="#" class="open_news" news_data="<?php echo $new['_id']; ?>" style="color:#0a0a0a"></a>&nbsp; &nbsp;
        <?php endforeach; ?>
        </marquee>
        <?php else: ?>
            <marquee  direction="left"><img src="<?php echo IMG; ?>/Panacea_small.png">No News Today</marquee>
        <?php endif; ?>
    </button>
    <br>
    <br>

<!--FIlters For Admin Dashboard-->
<!-- <div class="row clearfix"> -->
    <div id="collapseExample" class="collapse panel">
        <div class="panel-body">
            <div class="row clearfix">
            <!-- Academic Year Filter -->
                <div class="col-sm-2">
                    <label>Academic Year</label>
                    <select class="form-control show-tick academic_filter common_change" id="academic_filter">
                        <option value="2021-2022" selected="">2021-2022 AcademicYear</option>
                        <option value="2020-2021">2020-2021 AcademicYear</option>
                        <option value="2019-2020">2019-2020 AcademicYear</option>
                        <option value="2018-2019">2018-2019 AcademicYear</option>
                        <option value="2017-2018">2017-2018 AcademicYear</option>
                        <option value="2016-2017">2016-2017 AcademicYear</option>   
                        <option value="2015-2016">2015-2016 AcademicYear</option>
                    </select>
                </div>
            <!-- District Filter -->
                <div class="col-sm-2">
                    <label>District</label>
                    <select class="form-control show-tick district_filter common_change" id="district_filter">
                        <option value="All"  selected="">All</option>
                        <?php if(isset($distslist)): ?>
                            <?php foreach ($distslist as $dist):?>
                                <option value='<?php echo $dist['dt_name']?>'><?php echo ucfirst($dist['dt_name'])?></option>
                            <?php endforeach;?>
                        <?php else: ?>
                            <option value="1"  disabled="">No district entered yet</option>
                        <?php endif ?>
                    </select>
                </div>
                <!-- School FIlter -->
                <div class="col-sm-1">
                    <label>School</label>
                    <select class="form-control show-tick school_filter common_change" id="school_filter" disabled=true >
                      <option value="All" >All</option>  
                    </select>
                </div>
                <div class="col-sm-1">
                    <label>Gender</label>
                    <select class="form-control show-tick gender_filter common_change" id="gender_filter">
                        <option class="student_type_for_tails" value="All" checked>All</option>
                        <option name="student_type" class="student_type_for_tails" id="student_type_boys" value="Male">Male</option>
                        <option name="student_type" class="student_type_for_tails" id="student_type_girls"value="Female">Female</option>
                    </select>
                </div>
                <div class="col-sm-1">
                    <label>Choose date</label>
                    <div class="form-line">
                        <input type="text" id="set_date" name="set_date" class="datepicker form-control date set_date" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div> 
                <!-- <div class="col-sm-1">
                    <label>Choose date</label>
                    <div class="form-line">
                        <input type="text" id="set_date" name="set_date" class="datepicker form-control date set_date" value="<?php //echo date('yy-m-d'); ?>">
                    </div>
                </div>
                <div class="col-sm-1">
                    <label>Start date</label>
                    <div class="form-line">
                        <input type="text" id="start_date_request" name="start_date_request" class="datepicker form-control date start_date_request" value="<?php //echo date('yy-m-d'); ?>">
                    </div>
                </div>
                <div class="col-sm-1">
                     <label>End date</label>
                    <div class="form-line">
                        <input type="text" id="end_date_request" name="end_date_request" class="datepicker form-control date end_date_request" value="<?php //echo date('yy-m-d'); ?>">
                    </div>
                </div>  
                <div class="col-sm-1">
                    <div class="form-line">
                        <button type="button" id="request_date_set_span" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                            <i class="material-icons">search</i>
                        </button>
                    </div>
                </div> -->
                <div class="col-sm-5">
                    <label>Options</label>
                    <div class="demo-single-button-dropdowns">
                    <!-- <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Pie Info <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li <?php //if($current_page == 'raised_requests') { echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."panacea_mgmt/chronic_pie_view"; ?>'>
                                    <i class="material-icons">navigate_next</i>
                                    <span>Chronic Pie</span>
                                </a>
                            </li>
                            <li <?php //if($current_page == 'cc_raised_requests') { echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."panacea_mgmt/hospitalized_pie_view"; ?>'>
                                    <i class="material-icons">navigate_next</i>
                                    <span>Hospitalised Pie</span>
                                </a>
                            </li>
                            <li <?php //if($current_page == 'submitted_requests') { echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."panacea_mgmt/hb_pie_view"; ?>'>
                                    <i class="material-icons">navigate_next</i>   
                                    <span>HB Monthly</span>
                                </a>
                            </li>
                            <li <?php //if($current_page == 'submitted_requests') { echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."panacea_mgmt/hb_overall_dashboard"; ?>'>
                                    <i class="material-icons">navigate_next </i>    
                                    <span>HB Gender Wise</span>
                                </a>
                            </li>
                            <li <?php //if($current_page == 'submitted_requests') { echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."panacea_mgmt/bmi_pie_view"; ?>'>
                                    <i class="material-icons">navigate_next</i>    
                                    <span>BMI Monthly</span>
                                </a>
                            </li>
                            <li <?php //if($current_page == 'submitted_requests') { echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."panacea_mgmt/bmi_overall_dashboard"; ?>'>
                                     <i class="material-icons">navigate_next</i>  
                                    <span>BMI Gender Wise</span>
                                </a>
                            </li>
                            <li <?php //if($current_page == 'cured_requests') { echo 'class="active"';} ?>>
                                <a href='<?php //echo URL."panacea_mgmt/pie_export"; ?>'>
                                    <i class="material-icons">navigate_next</i>
                                    <span>Pie Export</span>
                                </a>
                            </li>
                        </ul>
                    </div> -->
                                 
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Masters <span class="caret"></span>
                        </button>
                            <ul class="dropdown-menu">
                                <li <?php if($current_page == 'Manage_States') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_states"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Manage States</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Manage_District') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_district"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Manage District</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Manage_Diagnostics') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_diagnostic"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Manage Diagnostic</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Manage_Schools') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_schools"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Manage Schools</span>
                                    </a>
                                </li>
                                 <li <?php if($current_page == 'Manage_classes') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_classes"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Manage classes</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Manage_Sections') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_sections"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Manage Sections</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Manage_Health_Supervisors') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_health_supervisors"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Manage Health Supervisors</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Manage_Hospitals') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_hospitals"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Manage Hospitals</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Manage_Doctors') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_doctors"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Manage Doctors</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Manage_Symptoms') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_symptoms"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Manage Symptoms</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Manage_Employees') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_emp"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Manage Employees</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Manage_CC_Users') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_cc"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Manage CC Users</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Reports<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li <?php if($current_page == 'School_Reports') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_school"; ?>">
                                        <i class="material-icons">navigate_next</i>
                                        <span>School Reports</span>
                                    </a>
                                </li>
                                
                                <li <?php if($current_page == 'Hospital_Reports') { echo 'class="active"';} ?>>
                                  <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_hospital"; ?>'>
                                      <i class="material-icons">navigate_next</i>
                                      <span>Hospital Reports</span>
                                  </a>
                                </li>

                                <li <?php if($current_page == 'blood banks') { echo 'class="active"';} ?>>
                                  <a href='<?php echo URL."bc_welfare_mgmt/panacea_blood_blanks_report"; ?>'>
                                      <i class="material-icons">navigate_next</i>
                                      <span>Blood Banks Data</span>
                                  </a>
                                </li>

                                 <li <?php if($current_page == 'cured_requests') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/panacea_mgmt_dmho_numbers"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Manage DM & HO</span>
                                    </a>
                                </li>

                                <li <?php if($current_page == 'Doctor_Reports') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_doctors"; ?>">
                                        <i class="material-icons">navigate_next</i>
                                        <span>Doctor Reports</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Student_Reports') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_students_filter"; ?>">
                                        <i class="material-icons">navigate_next</i>
                                        <span>Student Reports</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Diseases Counts') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/get_reports_download"; ?>">
                                        <i class="material-icons">navigate_next</i>
                                        <span>Download Reports</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Passedouts Students') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_passedouts_students"; ?>">
                                        <i class="material-icons">navigate_next</i>
                                        <span>2019-20 Passed outs Students</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Symptoms') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_symptom"; ?>">
                                        <i class="material-icons">navigate_next</i>
                                        <span>Symptoms</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Electronic_Health_Record') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_ehr"; ?>">
                                        <i class="material-icons">navigate_next</i>
                                        <span>Electronic Health Record</span>
                                    </a>
                                </li>

                                <li <?php if($current_page == 'Diseases Counts') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/napkin_distribution_reports"; ?>">
                                       <i class="material-icons">navigate_next</i>
                                       <span>Napkin Distribution Reports</span>
                                    </a>
                               </li>
                               
                            </ul>
                        </div>
                                         
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Imports<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li <?php if($current_page == 'Diagnostics') { echo 'class="active"';}?>>
                                        <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_imports_diagnostic"; ?>">
                                            <i class="material-icons">navigate_next</i>
                                            <span>Diagnostics</span>
                                        </a>
                                </li>                            
                                <li <?php if($current_page == 'Hospitals') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/bc_welfare_imports_hospital"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Hospitals</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Schools') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_imports_school"; ?>">
                                        <i class="material-icons">navigate_next</i>
                                        <span>Schools</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Health Supervisors') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_imports_health_supervisors"; ?>">
                                        <i class="material-icons">navigate_next</i>
                                        <span>Health Supervisors</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Import Students') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_imports_students"; ?>">
                                        <i class="material-icons">navigate_next</i>
                                        <span>Import Students</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Upgrade Classes') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_upgrade_students_classes"; ?>">
                                        <i class="material-icons">navigate_next</i>
                                        <span>Upgrade Classes</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                News Feed<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li <?php if($current_page == 'Add_News_Feed') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/add_news_feed_view"; ?>">
                                        <i class="material-icons">navigate_next</i>
                                        <span>Add News Feed</span>
                                    </a>
                                </li>                            
                                <li <?php if($current_page == 'Manage_News_Feed') { echo 'class="active"';} ?>>
                                  <a href='<?php echo URL."bc_welfare_mgmt/manage_news_feed_view"; ?>'>
                                      <i class="material-icons">navigate_next</i>
                                      <span>Manage News Feed</span>
                                  </a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Miscellaneous<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <!-- <li <?php //if($current_page == 'School_health_status') { //echo 'class="active"';} ?>>
                                    <a href='<?php //echo URL."panacea_mgmt/get_schools_health_status"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Schools Status</span>
                                    </a>
                                </li> -->

                                 <li <?php if($current_page == 'Disease_wise_Students_list') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/schools_overall_information"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Schools Information</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Health Assistants Counts') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/health_assistancts_status"; ?>">
                                       <i class="material-icons">navigate_next</i>
                                       <span>Health & Technical Assis.. Submitted Requests</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'Disease_wise_Students_list') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/disease_wise_students_list"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Health Track Chart</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'pie_export') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/pie_export"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Pie Export</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'send_message') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/send_message_to_schools"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Send Message</span>
                                    </a>
                                </li>
                                <li <?php if($current_page == 'parent_registration') { echo 'class="active"';} ?>>
                                    <a href='<?php echo URL."bc_welfare_mgmt/get_parent_health_registration"; ?>'>
                                        <i class="material-icons">navigate_next</i>
                                        <span>Parent Registration</span>
                                    </a>
                                </li>                               
                                <li <?php if($current_page == 'Diseases Counts') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/requests_notes"; ?>">
                                       <i class="material-icons">navigate_next</i>
                                       <span>Request Notes</span>
                                    </a>
                               </li>
                                <li <?php if($current_page == 'Diseases Counts') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_dr_not_responded_docs"; ?>">
                                        <i class="material-icons">navigate_next</i>
                                        <span>Dr Not Responded Docs</span>
                                    </a>
                                </li>

                                <li <?php if($current_page == 'Diseases Counts') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/panacea_aarogyasri_hospitals_list"; ?>">
                                       <i class="material-icons">navigate_next</i>
                                       <span>Aarogyasri Hospitals</span>
                                    </a>
                               </li>

                                <li <?php if($current_page == 'Diseases Counts') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/panacea_organ_transplant_hospitals_list"; ?>">
                                       <i class="material-icons">navigate_next</i>
                                       <span>Organ Transplant Hospitals</span>
                                    </a>
                               </li>

                               <li <?php if($current_page == 'Diseases Counts') { echo 'class="active"';}?>>
                                    <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_ro_plant_incinarators"; ?>">
                                        <i class="material-icons">navigate_next</i>
                                        <span>RO Plants & Incinarators</span>
                                    </a>
                                </li>                               


                            </ul>
                        </div>

                        <div class="btn-group">
                      
                        <div id="mySidepanel" class="sidepanel">
                          <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                          
                            <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_covid_cases"; ?>">
                                <i class="material-icons">navigate_next</i>
                                <span>Covid-19 Cases</span>
                            </a>

                            <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_covid_cured_cases"; ?>">
                                <i class="material-icons">navigate_next</i>
                                <span>Covid Cured Cases</span>
                            </a> 

                            <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_staff_covid_cases"; ?>">
                                <i class="material-icons">navigate_next</i>
                                <span>Staff Covid Cases</span>
                            </a> 
                           
                            <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_deadth_cases"; ?>">
                                <i class="material-icons">navigate_next</i>
                                <span>Expired Cases</span>
                            </a>                            

                        </div>
                        <button class="openbtn" data-toggle="tooltip" data-placement="bottom" title="To See Covid-19 Cases" onclick="openNav()">PanelBar</button> 
                    </div>

                    </div>    
                </div>
            </div>
        </div>
    </div>
<!-- </div> -->
<!-- End FIlters For Admin Dashboard -->

<!-- Info Cards Requests Information -->
<div class="row clearfix">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="card">
        <div class="btn-group" > 
            <select class="form-control show-tick school_health_zone_wise" id="school_health_zone_wise">
                  <option value="Scabies">Scabies</option>
                  <option value="Abnormalities">Abnormalities</option>
                  <option value="Bites">Bites</option>
                  <option value="HB">HB Status</option>
                  <option value="BMI">BMI status</option>
            </select>
        </div>
         <!-- <small><b> Select School Health Status For.....</b></small> -->
        <button id="refresh_scl"  style="float: right;">
            <i class="material-icons">loop</i>
        </button>
        <a href="javascript:void(0);" class="btn waves-effect" data-toggle="modal" data-target="#defaultModalshs" role="button" title="Health Status Criteria" aria-haspopup="true" aria-expanded="false" style="float: right;">
            <i class="material-icons">info_outline</i>
        </a>

        <div id="columnchart_values"></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="card">
            <div class="header" style="padding: 3px">
                <input type="radio" name="update_bar_radio" id="daily_health_bar" value="daily_health_bar" class="with-gap radio-col-pink" checked/>
                <label for="daily_health_bar"><b>Daily Health Request</b></label>

                 <input type="radio" name="update_bar_radio" id="updated_request_bar" value="updated_request_bar" class="with-gap radio-col-pink" />
                <label for="updated_request_bar"><b>Update Health Request</b></label>                
            </div>

            <div class="row clearfix" id="daily_time_span_filter" style="margin-left: 1px;">
                
                <div class="col-sm-4" >
                    <small><b>Start Date</b></small>
                    <input type="text" id="start_date_request" style="margin-right: -11px;" name="start_date_request" class="datepicker form-control date start_date_request" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-sm-4">
                    <small><b>End Date</b></small>
                    <input type="text" id="end_date_request" style="margin-right: -11px;" name="end_date_request" class="datepicker form-control date end_date_request" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-sm-4">
                    <button type="button" id="request_date_set_span" style="margin-top: 9px;" class="btn bg-green btn-circle waves-effect waves-circle waves-float">
                        <i class="material-icons">search</i>
                    </button>
                </div>
            </div>
          
            <div class="body">    
            <div id="columnchart_requests"></div>

            <div class="row clearfix" id="update_time_span_filter" style="display: none;margin-top: -10px;"> 
                    <div class="col-sm-5">                       
                        <span id="monitoring_datepicker">
                            Start Date
                        <input type="text" id="update_passing_date" name="update_passing_date" class="datepicker form-control date" value="<?php echo date('Y-m-d'); ?>">
                        </span>
                    </div>
                    <div class="col-sm-5">
                        <span id="monitoring_datepicker">
                           End Date
                        <input type="text" id="update_passing_end_date" name="update_passing_end_date" class="datepicker form-control date" value="<?php echo date('Y-m-d'); ?>">
                        </span>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-line">
                            <button type="button" id="update_date_set" style="margin-top: 5px;" class="btn bg-green btn-circle waves-effect waves-circle waves-float">
                                <i class="material-icons">search</i>
                            </button>
                        </div>
                    </div> 
                </div>
                <div id="ubdated_type_columnchart" style="display: none;margin-top: -5px;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="info-box-3 bg-orange hover-zoom-effect">
                    <div class="icon">
                        <div class="chart chart-bar">162, 178411</div>
                    </div>
                    <div class="content">                      
                        <!-- <a href="javascript:void(0);" data-target="#total_schools_list" class="total_schools_list" data-toggle="modal">
                           <div id="total_schools_count"></div>
                        </a> -->
                        <div id="total_schools_count"></div>                       
                    </div>
                    <div class="content">
                        <a href="<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_students_filter"; ?>">
                            <div id="totalStudentCount"></div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="info-box-3 bg-light-green hover-zoom-effect">
                    <div class="icon">
                        <div class="chart chart-bar">91749, 83782</div>
                    </div>
                    <div class="content">
                        <div id="screened_school_count"></div>
                    </div>
                    <div class="content">
                        <div id="not_screened_school_count"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="info-box-3 bg-cyan hover-zoom-effect">
                    <div class="icon">
                        <div class="chart chart-bar">273, 0</div>
                    </div>
                    <div class="content">
                        <div id="total_screened_stud_count"></div>
                    </div>
                    <div class="content">
                        <div id="not_screened_stud_count"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="info-box-3 bg-grey hover-zoom-effect">
                    <div class="icon">
                        <i class="material-icons">queue</i>
                    </div>
                    <div class="content">
                        <!-- <div class="text">Cured Requests</div>
                        <div class="number">14311</div> -->
                        <a href="<?php echo URL."bc_welfare_mgmt/panacea_today_followup_cases"; ?>" class="badge bg-red status_blink">Today Followup cases :<span class="badge bg-green" style="padding: 8px;"><?php echo $regular_alerts; ?></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Info Cards Requests Information -->

<!-- Screening and requests cards Info -->
<div class="row clearfix">
    <!-- Screening PIE -->
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
        <div class="card">
            <div class="header" style="padding: 15px">
                <h2>Screening Pie</h2>
                <!-- <input type="radio" name="screening_pie_radio" id="screening_pie" value="screening_pie" class="with-gap radio-col-pink" checked/>
                <label for="screening_pie"><b>Screening Pie</b></label>
                <input type="radio" name="screening_pie_radio" id="request_pie" value="request_pie" class="with-gap radio-col-pink"/>
                <label for="request_pie"><b>Request Pie</b></label> -->
                <ul class="header-dropdown m-r--5">
                    <li>
                        <button id="refresh_screening">
                            <i class="material-icons">loop</i>
                        </button>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">info_outline</i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <h5 class="text-center"><span class="badge bg-teal">Screening Pie Criteria</span></h5>
                            <table class="table">
                                <td>Pie is based on Screening done at schools by Screening team.It displays the screening data of the students and can get drilled up to students Electronic Health Record.</td>
                            </table>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div id="piechart" style=" height: 300px;"></div>
                <!-- <div id="piechart_3d" style="display: none;"></div> -->
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="header" style="padding: 10px">
                <input type="radio" name="requests_pie_radio" id="Hospitalized_pie" value="Hospitalized_pie" class="with-gap radio-col-pink" checked/>
                <label for="Hospitalized_pie"><b>Hospitalized Bar</b></label>

                <input type="radio" name="requests_pie_radio" id="Hospitalized_type"  class="with-gap radio-col-pink" value="Hospitalized_type"  />
                <label for="Hospitalized_type"><b>Hospital Type</b></label>
       
                <input type="radio" name="requests_pie_radio" id="Admitted_cases"  class="with-gap radio-col-pink" value="Admitted_cases"  />
                <label for="Admitted_cases"><b>Admitted Cases Issues</b></label>
            </div>
            <div class="body">
               
                <div class="row clearfix" id="time_span_filter" style="margin-bottom: -25px;">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">                    
                   
                        <div class="col-sm-4">                       
                            <span id="monitoring_datepicker">
                                From Date
                               <input type="text" id="h_passing_date" name="h_passing_date" class="form-control date" value="<?php echo date('Y-m-d'); ?>">
                            </span>
                        </div>
                        <div class="col-sm-4">
                            <span id="monitoring_datepicker">
                               To Date
                               <input type="text" id="h_passing_end_date" name="h_passing_end_date" class="form-control date" value="<?php echo date('Y-m-d'); ?>">
                            </span>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-line">
                                <button type="button" id="h_date_set" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                                    <i class="material-icons">search</i>
                                </button>
                            </div>
                        </div>
                    
                    <!-- <div class="col-sm-1">
                       <ul class="header-dropdown m-r--3">
                         <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#multicollapseExample" aria-controls="multicollapseExample"  data-placement="bottom" title="Date Filters"><img src="<?php //echo IMG ;?>/filter_icon.png"></button>
                      </ul>
                    </div> -->
                    </div>
                </div>  
                 <div id="hospitalized_bar_graph"></div>
                   
                    <div class="row clearfix" id="type_time_span_filter" style="display: none; margin-bottom: -20px;">                   
                    <div id="typemulticollapseExample">
                      <div class="col-sm-4">                       
                           <span id="monitoring_datepicker">
                            From Date
                           <input type="text" id="type_passing_date" name="type_passing_date" class="form-control date" value="<?php echo date('Y-m-d'); ?>">
                           </span>
                      </div>
                      <div class="col-sm-4">
                           <span id="monitoring_datepicker">
                           To Date
                           <input type="text" id="type_passing_end_date" name="type_passing_end_date" class="form-control date" value="<?php echo date('Y-m-d'); ?>">
                           </span>
                      </div>
                      <div class="col-sm-1">
                          <div class="form-line">
                                <button type="button" id="type_date_set" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                                <i class="material-icons">search</i>
                                </button>
                          </div>
                      </div>
                    </div>
                   <!--  <div class="col-sm-1">
                      <ul class="header-dropdown m-r--3">
                         <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#typemulticollapseExample" aria-controls="typemulticollapseExample"  data-placement="bottom" title="Date Filters"><img src="<?php// echo IMG ;?>/filter_icon.png"></button>
                     </ul>
                   </div> -->
                </div>  

                <div id="hospital_type_graph"  style="display: none;"></div>
                <div class="row clearfix" id="filter_for_table" style="display: none;">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
                    <div class="col-sm-4">
                    <?php $end_date  = date ( "Y-m-d", strtotime ( $today_date . "-90 days" ) ); ?>
                       <span id="monitoring_datepicker">
                       From Date:
                       <input type="text" id="passing_date" name="passing_date" class="form-control date" value="<?php echo $end_date; ?>">
                       </span>
                    </div>
                    <div class="col-sm-4">
                       <span id="monitoring_datepicker">
                       To Date: 
                       <input type="text" id="passing_end_date" name="passing_end_date" class="form-control date" value="<?php echo $today_date; ?>">
                       </span>
                    </div>
                    <div class="col-sm-4">
                       <div class="form-line">
                           <button type="button" id="date_set" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                           <i class="material-icons">search</i>
                           </button>
                       </div>
                    </div>
                </div>
            </div>
                <div  id="request_hospitalized_table_view" style="display: none;"></div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
        <div class="card">
            <div class="header" style="padding: 10px">
                 <h2>Quick Glance At Issues</h2>
            </div>
            <div class="body">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" >
                    
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active" style="height: 290px;">
                            <div class="card">
                                <div class="body bg-teal" style="height:352px;">
                                    <div class="font-bold m-b--35">QUICK GLANCE ON PROBLEMS
                                        <!-- <span class="font-bold m-l--20 pull-right" id="bs_datepicker_container" style="width: 100px;">
                                        <input type="text" id="set_date" name="set_date" class="datepicker form-control date" value="<?php //echo date('yy-m-d'); ?>">
                                        </span> -->
                                        <br>
                                    <small>Click on Below problems to see Students</small>
                                    </div>
                                    <div id="day_to_day_glance"></div>
                                </div>
                            </div>
                        </div>


                    <?php foreach ($emergency_alerts as $pic)  : ?><!-- Mentioned about this in the controller -->
                  
                        <div class="item">
                         <div class="body"  style="height: 290px;">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <!-- <div class="form-group"> -->
                                           <label>Student UniqueID</label>
                                            <div class="form-line">
                                                <input type="text" class="form-control" placeholder="col-sm-3" value="<?php echo $pic['uniqid']; ?>" />
                                            </div>
                                       <!--  </div> -->
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                       <!--  <div class="form-group"> -->
                                            <label>Student Name</label>
                                            <div class="form-line">
                                                <input type="text" class="form-control" placeholder="col-sm-3" value="<?php echo $pic['name']; ?>" />
                                            </div>
                                       <!--  </div>      -->   
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <!-- <div class="form-group"> -->
                                            <div class="form-line">
                                                <label>Description</label>
                                                <textarea cols="45" readonly=""><?php echo $pic['discription']; ?></textarea>
                                            </div>
                                       <!--  </div> -->
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <?php if(isset($pic['pics'])): ?>
                                       <img class="" src="<?php echo URLCustomer.$pic['pics'];?>" height="125" width="150" alt="Student Photo">
                                        <?php else: ?>
                                            <img class="" src="<?php echo IMG; ?>/demo/Student_photo.png" alt="Student Photo">
                                    <?php endif; ?>
                                    
                                    <a class='ldelete' href='<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_display_ehr_uid/"?>? id = <?php echo $pic['uniqid']; ?>'>
                                    <button type="button" style="width: 82%;" class="btn bg-pink waves-effect">Show EHR</button>
                                    </a> 
                                </div>
                            </div>
                        </div>
                    </div>   
                <?php endforeach; ?>
                    </div>
                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div> 
            </div>
        </div>
    </div>
</div>
<!-- End Screening and requests cards Info -->

<!-- Second row card Info HB and BMI -->
<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="header" style="padding: 10px">
                <!-- <h2>Yearly Requests Pie</h2> -->
                <input type="radio" name="disease_pie_radio" id="yearly_request_pie"  class="with-gap radio-col-pink" value="yearly_request_pie"  checked />
                <label for="yearly_request_pie"><b>Yearly Requests Pie</b></label>

                <input type="radio" name="disease_pie_radio" id="chronic_pie" value="chronic_pie" class="with-gap radio-col-pink"/>
                <label for="chronic_pie"><b>Chronic Pie</b></label>

                <ul class="header-dropdown m-r--5">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">info_outline</i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <h5 class="text-center"><span class="badge bg-teal">Request Pie Criteria</span></h5>
                            <table class="table">
                                <td>Request pie is shown based on health requests.It is displayed Year wise and can be fetched applying all global filters.</td>
                            </table>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <!-- <div id="chart_combo" style=" height: 300px;" ></div> -->
                <div id="piechart_3d"></div>
                <div id="chronic_pie_disease" style="display: none;"></div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="header" style="padding: 10px">

                <input type="radio" name="gender_wise_pie_radio" id="hb_overall_wise_pie" value="hb_overall_wise_pie" class="with-gap radio-col-pink" checked/>
                <label for="hb_overall_wise_pie"><b>HB-Overall</b></label>

                <input type="radio" name="gender_wise_pie_radio" id="hb_gender_wise_pie" value="hb_gender_wise_pie" class="with-gap radio-col-pink"/>
                <label for="hb_gender_wise_pie"><b>HB Gender wise Bar</b></label>              
       
                <input type="radio" name="gender_wise_pie_radio" id="bmi_gender_wise_pie"  class="with-gap radio-col-pink" value="bmi_gender_wise_pie"  />
               <label for="bmi_gender_wise_pie"><b>BMI Gender wise Bar</b></label>

                <ul class="header-dropdown m-r--5">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="modal" role="button" data-target="#HB_and_BMI_Criteria">
                            <i class="material-icons">info_outline</i>
                        </a>
                    </li>
                </ul>

            </div>
            <div class="body">

                <div class="row clearfix" id="time_span_filter_hb_overall" style="margin-bottom: -25px;">                   
                    <div class="col-sm-10 collapse" id="hboverallmulticollapseExample">
                        <div class="col-sm-4">                       
                           <?php $end_date  = date ( "Y-m-d", strtotime ( date('Y-m-d') . "-30 days" ) ); ?>
                             <span id="monitoring_datepicker">
                              Start Date
                             <input type="text" id="hb_overall_passing_date" name="hb_overall_passing_date" class="form-control date" value="<?php echo $end_date; ?>">
                             </span>
                        </div>
                        <div class="col-sm-4">
                             <span id="monitoring_datepicker">
                             End Date
                             <input type="text" id="hb_overall_passing_end_date" name="hb_overall_passing_end_date" class="form-control date" value="<?php echo date('Y-m-d'); ?>">
                             </span>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-line">
                                  <button type="button" id="hb_overall_date_set" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                                  <i class="material-icons">search</i>
                                  </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2" style="float: right;">
                        <ul class="header-dropdown m-l--20">
                            <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#hboverallmulticollapseExample" aria-controls="hboverallmulticollapseExample"  data-placement="bottom" title="Date Filters"><img src="<?php echo IMG ;?>/funnel.png"></button>
                        </ul>
                    </div>
                </div>              
             
                <div id="hb_overall"></div>

                <div class="row clearfix" id="time_span_filter_hb" style="display: none; margin-bottom: -25px;">
                    <div class="col-sm-10 collapse" id="hbmulticollapseExample">
                        <div class="col-sm-4">                       
                           <?php $end_date  = date ( "Y-m-d", strtotime ( date('Y-m-d') . "-30 days" ) ); ?>
                             <span id="monitoring_datepicker">
                              Start Date
                             <input type="text" id="hb_passing_date" name="hb_passing_date" class="form-control date" value="<?php echo $end_date; ?>">
                             </span>
                        </div>
                        <div class="col-sm-4">
                             <span id="monitoring_datepicker">
                             End Date
                             <input type="text" id="hb_passing_end_date" name="hb_passing_end_date" class="form-control date" value="<?php echo date('Y-m-d'); ?>">
                             </span>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-line">
                                  <button type="button" id="hb_date_set" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                                  <i class="material-icons">search</i>
                                  </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2" style="float: right;">
                        <ul class="header-dropdown m-l--20">
                        <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#hbmulticollapseExample" aria-controls="hbmulticollapseExample"  data-placement="bottom" title="Date Filters"><img src="<?php echo IMG ;?>/funnel.png"></button>
                        </ul>
                    </div>
                    </div> 
                    <div id="hb_bar" style="display: none;"></div>
                        <div class="row clearfix" id="time_span_filter_bmi" style="display: none;">
                            <div class="col-sm-10 collapse" id="bmimulticollapseExample">
                                <div class="col-sm-4">                       
                                 <?php $end_date  = date ( "Y-m-d", strtotime ( date('Y-m-d') . "-30 days" ) ); ?>
                                   <span id="monitoring_datepicker">
                                    Start Date
                                   <input type="text" id="bmi_passing_date" name="bmi_passing_date" class="form-control date" value="<?php echo $end_date; ?>">
                                   </span>
                                </div>
                                <div class="col-sm-4">
                                   <span id="monitoring_datepicker">
                                   End Date
                                   <input type="text" id="bmi_passing_end_date" name="bmi_passing_end_date" class="form-control date" value="<?php echo date('Y-m-d'); ?>">
                                   </span>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-line">
                                        <button type="button" id="bmi_date_set" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                                        <i class="material-icons">search</i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2" style="float: right;">
                               <ul class="header-dropdown m-l--20">
                                  <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#bmimulticollapseExample" aria-controls="bmimulticollapseExample"  data-placement="bottom" title="Date Filters"><img src="<?php echo IMG ;?>/funnel.png"></button>
                              </ul>
                            </div>
                        </div>   
                        <div id="bmi_bar" style="display: none;"></div>
                      </div>
                  
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="header" style="padding: 10px">

                <input type="radio" name="requests_bar_radio" id="sanitation_bar"  class="with-gap radio-col-pink" value="sanitation_bar" checked/>
                <label for="sanitation_bar"><b>Sanitation</b></label>

                <input type="radio" name="requests_bar_radio" id="attendance_bar" value="attendance_bar" class="with-gap radio-col-pink"/>
                <label for="attendance_bar"><b>Attendance</b></label>
                
                <ul class="header-dropdown m-r--5  m-t--10">
                    <li>
                        <div class="form-line">
                            <input type="text" id="date_for_sani_attend" name="date_for_sani_attend" class="datepicker form-control date date_for_sani_attend" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle for_absent_report" data-toggle="dropdown" data-toggle="tooltip" data-placement="bottom" title="Attendance List" role="button" aria-haspopup="true" aria-expanded="true">
                            <i class="material-icons">info_outline</i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <h5 class="text-center">Attendance List</h5>
                                <table class="table table-bordered">
                                     <tbody>
                                        <tr>
                                            <th><a href="javascript:void(0);" class="abs_submitted_schools_list" data-toggle="modal" data-target="#absent_sent_school_modal">Submitted Schools</a></th>
                                            <td><span class="badge bg-teal abs_submitted_schools"></span></td>
                                        </tr>
                                        <tr>
                                            <th><a href="javascript:void(0);" class="abs_not_submitted_schools_list" data-toggle="modal" data-target="#absent_not_sent_school_modal">Not Submitted Schools</a></th>
                                            <td><span class="badge bg-teal abs_not_submitted_schools"></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            <h5 class="text-center">Sanitation List</h5>
                                <table class="table table-bordered">
                                     <tbody>
                                        <tr>
                                            <th><a href="javascript:void(0);" class="sanitation_report_submitted_schools_list" data-toggle="modal" data-target="#sani_repo_sent_school_modal">Submitted Schools</a></th>
                                            <td><span class="badge bg-teal sanitation_report_submitted_schools" ></span></td>
                                        </tr>
                                        <tr>
                                            <th><a href="javascript:void(0);" class="sanitation_report_not_submitted_schools_list" data-toggle="modal" data-target="#sani_repo_not_sent_school_modal">Not Submitted Schools</a></th>
                                            <td><span class="badge bg-teal sanitation_report_not_submitted_schools"></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div id="column_bar"></div>
                <div id="attendance_pie" style="display: none;height: 300px;" class="graph"></div>
            </div>

        </div>
    </div>
    
</div>
<!-- End Second row card Info HB and BMI -->

</div> <!-- Container Div End -->

<!-- Info criteria for hb and bmi -->

<div class="modal fade" id="HB_and_BMI_Criteria" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Criteria for HB and BMI status</h4>
            </div>
            <div class="modal-body">
                <div id="carousel-example-generic_2" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic_2" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic_2" data-slide-to="1"></li>
                    </ol>
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <!-- <img src="<?php //echo IMG ;?>/demo/s1.jpg" /> -->
                            <div class="card">
                                <div class="body bg-grey" style="height: 320px;">
                                    <div class="font-bold m-b--35" style="text-align: center;">HB CALCULATION
                                    <br>
                                    <br>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <td>Category</td>
                                                    <td>Range</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Severe</td>
                                                    <td> <= 8</td>
                                                </tr>
                                                <tr>
                                                    <td>Moderate</td>
                                                    <td> Between 8.1 TO 10</td>
                                                </tr>
                                                <tr>
                                                    <td>Mild</td>
                                                    <td> Between 10.1 To 12</td>
                                                </tr>
                                                <tr>
                                                    <td>Normal</td>
                                                    <td> >=12</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card">
                                <div class="body bg-grey" style="height: 320px;">
                                    <div class="font-bold m-b--35" style="text-align: center;">BMI CALCULATION
                                    <br>
                                    <br>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <td>Category</td>
                                                    <td>Range</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Under Weight</td>
                                                    <td> <= 18.5</td>
                                                </tr>
                                                <tr>
                                                    <td>Normal</td>
                                                    <td> Between 18.5 TO 24.9</td>
                                                </tr>
                                                <tr>
                                                    <td>Over Weight</td>
                                                    <td> Between 25 To 29.9</td>
                                                </tr>
                                                <tr>
                                                    <td>Obese</td>
                                                    <td> >30</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-example-generic_2" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic_2" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
               
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<!-- School Health status Criteria Modal -->

<div class="modal fade" id="defaultModalshs" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">School Health Status Criteria</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                           <th>Type</th> 
                           <th>Red Zone</th> 
                           <th>Orange Zone</th> 
                           <th>Green Zone</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                           <th>Scabies</th>
                           <td>>5</td> 
                           <td>>2 & <5</td> 
                           <td><2 or No Cases</td> 
                           <!-- <td><span class="badge bg-teal">>5</span></td> 
                           <td><span class="badge bg-teal">>2 & <5</span></td> 
                           <td><span class="badge bg-teal"><2 or No Cases</span></td> --> 
                        </tr>
                        <tr>
                           <th>Abnormalities</th> 
                           <td>>6</td> 
                           <td>>2 & <5</td> 
                           <td><2 or No Cases</td> 
                        </tr>
                        <tr>
                           <th>HB status</th> 
                           <td>Severe aneamia >6 or all aneamic students in the school >10</td> 
                           <td>All aneamic cases(mild+moderate+severe) >25</td> 
                           <td><6</td> 
                        </tr>
                        <tr>
                           <th>BMI Status</th> 
                           <td>underweight bmi >6 or total bmi cases >17</td> 
                           <td>>25</td> 
                           <td><6</td>
                        </tr>
                        <tr>
                           <th>Bites</th> 
                           <td>>6</td> 
                           <td>>2 & <5</td> 
                           <td><2 or No Cases</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal For Attendance Submitted and Not Submitted Schools -->

<div class="modal fade" id="absent_sent_school_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Absent Report Submitted Schools List</h4>
            </div>
            <div id="absent_sent_school_modal_body" class="modal-body">
                <!-- Table is written in Script -->  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-teal waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="absent_not_sent_school_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Absent Report Not Submitted Schools List</h4>
            </div>
            <div id="absent_not_sent_school_modal_body" class="modal-body">
                <!-- Table is written in Script -->  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-teal waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<!-- End for Attendance modals-->

<!-- Modal for Sanitation Submitted and Not Sbmitted Schools list -->

<div class="modal fade" id="sani_repo_sent_school_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Sanitation Submitted Schools List</h4>
            </div>
            <div id="sani_repo_sent_school_modal_body" class="modal-body"></div>
            <!-- Table is written in script -->
            <div class="modal-footer">
                <button type="button" class="btn bg-teal waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sani_repo_not_sent_school_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Sanitation Not Submitted Schools List</h4>
            </div>
            <div id="sani_repo_not_sent_school_modal_body" class="modal-body">
                <!-- Table is written in Script -->  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-teal waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<!-- End Sanitation Modal-->

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="loading_modal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" id="loading">
            <center><img src="<?php echo(IMG.'loader.gif'); ?>" id="gif" ></center>
        </div>
    </div>
</div>

<!-- Card  Modal For Total School List info -->

<div class="modal fade" id="total_schools_listss" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h4 class="modal-title" id="defaultModalLabel">Total Schools List</h4>
           </div>
           <div class="modal-body" id="data_Scls">              
           </div>
           <div class="modal-footer">
               <button type="button" class="btn bg-teal waves-effect" data-dismiss="modal">CLOSE</button>
           </div>
       </div>
   </div>
</div>
<!-- End Card Model schools List -->

<div class="modal fade" id="doc_res_names" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Today Doctor Responses For Requests</h4>
            </div>
            <div class="modal-body">
              <div id="doc_res_table_view"></div>
            </div>
            <div class="modal-footer">
               
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<form style="display: hidden" action="get_day_to_day_glance_data_fetching" method="POST" id="day_to_day_glance_form">
      <input type="hidden" id="to_date" name="today_date" value=""/>
      <input type="hidden" id="day_to_day_status" name="day_to_day_status" value=""/>
</form> 

<form style="display: hidden" action="tswreis_diseases_counts_report" method="POST" id="abnormality_list_clicked">
    <input type="hidden" id="abnormality_name" name="abnormality_name" value=""/>
    <input type="hidden" id="screen_academic_year" name="academic_year" value=""/>
</form>

<form style="display: hidden" action="get_hospital_type_data_table" method="POST" id="hospital_type_table">
    <input type="hidden" id="start_date_type" name="start_date_type" value=""/>
        <input type="hidden" id="end_date_type" name="end_date_type" value=""/>
    <input type="hidden" id="hospital_value" name="hospital_value" value=""/>
</form>

<form style="display: hidden" action="get_school_health_status_zone_schools" method="POST" id="school_health_table">
    <input type="hidden" id="value_scl_zone" name="school_zone" value=""/>
    <input type="hidden" id="dist_id" name="dist_id">
    <input type="hidden" id="opt_selected" name="status">
</form>

<form style="display: hidden" action="get_hospitalized_data_table" method="POST" id="hospitalized_table">
   <!--  <input type="hidden" id="date_hospitalized" name="date_hospitalized" value=""/> -->
   <input type="hidden" id="start_date" name="start_date" value=""/>
   <input type="hidden" id="end_date" name="end_date" value=""/>
    <input type="hidden" id="request_type" name="request_type" value=""/>
    <input type="hidden" id="hospital_district" name="hospital_district" value=""/>
</form>

<form style="display: hidden" action="get_sanitation_report_school_wise" method="POST" id="sanitation_table">
    <input type="hidden" id="date_sani" name="today_date" value=""/>
    <input type="hidden" id="value_sani" name="sanitation_type" value=""/>
</form>

<form style="display: hidden" action="get_attendance_data_for_bar_schools" method="POST" id="attendance_table">
    <input type="hidden" id="value_attn" name="value_attn" value=""/>
    <input type="hidden" id="date_attendance" name="date_attendance" value=""/>
    <input type="hidden" id="atten_dist_id" name="dist_id" value=""/>
    <input type="hidden" id="atten_scl_id" name="scl_id" value=""/>
</form>

<form style="display: hidden" action="to_daily_health_request" method="POST" id="get_table">
    <input type="hidden" id="sart_id" name="sart_id" value=""/>
    <input type="hidden" id="end_id" name="end_id" value=""/>
    <input type="hidden" id="val_id" name="val_id" value=""/>
    <input type="hidden" id="req_dist_id" name="dist_id" value=""/>
    <input type="hidden" id="scl_id" name="scl_id" value=""/>
</form>

<form style="display: hidden" action="get_daily_updated_health_request" method="POST" id="get_table_update">
    <input type="hidden" id="sart_id2" name="sart_id2" value=""/>
    <input type="hidden" id="end_id2" name="end_id2" value=""/>
    <input type="hidden" id="district_id" name="district_id" value=""/>
    <input type="hidden" id="school_id" name="school_id" value=""/>        
    <input type="hidden" id="value_id" name="value_id" value=""/>        
</form>

<form style="display: hidden" action="total_request_page" method="POST" id="get_school_for_request">
    <input type="hidden" id="val_req" name="val_req" value=""/>
   <!--  <input type="hidden" id="date_id" name="date_id" value=""/> -->
    <input type="hidden" id="req_dist" name="req_dist" value=""/>
    <input type="hidden" id="req_scl" name="req_scl" value=""/>
    <input type="hidden" id="req_academic" name="req_academic" value=""/>
</form>

<form style="display: hidden" action="get_chronic_students_from_pie" method="POST" id="chronic_table">
    <input type="hidden" id="chronic_symptom" name="chronic_symptom" value=""/>       
</form>

<form style="display: hidden" action="get_hb_overall_data_table" method="POST" id="hb_overall_table">
    <input type="hidden" id="start_date_hb_overall" name="start_date_hb_overall" value=""/>
    <input type="hidden" id="end_date_hb_overall" name="end_date_hb_overall" value=""/>
    <input type="hidden" id="hb_overall_type" name="hb_overall_type" value=""/>       
    <!-- <input type="hidden" id="hb_gender" name="hb_gender" value=""/>       --> 
</form>

<form style="display: hidden" action="get_hb_gender_wise_data_table" method="POST" id="hb_gender_wise_table">
    <input type="hidden" id="start_date_hb" name="start_date_hb" value=""/>
    <input type="hidden" id="end_date_hb" name="end_date_hb" value=""/>
    <input type="hidden" id="hb_type" name="hb_type" value=""/>       
    <input type="hidden" id="hb_gender" name="hb_gender" value=""/>       
</form>

 <form style="display: hidden" action="get_bmi_gender_wise_data_table" method="POST" id="bmi_gender_wise_table">
   <input type="hidden" id="start_date_bmi" name="start_date_bmi" value=""/>
    <input type="hidden" id="end_date_bmi" name="end_date_bmi" value=""/>
    <input type="hidden" id="bmi_type" name="bmi_type" value=""/>       
    <input type="hidden" id="bmi_gender" name="bmi_gender" value=""/>       
</form>

<form style="display: none;" action="get_doc_res_students_daily_req" method="POST" id="get_students_with_doctor">
    <input type="hidden" name="get_stud_with_doc" id="get_stud_with_doc" value=""/>
    <input type="hidden" name="res_start_date" id="res_start_date" value=""/>
    <input type="hidden" name="res_end_date" id="res_end_date" value=""/>
</form>

<form style="display: none;" action="get_admitted_students_school_with_span" method="POST" id="admitted_show_form">
    <input type="hidden" name="start_date_new" id="start_date_new" value=""/>
    <input type="hidden" name="end_date_old" id="end_date_old" value=""/>
    <input type="hidden" name="request_type_newsss" id="request_type_newsss" value=""/>
</form>

<form style="display: hidden" action="anemia_daily_health_request" method="POST" id="anemia_get_table">  
   <input type="hidden" id="hs_date" name="hs_date" value=""/>
   <input type="hidden" id="hs_responce" name="hs_responce" value=""/>
</form>

<form style="display: hidden" action="surgery_daily_health_request" method="POST" id="surgery_get_table">  
    <input type="hidden" id="date" name="date" value=""/>
    <input type="hidden" id="hs_surgery" name="hs_surgery" value=""/>
</form>

<form style="display: hidden" action="daily_doctor_visit_schools_list" method="POST" id="dr_visit_get_schools">  
   <input type="hidden" id="visiting_date" name="visiting_date" value=""/>
   <input type="hidden" id="doctor_visit" name="doctor_visit" value=""/>
</form>

<!-- Google Charts -->
<script src="<?php echo(MDB_JS.'loader.js'); ?>"></script>

<!-- Jquery Core Js -->
<script src="<?php echo MDB_PLUGINS."jquery/jquery.min.js"; ?>"></script>

<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
<script src="<?php echo JS; ?>/d3pie/d3.js"></script>


<!-- Waves Effect Plugin Js -->
<script src="<?php echo MDB_PLUGINS."node-waves/waves.js"; ?>"></script>

<!-- Autosize Plugin Js -->
<script src="<?php echo MDB_PLUGINS."autosize/autosize.js"; ?>"></script>

<script src="<?php echo(MDB_JS.'exporting.js'); ?>"></script>

 <script src="<?php echo(MDB_JS.'export-data.js'); ?>"></script>
 
<script src="<?php echo(MDB_JS.'accessibility.js'); ?>"></script>


<!-- Moment Plugin Js -->
<script src="<?php echo MDB_PLUGINS."momentjs/moment.js"; ?>"></script>

<!-- Custom Js -->
<script src="<?php echo(MDB_JS.'admin.js'); ?>"></script>
<script src="<?php echo(MDB_JS.'pages/forms/basic-form-elements.js'); ?>"></script>

<!-- Demo Js -->
<script src="<?php echo(MDB_JS.'demo.js'); ?>"></script>

<!-- Bootstrap Datepicker Plugin Js -->
<script src="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script> 

<?php include("inc/footer_bar.php"); ?>

<script type="text/javascript">

    $(document).on('click','.total_schools_list',function(){
    var uid = $(this).attr('uid');

       var urlLink = "<?php echo URL;?>";
       
       $.ajax({
           url : urlLink+'panacea_mgmt/getschoolsInformation',
           type : 'POST',
           data : '',
           success : function(data){
          
               var docs = $.parseJSON(data);

            data_table = '<table class="table table-bordered" id="more_requests"><thead><tr><th>District Name</th><th>Boys Schools</th><th>Girls Schools</th><th>Action</th></tr></thead><tbody>';

             $.each(docs, function(){
               
             data_table = data_table+'<tr>';
             data_table = data_table+'<td>'+this.dist +'</td>';
             data_table = data_table+'<td>'+this.boy_scl +'</td>';
             data_table = data_table+'<td>'+this.grl_scl +'</td>';
             data_table = data_table+'<td><button type="button" class="btn bg-green show_scl" id="show_scl">Show</button></td>';
             });
           data_table = data_table+'</tr></tbody></table>';
            $('#more_requests').each(function(){
            $('#show_scl').click(function(){
                var row = $('$this').closest("tr");
                var dist = row.find("td:eq(0)").text();
                alert(dist);
           });
        });
           
           $('#data_Scls').html(data_table);
           $("#total_schools_listss").modal("show");

           },
           error:function(XMLHttpRequest, textStatus, errorThrown)
           {
            console.log('error', errorThrown);
           }
       });

       $("#total_schools_list").modal("show")  
});

</script>

<script type="text/javascript">
    $('input[name = "update_bar_radio"]').click(function(){
    var check = $('input[name= "update_bar_radio"]:checked').val();
    //alert(check);
    if(check== 'daily_health_bar'){
        $('#columnchart_requests').show();           
        $('#daily_time_span_filter').show();           
        $('#hospital_type_columnchart').hide();
        $('#update_time_span_filter').hide();
        $('#ubdated_type_columnchart').hide();                   
    }else {
        $('#ubdated_type_columnchart').show();
        $('#columnchart_requests').hide();          
        $('#hospital_type_columnchart').hide();
        $('#daily_time_span_filter').hide();
        $('#update_time_span_filter').show();
    } 
    })
</script> 

<script type="text/javascript">
    $('input[name = "requests_pie_radio"]').click(function(){
    var check = $('input[name= "requests_pie_radio"]:checked').val();
    //alert(check);
    if(check== 'Hospitalized_pie'){
        $('#hospitalized_bar_graph').show();
        $('#time_span_filter').show();
        $('#hospital_type_graph').hide();
        $('#type_time_span_filter').hide();
        $('#request_hospitalized_table_view').hide();
        $('#filter_for_table').hide();
        
    }else if(check== 'Hospitalized_type'){
        $('#hospital_type_graph').show();
        $('#type_time_span_filter').show();
        $('#hospitalized_bar_graph').hide();
        $('#time_span_filter').hide();
        $('#request_hospitalized_table_view').hide();
        $('#filter_for_table').hide();
    }else{
        $('#request_hospitalized_table_view').show();
        $('#filter_for_table').show();
        $('#hospitalized_bar_graph').hide();
        $('#time_span_filter').hide();
        $('#hospital_type_graph').hide();
        $('#type_time_span_filter').hide();

    }
 })

   $('input[name = "requests_bar_radio"]').click(function(){
        var radio = $('input[name = "requests_bar_radio"]:checked').val();
        //alert(radio);
        if(radio == 'sanitation_bar'){
            $('#column_bar').show();
            $('#attendance_pie').hide();
            

        }else{
            $('#attendance_pie').show();
            $('#column_bar').hide();
        }
   })

   $('input[name = "disease_pie_radio"]').click(function(){
        var radio = $('input[name = "disease_pie_radio"]:checked').val();
        //alert(radio);
        if(radio == 'yearly_request_pie'){
            $('#piechart_3d').show();
            $('#chronic_pie_disease').hide();
        }else{
            $('#chronic_pie_disease').show();
            $('#piechart_3d').hide();
        }
   });

   $('input[name = "gender_wise_pie_radio"]').click(function(){
    var check = $('input[name= "gender_wise_pie_radio"]:checked').val();
        //alert(check);
        if(check== 'hb_overall_wise_pie'){
            $('#hb_overall').show();
            $('#time_span_filter_hb_overall').show();
            $('#hb_bar').hide();
            $('#time_span_filter_hb').hide();
            $('#bmi_bar').hide();
            $('#time_span_filter_bmi').hide();
        }else if(check== 'hb_gender_wise_pie'){
            $('#hb_bar').show();
            $('#time_span_filter_hb').show();
            $('#bmi_bar').hide();
            $('#time_span_filter_bmi').hide();
            $('#hb_overall').hide();
            $('#time_span_filter_hb_overall').hide();
            
        }else{
            $('#bmi_bar').show();
            $('#time_span_filter_bmi').show();
            $('#hb_overall').hide();
            $('#time_span_filter_hb_overall').hide();
            $('#hb_bar').hide();
            $('#time_span_filter_hb').hide();
        } 
    });
   
</script> 
<script type="text/javascript">

//Date Filter Script
var today_date = $('#set_date').val();
$('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
$('#set_date').change(function(e){
        today_date = $('#set_date').val();
});

var today_date = $('#date_for_sani_attend').val();

//End Date Filter Script

/* Default Load functions calling */
default_filters();
daily_requests_bar_data();
get_day_to_day_update_request_for_bar();
day_to_day_problems_on_page_load();
get_day_to_day_district_hospitalized_bar();
get_day_to_day_type_of_hospital_bar();
get_hb_gender_wise_bar();
get_bmi_gender_wise_bar();
get_hb_overall_bar();
sanitation_data_for_daily();

/*Get Schools List Based on District*/
$('#district_filter').change(function(e){
    dist = $('#district_filter').val();
    dt_name = $("#district_filter option:selected").text();
   // alert(dist);
    var options = $("#school_filter");
    options.prop("disabled", true);
     $("#loading_modal").modal('show');
    $.ajax({
        url: 'get_schools_list_with_dist_name',
        type: 'POST',
        data: {"dist_id" : dist},
        success: function (data) {          
             $("#loading_modal").modal('hide');
            result = $.parseJSON(data);
            console.log(result)

            options.prop("disabled", false);
            options.empty();
            options.append($("<option />").val("All").prop("selected", true).text("All"));
            $.each(result, function() {
                options.append($("<option />").val(this.school_name).text(this.school_name));
            });
                    
            },
            error:function(XMLHttpRequest, textStatus, errorThrown)
            {
             console.log('error', errorThrown);
            }
        });
});
/*End Get Schools List Based on District*/


$('.common_change').change(function(){
    default_filters();
});

$('#request_date_set_span').click(function(){
    daily_requests_bar_data();
});

$('#update_date_set').click(function(){
    get_day_to_day_update_request_for_bar();
});

function default_filters(){
    var date = $('#set_date').val();
    var district = $('.district_filter').val();
    var school = $('.school_filter').val();
    var academicYear = $('.academic_filter').val();
    var gender =  $('.gender_filter').val();


    /* Screening Pie */
     $.ajax({

         url : 'get_screening_pie_values',
         type: 'POST',
         data: {'academic_year':academicYear},
         success: function(data){
            $('#piechart').empty();
            $('#piechart_3d').empty();
            // $('#screen_pie_new_one').empty();
             var datas = $.parseJSON(data);
             new_screening_pie_data(datas);
             
         }

    });
    /*End Screening Pie */

    /*Cards Data*/
    $.ajax({
        url:'get_data_for_cards',
        type:'POST',
        data:{'academic_year': academicYear, 'district_name':district, 'school_name':school, 'gender_type':gender},
        success: function(data){
            var data = $.parseJSON(data);
            
            show_cards_info(data);
        }
    });
    /*End Cards Data*/

    /*HB Pie*/
   /* $.ajax({
        url:'get_hb_gender_wise_data_count',
        type:'POST',
        data:{'today_date': date},
        success:function(data){
            var data = $.parseJSON(data);
           
            show_hb_gender_wise_bar(data);
        }
    });*/
    /*End HB Pie*/
    
    /*Sanitation Pie*/
    /*$.ajax({
        url: 'get_sanitation_day_to_day_counts',
        type: 'POST',
        data:{'today_date':date, 'district_name':district},
        success:function(data){
            datas = $.parseJSON(data);
            piedata = datas.sanitation_report_schools_list
            graph_view_of_sanitation(piedata);
           
        }
    });*/
    /*End Sanitation Pie*/
    

    /*Daily Requests Bar*/
    /*$.ajax({
        url:'get_daily_request_for_bar',
        type:'POST',
        data:{'request_start_date': date, 'district_name':district, 'school_name':school},
        success:function(data){
            var data = $.parseJSON(data);   

            show_daily_request_bar(data);

        }

    });*/
    /*End Daily Requests Bar*/
     /*Hospital name District wise */
   /* $.ajax({
        url : 'get_hospitalized_data_count',
        type : 'POST',
        data: {'today_date': date},
        success:function(data){
            var data = $.parseJSON(data);
            
            show_daily_hospitalized_bar(data);
        }
    });*/
    /*End Hospital name District wise */

    $.ajax({
        url:'get_total_counts_requests_pie',
        type:'POST',
        data:{'academic_year': academicYear, 'district_name':district, 'school_name':school, 'gender_type':gender},
        success:function(data){
            var data = $.parseJSON(data);
           
             show_total_request_bar(data);
        }
    })
    /*End Request Pie*/

    $.ajax({
        url:'get_chronic_counts_requests_pie',
        type:'POST',
        data:{},
        success:function(data){
            var datas = $.parseJSON(data);
           console.log(data);
             show_chronic_request_bar(datas);
        }
    })
    /*End Chronic-Selected Pie*/

};



function daily_requests_bar_data(){
    var re_start = $('#start_date_request').val();
    var re_end = $('#end_date_request').val();
    var district = $('.district_filter').val();
    var school = $('.school_filter').val();

    $.ajax({
        url:'get_daily_request_for_bar',
        type:'POST',
        data:{'request_start_date': re_start, 'request_end_date': re_end, 'district_name':district, 'school_name':school},
        success:function(data){
            var data = $.parseJSON(data);   

            show_daily_request_bar(data);

        }

    });
}

      /*Hospital Type Bar*/
/*$('#Hospitalized_type').click(function(){
    var date = $('#set_date').val();
    $.ajax({
        url:'get_hospital_type_of_bar_count',
        type:'POST',
        data:{'today_date': date},
        success:function(data){
            var data = $.parseJSON(data);
            show_daily_hospital_type_bar(data);
        }

    });
});*/
    /*End Hospital Type Bar*/

/*$('#request_pie').click(function(){

    var district = $('.district_filter').val();
    var school = $('.school_filter').val();
    var academicYear = $('.academic_filter').val();
    var gender =  $('.gender_filter').val();
    /*Request Pie*/
    /*$.ajax({
        url:'get_total_counts_requests_pie',
        type:'POST',
        data:{'academic_year': academicYear, 'district_name':district, 'school_name':school, 'gender_type':gender},
        success:function(data){
            var data = $.parseJSON(data);
           
             show_total_request_bar(data);
        }
    })
    
});*/

    /*BMI Pie*/
/*$('#bmi_gender_wise_pie').click(function(){
     var date = $('#set_date').val();
    $.ajax({
        url:'get_bmi_gender_wise_data_count',
        type:'POST',
        data:{'today_date': date},
        success:function(data){
            var data = $.parseJSON(data);
            show_bmi_gender_wise_bar(data);
        }
    });
});*/
    /*End BMI Pie*/


$('#attendance_bar').click(function(){

   attendance_data_for_daily();
});

$('#date_for_sani_attend').change(function(){
    sanitation_data_for_daily();
   attendance_data_for_daily();
});
function sanitation_data_for_daily(){

    var date = $('#date_for_sani_attend').val();
    var district = $('.district_filter').val();
    var school = $('.school_filter').val();
    /*Sanitation Pie*/
    $.ajax({
        url: 'get_sanitation_day_to_day_counts',
        type: 'POST',
        data:{'today_date':date, 'district_name':district},
        success:function(data){
            datas = $.parseJSON(data);
            piedata = datas.sanitation_report_schools_list
            graph_view_of_sanitation(piedata);
           
        }
    });
    /*End Sanitation Pie*/
};

function attendance_data_for_daily(){
    var date = $('#date_for_sani_attend').val();
    var district = $('.district_filter').val();
    var school = $('.school_filter').val();

    $.ajax({
        url:'get_daily_attendance_report',
        type:'POST',
        data: {'today_date':date, 'district_name':district, 'school_name':school},
        success:function(data){

            var datas = $.parseJSON(data);
          
            show_daily_attendance_for_bar(datas, district, school);

        }
    });
}

 /*End Attendance Pie*/


 /*End Attendance Pie*/
$('#Admitted_cases').click(function(){
    last_three_months_more_req_students();
});

$('#type_date_set').click(function(){
    get_day_to_day_type_of_hospital_bar();
});

$('#hb_date_set').click(function(){
    get_hb_gender_wise_bar();
});

$('#bmi_date_set').click(function(){
    get_bmi_gender_wise_bar();
});

$('#hb_overall_date_set').click(function(){
    get_hb_overall_bar();
});

/*Date Changing Function*/
$('#set_date').change(function(){
    day_to_day_problems_on_page_load();
});
/*End Date Changing Function*/
/*Today Glance Data*/
function day_to_day_problems_on_page_load()
{
   var todaydate = $('#set_date').val();
   $.ajax({
        url : 'show_quick_glance_label_counts',
        type : 'POST',
        data : {'today_date': todaydate},
        success :function(data){
            $('#day_to_day_glance').empty();

             var counts = $.parseJSON(data);
            
            $('#day_to_day_glance').html('<br><br><br><br><table class="table table-bordered" id="day_to_day_problems_tbl"><tr><td class="hssurgeryneeeded">Surgery Needed</td><td><span class="badge">'+counts.surgery_needed_counts+'</span></td><td class="fooutpatientCount">FO Out Patient</td><td><span class="badge">'+counts.fo_out_patient_count+'</span></td> </tr> <tr> <td class="doctorvisitCount">Doctor Visit Schools</td><td><span class="badge">'+counts.doc_visiting_schools_count+'</span></td>  <td class="foemergencycounts">FO Emergency Cases</td><td><span class="badge">'+counts.fo_emergency_count+'</span></td> </tr><tr> <td class="fosurgeyCount">Aneamic Cases</td><td><span class="badge">'+counts.aneamia_cases_count+'</span></td>  <td class="foreviewcaseCount">FO review Cases</td><td><span class="badge">'+counts.fo_review_cases_count+'</span></td> </tr> </table>');
            
            $('#day_to_day_problems_tbl tr td:even').each(function() {
            $(this).click(function (event){
               var day_to_day_status = $(this).text();
                var todaydate = $('#set_date').val();

               $('#to_date').val(todaydate);
               $("#day_to_day_status").val(day_to_day_status);

               if(day_to_day_status == 'Aneamic Cases'){

                   $('#hs_date').val(todaydate);
                   $('#hs_responce').val(day_to_day_status);
                   $('#anemia_get_table').submit();

               }else if(day_to_day_status == 'Surgery Needed'){

                   $('#date').val(todaydate);
                   $('#hs_surgery').val(day_to_day_status);
                   $('#surgery_get_table').submit();

               }else if(day_to_day_status == 'Doctor Visit Schools'){

                   $('#visiting_date').val(todaydate);
                   $('#doctor_visit').val(day_to_day_status);
                   $('#dr_visit_get_schools').submit();

               }else{

               $("#day_to_day_glance_form").submit();

               }

           });
       });

        }
   }); 
};
/*End Today Glance Data*/

/*Screening Pie*/
function new_screening_pie_data(datas){
    $('#piechart').empty();
    //$('#piechart_3d').empty();
   // $('#piechart_3d').hide();
    var pie = new d3pie("piechart", {
        "footer": {
            "color": "#999999",
            "fontSize": 10,
            "font": "open sans",
            "location": "bottom-left"
        },
        "size": {
            "canvasWidth": 450,
            //"pieOuterRadius": "90%"
            "canvasHeight": 350,
        },
        "data": {
            "sortOrder": "value-desc",
            "content": datas
        },
        "labels": {
            "outer": {
                "format": "label-value2",
                "pieDistance": 32
            },
            "inner": {
                "format": "value",
                "color": "#28b83a",
                "hideWhenLessThanPercentage": 3
            },
            "mainLabel": {
                "color": "#28b83a",
                "fontSize": 10
            },
            "percentage": {
                "color": "#ffffff",
                "decimalPlaces": 0
            },
            "value": {
                "color": "#FF0000",
                "fontSize": 10
            },
            "lines": {
                "enabled": true
            }
        },
        "tooltips": {
            "enabled": true,
            "type": "placeholder",
            "string": "{label}: {value}"
        },
        "effects": {
            "pullOutSegmentOnClick": {
                "effect": "linear",
                "speed": 400,
                "size": 8
            }
        },
        "misc": {
            "gradient": {
                "enabled": true,
                "percentage": 100
            },
            "canvasPadding": {
                "right": 3
            }
        },

        callbacks: {
            onClickSegment: function(a) {
                
                 var dats = a.data;
                 var label_name = dats.label;
                 //alert(label_name);
                 displayData(label_name);
                
                 function displayData(label_name) {
                        var academicYear = $('.academic_filter').val();
                       
                        $('#screen_academic_year').val(academicYear);
                         $("#abnormality_name").val(label_name);
                        
                         $("#abnormality_list_clicked").submit();
                    }
                
            }
        }
                    
    });
};

/*End Screening Pie*/

/*Requests Pie*/
function show_total_request_bar(data){
    $('#piechart_3d').empty();
    var normal = data.normal;
    var emergency = data.emergency;
    var chronic = data.chronic;
    var cured = data.cured;

    if(normal == 0 && emergency == 0 && chronic == 0){
        $('#piechart_3d').html("No Data Found")
    }else{

        google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Requests', 'Count'],
          ['General',     normal],
          ['Emergency',  emergency],
          ['Chronic',  chronic],
          ['Cured',  cured],
          
        ]);

        var options = {
          title: 'Request Pie Info',
          pieSliceText: 'value',
          is3D: true,
          width: 450,
          height: 300, 
          tooltip: {
                text: 'value'
            }
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));

        function selectHandler() {
            var selectedItem = chart.getSelection()[0];
            if (selectedItem) {
              var value = data.getValue(selectedItem.row, 0);
             //alert('The user selected ' + value);
              //var date = $('#set_date').val();
              var district = $('.district_filter').val();
              var school = $('.school_filter').val();
              var academicYear = $('.academic_filter').val();
              //var gender =  $('.gender_filter').val();
              //$('#date_req').val(date);
              $('#val_req').val(value);
              $('#req_dist').val(district);
              $('#req_scl').val(school);
              $('#req_academic').val(academicYear);
              $('#get_school_for_request').submit();
             
            }
        }

        google.visualization.events.addListener(chart, 'select', selectHandler);

        chart.draw(data, options);

      }


    }

    $(window).resize(function(){
    drawChart();
    }); 
    
};
/*End Requests Pie*/

/*Chronic Pie Info*/
function show_chronic_request_bar(datas){
    $('#chronic_pie_disease').empty();
    if(datas == "No Data Found"){
        $('#chronic_pie_disease').html('<h4>No Requests are there</h4>');
    }
    var chronic_pie_chart = Morris.Donut({
        element: chronic_pie_disease,
        data: datas,
        colors: ['rgb(233, 30, 99)', 'rgb(0, 188, 212)', 'rgb(255, 152, 0)', 'rgb(0, 150, 136)', 'rgba(0, 0, 255, 1)', 'rgb(60, 60, 60)', 'rgba(255, 99, 71, 0.6)', 'rgb(238, 130, 238)','rgba(0, 104, 0, 1)','rgba(255, 255, 0, 1)'],
        formatter: function (y) {
            return y + ''
        }
        
     }).on('click', function (i, row) {  
        // Do your actions
        // Example:
        displayData(i, row);

    // Selects the element in the Donut
    chronic_pie_chart.select(i);
    // Display the corresponding data
    displayData(i, chronic_pie_chart.data[i]);

    function displayData(i, row) {        
        $('#chronic_symptom').val(row.label);
        $('#chronic_table').submit();
    }
    }); 
};

/*End Chronic Pie Info*/

/*Cards Data Info Table*/
function show_cards_info(data){
    $('#totalStudentCount').empty();
    $('#total_schools_count').empty();
    $('#total_screened_stud_count').empty();
    $('#not_screened_stud_count').empty();
    $('#screened_school_count').empty();
    $('#not_screened_school_count').empty();
    var totalStudents = data.total_students;
    var totalSchool = data.total_schools;
    var totalScreened_stud = data.screened_students;
    var totalnotScreened_stud = data.not_screened_students;
    var screenedSchool = data.screened_schools;
    var notScreenedSchools = data.not_screened_schools;


    $('#totalStudentCount').html('<div class="text">Total Students </div><div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">'+totalStudents+'</div>');

    $('#total_schools_count').html('<div class="text">Total Schools </div><div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">'+totalSchool+'</div>');

    $('#total_screened_stud_count').html('<div class="text">Total Screened Students:</div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">'+totalScreened_stud+'<div');

    $('#not_screened_stud_count').html('<div class="text">Total Not Screened Students:</div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">'+totalnotScreened_stud+'<div');

    $('#screened_school_count').html('<div class="text">Screened Schools </div><div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">'+screenedSchool+'</div>');

    $('#not_screened_school_count').html('<div class="text">Not Screened Schools </div><div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">'+notScreenedSchools+'</div>'); 
    /*$('#screened_school_count').html('<div class="text">Screened Schools </div><div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">'+'</div>');

    $('#not_screened_school_count').html('<div class="text">Not Screened Schools </div><div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">24</div>');*/

};
/*End Cards Data Info Table*/

  function get_day_to_day_type_of_hospital_bar(){

   var todayDate = $('#type_passing_date').val();
    var endDate = $('#type_passing_end_date').val();
   
    $.ajax({
        url:'get_hospital_type_of_bar_count',
        type:'POST',
         data : {"start_date" : todayDate, "end_date": endDate},
        success:function(data){
            var data = $.parseJSON(data);

            console.log(data);

            show_daily_hospital_type_bar(data);

        }

    });

};
// HB  Genderwise barchart

function get_hb_gender_wise_bar(){
    //alert('Yoga');
    var todayDate = $('#hb_passing_date').val();
    var endDate = $('#hb_passing_end_date').val();
  
    $.ajax({
        url:'get_hb_gender_wise_data_count',
        type:'POST',
        data : {"start_date" : todayDate, "end_date": endDate},
        success:function(data){
            var data = $.parseJSON(data);
            console.log(data);
            show_hb_gender_wise_bar(data);
        }
    });
};

// HB overall barchart

function get_hb_overall_bar(){
    //alert('Yoga');
    var todayDate = $('#hb_overall_passing_date').val();
    var endDate = $('#hb_overall_passing_end_date').val();
  
    $.ajax({
        url:'get_hb_overall_data_count',
        type:'POST',
        data : {"start_date" : todayDate, "end_date": endDate},
        success:function(data){
            var data = $.parseJSON(data);
            console.log(data);
            show_hb_overall_bar(data);
        }
    });
};

/*HB overall Bar Info*/
function show_hb_overall_bar(data){
    
    var very_severe  = data.very_severe;
    var severe       = data.severe;
    var moderate     = data.moderate;
    var mild         = data.mild;
    var normal       = data.normal;
      
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Count", { role: "style" } ],
        ["VERY SEVERE", very_severe, "Dark blue"],
        ["SEVERE", severe, "Red"],
        ["MODERATE", moderate, "Orange"],
        ["MILD", mild, "pink"],
        ["NORMAL", normal, "Green"]
      
      ]);


       var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        //title: "HB-Overall",
        width: 475,
        height: 255,
        bar: {groupWidth: "70%"},
       vAxis: {title: "Counts"},
        legend: { position: "none" },
      };

        var chart = new google.visualization.ColumnChart(document.getElementById('hb_overall'));      

        function selectHandler() {
            var selectedItem = chart.getSelection()[0];
            if (selectedItem) {
              var value = data.getValue(selectedItem.row, 0);
              //alert('The user selected ' + value);
               var startDate = $('#hb_overall_passing_date').val();
              var endDate = $('#hb_overall_passing_end_date').val();
               $('#start_date_hb_overall').val(startDate);
              $('#end_date_hb_overall').val(endDate);
              $('#hb_overall_type').val(value);
              $('#hb_overall_table').submit();
             
            }
        }

        google.visualization.events.addListener(chart , 'select' ,selectHandler);

         chart.draw(view, options);
      }

    $(window).resize(function(){
        drawChart();
    });
};
/*End HB overall Bar Info*/

/*HB Bar Info*/
function show_hb_gender_wise_bar(data){
    $('#hb_bar').empty();
    var severe_male = data.severe_male;
    var moderate_male = data.moderate_male;
    var mild_male = data.mild_male;
    var normal_male = data.normal_male;

    var severe_female   = data.severe_female;
    var moderate_female   = data.moderate_female;
    var mild_female   = data.mild_female;
    var normal_female   = data.normal_female;

      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

       var data = google.visualization.arrayToDataTable([
        ['HB Genderwise', 'SEVERE', 'MODERATE', 'MILD','NORMAL', { role: 'annotation' } ],
        ['Male', severe_male, moderate_male, mild_male, normal_male, ''],
        ['Female', severe_female, moderate_female, mild_female,normal_female, '']   
      
      ]);

       var options_stacked = {
          title: "HB Genderwise",
          isStacked: true,
          height: 240,
          legend: {position: 'right', maxLines: 2},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('hb_bar'));      

        function selectHandler(e) {
           var selection = chart.getSelection();

           if (selection.length > 0) {

             var colLabel = data.getColumnLabel(selection[0].column);
             var mydata = data.getValue(selection[0].row,0);             
             //console.log(colLabel + ': ' + mydata);
             //chart.setSelection([]);

              var startDate = $('#hb_passing_date').val();
              var endDate = $('#hb_passing_end_date').val();

              $('#start_date_hb').val(startDate);
              $('#end_date_hb').val(endDate);   
              $('#hb_type').val(colLabel);
              $('#hb_gender').val(mydata);
              $('#hb_gender_wise_table').submit();

           }

         }

        google.visualization.events.addListener(chart , 'select' ,selectHandler);

        chart.draw(data,options_stacked);
      }

    $(window).resize(function(){
        drawChart();
    });
};
/*End HB Bar Info*/

/*BMI Bar Info*/
function get_bmi_gender_wise_bar(){
    //alert('Yoga');
    var todayDate = $('#bmi_passing_date').val();
    var endDate = $('#bmi_passing_end_date').val();
  
    $.ajax({
        url:'get_bmi_gender_wise_data_count',
        type:'POST',
        data : {"start_date" : todayDate, "end_date": endDate},
        success:function(data){
            var data = $.parseJSON(data);
            console.log(data);
            show_bmi_gender_wise_bar(data);
        }
    });
};
function show_bmi_gender_wise_bar(data){
    $('#bmi_bar').empty();
    var under_weight_male = data.under_weight_male;
    var over_weight_male = data.over_weight_male;
    var obese_male = data.obese_male;
    var normal_weight_male = data.normal_weight_male;

    var under_weight_female   = data.under_weight_female;
    var over_weight_female   = data.over_weight_female;
    var obese_female   = data.obese_female;   
    var normal_weight_female   = data.normal_weight_female; 

      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

       var data = google.visualization.arrayToDataTable([
        ['BMI Genderwise', 'UNDER', 'OVER', 'OBESE', 'NORMAL', { role: 'annotation' } ],
        ['Male', under_weight_male, over_weight_male, obese_male, normal_weight_male, ''],
        ['Female', under_weight_female, over_weight_female, obese_female, normal_weight_female ,'']   
      
      ]);

       var options_stacked = {
          title: "BMI Genderwise",
          isStacked: true,
          height: 230,
          legend: {position: 'right', maxLines: 2},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('bmi_bar'));      

        function selectHandler(e) {
           var selection = chart.getSelection();

           if (selection.length > 0) {

             var colLabel = data.getColumnLabel(selection[0].column);
             var mydata = data.getValue(selection[0].row,0);             
             //console.log(colLabel + ': ' + mydata);
             //chart.setSelection([]);

             var startDate = $('#bmi_passing_date').val();
              var endDate = $('#bmi_passing_end_date').val();

              $('#start_date_bmi').val(startDate);
              $('#end_date_bmi').val(endDate);
              $('#bmi_type').val(colLabel);
              $('#bmi_gender').val(mydata);
              $('#bmi_gender_wise_table').submit();

           }

         }

        google.visualization.events.addListener(chart , 'select' ,selectHandler);

        chart.draw(data,options_stacked);
      }

    $(window).resize(function(){
        drawChart();
    });
};
/*End BMI Bar Info*/

/*Sanitation Pie Info*/
function graph_view_of_sanitation(piedata){

    var sub = piedata.submitted_count;
    var not = piedata.not_submitted_count;
    var anim = piedata.animals;
    var not_work = piedata.not_work_washrooms;

    google.charts.load("current", {packages:['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ["Element", "Count", { role: "style" } ],
          ["Submitted", sub, "#4CAF50"],
          ["Not Submitted", not, "#d40000"],
          ["Animals", anim, "#FF9800"],
          ["Washrooms Required", not_work, "#FF9800"]
        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
                         { calc: "stringify",
                           sourceColumn: 1,
                           type: "string",
                           role: "annotation" },
                         2]);

        var options = {
          title: "Sanitation Schools Health Status",
          width: 450,
          height: 300,
          bar: {groupWidth: "65%"},
          legend: { position: "none" },
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("column_bar"));

         function selectHandler()
          {
             var sanitation = chart.getSelection()[0];
            if (sanitation) {
                var value = data.getValue(sanitation.row, 0);
                
                var date = $('#set_date').val();

                $('#date_sani').val(date);
                $('#value_sani').val(value);
                $('#sanitation_table').submit();
                //window.location = 'get_attendance_data_for_bar';/*controller function*/
            }
           
          }

        google.visualization.events.addListener(chart , 'select' ,selectHandler);

        chart.draw(view, options);
    }

    $(window).resize(function(){
       drawChart();
   });
};

/*End Sanitation Pie Info*/
/*Attendance Pie Info*/
function show_daily_attendance_for_bar(datas){
    $('#attendance_pie').empty();
    if(datas == "No Data Found"){
        $('#attendance_pie').html('<h4>No School Submitted</h4>');
    }
    var attendance_chart = Morris.Donut({
        element: attendance_pie,
        data: datas,
        colors: ['rgb(233, 30, 99)', 'rgb(0, 188, 212)', 'rgb(255, 152, 0)', 'rgb(0, 150, 136)'],
        formatter: function (y) {
            return y + ''
        }
        
     }).on('click', function (i, row) {  
        // Do your actions
        // Example:
        displayData(i, row);

    // Selects the element in the Donut
    attendance_chart.select(i);
    // Display the corresponding data
    displayData(i, attendance_chart.data[i]);

    function displayData(i, row) {
        var date = $('#set_date').val();
        var district = $('.district_filter').val();
        var school = $('.school_filter').val();        
        $('#date_attendance').val(date);
        $('#atten_dist_id').val(district);
        $('#atten_scl_id').val(school);
        $('#value_attn').val(row.label);
        $('#attendance_table').submit();


    }
    }); 
};

/*End Attendance Pie Info*/

/*Daily Requests Bar*/
function show_daily_request_bar(data){
    if(data == "No Data Found"){
        $('#columnchart_requests').html('<h4>No Requests Available</h4>');
    }else{
          $('#columnchart_requests').empty();
          var normal = data.normal;
          var emergency = data.emergency;
          var chronic = data.chronic;
          var doc = data.doc_response;
         
          google.charts.load("current", {packages:['corechart']});
          google.charts.setOnLoadCallback(drawChart);
          function drawChart() {
            var data = google.visualization.arrayToDataTable([
              ["Element", "Count", { role: "style" } ],
              ["General", normal, "#4CAF50"],
              ["Emergency", emergency, "#F44336"],
              ["Chronic", chronic, "#FF9800"],
              ["Doc Res", doc, "#FFC0CB"]
             
            ]);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                             { calc: "stringify",
                               sourceColumn: 1,
                               type: "string",
                               role: "annotation" },
                             2]);

            var options = {
              title: "Daily Health Request",
              width: 347,
              height: 140,
              bar: {groupWidth: "50%"},
              legend: { position: "none" },
            };
            var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_requests"));

           function selectHandler() {
                  var selectedItem = chart.getSelection()[0];
                  if (selectedItem) {
                    var value = data.getValue(selectedItem.row, 0);
                    //alert('The user selected ' + value);
                    var start_date = $('#start_date_request').val();                   
                    var end_date = $('#end_date_request').val();     
                    var district = $('.district_filter').val();
                    var school = $('.school_filter').val();
                    if(value == 'Doc Res'){
                        doc_res_function_for_modal(value,start_date,end_date,district, school);
                    }else{
                        
                        $('#sart_id').val(start_date);
                        $('#end_id').val(end_date);
                        $('#req_dist_id').val(district);
                        $('#scl_id').val(school);
                        $('#val_id').val(value);
                        $('#get_table').submit();
                    }
                    
                    //window.location = 'to_daily_health_request';
                  }
              }

            google.visualization.events.addListener(chart, 'select', selectHandler);

             
            chart.draw(view, options);


        }

    }

    $(window).resize(function(){
       drawChart();
   });

};

function doc_res_function_for_modal(value, start_date,end_date, district, school)
{
    $.ajax({
        url:'get_daily_doc_response_with_name',
        type:'POST',
        data:{'val_id':value, 'start_id':start_date, 'end_id':end_date, 'dist_id':district, 'scl_id':school},
        success:function(data){
            var result = $.parseJSON(data);

            if(result =='No Data Available'){

            }else{
                

                $('#doc_res_table_view').html('<div id="doc_response_table" class="text-center"></div>');

                var table = '<div style="overflow-y: auto;" ><table class="table table-striped table-bordered table-hover" id="responseTable"><thead><tr><th>Doc Name</th><th class="text-center">Count</th><th class="text-center">Action</th></tr></thead><tbody>';

                $.each(result, function(index, value){
                     table = table + '<tr><th>'+index+'</th><td><center>'+value+'</center></td><td><center><button class="doc">Show</button></center></td></tr>'
                });
                   

                $("#doc_response_table").html(table);
                table = table + '</tbody></table></div>';

                $("#doc_res_names").modal();

                $("#responseTable").each(function(){
                    $('.doc').click(function (){
                         //start_date = $("#passing_date").val();
                         //end_date   = $("#passing_end_date").val();
                        var currentRow=$(this).closest("tr"); 
                         
                         //var request_type_new=currentRow.find("th:eq(2)").text();
                         //var request_type_newww=currentRow.find("th:eq(3)").text();
                        var request_type_new = currentRow.find("th:eq(0)").text();
                            
                        //$("#start_date_new").val(start_date);
                        //$("#end_date_old").val(end_date);
                        $("#get_stud_with_doc").val(request_type_new);
                        $("#res_start_date").val(start_date);
                        $("#res_start_date").val(start_date);
                        $("#res_end_date").val(end_date);
                        //$("#request_type_status").val(request_type_status);
                        $("#get_students_with_doctor").submit();

                         
                    });
                 });
            }

            
        }
    });

};
/*End Daily Requests Bar*/

// update request bar
function get_day_to_day_update_request_for_bar(){
    var re_start = $('#update_passing_date').val();
    var re_end = $('#update_passing_end_date').val();
    var district = $('.district_filter').val();
    var school = $('.school_filter').val();

     
    $.ajax({
        url:'get_daily_updated_request_for_bar',
        type:'POST',
           data:{'request_start_date': re_start, 'request_end_date': re_end, 'district_name':district, 'school_name':school},
        success:function(data){
            var data = $.parseJSON(data);

            console.log(data);

            show_daily_updated_request_bar(data);

        }

    });

};

function show_daily_updated_request_bar(data){

    $('#ubdated_type_columnchart').empty();
    var updated_normal = data.updated_normal;
    var updated_emergency = data.updated_emergency;
    var updated_chronic = data.updated_chronic;     
   
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Count", { role: "style" } ],
        ["General", updated_normal, "#4CAF50"],
        ["Emergency", updated_emergency, "#F44336"],
        ["Chronic", updated_chronic, "#FF9800"],      
       
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        //title: "Daily updated Health Request",
        width: 300,
        height: 130,
        bar: {groupWidth: "50%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("ubdated_type_columnchart"));

     function selectHandler() {
            var selectedItem = chart.getSelection()[0];
            if (selectedItem) {
              var value = data.getValue(selectedItem.row, 0);
              //alert('The user selected ' + value);
               var start_date = $('#update_passing_date').val();                   
               var end_date = $('#update_passing_end_date').val(); 
              var district = $('.district_filter').val();
              var school = $('.school_filter').val();
              //alert(value);                
                    $('#sart_id2').val(start_date);
                    $('#end_id2').val(end_date);
                    $('#district_id').val(district);
                    $('#school_id').val(school);
                    $('#value_id').val(value);
                    $('#get_table_update').submit();                      
              
            }
        }

      google.visualization.events.addListener(chart, 'select', selectHandler);

       
      chart.draw(view, options);


  }  

};

/*Daily Hospitalised Bar*/
$('#h_date_set').click(function(){
    get_day_to_day_district_hospitalized_bar();
});
function get_day_to_day_district_hospitalized_bar(){
 //   alert('yoga');
    var todayDate = $('#h_passing_date').val();
    var endDate = $('#h_passing_end_date').val();  

    $.ajax({
        url:'get_hospitalized_data_count',
        type:'POST',
       data : {"start_date" : todayDate, "end_date": endDate},
        success:function(data){
            var data = $.parseJSON(data);
            console.log(data);
            show_daily_hospitalized_bar(data);
        }
    });
};
function show_daily_hospitalized_bar(data)
{

    var normal_hyderabad = data.normal_hyderabad;
    var emergency_hyderabad = data.emergency_hyderabad;
    var chronic_hyderabad = data.chronic_hyderabad;

    var normal_other_districts   = data.normal_other_districts;
    var emergency_other_districts   = data.emergency_other_districts;
    var chronic_other_districts   = data.chronic_other_districts;

     google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

       var data = google.visualization.arrayToDataTable([
        ['Admitted Cases', 'Normal', 'Emergency', 'Chronic', { role: 'annotation' } ],
        ['HYDERABAD', normal_hyderabad, emergency_hyderabad, chronic_hyderabad, ''],
        ['Other Districts', normal_other_districts, emergency_other_districts, chronic_other_districts, ''] 
      ]);

       var options_stacked = {
         isStacked: true,
          height: 220,
          legend: {position: 'bottom', maxLines: 2},
          vAxis: {minValue: 3}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('hospitalized_bar_graph'));

        function selectHandler(e) {
            var selection = chart.getSelection();
            if (selection.length > 0) {
              var colLabel = data.getColumnLabel(selection[0].column);
              var mydata = data.getValue(selection[0].row,0);
              console.log(colLabel + ': ' + mydata);

              //var date = $('#h_date_set').val();
              var startDate = $('#h_passing_date').val();
              var endDate = $('#h_passing_end_date').val();

               $('#start_date').val(startDate);
              $('#end_date').val(endDate);
               $('#request_type').val(colLabel);
               $('#hospital_district').val(mydata);
               $('#hospitalized_table').submit();
            }
          }

        google.visualization.events.addListener(chart , 'select' ,selectHandler);

        chart.draw(data,options_stacked);
      }

    $(window).resize(function(){
        drawChart();
    });  
}
/*End Daily Hospitalised Bar*/

/*Hospital Type Bar*/
function show_daily_hospital_type_bar(data)
{

    var government  = data.government;
    var private    = data.private;
    var discharge  = data.discharge;
    var cured      = data.cured;
      
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Count", { role: "style" } ],
        ["Government", government, "#FF5722"],
        ["Private", private, "#CDDC39"],
        ["Discharged", discharge, "#00BCD4"],
        ["Cured", cured, "color: #009688"]
      
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Hospital Type",
        width: 450,
        height: 230,
        bar: {groupWidth: "60%"},
       vAxis: {title: "Counts"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("hospital_type_graph"));

      function selectHandler() {
            var selectedItem = chart.getSelection()[0];
            if (selectedItem) {
              var value = data.getValue(selectedItem.row, 0);
              //alert('The user selected ' + value);
               var startDate = $('#type_passing_date').val();
              var endDate = $('#type_passing_end_date').val();
               $('#start_date_type').val(startDate);
              $('#end_date_type').val(endDate);
              $('#hospital_value').val(value);
              $('#hospital_type_table').submit();
             
            }
        }

      google.visualization.events.addListener(chart, 'select', selectHandler);

      chart.draw(view, options);
  }

  $(window).resize(function(){
    drawChart();
    });  
} 
/*End Hospital Type Bar*/
function last_three_months_more_req_students(){
    var todayDate = $('#passing_date').val();
    var endDate = $('#passing_end_date').val();
  
    $.ajax({
        url : 'last_three_months_req_monitoring',
        type : 'POST',
        data : {"start_date" : todayDate, "end_date": endDate},
        success : function(data){

          $("#loading_modal").modal('hide');
            
            var result = $.parseJSON(data); 
            data_table_req(result);
          
        }
    });

};

$("#date_set").click(function(){
    last_three_months_more_req_students();
});

/*Requests submitted admitted cases*/
  function data_table_req(result)
  { 
     start_date = $("#passing_date").val();
     end_date   = $("#passing_end_date").val();
     //school_name = $('#school_name').val();

  url = '<?php echo URL."ttwreis_mgmt/get_show_admitted_ehr_details"; ?>';

    $('#request_hospitalized_table_view').html('<div id="request_hospitalised" class="text-center"></div>');

        var table = '<div style="overflow-y: auto;" ><table class="table table-striped table-bordered table-hover" id="requestTable"><thead><tr><th>Status</th><th class="text-center">Requests count</th><th class="text-center">Action</th></tr></thead><tbody>'
            
          table = table + '<tr><th>Admitted</th><td><center>'+result.hospitalised+'</center></td><td class="hide">'+start_date+'</td><td class="hide">'+end_date+'</td><td class="hide">Admitted</td><td><center><button class="addd">Show</button></center></td></tr>'

            table = table + '<tr><th>Review</th><td><center>'+result.reiew+'</center></td><td class="hide">'+start_date+'</td><td class="hide">Review</td><td><center><button class="addd">Show</button></center></td></tr>'
            
            table = table + '<tr><th>Out-Patient</th><td><span id="chronic_request_uniqueids"><center>'+result.out+'</center></td><td class="hide">'+end_date+'</td><td class="hide">Out-Patient</td><td><center><button class="addd">Show</button></center></td></tr>'

        $("#request_hospitalised").html(table);
        table = table + '</tbody></table></div>';

    $("#requestTable").each(function(){
        $('.addd').click(function (){
             start_date = $("#passing_date").val();
             end_date   = $("#passing_end_date").val();
            var currentRow=$(this).closest("tr"); 
             
             //var request_type_new=currentRow.find("th:eq(2)").text();
             //var request_type_newww=currentRow.find("th:eq(3)").text();
             var request_type_new = currentRow.find("th:eq(0)").text();
             //alert(request_type_new);
            //var request_type_status=currentRow.find("td:eq(4)").text(); // get current row 4th TD
                    
            $("#start_date_new").val(start_date);
            $("#end_date_old").val(end_date);
            $("#request_type_newsss").val(request_type_new);
            //$("#request_type_status").val(request_type_status);
            $("#admitted_show_form").submit();

             
        });
     });
};

/*End Requests submitted admitted cases*/


</script>

<!-- Script for google bar graphs-->
<script type="text/javascript">

 school_health_status();

   function school_health_status(){
        var status_scl = $('#school_health_zone_wise').val();
        var district = $('.district_filter').val();
        var school = $('.school_filter').val();
        var check = "for_count";
       //alert(district);

       /* $.ajax({
                    url:'get_disease_wise_school_health_status',
                    type:'POST',
                    data:{'status' : status_scl, 'dist_id':district, 'scl_id':school, 'checks':check},
                    success:function(data){
                     var datas = $.parseJSON(data);
                     //console.log(datas);
                    show_student_health_status(datas);
                    }
                });*/
       if(district == "All"){

                $.ajax({
                    url:'get_disease_wise_school_health_status_for_all',
                    type:'POST',
                    data:{'status' : status_scl},
                    success:function(data){
                     var datas = $.parseJSON(data);
                    
                    //console.log(datas);
                    show_student_health_status(datas);
                    }
                });

       }else{

                $.ajax({
                    url:'get_disease_wise_school_health_status',
                    type:'POST',
                    data:{'status' : status_scl, 'dist_id':district, 'scl_id':school, 'checks':check},
                    success:function(data){
                     var datas = $.parseJSON(data);
                     //console.log(datas);
                    show_student_health_status(datas);
                    }
                });

       }
       

   };

$('.common_change').change(function(){
    school_health_status();
});

$('#school_health_zone_wise').change(function(){
       school_health_status();

   });

    $('#refresh_scl').click(function(){
        var status_scl = $('#school_health_zone_wise').val();
        var district = $('.district_filter').val();
        var school = $('.school_filter').val();
        var check = "for_count";

        //alert(status_scl);

         $.ajax({
           url:'refresh_for_create_db_school_zone_status',
           type:'POST',
           data:{'status' : status_scl, 'dist_id':district, 'scl_id':school, 'checks':check},
           success:function(data){
            $(this).window.load(1);
            //var datas = $.parseJSON(data);
            //console.log(datas);
           //show_student_health_status(datas);
           }
       });

    });

    $('#refresh_screening').click(function(){
       
        var academic = $('#academic_filter').val();

         $.ajax({
           url:'do_stage1_refresh',
           type:'POST',
           data:{'academic_year' : academic},
           success:function(data){
            $(this).window.load(1);
            //var datas = $.parseJSON(data);
            //console.log(datas);
           //show_student_health_status(datas);
           }
       });

    });



   function show_student_health_status(datas){
    //console.log('school status log',datas);
  
        var redZone = datas.red;
        var orangeZone = datas.orange;
        var greenZone = datas.green;
   
      google.charts.load("current", {packages:['corechart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ["Element", "Count", { role: "style" } ],
          ["Red Zone", redZone , "#FF5722"],
          ["Orange Zone", orangeZone , "#FFC107"],
          ["Green Zone", greenZone , "#8BC34A"]
        
        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
                         { calc: "stringify",
                           sourceColumn: 1,
                           type: "string",
                           role: "annotation" },
                         2]);

        var options = {
          title: "SCHOOL HEALTH STATUS",
          width: 350,
          height: 230,
        
          bar: {groupWidth: "50%"},
          legend: {position: 'none'},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
        function selectHandler()
            {
               var health_status = chart.getSelection()[0];
              if (health_status) {
                var value = data.getValue(health_status.row, 0);
                var district = $('.district_filter').val();
                var status_scl = $('#school_health_zone_wise').val();

                $('#value_scl_zone').val(value);
                $('#opt_selected').val(status_scl);
                $('#dist_id').val(district);
                $('#school_health_table').submit();
                

              }
            
            }

          google.visualization.events.addListener(chart , 'select' ,selectHandler);

        chart.draw(view, options);
    }


    $(window).resize(function(){
    drawChart();
    }); 

   };

 /*Strat Attendance submitted and not submitted list*/

$(".for_absent_report").click(function(){

    var dates = $(".date_for_sani_attend").val();
    ////alert(dates);

      $.ajax({
        url:'absent_report_for_date_wise',
        type:'POST',
        data:{'date' : dates},
        success:function(data){
            var datas = $.parseJSON(data);
            var lists = datas.absent_report_schools_list;
            

            attendace_sub_not_sub_counts(lists);
        }
    });

})

//var absent_sent_schools     = "";
//var absent_not_sent_schools = "";
var absent_submitted_schools_list     = "";
var absent_not_submitted_schools_list = "";
    

function attendace_sub_not_sub_counts(lists)
{
    absent_sent_schools = lists.submitted_count;
    absent_not_sent_schools = lists.not_submitted_count;

    $('.abs_submitted_schools').text(absent_sent_schools);
    $('.abs_not_submitted_schools').text(absent_not_sent_schools);

    absent_submitted_schools_list = lists.submitted;
    absent_not_submitted_schools_list = lists.not_submitted;
    

}  

/*Strat Attendance submitted and not submitted list*/

var absent_sent_schools     = <?php echo $absent_report_schools_list['submitted_count'];?>;
var absent_not_sent_schools = <?php echo $absent_report_schools_list['not_submitted_count'];?>;
var absent_submitted_schools_list     = "";
var absent_not_submitted_schools_list = "";
    
absent_submitted_schools_list = <?php echo json_encode($absent_report_schools_list['submitted']);?>;
absent_not_submitted_schools_list =<?php echo json_encode($absent_report_schools_list['not_submitted']);?>;



$('.abs_submitted_schools').html(absent_sent_schools);
$('.abs_not_submitted_schools').html(absent_not_sent_schools);

 $('.abs_submitted_schools_list').click(function(){
    
    if(absent_submitted_schools_list !=null)
    {
        if(absent_submitted_schools_list['school'] != "")
        {
            $('#absent_sent_school_modal_body').empty();
            var table="";
            var tr="";

            table += "<table class='table table-bordered table-striped table-hover' id='absent_sent_school_modal_body_tab'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";

            for(var i=0;i<absent_submitted_schools_list['school'].length;i++)
            {
                var j=i+1;
                table+= "<tr><td>"+j+"</td><td>"+absent_submitted_schools_list['district'][i]+"</td><td>"+absent_submitted_schools_list['school'][i]+"</td><td>"+absent_submitted_schools_list['mobile'][i]+"</td><td>"+absent_submitted_schools_list['person_name'][i]+"</td></tr>"
            }

            table += "</tbody></table>";
            $(table).appendTo('#absent_sent_school_modal_body');
        }
        else
        {
            table+="No Schools";
            $(table).appendTo('#absent_sent_school_modal_body');
        }
    }
    else
    {
        table+="No Schools";
        $(table).appendTo('#absent_sent_school_modal_body');
    }
    $('#absent_sent_school_modal').modal(show);
})


 $('.abs_not_submitted_schools_list').click(function(){
    if (absent_not_submitted_schools_list !=null) {
        if (absent_not_submitted_schools_list['school'] != "") {
            $('#absent_not_sent_school_modal_body').empty();
            var table="";
            var tr="";
            table += "<table class='table table-bordered table-striped table-hover'><thead><tr> <th>S.No</th> <th>District</th> <th>School Name</th> <th>Mobile</th> <th>Contact Person</th> </tr></thead><tbody>";

            for (var i = 0; i < absent_not_submitted_schools_list['school'].length; i++) {
                var j = i+1;
                table += "<tr><td>"+j+"</td> <td>"+absent_not_submitted_schools_list['district'][i]+"</td> <td>"+absent_not_submitted_schools_list['school'][i]+"</td> <td>"+absent_not_submitted_schools_list['mobile'][i]+"</td> <td>"+absent_not_submitted_schools_list['person_name'][i]+"</td> </tr>"
            }


            table += "</tbody></table>";
            $(table).appendTo('#absent_not_sent_school_modal_body');

        }else{
            table += "No Schools to Display";
            $(table).appendTo('#absent_not_sent_school_modal_body');
        }

    }else{
        table += "No Schools to Display";
        $(table).appendTo('#absent_not_sent_school_modal_body');
    }
    $('#absent_not_sent_school_modal').modal(show);
 })

 /*End of Attendance list*/

/*Start sanitation Submitted and not Submitted List*/

var sani_repo_sent_schools     = <?php echo $sanitation_report_schools_list['submitted_count'];?>;
var sani_repo_not_sent_schools = <?php echo $sanitation_report_schools_list['not_submitted_count'];?>;

var sani_repo_submitted_schools_list     = "";
var sani_repo_not_submitted_schools_list = "";
   
    
sani_repo_submitted_schools_list = <?php echo json_encode($sanitation_report_schools_list['submitted']);?>;
sani_repo_not_submitted_schools_list = <?php echo json_encode($sanitation_report_schools_list['not_submitted']);?>;
 
$('.sanitation_report_submitted_schools').html(sani_repo_sent_schools);
$('.sanitation_report_not_submitted_schools').html(sani_repo_not_sent_schools); 

$('.sanitation_report_submitted_schools_list').click(function(){

    if (sani_repo_submitted_schools_list !=null)
    {
        if (sani_repo_submitted_schools_list['school'] != "") 
        {
            $('#sani_repo_sent_school_modal_body').empty();
            var table ="";
            var tr ="";
            table +="<table class='table table-bordered table-striped table-hover'><thead><tr> <th>S.No</th> <th>District</th> <th>School Name</th> <th>Mobile</th> <th>Contact Person</th> </tr></thead><tbody>";

              for (var i = 0; i < sani_repo_submitted_schools_list['school'].length; i++) {
                  var j = i+1;
                  table += "<tr> <td>"+j+"</td> <td>"+sani_repo_submitted_schools_list['district'][i]+"</td> <td>"+sani_repo_submitted_schools_list['school'][i]+"</td> <td>"+sani_repo_submitted_schools_list['mobile'][i]+"</td> <td>"+sani_repo_submitted_schools_list['person_name'][i]+"</td> </tr>"
              }

              table += "</tbody></table>";
              $(table).appendTo('#sani_repo_sent_school_modal_body');

        }else
        {
            table += "No Schools";
            $(table).appendTo('#sani_repo_sent_school_modal_body');
        }
    }else
    {
        table += "No Schools";
        $(table).appendTo('#sani_repo_sent_school_modal_body');
    }
    $('#sani_repo_sent_school_modal').modal(show);

})

$('.sanitation_report_not_submitted_schools_list').click(function(){
    if(sani_repo_not_submitted_schools_list!=null)
    {
        var table= "";
        var tr   = "";
        
        if(sani_repo_not_submitted_schools_list['school']!="")
        {
            $('#sani_repo_not_sent_school_modal_body').empty();
            table += "<table class='table table-bordered table-striped table-hover'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";
            for(var i=0;i<sani_repo_not_submitted_schools_list['school'].length;i++)
            {
                var j=i+1;
                table+= "<tr><td>"+j+"</td><td>"+sani_repo_not_submitted_schools_list['district'][i]+"</td><td>"+sani_repo_not_submitted_schools_list['school'][i]+"</td><td>"+sani_repo_not_submitted_schools_list['mobile'][i]+"</td><td>"+sani_repo_not_submitted_schools_list['person_name'][i]+"</td></tr>"
            }
            table += "</tbody></table>";
            $(table).appendTo('#sani_repo_not_sent_school_modal_body');
           
        }
        else
        {
            table+="<table class='table table-bordered'><tbody><tr><td>No Schools</td></tr></tbody></table>";
            $(table).appendTo('#sani_repo_not_sent_school_modal_body');
           
        }
    }
    else
    {
        table+="No Schools";
        $(table).appendTo('#sani_repo_sent_school_modal_body');
    }
    $('#sani_repo_not_sent_school_modal').modal(show);
    })

/*End of sanitation Submitted and not Submitted List */ 

</script>
<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Work',     11],
          ['Eat',      2],
          ['Commute',  2],
          ['Watch TV', 2],
          ['Sleep',    7]
        ]);

        var options = {
          title: 'My Daily Activities'
        };

        var chart = new google.visualization.PieChart(document.getElementById('chronic_pie'));

        chart.draw(data, options);
      }
</script>
<script>
function openNav() {
  document.getElementById("mySidepanel").style.width = "250px";
}

function closeNav() {
  document.getElementById("mySidepanel").style.width = "0";
}
</script>

