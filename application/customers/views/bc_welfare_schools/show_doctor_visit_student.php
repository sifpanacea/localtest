    <?php

    //initilize the page
    //require_once("inc/init.php");

    //require UI configuration (nav, ribbon, etc.)
    require_once("inc/config.ui.php");

    /*---------------- PHP Custom Scripts ---------

    YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
    E.G. $page_title = "Custom Title" */

    $page_title = "Show student Doctor visit";

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
                          <div class="jarviswidget jarviswidget-color-orange" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" style="border: 2px solid #CB9235;">
                            
                            <header><center>
                              <span class="widget-icon"> <i class="fa fa-pencil-square"></i> </span>
                              <h2>Doctor Checkup</h2>
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
                                echo  form_open_multipart('tswreis_schools/show_doctor_treated_student',$attributes);
                                ?>
                                
                                <fieldset>
                                  <?php foreach ($unique_id as $student): ?>
                                   

                                <div class="row">
                                  <div class="form-group">
                                    <label class="col-md-2">&ensp; UNIQUE ID:</label>
                                     <input type="text" class="col-md-2 student_code" id="page1_StudentDetails_HospitalUniqueID" name="page1_StudentDetails_HospitalUniqueID" value="<?php echo $student['doc_data']['widget_data']['Student Details']['Hospital Unique ID'];  ?>" readonly>   

                                    
                                  
                                  
                                  
                                  </div>
                                </div>
                                  <div class="row">
                                    <section class="col col-lg-8">

                                   <div class="form-group">
                                    <label class="col-md-3 ">NAME:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="student_name" name="student_name" value="<?php echo $student['doc_data']['widget_data']['Student Details']['Name'];  ?>" readonly></div>
                                    </div>

                                    <div class="form-group">
                                    <label class="col-md-3 ">CLASS:</label>
                                    <div class="col-md-9">
                                       <input type="text" class="form-control" id="student_class" name="student_class" value="<?php echo $student['doc_data']['widget_data']['Student Details']['Class'];  ?>" readonly></div>
                                    </div>

                                    <div class="form-group">
                                    <label class="col-md-3 ">SECTION:</label>
                                    <div class="col-md-9">
                                       <input type="text" class="form-control" id="student_section" name="student_section" value="<?php echo $student['doc_data']['widget_data']['Student Details']['Section'];  ?>" readonly></div>
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
                                       <input type="date" class="form-control" id="doctor_visiting_date" name="doctor_visiting_date" value="<?php echo $student['doc_data']['widget_data']['Student Details']['doctor_visiting_date'] ?>" readonly="readonly"></div>
                                    </div>

                                    
                                    <div class="form-group">
                                      <label class="col-md-2">Remarks:</label>
                                    <div class="col-md-6" >
                                       <textarea class="form-control" name="remarks" readonly placeholder="Textarea" rows="4"><?php echo $student['doc_data']['widget_data']['Student Details']['remarks'];?></textarea></div>
                                    </div>
                                    
                                    </div>
                                   
                                      <input type="text" class="hide" id='doc_id' rel='doc_id' name='doc_id' value="<?php echo set_value('doc_id',(isset($student['doc_properties']['doc_id']) && !empty($student['doc_properties']['doc_id']))) ?  $student['doc_properties']['doc_id'] :  "" ;?>">
                
              
                       <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading  text-center"><strong>Attachments</strong>
                            </div>
                            
          <ul class="nav nav-tabs md-tabs" style="background:#DB7093; " role="tablist">
            <li class="nav-item">

              <a class="nav-link" data-toggle="tab" href="#panel668" role="tab">
              <!-- <i class="fa fa-heart pr-2"> </i>-->Prescriptions
              <?php if(count($student['doc_attachments']['Prescriptions']) > 0): ?>

              <span class="badge bg-color-green"><?php echo count($student['doc_attachments']['Prescriptions']); ?></span>
          <?php  endif; ?>
          </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#panel555" role="tab">
              <!-- <i class="fa fa-user pr-2"></i> -->Lab Reports
              <?php if(count($student['doc_attachments']['Lab_Reports']) >0): ?>
              <span class="badge bg-color-green"><?php echo count($student['doc_attachments']['Lab_Reports']); ?></span>
              <?php endif; ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#panel666" role="tab">
              X-ray/MRI/Digital Images
              <?php if(count($student['doc_attachments']['Digital_Images']) >0): ?>
              <span class="badge bg-color-green"><?php echo count($student['doc_attachments']['Digital_Images']); ?></span>
              <?php endif; ?></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#panel667" role="tab">
              <?php if(count($student['doc_attachments']['Payments_Bills']) >0): ?>
              <span class="badge bg-color-green"><?php echo count($student['doc_attachments']['Payments_Bills']); ?></span>
              <?php endif; ?>Payments/Bills</a>
            </li>
             <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#panel669" role="tab">
              <?php if(count($student['doc_attachments']['Discharge_Summary']) >0): ?>
              <span class="badge bg-color-green"><?php echo count($student['doc_attachments']['Discharge_Summary']); ?></span>
              <?php endif; ?>Discharge Summary</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#panel770" role="tab">
              <?php if(count($student['doc_attachments']['external_attachments']) >0): ?>
              <span class="badge bg-color-green"><?php echo count($student['doc_attachments']['external_attachments']); ?></span>
              <?php endif; ?>Others</a>
            </li>
          </ul>
          <!-- Nav tabs -->

          <!-- Tab panels -->
          <div class="tab-content">

            <!-- Panel 1 -->
            <div class="tab-pane fade" id="panel555" role="tabpanel">

              <!-- Nav tabs --><br>
              <?php if(isset($student['doc_attachments']['Lab_Reports'])): ?>
                      <div class='external_file_attachments'>
                      <?php foreach($student['doc_attachments']['Lab_Reports'] as $data):?>
                      
                     
                      <embed src="<?php echo URLCustomer.$data['file_path'];?>" width="200"/>
                      <?php endforeach ?>
                      </div>
              
                      <?php endif ?>
              <input type="file" id="files_labs"  name="Lab_Reports[]" style="display:none;" multiple/>
                                   
                 <label for="files_labs" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                   Labs Reports.....
               </label>

                                     

            </div>
            <!-- Panel 1 -->

            <!-- Panel 2 -->
            <div class="tab-pane fade" id="panel666" role="tabpanel" >

              <br>
             
                      <?php if(isset($student['doc_attachments']['Digital_Images'])): ?>
                      <div class='external_file_attachments'>
                      <?php foreach($student['doc_attachments']['Digital_Images'] as $data):?>
                      
                     
                      <embed src="<?php echo URLCustomer.$data['file_path'];?>" width="200"/>
                      <?php endforeach ?>
                      </div>
              
                      <?php endif ?>
                      <input type="file" id="files_xray"  name="Digital_Images[]" style="display:none;" multiple>
              <label for="files_xray" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                 X-ray/MRI/ Digital Images.....

              </label>

            </div>
            <!-- Panel 2 -->
            <!-- Panel 2 -->
            <div class="tab-pane fade" id="panel667" role="tabpanel">
              
              <?php if(isset($student['doc_attachments']['Payments_Bills'])): ?>
                      <div class='external_file_attachments'>
                      <?php foreach($student['doc_attachments']['Payments_Bills'] as $data):?>
                      
                     
                      <embed src="<?php echo URLCustomer.$data['file_path'];?>" width="200"/>
                      <?php endforeach ?>
                      </div>
              
                      <?php endif ?>
           
              <input type="file" id="files_bills"  name="Payments_Bills[]" style="display:none;" multiple>
                                   
              <label for="files_bills" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                 Payments Bills attachments.....
              </label>

            </div>
            <div class="tab-pane fade" id="panel669" role="tabpanel">
              <?php if(isset($student['doc_attachments']['Discharge_Summary']) ): ?>
                      <div class='external_file_attachments'>
                      <?php foreach($student['doc_attachments']['Discharge_Summary'] as $data):?>
                      
                     
                      <embed src="<?php echo URLCustomer.$data['file_path'];?>" width="200"/>
                      <?php endforeach ?>
                      </div>
              
                      <?php endif ?>
           
              <input type="file" id="files_ds"  name="Discharge_Summary[]" style="display:none;" multiple>
                                   
              <label for="files_ds" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                 Discharge Summary.....
              </label>

            </div>
            <!-- Panel 2 -->
            <!-- Panel 2 -->
            <div class="tab-pane fade" id="panel668" role="tabpanel">
              
            <?php if(isset($student['doc_attachments']['Prescriptions'])): ?>
                      <div class='external_file_attachments'>
                      <?php foreach($student['doc_attachments']['Prescriptions'] as $data):?>
                      
                     
                      <embed src="<?php echo URLCustomer.$data['file_path'];?>" width="200"/>
                      <?php endforeach ?>
                      </div>
              
                      <?php endif ?>
              <input type="file" id="files_prescriptions"  name="Prescriptions[]" style="display:none;" multiple>
                                   
              <label for="files_prescriptions" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                 Prescriptions.....
              </label>

            </div>
             <div class="tab-pane fade"  id="panel770" role="tabpanel">

           <?php if(isset($student['doc_attachments']['external_attachments'])): ?>
                      <div class='external_file_attachments'>
                      <?php foreach($student['doc_attachments']['external_attachments'] as $data):?>
                      
                     
                      <embed src="<?php echo URLCustomer.$data['file_path'];?>" width="200"/>
                      <?php endforeach ?>
                      </div>
              
                      <?php endif ?>
             <input type="file" id="files"  name="hs_req_attachments[]" style="display:none;" multiple>
                                   
              <label for="files" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                 Others.....
              </label>

            </div>
            <!-- Panel 2 -->

          </div>
        <!-- Tab panels -->

                          
                   
                          </div>
                        
                        </fieldset>
                            <?php endforeach; ?>
                          
                                 
                            <!-- <div class="form-group">
                                    <button class="col-md-offset-3 btn btn-success col-md-3 submit" type="submit" id="submit">
                                          <i class="fa fa-save"></i>
                                          SUBMIT
                                        </button>
                                    </div> -->
                                    

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
                              <script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>
                              <!-- <script src="<?php //echo JS; ?>mdb.min.js"></script> -->


