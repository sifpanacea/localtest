<?php $current_page = "Reports_download"; ?>
<?php $main_nav = "Reports"; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet" />   
<style>
.sk-circle {
  margin: 100px auto;
  width: 70px;
  height: 70px;
  position: relative;
}
.sk-circle .sk-child {
  width: 100%;
  height: 100%;
  position: absolute;
  left: 0;
  top: 0;
}
.sk-circle .sk-child:before {
  content: '';
  display: block;
  margin: 0 auto;
  width: 15%;
  height: 15%;
  background-color: #333;
  border-radius: 100%;
  -webkit-animation: sk-circleBounceDelay 1.2s infinite ease-in-out both;
          animation: sk-circleBounceDelay 1.2s infinite ease-in-out both;
}
.sk-circle .sk-circle2 {
  -webkit-transform: rotate(30deg);
      -ms-transform: rotate(30deg);
          transform: rotate(30deg); }
.sk-circle .sk-circle3 {
  -webkit-transform: rotate(60deg);
      -ms-transform: rotate(60deg);
          transform: rotate(60deg); }
.sk-circle .sk-circle4 {
  -webkit-transform: rotate(90deg);
      -ms-transform: rotate(90deg);
          transform: rotate(90deg); }
.sk-circle .sk-circle5 {
  -webkit-transform: rotate(120deg);
      -ms-transform: rotate(120deg);
          transform: rotate(120deg); }
.sk-circle .sk-circle6 {
  -webkit-transform: rotate(150deg);
      -ms-transform: rotate(150deg);
          transform: rotate(150deg); }
.sk-circle .sk-circle7 {
  -webkit-transform: rotate(180deg);
      -ms-transform: rotate(180deg);
          transform: rotate(180deg); }
.sk-circle .sk-circle8 {
  -webkit-transform: rotate(210deg);
      -ms-transform: rotate(210deg);
          transform: rotate(210deg); }
.sk-circle .sk-circle9 {
  -webkit-transform: rotate(240deg);
      -ms-transform: rotate(240deg);
          transform: rotate(240deg); }
.sk-circle .sk-circle10 {
  -webkit-transform: rotate(270deg);
      -ms-transform: rotate(270deg);
          transform: rotate(270deg); }
.sk-circle .sk-circle11 {
  -webkit-transform: rotate(300deg);
      -ms-transform: rotate(300deg);
          transform: rotate(300deg); }
.sk-circle .sk-circle12 {
  -webkit-transform: rotate(330deg);
      -ms-transform: rotate(330deg);
          transform: rotate(330deg); }
.sk-circle .sk-circle2:before {
  -webkit-animation-delay: -0.84s;
          animation-delay: -0.84s; }
.sk-circle .sk-circle3:before {
  -webkit-animation-delay: -0.84ss;
          animation-delay: -0.84ss; }
.sk-circle .sk-circle4:before {
  -webkit-animation-delay: -0.84ss;
          animation-delay: -0.9s; }
.sk-circle .sk-circle5:before {
  -webkit-animation-delay: -0.8s;
          animation-delay: -0.8s; }
.sk-circle .sk-circle6:before {
  -webkit-animation-delay: -0.7s;
          animation-delay: -0.7s; }
.sk-circle .sk-circle7:before {
  -webkit-animation-delay: -0.6s;
          animation-delay: -0.6s; }
.sk-circle .sk-circle8:before {
  -webkit-animation-delay: -0.5s;
          animation-delay: -0.5s; }
.sk-circle .sk-circle9:before {
  -webkit-animation-delay: -0.4s;
          animation-delay: -0.4s; }
.sk-circle .sk-circle10:before {
  -webkit-animation-delay: -0.3s;
          animation-delay: -0.3s; }
.sk-circle .sk-circle11:before {
  -webkit-animation-delay: -0.2s;
          animation-delay: -0.2s; }
