<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "All Reports";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["All_reports"]["active"] = true;
include("inc/nav.php");

?>
<style>
#flot-tooltip { font-size: 12px; font-family: Verdana, Arial, sans-serif; position: absolute; display: none; border: 2px solid; padding: 2px; background-color: #FFF; opacity: 0.8; -moz-border-radius: 5px; -webkit-border-radius: 5px; -khtml-border-radius: 5px; border-radius: 5px; }

</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->

<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<link href="<?php echo(CSS.'site.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

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
		
		
		
		
		<div class="row">

		<div class="col-xs-12 col-sm-4 col-md-12 col-lg-12">
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
							<span class="widget-icon"> <i class="fa fa-rss"></i> </span>
							<h2>News Feed </h2>

						</header>

						<!-- widget div-->
						<div class="no-padding">
							<!-- widget edit box -->
							<!-- end widget edit box -->

							<div class="widget-body">
								<!-- content -->
								<div id="myTabContent" class="tab-content">
		<div class="well well-sm well-light" id="news_feed_div" style="height: 172px;">
<?php if(count($news_feeds) > 0): ?>
									<div class="panel panel-default">
<div class="panel-body">
<div class="row">
<div class="col-xs-12">
<ul class="demo1">
<?php foreach ($news_feeds as $news_feed):?>
<li class="news-item">
<table >
<tr>
<td> <i class="fa fa-dot-circle-o"></i> <?php echo $news_feed["display_date"].' <i class="fa fa-lg fa-fa-calendar"></i> '.((strlen($news_feed["news_feed"])>=30)? substr($news_feed["news_feed"], 0,30)." <font size='2' color='blue'><i>cont...</i></font>" : $news_feed["news_feed"]) ;?> 
			
			<?php if(isset($news_feed["file_attachment"])){
					echo ' | <img src='.IMG.'/attachment.png'.' width="40" class="img-circle" />'.count($news_feed["file_attachment"]).' attachments ';
			}?><a href="#" class="open_news" news_data="<?php echo base64_encode(json_encode($news_feed))?>"> read more ...</a>
</td>
</tr>
</table>
</li>
<?php endforeach;?>
</ul>
</div>
</div>
</div>
<div class="panel-footer"> </div>
</div>

