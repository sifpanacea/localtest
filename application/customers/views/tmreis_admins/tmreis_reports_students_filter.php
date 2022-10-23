<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Students Report";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa reports"]["sub"]["student"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["tmreis Masters"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	
	<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Select a district | Total students : <span class="badge bg-color-greenLight">
							<?php if(!empty($studentscount)) {?><?php echo $studentscount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>

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
			<section class="col col-4">
				<label class="label" for="first_name">District Name</label>
				<label class="select">
				<select id="select_dt_name" >
					<option value="" selected="0" disabled="">Select a district</option>
					<?php if(isset($distslist)): ?>
						<option value='All' >All</option>
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
	<h2>Student Report</h2>

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
		Select from drop down to display student report.
		</div>
				<div>
					<button type="button" class="btn btn-primary pull-right hide" onclick="window.history.back();">Back</button>
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
				options.append($("<option />").val("All").text("All"));
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

	$('#school_name').change(function(e){
		school_name = $('#school_name').val();
		dist = $('#select_dt_name option:selected').text();
		//alert(school_name);
		$("#stud_report").html('<h5>Processing request, please wait.</h5>');
		if( school_name != "All" ){
			$.ajax({
				url: 'get_students_list',
				type: 'POST',
				data: {"school_name" : school_name, "dist_name" : dist},
				success: function (data) {			
	
					result = $.parseJSON(data);
					console.log(result);
					display_data_table(result);
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
						url: 'get_students_list',
						type: 'POST',
						data: {"school_name" : school_name, "dist_name" : dist},
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

	function display_data_table(result){
		if(result.length > 0){
			data_table = '<table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">  <thead> <tr> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter STUDENT NAME" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter UNIQUE ID" /></th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter FATHER NAME" /></th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter STUDENT SCHOOL" /> </th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter DISTRICT" /><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter DoB" /> </th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter CLASS" /> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter SECTION" /> </th> </th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter MOBILE" /> </th>   </tr> <tr> <th>STUDENT NAME</th><th>HOSPITAL UNIQUE ID</th><th>FATHER NAME</th> <th>STUDENT SCHOOL</th><th>DISTRICT</th><th>DATE OF BIRTH</th> <th>CLASS</th><th>SECTION</th><th>MOBILE</th>     </tr> </thead> <tbody>';

			$.each(result, function() {
				//console.log(this.doc_data.widget_data["page2"]['Personal Information']['AD No']);
				data_table = data_table + '<tr>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Name'] + '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Hospital Unique ID'] + '</td>';
				
				data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['Father Name'] + '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['School Name'] + '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['District'] + '</td>';
				
				
				
				data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']
				['Date of Birth'] + '</td>';
				
				data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['Class'] + '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['Section'] + '</td>';
				
				var mobile_numb = (typeof this.doc_data.widget_data["page1"]['Personal Information']['Mobile'] !== 'undefined' ? this.doc_data.widget_data["page1"]['Personal Information']['Mobile']['mob_num'] : "Not mention")
				data_table = data_table + '<td>'+ mobile_numb + '</td>';

				data_table = data_table + '</tr>';
					
			});

			data_table = data_table + '</tbody></table><div><button type="button" class="btn btn-primary pull-right" onclick="window.history.back();">Back</button></div>';


			
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
		    	//"bFilter": false,
		    	//"bInfo": false,
		    	//"bLengthChange": false
		    	//"bAutoWidth": false,
		    	//"bPaginate": false,
		    	//"bStateSave": true, // saves sort state using localStorage
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
		    
		    // custom toolbar
		    //$("div.toolbar").html('<div class="text-right"><img src="img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');
		    	   
		    // Apply the filter
		    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
		    	
		        otable
		            .column( $(this).parent().index()+':visible' )
		            .search( this.value )
		            .draw();
		            
		    } );
		    /* END COLUMN FILTER */   
			
			
			//=====================================================================================================
			}else{
				$("#stud_report").html('<h5>No students to display for this school</h5>');
			}

		
	}
});

</script>