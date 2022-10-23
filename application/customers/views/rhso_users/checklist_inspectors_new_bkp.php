<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Health Inspection";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["HEALTH INSPECTION"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<style type="text/css">
  label
  {
    font-weight: bold;
    font-size: inherit
  }
  #student_photo
{
  width: 148px;
    height: 130px;
}
.panel-primary>.panel-heading {
     color: #fff; 
     background-color: #2AA2C1; 
     border-color: #2AA2C1; 
}
/* IF YOU WANT ADD A ACCORDIAN JUST ADD THIS DATA-TOGGLE ="COLLAPSE"  */
 
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<div id="main" role="main">
  <?php include("inc/ribbon.php");?>
<!-- MAIN PANEL -->
  <div id="content">
    <div class="row">
   <!-- NEW WIDGET START -->
    <article class="col-sm-12 col-md-12 col-lg-11">
      <!-- Widget ID (each widget will need unique ID)-->
       <div class="jarviswidget jarviswidget-color-orange" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" style="border: 2px solid #CB9235;">
        <header><center>
          <span class="widget-icon"> <i class="fa fa-pencil-square"></i> </span>
          <h2>Health Inspection</h2></center>              
        </header><!-- widget div-->
          <div>
            <div class="widget-body">
   
                <?php  $attributes = array('class' => 'form-horizontal','id'=>'web_view','name'=>'userform');
                      echo  form_open_multipart('rhso_users/health_inspection_form_submit',$attributes);?>
               <!-- <legend>Student Details</legend> -->
                   <div class="panel-group" id="accordion">
                      <div class="panel panel-primary">
                              <div class="panel-heading bgc_head"  data-parent="#accordion" href="#collapse1"><strong >School Info</strong></div>
                              <div class="panel-body panel-collapse collapse in" id="collapse1">
                                            
                                         <div class="form-group">
                                              <label class="col-md-2">School Name</label>
                                              <div class="col-md-4">
                                                <select class="form-control" id="page1_SchoolInfo_SchoolName" name="page2_Personal Information_School Name">
                                      <option>Select School </option>
                                      <?php foreach($schools_list as $school): ?>
                                          <option><?php echo $school['school_name']; ?></option>
                                      <?php endforeach; ?>
                                    </select>
                                                  </div>
                                              <label class="col-md-2 ">Principal Name</label>
                                              <div class="col-md-4">
                                                 <input type="text" class="form-control" id="page1_SIFNOTEStatus_PrincipalName"name="page1_SIFNOTEStatus_PrincipalName"></div>
                                              
                                              </div>

                                              <div class="form-group">
                                              <label class="col-md-2 ">Date of Visit</label>
                                              <div class="col-md-4">
                                                 <input type="text" class="form-control"   value="<?php echo date('Y-m-d'); ?>" readonly></div>
                                            <label class="col-md-2 ">Time</label>
                                              <div class="col-md-4" >
                                                 <input type="text" class="form-control" id="" name="date_ss" value="<?php date_default_timezone_set("Asia/Kolkata"); echo date('H:i:sa'); ?>" readonly="readonly"></div>
                                              </div>
                                            </div>
                       </div>                         
                                             <!--  <fieldset class="demo-switcher-1"> -->
                            <div class="panel panel-primary">

                      <div class="panel-heading bgc_head"  data-parent="#accordion" href="#collapse2"><strong>SIFNOTE Status</strong></div>
                      <div class="panel-body panel-collapse collapse in" id="collapse2">
                                <div class="form-group">
                                 <label class="col-md-3">Info to PANACEA</label>
                                   <div class="col-md-8">
                                     <label class="col-md-3 radio radio-inline">
                                       <input type="radio" class="radiobox" name="page1_SIFNOTEStatus_InfotoPANACEA" id="page1_SIFNOTEStatus_InfotoPANACEA_0" value="Yes">
                                         <span>Yes</span> 
                                      </label>
                                      <label class="col-md-3 col-md-4 radio radio-inline">
                                      <input type="radio" class="radiobox" name="page1_SIFNOTEStatus_InfotoPANACEA" id="page1_SIFNOTEStatus_InfotoPANACEA_1" value="No">
                                      <span>No</span>  
                                      </label>
                                        </div>         
                                        </div>                
                             <div class="form-group">
                                              <label class="col-md-2 ">HS  NAME</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page2_SIFNOTEStatus_HSName"  name="page2_SIFNOTEStatus_HSName" readonly></div>
                                              
                                              <label class="col-md-2 ">HS  QUALIFICATION</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page2_SIFNOTEStatus_HSQualification"name="page2_SIFNOTEStatus_HSQualification" readonly></div>
                                              </div>
                                        <div class="form-group">
                                              
                                               <label class="col-md-2">Stay of HS</label>
                                   <div class="col-md-8">
                                     <label class="col-md-offset-2 col-md-3 radio radio-inlineradio radio-inline">
                                       <input type="radio" class="radiobox" id="page2_SIFNOTEStatus_StayofHS_0" name="page2_SIFNOTEStatus_StayofHS" value="at Campus">
                                         <span> at Campus </span> 
                                      </label>
                                      <label class="col-md-offset-2 col-md-3 radio radio-inline col-md-2 radio radio-inline">
                                      <input type="radio" class="radiobox" name="page2_SIFNOTEStatus_StayofHS_1" id="page2_SIFNOTEStatus_StayofHS" value="Outside">
                                      <span> Outside </span>  
                                      </label>
                                              </div>  
                                  </div>
                                              <div class="form-group">
                                              <label class="col-md-2 ">Name of asst care taker</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page2_SIFNOTEStatus_Nameofasstcaretaker" name="page2_SIFNOTEStatus_Nameofasstcaretaker" readonly></div>
                                              
                                              <label class="col-md-2 ">Asst care taker Qualification</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page2_SIFNOTEStatus_AsstcaretakerQualification" name="page2_SIFNOTEStatus_AsstcaretakerQualification" ></div>
                                              </div> 
                                               <div class="form-group">
                                                <label class="col-md-2 ">Stay of Asst care taker</label>
                                   <div class="col-md-8">
                                     <label class="col-md-offset-2 col-md-3 radio radio-inline">
                                       <input type="radio" class="radiobox" name="page2_SIFNOTEStatus_StayofAsstcaretaker" id="page2_SIFNOTEStatus_StayofAsstcaretaker_0" value="at Campus">
                                         <span>at Campus</span> 
                                      </label>
                                      <label class=" col-md-offset-2 col-md-4 radio radio-inline">
                                      <input type="radio" class="radiobox" name="page2_SIFNOTEStatus_StayofAsstcaretaker" id="page2_SIFNOTEStatus_StayofAsstcaretaker_1" value="Outside">
                                      <span>Outside</span>  
                                      </label>
                                              </div>  
                            </div>
                        <div class="form-group">
                                              <label class="col-md-2 ">Students Strength</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page2_SIFNOTEStatus_StudentsStrength" name="page2_SIFNOTEStatus_StudentsStrength" ></div>
                                              
                                              <label class="col-md-2 ">Classes</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page2_SIFNOTEStatus_Classes" name="page2_SIFNOTEStatus_Classes" ></div>
                                              </div>
                                            </div>
                          </div>                  
                                           <!--  </fieldset> -->
                                              <div class="panel panel-primary">
                                      <div class="panel-heading bgc_head"  data-parent="#accordion" href="#collapse3"><strong> Sick Room Specifications</strong></div>
                          <div id="collapse3" class="panel-body panel-collapse collapse in">
                                              <div class="form-group">
                                              <label class="col-md-2 ">Number of Rooms</label>
                                              <div class="col-md-4">
                                                  <input type="Number" class="form-control" id="page3_SickRoomSpecifications_NumberofRooms" name="page3_SickRoomSpecifications_NumberofRooms" ></div>
                                              
                                              <label class="col-md-2 ">Table Maintenance</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page3_SickRoomSpecifications_TableMaintenance" name="page3_SickRoomSpecifications_TableMaintenance" ></div>
                                              </div><div class="form-group">
                                              <label class="col-md-2 ">Green Cloth</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page3_SickRoomSpecifications_GreenCloth" name="page3_SickRoomSpecifications_GreenCloth" ></div>
                                             </div>
                                           </div>
                       </div>                    
                                          <div class="panel panel-primary">
                                         <div class="panel-heading bgc_head"  data-parent="#accordion" href="#collapse4"><strong> Tray</strong></div>
                                          <div class="panel-body panel-collapse collapse in" id="collapse4">
                                           <div class="form-group"> 
                                              <label class="col-md-2 ">Betadine</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page3_Tray_Betadine" name="page3_Tray_Betadine" ></div>
                                             
                                              <label class="col-md-2 ">Surgical Spirit</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page3_Tray_SurgicalSpirit" name="page3_Tray_SurgicalSpirit" ></div>
                                                </div>
                                              
                                              <div class="form-group">
                                              <label class="col-md-2 ">Hydrogen Peroxide</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page3_Tray_HydrogenPeroxide" name="page3_Tray_HydrogenPeroxide" ></div>
                                              
                                              <label class="col-md-2 ">Cotton or Gauge</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page3_Tray_CottonorGauge" name="page3_Tray_CottonorGauge" ></div>
                                              </div>
                                            </div>
                                          </div>
                                              <div class="panel panel-primary">
                                           <div class="panel-heading bgc_head"  data-parent="#accordion" href="#collapse5"><strong> Equipment</strong></div> 
                                            <div class="panel-body panel-collapse collapse in" id="collapse5">
                                              <div class="form-group">
                                              <label class="col-md-2 ">Weighing Machine</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page4_Equipment_WeighingMachine" name="page4_Equipment_WeighingMachine" ></div>
                                              
                                              <label class="col-md-2 ">BP apparatus</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page4_Equipment_BPapparatus" name="page4_Equipment_BPapparatus" ></div>
                                              </div>
                                              <div class="form-group">
                                              <label class="col-md-2 ">Pulse Oxymeter</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page4_Equipment_PulseOxymeter" name="page4_Equipment_PulseOxymeter" ></div>
                                              
                                              <label class="col-md-2 ">Thermometer</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page4_Equipment_Thermometer" name="page4_Equipment_Thermometer" ></div>
                                              </div><div class="form-group">
                                              <label class="col-md-2 ">Stethoscope</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page4_Equipment_Stethoscope" name="page4_Equipment_Stethoscope" ></div>
                                              
                                              <label class="col-md-2 ">Nebulizer</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page4_Equipment_Nebulizer" name="page4_Equipment_Nebulizer" ></div>
                                              </div>
                                              <div class="form-group">
                                              <label class="col-md-2 ">Examination TABLE</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page4_Equipment_ExaminationTable" name="page4_Equipment_ExaminationTable" ></div>
                                              
                                              <label class="col-md-2 ">Saline Stand</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page4_Equipment_SalineStand" name="page4_Equipment_SalineStand" ></div>
                                              </div>
                                              
                                              <div class="form-group">
                                              <label class="col-md-2 ">Cots or Mattress</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page5_Equipment_CotsorMattress" name="page5_Equipment_CotsorMattress" ></div>
                                            
                                              <label class="col-md-2 ">Curtains</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page5_Equipment_Curtains" name="page5_Equipment_Curtains" ></div>
                                              </div>
                                              <div class="form-group">
                                              <label class="col-md-2 ">Mesh</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page5_Equipment_Mesh" name="page5_Equipment_Mesh" ></div>
                                           
                                              <label class="col-md-2 ">Fans</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page5_Equipment_Fans" name="page5_Equipment_Fans" ></div>
                                              </div>
                                            </div>
                                          </div>
                                  <div class="panel panel-primary">
                                           <div class="panel-heading bgc_head"  data-parent="#accordion" href="#collapse6"><strong> Pharmacy</strong></div>
                                           <div class="panel-body panel-collapse collapse in" id="collapse6"> 
                                             <div class="form-group">
                                              <label class="col-md-2 ">Emergency</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page5_Pharmacy_Emergency" name="page5_Pharmacy_Emergency" ></div>
                                            
                                              <label class="col-md-2 ">Regular</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page5_Pharmacy_Regular" name="page5_Pharmacy_Regular" ></div>
                                              </div><div class="form-group">
                                              <label class="col-md-2 ">Flow Charts</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page5_Pharmacy_FlowCharts" name="page5_Pharmacy_FlowCharts" ></div>
                                              </div>
                                            </div>
                                          </div>
                          <div class="panel panel-primary">
                              <div class="panel-heading bgc_head"  data-parent="#accordion" href="#collapse7"><strong> Any Health Checkups</strong></div>
                              <div class="panel-body panel-collapse collapse in" id="collapse7">
                                              <div class="form-group">
                                              <label class="col-md-2 ">Vision</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page6_AnyHealthCheckups_Vision" name="page6_AnyHealthCheckups_Vision" ></div>
                                              
                                              <label class="col-md-2 ">HB</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page6_AnyHealthCheckups_HB" name="page6_AnyHealthCheckups_HB" ></div>
                                              </div><div class="form-group">
                                              <label class="col-md-2 ">Dental</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page6_AnyHealthCheckups_Dental" name="page6_AnyHealthCheckups_Dental" ></div>
                                              
                                              <label class="col-md-2 ">Deworming</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page6_AnyHealthCheckups_Deworming" name="page6_AnyHealthCheckups_Deworming" ></div>
                                              </div><div class="form-group">
                                              <label class="col-md-2">Vaccination</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page6_AnyHealthCheckups_Vaccination" name="page6_AnyHealthCheckups_Vaccination" ></div>
                                              
                                              <label class="col-md-2 ">Hospitalization</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page6_AnyHealthCheckups_Hospitalization" name="page6_AnyHealthCheckups_Hospitalization" ></div>
                                              </div><div class="form-group">
                                              <label class="col-md-2 ">Epidemics</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page6_AnyHealthCheckups_Epidemics" name="page6_AnyHealthCheckups_Epidemics" ></div>
                                              </div>
                                    </div>
                                </div>
                                              <div class="panel panel-primary">
                              <div class="panel-heading bgc_head"  data-parent="#accordion" href="#collapse9"><strong> Incenerators</strong></div>
                              <div class="panel-body panel-collapse collapse in" id="collapse9">
                                              <div class="form-group">
                                              <label class="col-md-2 ">Working Condition</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page7_Incenerators_WorkingCondition" name="page7_Incenerators_WorkingCondition" ></div>
                                             
                                              <label class="col-md-2 ">Using or not using</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page7_Incenerators_Usingornotusing" name="page7_Incenerators_Usingornotusing" ></div>
                                              </div>
                                            </div>
                               </div>             
                              <div class="panel panel-primary">
                              <div class="panel-heading bgc_head"  data-parent="#accordion" href="#collapse8"><strong>Others </strong></div>
                              <div class="panel-body panel-collapse collapse in" id="collapse8">
                                              <div class="form-group">
                                              <label class="col-md-2 ">Fly catchers</label>
                                              <div class="col-md-4">
                                                <input type="text" class="form-control" id="page7_Others_Flycatchers" name="page7_Others_Flycatchers" ></div>
                                              
                                              <label class="col-md-2 ">RO Plant</label>
                                              <div class="col-md-4">
                                                <input type="text" class="form-control" id="page7_Others_ROPlant" name="page7_Others_ROPlant">
                                              </div>
                                              </div>
                                              <div class="form-group">
                                              <label class="col-md-2 ">Wash rooms</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page7_Others_Washrooms" name="page7_Others_Washrooms" ></div>
                                              
                                              <label class="col-md-2 ">Sinks at Wash room or Mess</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page7_Others_SinksatWashroomorMess" name="page7_Others_SinksatWashroomorMess" ></div>
                                              </div>
                                              <div class="form-group">
                                              <label class="col-md-2 ">Handwash at Wash room or Mess</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page7_Others_HandwashatWashroomorMess" name="page7_Others_HandwashatWashroomorMess" ></div>
                                              
                                              <label class="col-md-2 ">Principal Visit date</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page8_Others_PrincipalVisitdate" name="page8_Others_PrincipalVisitdate" ></div>
                                              </div>
                                              <div class="form-group">
                                              <label class="col-md-2 ">Name of PD or PET</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page8_Others_NameofPDorPET" name="page8_Others_NameofPDorPET" ></div>
                                              
                                              <label class="col-md-2 ">Experience</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page8_Others_Experience" name="page8_Others_Experience" ></div>
                                              </div>
                                              <div class="form-group">
                                                  <label class="col-md-2 ">PET Qualification</label>
                                                 <label class="col-md-offset-2 col-md-3 radio radio-inline">
                                       <input type="radio" class="radiobox" name="page8_Others_PETQualification" value="Regular">
                                         <span>Regular</span> 
                                      </label>
                                      <label class=" col-md-offset-2 col-md-4 radio radio-inline">
                                      <input type="radio" class="radiobox" name="page8_Others_PETQualification" value="on Contract">
                                      <span>on Contract</span>  
                                      </label>
                                              </div>
                                               <div class="form-group">
                                                  <label class="col-md-2 ">Stay of PET</label>
                                                 <label class="col-md-offset-2 col-md-3 radio radio-inline">
                                       <input type="radio" class="radiobox" id="page8_Others_StayofPET_0" name="page8_Others_StayofPET" value="at Campus">
                                         <span>at Campus</span> 
                                      </label>
                                      <label class=" col-md-offset-2 col-md-4 radio radio-inline">
                                      <input type="radio" class="radiobox" id="page8_Others_StayofPET_1" name="page8_Others_StayofPET" value="Outside">
                                      <span>Outside</span>  
                                      </label>
                                              </div>
                                              <!-- <div class="form-group">
                                                  <label class="col-md-2 ">Type</label>
                                                 <label class="col-md-offset-2 col-md-3 radio radio-inline">
                                       <input type="radio" class="radiobox" name="" value="Yes">
                                         <span>SWAEROE</span> 
                                      </label>
                                      <label class=" col-md-offset-2 col-md-4 radio radio-inline">
                                      <input type="radio" class="radiobox" name="" value="No">
                                      <span>Non SWAEROES</span>  
                                      </label>
                                              </div> -->
                                              <div class="form-group">
                                              <label class="col-md-2 ">Regular Exercise</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page9_Others_RegularExercise" name="page9_Others_RegularExercise" ></div>
                                              
                                              <label class="col-md-2 ">Dietary Habits</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page9_Others_DietaryHabits" name="page9_Others_DietaryHabits" ></div>
                                              </div>
                                             <div class="form-group">
                                              <label class="col-md-2 ">Awareness</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page9_Others_Awareness" name="page9_Others_Awareness" ></div>
                                              
                                              <label class="col-md-2 ">Education</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page9_Others_Education" name="page9_Others_Educationc" ></div>
                                              </div> <div class="form-group">
                                              <label class="col-md-2">Motivation</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page9_Others_Motivation" name="page9_Others_Motivation" ></div>
                                              
                                              <label class="col-md-2">Special Sports
                                              </label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page9_Others_SpecialSports" name="page9_Others_SpecialSports" ></div>
                                              </div>
                                      </div> 
                                  </div>     
                              </div>                
                                              <div class="form-group">
                                              <!-- <label class="col-md-2 ">NAME</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="" name="" ></div> -->
                                                  <button class="btn btn-success col-md-offset-5" type="submit">
                                                    <i class="fa fa-save"></i>
                                                    SUBMIT
                                                  </button>
                                              </div>
                                                

              <?php echo form_close(); ?>

            </div>
                    <!-- end widget content -->
        </div>
              <!-- end widget div -->
      </div>
       <!-- end widget -->
    </article>
                    <!-- WIDGET END -->
    </div>
  </div>