<?php else: ?>
<h3> No news feed for today.</h3>
<?php endif ?>
													
							</div>
								<!-- end content -->

						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->
                 </div>
				</article>
				<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
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
							<h2>Set Date For PIE </h2>

						</header>

						<!-- widget div-->
						<div class="no-padding">
							<!-- widget edit box -->
							<!-- end widget edit box -->

							<div class="widget-body">
								<!-- content -->
								<div id="myTabContent" class="tab-content">
								<div class="well well-sm well-light">
								<form class='smart-form'>
									<fieldset>
											<div class="row">
											<section class="col col-12">
											<div class="form-group">
												<div class="input-group">
												<input type="text" id="set_data" name="set_data" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo $today_date?>">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												</div>
											</div>
											</section>
					                    
										</div>
												<div class="row">
													<section class="col col-lg-12">
														<label class="label" for="first_name">District Name</label>
														<label class="select">
														<select id="select_dt_name" >
															<option value="All">All</option>
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
													<section class="col col-lg-12">
														<label class="label" for="first_name">School Name</label>
														<label class="select">
														<select id="school_name" disabled=true>
															<option value='All' >All</option>
															
															
														</select> <i></i>
													</label>
													</section>
													<section class="col col-6">
													<button type="button" class="btn bg-color-pink txt-color-white btn-sm" id="set_date_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
						                       Set
						                    </button>
						                    </section>
													</div>
													
													
													</fieldset>
													
										</form>			
													
							</div>
								<!-- end content -->

						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->
                 </div>
				</article>
				<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<!-- new widget -->
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="wid-id-60" data-widget-editbutton="false">
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
							<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
							<h2>Screening Report</h2>

						</header>

						<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->

							</div>
							<!-- end widget edit box -->

							<!-- widget content -->
							<div class="col-md-12" id="loading_screening_pie" style="display:none;">
									<center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
								</div>
								
								<div id="screening_pies">
								
								<div class="row">
								
								
								<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									<div class="well well-sm well-light">
										<form class='smart-form'>
										<fieldset style="padding-top: 0px; padding-bottom: 0px;">
										<div class="row">
											<section class="col col-4">
												<label class="label">Search Span</label>
												<label class="select">
													<select id="screening_pie_span">
														<option value="Monthly">Monthly</option>
														<option value="Bi Monthly">Bi Monthly</option>
														<option value="Quarterly">Quarterly</option>
														<option value="Half Yearly">Half Yearly</option>
														<option selected value="Yearly">Yearly</option>
													</select> <i></i> </label>
											</section>
											<section class="col col-8">
											<label class="label" id="refresh_date"><?php echo $last_screening_update?></label>
													<button type="button" class="btn bg-color-greenDark txt-color-white btn-sm" id="refresh_screening_data" disabled>
							                       	Refresh
							                    	</button>
							                    	<img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="screening_update_loading" style="display:none;width:30px;height:30px">
											</section>
										</div>
										</fieldset>
										</form>
									</div>
								</div>
								
								
								
								
								</div>
								
								<div class="row">
								<br>
								
								<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									<div class="well well-sm well-light">
										<br>
										<div >
											<div id="pie_screening"></div>
											<i><label id="screening_note">Note : To get uptodate results, please click on refresh once.</label></i>
											<form style="display: hidden" action="drill_down_screening_to_students_load_ehr" method="POST" id="ehr_form">
											  <input type="hidden" id="ehr_data" name="ehr_data" value=""/>
											  <input type="hidden" id="ehr_navigation" name="ehr_navigation" value=""/>
											</form>
										</div>
									</div>
								</div>
								
								</div>
								</div>
								<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->
					<!-- end widget -->
					</article>
				
				</div>

				
			<!-- row -->
			<!-- end row -->
			</div>

			<!-- start row -->
			<div class="row">
		
				<h2 class="row-seperator-header"><i class="fa fa-th-list"></i> Attendance Reports </h2>
		
				<!-- NEW WIDGET START -->
				<article class="col-sm-12 col-md-12 col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget well transparent" id="wid-id-9" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
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
							<span class="widget-icon"> <i class="fa fa-comments"></i> </span>
							<h2>Accordiondds </h2>
		
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
							<div id="absent_report_schools">

							</div>
							<div id="attendance_submitted_schools_report" class="hide">
								<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-editbutton="false">
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
							<span class="widget-icon"> <i class="fa fa-table"></i> </span>
							<h2>No Padding</h2>
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

								<div class="alert alert-info no-margin fade in">
									
									<i class="fa-fw fa fa-info"></i>
									Attendance Submitted Schools Reports
								</div>

								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>District</th>
											<th>School Name</th>
											<th>Present</th>
											<th>Absent</th>
											<th>Sick
											<th>R2H</th>
											<th>Rest Room</th>
										</tr>
									</thead>
									<tbody>
										<?php	
														if(isset($absent_report_submitted_schools) && !empty($absent_report_submitted_schools) && !is_null($absent_report_submitted_schools)):

														
													
														 foreach ($absent_report_submitted_schools as $value) { ?>
														
														<tr>
															<td><?php echo $value['doc_data']['widget_data']['page1']['Attendence Details']['District']; ?></td>
															<td><?php echo $value['doc_data']['widget_data']['page1']['Attendence Details']['Select School']; ?></td>
															<td><?php echo $value['doc_data']['widget_data']['page1']['Attendence Details']['Attended']; ?></td>
															<td><?php echo $value['doc_data']['widget_data']['page1']['Attendence Details']['Absent']; ?></td>
															<td><?php echo $value['doc_data']['widget_data']['page1']['Attendence Details']['Sick']; ?></td>
															<td><?php echo $value['doc_data']['widget_data']['page1']['Attendence Details']['R2H']; ?></td>
															<td><?php echo $value['doc_data']['widget_data']['page2']['Attendence Details']['RestRoom']; ?></td>
														</tr>
														<?php  }
														else: ?>
															<tr>
															<td><?php echo "No schools Submitted Attendance on ".$today_date; ?></td>
														</tr>
														<?php endif; ?>
									</tbody>
								</table>
											<!-- ATTENDANCE SUBMITTED SCHOOLS LIST -->
								<div class="modal fade-in" id="absent_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							×
						</button>
						<h4 class="modal-title" id="myModalLabel">Absent Report Submitted Schools </h4>
					</div>
					<div id="absent_sent_school_modal_body" class="modal-body">
		            
					
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="absent_sent_school_download">
							Download
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
		
		<!-- ATTENDANCE NOT SUBMITTED SCHOOLS LIST -->
		<div class="modal" id="absent_not_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							×
						</button>
						<h4 class="modal-title" id="myModalLabel">Absent Report Not Submitted Schools </h4>
					</div>
					<div id="absent_not_sent_school_modal_body" class="modal-body">
					
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="absent_not_sent_school_download">
							Download
						</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
		<div class="well well-sm well-light">
								<label class="form-control"> <a href="javascript:void(0)" class="abs_submitted_schools_list">Submitted Schools &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</a> <span class="badge bg-color-blue txt-color-white abs_submitted_schools"></span></label>
								<label class="form-control"> <a href="javascript:void(0)" class="abs_not_submitted_schools_list">Not Submitted Schools : </a><span class="badge bg-color-blue txt-color-white abs_not_submitted_schools"></span></label>
								</div>
							</div>
							<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->
							</div>
		
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
		
				</article>
				<!-- WIDGET END -->
		
		
			</div>

			
						<!-- SANITATION INFRASTRUCTURE DONUT GRAPH -->
			<!-- widget grid -->
		<section id="widget-grid" class="">
			<div class="row">
				<article class="col-sm-12">
					<!-- new widget -->
					<div class="jarviswidget" id="wid-id-100" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
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
							<span class="widget-icon"> <i class="glyphicon glyphicon-record txt-color-darken"></i> </span>
							<h2>Sanitation Infrastructure </h2>

						</header>

						<!-- widget div-->
						<div class="no-padding">
							<!-- widget edit box -->
							<!-- end widget edit box -->

							<div class="widget-body">
							<div class="row">
								<br>
								<d`iv class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									<div class="well well-sm well-light">
										<form class="smart-form" >
										<fieldset style="padding-top: 0px; padding-bottom: 0px;">
										<section class="col col-6">
														<label class="label" for="district_name">District Name</label>
														<label class="select">
														<select id="select_sanitation_infra_dt_name" >
															<option value='select_school' >Select a district</option>
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
													<section class="col col-6">
														<label class="label" for="school_name">School Name</label>
														<label class="select">
														<select id="select_sanitation_infra_school_name" disabled=true>
														<option value='select_school' >Select a district first</option>
														</select> <i></i>
													</label>
													</section>
													
										</fieldset>
										</form>
									</div>
									<div>
									<br>
									<p class="sanitation_infra_note">&nbsp;&nbsp;Note : To get sanitation infrastructure chart, please select the district and school.</p>
									<div id="sanitation_chart" class="row" style="min-height:150px;">
									
								   </div>
									</div>
								</div>
								
								</div>
							

						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->
                 </div>
				</article>
				
			<!-- row -->

			<!-- end row -->
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
				
				
			</div><!-- end row -->
			</section>
	</div>
	<!-- END MAIN CONTENT -->
	
						<!-- Modal -->
<div id="news_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">News Details</h4>
      </div>
      <div class="modal-body">
        <div class="widget-body">
        <div id="news_body">
        </div>
        </div>
      </div>
      <div class="modal-footer">
      	
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

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

	var today_date = $('#set_data').val();

	var dt_name = $('#select_dt_name').val();
	var school_name = $('#school_name').val();
	console.log('ddddddddddddddddddddddddddd', dt_name);
	console.log('ssssssssssssssssssssssssssssssssssssss', school_name);
	
	var absent_sent_schools     = <?php echo $absent_report_schools_list['submitted_count'];?>;
	var absent_not_sent_schools = <?php echo $absent_report_schools_list['not_submitted_count'];?>;
	var absent_submitted_schools_list     = "";
	var absent_not_submitted_schools_list = "";
	
	absent_submitted_schools_list         = <?php echo json_encode($absent_report_schools_list['submitted']);?>;
	
	absent_not_submitted_schools_list     = <?php echo json_encode($absent_report_schools_list['not_submitted']);?>;

	//sanitation_report_obj                 = <?php //echo $sanitation_report_obj;?>;
	
	

$('.datepicker').datepicker({
	minDate: new Date(1900, 10 - 1, 25)
 });
initialize_variables(today_date);

change_to_default();

function change_to_default(today_date,absent_report,request_report,symptoms_report,screening_report){
	$('#request_pie_span').val("Monthly");
	$('#screening_pie_span').val("Yearly");
	
	//$('#set_data').val(today_date);
	//$('#select_dt_name').val(dt_name);
	$('#school_name').val(school_name);
}

function initialize_variables(today_date/*,absent_report,request_report,symptoms_report,screening_report*/){
	console.log('init fn', today_date);
	today_date = today_date;
	console.log('init fun222222', today_date);
	load_attendance_submitted_schools();
	/*init_absent_pie(absent_report);
	init_req_id_pie(request_report,symptoms_report);
	init_screening_pie(screening_report);*/
	$('.abs_submitted_schools').html(absent_sent_schools);
	$('.abs_not_submitted_schools').html(absent_not_sent_schools);

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
			console.log("nareshhhhhhhhhhhhhhhhhhhhhh",result)

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



function load_attendance_submitted_schools()
{
	today_date = $('#set_data').val();
	//alert(today_date);
	//location.reload();
	//$('#load_waiting').modal('show');

	$.ajax({
		url: 'dashboard_reports_with_date',
		type: 'POST',
		data: {"today_date" : today_date, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			$('#load_waiting').modal('hide');
			result = $.parseJSON(data);
			console.log('load_attendance_submitted_schools',result);
			display_data_table(result);
			
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	
}



$('#set_date_btn').click(function(e){
	today_date = $('#set_data').val();
	//alert(today_date);
	//location.reload();
	//$('#load_waiting').modal('show');

	$.ajax({
		url: 'dashboard_reports_with_date',
		type: 'POST',
		//dataType: "json",
		data:  {"today_date" : today_date, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			$('#load_waiting').modal('hide');
			result = $.parseJSON(data);
			init_display_data_table(result);
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	
});

function init_display_data_table(result){

		if(result.length > 0){
			data_table = '<table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%"> <thead> <tr> <th>District</th><th>School Name</th><th>Present</th> <th>Absent</th> <th>Sick</th><th>R2H</th> <th>Rest Room</th></tr> </thead> <tbody>';

			$.each(result, function() {


				data_table = data_table + '<tr>';
				data_table = data_table + '<td>'+this.doc_data.widget_data['page1']['Attendence Details']['District']+ '</td>';
				console.log('DataTable'.data_table);
				data_table = data_table + '<td>'+this.doc_data.widget_data['page1']['Attendence Details']['Select School']+ '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data['page1']['Attendence Details']['Attended']+ '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data['page1']['Attendence Details']['Absent']+ '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data['page1']['Attendence Details']['Sick']+ '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data['page1']['Attendence Details']['R2H']+ '</td>';
				data_table = data_table + '<td>'+this.doc_data.widget_data['page1']['Attendence Details']['RestRoom']+ '</td>';
				data_table = data_table + '</tr>';
					
			});

			data_table = data_table + '</tbody></table>';

			$("#absent_report_schools").html(data_table);

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
				/* "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
				 "oTableTools": {
		        	 "aButtons": [
		        	      {
		                 "sExtends": "xls",
		                 "sTitle": "JOHNSON GRAMMAR Student Report",
		                 "sPdfMessage": "JOHNSON GRAMMAR Student Excel Export",
		                 "sPdfSize": "letter"
			             },
			          	{
			             	"sExtends": "print",
			             	"sMessage": "JOHNSON GRAMMAR Student Printout <i>(press Esc to close)</i>"
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
				} */		
			
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
				$("#absent_report_schools").html('<h5>No reports to display on '+today_date+'</h5>');
			}
	}

// Absent list sent schools list download
$('#absent_sent_school_download').click(function(){
	
	if(absent_submitted_schools_list!=null)
	{
		console.log('bhanu',absent_submitted_schools_list)
       $.ajax({
		url : 'download_absent_sent_schools_list',
		type: 'POST',
		data: {"data" : absent_submitted_schools_list,"today_date" : today_date},
		success: function (data) {
			console.log('bhanu',data)
			//window.location = "to_dashboard";
			//$("#absent_sent_school_modal").modal('hide');
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

// Absent list sent schools list
$('.abs_submitted_schools_list').click(function(){
	
	if(absent_submitted_schools_list!=null)
	{
		if(absent_submitted_schools_list['school']!="")
		{
			$('#absent_sent_school_modal_body').empty();
			var table="";
			var tr="";
			table += "<table class='table table-bordered'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Contact person</th><th> Mobile Number</th></tr></thead><tbody>";
			for(var i=0;i<absent_submitted_schools_list['school'].length;i++)
			{
				var j=i+1;
				table+= "<tr><td>"+j+"</td><td>"+absent_submitted_schools_list['district'][i]+"</td><td>"+absent_submitted_schools_list['school'][i]+"</td><td>"+absent_submitted_schools_list['person_name'][i]+"</td><td>"+absent_submitted_schools_list['mobile'][i]+"</td></tr>"
			}
			table += "</tbody></table>";
			$(table).appendTo('#absent_sent_school_modal_body');
		}
		else
		{
			table+="No Schools";
			$(table).appendTo('#absent_sent_school_modal_body');
		}
	}
	else
	{
		table+="No Schools";
		$(table).appendTo('#absent_sent_school_modal_body');
	}
	$('#absent_sent_school_modal').modal('show');
})

// Absent list not sent schools list
$('.abs_not_submitted_schools_list').click(function(){
	
	if(absent_not_submitted_schools_list!=null)
	{
		if(absent_not_submitted_schools_list['school']!="")
		{
			$('#absent_not_sent_school_modal_body').empty();
			var table="";
			var tr="";
			table += "<table class='table table-bordered'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Contact person</th><th>Mobile Number</th></tr></thead><tbody>";
			for(var i=0;i<absent_not_submitted_schools_list['school'].length;i++)
			{
				var j=i+1;
				table+= "<tr><td>"+j+"</td><td>"+absent_not_submitted_schools_list['district'][i]+"</td><td>"+absent_not_submitted_schools_list['school'][i]+"</td><td>"+absent_not_submitted_schools_list['person_name'][i]+"</td><td>"+absent_not_submitted_schools_list['mobile'][i]+"</td></tr>"
			}
			table += "</tbody></table>";
			console.log(table)
			$(table).appendTo('#absent_not_sent_school_modal_body');
		}
		else
		{
			table+="No Schools";
			$(table).appendTo('#absent_not_sent_school_modal_body');
		}
	}
	else
	{
		 table+="No Schools";
		 $(table).appendTo('#absent_not_sent_school_modal_body');
	}
	$('#absent_not_sent_school_modal').modal('show');
})

// Absent list sent schools list download
$('#absent_sent_school_download').click(function(){
	
	if(absent_submitted_schools_list!=null)
	{
       $.ajax({
		url : 'download_absent_sent_schools_list',
		type: 'POST',
		data: {"data" : absent_submitted_schools_list,"today_date" : today_date},
		success: function (data) {
			window.location = data;
			$("#absent_sent_school_modal").modal('hide');
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

// Absent list not sent schools list download
$('#absent_not_sent_school_download').click(function(){
	
	if(absent_not_submitted_schools_list!=null)
	{
       $.ajax({
		url : 'download_absent_not_sent_schools_list',
		type: 'POST',
		data: {"data" : absent_not_submitted_schools_list,"today_date" : today_date},
		success: function (data) {
			window.location = data;
			$("#absent_not_sent_school_modal").modal('hide');
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

});

	$(document).on('click','.open_news',function(){
		//alert("newssssssssssssssssssssssssssssssssssss")
		var news_data = $(this).attr("news_data");
		console.log(news_data);
		news_obj = JSON.parse(atob(news_data))
		console.log(news_obj);
		var news_details = '<p>'+news_obj.display_date+'</p><p style="word-wrap: break-word;">'+news_obj.news_feed+'</p>';
		if (news_obj.hasOwnProperty('file_attachment')){
			news_obj.file_attachment.forEach(function(entry) {
			    console.log(entry);
			    url = "../../"+entry.file_path.substr(2);
			    news_details = news_details + '<embed src="'+url+'" width="100%" height="100%" autoplay="false" controller="false">'
			});}
		$("#news_body").html(news_details);
		$('#news_modal').modal("show");
		});	

	$("#news_modal").on('hidden.bs.modal', function(){
		$("#news_body").empty();
});

/* $(function () {
	
$(".demo1").bootstrapNews({
newsPerPage: 10,
navigation: true,
autoplay: true,
direction:'up', // up or down
animationSpeed: 'normal',
newsTickerInterval: 4000, //4 secs
pauseOnHover: true,
onStop: null,
onPause: null,
onReset: null,
onPrev: null,
onNext: null,
onToDo: null
});
});		 */		
		

//===================================drill down pie======================
//===================================end of dril down pie================
</script>

