<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Schools";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["schools"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
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
	
	
	<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Select a school | Total schools : <span class="badge bg-color-greenLight">
							<?php if(!empty($schoolscount)) {?><?php echo $schoolscount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>

</header>

<!-- widget div-->
<div>

	<!-- widget edit box -->
	<div class="jarviswidget-editbox">
		<!-- This area used as dropdown edit box -->

	</div>
	<!-- end widget edit box -->

	<!-- widget content -->
	
	<div class="widget-body no-padding">
	<form class=smart-form>
		<!--<form class="smart-form">-->
			
			<fieldset>
			<div class="row">
			<section class="col col-6">
				<label class="label" for="state_name">State Name</label>
				<label class="select">
				<select id="select_state_name" >
					<option value="" selected="0" disabled="">Select a state</option>
					<?php if(isset($states)): ?>
						<option value='All' >All</option>
						<?php foreach ($states as $state):?>
						<option value='<?php echo $state['_id']?>' ><?php echo ucfirst($state['st_name'])?></option>
						<?php endforeach;?>
						<?php else: ?>
						<option value="1"  disabled="">No state entered yet</option>
					<?php endif ?>
				</select> <i></i>
			</label>
			</section>
			<section class="col col-6">
				<label class="label" for="first_name">District Name</label>
				<label class="select">
				<select id="select_dt_name" >
					<option value="0" selected="" disabled="">Select a state first</option>
					
				</select> <i></i>
			</label>
			</section>
			</div>
			
			
			</fieldset>
			</form>
		

	</div>
	<!-- end widget content -->

</div>
<!-- end widget div -->

</div>
</article>

</div><!-- ROW -->


<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Schools List</h2>

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
	
		<div id="stud_report">
		Select from drop down to display schools.
		</div>

	</div>
	<!-- end widget content -->

</div>
<!-- end widget div -->

</div>
</article>

</div><!-- ROW -->
	


				

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
<script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
$(document).ready(function() {
    
	$('#select_state_name').change(function(e){
		state = $('#select_state_name').val();
		var options = $("#select_dt_name");
		options.prop("disabled",true);
		
	if( state != "All" )
	{
		options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching districts list..."));
		$.ajax({
			url: 'get_districts_list_for_state',
			type: 'POST',
			data: {"state" : state},
			success: function (data) {			

			     data = data.trim();
			    if(data!="NO_DISTRICTS")
				{
				result = $.parseJSON(data);

				options.prop("disabled", false);
				options.empty();
				options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Select district"));
				options.append($("<option />").val("All").text("All"));
				$.each(result, function() {
				    options.append($("<option />").val(this._id['$id']).text(this.dt_name));
				});
				}
				else
				{
				}
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
	}
	else
	{
			
			$.SmartMessageBox({
				title : "Alert !",
				content : "This will take long time to display school report. Are you sure you want to continue ?",
				buttons : '[No][Yes]'
			}, function(ButtonPressed) {
				if (ButtonPressed === "Yes") 
				{
			        options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching districts list..."));
					$.ajax({
						url: 'get_all_districts',
						type: 'POST',
						success: function (data) {			
							result = $.parseJSON(data);
							options.prop("disabled", false);
							options.empty();
							options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Select district"));
							options.append($("<option />").val("All").text("All"));
							$.each(result, function() {
								options.append($("<option />").val(this._id['$id']).text(this.dt_name));
							});
									
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
	
	
	$('#select_dt_name').change(function(e)
	{
		state = $('#select_state_name').val();
		dist  = $('#select_dt_name').val();
		
		if(dist != "All") 
		{
		    $.ajax({
			url: 'get_schools_list',
			type: 'POST',
			data: {"dist_id" :dist,"state_id":state},
			success: function (data) 
			{		
			    console.log(data);
                result = $.parseJSON(data);
				display_school_list(result);
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
			});
		}
		else
		{
			
			$.SmartMessageBox({
				title : "Alert !",
				content : "This will take long time to display schools report. Are you sure you want to continue ?",
				buttons : '[No][Yes]'
			}, function(ButtonPressed) 
			{
				if (ButtonPressed === "Yes") 
				{
					$.ajax({
						url  : 'get_all_schools_list',
						type : 'POST',
						success: function (data) 
						{			
							result = $.parseJSON(data);
							console.log(result);
							display_all_schools_list(result);
									
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

	function display_school_list(result)
	{
		if(result.length > 0)
		{
			data_table = '<table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">  <thead> <tr> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter SCHOOL NAME" /> </th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter SCHOOL CODE" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter CONTACT PERSON" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter MOBILE" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter ADDRESS" /> </th></tr> <tr> <th>SCHOOL NAME</th> <th>SCHOOL CODE</th> <th>CONTACT PERSON</th> <th>MOBILE</th> <th>ADDRESS</th></tr> </thead> <tbody>';

			$.each(result, function() {
				data_table = data_table + '<tr>';
				data_table = data_table + '<td>'+this.school_name + '</td>';
				data_table = data_table + '<td>'+this.school_code + '</td>';
				data_table = data_table + '<td>'+this.contact_person + '</td>';
				data_table = data_table + '<td>'+this.mobile + '</td>';
				data_table = data_table + '<td>'+this.address + '</td>';
				data_table = data_table + '</tr>';
					
			});

			data_table = data_table + '</tbody></table>';

			$("#stud_report").html(data_table);

			//=========================================data table functions=====================================
			
						/* BASIC ;*/
				var responsiveHelper_dt_basic = undefined;
				var responsiveHelper_datatable_fixed_column = undefined;
				var responsiveHelper_datatable_col_reorder = undefined;
				var responsiveHelper_datatable_tabletools = undefined;
				
				var breakpointDefinition = {
					tablet : 1024,
					phone : 480
				};
		
				$('#dt_basic').dataTable({
					"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
					"autoWidth" : true,
					"preDrawCallback" : function() {
						// Initialize the responsive datatables helper once.
						if (!responsiveHelper_dt_basic) {
							responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
						}
					},
					"rowCallback" : function(nRow) {
						responsiveHelper_dt_basic.createExpandIcon(nRow);
					},
					"drawCallback" : function(oSettings) {
						responsiveHelper_dt_basic.respond();
					}
				});
		
			/* END BASIC */
			var js_url = "<?php echo JS; ?>";
			/* COLUMN FILTER  */
		    var otable = $('#datatable_fixed_column').DataTable({
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
				 "oTableTools": {
		        	 "aButtons": [
		        	      {
		                 "sExtends": "xls",
		                 "sTitle": "TLSTEC Schools Report",
		                 "sPdfMessage": "TLSTEC Schools Excel Export",
		                 "sPdfSize": "letter"
			             },
			          	{
			             	"sExtends": "print",
			             	"sMessage": "TLSTEC Schools Printout <i>(press Esc to close)</i>"
			         	}],
		        	 "sSwfPath": js_url+"datatables/swf/copy_csv_xls_pdf.swf"
		        },
				"autoWidth" : true,
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_fixed_column) {
						responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_datatable_fixed_column.respond();
				}		
			
		    });
		    
		    // Apply the filter
		    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
		    	
		        otable
		            .column( $(this).parent().index()+':visible' )
		            .search( this.value )
		            .draw();
		            
		    } );
		    /* END COLUMN FILTER */   
			
			
			//=====================================================================================================
			
		}
		else
		{
			$("#stud_report").html('<h5>No schools to display for this district</h5>');
		}
	}
	
	function display_all_schools_list(result)
	{
		if(result.length > 0)
		{
			data_table = '<table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">  <thead> <tr> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter STATE" /> </th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter DISTRICT" /> </th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter SCHOOL NAME" /> </th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter SCHOOL CODE" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter CONTACT PERSON" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter MOBILE" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter ADDRESS" /> </th></tr> <tr> <th>STATE</th> <th>DISTRICT</th> <th>SCHOOL NAME</th> <th>SCHOOL CODE</th> <th>CONTACT PERSON</th> <th>MOBILE</th> <th>ADDRESS</th></tr> </thead> <tbody>';

			$.each(result, function() {
				
				data_table = data_table + '<tr>';
				data_table = data_table + '<td>'+this.st_name + '</td>';
				data_table = data_table + '<td>'+this.dt_name + '</td>';
				data_table = data_table + '<td>'+this.school_name + '</td>';
				data_table = data_table + '<td>'+this.school_code + '</td>';
				data_table = data_table + '<td>'+this.contact_person + '</td>';
				data_table = data_table + '<td>'+this.mobile + '</td>';
				data_table = data_table + '<td>'+this.address + '</td>';
				data_table = data_table + '</tr>';
					
			});

			data_table = data_table + '</tbody></table>';

			$("#stud_report").html(data_table);

			//=========================================data table functions=====================================
			
						/* BASIC ;*/
				var responsiveHelper_dt_basic = undefined;
				var responsiveHelper_datatable_fixed_column = undefined;
				var responsiveHelper_datatable_col_reorder = undefined;
				var responsiveHelper_datatable_tabletools = undefined;
				
				var breakpointDefinition = {
					tablet : 1024,
					phone : 480
				};
		
				$('#dt_basic').dataTable({
					"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
					"autoWidth" : true,
					"preDrawCallback" : function() {
						// Initialize the responsive datatables helper once.
						if (!responsiveHelper_dt_basic) {
							responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
						}
					},
					"rowCallback" : function(nRow) {
						responsiveHelper_dt_basic.createExpandIcon(nRow);
					},
					"drawCallback" : function(oSettings) {
						responsiveHelper_dt_basic.respond();
					}
				});
		
			/* END BASIC */
			var js_url = "<?php echo JS; ?>";
			/* COLUMN FILTER  */
		    var otable = $('#datatable_fixed_column').DataTable({
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
				 "oTableTools": {
		        	 "aButtons": [
		        	      {
		                 "sExtends": "xls",
		                 "sTitle": "TLSTEC Schools Report",
		                 "sPdfMessage": "TLSTEC Schools Excel Export",
		                 "sPdfSize": "letter"
			             },
			          	{
			             	"sExtends": "print",
			             	"sMessage": "TLSTEC Schools Printout <i>(press Esc to close)</i>"
			         	}],
		        	 "sSwfPath": js_url+"datatables/swf/copy_csv_xls_pdf.swf"
		        },
				"autoWidth" : true,
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_fixed_column) {
						responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_datatable_fixed_column.respond();
				}		
			
		    });
		    
		    // Apply the filter
		    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
		    	
		        otable
		            .column( $(this).parent().index()+':visible' )
		            .search( this.value )
		            .draw();
		            
		    } );
		    /* END COLUMN FILTER */   
			
			
			//=====================================================================================================
			
		}
		else
		{
			$("#stud_report").html('<h5>No schools to display for this district</h5>');
		}
	}
	

});

</script>