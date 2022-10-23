<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Students EHR";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "";
include("inc/header.php");
//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["basic_dashboard"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

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

				<!-- row -->
				<div class="row">
					
					<!-- col -->
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark">
							
							<!-- PAGE HEADER -->
							<i class="fa-fw fa fa-file-text-o"></i> 
								Students 
							<span>>  
								Overview
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
					<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
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
							<span class="widget-icon"> <i class="fa fa-table"></i> </span>
							<h2>Student details </h2>
		
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
								
						        <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
									<thead>			                
										<tr>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i>Hospital Unique ID</th>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i>Name</th>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i>Class</th>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i>School Name</th>
											<th > Action</th>
											
										</tr>
									</thead>
									<tbody>
										<?php foreach ($students_details as $index => $doc ):?>
											
											<tr>
												<td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Unique ID'] ;?>
												</td>
												<td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Name']['field_ref'] ;?></td>
												<td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Class']['field_ref'] ;?></td>
												<td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['School Name']['field_ref'] ;?>
													</td>
												<td> <a class='ldelete' href='<?php echo URL."tmreis_mgmt/drill_down_screening_to_students_load_ehr_new_dashboard/".$doc['doc_data']['widget_data']["page1"]['Student Info']['Unique ID'];?>'>
						                			<button class="btn btn-primary btn-xs">Show EHR</button>
						                			</a>
						                			
												</td>

											</tr>
										
										<?php endforeach;?>
									</tbody>
								</table>
								
									
									
							
							
							</div>
							
								
							<!-- end widget content -->
							<div>
					<button type="button" class="btn btn-primary pull-right" onclick="window.history.back();">Back</button>
					<br><br>
					</div>
						</div>
						<!-- end widget div -->

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
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<!-- PAGE FOOTER -->
<?php
	// include page footer
	include("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) -->
<script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>


<script>

	$(document).ready(function() {
		// PAGE RELATED SCRIPTS
		
		/* BASIC ;*/
		var responsiveHelper_dt_basic = undefined;
		var responsiveHelper_datatable_fixed_column = undefined;
		var responsiveHelper_datatable_col_reorder = undefined;
		var responsiveHelper_datatable_tabletools = undefined;
		
		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

		$('#dt_basic').dataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_dt_basic.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_dt_basic.respond();
			}
		});

	/* END BASIC */
	


	});

</script>
