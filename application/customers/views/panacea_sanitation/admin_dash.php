<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Sanitation Dashboard";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa home"]["active"] = true;
include("inc/nav.php");

?>
<style>
#flot-tooltip { font-size: 12px; font-family: Verdana, Arial, sans-serif; position: absolute; display: none; border: 2px solid; padding: 2px; background-color: #FFF; opacity: 0.8; -moz-border-radius: 5px; -webkit-border-radius: 5px; -khtml-border-radius: 5px; border-radius: 5px; }

 .table {
    border-collapse: collapse;
    border: 2px solid orange;
  }     
 

</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->

<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">
	<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> <?php echo lang('admin_dash_home');?> <span>> <?php echo lang('admin_dash_board');?></span></h1>
			</div>
			
		</div>
		
		<!-- SANITATION REPORT PIE -->
			<!-- widget grid -->
		<section id="widget-grid" class="">
			<div class="row">
				<article class="col-sm-12">
					<!-- new widget -->
					<div class="jarviswidget" id="wid-id-100" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
						
						<header>
							<span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
							<h2>Sanitation Report Pie </h2>

						</header>

						<!-- widget div-->
						<div class="no-padding">
							<!-- widget edit box -->
							<!-- end widget edit box -->

							<div class="widget-body">
							<br>
							
							<div class="row">
								<br>
								<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									<div class="well well-sm well-light">
										<form class="smart-form" >
										<fieldset style="padding-top: 0px; padding-bottom: 0px;">
										<section class="col col-1" style="width:6%;!important">
										<div class="form-group">
												<div class="input-group">
												<label class="label" for="item1">Prev</label></div>
												<div class="input-group">
											<a href="javascript:void(0);" class="btn btn-primary btn-circle sanitation_report_prev"><i class="glyphicon glyphicon-backward"></i></a>
												</div>
										</div>
										</section>
										<section class="col col-2">
										<div class="form-group">
												<div class="input-group">
												<label class="label" for="item1">Select Date</label></div>
												<div class="input-group">
											<input type="text" id="sanitation_report_date" name="sanitation_report_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo $today_date?>">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												</div>
										</div>
										</section>
										<section class="col col-1" style="width:6%;!important">
										<div class="form-group">
												<div class="input-group">
												<label class="label" for="item1">Next</label></div>
												<div class="input-group">
												<a href="javascript:void(0);" class="btn btn-primary btn-circle sanitation_report_next"><i class="glyphicon glyphicon-forward"></i></a>
												</div>
										</div>
										</section>
													<!--<section class="col col-2">
														<label class="label" for="item1">Item 1</label>
														<label class="select">
														<select id="select_sanitation_report_section" >
															
														</select> <i></i>
														</label>
													</section>
													<section class="col col-3">
														<label class="label" for="item2">Item 2</label>
														<label class="select">
														<select id="select_sanitation_report_question" disabled=true>
															<option value='select_question' >Select from Item 1 first</option>
															
															
														</select> <i></i>
													</label>
													</section>
													<section class="col col-3">
														<label class="label" for="item3">Item 3</label>
														<label class="select">
														<select id="select_sanitation_report_answer" disabled=true>
															<option value='select_answer' >Select from Item 1 first</option>
														</select> <i></i>
													</label>
													</section>-->
													
													<section class="col col-4">
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
													<section class="col col-4">
														<label class="label" for="first_name">School Name</label>
														<label class="select">
														<select id="school_name" disabled=true>
															<option value='All' >Select School</option>
															
															
														</select> <i></i>
													</label>
													</section>
													
										</fieldset>
										</form>
									
									
									
									<br>
									
									
									<!--<center><i><label id="sanitation_report_note">&nbsp; Note : To get sanitation report, please select from three items</label></i></center>-->
									<!--<div id="pie_sanitation_dist" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									
									</div>
									<div id="pie_sanitation_school_list" class="col-xs-6 col-sm-6 col-md-6 col-lg-6" >
									
									</div>
									</div>-->
									</div>
								
									
									
									<div id="sanitation_report_table" style="min-height:300px;max-height:300px;">
									<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									
									
									</div>
									
									</div>
								
								
							</div>
								<div class="row">
								<div class="col-xs-12 col-lg-3 pull-right">
								<div class="well well-sm well-light">
								<label>Status of <label class="sanitation_report_status_date" for="date"></label></label>
								<label class="form-control"> <a href="javascript:void(0)" class="sanitation_report_submitted_schools_list">Submitted Schools &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</a> <span class="sanitation_report_submitted_schools"></span></label>
								<label class="form-control"> <a href="javascript:void(0)" class="sanitation_report_not_submitted_schools_list"> Not Submitted Schools : </a><span class="sanitation_report_not_submitted_schools"></span></label>
								</div>
								</div>
								</div>
			<!-- SANITATION REPORT SUBMITTED SCHOOLS LIST -->
			<div class="modal fade-in" id="sani_repo_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							×
						</button>
						<h4 class="modal-title" id="myModalLabel">Sanitation Report Submitted Schools </h4>
					</div>
					<div id="sani_repo_sent_school_modal_body" class="modal-body">
		            
					
					</div>
					<div class="modal-footer">
					   <button type="button" class="btn btn-primary" id="sani_repo_sent_school_download">
							Download
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
		
		<!-- SANITATION REPORT NOT SUBMITTED SCHOOLS LIST -->
		<div class="modal" id="sani_repo_not_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							×
						</button>
						<h4 class="modal-title" id="myModalLabel">Sanitation Report Not Submitted Schools  </h4>
					</div>
					<div id="sani_repo_not_sent_school_modal_body" class="modal-body">
					
					</div>
					<div class="modal-footer">
					   <button type="button" class="btn btn-primary" id="sani_repo_not_sent_school_download">
							Download
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
		
		<!-- SANITATION REPORT ATTACHMENTS -->
		<div class="modal" id="sanitation_report_attachments_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							×
						</button>
						<h4 class="modal-title" id="myModalLabel">Sanitation Report Attachments</h4>
					</div>
					<div id="sanitation_report_attachments_modal_body" class="modal-body">
					
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
		
		</div>
						<!-- end widget div -->
					<!--</div>
					 end widget -->
                 </div>
				</article>
				
			<!-- row -->

			<!-- end row  -->
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
				
				
			<!-- </div>end row -->
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

