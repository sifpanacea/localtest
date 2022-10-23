<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Food_hygiene_inspection";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Food_hygiene_inspection"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo(CSS.'toastr.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
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
//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
//$breadcrumbs["New Crumb"] => "http://url.com"

  include("inc/ribbon.php");
  ?>
<!-- MAIN PANEL -->
    <div id="content">
       
            <div class="row">
               <!-- NEW WIDGET START -->
                    <article class="col-sm-12 col-md-12 col-lg-offset-1 col-lg-10">
                
                      <!-- Widget ID (each widget will need unique ID)-->
                      <div class="jarviswidget jarviswidget-color-orange" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false">
                        <!-- widget options:
                        usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
                
                        data-widget-colorbutton="false"
                        data-widget-editbutton="false"
                        data-widget-togglebutton="false"
                        data-widget-deletebutton="false"
                        data-widget-fullscreenbutton="false"
                        data-widget-custombutton="false"
                        data-widget-collapsed="true"
                        data-widget-sortable="false"
                
                        -->
                        <header class="text-center">
                          <span class="widget-icon"> <i class="fa fa-eye"></i> </span>
                          <h2 >Food And Hygiene Inspection </h2>
                
                        </header>
                
                        <!-- widget div-->
                        <div>
                
                          <!-- widget edit box -->
                          <!-- <div class="jarviswidget-editbox">
                            This area used as dropdown edit box
                
                          </div> -->
                          <!-- end widget edit box -->
                
                          <!-- widget content -->
                          <div class="widget-body">
                
                         <?php  $attributes = array('class' => 'form-horizontal','id'=>'web_view','name'=>'userform');
                            echo  form_open_multipart('rhso_users/food_hygiene_inspection_new_submit',$attributes);
                            ?>
                            
                            <fieldset>
                      
                              <div class="row col-lg-12">
                            
                               <div class="form-group">
                                <label class="col-md-2 control-label">School Name</label>
                                <div class="col-md-4">

                                     <select class="form-control" id="page1_SchoolInfo_SchoolName" name="page1_SchoolInfo_SchoolName">
                                      <option>Select School </option>
                                      <?php foreach($schools_list as $school): ?>
                                          <option><?php echo $school['school_name']; ?></option>
                                      <?php endforeach; ?>
                                    </select>
                                  </div>
                                      <label class="col-md-2 control-label">Principal Name</label>
                                <div class="col-md-4">
                                   <input type="text" class="form-control" id="page1_SchoolInfo_PrincipalName" name="page1_SchoolInfo_PrincipalName"></div>

                             
                                </div>

                                <div class="form-group">
                            
                               
                                <label class="col-md-2 control-label">Health Supervisor Name</label>
                                <div class="col-md-4">
                                   <input type="text" class="form-control" id="page1_SchoolInfo_HealthSupName" name="page1_SchoolInfo_HealthSupName"></div>
                                   <label class="col-md-2 control-label">Time from to</label>
                                <div class="col-md-4">
                                   <input type="text" class="form-control" id="page1_SchoolInfo_Timefromto" type="text" name="page1_SchoolInfo_Timefromto" ></div>
                                </div>
                                <div class="form-group">
                                
                                <label class="col-md-2 control-label">Date</label>
                                <div class="col-md-4" >
                                   <input type="date" class="form-control" id="page1_SchoolInfo_Date" name="page1_SchoolInfo_Date" readonly="readonly" value="<?php echo $today_date; ?>"></div>
                               
                                 <label class="col-md-2 control-label">Category</label>
                                <div class="col-md-4">
                                   <input type="text" class="form-control" id="page1_SchoolInfo_Category" name="page1_SchoolInfo_Category"></div>
                                </div>
                  
                        
                              
                              </fieldset>
                              <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Food Preparation area or Kitchen</strong>
                            </div><br>
                            <div class="form-group">
                             
                                <label class="col-md-3 control-label">1 Observation of issues or Faults</label>
                                <div class="col-md-8">
                                     <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page2_FoodPreparationareaorKitchen_1ObservationofissuesorFaults" name="page2_FoodPreparationareaorKitchen_1ObservationofissuesorFaults" ></textarea>
                                   </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-3 control-label">2 Remarks</label>
                                <div class="col-md-8">
                                     <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page2_FoodPreparationareaorKitchen_2Remarks" name="page2_FoodPreparationareaorKitchen_2Remarks" ></textarea>
                                   </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Cooking Mode</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">3 Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page2_CookingMode_3ObservationofissuesorFaults" name="page2_CookingMode_3ObservationofissuesorFaults"></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">4 Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll"  name="page3_CookingMode_4Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Storage of Vegetables and Cutting area</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">5 Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page3_SrorageofVegetablesandCuttingarea_5ObservationofissuesorFaults" name="page3_SrorageofVegetablesandCuttingarea_5ObservationofissuesorFaults" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">6 Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page3_SrorageofVegetablesandCuttingarea_6Remarks" name="page3_SrorageofVegetablesandCuttingarea_6Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                         <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Personal Hygiene of Food Handlers</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">7 Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page4_PersonalHygieneofFoodHandlers_7ObservationofissuesorFaults" name="page4_PersonalHygieneofFoodHandlers_7ObservationofissuesorFaults" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">8 Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page4_PersonalHygieneofFoodHandlers_8Remarks" name="page4_PersonalHygieneofFoodHandlers_8Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Condition of Cooking Containers</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">9 Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page5_ConditionofCookingContainers_9ObservationofissuesorFaults" name="page5_ConditionofCookingContainers_9ObservationofissuesorFaults" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">10 Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page5_ConditionofCookingContainers_10Remarks" name="page5_ConditionofCookingContainers_10Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Store room</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">11 Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page5_Storeroom_11ObservationofissuesorFaults" name="page5_Storeroom_11ObservationofissuesorFaults" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">12 Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page5_Storeroom_12Remarks" name="page5_Storeroom_12Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Quality of raw material for preperation of food</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">13 Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page6_Qualityofrawmaterialforpreperationoffood_13ObservationofissuesorFaults" name="page6_Qualityofrawmaterialforpreperationoffood_13ObservationofissuesorFaults" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">14 Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page6_Qualityofrawmaterialforpreperationoffood_14Remarks" name="page6_Qualityofrawmaterialforpreperationoffood_14Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Samples collected</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">15 Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page6_Samplescollected_15ObservationofissuesorFaults" name="page6_Samplescollected_15ObservationofissuesorFaults" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">16 Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page7_Samplescollected_16Remarks" name="page7_Samplescollected_16Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Eggs</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">17 Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page7_Eggs_17ObservationofissuesorFaults" name="page7_Eggs_17ObservationofissuesorFaults" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">18 Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page7_Eggs_18Remarks" name="page7_Eggs_18Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Milk and Curd</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">19 Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page8_MilkandCurd_19ObservationofissuesorFaults" name="page8_MilkandCurd_19ObservationofissuesorFaults" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">20 Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page8_MilkandCurd_20Remarks" name="page8_MilkandCurd_20Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Banana or Fruit</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">21 Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page8_BananaorFruit_21ObservationofissuesorFaults" name="page8_BananaorFruit_21ObservationofissuesorFaults" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">22 Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page8_BananaorFruit_22Remarks" name="page8_BananaorFruit_22Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Cooked prepared food articles</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">23 Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page9_Cookedpreparedfoodarticles_23ObservationofissuesorFaults" name="page9_Cookedpreparedfoodarticles_23ObservationofissuesorFaults" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">24 Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page9_Cookedpreparedfoodarticles_24Remarks" name="page9_Cookedpreparedfoodarticles_24Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Drinking Water</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">25 Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page9_DrinkingWater_25ObservationofissuesorFaults" name="page9_DrinkingWater_25ObservationofissuesorFaults" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">26 Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page10_DrinkingWater_26Remarks" name="page10_DrinkingWater_26Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Dining Hall</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">27 Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page10_DiningHall_27ObservationofissuesorFaults" name="page10_DiningHall_27ObservationofissuesorFaults" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">28 Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page10_DiningHall_28Remarks" name="page10_DiningHall_28Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Hand washing facility in dining area</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">29 Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page11_Handwashingfacilityindiningarea_29ObservationofissuesorFaults" name="page11_Handwashingfacilityindiningarea_29ObservationofissuesorFaults" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">30 Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page11_Handwashingfacilityindiningarea_30Remarks" name="page11_Handwashingfacilityindiningarea_30Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Any other</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">31 Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page12_Anyother_31ObservationofissuesorFaults" name="page12_Anyother_31ObservationofissuesorFaults" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">32 Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page12_Anyother_32Remarks" name="page12_Anyother_32Remarks" ></textarea> 
                                             
                                        </div>
                                </div>
                                <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">Comments or Suggestions</label>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page13_Anyother_CommentsorSuggestions" name="page13_Anyother_CommentsorSuggestions" ></textarea> 
                                             
                                        </div>
                                </div>
                                <div class="form-group">
                    <label class="col-md-4 control-label">Overall rating for the food and hygiens at institution</label>
                    <div class="col-md-8">
                      <label class="radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="page13_Anyother_Overallratingforthefoodandhygiensatinstitution" id="page13_Anyother_Overallratingforthefoodandhygiensatinstitution_0" value="Very Good">
                        <span>Very Good</span> 
                        
                      </label>
                      <label class="radio radio-inline">
                        <input type="radio" class="radiobox" name="page13_Anyother_Overallratingforthefoodandhygiensatinstitution" id="page13_Anyother_Overallratingforthefoodandhygiensatinstitution_1" value="Good">
                        <span>Good</span>  
                      </label>
                      <label class="radio radio-inline">
                        <input type="radio" class="radiobox" name="page13_Anyother_Overallratingforthefoodandhygiensatinstitution" id="page13_Anyother_Overallratingforthefoodandhygiensatinstitution_2" value="Average">
                        <span>Average</span>  
                      </label>
                      <label class="radio radio-inline">
                        <input type="radio" class="radiobox" name="page13_Anyother_Overallratingforthefoodandhygiensatinstitution" id="page13_Anyother_Overallratingforthefoodandhygiensatinstitution_3" value="Poor">
                        <span>Poor</span> 
                      </label>
                      <label class="radio radio-inline">
                        <input type="radio" class="radiobox" name="page13_Anyother_Overallratingforthefoodandhygiensatinstitution" id="page13_Anyother_Overallratingforthefoodandhygiensatinstitution_4" value="Very Poor">
                        <span>Very Poor</span>  
                      </label>
                    </div>
                  </div>
                                <!-- <div class="form-group">
                                        <label for="inputPassword" class="col-md-7  control-label">Overall rating for the food and hygiens at institution</label>
                                        
                               
                                            
                                                <input type="radio"   class="UID form-control custom-scroll" id="page1_AttendenceDetails_SickUID" name="page1_AttendenceDetails_SickUID" > 
                                             
                                        </div> -->


                        </fieldset>
                        
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Inspected By</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label">Name</label>
                                        <div class="col-md-8">
                                          <input type="text" class="form-control" id="inspected_by" name="inspected_by" />        
                                         </div>    
                                </div>
                                 <!-- <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">Signature</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page1_AttendenceDetails_SickUID" name="page1_AttendenceDetails_SickUID">
                                                </textarea> 
                                             
                                        </div>
                                </div> -->

                        </fieldset>
                              <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading  text-center"><strong>Attachments</strong></div>
                            <div class="form-group ">
                              
                         
                          <input type="file" id="files"  name="food_hygiene_attachments[]" style="display:none;" multiple>
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

<script type="text/javascript">

  $(document).ready(function (){

    $("#web_view").validate({
    ignore: "",
     errorClass: 'errors',
    // Rules for form validation
          rules : {
         page1_SchoolInfo_SchoolName:{required:true},
    page1_SchoolInfo_Date:{required:true},
    page1_SchoolInfo_Category:{required:true},

    page2_FoodPreparationareaorKitchen_1ObservationofissuesorFaults:{required:true},
    page2_FoodPreparationareaorKitchen_2Remarks:{required:true},
    page2_CookingMode_3ObservationofissuesorFaults:{required:true},
    page3_CookingMode_4Remarks:{required:true},
    page3_SrorageofVegetablesandCuttingarea_5ObservationofissuesorFaults:{required:true},
    page3_SrorageofVegetablesandCuttingarea_6Remarks:{required:true},
    page4_PersonalHygieneofFoodHandlers_7ObservationofissuesorFaults:{required:true},
    page4_PersonalHygieneofFoodHandlers_8Remarks:{required:true},
    page5_ConditionofCookingContainers_9ObservationofissuesorFaults:{required:true},
    page5_ConditionofCookingContainers_10Remarks:{required:true},
    page5_Storeroom_11ObservationofissuesorFaults:{required:true},
    page5_Storeroom_12Remarks:{required:true},
    page6_Qualityofrawmaterialforpreperationoffood_13ObservationofissuesorFaults:{required:true},
    page6_Qualityofrawmaterialforpreperationoffood_14Remarks:{required:true},
    page6_Samplescollected_15ObservationofissuesorFaults:{required:true},
    page7_Samplescollected_16Remarks:{required:true},
    page7_Eggs_17ObservationofissuesorFaults:{required:true},
    page7_Eggs_18Remarks:{required:true},
    page8_MilkandCurd_19ObservationofissuesorFaults:{required:true},
    page8_MilkandCurd_20Remarks:{required:true},
    page8_BananaorFruit_21ObservationofissuesorFaults:{required:true},
    page8_BananaorFruit_22Remarks:{required:true},
    page9_Cookedpreparedfoodarticles_23ObservationofissuesorFaults:{required:true},
    page9_Cookedpreparedfoodarticles_24Remarks:{required:true},
    page9_DrinkingWater_25ObservationofissuesorFaults:{required:true},
    page10_DrinkingWater_26Remarks:{required:true},
    page10_DiningHall_27ObservationofissuesorFaults:{required:true},
    page10_DiningHall_28Remarks:{required:true},
    page11_Handwashingfacilityindiningarea_29ObservationofissuesorFaults:{required:true},
    page11_Handwashingfacilityindiningarea_30Remarks:{required:true},
    page12_Anyother_31ObservationofissuesorFaults:{required:true},
    page12_Anyother_32Remarks:{required:true},
    page13_Anyother_CommentsorSuggestions:{required:true},
    page13_Anyother_Overallratingforthefoodandhygiensatinstitution:{required:true},
    inspected_by:{required:true}

          },
  
      //Messages for form validation
        messages : {
          page1_SchoolInfo_SchoolName:
          {required:"please select school name"},
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

   var page1_SchoolInfo_SchoolName = $('#page1_SchoolInfo_SchoolName').val();
    $('#page1_SchoolInfo_SchoolName').change(function (){
        page1_SchoolInfo_SchoolName = $('#page1_SchoolInfo_SchoolName').val();

        $.ajax({
                  url: 'get_school_info_by_school_name',
                  type: 'POST',
                  data: {"school_name" : page1_SchoolInfo_SchoolName},
                  success: function (data) {      

                    result = $.parseJSON(data);
                    console.log(result)

                          $.each(result, function() {
                            $("#page1_SchoolInfo_PrincipalName").val(this['contact_person_name']);
                          
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