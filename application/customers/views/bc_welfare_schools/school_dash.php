<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Dashboard";

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
#compliance_legend
{
	background-color: #fff;
    padding: 2px;
    margin-bottom: 8px;
    border-radius: 3px 3px 3px 3px;
    border: 1px solid #E6E6E6;
    display: inline-block;
    margin: 0 auto;
}
.square{
	width:170px;
	height:40px;
}
.no_values{
	background-color:#00ffbf;
}
.normal{
	background-color:#00ff40;
}
.over{
	background-color:#ffff00;
}
.under{
	background-color:#ff0000;
}
.obese{
	background-color:#ff8000;
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

.news-item
{
    color:blue;
    //border-bottom:1px dotted #555; 
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
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<?php if($school_color_code == "No BMI values"):?>
				<p class="square no_values"><strong>No BMI values</strong></p>
				<?php endIf;?>
				<?php if($school_color_code == "under_weight"):?>
				<span  class="square under"><strong>More Under Weight cases</strong></span>
				<?php endIf;?>
				<?php if($school_color_code == "normal_weight"):?>
				<p class="square normal"><strong>Towards Healthy School</strong></p>
				<?php endIf;?>
				<?php if($school_color_code == "over_weight"):?>
				<p class="square over"><strong>More Over Weight cases</strong></p>
				<?php endIf;?>
				<?php if($school_color_code == "obese"):?>
				<p class="square obese"><strong>More Obese</strong></p>
				<?php endIf;?>
			</div>
		</div>
		
		
		
		
		<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<article class="col-xs-8 col-sm-8 col-md-12 col-lg-12">
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
								<span class="widget-icon"> <i class="fa fa-list-alt"></i> </span>
								<h2>News Feed </h2>

							</header>

							<!-- widget div-->
							<div class="no-padding">
								<!-- widget edit box -->
								<!-- end widget edit box -->

								<div class="widget-body">
									<!-- content -->
									<div id="myTabContent" class="tab-content">
									<div class="well well-sm well-light" id="news_feed_div" style="min-height: 245px;">
										<?php if(count($news_feeds) > 0): ?>
										<div class="panel panel-default">
										<div class="panel-body">
										<div class="row">
										<div class="col-xs-12">
										<ul class="demo1">
										<?php foreach ($news_feeds as $news_feed):?>
										
										<table >
										<tr>
										<td> <li style="list-style-type:none" class="news-item"><i class="fa fa-bell" style="font-size:20px;color:red"></i>&nbsp&nbsp <?php echo $news_feed["display_date"].' <i class="fa fa-lg fa-fa-calendar"></i> '.((strlen($news_feed["news_feed"])>=500)? substr($news_feed["news_feed"], 0,500)." <font size='2' color='blue'><i>cont...</i></font>" : $news_feed["news_feed"]) ;?> 
													
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
					</div>
					</article>
			</div>
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
							<span class="widget-icon"> <i class="glyphicon glyphicon-dashboard txt-color-darken"></i> </span>
							<h2>Health Requests Docs </h2>
                       
						</header>
                         	<!-- widget div-->
						<div>
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<div>
									<label>Title:</label>
									<input type="text" />
								</div>
							</div>
							<!-- end widget edit box -->

							<div class="widget-body no-padding smart-form" style="height:250px; overflow-y: scroll;">
								<!-- content goes here -->
								<h5 class="todo-group-title"><i class="fa fa-exclamation"></i> Awaiting Requests (<small class="num-of-tasks"><?php if(!empty($hs_req_docs)):?><?php echo count($hs_req_docs);?><?php else: ?><?php echo "0";?><?php endIF;?></small>)</h5>
								<ul id="" class="todo">
								<?php if(!empty($hs_req_docs)):?>
								<?php foreach($hs_req_docs as $doc):?>
								<li>
										<span class="handle"> 
										</span>
										<p>
											<strong><?php if(isset($doc['notification_param']['Unique ID'])):?><?php echo $doc['notification_param']['Unique ID'];?><?php else:?>"Notification Field"<?php endIF;?></strong> - <?php echo $doc['doc_received_time'];?>&nbsp;&nbsp;&nbsp;<a href="<?php echo URL.'bc_welfare_schools/access_request/'.$doc['doc_id'].'';?>" class="btn bg-color-greenDark txt-color-white btn-xs">Access</a>
										</p>
									</li>
							    <?php endforEach;?>
								<?php else: ?>
								<p> No docs found </p>
								<?php endIf;?>
								</ul>
							<!-- end content -->
							</div>

						</div>
						<!-- end widget div -->
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
													<button type="button" class="btn bg-color-greenDark txt-color-white btn-sm" id="refresh_screening_data" disabled="disabled">
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
								<div id="pie_absent"></div>
								<form style="display: hidden" action="drill_down_absent_to_students_load_ehr" method="GET" id="ehr_form_for_absent">
									<input type="hidden" id="ehr_data_for_absent" name="ehr_data_for_absent" value=""/>
								</form>
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
											<form style="display: hidden" action="drill_down_identifiers_to_students_load_ehr" method="GET" id="ehr_form_for_identifiers">
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
											<form style="display: hidden" action="drill_down_request_to_students_load_ehr" method="GET" id="ehr_form_for_request">
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
													<option value="Daily">Daily</option>
													<option value="Weekly">Weekly</option>
													<option value="Bi Weekly">Bi Weekly</option>
													<option selected value="Monthly">Monthly</option>
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
			<?php $message = $this->session->flashdata('message');?>
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
										<section class="col col-3">
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
										<section class="col col-6">
										<label class="label" for="item1">Note:- To get sanitation report, select a date and click the button</label>
													<button type="button" class="btn bg-color-pink txt-color-white btn-sm" id="set_date_sanitation_report" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
						                       Get Sanitation Report
						                    </button>
						                </section>
										</fieldset>
										</form>
									</div>
									<div>
									<br>
									<div id="sanitation_report_table" style="min-height:300px;max-height:300px;">
									<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									
									
									</div>
									
									</div>
								 <div id="legend-container" style="padding-left:10px;padding-top:10px;"></div>
									</div>
								</div>
								
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
			
				<!-- CHRONIC REPORT LINE GRAPH -->
			<!-- widget grid -->
		<section id="widget-grid" class="">
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
												<label class="label" for="item1">Select Student</label></div>
												<div class="input-group">
											<select id="chronic_id_list" class="form-control">
														<option value='select_id' >-- Select --</option>
														</select> <i></i>
												<span class="input-group-addon"><i class="fa fa-list"></i></span>
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
										<section class="col col-5">
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
								<div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
									
								<div id="sanitation_chart" class="row">
									
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

						<!--News Feed Modal -->
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
				
				
			</div><!-- end row -->
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
<script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.fillbetween.min.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.orderBar.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.pie.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.time.min.js"></script>
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

	var absent_data = "";
	previous_absent_a_value = [];
	previous_absent_title_value = [];
	previous_absent_search = [];
	absent_search_arr = [];

	var request_pie_span = "Monthly";
	var request_data = "";
	previous_request_a_value = [];
	previous_request_title_value = [];
	previous_request_search = [];
	search_arr = [];

	var identifiers_data = "";
	previous_identifiers_a_value = [];
	previous_identifiers_title_value = [];
	previous_identifiers_search = [];
	search_arr = [];

	var screening_pie_span = "Yearly";
	var screening_data = "";
	previous_screening_a_value = [];
	previous_screening_title_value = [];
	previous_screening_search = [];
	search_arr = [];
	
	var sanitation_infrastructure_input = "";
	var sanitation_report_input = "";
    var chronic_id_list = "";
	
	$("a[rel^='prettyPhoto']").prettyPhoto();
	
	<?php if($message) { ?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
				content : "<?php echo $message?>",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 8000
			});
<?php } ?>

