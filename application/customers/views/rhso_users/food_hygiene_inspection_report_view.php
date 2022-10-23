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
$page_nav["rhso_reports"]["sub"]["food_hygiene_inspection"]["active"] = true;

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
							<h2>Food and Hygiene Inspection Report </h2>
						</header>
		
						
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body">

								<div id="food_hygiene_report">
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

		url: 'get_food_hygiene_inspection_report',
		type: 'POST',
		data: { "school_name" : school_name},
		success: function (data) 
		{	
			data = $.parseJSON(data);
			result = data.get_food_hygiene_inspection_reports;
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
				data_table = data_table +'<div class="row"><div class="col-sm-6"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Principal Name</td><td>'+this.doc_data['widget_data']['page1']['School Info']['Principal name']+'</td></tr><tr><td>HS Name</td><td>'+this.doc_data['widget_data']['page1']['School Info']['Health Sup Name']+'</td></tr><tr><td>Date</td><td>'+this.doc_data['widget_data']['page1']['School Info']['Date']+'</td></tr><tr><td>Category</td><td>'+this.doc_data['widget_data']['page1']['School Info']['Category']+'</td></tr> </tbody></table></div>';

				// Food Preparation area or Kitchen information
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Food Preparation area or Kitchen</div><div class="panel-body"> <table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page2']['Food Preparation area or Kitchen']['1 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page2']['Food Preparation area or Kitchen']['2 Remarks']+'</td></tr></tbody></table></div></div></div>';

				
				// Cooking Mode information
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Cooking Mode</div><div class="panel-body"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page2']['Cooking Mode']['3 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page3']['Cooking Mode']['4 Remarks']+'</td></tr></tbody></table></div></div></div>';
				

				// Srorage of Vegetables and Cutting area information
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Srorage of Vegetables and Cutting area</div><div class="panel-body"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page3']['Srorage of Vegetables and Cutting area']['5 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page3']['Srorage of Vegetables and Cutting area']['6 Remarks']+'</td></tr></tbody></table></div></div></div>';

				// Personal Hygiene of Food Handlers information
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Personal Hygiene of Food Handlers</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page4']['Personal Hygiene of Food Handlers']['7 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page4']['Personal Hygiene of Food Handlers']['8 Remarks']+'</td></tr></tbody></table></div></div</div>';
				
				// Condition of Cooking Containers
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Condition of Cooking Containers</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page5']['Condition of Cooking Containers']['9 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page5']['Condition of Cooking Containers']['10 Remarks']+'</td></tr></tbody></table></div></div></div>';

				// Store room
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Store room</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page5']['Store room']['11 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page5']['Store room']['12 Remarks']+'</td></tr></tbody></table></div></div>';

				// Quality of raw material for preperation of food
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Quality of raw material for preperation of food</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page6']['Quality of raw material for preperation of food']['13 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page6']['Quality of raw material for preperation of food']['14 Remarks']+'</td></tr></tbody></table></div></div>';

				// Samples collected
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Samples collected</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page6']['Samples collected']['15 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page7']['Samples collected']['16 Remarks']+'</td></tr></tbody></table></div></div>';



				// Eggs
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Eggs</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page7']['Eggs']['17 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page7']['Eggs']['18 Remarks']+'</td></tr></tbody></table></div></div>';

				// Milk and Curd
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Milk and Curd</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page8']['Milk and Curd']['19 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page8']['Milk and Curd']['20 Remarks']+'</td></tr></tbody></table></div></div>';

				// Banana or Fruit
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Banana or Fruit</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page8']['Banana or Fruit']['21 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page8']['Banana or Fruit']['22 Remarks']+'</td></tr></tbody></table></div></div>';

				// Cooked prepared food articles
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Cooked prepared food articles</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page9']['Cooked prepared food articles']['23 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page9']['Cooked prepared food articles']['24 Remarks']+'</td></tr></tbody></table></div></div>';

				// Drinking Water
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Drinking Water</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page9']['Drinking Water']['25 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page10']['Drinking Water']['26 Remarks']+'</td></tr></tbody></table></div></div>';

				// Dining Hall
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Dining Hall</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page10']['Dining Hall']['27 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page10']['Dining Hall']['28 Remarks']+'</td></tr></tbody></table></div></div>';

				// Hand washing facility in dining area
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Hand washing facility in dining area</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page11']['Hand washing facility in dining area']['29 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page11']['Hand washing facility in dining area']['30 Remarks']+'</td></tr></tbody></table></div></div>';

				// Any other
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Any other</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Observation of issues or Faults</td><td>'+this.doc_data['widget_data']['page12']['Any other']['31 Observation of issues or Faults']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page12']['Any other']['32 Remarks']+'</td></tr><tr><td>Comments or Suggestions</td><td>'+this.doc_data['widget_data']['page13']['Any other']['Comments or Suggestions']+'</td></tr><tr><td>Overall rating for the food and hygiens at institution</td><td>'+this.doc_data['widget_data']['page13']['Any other']['Overall rating for the food and hygiens at institution']+'</td></tr><tr><td>Inspected By</td><td>'+this.doc_data['widget_data']['page13']['Inspected By']['Name']+'</td></tr></tbody></table></div></div>'


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

			
			$("#food_hygiene_report").html(data_table);
			//=====================================================================================================
			}else{
				$("#food_hygiene_report").html('<h5>No Reports to display for this school</h5>');
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

