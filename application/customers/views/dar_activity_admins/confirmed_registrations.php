<?php $current_page = "Conformed Registrations"; ?>
<?php $main_nav = ""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<section class="content">
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
				<div class="header">
					<h2>Registrations Conformed List<span class="badge bg-color-greenLight"></span></h2>
					<ul class="header-dropdown m-r--5">
					    <div class="button-demo">
					    <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
					    </div>
					</ul>
            	</div>					
				<div class="body">
					<table id="datatable_fixed_column" class="table table-bordered table-striped table-hover dataTable js-exportable">
						<thead>
							<tr>
								<th class="hidden">Swaero Id</th>
								<th>Swaero Name</th>
								<th>Mobile Number</th>
								<th>Institution Name</th>
<!-- 								<th>Passout Year</th> -->
								<th>Questions Answered</th>
								<th>Accept</th>
								<th>Decline</th>
							</tr>
						</thead>
						<tbody>
				        	<?php if(isset($confirmed_list)): ?>
				        		<?php foreach($confirmed_list as $Con): ?>
							<tr>
								<td id="docID" class="hidden"><?php echo $Con['doc_properties']['doc_id']; ?></td>
								<td><?php echo $Con['doc_data']['name']; ?></td>
								<td><?php echo $Con['doc_data']['mobile_no']; ?></td>
								<td><?php echo $Con['doc_data']['institution_name']; ?></td>
								<!-- <td><?php //echo $Con['doc_data']['passed_out_year']; ?></td> -->
								<td><textarea rows="2" cols="50" class="form-control no-resize" placeholder="Answered by District Coordinators"></textarea></td>
								<!-- <textarea rows="1" class="form-control no-resize auto-growth" placeholder="Please type what you want... And please don't forget the ENTER key press multiple times :)"></textarea> -->
								<!-- <td>
									<a href="<?php //echo URL."dar_activity_mgmt/tswreis_electronic_sports_record"?>"><button type="button" class="btn bg-teal waves-effect">Personal Info</button></a>
								</td> -->
								<td>
				        			<button type="button" id="accept_dar_registrations" class="btn bg-blue waves-effect">
                                    	<i class="material-icons">done</i>
                                	</button>
                                </td>
								<td>	
                                	<button type="button" id="reject_dar_registrations" class="btn bg-red waves-effect">
                                    	<i class="material-icons">delete</i>
                                	</button>
				        		</td>
							</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</section>
<?php include('inc/footer_bar.php'); ?>

<script type="text/javascript">
	
	$('#accept_dar_registrations').click(function(){
		//alert('heloooo');
		var doc_id = $('#docID').text();
		//alert(doc_id);
		$.ajax({
			url : 'accept_conformed_registrations',
			type: 'POST',
			data:{"doc_id":doc_id},
			success:function(data){
				var data = $.parseJSON(data);

				if(data == "User Accepted"){
					alert(data);
					location.reload();
				}else{
					alert(data);
				}

			}
		});
	})


	$('#reject_dar_registrations').click(function(){
		
		var doc_id = $('#docID').text();
		alert(doc_id);
		$.ajax({
			url : 'decline_conformed_registrations',
			type: 'POST',
			data:{"doc_id":doc_id},
			success:function(data){
				var data = $.parseJSON(data);

				if(data == "Successfully Deleted"){
					location.reload();
				}

			}
		});
	})
		
	

	
		
	
</script>