$('.datepicker').datepicker({
	minDate: new Date(1900, 10 - 1, 25)
 });
 
initialize_variables(today_date,<?php echo $absent_report?>,<?php echo $request_report?>,<?php echo $symptoms_report?>,<?php echo $screening_report?>,<?php echo $sanitation_infra_data?>,<?php echo $chronic_ids;?>);

change_to_default();

function change_to_default(today_date,absent_report,request_report,symptoms_report,screening_report){
	$('#request_pie_span').val("Monthly");
	$('#screening_pie_span').val("Yearly");
}

/*******************************************************************
 *
 * Helper : Initialize dashboard pie's
 *
 */
 
function initialize_variables(today_date,absent_report,request_report,symptoms_report,screening_report,sanitation_infra_report,chronic_ids)
{
	console.log("sanitation_infra_report",sanitation_infra_report);
	today_date = today_date;
	
	init_absent_pie(absent_report);
	init_req_id_pie(request_report,symptoms_report);
	init_screening_pie(screening_report);
	init_sanitation_infrastructure(sanitation_infra_report);
    init_and_update_chronic_id_list(chronic_ids);
	load_sanitation_report();
}

/*******************************************************************
 *
 * Helper : Initialize for absent pie
 *
 */
 
function init_absent_pie(absent_report)
{
	absent_data 				= absent_report;
	previous_absent_a_value 	= [];
	previous_absent_title_value = [];
	previous_absent_search 		= [];
	absent_search_arr 			= [];
	console.log("absentttttttt",absent_data);
}

