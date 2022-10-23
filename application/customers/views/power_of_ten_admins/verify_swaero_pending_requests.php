<?php $current_page="Verify_Swaero_registration"; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
				<div class="header">
					<h2>Verify Swaero Registration<span class="badge bg-color-greenLight"></span></h2>
					<ul class="header-dropdown m-r--5">
					    <div class="button-demo">
					    <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
					    </div>
					</ul>
            	</div>					
				<div class="body">
					<?php if(isset($pending) && ($pending != "No data found")): ?>
						<table id="datatable_fixed_column" class="table table-bordered table-striped table-hover dataTable js-exportable">
							<thead>
								<tr>
									
									<th>Swaero Name</th>
									<th>Mobile Number</th>
									<th>District</th>
									<th>Accept</th>
									<th>Decline</th>
								</tr>
							</thead>
							<tbody>

								<?php foreach($pending as $pend): ?>
									<tr>
										
										<td><?php echo $pend['doc_data']['name']; ?></td>
										<td><?php echo $pend['doc_data']['mobile_no']; ?></td>
										<td><?php echo $pend['doc_data']['district']; ?></td>
										<td>
											<button type="button" class="btn btn-success waves-effect" accept_doc_id = "<?php echo $pend['doc_properties']['doc_id']; ?>" id="accept_btn">
			                                    <i class="material-icons">done</i>
			                                </button>
			                            </td>
										<td><button type="button" class="btn btn-danger waves-effect" decline_doc_id = "<?php echo $pend['doc_properties']['doc_id']; ?>" id="decline_btn">
		                                    <i class="material-icons">delete</i>
		                                	</button>
		                                </td>
									</tr>
								<?php endforeach; ?>
								
							</tbody>
						</table>
						<?php else: ?>
							<h4>No Data Found</h4>
					<?php endif; ?>
					
				</div>
			</div>
		</div>
	</div>
</section>


<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include footer
	include("inc/footer_bar.php"); 
?>

<script type="text/javascript">
	$("#accept_btn").click(function(){
		
		var id = $("#accept_btn").attr('accept_doc_id');
		alert(id);

		$.ajax({
			url : 'accept_registered_users',
			type : 'POST',
			data:{"doc_id" : id},
			success: function(data){
				var datas = $.parseJSON(data);

				window.alert(datas);

				location.reload();
			}
		})
	});

	// Decline a user

	$("#decline_btn").click(function(){
		
		var id = $("#decline_btn").attr('decline_doc_id');
		alert(id);

		$.ajax({
			url : 'decline_registered_users',
			type : 'POST',
			data:{"doc_id" : id},
			success: function(data){
				var datas = $.parseJSON(data);

				window.alert(datas);

				location.reload();

			}
		})
	});
</script>