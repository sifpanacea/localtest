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
$page_nav["reports"]["sub"]["student"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<style type="text/css">
	.modal-lg {
	    width: 1400px;
	}


</style>
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
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Select a Class | Total students : <span class="badge bg-color-greenLight">
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
			<section class="col col-6">
				<label class="label" for="first_name">Class</label>
				<label class="select">
				<select id="select_class" >
					<option value="" selected="0" disabled="">Select a class</option>
					<?php if(isset($classlist)): ?>
						<option value='All' >All</option>
						<?php foreach ($classlist as $class):?>
						<option  id="class_name"  value='<?php echo $class['class_name']?>'><?php echo ucfirst($class['class_name'])?></option>
						<?php endforeach;?>
						<?php else: ?>
						<option value="1"  disabled="">No class entered yet</option>
					<?php endif ?>
				</select> <i></i>
			</label>
			</section>
			<!--<section class="col col-4">
				<label class="label" for="first_name">Class</label>
				<label class="select">
				<select id="school_name" disabled=true>
					<option value="0" selected="" disabled="">Select a section first</option>
					
					
				</select> <i></i>
			</label>
			</section>-->
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

<!-- Modal -->
<div class="modal fade" id="idmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
       <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="body" id="printableArea">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="thumbnail">
                            <!-- <div class="label bg-purple font-16">MEDICAL EMERGENCY ID CARD</div> -->
                            <span class="badge bg-color-purple pull-right">MEDICAL EMERGENCY ID CARD</span>
                            <img src="http://placehold.it/500x100">
                            <!-- <img class="img-responsive thumbnail" src="../../images/image-gallery/thumb/thumb-18.jpg"> -->

                                
                                <span class="badge bg-color-red">Nandurbar Institute Of Medical Sciences</span>

                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6>Student Photo</h6>
                                        <div class="student_pic" style="border-style: solid;height: 150px;width: 150px;">
                                           
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                    	<div class="form-group row">
			                                <label for="" class="col-sm-2 col-form-label">Name: </label>
			                                <div class="col-sm-8">
			                                    <input type="text" class="form-control" id="names" readonly="readonly">
			                                </div>
			                            </div>
			                            <div class="form-group row">
			                                <label for="" class="col-sm-2 col-form-label">DOB: </label>
			                                <div class="col-sm-8">
			                                    <input type="text" class="form-control" id="dobs" readonly="readonly">
			                                </div>
			                            </div>
			                            <div class="form-group row">
			                                <label for="" class="col-sm-2 col-form-label">Gender: </label>
			                                <div class="col-sm-8">
			                                    <input type="text" class="form-control" id="GenderType" readonly="readonly">
			                                </div>
			                            </div>
			                            <div class="form-group row">
			                                <label for="" class="col-sm-2 col-form-label">Blood Group: </label>
			                                <div class="col-sm-8">
			                                    <input type="text" class="form-control" id="BloodGroup" readonly="readonly">
			                                </div>
			                            </div>
			                            <div class="form-group row">
			                                <label for="" class="col-sm-2 col-form-label">Contact Number:</label>
			                                <div class="col-sm-8">
			                                    <input type="text" class="form-control" id="MobileNo" readonly="readonly">
			                                </div>
			                            </div>
                                    </div>
                                   
                                    <div class="col-sm-3">
                                    	<h6>Student QR Code</h6>
                                   		<div class="QR_pic" id="images">
                                    	</div>
                                    </div> 
                                </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="thumbnail">
                        	<span class="badge bg-color-purple">COURTESY</span>
                        	<br>
                        	
                            <h4><strong>S.A.Mission School High School and Jr.College,(Marati Section) Nandurbar, Maharasthra - 425412.</strong></h4>

                            <br>
                            <h4><b>Instructions :</b></h4>
                           
                            <h5>1. This card is Issued by <strong> Community care and Public Health Divison</strong> of <strong>NIMS</strong>  for purpose of Managed Health Care.</h5>
                            <h5>2. This card is Not Trasferable and must be displayed during registration and billing.</h5>
                           
                            <h5>3. Community Care & Public Health Divison of NIMS is not responsible for misuse/mis-representation of the card.</h5>
                           
                            <h5>4. If Found Please Return To:</h5>
                           
                            <h5>Nandubar Institute Of Medical Sciences.</h5>
                           
                            <h6>65-67, Govardhan Hill,Opposite Telephone Exchange(BSNL),Nandurbar - 425412.</h6>

                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         <!-- <button type="button" class="btn btn-primary" id="prints">Print</button> --> 
         <input type="button" onclick="printDiv('printableArea')" value="Print Invoice" />
       
      </div>
    </div>
  </div>
</div>

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

<script src="http://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="http://code.jquery.com/jquery-plugins.js"></script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script type="text/javascript">
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
}
</script>

