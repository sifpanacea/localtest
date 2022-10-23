<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Feed BMI";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa feed_bmi"]["sub"]["feed_bmi_student"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<style type="text/css">
  label
  {
    font-weight: bold;
    font-size: inherit
  }
  #web_view .invalid {
    color: red;
}
.logo
{
  margin-left:10px;
  float:left;
  height:123px;
  width:134px;
  background-repeat: no-repeat;
  background-size:100%;
  border:1px dashed lightgrey;
}
#student_photo
{
  width: 148px;
    height: 130px;
    border: 3px solid;
    border-color: green;
}
.swal-text
{
  text-align: center;
}
.student_code{
  text-transform: uppercase;
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
                    <article class="col-sm-12 col-md-12 col-lg-10">
                
                      <!-- Widget ID (each widget will need unique ID)-->
                      <div class="jarviswidget jarviswidget-color-orange" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false"    style="border: 2px solid #CB9235;">
                        
                        <header >
                          <!-- <span class="widget-icon"></span> --> 
                         <center> <h2>BODY MASS INDEX</h2></center>
                
                        </header>
                
                        <!-- widget div-->
                        <div>
                
                          <!-- widget edit box -->
                          <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                
                          </div>
                          <!-- end widget edit box -->
                
                          <!-- widget content -->
                          <div class="widget-body">
                
                          <?php  $attributes = array('class' => 'form-horizontal','id'=>'web_view','name'=>'userform');
                            echo  form_open_multipart('panacea_cc/feedBmiStudentReport',$attributes);
                            ?>
                            <fieldset>
                              <!-- <legend>Student Details</legend> -->
                              <div class="form-group row">
                               
                                  
                                  <label class="col-md-2"> UNIQUE ID:</label>
                                 <div class="col-md-6">
                               
                                    <input type="text" class=" form-control student_code" id="page1_StudentDetails_HospitalUniqueID" name="page1_StudentDetails_HospitalUniqueID" required> 
                                          </div>
                                <button type="button" class="btn btn-primary   col-md-3 retriever_search" id="searchIdBtn"  field_ref='page1_Personal Information_Hospital Unique ID'><i class="fa fa-search"> SEARCH</i></button>
                              </div>
                             
                              <div class="row">
                                <section class="col col-lg-8">

                               <div class="form-group">
                                <label class="col-md-3 ">NAME</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="page1_StudentDetails_Name" id="page1_StudentDetails_Name" readonly></div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-3 ">CLASS</label>
                                <div class="col-md-9">
                                   <input type="text" class="form-control" name="page1_StudentDetails_Class" id="page1_StudentDetails_Class" readonly></div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-3 ">SECTION</label>
                                <div class="col-md-9">
                                   <input type="text" class="form-control" name="page1_StudentDetails_Section" id="page1_StudentDetails_Section" readonly></div>
                                </div>
                                <div class="form-group">
                                <label class="col-md-3 ">DATE</label>
                                <div class="col-md-9">
                                   <input type="date" class="form-control" name="page1_StudentDetails_Date" value="<?php echo date("Y-m-d");?>" readonly></div>
                                </div>
                              </section>
                              <section class="col col-lg-2">
                                  <div id="student_image" class="col-md-offset-3"></div>
                                    <div id="image_logo"></div>
                                </section>
                              </div>

                              <div class="col-md-6">
                                  <ul id="myTab1" class="nav nav-tabs bordered">
                                  <li class="active">
                                  <a href="#cms" data-toggle="tab" id="height-cms" style="color: #0f0e0e!important">HEIGHT Cms</a>
                                  </li>
                                 <li>
                                 <a href="#foots" data-toggle="tab" id="height-foots" style="color: #0f0e0e!important">HEIGHT Feets</a>
                               </li>
                                <li>
                                 <a href="#inchs" data-toggle="tab" id="height-inchs" style="color: #0f0e0e!important">HEIGHT Inchs</a>
                               </li> 
                                </ul>
                                
                               <div id="myTabContent1" class="tab-content padding-10">
                                 <input type = "number" name= "page1_StudentDetails_Heightcms" class = "form-control tab-pane fade in active" id = "cms" placeholder = "Enter only Cms(Eg:155)">
                                 <input type = "number" class = "form-control height-foots tab-pane fade" id = "foots" placeholder = "Enter only foots(Eg:5.5)">
                                 <input type = "number" class = "form-control height-inchs tab-pane fade" id = "inchs"  placeholder = "Enter only inchs(Eg:50)">
                               </div>
                                </div>

                               <div class="col-md-6">
                                  <label class="col-md-3">WEIGHT (kgs)</label>
                                   <input type="number" class="form-control height-weight col-md-2" name="page1_StudentDetails_Weightkgs" id="page1_StudentDetails_Weightkgs">
                                
                                   <label class="col-md-3">BMI</label>
                                    <input type="text" class="form-control col-md-3" name="page1_StudentDetails_BMI" id="page1_StudentDetails_BMI" readonly>
                                 
                                    <button class="btn btn-success col-md-3 submit" type="submit">
                                      <i class="fa fa-save"></i>
                                      SUBMIT
                                    </button>
                                </div> 
                        
                              
                              </fieldset>
                              
                            <!--   <div class="form-actions">
                                <div class="row">
                                  <div class="col-md-12">
                                   
                                    <button class="btn btn-success " type="submit">
                                      <i class="fa fa-save"></i>
                                      Submit
                                    </button>
                                  </div>
                                </div>
                              </div> -->
                
                        
                          <?php echo form_close(); ?><div id="reasons">
                
                          </div>
                          <!-- end widget content -->
                
                        </div>
                        <!-- end widget div -->
                
                      </div>
                      <!-- end widget -->
                
                
                    </article>
            
        
    </div>
</div>

<!-- Modal -->
          

<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
  //include required scripts
  include("inc/scripts.php"); 
?>
<script src="<?php echo JS; ?>sweetalert.min.js"></script>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



<script>
$(document).ready(function() {
<?php if($this->session->flashdata('success')): ?>

         swal({
                title: "Good job!",
                text: "<?php echo $this->session->flashdata('success'); ?>",
                icon: "success",
    
          });
       <?php elseif($this->session->flashdata('fail')): ?>
       swal({
                title: "Failed!",
                text: "<?php echo $this->session->flashdata('fail'); ?>",
                icon: "error",
    
          });
<?php endif; ?>


  $("#page1_StudentDetails_Heightcms").on("keypress", function(evt) {
      var keycode = evt.charCode || evt.keyCode;
      if (keycode == 46) {
        alert("Please enter Height in cms format(eg:155)");
      return false;
      }
    });

  $('#height-cms').click(function(){
    $('#cms').attr('name',"page1_StudentDetails_Heightcms");
    $("#foots").val("");
    $("#inchs").val("");
    $("#foots").attr('name',"");
    $("#inchs").attr('name',"");
  });

  $('#height-foots').click(function(){
    $('#foots').attr('name',"page1_StudentDetails_Heightfoots");
    $("#cms").val("");    
    $("#inchs").val("");
    $("#cms").attr('name',"");
    $("#inchs").attr('name',"");
  });

  $('#height-inchs').click(function(){
    $("#inchs").attr('name','page1_StudentDetails_Heightinchs');
    $("#foots").val("");
    $("#cms").val("");
    $("#foots").attr('name',"");
    $("#cms").attr('name',"");
  });


 $('.height-weight').keyup(function (){

    var height_cms = parseFloat($('#cms').val()) || 0;
    var height_foots = parseFloat($('#foots').val()) || 0;
    var height_inchs = parseFloat($('#inchs').val()) || 0;
    var weight = parseFloat($('#page1_StudentDetails_Weightkgs').val()) || 0;

      if( height_cms !=0 && weight !=0 || height_foots !=0 && weight !=0 || height_inchs !=0 && weight !=0)
      {
         var foots_to_cms =  (height_foots * 30.48).toFixed(0);
         var inchs_to_cms =  (height_inchs * 2.54).toFixed(0);
        if(foots_to_cms != 0)
        {       
          var height_it = (foots_to_cms/100);      
        }
        else if(inchs_to_cms != 0)
        {
           var height_it = (inchs_to_cms/100);
        }else if(height_cms !=0 )
        {
           var height_it = (height_cms/100);
        }
        
         $("#page1_StudentDetails_BMI").val(parseFloat(weight / (height_it * height_it)).toFixed(1));
        
      }
      else{
        $("#page1_StudentDetails_BMI").val("");
      }

  });

 //setting unique id value during submission //
    $(document).on('click','.submit',function(e)
    {
     //loading  buttion in jquery
       if (($.trim($('#page1_StudentDetails_HospitalUniqueID').val()).length > 0) && ($.trim($('#page1_StudentDetails_Heightcms').val()).length > 0)  &&($.trim($('#page1_StudentDetails_Weightkgs').val()).length > 0)  )
                {
                  var height = parseFloat($('#page1_StudentDetails_Heightcms').val()) || 0;
                  var weight = parseFloat($('#page1_StudentDetails_Weightkgs').val()) || 0;

                    var height_cms = parseFloat($('#cms').val()) || 0;
                    var height_foots = parseFloat($('#foots').val()) || 0;
                    var height_inchs = parseFloat($('#inchs').val()) || 0;
                    var weight = parseFloat($('#page1_StudentDetails_Weightkgs').val()) || 0;

                      if( height_cms !=0 && weight !=0 || height_foots !=0 && weight !=0 || height_inchs !=0 && weight !=0)
                      {
                         var foots_to_cms =  (height_foots * 30.48).toFixed(0);
                         var inchs_to_cms =  (height_inchs * 2.54).toFixed(0);
                        if(foots_to_cms != 0)
                        {       
                          var height_it = (foots_to_cms/100);      
                        }
                        else if(inchs_to_cms != 0)
                        {
                           var height_it = (inchs_to_cms/100);
                        }else if(height_cms !=0 )
                        {
                           var height_it = (height_cms/100);
                        }
                        
                         $("#page1_StudentDetails_BMI").val(parseFloat(weight / (height_it * height_it)).toFixed(1));
                        
                      }
                      else{
                        $("#page1_StudentDetails_BMI").val("");
                      }
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
    var uniqueId = $('#page1_StudentDetails_HospitalUniqueID').val();
  
    $.ajax({
      url: 'fetch_student_info',
      type: 'POST',
      data: {'page1_StudentDetails_HospitalUniqueID':uniqueId },
      success:function(data){
       
     if(data == 'NO_DATA_AVAILABLE')
        {

            var uniqueIdField = $("input#page1_StudentDetails_HospitalUniqueID").val();
            $('#web_view').trigger('reset');
            $("input#page1_StudentDetails_HospitalUniqueID").val(uniqueIdField);

             swal("Info !", "No student deatils available for this Unique ID: " + uniqueId);
      
        }
        else{

          data = $.parseJSON(data);
          get_data = data.get_data;
          console.log('get_data',get_data);
          $.each(get_data, function() {
            $("#page1_StudentDetails_Name").val(this['doc_data']['widget_data']['page1']['Personal Information']['Name']);
            
            $("#page1_StudentDetails_Class").val(this['doc_data']['widget_data']['page2']['Personal Information']['Class']);
            $("#page1_StudentDetails_Section").val(this['doc_data']['widget_data']['page2']['Personal Information']['Section']);
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
                      page1_StudentDetails_Name:{ required:true,minlength:1,maxlength:123},
                      page1_StudentDetails_Class:{minlength:1,maxlength:123},
                      page1_StudentDetails_Section:{minlength:1, maxlength:123},
                      page1_StudentDetails_Heightcms:{required:true, minlength:3, maxlength:3},
                      page1_StudentDetails_Heightfoots:{required:true,maxlength:3},
                      page1_StudentDetails_Heightinchs:{required:true,maxlength:3},
                      page1_StudentDetails_Weightkgs:{required:true,minlength:1,maxlength:4},
                      page1_StudentDetails_BMI:{required:true,minlength:1,maxlength:8},
                      page1_StudentDetails_Date:{required:false},
                    },
       
       //Messages for form validation
          messages : {

                        page1_StudentDetails_HospitalUniqueID:{required:"Hospital Unique ID field is required"},
                        page1_StudentDetails_Name:{required:"Name  field is required"},
                        page1_StudentDetails_Class:{},
                        page1_StudentDetails_Section:{},
                        page1_StudentDetails_Heightcms:{required:"Height cms field is required only 3 digits"},
                        page1_StudentDetails_Heightfoots:{required:"Height foots field is required only 3 digits"},
                        page1_StudentDetails_Heightinchs:{required:"Height inchs field is required only 3 digits"},
                        page1_StudentDetails_Weightkgs:{required:"Weight kgs field is required"},
                        page1_StudentDetails_BMI:{required:"BMI field is required"},
                        },onkeyup: false, //turn off auto validate whilst typing
                                    // Do not change code below
                                          errorPlacement : function(error, element) {
                                            error.insertAfter(element.parent());
                                    }
                          });

            });

});
</script>
<?php 
  //include footer
  include("inc/footer.php"); 
?>