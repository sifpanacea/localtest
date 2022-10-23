<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Field Officer";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav['field_officer_form']['sub']["field_officer"]["active"] = true;
include("inc/nav.php");

?>
<style>


.pip {
    display: inline-block;
    margin: 10px 10px 50px 90px;
  }

label {
    font-size: medium;
    font-family: serif;


}
/*note class styles */ 
.note {
   color: #008000 !important;
}
.required_field, .invalid{
  color:red;
}

.panel-warning>.panel-heading {
    color:white;
    background-color: #C79121;
    border-color: #C79121;
}
.imageThumb {
    max-height: 75px;
    border: 2px solid;
    padding: 1px;
    cursor: pointer;
  }

</style>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo(CSS.'smartadmin-production.min.css'); ?>"  rel="stylesheet" type="text/css" />
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
                <div class="panel panel-warning">
                    <div class="panel-heading"> <h3 class="panel-title text-center"><i class="fa fa-file-text-o"></i>&nbsp;Field Officer Form</h3></div>
                    <div class="panel panel-body" style="border-bottom-color: cornflowerblue;">
                     
                          <?php  $attributes = array('class' => 'form-horizontal','id'=>'web_view','name'=>'userform');
                            echo form_open_multipart('bc_welfare_cc/submit_field_officer',$attributes);
                            ?>
                        <div>
                                 
                            <!-- <div class="form-group row">

                                <label for="staticEmail" class="col-sm-2 col-form-label">District </label>
                                <div class="col-sm-5">
                                    <label class="select">
                <select id="page1_AttendenceDetails_District" name= "page1_AttendenceDetails_District" class="form-control" >
                    <option value="" selected="0" disabled="">Select a district</option>
                   

                    <?php //if(isset($districts_list)): ?>
                        <option value='All' >All</option>
                        <?php //foreach ($districts_list as $dist):  ?>
                        <option value='<?php //echo $dist['_id']?>' ><?php// echo strtoupper($dist['dt_name'])?></option>
                        <?php //endforeach;?>
                        <?php //else: ?>
                        <option value="1"  disabled="">No district entered yet</option>
                    <?php //endif ?>
                </select> <i></i>
            </label>
                                    
                                </div>

                            </div> -->
                            <!-- <div class="form-group row">

                                 <label  class="col-sm-2 col-form-label ">Select School</label>
                                <div class="col-sm-5">
                                    <label class="select">
                                        <select id="page1_AttendenceDetails_SelectSchool" name = "page1_AttendenceDetails_SelectSchool" class="form-control" disabled=true>
                                            <option value="0" selected="" disabled="">Select a district first</option>
                                        </select> <i></i>
                                    </label>
                                   
                                </div>

                            </div> -->
                                
                          <div id="school_code"></div>
                            <div class="form-group row">
                                <label  class="col-sm-3 col-md-3 col-form-label" >Student Unique ID</label>
                                <div class="col-sm-5 col-md-5">
                                    <input type="text"  class="form-control " id ="page1_StudentDetails_HospitalUniqueID" name="page1_StudentDetails_HospitalUniqueID">
                                </div>
                                <button type="button" class="btn btn-primary  col-md-3 retriever_search" id="searchIdBtn"  field_ref='page1_Personal Information_Hospital Unique ID'><i class="fa fa-search"> SEARCH</i></button>
                            </div>
                            <div class="form-group">
                                <label  class="col-md-3 col-form-label">Student Name:</label>
                                <div class="col-md-5">
                                    <input type="text"  class="form-control " id ="student_name" name="student_name" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-sm-3 col-form-label">Class</label>
                                <div class="col-sm-5">
                                    <input type="text"  class="form-control " id ="student_class" name="student_class" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-sm-3 col-form-label">Section</label>
                                <div class="col-sm-5">
                                    <input type="text"  class="form-control " id ="student_section" name="student_section" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-sm-3 col-form-label">Father Name</label>
                                <div class="col-sm-5">
                                    <input type="text"  class="form-control " id ="student_fathername" name="student_fathername" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-sm-3 col-form-label">Mobile Number</label>
                                <div class="col-sm-5">
                                    <input type="text"  class="form-control " id ="mobile_number" name="mobile_number" >
                                </div>
                            </div>
                            <fieldset>
                                <div class="row bg-color-pink txt-color-white">
                                    <div class="col col-md-4">
                                    <label class="radio radio-inline">  
                                        <input type="radio"  class="radiobox" id="outpatient" name="type_of_request" value="Out Patients" checked><span><strong>Out Patient</strong></span>
                                    </label>
                                </div>
                                <div class="col col-md-4">
                                    <label class="radio radio-inline">          
                                        <input type="radio"  class="radiobox" name="type_of_request" id="emergency_admitted" value="Emergency or Admitted"><span><strong>EMERGENCY/Admitted</strong></span>
                                    </label>
                                </div>
                                <div class="col col-md-4">
                                    <label class="radio radio-inline">          
                                        <input type="radio"  class="radiobox" name="type_of_request" id="case_type" value="Review Cases"><span><strong>Review Cases</strong></span>
                                    </label>
                                </div>
                            
                                </div>
                    <div class="general_related bg-color-light-pink">
                        <div class="widget-body">
                                   
                            <div id="out_patient" class="tab-content padding-10">
                                <div class="row tab-pane fade in active" id="op">
                                <div class="form-group row">
                                    <label  class="col-sm-3">Doctor Name</label>
                                        <div class="col-md-5">
                                            <input type="text"  class="form-control" id ="op_doctor_name" name="op_doctor_name" >
                                        </div> 
                                </div>
                                <div class="form-group">
                                    
                                        <label class="col-sm-3">Hospital  Name</label>
                                        <div class="col-md-5">
                                            <input type="text"  class="form-control" id ="op_hospital_name" name="op_hospital_name" >
                                        </div>
                                        
                                    </div>
                                
                        <div class="form-group">
                            
                        
                            <label class="textarea col-md-3">Case Details</label>
                            <textarea rows="4" name="op_patient_details" id="op_patient_details" name="op_patient_details" class="col-md-5" placeholder="case details"></textarea> 
                        
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Investigatin:</label>
                        <div class="col-md-5">
                            <input type="text"  class="form-control" id ="op_investigation" name="op_investigation" >
                            
                        </div>
                    </div>
                        <div class="form-group">
                        
                            <label class="col-md-3">Review Date :</label>
                            <div class="col-md-5">
                            <input type="text"  class="form-control datepicker"  id ="op_review_date" name="op_review_date" >
                            </div> 
                        </div>
                        
                        <div class="form-group">
                        
                            <label class="col-md-3">Medication :</label>
                            <div class="col-md-5">
                            <input type="text"  class="form-control " id ="op_meditation" name="op_meditation" >
                            </div> 
                        </div>
                        
                    
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="general_related">
                        <div class="widget-body">
                            <hr class="simple">
                             <div class="row tab-pane fade in active" id="admitted">
                                <div class="form-group row">
                                    
                                    <label  class="col-sm-3 col-form-label">Doctor Name</label>
                                        <div class="col-md-5">
                                            <input type="text"  class="form-control " id ="admitted_doctor_name" name="admitted_doctor_name" >
                                        </div> 
                                    </div>
                                    <div class="form-group">
                                        <label  class="col-sm-3 col-form-label">Hospital  Name</label>
                                        <div class="col-md-5">
                                            <input type="text"  class="form-control " id ="admitted_hospital_name" name="admitted_hospital_name" >
                                        </div>
                                    </div>
                                
                        
                        <div class="form-group">
                            <label class="col-md-3">Case Details</label>
                            <textarea rows="4" name="admitted_patient_details" id="admitted_patient_details" name ="admitted_patient_details" class="
                            col-md-5" placeholder="case details"></textarea> 
                        </div>
                       
                        
                        <div class="form-group">
                            <label class="col-md-3">Investigatin :</label>
                            <div class="col-md-5">
                            <input type="text"  class="form-control " id ="admitted_investigation" name="admitted_investigation" >
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-md-offset-3 col-md-2">Doctor Advice :</label>
                                       <div class="form-check form-check-inline col-sm-3">

                                            <label class='labelform'><input class="selectstudent" added="false" id="dr_advice_surgery" name="dr_advice_surgery" target_id="page1_AttendenceDetails_SickUID" type="checkbox" value=""/> Surgery</label>
                                            
                                        </div>
                                        <div class="form-check form-check-inline col col-sm-2">
                                            <label class="labelform"><input class="clearstudent" target_id="page1_AttendenceDetails_SickUID" id="dr_advice_tretment" name="dr_advice_tretment" type="checkbox" value=""> Treatment</label>
                                        </div>
                                    </div>
                        
                        
                        <div class="form-group">
                        
                            <label class="col-md-3">Medication :</label>
                            <div class="col-md-5">
                            <input type="text"  class="form-control " id ="admitted_medication" name="admitted_medication" >
                            </div> 
                        </div>
                        <div class="form-group">
                            <label class="col-md-3">Review Date :</label>
                            <div class="col-md-5">
                            <input type="text"  class="form-control datepicker" id ="admitted_review_date" name="admitted_review_date" data-dateformat="yy-mm-dd">
                            </div> 
                        </div>
                        
                    </div>
                </div>
            </div>
        
        <div class="general_related">
                        <div class="widget-body">
                                    <hr class="simple">
                            <div id="out_patient" class="tab-content padding-10">
                                <div class="row tab-pane fade in active" id="review_case">
                                
                                    <div class="form-group">
                                    <label  class="col-sm-3 col-form-label">Doctor Name
                                        </label>
                                        <div class="col-md-5">
                                            <input type="text"  class="form-control " id ="review_doctor_name" name="review_doctor_name" >
                                        </div> 
                                    </div>
                                    <div class="form-group">
                                        <label  class="col-sm-3 col-form-label">Hospital  Name</label>
                                        <div class="col-md-5">
                                            <input type="text"  class="form-control " id ="review_hospital_name" name="review_hospital_name" >
                                        </div>
                                    </div>
                                
                        <div class="form-group">
                        
                            <label class="col-md-3">Case Details</label>
                            <textarea rows="4" name="review_patient_details" id="review_patient_details" class="col-md-5" placeholder="case details"></textarea> 
                        
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3">Investigatin :</label>
                            <div class="col-md-5">
                            <input type="text"  class="form-control " id ="review_investigation" name="review_investigation" >
                            </div> 
                        </div>
                        
                        
                        
                        <div class="form-group">
                            <label class="col-md-3">Medication :</label>
                            <div class="col-md-5">
                            <input type="text"  class="form-control " id ="review_medication" name="review_medication" >
                            </div> 
                        </div>
                         <div class="form-group">
                            <label class="col-md-3 control-label">Case Closed:</label>
                            <div class="col-md-9">
                                <label class="radio radio-inline">
                                <input type="radio" name="review_caseclose" id="case_closed_yes" value="Yes"><span>Yes</span>
                                </label>
                                <label><input type="radio" name="review_caseclose" id="case_closed_no" value="No"><span>NO</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="review_date_div">
                            <label class="col-md-3">Review Date :</label>
                            
                            <div class="col-md-5 input-group">
                                <input type="text" id="review_review_date" name="review_review_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div> 
                        </div>
                       
                        
                    </div>
                </div>
            </div>
        </div>    
    </fieldset>
                    <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading  text-center"><strong>Attachments</strong></div>

                            
                          <ul class="nav nav-tabs md-tabs nav-justified primary-color" role="tablist">
                        <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#panel668" role="tab">
                        Prescriptions</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#panel555" role="tab">
                        Lab Reports</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#panel666" role="tab">
                        X-ray/MRI/Digital Images</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#panel667" role="tab">
                        <!-- <i class="fa fa-heart pr-2"> </i>-->Payments/Bills</a>
                      </li>
                       <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#panel669" role="tab">
                        <!-- <i class="fa fa-heart pr-2"> </i>-->Discharge Summary</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#panel770" role="tab">
                        <!-- <i class="fa fa-heart pr-2"> </i>-->Others</a>
                      </li>
                    </ul>
                    <!-- Nav tabs -->

                    <!-- Tab panels -->
                    <div class="tab-content">

                      <!-- Panel 1 -->
                      <div class="tab-pane fade" id="panel555" role="tabpanel">

                        <!-- Nav tabs -->
                        
            <input type="file" id="files_labs"  name="Lab_Reports[]" style="display:none;" multiple/>
                                             
                             <label for="files_labs" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                               Labs Reports.....
                           </label>

                                               

                      </div>
                      <!-- Panel 1 -->

                      <!-- Panel 2 -->
                      <div class="tab-pane fade" id="panel666" role="tabpanel" >

                      <input type="file" id="files_xray"  name="Digital_Images[]" style="display:none;" multiple>
                       
                                             
                            <label for="files_xray" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                               X-ray/MRI/ Digital Images.....

                            </label>

                      </div>
                      <!-- Panel 2 -->
                      <!-- Panel 2 -->
                      <div class="tab-pane fade" id="panel667" role="tabpanel">

                     
                        <input type="file" id="files_bills"  name="Payments_Bills[]" style="display:none;" multiple>
                                             
                            <label for="files_bills" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                               Payments Bills attachments.....
                            </label>

                      </div>
                      <div class="tab-pane fade" id="panel669" role="tabpanel">

                     
                        <input type="file" id="files_ds"  name="Discharge_Summary[]" style="display:none;" multiple>
                                             
                            <label for="files_ds" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                               Discharge Summary.....
                            </label>

                      </div>
                      <!-- Panel 2 -->
                      <!-- Panel 2 -->
                      <div class="tab-pane fade" id="panel668" role="tabpanel">

                     
                        <input type="file" id="files_prescriptions"  name="Prescriptions[]" style="display:none;" multiple>
                                             
                            <label for="files_prescriptions" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                               Prescriptions.....
                            </label>

                      </div>
                       <div class="tab-pane fade"  id="panel770" role="tabpanel">

                     
                       <input type="file" id="files"  name="hs_req_attachments[]" style="display:none;" multiple>
                                             
                            <label for="files" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                               Others.....
                            </label>

                      </div>
                      <!-- Panel 2 -->

                    </div>

                   
                          </div>
                        
                        </fieldset>                 
                                    
                            </div>
                        <button type="submit" class="btn btn-primary col-sm-offset-5" id="submit" >Submit</button>
                    <?php echo form_close();?>
                </div>
            </div>
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

 <script src="<?php echo(JS.'bootstrap-multiselect.js'); ?>" type="text/javascript"></script>
 <script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