/*******************************************************************
 *
 * Helper : Initialize for request/identifiers pie
 *
 */
 
function init_req_id_pie(request_report,symptoms_report)
{
	request_data 			 	 = request_report;
	previous_request_a_value 	 = [];
	previous_request_title_value = [];
	previous_request_search 	 = [];
	search_arr 					 = [];
	
	identifiers_data 			     = symptoms_report;
	previous_identifiers_a_value     = [];
	previous_identifiers_title_value = [];
	previous_identifiers_search 	 = [];
	search_arr 						 = [];
}

/*******************************************************************
 *
 * Helper : Initialize for screening pie
 *
 */
 
function init_screening_pie(screening_report)
{
	screening_data 					= screening_report;
	screening_navigation            = [];
	previous_screening_a_value 		= [];
	previous_screening_title_value 	= [];
	previous_screening_search 		= [];
	search_arr 						= [];
}

/*******************************************************************
 *
 * Helper : Initialize for sanitation infrastructure table
 *
 */
 
function init_sanitation_infrastructure(sanitation_infra_data)
{
	sanitation_infrastructure_input = sanitation_infra_data;
}

/*******************************************************************
 *
 * Helper : Initialize for sanitation report table
 *
 */
 
function init_sanitation_report(sanitation_report)
{
	sanitation_report_input = sanitation_report;
}

/*******************************************************************
 *
 * Helper : Initialize and update id list for chronic case line graph
 *
 */
 
function init_and_update_chronic_id_list(chronic_ids)
{
	chronic_id_list = chronic_ids;
	var options = $("#chronic_id_list");
	options.empty();
	options.append($("<option />").val("select_id").prop("selected", true).text("--select--"));
	
	for(var i in chronic_id_list)
	{
       options.append($("<option />").val(chronic_id_list[i].student_unique_id).text(chronic_id_list[i].student_unique_id).attr('case_id',chronic_id_list[i].case_id).attr('months',chronic_id_list[i].scheduled_months));
	}
	
}