<script>
$(document).ready(function() {
    <?php if($this->session->flashdata('success')): ?>
          toastr.success("<?php echo $this->session->flashdata('success'); ?>","Success");
          <?php elseif($this->session->flashdata('fail')): ?>
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
        $('.external_file_attachments').children().each(function (index)
        {
          var href_ = $(this).attr("href")

          var name_ = $(this).attr("name")
          var in_val = index+1;
          if(in_val%2==0)
          {
          
          $("<span style=\"height:55px;\"><b class=\"ind_val\"></b><b class=\"word_break\"><a href="+href_+" rel='prettyPhoto[gal]'>&nbsp;<img class=\"img-thumbnail\" src=\"" + href_ + "\" style =\"width:150px;height:150px;\" title=\"" + name_ + "\"/>&nbsp;</a></b></span>").appendTo('.files_attached');
          
          }
          else
          {
          

          $("<span class=\"active\" style=\"height:55px;\"><b class=\"ind_val\"></b><b class=\"word_break\"><a href="+href_+" rel='prettyPhoto[gal]'>&nbsp;<img class=\"img-thumbnail\" src=\"" + href_ + "\" style =\"width:150px;height:150px;\" title=\"" + name_ + "\"/> &nbsp;</a></b></span>").appendTo('.files_attached');

          
          }
        })
        $("a[rel^='prettyPhoto']").prettyPhoto();
        if($('.files_attached').children('span').length==0)
        {
          $('<span class="" style="height:55px;"><b class=""><h1><b>No external files attached<b></h1></b></span>').appendTo('.files_attached');  
        }

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
                              
                                
      <script type="text/javascript">
        $("a[rel^='prettyPhoto']").prettyPhoto();
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
   <!--  <script type="text/javascript">
 $("#page1_StudentDetails_HospitalUniqueID").text(<?php //echo "string"; ?>)
    </script> -->
    
    <?php 
      //include footer
      include("inc/footer.php"); 
    ?>