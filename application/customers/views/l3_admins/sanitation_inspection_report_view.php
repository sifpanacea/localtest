<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "RHSO Dashboard";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["rhso_reports"]["sub"]["sanitation_inspection"]["active"] = true;

include("inc/nav.php");

?>

<link href="<?php echo(CSS.'jquery.fullPage.css'); ?>" media="screen" rel="stylesheet" type="text/css" /><!--
<link href="<?php echo(CSS.'fullPage_styles.css'); ?>" media="screen" rel="stylesheet" type="text/css" /> 
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<link href="<?php echo(CSS.'site.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
-->

<style>



.dataTables_filter .input-group-addon {
    width: 32px;
    margin-top: 0;
    float: left;
    height: 32px;
    padding-top: 8px;
}
.input-group-addon{
	margin-left: -12px;
}
div.dataTables_info 
{
    padding-top: 9px;
    font-size: 13px;
    font-weight: 700;
    font-style: italic;
    color: #969696;
}
#datatable_fixed_column_paginate
{
	float: right;
}
.chart {
    height: 170px;
}
</style>
<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">
		<div id="fullpage">
			<div class="vertical-scrolling">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<!-- new widget -->
							<div class="jarviswidget" id="wid-id-1000" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
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
								<span class="widget-icon"> <i class="glyphicon glyphicon-calendar txt-color-darken"></i> </span>
								<h2>Select School </h2>

							</header>

							<!-- widget div-->
								<div class="no-padding">
								<!-- widget edit box -->
								<!-- end widget edit box -->

									<div class="widget-body">
									<!-- content -->
										<div id="myTabContent" class="tab-content">
											<div class="well well-sm well-light">
												<?php
												$attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
												echo  form_open('rhso_users/sanitation_inspection_report',$attributes);
												?>
													<div class="row">
													<section class="col col-4">
														<label class="label" for="first_name">District Name</label>
														<label class="select">
														<select id="select_dt_name" >
															<option value="" selected="0" disabled="">Select a district</option>
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
															<option value="0" selected="" disabled="">Select a district first</option>
															
															
														</select> <i></i>
													</label>
													</section>
													</div>
												</form>			
											</div>
									<!-- end content -->
									</div>
							<!-- end widget div -->
								</div>
						<!-- end widget -->
								</div>
							</div>
						</article>
					
					</div>
				</div>
			</div>
			
			<div class="vertical-scrolling">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<!-- new widget -->
							<div class="jarviswidget" id="wid-id-1000" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
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
						

							<!-- widget div-->
								<div class="no-padding">
								<!-- widget edit box -->
								<!-- end widget edit box -->
									<!-- widget content -->
						
							<div class="row">
     				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="wid-id-5" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
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
							<h2>Saniataton Inspection Report </h2>
						</header>
		
						
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body">

								<div id="sanitation_report">
									Select School from drop down to display report.
									
								</div>
								
							</div>
						</div>
							<!-- end widget content -->
		
						
		
					</div>
					<!-- end widget -->
					
				</article>
        
        </div><!-- ROW -->
								</div>
							</div>
						</article>
					
					</div>
				</div>
			</div>
		
		</div>
	<!-- End MAIN CONTENT -->
	</div>
	
	<!-- MAIN PANEL -->
</div>

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


<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>

<!--
<script src="<?php echo JS; ?>jquery.fullPage.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS; ?>fullPage_index.js" type="text/javascript" charset="utf-8"></script>
-->

