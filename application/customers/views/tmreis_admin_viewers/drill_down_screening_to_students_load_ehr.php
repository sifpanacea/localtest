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
$page_nav["pa home"]["active"] = true;
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
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Admission Number</th>
											<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Student Name</th>
											<th data-hide="phone"><i class="fa fa-fw fa-phone text-muted hidden-md hidden-sm hidden-xs"></i> Mobile Number</th>
											<th data-hide="phone,tablet"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Class</th>
											<th data-hide="phone,tablet"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Section</th>
											<th > Action</th>
											
										</tr>
									</thead>
									<tbody>
										<?php foreach ($students as $student):?>
											<tr>
												<td><?php echo $student['doc_data']['widget_data']["page1"]['Personal Information']['Hospital Unique ID'] ;?></td>
												<td><?php echo $student['doc_data']['widget_data']["page2"]['Personal Information']['AD No'] ;?></td>
												<td><?php echo $student['doc_data']['widget_data']["page1"]['Personal Information']['Name'] ;?></td>
												<td><?php if(isset($student['doc_data']['widget_data']["page1"]['Personal Information']['Mobile']['mob_num'])):?><?php echo $student['doc_data']['widget_data']["page1"]['Personal Information']['Mobile']['mob_num'] ;?><?php else:?><?php echo "Mobile Number not available";?><?php endIf;?></td>
												<td><?php echo $student['doc_data']['widget_data']["page2"]['Personal Information']['Class'] ;?></td>
												<td><?php echo $student['doc_data']['widget_data']["page2"]['Personal Information']['Section'] ;?></td>
												<td><a class='ldelete' href='<?php echo URL."tmreis_viewers/drill_down_screening_to_students_load_ehr_doc/".$student['_id'];?>'>
						                			<button class="btn btn-primary btn-xs">Show EHR</button>
						                			</a>
						                			|
						                			<label class="checkbox">
														<input type="checkbox" name="checkboxName[]" id='<?php echo $student['doc_data']['widget_data']["page1"]['Personal Information']['Hospital Unique ID']?>' class="checkBoxClass">
														<i></i>Request to doctor</label>
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
					<div class="col col-4">
					<form class='smart-form'  action="forward_request" method="POST" id="request_form">
					<fieldset>
					<section class="col col-4">
					<label class="label">Select all or select EHR from above table to froward to doctors as request</label>
					
												<label class="checkbox">
														<input type="checkbox" name="checkbox" id="select_all_chk">
														<i></i>Select All</label>
					</section>
									</fieldset>
									<fieldset>
													<section class="col col-4">
														<label class="label" for="first_name">Select doctor</label>
														<label class="select">
														<select id="select_doc" name="select_doc">
															<option value=0 >Select a doctor</option>
															<?php if(isset($doctor_list)): ?>
																<?php foreach ($doctor_list as $doctor):?>
																<option value='<?php echo str_replace( "@", "#", $doctor['email'])?>' ><?php echo ucfirst($doctor['name'])?> | <?php echo $doctor['email']?> | <?php echo ucfirst($doctor['specification'])?></option>
																<?php endforeach;?>
																<?php else: ?>
																<option value="1"  disabled="">No doctors entered yet</option>
															<?php endif ?>
														</select> <i></i>
													</label>
													</section>
													</fieldset>
													<fieldset>
					<section>
											<label class="label">Description</label>
											<label class="textarea textarea-expandable"> 										
												<textarea rows="3" class="custom-scroll" id="desc_request" name="desc_request"><?php echo $navigation ;?> </textarea> 
											</label>
										</section>
									</fieldset>
									<fieldset>
													<section class="col col-4">
													<button type="button" class="btn bg-color-pink txt-color-white btn-sm" id="submit_request">
						                       Submit
						                    </button>
						                    </section>
						                    <input type="hidden" id="ehr_data_for_request" name="ehr_data_for_request" value=""/>
					</fieldset>
					</form></div>
		
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
	
	<?php if($message) { ?>
	$.smallBox({
					title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
					content   : "<?php echo $message?>",
					color     : "#2c699d",
					iconSmall : "fa fa-bell bounce animated",
					timeout : 4000
					
				});
	<?php } ?>
});
</script>
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

$("#select_all_chk").click(function(){
    var oTable = $('#dt_basic').dataTable();
    var allPages = oTable.fnGetNodes();
    $('input[type="checkbox"]', allPages).prop('checked', $(this).is(':checked'));
});

$("#submit_request").click(function(){
	$(this).prop('disabled', true);
	var id_array = [];
	var oTable = $('#dt_basic').dataTable();
	var rowcollection =  oTable.$("input[name='checkboxName[]']:checked", {"page": "all"});
	rowcollection.each(function(index,elem){
	    var checkbox_value = $(elem).attr("id");
	    id_array.push(checkbox_value);
	});
	var doctor_id = $('#select_doc :selected').val();
	if((id_array.length == 0) ){
		$.smallBox({
			title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Error!",
			content : "Select at least one EHR",
			color : "#C79121",
			iconSmall : "fa fa-bell bounce animated"
		});
		$(this).prop('disabled', false);
		return;
	}
	if((doctor_id == 0) ){
		$.smallBox({
			title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Error!",
			content : "Select a doctor",
			color : "#C79121",
			iconSmall : "fa fa-bell bounce animated"
		});
		$(this).prop('disabled', false);
		return;
	}
	$("#ehr_data_for_request").val(JSON.stringify(id_array));
	$("#request_form").submit();
});

</script>
