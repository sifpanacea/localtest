<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "HB PIE";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder 
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa hb"]["sub"]["hb_gender wise"]["active"] = true;
include("inc/nav.php");

?>

  <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo(MDB_PLUGINS.'bootstrap/css/bootstrap.css'); ?>" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo(MDB_PLUGINS.'node-waves/waves.css'); ?>" rel="stylesheet" />

    <!-- Animation Css -->
  
    <link href="<?php echo(MDB_PLUGINS.'animate-css/animate.css'); ?>" rel="stylesheet">

    <!-- JQuery DataTable Css -->
    <link href="<?php echo(MDB_PLUGINS.'jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css'); ?>" rel="stylesheet">   

    <!-- Morris Chart Css-->
    <link href="<?php echo(MDB_PLUGINS.'morrisjs/morris.css'); ?>" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo(MDB_CSS.'style.css'); ?>" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo(MDB_CSS.'themes/all-themes.css'); ?>" rel="stylesheet" />
<style>

</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->

<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
	include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">		
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-md-12 col-lg-12">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					
					<div class="jarviswidget" id="wid-id-60" data-widget-editbutton="false">
						
					<header>
						<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
						<h2>HB PIE</h2>
						<!-- <center>
                            <h2> Male Students <?php //echo $total_students['male'];?> &nbsp;&nbsp; Female Students : <?php //echo $total_students['female'];?> &nbsp; &nbsp; Total Students Count : <?php// echo $total_students['total_students'];?> &nbsp;&nbsp;</h2>
                        </center>  -->
						<!-- <button type="button" class="btn bg-color-pink txt-color-white btn-sm pull-right export-button hide" id="bmi_export_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
                            Export to Excel
                        </button> -->
					</header>

					<!-- widget div-->
					<div>

						<!-- widget edit box -->
						<div class="jarviswidget-editbox">
							<!-- This area used as dropdown edit box -->

						</div>
						<!-- end widget edit box -->


						<div class="col-md-12" id="loading_request_pie" style="display:none;">
							<center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
						</div>

						<div id="request_pies">						

					<div class="row clearfix">
                             <!-- HB Pie Usage -->
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                               <div class="card">
                                <div class="header">
                                  <h5 class="font-bold col-green">HB Report Female</h5>                                   
                                    <ul class="header-dropdown m-r--5">                                       
                                    </ul>
                                </div>
                                  <div class="body">                                  
                                    <div id="hb_girl_bar_chart" ></div> 
                                 
                                  </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                               <div class="card">
                                <div class="header">
                                  <h5 class="font-bold col-green">HB Report Male</h5>                                   
                                    <ul class="header-dropdown m-r--5">                                       
                                    </ul>
                                </div>
                                  <div class="body">                                  
                                    <div id="hb_boy_bar_chart" ></div> 
                                  
                                  </div>
                                </div>
                            </div>
                    </div>

								</div>
								<!-- end widget content -->

								<!-- end widget div -->

							</div>
							<!-- end widget -->
							<!-- end widget -->

						</article>
					</div>			
					 <form style="display: hidden" action="get_hb_students_girls_from_bar" method="POST" id="ehr_form_for_hb">
                        <input type="hidden" id="hb_symptom_boy" name="hb_symptom" value=""/>
                        <input type="hidden" id="gender_male" name="gender_hb" value=""/>
                    </form> 
          <form style="display: hidden" action="get_hb_students_girls_from_bar" method="POST" id="ehr_form_for_hb_girl">
            <input type="hidden" id="hb_symptom_girl" name="hb_symptom" value=""/>
            <input type="hidden" id="gender_female" name="gender_hb" value=""/>
        </form>
					<!-- BMI SUBMITTED SCHOOLS LIST -->				
		
		<!-- BMI NOT SUBMITTED SCHOOLS LIST -->
		<div class="modal" id="bmi_not_submitted_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-footer">
					<!-- <button type="button" class="btn btn-primary" id="absent_not_sent_school_download">
							Download
						</button> -->
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>

		<!-- row -->
		<!-- end row -->
	</div>
	<!-- widget grid -->