<!-- PAGE RELATED PLUGIN(S) 
    <script src="..."></script>-->
    <!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->

   

   <script type="text/javascript">
    
           $(document).ready(function() { 
            $("#review_date_div").hide();
             $("input[name='review_caseclose']").click(function () {
                if ($("#case_closed_no").is(":checked")) {
                    $("#review_date_div").show();
                } else {
                    $("#review_date_div").hide();
                }
            });

            $('.datepicker').datepicker({
                minDate: new Date(1900, 10 - 1, 25)
             });

            //datapicker

    var today_date = $('#set_data').val();
            $("#out_patient").show();
            $("#review_case").hide();
            $("#admitted").hide();
        //$('.submit_attendance').modal('show');
        $("#outpatient").change(function(){
        var  casetype = $("input[name='type_of_request']:checked").val();



        if( casetype == "Out Patients"){
            $("#out_patient").show();
            $("#review_case").hide();
            $("#admitted").hide();

        }
    })
        $("#emergency_admitted").change(function(){
        var  casetype = $("input[name='type_of_request']:checked").val();
       
        if( casetype == "Emergency or Admitted"){
            $("#out_patient").hide();
            $("#review_case").hide();
            $("#admitted").show();

        }
    })
        $("#case_type").change(function(){
        var  casetype = $("input[name='type_of_request']:checked").val();
        
        if( casetype == "Review Cases"){
            $("#out_patient").hide();
            $("#review_case").show();
            $("#admitted").hide();

        }
    })
        
            
            
            
            
            
            // clear 
           
            
            // Focus out events //
           
            
            
            
            

            

            
           

             //loading  buttion in jquery
            $(document).on('click','#submit',function(e)
        {
        if (($.trim($('#page1_StudentDetails_HospitalUniqueID').val()).length > 0) && ($.trim($('#page1_AttendenceDetails_Sick').val()).length > 0)  &&($.trim($('#page1_AttendenceDetails_SickUID').val()).length > 0) && ($.trim($('#page1_AttendenceDetails_R2H').val()).length > 0) && ($.trim($('#page1_AttendenceDetails_R2HUID').val()).length > 0) && ($.trim($('#page1_AttendenceDetails_Absent').val()).length > 0) && ($.trim($('#page2_AttendenceDetails_AbsentUID').val()).length > 0) && ($.trim($('#page2_AttendenceDetails_RestRoom').val()).length > 0) )
                {
                    $("#web_view").submit(function () {
                    $("#submit").attr("disabled", true);
                    return true;
                });
                
                $.smallBox({
                        title : "<i class='fa fa-refresh fa-4x fa-spin'></i>&nbsp;&nbsp;",
                        content : "Form Submitting please wait..!!",
                        color : "#4c6e4c",
                        iconSmall : "fa fa-bell bounce animated",
                        timeout : 5000
                      });
                }
                else{
                    $('#submit').prop('disabled',false);
                }
            });
            
           });
        
       
    
    
        </script>
           
   
    
    
           

               <script type="text/javascript">
                
              
               $(function() {
                // Validation
                $("#web_view").validate({
                ignore: "",
                // Rules for form validation
                    rules : {
   page1_AttendenceDetails_District:{required:true},
   page1_AttendenceDetails_SelectSchool:{required:true},
   page1_StudentDetails_HospitalUniqueID:{required:true,minlength:1,maxlength:123},
   page1_AttendenceDetails_Absent:{required:true,minlength:1,maxlength:123},
   page2_AttendenceDetails_AbsentUID:{required:true,minlength:1,maxlength:5000,match_absent_count:true},
   //page2_AttendenceDetails_AbsentUID:{required:true,minlength:1,maxlength:5000},
  
   },
       
       //Messages for form validation
            messages : {page1_AttendenceDetails_District:{required:"District field is required"},
   page1_AttendenceDetails_SelectSchool:{required:"Select School field is required"},
   page1_StudentDetails_HospitalUniqueID:{required:"Attended field is required"},
   page1_AttendenceDetails_Sick:{required:"Sick field is required"},
   
   page1_AttendenceDetails_SickUID:{required:"Sick UID field is required",match_sick_count:"Sick UID should match the count mentioned in Sick field"},
   
   
   page1_AttendenceDetails_Absent:{required:"Absent field is required"},
   page2_AttendenceDetails_AbsentUID:{required:"Absent UID field is required",match_absent_count:"Absent UID should match the count mentioned in Absent field"},
   
   page2_AttendenceDetails_RestRoom:{required:"RestRoom field is required"},
   page2_AttendenceDetails_RestRoomUID:{required:"RestRoom UID field is required",match_rest_count:"RestRoom UID should match the count mentioned in RestRoom field"},
   },highlight: function(element) {
   
       // add a class "has_error" to the element 
       $(element).addClass('has_error');
   },
   unhighlight: function(element) {
   
       // remove the class "has_error" from the element 
       $(element).removeClass('has_error');
   },onkeyup: false, 
    });
    /////////////////////////


   $ ('#page1_AttendenceDetails_District').change(function(e){
        dist = $('#page1_AttendenceDetails_District').val();

        //alert(dist);
        var options = $("#page1_AttendenceDetails_SelectSchool");


        options.prop("disabled", true);
        
    if( dist != "All" ){
        options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching schools list..."));
        $.ajax({
            url: 'get_schools_list_by_district',
            type: 'POST',
            data: {"dist_id" : dist},
            success: function (data) {          

                result = $.parseJSON(data);
                console.log('ssssssssssssss',result)
                
              
                options.prop("disabled", false);
                options.empty();
                options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Select school"));
                options.append($("<option />").val("All").text("All"));
                $.each(result, function() {
                    $("#school_code").val(this.school_code);
                
                    options.append($("<option />").val(this.school_name).text(this.school_name));
                });
                        
                },
                error:function(XMLHttpRequest, textStatus, errorThrown)
                {
                 console.log('error', errorThrown);
                }
            });

        }


    });
   //var page1_AttendenceDetails_SelectSchool = $('#page1_AttendenceDetails_SelectSchool').val();
   $('#page1_AttendenceDetails_SelectSchool').change(function(){
    school_code = $('#school_code').val();
    console.log("school_code",school_code);
       $.ajax({
            url: 'get_uniqueid_by_school_code',
            type: 'POST',
            data: {"school_code" : school_code},
            success: function (data) {          

                result = $.parseJSON(data);
                console.log('final_school_code',result)

                $('#page1_StudentDetails_HospitalUniqueID').val(result);
                        
                },
                error:function(XMLHttpRequest, textStatus, errorThrown)
                {
                 console.log('error', errorThrown);
                }
            });
    
   });
   $('#searchIdBtn').click(function (){

    var uid = $("#page1_StudentDetails_HospitalUniqueID").val();
   /* console.log('uiddddddddddddddd',uid);
    debugger;*/
                var field_ref = $(this).attr("field_ref") || '';
                  if($(this).prev('input').prev('label').hasClass('unique_id'))
                  {
                    var query_ref_label = $(this).prev('input').prev('label').text() || '';
                    var query_ref_input = $(this).prev('input').val() || '';
                    var query_ref = ""+query_ref_label+""+query_ref_input+""
                        $('#page1_StudentDetails_HospitalUniqueID').val(query_ref);
                    console.log(query_ref,"unique_id");
                  }
                  else
                  {
                    var query_ref = $(this).prev('input').val() || '';
                        $('#page1_StudentDetails_HospitalUniqueID').val(query_ref);
                    console.log(query_ref,"unique_id");
                  }

                $.ajax({
                  url: 'fetch_student_info',
                  type: 'POST',
                  data: {'page1_StudentDetails_HospitalUniqueID':uid },
                  success:function(data){
                  
                 if(data == 'NO_DATA_AVAILABLE')
                    {

                        var uniqueIdField = $("input#page1_StudentDetails_HospitalUniqueID").val();
                        $('#web_view').trigger('reset');
                        $("input#page1_StudentDetails_HospitalUniqueID").val(uniqueIdField);

                        swal("Info !", "No student deatils available for this Unique ID: " + query_ref);

                    }
                    else{

                      data = $.parseJSON(data);
                      get_data = data.get_data;
                      console.log('get_data',get_data);
                      $.each(get_data, function() {
                        $("#page1_StudentDetails_HospitalUniqueID").val(this['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']);
                        $("#student_name").val(this['doc_data']['widget_data']['page1']['Personal Information']['Name']);
                        
                        $("#student_class").val(this['doc_data']['widget_data']['page2']['Personal Information']['Class']);
                        $("#student_section").val(this['doc_data']['widget_data']['page2']['Personal Information']['Section']);

                        $("#student_fathername").val(this['doc_data']['widget_data']['page2']['Personal Information']['Father Name']);
                        $("#mobile_number").val(this['doc_data']['widget_data']['page1']['Personal Information']['Mobile']['mob_num']);
                        
                        if(typeof(this['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path']) != "undefined" && this['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'] !== null)
                       {
                         var photo_student = this['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];
                    
                       $('#student_image').show();
                       $('#image_logo').hide();
                       
                          
                         $('#student_image').html('<img id="student_photo" src="<?php echo URLCustomer;?>'+photo_student+'">');
                                  
                                
                       }

                       else {
                             
                         
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

        

 });
   </script>
   <script>
        if (window.File && window.FileList && window.FileReader) 
        {
                
        //var numFiles = $("input:file")[0].files.length;
         $("#files_bills").on("change", function(e) {
            var files = e.target.files,
            filesLength = files.length;

            console.log("dsfdfsfsdfsfsdfds",files);
            console.log('filesLength',filesLength);
            for(var j=0;j<filesLength;j++)
            {
                var f = files[j];
                var fileReader = new FileReader();
                fileReader.onload = (function(e) {
                  var file = e.target;
                  $("<span   class=\"pip\">" +
                "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                "<br/><span class=\"remove\">Remove image</span>" +
                "</span>").prependTo("#panel667");
                  $(".remove").on('click',function(){
                    $(this).parent(".pip").remove();
                  });
                });
                 fileReader.readAsDataURL(f);

             //var size = $("input:file")[0].files[j].size;
             var size = f.size;
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
             
        });

        }
    </script>
    <script type="text/javascript">
        if (window.File && window.FileList && window.FileReader) 
        {
                
        //var numFiles = $("input:file")[0].files.length;
         $("#files_prescriptions").on("change", function(e) {
            
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
                "</span>").prependTo("#panel668");
                  $(".remove").on('click',function(){
                    $(this).parent(".pip").remove();
                  });
                });
                 fileReader.readAsDataURL(f);

             var size = f.size;
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
             
        });

        }
    </script>
    
    <script>
        if (window.File && window.FileList && window.FileReader) 
        {
                
       
         $("#files_ds").on("change", function(e) {
            var files = e.target.files,
            filesLength = files.length;

           
            for(var j=0;j<filesLength;j++)
            {
                var f = files[j];
                var fileReader = new FileReader();
                fileReader.onload = (function(e) {
                  var file = e.target;
                  $("<span   class=\"pip\">" +
                "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                "<br/><span class=\"remove\">Remove image</span>" +
                "</span>").prependTo("#panel669");
                  $(".remove").on('click',function(){
                    $(this).parent(".pip").remove();
                  });
                });
                 fileReader.readAsDataURL(f);

            
             var size = f.size;
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
             
        });

        }
    </script>
    <script>
        if (window.File && window.FileList && window.FileReader) 
        {
                
       
         $("#files").on("change", function(e) {
            var files = e.target.files,
            filesLength = files.length;

            
            for(var j=0;j<filesLength;j++)
            {
                var f = files[j];
                var fileReader = new FileReader();
                fileReader.onload = (function(e) {
                  var file = e.target;
                  $("<span   class=\"pip\">" +
                "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                "<br/><span class=\"remove\">Remove image</span>" +
                "</span>").prependTo("#panel770");
                  $(".remove").on('click',function(){
                    $(this).parent(".pip").remove();
                  });
                });
                 fileReader.readAsDataURL(f);

           
             var size = f.size;
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
             
        });

        }
    </script>
    <script >
        if (window.File && window.FileList && window.FileReader) 
        {
                
        //var numFiles = $("input:file")[0].files.length;
         $("#files_labs").on("change", function(e) {
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
                "</span>").prependTo("#panel555");
                  $(".remove").on('click',function(){
                    $(this).parent(".pip").remove();
                  });
                });
                 fileReader.readAsDataURL(f);

             var size = f.size;
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
        })
        }
    </script>
    <script type="text/javascript">
        if (window.File && window.FileList && window.FileReader) 
        {
    
            $("#files_xray").on("change", function(e) {
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
                "</span>").prependTo("#panel666");
                  $(".remove").on('click',function(){
                    $(this).parent(".pip").remove();
                  });
                });
                 fileReader.readAsDataURL(f);

             var size = f.size;
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
             
        });

        }
    </script>