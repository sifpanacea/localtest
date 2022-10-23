<?php

//initilize the page
//require_once("inc/init.php");

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
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["basic_dashboard"]["active"] = true;
include("inc/nav.php");

?>
<style>
	.checkbox_view_only {
	cursor: not-allowed;
}
</style>
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
<div class="row">
	
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
						<!-- widget div-->
							<h2><?php echo $hb_case_type; ?><span class="badge bg-color-greenLight"><?php if(!empty($get_hb_docs)) {?><?php echo count($get_hb_docs);?><?php } else {?><?php echo "0";?><?php }?></span></h2>
						<button type="button" class="btn btn-primary pull-right" onclick="window.history.back();">Back</button>
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
					<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover">
					<?php if ($get_hb_docs): ?>
					<thead>
					<tr>
						<th>Hospital Unique ID</th>
						<th>Student Name</th>
						<th>Class</th>
						<th>Section</th>
						<th>HB</th>
						<th>District</th>
						<th>School Name</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($get_hb_docs as $hb):?>
					<tr>
						
						<td><?php echo $hb['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];?></td>
						<td><?php echo $hb['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref'];?></td>
						<td><?php echo $hb['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref'];?></td>
						<td><?php echo $hb['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref'];?></td>
						<?php $hb_val = end($hb['doc_data']['widget_data']['page1']['Student Details']['HB_values']); ?>
						<td><?php echo $hb_val['hb'];?></td>
						<td><?php echo $hb['doc_data']['widget_data']['school_details']['District'];?></td>
						<td><?php echo $hb['doc_data']['widget_data']['school_details']['School Name'];?></td>
						<td> <a class='ldelete' href='<?php echo URL."panacea_mgmt/panacea_reports_display_ehr_uid/"?>? id = <?php echo $hb['doc_data']['widget_data']["page1"]['Student Details']['Hospital Unique ID'];?>'>
						                			<button class="btn btn-primary btn-xs">Show EHR</button>
						                			</a>
						                			
												</td>
					</tr>	
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<center><label>No reports found</label></center>
        			</p>
        			<?php endif ?>
									</tbody>
									
								</table>
		
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
					</article>
        
        </div><!-- ROW -->

					<br><br>
	
	
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
<script src="<?php echo(JS.'bootstrap-datepicker.js');?>" type="text/javascript"></script>
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
		var js_url = "<?php echo JS; ?>";

		$('#datatable_fixed_column').dataTable({
			//"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
			//	"t"+
				//"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",

				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
				 "oTableTools": {
		        	 "aButtons": [
		        	      {
		                 "sExtends": "xls",
		                 "sTitle": "TLSTEC Schools Report",
		                 "sPdfMessage": "TLSTEC Schools Excel Export",
		                 "sPdfSize": "letter"
			             },
			          	{
			             	"sExtends": "print",
			             	"sMessage": "TLSTEC Schools Printout <i>(press Esc to close)</i>"
			         	}],
		        	 "sSwfPath": js_url+"datatables/swf/copy_csv_xls_pdf.swf"
		        },

			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
				}
			},
			"rowCallback" : function(nRow) {
				responsiveHelper_dt_basic.createExpandIcon(nRow);
			},
			"drawCallback" : function(oSettings) {
				responsiveHelper_dt_basic.respond();
			},
			'mRender': function (data, type, full) {
        if (full[7] !== null) {
             return full[7]; 
         }else{
             return '';
         }
    }
		});			
		    // Apply the filter
		    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
		    	
		        otable
		            .column( $(this).parent().index()+':visible' )
		            .search( this.value )
		            .draw();
		            
		    } );
		    /* END COLUMN FILTER */ 

	/* END BASIC */
	});

</script>	
<?php 
	//include footer
	include("inc/footer.php"); 
?>
