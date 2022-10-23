<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "TMREIS Dashboard";

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
								<div class="well well-sm well-light" id="news_feed_div" style="min-height: 210px;">
					<?php if(count($news_feeds) > 0): ?>
					<div class="panel panel-default">
					<div class="panel-body">
					<div class="row">
					<div class="col-xs-12">
					<ul class="demo1">
				<?php foreach ($news_feeds as $news_feed):?>
				<li style="list-style-type:none" class="news-item">
				<table >
				<tr>
				<td> <i class="fa fa-bell" style="font-size:20px;color:red"></i>&nbsp;&nbsp; <?php echo $news_feed["display_date"].' <i class="fa fa-lg fa-fa-calendar"></i> '.((strlen($news_feed["news_feed"])>=500)? substr($news_feed["news_feed"], 0,500)." <font size='2' color='blue'><i>cont...</i></font>" : $news_feed["news_feed"]) ;?> 
			
				<?php if(isset($news_feed["file_attachment"])){
						echo ' | <img src='.IMG.'/attachment.png'.' width="40" class="img-circle" />'.count($news_feed["file_attachment"]).' attachments ';
				}?><a href="#" class="open_news" news_data="<?php echo base64_encode(json_encode($news_feed))?>"> read more ...</a>
				</li><br>
				</td>
				</tr>
				</table>
				
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
		<div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
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
							<h2>Set Date For PIE</h2>

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
													<section class="col col-6">
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
													<section class="col col-6">
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
			
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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
											<section class="col col-9">
													<label class="label">Search Span</label>
													<label class="select">
														<select id="screening_pie_span">
															<option value="Monthly">Monthly</option>
															<option value="Bi Monthly">Bi Monthly</option>
															<option value="Quarterly">Quarterly</option>
															<option value="Half Yearly">Half Yearly</option>
															<option value="2015-16 Academic Year">2015-16 Academic Year</option>
															<option value="2016-17 Academic Year">2016-17 Academic Year</option>
															<option selected value="Yearly">2017-18 Academic Year</option>
														</select> <i></i> </label>
												</section>
											<section class="col col-8">
											<label class="label" id="refresh_date"><?php echo $last_screening_update?></label>
													<button type="button" class="btn bg-color-greenDark txt-color-white btn-sm" id="refresh_screening_data">
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
							<span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
							<h2>Attendance and Request PIE </h2>

						</header>

						<!-- widget div-->
						<div class="no-padding">
							<!-- widget edit box -->
							<!-- end widget edit box -->

							<div class="widget-body">
								<!-- content -->
								<div id="myTabContent" class="tab-content">
								<div class="row">
								<br>
								
								<div class="col-xs-12 col-sm-3 col-md-4 col-lg-4">
								<div class="well well-sm well-light">
								
								<div class="well well-sm well-light">
								<br>
								<div id="pie_absent"></div>
								<form style="display: hidden" action="drill_down_absent_to_students_load_ehr" method="POST" id="ehr_form_for_absent">
									<input type="hidden" id="ehr_data_for_absent" name="ehr_data_for_absent" value=""/>
									<input type="hidden" id="ehr_navigation_for_absent" name="ehr_navigation_for_absent" value=""/>
								</form>
								</div>
								
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
					<div class="well well-sm well-light">
					<button type="button" class="btn btn-primary" id="absent_sent_school_download">
							Download
						</button>
						</div>
					<div id="absent_sent_school_modal_body" class="modal-body">
		            
					
					</div>
					<div class="modal-footer">
					<!-- <button type="button" class="btn btn-primary" id="absent_sent_school_download">
							Download
						</button> -->
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
		
		<!-- ATTENDANCE NOT SUBMITTED SCHOOLS LIST -->
		<div class="modal fade-in" id="absent_not_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							×
						</button>
						<h4 class="modal-title" id="myModalLabel">Absent Report Not Submitted Schools </h4>
					</div>
					<div class="well well-sm well-light">
					<button type="button" class="btn btn-primary" id="absent_not_sent_school_download">
							Download
						</button>
						</div>
					<div id="absent_not_sent_school_modal_body" class="modal-body">
					
					</div>
					<div class="modal-footer">
					
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
		
								<div class="well well-sm well-light">
								<label class="form-control"> <a href="javascript:void(0)" class="abs_submitted_schools_list">Submitted Schools &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</a> <span class="abs_submitted_schools"></span></label>
								<label class="form-control"> <a href="javascript:void(0)" class="abs_not_submitted_schools_list"> Not Submitted Schools : </a><span class="abs_not_submitted_schools"></span></label>
								</div>
							</div>
							</div>
							
							<div class="col-xs-12 col-sm-3 col-md-8 col-lg-8">
								<div class="well well-sm well-light">
								
								<div class="col-md-12" id="loading_req_pie" style="display:none;">
									<center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
								</div>
								
								<div id="req_id_pies">
								
								
								<div class="row">
								
								
								<div class="col-xs-12 col-sm-3 col-md-6 col-lg-6">
									<div class="well well-sm well-light">
										<br>
										<div >							
											<div id="pie"></div>
											<form style="display: hidden" action="drill_down_identifiers_to_students_load_ehr" method="POST" id="ehr_form_for_identifiers">
												<input type="hidden" id="ehr_data_for_identifiers" name="ehr_data_for_identifiers" value=""/>
												<input type="hidden" id="ehr_navigation_for_identifiers" name="ehr_navigation_for_identifiers" value=""/>
											</form>
										</div>
									</div>
								</div>
								
								<div class="col-xs-12 col-sm-3 col-md-6 col-lg-6">
									<div class="well well-sm well-light">
									
										<br>
										<div >
											<div id="pie_request"></div>
											<span class="badge bg-color-blue  pull-left inbox-badge" id="total_active_req">Total active request today : </span>
											<span class="badge bg-color-greenLight pull-right inbox-badge" id="total_rised_req">Total request raised today : </span>
											<br/>
											<form style="display: hidden" action="drill_down_request_to_students_load_ehr" method="POST" id="ehr_form_for_request">
												<input type="hidden" id="ehr_data_for_request" name="ehr_data_for_request" value=""/>
												<input type="hidden" id="ehr_navigation_for_request" name="ehr_navigation_for_request" value=""/>
											</form>
										</div>
									</div>
								</div>
								</div>
								<div class="row">
								<br>
								<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									<div class="well well-sm well-light">
										<form class="smart-form" >
										<fieldset style="padding-top: 0px; padding-bottom: 0px;">
										<section>
											<label class="label">Search Span</label>
											<label class="select">
												<select id="request_pie_span">
													<option selected value="Daily">Daily</option>
													<option value="Weekly">Weekly</option>
													<option value="Bi Weekly">Bi Weekly</option>
													<option value="Monthly">Monthly</option>
													<option value="Bi Monthly">Bi Monthly</option>
													<option value="Quarterly">Quarterly</option>
													<option value="Half Yearly">Half Yearly</option>
													<option value="Yearly">Yearly</option>
												</select> <i></i> </label>
										</section>
										</fieldset>
										</form>
									</div>
								</div>
								</div>
								</div>
							</div>
								</div>
							</div>
								<!-- end content -->
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
				
				
			<!--</div> end row -->
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
<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>
<script src="<?php echo JS; ?>jquery.bootstrap.newsbox.min.js" type="text/javascript" charset="utf-8"></script>


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
	
	$('#total_active_req').html("Total active request today : "+<?php echo $total_active_req;?>);
	$('#total_rised_req').html("Total raised request today : "+<?php echo $total_raised_req;?>);
	
	var absent_sent_schools     = <?php echo $absent_report_schools_list['submitted_count'];?>;
	var absent_not_sent_schools = <?php echo $absent_report_schools_list['not_submitted_count'];?>;
	var absent_submitted_schools_list     = "";
	var absent_not_submitted_schools_list = "";
	
	absent_submitted_schools_list         = <?php echo json_encode($absent_report_schools_list['submitted']);?>;
	absent_not_submitted_schools_list     = <?php echo json_encode($absent_report_schools_list['not_submitted']);?>;

	
	var absent_data = "";
	var absent_navigation = [];
	previous_absent_a_value = [];
	previous_absent_title_value = [];
	previous_absent_search = [];
	absent_search_arr = [];

	var request_pie_span = "Daily";
	var request_data = "";
	var request_navigation = [];
	previous_request_a_value = [];
	previous_request_title_value = [];
	previous_request_search = [];
	search_arr = [];

	var identifiers_data = "";
	var identifiers_navigation = [];
	previous_identifiers_a_value = [];
	previous_identifiers_title_value = [];
	previous_identifiers_search = [];
	search_arr = [];

	var screening_pie_span = "Yearly";
	var screening_data = "";
	var screening_navigation = [];
	previous_screening_a_value = [];
	previous_screening_title_value = [];
	previous_screening_search = [];
	search_arr = [];

	<?php if($message) { ?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
				content : "<?php echo $message?>",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
<?php } ?>

