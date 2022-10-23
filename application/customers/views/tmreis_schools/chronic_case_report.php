<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Chronic Case Report";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa chronic_report"]["active"] = true;
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
							<h2>Chronic Cases <span class="badge bg-color-greenLight"><?php if(!empty($case_count)) {?><?php echo $case_count;?><?php } else {?><?php echo "0";?><?php }?></span></h2>
		
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
					<?php $a = 0;?>
					<?php if ($cases): ?>
					<thead>
					<tr>
						<th>Hospital Unique ID</th>
						<th>Disease</th>
						<th>Description</th>
						<th>Request Created</th>
						<th>Follow Up</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($cases as $case):?>
					<tr>
						<td class='unique_id'><?php echo $case['student_unique_id'];?>
						<?php if(isset($case['chronic_disease']) && !empty($case['chronic_disease'])):?>
						<td><?php echo implode(",",$case['chronic_disease']);?></td>
						<?php else:?>
						<td><?php echo " ";?></td>
						<?php endIf;?>
						<td><?php echo $case['disease_desc'];?></td>
						<td><?php echo $case['created_time'];?></td>
						<td><?php if($case['followup_scheduled']=="false"):?><a href="javascript:void('0')" uid='<?php echo $case['student_unique_id'];?>' cid='<?php echo $case['case_id'];?>' class='schedule_followup'>Create Schedule</a><?php else:?><a href="javascript:void('0')" uid='<?php echo $case['student_unique_id'];?>' sdate='<?php echo $case['start_date'];?>' schedule='<?php echo json_encode($case['medication_schedule']);?>' cid='<?php echo $case['case_id'];?>' class='update_followup'>Feed Data</a><?php endIF;?></td>
					</tr>
					<?php $a++;?>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<center><label>No cases added</label></center>
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

		<!-- Modal -->
		<div class="modal fade" id="schedule_followup_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title" id="myModalLabel">Create Follow Up</h4>
					</div>
					<div class="modal-body">
					<form class="smart-form">
					<fieldset>
					   <section>
					   <label class="input"> <i class="icon-append fa fa-calendar"></i>
													<input type="text" name="medic_start_date" id="medic_start_date" placeholder="Select start date" class="hasDatepicker">
												</label>
					   </section>
					   <section>
							<label class="label">Medication to be taken</label>
							<div class="inline-group">
								<label class="checkbox">
									<input type="checkbox" name="med_to_be_taken" value="mor" id="med_taken_mrng">
									<i></i>Morning</label>
								<label class="checkbox">
									<input type="checkbox" name="med_to_be_taken" value="noon" id="med_taken_aftn">
									<i></i>Afternoon</label>
								<label class="checkbox">
									<input type="checkbox" name="med_to_be_taken" value="night" id="med_taken_night">
									<i></i>Night</label>
							</div>
						</section>
						<section>
							<label class="label">Period of treatment</label>
							<label class="input"> <i class="icon-append fa fa-question-circle"></i>
								<input type="number" class="period_to_be_taken">
								<b class="tooltip tooltip-top-right">
									<i class="fa fa-info-circle txt-color-teal"></i> 
									Number of days</b> 
							</label>
						</section>
						<input type='hidden' class='hospital_unique_id' value="" />
						<input type='hidden' class='case_id' value="" />
					</fieldset>
					</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Cancel
						</button>
						<button type="button" class="btn btn-primary complete_schedule_followup">
							Create
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
				
        
		<!-- Modal -->
		<div class="modal fade" id="update_followup_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title" id="myModalLabel">Update Follow Up</h4>
					</div>
					<div class="modal-body">
					<form class="smart-form">
					   <fieldset>
					   <section>
												<label class="input"> <i class="icon-append fa fa-calendar"></i>
													<input type="text" name="today_date" id="today_date" placeholder="Select date" class="hasDatepicker">
												</label>
											</section>
						<section>
											<label class="label">Medication Taken</label>
											<div class="inline-group">
												<label class="checkbox state-disabled">
													<input type="checkbox" name="med_taken" value="mor" id="update_med_mrng">
													<i></i>Morning</label>
												<label class="checkbox state-disabled">
													<input type="checkbox" name="med_taken" value="noon" id="update_med_aftn">
													<i></i>Afternoon</label>
												<label class="checkbox state-disabled">
													<input type="checkbox" name="med_taken" value="night" id="update_med_night">
													<i></i>Night</label>
											</div>
										</section>
										<input type='hidden' class='hospital_uid' value="" />
										<input type='hidden' class='case_id' value="" />
										</fieldset>
										</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default complete_update_followup_cancel" data-dismiss="modal">
							Cancel
						</button>
						<button type="button" class="btn btn-primary complete_update_followup">
							Update
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		
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
						content   : "Please select All Fields!",
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
		    $("#chronic_report_table thead th input[type=text]").on('keyup change', function () {
		    	
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