<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Panacea Dashboard";

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

<link href="<?php echo(CSS.'jquery.fullPage.css'); ?>" media="screen" rel="stylesheet" type="text/css" /><!--
<link href="<?php echo(CSS.'fullPage_styles.css'); ?>" media="screen" rel="stylesheet" type="text/css" /> 
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

<link href="<?php echo(CSS.'site.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
-->

<style>
#flot-tooltip { font-size: 12px; font-family: Verdana, Arial, sans-serif; position: absolute; display: none; border: 2px solid; padding: 2px; background-color: #FFF; opacity: 0.8; -moz-border-radius: 5px; -webkit-border-radius: 5px; -khtml-border-radius: 5px; border-radius: 5px; }
#fp-nav ul:hover .fp-tooltip{
    right: 20px;
    transition: opacity 0.2s ease-in;
    width: auto;
    opacity: 1;
	color : #729eb3;
}

.fp-tableCell{
     vertical-align: top;
}

#chronic_line_graph div.xAxis div.tickLabel {
  transform: translateY(15px) rotate(45deg);
  -ms-transform: translateY(15px) rotate(45deg);
  /* IE 9 */
  -moz-transform: translateY(15px) rotate(45deg);
  /* Firefox */
  -webkit-transform: translateY(10px) rotate(45deg);
  -webkit-transition: width 0px; /* Safari */
  /* Safari and Chrome */
  -o-transform: translateY(15px) rotate(-90deg);
  /* Opera */
  /*rotation-point:50% 50%;*/
  /* CSS3 */
  /*rotation:270deg;*/
  /* CSS3 */
}
/*.demo1
{
  
    border-bottom:1px dotted red; 
}*/


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
			
			
				<div class="row">
				<!-- NEW WIDGET START -->
				<article class="col-sm-12 col-md-12 col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
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
							<h2>Default Tabs with border </h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="panel-group smart-accordion-default" id="accordion-2">
								<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseTwo-1" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> Click to Set date and District </a></h4>
										</div>
										<div id="collapseTwo-1" class="panel-collapse collapse">
											<div class="panel-body">
												 <div class="well well-sm well-light">
									<form>
									  <div class="form-row">
									  	<div class="form-group col-md-4">
										  <label for="inputZip">Select date</label>
										  <input type="text" id="set_data" name="set_data" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo $today_date?>">
										</div>
										<div class="form-group col-md-4">
										  <label for="inputCity">District Name</label>
															<select id="select_dt_name" class="form-control" >
																<option value='All' >All</option>
																<?php if(isset($distslist)): ?>
																	<?php foreach ($distslist as $dist):?>
																	<option value='<?php echo $dist['_id']?>' ><?php echo ucfirst($dist['dt_name'])?></option>
																	<?php endforeach;?>
																	<?php else: ?>
																	<option value="1"  disabled="">No district entered yet</option>
																<?php endif ?>
															</select> <i></i>
										</div>
										<div class="form-group col-md-4">
										  <label for="inputState">School Name</label>
										  <select id="school_name" class="form-control" disabled=true >
																<option value='All' >All</option>
																
																
															</select> <i></i>
										</div>
										<div class="form-group">
									
									  </div>
									</form>		
									<button type="button" class="btn bg-color-pink txt-color-white btn-sm" id="set_date_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
												   Set
												</button>	
								</div>
								</div>
									<!-- end content -->
											</div>
										</div>
									</div>
								</div>
							<div class="row">
							
								<br>
								<div class="alert alert-info"><i class="fa-fw fa fa-info"></i><strong>Info!</strong> Select Tabs to view the Report</div>
								<ul id="myTab1" class="nav nav-tabs bordered">
									<li class="active">
										<a href="#s1" data-toggle="tab">Screening PIE</a>
									</li>
									<li>
										<a href="#s2" data-toggle="tab"><i class="fa fa-fw fa-lg fa-gear"></i>Symptoms & Request Pie Chart</a>
									</li>
									<li>
										<a href="#s3" data-toggle="tab"><i class="fa fa-fw fa-lg fa-gear"></i>Attendance Report</a>
									</li>
									<li class="dropdown">
										<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Sanitation Report <b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li>
												<a href="#s4" data-toggle="tab">Sanitation PIE Report</a>
											</li>
											<li>
												<a href="#s5" data-toggle="tab">Sanitation Infrastructure</a>
											</li>
										</ul>
									</li>
								</ul>
		
								<div id="myTabContent1" class="tab-content padding-10">
									<div class="tab-pane fade in active" id="s1">
						<!-- new widget -->
						<!-- Widget ID (each widget will need unique ID)-->
						
						
								<!-- widget content -->
								<div class="col-md-12" id="loading_screening_pie" style="display:none;">
										<center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
									</div>
									
									<div class="row">
									<div id="screening_pies">
									
									
									<div class="col-xs-12 col-sm-6 col-md-5 col-lg-5">
										<div class="well well-sm well-light">
											<form class='smart-form'>
											<fieldset style="padding-top: 0px; padding-bottom: 0px;">
											<div class="row">
												<section class="col col-6">
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
												<section class="col col-6">
												<label class="label" style="color:#fff;">.</label>
														<button type="button" class="btn bg-color-greenDark txt-color-white btn-sm" id="refresh_screening_data">
														Refresh
														</button>
														<img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="screening_update_loading" style="display:none;width:30px;height:30px">
												</section>
												
												
												<!--<section class="col col-4">
												<label class="label" id="ehr_type">EHR type</label>
														<div class="inline-group" id='re_gen'>
														<label class="radio">
															<input type="radio" name="radio-inline" checked="checked">
															<i></i>Students</label>
														<label class="radio">
															<input type="radio" name="radio-inline">
															<i></i>Staffs</label>
												</div> 
														
												</section>-->
											</div>
											<label class="label" id="refresh_date"><?php echo $last_screening_update?></label>
											</fieldset>
											</form>
											
											<i><label id="screening_note">Note : To get uptodate results, please click on refresh once.</label></i><br>
												<form style="display: hidden" action="drill_down_screening_to_students_load_ehr" method="POST" id="ehr_form">
												  <input type="hidden" id="ehr_data" name="ehr_data" value=""/>
												  <input type="hidden" id="ehr_navigation" name="ehr_navigation" value=""/>
												</form>
										</div>
									</div>
									
									<div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
										<div class="well well-sm well-light">
											
											<div >
												<div id="pie_screening" style="margin-left: 200px;"></div>
												
											</div>
										</div>
									</div>
									
									</div>
									</div>
									<!-- end widget content -->

							<!-- end widget div -->
									</div>
									<div class="tab-pane fade" id="s2">
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
													<br/>
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
													<div id="pie_request" style="margin-left: 70px;"></div>
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
												<section class="col col-6">
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
												
												<section class="col col-6">
													<label class="label">Select Status Type</label>
													<label class="select">
														<select id="request_pie_status">
															<option selected value="All">All (except Cured)</option>
															<option value="Cured">Cured</option>
														</select> <i></i> </label>
												</section>
												</fieldset>
												</form>
											</div>
										</div>
										
										
										
										
										</div>
										</div>
									</div>
									<div class="tab-pane fade" id="s3">
										<div class="well well-lg well-light">
											<div class="row">
												<div>
														<div id="absent_report_schools">
														
														</div>
														<br><br>
														<div class="">
														
																<label class="form-control"> <a href="javascript:void(0)" class="abs_submitted_schools_list">Submitted Schools &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</a> <span class="badge bg-color-blue txt-color-white abs_submitted_schools"></span></label>
																<label class="form-control"> <a href="javascript:void(0)" class="abs_not_submitted_schools_list"> Not Submitted Schools : </a><span class=" badge bg-color-blue txt-color-white abs_not_submitted_schools"></span></label>
														</div>
													<!-- end widget content -->
												<!-- end widget div -->
												</div><!-- ROW -->
												</div><!-- ROW -->
												</div><!-- ROW -->
									</div>
							<div class="tab-pane fade" id="s4">
								<div class="row">
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
								<label>Status of <label class="sanitation_report_status_date" for="date"></label></label>
								<label class="form-control"> <a href="javascript:void(0)" class="sanitation_report_submitted_schools_list">Submitted Schools &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</a> <span class="sanitation_report_submitted_schools"></span></label>
								<label class="form-control"> <a href="javascript:void(0)" class="sanitation_report_not_submitted_schools_list"> Not Submitted Schools : </a><span class="sanitation_report_not_submitted_schools"></span></label>
								</div>
								</div>
								</div>
		
							</div>
									<div class="tab-pane fade" id="s5">
										<div class="row">
								<br>
								<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
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
								</div>
		
							</div>
							<!-- end widget content -->
		
					</div>
					<!-- end widget -->
				</article>
			</div>
				
			</div>
			
			
		<div class="vertical-scrolling">
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
									<div class="well well-sm well-light" id="news_feed_div" style="min-height: 210px;">
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
					</div>
					</article>
				</div>
				</div>
			
			
			<div class="vertical-scrolling">
			
				<div class="row">
				<article class="col-sm-12">
					<!-- new widget -->
					<div class="jarviswidget" id="wid-id-100" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
						
						<header>
							<span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
							<h2>Chronic Report Line Graph </h2>

						</header>

						<!-- widget div-->
						<div class="no-padding">
							<!-- widget edit box -->
							<!-- end widget edit box -->

							<div class="widget-body">
							<div class="row">
								<br>
								<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12 chronic_tile">
									<div class="well well-sm well-light chronic_simple">
										<form class="smart-form" >
										<fieldset style="padding-top: 0px; padding-bottom: 0px;">
										<section class="col col-3">
										<div class="form-group">
												<div class="input-group">
												<label class="label" for="item1">Select School</label></div>
												<div class="input-group">
											<select id="chronic_id_school_list" class="form-control">
														<option value='select_school' >-- Select --</option>
														</select> <i></i>
												<span class="input-group-addon"><i class="fa fa-building"></i></span>
												</div>
										</div>
										</section>
										<section class="col col-3">
										<div class="form-group">
												<div class="input-group">
												<label class="label" for="item1">Select Student</label></div>
												<div class="input-group">
											<select id="chronic_id_list" class="form-control">
														<option value='select_id' >-- Select --</option>
														</select> <i></i>
												<span class="input-group-addon"><i class="fa fa-user"></i></span>
												</div>
										</div>
										</section>
										<section class="col col-2 hide timeline_month">
										<div class="form-group">
												<div class="input-group">
												<label class="label" for="item1">Select Follow-Up Month</label></div>
												<div class="input-group">
											<select id="chronic_select_month" name="chronic_select_month" class="form-control">
														<option value='select_month' >-- Select --</option>
														</select> <i></i>
												<span class="input-group-addon"><i class="fa  fa-caret-down"></i></span>
												</div>
										</div>
										</section>
										<section class="col col-4">
										<div class="input-group">
										<label class="label" for="item1">Note:- select a student (hospital unique id) and proceed</label>
													<button type="button" class="btn bg-color-orange txt-color-white btn-sm" id="view_pill_compliance" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
						                       View Pill Compliance
						                    </button>
											</div>
						                    </section>
										</fieldset>
										</form>
									</div>
									<!-- CHRONIC ADVANCED -->
									<div class="well well-sm well-light chronic_advanced hide">
									<form class="smart-form" >
										<fieldset style="padding-top: 0px; padding-bottom: 0px;">
										<section class="col col-2 chronic_advanced_start_month">
										<div class="form-group">
												<div class="input-group">
												<label class="label" for="item1">Select Start Month</label></div>
												<div class="input-group">
											<select id="chronic_advanced_select_start" name="chronic_advanced_select_start" class="form-control">
														<option value='select_month' >-- Select --</option>
														</select> <i></i>
												<span class="input-group-addon"><i class="fa  fa-caret-down"></i></span>
												</div>
												<div class="input-group">

												</div>
										</div>
										</section>
										<section class="col col-2 chronic_advanced_end_month">
										<div class="form-group">
												<div class="input-group">
												<label class="label" for="item1">Select End Month</label></div>
												<div class="input-group">
											<select id="chronic_advanced_select_end" name="chronic_advanced_select_end" class="form-control">
														<option value='select_month' >-- Select --</option>
														</select> <i></i>
												<span class="input-group-addon"><i class="fa  fa-caret-down"></i></span>
												</div>
										</div>
										</section>
										<section class="col col-4">
										<div class="input-group">
										<label class="label" for="item1">Action</label>
													<button type="button" class="btn bg-color-green txt-color-white btn-sm view_advanced_pill_compliance" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
						                       View
						                    </button>
						             		<button style="margin-left:5px;" type="button" class="btn bg-color-orange txt-color-white btn-sm cancel_chronic_advanced">
											Cancel
										   </button>
											</div>
						                    </section>
										</fieldset>
									</form>
									</div>
									<div>
									<br>
									<div id="chronic_line_graph" style="min-height:300px;max-height:300px;">
									<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									
									
									</div>
									
									</div>
								 <div id="compliance_legend" style="padding-left:10px;padding-top:10px;"></div>
									</div>
								</div>
								
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
		</div>
			
		
		
		<br><br>
		
		<!-- end full page  -->
		</div>
		
		
		
	
	<!-- End MAIN CONTENT -->
	</div>
	
	<!-- MAIN PANEL -->
