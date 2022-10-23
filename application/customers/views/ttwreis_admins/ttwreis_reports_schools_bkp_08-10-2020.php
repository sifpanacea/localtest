<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "ttwreis School Report";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa reports"]["sub"]["school"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["ttwreis Masters"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	

				
				<section id="widget-grid" class="">

					<!-- row -->
					<div class="row">
						
						<!-- NEW WIDGET START -->
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							
							<!--<div class="alert alert-info">
								<strong>NOTE:</strong> All the data is loaded from a seperate JSON file
							</div>-->

							<!-- Widget ID (each widget will need unique ID)-->
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget" id="wid-id-3" data-widget-editbutton="false">
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
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>All Schools <span class="badge bg-color-greenLight"><?php if(!empty($schoolscount)) {?><?php echo $schoolscount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>
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
								
								<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
									
							        <thead>
										<tr>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Filter School Code" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Filter School Name" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Filter School Address" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Filter District" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Filter Contact Email" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Filter Contact Phone" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Filter Contact Mobile" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Filter Contact Person" />
											</th>
										</tr>
							            <tr>
						                    <th>School Code</th>
											<th>School Name</th>
											<th>School Address</th>
											<th>School District</th>
											<th>Contact Email</th>
											<th>Contact Phone</th>
											<th>Contact Mobile</th>
											<th>Contact Person</th>
							            </tr>
							        </thead>
		 							<tbody>
							        <?php foreach ($schools as $school):?>
                   
										<tr>
											<td><?php echo $school["school_code"] ;?></td>
											<td><?php echo ucwords($school["school_name"]) ;?></td>
											<td><?php echo $school["school_addr"] ;?></td>
											<td><?php echo ucwords($school["dt_name"]) ;?></td>
											<td><?php echo $school["email"] ;?></td>
											<td><?php echo $school["school_ph"] ;?></td>
											<td><?php echo $school["school_mob"] ;?></td>
											<td><?php echo $school["contact_person_name"] ;?></td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
							<!-- end widget content -->
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->

						</article>
						<!-- WIDGET END -->
						
					</div>

					<!-- end row -->
					
					<div>
					<button type="button" class="btn btn-primary pull-right" onclick="window.history.back();">Back</button>
					</div>

					<!-- row -->

					<div class="row">

						<!-- a blank row to get started -->
						<div class="col-sm-12">
							<!-- your contents here -->
						</div>
							
					</div>
					<!-- end row -->
<br>
			<br>
			<br>
			<br>
			<br>
			<br>
				</section>
				<!-- end widget grid -->

				

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
<script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>
<script>

//DO NOT REMOVE : GLOBAL FUNCTIONS!

$(document).ready(function() {
	
	/* // DOM Position key index //
		
	l - Length changing (dropdown)
	f - Filtering input (search)
	t - The Table! (datatable)
	i - Information (records)
	p - Pagination (paging)
	r - pRocessing 
	< and > - div elements
	<"#id" and > - div with an id
	<"class" and > - div with a class
	<"#id.class" and > - div with an id and class
	
	Also see: http://legacy.datatables.net/usage/features
	*/	

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

	

});
</script>