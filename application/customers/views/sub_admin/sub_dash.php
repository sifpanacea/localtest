<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = lang('title');

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["home"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<style>
.txt-color-bluee
{
color:#214e75;!important
}
</style>
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
	<?php foreach($plan_details as $plan):?>
	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> <?php echo lang('admin_dash_home');?> <span>> <?php echo lang('admin_dash_board');?></span></h1>
			</div>
			<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
				<ul id="sparks" class="">
					<li class="sparks-info">
						<h5><?php echo lang('admin_dash_sub');?><span class="txt-color-blue"><div id="subdaysleft"><?php echo $dayss;?></div></span></h5>
						<!--<div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
							1300, 1877, 2500, 2577, 2000, 2100, 3000, 2700, 3631, 2471, 2700, 3631, 2471
						</div>-->
					</li>
					<li class="sparks-info">
						<h5> <?php echo lang('admin_dash_paper');?> <span class="txt-color-purple"><i class="fa fa-arrow-circle-up"></i>&nbsp;<?php if(!empty($papersavedcount)) {?><?php echo $papersavedcount;?><?php } else { ?><?php echo "0";}?></span></h5>
						<!--<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
							110,150,300,130,400,240,220,310,220,300, 270, 210
						</div>-->
					</li>
					<li class="sparks-info">
						<h5> <?php echo lang('admin_dash_tree');?> <span class="txt-color-greenDark"><i class="glyphicon glyphicon-tree-deciduous"></i>&nbsp;<?php if(!empty($treesavedcount)) {?><?php echo $treesavedcount;?><?php } else { ?><?php echo "0";}?></span></h5>
						<!--<div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm">
							110,150,300,130,400,240,220,310,220,300, 270, 210
						</div>-->
					</li>
				</ul>
			</div>
			
		</div>
		
		<!-- widget grid -->
		<section id="widget-grid" class="">
		<div class="row">
				<article class="col-sm-12">
					<!-- new widget -->
					<div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
						
						<header>
							<span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
							<h2><?php echo lang('admin_dash_live');?> </h2>
							<ul class="nav nav-tabs pull-right in" id="myTab">
								<li class="active">
									<a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet"><?php echo lang('admin_dash_live_stat');?></span></a>
								</li>

								<li>
									<a data-toggle="tab" href="#s2"><i class="fa fa-save"></i> <span class="hidden-mobile hidden-tablet"><?php echo lang('admin_dash_saved');?></span></a>
								</li>
							</ul>

						</header>

						<!-- widget div-->
						<div class="no-padding">
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">

								test
							</div>
							<!-- end widget edit box -->

							<div class="widget-body">
								<!-- content -->
								<div id="myTabContent" class="tab-content">
									<!-- start s1 tab pane --><div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1">
										<div class="row no-space">
											<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
												<span class="demo-liveupdate-1"> <span class="onoffswitch-title"><?php echo lang('admin_dash_live_swtich');?></span> <span class="onoffswitch">
														<input type="checkbox" name="start_interval" class="onoffswitch-checkbox" id="start_interval">
														<label class="onoffswitch-label" for="start_interval"> 
															<span class="onoffswitch-inner" data-swchon-text="<?php echo lang('admin_dash_live_swtich_on');?>" data-swchoff-text="<?php echo lang('admin_dash_live_swtich_off');?>"></span> 
															<span class="onoffswitch-switch"></span> </label> </span> </span>
												<div id="updating-chart" class="chart-large txt-color-blue"></div>

											</div>
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 show-stats">

												<div class="row">
												<div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> Applications <span class="pull-right"><?php if(!empty($appscnt)) {?><?php echo count($appscnt);?><?php } else {?><?php echo "0";?><?php }?>/<?php echo $plan->total_apps;?></span> </span>
														
														<div class="progress">
														<?php if(!empty($appscnt)){ ?><?php $acnt = count($appscnt);?><?php } else {?><?php $acnt="0";?><?php }?>
														   <?php $apppercent = intval($acnt/$plan->total_apps * 100); ?>
															<div class="progress-bar bg-color-greenLight" style="width:<?php echo $apppercent."%";?>"></div>
														</div> </div><br><br><br>
												<div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> Document Submissions <span class="pull-right"><?php if(!empty($docs)) {?><?php echo count($docs);?><?php } else {?><?php echo "0";?><?php }?>/<?php echo $plan->total_docs;?></span> </span>
														
														<div class="progress">
														   <?php $docpercent = intval(count($docs)/$plan->total_docs * 100); ?>
															<div class="progress-bar bg-color-orange" style="width:<?php echo $docpercent."%";?>"></div>
														</div> </div><br><br>
														<?php $apicount = count($api);?>
												<div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> Third Party Subscriptions <span class="pull-right"><?php if(!empty($api)) {?><?php echo count($api);?><?php } else {?><?php echo "0";?><?php }?>/<?php echo $plan->third_party;?></span> </span>
														
														<div class="progress">
														<?php if(!empty($api)){ ?><?php $apicnt = count($api);?><?php } else {?><?php $apicnt="0";?><?php }?>
														<?php $thirdpartypercent = intval($apicnt/$plan->third_party * 100); ?>
															<div class="progress-bar bg-color-magenta" style="width:<?php echo $thirdpartypercent."%";?>"></div>
														</div> </div><br><br>
												   <!--<div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> Total Documents<span class="pull-right"><span class="badge bg-color-red"><?php if(!empty($docs)) {?><?php echo count($docs);?><?php } else {?><?php echo "0";?><?php }?></span></span> </span>
												   </div><br><br>-->
												   
												<div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> Finished Workflows<span class="pull-right"><span class="badge bg-color-green"><?php echo $numberoffinished;?></span></span></span> </span>
												   </div><br><br>
												   
												   <div class="col-xs-6 col-sm-6 col-md-12 col-lg-12"> <span class="text"> Ongoing Workflows<span class="pull-right"><span class="badge bg-color-red"><?php echo $numberofunfinished;?></span></span></span> </span>
												   </div><br><br>
                                                   
													<div class="col-xs-12 col-sm-6 col-md-12 col-lg-12 hide">
												<span class="show-stat-buttons"> <span class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> <a href="javascript:void(0);" class="btn btn-default btn-block hidden-xs">Generate PDF</a> </span></span>
											</div>
                                                <?php endforeach;?>
												</div>

											</div>
										</div>

										<div class="show-stat-microcharts">
											<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                <span class="easy-pie-title"> Applications </span>
												<div class="easy-pie-chart txt-color-greenLight" data-percent="<?php echo $apppercent;?>" data-pie-size="50">
												<span class="percent percent-sign"><?php echo $apppercent;?></span>
												</div>
												
											</div>
											<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                <span class="easy-pie-title"> Documents </span>
												<div class="easy-pie-chart txt-color-blue" data-percent="<?php echo $docpercent;?>" data-pie-size="50">
												<span class="percent percent-sign"><?php echo $docpercent;?></span>
												</div>
												<div class="sparkline txt-color-greenLight hidden-sm hidden-md pull-right" data-sparkline-type="line" data-sparkline-height="33px" data-sparkline-width="70px" data-fill-color="transparent">
													<?php echo $docgraphs; ?>
												</div>
											</div>
											<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                <span class="easy-pie-title"> Third Party </span>
												<div class="easy-pie-chart txt-color-orangeDark" data-percent="<?php echo $thirdpartypercent;?>" data-pie-size="50">
												<span class="percent percent-sign"><?php echo $thirdpartypercent;?></span>
												</div>
												
											</div>
											<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                <span class="easy-pie-title"> App Usage</span>
												<div class="sparkline txt-color-darken hidden-sm hidden-md pull-right" data-sparkline-type="line" data-sparkline-height="33px" data-sparkline-width="70px" data-fill-color="transparent">
                                              
                                              <?php echo $appgraphs; ?>
												</div>
												
											</div>
											<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
											<?php $dbpercent = intval($dbsize/$plan->disk_space * 100); ?>
                                                <span class="easy-pie-title"> Disk Space</span>
												<span class="label bg-color-darken pull-right"><p>Used <?php echo $dbsize;?> GB</p></span>
												<span class="label bg-color-blue pull-right"><p>Total <?php echo $plan->disk_space;?></p></span>
												<div class="easy-pie-chart txt-color-red" data-percent="<?php echo $dbpercent;?>" data-pie-size="50">
												<span class="percent percent-sign"><?php echo $dbpercent;?></span>
												</div>
												
											</div>
								
										</div>

									</div><!-- end s1 tab pane -->
									
									
                                    <!-- start s2 tab pane --><div class="tab-pane fade" id="s2">
										<div class="row no-space">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											   <?php if ($analytics): ?>
												<table class="table table-bordered">
												
									<thead>
										<tr>
										    <th>Application Name</th>
											<th>Title</th>
											<th>Description</th>
											<th>Action</th>
										</tr>
									</thead>
									<?php $a = 0;?>
					                <?php foreach ($analytics as $app):?>
									<tbody>
										<tr>
										    <td><?php echo $app['app_name'];?></td>
											<td><?php echo $app['title'];?></td>
											<td><?php echo $app['description'];?></td>
											<td><button class="btn btn-success btn-xs query_sub_admin" id="<?php echo $app['app_id']; ?>" pattern="<?php echo base64_encode($app['pattern']); ?>"><?php echo lang('query_pattern');?></button>
											<span id="deletequery">
											<a class='ldelete' href='<?php echo URL."sub_admin/delete_saved_pattern/".$app['_id'];?>'>
											<button class="btn btn-danger btn-xs query"><?php echo lang('query_pattern_delete');?></button>
											</a>
											</span>
											</td>
										</tr>
									</tbody>
									<?php $a++;?>
					                <?php endforeach;?>
					                <?php else: ?>
        			                 <p>
          				           <?php echo lang('admin_no_saved_patterns');?>
        			                  </p>
        			               <?php endif ?>
								</table>
											</div>
										</div>
										</div><!-- end s2 tab pane -->
									
								</div>

								<!-- end content -->
							</div>

						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->
                 
				</article>
				</div>
			<!-- row -->
<div class="row">
		
				<!-- NEW COL START -->
				<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
		
					<!-- Widget ID (each widget will need unique ID)-->
					
					<!-- end widget -->
		
				<div class="jarviswidget jarviswidget-sortable" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget" style="">
						
						<header role="heading"><div class="jarviswidget-ctrls" role="menu">   <a href="#" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-resize-full "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="fa fa-times"></i></a></div>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>Feedbacks </h2>
		
						<span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
		
						<!-- widget div-->
						<div role="content">
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body no-padding">
		
<table class="table table-striped table-hover table-condensed">
									
									<!--for loop-->
										
										
										<thead>
										<tr>
											<th>Feedback Name</th>
											<th>Total Users</th>
											<th class="text-align-center">Replied Users</th>
											<th class="text-align-center">Description</th>
											<th class="text-align-center">Feedback Summary</th>
										</tr>
										</thead>
										<tbody class="feedbacks">
					
									</tbody>
									
								</table>
		<div class="dt-row dt-bottom-row"><div class="row"><div class="col-sm-6"><button  id="previous_feedback" class="btn btn-xs btn-default txt-color-bluee">&larr; Previous</button>
            <lable>Page <lable id="page_number_feedback"></lable> of <lable id="total_page_feedback"></lable></lable>
            <button  id="next_feedback" class="btn btn-xs btn-default txt-color-bluee">Next &rarr;</button></div></div></div>
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div></article>
				<!-- END COL -->
		
				<!-- NEW COL START -->
				<article class="col-sm-12 col-md-12 col-lg-6 sortable-grid ui-sortable">
		
					<!-- Widget ID (each widget will need unique ID)-->
					
					<!-- end widget -->
		
				<div class="jarviswidget jarviswidget-sortable" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget" style="">
						
						<header role="heading"><div class="jarviswidget-ctrls" role="menu">   <a href="#" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-resize-full "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="fa fa-times"></i></a></div>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>Events </h2>
		
						<span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
		
						<!-- widget div-->
						<div role="content">
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body no-padding">
		
								<table class="table table-striped table-hover table-condensed">
									
									
									
									<thead>
										<tr>
											<th>Event Name</th>
											<th>Total users</th>
											<th class="text-align-center">Confirmed users</th>
											<th class="text-align-center">Event Time</th>
											<th class="text-align-center">Event Summary</th>
										</tr>
									</thead>
									<tbody class="events_ajax">
					
										<!--end-->
									</tbody>
									
								</table>
								<div class="dt-row dt-bottom-row"><div class="row"><div class="col-sm-6"><button  id="previous" class="btn btn-xs btn-default txt-color-bluee">&larr; Previous</button>
            <lable>Page <lable id="page_number"></lable> of <lable id="total_page"></lable></lable>
            <button  id="next" class="btn btn-xs btn-default txt-color-bluee">Next &rarr;</button></div></div></div>
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div></article>
				<!-- END COL -->
		
			</div>
			
			<!-- end row -->
			
			<div class="row">
			<article class="col-sm-12 col-md-12 col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					
					<!-- end widget -->
		
				<div class="jarviswidget jarviswidget-sortable" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget" style="">
						
						<header role="heading"><div class="jarviswidget-ctrls" role="menu">   <a href="#" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-resize-full "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="fa fa-times"></i></a></div>
							<span class="widget-icon"> <i class="fa fa-crop"></i> </span>
							<h2>Analytics </h2>
		
						<span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
		
						<!-- widget div-->
						<div role="content">
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body no-padding">
							<!-- content goes here -->
								<div id="queryres1"></div>
								<div id="analytics_query"></div>
								<span id="ajaxdata"></span>
								<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
													&times;
												</button>
												<h4 class="modal-title" id="myModalLabel">Save Pattern</h4>
											</div>
											<div class="modal-body">
								
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<input type="text" id="pattern_title" class="form-control" placeholder="Title" required />
														</div>
														<div class="form-group">
															<textarea class="form-control" id="pattern_description" placeholder="Content" rows="5" required></textarea>
														</div>
													</div>
												</div>
											  </div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">
													Cancel
												</button>
												<button type="button" id="pattern123" class="btn btn-primary">
													Save
												</button>
											</div>
										</div><!-- /.modal-content -->
									</div><!-- /.modal-dialog -->
								</div><!-- /.modal -->
								<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
												&times;
											</button>
											<h4 class="modal-title" id="myModalLabel">Save Pattern</h4>
										</div>
										<div class="modal-body">
										<?php
												$attributes = array('class' => 'smart-form');
												echo  form_open('sub_admin/savepattern',$attributes);
												?>
													<!--<form class="smart-form">-->
														<fieldset>
														<section>
															<label class="label" for="pattern_title"><?php echo lang('pattern_title');?></label>
																<label class="input"> <i class="icon-append fa fa-pencil"></i>
																<input type="text" name="pattern_title" id="pattern_title" placeholder="Title">
															</label>
														</section>
														<section>
															<label class="label"><?php echo lang('pattern_description');?></label>
																<label class="textarea textarea-expandable"> <i class="icon-append fa fa-pencil"></i>
																<textarea rows="3" id="pattern_description" name="pattern_description" placeholder="Please be brief" required></textarea>
															</label>
														</section>
														</fieldset>
														<footer>
															<button type="submit" class="btn bg-color-greenDark txt-color-white">
																Submit
															</button>
															<button type="button" class="btn btn-default" data-dismiss="modal">
															   Cancel
															</button>
														</footer>
														<input type="hidden" id="app_id" name="id"/>
														<input type="hidden" id="app_name" name="name"/>
														<input type="hidden" id="save_query" name="saved_query"/>
														</form>
										</div>
									</div>
								</div>
							</div>
								<button class="btn bg-color-greenDark txt-color-white pull-right" id="pattern" data-toggle="modal" data-target="#myModal" style="margin-bottom: 10px;/* display: block; */margin-right: 10px;">Save Query Pattern</button>
		
<table class="table table-striped table-hover table-condensed analytics_table">
									
									<!--for loop-->
										
										
										
										<tbody class="analytics">
					
									</tbody>
									
								</table>
		<div class="dt-row dt-bottom-row analytics_row"><div class="row"><div class="col-sm-6"><button  id="previous_analytics" class="btn btn-xs btn-default txt-color-bluee">&larr; Previous</button>
            <lable>Page <lable id="page_number_analytics"></lable> of <lable id="total_page_analytics"></lable></lable>
            <button  id="next_analytics" class="btn btn-xs btn-default txt-color-bluee">Next &rarr;</button></div></div></div>
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div></article>
				<!-- END COL -->
			</div>
			
			<!-- end row -->
			
			<!--  Item based analyticssssssssssssssssssssssssssssssssssssssssssssssssssssss -->
			
			<div class="row">
			<article class="col-sm-12 col-md-12 col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					
					<!-- end widget -->
		
				<div class="jarviswidget jarviswidget-sortable" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" role="widget" style="">
						
						<header role="heading"><div class="jarviswidget-ctrls" role="menu">   <a href="#" class="button-icon jarviswidget-toggle-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Collapse"><i class="fa fa-minus "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-fullscreen-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Fullscreen"><i class="fa fa-resize-full "></i></a> <a href="javascript:void(0);" class="button-icon jarviswidget-delete-btn" rel="tooltip" title="" data-placement="bottom" data-original-title="Delete"><i class="fa fa-times"></i></a></div>
							<span class="widget-icon"> <i class="fa fa-tachometer"></i> </span>
							<h2>Item based analytics </h2>
		
						<span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
		
						<!-- widget div-->
						<div role="content">
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body no-padding">
							<!-- content goes here -->
							<div class="col-xs-12 col-sm-2 col-md-2 col-lg-1">
								<!-- input: search field -->
								<div id='item_analytics'>
								<input type="text" name="param" placeholder="Enter an item to analyze..!" id="tiem_search-fld">
								<button id="itemanalytics" type="submit">
									<i class="fa fa-search"></i>
								</button>
								<a href="javascript:void(0);" id="cancel-search-js" title="Cancel Search"><i class="fa fa-times"></i></a>
								<!-- end input: search field -->
								</div>
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div></article>
				<!-- END COL -->
			</div>
			
			<!-- end row -->
			
			<!-- Item based analyticsssssssssssssssssssssssssssssssssssssssssssssssss -->
				

			

			</section>
			<div class="hide printing" id="pdf"></div>
	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
			

<!-- ==========================CONTENT ENDS HERE ========================== -->
<input type='hidden' id='queryapp' value='<?php echo set_value('queryapp', (isset($template->app_template)) ? json_encode($template->app_template) : ''); ?>' /><input type='hidden' id='queryid' value='<?php echo set_value('queryid', (isset($template->_id)) ? ($template->_id) : ''); ?>' /><input type='hidden' id='appname' value='<?php echo set_value('appname', (isset($template->app_name)) ? ($template->app_name) : ''); ?>' /><input type="hidden" id="get_pattern"/>
<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script>

$(document).ready(function() {
	$('#pattern').hide();
	getReport(page_number);
	getReport_feedback(page_number_feedback);
	getReport_analytics(page_number_analytics);
	//console.log("ready")
	<?php if($message) { ?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
				content : "<?php echo $message?>",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
<?php } ?>

$(document).on('click','.print_event',function()
{
	$('#pdf').empty();
	var data_a = $(this).attr('print')
	var data = window.atob(data_a);
	var obj = JSON.parse(data);
	var reply = obj.user_reply;
	$('<label><center><h2><strong><u>'+obj.event_name+'</u></strong><h2></center></label><br><br><p><label><strong>Event Description:</strong></label><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+obj.description+'</p><br><br><label><strong>Total number of users :</strong>'+obj.users.length+'</label><br><br><label><strong>Confirmed users :</strong>'+obj.confirmed_users+'</label><br><br><label><strong>Pending users :</strong>'+obj.noreply_users+'</label><br><br><label><strong>Declined users :</strong>'+obj.declinded_users+'</label><br><br><label><strong>Event Time : </strong>'+obj.start+'</label><br><br><table id="dt_basic" border="1" cellpadding="5" style="width:100%"><caption>Users List</caption><tbody class="print_table"><th>User ID</th><th>Status</th></tbody></table>').appendTo('.printing')
	for(var i in reply)
	{
		$('<tr><td>'+i+'</td><td>'+reply[i]+'</td></tr>').appendTo('.print_table')
	}
    var divToPrint=document.getElementById("pdf");
    newWin= window.open("");
    newWin.document.write(divToPrint.outerHTML);
    newWin.print();
    newWin.close();
})

$(document).on('click','.query',function()
{
	$('#queryres').hide();
	$('#appmicrochart').hide();
	$('#analytics').show();
	$('#pattern').hide();
	$('#analyticsbtn').show();

	$('.analytics_table').hide();
	$('.analytics_row').hide();
	
	var query_app_id = $(this).attr('id');
	var query_pattern = $(this).attr('pattern');
	query_pattern=atob(query_pattern);
	$.ajax({
		url: 'searching',
		type: 'POST',
		dataType:"json",
		data: {"strng" : query_pattern,"dataid" : query_app_id},
		success: function (data) 
		{
			console.log(data);
			
			$('#analytics').hide();
			$('.table-condensed').hide();
		    $('#analyticsbtn').hide();
			$('#queryres').show();
			$('#queryres1').show();
			$('#appmicrochart').show();
			
			var total_docs = data.count;
			var matched_docs = data.docs[0].length;
			console.log(total_docs);
			console.log(matched_docs);
			var match_percent = 100;
			if(total_docs != 0){
				var match_percent = (matched_docs/total_docs)*100;
			}
			
			console.log(match_percent);
			
			//------------------pie chart-----------------------------------------------------------
			
			var pie_chart = '<div class="row"><div class="col-xs-8 col-sm-2 col-md-2 col-lg-1"><div class="chart" style="height:110px;"><div class="percentage" data-percent="'+match_percent+'"><span>'+match_percent+'</span><sup>%</sup></div><div class="label label-warning">Documents Matched</div></div></div><div class="col-xs-8 col-sm-2 col-md-2 col-lg-2"><ul style="margin-top:50px;"><li>Matched documents &nbsp;('+matched_docs+')</li><li>Total documents &nbsp;('+total_docs+')</li></ul></div></div>'
			
			//----------------------table-------------------------------------------------------------------------
			var table="";
			for(var doc in data.docs[0]){
				for(var doc_section in data.docs[0][doc]['doc_data']['widget_data']['page1']){
					for(var name_value in data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section]){
						console.log(name_value);
						table=table+"<tr><td><label search_val="+name_value+">"+name_value+"</label></td><td><label search_val="+data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value]+">"+data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value]+"</label></td></tr>";
						
					}
					
					table = table+'<tr><td></td><td></td></tr>';
				}
			}
			var table_data = '<div class="row"><div class="col-xs-12 col-sm-2 col-md-2 col-lg-12"><table class="table table-bordered" style="margin-top:15px;"><tbody class="analytics_tbody">'+table+'</tbody></table></div></div>'
			
			$('#queryres1').html(pie_chart);
			$('#queryres1').append(table_data);
			
			$('.percentage').easyPieChart({
				  animate: 1000,
				  lineWidth: 8,
				  barColor: '#398E72',
				  lineCap: 'square',
				  scaleColor:false,
				  onStep: function(value) {
				    this.$el.find('span').text(Math.round(match_percent));
				  },
				  onStop: function(value, to) {
				    this.$el.find('span').text(Math.round(match_percent));
				  }
				});	
			
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
			console.log('error', errorThrown);
		}
	});
	//$('#query_modal').modal("show");	
});

$(document).on('click','.print_feedback',function()
{
	$('#pdf').empty();
	var data_a = $(this).attr('print')
	var data = window.atob(data_a);
	var obj = JSON.parse(data);
	//console.log(obj);
	var total = obj.users.length
	var filled = obj.user_filled_forms_count
	total = parseInt(total);
	filled = parseInt(filled)
	var remaining = total-filled;
	$('<label><center><h2><strong><u>'+obj.feedback_name+'</u></strong><h2></center></label><br><br><p><label><strong>Feedback  Description:</strong></label><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+obj.description+'</p><br><br><label><strong>Total number of users :</strong>'+total+'</label><br><br><label><strong>Filled users :</strong>'+filled+'</label><br><br><label><strong>Pending users :</strong>'+remaining+'</label><br><br>').appendTo('.printing')

	var divToPrint=document.getElementById("pdf");
	newWin= window.open("");
	newWin.document.write(divToPrint.outerHTML);
	newWin.print();
	newWin.close();
})

		 // getReport(page_number);
		 // console.log(sr);
		   
		 $("#next").on("click", function(){
			   //$(".events_ajax").empty();
			   page_number = (page_number+1);
			   getReport(page_number);
			   //console.log(page_number);
			   
		 });
			
		 $("#previous").on("click", function(){
			  //$(".events_ajax").empty();
			  page_number = (page_number-1);
			  getReport(page_number);
		 });
		    
		 $("#next_feedback").on("click", function(){
			  // $(".feedbacks").empty();
			   page_number_feedback = (page_number_feedback+1);
			   getReport_feedback(page_number_feedback);
			   //console.log(page_number);
			   
		 });
			
		 $("#previous_feedback").on("click", function(){
			  //$(".feedbacks").empty();
			  page_number_feedback = (page_number_feedback-1);
			  getReport_feedback(page_number_feedback);
		 });
		 
		 $("#previous_analytics").on("click", function(){
			  //$(".feedbacks").empty();
			  page_number_analytics = (page_number_analytics-1);
			  getReport_analytics(page_number_analytics);
		 });
		 
		 $("#next_analytics").on("click", function(){
			   page_number_analytics = (page_number_analytics+1);
			   getReport_analytics(page_number_analytics);
			   //console.log(page_number);
			   
		 });
		 
	// Delete query 
	$('#deletequery a').click(function(e) {
		//get the link
		var $this = $(this);
		$.delURL = $this.attr('href');

		// ask verification
		$.SmartMessageBox({
			title : "<i class='fa fa-minus-square txt-color-orangeDark'></i> Do you want to delete this query ?",
			buttons : '[No][Yes]'

		}, function(ButtonPressed) {
			if (ButtonPressed == "Yes") {
				setTimeout(deletequery, 1000)
			}

		});
		e.preventDefault();
	});

	/*
	 * Delete My apps ACTION
	 */

	function deletequery() {
		window.location = $.delURL;
	}

});

</script>

<script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
<!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->
<script src="<?php echo JS; ?>vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo JS; ?>vectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?php echo JS; ?>demograph.js"></script>
<script src="<?php echo JS; ?>jquery.easy-pie-chart.min.js"></script>
<script src="<?php echo JS; ?>sub_admin_queryapp.js"></script>
<script src="<?php echo JS; ?>save_pattern_modal.js"></script>


<?php 
	//include footer
	include("inc/footer.php"); 
?>