</div>

<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
  //include required scripts
  include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin Flot Engine, Flot Resizer, Flot Tooltip -->
<!-- <script src="<?php //echo JS; ?>toastr.min.js"></script> -->


<script>
$(document).ready(function() {

  //toastr.info('Toastr Included');

  runAllForms();

  $.validator.addMethod('fileminsize', function(value, element, param) {
  return this.optional(element) || (element.files[0].size >= param) 
 });

 
  $.validator.addMethod('filemaxsize', function(value, element, param) {
  return this.optional(element) || (element.files[0].size <= param) 
 });

  $(function() {
// Validation
$("#web_view").validate({
ignore: "",
// Rules for form validation
rules : {
page1_StudentDetails_HospitalUniqueID:{required:true,minlength:1,maxlength:123},
page1_StudentDetails_Name:{minlength:1,maxlength:123},
page1_StudentDetails_Class:{minlength:1,maxlength:123},
page1_StudentDetails_Section:{minlength:1,maxlength:123},
page1_StudentDetails_bloodgroup:{required:true,minlength:1,maxlength:4},
page1_StudentDetails_HB:{required:true,minlength:1,maxlength:4},
 page1_StudentDetails_BMI:{minlength:1,maxlength:5}
// page1_StudentDetails_Date:{required:false},
},
  
  //Messages for form validation
messages : {page1_StudentDetails_HospitalUniqueID:{required:"Hospital Unique ID field is required"},
page1_StudentDetails_Name:{},
page1_StudentDetails_Class:{},
page1_StudentDetails_Section:{},
page1_StudentDetails_bloodgroup:{required:"bloodgroup  field is required"},
page1_StudentDetails_HB:{required:"HB  field is required"},
page1_StudentDetails_BMI:{},
},onkeyup: false, //turn off auto validate whilst typing
// Do not change code below
           errorPlacement : function(error, element) {
           error.insertAfter(element.parent());
}
});

});
});
</script>
<script type="text/javascript">
  //date functionality 
  
