<?php $current_page="Pie_Export"; ?>
<?php $main_nav=""; ?>
<?php
include('inc/header_bar.php');
include('inc/sidebar.php');
?>
    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet" />   

<section class="content">
        <div class="container-fluid">
            <div class="block-header">
               <!--  <h2>BASIC FORM ELEMENTS</h2> -->
            </div>
            <!-- Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               EXPORT PIE INFORMATIONS                                
                            </h2>
                            <ul class="header-dropdown m-r--5">
							    <div class="button-demo">
							    <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
							    </div>
							</ul>                            
                        </div>
                        <div class="body">                                                       
                            <h2 class="card-inside-title">Select date and click on PIE to export information in excel format</h2>
                            <div class="row clearfix">
                            	<div class="col-sm-4">
                            		<div class="form-group">
                                        <div class="form-line">
                                            <input type="text" id="set_date" name="set_date" class="datepicker form-control" placeholder="Please choose a date..." value="<?php echo $today_date?>">
                                        </div>
                                    </div>
                                	<!-- <div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="text" id="set_date" name="set_date" placeholder="Select a date" class="form-control" data-dateformat="yy-mm-dd" value="<?php //echo $today_date?>">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="col-sm-4">
                                 <select id="select_dt_name" class="form-control">
                                    <option value="All">All</option>
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
                                <div class="col-sm-4">                                	
                                    <select class="form-control show-tick" id="school_name" disabled=true >
                                        <option value="All"  selected="">All</option>
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">Generate New Excel Sheet</h2>                      		
                            <div class="row clearfix">                              
		                           <div class="demo-radio-button"> 
			                            <!-- <div class="col-sm-4">                               	
		                                    <label for="radio_yes">Yes</label>
		                                	<input name="yes" type="radio" id="radio_yes" class="with-gap radio-col-pink" value="yes" checked="checked" />
		                                </div>
		                                <div class="col-sm-4">
		                                	<label for="radio_no">No</label>
	                                		<input name="no" type="radio" id="radio_no" class="" value="no" />
		                                </div> -->
					                    <div class="col-sm-4">
			                                <input name="radio" type="radio" id="radio_yes" class="with-gap radio-col-blue" checked/>
		                                	<label for="radio_yes">Yes</label>
		                                	<input name="radio" type="radio" id="radio_no" class="with-gap radio-col-blue" />
		                                	<label for="radio_no">No</label>
		                                </div>
				                    </div>
                        	</div>
                        	<h2></h2>
                        	<div class="button-demo"> 
                        		<button type="button" id="bmi_pie_btn" class="btn bg-blue waves-effect">BMI PIE</button>
                        		<button type="button" id="absent_pie_btn"class="btn bg-pink waves-effect">ABSENT PIE</button>
                        		<i>* Absent Pie is generated for single day based on date selected</i>
                        	</div>                        	    
                        	<h2 class="card-inside-title">Pie Span</h2>
                        	<div class="row clearfix">                            	
                                <div class="col-sm-4">                                      	
                                    <select class="form-control show-tick">
						                <option value="Daily">Daily</option>
										<option value="Weekly">Weekly</option>
										<option value="Bi Weekly">Bi Weekly</option>
										<option selected value="Monthly">Monthly</option>
										<option value="Bi Monthly">Bi Monthly</option>
										<option value="Quarterly">Quarterly</option>
										<option value="Half Yearly">Half Yearly</option>
										<option value="Yearly">Yearly</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">                                	
                                    <select class="form-control show-tick">
                                        <option value="Daily">Daily</option>
										<option value="Weekly">Weekly</option>
										<option value="Bi Weekly">Bi Weekly</option>
										<option value="Monthly">Monthly</option>
										<option value="Bi Monthly">Bi Monthly</option>
										<option value="Quarterly">Quarterly</option>
										<option value="Half Yearly">Half Yearly</option>
										<option value="2015-16 Academic Year">2015-16 Academic Year</option>
										<option value="2016-17 Academic Year">2016-17 Academic Year</option>
										<option selected value="Yearly">2017-18 Academic Year</option>
                                    </select>
                                </div>
                            </div>
                            	<br><br>
                            <div class="button-demo">
                                <button type="button" id="screening_pie_btn" class="btn bg-indigo waves-effect">Request PIE</button>                            	
                        		<button type="button" id="screening_pie_btn" class="btn bg-light-green waves-effect">Screening PIE</button>
                        		<i>*Note : Detailed screeninig will be generated only on per school basis. </i>
                        	</div>
            			</div>
                	</div>
            	</div>
    		</div>
    	</div>	
    </section>	
    <!--<div class="modal fade" id="load_waiting" tabindex="-1" role="dialog" >
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h2 class="modal-title" id="myModalLabel">Generating excel sheet, please wait!</h2>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-12">
											<img src="<?php //echo(IMG.'ajax-loader.gif'); ?>" id="gif" style="display: block; margin: 0 auto; width: 100px;">
										</div>
									</div>
								</div>
						 	</div>
	                    </div>  
	                </div> --> 
<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

 <?php 
	//include footer
	//include("inc/footer_bar.php"); 
?> 
 
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



<!--Added by lavanya just to fetch schools for paticular distict taken from below script(a peice for selecting schools) -->
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
</script>
<!-- Fetching schools script ends here -->

<script>
$(document).ready(function() {

	var check = $('#select_dt_name').val();
	alert(check);
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
	
	$('#bmi_pie_btn').click(function(e){
		console.log('php222222222222222', today_date);
		$.ajax({
			url: 'generate_excel_for_bmi_pie',
			type:'POST',
			data:{"today_date": today_date,"dt_name":dt_name,"school_name": school_name},
			success : function (data) {
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

	$('#request_pie_btn').click(function(e){
		//console.log('php222222222222222', today_date);
		request_pie_span = $('#request_pie_span').val();
		$.ajax({
			url: 'generate_excel_for_request_pie',
			type: 'POST',
			data: {"today_date" : today_date,"request_pie_span":request_pie_span, "dt_name" : dt_name, "school_name" : school_name},
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
	$('#screening_pie_btn').click(function(e){
		console.log('php222222222222222', today_date);
		screening_pie_span = $('#screening_pie_span').val();
		$.ajax({
			url: 'generate_excel_for_screening_pie',
			type: 'POST',
			data: {"today_date" : today_date,"screening_pie_span":screening_pie_span, "dt_name" : dt_name, "school_name" : school_name},
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
	
});
</script>
