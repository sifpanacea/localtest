<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Monthly Request Chart";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["weekly_doctor_visit"]["active"] = true;
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
							<h2> Chronic students on Month Wise <span class="badge bg-color-greenLight"><?php if(!empty($student_list)) {?><?php echo count($student_list);?><?php } else {?><?php echo "0";?><?php }?></span></h2>
		
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
					<table id="chronic_report_table" class="table table-striped table-bordered table-hover">
					
					<?php if ($student_list): ?>
					<thead>
					<tr>
						<th>Student Unique ID</th>
						<th>Student Name</th>
						<th>Class</th>
						<th>Section</th>
						<!-- <th>Health Issues</th> -->
						<th>Student Profile</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($student_list as $student):?>
						<tr>
                   		<td id ="uid" name= "uid"><?php echo $student['doc_data']['widget_data']['page1']['Student Info']['Unique ID']; ?></td>
                   		<td><?php echo $student['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref']; ?></td>
                   		<td><?php echo $student['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref']; ?></td>
                   		<td><?php echo $student['doc_data']['widget_data']['page1']['Student Info']['Section']['field_ref']; ?></td>
                   		<td><a href="#" class="btn bg-color-greenDark txt-color-white btn-xs">Show</a></td>
                   		</tr>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<center><label>No Reports Found</label></center>
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
			
//var a = <?php //echo json_encode($student['doc_data']['widget_data']['Student Details']['Hospital Unique ID']); ?>;
			
		//$(document).on('click','.show_profile',function(e){
			$('#show_profile').click(function(){
				debugger;
				console.log('nareshhhhh');
				//a[0]["doc_data"]["widget_data"]["Student Details"]["Hospital Unique ID"]
			var unique_id        = $('#uid').text();
			//debugger;

			//var case_id_         = $('.case_id').val();
			//var selected_date =  <?php //echo $doctor_visiting_date; ?> 
			
			$.ajax({
			url    : 'show_student_naresh',
			type   : 'POST',
			data   : {"unique_id" : unique_id},
			success: function (data) {
			
			    console.log("dataaaaaaaaaaaa",data);
				
						
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});

			})


		
		
		
		
		
		
		 
		  
		//})


	});

</script>
	
<?php 
	//include footer
	include("inc/footer.php"); 
?>