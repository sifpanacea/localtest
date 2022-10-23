<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "RHSO Reports";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["rhso_reports"]["sub"]["health_inspector_inspection"]["active"] = true;

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
   
}
.panel-body {
	padding: 0px;
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
													<fieldset>
													
														<section class="col col-6">
														<label class="label" for="school_name">School Name</label>
														<label class="select">
														<select id="school_name" name="school_name">
														<option value='Select' >Select</option>
																<?php if(isset($schools_list)): ?>
																	<?php foreach ($schools_list as $school):?>
																	<option value='<?php echo $school['school_name']?>'><?php echo ucfirst($school['school_name'])?></option>
																	<?php endforeach;?>
																	<?php else: ?>
																	
																<?php endif ?>
														</select> <i></i>
													</label>
													</section>
													
													</fieldset>
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
							<h2>Health Inspector Inspection Report</h2>
						</header>
		
						
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body">

								<div id="health_inspection_report">
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

var school_name = $('#school_name').val();


	<?php if($message) { ?>
	$.smallBox({
					title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
					content : "<?php echo $message?>",
					color : "#296191",
					iconSmall : "fa fa-bell bounce animated",
					timeout : 8000
				});
	<?php } ?>

$('#school_name').change(function(e){
	
	school_name = $("#school_name option:selected").text();
	
	$.ajax({

		url: 'get_health_inspector_inspection_report',
		type: 'POST',
		data: { "school_name" : school_name},
		success: function (data) 
		{	
			data = $.parseJSON(data);
			result = data.get_health_inspector_reports;
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
				
				

				data_table = data_table+'<div class="accordion-2"><h4>'+ this.doc_data['School Information']['school_name']+'&nbsp;&nbsp;&nbsp;'+this.history['last_stage']['time']+'</h4>';

				// School information
				data_table = data_table +'<div class="row"><div class="col-sm-6"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>School Code</td><td>'+this.doc_data['widget_data']['page1']['School Info']['Hospital Unique ID']+'</td></tr><tr><td>Date of Visit</td><td>'+this.doc_data['widget_data']['page1']['School Info']['Date of Visit']+'</td></tr><tr><td>Info to PANACEA</td><td>'+this.doc_data['widget_data']['page1']['SIFNOTE Status']['Info to PANACEA']+'</td></tr><tr><td>Principal Name</td><td>'+this.doc_data['widget_data']['page1']['SIFNOTE Status']['Principal Name']+'</td></tr><tr><td>Category</td><td>'+this.doc_data['widget_data']['page1']['SIFNOTE Status']['Category']+'</td></tr><tr><td>Hs Name</td><td>'+this.doc_data['widget_data']['page2']['SIFNOTE Status']['HS Name']+'</td></tr><tr><td>HS Qualification</td><td>'+this.doc_data['widget_data']['page2']['SIFNOTE Status']['HS Qualification']+'</td></tr><tr><td>Name of asst care taker</td><td>'+this.doc_data['widget_data']['page2']['SIFNOTE Status']['Name of asst care taker']+'</td></tr><tr><td>Students Strength</td><td>'+this.doc_data['widget_data']['page2']['SIFNOTE Status']['Students Strength']+'</td></tr><tr><td>Classes</td><td>'+this.doc_data['widget_data']['page2']['SIFNOTE Status']['Classes']+'</td></tr></tbody></table></div>';

				 //Sick Room Specifications
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Sick Room Specifications</div><div class="panel-body"> <table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Number of Rooms</td><td>'+this.doc_data['widget_data']['page3']['Sick Room Specifications']['Number of Rooms']+'</td></tr><tr><td>Table Maintenance</td><td>'+this.doc_data['widget_data']['page3']['Sick Room Specifications']['Table Maintenance']+'</td></tr><tr><td>Green Cloth</td><td>'+this.doc_data['widget_data']['page3']['Sick Room Specifications']['Green Cloth']+'</td></tr></tbody></table></div></div></div>';

				
				// Tray
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Tray</div><div class="panel-body"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Betadine</td><td>'+this.doc_data['widget_data']['page3']['Tray']['Betadine']+'</td></tr><tr><td>Surgical Spirit</td><td>'+this.doc_data['widget_data']['page3']['Tray']['Surgical Spirit']+'</td></tr><tr><td>Surgical Spirit</td><td>'+this.doc_data['widget_data']['page3']['Tray']['Hydrogen Peroxide']+'</td></tr><tr><td>Cotton or Gauge</td><td>'+this.doc_data['widget_data']['page3']['Tray']['Cotton or Gauge']+'</td></tr></tbody></table></div></div></div>';
				

				// Equipment
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Equipment</div><div class="panel-body"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Weighing Machine</td><td>'+this.doc_data['widget_data']['page4']['Equipment']['Weighing Machine']+'</td></tr><tr><td>BP apparatus</td><td>'+this.doc_data['widget_data']['page4']['Equipment']['BP apparatus']+'</td></tr><tr><td>Pulse Oxymeter</td><td>'+this.doc_data['widget_data']['page4']['Equipment']['Pulse Oxymeter']+'</td></tr><tr><td>Thermometer</td><td>'+this.doc_data['widget_data']['page4']['Equipment']['Thermometer']+'</td></tr><tr><td>Stethoscope</td><td>'+this.doc_data['widget_data']['page4']['Equipment']['Stethoscope']+'</td></tr><tr><td>Nebulizer</td><td>'+this.doc_data['widget_data']['page4']['Equipment']['Nebulizer']+'</td></tr><tr><td>Examination Table</td><td>'+this.doc_data['widget_data']['page4']['Equipment']['Examination Table']+'</td></tr><tr><td>Saline Stand</td><td>'+this.doc_data['widget_data']['page4']['Equipment']['Saline Stand']+'</td></tr><tr><td>Cots or Mattress</td><td>'+this.doc_data['widget_data']['page5']['Equipment']['Cots or Mattress']+'</td></tr><tr><td>Curtains</td><td>'+this.doc_data['widget_data']['page5']['Equipment']['Curtains']+'</td></tr><tr><td>Mesh</td><td>'+this.doc_data['widget_data']['page5']['Equipment']['Mesh']+'</td></tr><tr><td>Fans</td><td>'+this.doc_data['widget_data']['page5']['Equipment']['Fans']+'</td></tr></tbody></table></div></div></div>';


				// Pharmacy
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Pharmacy</div><div class="panel-body"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Emergency</td><td>'+this.doc_data['widget_data']['page5']['Pharmacy']['Emergency']+'</td></tr><tr><td>Regular</td><td>'+this.doc_data['widget_data']['page5']['Pharmacy']['Regular']+'</td></tr><tr><td>Flow Charts</td><td>'+this.doc_data['widget_data']['page5']['Pharmacy']['Flow Charts']+'</td></tr></tbody></table></div></div></div>';

				// Any Health Checkups
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Any Health Checkups</div><div class="panel-body"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Vision</td><td>'+this.doc_data['widget_data']['page6']['Any Health Checkups']['Vision']+'</td></tr><tr><td>HB</td><td>'+this.doc_data['widget_data']['page6']['Any Health Checkups']['HB']+'</td></tr><tr><td>Dental</td><td>'+this.doc_data['widget_data']['page6']['Any Health Checkups']['Dental']+'</td></tr><tr><td>Deworming</td><td>'+this.doc_data['widget_data']['page6']['Any Health Checkups']['Deworming']+'</td></tr><tr><td>Vaccination</td><td>'+this.doc_data['widget_data']['page6']['Any Health Checkups']['Vaccination']+'</td></tr><tr><td>Hospitalization</td><td>'+this.doc_data['widget_data']['page6']['Any Health Checkups']['Hospitalization']+'</td></tr><tr><td>Epidemics</td><td>'+this.doc_data['widget_data']['page6']['Any Health Checkups']['Epidemics']+'</td></tr></tbody></table></div></div></div>';


				// Incenerators / Others
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Incenerators / Others</div><div class="panel-body"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Working Condition</td><td>'+this.doc_data['widget_data']['page7']['Incenerators']['Working Condition']+'</td></tr><tr><td>Using or not using</td><td>'+this.doc_data['widget_data']['page7']['Incenerators']['Using or not using']+'</td></tr><tr><td>Fly catchers</td><td>'+this.doc_data['widget_data']['page7']['Others']['Fly catchers']+'</td></tr><tr><td>RO Plant</td><td>'+this.doc_data['widget_data']['page7']['Others']['RO Plant']+'</td></tr><tr><td>Wash rooms</td><td>'+this.doc_data['widget_data']['page7']['Others']['Wash rooms']+'</td></tr><tr><td>Sinks at Wash room or Mess</td><td>'+this.doc_data['widget_data']['page7']['Others']['Sinks at Wash room or Mess']+'</td></tr><tr><td>Handwash at Wash room or Mess</td><td>'+this.doc_data['widget_data']['page7']['Others']['Handwash at Wash room or Mess']+'</td></tr></tbody></table></div></div></div>';

				// Others
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Others</div><div class="panel-body"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Principal Visit date</td><td>'+this.doc_data['widget_data']['page8']['Others']['Principal Visit date']+'</td></tr><tr><td>Name of PD or PET</td><td>'+this.doc_data['widget_data']['page8']['Others']['Name of PD or PET']+'</td></tr><tr><td>PET Qualification</td><td>'+this.doc_data['widget_data']['page8']['Others']['PET Qualification']+'</td></tr><tr><td>Type</td><td>'+this.doc_data['widget_data']['page8']['Others']['Type']+'</td></tr><tr><td>Regular Exercise</td><td>'+this.doc_data['widget_data']['page9']['Others']['Regular Exercise']+'</td></tr><tr><td>Dietary Habits</td><td>'+this.doc_data['widget_data']['page9']['Others']['Dietary Habits']+'</td></tr><tr><td>Awareness</td><td>'+this.doc_data['widget_data']['page9']['Others']['Awareness']+'</td></tr><tr><td>Education</td><td>'+this.doc_data['widget_data']['page9']['Others']['Education']+'</td></tr><tr><td>Motivation</td><td>'+this.doc_data['widget_data']['page9']['Others']['Motivation']+'</td></tr><tr><td>Special Sports</td><td>'+this.doc_data['widget_data']['page9']['Others']['Special Sports']+'</td></tr><tr><td>Name of the Officer</td><td>'+this.doc_data['widget_data']['page9']['Name and Signature of the Inspection Officer']['Name']+'</td></tr></tbody></table></div></div></div>';

				var external_attachments = this.doc_data.external_attachments;
				console.log('external_attachments',external_attachments);
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
				$("a[rel^='prettyPhoto']").prettyPhoto();

				$('.attach_count').html(length);

				data_table = data_table+'</table></div></div></div>';		
				
		
			});

			
			$("#health_inspection_report").html(data_table);
			//=====================================================================================================
			}else{
				$("#health_inspection_report").html('<h5>No Reports to display for this school</h5>');
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

