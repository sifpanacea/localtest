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
							<h2>Regular Followup Cases <span class="badge bg-color-greenLight"><?php if(!empty($regular_followup_cases)) {?><?php echo count($regular_followup_cases);?><?php } else {?><?php echo "0";?><?php }?></span></h2>
		
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
					
					<thead>
					<tr>
						<th>Hospital Unique ID</th>
						<th>Student Name</th>
						<th>School Name</th>
						<th>Disease</th>
						<th>Status</th>
						<th>Next Followup date</th>
						<th>Feed Data</th>
						<th>EHR</th>
						<th>Close Case</th>
					</tr>
					</thead>
					<tbody>
						<?php if(!empty($regular_followup_cases)):?>
                        <?php foreach($regular_followup_cases as $index => $doc):?>
					<tr>
						<td><?php if(isset($doc['student_unique_id'])):?><?php echo $doc['student_unique_id'];?><?php else:?><?php echo "No Unique";?> <?php endif;?></td>
						<td><?php if(isset($doc['student_name'])):?><?php echo $doc['student_name'];?><?php else:?><?php echo "No Student Name";?> <?php endif;?> </td>
	                    <td><?php if(isset($doc['school_name'])):?><?php echo $doc['school_name'];?><?php else:?><?php echo "Nil";?> <?php endif;?> </td>
	                    <td><?php if(isset($doc['review_status'])):?><?php echo $doc['review_status'];?><?php else:?><?php echo "Nil";?> <?php endif;?> </td>
						<td><?php echo implode (", ",$doc['symptom']);?></td>
						<?php if(isset($doc['scheduled_date']) && (empty($doc['Follow_Up']))){

                           $current_date = date('Y-m-d')?>
                           <td>
                              
                                <?php if($doc['scheduled_date'] == $current_date): ?>
                                  <span class="label label-warning">
                                   Follow-up Today.
                                  </span>
                                  <?php elseif($doc['scheduled_date'] < $current_date):?>
                                    <span class="badge bg-red">  
                                  <?php $diff = (strtotime($current_date) - strtotime($doc['scheduled_date']))/60/60/24;
                                     
                                      echo $diff." Days Passed,\n Status Not Yet Updated";
                                  ?>
                                  </span>
                                  <?php else :?>
                                  <span class="label label-warning">
                                      <?php echo $doc['scheduled_date'];?>
                                  </span>
                                
                              <?php endif;?>
                              
                          </td>

                      <?php } else { 
                       if(isset($doc['Follow_Up'])){ 
                            $follow_up = end($doc['Follow_Up']); ?>
                          <td>
                          <?php if(isset($follow_up['next_scheduled_date'])) :?>
                          <?php $date = $follow_up['next_scheduled_date']; ?>
                          <?php $current_date = date('Y-m-d'); ?>
                          <?php if($date == $current_date): ?>
                              <span class="label label-warning">
                                       Follow-up Today.
                              </span>
                          <?php elseif($date < $current_date):?>
                              <span class="label label-warning">  
                              <?php $diff = (strtotime($current_date) - strtotime($date))/60/60/24;
                              echo $diff." Days Passed,\n Status Not Yet Updated";
                              ?>
                          </span>
                          <?php else:?>
                              <span class="label label-success">
                                <?php echo "$date"; ?>
                              </span>
                          <?php endif; ?>
                         
                          <?php else:?>
                        
                          <span class="label label-warning">
                             <?php echo "Not Set"; ?>
                          </span>
                          
                          <?php endif; ?>
                      </td>
                      <?php } ?>
                      <?php } ?>

                          <?php if(isset($doc['Follow_Up'])){  ?>

                          
                            <?php $follow_up = end($doc['Follow_Up']); ?>
                            <?php $medicine_details = $follow_up['medicine_details']; ?>
                            <?php $description_details = $follow_up['followup_desc']; ?>
                          <?php } ?>
						<td><a href="javascript:void('0')" uid='<?php echo $doc['student_unique_id'];?>' cid='<?php echo $doc['case_id'];?>' medicine = '<?php echo $medicine_details;?>' description = '<?php echo $description_details; ?>' class='schedule_followup'><button class="btn btn-primary" data-toggle="modal" data-target="#myModal">Feed Data</button></a></td>
						<form action='<?php echo URL."panacea_cc/panacea_reports_display_ehr_uid" ?>'accept-charset="utf-8" method="POST">

						<!-- <input type="text" class ="hide" name="student_unique_id" id="student_unique_id" placeholder="Focus to view the tooltip" value="<?php //echo $doc['student_unique_id'];?>"> -->

              <input type="hidden" name="uid" value="<?php echo $doc['student_unique_id'];?>">
						<td><button class="btn btn-primary">EHR</button></td>
						</form>
						<form action='<?php echo URL."panacea_cc/close_followup_request" ?>' accept-charset="utf-8" method="POST">
                        <input type="hidden" name="followupcid" value="<?php echo $doc['case_id']; ?>">
						<td><button class="btn btn-primary" onClick="return confirm('Are you sure, you want to close this Case from Follow Up list?');">Case Close</button></td>
						</form>
						
					</tr>

				<?php endforeach; ?>
			<?php endif; ?>

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
	<!-- Modal -->
	 <?php
        $attributes = array('class' => '','id'=>'followup_form','name'=>'userform');
        echo  form_open('panacea_doctor/update_regular_followup_feed_data',$attributes);
     ?>
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title" id="myModalLabel">Regular Followup</h4>
					</div>
					<div class="modal-body">
						 <div class="row clearfix">
                                  <div class="list-group">
                                <a href="javascript:void(0);" class="list-group-item active">
                                    <h4 class="list-group-item-heading">Previous Follow-up Data</h4>
                                   
                                </a>
                                <a href="javascript:void(0);" class="list-group-item">
                                    <h4 class="list-group-item-heading">Student Unique ID</h4>
                                    <p class="list-group-item-text" id="student_health_id">
                                     
                                    </p>
                                </a> 
                                <a href="javascript:void(0);" class="list-group-item">
                                    <h4 class="list-group-item-heading">Medicine Details</h4>
                                    <p class="list-group-item-text" id="medicine">
                                     
                                    </p>
                                </a>
                                <a href="javascript:void(0);" class="list-group-item">
                                    <h4 class="list-group-item-heading">Description</h4>
                                    <p class="list-group-item-text" id="description">
                                         
                                    </p>
                                </a>
                            </div>
                            <hr>
                                <input type="hidden" name="case_id" id="case_id">
                                <input type="hidden" name="student_id" id="student_id">

                                <div class="col-sm-12">
                                     <label>Date :</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="feeding_date" class="form-control" class="hasDatepicker" value="<?php echo date("Y-m-d");?>" placeholder="Select Date" readonly/>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-12">
                                     <label>Medicine Details :</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="medicine_details" class="form-control" placeholder="Enter Medicine Details If any" required="required" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                     <label>Description If any :</label>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" name="followup_desc" class="form-control" placeholder="Give Description If any" required="required"  />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                     <label>Next Follow-up Date :</label>
                                    <div class="form-group">
                                    <div class="input-group" id="bs_datepicker_container">
									<input type="text" id="next_scheduled_date" name="next_scheduled_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="">
									</div>
                                </div>
                            </div>
                               
                            </div>
		
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="reset_close">CLOSE</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		 <?php form_close(); ?>

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

