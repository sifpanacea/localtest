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
$page_nav["rhso_reports"]["sub"]["civil_infrastructure"]["active"] = true;

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
							<h2>Civil and Infrastructure Report </h2>
						</header>
		
						
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body">

								<div id="civil_infrastructure_report">
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
	
	dist = $('#select_dt_name option:selected').text();
	school_name = $('#school_name').val();
	
	$.ajax({

		url: 'get_civil_and_infrastructure_report',
		type: 'POST',
		data: {"select_dt_name" : dist,"school_name" : school_name},
		success: function (data) 
		{	
			data = $.parseJSON(data);
			result = data.get_civil_and_infrastructure_reports;
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
				
				

				data_table = data_table+'<div class="accordion-2"><h4>'+ this.doc_data['School Information']['school_name']+'&nbsp;&nbsp;&nbsp; Submitted Report at  '+this.history['last_stage']['time']+'</h4>';

				// School information
				data_table = data_table +'<div class="row"><div class="col-sm-6"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Principal Name</td><td>'+this.doc_data['widget_data']['page1']['School Info']['Principal Name']+'</td></tr><tr><td>Contact Number</td><td>'+this.doc_data['widget_data']['page1']['School Info']['Principal Number']+'</td></tr><tr><td>HS Name</td><td>'+this.doc_data['widget_data']['page1']['School Info']['HS Name']+'</td></tr><tr><td>HS Mobile</td><td>'+this.doc_data['widget_data']['page1']['School Info']['HS Number']+'</td></tr><tr><td>Category</td><td>'+this.doc_data['widget_data']['page1']['School Info']['Category']+'</td></tr> </tbody></table></div>';

				// School Building information
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">School Building</div><div class="panel-body"> <table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Obsevation of issues</td><td>'+this.doc_data['widget_data']['page2']['School Building']['1 Obsevation of issues']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page2']['School Building']['2 Remarks']+'</td></tr></tbody></table></div></div></div>';

				
				// Kitchen and Dining information
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Kitchen and Dining</div><div class="panel-body"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Obsevation of issues</td><td>'+this.doc_data['widget_data']['page2']['Kitchen and Dining']['3 Obsevation of issues']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page3']['Kitchen and Dining']['4 Remarks']+'</td></tr></tbody></table></div></div></div>';
				

				// Water Supply information
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Water Supply</div><div class="panel-body"><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Obsevation of issues</td><td>'+this.doc_data['widget_data']['page3']['Water Supply']['5 Obsevation of issues']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page3']['Water Supply']['6 Remarks']+'</td></tr></tbody></table></div></div></div>';

				// RO Plant information
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">RO Plant</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Obsevation of issues</td><td>'+this.doc_data['widget_data']['page4']['RO Plant']['7 Obsevation of issues']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page4']['RO Plant']['8 Remarks']+'</td></tr></tbody></table></div></div</div>';
				
				// Electrical Transformer
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Electrical Transformer</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Obsevation of issues</td><td>'+this.doc_data['widget_data']['page4']['Electrical Transformer']['9 Obsevation of issues']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page5']['Electrical Transformer']['10 Remarks']+'</td></tr></tbody></table></div></div></div>';

				// Generator
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Generator</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Obsevation of issues</td><td>'+this.doc_data['widget_data']['page5']['Generator']['11 Obsevation of issues']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page5']['Generator']['12 Remarks']+'</td></tr></tbody></table></div></div>';

				// Compound wall
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Compound wall</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Obsevation of issues</td><td>'+this.doc_data['widget_data']['page6']['Compound wall']['13 Obsevation of issues']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page6']['Compound wall']['14 Remarks']+'</td></tr></tbody></table></div></div>';

				// Internal road
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Internal road</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Obsevation of issues</td><td>'+this.doc_data['widget_data']['page6']['Internal road']['15 Obsevation of issues']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page7']['Internal road']['16 Remarks']+'</td></tr></tbody></table></div></div>';

				// Fire Extinguishers
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Fire Extinguishers</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Obsevation of issues</td><td>'+this.doc_data['widget_data']['page7']['Fire Extinguishers']['17 Obsevation of issues']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page7']['Fire Extinguishers']['18 Remarks']+'</td></tr></tbody></table></div></div>';

				// Electrification
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Electrification</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Obsevation of issues</td><td>'+this.doc_data['widget_data']['page8']['Electrification']['19 Obsevation of issues']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page8']['Electrification']['20 Remarks']+'</td></tr></tbody></table></div></div>';

				// General or Water sanitation
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">General or Water sanitation</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Obsevation of issues</td><td>'+this.doc_data['widget_data']['page8']['General or Water sanitation']['21 Obsevation of issues']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page9']['General or Water sanitation']['22 Remarks']+'</td></tr></tbody></table></div></div>';

				// Any Others
				data_table = data_table +'<div class="col-sm-6"><div class="panel panel-primary"><div class="panel-heading">Any Others</div><table class="table table-bordered"> <thead><tr><th style="width:50%">Item</th><th style="width:50%">Value</th></tr> </thead> <tbody><tr><td>Obsevation of issues</td><td>'+this.doc_data['widget_data']['page9']['Any Others']['23 Obsevation of issues']+'</td></tr><tr><td>Remarks</td><td>'+this.doc_data['widget_data']['page9']['Any Others']['24 Remarks']+'</td></tr><tr><td>Comments or Suggestions</td><td>'+this.doc_data['widget_data']['page9']['Any Others']['Comments or Suggestions']+'</td></tr><tr><td>Overall rating for the food and hygine at institution</td><td>'+this.doc_data['widget_data']['page9']['Any Others']['Overall rating for the food and hygine at institution']+'</td></tr></tbody></table></div></div>';

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

			
			$("#civil_infrastructure_report").html(data_table);
			//=====================================================================================================
			}else{
				$("#civil_infrastructure_report").html('<h5>No Reports to display for this school</h5>');
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