.sk-circle .sk-circle12:before {
  -webkit-animation-delay: -0.1s;
          animation-delay: -0.1s; }

@-webkit-keyframes sk-circleBounceDelay {
  0%, 80%, 100% {
    -webkit-transform: scale(0);
            transform: scale(0);
  } 40% {
    -webkit-transform: scale(1);
            transform: scale(1);
  }
}

@keyframes sk-circleBounceDelay {
  0%, 80%, 100% {
    -webkit-transform: scale(0);
            transform: scale(0);
  } 40% {
    -webkit-transform: scale(1);
            transform: scale(1);
  }
}
</style>
	
<section class="content">
	<div class="container-fluid">
        <div class="block-header">
           <h2><b>Reports Download</b></h2>
        </div>
		<div class="row clearfix">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>Select Date Range to Get Reports
							<button type="button" class="btn bg-pink waves-effect pull-right" onclick="window.history.back();">Back</button></h2>						
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
	    	<!-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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
	                                <?php //if(isset($distslist)): ?>
	                                	<?php //echo print_r($distslist, true); ?>
	                                    <?php //foreach ($distslist as $dist):?>
	                                    <option value='<?php //echo $dist['_id']; ?>' ><?php //echo ucfirst($dist['dt_name'])?></option>
	                                    <?php //endforeach;?>
	                                    <?php //else: ?>
	                                    <option value="1"  disabled="">No District entered yet</option>
	                                <?php //endif ?>
	                            </select>
                            </div>
                            <div class="col-sm-3"> 
                            	<label>Select School</label>                               	
                                <select class="form-control show-tick school_name" id="school_name_for_screen" disabled=true >
                                    <option value="All"  selected="">All</option>
                                </select>
                            </div> -->
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
						<!-- <button class="btn bg-green btn-lg waves-effect" type="button" id="screening_report">Get Screening Report<span class="badge"></span></button> -->
						
						
					</div>
				</div>
			</div>
			<!-- <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="card">
					<div class="header">
						<h2>Get Messages Reports</h2>
					</div>
					<div class="body">
						<div class="row clearfix">
							<div class="col-sm-4">
		                    <?php $end_date  //= date ( "yy-m-d"); ?>
		                       <label>From Date:</label>
		                       <input type="text" id="passing_date" name="passing_date" class="form-control date passing_date" value="<?php //echo $end_date; ?>">
		                       </span>
		                    </div>
		                    <div class="col-sm-4">
		                       <label>To Date:</label>
		                       <input type="text" id="passing_end_date" name="passing_end_date" class="form-control date passing_end_date" value="<?php //echo date('yy-m-d'); ?>">
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
					</div> -->
				</div>
			</div>
	    </div>
	</div>
	
</section>
		<center>
		<div class="sk-circle">
			<div class="sk-circle1 sk-child"></div>
			<div class="sk-circle2 sk-child"></div>
			<div class="sk-circle3 sk-child"></div>
			<div class="sk-circle4 sk-child"></div>
			<div class="sk-circle5 sk-child"></div>
			<div class="sk-circle6 sk-child"></div>
			<div class="sk-circle7 sk-child"></div>
			<div class="sk-circle8 sk-child"></div>
			<div class="sk-circle9 sk-child"></div>
			<div class="sk-circle10 sk-child"></div>
			<div class="sk-circle11 sk-child"></div>
			<div class="sk-circle12 sk-child"></div>
		</div>
	</center>

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
    	$('.sk-circle').hide();
    	$('.get_excel').click(function(){
    	$('.sk-circle').show();
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
                $('.sk-circle').hide();
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
	//console.log('php111111111111111', today_date);
	var dt_name = $('#select_dt_name').val();
	var school_name = $('#school_name').val();

	$('.datepicker').datepicker({
		minDate: new Date(1900, 10 - 1, 25)
	 });

	$('#set_date').change(function(e){
		today_date = $('#set_date').val();
		//console.log('php222222222222222', today_date);
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