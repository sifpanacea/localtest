<?php $current_page = "Reports_download"; ?>
<?php $main_nav = "Reports"; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet" />   

<section class="content">
	<div class="container-fluid">
        <div class="block-header">
           <h2>Reports Download</h2>
        </div>
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>Select Date Range to Get Reports</h2>
					</div>
					<div class="body">
						<div class="row clearfix">
                        	<div class="col-sm-3">
                        		<label>Select Date</label>
                                    <div class="form-line">
                                        <input type="text" id="set_date" name="set_date" class=" form-control date" placeholder="Please choose a date..." value="<?php echo date('yy-m-d'); ?>">
                                    </div>
                               
                            </div>
                            <div class="col-sm-3">
                            	<label>Select Time Span</label>                                      	
                                <select class="form-control show-tick">
					                <option selected value="Daily">Daily</option>
									<option value="Weekly">Weekly</option>
									<option value="Bi Weekly">Bi Weekly</option>
									<option value="Monthly">Monthly</option>
									<option value="Bi Monthly">Bi Monthly</option>
									<option value="Quarterly">Quarterly</option>
									<option value="Half Yearly">Half Yearly</option>
									<option value="Yearly">Yearly</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                            	<label>Select District</label>
                                <select id="select_dt_name" class="form-control">
	                                <option value="All" selected="">All</option>
	                                <?php if(isset($distslist)): ?>
	                                	<?php echo print_r($distslist, true); ?>
	                                    <?php foreach ($distslist as $dist):?>
	                                    <option value='<?php echo $dist['_id']; ?>' ><?php echo ucfirst($dist['dt_name'])?></option>
	                                    <?php endforeach;?>
	                                    <?php else: ?>
	                                    <option value="1"  disabled="">No District entered yet</option>
	                                <?php endif ?>
	                            </select>
                            </div>
                            <div class="col-sm-3"> 
                            	<label>Select District</label>                               	
                                <select class="form-control show-tick" id="school_name" disabled=true >
                                    <option value="All"  selected="">All</option>
                                </select>
                            </div>
                        </div>
                        
                        <button class="btn bg-green btn-lg waves-effect" type="button">Get Request Report<span class="badge"></span></button>&nbsp;
                        <button class="btn bg-green btn-lg waves-effect" type="button">Get Attendance Report<span class="badge"></span></button>&nbsp;
                        <button class="btn bg-green btn-lg waves-effect" type="button">Get Sanitation Report<span class="badge"></span></button>&nbsp;
                        <button class="btn bg-green btn-lg waves-effect" type="button">Get Screening Report<span class="badge"></span></button>&nbsp;
                        <button class="btn bg-green btn-lg waves-effect" type="button">Get HB Report<span class="badge"></span></button>&nbsp;
                        <button class="btn bg-green btn-lg waves-effect" type="button">Get BMI Report<span class="badge"></span></button>
					</div>
				</div>
				
			</div>
	        
	    </div>
	</div>
	
</section>

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

    <script type="text/javascript">
	$('#select_dt_name').change(function(e){
		/*var datas = $('#select_dt_name').val();
         alert(datas);*/
		dist = $('#select_dt_name').val();
		dt_name = $("#select_dt_name option:selected").text();
		//alert(dist);
		var options = $("#school_name");
		options.prop("disabled", true);
		
		options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching schools list..."));
		$.ajax({
			url: 'get_schools_list',
			type: 'POST',
			data: {"dist_id" : dist},
			success: function (data) {			

				result = $.parseJSON(data);
				console.log(result)

				options.prop("disabled", false);
				options.empty();
				options.append($("<option />").val("All").prop("selected", true).text("All"));
				$.each(result, function() {
				    options.append($("<option />").val(this.school_name).text(this.school_name));
				});
						
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
	});

	var check = $('#select_dt_name').val();
	//alert(check);
	var today_date = $('#set_date').val();
	console.log('php111111111111111', today_date);
	var dt_name = $('#select_dt_name').val();
	var school_name = $('#school_name').val();

	$('.datepicker').datepicker({
		minDate: new Date(1900, 10 - 1, 25)
	 });

	$('#set_date').change(function(e){
		today_date = $('#set_date').val();
		console.log('php222222222222222', today_date);
	});

	$('#absent_pie_btn').click(function(e){
		//console.log('php222222222222222', today_date);
		$.ajax({
			url: 'generate_excel_for_absent_pie',
			type: 'POST',
			data: {"today_date" : today_date, "dt_name" : dt_name, "school_name" : school_name},
			success: function (data) {			
				$('#load_waiting').modal('hide');
				console.log('replyyyyyyyyyyyyyyyyyyyyyyy', data);
				window.location = data;
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
	});

	$('#school_name').change(function(e){
		school_name = $("#school_name option:selected").text();
	});
</script>

<?php //include('inc/footer_bar.php'); ?> 