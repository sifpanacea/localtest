<?php $current_page = "District Type"; ?>
<?php $main_nav = ""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<section class="content">
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
				<div class="header">
					<h2>Recieved At District Level List<span class="badge bg-color-greenLight"></span></h2>
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
								<th>Swaero Name</th>
								<th>Mobile Number</th>
								<th>Institution Name</th>
								<th>Passout Year</th>
								<th>District</th>
								<th>Action</th>
								
							</tr>
						</thead>
						<tbody>
				        	<?php if(isset($pending)): ?>
				        		<?php foreach($pending as $pend): ?>
							<tr>
								<td><?php echo $pend['doc_data']['name']; ?></td>
								<td><?php echo $pend['doc_data']['mobile_no']; ?></td>
								<td><?php echo $pend['doc_data']['institution_name']; ?></td>
								<td><?php echo $pend['doc_data']['passed_out_year']; ?></td>
								<td><?php echo $pend['doc_data']['district']; ?></td>
								<td>
									<a href="<?php echo URL."dar_activity_mgmt/tswreis_electronic_sports_record"?>"><button type="button" class="btn bg-teal waves-effect">Personal Info</button></a>
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