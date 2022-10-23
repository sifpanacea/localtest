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
<!-- <link href="<?php //echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
 --><!-- <link href="<?php //echo(CSS.'toastr.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" /> -->
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
.imageThumb {
    max-height: 75px;
    border: 2px solid;
    padding: 1px;
    cursor: pointer;
  }
  .pip {
    display: inline-block;
    margin: 10px 10px 50px 90px;
  }
  .invalid{
    color:red;
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
    <article class="col-sm-12 col-md-12 col-lg-offset-1 col-lg-10">
      <!-- Widget ID (each widget will need unique ID)-->
       <div class="jarviswidget jarviswidget-color-orange" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" style="border: 2px solid #CB9235;">
        <header><center>
          <span class="widget-icon"> <i class="fa fa-pencil-square"></i> </span>
          <h2>Health Inspection</h2></center>              
        </header><!-- widget div-->
          
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
                                                <select class="form-control" id="page1_SchoolInfo_SchoolName" name="page1_SchoolInfo_SchoolName">
                                      <option value="">Select School </option>
                                      <?php foreach($schools_list as $school): ?>
                                       
                                        <option value="<?php echo $school['school_name']; ?>"><?php echo $school['school_name']; ?></option>
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
                                                 <input type="text" class="form-control"   name="page1_SchoolInfo_DateofVisit"value="<?php echo date('Y-m-d'); ?>" readOnly></div>
                                            <label class="col-md-2 ">Time</label>
                                              <div class="col-md-4" >
                                                 <input type="text" class="form-control" id="date_ss" name="page1_SchoolInfo_Timefromto" value="<?php date_default_timezone_set("Asia/Kolkata"); echo date('H:i:sa'); ?>" readonly="readonly"></div>
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
                                              <label class="col-md-2 ">HS NAME</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page2_SIFNOTEStatus_HSName"  name="page2_SIFNOTEStatus_HSName" ></div>
                                              
                                              <label class="col-md-2 ">HS QUALIFICATION</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page2_SIFNOTEStatus_HSQualification"name="page2_SIFNOTEStatus_HSQualification" ></div>
                                              </div>
                                        <div class="form-group">
                                              
                                               <label class="col-md-2">Stay of HS</label>
                                   <div class="col-md-8">
                                     <label class="col-md-offset-2 col-md-3 radio radio-inlineradio radio-inline">
                                       <input type="radio" class="radiobox" id="page2_SIFNOTEStatus_StayofHS_0" name="page2_SIFNOTEStatus_StayofHS" value="at Campus">
                                         <span> at Campus </span> 
                                      </label>
                                      <label class="col-md-offset-2 col-md-3 radio radio-inline col-md-2 radio radio-inline">
                                      <input type="radio" class="radiobox" id="page2_SIFNOTEStatus_StayofHS_1" name="page2_SIFNOTEStatus_StayofHS" value="Outside">
                                      <span> Outside </span>  
                                      </label>
                                              </div>  
                                  </div>
                                              <div class="form-group">
                                              <label class="col-md-2 ">Name of asst care taker</label>
                                              <div class="col-md-4">
                                                  <input type="text" class="form-control" id="page2_SIFNOTEStatus_Nameofasstcaretaker" name="page2_SIFNOTEStatus_Nameofasstcaretaker" ></div>
                                              
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
                                                  <input type="number" class="form-control" id="page3_SickRoomSpecifications_NumberofRooms" name="page3_SickRoomSpecifications_NumberofRooms" ></div>
                                              
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
                                                  <input type="text" class="form-control" id="page9_Others_Education" name="page9_Others_Education" ></div>
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
                              <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Inspected By</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">Name</label>
                                        <div class="col-md-8">
                                          <input type="text" class="form-control" id="page9_NameandSignatureoftheInspectionOfficer_Name" name="page9_NameandSignatureoftheInspectionOfficer_Name">        
                                         </div>    
                                </div>
                                

                        </div></fieldset>
                              <!-- <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading  text-center">
                              <strong>Name and Signature of the Inspection Officer
                              </strong></div>
                              
                            <div class="form-group ">
                              
                          <label class="col-md-2">Name</label>
                          <input type="text" class="col-md-8 form-control" id="page9_NameandSignatureoftheInspectionOfficer_Name"  name="page9_NameandSignatureoftheInspectionOfficer_Name" />
                           
                          </div>
                   
                          </div>
                        
                        </fieldset> -->
                              <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading  text-center"><strong>Attachments</strong></div>
                            <div class="form-group ">
                              
                         
                          <input type="file" id="files"  name="Check_health_inspection_attachments[]" style="display:none;" multiple>
                            <label for="files" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                               Browse.....
                           </label>
                          </div>
                   
                          </div>
                        
                        </fieldset>                
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
  $("#web_view").validate({
    ignore: "",
    // Rules for form validation
          rules : {
          page1_SchoolInfo_SchoolName:{required:true},
           page2_SIFNOTEStatus_HSName:{required:true},
           page2_SIFNOTEStatus_HSQualification:{required:true},
           page1_SIFNOTEStatus_InfotoPANACEA:{required:true},
           page2_SIFNOTEStatus_Nameofasstcaretaker:{required:true},
           page2_SIFNOTEStatus_AsstcaretakerQualification:{required:true},
           page2_SIFNOTEStatus_StudentsStrength:{required:true},
           page2_SIFNOTEStatus_Classes:{required:true},
           page3_SickRoomSpecifications_NumberofRooms:{required:true},
           page3_SickRoomSpecifications_TableMaintenance:{required:true},
           page3_SickRoomSpecifications_GreenCloth:{required:true},
           page3_Tray_Betadine:{required:true},
           page3_Tray_SurgicalSpirit:{required:true},
           page3_Tray_HydrogenPeroxide:{required:true},
           page3_Tray_CottonorGauge:{required:true},
           page4_Equipment_WeighingMachine:{required:true},
           page4_Equipment_BPapparatus:{required:true},
           page4_Equipment_PulseOxymeter:{required:true},
           page4_Equipment_Thermometer:{required:true},
           page4_Equipment_Stethoscope:{required:true},
           page4_Equipment_Nebulizer:{required:true},
           page4_Equipment_ExaminationTable:{required:true},
           page4_Equipment_SalineStand:{required:true},
           page5_Equipment_CotsorMattress:{required:true},
           page5_Equipment_Curtains:{required:true},
           page5_Equipment_Mesh:{required:true},
           page5_Equipment_Fans:{required:true},
           page5_Pharmacy_Emergency:{required:true},
           page5_Pharmacy_Regular:{required:true},
           page5_Pharmacy_FlowCharts:{required:true},
           page6_AnyHealthCheckups_Vision:{required:true},
           page6_AnyHealthCheckups_HB:{required:true},
           page6_AnyHealthCheckups_Dental:{required:true},
           page6_AnyHealthCheckups_Deworming:{required:true},
           page6_AnyHealthCheckups_Vaccination:{required:true},
           page6_AnyHealthCheckups_Hospitalization:{required:true},
           page6_AnyHealthCheckups_Epidemics:{required:true},
           page7_Incenerators_WorkingCondition:{required:true},
           page7_Incenerators_Usingornotusing:{required:true},
           page7_Others_Flycatchers:{required:true},
           page7_Others_ROPlant:{required:true},
           page7_Others_Washrooms:{required:true},
           page7_Others_SinksatWashroomorMess:{required:true},
           page7_Others_HandwashatWashroomorMess:{required:true},  
           page8_Others_PrincipalVisitdate:{required:true},
           page8_Others_NameofPDorPET:{required:true},
           page8_Others_Experience:{required:true},
           page8_Others_PETQualification:{required:true},
           page8_Others_StayofPET:{required:true},
           page9_Others_RegularExercise:{required:true},
           page9_Others_DietaryHabits:{required:true},
           page9_Others_Awareness:{required:true},
           page9_Others_Education:{required:true},
           page9_Others_Motivation:{required:true},  
           page9_Others_SpecialSports:{required:true},
           page9_NameandSignatureoftheInspectionOfficer_Name:{required:true},

          
          },
  
      //Messages for form validation
        messages : {
          page1_SchoolInfo_SchoolName:{
          required:"please select school name"},
        

        },
        highlight: function(element) {
           
               // add a class "has_error" to the element 
               $(element).addClass('has_error');
           },
        unhighlight: function(element) {
           // remove the class "has_error" from the element 
               $(element).removeClass('has_error');
           },
        onkeyup: false, //turn off auto validate whilst typing
        });
  var page1_SchoolInfo_SchoolName = $('#page1_SchoolInfo_SchoolName').val();
    $('#page1_SchoolInfo_SchoolName').change(function (){
        page1_SchoolInfo_SchoolName = $('#page1_SchoolInfo_SchoolName').val();

        $.ajax({
                  url: 'get_school_info_by_school_name',
                  type: 'POST',
                  data: {"school_name" : page1_SchoolInfo_SchoolName},
                  success: function (data) {      

                    result = $.parseJSON(data);
                    console.log(result);

                          $.each(result, function() {
                            $("#page1_SIFNOTEStatus_PrincipalName").val(this['contact_person_name']);
                           /* $("#page1_SchoolInfo_PrincipalNumber").val(this['school_mob']);*/
                          
                         });
                    },
                      error:function(XMLHttpRequest, textStatus, errorThrown)
                    {
                     console.log('error', errorThrown);
                      }
         });
         return false;
    });

  if (window.File && window.FileList && window.FileReader) 
      {
        
    //var numFiles = $("input:file")[0].files.length;
     $("#files").on("change", function(e) {
      $(".remove").parent(".pip").remove();
      var files = e.target.files,
          filesLength = files.length;
          console.log('filesLength',filesLength);
        for(var j=0;j<filesLength;j++)
        {
          var f = files[j]
            var fileReader = new FileReader();
            fileReader.onload = (function(e) {
              var file = e.target;
              $("<span   class=\"pip\">" +
              "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
              "<br/><span class=\"remove\">Remove image</span>" +
              "</span>").insertAfter("#files");
              $(".remove").on('click',function(){
                $(this).parent(".pip").remove();
              });
            });
             fileReader.readAsDataURL(f);

         var size = $("input:file")[0].files[j].size;
       if(size > 2000000 )
       {
            $.bigBox({
          title   : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message !",
          content : "Attach file less than 2 MB !",
          color   : "#C46A69",
          icon    : "fa fa-warning shake animated",
          timeout : 8000
          });
        
        e.preventDefault();
        $("input:file").val(""); 
        var no_of_files = $("input:file")[0].files.length;
          var count = no_of_files+' files attached';
          $('.file_attach_count').text(count);
              $(this).removeClass("hide");
        break;
        
       }
        }
         $("input:file").html("#files");
      
      //var files = $(".imageThumb").array(); 
    });

    }
});

</script>





<?php 
  //include footer
  include("inc/footer.php"); 
?>