$('.datepicker').datepicker({
	minDate: new Date(1900, 10 - 1, 25)
 });
initialize_variables(today_date,<?php echo $absent_report?>,<?php echo $request_report?>,<?php echo $symptoms_report?>,<?php echo $screening_report?>);

change_to_default();

function change_to_default(today_date,absent_report,request_report,symptoms_report,screening_report){
	$('#request_pie_span').val("Daily");
	$('#screening_pie_span').val("Yearly");
	
	//$('#set_data').val(today_date);
	//$('#select_dt_name').val(dt_name);
	$('#school_name').val(school_name);
}

function initialize_variables(today_date,absent_report,request_report,symptoms_report,screening_report){
	console.log('init fn', today_date);
	today_date = today_date;
	console.log('init fun222222', today_date);

	init_absent_pie(absent_report);
	init_req_id_pie(request_report,symptoms_report);
	init_screening_pie(screening_report);
	
	
	$('.abs_submitted_schools').html(absent_sent_schools);
	$('.abs_not_submitted_schools').html(absent_not_sent_schools);
	
	
}

function init_absent_pie(absent_report){
	absent_data = absent_report;
	absent_navigation = [];
	previous_absent_a_value = [];
	previous_absent_title_value = [];
	previous_absent_search = [];
	absent_search_arr = [];
}

