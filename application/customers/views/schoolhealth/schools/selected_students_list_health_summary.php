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
$page_nav["home"]["active"] = true;
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
		
					<!--<div class="alert alert-info fade in">
						<i class="fa-fw fa fa-info"></i>
						<strong>  <?php echo $navigation ;?> </strong>
					</div>-->
		
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
												<td>
						                			<label class="checkbox">
														<input type="checkbox" name="checkboxName[]" id='<?php echo $student['doc_data']['widget_data']["page1"]['Personal Information']['Hospital Unique ID']?>' class="checkBoxClass">
														<i></i>Generate Health Summary Report</label>
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
					<form class='smart-form'  action="init_health_summary_report_process" method="POST" id="init_health_summary_report">
					<fieldset>
					<section class="col col-4">
					<label class="label">Select all or select EHR from above table to print health summary report</label>
					
												<label class="checkbox">
														<input type="checkbox" name="checkbox" id="select_all_chk">
														<i></i>Select All</label>
					</section>
									</fieldset>
									
										
									<fieldset>
													<section class="col col-4">
													<button type="button" class="btn bg-color-pink txt-color-white btn-sm" id="submit_print_request">
						                       <i class="fa fa-print"></i> Print
						                    </button>
						                    </section>
						                    <input class="hide" type="text" id="health_summary_report_print" name="health_summary_report_print" value=""/>
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
<style>
@media print{
 
.page_break{float: none !important; display:block;position: relative !important;page-break-after: always;}

}
</style>
<script>
	$(document).ready(function() {
	  /* #parent{display:none;}
   #parent{overflow:initial !important;float: none !important;display:block; position: relative !important; border:0; width:100%; min-height:500px} */
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

$("#submit_print_request").click(function(){
	$(this).prop('disabled', true);
	var id_array = [];
	var oTable = $('#dt_basic').dataTable();
	var rowcollection =  oTable.$("input[name='checkboxName[]']:checked", {"page": "all"});
	console.log('ROWCOLLECTION',rowcollection);
	rowcollection.each(function(index,elem){
	    var checkbox_value = $(elem).attr("id");
	    id_array.push(checkbox_value);
	});
	
	if((id_array.length == 0) ){
		$.smallBox({
			title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message !",
			content : "Select at least one EHR",
			color : "#C79121",
			iconSmall : "fa fa-bell bounce animated"
		});
		$(this).prop('disabled', false);
		return;
	}
	
	$.ajax({
		url    : 'init_health_summary_report_process',
		type   : 'POST',
		data   : {"unique_ids" : id_array},
		success: function (data) {			
			console.log(data);	
			
			/*newWin= window.open("");
			newWin.document.write("<style>@media print{.page_break{float: none !important; display:block;position: relative !important;page-break-after: always;}table{width:100%}tr{width:100%}td{padding:5px;width:50%;border:1px solid black;}.physical_information{border:1px solid black;padding:5px;margin-bottom:5px;}.eye_abnormalities{border:1px solid black;padding:5px;margin-bottom:5px;}.auditory_abnormalities{border:1px solid black;padding:5px;margin-bottom:5px;}.dental_abnormalities{border:1px solid black;padding:5px;margin-bottom:5px;}.doctor_signature{border:0px solid black;padding:5px;margin-bottom:5px;}.note{border:1px solid black;padding:5px;margin-bottom:5px;clear:both;}.general_abnormalities{border:1px solid black;padding:5px;margin-bottom:5px;}.school_name{}.title{font-size:110%;font-weight:bold;}label{margin:5px!important;display:inline-block;}}</style>");
			newWin.document.write(data);
			newWin.print();
			newWin.close();*/
			
			var mywindow = window.open("");
			var is_chrome = Boolean(mywindow.chrome);
			mywindow.document.write("<style>@media print{.page_break{float: none !important; display:block;position: relative !important;page-break-after: always;}table{width:100%}tr{width:100%}td{padding:5px;width:50%;border:1px solid black;}.physical_information{border:1px solid black;padding:5px;margin-bottom:3px;}.eye_abnormalities{border:1px solid black;padding:5px;margin-bottom:3px;}.auditory_abnormalities{border:1px solid black;padding:5px;margin-bottom:3px;}.dental_abnormalities{border:1px solid black;padding:5px;margin-bottom:3px;}.doctor_signature{border:0px solid black;padding:5px;margin-bottom:3px;}.note{border:1px solid black;padding:5px;margin-bottom:3px;clear:both;}.general_abnormalities{border:1px solid black;padding:5px;margin-bottom:3px;}.school_name{}.title{font-size:100%;font-weight:bold;}label{margin:5px!important;display:inline-block;}.tlstec{margin-left:0px;}}</style>");
			mywindow.document.write(data);
			mywindow.document.close(); // necessary for IE >= 10 and necessary before onload for chrome

			if (is_chrome) {
				mywindow.onload = function() { // wait until all resources loaded 
					mywindow.focus(); // necessary for IE >= 10
					mywindow.print();  // change window to mywindow
					mywindow.close();// change window to mywindow
				};
			}
			else {
				mywindow.document.close(); // necessary for IE >= 10
				mywindow.focus(); // necessary for IE >= 10
				mywindow.print();
				mywindow.close();
			}
		},
		error  :function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
		 }
	});
	
	//$("#ehr_data_for_request").val(JSON.stringify(id_array));
	//$("#init_health_summary_report").submit();
});

</script>
