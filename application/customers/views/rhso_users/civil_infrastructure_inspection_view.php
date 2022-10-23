<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Civil And Infrastructure";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Civil Infrastructure"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<style type="text/css">
  label
  {
    font-weight: bold;
    font-size: inherit
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
                          <h2 >Civil Infrastructure</h2>
                
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
                            echo  form_open_multipart('rhso_users/civil_infrastructure_new_submit',$attributes);
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
                                    

                                       <label class="col-md-2 control-label">Category</label>
                                <div class="col-md-4">
                                   <label class="radio radio-inline">
                        
                                      <input type="radio" class="radiobox" name="page1_SchoolInfo_Category" id="page1_SchoolInfo_Category_0" value="Upgraded">
                                      <span>Upgraded</span> 
                                      
                                    </label>
                                    <label class="radio radio-inline">
                                      <input type="radio" class="radiobox" name="page1_SchoolInfo_Category" id="page1_SchoolInfo_Category_1" value="Non upgraded">
                                      <span>Non upgraded</span>  
                                    </label>
                                    <label class="radio radio-inline">
                                      <input type="radio" class="radiobox" name="page1_SchoolInfo_Category" id="page1_SchoolInfo_Category_2" value="Degree">
                                      <span>Degree</span> 
                                    </label></div>

                                           
                                </div>

                                <div class="form-group">
                            
                                 <label class="col-md-2 control-label">Principal Name</label>
                                <div class="col-md-4">
                                   <input type="text" class="form-control" id="page1_SchoolInfo_PrincipalName" name="page1_SchoolInfo_PrincipalName" readonly="readonly"></div>

                                      <label class="col-md-2 control-label">Principal Number</label>
                                <div class="col-md-4">
                                   <input type="text" class="form-control" id="page1_SchoolInfo_PrincipalNumber" name="page1_SchoolInfo_PrincipalNumber" readonly="readonly"></div>

                               
                                 
                                </div>

                                <div class="form-group">
                            
                               <label class="col-md-2 control-label">Health Supervisor Name</label>
                                <div class="col-md-4">
                                   <input type="text" class="form-control" id="page1_SchoolInfo_HealthSupName" name="page1_SchoolInfo_HealthSupName"></div>

                                <label class="col-md-2 control-label">H.S Number</label>
                                <div class="col-md-4">
                                   <input type="text" class="form-control" id="page1_SchoolInfo_HSNumber" name="page1_SchoolInfo_HSNumber"></div>
                                 
                                </div>
                                <div class="form-group">
                                
                                <label class="col-md-2 control-label">Date</label>
                                <div class="col-md-4" >
                                   <input type="date" class="form-control" id="page1_SchoolInfo_Date" name="page1_SchoolInfo_Date" readonly="readonly" value="<?php echo $today_date; ?>"></div>

                                
                               
                             
                                </div>
                  
                        
                              
                              </fieldset>
                              <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>School Building</strong>
                            </div><br>
                            <div class="form-group">
                             
                                <label class="col-md-3 control-label"> Observation of issues or Faults</label>
                                <div class="col-md-8">
                                     <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page2_SchoolBuilding_1Obsevationofissues" name="page2_SchoolBuilding_1Obsevationofissues" ></textarea>
                                   </div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-3 control-label"> Remarks</label>
                                <div class="col-md-8">
                                     <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page2_SchoolBuilding_2Remarks" name="page2_SchoolBuilding_2Remarks" ></textarea>
                                   </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Kitchen and Dining</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label"> Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page2_KitchenandDining_3Obsevationofissues" name="page2_KitchenandDining_3Obsevationofissues" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label"> Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page3_KitchenandDining_4Remarks" name="page3_KitchenandDining_4Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Water Supply</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label"> Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page3_WaterSupply_5Obsevationofissues" name="page3_WaterSupply_5Obsevationofissues" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label"> Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page3_WaterSupply_6Remarks" name="page3_WaterSupply_6Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                         <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>RO Plant</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label"> Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page4_ROPlant_7Obsevationofissues" name="page4_ROPlant_7Obsevationofissues" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label"> Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page4_ROPlant_8Remarks" name="page4_ROPlant_8Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Electrical Transformer</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label"> Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page4_ElectricalTransformer_9Obsevationofissues" name="page4_ElectricalTransformer_9Obsevationofissues" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label"> Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page5_ElectricalTransformer_10Remarks" name="page5_ElectricalTransformer_10Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Generator</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label"> Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page5_Generator_11Obsevationofissues" name="page5_Generator_11Obsevationofissues" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label"> Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page5_Generator_12Remarks" name="page5_Generator_12Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Compound wall</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label"> Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page6_Compoundwall_13Obsevationofissues" name="page6_Compoundwall_13Obsevationofissues" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label"> Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page6_Compoundwall_14Remarks" name="page6_Compoundwall_14Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Internal road</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label"> Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page6_Internalroad_15Obsevationofissues" name="page6_Internalroad_15Obsevationofissues" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label"> Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page7_Internalroad_16Remarks" name="page7_Internalroad_16Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Fire Extinguishers</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label"> Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page7_FireExtinguishers_17Obsevationofissues" name="page7_FireExtinguishers_17Obsevationofissues" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label"> Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page7_FireExtinguishers_18Remarks" name="page7_FireExtinguishers_18Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Electrification</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label"> Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page8_Electrification_19Obsevationofissues" name="page8_Electrification_19Obsevationofissues" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label"> Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page8_Electrification_20Remarks" name="page8_Electrification_20Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>General or Water sanitation</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label"> Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page8_GeneralorWatersanitation_21Obsevationofissues" name="page8_GeneralorWatersanitation_21Obsevationofissues" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label"> Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page9_GeneralorWatersanitation_22Remarks" name="page9_GeneralorWatersanitation_22Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                        </fieldset>
                        <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading text-center panelheading"><strong>Any Others</strong>
                            </div><br>
                              <div class="form-group">
                                    <label for="inputPassword" class="col-md-3 control-label"> Observation of issues or Faults</label>
                                        <div class="col-md-8">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page9_AnyOthers_23Obsevationofissues" name="page9_AnyOthers_23Obsevationofissues" ></textarea>
                                         </div>    
                                </div>
                                 <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label"> Remarks</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page9_AnyOthers_24Remarks" name="page9_AnyOthers_24Remarks" ></textarea> 
                                             
                                        </div>
                                </div>

                                      

                        </fieldset>
                        <fieldset>
                           <div class="form-group">
                                        <label for="inputPassword" class="col-md-3 control-label">Comments or Suggestions</label><br>
                                        <div class="col-md-8">
                                            
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page9_AnyOthers_CommentsorSuggestions" name="page9_AnyOthers_CommentsorSuggestions" ></textarea> 
                                             
                                        </div>
                                </div>
                                <div class="form-group">
                    <label class="col-md-4 control-label">Overall rating for the food and hygiens at institution</label>
                    <div class="col-md-8">
                      

                      <label class="radio radio-inline">
                        <input type="radio" class="radiobox" name="page9_AnyOthers_Overallratingforthefoodandhygineatinstitution" id="page9_AnyOthers_Overallratingforthefoodandhygineatinstitution_0" value="Very Good">
                        <span>Very Good</span>  
                      </label> 

                      <label class="radio radio-inline">
                        <input type="radio" class="radiobox" name="page9_AnyOthers_Overallratingforthefoodandhygineatinstitution" id="page9_AnyOthers_Overallratingforthefoodandhygineatinstitution_1" value="Good">
                        <span>Good</span>  
                      </label>
                      <label class="radio radio-inline">
                        <input type="radio" class="radiobox" name="page9_AnyOthers_Overallratingforthefoodandhygineatinstitution" id="page9_AnyOthers_Overallratingforthefoodandhygineatinstitution_2" value="Average">
                        <span>Average</span>  
                      </label>
                      <label class="radio radio-inline">
                        <input type="radio" class="radiobox" name="page9_AnyOthers_Overallratingforthefoodandhygineatinstitution" id="page9_AnyOthers_Overallratingforthefoodandhygineatinstitution_3" value="Poor">
                        <span>Poor</span> 
                      </label>
                      <label class="radio radio-inline">
                        <input type="radio" class="radiobox" name="page9_AnyOthers_Overallratingforthefoodandhygineatinstitution" id="page9_AnyOthers_Overallratingforthefoodandhygineatinstitution_4" value="Very Poor">
                        <span>Very Poor</span>  
                      </label>
                    </div>
                  </div>
             </fieldset>
             <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading  text-center"><strong>Attachments</strong></div>
                            <div class="form-group ">
                              
                         
                          <input type="file" id="files"  name="civil_infrastructure_attachments[]" style="display:none;" multiple>
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
<script type="text/javascript">
   $(document).ready(function (){
    $("#web_view").validate({
    ignore: "",
     errorClass: 'errors',
    // Rules for form validation
          rules : {
         page1_SchoolInfo_SchoolName:{required:true},
    page1_SchoolInfo_HealthSupName:{required:true},
    page1_SchoolInfo_HSNumber:{required:true},
    page1_SchoolInfo_Date:{required:true},
    page1_SchoolInfo_Category:{required:true},
    page2_SchoolBuilding_1Obsevationofissues:{required:true},
    page2_SchoolBuilding_2Remarks:{required:true},
    page2_KitchenandDining_3Obsevationofissues:{required:true},
    page3_KitchenandDining_4Remarks:{required:true},
    page3_WaterSupply_5Obsevationofissues:{required:true},
    page3_WaterSupply_6Remarks:{required:true},
    page4_ROPlant_7Obsevationofissues:{required:true},
    page4_ROPlant_8Remarks:{required:true},
    page4_ElectricalTransformer_9Obsevationofissues:{required:true},
    page5_ElectricalTransformer_10Remarks:{required:true},
    page5_Generator_11Obsevationofissues:{required:true},
    page5_Generator_12Remarks:{required:true},
    page6_Compoundwall_13Obsevationofissues:{required:true},
    page6_Compoundwall_14Remarks:{required:true},
    page6_Internalroad_15Obsevationofissues:{required:true},
    page7_Internalroad_16Remarks:{required:true},
    page7_FireExtinguishers_17Obsevationofissues:{required:true},
    page7_FireExtinguishers_18Remarks:{required:true},
    page8_Electrification_19Obsevationofissues:{required:true},
    page8_Electrification_20Remarks:{required:true},
    page8_GeneralorWatersanitation_21Obsevationofissues:{required:true},
    page9_GeneralorWatersanitation_22Remarks:{required:true},
    page9_AnyOthers_23Obsevationofissues:{required:true},
    page9_AnyOthers_24Remarks:{required:true},
    page9_AnyOthers_CommentsorSuggestions:{required:true},
    page9_AnyOthers_Overallratingforthefoodandhygineatinstitution:{required:true}

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
                            $("#page1_SchoolInfo_PrincipalNumber").val(this['school_mob']);
                          
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