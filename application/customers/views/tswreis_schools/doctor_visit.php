<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Doctor visit";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["weekly_doctor_visit"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- <link href="<?php //echo(CSS.'mdb.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" /> -->

<style type="text/css">
  
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
  .attachments{
    background: #1E608E;
  }
  #student_photo{
    height: 141px;
    width: 185px;
    }
  @media only screen and (max-width:600px){
    #student_photo{
      height: 154px;
    width: 315px;
    }
  } 
</style>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<!-- ==========================CONTENT STARTS HERE ========================== -->
<div id="main" role="main">
  <?php


  include("inc/ribbon.php");
  ?>
<!-- MAIN PANEL -->
    <div id="content">
       
            <div class="row">
               <!-- NEW WIDGET START -->
                    <article class="col-sm-12 col-md-12 col-lg-10">
                
                      <!-- Widget ID (each widget will need unique ID)-->
                      <div class="jarviswidget jarviswidget-color-orange" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" style="border: 5px solid #CB9235;">
                        
                        <header><center>
                          <span class="widget-icon"> <i class="fa fa-pencil-square"></i> </span>
                          <h2>Doctor Visiting Form</h2>
                          </center>
                
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
                            echo  form_open_multipart('tswreis_schools/create_doctor_visit_report',$attributes);
                            ?>
                             <div class="row">
                              <div class="col col-lg-1">
                              </div>
                                    <div class="clo-md-6">
                                      <label class="col-md-5"><h4 style="color:blue;"><u> If new doctor came please fill this form</u> ==></h4></label>
                                      <!-- <button type="submit" id="doctor_form" class="bg-color-blue">Doctor Form</button><br>  -->                           
                                    </div>                             
                               <!-- Doctor personal profile -->
                              <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Create Doctor</button>
                              </div>
                            <fieldset>
                              <br>
                              <br>
                            <div class="row">
                              <div class="form-group">
                                <label class="col-md-2">&ensp; UNIQUE ID:</label>
                                 <label class='input col-md-1  unique_id'><?php echo $district_code."_".$school_code."_";?></label >
                                    <input type="number" class="col-md-2 student_code" id="page1_StudentDetails_HospitalUniqueID" name="page1_StudentDetails_HospitalUniqueID" required="required">  
                                <button type="button" class="btn btn-primary  col-md-offset-3 col-md-3 retriever_search" id="searchIdBtn"  field_ref='page1_Personal Information_Hospital Unique ID'><i class="fa fa-search"> SEARCH</i></button>       
                              </div>
                            </div>
                              <div class="row">
                                <section class="col col-lg-8">
                                <div class="form-group">
                                <label class="col-md-3 ">NAME:</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="student_name" name="student_name" readonly></div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-3 ">CLASS:</label>
                                <div class="col-md-9">
                                   <input type="text" class="form-control" id="student_class" name="student_class" readonly></div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-3 ">SECTION:</label>
                                <div class="col-md-9">
                                   <input type="text" class="form-control" id="student_section" name="student_section" readonly></div>
                                </div>
                               </section>
                              <section class="col col-lg-2 ">
                                  <!-- <img src="" alt="hi"/> -->
                                  <div id="student_image" class="col-md-offset-3"></div>
                                    <div id="image_logo"></div>
                                </section>
                              </div>
                              <div class="form-group">
                                <label class="col-md-2">DATE:</label>
                                <div class="col-md-6" >
                                   <input type="date" class="form-control" id="doctor_visiting_date" name="doctor_visiting_date" value="<?php echo date('Y-m-d'); ?>" readonly="readonly"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2">REMARKS:</label>
                                  <div class="col-md-6">
                                     <textarea class="form-control" name="remarks" placeholder="Textarea" rows="4"></textarea>
                                  </div>
                                </div>
                            <div class="form-group">
                                <label class="col-md-2">VISITING Dr.. NAME:</label>
                                 <div class="col-md-6">
                                      <section class="col col-10">
                                        <label class="select"><i class="arrow down"></i>
                                            <select id="select_doc_name" name="select_doc_name" style="width: 200px; height:40px;">
                                              <option value="" selected="0" disabled="">Select a doctor</option>
                                              <?php if(isset($doc_names)): ?>
                                               <!--  <option value='All' >Choose Dr..</option> -->
                                                <?php foreach ($doc_names as $dist):?>
                                                <option selected="selected" value='<?php echo $dist['doc_data']['widget_data']['page1']['Personal Information']['Name'];?>' ><?php echo $dist['doc_data']['widget_data']['page1']['Personal Information']['Name'];?></option>
                                                <?php endforeach;?>
                                                <?php else: ?>
                                                <option value="1"  disabled="">No district entered yet</option>
                                              <?php endif ?>
                                            </select>
                                        </label>
                                    </section>
                                </div>
                            </div>
                              <input type="hidden" name="doc_id" id="docId" value="">
                                </div>

                                  <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading  text-center"><strong>Attachments</strong>
                            </div>
                            <!-- <div class="form-group ">
                           <input type="file" id="files"  name="hs req attachments[]" style="display:none;" multiple> 
                            <label for="files" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;  id="attachment_browse" >
                               Browse.....
                           </label>  Nav tabs
                       </div> -->
          <ul class="nav nav-tabs md-tabs nav-justified primary-color" role="tablist">
            <li class="nav-item">
              <a class="nav-link attachments" data-toggle="tab" href="#panel668" role="tab">
              <!-- <i class="fa fa-heart pr-2"> </i>-->Prescriptions<span class="badge" id="prescriptions_count"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link active attachments" data-toggle="tab" href="#panel555" role="tab">
              <!-- <i class="fa fa-user pr-2"></i> -->Lab Reports<span class="badge" id="lab_count"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link attachments" data-toggle="tab" href="#panel666" role="tab">
              <!-- <i class="fa fa-heart pr-2"> </i>-->X-ray/MRI/Digital Images<span class="badge" id="xray_count"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link attachments" data-toggle="tab" href="#panel667" role="tab">
              <!-- <i class="fa fa-heart pr-2"> </i>-->Payments/Bills<span class="badge" id="payment_count"></span></a>
            </li>
             <li class="nav-item">
              <a class="nav-link attachments" data-toggle="tab" href="#panel669" role="tab">
              <!-- <i class="fa fa-heart pr-2"> </i>-->Discharge Summary<span class="badge" id="discharge_count"></span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link attachments" data-toggle="tab" href="#panel770" role="tab">
              <!-- <i class="fa fa-heart pr-2"> </i>-->Others<span class="badge" id="others_count"></span></a>
            </li>
          </ul>
          <!-- Nav tabs -->

          <!-- Tab panels -->
          <div class="tab-content">

            <!-- Panel 1 -->
            <div class="tab-pane fade" id="panel555" role="tabpanel">

              <!-- Nav tabs -->
              <div id="lab_reports_doctor_attachments"></div>
              
      <input type="file" id="files_labs"  name="Lab_Reports[]" style="display:none;" multiple/>
                                   
                 <label for="files_labs" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">

                   Labs Reports.....
               </label>

                                     

            </div>
            <!-- Panel 1 -->

            <!-- Panel 2 -->
            <div class="tab-pane fade" id="panel666" role="tabpanel" >

              <div id="xray_doctor_attachments"></div>

            <input type="file" id="files_xray"  name="Digital_Images[]" style="display:none;" multiple>
              <label for="files_xray" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                 X-ray/MRI/ Digital Images.....
              </label>
            </div>
            <!-- Panel 2 -->
            <!-- Panel 2 -->

            <div class="tab-pane fade" id="panel667" role="tabpanel">

              <div id="bills_doctor_attachments"></div>
              <input type="file" id="files_bills"  name="Payments_Bills[]" style="display:none;" multiple>
                                   
              <label for="files_bills" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                 Payments Bills attachments.....
              </label>
            
            </div>
            <div class="tab-pane fade" id="panel669" role="tabpanel">

              <div id="summary_doctor_attachments"></div>
              <input type="file" id="files_ds"  name="Discharge_Summary[]" style="display:none;" multiple>
                                   
              <label for="files_ds" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                 Discharge Summary.....
              </label>

            </div>
            <!-- Panel 2 -->
            <!-- Panel 2 -->
            <div class="tab-pane fade" id="panel668" role="tabpanel">
            <div id="prescription_doctor_attachments"></div>
              <input type="file" id="files_prescriptions"  name="Prescriptions[]" style="display:none;" multiple>
                                   
              <label for="files_prescriptions" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                 Prescriptions.....
              </label>
            </div>
             <div class="tab-pane fade"  id="panel770" role="tabpanel">

           <div id="external_attachments"></div>
             <input type="file" id="files"  name="hs_req_attachments[]" style="display:none;" multiple>
                                   <canvas id="canvas" width="5" height="5"></canvas>
              <label for="files" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                 Others.....
              </label>

            </div>
            <!-- Panel 2 -->

          </div>
        <!-- Tab panels -->
        
                          </div>
                        
                        </fieldset>
                             
                        <div class="form-group">
                                <button class="col-md-offset-3 btn btn-success col-md-3 submit" type="submit" id="submit">
                                      <i class="fa fa-save"></i>
                                      SUBMIT
                                    </button>
                                </div>
                              </fieldset>
                        <fieldset>
                          <div class="form-group hide">
                              <div id="chartContainer" style="height: 370px; width: 80%;"></div>
                            </div>
                        </fieldset>
                          <?php echo form_hidden('student_code','');?>
                          <?php echo form_close(); ?><div id="reasons">
                
                          </div>
                          <!-- end widget content -->
                
                        </div>
                        <!-- end widget div -->
                
                      </div>
                      <!-- end widget -->
                
                
                    </article>
                    <!-- WIDGET END -->
                    <form style="display: hidden" action="drill_down_to_doctor_treated_list" method="POST" 
                    id="ehr_data_form">
                        <input type="hidden" id="selected_date" name="selected_date" value=""/>
                      </form>
    </div>

          <!-- Modal -->
                          <div id="myModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">                                      
                                      <?php  $attributes = array('class' => 'form-horizontal','id'=>'web_view','name'=>'userform');
                                        echo  form_open_multipart('tswreis_schools/add_doctor_profile',$attributes);
                                        ?>
                                      <div class="modal-header"  style="background-color:teal">
                                        <button type="button" class="close btn-primary" data-dismiss="modal"></button>
                                        <h3 class="modal-title" align="center" style="color:white;" >Create Doctor</h3>
                                      </div>
                                      <div class="modal-body">
                                       <section class="col col-5">
                                                <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                                  <input type="text" name="doc_name" id="doc_name" placeholder="Doctor Name" required="required">
                                                </label><br>
                                                <label class="input"> <i class="icon-prepend fa fa-phone"></i>
                                                  <input type="tel" name="mobile" id="mobile" placeholder="Mobile Number" class="valid">
                                                </label>
                                                <label class="input"><i class="icon-prepend fa fa fa-user-md"></i>
                                                <input type="text" name="qualification_id" id="qualification_id" placeholder="Qualification" required="required">
                                              </label>                                               
                                        </section>                                       
                                         <section class="col col-5">
                                                <label class="input"> <i class="icon-prepend fa fa-info-circle"></i>
                                                  <input type="text" name="rgs_no" id="rgs_no" placeholder="Registraction Number">
                                                </label>
                                                <label class="input"> <i class="icon-prepend fa fa-ambulance"></i>
                                                  <input type="text" name="current_working_place" id="current_working_place" placeholder="Current Working hos. Name"  required="required">
                                                </label>
                                                <label class="input"><i class="icon-prepend fa fa-stethoscope"></i>
                                                <input type="text" name="doc_specialization" id="doc_specialization" placeholder="Specialization" required="required">
                                              </label>  
                                          </section>
                                                                                      
                                      </div>
                                      <br><br><br>
                                      <div class="modal-footer">
                                        <button type="submit" class="btn btn-success bg-color-green" style="height:30px;width:200px" id="submit_form">
                                              Create Doctor Details                                           
                                        </button>
                                        <button type="submit" class="btn btn-primary bg-color-red" data-dismiss="modal" style="font-color:white">Close</button>
                                      </div>
                                      <?php echo form_close();?>
                                    </div>
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
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script src="<?php echo JS; ?>sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<!-- <script src="<?php //echo JS; ?>mdb.min.js"></script> -->


