    <?php

    //initilize the page
    //require_once("inc/init.php");

    //require UI configuration (nav, ribbon, etc.)
    require_once("inc/config.ui.php");

    /*---------------- PHP Custom Scripts ---------

    YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
    E.G. $page_title = "Custom Title" */

    $page_title = "Show student Field Officer";

    /* ---------------- END PHP Custom Scripts ------------- */

    //include header
    //you can add your custom css in $page_css array.
    //Note: all css files are inside css/ folder
    $page_css[] = "your_style.css";
    include("inc/header.php");

    //include left panel (navigation)
    //follow the tree in inc/config.ui.php
    $page_nav["basic_dashboard"]["active"] = true;
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

    </style>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- ==========================CONTENT STARTS HERE ========================== -->
    <div id="main" role="main">
      <?php include("inc/ribbon.php"); ?>
    <!-- MAIN PANEL -->
        <div id="content">
                <div class="row">
                   <!-- NEW WIDGET START -->
                        <article class="col-sm-12 col-md-12 col-lg-8">
                     
                             <!-- Widget ID (each widget will need unique ID)-->
                    <div class="jarviswidget" id="wid-id-8" data-widget-editbutton="false" data-widget-custombutton="false">
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
                        <header>
                            <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                            <h2>Field Officer Report</h2>             
                            
                        </header>

                        <!-- widget div-->
                        <div>
                            
                            <!-- widget edit box -->
                            <div class="jarviswidget-editbox">
                                <!-- This area used as dropdown edit box -->
                                
                            </div>
                            <!-- end widget edit box -->
                            
                            <!-- widget content -->
                            <div class="widget-body no-padding">
                                
                                <form action="#" method="post" id="contact-form" class="smart-form">
                                  <?php foreach ($unique_id as $student): ?>
                                    <fieldset>                  
                                        <div class="row">
                                            <section class="col col-6">
                                                <label class="label">Unique ID</label>
                                                <label class="input">
                                                    <input type="text" value="<?php echo $student['doc_data']['widget_data']['Student Details']['Hospital Unique ID'];  ?>" readonly>
                                                </label>
                                            </section>
                                            <section class="col col-6">
                                                <label class="label">Student's Name</label>
                                                <label class="input">
                                                    <input type="email" value="<?php echo $student['doc_data']['widget_data']['Student Details']['Name'];  ?>" readonly>
                                                </label>
                                            </section>
                                        </div> 
                                        <div class="row">
                                            <section class="col col-6">
                                                <label class="label">Class</label>
                                                <label class="input">
                                                    <input type="text" value="<?php echo $student['doc_data']['widget_data']['Student Details']['Class'];  ?>" readonly>
                                                </label>
                                            </section>
                                            <section class="col col-6">
                                                <label class="label">Section</label>
                                                <label class="input">
                                                    <input type="email" value="<?php echo $student['doc_data']['widget_data']['Student Details']['Section'];  ?>" readonly>
                                                </label>
                                            </section>
                                        </div>
                                         <?php if($student['doc_data']['widget_data']['type_of_request']  == "Out Patients"): ?>
                                        <div class="row">
                                            <section class="col col-6">
                                                <label class="label">Hospital Name</label>
                                                <label class="input">
                                                    <input type="text" value="<?php echo $student['doc_data']['widget_data']['Out Patient']['hospialt_name'];  ?>" readonly>
                                                </label>
                                            </section>
                                            <section class="col col-6">
                                                <label class="label">Doctor's Name</label>
                                                <label class="input">
                                                    <input type="email" value="<?php $student['doc_data']['widget_data']['Out Patient']['doctor_name'];  ?>" readonly>
                                                </label>
                                            </section>
                                        </div>
                                        
                                        <section>
                                            <label class="label">Case Details</label>
                                            <label class="textarea">
                                                <i class="icon-append fa fa-comment"></i>
                                                <textarea rows="4" name="message" id="message"><?php echo $student['doc_data']['widget_data']['Out Patient']['patient_details'];  ?></textarea>
                                            </label>
                                        </section>
                                        <div class="row">
                                            <section class="col col-6">
                                                <label class="label">Investigation</label>
                                                <label class="input">
                                                    <input type="text" name="named" id="named" value="<?php echo $student['doc_data']['widget_data']['Out Patient']['investigations'];  ?>">
                                                </label>
                                            </section>
                                            <section class="col col-6">
                                                <label class="label">Review Date</label>
                                                <label class="input">
                                                    <input type="email" name="emaild" id="emaild" value="<?php echo $student['doc_data']['widget_data']['Out Patient']['review_date'];  ?>">
                                                </label>
                                            </section>
                                        </div>
                                         <section>
                                            <label class="label">Medication</label>
                                            <label class="textarea">
                                                <i class="icon-append fa fa-comment"></i>
                                                <textarea rows="4" name="message" id="message"><?php echo $student['doc_data']['widget_data']['Out Patient']['medication'];  ?></textarea>
                                            </label>
                                        </section>
                                        <?php endif; ?>
                                        <?php if($student['doc_data']['widget_data']['type_of_request']  == "Emergency or Admitted"): ?>

                                            <div class="row">
                                            <section class="col col-6">
                                                <label class="label">Hospital Name</label>
                                                <label class="input">
                                                    <input type="text" value="<?php echo $student['doc_data']['widget_data']['Emergency or Admitted']['hospialt_name'];  ?>" readonly>
                                                </label>
                                            </section>
                                            <section class="col col-6">
                                                <label class="label">Doctor's Name</label>
                                                <label class="input">
                                                    <input type="email" value="<?php $student['doc_data']['widget_data']['Emergency or Admitted']['doctor_name'];  ?>" readonly>
                                                </label>
                                            </section>
                                        </div>
                                        
                                        <section>
                                            <label class="label">Case Details</label>
                                            <label class="textarea">
                                                <i class="icon-append fa fa-comment"></i>
                                                <textarea rows="4" name="message" id="message"><?php echo $student['doc_data']['widget_data']['Emergency or Admitted']['patient_details'];  ?></textarea>
                                            </label>
                                        </section>
                                        <div class="row">
                                            <section class="col col-6">
                                                <label class="label">Investigation</label>
                                                <label class="input">
                                                    <input type="text" name="named" id="named" value="<?php echo $student['doc_data']['widget_data']['Emergency or Admitted']['investigations'];  ?>">
                                                </label>
                                            </section>
                                            <section class="col col-6">
                                                <label class="label">Review Date</label>
                                                <label class="input">
                                                    <input type="email" name="emaild" id="emaild" value="<?php echo $student['doc_data']['widget_data']['Emergency or Admitted']['review_date'];  ?>">
                                                </label>
                                            </section>
                                        </div>
                                         <section>
                                            <label class="label">Medication</label>
                                            <label class="textarea">
                                                <i class="icon-append fa fa-comment"></i>
                                                <textarea rows="4" name="message" id="message"><?php echo $student['doc_data']['widget_data']['Emergency or Admitted']['medication'];  ?></textarea>
                                            </label>
                                        </section>

                                       <?php endif; ?>
                                       <?php if($student['doc_data']['widget_data']['type_of_request']  == "Review Cases"): ?>
                                             <div class="row">
                                            <section class="col col-6">
                                                <label class="label">Hospital Name</label>
                                                <label class="input">
                                                    <input type="text" value="<?php echo $student['doc_data']['widget_data']['Review Cases']['hospialt_name'];  ?>" readonly>
                                                </label>
                                            </section>
                                            <section class="col col-6">
                                                <label class="label">Doctor's Name</label>
                                                <label class="input">
                                                    <input type="email" value="<?php $student['doc_data']['widget_data']['Review Cases']['doctor_name'];  ?>" readonly>
                                                </label>
                                            </section>
                                        </div>
                                        
                                        <section>
                                            <label class="label">Case Details</label>
                                            <label class="textarea">
                                                <i class="icon-append fa fa-comment"></i>
                                                <textarea rows="4" name="message" id="message"><?php echo $student['doc_data']['widget_data']['Review Cases']['patient_details'];  ?></textarea>
                                            </label>
                                        </section>
                                        <div class="row">
                                            <section class="col col-6">
                                                <label class="label">Investigation</label>
                                                <label class="input">
                                                    <input type="text" name="named" id="named" value="<?php echo $student['doc_data']['widget_data']['Review Cases']['investigations'];  ?>">
                                                </label>
                                            </section>
                                            <section class="col col-6">
                                                <label class="label">Review Date</label>
                                                <label class="input">
                                                    <input type="email" name="emaild" id="emaild" value="<?php echo $student['doc_data']['widget_data']['Review Cases']['review_date'];  ?>">
                                                </label>
                                            </section>
                                        </div>
                                         <section>
                                            <label class="label">Medication</label>
                                            <label class="textarea">
                                                <i class="icon-append fa fa-comment"></i>
                                                <textarea rows="4" name="message" id="message"><?php echo $student['doc_data']['widget_data']['Review Cases']['medication'];  ?></textarea>
                                            </label>
                                        </section>
                                        <?php endif; ?>
                                    </fieldset>

                                   <h4 class="text-center text-primary"><strong><i class="fa fa-link"> Attachments</strong></h4></i>
                                     <ul id="myTab1" class="nav nav-tabs bordered">
                                        <li class="active">
                                            <?php if(isset($student['doc_attachments']['Prescriptions']) && !empty($student['doc_attachments']['Prescriptions'])) : ?>
                                            <a href="#prescription_attachments" data-toggle="tab">Prescriptions <?php if(count($student['doc_attachments']['Prescriptions']) > 0): ?> <span class="badge bg-color-green"><?php echo count($student['doc_attachments']['Prescriptions']); ?></span><?php  endif; ?></a>
                                        <?php endif; ?>
                                        </li>
                                        <li>
                                            <?php if(isset($student['doc_attachments']['Lab_Reports']) && !empty($student['doc_attachments']['Lab_Reports'])) : ?>
                                            <a href="#lab_report_attachments" data-toggle="tab">Lab Reports <?php if(count($student['doc_attachments']['Lab_Reports']) >0): ?><span class="badge bg-color-green"><?php echo count($student['doc_attachments']['Lab_Reports']); ?></span><?php endif; ?></a>
                                            <?php endif; ?>
                                        </li>
                                        <li>
                                            <?php if(isset($student['doc_attachments']['Digital_Images']) && !empty($student['doc_attachments']['Digital_Images'])) : ?>
                                            <a href="#xray_attachments" data-toggle="tab">X-ray/MRI/Digital Images<?php if(count($student['doc_attachments']['Digital_Images']) >0): ?><span class="badge bg-color-green"><?php echo count($student['doc_attachments']['Digital_Images']); ?></span><?php endif; ?> </a>
                                             <?php endif; ?>
                                        </li> 
                                        <li>
                                            <?php if(isset($student['doc_attachments']['Payments_Bills']) && !empty($student['doc_attachments']['Payments_Bills'])) : ?>
                                            <a href="#payment_bill_attachments" data-toggle="tab">Payments/Bills<?php if(count($student['doc_attachments']['Payments_Bills']) >0): ?><span class="badge bg-color-green"><?php echo count($student['doc_attachments']['Payments_Bills']); ?></span><?php endif; ?> </a>
                                             <?php endif; ?>
                                        </li> 
                                       
                                        <li>
                                            <?php if(isset($student['doc_attachments']['Discharge_Summary']) && !empty($student['doc_attachments']['Discharge_Summary'])) : ?>
                                            <a href="#discharge_summary_attachments" data-toggle="tab">Discharge Summary<?php if(count($student['doc_attachments']['Discharge_Summary']) >0): ?><span class="badge bg-color-green"><?php echo count($student['doc_attachments']['Discharge_Summary']); ?></span><?php endif; ?> </a>
                                             <?php endif; ?>
                                        </li>
                                         <li>
                                            <?php if(isset($student['doc_attachments']['external_attachments']) && !empty($student['doc_attachments']['external_attachments'])) : ?>
                                            <a href="#other_attachments" data-toggle="tab">Others<?php if(count($student['doc_attachments']['external_attachments']) >0): ?><span class="badge bg-color-green"><?php echo count($student['doc_attachments']['external_attachments']); ?></span><?php endif; ?></a>
                                             <?php endif; ?>
                                        </li>
                                  
                                    </ul>
                                    <div id="myTabContent1" class="tab-content padding-10">
                                        <div class="tab-pane fade in active" id="prescription_attachments">
                                           <?php if(isset($student['doc_attachments']['Prescriptions'])): ?>
                                                <div class='external_file_attachments'>
                                                <?php foreach($student['doc_attachments']['Prescriptions'] as $data):?>
                                                <a href="<?php echo URLCustomer.$data['file_path'];?>" rel="prettyPhoto[pp_gal]">
                                                    <embed src="<?php echo URLCustomer.$data['file_path'];?>" width="200"/>
                                                </a>
                                                <?php endforeach ?>
                                                </div>
                                                <?php endif ?>
                                        </div>
                                        <div class="tab-pane fade" id="lab_report_attachments">
                                            <?php if(isset($student['doc_attachments']['Lab_Reports'])): ?>
                                            <div class='external_file_attachments'>
                                            <?php foreach($student['doc_attachments']['Lab_Reports'] as $data):?>
                                            <a href="<?php echo URLCustomer.$data['file_path'];?>" rel="prettyPhoto[pp_gal]">
                                                <embed src="<?php echo URLCustomer.$data['file_path'];?>" width="200"/>
                                            </a>
                                            <?php endforeach ?>
                                            </div>
                                            <?php endif ?>
                                        </div>
                                        <div class="tab-pane fade" id="xray_attachments">
                                            <p>
                                                <?php if(isset($student['doc_attachments']['Digital_Images'])): ?>
                                                <div class='external_file_attachments'>
                                                <?php foreach($student['doc_attachments']['Digital_Images'] as $data):?>
                                                 <a href="<?php echo URLCustomer.$data['file_path'];?>" rel="prettyPhoto[pp_gal]">
                                                    <embed src="<?php echo URLCustomer.$data['file_path'];?>" width="200"/>
                                                </a>
                                                <?php endforeach ?>
                                                </div>

                                                <?php endif ?>
                                            </p>
                                        </div>
                                        <div class="tab-pane fade" id="payment_bill_attachments">
                                           <?php if(isset($student['doc_attachments']['Payments_Bills'])): ?>
                                           <div class='external_file_attachments'>
                                           <?php foreach($student['doc_attachments']['Payments_Bills'] as $data):?>
                                            <a href="<?php echo URLCustomer.$data['file_path'];?>" rel="prettyPhoto[pp_gal]">
                                                <embed src="<?php echo URLCustomer.$data['file_path'];?>" width="200"/>
                                           </a>
                                           <?php endforeach ?>
                                           </div>

                                           <?php endif ?>
                                        </div> 
                                        <div class="tab-pane fade" id="discharge_summary_attachments">
                                          <?php if(isset($student['doc_attachments']['Discharge_Summary']) ): ?>
                                          <div class='external_file_attachments'>
                                          <?php foreach($student['doc_attachments']['Discharge_Summary'] as $data):?>
                                          <a href="<?php echo URLCustomer.$data['file_path'];?>" rel="prettyPhoto[pp_gal]">
                                            <embed src="<?php echo URLCustomer.$data['file_path'];?>" width="200"/>
                                          </a>
                                          <?php endforeach ?>
                                          </div>

                                          <?php endif ?>
                                        </div>
                                        <div class="tab-pane fade" id="other_attachments">
                                          <?php if(isset($student['doc_attachments']['external_attachments'])): ?>
                                          <div class='external_file_attachments'>
                                          <?php foreach($student['doc_attachments']['external_attachments'] as $data):?>
                                          <a href="<?php echo URLCustomer.$data['file_path'];?>" rel="prettyPhoto[pp_gal]">
                                            <embed src="<?php echo URLCustomer.$data['file_path'];?>" width="200"/>
                                         </a>
                                          <?php endforeach ?>
                                          </div>

                                          <?php endif ?>
                                        </div>
                                    </div>
                                    
                                    <footer>
                                        <button type="button" class="btn btn-primary" onclick="window.history.back()">Back</button>
                                    </footer>
                                    
                                  
                                </form>                     
                                
                            </div>
                            <!-- end widget content -->
                            
                        </div>
                        <!-- end widget div -->
                        
                    </div>
                    <!-- end widget -->   
                    
                        </article>
                    
                        <?php endforeach; ?>
                </div>
                <br>
                    <br>
        </div>
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
                              <script src="<?php echo JS; ?>sweetalert.min.js"></script>
                              <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
                              <script src="https://code.highcharts.com/highcharts.js"></script>
                              <script src="https://code.highcharts.com/highcharts-more.js"></script>
                              <!-- <script src="<?php //echo JS; ?>mdb.min.js"></script> -->


                              <script>
                              $(document).ready(function() {
                                $("a[rel^='prettyPhoto']").prettyPhoto();
                              <?php if($this->session->flashdata('success')): ?>

                                      /* swal({
                                              title: "Good job!",
                                              text: "<?php //echo $this->session->flashdata('success'); ?>",
                                              icon: "success",
                                  
                                        });*/
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
                                    url: 'fetch_student_info',
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
                                        get_data = data.get_data;
                                        console.log('get_data',get_data);
                                        $.each(get_data, function() {
                                          $("#student_name").val(this['doc_data']['widget_data']['page1']['Personal Information']['Name']);
                                          
                                          $("#student_class").val(this['doc_data']['widget_data']['page2']['Personal Information']['Class']);
                                          $("#student_section").val(this['doc_data']['widget_data']['page2']['Personal Information']['Section']);
                                          
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
                            <!--   <script type="text/javascript">
                           $("#page1_StudentDetails_HospitalUniqueID").text(<?php //echo "string"; ?>)
                              </script> -->
                              
                              <?php 
                                //include footer
                                include("inc/footer.php"); 
                              ?>