function init_req_id_pie(request_report,symptoms_report){
	request_data = request_report;
	request_navigation = [];
	previous_request_a_value = [];
	previous_request_title_value = [];
	previous_request_search = [];
	search_arr = [];
	
	identifiers_data = symptoms_report;
	identifiers_navigation = [];
	previous_identifiers_a_value = [];
	previous_identifiers_title_value = [];
	previous_identifiers_search = [];
	search_arr = [];
}

function init_screening_pie(screening_report){
	screening_data = screening_report;
	screening_navigation = [];
	previous_screening_a_value = [];
	previous_screening_title_value = [];
	previous_screening_search = [];
	search_arr = [];
}

/*******************************************************************
 *
 * Helper : Initialize and update id list for chronic case line graph
 *
 */
 
function init_and_update_chronic_id_list(chronic_ids)
{
	chronic_id_list = chronic_ids;
	var options = $("#chronic_id_school_list");
	options.empty();
	options.append($("<option />").val("select_school").prop("selected",true).text("--select--"));
	
	var previous_selected_school = "";
	for(var index in chronic_ids)
	{
       if(chronic_ids[index].school_name !== previous_selected_school)
	   {
       options.append($("<option />").val(chronic_ids[index].school_name).text(chronic_ids[index].school_name));
	   }
	   previous_selected_school = chronic_ids[index].school_name;
	}
	
}

$('#chronic_id_school_list').change(function(e){
	
	var school    = $('#chronic_id_school_list').val();
	var options   = $("#chronic_id_list");
	options.empty();
	options.append($("<option />").val("select_id").prop("selected",true).text("--select--"));
	
	for(var i in chronic_id_list)
	{
       if(chronic_id_list[i].school_name === school)
	   {
       options.append($("<option />").val(chronic_id_list[i].student_unique_id).text(chronic_id_list[i].student_unique_id).attr('case_id',chronic_id_list[i].case_id).attr('months',chronic_id_list[i].scheduled_months));
	   }
	}
})

draw_absent_pie();
draw_identifiers_pie();
draw_request_pie();
draw_screening_pie();