</div>


<!-- ATTENDANCE SUBMITTED SCHOOLS LIST -->
		<div class="modal fade-in" id="absent_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							??
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
		<div class="modal" id="absent_not_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							??
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
					<!-- <button type="button" class="btn btn-primary" id="absent_not_sent_school_download">
							Download
						</button> -->
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Close
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
		
					<!-- SANITATION REPORT SUBMITTED SCHOOLS LIST -->
			<div class="modal fade-in" id="sani_repo_sent_school_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							??
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
							??
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
							??
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
<script src="<?php echo JS; ?>jquery.bootstrap.newsbox.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>
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
	
	$("select").each(function () {
        //$(this).val($(this).find('option[selected]').val());
    });
	
	$('#total_active_req').html("Total active request today : "+<?php echo $total_active_req;?>);
	$('#total_rised_req').html("Total raised request today : "+<?php echo $total_raised_req;?>);

	var today_date = $('#set_data').val();

	var dt_name = $('#select_dt_name').val();
	var school_name = $('#school_name').val();
	
	
	var absent_sent_schools     = <?php echo $absent_report_schools_list['submitted_count'];?>;
	var absent_not_sent_schools = <?php echo $absent_report_schools_list['not_submitted_count'];?>;
	var absent_submitted_schools_list     = "";
	var absent_not_submitted_schools_list = "";
	
	absent_submitted_schools_list         = <?php echo json_encode($absent_report_schools_list['submitted']);?>;
	absent_not_submitted_schools_list     = <?php echo json_encode($absent_report_schools_list['not_submitted']);?>;
	sanitation_report_obj                 = <?php echo $sanitation_report_obj;?>;
	
	// SANIATION REPORT
	var sani_repo_sent_schools     = <?php echo $sanitation_report_schools_list['submitted_count'];?>;
	var sani_repo_not_sent_schools = <?php echo $sanitation_report_schools_list['not_submitted_count'];?>;
	var sani_repo_submitted_schools_list     = "";
	var sani_repo_not_submitted_schools_list = "";
	var chronic_id_list        		      = "";
	
	sani_repo_submitted_schools_list         = <?php echo json_encode($sanitation_report_schools_list['submitted']);?>;
	sani_repo_not_submitted_schools_list     = <?php echo json_encode($sanitation_report_schools_list['not_submitted']);?>;
	
	var sanitation_report_sec_options = $("#select_sanitation_report_section");
	var sanitation_report_que_options = $("#select_sanitation_report_question");
	var sanitation_report_ans_options = $("#select_sanitation_report_answer");
	previous_section = "";
	sanitation_report_sec_options.prop("disabled", false);
	sanitation_report_sec_options.empty();
	sanitation_report_sec_options.append($("<option />").val("Select a value").prop("selected", true).text("Select a value"));
	
	// SANITATION REPORT SELECT SECTION
	for(var i in sanitation_report_obj)
	{
		console.log("sanitation_report_obj======",sanitation_report_obj);
	  for(var section in sanitation_report_obj[i])
	  {
		  
        if(section!=previous_section)
		{
          sanitation_report_sec_options.append($("<option />").val(section).text(section));
		  console.log("section======",section);
		}
		previous_section = section;
		console.log("previous_section======",previous_section);
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
	        canvasHeight: 450,
	        canvasWidth: 700
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
	
	var absent_data = "";
	var absent_navigation = [];
	previous_absent_a_value = [];
	previous_absent_title_value = [];
	previous_absent_search = [];
	absent_search_arr = [];

	var request_pie_span = "Daily";
	var request_pie_status = "All";
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
initialize_variables(today_date,<?php echo $absent_report?>,<?php echo $request_report?>,<?php echo $symptoms_report?>,<?php echo $screening_report?>,<?php echo $chronic_ids;?>,<?php echo $absent_submitted_info; ?>);

change_to_default();

function change_to_default(today_date,absent_report,request_report,symptoms_report,screening_report,absent_submitted_info){
	$('#request_pie_span').val("Daily");
	$('#request_pie_status').val("All");
	$('#screening_pie_span').val("Yearly");
	
	//$('#set_data').val(today_date);
	//$('#select_dt_name').val(dt_name);
	$('#school_name').val(school_name);
}

function initialize_variables(today_date,absent_report,request_report,symptoms_report,screening_report,chronic_ids,absent_submitted_info){
	console.log('init fn', today_date);
	today_date = today_date;
	console.log('init fun222222', today_date);

	init_absent_pie(absent_report);
	init_req_id_pie(request_report,symptoms_report);
	init_screening_pie(screening_report);
	init_and_update_chronic_id_list(chronic_ids);
	
	console.log('absent_submitted_info',absent_submitted_info);
	init_display_data_table(absent_submitted_info);
	$('.sanitation_report_status_date').html('');
	$('.sanitation_report_status_date').html(today_date);
	$('.abs_submitted_schools').html(absent_sent_schools);
	$('.abs_not_submitted_schools').html(absent_not_sent_schools);
	
	$('.sanitation_report_submitted_schools').html(sani_repo_sent_schools);
	$('.sanitation_report_not_submitted_schools').html(sani_repo_not_sent_schools);
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
	request_pie_status = $('#request_pie_status').val();
	$( "#req_id_pies" ).hide();
	$("#loading_req_pie").show();
	console.log('error', today_date);
	console.log('error', request_pie_span);
	$.ajax({
		url: 'update_request_pie',
		type: 'POST',
		data: {"today_date" : today_date, "request_pie_span" : request_pie_span, "request_pie_status" : request_pie_status},
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

$('#request_pie_status').change(function(e){
	request_pie_span = $('#request_pie_span').val();request_pie_status
	request_pie_status = $('#request_pie_status').val();
	$( "#req_id_pies" ).hide();
	$("#loading_req_pie").show();
	console.log('error', today_date);
	console.log('error', request_pie_span);
	$.ajax({
		url: 'update_request_pie',
		type: 'POST',
		data: {"today_date" : today_date, "request_pie_span" : request_pie_span, "request_pie_status" : request_pie_status},
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
	console.log('Date_clicked',today_date);

	$.ajax({
		url: 'to_dashboard_with_date',
		type: 'POST',
		data: {"today_date" : today_date, "request_pie_span" : request_pie_span, "screening_pie_span" : screening_pie_span, "dt_name" : dt_name, "school_name" : school_name,"request_pie_status":request_pie_status},
		success: function (data) {
			$('#load_waiting').modal('hide');
			$( "#pie_absent" ).empty();
			$( "#pie_request" ).empty();
			$( "#pie" ).empty();
			$( "#pie_screening" ).empty();
			
			data = $.parseJSON(data);
			console.log(data);
			absent_report     = $.parseJSON(data.absent_report);
			request_report    = $.parseJSON(data.request_report);
			symptoms_report   = $.parseJSON(data.symptoms_report);
			screening_report  = $.parseJSON(data.screening_report);
			chronic_ids       = $.parseJSON(data.chronic_ids);
			news_feeds_data   = $.parseJSON(data.news_feeds);
			result   = $.parseJSON(data.absent_submitted_info);
			
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
			
			
			initialize_variables(today_date,absent_report,request_report,symptoms_report,screening_report,chronic_ids,result);
			draw_absent_pie();
			draw_identifiers_pie();
			draw_request_pie();
			draw_screening_pie();
			init_display_data_table(result);
			update_absent_schools_data(absent_submitted_schools_list_count,absent_not_submitted_schools_list_count);
			update_sanitation_report_schools_data(sanitation_report_submitted_schools_list_count,sanitation_report_not_submitted_schools_list_count);
			
			
			if(news_feeds_data.length > 0){
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
				
			}
			
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
				data_table = data_table + '<td>'+this.doc_data.widget_data['page2']['Attendence Details']['RestRoom']+ '</td>';
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
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>"+
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
				}		
			
		    });
		    
		    // custom toolbar
		   /*  $("div.toolbar").html('<div class="text-right"><img src="img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>'); */
		    	   
		    // Apply the filter
		    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
		    	
		        otable
		            .column( $(this).parent().index()+':visible' )
		            .search( this.value )
		            .draw();
		            
		    } );
		    /* END COLUMN FILTER */   
			}else{
				$("#absent_report_schools").html('<h5>No reports to display on '+today_date+'</h5>');
			}
	}

function update_absent_schools_data(absent_submitted_schools_list_count,absent_not_submitted_schools_list_count)
{
	$('.abs_submitted_schools').html(absent_submitted_schools_list_count);
	$('.abs_not_submitted_schools').html(absent_not_submitted_schools_list_count);
}

function update_sanitation_report_schools_data(sanitation_report_submitted_schools_list_count,sanitation_report_not_submitted_schools_list_count)
{
	$('.sanitation_report_submitted_schools').html(sanitation_report_submitted_schools_list_count);
	$('.sanitation_report_not_submitted_schools').html(sanitation_report_not_submitted_schools_list_count);
}

// Absent list sent schools list
$('.abs_submitted_schools_list').click(function(){
	
	if(absent_submitted_schools_list!=null)
	{
		if(absent_submitted_schools_list['school']!="")
		{
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
			table += "<table class='table table-bordered' id='absent_not_submitted_schools_list_tab'><thead><tr><th>S.No</th><th> District </th><th> School Name </th><th> Mobile </th><th> Contact Person </th></tr></thead><tbody>";
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
	
	      var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
      var textRange; var j=0;
      tab = document.getElementById('absent_not_submitted_schools_list_tab'); // id of table

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

function absent_pie(heading, data, onClickFn){
	var pie = new d3pie("pie_absent", {
		header: {
			title: {
				text: absent_navigation.join(" / "),
				/*color:    "#fff",*/
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
				text: identifiers_navigation.join(" / "),
				/*color:    "#fff",*/
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
		data: {"data" : JSON.stringify(pie_data.data), "today_date" : today_date, "request_pie_span" : request_pie_span, "dt_name" : dt_name, "school_name" : school_name,"request_pie_status":request_pie_status},
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
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "request_pie_span" : request_pie_span, "dt_name" : dt_name, "school_name" : school_name,"request_pie_status":request_pie_status},
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
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "request_pie_span" : request_pie_span, "dt_name" : dt_name, "school_name" : school_name,"request_pie_status":request_pie_status},
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
				text: request_navigation.join(" / "),
				/*color:    "#fff",*/
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
		/*	mainLabel: {
				color: "#fff",
				font: "arial",
				fontSize: 15
				},*/
			 truncation: {
				enabled: true,
				truncateLength:10
			} 
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
					d3.select(this).on('click',null);
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
		data: {"data" : JSON.stringify(pie_data.data), "today_date" : today_date, "request_pie_span" : request_pie_span, "dt_name" : dt_name, "school_name" : school_name,"request_pie_status":request_pie_status},
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
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "request_pie_span" : request_pie_span, "dt_name" : dt_name, "school_name" : school_name,"request_pie_status":request_pie_status},
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
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "request_pie_span" : request_pie_span, "dt_name" : dt_name, "school_name" : school_name,"request_pie_status":request_pie_status},
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
				text: screening_navigation.join(" / "),
				/*color:    "#fff",*/
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
		/*	mainLabel: {
				color: "#fff",
				font: "arial",
				fontSize: 15
				},*/
			 truncation: {
				enabled: true,
				truncateLength:10
			} 
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					d3.select(this).on('click',null);
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


// SANITATION INFRASTRUCTURE

$('#select_sanitation_infra_dt_name').change(function(e){
	dist = $('#select_sanitation_infra_dt_name').val();
	dt_name = $("#select_sanitation_infra_dt_name option:selected").text();
	//alert(dist);
	var options = $("#select_sanitation_infra_school_name");
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


$('#select_sanitation_infra_school_name').change(function(e){
	district_name = $("#select_sanitation_infra_dt_name option:selected").text();
	school_name   = $("#select_sanitation_infra_school_name option:selected").text();
	
	$.ajax({
		url: 'get_sanitation_infrastructure',
		type: 'POST',
		data: {"district_name" : district_name,"school_name":school_name},
		success: function (data) 
		{	
           data = data.trim();
		  
           if(data != "NO_DATA_AVAILABLE")	
		   {			   
              result = $.parseJSON(data);
			  console.log('dsghhhhhhhhhhhhhhhhhhhhhhhhf',result);
			  $('#sanitation_chart').html('<div id="toilets_graph" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="hand_sanitizers_graph" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="disposable_bins_graph" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="water_dispensaries_graph" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="children_seating_graph" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div></div>');
			  
			  $('.sanitation_infra_note').remove();
			 
			  var toilets            = result.toilets;
			  var hand_sanitizers    = result.hand_sanitizers;
			  var disposable_bins    = result.disposable_bins;
			  var water_dispensaries = result.water_dispensaries;
			  var children_seating   = result.children_seating;
			  
		
			// toilets
			if ($("#toilets_graph").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>'
				for (var item in toilets) {
				  if (toilets.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+toilets[item].label+'</td><td>'+toilets[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#toilets_graph").html(table)
				// Morris.Donut({
					// element : 'toilets_graph',
					// data : toilets
				// });
			}
			
			$('#toilets_graph').prepend('<div class="">Toilets</div>');
			
			// hand sanitizers
			if ($("#hand_sanitizers_graph").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>'
				for (var item in hand_sanitizers) {
				  if (hand_sanitizers.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+hand_sanitizers[item].label+'</td><td>'+hand_sanitizers[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#hand_sanitizers_graph").html(table)
				
				// Morris.Donut({
					// element : 'hand_sanitizers_graph',
					// data : hand_sanitizers
				// });
			}
			
			$('#hand_sanitizers_graph').prepend('<div class="spec">Hand Sanitizers</div>');
			
			// disposable bins
			if ($("#disposable_bins_graph").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>'
				for (var item in disposable_bins) {
				  if (disposable_bins.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+disposable_bins[item].label+'</td><td>'+disposable_bins[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#disposable_bins_graph").html(table)
				
				// Morris.Donut({
					// element : 'disposable_bins_graph',
					// data : disposable_bins
				// });
			}
			
			$('#disposable_bins_graph').prepend('<div class="spec">Disposable Bins in</div>');
			
			// water dispensaries
			if ($("#water_dispensaries_graph").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>'
				for (var item in water_dispensaries) {
				  if (water_dispensaries.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+water_dispensaries[item].label+'</td><td>'+water_dispensaries[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#water_dispensaries_graph").html(table)
				
				// Morris.Donut({
					// element : 'water_dispensaries_graph',
					// data : water_dispensaries
				// });
			}
			
			$('#water_dispensaries_graph').prepend('<div class="spec">Water Dispensaries</div>');
			
			// children seating
			if ($("#children_seating_graph").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>'
				for (var item in children_seating) {
				  if (children_seating.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+children_seating[item].label+'</td><td>'+children_seating[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#children_seating_graph").html(table)
				
				// Morris.Donut({
					// element : 'children_seating_graph',
					// data : children_seating
				// });
			}

			$('#children_seating_graph').prepend('<div class="spec">Children sit on</div>');
		
		   }
		   else
		   {
			   $('.sanitation_infra_note').remove();
			   $('#sanitation_chart').html('<center><label id="sanitation_infra_note">No sanitation infrastructure data available for this school</label></center>');
		   }

			
					
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		}); 
	
});

$('#chronic_id_list').change(function(e){
	
	$('.timeline_month').removeClass('hide');
	var id_    = $('#chronic_id_list').val();
	var months = $("#chronic_id_list option:selected").attr('months');
	console.log(id_);
	console.log(months);
	var months_array = months.split(",");
	console.log(months_array);
	var options = $("#chronic_select_month");
	options.empty();
	options.append($("<option />").val("select_month").prop("selected",true).text("--select--"));
	
	for(var i in months_array)
	{
       options.append($("<option />").val(months_array[i]).text(months_array[i]));
	}
	
	options.append($("<option />").val("all_months").text("All Months Overview"));
	options.append($("<option />").val("custom_range").text("Custom Range"));
})

function getMonth(monthStr){
    return new Date(monthStr+'-1-01').getMonth()+1;
}

// custom range
$('#chronic_select_month').change(function(e){
	var selected_month = $("#chronic_select_month").val();
	if(selected_month == "custom_range")
	{
		$('#chronic_line_graph').empty();
		$('#compliance_legend').empty();
		$('.compliance_schedule').remove();
		$('.chronic_simple').addClass('hide');
		$('.chronic_advanced').removeClass('hide');
		var months = $("#chronic_id_list option:selected").attr('months');
		var months_array = months.split(",");
		
		// start
		var options = $("#chronic_advanced_select_start");
		options.empty();
		options.append($("<option />").val("select_startmonth").prop("selected",true).text("--select--"));
	
		for(var i in months_array)
		{
	       options.append($("<option />").val(months_array[i]).text(months_array[i]));
		}

        // end
		var options = $("#chronic_advanced_select_end");
		options.empty();
		options.append($("<option />").val("select_endmonth").prop("selected",true).text("--select--"));
	
		for(var i in months_array)
		{
	       options.append($("<option />").val(months_array[i]).text(months_array[i]));
		}
	}
});


$(document).on("click",'.cancel_chronic_advanced',function(e){
	$('.chronic_advanced').addClass('hide');
	$('.chronic_simple').removeClass('hide');
	$('select[name="chronic_select_month"]').val('select_month');
	$('#chronic_line_graph').empty();
	$('#compliance_legend').empty();
	$('.compliance_schedule').remove();
})

/**
 * @param {int} The month number, 0 based
 * @param {int} The year, not zero based, required to account for leap years
 * @return {Date[]} List with date objects for each day of the month
 */
function getDaysInMonth(month, year) {
     // Since no month has fewer than 28 days
     var date = new Date(year, month, 1);
     var days = [];
     while (date.getMonth() === month) {
        days.push(new Date(date));
        date.setDate(date.getDate() + 1);
     }
     return days;
}

$(document).on("click",'.view_advanced_pill_compliance',function(e){
	
	$('.compliance_schedule').remove();

	var id_         = $('#chronic_id_list').val();
	var case_id     = $("#chronic_id_list option:selected").attr('case_id');
	var start_month = $("#chronic_advanced_select_start").val();
	var end_month   = $("#chronic_advanced_select_end").val();
	
	// start
	var date = new Date();
	var start_month_no = getMonth(start_month);
	var end_month_no   = getMonth(end_month);

	var firstDay = new Date(date.getFullYear(), start_month_no-1, 1);
	var lastDay  = new Date(date.getFullYear(), end_month_no, 0);
	var begin    = firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
	var end      = lastDay.getFullYear()+'-'+(lastDay.getMonth()+1)+'-'+lastDay.getDate();
		
		
	pill_graph_values = [];
		
		// PILL COMPLIANCE GRAPH PLOT
		$.ajax
		({
			url  : 'prepare_pill_compliance_monthly_graph',
			type : 'POST',
			data :{'unique_id':id_,'case_id':case_id,'begin':begin,'end':end},
			async:true,
			beforeSend:function ()
			{

			},
			complete:function ()
			{	
				
			},
			success: function (success_data) 
			{ 
			   $("#load_waiting").modal('hide');
			   success_data = success_data.trim();
			   console.log(success_data);
			   if((success_data!=="") && (success_data!==null))
			   {
				 try
				{
					var pill_comp_graph_data = JSON.parse(success_data);
					var graph    = pill_comp_graph_data.graph_data; 
					var start    = pill_comp_graph_data.start_date; 
					var end      = pill_comp_graph_data.end_date; 
					var schedule = pill_comp_graph_data.schedule;
					
					var obj = {'label':'Compliance Percentage','data':graph};
					pill_graph_values.push(obj);
					
		
					$.plot($("#chronic_line_graph"), pill_graph_values, {
					series: 
					{
					lines : {show: true},
					points: {show: true}
					},
					xaxis : {
					mode: 'time',
					tickSize: [2, "day"],
					axisLabel: "Days",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					axisLabelPadding: 20,
					min: start,
					max: end
					},
					grid: 
					{
					borderColor: 'black',
					borderWidth: 1
					},
					legend: 
					{
					show: true,
					container: '#compliance_legend'    
					},
					yaxis : {
					min: 0,
					max: 100,
					tickSize:25,
					axisLabel:"Percentage",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					axisLabelPadding: 10
					}
					});

					$('<div class="well pull-right compliance_schedule" style="margin-right:10px;">Schedule : '+schedule+'</div>').insertAfter('#compliance_legend');
				}
				catch(e)
				{
				
				} 
			   }
			   else
			   {
				 $("#chronic_line_graph").html("<center><label>No Data Available !</label></center>");
			   }
			}
		})
})

$(document).on("click",'#view_pill_compliance',function(e){

	$('.compliance_schedule').remove();
	
	var id_            = $('#chronic_id_list').val();
	var case_id        = $("#chronic_id_list option:selected").attr('case_id');
	var selected_month = $("#chronic_select_month").val();
	
	console.log(id_);
	console.log(case_id);
	console.log(selected_month);
	
	if(selected_month != "all_months")
	{
		var date = new Date();
		var month_no = getMonth(selected_month);
		var firstDay = new Date(date.getFullYear(), month_no-1, 1);
		var lastDay  = new Date(date.getFullYear(), month_no, 0);
		var begin    = firstDay.getFullYear()+'-'+(firstDay.getMonth()+1)+'-'+firstDay.getDate();
		var end      = lastDay.getFullYear()+'-'+(lastDay.getMonth()+1)+'-'+lastDay.getDate();
		
		
		pill_graph_values = [];
		
		// PILL COMPLIANCE GRAPH PLOT
		$.ajax
		({
			url  : 'prepare_pill_compliance_monthly_graph',
			type : 'POST',
			data :{'unique_id':id_,'case_id':case_id,'begin':begin,'end':end},
			async:true,
			beforeSend:function ()
			{

			},
			complete:function ()
			{	
				
			},
			success: function (success_data) 
			{ 
			   $("#load_waiting").modal('hide');
			   success_data = success_data.trim();
			   console.log(success_data);
			   if((success_data!=="") && (success_data!==null))
			   {
				 try
				{
					var pill_comp_graph_data = JSON.parse(success_data);
					var graph    = pill_comp_graph_data.graph_data; 
					var start    = pill_comp_graph_data.start_date; 
					var end      = pill_comp_graph_data.end_date; 
					var schedule = pill_comp_graph_data.schedule;
					console.log("graph",graph);
					
					var obj = {'label':'Compliance Percentage','data':graph};
					pill_graph_values.push(obj);
					
		
					$.plot($("#chronic_line_graph"), pill_graph_values, {
					series: 
					{
					lines : {show: true},
					points: {show: true}
					},
					xaxis : {
					mode: 'time',
					tickSize: [1, "day"],
					axisLabel: "Days",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					axisLabelPadding: 20,
					min: start,
					max: end
					},
					grid: 
					{
					borderColor: 'black',
					borderWidth: 1
					},
					legend: 
					{
					show: true,
					container: '#compliance_legend'    
					},
					yaxis : {
					min: 0,
					max: 100,
					tickSize:25,
					axisLabel:"Percentage",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					axisLabelPadding: 10
					}
					});

					$('<div class="well pull-right compliance_schedule" style="margin-right:10px;">Schedule : '+schedule+'</div>').insertAfter('#compliance_legend');
				}
				catch(e)
				{
				
				} 
			   }
			   else
			   {
				 $("#chronic_line_graph").html("<center><label>No Data Available !</label></center>");
			   }
			}
		})
	}
	else
	{
        pill_graph_values = [];
		
		// PILL COMPLIANCE GRAPH PLOT
		$.ajax
		({
			url  : 'prepare_pill_compliance_overall_graph',
			type : 'POST',
			data :{'unique_id':id_,'case_id':case_id},
			async:true,
			beforeSend:function ()
			{

			},
			complete:function ()
			{	
				
			},
			success: function (success_data) 
			{ 
			   $("#load_waiting").modal('hide');
			   success_data = success_data.trim();
			   console.log(success_data);
			   if((success_data!=="") && (success_data!==null))
			   {
				 try
				{
					var pill_comp_graph_data = JSON.parse(success_data);
					var graph    = pill_comp_graph_data.graph_data; 
					var start    = pill_comp_graph_data.start_date; 
					var end      = pill_comp_graph_data.end_date; 
					var schedule = pill_comp_graph_data.schedule; 
					console.log("graph",graph);
					
					var obj = {'label':'Compliance Percentage','data':graph};
					pill_graph_values.push(obj);
					
		
					$.plot($("#chronic_line_graph"), pill_graph_values, {
					series: 
					{
					lines : {show: true},
					points: {show: true}
					},
					grid: { hoverable: true, clickable: false },
					xaxis : {
					mode: "time",
					ticks: 12,
					timeformat:"%b %y",
					minTickSize: [1, "month"],
					axisLabel: "Months",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					axisLabelPadding: 20,
					min: start,
					max: end
				    },
					grid: 
					{
					borderColor: 'black',
					borderWidth: 1
					},
					legend: 
					{
					show: true,
					container: '#compliance_legend'    
					},
					yaxis : {
					min: 0,
					max: 100,
					tickSize:25,
					axisLabel:"Percentage",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					axisLabelPadding: 10
					}
					});

					$('<div class="well pull-right compliance_schedule" style="margin-right:10px;">Schedule : '+schedule+'</div>').insertAfter('#compliance_legend');
				}
				catch(e)
				{
				
				} 
			   }
			   else
			   {
				 $("#chronic_line_graph").html("<center><label>No Data Available !</label></center>");
			   }
			}
		}) 
	}
	
})

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
		
		

//===================================drill down pie======================
//===================================end of dril down pie================
</script>

