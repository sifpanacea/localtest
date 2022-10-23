<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Sanitation Reports";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["sanitation_report"]["active"] = true;
include("inc/nav.php");

?>
<style>
#flot-tooltip { font-size: 12px; font-family: Verdana, Arial, sans-serif; position: absolute; display: none; border: 2px solid; padding: 2px; background-color: #FFF; opacity: 0.8; -moz-border-radius: 5px; -webkit-border-radius: 5px; -khtml-border-radius: 5px; border-radius: 5px; }
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->

<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "https://url.com"
		include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">
	
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
													<section class="col col-2">
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
													</section>
													
										</fieldset>
										</form>
									</div>
									<div>
									<br>
									<div id="sanitation_report_pie_diagram" style="min-height:300px;max-height:300px;">
									<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									<center><i><label id="sanitation_report_note">&nbsp; Note : To get sanitation report pie, please select from three items</label></i></center>
									<div id="pie_sanitation_dist" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
									
									</div>
									<div id="pie_sanitation_school_list" class="col-xs-6 col-sm-6 col-md-6 col-lg-6" >
									
									</div>
									</div>
									
									</div>
								 <div id="legend-container" style="padding-left:10px;padding-top:10px;"></div>
									</div>
								</div>
								
							</div>
								<div class="row">
								<div class="col-xs-12 col-lg-3 pull-right">
								<div class="well well-sm well-light">
								<label>Status of <label class="sanitation_report_status_date" for="date"><?php echo $today_date; ?></label></label>
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
					
					<div class="well well-sm well-light">
					<button type="button" class="btn btn-primary" id="sani_repo_sent_school_download">
							Download
						</button>
						</div>
					
					<div id="sani_repo_sent_school_modal_body" class="modal-body">
		            
					
					</div>
					<div class="modal-footer">
					  <!-- <button type="button" class="btn btn-primary" id="sani_repo_sent_school_download">
							Download
						</button> -->
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
					
					<div class="well well-sm well-light">
					<button type="button" class="btn btn-primary" id="sani_repo_not_sent_school_download">
							Download
						</button>
						</div>
					
					<div id="sani_repo_not_sent_school_modal_body" class="modal-body">
					
					</div>
					<div class="modal-footer">
					   <!-- <button type="button" class="btn btn-primary" id="sani_repo_not_sent_school_download">
							Download
						</button> -->
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
		
		<!-- SANITATION REPORT ATTACHMENTS -->
		<div class="modal" id="sanitation_report_attachments_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
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

	var today_date = $('#set_data').val();

	var dt_name = $('#select_dt_name').val();
	var school_name = $('#school_name').val();
	

	var sanitation_report_obj = <?php echo $sanitation_report_obj;?>;
	// SANIATION REPORT
	var sani_repo_sent_schools     = <?php echo $sanitation_report_schools_list['submitted_count'];?>;
	var sani_repo_not_sent_schools = <?php echo $sanitation_report_schools_list['not_submitted_count'];?>;
	var sani_repo_submitted_schools_list     = "";
	var sani_repo_not_submitted_schools_list = "";

	
	sani_repo_submitted_schools_list         = <?php echo json_encode($sanitation_report_schools_list['submitted']);?>;
	sani_repo_not_submitted_schools_list     = <?php echo json_encode($sanitation_report_schools_list['not_submitted']);?>;
	
	var sanitation_report_sec_options = $("#select_sanitation_report_section");
	var sanitation_report_que_options = $("#select_sanitation_report_question");
	var sanitation_report_ans_options = $("#select_sanitation_report_answer");
	previous_section = "";
	sanitation_report_sec_options.prop("disabled", false);
	sanitation_report_sec_options.empty();
	sanitation_report_sec_options.append($("<option />").val("Select a value").prop("selected", true).text("Select a value")); 
	
	$('.sanitation_report_submitted_schools').html(sani_repo_sent_schools);
	$('.sanitation_report_not_submitted_schools').html(sani_repo_not_sent_schools);
	
	// SANITATION REPORT SELECT SECTION
	for(var i in sanitation_report_obj)
	{
	  for(var section in sanitation_report_obj[i])
	  {
        if(section!=previous_section)
		{
          sanitation_report_sec_options.append($("<option />").val(section).text(section));
		}
		previous_section = section;
	  }
	}
	
	// SANITATION REPORT SELECT QUESTION
	$('#select_sanitation_report_section').change(function(e){
	sanitation_report_que_options.prop("disabled", false);
	sanitation_report_que_options.empty();
	sanitation_report_que_options.append($("<option />").val("Select a value").prop("selected", true).text("Select a value"));
	selected_section = $('#select_sanitation_report_section option:selected').val();
	for(var i in sanitation_report_obj)
	{
	  for(var j in sanitation_report_obj[i][selected_section])
	  {
		 for(var que in sanitation_report_obj[i][selected_section][j])
		 {
	       sanitation_report_que_options.append($("<option/>").val(sanitation_report_obj[i][selected_section][j][que].path).text(que));
		 }
	  }
	}
	sanitation_report_ans_options.empty();
	sanitation_report_ans_options.append($("<option />").val("select").prop("selected", true).text("Select from Item 2 first"));
	})
	
	// SANITATION REPORT SELECT ANSWER
	$('#select_sanitation_report_question').change(function(e){
	sanitation_report_ans_options.prop("disabled", false);
	sanitation_report_ans_options.empty();
	sanitation_report_ans_options.append($("<option />").val("Select a value").prop("selected", true).text("Select a value"));
	selected_section  = $('#select_sanitation_report_section option:selected').val();
	selected_question = $("#select_sanitation_report_question option:selected").text();
	selected_que_page = $("#select_sanitation_report_question option:selected").val();
	
	for(var i in sanitation_report_obj)
	{
	  for(var j in sanitation_report_obj[i][selected_section])
	  {
		 if(sanitation_report_obj[i][selected_section][j].hasOwnProperty([selected_question]))
		 {
		    $.each(sanitation_report_obj[i][selected_section][j][selected_question].options, function() {
			    sanitation_report_ans_options.append($("<option />").val(this).text(this));
			});
		 }
	 }
	}
	
	})

	// DRAW SANITATION REPORT PIE
	$('#select_sanitation_report_answer').change(function(e){
	   
	   var date     = $('#sanitation_report_date').val();
	   var question = $('#select_sanitation_report_question option:selected').val();
	   var opt      = $('#select_sanitation_report_answer option:selected').val();
	   
	   console.log(date);
	   
	   $.ajax({
		url  : 'draw_sanitation_report_pie',
		type : 'POST',
		data : { "date" : date, "que" : question, "opt" : opt},
		success: function (data) {
				data = data.trim();
				if(data!="NO_DATA_AVAILABLE")
				{
					data = JSON.parse(data);
					$('#sanitation_report_note').empty();
					var district_list = data.district_list;
					var schools_list  = data.schools_list;
					var attach_list   = data.attachment_list;
					var all_values = 0;
					for (var key in district_list) {
					  if (district_list.hasOwnProperty(key)) {
						all_values = all_values + district_list[key].value;
					  }
					}
					if(all_values != 0){
						$("#pie_sanitation_school_list").empty();
					    $("#pie_sanitation_dist").empty();
					    sanitation_report_pie(district_list,schools_list,attach_list);
					}else{
						$('#sanitation_report_note').empty();
						$('#sanitation_report_note').html('<label id="sanitation_report_note">&nbsp; No data available !</label>');
					}
				   
				}
				else
				{
			       $('#sanitation_report_note').empty();
			       $('#sanitation_report_note').html('<label id="sanitation_report_note">&nbsp; No data available !</label>');
				}
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
	  });
	   
	})
	
	function sanitation_report_pie(sanitation_report, school_list, attachments){
	var pie = new d3pie("pie_sanitation_dist", {
		header: {
			title: {
				text: "",
				/*color:"#fff",*/
			}
		},
		size: {
	        canvasHeight: 250,
	        canvasWidth: 600
	    },
	    data: {
	      content: sanitation_report
	    },
	    labels: {
	        inner: {
	            format: "value"
	        },
		/*	mainLabel: {
				color: "#fff",
				font: "arial",
				fontSize: 15
				},*/
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					d3.select(this).on('click',null);
					var dist_name = a.data.label
				
				var school_table = '<table class="table table-bordered"><thead><tr><th>'+dist_name+'</th><th>Attachments</th></tr></thead><tbody>';
				var schools_in_dist = school_list[dist_name];
				var attach_in_dist  = attachments[dist_name];
				
				for(school_ind = 0; school_ind < schools_in_dist.length; school_ind++){
					var school_name = schools_in_dist[school_ind];
					if(typeof(attach_in_dist[school_name])!=="undefined")
					{
				       school_table = school_table + '<tr><td>'+schools_in_dist[school_ind]+'</td><td><a class="btn btn-primary btn-xs view_sanitation_images" href="javascript:void(0);" path="'+attach_in_dist[school_name]+'">View</a></td><tr>'; 
					}
					else
					{
						school_table = school_table + '<tr><td>'+schools_in_dist[school_ind]+'</td><td>No attachments</td><tr>';
					}
				}
				school_table = school_table + '</tbody></table>';
				$("#pie_sanitation_school_list").html(school_table);
					
					
				}
			}
	      
		});
    }
	
	// View Sanitation Report Attachments
   /*$(document).on('click','.view_sanitation_images',function(e)
   {
	 var path = $(this).attr('path');
	 var paths = path.split(',');
	 $('#sanitation_report_attachments_modal_body').empty();
	 var gallery="";
	 var img    = "";
	 gallery="<div class='row'><div class='superbox col-sm-12'><div class='superbox-list'>";
	 for(var i=0;i<paths.length;i++)
	 {
        img+= "<div class='well'><img src='<?php echo URLCustomer;?>"+paths[i]+"' height='125px;' width='200px;' alt='image'></div>";
	 }
	 gallery+=img;
	 gallery+="</div></div></div>";rel="prettyPhoto['Image'+i+'']"
	 $(gallery).appendTo('#sanitation_report_attachments_modal_body');
	 $('#sanitation_report_attachments_modal').modal('show');
   })*/
   
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
	
		var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
      var textRange; var j=0;
      tab = document.getElementById('sani_repo_sent_school_modal_body_tab'); // id of table

      for(j = 0 ; j < tab.rows.length ; j++)
      {    
            tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
            //tab_text=tab_text+"</tr>";
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

    // Sanitation report sent schools list
    $('.sanitation_report_submitted_schools_list').click(function(){
		if(sani_repo_submitted_schools_list!=null)
		{
	        var table="";
			var tr="";
			
			if(sani_repo_submitted_schools_list['school']!="")
			{
				$('#sani_repo_sent_school_modal_body').empty();
				table += "<table class='table table-bordered' id='sani_repo_sent_school_modal_body_tab'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";
				for(var i=0;i<sani_repo_submitted_schools_list['school'].length;i++)
				{
					var j=i+1;
					table+= "<tr><td>"+j+"</td><td>"+sani_repo_submitted_schools_list['district'][i]+"</td><td>"+sani_repo_submitted_schools_list['school'][i]+"</td><td>"+sani_repo_submitted_schools_list['mobile'][i]+"</td><td>"+sani_repo_submitted_schools_list['person_name'][i]+"</td></tr>"
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
	
		var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
      var textRange; var j=0;
      tab = document.getElementById('sani_repo_not_submitted_schools_list_tab'); // id of table

      for(j = 0 ; j < tab.rows.length ; j++)
      {    
            tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
            //tab_text=tab_text+"</tr>";
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
				table += "<table class='table table-bordered' id='sani_repo_not_submitted_schools_list_tab'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";
				for(var i=0;i<sani_repo_not_submitted_schools_list['school'].length;i++)
				{
					var j=i+1;
					table+= "<tr><td>"+j+"</td><td>"+sani_repo_not_submitted_schools_list['district'][i]+"</td><td>"+sani_repo_not_submitted_schools_list['school'][i]+"</td><td>"+sani_repo_not_submitted_schools_list['mobile'][i]+"</td><td>"+sani_repo_not_submitted_schools_list['person_name'][i]+"</td></tr>"
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
	


 $('.datepicker').datepicker({
	minDate: new Date(1900, 10 - 1, 25)
 });

change_to_default();

function change_to_default(today_date,absent_report,request_report,symptoms_report,screening_report){
	$('#request_pie_span').val("Daily");
	$('#screening_pie_span').val("Yearly");
	
	//$('#set_data').val(today_date);
	//$('#select_dt_name').val(dt_name);
	$('#school_name').val(school_name);
} 

function initialize_variables(today_date,absent_report,request_report,symptoms_report,screening_report,chronic_ids){
	console.log('init fn', today_date);
	today_date = today_date;
	console.log('init fun222222', today_date);

	init_absent_pie(absent_report);
	init_req_id_pie(request_report,symptoms_report);
	init_screening_pie(screening_report);
	init_and_update_chronic_id_list(chronic_ids);
	
	$('.sanitation_report_status_date').html('');
	$('.sanitation_report_status_date').html(today_date);
	$('.abs_submitted_schools').html(absent_sent_schools);
	$('.abs_not_submitted_schools').html(absent_not_sent_schools);
	
	
}
 


$('#set_date_btn').click(function(e){
	today_date = $('#set_data').val();
	//alert(today_date);
	//location.reload();
	//$('#load_waiting').modal('show');

	$.ajax({
		url: 'to_dashboard_with_date',
		type: 'POST',
		data: {"today_date" : today_date, "request_pie_span" : request_pie_span, "screening_pie_span" : screening_pie_span, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			$('#load_waiting').modal('hide');
			$( "#pie_absent" ).empty();
			$( "#pie_request" ).empty();
			$( "#pie" ).empty();
			$( "#pie_screening" ).empty();
			
			data = $.parseJSON(data);
			absent_report     = $.parseJSON(data.absent_report);
			request_report    = $.parseJSON(data.request_report);
			symptoms_report   = $.parseJSON(data.symptoms_report);
			screening_report  = $.parseJSON(data.screening_report);
			chronic_ids       = $.parseJSON(data.chronic_ids);
			
			// Absent Report
			var absent_submitted_schools_list_count = data.absent_report_schools_list.submitted_count;
			var absent_not_submitted_schools_list_count = data.absent_report_schools_list.not_submitted_count;
			absent_submitted_schools_list     = "";
			absent_not_submitted_schools_list = "";
			absent_submitted_schools_list     = data.absent_report_schools_list.submitted;
	        absent_not_submitted_schools_list = data.absent_report_schools_list.not_submitted; 
			
			// Sanitation Report
			var sanitation_report_submitted_schools_list_count = data.sanitation_report_schools_list.submitted_count;
			var sanitation_report_not_submitted_schools_list_count = data.sanitation_report_schools_list.not_submitted_count;
			sani_repo_submitted_schools_list     = "";
	        sani_repo_not_submitted_schools_list = "";
			sani_repo_submitted_schools_list     = data.sanitation_report_schools_list.submitted;
	        sani_repo_not_submitted_schools_list = data.sanitation_report_schools_list.not_submitted; 
			
			
			initialize_variables(today_date,absent_report,request_report,symptoms_report,screening_report,chronic_ids);
			draw_absent_pie();
			draw_identifiers_pie();
			draw_request_pie();
			draw_screening_pie();
			update_absent_schools_data(absent_submitted_schools_list_count,absent_not_submitted_schools_list_count);
			update_sanitation_report_schools_data(sanitation_report_submitted_schools_list_count,sanitation_report_not_submitted_schools_list_count);
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	
});

function update_sanitation_report_schools_data(sanitation_report_submitted_schools_list_count,sanitation_report_not_submitted_schools_list_count)
{
	$('.sanitation_report_submitted_schools').html(sanitation_report_submitted_schools_list_count);
	$('.sanitation_report_not_submitted_schools').html(sanitation_report_not_submitted_schools_list_count);
}


$('#select_dt_name').change(function(e){
	dist = $('#select_dt_name').val();
	dt_name = $("#select_dt_name option:selected").text();
	//alert(dist);
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
			options.append($("<option />").val("All").prop("selected", true).text("All"));
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
	school_name = $("#school_name option:selected").text();
});


// SANITATION INFRASTRUCTURE


$(document).on('click','.sanitation_report_prev',function(e){
	
	var current_date = $('#sanitation_report_date').val(); 
	
	var item1 = $('#select_sanitation_report_section').val();
	var item2 = $('#select_sanitation_report_question').val();
	var item3 = $('#select_sanitation_report_answer').val();

	if(item1 ==="select_section" || item2 ==="select_question" || item3 ==="select_answer")
	{
        $.smallBox({
			title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
			content   : "Select all items !",
			color     : "#C46A69",
			iconSmall : "fa fa-bell bounce animated",
			timeout   : 4000
		});
		
		e.preventDefault();
	}
	else
	{
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
		
		var question = $('#select_sanitation_report_question option:selected').val();
		var opt      = $('#select_sanitation_report_answer option:selected').val();
		   
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
		
		$.ajax({
		url  : 'draw_sanitation_report_pie',
		type : 'POST',
		data : { "date" : prev_date_formatted, "que" : question, "opt" : opt},
		success: function (data) {
				data = data.trim();
				if(data!="NO_DATA_AVAILABLE")
				{
				   data = JSON.parse(data);
				   $('#sanitation_report_note').empty();
				   var district_list = data.district_list;
				   var schools_list  = data.schools_list;
				   var attach_list   = data.attachment_list;
				   $("#pie_sanitation_school_list").empty();
				   $("#pie_sanitation_dist").empty();
				   sanitation_report_pie(district_list,schools_list,attach_list);
				   
				}
				else
				{
				   $('#sanitation_report_note').empty();
				   $('#sanitation_report_note').html('<label id="sanitation_report_note">&nbsp; No data available !</label>');
				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});
  }
})

$(document).on('click','.sanitation_report_next',function(e){
	var current_date = $('#sanitation_report_date').val(); 
	
	var item1 = $('#select_sanitation_report_section').val();
	var item2 = $('#select_sanitation_report_question').val();
	var item3 = $('#select_sanitation_report_answer').val();

	if(item1 ==="select_section" || item2 ==="select_question" || item3 ==="select_answer")
	{
        $.smallBox({
			title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
			content   : "Select all items !",
			color     : "#C46A69",
			iconSmall : "fa fa-bell bounce animated",
			timeout   : 4000
		});
	}
	else
	{
	
	    var question = $('#select_sanitation_report_question option:selected').val();
		var opt      = $('#select_sanitation_report_answer option:selected').val();
		
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
		
		// set date
		$('#sanitation_report_date').val(next_date_formatted);
		
		$.ajax({
		url  : 'draw_sanitation_report_pie',
		type : 'POST',
		data : { "date" : next_date_formatted, "que" : question, "opt" : opt},
		success: function (data) {
				data = data.trim();
				if(data!="NO_DATA_AVAILABLE")
				{
				   data = JSON.parse(data);
				   $('#sanitation_report_note').empty();
				   var district_list = data.district_list;
				   var schools_list  = data.schools_list;
				   var attach_list   = data.attachment_list;
				   $("#pie_sanitation_school_list").empty();
				   $("#pie_sanitation_dist").empty();
				   sanitation_report_pie(district_list,schools_list,attach_list);
				   
				}
				else
				{
				   $('#sanitation_report_note').empty();
				   $('#sanitation_report_note').html('<label id="sanitation_report_note">&nbsp; No data available !</label>');
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
		

//===================================drill down pie======================
//===================================end of dril down pie================
</script>

