<?php  $current_page = ""; ?>
<?php  $main_nav = ""; ?>
<?php include('inc/header_bar.php');?>
<?php include('inc/sidebar.php');?>

<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
				<div class="header">
					<h2>Pending Registrations List<span class="badge bg-color-greenLight"></span></h2>
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
								<th>Districts</th>
								<th>Counts</th>
							</tr>
						</thead>
						<tbody>
							
							<?php foreach($pending_requests as $key => $value): ?>
				        	<tr>
				        		<td><?php echo $key;?></td>
				        		<td><?php echo $value;?></td>
				        	</tr>
				        	<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<?php include('inc/footer_bar.php');?>