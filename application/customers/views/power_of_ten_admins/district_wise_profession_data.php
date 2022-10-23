<?php $current_page=""; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php');?>
<?php include('inc/sidebar.php');?>

<section class="content">
	<div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<h2>Professions District Wise</h2>
					<ul class="header-dropdown m-r--5">
					    <div class="button-demo">
					    <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
					    </div>
					</ul>
				</div>
				<div class="body">
					<div class="row">
						<div class="col-sm-3">
							<label>Select District</label>
							<select class="form-control">
								<option value="">Hyderabad</option>
								<option value="">Adilabad</option>
								<option value="">Khammam</option>
							</select>
						</div>
						<!-- <div class="col-sm-3">
							<label>Select State</label>
							<select class="form-control">
								<option></option>
								<option></option>
								<option></option>
							</select>
						</div> -->
						<div class="col-sm-3">
							<label>Select Mandal</label>
							<select class="form-control">
								<option>Medchal</option>
								<option>Rangareddy</option>
								<option>RR</option>
							</select>
						</div>
						<div class="col-sm-3">
							<label>Select Village</label>
							<select class="form-control">
								<option>maleepalle</option>
								<option>maleepalle</option>
								<option>maleepalle</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Name</th>
										<th>Mobile Number</th>
										<th>Profession</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>praveen</td>
										<td>7894561235</td>
										<td>Doctor</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php include('inc/footer_bar.php');?>