</div>
<!-- END MAIN CONTENT -->

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
	<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



	<!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->

	<script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
	<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
	<script src="<?php echo JS; ?>flot/jquery.flot.fillbetween.min.js"></script>
	<script src="<?php echo JS; ?>flot/jquery.flot.orderBar.js"></script>
	<script src="<?php echo JS; ?>flot/jquery.flot.pie.js"></script>
	<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
	<script src="<?php echo JS; ?>flot/jquery.flot.time.min.js"></script>
	<script src="<?php echo JS; ?>flot/jquery.flot.axislabels.js"></script>
	<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
	<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>

              <!-- MDB Style -->

	       <!-- Jquery Core Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery/jquery.min.js'); ?>"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo(MDB_PLUGINS.'bootstrap/js/bootstrap.js'); ?>"></script>

   

    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery-slimscroll/jquery.slimscroll.js'); ?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'node-waves/waves.js'); ?>"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery-countto/jquery.countTo.js'); ?>"></script>

    <!-- Morris Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'raphael/raphael.min.js'); ?>"></script>
    <script src="<?php echo(MDB_PLUGINS.'morrisjs/morris.js'); ?>"></script>

    <!-- Jquery DataTable Plugin Js -->
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/jquery.dataTables.js"; ?>'></script>
    <script src="<?php echo MDB_PLUGINS."jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"; ?>"></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/dataTables.buttons.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.flash.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/jszip.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/pdfmake.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/vfs_fonts.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.html5.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.print.min.js"; ?>'></script>

    

    <!-- Sparkline Chart Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery-sparkline/jquery.sparkline.js'); ?>"></script>

    <!-- Custom Js -->
    <script src="<?php echo(MDB_JS.'admin.js'); ?>"></script>

    <script src="<?php echo(MDB_JS.'pages/examples/profile.js'); ?>"></script>
    <script src="<?php echo(MDB_JS.'pages/index.js'); ?>"></script>

    <!-- Demo Js -->
    <script src="<?php echo(MDB_JS.'demo.js'); ?>"></script>

	<?php 
	//include footer
	include("inc/footer.php"); 
	?>

	<!-- ===================== BMI GENDER WISE BAR CHART================================== -->
<script type="text/javascript">
   show_hb_cases();


   function show_hb_cases(){

   	// var genderType = $("input[name='gender_type']:checked").val();
    // var schoolName = $("#school_name").val();
    //var academicYear = $("#get_academic_year").val();
    //alert(genderType+schoolName);
    
        $.ajax({
        url: 'get_screening_hb_data_by_gender',
        type: 'POST',
       
        success: function (data) {
            
        var hb_counts = $.parseJSON(data);
        var hb_boy = $.parseJSON(hb_counts.hb_boys);
        var hb_girl = $.parseJSON(hb_counts.hb_girls);
        var $arrColors = ['red', 'purple', 'green'];
        var bmiBarChart = Morris.Bar({
          barGap:1,
          element: 'hb_boy_bar_chart',
          data: hb_boy,
          xkey: 'label',
          //xLabelAngle:40,
          barSizeRatio:0.45,
          ykeys: ['value'],
          labels: ['value'],
          barColors: function (row, series, type) {
        return $arrColors[row.x];
    }, 
    hideHover: 'auto',
    resize: true
}).on('click', function(i, row){
              displayData(i, row);

            // Selects the element in the Donut
            bmiBarChart.select(i);
            // Display the corresponding data
            displayData(i, bmiBarChart.data[i]);

            function displayData(i, row) {
               $("#gender_male").val('Male');
               $("#hb_symptom_boy").val(row.label);
               $("#ehr_form_for_hb").submit();

            }
        });
        
         var girlBarChart = Morris.Bar({
          element: 'hb_girl_bar_chart',
          data: hb_girl,
          xkey: 'label',
          //xLabelAngle:40,                   
          barSizeRatio:0.45,          
          ykeys: ['value'],
          labels: ['value'],
          barColors: function (row, series, type) {
        return $arrColors[row.x];
    }, 
    hideHover: 'auto',
    resize: true
        }).on('click', function(i, row){
              displayData(i, row);

            // Selects the element in the Donut
            bmiBarChart.select(i);
            // Display the corresponding data
            displayData(i, bmiBarChart.data[i]);

            function displayData(i, row) {
               $("#gender_female").val('Female');
               $("#hb_symptom_girl").val(row.label);
               $("#ehr_form_for_hb_girl").submit();

            }
        });
            
        },
        error:function(XMLHttpRequest, textStatus, errorThrown)
        {
         console.log('error', errorThrown);
        }
    });
   }

</script>
<!-- ===================== BMI GENDER WISE BAR CHART================================== -->

