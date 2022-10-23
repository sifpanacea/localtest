<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Attendance Report";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa att_hs_req"]["active"] = true;
include("inc/nav.php");

?>
<style>

.Attendence {
    background-color:  lightcoral;
    color: white;
    font-size: 22px;
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
                    <div class="panel-heading"> <h3 class="panel-title text-center"><i class="fa fa-file-text-o"></i>&nbsp;Attendance Report Form</h3></div>
                    <div class="panel panel-body" style="border-bottom-color: cornflowerblue;">
                     
                          <?php  $attributes = array('class' => 'form-horizontal','id'=>'web_view','name'=>'userform');
                            echo form_open('tmreis_schools/create_attendence_report',$attributes);
                            ?>
                        <div class="col-md-offset-1">
                                 <span class="pull-right note">
									 Note: Fields marked with <span class="required_field">*</span> Required.<br>
                                     Note: If any category count is 0, Nil will be auto filled.</span>
                            <div class="form-group row">

                                <label for="staticEmail" class="col-sm-2 col-form-label">District <span class="required_field">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="page1_AttendenceDetails_District" name="page1_AttendenceDetails_District" value="<?php echo $districtName; ?>" readonly>
                                </div>

                            </div>
                            <div class="form-group row">

                                 <label for="inputPassword" class="col-sm-2 col-form-label">Select School<span class="required_field">*</span></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" id="page1_AttendenceDetails_SelectSchool" name="page1_AttendenceDetails_SelectSchool" value="<?php echo $schoolName; ?>" readonly>
                                </div>

                            </div>
                                
                          
                           
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Present<span class="required_field">*</span></label>
                                <div class="col-sm-5">
                                    <input type="number"  class="form-control " id ="page1_AttendenceDetails_Attended" name="page1_AttendenceDetails_Attended" placeholder="Total Present in school student count">
                                </div>
                            </div><br>

                                    <div class="form-group row">
                                        <label for="inputPassword" class="col-sm-2 col-form-label">Sick<span class="required_field">*</span></label>
                                        <div class="col-sm-5">
                                            <input type="number"  class="form-control" name="page1_AttendenceDetails_Sick" id="page1_AttendenceDetails_Sick" placeholder="Number of Sick  students in the school" value="">
                                        </div>
                                    </div>  
                                    <div class="form-group row">
                                        <label for="inputPassword" class="col-sm-2 col-form-label">Sick UID <span class="required_field">*</span></label>
                                        <div class="col-sm-5">
                                            <label class="textarea textarea-resizable">
                                                <textarea rows="3" cols="80" class="UID form-control custom-scroll" id="page1_AttendenceDetails_SickUID" name="page1_AttendenceDetails_SickUID" readOnly></textarea> 
                                              </label>
                                              </div>
                                              <div id="page1_AttendenceDetails_SickUID_note" class="note">
                                                   
                                                </div>
                                            
                                        
                                    </div>

                                    <div class="form-group row">
                                       <div class="form-check form-check-inline col col-sm-offset-2 col-sm-3">

                                            <label class='labelform'><input class="selectstudent" added="false" id="" target_id="page1_AttendenceDetails_SickUID" type="checkbox" value=""/> Select ID's</label>
                                            
                                        </div>
                                        <div class="form-check form-check-inline col col-sm-4">
                                            <label class="labelform"><input class="clearstudent" target_id="page1_AttendenceDetails_SickUID" type="checkbox" value=""> Clear ID's</label>
                                        </div>
                                    </div><br>
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">R2H <span class="required_field">*</span></label>
                                <div class="col-sm-5">
                                    <input type="number"  class="form-control " id="page1_AttendenceDetails_R2H" name="page1_AttendenceDetails_R2H" placeholder="Number of Sick  students in the school">
                                </div>
                            </div>
                       
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">R2H UID<span class="required_field">*</span></label>
                                <div class="col-sm-5">
                                   <label class="textarea textarea-resizable">
                                        <textarea rows="3" cols="80" class="form-control custom-scroll" id="page1_AttendenceDetails_R2HUID" name="page1_AttendenceDetails_R2HUID" readOnly></textarea> 
                                      </label>
                                </div>
                                      <div id="page1_AttendenceDetails_R2HUID_note" class="note">
                                           
                                        </div>
                                
                            </div>  
                            <div class="form-group row">
                                <div class="form-check form-check-inline col col-sm-offset-2 col-sm-3">
                                   
                                   <label class="labelform"><input class="selectstudent" added="true" id="" target_id="page1_AttendenceDetails_R2HUID" type="checkbox" value=""> Select ID's</label>
                                </div>
                                <div class="form-check form-check-inline col col-sm-4">
                                    <label class="labelform"><input class="clearstudent" target_id="page1_AttendenceDetails_R2HUID" type="checkbox" value=""> Clear ID's</label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Absent<span class="required_field">*</span> </label>
                                <div class="col-sm-5">
                                    <input type="number"  class="form-control" id="page1_AttendenceDetails_Absent" name="page1_AttendenceDetails_Absent" placeholder="Number of Sick  students in the school">
                                </div>
                            </div>  
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Absent UID<span class="required_field">*</span></label>
                                <div class="col-sm-5">
                                  <label class="textarea textarea-resizable">
                                        <textarea rows="3" cols="80" class="form-control custom-scroll" id="page2_AttendenceDetails_AbsentUID" name="page2_AttendenceDetails_AbsentUID" readOnly></textarea> 
                                      </label>
                                      </div>
                                      <div id="page2_AttendenceDetails_AbsentUID_note" class="note">
                                           
                                        </div>
                                
                            </div> 

                        
                            <div class="form-group row">
                                <div class="form-check form-check-inline col col-sm-offset-2 col-sm-3">
                                    <label class="labelform"><input class="selectstudent select_absented_Students" added="true" id="" target_id="page2_AttendenceDetails_AbsentUID" type="checkbox" value=""> Select ID's</label>
                                </div>
                                <div class="form-check form-check-inline col col-sm-4">
                                    <label class="labelform"><input class="clearstudent clear_absented_Students" target_id="page2_AttendenceDetails_AbsentUID" type="checkbox" value=""> Clear ID's</label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">RestRoom<span class="required_field">*</span>  </label>
                                <div class="col-sm-5">
                                    <input type="number" id="page2_AttendenceDetails_RestRoom" name="page2_AttendenceDetails_RestRoom" class="form-control " id="inputPassword" placeholder="Number of Sick  students in the school">
                                </div>
                            </div>  
                            <div class="form-group row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">RestRoom UID<span class="required_field">*</span></label>
                                <div class="col-sm-5">
                                    <label class="textarea textarea-resizable">
                                        <textarea rows="3" cols="80" id="page2_AttendenceDetails_RestRoomUID" name="page2_AttendenceDetails_RestRoomUID" class="form-control custom-scroll" readOnly></textarea> 
                                      </label>
                                 </div>
                                      <div id="page2_AttendenceDetails_RestRoomUID_note" class="note">
                                           
                                        </div>
                                
                            </div>  
                            <div class="form-group row">
                                <div class="form-check form-check-inline col col-sm-offset-2 col-sm-3">
                                    <label class="labelform"><input class="selectstudent" added="true" id="" target_id="page2_AttendenceDetails_RestRoomUID" type="checkbox" value=""> Select ID's</label>
                                    <!-- <input class="form-check-input" type="checkbox" target_id="page2_AttendenceDetails_RestRoomUID" value="option1">
                                    <label class="form-check-label" for="inlineCheckbox1">select ID's</label> -->
                                </div>
                                <div class="form-check form-check-inline  col-sm-2">
                                    <!-- <input class="form-check-input" type="checkbox" target_id="page2_AttendenceDetails_RestRoomUID" value="option2">
                                    <label class="form-check-label" for="inlineCheckbox2">Clear ID's</label> -->
                                    <label class="labelform"><input class="clearstudent" target_id="page2_AttendenceDetails_RestRoomUID" type="checkbox" value=""> Clear ID's</label>
                                </div>
                            </div>

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
<!-- END MAIN PANEL --> <!-- Modal -->
    <div class="modal fade submit_attendance" id="load_waiting" tabindex="-1" role="dialog" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Submit in progress !</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" style="display: block; margin: 0 auto; width: 100px;">
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 

    //include required scripts
include("inc/scripts.php"); 
?>

 <script src="<?php echo(JS.'bootstrap-multiselect.js'); ?>" type="text/javascript"></script>
<!-- PAGE RELATED PLUGIN(S) 
    <script src="..."></script>-->
    <!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->

    <?php 
    //include footer
    include("inc/footer.php"); 
    ?>

   <script type="text/javascript">
    // JavaScript Document
           $(document).ready(function() { 
            //please wait  loading functionality
             $(document).on('click','#submit',function(e)
        {
        if (($.trim($('#page1_AttendenceDetails_Attended').val()).length > 0) && ($.trim($('#page1_AttendenceDetails_Sick').val()).length > 0)  &&($.trim($('#page1_AttendenceDetails_SickUID').val()).length > 0) && ($.trim($('#page1_AttendenceDetails_R2H').val()).length > 0) && ($.trim($('#page1_AttendenceDetails_R2HUID').val()).length > 0) && ($.trim($('#page1_AttendenceDetails_Absent').val()).length > 0) && ($.trim($('#page2_AttendenceDetails_AbsentUID').val()).length > 0) && ($.trim($('#page2_AttendenceDetails_RestRoom').val()).length > 0) )
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
        //$('.submit_attendance').modal('show');
        $('#page2_AttendenceDetails_AbsentUID').val("");
        $('<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" id="studentmodal" role="dialog" style="min-height:400px;"><div class="modal-dialog modal-lg" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> <h4 class="modal-title">Select ID</h4></div><div class="modal-body"><div class="content-top"></div><div class="content-bottom"><textarea class="st-lists view_only" style="width:100%;margin-top:20px;min-height:60px;" readOnly="readOnly"></textarea><button type="button" class="btn btn-default clear">Clear</button></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary save_ids">Add to application </button></div></div></div></div>').appendTo('body')
            $('#reasons').hide();
            $('#cancel').hide();
            
            $(document).on("click",".clear",function()
            {
                $(".st-lists").val("");
            })
            
            $(document).on("change","#page1_AttendenceDetails_SelectSchool",function()
            {
                $(".st_id").remove();
                $(".st-lists").val("");
                $(".btn-group").remove();
                $("#add_stu_id").remove();
                $("#classes").remove();
                $(".selectstudent").attr("added","false");
            })
            $(document).on("change","#classes",function()
            {
                $(".st_id").remove();
                $(".btn-group").remove();
                $("#add_stu_id").remove();
                var current_class = $(this).val();

                   console.log("JSON",json);
                
                
                var optionsss = ""
                //optionsss += "<option value='Nil'>Nil</option>";
                
                for(i=0;i<json[current_class].length;i++)
                {
                    optionsss += "<option value='"+json[current_class][i]+"'>"+json[current_class][i]+"</option>"
                }
                $('<select class="st_id" id="" style="margin-left:20px" multiple="multiple">'+optionsss+'</select><button class="btn btn-default" id="add_stu_id" style="margin-left:5px;">Add ID\'s</button>').appendTo(".content-top")
                
                $('.st_id').multiselect({
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    includeSelectAllOption: true,
                    maxHeight: 150/*,
                       buttonWidth: '300px'*/
                });
                //$('.st_id').multiselect('refresh');
            })
            $('#studentmodal').on('hidden.bs.modal', function (e) {
                $(".selectstudent").prop("checked",false);
                $("#classes").val('0');
            })
            $(document).on("click",".multiselect",function(){
                $(this).parent().addClass("open");
            })
            $(document).on("click",".save_ids",function(){
                var selec_textarea = $('.st-lists').val()
                var target_id = $("#studentmodal").attr("target_id");
                var app_textarea = $('#'+target_id+'').val() || ""
                app_textarea = app_textarea.trim()
                
                if(app_textarea != ""){
                    
                    app_textarea += ","
                    app_textarea += selec_textarea
                    var count = app_textarea.split(",").length;
                    $('#'+target_id+'').val(app_textarea);  
                    $('#'+target_id+'_note').empty();
                    $('#'+target_id+'_note').html('<label>Note:- Selected IDs : '+count+'');
                    
                }
                else
                {
                    var count = selec_textarea.split(",").length;
                    $('#'+target_id+'').val(selec_textarea);
                    $('#'+target_id+'_note').empty();
                    $('#'+target_id+'_note').html('<label>Note:- Selected IDs : '+count+'');
                }
                $("#studentmodal").modal("hide")
            })
            
            $(document).on("click","#add_stu_id",function(){
                /*var selec_values = $('.st_id').val();
                
                var selec_textarea = $('.st-lists').val() || ""
                if(selec_textarea != ""){
                    selec_textarea += ","
                    selec_textarea += selec_values
                    $('.st-lists').val(selec_textarea); 
                }
                else
                {
                    $('.st-lists').val(selec_values);   
                }*/

                   var selec_values = $('.st_id').val();
                   var array1 = [];
                   var array2 = [];
                   for(var i=0; i<selec_values.length; i++)
                   {
                       var str = selec_values[i].substring(0, selec_values[i].search("---"));
                       array1[i] = str;
                       array2.push(array1[i]);
                   }
                   var selec_textarea = $('.st-lists').val() || ""
                   if(selec_textarea != ""){
                       selec_textarea += ","
                       selec_textarea += array1
                       $('.st-lists').val(selec_textarea);
                   }
                   else
                   {

                       $('.st-lists').val(array1);
                   }
                
            })
            
            $(document).on("click",".selectstudent",function()
            {
                var added     = $(".selectstudent").attr("added");
                var target_id = $(this).attr("target_id");
                if($(".selectstudent").is(':checked') && added == "false")
                {
                    var district    = $("#page1_AttendenceDetails_District").val() || ""
                    var school      = $("#page1_AttendenceDetails_SelectSchool").val() || ""
                    var students_length = 0;
                    if(district != "" && school!= "")
                    {
                    
                    school = btoa(school)
                    $.ajax({
                    url: '../healthcare/healthcare2017120192713965_con/get_students_list/'+school,
                    type: 'POST',
                    success: function (data) {
                        //console.log(data)
                            json = $.parseJSON(data)
                            students_length = json.length;
                            if(students_length != 0)
                            {
                                var optionsss = "<option value='0'>Select Class</option>"
                                $.each( json, function( key, value ) {
                                  optionsss += "<option value='"+key+"'>"+key+"</option>"
                                });
                                $('<select class="" id="classes" style="height:32px;width:130px;margin-right:10px;">'+optionsss+'</select>').appendTo(".content-top")
                                
                                $("#studentmodal").modal("show")
                                $("#studentmodal").attr("target_id",target_id)
                                $(".selectstudent").attr("added","true");
                            }
                            else
                            {
                                alert("No Data Available")
                                $(".selectstudent").prop("checked",false)
                            }
                        },
                        error:function(XMLHttpRequest, textStatus, errorThrown)
                        {
                         console.log('error', errorThrown);
                        }
                    });
                    }
                    else
                    {
                        alert("please select DISTRICT and SCHOOL")
                        $(".selectstudent").prop("checked",false)
                    }
                }
                else if(added == "true")
                {
                    $(".st_id").remove();
                    $(".btn-group").remove();
                    $("#add_stu_id").remove();
                    $(".st-lists").val("");
                    $("#studentmodal").attr("target_id",target_id)
                    $("#studentmodal").modal("show")
                }
            })
            
            // clear 
            $(document).on("click",".clearstudent",function()
            {
                var target_id = $(this).attr("target_id");
                $('#'+target_id+'').val("");
                $('#'+target_id+'_note').empty();
                $('#'+target_id+'_note').html('<label>Note:- Selected IDs : 0');
                $(".clearstudent").prop("checked",false)
            })
            
            // Focus out events //
            $("#page1_AttendenceDetails_Sick").focusout(function()
            {
                var sick = $('#page1_AttendenceDetails_Sick').val();
                var sick_uid = $('#page1_AttendenceDetails_SickUID').val();
                
                if(sick == 0)
                {
                  $("#page1_AttendenceDetails_SickUID").val("Nil");
                }
                
                if(sick != 0 && sick_uid == "Nil")
                {
                    $("#page1_AttendenceDetails_SickUID").val("");
                }
            });
            
            $("#page1_AttendenceDetails_R2H").focusout(function()
            {
                var r2h = $('#page1_AttendenceDetails_R2H').val();
                var r2h_uid = $("#page1_AttendenceDetails_R2HUID").val();
               
                if(r2h == 0)
                {
                  $("#page1_AttendenceDetails_R2HUID").val("Nil");
                }
                
                if(r2h != 0 && r2h_uid == "Nil")
                {
                  $("#page1_AttendenceDetails_R2HUID").val("");
                }
            });
            
            /*$("#page1_AttendenceDetails_Absent").focusout(function()
            {
                var absent = $('#page1_AttendenceDetails_Absent').val();
                var absent_uid = $('#page2_AttendenceDetails_AbsentUID').val();
                
                if(absent == 0)
                {
                  $("#page2_AttendenceDetails_AbsentUID").val("Nil");
                } 
                
                if(absent != 0 && absent_uid == "Nil")
                {
                  $("#page2_AttendenceDetails_AbsentUID").val("");
                }
            });*/

            $("#page1_AttendenceDetails_Absent").focusout(function()
               {
                   var absent = $('#page1_AttendenceDetails_Absent').val();
                   var absent_uid = $('#page2_AttendenceDetails_AbsentUID').val();
                  
                  if(absent == 0)
                   {
                     $("#page2_AttendenceDetails_AbsentUID").val("Nil");
                     $(".select_absented_Students").attr("disabled", true);
                     $(".clear_absented_Students").attr("disabled", true);

                   } 
                   else if(absent > 20)
                   {
                       $("#page2_AttendenceDetails_AbsentUID").val("If absent students count is more than 20 no need to Select Unique ID's")
					   .css('font-size','16px');
                       $(".select_absented_Students").attr("disabled", true);
                       $(".clear_absented_Students").attr("disabled", true);
                   }
                   
                   else if(absent != 0 || absent < 20 && absent_uid == "Nil")
                   {
                     $("#page2_AttendenceDetails_AbsentUID").val("");
                     //$(".selectstudent").prop("enable", true);
                      $(".select_absented_Students").removeAttr("disabled");
                      $(".clear_absented_Students").removeAttr("disabled");


                   }
                });

            
            $("#page2_AttendenceDetails_RestRoom").focusout(function()
            {
                var restroom = $('#page2_AttendenceDetails_RestRoom').val();
                var restroom_uid = $('#page2_AttendenceDetails_RestRoomUID').val();
                
                if(restroom == 0)
                {
                  $("#page2_AttendenceDetails_RestRoomUID").val("Nil");
                }
                
                if(restroom != 0 && restroom_uid == "Nil")
                {
                  $("#page2_AttendenceDetails_RestRoomUID").val("");
                }
            });
            
           });
        
       
    
    
        </script>
           
   <?php
    //include footer
    include("inc/footer.php");
   ?>
    
    
           

               <script type="text/javascript">
                runAllForms();

               $.validator.addMethod('fileminsize', function(value, element, param) {
                   return this.optional(element) || (element.files[0].size >= param) 
              });

              
               $.validator.addMethod('filemaxsize', function(value, element, param) {
                   return this.optional(element) || (element.files[0].size <= param) 
              });
              
              $.validator.addMethod('match_sick_count', function(value, element) {
                   var sick_count = $("#page1_AttendenceDetails_Sick").val();
                   if(sick_count !=0)
                   {
                       var sick_id_array = [];
                       var sick_ids = $("#page1_AttendenceDetails_SickUID").val();
                       sick_id_array = sick_ids.split(",");
                       var id_count = sick_id_array.length;
                       return id_count == sick_count;
                   }
                   else
                   {
                       var id_ = element.id;
                       $('#'+id_+'').val("");
                       $('#'+id_+'').val("Nil");
                       return true;
                   }
              });

              $.validator.addMethod('match_absent_count', function(value, element) {
                   var absent_count = $("#page1_AttendenceDetails_Absent").val();
                   if(absent_count !=0 && absent_count < 20)
                   {
                       var absent_id_array = [];
                       var absent_ids = $("#page2_AttendenceDetails_AbsentUID").val();
                       absent_id_array = absent_ids.split(",");
                       var id_count = absent_id_array.length;
                       return id_count == absent_count;
                   }
                   else if(absent_count > 20)
                   {
                         return true;
                   }

                   else
                   {
                       var id_ = element.id;
                       $('#'+id_+'').val("");
                       $('#'+id_+'').val("Nil");
                       return true;
                   }
              });
              
               $.validator.addMethod('match_r2h_count', function(value, element) {
                   var r2h_count = $("#page1_AttendenceDetails_R2H").val();
                   if(r2h_count !=0)
                   {
                       var r2h_id_array = [];
                       var r2h_ids = $("#page1_AttendenceDetails_R2HUID").val();
                       r2h_id_array = r2h_ids.split(",");
                       var id_count = r2h_id_array.length;
                       return id_count == r2h_count;
                   }
                   else
                   {
                       var id_ = element.id;
                       $('#'+id_+'').val("");
                       $('#'+id_+'').val("Nil");
                       return true;
                   }
              });
              
               $.validator.addMethod('match_rest_count', function(value, element) {
                   var rest_count = $("#page2_AttendenceDetails_RestRoom").val();
                   if(rest_count !=0)
                   {
                       var rest_id_array = [];
                       var rest_ids = $("#page2_AttendenceDetails_RestRoomUID").val();
                       rest_id_array = rest_ids.split(",");
                       var id_count = rest_id_array.length;
                       return id_count == rest_count;
                   }
                   else
                   {
                       var id_ = element.id;
                       $('#'+id_+'').val("");
                       $('#'+id_+'').val("Nil");
                       return true;
                   }
              });
              
               $(function() {
                // Validation
                $("#web_view").validate({
                ignore: "",
                // Rules for form validation
                    rules : {
   page1_AttendenceDetails_District:{required:true},
   page1_AttendenceDetails_SelectSchool:{required:true},
   page1_AttendenceDetails_Attended:{required:true,minlength:1,maxlength:123},
   page1_AttendenceDetails_Sick:{required:true,minlength:1,maxlength:123},
   page1_AttendenceDetails_SickUID:{required : true,minlength:1,maxlength:5000,match_sick_count:true},
   //page1_AttendenceDetails_SickUID:{required : true,minlength:1,maxlength:5000},
   page1_AttendenceDetails_R2H:{required:true,minlength:1,maxlength:123},
   page1_AttendenceDetails_R2HUID:{required:true,minlength:1,maxlength:5000,match_r2h_count:true},
   //page1_AttendenceDetails_R2HUID:{required:true,minlength:1,maxlength:5000},
   page1_AttendenceDetails_Absent:{required:true,minlength:1,maxlength:123},
   page2_AttendenceDetails_AbsentUID:{required:true,minlength:1,maxlength:5000,match_absent_count:true},
   //page2_AttendenceDetails_AbsentUID:{required:true,minlength:1,maxlength:5000},
   page2_AttendenceDetails_RestRoom:{required:true,minlength:1,maxlength:123},
   page2_AttendenceDetails_RestRoomUID:{required:true,minlength:1,maxlength:5000,match_rest_count:true},
   },
       
       //Messages for form validation
            messages : {page1_AttendenceDetails_District:{required:"District field is required"},
   page1_AttendenceDetails_SelectSchool:{required:"Select School field is required"},
   page1_AttendenceDetails_Attended:{required:"Attended field is required"},
   page1_AttendenceDetails_Sick:{required:"Sick field is required"},
   //page1_AttendenceDetails_SickUID:{required:"Sick UID field is required",match_sick_count:"Sick UID should match the count mentioned in Sick field"},
   //page1_AttendenceDetails_SickUID:{required:"Sick UID field is required"},
   page1_AttendenceDetails_SickUID:{required:"Sick UID field is required",match_sick_count:"Sick UID should match the count mentioned in Sick field"},
   page1_AttendenceDetails_R2H:{required:"R2H field is required"},
   page1_AttendenceDetails_R2HUID:{required:"R2H UID field is required",match_r2h_count:"R2H UID should match the count mentioned in R2H field"},
   //page1_AttendenceDetails_R2HUID:{required:"R2H UID field is required"},
   page1_AttendenceDetails_Absent:{required:"Absent field is required"},
   page2_AttendenceDetails_AbsentUID:{required:"Absent UID field is required",match_absent_count:"Absent UID should match the count mentioned in Absent field"},
   //page2_AttendenceDetails_AbsentUID:{required:"Absent UID field is required"},
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

 });
   </script>