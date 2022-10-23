<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Sanitation Inspection";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Sanitation Inspection"]["active"] = true;
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
 .panelheading{
  color: white !important;
    background-color: #40a3c3 !important;

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
  .errors{
  color: red;
 /* display: block;*/
}


</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<div id="main" role="main">
  <?php

  include("inc/ribbon.php");
  ?>
<!-- MAIN PANEL -->
    <div id="content">
       
            <div class="row">
               <!-- NEW WIDGET START -->
                    <article class="col-sm-12 col-md-12 col-lg-offset-1 col-lg-10">
                
                      <!-- Widget ID (each widget will need unique ID)-->
                      <div class="jarviswidget jarviswidget-color-orange" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false">
                        
                        <header class="text-center">
                          <span class="widget-icon"> <i class="fa fa-eye"></i> </span>
                          <h2 >Sanitation Inspection</h2>
                
                        </header>
                
                        <!-- widget div-->
                        <div>
                
                         
                          <div class="widget-body">
                
                         <?php  $attributes = array('class' => 'form-horizontal','id'=>'web_view','name'=>'userform');
                            echo  form_open_multipart('rhso_users/sanitation_inspection_submit_v2',$attributes);
                            ?>
                            
                            <fieldset>
                              
                              <div class="row col-lg-12">
                                

                               <div class="form-group">
                                <label class="col-md-2 control-label">School Name</label>
                                <div class="col-md-4">

                                     <select class="form-control" id="page1_SchoolInfo_SchoolName" name="page1_SchoolInfo_SchoolName">
                                      <option value="">Select School </option>

                                      <?php foreach($schools_list as $school): ?>
                                        
                                          <option value="<?php echo $school['school_name']; ?>"><?php echo $school['school_name']; ?></option>
                                      <?php endforeach; ?>
                                    </select>
                                  </div>


                                
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Principal Name</label>
                                <div class="col-md-4">
                                   <input type="text" class="form-control" id="page1_SchoolInfo_PrincipalName"  name="page1_SchoolInfo_PrincipalName"></div>
                                   <label class="col-md-2 control-label">Contact NO</label>
                                <div class="col-md-4">
                                   <input type="number" class="form-control" id="page1_SchoolInfo_ContactNumber"  name="page1_SchoolInfo_ContactNumber">
                                 </div>
                               </div>
                                
                                <div class="form-group">
                                <label class="col-md-2 control-label">Health Supervisor Name</label>
                                <div class="col-md-4">
                                   <input type="text" class="form-control" id="page1_SchoolInfo_HSName"  name="page1_SchoolInfo_HSName"></div>
                                   <label class="col-md-2 control-label">Asst Care Taker Name</label>
                                <div class="col-md-4">
                                   <input type="text" class="form-control" id="page1_SchoolInfo_AsstCareTakerName"  name="page1_SchoolInfo_AsstCareTakerName"></div>
                                </div>
                               
                               </fieldset>
                              <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>GENERAL INFORMATION</strong>
                            </div><br>
                            <div class="form-group">
                             
                                <label class="col-md-3 control-label">No of Children with Special Needs</label>
                                <div class="col-md-8">
                                     <input type="text" class="form-control" id="page2_GENERALINFORMATION_NoofChildrenwithSpecialNeeds"  name="page2_GENERALINFORMATION_NoofChildrenwithSpecialNeeds">
                                   </div>
                                </div>
                                
                                
                  <div class="form-group">
                    <label class="col-md-3 control-label">Type of School</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"  name="page2_GENERALINFORMATION_TypeofSchool" id="page2_GENERALINFORMATION_TypeofSchool_0" value="Boys">
                          <span>Boys</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox" name="page2_GENERALINFORMATION_TypeofSchool" value="Girls">
                          <span>Girls</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox" name="page2_GENERALINFORMATION_TypeofSchool" value="Co Education">
                          <span>Co Education</span> 
                        </label>
                      </div>
                    </div>
                  </div>
                   <div class="form-group">
                    <label class="col-md-3 control-label">Whether the school has electricity</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"  name="page2_GENERALINFORMATION_Whethertheschoolhaselectricity" id="page2_GENERALINFORMATION_Whethertheschoolhaselectricity_0" value="Yes">
                          <span>Yes</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page2_GENERALINFORMATION_Whethertheschoolhaselectricity" id="page2_GENERALINFORMATION_Whethertheschoolhaselectricity_1" value="No">
                          <span>No</span> 
                        </label>
                      
                      </div>
                    </div>
                  </div>         
                  <div class="form-group">
                    <label class="col-md-3 control-label">Status of school boundary or compound wall</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"  name="page2_GENERALINFORMATION_Statusofschoolboundaryorcompoundwall" id="page2_GENERALINFORMATION_Statusofschoolboundaryorcompoundwall_0" value="Complete">
                          <span>Complete</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page2_GENERALINFORMATION_Statusofschoolboundaryorcompoundwall" id="page2_GENERALINFORMATION_Statusofschoolboundaryorcompoundwall_1" value="Partial">
                          <span>Partial</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page2_GENERALINFORMATION_Statusofschoolboundaryorcompoundwall" id="page2_GENERALINFORMATION_Statusofschoolboundaryorcompoundwall_2" value="No boundary wall or fence">
                          <span>No boundary wall or fence</span> 
                        </label>
                      </div>
                    </div>
                  </div>
                            </div>
                        </fieldset>
                      
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>WATER</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">What is the source of drinking water in the premises</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page3_WATER_Whatisthesourceofdrinkingwaterinthepremises" name="page3_WATER_Whatisthesourceofdrinkingwaterinthepremises" ></textarea>
                                         </div>    
                                </div>
                            <div class="form-group">
                              <label class="col-md-3 control-label">What is the status of functionality of the source of the drinking water</label>
                              <div class="col-md-9">
                                <label class="checkbox-inline col-md-3">
                                    <input type="checkbox" class="checkbox style-0" name="ac_page3_WATER_Whatisthestatusoffunctionalityofthesourceofthedrinkingwater[]" id="ac_page3_WATER_Whatisthestatusoffunctionalityofthesourceofthedrinkingwater[]" value="Functional">
                                    <span>Functional</span>
                                </label>
                                <label class="checkbox-inline col-md-3">
                                    <input type="checkbox" class="checkbox style-0" name="ac_page3_WATER_Whatisthestatusoffunctionalityofthesourceofthedrinkingwater[]" id="ac_page3_WATER_Whatisthestatusoffunctionalityofthesourceofthedrinkingwater[]" value="Partially functional">
                                    <span>Partially functional</span>
                                </label>
                                <label class="checkbox-inline col-md-3">
                                    <input type="checkbox" class="checkbox style-0" name="ac_page3_WATER_Whatisthestatusoffunctionalityofthesourceofthedrinkingwater[]" id="ac_page3_WATER_Whatisthestatusoffunctionalityofthesourceofthedrinkingwater[]" value="Non functional">
                                    <span>Non functional</span>
                                </label>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="col-md-3 control-label">What methods of water treatment are used before drinking or cooking</label>

                              <div class="col col-md-9">
                                <label class="checkbox-inline col-md-3">
                                    <input type="checkbox" class="checkbox style-0" name="ac_page3_WATER_Whatmethodsofwatertreatmentareusedbeforedrinkingorcooking[]" id="ac_page3_WATER_Whatmethodsofwatertreatmentareusedbeforedrinkingorcooking[]" value="Sieve water">
                                    <span> Sieve water</span>
                                </label>
                                <label class="checkbox-inline col-md-3">
                                    <input type="checkbox" class="checkbox style-0" name="ac_page3_WATER_Whatmethodsofwatertreatmentareusedbeforedrinkingorcooking[]" id="ac_page3_WATER_Whatmethodsofwatertreatmentareusedbeforedrinkingorcooking[]" value="Boiling">
                                    <span>Boiling</span>
                                </label>
                                <label class="checkbox-inline col-md-3">
                                    <input type="checkbox" class="checkbox style-0" name="ac_page3_WATER_Whatmethodsofwatertreatmentareusedbeforedrinkingorcooking[]" id="ac_page3_WATER_Whatmethodsofwatertreatmentareusedbeforedrinkingorcooking[]" value="Using filter with ceramic or other candle">
                                    <span>Using filter with ceramic or other candle</span>
                                </label>
                              </div>
                              <div class="col col-md-offset-3 col-md-9">
                                <label class="checkbox-inline col-md-3">
                                    <input type="checkbox" class="checkbox style-0" name="ac_page3_WATER_Whatmethodsofwatertreatmentareusedbeforedrinkingorcooking[]" id="ac_page3_WATER_Whatmethodsofwatertreatmentareusedbeforedrinkingorcooking[]" value="Chlorination">
                                    <span>Chlorination</span>
                                </label>
                                <label class="checkbox-inline col-md-3">
                                    <input type="checkbox" class="checkbox style-0" name="ac_page3_WATER_Whatmethodsofwatertreatmentareusedbeforedrinkingorcooking[]" id="ac_page3_WATER_Whatmethodsofwatertreatmentareusedbeforedrinkingorcooking[]" value="No treatment">
                                    <span>No treatment</span>
                                </label>
                                <label class="checkbox-inline col-md-3">
                                    <input type="checkbox" class="checkbox style-0" name="ac_page3_WATER_Whatmethodsofwatertreatmentareusedbeforedrinkingorcooking[]" id="ac_page3_WATER_Whatmethodsofwatertreatmentareusedbeforedrinkingorcooking[]" value="Any other methods please specify">
                                    <span>Any other methods please specify</span>
                                </label>
                              </div>
                            </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">Does water source need repairs</label><br>
                                        <div class="col-md-8">
                                        <input type="text" class="form-control" id="page3_WATER_Doeswatersourceneedrepairs"  name="page3_WATER_Doeswatersourceneedrepairs">                                             
                                        </div>
                                </div>
                                <div class="form-group">
                              <label class="col-md-3 control-label">Whether the school has functioning overhead tank for drinking water storage</label>
                              <div class="radio">
                                <div class="col-md-9">
                                  <label class="col-md-3">
                                    <input type="radio" class="radiobox style-0"  name="page3_WATER_Whethertheschoolhasfunctioningoverheadtankfordrinkingwaterstorage" id="page3_WATER_Whethertheschoolhasfunctioningoverheadtankfordrinkingwaterstorage_0" value="Yes">
                                    <span>Yes</span> 
                                  </label>
                                <label class="col-md-3">
                                    <input type="radio" class="radiobox style-0" name="page3_WATER_Whethertheschoolhasfunctioningoverheadtankfordrinkingwaterstorage" id="page3_WATER_Whethertheschoolhasfunctioningoverheadtankfordrinkingwaterstorage_1" value="No">
                                    <span>No</span> 
                                  </label>
                      
                                </div>
                              </div>
                            </div>
                             <div class="form-group">
                              <label class="col-md-3 control-label">If so how is water lifted to the tank</label>
                              <div class="radio">
                                <div class="col-md-9">
                                  <label class="col-md-3">
                                    <input type="radio" class="radiobox style-0"  name="page3_WATER_Ifsohowiswaterliftedtothetank" id="page3_WATER_Ifsohowiswaterliftedtothetank_0" value="Electric Motor">
                                    <span>Electric Motor</span> 
                                  </label>
                                <label class="col-md-3">
                                    <input type="radio" class="radiobox style-0" name="page3_WATER_Ifsohowiswaterliftedtothetank" id="page3_WATER_Ifsohowiswaterliftedtothetank_1" value="Manual">
                                    <span>Manual</span> 
                                  </label>
                      
                                </div>
                              </div>
                            </div>
                        </fieldset>
                         <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>TOILETS</strong>
                            </div><br>
                            <div class="form-group">
                              <label class="col-md-3 control-label">Does the school have toilets in the premises</label>
                              <div class="radio">
                                <div class="col-md-9">
                                  <label class="col-md-3">
                                    <input type="radio" class="radiobox style-0" name="page4_TOILETS_Doestheschoolhavetoiletsinthepremises" id="page4_TOILETS_Doestheschoolhavetoiletsinthepremises_0" value="Yes">
                                    <span>Yes</span> 
                                  </label>
                                <label class="col-md-3">
                                    <input type="radio" class="radiobox style-0"name="page4_TOILETS_Doestheschoolhavetoiletsinthepremises" id="page4_TOILETS_Doestheschoolhavetoiletsinthepremises_1" value="No">
                                    <span>No</span> 
                                  </label>
                      
                                </div>
                              </div>
                            </div>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">How many toilets are there for each of the following</label>
                                        <div class="col-md-8">
                                             <div class="col-md-2">
                                              <label>Girls
                                              <input type="number" class="form-control" id="page4_TOILETS_Girls"  name="page4_TOILETS_Girls">
                                              </label>
                                            </div>
                                            <div class="col-md-2">
                                              <label>Boys
                                              <input type="number" class="form-control" id="page4_TOILETS_Boys"  name="page4_TOILETS_Boys">
                                              </label>
                                            </div>
                                            <div class="col-md-2">
                                              <label>Teachers
                                              <input type="number" class="form-control" id="page4_TOILETS_Teachers"  name="page4_TOILETS_Teachers">
                                              </label>
                                            </div>
                                            <div class="col-md-2">
                                              <label>Common
                                              <input type="number" class="form-control" id="page4_TOILETS_Common"  name="page4_TOILETS_Common">
                                              </label>
                                            </div>

                                         </div>    
                                </div>
                                 <div class="form-group">
                                       <label for="inputPassword" class="col-md-3 control-label">How many are functional</label><br>
                                        <div class="col-md-8">
                                        <input type="text" class="form-control" id="page4_TOILETS_Howmanyarefunctional"  name="page4_TOILETS_Howmanyarefunctional"> 
                                        </div>
                                </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Are the toilets clean odorless and well maintained</label>
                    <div class="col-md-9">
                      
                      <div class="checkbox">
                        <label class="col-md-3">
                          <input type="checkbox" class="checkbox style-0" name="ac_page4_TOILETS_Arethetoiletscleanodorlessandwellmaintained[]" id="ac_page4_TOILETS_Arethetoiletscleanodorlessandwellmaintained[]" value="Toilets are clean with minimal odor" >
                          <span>Toilets are clean with minimal odor</span>
                        </label>
                      
                        <label class="col-md-3">
                          <input type="checkbox" class="checkbox style-0" name="ac_page4_TOILETS_Arethetoiletscleanodorlessandwellmaintained[]" id="ac_page4_TOILETS_Arethetoiletscleanodorlessandwellmaintained[]" value="Toilets are dirty and unusable">
                          <span>Toilets are dirty and unusable</span>
                        </label>
                     
  
                     
                        <label>
                          <input type="checkbox" class="checkbox style-0" name="ac_page4_TOILETS_Arethetoiletscleanodorlessandwellmaintained[]" id="ac_page4_TOILETS_Arethetoiletscleanodorlessandwellmaintained[]" value="Toilets are not clean smelly but are being used">
                          <span>Toilets are not clean smelly but are being used</span>
                        </label>
                     </div>
  
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-3 control-label">Do the toilets need repairs</label>
                    <div class="col-md-9">
                      
                      <div class="checkbox">
                        <label class="col-md-3">
                          <input type="checkbox" class="checkbox style-0" name="ac_page4_TOILETS_Dothetoiletsneedrepairs[]" id="ac_page4_TOILETS_Dothetoiletsneedrepairs[]" value="Minor repairs like fixing doors and lathes and broken taps etc" >
                          <span>Minor repairs like fixing doors and lathes and broken taps etc</span>
                        </label>
                      
                        <label class="col-md-3">
                          <input type="checkbox" class="checkbox style-0" name="ac_page4_TOILETS_Dothetoiletsneedrepairs[]" id="ac_page4_TOILETS_Dothetoiletsneedrepairs[]" value="Major repairs like broken pan and adding ventilators and septic tank connection etc">
                          <span> Major repairs like broken pan and adding ventilators and septic tank connection etc</span>
                        </label>
                        <label>
                          <input type="checkbox" class="checkbox style-0" name="ac_page4_TOILETS_Dothetoiletsneedrepairs[]" id="ac_page4_TOILETS_Dothetoiletsneedrepairs[]" value="Incomplete toilet construction">
                          <span>Incomplete toilet construction</span>
                        </label>
                        <label>
                          <input type="checkbox" class="checkbox style-0" name="ac_page4_TOILETS_Dothetoiletsneedrepairs[]" id="ac_page4_TOILETS_Dothetoiletsneedrepairs[]" value="Defunct or completely damaged toilets">
                          <span>Defunct or completely damaged toilets</span>
                        </label>
                     </div>
  
                    </div>
                  </div>
                  <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">How is the water provided to toilets or urinals</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page5_TOILETS_Howisthewaterprovidedtotoiletsorurinals" name="page5_TOILETS_Howisthewaterprovidedtotoiletsorurinals">
                                                </textarea> 
                                             
                                        </div>
                                </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Is there a toilet specially for children with special needs</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"  name="page5_TOILETS_Isthereatoiletspeciallyforchildrenwithspecialneeds" id="page5_TOILETS_Isthereatoiletspeciallyforchildrenwithspecialneeds_0" value="Yes">
                          <span>Yes</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page5_TOILETS_Isthereatoiletspeciallyforchildrenwithspecialneeds" id="page5_TOILETS_Isthereatoiletspeciallyforchildrenwithspecialneeds_1" value="No">
                          <span>No</span> 
                        </label>
                      
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">If yes does it have ramp access with handrail</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"  name="page5_TOILETS_Ifyesdoesithaverampaccesswithhandrail" id="page5_TOILETS_Ifyesdoesithaverampaccesswithhandrail_0" value="Yes">
                          <span>Yes</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page5_TOILETS_Ifyesdoesithaverampaccesswithhandrail" id="page5_TOILETS_Ifyesdoesithaverampaccesswithhandrail_1" value="No">
                          <span>No</span> 
                        </label>
                      
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">A wide door for wheelchair entry</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"  name="page5_TOILETS_Awidedoorforwheelchairentry" id="page5_TOILETS_Awidedoorforwheelchairentry_0" value="Yes">
                          <span>Yes</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page5_TOILETS_Awidedoorforwheelchairentry" id="page5_TOILETS_Awidedoorforwheelchairentry_1" value="No">
                          <span>No</span> 
                        </label>
                      
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Handrails inside the toilet for support</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"  name="page5_TOILETS_Handrailsinsidethetoiletforsupport" id="page5_TOILETS_Handrailsinsidethetoiletforsupport_0" value="Yes">
                          <span>Yes</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page5_TOILETS_Handrailsinsidethetoiletforsupport" id="page5_TOILETS_Handrailsinsidethetoiletforsupport_1" value="No">
                          <span>No</span> 
                        </label>
                      
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Are there cleaning materials available near the toilet for cleaning toilets or urinals</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"  name="page5_TOILETS_Aretherecleaningmaterialsavailablenearthetoiletforcleaningtoiletsorurinals" id="page5_TOILETS_Aretherecleaningmaterialsavailablenearthetoiletforcleaningtoiletsorurinals_0" value="Yes">
                          <span>Yes</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page5_TOILETS_Aretherecleaningmaterialsavailablenearthetoiletforcleaningtoiletsorurinals" id="page5_TOILETS_Aretherecleaningmaterialsavailablenearthetoiletforcleaningtoiletsorurinals_1" value="No">
                          <span>No</span> 
                        </label>
                      
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Is there handwashing facility attached or close to the toilet</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"   name="page5_TOILETS_Istherehandwashingfacilityattachedorclosetothetoilet" id="page5_TOILETS_Istherehandwashingfacilityattachedorclosetothetoilet_0" value="Yes">
                          <span>Yes</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"  name="page5_TOILETS_Istherehandwashingfacilityattachedorclosetothetoilet" id="page5_TOILETS_Istherehandwashingfacilityattachedorclosetothetoilet_1" value="No">
                          <span>No</span> 
                        </label>
                      
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Is there water provided in the handwashing facility</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"  name="page6_TOILETS_Istherewaterprovidedinthehandwashingfacility" id="page6_TOILETS_Istherewaterprovidedinthehandwashingfacility_0" value="Yes">
                          <span>Yes</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page6_TOILETS_Istherewaterprovidedinthehandwashingfacility" id="page6_TOILETS_Istherewaterprovidedinthehandwashingfacility_1" value="No">
                          <span>No</span> 
                        </label>
                      
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Is there soap provided in the handwashing facility</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"  name="page6_TOILETS_Istheresoapprovidedinthehandwashingfacility" id="page6_TOILETS_Istheresoapprovidedinthehandwashingfacility_0" value="Yes">
                          <span>Yes</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page6_TOILETS_Istheresoapprovidedinthehandwashingfacility" id="page6_TOILETS_Istheresoapprovidedinthehandwashingfacility_1" value="No">
                          <span>No</span> 
                        </label>
                      
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                                <label class="col-md-3 control-label">Who cleans the toilets and urinals</label>
                                <div class="col-md-4 " >
                                   <input type="text" class="form-control" id="page6_TOILETS_Whocleansthetoiletsandurinals"  name="page6_TOILETS_Whocleansthetoiletsandurinals"></div>
                                </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">How often are the toilets or urinals cleaned</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"  name="page6_TOILETS_Howoftenarethetoiletsorurinalscleaned" id="page6_TOILETS_Howoftenarethetoiletsorurinalscleaned_0" value="Daily">
                          <span>Daily</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page6_TOILETS_Howoftenarethetoiletsorurinalscleaned" id="page6_TOILETS_Howoftenarethetoiletsorurinalscleaned_1" value="Alternate days">
                          <span>Alternate days</span> 
                        </label>
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page6_TOILETS_Howoftenarethetoiletsorurinalscleaned" id="page6_TOILETS_Howoftenarethetoiletsorurinalscleaned_2" value="Twice a week">
                          <span>Twice a week</span> 
                        </label>
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page6_TOILETS_Howoftenarethetoiletsorurinalscleaned" id="page6_TOILETS_Howoftenarethetoiletsorurinalscleaned_3" value="Weekly">
                          <span>Weekly</span> 
                        </label>
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page6_TOILETS_Howoftenarethetoiletsorurinalscleaned" id="page6_TOILETS_Howoftenarethetoiletsorurinalscleaned_4" value="Not cleaned">
                          <span>Not cleaned</span> 
                        </label>
                      
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Is there adeqate and private space for changing and disposal facilities for menstrual waste including dust bins</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"  name="page6_TOILETS_Isthereadeqateandprivatespaceforchanginganddisposalfacilitiesformenstrualwasteincludingdustbins" id="page6_TOILETS_Isthereadeqateandprivatespaceforchanginganddisposalfacilitiesformenstrualwasteincludingdustbins_0" value="Yes">
                          <span>Yes</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page6_TOILETS_Isthereadeqateandprivatespaceforchanginganddisposalfacilitiesformenstrualwasteincludingdustbins" id="page6_TOILETS_Isthereadeqateandprivatespaceforchanginganddisposalfacilitiesformenstrualwasteincludingdustbins_1" value="No">
                          <span>No</span> 
                        </label>
                      
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Is there any incinerator installed for the disposal of menstrual waste</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"  name="page6_TOILETS_Isthereanyincineratorinstalledforthedisposalofmenstrualwaste" id="page6_TOILETS_Isthereanyincineratorinstalledforthedisposalofmenstrualwaste_0" value="Yes">
                          <span>Yes</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page6_TOILETS_Isthereanyincineratorinstalledforthedisposalofmenstrualwaste" id="page6_TOILETS_Isthereanyincineratorinstalledforthedisposalofmenstrualwaste_1" value="No">
                          <span>No</span> 
                        </label>
                      
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label">Handrails inside the toilet for support</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox style-0"  name="page7_TOILETS_Handrailsinsidethetoiletforsupport" id="page7_TOILETS_Handrailsinsidethetoiletforsupport_0" value="Yes">
                          <span>Yes</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox style-0" name="page7_TOILETS_Handrailsinsidethetoiletforsupport" id="page7_TOILETS_Handrailsinsidethetoiletforsupport_1" value="No">
                          <span>No</span> 
                        </label>
                      
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3">Are there cleaning materials available near the toilet for cleaning toilets or urinals</label>
                    <div class="radio">
                      <div class="col-md-9">
                        <label class="col-md-3">
                          <input type="radio" class="radiobox "  name="page7_TOILETS_Aretherecleaningmaterialsavailablenearthetoiletforcleaningtoiletsorurinals" id="page7_TOILETS_Aretherecleaningmaterialsavailablenearthetoiletforcleaningtoiletsorurinals_0" value="Yes">
                          <span>Yes</span> 
                        </label>
                      <label class="col-md-3">
                          <input type="radio" class="radiobox " name="page7_TOILETS_Aretherecleaningmaterialsavailablenearthetoiletforcleaningtoiletsorurinals" id="page7_TOILETS_Aretherecleaningmaterialsavailablenearthetoiletforcleaningtoiletsorurinals_1" value="No">
                          <span>No</span> 
                        </label>
                      
                      </div>
                    </div>
                  </div>

                  </fieldset>

                <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading  text-center"><strong>Attachments</strong></div>
                            <div class="form-group ">
                              
                         
                          <input type="file" id="files"  name="rhso_req_attachments[]" style="display:none;" multiple>
                            <label for="files" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                               Browse.....
                           </label>
                          </div>
                   
                          </div>
                        
                </fieldset>
                              <div class="form-actions">
                                <div class="row">
                                  <div class="col-md-6">
                                    
                                    <button class="btn btn-success" type="submit">
                                      <i class="fa fa-save"></i>
                                      Submit
                                    </button>
                                  </div>
                                </div>
                              </div>
                
                            </form>
                
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

<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
  //include required scripts
  include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $("a[rel^='prettyPhoto']").prettyPhoto(); 


    $("#web_view").validate({
    ignore: "",
     errorClass: 'errors',
    // Rules for form validation
          rules : {
          page1_SchoolInfo_SchoolName:{required:true},
          page1_SchoolInfo_HSName:{required:true},
          page1_SchoolInfo_AsstCareTakerName:{required:true},
          page2_GENERALINFORMATION_NoofChildrenwithSpecialNeeds:{required:true},
          page2_GENERALINFORMATION_TypeofSchool:{required:true},
          page2_GENERALINFORMATION_Whethertheschoolhaselectricity:{required:true},
          page2_GENERALINFORMATION_Statusofschoolboundaryorcompoundwall:{required:true},
          page3_WATER_Whatisthesourceofdrinkingwaterinthepremises:{required:true},
          ac_page3_WATER_Whatisthestatusoffunctionalityofthesourceofthedrinkingwater:{required:true},
          ac_page3_WATER_Whatmethodsofwatertreatmentareusedbeforedrinkingorcooking:{required:true},
          page3_WATER_Doeswatersourceneedrepairs:{required:true},
          page3_WATER_Whethertheschoolhasfunctioningoverheadtankfordrinkingwaterstorage:{required:true},
          ac_page3_WATER_Whatisthestatusoffunctionalityofthesourceofthedrinkingwater:{required:true},                                
            page4_TOILETS_Doestheschoolhavetoiletsinthepremises:{required:true},
            /*page4_TOILETS_Girls:{required:true},
            page4_TOILETS_Boys:{required:true},
            page4_TOILETS_Teachers:{required:true},*/
            page4_TOILETS_Common:{required:true},
            page4_TOILETS_Howmanyarefunctional:{required:true},
            ac_page4_TOILETS_Arethetoiletscleanodorlessandwellmaintained:{required:true},
            page5_TOILETS_Howisthewaterprovidedtotoiletsorurinals:{required:true},
            page5_TOILETS_Isthereatoiletspeciallyforchildrenwithspecialneeds:{required:true},
            page5_TOILETS_Ifyesdoesithaverampaccesswithhandrail:{required:true},
            page5_TOILETS_Awidedoorforwheelchairentry:{required:true},
            page5_TOILETS_Handrailsinsidethetoiletforsupport:{required:true},
            page5_TOILETS_Aretherecleaningmaterialsavailablenearthetoiletforcleaningtoiletsorurinals:{required:true},
            page5_TOILETS_Istherehandwashingfacilityattachedorclosetothetoilet:{required:true},
            page6_TOILETS_Istherewaterprovidedinthehandwashingfacility:{required:true},
            page6_TOILETS_Whocleansthetoiletsandurinals:{required:true},
            page6_TOILETS_Howoftenarethetoiletsorurinalscleaned:{required:true},
            page6_TOILETS_Isthereadeqateandprivatespaceforchanginganddisposalfacilitiesformenstrualwasteincludingdustbins:{required:true},
            page6_TOILETS_Isthereanyincineratorinstalledforthedisposalofmenstrualwaste:{required:true},
            page7_TOILETS_Handrailsinsidethetoiletforsupport:{required:true},
            page7_TOILETS_Aretherecleaningmaterialsavailablenearthetoiletforcleaningtoiletsorurinals:{required:true},

          },
  
      //Messages for form validation
        messages : {
          page1_SchoolInfo_SchoolName:
          {required:"please select school name"},
          page2_GENERALINFORMATION_TypeofSchool:{required:"select the type of school."},
        

        },
        highlight: function(element) {
           
               // add a class "has_error" to the element 
               $(element).addClass('has_error');
           },
        unhighlight: function(element) {
           // remove the class "has_error" from the element 
               $(element).removeClass('has_error');
           },
        onkeyup: false,
        errorPlacement : function(error, element) {
                    error.insertAfter(element.parent().parent());
                    error.addClass("col-md-offset-2");
            } //turn off auto validate whilst typing
        });

if (window.File && window.FileList && window.FileReader) 
      {
        var page1_SchoolInfo_SchoolName = $('#page1_SchoolInfo_SchoolName').val();
    $('#page1_SchoolInfo_SchoolName').change(function (){
        page1_SchoolInfo_SchoolName = $('#page1_SchoolInfo_SchoolName').val();
        //debugger;
        $.ajax({
                  url: 'get_school_info_by_school_name',
                  type: 'POST',
                  data: {"school_name" : page1_SchoolInfo_SchoolName},
                  success: function (data) {      

                    result = $.parseJSON(data);
                    console.log(result);

                          $.each(result, function() {
                            $("#page1_SchoolInfo_PrincipalName").val(this['contact_person_name']);
                            $("#page1_SchoolInfo_ContactNumber").val(this['school_mob']);
                          
                         });
                    },
                      error:function(XMLHttpRequest, textStatus, errorThrown)
                    {
                     console.log('error', errorThrown);
                      }
         });
         return false;
    });
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
      
      
    });

    }
    
    })
  
</script>




<?php 
  //include footer
  include("inc/footer.php"); 
?>