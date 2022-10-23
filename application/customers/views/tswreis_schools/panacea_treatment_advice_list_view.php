<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Treatment Advice";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "";
include("inc/header.php");
//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["treatment_advice"]["active"] = true;
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
							<h2>Treatent Advice</h2>
		
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
								<div class = "under weight " id ="under weight">
								
									<table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">  <thead> <tr>  <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter UNIQUE ID" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter STUDENT NAME" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter MOBILE" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter DoB" /> </th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter CLASS" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter SECTION" /> </th>   </tr> <tr> <th>HOSPITAL UNIQUE ID</th> <th>STUDENT NAME</th> <th>CLASS</th> <th>SECTION</th> <th>TREATMENT ADVICE</th> <th>IMAGE</th> <th>ACTION</th> </tr> </thead> <tbody>
								<?php	if(isset($treatment_docs) && !empty($treatment_docs)):
										 foreach ($treatment_docs as $get_doc): //echo print_r($get_doc,true); exit;?>
												<?php if($get_doc['doc_data']['widget_data']['page7']['Colour Blindness']['Description'] == '(No\n)'):?>
													<?php echo "No Data";?>
													<?php else:?>
											<tr>
												<td><?php echo $get_doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];?></td>
												<td><?php echo $get_doc['doc_data']['widget_data']['page1']['Personal Information']['Name'];?></td>	
												<td><?php echo $get_doc['doc_data']['widget_data']['page2']['Personal Information']['Class'];?></td>
												<td><?php echo $get_doc['doc_data']['widget_data']['page2']['Personal Information']['Section'];?></td>
												<td><?php echo $get_doc['doc_data']['widget_data']['page7']['Colour Blindness']['Description'];?></td>
												<td>
													<?php if( isset($get_doc['doc_data']['subjective_refraction_attachments']) != [] || isset($get_doc['doc_data']['ocular_diagnosis_attachments']) != [] ) {?>
														
														<span> <i class="fa fa-paperclip" aria-hidden="true"></i> </span>

													<?php } ;?>
												</td>
												
												<?php //echo json_encode($get_doc['BMI_values']);?>
												<td><a class='delete1' href='<?php echo URL."tswreis_schools/drill_down_screening_to_students_load_ehr_doc/".$get_doc['_id'];?>'>
						                			<button class="btn btn-primary btn-xs"> Show EHR</button>
						                			</a>
												</td>
											</tr>
											<?php endif;?>
										<?php endforeach; endif;?>
										
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
<script src="<?php echo JS; ?>sweetalert.min.js"></script>
<script>

	$(document).ready(function() {

		<?php if(isset($message)) { ?>
			$.smallBox({
							title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
							content : "<?php echo $message?>",
							color : "#296191",
							iconSmall : "fa fa-bell bounce animated",
							timeout : 8000
						});
			<?php } ?>
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

			var js_url = "<?php echo JS; ?>";
			/* COLUMN FILTER  */
		    var otable = $('#datatable_fixed_column').DataTable({
		    	//"bFilter": false,
		    	//"bInfo": false,
		    	//"bLengthChange": false
		    	//"bAutoWidth": false,
		    	//"bPaginate": false,
		    	//"bStateSave": true, // saves sort state using localStorage
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
				 "oTableTools": {
		        	 "aButtons": [
		        	      {
		                 "sExtends": "xls",
		                 "sTitle": "TSWREIS School Student Report",
		                 "sPdfMessage": "TSWREIS School Student Excel Export",
		                 "sPdfSize": "letter"
			             },
			          	{
			             	"sExtends": "print",
			             	"sMessage": "TSWREIS School Student Printout <i>(press Esc to close)</i>"
			         	}],
		        	 "sSwfPath": js_url+"datatables/swf/copy_csv_xls_pdf.swf"
		        },
				"autoWidth" : true,
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_fixed_column) {
						responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_datatable_fixed_column.respond();
				}		
			
		    });
		    
		    // custom toolbar
		    //$("div.toolbar").html('<div class="text-right"><img src="img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');
		    	   
		    // Apply the filter
		    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
		    	
		        otable
		            .column( $(this).parent().index()+':visible' )
		            .search( this.value )
		            .draw();
		            
		    } );
		    /* END COLUMN FILTER */  
		
		
	/* 	var type = '<?php //echo strtolower($symptom_type);?>';
		console.log('TYPE',type)
		$('.'+type+'').removeClass('');
 */
	});
</script>
