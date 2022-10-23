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
                        	
                            <div class="col-sm-2">
                            	<label>Select District</label>
                                <select id="select_dt_name" class="form-control select_dt_name">
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
                            	<label>Select School</label>                               	
                                <select class="form-control show-tick school_name" id="school_name" disabled=true >
                                    <option value="All"  selected="">All</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
		                    <?php $end_date  = date ( "Y-m-d"); ?>
		                       <label>From Date:</label>
		                       <input type="text" id="passing_date" name="passing_date" class="form-control date" value="<?php echo $end_date; ?>">
		                       </span>
		                    </div>
		                    <div class="col-sm-2">
		                       <label>To Date:</label>
		                       <input type="text" id="passing_end_date" name="passing_end_date" class="form-control date" value="<?php echo date('Y-m-d'); ?>">
		                       </span>
		                    </div>
		                   
                        </div>
                        
                        <button class="btn bg-green btn-lg waves-effect get_excel" type="button">Get Request Report<span class="badge"></span></button>&nbsp;
                        <button class="btn bg-green btn-lg waves-effect get_excel" type="button">Get Attendance Report<span class="badge"></span></button>&nbsp;
                        <button class="btn bg-green btn-lg waves-effect get_excel" type="button">Get Sanitation Report<span class="badge"></span></button>&nbsp;
                        <button class="btn bg-green btn-lg waves-effect get_excel" type="button">Get HB Report<span class="badge"></span></button>&nbsp;
                        <button class="btn bg-green btn-lg waves-effect get_excel" type="button">Get BMI Report<span class="badge"></span></button>
                        
					</div>
				</div>
				
			</div>
	        
	    </div>
	    <div class="row clearfix">
	    	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>Get Screening Reports</h2>
					</div>
					<div class="body">
						<div class="row clearfix">
							<div class="col-sm-3">
					            <label>Academic Year</label>
					            <select class="form-control show-tick academic_filter common_change" id="academic_filter">
					                <option value="2019-2020" selected="">2019-2020 AcademicYear</option>
					                <option value="2018-2019">2018-2019 AcademicYear</option>
					                <option value="2017-2018">2017-2018 AcademicYear</option>
					                <option value="2016-2017">2016-2017 AcademicYear</option>
					                <option value="2015-2016">2015-2016 AcademicYear</option>
					            </select>
					        </div>
							<div class="col-sm-3">
                            	<label>Select District</label>
                                <select id="select_dt_name_for_screen" class="form-control select_dt_name_for_screen">
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
                            	<label>Select School</label>                               	
                                <select class="form-control show-tick school_name" id="school_name_for_screen" disabled=true >
                                    <option value="All"  selected="">All</option>
                                </select>
                            </div>
                            <!-- <div class="col-sm-3">
                            	<label>Abnormality</label>
                            	<select id="abnormalities_from_pie" class="form-control abnormalities">
								    <option value="Ortho Abnormalities">Ortho Abnormalities</option>
								    <option value="Postural Abnormalities">Postural Abnormalities</option>
								    <option value="Defects At Birth">Defects At Birth</option>
								    <option value="Deficiencies">Deficiencies</option>
								    <option value="Childhood Diseases">Childhood Diseases</option>
								    <option value="General Abnormalities">General Abnormalities</option>
								    <option value="Dental Abnormalities">Dental Abnormalities</option>
								    <option value="Eye Abnormalities">Eye Abnormalities</option>
								    <option value="Auditory And Speech Abnormalities">Auditory And Speech Abnormalities</option>
								</select>
                            </div> -->
                            <!-- <div class="col-sm-3">
                        		<label>Select Date</label>
                                    <div class="form-line">
                                        <input type="text" id="set_date" name="set_date" class=" form-control date" placeholder="Please choose a date..." value="<?php //echo date('yy-m-d'); ?>">
                                    </div>
                               
                            </div> -->
						</div>
						<button class="btn bg-green btn-lg waves-effect" type="button" id="screening_report">Get Screening Report<span class="badge"></span></button>
						
						
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>Get Messages Reports</h2>
					</div>
					<div class="body">
						<div class="row clearfix">
							<div class="col-sm-4">
		                    <?php $end_date  = date ( "Y-m-d"); ?>
		                       <label>From Date:</label>
		                       <input type="text" id="passing_date" name="passing_date" class="form-control date passing_date" value="<?php echo $end_date; ?>">
		                       </span>
		                    </div>
		                    <div class="col-sm-4">
		                       <label>To Date:</label>
		                       <input type="text" id="passing_end_date" name="passing_end_date" class="form-control date passing_end_date" value="<?php echo date('Y-m-d'); ?>">
		                       </span>
		                    </div>
		                    <div class="col-sm-4">
		                    	<label>Messages Regarding</label>
		                    	<select class="form-control">
		                    		<option>All</option>
		                    		<option>HS</option>
		                    		<option>Principal</option>
		                    	</select>
		                    </div>
							
						</div>
						<button class="btn bg-green btn-lg waves-effect" type="button" id="screening_report">Get Messaging Report<span class="badge"></span></button>
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

    $('#passing_end_date').change(function(e){
            date = $('#passing_end_date').val();
    });

    	$('.get_excel').click(function(){
		var dist = $('#select_dt_name').val();
		var scl = $('#school_name').val();
		var start = $('#passing_date').val();
		var end = $('#passing_end_date').val();
		var button_val = $(this).text();
		//alert(button_val);
		//$("#loading_modal").modal('show');
		$.ajax({

			//url:'get_excel_for_students_nos',
			url:'get_excel_for_selected_field',
			type:'POST',
			data:{'dist_name':dist, 'school':scl, 'start_date':start, 'end_date':end, 'request':button_val},
			success : function(data){
				//$("#loading_modal").modal('show');
                //console.log(data);
                window.location = data;
            },
            error:function(XMLHttpRequest, textStatus, errorThrown)
            {
             console.log('error', errorThrown);
            }
		});
	});

    $('#screening_report').click(function(){
		var dist_screen = $('#select_dt_name_for_screen option:selected').text();
		var scl_screen = $('#school_name_for_screen').val();
		var year_screen = $('#academic_filter').val();
		//var abnormalities = $('#abnormalities_from_pie').val();
		//var button_val = $(this).text();
		/*alert(dist_screen);
		alert(scl_screen);
		alert(year_screen);*/
		
		$.ajax({

			//url:'get_excel_for_students_nos',
			url:'get_excel_for_screening_overall',
			type:'POST',
			data:{'dist_name':dist_screen, 'school':scl_screen, 'academic_year':year_screen},
			success : function(data){
                console.log(data);
                window.location = data;
            },
            error:function(XMLHttpRequest, textStatus, errorThrown)
            {
             console.log('error', errorThrown);
            }
		});
	});


    </script>

    <script type="text/javascript">
	$('.select_dt_name').change(function(e){
		dist = $('.select_dt_name').val();
		get_scls_with_district_data(dist);
	});

	$('.select_dt_name_for_screen').change(function(e){
		dist = $('#select_dt_name_for_screen').val();
		get_scls_with_district_data(dist);
	});


	function get_scls_with_district_data(dist){
		/*var datas = $('#select_dt_name').val();
         alert(datas);*/
		
		//dt_name = $(".select_dt_name option:selected").text();
		//alert(dist);
		var options = $(".school_name");
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
	}

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
