<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Parents Submitted Doc's";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
//$page_nav["submitted_parents"]["active"] = true;
include("inc/nav.php");

?>

<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<link href="<?php echo(CSS.'site.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo(CSS.'jquery.dataTables.min.css'); ?>">
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
     	<!-- NEW WIDGET START -->
		<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
				
				<header>
					<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
					<h2>Parents Submitted Documents List</h2>
					<button class="btn btn-primary pull-right" id="btnExport" onclick="window.history.back();">Back</button>
				</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box -->

					<div class="well well-sm well-light">
						<form class="smart-form">          
							<div class="row">
								<section class="col col-3">
									<label class="label" for="">Start Date</label>
										<div class="form-group">
						                    <div class="input-group">
						                    	<?php $end_date  = date ( "Y-m-d", strtotime ( date('yy-m-d') . "-90 days" ) ); ?>

						                        <input type="text" id="passing_date" name="passing_date" placeholder="Select a date" class="form-control datepicker"  value="<?php echo $end_date; ?>"; ?>
						                        
						                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						                    </div>
						                </div>
								</section>
								<section class="col col-3">
									<label class="label" for="">End Date</label>
									<div class="form-group">
					                    <div class="input-group">
					                        <input type="text" id="passing_end_date" name="passing_end_date" placeholder="Select a date" class="form-control datepicker" value="<?php echo date('yy-m-d'); ?>">
					                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					                    </div>
					                </div>
								</section>
								<section class="col col-3">
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
								<section class="col col-3">
									<label class="label" for="first_name">School Name</label>
										<label class="select">
											<select id="school_name" disabled="true">
												<option value='All' >All</option>
											</select> <i></i>
										</label>
								</section>
							</div>		         
						</form>
					</div>
		
					<!-- widget content -->
					<div class="widget-body no-padding table-responsive">
			            <div id="stud_report"></div>
					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		</article>

</div><!-- ROW -->

				

</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
			
<!--=================== main content ends==================================== -->
<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>
<script src="<?php echo JS; ?>sweetalert.min.js"></script>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->

<script type="text/javascript" charset="utf8" src="<?php echo JS;?>jquery_new_version.dataTables.min.js"></script>


<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script type="text/javascript">

	/*setTimeout(function(){
	   window.location.reload(1);
	}, 10000);
	
*/
	get_data_for_submitted_docs();

	function get_data_for_submitted_docs(){
		var start_dates = $('#passing_date').val();
		var end_dates = $('#passing_end_date').val();
	  	var dt_name = $('#select_dt_name').val();
	  	var school_name = $('#school_name').val();

	  	$.ajax({
	  		url: 'get_parents_health_submitted_docs_data',
	  		type:'POST',
	  		data:{"start_date":start_dates, 'end_date':end_dates, 'district':dt_name, 'school':school_name},
	  		success:function(data){
	  			var result = $.parseJSON(data);
	  			console.log(result);
	  			table_show(result);
	  		}
	  	}); 
	}
	
  
  	$('#passing_end_date').change(function(e){
        today_date = $('#passing_end_date').val();

        console.log('php222222222222222', today_date);
    });

    $('#select_dt_name').change(function(e){
	dist = $('#select_dt_name').val();
	dt_name = $("#select_dt_name option:selected").text();
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
			//options.append($("<option />").val("select").prop("selected", true).text("All"));
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
});


    function table_show(result){
    	if(result.length > 0){
    		data_table = '<table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">  <thead> <tr> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter UNIQUE ID" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter STUDENT NAME" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter MOBILE" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter DoB" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter DISTRICT" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter STUDENT SCHOOL" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter CLASS" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter ACTION" /> </th> </tr> <tr> <th>STUDENT HEALTH ID</th> <th>STUDENT NAME</th> <th>MOBILE</th> <th>DATE OF BIRTH</th> <th>DISTRICT</th> <th>STUDENT SCHOOL</th> <th>CLASS</th><th>ACTION</th> </tr> </thead> <tbody>';

    		$.each(result, function() {
    			//console.log(this.doc_data.widget_data["page2"]['Personal Information']['AD No']);
    			data_table = data_table + '<tr>';
    			
    			data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Hospital Unique ID'] + '</td>';
    			data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Name'] + '</td>';
    			
    			var mobile_numb = (typeof this.doc_data.widget_data["page1"]['Personal Information']['Mobile'] !== 'undefined' ? this.doc_data.widget_data["page1"]['Personal Information']['Mobile']['mob_num'] : "Not mention")
    			data_table = data_table + '<td>'+ mobile_numb + '</td>';
    			data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Date of Birth'] + '</td>';
    			data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['District'] + '</td>';
    			data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['School Name'] + '</td>';
    			data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Class'] + '</td>';

    			var urlLink = "https://mednote.in/PaaS/healthcare/index.php/";
				
				data_table = data_table + '<td><a class="btn btn-primary btn-xs" href="'+urlLink+'panacea_doctor/parents_personal_details_form/?id = '+this.doc_data.widget_data["page1"]['Personal Information']['Hospital Unique ID']+'">Show Family</a></td>';
    			
    			data_table = data_table + '</tr>';
    				
    		});

    		data_table = data_table + '</tbody></table>';

    		$("#stud_report").html(data_table);
    	}else{
    		$("#stud_report").html('<h4>No Data Available</h4>');
    	}
    		
    };


</script>



									
									
									
								
							
						