<script>
$(document).ready(function() {

<?php if($this->session->flashdata('success')): ?>

         swal({
                title: "Good job! successfully",
                text: "<?php //echo $this->session->flashdata('success'); ?>",
                icon: "success",
    
          });
          toastr.success("<?php echo $this->session->flashdata('success'); ?>","Success");
       <?php elseif($this->session->flashdata('fail')): ?>
      /* swal({
                title: "Failed!",
                text: "<?php // echo $this->session->flashdata('fail'); ?>",
                icon: "error",
    
          });*/
          toastr.error("<?php echo $this->session->flashdata('fail'); ?>","Failed");
<?php endif; ?>

       //setting unique id value during submission //
    $(document).on('click','.submit',function(e)
    {
      
        if ( ($.trim($('#page1_StudentDetails_HospitalUniqueID').val()).length > 0)  &&  ($.trim($('#page1_StudentDetails_bloodgroup').val()).length > 0)  &&($.trim($('#page1_StudentDetails_HB').val()).length > 0)  )
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
      var field_ref = $(".retriever_search").attr("field_ref") || '';
      console.log('field_ref',field_ref);
      if($(".retriever_search").prev('input').prev('label').hasClass('unique_id'))
      {
        var query_ref_label = $(".retriever_search").prev('input').prev('label').text() || '';
        var query_ref_input = $(".retriever_search").prev('input').val() || '';
        var query_ref_ = ""+query_ref_label+""+query_ref_input+""
      }
      else
      {
        var query_ref_ = $(".retriever_search").prev('input').val() || '';
      }
      
      var stu_code = $('input[name="student_code"]').val(query_ref_);
      //console.log(naresh,'nareshhhhh');
    }); 


$('#searchIdBtn').click(function (){


    var field_ref = $(this).attr("field_ref") || '';
      if($(this).prev('input').prev('label').hasClass('unique_id'))
      {
        var query_ref_label = $(this).prev('input').prev('label').text() || '';
        var query_ref_input = $(this).prev('input').val() || '';
        var query_ref = ""+query_ref_label+""+query_ref_input+""
            $('#student_unique_id').val(query_ref);
        console.log(query_ref,"unique_id");
      }
      else
      {
        var query_ref = $(this).prev('input').val() || '';
            $('#student_unique_id').val(query_ref);
        console.log(query_ref,"unique_id");
      }



   /* var district_school_code = $('.unique_id').text() || '';
    var student_code = $('.student_code').val() || '';
    var dist_school_unique_code = ""+district_school_code+""+student_code+"";
    console.log('dist_school_unique_code',dist_school_unique_code);*/
  
    $.ajax({
      url: 'fetch_student_info_for_doctor_visit',
      type: 'POST',
      data: {'page1_StudentDetails_HospitalUniqueID':query_ref },
      success:function(data){
      
     if(data == 'NO_DATA_AVAILABLE')
        {

            var uniqueIdField = $("input#page1_StudentDetails_HospitalUniqueID").val();
            $('#web_view').trigger('reset');
            $("input#page1_StudentDetails_HospitalUniqueID").val(uniqueIdField);

            //swal("Info !", "No student deatils available for this Unique ID: " + query_ref);
            toastr.error('No student deatils available for this Unique ID',query_ref)

        }
        else{
          
          data = $.parseJSON(data);
          console.log('check1', data);

          get_data = data.get_data;
          console.log('get_data',get_data);
          if(data.get_attachments == false)
          {

          }
          /*else
          {
             get_attachments = data.get_attachments;
         console.log('check2', get_attachments);
          var doc_id = get_attachments[0]["doc_properties"]["doc_id"];

          $("#docId").val(doc_id);

          // debugger;
       
          $.each(get_attachments,function(){
          
              $.each(this['doc_attachments']["Prescriptions"],function(){
             
                var attach = this['file_path'];
              //debugger;
                $('#prescription_doctor_attachments').append('<code>             </code><embed id="image1" class="imageThumb" src="<?php echo URLCustomer;?>'+attach+'">');
           
              });
              var prescriptions_count =$("#prescription_doctor_attachments > embed").length;
              if(prescriptions_count>0){
              $("#prescriptions_count").text(prescriptions_count);
            }

              $.each(this['doc_attachments']["Lab_Reports"],function(){
             
                var attach = this['file_path'];
              
                $('#lab_reports_doctor_attachments').append('<code>             </code><embed id="image2" class="imageThumb" src="<?php echo URLCustomer;?>'+attach+'">');
           
              });
              var lab_count =$("#lab_reports_doctor_attachments > embed").length;
              if(lab_count>0){
              $("#lab_count").text(lab_count);
            }
              $.each(this['doc_attachments']["Digital_Images"],function(){
             
                var attach = this['file_path'];
              
                $('#xray_doctor_attachments').append('<code>             </code><embed id="image3" class="imageThumb" src="<?php echo URLCustomer;?>'+attach+'">');
           
              })
              var xray_count =$("#xray_doctor_attachments > embed").length;
              if(xray_count>0){
              $("#xray_count").text(xray_count);
            }
              $.each(this['doc_attachments']["Payments_Bills"],function(){
             
                var attach = this['file_path'];
              
                $('#bills_doctor_attachments').append('<code>             </code><embed id="image4" class="imageThumb" src="<?php echo URLCustomer;?>'+attach+'">');
           
              })
              var payment_count =$("#bills_doctor_attachments > embed").length;
              if(payment_count>0){
              $("#payment_count").text(payment_count);
            }
              $.each(this['doc_attachments']["Discharge_Summary"],function(){
             
                var attach = this['file_path'];
              
                $('#summary_doctor_attachments').append('<code>             </code><embed id="image5" class="imageThumb" src="<?php echo URLCustomer;?>'+attach+'">');
           
              })
              var discharge_count =$("#summary_doctor_attachments > embed").length;
              if(discharge_count > 0) {
              $("#discharge_count").text(discharge_count);
            }
              $.each(this['doc_attachments']["external_attachments"],function(){
             
                var attach = this['file_path'];
              
                $('#external_attachments').append('<code>             </code><embed id="image6" class="imageThumb" src="<?php echo URLCustomer;?>'+attach+'">');
           
              })
              var others_count =$("#external_attachments > embed").length;
              if (others_count > 0){
                $("#others_count").text(others_count);
              }


          })
          }*/
         
         

          $.each(get_data, function() {
             
            $("#student_name").val(this['doc_data']['widget_data']['page1']['Personal Information']['Name']);
            
            $("#student_class").val(this['doc_data']['widget_data']['page2']['Personal Information']['Class']);
            $("#student_section").val(this['doc_data']['widget_data']['page2']['Personal Information']['Section']);

            //$("#student_section").val(this['doc_data']['widget_data']['page2']['Personal Information']['Section']);
            
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
student_name:{minlength:1,maxlength:123},
student_class:{minlength:1,maxlength:123},
student_section:{minlength:1,maxlength:123},
page1_StudentDetails_bloodgroup:{required:true,minlength:1,maxlength:4},
page1_StudentDetails_HB:{required:true,minlength:1,maxlength:4},
 page1_StudentDetails_BMI:{minlength:1,maxlength:5}
// doctor_visiting_date:{required:false},
},
  
  //Messages for form validation
messages : {page1_StudentDetails_HospitalUniqueID:{required:"Hospital Unique ID field is required"},
student_name:{},
student_class:{},
student_section:{},
page1_StudentDetails_bloodgroup:{required:"bloodgroup  field is required"},
page1_StudentDetails_HB:{required:"HB  field is required"},
page1_StudentDetails_BMI:{},
},highlight: function(element) {
   
       // add a class "has_error" to the element 
       $(element).addClass('has_error');
   },
   unhighlight: function(element) {
   
       // remove the class "has_error" from the element 
       $(element).removeClass('has_error');
   },
onkeyup: false, //turn off auto validate whilst typing
});

});

});
</script>
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
         //$("input:file").html("#files_xray");
      
      //var files = $(".imageThumb").array(); 
    });

    }
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
         $("input:file").html("#files_prescriptions");
      
      //var files = $(".imageThumb").array(); 
    });

    }
  </script>
  
  <script>
    if (window.File && window.FileList && window.FileReader) 
    {
        
    //var numFiles = $("input:file")[0].files.length;
     $("#files_ds").on("change", function(e) {
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
              var canvas = document.createElement("canvas");
              $("<span   class=\"pip\">" +
              "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
              "<br/><span class=\"remove\">Remove image</span>" +
              "</span>").prependTo("#panel669");
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
  <script>
    if (window.File && window.FileList && window.FileReader) 
    {
        
    //var numFiles = $("input:file")[0].files.length;
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

        /* var size = $("input:file")[0].files[j].size;*/
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
         //$("input:file").html("#files_labs");
      
      //var files = $(".imageThumb").array(); 
    });

    }
</script>

<?php 
  //include footer
  include("inc/footer.php"); 
?>
