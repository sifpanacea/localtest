<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "BMI Reports";

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
							<h2><?php echo $bmi_case_type; ?><span class="badge bg-color-greenLight"><?php if(!empty($get_bmi_docs)) {?><?php echo count($get_bmi_docs);?><?php } else {?><?php echo "0";?><?php }?></span></h2>
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
					<table id="chronic_report_table" class="table table-striped table-bordered table-hover">
					<?php if ($get_bmi_docs): ?>
					<thead>
					<tr>
						<th>Hospital Unique ID</th>
						<th>Student Name</th>
						<th>Class</th>
						<th>Section</th>
						<th>District</th>
						<th>School Name</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($get_bmi_docs as $bmi):?>
					<tr>
						
						<td><?php echo $bmi['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'];?></td>
						<?php if(isset($bmi['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref']) && !empty($bmi['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref']) && !is_null($bmi['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref'])): ?>
						<td><?php echo $bmi['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref'];?></td>
						<?php else: ?>
							<td><?php echo ""; ?></td>
					<?php endif; ?>
					<?php if(isset($bmi['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref']) && !empty($bmi['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref']) && !is_null($bmi['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref'])): ?>
						<td><?php echo $bmi['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref'];?></td>
							<?php else: ?>
							<td><?php echo ""; ?></td>
					<?php endif; ?>
					<?php if(isset($bmi['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref']) && !empty($bmi['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref']) && !is_null($bmi['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref'])): ?>
						<td><?php echo $bmi['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref'];?></td>
							<?php else: ?>
							<td><?php echo ""; ?></td>
					<?php endif; ?>
						<td><?php echo $bmi['doc_data']['widget_data']['school_details']['District'];?></td>
						<td><?php echo $bmi['doc_data']['widget_data']['school_details']['School Name'];?></td>
						<td> <a class='ldelete' href='<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_display_ehr_uid/"?>? id = <?php echo $bmi['doc_data']['widget_data']["page1"]['Student Details']['Hospital Unique ID'];?>'>
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
	$(document).ready(function(){
	    
		 /* var nowDate = new Date();
		 var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0); */
		 var selected_date    = "";
		 var medic_start_date = "";
		
		$("#medic_start_date").datepicker({
            todayHighlight:true,
	        format:"yyyy-mm-dd",
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
        }).on("changeDate", function (e) {
		   medic_start_date = e.target.value;
        });
		
		$("#today_date").datepicker({
            todayHighlight:true,
	        format:"yyyy-mm-dd",
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
        }).on("changeDate", function (e) {
		   selected_date = e.target.value;
        });
	
		$(document).on('click','.schedule_followup',function(){
		 
		 var uid = $(this).attr('uid');
		 var cid = $(this).attr('cid');
		 $('.hospital_unique_id').val(uid);
		 $('.case_id').val(cid);
		 $("#schedule_followup_modal").modal("show")
		  
		})
		
		$(document).on('click','.update_followup',function(){
		 var uid      = $(this).attr('uid');
		 var caseid   = $(this).attr('cid');
		 var sdate    = $(this).attr('sdate');
		 var schedule = $(this).attr('schedule');
		 schedule     = JSON.parse(schedule);
		 for(var i in schedule)
		 {
	       $('input:checkbox[name="med_taken"][value="'+schedule[i]+'"]').parent('label').removeClass('state-disabled');
	       $('input:checkbox[name="med_taken"][value="'+schedule[i]+'"]').parent('label').removeClass('checkbox_view_only');
		 }
		 $('.hospital_uid').val(uid);
		 $('.case_id').val(caseid);
		 $('#today_date').datepicker('setStartDate',sdate);
		 $("#update_followup_modal").modal("show")
		  
		})
		
		$(document).on('click','.complete_schedule_followup',function(){
		
		var unique_id           = $('.hospital_unique_id').val();
		var case_id             = $('.case_id').val();
		var treatment_period    = $('.period_to_be_taken').val();
		var medication_schedule = [];
		
		$. each($("input[name='med_to_be_taken']:checked"), function(){
		   medication_schedule.push($(this).val());
		});
		
		if(($('#medic_start_date').val() != '') && ($('#period_to_be_taken').val() != '') && ($("#med_taken_mrng").is(':checked')) || ($("#med_taken_aftn").is(':checked')) || ($("#med_taken_night").is(':checked')))
		{
		$.ajax({
			url    : 'create_schedule_followup',
			type   : 'POST',
			data   : {"start_date":medic_start_date,"unique_id" : unique_id, "medication_schedule" : medication_schedule, "treatment_period" : treatment_period,"case_id":case_id},
			success: function (data) {			
				if(data=='SCHEDULE_CREATION_COMPLETED')
				{
			       $('#schedule_followup_modal').modal('hide');
				   window.location.reload();
			       $.smallBox({
						title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
						content   : "Created Successfully !",
						color     : "#296191",
						iconSmall : "fa fa-bell bounce animated",
						timeout   : 4000
			      });
				  
				}
				else
				{
			       $('#schedule_followup_modal').modal('hide');
				   window.location.reload();
			       $.smallBox({
						title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Error",
						content   : "Creation Failed !",
						color     : "#C46A69",
						iconSmall : "fa fa-bell bounce animated",
						timeout   : 4000
			      });
				  
				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});
		}
		else
		{
			$.smallBox({
						title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Error",
						content   : "Please select Medication Time and Period of treatment!",
						color     : "#D54841",
						iconSmall : "fa fa-bell bounce animated",
						timeout   : 4000
			      });
		}
		})
		
		$(document).on('click','.complete_update_followup',function(e){
		
		var unique_id        = $('.hospital_uid').val();
		var case_id_         = $('.case_id').val();
		var medication_taken = [];
		
		$.each($("input[name='med_taken']:checked"), function(){
		   medication_taken.push($(this).val());
		});
		
		console.log('medication_taken',medication_taken);
		if(($('#today_date').val() != '') && ($("#update_med_mrng").is(':checked')) || ($("#update_med_aftn").is(':checked')) || ($("#update_med_night").is(':checked')))
		{
		$.ajax({
			url    : 'update_schedule_followup',
			type   : 'POST',
			data   : {"selected_date":selected_date,"case_id" : case_id_,"unique_id" : unique_id, "medication_taken" : medication_taken},
			success: function (data) {
			
			    if(data=='SCHEDULE_ALREADY_UPDATED')
				{
			       $('#update_followup_modal').modal('hide');
				   $("#today_date").val("");
				   $.each($("input[name='med_taken']:checked"), function(){
					   $(this).attr('checked',false);
				   });
			       $.smallBox({
						title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Error",
						content   : "Already updated for the selected date !",
						color     : "#C46A69",
						iconSmall : "fa fa-bell bounce animated",
						timeout   : 4000
			      });
				  
				}
				else if(data=='SCHEDULE_UPDATE_COMPLETED')
				{
			       $('#update_followup_modal').modal('hide');
				   $("#today_date").val("");
				   $.each($("input[name='med_taken']:checked"), function(){
					   $(this).attr('checked',false);
				   });
			       $.smallBox({
						title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
						content   : "Updated Successfully !",
						color     : "#296191",
						iconSmall : "fa fa-bell bounce animated",
						timeout   : 4000
			      });
				  
				}
				else
				{
			       $('#update_followup_modal').modal('hide');
				   $("#today_date").val("");
				   $.each($("input[name='med_taken']:checked"), function(){
					   $(this).attr('checked',false);
				   });
			       $.smallBox({
						title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Error",
						content   : "Update Failed !",
						color     : "#C46A69",
						iconSmall : "fa fa-bell bounce animated",
						timeout   : 4000
			      });
				  
				}		
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});
		}
		else
		{
			$.smallBox({
						title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Error",
						content   : "Please select Medication time",
						color     : "#D54841",
						iconSmall : "fa fa-bell bounce animated",
						timeout   : 4000
			      });
		}
		  
		})
		
		$(document).on('click','.complete_update_followup_cancel',function(e){
		   window.location.reload();
		})
		
		
	})
	
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

		$('#chronic_report_table').dataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"autoWidth" : true,
			"preDrawCallback" : function() {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper_dt_basic) {
					responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#chronic_report_table'), breakpointDefinition);
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
		
		var js_url = "<?php echo JS; ?>";
			/* COLUMN FILTER  */
		    
			var otable = $('#datatable_fixed_column').DataTable({});
			
		    // Apply the filter
		    $("#chronic_report_table thead th input[type=text]").on( 'keyup change', function () {
		    	
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