<?php $current_page = "Achievements_list"; ?>
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
								<th>Student Unique ID</th>
								<th>Student Name</th>
								<th>School Name</th>
								<th>Event</th>
								<th>Gender</th>
								<th>District</th>
								<th>Year Of Paticipation</th>
								<th>Medal</th>
								
								
							</tr>
						</thead>
						<tbody>
				        <?php foreach($medal_details as $medals): ?>
							<tr>
								<td><?php echo $medals['doc_data']['unique id']; ?></td>
								<td><?php echo $medals['doc_data']['student name']; ?></td>
								<td><?php echo $medals['doc_data']['school name']; ?></td>
								<td><?php echo $medals['doc_data']['event']; ?></td>
								<td><?php echo $medals['doc_data']['gender']; ?></td>
								<td><?php echo $medals['doc_data']['district']; ?></td>
								<td><?php echo $medals['doc_data']['level of participation']; ?></td>
								<td><?php echo $medals['doc_data']['medal']; ?></td>
								
								<!-- <td>
									<a href="<?php //echo URL."tswreis_sports_mgmt/tswreis_electronic_sports_record"?>"><button type="button" class="btn bg-teal waves-effect">Personal Info</button></a>
								</td> -->
							</tr>
						<?php endforeach; ?>	
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('inc/footer_bar.php'); ?>