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
$page_nav["Hemoglobin Percentage"]["active"] = true;
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
                        <header>
                          <span class="widget-icon"> <i class="fa fa-eye"></i> </span>
                          <h2>Hemoglobin Pecentage</h2>
                
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
                            echo  form_open_multipart('bc_welfare_schools/hemoglobin_submit',$attributes);
                            ?>
                            
                            <fieldset>
                              <!-- <legend>Student Details</legend> -->
                              <div class="form-group">
                                <label class="col-md-2 control-label">Unique_Id</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="page1_StudentDetails_HospitalUniqueID" name="page1_StudentDetails_HospitalUniqueID" value="<?php echo $district_code."_".$school_code."_";?>" required></div>
                                    <div id="output"></div>
                                  <button class="col-md-3 btn btn-primary" id="searchIdBtn">search</button>
                              </div>
                              <div class="row">
                                <section class="col col-lg-8">

                               <div class="form-group">
                                <label class="col-md-3 control-label">Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="page1_StudentDetails_Name" name="page1_StudentDetails_Name" readonly></div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-3 control-label">Class</label>
                                <div class="col-md-9">
                                   <input type="text" class="form-control" id="page1_StudentDetails_Class" name="page1_StudentDetails_Class" readonly></div>
                                </div>

                                <div class="form-group">
                                <label class="col-md-3 control-label">Section</label>
                                <div class="col-md-9">
                                   <input type="text" class="form-control" id="page1_StudentDetails_Section" name="page1_StudentDetails_Section" readonly></div>
                                </div>
                              </section>
                              <section class="col col-lg-2">
                                <div >
                                  <img src="" alt="hi"/>
                                </div>
                                </section>
                              </div>

                                <div class="form-group">
                                <label class="col-md-2 control-label">Blood Group</label>
                                <div class="col-md-3">
                                 <input type="text" class="form-control" name="page1_StudentDetails_Heightcms"></div>
                               
                                <label class="col-md-1 control-label">HB</label>
                                <div class="col-md-2">
                                   <input type="number" class="form-control" name="page1_StudentDetails_Weightkgs"></div>
                                </div>

                                <!-- <div class="form-group">
                                <label class="col-md-2 control-label">BMI</label>
                                <div class="col-md-10">
                                    <input type="number" class="form-control" name="page1_StudentDetails_BMI"></div>
                                </div>  -->

                                <div class="form-group">
                                <label class="col-md-2 control-label">Date</label>
                                <div class="col-md-10" >
                                   <input type="text" class="form-control" id="date_ss" readonly="readonly"></div>
                                </div>
                  
                        
                              
                              </fieldset>
                              
                              <div class="form-actions">
                                <div class="row">
                                  <div class="col-md-6">
                                    <button class="btn btn-default">
                                      Cancel
                                    </button>
                                    <button class="btn btn-primary" type="submit">
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
<script src="<?php echo JS; ?>toastr.min.js"></script>


<script>
$(document).ready(function() {

  //toastr.info('Toastr Included');

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
page1_StudentDetails_Heightcms:{required:true,minlength:1,maxlength:4},
page1_StudentDetails_Weightkgs:{required:true,minlength:1,maxlength:4},
 page1_StudentDetails_BMI:{minlength:1,maxlength:5}
// page1_StudentDetails_Date:{required:false},
},
  
  //Messages for form validation
messages : {page1_StudentDetails_HospitalUniqueID:{required:"Hospital Unique ID field is required"},
page1_StudentDetails_Name:{},
page1_StudentDetails_Class:{},
page1_StudentDetails_Section:{},
page1_StudentDetails_Heightcms:{required:"Height cms field is required"},
page1_StudentDetails_Weightkgs:{required:"Weight kgs field is required"},
page1_StudentDetails_BMI:{},
},onkeyup: false, //turn off auto validate whilst typing
// Do not change code below
           errorPlacement : function(error, element) {
           error.insertAfter(element.parent());
}
});

});
});
</script>
<script type="text/javascript">
  //date functionality 
  

    var date = new Date();
    
  document.getElementById('date_ss').value = date.toLocaleDateString();
    
    
  
</script>
<script type="text/javascript">
  var readOnlyLength = $('#page1_StudentDetails_HospitalUniqueID').val().length;

// $('#output').text(readOnlyLength);

$('#page1_StudentDetails_HospitalUniqueID').on('keypress, keydown', function(event) {
  var $field = $(this);
  // $('#output').text(event.which + '-' + this.selectionStart);
  if ((event.which != 37 && (event.which != 39)) &&
    ((this.selectionStart < readOnlyLength) ||
      ((this.selectionStart == readOnlyLength) && (event.which == 8)))) {
    return false;
  }
});
// search button 


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

            toastr.error('NO_DATA_AVAILABLE');

            toastr.options = {
              "closeButton": false,
              "debug": false,
              "newestOnTop": false,
              "positionClass": "center",
              "preventDuplicates": false,
              "onclick": null,
              "showDuration": "300",
              "hideDuration": "1000",
              "timeOut": "5000",
              "extendedTimeOut": "1000",
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            }
      
        }
        else{

          data = $.parseJSON(data);
          get_data = data.get_data;
          console.log('get_data',get_data);
          $.each(get_data, function() {
            $("#page1_StudentDetails_Name").val(this['doc_data']['widget_data']['page1']['Personal Information']['Name']);
            
            $("#page1_StudentDetails_Class").val(this['doc_data']['widget_data']['page2']['Personal Information']['Class']);
            $("#page1_StudentDetails_Section").val(this['doc_data']['widget_data']['page2']['Personal Information']['Section']);
            if(this['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'])
            {
              $('<img/>')
                      .attr('src', "<?php echo URLCustomer;  ?>" + this['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'] + "").css({"height":"200 px","width" : "100px"})
                      
                      .val($('#student_image'));
                      // $('#image_logo').show();
                     
            }

            else {
                   //$('#image_logo').load(location.href + ' #image_logo');
               // $('#image_logo').show();
              $('#image_logo').text( "No Image Found").css({"color":"red","text-align":"center"});
                // $('#student_image').remove();
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
</script>



<?php 
  //include footer
  include("inc/footer.php"); 
?>