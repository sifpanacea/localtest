<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "All Reports";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["All_reports"]["active"] = true;
include("inc/nav.php");

?>
<style>
#flot-tooltip { font-size: 12px; font-family: Verdana, Arial, sans-serif; position: absolute; display: none; border: 2px solid; padding: 2px; background-color: #FFF; opacity: 0.8; -moz-border-radius: 5px; -webkit-border-radius: 5px; -khtml-border-radius: 5px; border-radius: 5px; }
.modal-body{
	height: 450px;

}

</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->

<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<link href="<?php echo(CSS.'site.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">
	<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> <?php echo lang('admin_dash_home');?> <span>> <?php echo lang('admin_dash_board');?></span></h1>
			</div>
			
	</div>
		
		
		
		
		<div class="row">

		<div class="col-xs-12 col-sm-4 col-md-12 col-lg-12">
				
				<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<!-- new widget -->
					<div class="jarviswidget" id="wid-id-1000" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
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
							<span class="widget-icon"> <i class="glyphicon glyphicon-calendar txt-color-darken"></i> </span>
							<h2>Set Date For PIE </h2>

						</header>

						<!-- widget div-->
						<div class="no-padding">
							<!-- widget edit box -->
							<!-- end widget edit box -->

							<div class="widget-body">
								<!-- content -->
								<div id="myTabContent" class="tab-content">
								<div class="well well-sm well-light">
								<form class='smart-form'>
									<fieldset>
											<div class="row">
											<section class="col col-12">
											<div class="form-group">
												<div class="input-group">
												<input type="text" id="set_data" name="set_data" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo $today_date?>">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												</div>
											</div>
											</section>
					                    
										</div>
												<div class="row">
													<section class="col col-lg-12">
														<label class="label" for="first_name">District Name</label>
														<label class="select">
														<select id="select_dt_name" >
															<option value="All">All</option>
															<?php if(isset($distslist)): ?>
																<?php foreach ($distslist as $dist):?>
																<option value='<?php echo $dist['_id']?>' ><?php echo ucfirst($dist['dt_name'])?></option>
																<?php endforeach;?>
																<?php else: ?>
																<option value="1"  disabled="">No district entered yet</option>
															<?php endif ?>
														</select> <i></i>
													</label>
													</section>
													<section class="col col-lg-12">
														<label class="label" for="first_name">School Name</label>
														<label class="select">
														<select id="school_name" disabled=true>
															<option value='All' >All</option>
															
															
														</select> <i></i>
													</label>
													</section>
													<section class="col col-6">
													<button type="button" class="btn bg-color-pink txt-color-white btn-sm" id="set_date_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
						                       Set
						                    </button>
						                    </section>
													</div>
													
													
													</fieldset>
													
										</form>			
													
							</div>
								<!-- end content -->

						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->
                 </div>
				</article>
				<article class="col-sm-12">
					<!-- Widget ID (each widget will need unique ID)-->
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget" id="wid-id-4" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>All Students <span class="badge bg-color-greenLight">
							<?php if(!empty($studentscount)) {?><?php echo $studentscount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>
							
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
							
							<!-- widget content -->
							<div class="widget-body no-padding table-responsive">
								
								<table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">
								
							        <thead>
							            <tr>
						                    <th>District</th>
											<th>School Name</th>
											<th>Action</th>
							            </tr>
							        </thead>
							        <tbody>
							        <?php
							        if($sanitation_submitted_schools)
							        {
							        	$i = 0;
							        foreach ($sanitation_submitted_schools as $school):
							        	$i ++;
							        	?>
							        
									<tr>
										
										<td><?php echo $school['doc_data']['widget_data']["page4"]['School Information']['District'];?></td>
										<td><?php echo $school['doc_data']['widget_data']["page4"]['School Information']['School Name'];?></td>
										 <td><a data-target="#myModal" data-toggle="modal" id="<?php echo $i; ?>"  class="btn btn-default details">Details</a></td>
									</tr>
									<?php endforeach;
										
									} ?>
								</tbody>
								</table>
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
						</div>
						<!-- end widget div -->
				</article>
				
				<article class="col-sm-12">
					<!-- new widget -->
					<div class="jarviswidget" id="wid-id-100" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
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
							<span class="widget-icon"> <i class="glyphicon glyphicon-record txt-color-darken"></i> </span>
							<h2>Sanitation Infrastructure </h2>

						</header>

						<!-- widget div-->
						<div class="no-padding">
							<!-- widget edit box -->
							<!-- end widget edit box -->

							<div class="widget-body">
							<div class="row">
								<br>
								<d`iv class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									<div class="well well-sm well-light">
										<form class="smart-form" >
										<fieldset style="padding-top: 0px; padding-bottom: 0px;">
										<section class="col col-6">
														<label class="label" for="district_name">District Name</label>
														<label class="select">
														<select id="select_sanitation_infra_dt_name" >
															<option value='select_school' >Select a district</option>
															<?php if(isset($distslist)): ?>
																<?php foreach ($distslist as $dist):?>
																<option value='<?php echo $dist['_id']?>' ><?php echo ucfirst($dist['dt_name'])?></option>
																<?php endforeach;?>
																<?php else: ?>
																<option value="1"  disabled="">No district entered yet</option>
															<?php endif ?>
														</select> <i></i>
													</label>
													</section>
													<section class="col col-6">
														<label class="label" for="school_name">School Name</label>
														<label class="select">
														<select id="select_sanitation_infra_school_name" disabled=true>
														<option value='select_school' >Select a district first</option>
														</select> <i></i>
													</label>
													</section>
													
										</fieldset>
										</form>
									</div>
									<div>
									<br>
									<p class="sanitation_infra_note">&nbsp;&nbsp;Note : To get sanitation infrastructure chart, please select the district and school.</p>
									<div id="sanitation_chart" class="row" style="min-height:150px;">
									
								   </div>
									</div>
								</div>
								
								</div>
							

						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->
                 </article>

				</div>
				<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
           <table class="col-lg-5 table-bordered table-condensed cf">
		  <thead>
		  <tr>
		   <th>Item</th>
		   <th>Value</th>
		  </tr>
		  
		  </thead>
		  <tbody>
		  <?php if($sanitation_submitted_schools){
			  $i=1;
		   foreach($sanitation_submitted_schools as $row){ 
			   ?>
		
		<tr id="handwash<?php echo $i; ?>" class="handwash-popup-data">
			
		   	<td>Hand sanitizers/soap used</td>
		   <td><?php echo $row['doc_data']['widget_data']["page1"]['Hand Wash']['Hand sanitizers/soap used'];?></td>
		</tr>

		<tr id="kitchen<?php echo $i; ?>" class="kitchen-popup-data">
			<td>Food stored and served with tight containers</td>
		   <td><?php echo $row['doc_data']['widget_data']["page1"]['Kitchen']['Food stored and served with tight containers'];?></td>
		</tr>

		<tr id="kitchen2<?php echo $i; ?>" class="kitchen-popup-data2">
		   	<td>Availabilities of storage of perishable products</td>
		   <td><?php echo $row['doc_data']['widget_data']["page1"]['Kitchen']['Availabilities of storage of perishable products'];?></td>
		</tr>
		
		 <?php
		 $i++;
		 }
	   }?>
		  
		  </tbody>
		  </table>

		

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
				
			<!-- row -->
			<!-- end row -->
			</div>
				<br/>
                 <br/>
                 <br/>
</div>
<!-- END MAIN PANEL -->
			
</div>
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


<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.barnumbers.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.orderBar.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.axislabels.js"></script>
<script src="<?php echo JS; ?>plugin/morris/raphael.min.js"></script>
<script src="<?php echo JS; ?>plugin/morris/morris.min.js"></script>
<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>



<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
$(document).ready(function() {

	var today_date = $('#set_data').val();

	var dt_name = $('#select_dt_name').val();
	var school_name = $('#school_name').val();
	

$('.datepicker').datepicker({
	minDate: new Date(1900, 10 - 1, 25)
 });
initialize_variables(today_date);

change_to_default();

function change_to_default(today_date,absent_report,request_report,symptoms_report,screening_report){
	$('#request_pie_span').val("Monthly");
	$('#screening_pie_span').val("Yearly");
	
	//$('#set_data').val(today_date);
	//$('#select_dt_name').val(dt_name);
	$('#school_name').val(school_name);
}

function initialize_variables(today_date/*,absent_report,request_report,symptoms_report,screening_report*/){
	console.log('init fn', today_date);
	today_date = today_date;
	console.log('init fun222222', today_date);

	/*init_absent_pie(absent_report);
	init_req_id_pie(request_report,symptoms_report);
	init_screening_pie(screening_report);*/
}

$('#set_date_btn').click(function(e){
	today_date = $('#set_data').val();
	//alert(today_date);
	//location.reload();
	//$('#load_waiting').modal('show');

	$.ajax({
		url: 'dashboard_reports_with_date',
		type: 'POST',
		data: {"today_date" : today_date, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			$('#load_waiting').modal('hide');
			console.log('aaaaaaaaaaaaaa',data);
			data = $.parseJSON(data);
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	
});

		$('.handwash-popup-data').hide();
			var that;
			$(document).on('click','.details',function()
			{
				console.log("Entered details")
				that = $(this).attr("id")
				$('.handwash-popup-data').hide();
				$('#handwash'+that+'').show()
				$('#myModal').modal('show');
		})

		$('.kitchen-popup-data').hide();
			var that;
			$(document).on('click','.details',function()
			{
				console.log("Entered details")
				that = $(this).attr("id")
				$('.kitchen-popup-data').hide();
				$('#kitchen'+that+'').show()
				$('#myModal').modal('show');
		})
		$('.kitchen-popup-data2').hide();
			var that;
			$(document).on('click','.details',function()
			{
				console.log("Entered details")
				that = $(this).attr("id")
				$('.kitchen-popup-data2').hide();
				$('#kitchen2'+that+'').show()
				$('#myModal').modal('show');
		})

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

	

//===================================drill down pie======================
//===================================end of dril down pie================
</script>

