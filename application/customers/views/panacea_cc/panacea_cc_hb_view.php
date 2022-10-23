<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Hemoglobin Percentage";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["hb_reports"]["sub"]["hemoglobin_view"]["active"] = true;
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
    border: 3px solid;
    border-color: green;
}
.invalid{
  color: red; 
}
.swal-text
{
  text-align: center;
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
                    <article class="col-sm-12 col-md-12 col-lg-10">
                
                      <!-- Widget ID (each widget will need unique ID)-->
                      <div class="jarviswidget jarviswidget-color-orange" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" style="border: 2px solid #CB9235;">
                       
                        <header><center>
                          <span class="widget-icon"> <i class="fa fa-pencil-square"></i> </span>
                          <h2>Hemoglobin Pecentage</h2>
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
                            echo  form_open_multipart('panacea_cc/create_hemoglobin_report',$attributes);
                            ?>
                            
                            <fieldset>
                              <!-- <legend>Student Details</legend> -->
                              
                              <div class="form-group row">
                                <label class="col-md-2">UNIQUE ID:</label>
                                <div class="col-md-6">
                                 
                            
                                    <input type="text" class="col-md-2 student_code form-control" id="page1_StudentDetails_HospitalUniqueID" name="page1_StudentDetails_HospitalUniqueID" required  style=" text-transform: uppercase;">  
                                      </div>
                                <button type="button" class="btn btn-primary   col-md-3 retriever_search" id="searchIdBtn"  field_ref='page1_Personal Information_Hospital Unique ID'><i class="fa fa-search"> SEARCH</i></button>
                              
                              
                              
                              </div>
                            
                              <div class="row">
                                <section class="col col-lg-8">

                               <div class="form-group">
                                <label class="col-md-3 ">NAME:</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="page1_StudentDetails_Name" name="page1_StudentDetails_Name" readonly></div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-3 ">CLASS:</label>
                                <div class="col-md-9">
                                   <input type="text" class="form-control" id="page1_StudentDetails_Class" name="page1_StudentDetails_Class" readonly></div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-3 ">SECTION:</label>
                                <div class="col-md-9">
                                   <input type="text" class="form-control" id="page1_StudentDetails_Section" name="page1_StudentDetails_Section" readonly></div>
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
                                   <input type="date" class="form-control" id="page1_StudentDetails_Date" name="page1_StudentDetails_Date" value="<?php echo date('Y-m-d'); ?>" readonly="readonly"></div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-2 ">BLOOD GROUP:</label>
                                <div class="col-md-3">
                                 <!-- <input type="text" class="form-control" name="page1_StudentDetails_bloodgroup"> -->
                                  <input list="blood groups" class="form-control" name="page1_StudentDetails_bloodgroup" id="page1_StudentDetails_bloodgroup">
                                      <datalist id="blood groups" value="select browser" >
                                        <option value="A+">A+</option>
                                        <option value="B+">B+</option>
                                        <option value="AB+">AB+</option>
                                        <option value="O+">O+</option>
                                        <option value="A-">A-</option>
                                        <option value="B-">B-</option>
                                        <option value="AB-">AB-</option> 
                                        <option value="O-">O-</option>
                                      </datalist>
                                 </div>
                               
                                <label class="col-md-1 ">HB:</label>
                                <div class="col-md-2">
                                   <input type="number" class="form-control" name="page1_StudentDetails_HB"  id="page1_StudentDetails_HB"></div>
                                   <button class="btn btn-success col-md-3 submit" type="submit" id="submit">
                                      <i class="fa fa-save"></i>
                                      SUBMIT
                                    </button>
                                </div>

                             
                        
                              
                              </fieldset>
                              
                        
                
                       
                          <?php echo form_close(); ?><div id="reasons">
                
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
<script src="<?php echo JS; ?>sweetalert.min.js"></script>


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

       //setting unique id value during submission //
    $(document).on('click','.submit',function(e)
    {
        if (($.trim($('#page1_StudentDetails_HospitalUniqueID').val()).length > 0) && ($.trim($('#page1_StudentDetails_bloodgroup').val()).length > 0)  &&($.trim($('#page1_StudentDetails_HB').val()).length > 0)  )
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

$('#searchIdBtn').click(function (){


     var uniqueId = $('#page1_StudentDetails_HospitalUniqueID').val();



   /* var district_school_code = $('.unique_id').text() || '';
    var student_code = $('.student_code').val() || '';
    var dist_school_unique_code = ""+district_school_code+""+student_code+"";
    console.log('dist_school_unique_code',dist_school_unique_code);*/
  
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
page1_StudentDetails_Name:{minlength:1,maxlength:123},
page1_StudentDetails_Class:{minlength:1,maxlength:123},
page1_StudentDetails_Section:{minlength:1,maxlength:123},
page1_StudentDetails_bloodgroup:{required:true,minlength:1,maxlength:4},
page1_StudentDetails_HB:{required:true,minlength:1,maxlength:4},
 page1_StudentDetails_BMI:{minlength:1,maxlength:5}
// page1_StudentDetails_Date:{required:false},
},
  
  //Messages for form validation
messages : {page1_StudentDetails_HospitalUniqueID:{required:"Hospital Unique ID field is required"},
page1_StudentDetails_Name:{},
page1_StudentDetails_Class:{},
page1_StudentDetails_Section:{},
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



<?php 
  //include footer
  include("inc/footer.php"); 
?>