<script type="text/javascript">
		$('.datepicker').datepicker({
			minDate: new Date(1900, 10 - 1, 25)
		});
	</script>

<script type="text/javascript">
        $(document).ready(function(){
            $(document).on('click','.schedule_followup',function(){
         
             var uid = $(this).attr('uid');

             var cid = $(this).attr('cid');

             var medicine = $(this).attr('medicine');
             var description = $(this).attr('description');
             console.log("medicine", medicine);

             $('#student_health_id').text(uid);
             $('#student_id').val(uid);
            
             $('#case_id').val(cid);
             $('#medicine').text(medicine);
             $('#description').text(description);
             $("#followup_modal").modal("show")
              
            })

            // Display an info toast with no title
           
            $('#reset_close').click(function(){
                $('#followup_form')[0].reset();
          });

        <?php if($this->session->flashdata('success')): ?>
                toastr.options = {
                    "positionClass": "toast-bottom-right",
                    "progressBar": true,
                    "closeButton": true,
                }
              toastr.success("<?php echo $this->session->flashdata('success'); ?>","Success")
            <?php endif; ?>

      
   
        var today_date = $('#set_date').val();
        $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
        $('#set_date').change(function(e){
                today_date = $('#set_date').val();
        });

  

  
    $('#hospitalized_students_btn').click(function(e){
        $("#loading_modal").modal('show');
       
        $.ajax({
            url: 'generate_excel_for_hospitalized_students',
            type: 'POST',
            data: '',
            success: function (data) {          
                $("#loading_modal").modal('hide');
                console.log('replyyyyyyyyyyyyyyyyyyyyyyy', data);
                window.location = data;
                },
                error:function(XMLHttpRequest, textStatus, errorThrown)
                {
                 console.log('error', errorThrown);
                }
            });
    });
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