<?php  $current_page = ""; ?>
<?php  $main_nav = ""; ?>
<?php include('inc/header_bar.php');?>
<?php include('inc/sidebar.php');?>

<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
				<div class="header">
					<h2>Alumni Request's<span class="badge bg-color-greenLight"></span></h2>
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
								<th>Email ID</th>
								<th>Mobile Number</th>
								<th>Request Type</th>
								<th>Request Description</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($requests)):?>
								<?php foreach ($requests as $request): ?>
				       		<tr>
				       			<td><?php echo $request['email_id']; ?></td>
				       			<td><?php echo $request['mobile']; ?></td>
				       			<td><?php echo $request['request_type']; ?></td>
				       			<td><?php echo $request['request_description']; ?></td>
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
<?php include('inc/footer_bar.php');?>
