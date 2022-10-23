<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "My Appointments";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["appointment"]["sub"]["my_appointments"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<style>
.txt-color-bluee
{
color:#214e75;!important
}
</style>

<link href="<?php echo CSS; ?>user_dash.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?php echo CSS; ?>smartadmin-production.css"/>
<div id="main" role="main">
<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
	<div id="content">
<!-- row -->
				<div class="row">
					
					<!-- col -->
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark">
							
							<!-- PAGE HEADER -->
							<i class="fa-fw fa fa-stethoscope"></i> 
								Appointment 
							<span>>  
								Details
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
							<div class="jarviswidget well" id="wid-id-0">
								<header>
									<span class="widget-icon"> <i class="fa fa-comments"></i> </span>
									<h2>Widget Title </h2>				
									
								</header>

								<!-- widget div-->
								<div>
									
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										<input class="form-control" type="text">	
									</div>
									<!-- end widget edit box -->
									
									<!-- widget content -->
									<div class="widget-body no-padding">
										<table id="example" class="display projects-table table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									        <thead>
									            <tr>
													<th><i class="fa fa-fw fa-envelope text-muted hidden-md hidden-sm hidden-xs"></i> Date </th>
													<th><i class="fa fa-fw fa-ticket text-muted hidden-md hidden-sm hidden-xs"></i> Time </th>
													<th><i class="fa fa-fw fa-user-md text-muted hidden-md hidden-sm hidden-xs"></i> Doctor </th>
									                <th><i class="fa fa-fw fa-hospital text-muted hidden-md hidden-sm hidden-xs"></i> Hospital </th>
													<th><i class="fa fa-fw fa-wheelchair text-muted hidden-md hidden-sm hidden-xs"></i> Reasion of visit </th>
													<th><i class="fa fa-fw fa-gear text-muted hidden-md hidden-sm hidden-xs"></i> options </th>
									            </tr>
									        </thead>
									    </table>
									</div>
									<!-- end widget content -->
								</div>
								<!-- end widget div -->
							</div>
							<!-- end widget -->
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
				
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
    
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Appointment</h4>
      </div>
      <div class="modal-body">
        <div class="widget-body">
        <form action="../patient_login/edit_appointment" method='post' id='appointment_form' class="smart-form" novalidate="novalidate">
        <input type="hidden" id="user_email" name="user_email" value="" />
        <input type="hidden" id="app_id" name="app_id" value="" />
        <fieldset>
			<section>
				<div class="row">
					<label class="label col col-3">Appointment purpose</label>
					<div class="col col-9">
						<label class="input"> <i class="icon-append fa fa-user"></i>
							<input type="text" name="text" id='appointment_title'>
						</label>
					</div>
				</div>
			</section>
		</fieldset>
        </form>
        </div>
      </div>
      <div class="modal-footer">
      	<button type="button" class="btn btn-success" id='form_submit'>Edit appointment</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      
    </div>

  </div>
</div>
</div>
<!-- END MAIN PANEL -->
<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<!-- PAGE RELATED PLUGIN(S) -->
<script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>
<script>

$(document).ready(function() {
//PAGE RELATED SCRIPTS
	
	var table = $('#example').dataTable( {
     "data": <?php echo $data;?>,
		"bDestroy": true,
	    "iDisplayLength": 12,
	     "columns": [
			            { "data": "appointment_date" },
			            { "data": "appointment_time" },
			            { "data": "username" },
			            { "data": "company" },
			            { "data": "appointment_title" },
			            {     // fifth column (Edit link)
			                "sName": "RoleId",
			                "bSearchable": false,
			                "bSortable": false,
			                "mRender": function (data, type, full) {
			                    return "<a href='#'><button class='btn btn-warning btn-xs book_appointment' appid='"+full._id['$id']+"' userid='"+btoa(full.user_id)+"' appointment_title='"+full.appointment_title+"'>Edit</button></a>&nbsp&nbsp&nbsp&nbsp<a href='../patient_login/delete_appointment/"+full._id['$id']+"/"+btoa(full.user_id)+"'><button class='btn btn-danger btn-xs'>Delete</button></a>";
			               }
			            },
		        	],
		"order": [[0, 'asc']],
	        "fnDrawCallback": function( oSettings ) {
		       runAllCharts()
		    }
	} );

	$(document).on("click",'.book_appointment',function(e)
	{
		
		var app_id = $(this).attr("appid");
		var user_id = $(this).attr("userid");
		var appointment_title = $(this).attr("appointment_title");
		$('#app_id').val(app_id);
		$('#user_email').val(user_id);
		$('#appointment_title').val(appointment_title);
		
		$('#myModal').modal("show");
	});
	$(document).on("click",'#form_submit',function(e)
	{
		$('#appointment_form').submit();
	});
	
});

</script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>