/*
    var date = new Date();
    
  document.getElementById('date_ss').value = date.toLocaleDateString();*/
    
    
  
</script>
<script type="text/javascript">
 

$('#page1_StudentDetails_HospitalUniqueID').on('keypress, keydown', function(event) {
  var $field = $(this);
  // $('#output').text(event.which + '-' + this.selectionStart);
  if ((event.which != 37 && (event.which != 39)) &&
    ((this.selectionStart < readOnlyLength) ||
      ((this.selectionStart == readOnlyLength) && (event.which == 8)))) {
    return false;
  }
});
// search button 


$('#searchIdBtn').click(function (){

    var uniqueId = $('#page1_StudentDetails_HospitalUniqueID').val();
  
    $.ajax({
      url: 'fetch_student_info',
      type: 'POST',
      data: {'page1_StudentDetails_HospitalUniqueID':uniqueId },
      success:function(data){
      
     if(data == 'NO_DATA_AVAILABLE')
        {

            var uniqueIdField = $("input#page1_StudentDetails_HospitalUniqueID").val();
            $('#web_view').trigger('reset');
            $("input#page1_StudentDetails_HospitalUniqueID").val(uniqueIdField);

            toastr.error('NO_DATA_AVAILABLE');

            toastr.options = {
              "closeButton": false,
              "debug": false,
              "newestOnTop": false,
              "positionClass": "center",
              "preventDuplicates": false,
              "onclick": null,
              "showDuration": "300",
              "hideDuration": "1000",
              "timeOut": "5000",
              "extendedTimeOut": "1000",
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            }
      
        }
        else{

          data = $.parseJSON(data);
          get_data = data.get_data;
          console.log('get_data',get_data);
          $.each(get_data, function() {
            $("#page1_StudentDetails_Name").val(this['doc_data']['widget_data']['page1']['Personal Information']['Name']);
            
            $("#page1_StudentDetails_Class").val(this['doc_data']['widget_data']['page2']['Personal Information']['Class']);
            $("#page1_StudentDetails_Section").val(this['doc_data']['widget_data']['page2']['Personal Information']['Section']);
            
            if(typeof(this['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path']) != "undefined" && this['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'] !== null)
           {
             var photo_student = this['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];
          // $('#student_image').append('<img src='<?php //echo URLCustomer;?>+photo_student+'/>');
           $('#student_image').show();
           $('#image_logo').hide();
           
               /*$('<img />', {
                 src: '<?php //echo URLCustomer;?>'+photo_student+'',
                 width: '100px',
                 height: '100px',
                 id : 'logo',
                 class : 'logo_img'
             }).appendTo($('#student_image'));*/
             $('#student_image').html('<img id="student_photo" src="<?php echo URLCustomer;?>'+photo_student+'">');
                    /*.attr('src', "<?php //echo URLCustomer;  ?>" + this['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'] + "").css({"height":"200 px","width" : "100px"})
                     .val($('#student_image'));*/
                     // $('#image_logo').show();
                    
           }

           else {
                  //$('#image_logo').load(location.href + ' #image_logo');
             
             $('#image_logo').show();
             $('#image_logo').text( "No Image Found").css({"color":"red","text-align":"center"});
             $('#student_image').hide();
            

           }

            
            });


        }

        

      },
      error:function(XMlHttpRequest, textStatus, errorThrown) {
        console.log('error',errorThrown);

      }
    })

    return false;

    });
</script>



<?php 
  //include footer
  include("inc/footer.php"); 
?>