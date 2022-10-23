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
											<th>Unique ID</th>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i>Name</th>
											<th>Class</th>
											<!--<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i>School Name</th>-->
											<th><i class="fa fa-plus-square" style="font-size:24px;"></i>Diseases Type </th>
								            <th>Request Raised Time </th>
								            <th>Doctor Response Time </th>
								            <th><i class="fa fa-user-md" aria-hidden="true" style="font-size:24px"></i>Doctor Name</th>
								            <th><i class="fa fa-paperclip" style="font-size:24px;"></i>Attachments</th>
											<th><i class="fa fa-address-card" aria-hidden="true"></i>Action</th>
											
										</tr>
									</thead>
									<tbody>
										<?php foreach ($students_details as $index => $doc ):?>
											
											<tr>
												<td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Unique ID'] ;?>
												</td>
												<td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Name']['field_ref'] ;?></td>
												<td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Class']['field_ref'] ;?></td>
												<!--<td><?php //echo $doc['doc_data']['widget_data']["page1"]['Student Info']['School Name']['field_ref'] ;?>
													</td>-->
												<?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Normal"):?>
												<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'];?>
												<td><span class="badge badge-warning" style="background-color:blue"><?php foreach ($identifiers as $identifier => $values) :?>
													
													<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]); ?>
												<?php if(!empty($var123)):?> 
												<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]) : "No Identifier";?>
												
											<?php endif;?>
											<?php endforeach;?></span></td>
												
												<td> <?php echo $doc['history'][0]['time'];?></td>
												<?php $last_doc = end($doc['history']);
											if(preg_match("/bcwelfare.dr/i",$last_doc['submitted_by'])):?>
												<td><?php echo $last_doc['time'];?></td> 
												<td><?php echo $last_doc['submitted_by_name'];?></td>
												<?php else:?>
													<td><?php echo "Nill";?></td>
													<td><?php echo "Doctor not to yet responded";?></td>
												<?php endif;?>
												<?php if(isset($doc['doc_data']['external_attachments']) && !empty($doc['doc_data']['external_attachments'])):?>
												<td class="text-center"><i class="fa fa-paperclip fa-2x" aria-hidden="true"></i></td>
												<?php else:?>
													<td>No Attachments</td>
											<?php endif;?>
											<?php elseif($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Emergency"):?>
												<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'];?>
											<td><span class="badge badge-warning" style="background-color:red"><?php foreach ($identifiers as $identifier => $values) :?>
												
												<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]); ?>
											<?php if(!empty($var123)):?> 
											<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]) : "No Identifier";?>
											
										<?php endif;?>
										<?php endforeach;?></span></td>
											
											<td> <?php echo $doc['history'][0]['time'];?></td>
											<?php $last_doc = end($doc['history']);
										if(preg_match("/bcwelfare.dr/i",$last_doc['submitted_by'])):?>
											<td><?php echo $last_doc['time'];?></td> 
											<td><?php echo $last_doc['submitted_by_name'];?></td>
											<?php else:?>
												<td><?php echo "Nill";?></td>
												<td><?php echo "Doctor not to yet responded";?></td>
											<?php endif;?>

											<?php if(isset($doc['doc_data']['external_attachments']) && !empty($doc['doc_data']['external_attachments'])):?>
											<td>  <i class="fa fa-paperclip" aria-hidden="true"></i></td>
											<?php else:?>
												<td>No Attachments</td>
										<?php endif;?>
												<?php elseif($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Chronic"):?>
													<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'];?>
											<td><span class="badge badge-warning" style="background-color:Green"><?php foreach ($identifiers as $identifier => $values) :?>
												
												<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]); ?>
											<?php if(!empty($var123)):?> 
											<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]) : "No Identifier";?>
											
										<?php endif;?>
										<?php endforeach;?></span></td>
											
											<td> <?php echo $doc['history'][0]['time'];?></td>
											<?php $last_doc = end($doc['history']);
										if(preg_match("/bcwelfare.dr/i",$last_doc['submitted_by'])):?>
											<td><?php echo $last_doc['time'];?></td> 
											<td><?php echo $last_doc['submitted_by_name'];?></td>
											<?php else:?>
												<td><?php echo "Nill";?></td>
												<td><?php echo "Doctor not to yet responded";?></td>
											<?php endif;?>
											<?php if(isset($doc['doc_data']['external_attachments']) && !empty($doc['doc_data']['external_attachments'])):?>
											<td>  <i class="fa fa-paperclip" aria-hidden="true"></i></td>
											<?php else:?>
												<td>No Attachments</td>
										<?php endif;?>
												<?php endif;?>

												<td> <a class='ldelete' href='<?php echo URL."bc_welfare_mgmt/drill_down_screening_to_students_load_ehr_new_dashboard/".$doc['doc_data']['widget_data']["page1"]['Student Info']['Unique ID'];?>'>
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
