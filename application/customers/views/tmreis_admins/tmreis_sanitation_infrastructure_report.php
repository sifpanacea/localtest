<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Sanitation Infrastructure";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["sanitation_infrastructure"]["active"] = true;
include("inc/nav.php");

?>
<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "https://url.com"
		include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">
	
			<!-- SANITATION INFRASTRUCTURE DONUT GRAPH -->
			<!-- widget grid -->
		<section id="widget-grid" class="">
			<div class="row">
				<article class="col-sm-12">
					<!-- new widget -->
					<div class="jarviswidget" id="wid-id-100" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
						<!-- widget options:
						usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

						data-widget-colorbutton="false"
						data-widget-editbutton="false"
						data-widget-togglebutton="false"
						data-widget-deletebutton="false"
						data-widget-fullscreenbutton="false"
						data-widget-custombutton="false"
						data-widget-collapsed="true"
						data-widget-sortable="false"

						-->
						<header>
							<span class="widget-icon"> <i class="glyphicon glyphicon-record txt-color-darken"></i> </span>
							<h2>Sanitation Infrastructure </h2>

						</header>

						<!-- widget div-->
						<div class="no-padding">
							<!-- widget edit box -->
							<!-- end widget edit box -->

							<div class="widget-body">
							<div class="row">
								<br>
								<d`iv class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									<div class="well well-sm well-light">
										<form class="smart-form" >
										<fieldset style="padding-top: 0px; padding-bottom: 0px;">
										<section class="col col-6">
														<label class="label" for="district_name">District Name</label>
														<label class="select">
														<select id="select_sanitation_infra_dt_name" >
															<option value='select_school' >Select a district</option>
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
													<section class="col col-6">
														<label class="label" for="school_name">School Name</label>
														<label class="select">
														<select id="select_sanitation_infra_school_name" disabled=true>
														<option value='select_school' >Select a district first</option>
														</select> <i></i>
													</label>
													</section>
													
										</fieldset>
										</form>
									</div>
									<div>
									<br>
									<p class="sanitation_infra_note">&nbsp;&nbsp;Note : To get sanitation infrastructure chart, please select the district and school.</p>
									<div id="sanitation_chart" class="row" style="min-height:150px;">
									
								   </div>
									</div>
								</div>
								
								</div>
							

						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->
                 </div>
				</article>
				
			<!-- row -->

			<!-- end row -->
				</div>
				
				
				<!-- Modal -->
					<div class="modal fade" id="load_waiting" tabindex="-1" role="dialog" >
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title" id="myModalLabel">Loading dashboard in progress</h4>
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
				
				
			</div><!-- end row -->
			</section>
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
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



<!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->

<script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.fillbetween.min.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.orderBar.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.pie.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.time.min.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.axislabels.js"></script>
<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>
<script>
$(document).ready(function() {

	var today_date = $('#set_data').val();

	var dt_name = $('#select_dt_name').val();
	var school_name = $('#school_name').val();
	


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


// SANITATION INFRASTRUCTURE

$('#select_sanitation_infra_dt_name').change(function(e){
	dist = $('#select_sanitation_infra_dt_name').val();
	dt_name = $("#select_sanitation_infra_dt_name option:selected").text();
	//alert(dist);
	var options = $("#select_sanitation_infra_school_name");
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
			options.append($("<option />").val("select_school").prop("selected", true).text("Select a school"));
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

$('#select_sanitation_infra_school_name').change(function(e){
	district_name = $("#select_sanitation_infra_dt_name option:selected").text();
	school_name   = $("#select_sanitation_infra_school_name option:selected").text();
	
	$.ajax({
		url: 'get_sanitation_infrastructure',
		type: 'POST',
		data: {"district_name" : district_name,"school_name":school_name},
		success: function (data) 
		{	
           data = data.trim();
		  
           if(data != "NO_DATA_AVAILABLE")	
		   {			   
              result = $.parseJSON(data);
			  
			  $('#sanitation_chart').html('<div id="toilets_graph" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="hand_sanitizers_graph" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="disposable_bins_graph" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="water_dispensaries_graph" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="children_seating_graph" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div></div>');
			  
			  $('.sanitation_infra_note').remove();
			 
			  var toilets            = result.toilets;
			  var hand_sanitizers    = result.hand_sanitizers;
			  var disposable_bins    = result.disposable_bins;
			  var water_dispensaries = result.water_dispensaries;
			  var children_seating   = result.children_seating;
			  
		
			// toilets
			if ($("#toilets_graph").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>'
				for (var item in toilets) {
				  if (toilets.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+toilets[item].label+'</td><td>'+toilets[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#toilets_graph").html(table)
				// Morris.Donut({
					// element : 'toilets_graph',
					// data : toilets
				// });
			}
			
			$('#toilets_graph').prepend('<div class="">Toilets</div>');
			
			// hand sanitizers
			if ($("#hand_sanitizers_graph").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>'
				for (var item in hand_sanitizers) {
				  if (hand_sanitizers.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+hand_sanitizers[item].label+'</td><td>'+hand_sanitizers[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#hand_sanitizers_graph").html(table)
				
				// Morris.Donut({
					// element : 'hand_sanitizers_graph',
					// data : hand_sanitizers
				// });
			}
			
			$('#hand_sanitizers_graph').prepend('<div class="spec">Hand Sanitizers</div>');
			
			// disposable bins
			if ($("#disposable_bins_graph").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>'
				for (var item in disposable_bins) {
				  if (disposable_bins.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+disposable_bins[item].label+'</td><td>'+disposable_bins[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#disposable_bins_graph").html(table)
				
				// Morris.Donut({
					// element : 'disposable_bins_graph',
					// data : disposable_bins
				// });
			}
			
			$('#disposable_bins_graph').prepend('<div class="spec">Disposable Bins in</div>');
			
			// water dispensaries
			if ($("#water_dispensaries_graph").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>'
				for (var item in water_dispensaries) {
				  if (water_dispensaries.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+water_dispensaries[item].label+'</td><td>'+water_dispensaries[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#water_dispensaries_graph").html(table)
				
				// Morris.Donut({
					// element : 'water_dispensaries_graph',
					// data : water_dispensaries
				// });
			}
			
			$('#water_dispensaries_graph').prepend('<div class="spec">Water Dispensaries</div>');
			
			// children seating
			if ($("#children_seating_graph").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>'
				for (var item in children_seating) {
				  if (children_seating.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+children_seating[item].label+'</td><td>'+children_seating[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#children_seating_graph").html(table)
				
				// Morris.Donut({
					// element : 'children_seating_graph',
					// data : children_seating
				// });
			}

			$('#children_seating_graph').prepend('<div class="spec">Children sit on</div>');
		
		   }
		   else
		   {
			   $('.sanitation_infra_note').remove();
			   $('#sanitation_chart').html('<center><label id="sanitation_infra_note">No sanitation infrastructure data available for this school</label></center>');
		   }

			
					
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		}); 
	
});

 
});			
		

//===================================drill down pie======================
//===================================end of dril down pie================
</script>