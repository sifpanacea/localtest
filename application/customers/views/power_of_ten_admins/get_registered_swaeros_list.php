<?php $current_page="registered_swaero_list"; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
				<div class="header">
					<h2>Registered Swaero's List<span class="badge bg-color-greenLight"></span></h2>
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
								<th>Name</th>
								<th>Gender</th>
								<th>Alumni Of</th>
								<th>Mobile Number</th>
								<th>Qualification</th>
								<th>Institute Name</th>
								<th>Passed Out Year</th>
								<th>District</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($registered)):?>
								<?php foreach ($registered as $register): ?>
				       		<tr>
				       			<td><?php echo $register['doc_data']['name']; ?></td>
				       			<td><?php echo $register['doc_data']['gender']; ?></td>
				       			<td><?php echo $register['doc_data']['are_you_alumni_of']; ?></td>
				       			<td><?php echo $register['doc_data']['mobile_no']; ?></td>
				       			<td><?php echo $register['doc_data']['course_name']; ?></td>
				       			<td><?php echo $register['doc_data']['institution_name']; ?></td>
				       			<td><?php echo $register['doc_data']['passed_out_year']; ?></td>
				       			<td><?php echo $register['doc_data']['district']; ?></td>
				       			<td><a href='<?php echo URL."power_of_ten_mgmt/tswreis_swaero_electronic_record"; ?>'>
				       			    <button type="button" id="" class="btn bg-blue waves-effect">Show ESR</button></a>
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
</section>





								
							


<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include footer
	include("inc/footer_bar.php"); 
?>