$('#request_pie_span').change(function(e){
	request_pie_span = $('#request_pie_span').val();
	$( "#req_id_pies" ).hide();
	$("#loading_req_pie").show();
	console.log('error', today_date);
	console.log('error', request_pie_span);
	$.ajax({
		url: 'update_request_pie',
		type: 'POST',
		data: {"today_date" : today_date, "request_pie_span" : request_pie_span},
		success: function (data) {			
			$("#loading_req_pie").hide();
			$( "#req_id_pies" ).show();
			
			$( "#pie_request" ).empty();
			$( "#pie" ).empty();
			
			data = $.parseJSON(data);
			request_report = $.parseJSON(data.request_report);
			symptoms_report = $.parseJSON(data.symptoms_report);
			
			init_req_id_pie(request_report,symptoms_report);
			draw_identifiers_pie();
			draw_request_pie();			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	
});

$('#screening_pie_span').change(function(e){
	screening_pie_span = $('#screening_pie_span').val();
	$( "#screening_pies" ).hide();
	$("#loading_screening_pie").show();
	if(screening_pie_span != "Yearly" )
	{
		$("#refresh_screening_data").show();
	}else{
		$("#refresh_screening_data").show();
	}
	console.log('error', today_date);
	console.log('error', screening_pie_span);
	$.ajax({
		url: 'update_screening_pie',
		type: 'POST',
		data: {"today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {			
			$("#loading_screening_pie").hide();
			$( "#screening_pies" ).show();
			$( "#pie_screening" ).empty();
			data = $.parseJSON(data);
			screening_data = $.parseJSON(data.screening_report);
			console.log(screening_data);
			init_screening_pie(screening_data);
			draw_screening_pie();			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	
});

$('#set_date_btn').click(function(e){
	today_date = $('#set_data').val();
	console.log('today_date=======checking',today_date);
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
			console.log('bhanu1', data);
			absent_report     = $.parseJSON(data.absent_report);
			request_report    = $.parseJSON(data.request_report);
			symptoms_report   = $.parseJSON(data.symptoms_report);
			screening_report  = $.parseJSON(data.screening_report);
			console.log('testinggggggg', screening_report);

			//news_feeds_data   = $.parseJSON(data.news_feeds);
			news_feeds_data   = $.parseJSON(JSON.stringify(data.news_feeds));
			console.log('bhanu2', news_feeds_data);
			// Absent Report
			var absent_submitted_schools_list_count = data.absent_report_schools_list.submitted_count;
			var absent_not_submitted_schools_list_count = data.absent_report_schools_list.not_submitted_count;
			absent_submitted_schools_list     = "";
			absent_not_submitted_schools_list = "";
			absent_submitted_schools_list     = data.absent_report_schools_list.submitted;
	        absent_not_submitted_schools_list = data.absent_report_schools_list.not_submitted; 
			
			
			
			initialize_variables(today_date,absent_report,request_report,symptoms_report,screening_report);
			draw_absent_pie();
			draw_identifiers_pie();
			draw_request_pie();
			draw_screening_pie();
			update_absent_schools_data(absent_submitted_schools_list_count,absent_not_submitted_schools_list_count);
			
			/* if(news_feeds_data.length > 0){
				console.log(news_feeds_data.length);
				console.log(news_feeds_data);
				var news_feeds = '<div class="panel panel-default"><div class="panel-body"><div class="row"><div class="col-xs-12"><ul class="demo1">';
				news_feeds_data.forEach( function (news_item)
				{
					var news_content = ((news_item.news_feed.length >= 30) ? news_item.news_feed.substr(0, 30)+" <font size='2' color='blue'><i>cont...</i></font>" : news_item.news_feed);
					console.log(news_item);
					news_feeds = news_feeds+'<li class="news-item"><table ><tr><td> <i class="fa fa-dot-circle-o"></i>'+news_item.display_date+' <i class="fa fa-lg fa-fa-calendar"></i> '+news_content+'<a href="#" class="open_news" news_data="'+btoa(JSON.stringify(news_item))+'"> read more ...</a></td></tr></table></li>';
				});
				news_feeds = news_feeds+'</ul></div></div></div><div class="panel-footer"> </div></div>';
				$('#news_feed_div').html(news_feeds);
				
			} */
			if(news_feeds_data.length > 0){
				console.log(news_feeds_data.length);
				console.log(news_feeds_data);
				var news_feeds = '<div class="panel panel-default"><div class="panel-body"><div class="row"><div class="col-xs-12"><ul class="demo1">';
				news_feeds_data.forEach( function (news_item)
				{
					var news_content = ((news_item.news_feed.length >= 500) ? news_item.news_feed.substr(0, 500)+" <font size='2' color='blue'><i>cont...</i></font>" : news_item.news_feed);
					console.log(news_item);
					news_feeds = news_feeds+'<li class="news-item"><table ><tr><td> <i class="fa fa-dot-circle-o"></i>'+news_item.display_date+' <i class="fa fa-lg fa-fa-calendar"></i> '+news_content+'<a href="#" class="open_news" news_data="'+btoa(JSON.stringify(news_item))+'"> read more ...</a></td></tr></table></li>';
				});
				news_feeds = news_feeds+'</ul></div></div></div><div class="panel-footer"> </div></div>';
				$('#news_feed_div').html(news_feeds);
				
			}
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	
});

function update_absent_schools_data(absent_submitted_schools_list_count,absent_not_submitted_schools_list_count)
{
	$('.abs_submitted_schools').html(absent_submitted_schools_list_count);
	
	$('.abs_not_submitted_schools').html(absent_not_submitted_schools_list_count);
}



// Absent list sent schools list
$('.abs_submitted_schools_list').click(function(){
	
	if(absent_submitted_schools_list!=null)
	{
		if(absent_submitted_schools_list['school']!="")
		{
		//console.log("submitted schools",absent_submitted_schools_list['school']);
			$('#absent_sent_school_modal_body').empty();
			var table="";
			var tr="";
			table += "<table class='table table-bordered' id='absent_sent_school_modal_body_tab'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";
			for(var i=0;i<absent_submitted_schools_list['school'].length;i++)
			{
				var j=i+1;
				table+= "<tr><td>"+j+"</td><td>"+absent_submitted_schools_list['district'][i]+"</td><td>"+absent_submitted_schools_list['school'][i]+"</td><td>"+absent_submitted_schools_list['mobile'][i]+"</td><td>"+absent_submitted_schools_list['person_name'][i]+"</td></tr>"
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
			table += "<table class='table table-bordered'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";
			for(var i=0;i<absent_not_submitted_schools_list['school'].length;i++)
			{
				var j=i+1;
				table+= "<tr><td>"+j+"</td><td>"+absent_not_submitted_schools_list['district'][i]+"</td><td>"+absent_not_submitted_schools_list['school'][i]+"</td><td>"+absent_not_submitted_schools_list['mobile'][i]+"</td><td>"+absent_not_submitted_schools_list['person_name'][i]+"</td></tr>"
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
	
	/*if(absent_submitted_schools_list!=null)
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
})*/
var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
      var textRange; var j=0;
      tab = document.getElementById('absent_sent_school_modal_body_tab'); // id of table

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
         sa=txtArea1.document.execCommand("SaveAs",true,"absent_sent_school_.xlsx");
      } 
      else //other browser not tested on IE 11
         sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text)); 
        return (sa);
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

function absent_pie(heading, data, onClickFn){
	var pie = new d3pie("pie_absent", {
		header: {
			title: {
				text: absent_navigation.join(" / ")
			}
		},
		size: {
	        canvasHeight: 250,
	        canvasWidth: 400
	    },
	    data: {
	      content: data
	    },
	    labels: {
	        inner: {
	            format: "value"
	        }
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					console.log("Segment clicked! See the console for all data passed to the click handler.");
					console.log(onClickFn);
					if(onClickFn == "drill_down_absent_to_districts"){
						console.log(a);
						previous_absent_a_value[1] = absent_data;
						previous_absent_title_value[1] = today_date;
						console.log(previous_absent_a_value);
						console.log("11111111111111111111111111111111111111111");
						drill_down_absent_to_districts(a);
					}else if(onClickFn == "drill_down_absent_to_schools"){
						console.log(a);
						previous_absent_a_value[2] = data;
						previous_absent_title_value[2] = heading;
						previous_absent_search[2] = heading;
						console.log(previous_absent_a_value);
						search_arr[0] = previous_absent_search[2];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						console.log("calling school funnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn");
						drill_down_absent_to_schools(search_arr);
					}else if(onClickFn == "drill_down_absent_to_students"){
						search_arr[0] = previous_absent_search[2];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						console.log("calling student funcccccccccccccccccccccccccc");
						drill_down_absent_to_students(search_arr);
					}else{
						index = onClickFn;
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						console.log(a);
						//previous_screening_a_value[index] = previous_screening_a_value[index];
						//previous_screening_title_value[index] = previous_screening_title_value[index];
						//previous_screening_search[index] = previous_screening_title_value[index];
						console.log("value from previous function -------------------------------------------");
						//console.log(previous_screening_a_value);
		
						if (index == 1){
							drill_down_absent_to_districts(a);
						}else if (index == 2){
							search_arr[0] = previous_absent_search[2];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_absent_to_schools(search_arr);
						}
						
					}
					
				}
			}
	      
		});
}

function draw_absent_pie(){
	if(absent_data == 1){
		$("#pie_absent").append('No positive values to dispaly');
	}else{
		absent_navigation.push(today_date);
	absent_pie(today_date,absent_data,"drill_down_absent_to_districts");
}
}

function drill_down_absent_to_districts(pie_data){
	console.log(pie_data);

	$.ajax({
		url: 'drilldown_absent_to_districts',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data),"today_date" : today_date, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#pie_absent" ).empty();
			//$("#pie_absent").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_absent_back(1);"> Back </button>');
			$("#pie_absent").append('<button class="btn btn-primary pull-right" id="absent_back_btn" ind= "1"> Back </button>');

			absent_navigation.push(pie_data.data.label);
			absent_pie(pie_data.data.label,content,"drill_down_absent_to_schools");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}

function drill_down_absent_to_schools(pie_data){
	console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaschooooooooooooooooooooooooooooooooool");
	console.log(pie_data);
	$.ajax({
		url: 'drilling_absent_to_schools',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data),"today_date" : today_date, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			var content = $.parseJSON(data);
			$( "#pie_absent" ).empty();
			//$("#pie_absent").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_absent_back(2);"> Back </button>');
			$("#pie_absent").append('<button class="btn btn-primary pull-right" id="absent_back_btn" ind= "2"> Back </button>');

			absent_navigation.push(pie_data[1]);
			absent_pie(pie_data[1],content,"drill_down_absent_to_students");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

function drill_down_absent_to_students(pie_data){

	$.ajax({
		url: 'drill_down_absent_to_students',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			console.log(data);
			$("#ehr_data_for_absent").val(data);
			absent_navigation.push(pie_data[1]);
			$("#ehr_navigation_for_absent").val(absent_navigation.join(" / "));
			//window.location = "drill_down_screening_to_students_load_ehr/"+data;
			//alert(data);
			
			$("#ehr_form_for_absent").submit();
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

$(document).on("click",'#absent_back_btn',function(e){
		var index = $(this).attr("ind");
		console.log("in back functionnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn-----------");
		console.log(index);
		$( "#pie_absent" ).empty();
		if(index>1){
			var ind = index - 1;
		$("#pie_absent").append('<button class="btn btn-primary pull-right" id="absent_back_btn" ind= "' + ind + '"> Back </button>');
		}
		absent_navigation.pop();
		absent_pie(previous_absent_title_value[index], previous_absent_a_value[index], index);
});

function identifiers_pie(heading, identifiers_data, onClickFn){
	var pie = new d3pie("pie", {
		header: {
			title: {
				text: identifiers_navigation.join(" / ")
			}
		},
		size: {
	        canvasHeight: 250,
	        canvasWidth: 400
	    },
	    data: {
	      content: identifiers_data
	    },
	    labels: {
	        inner: {
	            format: "value"
	        }
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}"
	     },

	     callbacks: {
				onClickSegment: function(a) {
					
					if(onClickFn == "drill_down_identifiers_to_districts"){
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						console.log(a);
						previous_identifiers_a_value[1] = identifiers_data;
						previous_identifiers_title_value[1] = heading;
						console.log(previous_identifiers_a_value);
						drill_down_identifiers_to_districts(a);
					}else if(onClickFn == "drill_down_identifiers_to_schools"){
						console.log("schollllllllllllllllllllllllllllllllllllllllll");
						console.log(a);
						previous_identifiers_a_value[2] = identifiers_data;
						previous_identifiers_title_value[2] = heading;
						previous_identifiers_search[2] = heading;
						console.log(previous_identifiers_a_value);
						search_arr[0] = previous_identifiers_search[2];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						drill_down_identifiers_to_schools(search_arr);
					}else if(onClickFn == "drill_down_identifiers_to_students"){
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						
						search_arr[0] = previous_identifiers_search[2];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						drill_down_identifiers_to_students(search_arr);
					}else{
						index = onClickFn;
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						console.log(a);
						//previous_screening_a_value[index] = previous_screening_a_value[index];
						//previous_screening_title_value[index] = previous_screening_title_value[index];
						//previous_screening_search[index] = previous_screening_title_value[index];
						console.log("value from previous function -------------------------------------------");
						//console.log(previous_screening_a_value);

						if (index == 1){
							drill_down_identifiers_to_districts(a);
						}else if (index == 2){
							search_arr[0] = previous_identifiers_search[2];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_identifiers_to_schools(search_arr);
						}
						
					}
					
				}
			}
	      
	});
}

function draw_identifiers_pie(){
	if(identifiers_data == 1){
		console.log('in false of abbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
		$("#pie").append('No positive values to dispaly');
	}else{
		identifiers_navigation.push("Symptoms Pie Chart");
		identifiers_pie("Symptoms Pie Chart",identifiers_data,"drill_down_identifiers_to_districts");
	}
}

function drill_down_identifiers_to_districts(pie_data){
	
	$.ajax({
		url: 'drilldown_identifiers_to_districts',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data), "today_date" : today_date, "request_pie_span" : request_pie_span, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#pie" ).empty();
			//$("#pie").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_identifiers_back(1);"> Back </button>');
			$("#pie").append('<button class="btn btn-primary pull-right" id="identifiers_back_btn" ind="1"> Back </button>');

			identifiers_navigation.push(pie_data.data.label);
			identifiers_pie(pie_data.data.label,content,"drill_down_identifiers_to_schools");			
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

function drill_down_identifiers_to_schools(pie_data){
	console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaschooooooooooooooooooooooooooooooooool");
	console.log(pie_data);
	$.ajax({
		url: 'drilling_identifiers_to_schools',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "request_pie_span" : request_pie_span, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#pie" ).empty();
			//$("#pie").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_identifiers_back(2);"> Back </button>');
			$("#pie").append('<button class="btn btn-primary pull-right" id="identifiers_back_btn" ind="2"> Back </button>');

			identifiers_navigation.push(pie_data[1]);
			identifiers_pie(pie_data[1],content,"drill_down_identifiers_to_students");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

function drill_down_identifiers_to_students(pie_data){

	$.ajax({
		url: 'drill_down_identifiers_to_students',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "request_pie_span" : request_pie_span, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			console.log(data);
			$("#ehr_data_for_identifiers").val(data);
			identifiers_navigation.push(pie_data[1]);
			$("#ehr_navigation_for_identifiers").val(identifiers_navigation.join(" / "));
			//window.location = "drill_down_screening_to_students_load_ehr/"+data;
			//alert(data);
			
			$("#ehr_form_for_identifiers").submit();
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}


$(document).on("click",'#identifiers_back_btn',function(e){
	var index = $(this).attr("ind");
	console.log("in back functionnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn-----------");
	console.log(index);
	$( "#pie" ).empty();
	if(index>1){
		var ind = index - 1;
	$("#pie").append('<button class="btn btn-primary pull-right" id="identifiers_back_btn" ind="' + ind + '"> Back </button>');
	}
	identifiers_navigation.pop();
	identifiers_pie(previous_identifiers_title_value[index],previous_identifiers_a_value[index],index);
});

function request_pie(heading, request_data, onClickFn){
	var pie = new d3pie("pie_request", {
		header: {
			title: {
				text: request_navigation.join(" / ")
			}
		},
		size: {
	        canvasHeight: 250,
	        canvasWidth: 300//400
	    },
	    labels: {
			outer:
			{
				pieDistance:10
			},
	        inner: {
	            format: "value"
	        },
			/* truncation: {
				enabled: true,
				truncateLength:10
			} */
	    },
	    data: {
	      content: request_data
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					//alert("Segment clicked! See the console for all data passed to the click handler.");
					if(onClickFn == "drill_down_request_to_districts")
						{
						console.log(a);
						previous_request_a_value[1] = request_data;
						previous_request_title_value[1] = "Request Pie Chart";
						console.log(previous_request_a_value);
						drill_down_request_to_districts(a);
					}else if (onClickFn == "drill_down_request_to_schools"){
						console.log(a);
						previous_request_a_value[2] = request_data;
						previous_request_title_value[2] = heading;
						previous_request_search[2] = heading;
						console.log(previous_request_a_value);
						search_arr[0] = previous_request_search[2];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						drill_down_request_to_schools(search_arr);
					}else if (onClickFn == "drill_down_request_to_students"){
						search_arr[0] = previous_request_search[2];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						drill_down_request_to_students(search_arr);
					}else {
						index = onClickFn;
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						console.log(a);
						//previous_screening_a_value[index] = previous_screening_a_value[index];
						//previous_screening_title_value[index] = previous_screening_title_value[index];
						//previous_screening_search[index] = previous_screening_title_value[index];
						console.log("value from previous function -------------------------------------------");
						//console.log(previous_screening_a_value);

						if (index == 1){
							drill_down_request_to_districts(a);
						}else if (index == 2){
							search_arr[0] = previous_request_search[2];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_request_to_schools(search_arr);
						}
					}
				}
			}
	      
	});
}

function draw_request_pie(){
	if(request_data == 1){
		$("#pie_request").append('No positive values to dispaly');
	}else{
		request_navigation.push("Request Pie Chart");
		request_pie("Request Pie Chart",request_data,"drill_down_request_to_districts");
	}
}

function drill_down_request_to_districts(pie_data){
	$.ajax({
		url: 'drilldown_request_to_districts',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data), "today_date" : today_date, "request_pie_span" : request_pie_span, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#pie_request" ).empty();
			$("#pie_request").append('<button class="btn btn-primary pull-right" id="reports_back_btn" ind="1"> Back </button>');
			request_navigation.push(pie_data.data.label);
			request_pie(pie_data.data.label,content,"drill_down_request_to_schools");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

function drill_down_request_to_schools(pie_data){
	console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaschooooooooooooooooooooooooooooooooool");
	console.log(pie_data);
	$.ajax({
		url: 'drilling_request_to_schools',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "request_pie_span" : request_pie_span, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#pie_request" ).empty();

			$("#pie_request").append('<button class="btn btn-primary pull-right" id="reports_back_btn" ind="2"> Back </button>');
			request_navigation.push(pie_data[1]);
			request_pie(pie_data[1],content,"drill_down_request_to_students");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

function drill_down_request_to_students(pie_data){

	$.ajax({
		url: 'drill_down_request_to_students',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "request_pie_span" : request_pie_span, "dt_name" : dt_name, "school_name" : school_name},
		success: function (data) {
			console.log(data);
			$("#ehr_data_for_request").val(data);
			request_navigation.push(pie_data[1]);
			$("#ehr_navigation_for_request").val(request_navigation.join(" / "));
				
			//window.location = "drill_down_screening_to_students_load_ehr/"+data;
			//alert(data);
			
			$("#ehr_form_for_request").submit();
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

$(document).on("click",'#reports_back_btn',function(e){
	var index = $(this).attr("ind");
	$( "#pie_request" ).empty();
	if(index>1){
		var ind = index - 1;
	$("#pie_request").append('<button class="btn btn-primary pull-right" id="reports_back_btn" ind="' + ind + '"> Back </button>');
	}
	request_navigation.pop();
	request_pie(previous_request_title_value[index], previous_request_a_value[index], index);
});


$('#refresh_screening_data').click(function(e){
	$( "#screening_update_loading" ).show();
	$( "#refresh_screening_data" ).prop('disabled', true);
	$.ajax({
		url: 'refresh_screening_data',
		type: 'POST',
		data: {"today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {
			console.log(data);
			data = data;
			$("#refresh_date").text(data);
			$( "#screening_update_loading" ).hide();
			$( "#refresh_screening_data" ).prop('disabled', false);
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
});



function screening_pie(heading, screening_data, onClickFn){
	var pie = new d3pie("pie_screening", {
		header: {
			title: {
				text: screening_navigation.join(" / ")
			}
		},
		size: {
	        canvasHeight: 250,
	        canvasWidth: 400//600
	    },
	    data: {
	      content: screening_data
	    },
	    labels: {
			outer:
			{
				pieDistance:10
			},
	        inner: {
	            format: "value",
				},
			/* truncation: {
				enabled: true,
				truncateLength:10
			} */
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					//alert("Segment clicked! See the console for all data passed to the click handler.");
					if(onClickFn == "drill_down_screening_to_abnormalities"){
						console.log(a);
						previous_screening_a_value[1] = screening_data;
						previous_screening_title_value[1] = "Screening Pie Chart";
						console.log(previous_screening_a_value);
						drill_down_screening_to_abnormalities(a);
					}else if(onClickFn == "drill_down_screening_to_districts"){
						console.log(a);
						previous_screening_a_value[2] = screening_data;
						previous_screening_title_value[2] = heading;
						previous_screening_search[2] = heading;
						console.log(previous_screening_a_value);
						drill_down_screening_to_districts(a);
					}else if(onClickFn == "drill_down_screening_to_schools"){
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						console.log(a);
						previous_screening_a_value[3] = screening_data;
						previous_screening_title_value[3] = heading;
						previous_screening_search[3] = heading;
						console.log(previous_screening_a_value);
						search_arr[0] = previous_screening_search[3];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						drill_down_screening_to_schools(search_arr);
					}else if(onClickFn == "drill_down_screening_to_students"){
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						search_arr[0] = previous_screening_search[3];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						drill_down_screening_to_students(search_arr);
					}else{
						index = onClickFn;
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						console.log(a);
						//previous_screening_a_value[index] = previous_screening_a_value[index];
						//previous_screening_title_value[index] = previous_screening_title_value[index];
						//previous_screening_search[index] = previous_screening_title_value[index];
						console.log("value from previous function -------------------------------------------");
						//console.log(previous_screening_a_value);

						if(index == 1){
							drill_down_screening_to_abnormalities(a);
						}else if (index == 2){
							drill_down_screening_to_districts(a);
						}else if (index == 3){
							search_arr[0] = previous_screening_search[3];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_screening_to_schools(search_arr);
						}
					}
					
				}
			}
	      
	});
}

function draw_screening_pie(){
	if(screening_data == 1){
		console.log('in false of abbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
		$("#pie_screening").append('No positive values to dispaly<br><br>');
	}else{
		screening_navigation.push("Screening Pie Chart");
		screening_pie("Screening Pie Chart", screening_data, "drill_down_screening_to_abnormalities");
	
	}
}

function drill_down_screening_to_abnormalities(pie_data){
	console.log("in drill_down_screening_to_abnormalities-------------aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa-------------");
	$.ajax({
		url: 'drilling_screening_to_abnormalities',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data), "today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#pie_screening" ).empty();
			$("#pie_screening").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "1"> Back </button>');
			screening_navigation.push(pie_data.data.label);
			screening_pie(pie_data.data.label, content, "drill_down_screening_to_districts");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}

function drill_down_screening_to_districts(pie_data){

	$.ajax({
		url: 'drilling_screening_to_districts',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data), "today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#pie_screening" ).empty();
			$("#pie_screening").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "2"> Back </button>');
			screening_navigation.push(pie_data.data.label);
			screening_pie(pie_data.data.label, content, "drill_down_screening_to_schools");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}

	function drill_down_screening_to_schools(pie_data){
	console.log("in school pieeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee---------------------pie data");
	console.log(pie_data);
	$.ajax({
		url: 'drilling_screening_to_schools',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$( "#pie_screening" ).empty();
			$("#pie_screening").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "3"> Back </button>');
			screening_navigation.push(pie_data[1]);
			screening_pie(pie_data[1], content, "drill_down_screening_to_students");
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}

	function drill_down_screening_to_students(pie_data){
		

		$.ajax({
			url: 'drill_down_screening_to_students',
			type: 'POST',
			data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "screening_pie_span" : screening_pie_span},
			success: function (data) {
				console.log(data);
				$("#ehr_data").val(data);
				screening_navigation.push(pie_data[1]);
				$("#ehr_navigation").val(screening_navigation.join(" / "));
				//window.location = "drill_down_screening_to_students_load_ehr/"+data;
				//alert(data);
				
				$("#ehr_form").submit();
				
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
		}

$(document).on("click",'#screening_back_btn',function(e){
	var index = $(this).attr("ind");
	$( "#pie_screening" ).empty();
	if(index>1){
		var ind = index - 1;
	$("#pie_screening").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "' + ind + '"> Back </button>');
	}
	screening_navigation.pop();
	screening_pie(previous_screening_title_value[index], previous_screening_a_value[index], index);
});

/* end pie chart */

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


$(function () {
	
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
});	
});		
		

//===================================drill down pie======================
//===================================end of dril down pie================
</script>