draw_absent_pie();
draw_request_pie();
draw_screening_pie();
draw_identifiers_pie();
draw_sanitation_infra_table();

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
			//console.log(request_report,"console================1210");
			symptoms_report = $.parseJSON(data.symptoms_report);
			//console.log(symptoms_report,"console================1213");
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
		console.log('update_screening_pie=', data);
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

	$.ajax({
		url: 'to_dashboard_with_date',
		type: 'POST',
		data: {"today_date" : today_date, "request_pie_span" : request_pie_span, "screening_pie_span" : screening_pie_span},
		success: function (data) {
			$('#load_waiting').modal('hide');
			$( "#pie_absent" ).empty();
			$( "#pie_request" ).empty();
			$( "#pie" ).empty();
			$( "#pie_screening" ).empty();
			
			data = $.parseJSON(data);
			absent_report = $.parseJSON(data.absent_report);
			request_report = $.parseJSON(data.request_report);
			symptoms_report = $.parseJSON(data.symptoms_report);
			screening_report = $.parseJSON(data.screening_report);
			news_feeds_data  = $.parseJSON(data.news_feeds);
			
			initialize_variables(today_date,absent_report,request_report,symptoms_report,screening_report);
			draw_absent_pie();
			draw_identifiers_pie();
			draw_request_pie();
			draw_screening_pie();

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

function load_sanitation_report()
{
	var selectedDate = $('#sanitation_report_date').val();
    console.log("selectedDate==",selectedDate);
	$.ajax({
		url    : 'fetch_sanitation_report_against_date',
		type   : 'POST',
		data   : {"date" : selectedDate},
		success: function (data) {
		    console.log("DATA==1129==",data);
			$('#load_waiting').modal('hide');
			if(data == 'NO_DATA_AVAILABLE')
			{
		      $('#sanitation_report_table').html('<center>No sanitation report data available !</center>');
			}
			else
			{
		       init_sanitation_report(data);
			   draw_sanitation_report_table();
			}
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	
}

$(document).on('click','#set_date_sanitation_report',function(e){
	
	var selectedDate = $('#sanitation_report_date').val();
    console.log("selectedDate==",selectedDate);
	$.ajax({
		url    : 'fetch_sanitation_report_against_date',
		type   : 'POST',
		data   : {"date" : selectedDate},
		success: function (data) {
		    console.log("DATA==1129==",data);
			$('#load_waiting').modal('hide');
			if(data == 'NO_DATA_AVAILABLE')
			{
		      $('#sanitation_report_table').html('<center>No sanitation report data available !</center>');
			}
			else
			{
		       init_sanitation_report(data);
			   draw_sanitation_report_table();
			}
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	
});

/*******************************************************************
 *
 * Helper : Absent Pie
 *
 *
 */
 
function absent_pie(heading, data, onClickFn)
{
	var pie = new d3pie("pie_absent", {
		header: {
			title: {
				text: heading
			}
		},
		size: {
	        canvasHeight: 250,
	        canvasWidth : 400
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
				
					if(onClickFn == "drill_down_absent_to_students")
					{
						drill_down_absent_to_students(a.data.label);
					}
					
				}
			}
	      
		});
}

/*******************************************************************
 *
 * Helper : Draw absent pie
 *
 *
 */
 
function draw_absent_pie()
{
	if(absent_data == 1)
	{
		$("#pie_absent").append('No positive values to dispaly');
	}
	else if(absent_data == 2)
	{
		$("#pie_absent").append('<p  class="alert alert-info"><strong><i style="font-size: initial";>Attendance Details submitted, but no issues found('+" "+today_date+')</i></strong></p>');
	}
	else
	{
	   absent_pie(today_date,absent_data,"drill_down_absent_to_students");
	}
}

/*******************************************************************
 *
 * Helper : Absent Pie - drill to students
 *
 *
 */
 
function drill_down_absent_to_students(label)
{
    $.ajax({
		url: 'drill_down_absent_to_students',
		type: 'POST',
		data: {"label" : label, "today_date" : today_date},
		success: function (data) 
		{
			$("#ehr_data_for_absent").val(data);
			$("#ehr_form_for_absent").submit();
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
		}
	});
}

/*******************************************************************
 *
 * Helper : Identifiers Pie
 *
 *
 */
 
function identifiers_pie(heading, identifiers_data, onClickFn){
	var pie = new d3pie("pie", {
		header: {
			title: {
				text: heading
			}
		},
		size: {
	        canvasHeight: 250,
	        canvasWidth : 400
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
					
					if(onClickFn == "drill_down_identifiers_to_students"){
					    
						drill_down_identifiers_to_students(a.data.label);
					
						
					}
					
				}
			}
	});
}

/*******************************************************************
 *
 * Helper : Draw identifiers pie
 *
 * @author  Vikas
 *
 */
 
function draw_identifiers_pie()
{
	if(identifiers_data == 1)
	{
		console.log('in false of identifiers');
		$("#pie").append('No positive values to dispaly');
	}
	else
	{
		identifiers_pie("Identifiers Pie Chart",identifiers_data,"drill_down_identifiers_to_students");
	}
}

/*******************************************************************
 *
 * Helper : Identifiers Pie - drill to students
 *
 * @author  Vikas
 *
 */
 
function drill_down_identifiers_to_students(identifier)
{
	$.ajax({
		url: 'drill_down_identifiers_to_students',
		type: 'POST',
		data: {"identifier" : identifier, "today_date" : today_date, "request_pie_span" : request_pie_span},
		success: function (data) 
		{
		    $("#ehr_data_for_identifiers").val(data);
			$("#ehr_form_for_identifiers").submit();
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
		}
	});
}

/*******************************************************************
 *
 * Helper : Request Pie
 *
 *
 */
 
 function request_pie(heading, request_data, onClickFn)
 {
	var pie = new d3pie("pie_request", {
		header: {
			title: {
				text: heading
			}
		},
		size: {
	        canvasHeight: 250,
	        canvasWidth : 400
	    },
	    labels: {
	        inner: {
	            format: "value"
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
				    console.log(a);
				    console.log(onClickFn);
					if (onClickFn == "drill_down_request_to_students"){
						drill_down_request_to_students(a.data.label.trim());
					}
				}
			}
	      
	});
}

/*******************************************************************
 *
 * Helper : Draw request pie
 *
 *
 */
 
function draw_request_pie()
{
	if(request_data == 1)
	{
		$("#pie_request").append('No positive values to dispaly');
	}
	else
	{
		console.log(request_data,"requesttttttttttt");
		request_pie("Request Pie Chart",request_data,"drill_down_request_to_students");
	}
}

/*******************************************************************
 *
 * Helper : Request Pie - drill to students
 *
 *
 */
 
function drill_down_request_to_students(request_label)
{
    console.log(request_label,"request_label========1626");
	$.ajax({
		url: 'drill_down_request_to_students',
		type: 'POST',
		data: {"data" : request_label, "today_date" : today_date, "request_pie_span" : request_pie_span},
		success: function (data) 
		{
		    console.log(data);
			$("#ehr_data_for_request").val(data);
			$("#ehr_form_for_request").submit();
			
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
		}
	});
}

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

/*******************************************************************
 *
 * Helper : Screening Pie
 *
 */
 
function screening_pie(heading, screening_data, onClickFn){
	var pie = new d3pie("pie_screening", {
		header: {
			title: {
				text: heading
			}
		},
		size: {
	        canvasHeight: 250,
	        canvasWidth: 400
	    },
	    data: {
	      content: screening_data
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
					
					if(onClickFn == "drill_down_screening_to_abnormalities"){
						console.log(a);
						previous_screening_a_value[1] = screening_data;
						previous_screening_title_value[1] = "Screening Pie Chart";
						console.log(previous_screening_a_value);
						drill_down_screening_to_abnormalities(a);
					}else if(onClickFn == "drill_down_screening_to_students"){
						
						search_arr[0] =  a.data.label;
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						console.log("drill_down_screening_to_students==1060",search_arr);
						drill_down_screening_to_students(search_arr);
					}else{
						index = onClickFn;
						
						if(index == 1){
							drill_down_screening_to_abnormalities(a);
						}else if (index == 2){
							search_arr[0] = previous_screening_search[3];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_screening_to_students(search_arr);
						}
					}
					
				}
			}
	      
	});
}

/*******************************************************************
 *
 * Helper : Draw screening pie
 *
 */
 
function draw_screening_pie()
{
	if(screening_data == 1)
	{
		$("#pie_screening").append('No positive values to dispaly<br><br>');
	}
	else
	{
		screening_pie("Screening Pie Chart", screening_data, "drill_down_screening_to_abnormalities");
	}
}

/*******************************************************************
 *
 * Helper : Screening Pie - drill to abnormalities
 *
 *
 */
 
function drill_down_screening_to_abnormalities(pie_data)
{
	
	$.ajax({
		url: 'drilling_screening_to_abnormalities',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data), "today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {
			console.log(data);
			var content = $.parseJSON(data);
			console.log(content);
			$("#pie_screening").empty();
			$("#pie_screening").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "1"> Back </button>');
			screening_navigation.push(pie_data.data.label);
			screening_pie(pie_data.data.label, content, "drill_down_screening_to_students");
			
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
		}
	});
}

/*******************************************************************
 *
 * Helper : Screening Pie - drill to students
 *
 *
 */
 
function drill_down_screening_to_students(pie_data)
{

	$.ajax({
		url : 'drill_down_screening_to_students',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "screening_pie_span" : screening_pie_span},
		success: function (data) {
			$("#ehr_data").val(data);
			screening_navigation.push(pie_data[1]);
			$("#ehr_navigation").val(screening_navigation.join(" / "));
			
			$("#ehr_form").submit();
			
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
		});
}

/*******************************************************************
 *
 * Helper : Screening Pie - back button functionality
 *
 *
 */
 
$(document).on("click",'#screening_back_btn',function(e){
	
	var index = $(this).attr("ind");
	$( "#pie_screening" ).empty();
	
	if(index>1)
	{
		var ind = index - 1;
		$("#pie_screening").append('<button class="btn btn-primary pull-right" id="screening_back_btn" ind= "' + ind + '"> Back </button>');
	}
	
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

/*******************************************************************
 *
 * Helper : Sanitation Infrastructure 
 *
 *
 */
 
function draw_sanitation_infra_table()
{
	   if(sanitation_infrastructure_input != 1)	
	   {			   
		  //result = $.parseJSON(sanitation_infrastructure_input);
		  result = sanitation_infrastructure_input;
		  
		  $('#sanitation_chart').html('<div id="toilets_graph" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="hand_sanitizers_graph" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="disposable_bins_graph" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="water_dispensaries_graph" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="children_seating_graph" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div></div>');
		  
		  $('.sanitation_infra_note').remove();
		 
		  var toilets            = result.toilets;
		  var hand_sanitizers    = result.hand_sanitizers;
		  var disposable_bins    = result.disposable_bins;
		  var water_dispensaries = result.water_dispensaries;
		  var children_seating   = result.children_seating;
		  
	
		// toilets
		toilets = $.parseJSON(toilets);
			if ($("#toilets_graph").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>'
				for (var item in toilets) {
				  if (toilets.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+toilets[item].label+'</td><td>'+toilets[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#toilets_graph").html(table)
			}
			
			$('#toilets_graph').prepend('<div class="">Toilets</div>');
			
			// hand sanitizers
			hand_sanitizers = $.parseJSON(hand_sanitizers);
			if ($("#hand_sanitizers_graph").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>'
				for (var item in hand_sanitizers) {
				  if (hand_sanitizers.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+hand_sanitizers[item].label+'</td><td>'+hand_sanitizers[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#hand_sanitizers_graph").html(table)
			}
			
			$('#hand_sanitizers_graph').prepend('<div class="spec">Hand Sanitizers</div>');
			
			// disposable bins
			disposable_bins = $.parseJSON(disposable_bins);
			if ($("#disposable_bins_graph").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>'
				for (var item in disposable_bins) {
				  if (disposable_bins.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+disposable_bins[item].label+'</td><td>'+disposable_bins[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#disposable_bins_graph").html(table)
			}
			
			$('#disposable_bins_graph').prepend('<div class="spec">Disposable Bins in</div>');
			
			// water dispensaries
			water_dispensaries = $.parseJSON(water_dispensaries);
			if ($("#water_dispensaries_graph").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>'
				for (var item in water_dispensaries) {
				  if (water_dispensaries.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+water_dispensaries[item].label+'</td><td>'+water_dispensaries[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#water_dispensaries_graph").html(table)
			}
			
			$('#water_dispensaries_graph').prepend('<div class="spec">Water Dispensaries</div>');
			
			// children seating
			children_seating = $.parseJSON(children_seating);
			if ($("#children_seating_graph").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody>'
				for (var item in children_seating) {
				  if (children_seating.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+children_seating[item].label+'</td><td>'+children_seating[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#children_seating_graph").html(table)
			}
				
		$('#children_seating_graph').prepend('<div class="spec">Children sit on</div>');
	
	   }
	   else
	   {
		   $('.sanitation_infra_note').remove();
		   $('#sanitation_chart').html('<br><center><label id="sanitation_infra_note">No sanitation infrastructure data available</label></center>');
	   }
	
}

/*******************************************************************
 *
 * Helper : Sanitation Report 
 *
 *
 */
 
function draw_sanitation_report_table()
{
	   if(sanitation_report_input != 1)	
	   {			   
		  result = $.parseJSON(sanitation_report_input);
		  
		  $('#sanitation_report_table').html('<div id="handwash" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="kitchen" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="cleanliness" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="food" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="waste_management" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div id="external_files" class="chart col-xs-12 col-sm-2 col-md-2 col-lg-2"></div><div></div>');
		 
		  var handwash           = result.handwash;
		  var kitchen            = result.kitchen;
		  var cleanliness        = result.cleanliness;
		  var food 				 = result.food;
		  var waste_management   = result.waste_management;
		  var external_files     = result.external_attachments;
		  
		  console.log("external_files==>",external_files);
		  
	
		// hand wash
		handwash = $.parseJSON(handwash);
			if ($("#handwash").length) {
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in handwash) {
				  if (handwash.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+handwash[item].label+'</td><td>'+handwash[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#handwash").html(table)
			}
			
			$('#handwash').prepend('<div class="">HandWash</div>');
			
			// kitchen
			kitchen = $.parseJSON(kitchen);
			if ($("#kitchen").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in kitchen) {
				  if (kitchen.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+kitchen[item].label+'</td><td>'+kitchen[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#kitchen").html(table)
			}
			
			$('#kitchen').prepend('<div class="spec">Kitchen</div>');
			
			// cleanliness
			cleanliness = $.parseJSON(cleanliness);
			if ($("#cleanliness").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in cleanliness) {
				  if (cleanliness.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+cleanliness[item].label+'</td><td>'+cleanliness[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#cleanliness").html(table)
			}
			
			$('#cleanliness').prepend('<div class="spec">Cleanliness</div>');
			
			// water dispensaries
			food = $.parseJSON(food);
			if ($("#food").length) {
				
				var table = '<div style="overflow-y: auto; height:200px;" ><table class="table table-bordered"><thead><tr><th>Item</th><th>Value</th></tr></thead><tbody>'
				for (var item in food) {
				  if (food.hasOwnProperty(item)) {
				  table = table + '<tr><td>'+food[item].label+'</td><td>'+food[item].value+'</td></tr>'
				  }
				}
				table = table + '</tbody></table></div>';
				
				$("#food").html(table)
			}
			
			$('#food').prepend('<div class="spec">Food</div>');
			
			// waste management
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
				
		$('#waste_management').prepend('<div class="spec">Waste Management</div>');
		
		// external files
		external_files = $.parseJSON(external_files);
		console.log(external_files);	
		var table = '<div style="overflow-y: auto; height:200px;" ><table class=" table table-bordered"><thead><tr><th>Attachments <span class="attach_count badge bg-color-blueDark txt-color-white"></span></th></tr></thead><tbody>'
		var length = Object.keys(external_files).length;
		if(length > 0)
		{
		for(var item in external_files)
		{
	      table = table + '<tr><td><a href="<?php echo URLCustomer;?>'+external_files[item].file_path+'" rel="prettyPhoto[gal]">'+external_files[item].file_client_name+'</a></td></tr>'
		  
		}
		}
		else
		{
	      table = table + '<tr><td>No attachments </td></tr>'
		}
		
		table = table + '</tbody></table></div>';
		
		$("#external_files").html(table)
		$('.attach_count').text(length);
		
		$("a[rel^='prettyPhoto']").prettyPhoto();
			
	
	   }
	   else
	   {
		   $('#sanitation_report_table').html('<br><center><label id="sanitation_report_note">No sanitation report data available</label></center>');
	   }
	
}

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
			async:false,
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
			data :{'unique_id':id_,'case_id':case_id,'begin':begin,'end':end},
			async:false,
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
					grid: { hoverable: true, clickable: false },
					xaxis : {
					mode: "time",
					ticks: 12,
					timeformat:"%b %Y",
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
	
	$.ajax({
		url    : 'fetch_sanitation_report_against_date',
		type   : 'POST',
		data   : {"date" : prev_date_formatted},
		success: function (data) {
			$('#load_waiting').modal('hide');
			if(data == 'NO_DATA_AVAILABLE')
			{
		      $('#sanitation_report_table').html('<center>No sanitation report data available !</center>');
			}
			else
			{
		       init_sanitation_report(data);
			   draw_sanitation_report_table();
			}
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
		}
	});
})

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
		
	// set date
	$('#sanitation_report_date').val(next_date_formatted);
	
	$.ajax({
		url    : 'fetch_sanitation_report_against_date',
		type   : 'POST',
		data   : {"date" : next_date_formatted},
		success: function (data) {
			$('#load_waiting').modal('hide');
			if(data == 'NO_DATA_AVAILABLE')
			{
		      $('#sanitation_report_table').html('<center>No sanitation report data available !</center>');
			}
			else
			{
		       init_sanitation_report(data);
			   draw_sanitation_report_table();
			}
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
		}
	});
	
})

$(document).on('click','.open_news',function(){
		//alert("newssssssssssssssssssssssssssssssssssss")
		var news_data = $(this).attr("news_data");
		console.log(news_data);
		news_obj = JSON.parse(atob(news_data))
		console.log(news_obj);
		var news_details = '<p><h3>Time:</h3>'+news_obj.display_date+'</p><p style="word-wrap: break-word;"><h3>News:</h3>'+news_obj.news_feed+'</p>';
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

