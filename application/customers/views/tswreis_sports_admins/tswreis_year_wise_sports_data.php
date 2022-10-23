<?php $current_page = "Year Wise"; ?>
<?php $main_nav = ""; ?>
<?php include('inc/header_bar.php'); ?>
<br>
<br>
<br>
<br>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
				<div class="header">
					<h2>Achievements List<span class="badge bg-color-greenLight"></span></h2>
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
								<th>Student Name</th>
								<th>Father Name</th>
								<th>Class</th>
								<th>Date</th>
								<th>Year</th>
								<th>Venue</th>
								<th>Event</th>
								<th>Institution</th>
								<th>District</th>
								<th>Action</th>
								
							</tr>
						</thead>
						<tbody>
				        
							<tr>
								<td>Solon Xavier</td>
								<td>John Xavier</td>
								<td>9</td>
								<td>12-06-2018</td>
								<td>2018</td>
								<td>Uppal Stadium</td>
								<td>State Level</td>
								<td>Tswreis</td>
								<td>Hyderabad</td>
								<td>
									<a href="<?php echo URL."tswreis_sports_mgmt/tswreis_electronic_sports_record"?>"><button type="button" class="btn bg-teal waves-effect">Personal Info</button></a>
								</td>
							</tr>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('inc/footer_bar.php'); ?>