<?php $current_page="Payment_info"; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">

<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
				<div class="header">
					<h2>Swaero's Payment Information<span class="badge bg-color-greenLight"></span></h2>
					<ul class="header-dropdown m-r--5">
					    <div class="button-demo">
					    <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
					    </div>
					</ul>
            	</div>					
				<div class="body">
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
		                        <div class="form-line">
		                            <input type="text" id="search_by_details" name="" placeholder="Search by Mobile Number or Mail ID" name="" class=" form-control">
		                        </div>
		                    </div>
	                    </div> 
	               		<div class="col-sm-4">	
	                		<button type="button" id="fetch_data_using_search" class="btn bg-pink waves-effect">GET</button>
	                	</div>
					</div>
					<div id="payment_data_table"></div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
    $attributes = array('class' => '','id'=>'followup_form','name'=>'userform');
    echo  form_open('power_of_ten_mgmt/update_payment_details',$attributes);
 ?>

<div class="modal fade" id="payment_details" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Payments Details</h4>
            </div>
            <div class="modal-body">
            	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            		<input type="hidden" name="doc_id" id="doc_id">
                   <div class="form-group">
                        <label>Name</label>
                        <div class="form-line">
                            <input type="text" id="doc_name" name="doc_name" class="form-control" placeholder="Name" value="" />
                        </div>
                   </div> 
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                   <div class="form-group">
                        <label>Email ID</label>
                        <div class="form-line">
                            <input type="text" id="doc_email" name="doc_email" class="form-control" placeholder="Mail ID" value="" />
                        </div>
                   </div> 
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                   <div class="form-group">
                        <label>Mobile Number</label>
                        <div class="form-line">
                            <input type="text" id="doc_phone" name="doc_phone" class="form-control" placeholder="Mobile Number" value="" />
                        </div>
                   </div> 
                </div>
            	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                   <div class="form-group">
                        <label>Transation Number</label>
                        <div class="form-line">
                            <input type="text" name="transaction_no" class="form-control" placeholder="Transation Number" value="" />
                        </div>
                   </div> 
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                   <div class="form-group">
                        <label>Transation Date</label>
                        <div class="form-line">
                            <input type="text" id="set_date" name="set_date" class="datepicker form-control date set_date" value="<?php echo date('Y-m-d'); ?>" readonly="readOnly">
                        </div>
                   </div> 
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                   <div class="form-group">
                        <label>Amount</label>
                        <div class="form-line">
                            <input type="text" name="amount" class="form-control" placeholder="Amount" value="120"  readonly="readOnly" />
                        </div>
                   </div> 
                </div>
              
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-link waves-effect" >SUBMIT</button>
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<?php form_close(); ?>

<!-- Moment Plugin Js -->
<script src="<?php echo MDB_PLUGINS."momentjs/moment.js"; ?>"></script>

<!-- Demo Js -->
<script src="<?php echo(MDB_JS.'demo.js'); ?>"></script>

<!-- Bootstrap Datepicker Plugin Js -->
<script src="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script> 

<?php 
	//include footer
	include("inc/footer_bar.php"); 
?>


<script type="text/javascript">
	var today_date = $('#set_date').val();
	$('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
	$('#set_date').change(function(e){
	        today_date = $('#set_date').val();
	});

	$('#fetch_data_using_search').click(function(){

		var search_param = $('#search_by_details').val();
		alert(search_param);
		$.ajax({
			url:'get_searched_payment_data',
			type: 'POST',
			data: {'search_param':search_param },
			success:function(data){ 

				var result = $.parseJSON(data);

				console.log("datafromusers", result);

				get_data_on_search(result);
			}
		})

		
	})

	function get_data_on_search(result){
	data_table = '<table id="" class="table table-bordered table-striped table-hover dataTable js-exportable"><thead><tr><th class="hidden">doc_id</th><th>Swaero Name</th><th>Mobile Number</th><th>Email ID</th><th>Accept</th><th>Decline</th></tr></thead><tbody>';

		

	$.each(result, function(index, value){
		data_table = data_table + '<tr>';
		data_table = data_table + '<td class="hidden">'+value.doc_properties_id+'</td>';
		data_table = data_table + '<td>'+value.username+'</td>';
		data_table = data_table + '<td>'+value.phone_no+'</td>';
		data_table = data_table + '<td>'+value.email+'</td>';

		data_table = data_table + '<td><button class="btn btn-primary waves-effect schedule_followup" doc_id='+value.doc_properties_id+' name='+value.username+' phone='+value.phone_no+' email='+value.email+' ph type="button"><i class="material-icons">done</i></button></button></td>';

		data_table = data_table + '<td><button type="button" class="btn btn-danger waves-effect"  id="decline_btn"><i class="material-icons">delete</i></button></td>';
		data_table = data_table + '</tr>';
	});
	

	data_table = data_table + '</tbody></table>';

	$('#payment_data_table').html(data_table);

	$('.schedule_followup').click(function(){
		
		var doc_id = $(this).attr('doc_id');
		var name = $(this).attr('name');
		var phone = $(this).attr('phone');
		var email = $(this).attr('email');
		
		
		$('#doc_id').val(doc_id);
		$('#doc_name').val(name);
		$('#doc_phone').val(phone);
		$('#doc_email').val(email);
		$("#payment_details").modal("show")

	});

	}

	

	
</script>

