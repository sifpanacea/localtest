<?php $current_page="registered_swaero_list"; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
				<div class="header">
					<h2>Registered Saeros List<span class="badge bg-color-greenLight"></span></h2>
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
								<th>Date</th>
								<th>News Feed</th>
								
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
				       		<tr>
				       			<td></td>
				       			<td></td>
				       			<td></td>
				       		</tr>
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