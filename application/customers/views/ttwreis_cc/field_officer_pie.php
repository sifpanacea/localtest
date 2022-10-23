

 <?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Field Officer Pie";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["field_officer_form"]["sub"]["field_officer_pie"]["active"] = true;
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
                        <header><center>
                          <span class="widget-icon"> <i class="fa fa-pencil-square"></i> </span>
                          <h2>Field Officer Pie</h2>
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
                
                         <!--  <?php  $attributes //= array('class' => 'form-horizontal','id'=>'web_view','name'=>'userform');
                            //echo  form_open_multipart('tswreis_schools/create_hemoglobin_report',$attributes);
                            ?> -->
                            
                            <fieldset>
                              <!-- <legend>Student Details</legend> -->
                              <div class="form-group">
												<div class="input-group">
												<input type="text" id="set_data" name="set_data" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo $today_date ?>">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												</div>
											</div>
                      </fieldset>
                              
                        
                
                          <!-- <?php //echo form_hidden('student_code','');?>
                          <?php //echo form_close(); ?><div id="reasons"> -->
                
                          </div>
                          <!-- end widget content -->
                
                        </div>
                        <!-- end widget div -->
                
                      </div>
                      <!-- end widget -->
                
                	<div id="pie_field_officer"></div>
                    </article>
                    <!-- WIDGET END -->
                    <form style="display: hidden" action="drill_down_to_field_officer_reports" method="POST" 
                    id="ehr_data_form">
                        <input type="hidden" id="selected_case" name="selected_case" value=""/>
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
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
<script src="<?php echo JS; ?>sweetalert.min.js"></script>


<script src="https://code.highcharts.com/highcharts.js"></script>


<script>
$(document).ready(function() {

	$('.datepicker').datepicker({
	minDate: new Date(1900, 10 - 1, 25)
 });
	var today_date = $('#set_data').val();

	
 		$(".datepicker").change(function(){
 			var today_date = $("#set_data").val();
 			
 			$.ajax({
                  url: 'fetch_field_officer_reports',
                  type: 'POST',
                  data: {'today_date':today_date },
                  success:function(data){
                  
                 	if(data == 'NO_DATA_AVAILABLE')
                    {

                     
                    }
                    else{

                      data = $.parseJSON(data);
                     
                  	console.log('get_data',data);
                
					         draw_field_officer_pie(data);


                    }

                    

                  },
                  error:function(XMlHttpRequest, textStatus, errorThrown) {
                    console.log('error',errorThrown);

                  }
                })
 		});            

                        function today_field_officer_submit(){
      var today_date = $("#set_data").val();
      
      $.ajax({
                  url: 'fetch_field_officer_reports',
                  type: 'POST',
                  data: {'today_date':today_date },
                  success:function(data){
                  
                  if(data == 'NO_DATA_AVAILABLE')
                    {

                     
                    }
                    else{

                      data = $.parseJSON(data);
                     
                    console.log('get_data',data);
                
                   draw_field_officer_pie(data);


                    }

                    

                  },
                  error:function(XMlHttpRequest, textStatus, errorThrown) {
                    console.log('error',errorThrown);

                  }
                })
    }

                            today_field_officer_submit();
                            function draw_field_officer_pie(data){
                              


                            var today_date = $("#set_data").val();
                                // Build the chart
                            Highcharts.chart('pie_field_officer', {
                                chart: {
                                    plotBackgroundColor: null,
                                    plotBorderWidth: null,
                                    plotShadow: false,
                                    type: 'pie'
                                },
                                title: {
                                    text: 'Field Officer Report'+" "+today_date
                                },
                                tooltip: {
                                    pointFormat: '{series.name}: <b>{point.y}</b>'
                                },
                                plotOptions: {
                                    pie: {
                                        allowPointSelect: true,
                                        cursor: 'pointer',
                                        dataLabels: {
                                            enabled: false
                                        },
                                        showInLegend: true
                                    }
                                },
                                series: [{
                                    name: 'Filed officer submitted students',
                                    colorByPoint: true,
                                    data: data,
                                    point: {
                                            events: {
                                               

                                                  click: function (event) {
                                                      
                                                    $('#selected_case').val(this.name);
                                                    $('#selected_date').val(today_date);
                                                    $('#ehr_data_form').submit();

                                                  }
                                            }
                                        }
                                }]
                            });
                      }
                              

                });
</script>



<?php 
  //include footer
  include("inc/footer.php"); 
?>