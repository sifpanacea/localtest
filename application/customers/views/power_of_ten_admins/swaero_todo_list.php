<?php $current_page = "todo_list"; ?>
<?php $main_nav = ""; ?>
<?php include("inc/header_bar.php"); ?>
<?php include("inc/sidebar.php"); ?>

<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">

<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
				<div class="header">
					<h2>ToDo List News Feed <span class="badge bg-color-greenLight"></span></h2>
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
								<th>Events</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
				        	<tr>
				        		<td><div class="form-line">
				                        <input type="text" id="set_date" name="set_date" class="datepicker form-control date set_date" value="<?php echo date('yy-m-d'); ?>">
				                    </div>
				                </td>
				        		<td>                                	
                                    <select class="form-control show-tick">
                                        <option value="Daily">Pending</option>
										<option value="Weekly">Completed</option>
                                    </select>
	                            </td>
				        		<td>
				        			<button type="button" class="btn bg-blue waves-effect">
                                    	<i class="material-icons">mode_edit</i>
                                	</button>
                                	<button type="button" class="btn bg-red waves-effect">
                                    	<i class="material-icons">delete</i>
                                	</button>
				        		</td>
				        	</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
<?php include("inc/footer_bar.php"); ?>

<!-- Jquery Core Js -->
    <script src="<?php echo MDB_PLUGINS."jquery/jquery.min.js"; ?>"></script>

    

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo MDB_PLUGINS."node-waves/waves.js"; ?>"></script>

    <!-- Autosize Plugin Js -->
    <script src="<?php echo MDB_PLUGINS."autosize/autosize.js"; ?>"></script>

    
    <!-- Moment Plugin Js -->
    <script src="<?php echo MDB_PLUGINS."momentjs/moment.js"; ?>"></script>

    <!-- Custom Js -->
    <script src="<?php echo(MDB_JS.'admin.js'); ?>"></script>
    <script src="<?php echo(MDB_JS.'pages/forms/basic-form-elements.js'); ?>"></script>
    
    <!-- Demo Js -->
    <script src="<?php echo(MDB_JS.'demo.js'); ?>"></script>

<!-- Bootstrap Datepicker Plugin Js -->
<script src="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script>

<script type="text/javascript">
	var today_date = $('#set_date').val();
    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#set_date').change(function(e){
            today_date = $('#set_date').val();
    });
</script>