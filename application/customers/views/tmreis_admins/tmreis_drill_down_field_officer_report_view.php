<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Reports";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "";
include("inc/header.php");
//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa field_officer"]["active"] = true;
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

				<!-- row -->
				<div class="row">
					
					<!-- col -->
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark">
							
							<!-- PAGE HEADER -->
							<i class="fa-fw fa fa-file-text-o"></i> 
								Field Officer 
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
				<article class="col-sm-12">
		
					<div class="alert alert-info fade in">
						<i class="fa-fw fa fa-info"></i>
						<strong>  <?php echo $navigation ;?> </strong>
					</div>
		
				</article>
				<!-- WIDGET END -->
		
			</div>
		
			<!-- end row -->

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
							<h2>Reports</h2>
		
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
							
								
																
								<div class = "school hide" id ="school">
								<h3><i class="fa fa-table"></i> School Reports </h3>
									<table id="dt_basic" class="table table-striped table-bordered table-hover school" width="100%">
									<thead>			                
										<tr>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> school Code</th>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> school Name</th>
											<th data-hide="expand"><i class="fa fa-fw fa-lists text-muted hidden-md hidden-sm hidden-xs"></i> Action</th>
										</tr>
									</thead>
									<tbody>
									<?php if($get_docs): $n=0; ?>
										<?php foreach ($get_docs['school'] as $get_doc):?>
											<tr>
												<td><?php echo $get_doc['doc_data']['school_code'];?></td>
												<td><?php echo $get_doc['schools']; $n++;?></td>
												<td><button data-target="#schoolModal" data-toggle="modal" id="<?php echo $n; ?>"class="btn btn-primary sch_report">Show Report</button></td>
											</tr>
										<?php endforeach;?>
										<?php endif;?>
										
									</tbody>
								</table>
								</div>
								<div class = "hospital hide" id = "hospital">
								<h3><i class="fa fa-table"></i> Hospital Reports </h3>
									<table id="dt_basic2" class="table table-striped table-bordered table-hover hospital hide" width="100%">
									<thead>			                
										<tr>
											<th data-class="expand"><i class="fa fa-fw fa-hospital text-muted hidden-md hidden-sm hidden-xs"></i>Hospital Name</th>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Student ID</th>
											<th data-hide="phone"><i class="fa fa-fw fa-briefcase text-muted hidden-md hidden-sm hidden-xs"></i> Action</th>
											<!--<th data-hide="phone"><i class="fa fa-fw fa-link text-muted hidden-md hidden-sm hidden-xs"></i> Attachments</th>-->
										</tr>
									</thead>
									<tbody>
										<?php if($get_docs): ?>
										<?php foreach ($get_docs['hospital'] as $get_doc):?>
											<tr>
												<td><?php echo $get_doc['doc_data']['hospital_name']; ?></td>
												<td><?php echo $get_doc['doc_data']['student_id']; ?></td>
												<td><button type "submit" class="btn btn-primary btn-xs show_report" id ="show_report" unique_id="<?php echo $get_doc['doc_data']['student_id'];?>">Show Report </button></td>
												<?php 
												endforeach;
												endif;
												?>
												
											</tr>
									</tbody>
								</table>
								</div>
								
								<form name="redirect_to_ehr" id="redirect_to_ehr" class="hide" action ="https://mednote.in/PaaS/healthcare/index.php/panacea_mgmt/panacea_reports_display_ehr_uid" method = "POST">
								<input type = "hidden" name = "uid" id="uid">
								<button type = "submit" class = "show_ehr" id = "show_ehr"></button>
								</form>
								
								
								<div class = "dept hide" id = "dept">
								<h3><i class="fa fa-table"></i> Department Reports </h3>
						        <table id="dt_basic3" class="table table-striped table-bordered table-hover" width="100%">
									<thead>			                
										<tr>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Department Name</th>
											<th data-hide="phone"><i class="fa fa-link"></i> Action</th>
											
										</tr>
									</thead>
									<tbody>
									<?php if($get_docs):  $i=0; ?>
									
										<?php foreach ($get_docs['dept'] as $get_doc): $i++;?>
												<td><?php echo $get_doc['doc_data']['department_name'];?></td>
												<td><button data-target="#myModal" data-toggle="modal" id="<?php echo $i; ?>"class="btn btn-primary dept_report">Show Report</button></td>
										</tr>
										<?php endforeach;?>
									<?php endif;?>
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
		
		<!-- Modal
		<div class="modal fade" id="field_officer_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Reports</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div id = "field_officer_attachments_modal_body" class="field_officer_attachments_modal_body" >
				
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				
			  </div>
			</div>
		  </div>
		</div> -->
		
		<div id="myModal" class="modal fade" role="dialog">
			  <div class="modal-dialog modal-lg">

				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title">Department Reports</h2>
				  </div>
				  <div class="modal-body">
					<div id="accordion">
							<h4>Submitted at:<?php echo $get_doc['time'];?></h4>
							<?php if($get_docs){
								  $i=1;
								foreach($get_docs['dept'] as $get_doc){ 
								   ?>
								
								<div class="padding-10">
									<div id="data<?php echo $i; ?>" class="popup-data">
									<div class="panel panel-primary">
										<div class="panel-heading"><i class = "fa fa-file-text-o"> Case Purpose:</i></div>
										 <div class="panel-body fixed-panel"><p><?php echo $get_doc['case_purpose'];?></p>
										</div>
									</div>
									
									<div class="panel panel-primary">
										<div class="panel-heading"><i class = "fa fa-file-text-o"> Case Details:</i></div>
											<div class="panel-body fixed-panel"><p><?php echo $get_doc['case_details'];?></p>
											</div>
									</div>
									<div class="panel panel-primary">
										<div class="panel-heading"><i class = "fa fa-file-text-o"> Attachments:</i></div>
											<div class="panel-body fixed-panel"><p>
											<?php if(isset($get_doc['attachments']) && !is_null($get_doc['attachments']) && !empty($get_doc['attachments'])):?>
											<?php foreach($get_doc['attachments'] as $attachment): ?>
									<img src="<?php echo URLCustomer.$attachment['full_path'];?>" height="50%" width="40%"><?php endforeach; ?><?php else:?><?php echo "No Attachments"; ?><?php endif ?></p>
											</div>
									</div>
										<div class="alert alert-info col-sm-offset-8"><i class = "fa fa-user">  Submitted By:  <?php echo $get_doc['email'];?></i>
										</div>
									
									</div>
								
								<?php
								$i++;
								}
							}?>
							</div>
					</div>

				</div>
				
				
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				  </div>
				</div>

			  </div>
		</div>
		
		<!--------------------------------school report------------------------------------------------>
		
		<div id="schoolModal" class="modal fade" role="dialog">
			  <div class="modal-dialog modal-lg">

				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title">School Reports</h2>
				  </div>
				  <div class="modal-body">
					<div id="accordionn">
							<h5>Submitted at:<?php echo $get_doc['time'];?>
							</h5>
							<?php if($get_docs):
								  $n=1;
								foreach($get_docs['school'] as $get_doc):
								   ?>
								 
								<div class="padding-10">
									<div id="school_data<?php echo $n; ?>" class="popup_school_data">
									<div class="panel panel-primary">
										<div class="panel-heading"><i class = "fa fa-file-text-o"> Case Purpose:</i></div>
										 <div class="panel-body fixed-panel"><p><?php echo $get_doc['case_purpose'];?></p>
										</div>
									</div>
									
									<div class="panel panel-primary">
										<div class="panel-heading"><i class = "fa fa-file-text-o"> Case Details:</i></div>
											<div class="panel-body fixed-panel"><p><?php echo $get_doc['case_details'];?></p>
											</div>
									</div>
									<div class="panel panel-primary">
										<div class="panel-heading"><i class = "fa fa-file-text-o"> Attachments:</i></div>
											<div class="panel-body fixed-panel"><p>
											<?php if(isset($get_doc['attachments']) && !is_null($get_doc['attachments']) && !empty($get_doc['attachments'])):?>
											<?php foreach($get_doc['attachments'] as $attachment): ?>
									<img src="https://mednote.in/'<?php echo $attachment['file_name'];?>" height="50%" width="40%"><?php endforeach; ?><?php else:?><?php echo "No Attachments"; ?><?php endif ?></p>
											</div>
									</div>
										
										<div class="alert alert-info col-sm-offset-8"><i class = "fa fa-user">  Submitted By:  <?php echo $get_doc['email'];?></i>
										</div>
									</div>
								<?php
								$n++;
								endforeach;
							endif; ?>
					</div>

				</div>
				
				</div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				  </div>
				

			  </div>
			  </div>
		</div>
		
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
		
		 $('#dt_basic2').dataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic2'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_dt_basic.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_dt_basic.respond();
			}
		}); 
		
		 $('#dt_basic3').dataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic3'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_dt_basic.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_dt_basic.respond();
			}
		});
		
		var type = '<?php echo strtolower($type);?>';
		console.log(type)
        $('.'+type+'').removeClass('hide');
		
	
		
	/* END BASIC */
	
	
	   // View Sanitation Report Attachments
  /*  $(document).on('click','.school_report',function(e)
   {
		
		var path = $(this).attr('path');
		console.log('path',path);
		var path = atob(path);
		var paths = JSON.parse(path);
		console.log('paths',paths);
		//var paths = path.split(',');
		$('#field_officer_attachments_modal_body').empty();
		var gallery = "";
		var img     = "";
		gallery="<div class='row'><div class='superbox col-sm-12'><div class='superbox-list'><div class=''>";
		for(var i=0;i<paths.length;i++)
		{
			var j=i+1;
			//img+="<a href='<?php echo URLCustomer;?>"+paths[i].full_path+"' rel='prettyPhoto[gal]'>Image "+j+"</a><br>";
			img+="<img src ='<?php echo URLCustomer;?>"+paths[i].full_path+"' rel='prettyPhoto[gal]'>Image "+j+"</a><br>";
			console.log('imageee==',img);
		} 
		gallery+=img;
		gallery+="</div></div></div></div>";
		$(gallery).appendTo('#field_officer_attachments_modal_body');
		$('#field_officer_modal').modal('show');
		$("a[rel^='prettyPhoto']").prettyPhoto();
   }) */
   
    $(".show_report").click(function(){
	   var unique_id = $(this).attr('unique_id');
	$('#uid').val(unique_id);
    $("#redirect_to_ehr").submit();
	});
	
	$('.popup-data').hide();
		var that;
		$(document).on('click','.dept_report',function()
		{
			that = $(this).attr("id")
			$('.popup-data').hide();
			$('#data'+that+'').show()
			$('#myModal').modal('show');
		})
		
		$('.popup_school_data').hide();
		var that;
		$(document).on('click','.sch_report',function()
		{
			that = $(this).attr("id")
			console.log('schoollll_dataa',that);
			$('.popup_school_data').hide();
			$('#school_data'+that+'').show()
			$('#schoolModal').modal('show');
		})
		
		
					/*
			* ACCORDION
			*/
			//jquery accordion
			
		     var accordionIcons = {
		         header: "fa fa-plus",    // custom icon class
		         activeHeader: "fa fa-minus" // custom icon class
		     };
		     
			$("#accordion").accordion({
				autoHeight : false,
				heightStyle : "content",
				collapsible : true,
				animate : 300,
				icons: accordionIcons,
				header : "h4",
			})
			  
			$("#accordionn").accordion({
				autoHeight : false,
				heightStyle : "content",
				collapsible : true,
				animate : 300,
				icons: accordionIcons,
				header : "h5",
			})
	
	});
</script>
