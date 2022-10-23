<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = lang('admin_title');

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
.labelinfo
{
	margin-left:25px;
	margin-top:20px;
}
#legend ul{
	list-style-type:none;
}
#legend li span{
	
    display: inline-block;
    width: 12px;
    height: 12px;
	border-radius: 6px;
    margin-right: 5px;
}
.axis_type
{
	margin-right:5px;
}
#flot-tooltip { font-size: 12px; font-family: Verdana, Arial, sans-serif; position: absolute; display: none; border: 2px solid; padding: 2px; background-color: #FFF; opacity: 0.8; -moz-border-radius: 5px; -webkit-border-radius: 5px; -khtml-border-radius: 5px; border-radius: 5px; }
.sear_inp
{
	max-width:120px;
}
.loading {
  position: absolute;
  top: 90%;
  left: 50%;
}
.loading-bar {
  display: inline-block;
  width: 6px;
  height: 30px;
  border-radius: 4px;
  animation: loading 1s ease-in-out infinite;
}
.loading-bar:nth-child(1) {
  background-color: #3498db;
  animation-delay: 0;
}
.loading-bar:nth-child(2) {
  background-color: #c0392b;
  animation-delay: 0.09s;
}
.loading-bar:nth-child(3) {
  background-color: #f1c40f;
  animation-delay: .18s;
}
.loading-bar:nth-child(4) {
  background-color: #27ae60;
  animation-delay: .27s;
}

@keyframes loading {
  0% {
    transform: scale(1);
  }
  20% {
    transform: scale(1, 3.2);
  }
  40% {
    transform: scale(1);
  }
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
							<h2><?php echo lang('admin_dash_live');?> </h2>
							<a class="help" href="<?php echo URL.'help/dashboard';?>"target="_blank">HELP?</a>
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
												</div><span style="margin-left:5px;font-size:smaller">Weekly Stats</span>
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
                                                <span class="easy-pie-title"> App Usage <span style="
    font-size: xx-small;
">(24hrs)</span></span>
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
											<td>
											<button class="btn btn-success btn-xs query" id="<?php echo $app['app_id']; ?>" pattern="<?php echo base64_encode($app['pattern']); ?>" gtype="<?php echo $app['graph_type']; ?>"><?php echo lang('query_pattern');?></button>
											
											<span id="deletequery">
											<a class='ldelete' href='<?php echo URL."dashboard/delete_saved_pattern/".$app['_id'];?>'>
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
                 </div>
				</article>
				
			<!-- row -->

			<div class="row">

				<article class="col-sm-12 col-md-12 col-lg-12">

					<!-- new widget -->
					<div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="true">

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
							<span class="widget-icon"> <i class="fa fa-crop"></i> </span>
							<h2>Analytics</h2>
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

							<div class="widget-body no-padding">
								<!-- content goes here <div id="bar-chart" class="chart"></div> -->
								<div id="queryres1" style="min-height:400px;"></div>
								 <div id="legend-container" style="padding-left:10px;padding-top:10px;"></div>
								
                    <?php if($updType!='query') { ?>
					<table class="table table-striped table-hover table-condensed">
					<?php $a = 0;?>
					<?php if ($apps): ?>
					<?php /*?><!--<tr>
						<th><?php echo lang('index_app_th');?></th>
						<th><?php echo lang('index_action_th');?></th>
					</tr>--><?php */?>
					<?php foreach ($apps as $app):?>
					<tr>
					
						<td><?php echo $app['app_name'];?></td>
						<td><?php echo anchor("dashboard/query_app/".$app['_id'].'/query', lang('admin_query'),array('class' => '')) ;?></td>
						
					</tr>
					<?php $a++;?>
					<?php endforeach;?>
                   
                    <?php else: ?>
        			<p>
          				<?php echo lang('admin_no_apps');?>
        			</p>
					<?php endif?>
					<?php $a++;?>
					<tfoot>
                      <tr>
                      	<?php if($links):?>
                         <td colspan="5">
                            <?php echo $links; ?>
                         </td><?php endif ?>
                      </tr>
				    </tfoot>
					</table>
					<?php } else {?>
                    <div id="analytics"></div>
                    <div id="analyticsbtn">
					<button type="button" id="searchquery" data-loading-text="Finding..." class="btn btn-primary create" autocomplete="off" style="float: right;margin-right: 20px;margin-bottom: 10px;">Submit</button>
					<!--<button class="btn btn-default" id="searchquery" style="float: right;margin-right: 20px;margin-bottom: 10px;">Query</button> -->
					</div>
                    
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
						<button type="button" id="pattern_sav" class="btn btn-primary">
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
							echo  form_open('dashboard/savepattern',$attributes);
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
									<input type="hidden" id="graphtyp" name="graphtyp"/>
									</form>
									
					</div>
				</div>
			</div>
		</div>
		<button class="btn bg-color-greenDark txt-color-white pull-right" id="pattern" data-toggle="modal" data-target="#myModal" style="margin-bottom: 10px;/* display: block; */margin-right: 10px;">Save Query Pattern</button>
		<button class="btn btn-default query_back pull-right" style="margin-right:5px;" onclick="history.go(-1)">Back</button>

                    <?php } ?>
                    

								<!-- end content -->

							</div>

						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->
					

				</article>

			</div>

			<!-- end row -->
				</div>
			</div><!-- end row -->
			</section>
<div class="modal fade" id="query_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Query</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
	</div>
	<!-- END MAIN CONTENT -->
	
	<!-- LOADING CONTENT -->
	<div class="loading">
	  <div class="loading-bar"></div>
	  <div class="loading-bar"></div>
	  <div class="loading-bar"></div>
	  <div class="loading-bar"></div>
	</div>
	<!-- END LOADING CONTENT -->

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

	<?php if($message) { ?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
				content : "<?php echo $message?>",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
<?php } ?>
});
</script>

<script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.barnumbers.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.orderBar.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.axislabels.js"></script>
<!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->
<script src="<?php echo JS; ?>vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo JS; ?>vectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?php echo JS; ?>Chart.js"></script>
<script src="<?php echo JS; ?>demograph.js"></script>
<script src="<?php echo JS; ?>queryapp.js"></script>
<script src="<?php echo JS; ?>save_pattern_modal.js"></script>


<?php 
	//include footer
	include("inc/footer.php"); 
?>

