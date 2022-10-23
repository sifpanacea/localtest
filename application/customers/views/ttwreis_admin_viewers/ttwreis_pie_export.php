<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Pie Export";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pie_export"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Reports"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	
	
	
<div class="row">
     				<!-- NEW WIDGET START -->
				<article class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>Export pie informations</h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body">
							<h2>Select date and click on PIE to export information in excel format</h2>
							<div class="well well-sm well-light">
							<form class="smart-form">
								<div class='row'> 
										<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
										<fieldset>
											<section>
											<div class="input-group">
												<input type="text" id="set_date" name="set_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo $today_date?>">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
											</section>
											</fieldset>
										</div>
				                    </div>
				                    
				                    
				                    			<div class="row">
			<section class="col col-4">
				<label class="label" for="first_name">District Name</label>
					<label class="select">
					<select id="select_dt_name" >
						<option value='All' >All</option>
						<?php if(isset($distslist)): ?>
							<?php foreach ($distslist as $dist):?>
							<option value='<?php echo $dist['_id']?>' ><?php echo ucfirst($dist['dt_name'])?></option>
							<?php endforeach;?>
							<?php else: ?>
							<option value="1"  disabled="">No district entered yet</option>
						<?php endif ?>
					</select> <i></i>
			</label>
			</section>
			<section class="col col-4">
				<label class="label" for="first_name">School Name</label>
					<label class="select">
					<select id="school_name" disabled=true>
						<option value='All' >All</option>
						
						
					</select> <i></i>
			</label>
			</section>
			</div>
				                    
				                    
				                    <div class='row'> 
										<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
											
											<fieldset>
											<section>
											<label class="label">Generate new excel sheet?</label>
											<div class="inline-group" id='re_gen'>
												<label class="radio">
													<input type="radio" name="radio-inline" checked="checked">
													<i></i>Yes</label>
												<label class="radio">
													<input type="radio" name="radio-inline">
													<i></i>No</label>
											</div>
										</section>
										</fieldset>
										
										</div>
				                    </div>
				                    <div class='row'> 
				                   		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				                   		<fieldset>
											<section>
										<button type="button" class="btn btn-success" id="absent_pie_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false" style="padding: 6px;">
					                       Absent PIE
					                    </button>
					                   * Absent PIE is generated for single day based on date selected
					                   </section>
					                   </fieldset>
				                    	</div>
					         		</div>
					         		<div class='row'>
						         		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
						         		<fieldset>
											<section>
							         		<div class="well well-sm well-light">
							         <div >
									<div class="well well-sm well-light">
										<fieldset>
										<section>
											<label class="label">PIE Span</label>
											<label class="select">
												<select id="request_pie_span">
													<option value="Daily">Daily</option>
													<option value="Weekly">Weekly</option>
													<option value="Bi Weekly">Bi Weekly</option>
													<option selected value="Monthly">Monthly</option>
													<option value="Bi Monthly">Bi Monthly</option>
													<option value="Quarterly">Quarterly</option>
													<option value="Half Yearly">Half Yearly</option>
													<option value="Yearly">Yearly</option>
												</select> <i></i> </label>
										</section>
										</fieldset>
									</div>
								</div>
							         		<div >
							         		<fieldset>
											<section>
							         	<button type="button" class="btn btn-warning" id="request_pie_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false" style="padding: 6px;">
					                       Request PIE
					                    </button>
					                    </section>
					                    </fieldset>
							         		</div>
							         		
							         		</div>
							         		</section>
							         		</fieldset>
							         		</div>
							         		
							         		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
						         		<fieldset>
											<section>
							         		<div class="well well-sm well-light">
							         <div >
									<div class="well well-sm well-light">
										<fieldset>
										<section>
											<label class="label">PIE Span</label>
											<label class="select">
												<select id="screening_pie_span">
													<option value="Daily">Daily</option>
													<option value="Weekly">Weekly</option>
													<option value="Bi Weekly">Bi Weekly</option>
													<option selected value="Monthly">Monthly</option>
													<option value="Bi Monthly">Bi Monthly</option>
													<option value="Quarterly">Quarterly</option>
													<option value="Half Yearly">Half Yearly</option>
													<option value="Yearly">Yearly</option>
												</select> <i></i> </label>
										</section>
										</fieldset>
									</div>
								</div>
							         		<div >
							         		<fieldset>
											<section>
							         	<button type="button" class="btn btn-default" id="screening_pie_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false" style="padding: 6px;">
					                       Screening PIE
					                    </button>
					                    <i>*Note : Detailed screeninig will be generated only on per school basis. </i>
					                    </section>
					                    </fieldset>
							         		</div>
							         		
							         		</div>
							         		</section>
							         		</fieldset>
							         		</div>
							         		
					         		</div>
							</form>
							</div>
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
					</article>
        
        </div><!-- ROW -->

				<!-- Modal -->
					<div class="modal fade" id="load_waiting" tabindex="-1" role="dialog" >
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h2 class="modal-title" id="myModalLabel">Generating excel sheet, please wait!</h2>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-12">
											<img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" style="display: block; margin: 0 auto; width: 100px;">
										</div>
									</div>
								</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
$(document).ready(function() {
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

	$('#select_dt_name').change(function(e){
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

	$('#school_name').change(function(e){
		school_name = $("#school_name option:selected").text();
	});
	
});
</script>