<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "HB Reports";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "";
include("inc/header.php");
//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["hb_reports"]["sub"]["hb_pie_view"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<!-- ==========================CONTENT STARTS HERE ========================== -->
		<!-- MAIN PANEL -->
		<div id="main" role="main">
		<?php
			//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
			//$breadcrumbs["New Crumb"] => "https://url.com"
			include("inc/ribbon.php");
		?>
			<!-- MAIN CONTENT -->
			<div id="content">

				
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
						
						<header>
							<span class="widget-icon"> <i class="fa fa-table"></i> </span>
							<h2>hb Reports</h2>
		
						</header>
		
						<!-- widget div-->
						
						
						<div>
		
							<!-- widget edit box -->
							
							<!-- end widget edit box -->
							
							<!-- widget content -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							
							<div class="widget-body">
							<h5 class="alert alert-info"> <?php echo ucwords($navigation) ;?> </h5>
								
																
								<div class = "under weight " id ="under weight">
								
									<table id="dt_basic" class="table table-striped table-bordered table-hover under weight" width="100%">
									<thead>			                
										<tr>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Hospital Unqiue ID</th>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Student Name</th>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Class</th>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> HB Values</th>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> school Name</th>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> school District</th>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> HB Graph</th>
											
										</tr>
									</thead>
									<tbody>
								<?php	//echo print_r($get_bmi_docs, true);
										 foreach ($get_hb_docs as $get_doc):// echo print_r($get_doc,true); exit;?>
												
											<tr>
												<td><?php echo $get_doc['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];?></td>
												<td><?php echo $get_doc['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref'];?></td>
												<td><?php echo $get_doc['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref'];?></td>
												<td><?php echo $get_doc['doc_data']['widget_data']['page1']['Student Details']['HB_values']['0']['hb'];?></td>
												<td><?php echo $get_doc['doc_data']['widget_data']['school_details']['School Name'];?></td>
												<td><?php echo $get_doc['doc_data']['widget_data']['school_details']['District'];?></td>
												<?php //foreach() ?>
												<?php //echo json_encode($get_doc['BMI_values']);?>
												<td><a class='delete1' href='<?php echo URL."bc_welfare_schools/show_student_hb_graph/".$get_doc['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];?>'>
						                			<button class="btn btn-primary btn-xs">Show Graph</button>
						                			</a>
												</td>
											</tr>
										<?php endforeach;?>
										
									</tbody>
								</table>
								</div>
								
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
		

	
		
	<!--------------------------------school report------------------------------------------------>
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
<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>
<script>

	$(document).ready(function() {
		// PAGE RELATED SCRIPTS
		
		/* BASIC ;*/
		//$("a[rel^='prettyPhoto']").prettyPhoto();
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
		
		
	/* 	var type = '<?php //echo strtolower($symptom_type);?>';
		console.log('TYPE',type)
		$('.'+type+'').removeClass('');
 */
	});
</script>
