<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Search Doctors";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["appointment"]["sub"]["search_user"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<style>
.txt-color-bluee
{
color:#214e75;!important
}
</style>

<link href="<?php echo CSS; ?>user_dash.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?php echo CSS; ?>smartadmin-production.css"/>
<div id="main" role="main">
<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
	<div id="content">
<!-- row -->
				<div class="row">
					
					<!-- col -->
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark">
							
							<!-- PAGE HEADER -->
							<i class="fa-fw fa fa-file-text-o"></i> 
								Doctors 
							<span>>  
								Details
							</span>
						</h1>
					</div>
					<!-- end col -->
					
				</div>
				<!-- end row -->
				
				<!--
					The ID "widget-grid" will start to initialize all widgets below 
					You do not need to use widgets if you dont want to. Simply remove 
					the <section></section> and you can use wells or panels instead 
					-->
				
				<!-- widget grid -->
				<section id="widget-grid" class="">

					<!-- row -->
					<div class="row">
						
						<!-- NEW WIDGET START -->
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							
							<!--<div class="alert alert-info">
								<strong>NOTE:</strong> All the data is loaded from a seperate JSON file
							</div>-->

							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget well" id="wid-id-0">
								<header>
									<span class="widget-icon"> <i class="fa fa-comments"></i> </span>
									<h2>Widget Title </h2>				
									
								</header>

								<!-- widget div-->
								<div>
									
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										<input class="form-control" type="text">	
									</div>
									<!-- end widget edit box -->
									
									<!-- widget content -->
									<div class="widget-body no-padding">
										<table id="example" class="display projects-table table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									        <thead>
									            <tr>
									                <th><i class="fa fa-fw fa-user-md text-muted hidden-md hidden-sm hidden-xs"></i> Doctor </th>
									                <th><i class="fa fa-fw fa-hospital text-muted hidden-md hidden-sm hidden-xs"></i> Hospital </th>
													<th><i class="fa fa-fw fa-envelope text-muted hidden-md hidden-sm hidden-xs"></i> Email ID </th>
													<th><i class="fa fa-fw fa-calendar text-muted hidden-md hidden-sm hidden-xs"></i> options </th>
									            </tr>
									        </thead>
									    </table>
									</div>
									<!-- end widget content -->
								</div>
								<!-- end widget div -->
							</div>
							<!-- end widget -->
						</article>
						<!-- WIDGET END -->
					</div>

					<!-- end row -->

					<!-- row -->

					<div class="row">

						<!-- a blank row to get started -->
						<div class="col-sm-12">
							<!-- your contents here -->
						</div>
							
					</div>
					<!-- end row -->

				</section>
				<!-- end widget grid -->
</div>
<!-- END MAIN PANEL -->
			

<!-- ==========================CONTENT ENDS HERE ========================== -->
<input type='hidden' id='queryapp' value='<?php echo set_value('queryapp', (isset($template->app_template)) ? json_encode($template->app_template) : ''); ?>' /><input type='hidden' id='queryid' value='<?php echo set_value('queryid', (isset($template->_id)) ? ($template->_id) : ''); ?>' /><input type='hidden' id='appname' value='<?php echo set_value('appname', (isset($template->app_name)) ? ($template->app_name) : ''); ?>' /><input type="hidden" id="get_pattern"/>
<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<!-- PAGE RELATED PLUGIN(S) -->
<script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>
<script>

$(document).ready(function() {
//PAGE RELATED SCRIPTS
	
	var table = $('#example').dataTable( {
     "data": <?php echo $data;?>,
		"bDestroy": true,
	    "iDisplayLength": 12,
	     "columns": [
		            { "data": "username" },
		            { "data": "company" },
		            { "data": "email" },
		            {     // fifth column (Edit link)
		                "sName": "RoleId",
		                "bSearchable": false,
		                "bSortable": false,
		                "mRender": function (data, type, full) {
		                    var data = full.email; //row id in the first column
		                    console.log(data);
		                    return "<a href='../patient_login/display_user_appointments/"+btoa(data)+"/"+btoa(full.username)+"'>Get Appointment</a>";
		               }
		            },
		           
		        ],
		"order": [[1, 'dsc']],
	        "fnDrawCallback": function( oSettings ) {
		       runAllCharts()
		    }
	} );
});

</script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>

