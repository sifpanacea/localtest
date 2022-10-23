<?php $current_page=""; ?>
<?php $main_nav =""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
				<div class="header">
					<h2>Names List By Search<span class="badge bg-color-greenLight"></span></h2>
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
								<th>School</th>
								<th>Hospital Unique ID</th>
								<th>Student Name</th>
								<th>Class</th>
								<th>Section</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if(isset($students)): ?>
							 <?php foreach($students as $stud):?>
							
				        	<tr>
				        		<td><?php echo $stud['doc_data']['widget_data']['page2']['Personal Information']["School Name"];?></td> 
				        		<td><?php echo $stud['doc_data']['widget_data']['page1']['Personal Information']["Hospital Unique ID"];?></td>
				        		<td><?php echo $stud['doc_data']['widget_data']['page1']['Personal Information']['Name'];?></td>
				        		<td><?php echo $stud['doc_data']['widget_data']['page2']['Personal Information']["Class"];?></td>
				        		<td><?php echo $stud['doc_data']['widget_data']['page2']['Personal Information']["Section"];?></td>
				        		<td>
				        			<a href="<?php echo URL."panacea_mgmt/panacea_reports_display_ehr_uid/"?>? id = <?php echo $stud['doc_data']['widget_data']['page1']['Personal Information']["Hospital Unique ID"]; ?>"><button type="button" class="btn bg-teal waves-effect">Show EHR</button></a>
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
<?php include('inc/footer_bar.php'); ?>