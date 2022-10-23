<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Sanitation Report";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa sani_report_new"]["active"] = true;
include("inc/nav.php");

?>

<style type="text/css">
.jarviswidget-color-greenLight>header>.jarviswidget-ctrls a,
.jarviswidget-color-greenLight .nav-tabs li:not(.active) a {
  color: #1e1d1d!important
}

.jarviswidget-color-greenLight .nav-tabs li a:hover {
  color: #333!important
}

strong
{
  color:#AA6708;
  font-size: medium;
  font-family: serif;
}
.col-md-offset-1
{
  font-weight: bold;
}

  input[type="file"] {
    display: block;
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
  .remove {
    display: block;
    background: #444;
    border: 1px solid black;
    color: white;
    text-align: center;
    cursor: pointer;
  }
  .remove:hover {
    background: white;
    color: black;
  }
   form{
    border:  solid #c79121;
   } 
.fc{
  color: red;
  font-size: 30px !important;
}
.alert{
  color: white;
  background-color: #c79121;
}
.fattchment{
  font-size: 30px;
  color: red;
} 

.swal-text{
  font-weight: 600;
}
.well{
  background: #FCF8E5;
}
.errors{
  color: red;
  display: block;
}

</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
  <?php
//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
//$breadcrumbs["New Crumb"] => "http://url.com"

  include("inc/ribbon.php");
  ?>

  <!-- MAIN CONTENT -->
<div id="content">
      <div class="container">
        <div class="row">
          <!-- <form action="https://mednote.in/PaaS/healthcare/index.php/tmreis_schools/create_sanitation_report_new" method="POST" id='web_view' class="form-horizontal" enctype="multipart/form-data"> -->
            <?php
$attributes = array('class' => 'form-horizontal','id'=>'web_view','name'=>'userform');
echo  form_open_multipart('tmreis_schools/create_sanitation_report_new',$attributes);
?>

            <div class="alert alert-info" role="alert">
                <h4 class="text-center"><b>Sanitation Form</b> </h4>
              </div>
          <div class="well well-lg ">
              
              <!-- Nav tabs -->
<ul class="nav nav-tabs nav-justified" role="tablist">
    <li class="nav-item active">
        <a class="nav-link active" data-toggle="tab" href="#panel5" role="tab"><i class="fa fa-user fc"></i> <span>Daily</span></a>
    </li>
    <li class="nav-item" id="nav_weekly">
        <a class="nav-link" data-toggle="tab" href="#panel6" role="tab"><i class="fa fa-krw fc"></i> Weekly</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#panel7" role="tab"><i class="fa fa-calendar fc"></i> Monthly</a>
    </li>
</ul>
<!-- Tab panels -->
<div class="tab-content">
    <!--Panel 1-->
    <div class="tab-pane fade in active" id="panel5" role="tabpanel">
      <fieldset class="demo-switcher-1">
        <div class="panel panel-warning">
        <div class="panel-heading "><strong class ="col-md-offset-5">CAMPUS
        </strong>
        <label for="files_campus" class="col-md-offset-3 "><i class="fa fa-paperclip fattchment"></i>Attachment</label>
                      <br>
                      <input type="file" id="files_campus" name="hs_req_attachments_campus[]" style="display: none;" multiple="">
                      <div class="file_attach_count note pull-right"></div>
        </div>
      <div class="form-group">
                    <label class="col-md-offset-1 col-md-4">Cleanliness Of Campus</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="cleanliness_Of_the_campus" id="cleanliness_Of_the_campus_0" value="Yes">
                        <span>Yes</span> 
                        </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="cleanliness_Of_the_campus" id="cleanliness_Of_the_campus_1" value="No">
                        <span>No</span>  
                      </label>
                    </div>
      </div>
      <div class="form-group hide" id="campus">
                    &ensp;&ensp;&ensp;&nbsp;<label class="col-md-offset-6  radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="campus_cleanliness_times" id="campus_cleanliness" value="Once">
                        <span>Once</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="campus_cleanliness_times" id="" value="Twice">
                        <span>Twice</span>  
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="campus_cleanliness_times" id="" value="Thrice">
                        <span>Thrice</span> 
                        
                      </label>
                      
                  </div>            
      <div class="form-group">
          <label class="col-md-4 col-md-offset-1">Animals Around Campus</label>
            <div class="col-md-7">
              <label class="col-md-offset-2 radio radio-inline">
                <input type="radio" class="radiobox" name="animals_around_campus" id="animals_around_campus_0" value="Yes" />
                <span>Yes</span> 
              </label>
              <label class="col-md-offset-2 radio radio-inline">
                <input type="radio" class="radiobox" name="animals_around_campus" id="animals_around_campus_1" value="No">
                 <span>No</span>  
              </label>
              </div>
      </div>
      <div class="form-group hide" id="animals">
                   &ensp;&ensp;&ensp;&nbsp; <label class="radio radio-inline col-md-offset-6">
                        
                        <input type="radio" class="radiobox type_of_animal" name="type_of_animal" id="type_of_animal" value="Pigs">
                        <span>Pigs</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox type_of_animal" name="type_of_animal" id="" value="Monkeys">
                        <span>Monkeys</span>  
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox type_of_animal" name="type_of_animal" id="" value="Dogs">
                        <span>Dogs</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="type_of_animal" id="type_of_animal_other" value="Others">
                        <span>Others</span>  
                      </label>
                  </div>
                  <div class="form-group hide" id="other_animal">
                                <label for="inputPassword" class="col-sm-offset-1 col-sm-2 col-form-label"><b>Other Animal Name</b></label>
                                <div class="col-sm-5">
                                   <label class="col-sm-offset-5">
                                        <input type="text"  class="col-sm-offset-8" id="other_animal_name" name="other_animal_name" />
                                      </label>
                                </div>
                                      
                                
                            </div>
            </div>
            </fieldset>      
                <fieldset>
                   <div class="panel panel-warning">
                    <div class="panel-heading "><strong class = "col-md-offset-5">TOILETS
                                                </strong>
                      <label for="files_toilets" class="col-md-offset-3 "><i class="fa fa-paperclip fattchment"></i>Attachment</label>
                      <br>
                      <input type="file" id="files_toilets"  name="hs_req_attachments_toilets[]" style="display: none;" multiple>
                    </div>
                    <div class="form-group">
                    <label class="col-md-4 col-md-offset-1">Cleanliness Toilets/Bathrooms</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="cleanliness_toilets" id="cleanliness_toilets_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="cleanliness_toilets" id="cleanliness_toilets_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
                  <div class="hide form-group" id="toilets">
                    <label class="col-md-4 col-md-offset-1">Cleanliness Toilets/Bathrooms In A Day</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                       <input type="radio" class="radiobox" name="page3_Cleanliness_Toilets" id="page3_Cleanliness_Toilets_0" value="Once">
                        <span>Onces</span> 
                       </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="page3_Cleanliness_Toilets" id="page3_Cleanliness_Toilets_1" value="Twice">
                        <span>Twice</span>  
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="page3_Cleanliness_Toilets" id="page3_Cleanliness_Toilets_2" value="Thrice">
                        <span>Thrice</span>  
                      </label>
                    </div>
                  </div>
                 <div class="form-group" id="damages_toilets">
                    <label class="col-md-4 col-md-offset-1">Any Damages To The Toilets</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="any_damages_toilets" id="any_damages_toilets_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="any_damages_toilets" id="any_damages_toilets_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div> 
                 </div>
               </fieldset>
                <fieldset class="demo-switcher-1">
                  <div class="panel panel-warning">
              <div class="panel-heading ">
                <strong class = "col-md-offset-5">KITCHEN</strong>
                  <label for="files_kitchen" class="col-md-offset-3"><i class="fa fa-paperclip fattchment"></i>Attachment</label><br>
                  <input type="file" id="files_kitchen"  name="hs_req_attachments_kitchen[]"  style="display: none;"   multiple>
                </div>
                
                <div class="form-group">
                    <label class="col-md-4 col-md-offset-1">Cleanliness Of The Kitchen Place</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="cleanliness_Kitchen" id="cleanliness_Kitchen_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="cleanliness_Kitchen" id="cleanliness_Kitchen_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
                  <div class="hide form-group" id="kitchen">
                    <label class="col-md-4 col-md-offset-1">Cleanliness Of The Kitchen Place In A Day</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline ">
                       <input type="radio" class="radiobox" name="cleanliness_Kitchen_times" id="page2_Cleanliness_Kitchen_0" value="Once">
                        <span>Onces</span> 
                       </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="cleanliness_Kitchen_times" id="page2_Cleanliness_Kitchen_1" value="Twice">
                        <span>Twice</span>  
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="cleanliness_Kitchen_times" id="page2_Cleanliness_Kitchen_2" value="Thrice">
                        <span>Thrice</span>  
                      </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-4 col-md-offset-1">Daily Menu Followed</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="page3_Food_Foodpreparedaccordingtothedaysmenu" id="cleanliness_Of_the_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="page3_Food_Foodpreparedaccordingtothedaysmenu" id="cleanliness_Of_the_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-4 col-md-offset-1">Utensils Cleanliness</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="page3_Cleanliness_KitchenUtensils" id="cleanliness_Of_the_KitchenUtensils_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="page3_Cleanliness_KitchenUtensils" id="cleanliness_Of_the_KitchenUtensils_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-4 col-md-offset-1">Dining Hall Cleanliness</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="cleanliness_diningHalls" id="cleanliness_diningHalls_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="cleanliness_diningHalls" id="cleanliness_diningHalls_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
                  <div class="hide form-group" id="dining">
                    <label class="col-md-4 col-md-offset-1">Cleanliness Of The Dining Hall Place In A Day</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline  ">
                       <input type="radio" class="radiobox" name="page2_Cleanliness_DiningHalls" id="page2_Cleanliness_DiningHalls_0" value="Once">
                        <span>Onces</span> 
                       </label>
                      <label class="col-md-offset-2 radio radio-inline  ">
                        <input type="radio" class="radiobox" name="page2_Cleanliness_DiningHalls" id="page2_Cleanliness_DiningHalls_1" value="Twice">
                        <span>Twice</span>  
                      </label>
                      <label class="col-md-offset-2 radio radio-inline  ">
                        <input type="radio" class="radiobox" name="page2_Cleanliness_DiningHalls" id="page2_Cleanliness_DiningHalls_2" value="Thrice">
                        <span>Thrice</span>  
                      </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-4 col-md-offset-1">Hand Gloves Used By Serving People</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="hand_gloves_used_by_serving_people" id="hand_gloves_used_by_serving_people_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="hand_gloves_used_by_serving_people" id="hand_gloves_used_by_serving_people_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-4 col-md-offset-1">Staffmembers Tasty Food Before Serving Meals</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="staffmembers_tasty_food_before_serving_meals" id="staffmembers_tasty_food_before_serving_meals_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="staffmembers_tasty_food_before_serving_meals" id="staffmembers_tasty_food_before_serving_meals_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div> 
                  <div class="form-group">
                    <label class="col-md-4 col-md-offset-1">Wellness Centre Cleanliness</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="cleanliness_Of_the_wellness" id="cleanliness_Of_the_wellness_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="cleanliness_Of_the_wellness" id="cleanliness_Of_the_wellness_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
                  <div class="hide form-group" id="wellness">
                    <label class="col-md-4 col-md-offset-1">Cleanliness Of The Wellness Centre</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                       <input type="radio" class="radiobox" name="page3_Cleanliness_Wellness" id="cleanliness_wellness_0" value="Once">
                        <span>Onces</span> 
                       </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="page3_Cleanliness_Wellness" id="cleanliness_wellness_1" value="Twice">
                        <span>Twice</span>  
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="page3_Cleanliness_Wellness" id="cleanliness_wellness_2" value="Thrice">
                        <span>Thrice</span>  
                      </label>
                    </div>
                  </div>
                  </div>
                  </fieldset>  
                
                
    </div>
    <!--/.Panel 1-->
    <!--Panel 2 Weekly-->
    <div class="tab-pane fade " id="panel6" role="tabpanel">
       <fieldset class="demo-switcher-1">
        <div class="panel panel-warning">
        <div class="panel-heading ">
          <strong class = "col-md-offset-5">WATER SUPPLY CONDITION
        </strong>
        </div>
                  
            <div class="form-group">
                    <label class="col-md-3 col-md-offset-1">RO Plant</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox weekly" name="water_condition_ro_plant" id="cleanliness_Of_the_ro_plant_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="water_condition_ro_plant" id="cleanliness_Of_the_ro_plant_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
               <div class="form-group">
                    <label class="col-md-3 col-md-offset-1">Bore Water</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="water_condition_borewater" id="cleanliness_Of_the_borewater_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="water_condition_borewater" id="cleanliness_Of_the_borewater_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
                <div class="form-group">
                    <label class="col-md-3 col-md-offset-1">No Plant Working</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="water_condition_noplant_working" id="cleanliness_Of_the_noplant_working_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="water_condition_noplant_working" id="cleanliness_Of_the_noplant_working_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 col-md-offset-1">Water Tank Cleaning</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="water_tank_cleaning" id="cleanliness_Of_the_watertank_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="water_tank_cleaning" id="cleanliness_Of_the_watertank_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
                </div>
              </fieldset>
              <fieldset class="demo-switcher-1">
        <div class="panel panel-warning">
        <div class="panel-heading ">
          <strong class = "col-md-offset-5">DORMITORY</strong>
          <label for="files_dormitory" class="col-md-offset-3 "><i class="fa fa-paperclip fattchment"></i>Attachment</label>
                      <br>
                      <input type="file" id="files_dormitory" name="hs_req_attachments_dormitory[]" style="display: none;" multiple="">
        </div>
            <div class="form-group">
                    <label class="col-md-3 col-md-offset-1">Dormitory Cleaning</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="cleanliness_dormitories" id="cleanliness_Of_the_dormitories_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="cleanliness_dormitories" id="cleanliness_Of_the_dormitories_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
                  <div class="hide form-group" id="dormitory">
                    <label class="col-md-4 col-md-offset-1">Cleanliness Of The Dormitory Room</label>
                    <div class="col-md-7">
                      &ensp;&ensp;<label class="radio radio-inline">
                       <input type="radio" class="radiobox" name="page2_Cleanliness_Dormitories" id="page2_Cleanliness_Dormitories_0" value="Once">
                        <span>Onces</span> 
                       </label>
                      <label class="radio radio-inline">
                        <input type="radio" class="radiobox" name="page2_Cleanliness_Dormitories" id="page2_Cleanliness_Dormitories_1" value="Twice">
                        <span>Twice</span>  
                      </label>
                      <label class="radio radio-inline">
                        <input type="radio" class="radiobox" name="page2_Cleanliness_Dormitories" id="page2_Cleanliness_Dormitories_2" value="Thrice">
                        <span>Thrice</span>  
                      </label>
                    </div>
                  </div>
                <div class="form-group" id="damages_beds">
                    <label class="col-md-3 col-md-offset-1">Any Damages To Beds</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="any_damages_to_beds" id="any_damages_to_beds_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="any_damages_to_beds" id="any_damages_to_beds_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
                </div>
              </fieldset>
            <fieldset class="demo-switcher-1">
                <div class="panel panel-warning">
                            <div class="panel-heading ">
                              <strong class = "col-md-offset-5">STORE</strong>
                            </div>
                   <div class="form-group">
                    <label class="col-md-3 col-md-offset-1">Store Room Cleanliness</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="cleanliness_of_the_store"
                         id="cleanliness_Of_the_store_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="cleanliness_of_the_store" id="cleanliness_Of_the_store_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>  
                  </div> 
                   
                  <div class="hide form-group" id="store">
                    <label class="col-md-4 col-md-offset-1">Cleanliness Of The Store Room </label>
                    <div class="col-md-7">
                      &ensp;&ensp;<label class="radio radio-inline">
                       <input type="radio" class="radiobox" name="page3_Cleanliness_Store" id="page3_Cleanliness_Store_0" value="Once">
                        <span>Once</span> 
                       </label>
                      <label class="radio radio-inline">
                        <input type="radio" class="radiobox" name="page3_Cleanliness_Store" id="page3_Cleanliness_Store_1" value="Twice">
                        <span>Twice</span>  
                      </label>
                      <label class="radio radio-inline">
                        <input type="radio" class="radiobox" name="page3_Cleanliness_Store" id="page3_Cleanliness_Store_2" value="Thrice">
                        <span>Thrice</span>  
                      </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 col-md-offset-1">Proper Storage Of ITEMS</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="storage_Of_the_items" id="cleanliness_Of_the_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="storage_Of_the_items" id="cleanliness_Of_the_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 col-md-offset-1">Any Default Items Issued</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline">
                        
                        <input type="radio" class="radiobox" name="any_items_issued" id="any_items_issued_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline">
                        <input type="radio" class="radiobox" name="any_items_issued" id="any_items_issued_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
                </div>
              </fieldset>
                  <fieldset class="demo-switcher-1">
                            <div class="panel panel-warning">
                            <div class="panel-heading "><strong class = "col-md-offset-5">WASTE MANAGEMENT</strong></div>
                            <div class="form-group">
                              <label class="col-md-3 col-md-offset-1">Separate dumping of Inorganic waste</label>
                              <div class="col-md-7">
                                <label class="col-md-offset-2  radio radio-inline">
                                  <input type="radio" class="radiobox" name="page4_WasteManagement_SeparatedumpingofInorganicwaste" value="Yes">
                                  <span>Yes</span>


                                </label>
                                <label class="col-md-offset-2 radio radio-inline">
                                  <input type="radio" class="radiobox" name="page4_WasteManagement_SeparatedumpingofInorganicwaste" value="No">
                                  <span>No</span>  
                                </label>
                               
                              </div>
                            </div>

                              <div class="form-group">
                              <label class="col-md-3 col-md-offset-1">Separate dumping of Organic waste</label>
                              <div class="col-md-7">
                                <label class="col-md-offset-2 radio radio-inline">
                                  
                                  <input type="radio" class="radiobox" name="page4_WasteManagement_SeparatedumpingofOrganicwaste" value="Yes">
                                  <span>Yes</span> 
                                  
                                </label>
                                <label class=" col-md-offset-2 radio radio-inline">
                                  <input type="radio" class="radiobox" name="page4_WasteManagement_SeparatedumpingofOrganicwaste" value="No">
                                  <span>No</span>  
                                </label>
                               
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="col-md-3 col-md-offset-1">Dustbin</label>
                              <div class="col-md-7">
                                <label class="col-md-offset-2 radio radio-inline">
                                  
                                  <input type="radio" class="radiobox" name="dustbins" value="Yes">
                                  <span>Yes</span> 
                                  
                                </label>
                                <label class="col-md-offset-2 radio radio-inline">
                                  <input type="radio" class="radiobox" name="dustbins" value="No">
                                  <span>No</span>  
                                </label>
                               
                              </div>
                            </div>  
                   
                          </div>
                        
                        </fieldset>               
    </div>
    <!--/.Panel 2-->
    <!--Panel 3 monthly-->
    <div class="tab-pane " id="panel7" role="tabpanel">
        
        <div class="form-group">
                    <label class="col-md-4 col-md-offset-1">Water Loading Areas</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline col-md-offset-1">
                        
                        <input type="radio" class="radiobox" name="cleanliness_water_loading" id="cleanliness_water_loading_0" value="Yes">
                        <span>Yes</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline col-md-offset-1">
                        <input type="radio" class="radiobox" name="cleanliness_water_loading" id="cleanliness_water_loading_1" value="No">
                        <span>No</span>  
                      </label>
                      
                    </div>
                  </div>
            <div class="hide form-group" id="water_tank">
                    <label class="col-md-4 col-md-offset-1">Warter loading Areas Times</label>
                    <div class="col-md-7">
                      <label class="col-md-offset-2 radio radio-inline col-md-offset-1">
                        
                        <input type="radio" class="radiobox" name="cleanliness_waterLoading_times" id="cleanliness_waterLoading_times_0" value="Once">
                        <span>Onces</span> 
                        
                      </label>
                      <label class="col-md-offset-2 radio radio-inline col-md-offset-1">
                        <input type="radio" class="radiobox" name="cleanliness_waterLoading_times" id="cleanliness_waterLoading_times_1" value="Twice">
                        <span>Twice</span>  
                      </label>
                      <label class="col-md-offset-2 radio radio-inline col-md-offset-1">
                        <input type="radio" class="radiobox" name="cleanliness_waterLoading_times" id="cleanliness_waterLoading_times_2" value="Thrice">
                        <span>Thrice</span>  
                      </label>
                    </div>
                  </div>               
    </div>
    <!--/.Panel 3-->
</div>
<!-- <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading  " ><strong class = "col-md-offset-5">Declaration Information</strong></div>
                         <div class="form-group">
                            <label class="col-md-4 col-md-offset-1"><b>Declaration Information : </b></label>
                            <p></p>
                            <div class="col-md-7">
                              I here by declare i would render all the responsibilities as mentioned above
                            </div>
                          </div>

                         <div class="form-group">
                            <label class="col-md-4 col-md-offset-1">Place:</label>
                            <div class="col-md-6">
                              <input class="form-control" placeholder="Default Text Field" type="text" name="page4_DeclarationInformation_Place:">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-4 col-md-offset-1">Date:</label>
                            <div class="col-md-6">
                              <input type="text" class="form-control" placeholder="Default Text Field"  name="page4_DeclarationInformation_Date:" value="<?php //echo date('Y-m-d'); ?>" readOnly/>
                            </div>
                          </div>
                   
                          </div>
                        
                        </fieldset-->
                         

                          <!-- <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading  " ><strong class = "col-md-offset-6">Attachments</strong></div>
                            <div class="form-group ">
                              <br>
                          <label for="files" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                               Browse.....
                           </label>
                           <input type="file" id="files"  name="hs_req_attachments[]" style="display:none;" multiple>
                          </div>
                   
                          </div> 
                        
                        </fieldset> -->
                      
                        <center><button type="submit" id="submit" class="btn btn-primary btn-lg submit">Submit</button></center>
                        <br><br>
                  <br><br>
                        
        </div>
    <!--   </form>
                       --><?php echo form_close(); ?>

      </div><!-- ROW -->
    </div>
  </div>
    <!-- END MAIN CONTENT -->
</div>

  <!-- END MAIN PANEL -->

  <!-- ==========================CONTENT ENDS HERE ========================== -->

  <?php 
//include required scripts
  include("inc/scripts.php"); 
  ?>
  <script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>

<!-- PAGE RELATED PLUGIN(S) 
  <script src="..."></script>-->
  <!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script src="<?php echo JS; ?>sweetalert.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
     /* $("#nav_weekly").show(10000,function(){
        $("#nav_weekly").hide();
      } );*/

    $("a[rel^='prettyPhoto']").prettyPhoto();
     <?php if($this->session->userdata('message')) { ?>
                $.smallBox({
                        title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
                        content : "<?php echo $this->session->userdata('message') ?>",
                        color : "#296191",
                        iconSmall : "fa fa-bell bounce animated",
                        timeout : 8000
                      });
                <?php } ?>
      //daily
      $("#animals_around_campus_0").click(function(){
        $("#animals").removeClass("hide");
      });
      
      $("#animals_around_campus_1").click(function(){
        $("#animals").addClass("hide");
      });
      $("#type_of_animal_other").click(function(){
        $("#other_animal").removeClass("hide");
      });
      
      $(".type_of_animal").click(function(){
        $("#other_animal").addClass("hide");
      });
      $("#cleanliness_Of_the_campus_0").click(function(){
        $("#campus").removeClass("hide");
      })
      $("#cleanliness_Of_the_campus_1").click(function(){
        $("#campus").addClass("hide");
      })
      $("#cleanliness_toilets_0").click(function(){
        $("#toilets").removeClass("hide");
      })
      $("#cleanliness_toilets_1").click(function(){
        $("#toilets").addClass("hide");
      })
      $("#cleanliness_Kitchen_0").click(function(){
        $("#kitchen").removeClass("hide");
      })
      $("#cleanliness_Kitchen_1").click(function(){
        $("#kitchen").addClass("hide");
      })
      $("#cleanliness_diningHalls_0").click(function(){
        $("#dining").removeClass("hide");
      })
      $("#cleanliness_diningHalls_1").click(function(){
        $("#dining").addClass("hide");
      })
      $("#cleanliness_Of_the_store_0").click(function(){
        $("#store").removeClass("hide");
      })
      $("#cleanliness_Of_the_store_1").click(function(){
        $("#store").addClass("hide");
      })
      $("#cleanliness_Of_the_wellness_0").click(function(){
        $("#wellness").removeClass("hide");
      })
      $("#cleanliness_Of_the_wellness_1").click(function(){
        $("#wellness").addClass("hide");
      })
      $("#cleanliness_water_loading_0").click(function(){
        $("#water_tank").removeClass("hide");
      })
      $("#cleanliness_water_loading_1").click(function(){
        $("#water_tank").addClass("hide");
      })
      $("#cleanliness_Of_the_dormitories_0").click(function(){
        $("#dormitory").removeClass("hide");
      })
      $("#cleanliness_Of_the_dormitories_1").click(function(){
        $("#dormitory").addClass("hide");
      })
      
    $("#web_view").validate({
        ignore: "",
        errorClass: 'errors',
        // Rules for form validation
          rules : {
'cleanliness_Of_the_campus':{required:true},

'cleanliness_toilets':{required:true},

'cleanliness_Kitchen':{required:true},

},
     
     //Messages for form validation
      messages : {'cleanliness_Of_the_campus':{required:"Select the Yes or No for Cleaning Campus"},
'cleanliness_toilets':{required:"Select the Yes or No For Cleaning Toilets" },
'cleanliness_Kitchen':{required:"Select the Yes or No For Cleaning Kitchen" },

},onkeyup: false, //turn off auto validate whilst typing
            // Do not change code below
                  errorPlacement : function(error, element) {
                    error.insertAfter(element.parent().parent());
                    error.addClass("text-center");
            }
            /*errorPlacement: function(error, element) {
        if ( element.is(":radio") ) {                   
            error.insertAfter( element.parent().parent().parent().parent());
        }
        else { // This is the default behavior of the script for all fields
            error.insertAfter( element );
        }
 },*/
            });

           /*$.validator.addMethod('fileminsize', function(value, element, param) {
             return this.optional(element) || (element.files[0].size >= param) 
          });

          
           $.validator.addMethod('filemaxsize', function(value, element, param) {
             return this.optional(element) || (element.files[0].size <= param) 
          });*/
//file upload or image preview
if (window.File && window.FileList && window.FileReader) {

    $("#files").on("change", function(e) {
      var files = e.target.files,
        filesLength = files.length;
      for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
         $("<span class='pip'><a href="+e.target.result+"rel='prettyPhoto'<img src="+e.target.result+"></a><br><span class='remove'>Remove imagrrrre</span></span>").insertAfter("#files");

          $(".remove").click(function(){
            $(this).parent(".pip").remove();
          });
        });
        fileReader.readAsDataURL(f);
        $("input:file").html("#files");
      }
    });
  }
  
   else {
    alert("Your browser doesn't support to File API")
  }


    });




  </script>
  <script type="text/javascript">
    if(window.File && window.FileList && window.FileReader) {

    $("#files_toilets").on("change", function(e) {
      var files = e.target.files,
        filesLength = files.length;
      for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
          $("<span   class=\"pip\">" +
            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
            "<br/><span class=\"remove\">Remove image</span>" +
            "</span>").insertAfter("#damages_toilets");
          $(".remove").click(function(){
            $(this).parent(".pip").remove();
          });
        });
        fileReader.readAsDataURL(f);

      }
    });
  } </script>
  <script type="text/javascript">
  if(window.File && window.FileList && window.FileReader) {
    $("#files_kitchen").on("change", function(e) {
      var files = e.target.files,
        filesLength = files.length;
      for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
          $("<span   class=\"pip\">" +
            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
            "<br/><span class=\"remove\">Remove image</span>" +
            "</span>").insertAfter("#wellness");
          $(".remove").click(function(){
            $(this).parent(".pip").remove();
          });
        });
        fileReader.readAsDataURL(f);
      }
    });
  } 
  </script>
  <script type="text/javascript">
    if(window.File && window.FileList && window.FileReader) {

    $("#files_campus").on("change", function(e) {
      var files = e.target.files,
        filesLength = files.length;
      for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
         $("<span class=\"pip\">" +
            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/ rel=\"prettyPhoto[]\">" +
            "<br/><span class=\"remove\">Remove image</span>" +
            "</span>").insertAfter("#animals");
            $(".remove").click(function(){
            $(this).parent(".pip").remove();
          });
        });
        fileReader.readAsDataURL(f);
      }
    });
  }
  </script>
  <script type="text/javascript">
    if(window.File && window.FileList && window.FileReader) {

    $("#files_dormitory").on("change", function(e) {
      var files = e.target.files,
        filesLength = files.length;
      for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
          $("<span   class=\"pip\">" +
            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
            "<br/><span class=\"remove\">Remove image</span>" +
            "</span>").insertAfter("#damages_beds");
          $(".remove").click(function(){
            $(this).parent(".pip").remove();
          });
        });
        fileReader.readAsDataURL(f);
      }
    });
    // Checking whether attachments are attached //
    $(document).on('click','#submit',function(e)
    {
      var attach_campus = $("#files_campus").val().length;
     
      var attach_toilets = $("#files_toilets").val().length;
      /*document.getElementById("files_toilets");*/
      var attach_kitchen = $("#files_kitchen").val().length;
    
      if(attach_campus < 4  ) {
        e.preventDefault();
      $.SmartMessageBox({
        title : "Must select any sanitation pictures for upload !",
        buttons : '[Ok]'

      }, function(ButtonPressed) {
        if (ButtonPressed == "Ok") {
          //window.location.reload();
          $.smallBox({
              title : "Warning Message!",
              content : "<i class='fa fa-clock-o'></i> <i>Please select any sanitation pictures for upload.Each file should be less than 2 MB </i>",
              color : "#ebb828",
              //iconSmall : "fa fa-check fa-2x fadeInRight animated",
              timeout : 4000
            });
        }

      });
      } 
      
      var numFiles = $("input:file")[0].files.length;
      for(var j=0;j<numFiles;j++)
      {
         var size = $("input:file")[0].files[j].size;
       if(size > 4097152 )
       {
            $.bigBox({
          title   : "<i class='fa fa-warning'></i>&nbsp;&nbsp;  Error !",
          content : "Attach file less than 4 MB !",
          color   : "#C46A69",
          icon    : "fa fa-warning shake animated",
          timeout : 8000
          });
        
        e.preventDefault();
        $("input:file").val(""); 
        var no_of_files = $("input:file")[0].files.length;
          var count    = no_of_files+' files attached';
          $('.file_attach_count').text(count);
        break;
        
       }
      }
      
      //$(this).addClass("hide");
      
    });
  }
  </script>
 