<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
$(document).ready(function() {

	$('#select_dt_name').change(function(e){
		dist = $('#select_dt_name').val();
		//alert(dist);
		var options = $("#school_name");
		options.prop("disabled", true);
		
	if( dist != "All" ){
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
				options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Select school"));
				//options.append($("<option />").val("").text(""));
				$.each(result, function() {
				    options.append($("<option />").val(this.school_name).text(this.school_name));
				});
						
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
		}else{
			
			$.SmartMessageBox({
				title : "Alert !",
				content : "This will take long time to display students report. Are you sure you want to continue.",
				buttons : '[No][Yes]'
			}, function(ButtonPressed) {
				if (ButtonPressed === "Yes") 
				{
					$.ajax({
						url: 'get_schools_list',
						type: 'POST',
						data: {"dist_id" : dist},
						success: function (data) {			
							console.log(data);
							result = $.parseJSON(data);
							console.log(result);
							display_data_table(result);
									
							},
						    error:function(XMLHttpRequest, textStatus, errorThrown)
							{
							 console.log('error', errorThrown);
						    }
						});
				}
				if (ButtonPressed === "No")
				{
					
				}
				
	       });
			
		}
	});


var school_name = $('#school_name').val();



$('#school_name').change(function(e){
	
	school_name = $('#school_name').val();
	dist = $('#select_dt_name option:selected').text();

	$.ajax({

		url: 'get_sanitation_inspection_report',
		type: 'POST',
		data: { "school_name" : school_name, "select_dt_name" : dist},
		success: function (data) 
		{	
			data = $.parseJSON(data);
			result = data.get_sanitation_reports;
			display_data_table(result);
		},
	    error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 	console.log('error', errorThrown);
	    }
	});
	
	});

	function display_data_table(result){

		if(result.length > 0){
			

			data_table = '';
			$.each(result, function() {
				
				//data_table = data_table +'<div class="panel-group smart-accordion-default accordion-2"><div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseOne-1" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i>'+ this.doc_data['School Information']['school_name']+'&nbsp;&nbsp;&nbsp;'+this.history['last_stage']['time']+'</a></h4></div><div id="collapseOne-1" class="panel-collapse collapse"><div class="panel-body">';

				data_table = data_table+'<div class="accordion-2"><h4>'+ this.doc_data['School Information']['school_name']+'&nbsp;&nbsp;&nbsp; Submitted Report at  '+this.history['last_stage']['time']+'</h4>';

				// School information
				data_table = data_table +'<div class="panel-body"><div class="row"><div class="col-sm-6"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Principal Name</td><td>'+this.doc_data['widget_data']['page1']['School Info']['Principal Name']+'</td></tr><tr><td>Contact Number</td><td>'+this.doc_data['widget_data']['page1']['School Info']['Contact Number']+'</td></tr><tr><td>HS Name</td><td>'+this.doc_data['widget_data']['page1']['School Info']['HS Name']+'</td></tr><tr><td>Asst Care Taker Name</td><td>'+this.doc_data['widget_data']['page1']['School Info']['Asst Care Taker Name']+'</td></tr> </tbody></table></div>';

				// General information
				data_table = data_table +'<div class="col-sm-6"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>No of Children with Special Needs</td><td>'+this.doc_data['widget_data']['page2']['GENERAL INFORMATION']['No of Children with Special Needs']+'</td></tr><tr><td>School Type</td><td>'+this.doc_data['widget_data']['page2']['GENERAL INFORMATION']['Type of School']+'</td></tr><tr><td>Whether the school has electricity</td><td>'+this.doc_data['widget_data']['page2']['GENERAL INFORMATION']['Whether the school has electricity']+'</td></tr><tr><td>Status of school boundary or compound wall</td><td>'+this.doc_data['widget_data']['page2']['GENERAL INFORMATION']['Status of school boundary or compound wall']+'</td></tr> </tbody></table></div>';

				// Water information
				data_table = data_table +'<div class="col-sm-6"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>What is the source of drinking water in the premises</td><td>'+this.doc_data['widget_data']['page3']['WATER']['What is the source of drinking water in the premises']+'</td></tr><tr><td>What is the status of functionality of the source of the drinking water</td><td>'+this.doc_data['widget_data']['page3']['WATER']['What is the status of functionality of the source of the drinking water']+'</td></tr><tr><td>Does water source need repairs</td><td>'+this.doc_data['widget_data']['page3']['WATER']['Does water source need repairs']+'</td></tr> <tr><td>Whether the school has functioning overhead tank for drinking water storage</td><td>'+this.doc_data['widget_data']['page3']['WATER']['Whether the school has functioning overhead tank for drinking water storage']+'</td></tr><tr><td>If so how is water lifted to the tank</td><td>'+this.doc_data['widget_data']['page3']['WATER']['If so how is water lifted to the tank']+'</td></tr> </tbody></table></div>';

				// TOILETS information
				data_table = data_table +'<div class="col-sm-6"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Does the school have toilets in the premises</td><td>'+this.doc_data['widget_data']['page4']['TOILETS']['Does the school have toilets in the premises']+'</td></tr><tr><td>Girls</td><td>'+this.doc_data['widget_data']['page4']['TOILETS']['Girls']+'</td></tr><tr><td>Boys</td><td>'+this.doc_data['widget_data']['page4']['TOILETS']['Boys']+'</td></tr><tr><td>Teachers</td><td>'+this.doc_data['widget_data']['page4']['TOILETS']['Teachers']+'</td></tr><tr><td>Common</td><td>'+this.doc_data['widget_data']['page4']['TOILETS']['Common']+'</td></tr><tr><td>How many are functional</td><td>'+this.doc_data['widget_data']['page4']['TOILETS']['How many are functional']+'</td></tr><tr><td>Are the toilets clean odorless and well maintained</td><td>'+this.doc_data['widget_data']['page4']['TOILETS']['Are the toilets clean odorless and well maintained']+'</td></tr><tr><td>Do the toilets need repairs</td><td>'+this.doc_data['widget_data']['page4']['TOILETS']['Do the toilets need repairs']+'</td></tr> </tbody></table></div>';

				// TOILETS information
				data_table = data_table +'<div class="col-sm-6"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>How is the water provided to toilets or urinals</td><td>'+this.doc_data['widget_data']['page5']['TOILETS']['How is the water provided to toilets or urinals']+'</td></tr><tr><td>Is there a toilet specially for children with special needs</td><td>'+this.doc_data['widget_data']['page5']['TOILETS']['Is there a toilet specially for children with special needs']+'</td></tr><tr><td>If yes does it have ramp access with handrail</td><td>'+this.doc_data['widget_data']['page5']['TOILETS']['If yes does it have ramp access with handrail']+'</td></tr><tr><td>A wide door for wheelchair entry</td><td>'+this.doc_data['widget_data']['page5']['TOILETS']['A wide door for wheelchair entry']+'</td></tr><tr><td>Handrails inside the toilet for support</td><td>'+this.doc_data['widget_data']['page5']['TOILETS']['Handrails inside the toilet for support']+'</td></tr><tr><td>Are there cleaning materials available near the toilet for cleaning toilets or urinals</td><td>'+this.doc_data['widget_data']['page5']['TOILETS']['Are there cleaning materials available near the toilet for cleaning toilets or urinals']+'</td></tr><tr><td>Is there handwashing facility attached or close to the toilet</td><td>'+this.doc_data['widget_data']['page5']['TOILETS']['Is there handwashing facility attached or close to the toilet']+'</td></tr> </tbody></table></div>';

				// TOILETS information
				data_table = data_table +'<div class="col-sm-6"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Is there water provided in the handwashing facility</td><td>'+this.doc_data['widget_data']['page6']['TOILETS']['Is there water provided in the handwashing facility']+'</td></tr><tr><td>Is there soap provided in the handwashing facility</td><td>'+this.doc_data['widget_data']['page6']['TOILETS']['Is there soap provided in the handwashing facility']+'</td></tr><tr><td>Who cleans the toilets and urinals</td><td>'+this.doc_data['widget_data']['page6']['TOILETS']['Who cleans the toilets and urinals']+'</td></tr><tr><td>How often are the toilets or urinals cleaned</td><td>'+this.doc_data['widget_data']['page6']['TOILETS']['How often are the toilets or urinals cleaned']+'</td></tr><tr><td>Is there adeqate and private space for changing and disposal facilities for menstrual waste including dust bins</td><td>'+this.doc_data['widget_data']['page6']['TOILETS']['Is there adeqate and private space for changing and disposal facilities for menstrual waste including dust bins']+'</td></tr><tr><td>Is there any incinerator installed for the disposal of menstrual waste</td><td>'+this.doc_data['widget_data']['page6']['TOILETS']['Is there any incinerator installed for the disposal of menstrual waste']+'</td></tr></tbody></table></div>';

				// General information
				data_table = data_table +'<div class="col-sm-6"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Handrails inside the toilet for support</td><td>'+this.doc_data['widget_data']['page7']['TOILETS']['Handrails inside the toilet for support']+'</td></tr><tr><td>Are there cleaning materials available near the toilet for cleaning toilets or urinals</td><td>'+this.doc_data['widget_data']['page7']['TOILETS']['Are there cleaning materials available near the toilet for cleaning toilets or urinals']+'</td></tr></tbody></table></div>';

				

				//data_table = data_table +'<table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th></tr></thead><tbody><tr><td><a href="<?php echo URLCustomer;?>'+this.doc_data.external_attachments.file_path+'" rel="prettyPhoto[gal]">'+this.doc_data.external_attachments.file_client_name+'</a></td></tr></tbody></table></div></div></div>';

				var external_attachments = this.doc_data.external_attachments;
				
				data_table = data_table +'<h3>Attachments</h3><span class="attach_count badge bg-color-blueDark txt-color-white"></span><table class="table table-bordered" id="external_files" style="width: fit-content">';

				var length = Object.keys(external_attachments).length;
				if(length > 0)
				{
				for(var item in external_attachments)
				{
				  data_table = data_table + '<tr><td><a href="<?php echo URLCustomer;?>'+external_attachments[item].file_path+'" rel="prettyPhoto[gal]">'+external_attachments[item].file_client_name+'</a></td></tr>'
				  
				}
				}
				else
				{
				  data_table = data_table + '<tr><td>No attachments </td></tr>'
				}
				
				$("#external_files").html(data_table)
				$('.attach_count').html(length);

				data_table = data_table+'</table></div></div></div></div>';		
				$("a[rel^='prettyPhoto']").prettyPhoto();
		
			});

			
			$("#sanitation_report").html(data_table);
			//=====================================================================================================
			}else{
				$("#sanitation_report").html('<h5>No Reports to display for this school</h5>');
			}

			var accordionIcons = {
		         header: "fa fa-plus",    // custom icon class
		         activeHeader: "fa fa-minus" // custom icon class
		     };
		     
			$(".accordion-2").accordion({
				autoHeight : false,
				heightStyle : "content",
				collapsible : true,
				toggle:true,
				active : 'none',
				animate : 300,
				icons: accordionIcons,
				header : "h4"
			})


	}

});

//===================================drill down pie======================
//===================================end of dril down pie================
</script>