<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.barnumbers.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.orderBar.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.axislabels.js"></script>
<script src="<?php echo JS; ?>plugin/morris/raphael.min.js"></script>
<script src="<?php echo JS; ?>plugin/morris/morris.min.js"></script>
<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
$(document).ready(function() {
	
	var today_date = $('#sanitation_report_date').val();
	
	var dt_name = $('#select_dt_name').val();
	
	
	// SANIATION REPORT
	var sani_repo_sent_schools     = <?php echo $sanitation_report_schools_list['submitted_count'];?>;
	
	var sani_repo_not_sent_schools = <?php echo $sanitation_report_schools_list['not_submitted_count'];?>;
	var sani_repo_submitted_schools_list     = "";
	var sani_repo_not_submitted_schools_list = "";
	sanitation_report_obj                 = <?php echo $sanitation_report_obj;?>;
	
	sani_repo_submitted_schools_list         = <?php echo json_encode($sanitation_report_schools_list['submitted']);?>;
	sani_repo_not_submitted_schools_list     = <?php echo json_encode($sanitation_report_schools_list['not_submitted']);?>;

    // DRAW SANITATION REPORT PIE WHEN DATE CHANGED
    $('#sanitation_report_date').change(function(e){
	   
	   var date        = $('#sanitation_report_date').val();
	   var dt_name     = $('#select_dt_name').val();
	   var school_name = $("#school_name option:selected").text();

	   if(dt_name!="All" && school_name!="Select a school")
	   {

			$.ajax({
			url : 'fetch_sanitation_report_against_date',
			type: 'POST',
			data: {"selected_date" : date,"selected_school":school_name},
			success: function (data) {			
				
				var obj = JSON.parse(data);
				sanitation_report_input = obj.report_data;
				var schools_list_obj    = obj.schools_list;
				sani_repo_submitted_schools_list     = obj.schools_list.submitted;
				sani_repo_not_submitted_schools_list = obj.schools_list.not_submitted;
				draw_sanitation_report_table();
				$('.sanitation_report_status_date').html('');
				$('.sanitation_report_status_date').html(date);
			    $('.sanitation_report_submitted_schools').html(obj.schools_list.submitted_count);
				$('.sanitation_report_not_submitted_schools').html(obj.schools_list.not_submitted_count);
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
		    });
	    }
	    else
	    {
	    	$.ajax({
			url    : 'fetch_sanitation_report_against_date',
			type   : 'POST',
			data   : {"selected_date" : date},
			success: function (data) {
				
				$('#load_waiting').modal('hide');
				if(data == 'NO_DATA_AVAILABLE')
				{
			      $('#sanitation_report_table').html('<center>No sanitation report data available !</center>');
				}
				else
				{
					var obj = JSON.parse(data);
					var schools_list_obj    = obj.schools_list;
					sani_repo_submitted_schools_list     = obj.schools_list.submitted;
					sani_repo_not_submitted_schools_list = obj.schools_list.not_submitted;
					$('.sanitation_report_status_date').html('');
					$('.sanitation_report_status_date').html(date);
				    $('.sanitation_report_submitted_schools').html(obj.schools_list.submitted_count);
					$('.sanitation_report_not_submitted_schools').html(obj.schools_list.not_submitted_count);
				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});
	    }
	   
	})
	
    $('.sanitation_report_status_date').html('');
	$('.sanitation_report_status_date').html(today_date);
    $('.sanitation_report_submitted_schools').html(sani_repo_sent_schools);
	$('.sanitation_report_not_submitted_schools').html(sani_repo_not_sent_schools);

   // View Sanitation Report Attachments
   $(document).on('click','.view_sanitation_images',function(e)
   {
	 var path = $(this).attr('path');
	 var paths = path.split(',');
	 $('#sanitation_report_attachments_modal_body').empty();
	 var gallery = "";
	 var img     = "";
	 gallery="<div class='row'><div class='superbox col-sm-12'><div class='superbox-list'><div class=''>";
	 for(var i=0;i<paths.length;i++)
	 {
        var j=i+1;
        img+="<a href='<?php echo URLCustomer;?>"+paths[i]+"' rel='prettyPhoto[gal]'><img src='<?php echo IMG;?>galleryicon.png' alt='Image'/> Image "+j+"</a><br>";
	 }
	 gallery+=img;
	 gallery+="</div></div></div></div>";
	 $(gallery).appendTo('#sanitation_report_attachments_modal_body');
	 $('#sanitation_report_attachments_modal').modal('show');
	 $("a[rel^='prettyPhoto']").prettyPhoto();
   }) 

	// Sanitation report sent schools list download
	$('#sani_repo_sent_school_download').click(function(){
	
	    var date  = $('#sanitation_report_date').val();
		if(sani_repo_submitted_schools_list!=null)
		{
		   $.ajax({
			url : 'download_sanitation_report_sent_schools_list',
			type: 'POST',
			data: {"data" : sani_repo_submitted_schools_list,"today_date" : date},
			success: function (data) {
				window.location = data;
				$("#sani_repo_sent_school_modal").modal('hide');
				},
				error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
				}
			});
		}
		else
		{

		}
	}) 

    // Sanitation report sent schools list
   $('.sanitation_report_submitted_schools_list').click(function(){
		if(sani_repo_submitted_schools_list!=null)
		{
	        var table="";
			var tr="";
			
			if(sani_repo_submitted_schools_list['school']!="")
			{
				$('#sani_repo_sent_school_modal_body').empty();
				table += "<table class='table table-bordered'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Contact Person </th><th> Mobile </th></tr></thead><tbody>";
				for(var i=0;i<sani_repo_submitted_schools_list['school'].length;i++)
				{
					var j=i+1;
					table+= "<tr><td>"+j+"</td><td>"+sani_repo_submitted_schools_list['district'][i]+"</td><td>"+sani_repo_submitted_schools_list['school'][i]+"</td><td>"+sani_repo_submitted_schools_list['person_name'][i]+"</td><td>"+sani_repo_submitted_schools_list['mobile'][i]+"</td></tr>"
				}
				table += "</tbody></table>";
				$(table).appendTo('#sani_repo_sent_school_modal_body');
				$('#sani_repo_sent_school_download').prop('disabled',false);
			}
			else
			{
				$('#sani_repo_sent_school_modal_body').empty();
				table+="<table class='table table-bordered'><tbody><tr><td>No Schools</td></tr></tbody></table>";
				$(table).appendTo('#sani_repo_sent_school_modal_body');
				$('#sani_repo_sent_school_download').prop('disabled',true);
			}
		}
		else
		{
			table+="No Schools";
			$(table).appendTo('#sani_repo_sent_school_modal_body');
		}
		$('#sani_repo_sent_school_modal').modal('show');
    })
    
	// Sanitation report not sent schools list download
	$('#sani_repo_not_sent_school_download').click(function(){
	
	    /*var date  = $('#sanitation_report_date').val();
		if(sani_repo_not_submitted_schools_list!=null)
		{
		   $.ajax({
			url : 'download_sanitation_report_not_sent_schools_list',
			type: 'POST',
			data: {"data" : sani_repo_not_submitted_schools_list,"today_date" : date},
			success: function (data) {
				window.location = data;
				$("#sani_repo_not_sent_school_modal").modal('hide');
				},
				error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
				}
			});
		}
		else
		{

		}*/

	  var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
      var textRange; var j=0;
      tab = document.getElementById('sani_repo_not_submitted_schools_list_tab'); // id of table

      for(j = 0 ; j < tab.rows.length ; j++)
      {    
            tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
      }

      tab_text=tab_text+"</table>";


      var ua = window.navigator.userAgent;
      var msie = ua.indexOf("MSIE ");

      if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
      {
         txtArea1.document.open("txt/html","replace");
         txtArea1.document.write(tab_text);
         txtArea1.document.close();
         txtArea1.focus();
         sa=txtArea1.document.execCommand("SaveAs",true,"Global View Task.xls");
      } 
      else //other browser not tested on IE 11
         sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text)); 
        return (sa);
	}) 
	
	// Sanitation report not sent schools list
    $('.sanitation_report_not_submitted_schools_list').click(function(){
		if(sani_repo_not_submitted_schools_list!=null)
		{
	        var table= "";
			var tr   = "";
			
			if(sani_repo_not_submitted_schools_list['school']!="")
			{
				$('#sani_repo_not_sent_school_modal_body').empty();
				table += "<table class='table table-bordered' id='sani_repo_not_submitted_schools_list_tab'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Contact Person </th><th> Mobile </th></tr></thead><tbody>";
				for(var i=0;i<sani_repo_not_submitted_schools_list['school'].length;i++)
				{
					var j=i+1;
					table+= "<tr><td>"+j+"</td><td>"+sani_repo_not_submitted_schools_list['district'][i]+"</td><td>"+sani_repo_not_submitted_schools_list['school'][i]+"</td><td>"+sani_repo_not_submitted_schools_list['person_name'][i]+"</td><td>"+sani_repo_not_submitted_schools_list['mobile'][i]+"</td></tr>"
				}
				table += "</tbody></table>";
				$(table).appendTo('#sani_repo_not_sent_school_modal_body');
				$('#sani_repo_not_sent_school_download').prop('disabled',false);
			}
			else
			{
				$('#sani_repo_not_sent_school_modal_body').empty();
				table+="<table class='table table-bordered'><tbody><tr><td>No Schools</td></tr></tbody></table>";
				$(table).appendTo('#sani_repo_not_sent_school_modal_body');
				$('#sani_repo_not_sent_school_download').prop('disabled',true);
			}
		}
		else
		{
			table+="No Schools";
			$(table).appendTo('#sani_repo_sent_school_modal_body');
		}
		$('#sani_repo_not_sent_school_modal').modal('show');
    }) 
	
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

$('#school_name').change(function(e){
	var date = $('#sanitation_report_date').val();
	var school_name = $("#school_name option:selected").text();

	
	$.ajax({
		url : 'fetch_sanitation_report_against_date',
		type: 'POST',
		data: {"selected_date" : date,"selected_school":school_name},
		success: function (data) {			
			
			var obj = JSON.parse(data);
			sanitation_report_input = obj.report_data;
			var schools_list_obj    = obj.schools_list;
			sani_repo_submitted_schools_list     = obj.schools_list.submitted;
			sani_repo_not_submitted_schools_list = obj.schools_list.not_submitted;
			draw_sanitation_report_table();
			$('.sanitation_report_status_date').html('');
			$('.sanitation_report_status_date').html(date);
		    $('.sanitation_report_submitted_schools').html(obj.schools_list.submitted_count);
			$('.sanitation_report_not_submitted_schools').html(obj.schools_list.not_submitted_count);
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
});

/*******************************************************************
 *
 * Helper : Sanitation Report 
 *
 *
 */
 
function draw_sanitation_report_table()
{
	   $('#sanitation_report_table').html('');
	   if(sanitation_report_input !== 'null')	
	   {			   
		  result = $.parseJSON(sanitation_report_input);
		  
		  $('#sanitation_report_table').html('<div class="row"><div id="campus" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="toilets" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="kitchen" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div></div><br><br><br><div class="row"><div id="external_files" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div> <div id="toilet_external_files" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="kitchen_external_files" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div></div>');


		  /*$('#sanitation_report_table').html('<div id="campus" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="toilets" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="kitchen" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="water_supply" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="dormitories" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="store" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="waste_management" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="external_files" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div>');*/
		 
		  var campus              	= result.campus;
		  var toilets             	= result.toilets;
		  var kitchen               = result.kitchen;
		  var water_supply 			= result.water_supply;
		  var dormitories  			= result.dormitories;
		  var store 				= result.store;
		  var waste_management  	= result.waste_management;
		  var external_files     	= result.external_attachments;
		  var toilet_external_files     	= result.toilet_external_attachments;
		  var kitchen_external_files     	= result.kitchen_external_attachments;
		  
		  console.log("external_files==>",external_files);
		  
	
		// hand wash
		campus = $.parseJSON(campus);
			if ($("#campus").length) {
				var table = '<div style="overflow-y: auto; height:210px; width:105%;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in campus) {
				  if (campus.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+campus[item].label+'</td><td>'+campus[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#campus").html(table)
			}
			
			$('#campus').prepend('<div class=""><center>CAMPUS</center></div>');
			
			// toilets
			toilets = $.parseJSON(toilets);
			if ($("#toilets").length) {
				
				var table = '<div style="overflow-y: auto; height:400px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in toilets) {
				  if (toilets.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+toilets[item].label+'</td><td>'+toilets[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#toilets").html(table)
			}
			
			$('#toilets').prepend('<div class="spec"><center>TOILETS</center></div>');
			
			// kitchen
			kitchen = $.parseJSON(kitchen);
			if ($("#kitchen").length) {
				
				var table = '<div style="overflow-y: auto; height:210px; width:200%;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in kitchen) {
				  if (kitchen.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+kitchen[item].label+'</td><td>'+kitchen[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#kitchen").html(table)
			}
			
			$('#kitchen').prepend('<div class="spec"><center>KITCHEN</center></div>');
			
			// water_supply
			
			water_supply = $.parseJSON(water_supply);
			if ($("#water_supply").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in water_supply) {
				  if (water_supply.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+water_supply[item].label+'</td><td>'+water_supply[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#water_supply").html(table)
			}
			
			$('#water_supply').prepend('<div class="spec">WATER SUPPLY</div>');
			
			// dormitories
			
			dormitories = $.parseJSON(dormitories);
			if ($("#dormitories").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in dormitories) {
				  if (dormitories.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+dormitories[item].label+'</td><td>'+dormitories[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#dormitories").html(table)
			}
				
		$('#dormitories').prepend('<div class="spec">DORMITORIES</div>');
     
        //store
        
		store = $.parseJSON(store);
			if ($("#store").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in store) {
				  if (store.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+store[item].label+'</td><td>'+store[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#store").html(table)
			}
			
			$('#store').prepend('<div class="">STORE</div>');

			//waste_management
		waste_management = $.parseJSON(waste_management);
			if ($("#waste_management").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in waste_management) {
				  if (waste_management.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+waste_management[item].label+'</td><td>'+waste_management[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#waste_management").html(table)
			}
			
			$('#waste_management').prepend('<div class="">WASTE MANAGEMENT</div>');

		
		//campus external files
		external_files = $.parseJSON(external_files);
		console.log(external_files);	
		var table = '<div style="overflow-y: auto; height:300px;" ><table class=" table table-bordered"><thead><tr><th>Campus Attachments <span class="campus_attach_count badge bg-color-blueDark txt-color-white"></span></th></tr></thead><tbody>'
		var length_campus = Object.keys(external_files).length;
		if(length_campus > 0)
		{
		for(var item in external_files)
		{
		
	      table = table + '<tr><td><a href="<?php echo URLCustomer;?>'+external_files[item].file_path+'" rel="prettyPhoto[gal]"><img src="<?php echo URLCustomer;?>'+external_files[item].file_path+'" height="125" width="100"></a></td></tr>'
		  
		}
		}
		else
		{
	      table = table + '<tr><td>No attachments </td></tr>'
		}

		//Toilet external files
		external_files = $.parseJSON(toilet_external_files);
		console.log(external_files);	
		var toilet_table = '<div style="overflow-y: auto; height:300px;" ><table class=" table table-bordered"><thead><tr><th>Toilet Attachments <span class="toilet_attach_count badge bg-color-blueDark txt-color-white"></span></th></tr></thead><tbody>'
		var length_toilets = Object.keys(external_files).length;
		if(length_toilets > 0)
		{
		for(var item in external_files)
		{
		
	      toilet_table = toilet_table + '<tr><td><a href="<?php echo URLCustomer;?>'+external_files[item].file_path+'" rel="prettyPhoto[gal]"> <img src="<?php echo URLCustomer;?>'+external_files[item].file_path+'" height="125" width="100"></a></td></tr>'
		  
		}
		}
		else
		{
	      toilet_table = toilet_table + '<tr><td>No attachments </td></tr>'
		}		
		
		//Kitchen external files
		
		external_files = $.parseJSON(kitchen_external_files);
		console.log(external_files);	
		var kitchen_table = '<div style="overflow-y: auto; height:300px;" ><table class=" table table-bordered"><thead><tr><th>Kitchen Attachments <span class="kitchen_attach_count badge bg-color-blueDark txt-color-white"></span></th></tr></thead><tbody>'
		var length_kitchen = Object.keys(external_files).length;
		if(length_kitchen > 0)
		{
		for(var item in external_files)
		{
		
	      kitchen_table = kitchen_table + '<tr><td><a href="<?php echo URLCustomer;?>'+external_files[item].file_path+'" rel="prettyPhoto[gal]"><img src="<?php echo URLCustomer;?>'+external_files[item].file_path+'" height="125" width="100"></a></td></tr>'
		  
		}
		}
		else
		{
	      kitchen_table = kitchen_table + '<tr><td>No attachments </td></tr>'
		}
		toilet_table = toilet_table + '</tbody></table></div>';
		
		$("#external_files").html(table)
		$("#toilet_external_files").html(toilet_table)
		$("#kitchen_external_files").html(kitchen_table)
		$('.campus_attach_count').text(length_campus)
		$('.toilet_attach_count').text(length_toilets)
		$('.kitchen_attach_count').text(length_kitchen);
		
		$("a[rel^='prettyPhoto']").prettyPhoto();

		// status update		
	
	   }
	   else
	   {
		   $('#sanitation_report_table').html('<br><center><label id="sanitation_report_note">Today sanitation report not submitted in this school......</label></center>');
	   }		
	
}

// PREV
$(document).on('click','.sanitation_report_prev',function(e){
	

	var current_date = $('#sanitation_report_date').val(); 
	
	var current_date_unformatted = new Date(current_date);
		
	var cur_date   = current_date_unformatted.getDate();
	var cur_month  = current_date_unformatted.getMonth() + 1; //Months are zero based
	var cur_year   = current_date_unformatted.getFullYear();
	var cur_date_formatted = cur_year + "-" + cur_month + "-" + cur_date;
	
		
	if(current_date_unformatted.getDate()-1 <= 0)
	{
	  // Last day of the month
	  var lastDayOfMonth_unformatted = new Date(current_date_unformatted.getFullYear(), current_date_unformatted.getMonth(), 0);
	  var prev_date_unformatted = new Date();
	  var date_to_be_set = lastDayOfMonth_unformatted.getDate();
	  prev_date_unformatted.setMonth(lastDayOfMonth_unformatted.getMonth(),date_to_be_set);
	  prev_date_unformatted.setFullYear(lastDayOfMonth_unformatted.getFullYear());
	  
	}
	else
	{
	  var prev_date_unformatted = new Date();
	  prev_date_unformatted.setMonth(current_date_unformatted.getMonth(),current_date_unformatted.getDate()-1);
	  prev_date_unformatted.setFullYear(current_date_unformatted.getFullYear());
	}
	console.log("prev_date_unformatted==2=",prev_date_unformatted);
	   
	var prev_date  = prev_date_unformatted.getDate();
	if(prev_date <= 9)
	{
	  prev_date   = "0"+prev_date;
	}
	var prev_month = prev_date_unformatted.getMonth() + 1; //Months are zero based
	var prev_year  = prev_date_unformatted.getFullYear();
	if(prev_month <= 9)
	{
	  var prev_date_formatted = prev_year + "-0" + prev_month + "-" + prev_date;
	}
	else
	{
	  var prev_date_formatted = prev_year + "-" + prev_month + "-" + prev_date;
	}
		
		// set date
	$('#sanitation_report_date').val(prev_date_formatted);
	
	var school_name = $("#school_name option:selected").text();
	var dt_name     = $('#select_dt_name').val();
	if(dt_name!="All" && school_name!="Select a school")
	{
		
		$.ajax({
			url    : 'fetch_sanitation_report_against_date',
			type   : 'POST',
			data   : {"selected_date" : prev_date_formatted,"selected_school":school_name},
			success: function (data) {
				
				$('#load_waiting').modal('hide');
				if(data == 'NO_DATA_AVAILABLE')
				{
			      $('#sanitation_report_table').html('<center>No sanitation report data available !</center>');
				}
				else
				{
					var obj = JSON.parse(data);
					sanitation_report_input = obj.report_data;
					var schools_list_obj    = obj.schools_list;
					sani_repo_submitted_schools_list     = obj.schools_list.submitted;
					sani_repo_not_submitted_schools_list = obj.schools_list.not_submitted;
					draw_sanitation_report_table();
					$('.sanitation_report_status_date').html('');
					$('.sanitation_report_status_date').html(prev_date_formatted);
				    $('.sanitation_report_submitted_schools').html(obj.schools_list.submitted_count);
					$('.sanitation_report_not_submitted_schools').html(obj.schools_list.not_submitted_count);
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
    	$.ajax({
			url    : 'fetch_sanitation_report_against_date',
			type   : 'POST',
			data   : {"selected_date" : prev_date_formatted},
			success: function (data) {
				
				$('#load_waiting').modal('hide');
				if(data == 'NO_DATA_AVAILABLE')
				{
			      $('#sanitation_report_table').html('<center>No sanitation report data available !</center>');
				}
				else
				{
					var obj = JSON.parse(data);
					var schools_list_obj    = obj.schools_list;
					sani_repo_submitted_schools_list     = obj.schools_list.submitted;
					sani_repo_not_submitted_schools_list = obj.schools_list.not_submitted;
					$('.sanitation_report_status_date').html('');
					$('.sanitation_report_status_date').html(prev_date_formatted);
				    $('.sanitation_report_submitted_schools').html(obj.schools_list.submitted_count);
					$('.sanitation_report_not_submitted_schools').html(obj.schools_list.not_submitted_count);
				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});
    }
})

// NEXT
$(document).on('click','.sanitation_report_next',function(e){
	
	
	var current_date = $('#sanitation_report_date').val(); 
	
	var current_date_unformatted = new Date(current_date);
		
	var cur_date   = current_date_unformatted.getDate();
	var cur_month  = current_date_unformatted.getMonth() + 1; //Months are zero based
	var cur_year   = current_date_unformatted.getFullYear();
	var cur_date_formatted = cur_year + "-" + cur_month + "-" + cur_date;
	
		
	// Last day of the current month
	var lastDayOfCurrentMonth_unformatted = new Date(current_date_unformatted.getFullYear(), current_date_unformatted.getMonth()+1, 0);
	
	if(current_date_unformatted.getDate() >= lastDayOfCurrentMonth_unformatted.getDate())
	{
	  // First day of the month
	  var firstDayOfMonth_unformatted = new Date(current_date_unformatted.getFullYear(), current_date_unformatted.getMonth()+1, 1);
	  var next_date_unformatted = new Date();
	  var date_to_be_set = firstDayOfMonth_unformatted.getDate();
	  next_date_unformatted.setMonth(firstDayOfMonth_unformatted.getMonth(),date_to_be_set);
	  next_date_unformatted.setFullYear(firstDayOfMonth_unformatted.getFullYear());
	  
	}
	else
	{
	  var next_date_unformatted = new Date();
	  next_date_unformatted.setMonth(current_date_unformatted.getMonth(),current_date_unformatted.getDate()+1);
	  next_date_unformatted.setFullYear(current_date_unformatted.getFullYear());
	}
	
	var next_date   = next_date_unformatted.getDate();
	if(next_date <= 9)
	{
	 next_date   = "0"+next_date;
	}
	var next_month  = next_date_unformatted.getMonth() + 1; //Months are zero based
	var next_year   = next_date_unformatted.getFullYear();
	if(next_month <= 9)
	{
	 next_date_formatted = next_year + "-0" + next_month + "-" + next_date;
	}
	else
	{
	 next_date_formatted = next_year + "-" + next_month + "-" + next_date;
	}
		
	
	var school_name = $("#school_name option:selected").text();
    var dt_name     = $('#select_dt_name').val();
	if(dt_name!="All" && school_name!="Select a school")
	{
		// set date
	    $('#sanitation_report_date').val(next_date_formatted);
	
		$.ajax({
			url    : 'fetch_sanitation_report_against_date',
			type   : 'POST',
			data   : {"selected_date" : next_date_formatted,"selected_school":school_name},
			success: function (data) {
				
				$('#load_waiting').modal('hide');
				if(data == 'NO_DATA_AVAILABLE')
				{
			      $('#sanitation_report_table').html('<center>No sanitation report data available !</center>');
				}
				else
				{
					var obj = JSON.parse(data);
					sanitation_report_input = obj.report_data;
					var schools_list_obj    = obj.schools_list;
					sani_repo_submitted_schools_list     = obj.schools_list.submitted;
					sani_repo_not_submitted_schools_list = obj.schools_list.not_submitted;
					draw_sanitation_report_table();
					$('.sanitation_report_status_date').html('');
					$('.sanitation_report_status_date').html(next_date_formatted);
				    $('.sanitation_report_submitted_schools').html(obj.schools_list.submitted_count);
					$('.sanitation_report_not_submitted_schools').html(obj.schools_list.not_submitted_count);

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
    	$('#sanitation_report_date').val(next_date_formatted);
	
		$.ajax({
			url    : 'fetch_sanitation_report_against_date',
			type   : 'POST',
			data   : {"selected_date" : next_date_formatted},
			success: function (data) {
				
				$('#load_waiting').modal('hide');
				if(data == 'NO_DATA_AVAILABLE')
				{
			      $('#sanitation_report_table').html('<center>No sanitation report data available !</center>');
				}
				else
				{
					var obj = JSON.parse(data);
					var schools_list_obj    = obj.schools_list;
					sani_repo_submitted_schools_list     = obj.schools_list.submitted;
					sani_repo_not_submitted_schools_list = obj.schools_list.not_submitted;
					$('.sanitation_report_status_date').html('');
					$('.sanitation_report_status_date').html(next_date_formatted);
				    $('.sanitation_report_submitted_schools').html(obj.schools_list.submitted_count);
					$('.sanitation_report_not_submitted_schools').html(obj.schools_list.not_submitted_count);

				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});
    }
	
})


});	

</script>