<script>
$(document).ready(function() {
var schoolname = <?php echo json_encode($schoolname); ?>;
console.log('jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj',schoolname);


/*$('#get_print').click(function(){
	alert();
     $("#printable").print();
});*/
/* 	$('#select_class').change(function(e){
		selectedClass = $('#select_class').val();
		var options = $("#school_name");
		options.prop("disabled", true);
		
	if( selectedClass != "All" ){
		options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching sections list..."));
		$.ajax({
			url: 'student_reports',
			type: 'POST',
			data: {"selected_class" : selectedClass},
			
			success: function(data) {			

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
						url: 'student_reports',
						type: 'POST',
						data: {"school_name" : dist},
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
	}); */

	

	$('#select_class').change(function(e){
		selected_class = $('#select_class').val();
		//selected_class = $('#select_class option:selected').text();
		console.log(selected_class);
		$("#stud_report").html('<h5>Processing request, please wait.</h5>');
		
		if( selected_class != "All" ){
			$.ajax({
				url: 'get_students_list_by_class',
				type: 'POST',
				data: {"selected_class" : selected_class},
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
		}else{
	
			$.SmartMessageBox({
				title : "Alert !",
				content : "This will take long time to display students report. Are you sure you want to continue.",
				buttons : '[No][Yes]'
			}, function(ButtonPressed) {
				if (ButtonPressed === "Yes") 
				{					
					$.ajax({
						url: 'get_students_list_by_class',
						type: 'POST',
						data: {"selected_class" : selected_class},
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
			data_table = '<table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">  <thead> <tr> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter STUDENT NAME" /> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter UNIQUE ID" /> </th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter Father Name" /> </th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter STUDENT SCHOOL" /></th>  <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter DoB" /> </th>   </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter CLASS" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter SECTION" /> </th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter MOBILE" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter Genrate" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter ID CARD" /> </th>  </tr> <tr>  <th>STUDENT NAME</th><th>HOSPITAL UNIQUE ID</th><th>FATHER NAME</th> <th>STUDENT SCHOOL</th> <th>DATE OF BIRTH</th>   <th>CLASS</th> <th>SECTION</th><th>MOBILE</th><th>Genrate</th><th>ID CARD</th> </tr> </thead> <tbody>';

			$.each(result, function() {
				//console.log(this.doc_data.widget_data["page2"]['Personal Information']['AD No']);
				data_table = data_table + '<tr>';
				var docId = this.doc_properties['doc_id'];
				data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Name'] + '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Hospital Unique ID'] + '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['Father Name'] + '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['School Name'] + '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Date of Birth'] + '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['Class'] + '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['Section'] + '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Mobile']['mob_num'] + '</td>';

				var bloodGroup = this.doc_data.widget_data['page3']['Physical Exam']['Blood Group'];
				var gender = this.doc_data.widget_data["page1"]['Personal Information']['Gender'];
				
				var QR = this.doc_data['qrcodeimage'];
				console.log(QR);
				if(typeof QR != 'undefined'){
					 var qrcodelink = QR.file_path;
				}

				data_table = data_table+'<td><button type="button" class="qrGenerator"  id="docsId" value='+docId+' >Genrate Code</td>';
				data_table = data_table+'<td><button type="button" class="idcard" blood='+bloodGroup+' gend = '+gender+'  id="imagesqr" qrimg = '+qrcodelink+' >Show ID</td>';
				data_table = data_table + '</tr>';
					
			});

			data_table = data_table + '</tbody></table>';

			$("#stud_report").html(data_table);

		    $("#datatable_fixed_column").each(function(){
		    	$('.qrGenerator').click(function(){
		    		var getcurrentRow = $(this).closest("tr");
		    		var name = getcurrentRow.find("td:eq(0)").text();
		    		var id = getcurrentRow.find("td:eq(1)").text();
		    		var scl = getcurrentRow.find("td:eq(3)").text();
		    		var docID = $('#docsId').val();
		    		
		    		$.ajax({
		    			url: 'get_qr_image_for_student',
		    			type: 'POST',
		    			data:{'stud_id':id, 'stud_name':name, 'stud_scl':scl, 'doc_ID':docID},
		    			success:function(data){
		    				console.log(data);
		    				window.location();
		    			}
		    		});
		    	});
		    });

		     $("#datatable_fixed_column").each(function(){
		    	$('.idcard').click(function(){
		    		var getcurrentRow = $(this).closest("tr");
		    		var name = getcurrentRow.find("td:eq(0)").text();
		    		/*var id = getcurrentRow.find("td:eq(1)").text();
		    		var scl = getcurrentRow.find("td:eq(3)").text();
		    		var cls = getcurrentRow.find("td:eq(4)").text();*/
		    		var dob = getcurrentRow.find("td:eq(5)").text();
		    		var mobi = getcurrentRow.find("td:eq(7)").text();
		    		//var docID = $('#docsId').val();
		    		var image = $("#imagesqr").attr('qrimg');
		    		var Blood = $("#imagesqr").attr('blood');
		    		var Gender = $("#imagesqr").attr('gend');
		    		//var Mobile = $("#imagesqr").attr('mob');
		    		var urlqr = 'https://mednote.in/PaaS/healthcare/'+image+'';
		    		var replacedUrl = urlqr.replace('./', '');
		    		
		    		$('#names').val(name);
		    		/*$('#ids').val(id);
		    		$('#scls').val(scl);
		    		$('#clss').val(cls);*/
		    		$('#dobs').val(dob);
		    		$('#BloodGroup').val(Blood);
		    		$('#GenderType').val(Gender);
		    		$('#MobileNo').val(mobi);
		    		$('#images').html('<img src='+replacedUrl+' style="height: 130px;">');

		    		$('#idmodal').modal('show');
		    		
		    	});
		    });

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
		                 "sTitle": schoolname+" Student Report",
		                 "sPdfMessage": schoolname+" Student Excel Export",
		                 "sPdfSize": "letter"
			             },
			          	{
			             	"sExtends": "print",
			             	"sMessage": schoolname+" Student Printout <i>(press Esc to close)</i>"
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