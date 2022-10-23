<?php $current_page = "Passedouts Students"; ?>
<?php $main_nav = "Reports"; ?>
<?php include("inc/header_bar.php"); ?>

<?php include("inc/sidebar.php"); ?>

<!-- MAIN PANEL -->
<section class="content">
    <div class="row clearfix">
    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    		<div class="card">
    			<div class="header">
    				<h3 class="font-bold col-green">Total students : <span class="badge bg-teal">
						<?php if(!empty($passedouts_studentscount)) {?><?php echo $passedouts_studentscount;?><?php } else {?><?php echo "0";?><?php }?></span>
					</h3>
					<ul class="header-dropdown m-r--5">
                      <div class="button-demo">
                          <button class="btn bg-blue waves-effect" id="get_excel"> Excel </button>
                          <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                      </div>
                    </ul>
    			</div>
				<div class="body">
					<div class="row clearfix">
						<div class="col-sm-4">
							<select id="collection_name" class="form-control">
								<option value="bcwelfare_screening_report_col_2021-2022_passed_out">Academic Year 2021-22</option>
								<option value="bcwelfare_screening_report_col_2020-2021_passed_out">Academic Year 2019-20</option>
								<!-- <option value="healthcare2016226112942701">Academic Year 2019-20</option> -->
							</select>
						</div>
                        <div class="col-sm-4">
                            <select id="select_dt_name" class="form-control">
                                <option value="All">All</option>
                                <?php if(isset($distslist)): ?>                                	
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
		        	<div id="stud_report">
						Select from drop down to display student report.
					</div>
		        </div>
            </div>
    	</div>
    </div>

</section>

<!-- Jquery Core Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery/jquery.min.js'); ?>"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo(MDB_PLUGINS.'bootstrap/js/bootstrap.js'); ?>"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery-slimscroll/jquery.slimscroll.js'); ?>"></script>

    <!-- Bootstrap Notify Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-notify/bootstrap-notify.js'); ?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'node-waves/waves.js'); ?>"></script>
 
  <!-- Jquery DataTable Plugin Js -->
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/jquery.dataTables.js"; ?>'></script>
    <script src="<?php echo MDB_PLUGINS."jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"; ?>"></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/dataTables.buttons.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.flash.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/jszip.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/pdfmake.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/vfs_fonts.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.html5.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.print.min.js"; ?>'></script>

    <script src="<?php echo(MDB_JS.'admin.js'); ?>"></script>
    <script src='<?php echo MDB_JS."pages/tables/jquery-datatable.js"; ?>'></script>
    <script src='<?php echo MDB_JS."pages/ui/modals.js"; ?>' ></script>

    <!-- Demo Js -->
    <script src="<?php echo(MDB_JS.'demo.js'); ?>"></script>
    <!-- Moment Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'momentjs/moment.js'); ?>"></script>
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script>
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script src="<?php echo MDB_PLUGINS.'bootstrap-notify/bootstrap-notify.js'; ?>"></script>

<script type="text/javascript">
	var today_date = $('#passing_end_date').val();
    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#passing_end_date').change(function(e){
            today_date = $('#passing_end_date').val();
    });

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
		collection_name = $('#collection_name').val();
		dist = $('#select_dt_name option:selected').text();
		//alert(school_name);
		$("#stud_report").html('<h5>Processing request, please wait.</h5>');
		if( school_name != "All" ){
			$.ajax({
				url: 'get_students_list',
				type: 'POST',
				data: {"school_name" : school_name, "dist_name" : dist, "collection":collection_name},
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

	$('#collection_name').change(function(){
		school_name = $('#school_name').val();
		collection_name = $('#collection_name').val();
		dist = $('#select_dt_name option:selected').text();
		//alert(school_name);
		$("#stud_report").html('<h5>Processing request, please wait.</h5>');
		if( school_name != "All" ){
			$.ajax({
				url: 'get_students_list',
				type: 'POST',
				data: {"school_name" : school_name, "dist_name" : dist, "collection":collection_name},
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
		}
	});

	function display_data_table(result){
		if(result.length > 0){
			data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic">  <thead> <tr> <th>STUDENT NAME</th><th>HOSPITAL UNIQUE ID</th><th>FATHER NAME</th> <th>STUDENT SCHOOL</th><th>DISTRICT</th><th>DATE OF BIRTH</th> <th>CLASS</th><th>SECTION</th><th>MOBILE</th><th>Action</th></tr> </thead> <tbody>';

			$.each(result, function() {
				//console.log(this.doc_data.widget_data["page2"]['Personal Information']['AD No']);
				data_table = data_table + '<tr>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Name'] + '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Hospital Unique ID'] + '</td>';				
				data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['Father Name'] + '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['School Name'] + '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['District'] + '</td>';	
				data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Date of Birth'] + '</td>';				
				data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['Class'] + '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['Section'] + '</td>';
				
				var mobile_numb = (typeof this.doc_data.widget_data["page1"]['Personal Information']['Mobile'] !== 'undefined' ? this.doc_data.widget_data["page1"]['Personal Information']['Mobile']['mob_num'] : "Not mention")
				data_table = data_table + '<td>'+ mobile_numb + '</td>';
				
				var urlLink = "https://mednote.in/PaaS/healthcare/index.php/";
				var obj = Object.values(this['_id']);
				data_table = data_table + '<td><a class="btn btn-primary btn-xs" href="'+urlLink+'panacea_mgmt/panacea_reports_display_ehr_uid/?id = '+this.doc_data.widget_data["page1"]['Personal Information']['Hospital Unique ID']+'">Show EHR</a></td>';

				data_table = data_table + '</tr>';
				
					
			});

			data_table = data_table + '</tbody></table><div><button type="button" class="btn btn-primary pull-right" onclick="window.history.back();">Back</button></div>';

			$("#stud_report").html(data_table);

			$('#dt_basic').DataTable({
                "paging": true,
                "lengthMenu" : [25, 50, 75, 100]
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
$('#date_set').click(function(){
get_data_of_otp();
});

$('#get_excel').click(function(){
        var dist = $('#select_dt_name').val();
        var scl = $('#school_name').val(); 
        $.ajax({
            
            url:'get_excel_for_students_reports',
            type:'POST',
            data:{'dist_name':dist, 'school':scl},
            success : function(data){               
                window.location = data;
            },
            error:function(XMLHttpRequest, textStatus, errorThrown)
            {
             console.log('error', errorThrown);
            }
        });
    });
</script>

<?php 
	//include footer
	include("inc/footer_bar.php"); 
?>
