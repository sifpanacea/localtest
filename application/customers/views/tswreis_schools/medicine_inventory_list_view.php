<?php

require_once("inc/config.ui.php");
$page_title = "Medicine Inventory List";
$page_css[] = "your_style.css";
include("inc/header.php");


$page_nav["medicine"]["sub"]["medicine_list"]["active"] = true;
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
						
						<header class ="bg-color-orange txt-color-white">
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							
					<h2>Total Medicine Count : <span class="badge bg-color-orange"><?php if(!empty($count)) { ?><?php echo $count; ?><?php }else { ?><?php echo 0 ; ?><?php } ?></span></h2>

						</header>
<!-- 
						<?php //if(!empty($count)) : ?>

							<?php //echo $count; ?>

							<?php //else : ?>
							<?php //echo 0; ?>

						<?php //endif; ?> -->
		
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
					
					<?php if (isset($tab_data) && !empty($tab_data)):?>

					<thead>
					<tr>
						<th>Type of Medicine</th>
						 <th>Medicine Name</th>
						<th>Batch No</th>
						<th>Quantity</th>
					</tr>
					</thead>
					<tbody>						
						<?php $datas = $tab_data[0]['doc_data']['widget_data']['page2']['medicine_names']; ?>
                    	<?php foreach($datas as $data): ?>						
                    <tr>                    	
						<td><?php echo $data['med_type'];?></td>
					   <td><?php echo $data['med_name'];?></td>
						<td><?php echo $data['batch_no'];?></td>
						<td><?php echo $data['med_qty'];?></td>						
					</tr>
					<?php endforeach; ?>					
					<?php else: ?>
        			<p>
          				<center><label>No Medicine List added</label></center>
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
		else{
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
		console.log('selected_date=======',selected_date);
		
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
		else{
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
