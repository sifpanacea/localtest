<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Extend Request";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["req_extend"]["active"] = true;
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

		<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>List of raised request</h2>
		
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
											<th class="hasinput" style="width:17%" >
												<input type="text" class="form-control" placeholder="Filter Raised Time" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Filter Unique ID" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Filter Problem Information" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Filter Status" />
											</th>
											<th class="hasinput" style="width:17%">
												<input type="text" class="form-control" placeholder="Filter Doctor's Advice" />
											</th>
											<th class="hasinput" style="width:17%">
												
											</th>
											
											
										</tr>
							            <tr>
						                    <th>Raised Time</th>
											<th>Unique ID</th>
											<th>Problem Information</th>
											<th>Status</th>
											<th>Doctor's Advice</th>
											<th>options</th>
							            </tr>
							        </thead>
		 							<tbody>
							        <?php foreach ($docs_requests as $request):?>
                   
										<tr>
											<td><?php 
													$newformat = new DateTime($request['history'][0]['time']);
													//$newformat = new $last_stage['time'];
													$tz = new DateTimeZone('Asia/Kolkata'); // or whatever zone you're after

													$newformat->setTimezone($tz);
													//echo $dt->format('Y-m-d H:i:s');
													echo $newformat->format('Y-m-d H:i:s') ;?>
											</td>
											
											<td><?php echo $request['doc_data']['widget_data']['page1']['Student Info']['Unique ID'] ;?></td>
													
											<td>
												<?php echo (gettype($request['doc_data']['widget_data']['page1']['Problem Info']['Identifier'])=="array")? implode (", ",$request['doc_data']['widget_data']['page1']['Problem Info']['Identifier']) : $request['doc_data']['widget_data']['page1']['Problem Info']['Identifier'];?>
											</td>
											<td><?php echo $request['doc_data']['widget_data']['page2']['Review Info']['Status'] ;?></td>
											<td><?php echo $request['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice'] ;?></td>
											
											<td>
											
											<a class="btn btn-danger btn-xs" href="<?php echo URL."ttwreis_cc/app_access/".$request['doc_properties']['doc_id'];?>">Extend</a>
											</td>
											
										</tr>
										
										<?php endforeach;?>
									</tbody>
								</table>
							
		
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
			</article>
        
        </div><!-- ROW -->
				

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->

<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
$(document).ready(function() {
<?php if($message) { ?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
				content : "<?php echo $message?>",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
<?php } ?>


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


/* END BASIC */
var js_url = "<?php echo JS; ?>";
/* COLUMN FILTER  */
var otable = $('#datatable_fixed_column').DataTable({
	"order": [[ 0, "desc" ]]
	//"bFilter": false,
	//"bInfo": false,
	//"bLengthChange": false
	//"bAutoWidth": false,
	//"bPaginate": false,
	//"bStateSave": true, // saves sort state using localStorage	

	

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