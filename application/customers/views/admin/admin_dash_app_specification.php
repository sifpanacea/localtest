<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Application Properties";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["application"]["active"] = true;
include("inc/nav.php");

?>
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
     				<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->

							</div>
							<!-- end widget edit box -->
                                 <!-- NEW WIDGET START -->
				<article class="col-sm-12 col-md-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-editbutton="false">
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
							<span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
							<h2>Application Properties</h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							
							
							
							
								<div class="tree smart-form">
									<ul>
										<li>
											<span><i class="fa fa-lg fa-folder-open"></i>&nbsp;<?php echo $appname;?></span>
											<ul>
											<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Properties</span>
													<ul>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Description</p><i class="icon-leaf"><?php echo $appdescription;?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Category</p><i class="icon-leaf"><?php echo $appcategory;?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Application Type</p><i class="icon-leaf"><?php echo $apptype;?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Expiry</p><i class="icon-leaf"><?php echo $appexpiry;?></i></div>
														</li>
														<!--<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Number of elements</p><i class="icon-leaf"><?php echo "15";?></i></div>
														</li>-->
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Number of pages</p><i class="icon-leaf"><?php echo $pages;?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Version</p><i class="icon-leaf"><?php echo $version;?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Created by</p><i class="icon-leaf"><?php echo $createdby;?></i> at <?php echo $createdtime;?></div>
														</li>
													</ul>
												</li>
												<li>
												<span><i class="fa fa-lg fa-plus-circle"></i> Application Header Details</span>
												<?php if(isset($application_header)):?>
												<?php foreach($application_header as $header_details):?>
													<ul>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Company Name</p><i class="icon-leaf"><?php echo $header_details['companyname'];?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Address</p><i class="icon-leaf"><?php echo $header_details['address'];?></i></div>
														</li>
														<!--<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Logo</p><i class="icon-leaf"></i>
																<?php $pathvar = str_replace('/','=',$header_details['logo']); ?><a target='_blank' href='../secure_file_download/<?php echo $pathvar;?>'>Download</a></div>
														</li>-->
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Logo</p><i class="icon-leaf"></i>
															    <?php if(isset($header_details['logo']) && !is_null($header_details['logo']) && !empty($header_details['logo'])):?>
																<img src="<?php echo URLCustomer.$header_details['logo'];?>" height="50" width="50"/><div><?php $pathvar = str_replace('/','=',$header_details['logo']); ?><a target='_blank' href='../secure_file_download/<?php echo $pathvar;?>'>Download</a></div><?php else: ?><?php echo "No Logo file uploaded";?><?php endif ?></div>
														</li>
													</ul>
												<?php endforeach?>
													<?php endif?>	
												</li>
												
													
													<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Workflow Assignments</span>
													<ul>
														<li style="display:none">
														<table class="table table-bordered bg-color-darken txt-color-white">
														<?php if(isset($workflow) && !empty($workflow)):?>
														<tr><th><p class="fa fa-location-arrow"> Stage Name</p></th><th><p class="fa fa-user"> User</p></th><th><b class="fa  fa-level-up"> Stage Type</b></th><th><b class="fa fa-book"> Can View</b></th><th><b class="fa fa-pencil-square"> Can Edit</b></th><th><b class="fa fa-print"> Print</b></th><th><b class="fa fa-envelope"> SMS</b></th></tr>
														<?php foreach($workflow as $value):?>
														<tr><td><?php echo $value['Stage'];?></p></td>
														<td><?php echo implode(',',$value['Users']);?></p></td>
														<td><?php echo $value['Stage_Type'];?></p></td>
														<td><?php echo implode(',',$value['View_Permissions']);?></p></td>
														<td><?php echo implode(',',$value['Edit_Permissions']);?></p></td><?php if($value['Print']=="true"):?><td><p class='fa fa-check'></p></td><?php else:?><td><p class='fa fa-times'></p></td><?php endif?><?php if($value['SMS']=="true"):?><td><p class='fa fa-check'></p></td><?php else: ?><td><p class='fa fa-times'></p></td><?php endif ?></tr>
														<?php endforeach ?>
														<?php else: ?>
														<tr><td>
														<center><?php echo "Workflow not assigned"; ?></center>
														</td></tr>
														<?php endif ?>
													    </table>
														</li>
													</ul></li>
													
													<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Notification Parameters </span>
													<ul>
														<li style="display:none">
														<table class="table table-bordered bg-color-darken txt-color-white">
														<?php if(isset($notify_param) && !empty($notify_param)):?>
														<tr><th><p class="fa fa-file"> Page</p></th><th><p class="fa  fa-list">  Section</p></th><th><p class="fa fa-keyboard-o">  Field </p></th></tr>
														<?php foreach($notify_param as $value):?>
														<tr><td><?php echo $value['page_num'];?></p></td>
														<td><?php echo $value['section'];?></p></td>
														<td><?php echo $value['field'];?></p></td>
														</tr>
														<?php endforeach ?>
														<?php else: ?>
														<tr><td>
														<center><?php echo "No notification parameters configured"; ?></center>
														</td></tr>
														<?php endif ?>
													    </table>
														</li>
													</ul></li>
													
													
													<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Print Template</span>
													<ul>
														<li style="display:none">
														<table class="table table-bordered bg-color-darken txt-color-white">
														<?php if(isset($print_template) && !empty($print_template)):?>
														<tr><th><p class="fa fa-file"> Page</p></th><th><p class="fa  fa-tag">  Title </p></th><th><p class="fa fa-info">  Description </p></th></tr>
														<?php foreach($print_template as $value):?>
														<tr><td><?php echo $value['page_num'];?></td>
														<?php if(isset($value['title'])&& !empty($value['title'])):?>
														<td><?php echo $value['title'];?></td>
														<?php else: ?>
														<td><p class='fa fa-minus'></p></td>
														<?php endif?>
														<?php if(isset($value['desc'])&& !empty($value['desc'])):?>
														<td><?php echo $value['desc'];?></td>
														<?php else: ?>
														<td><p class='fa fa-minus'></p></td>
														<?php endif?>
														</tr>
														<?php endforeach ?>
														<?php else: ?>
														<tr><td>
														<center><?php echo "No Print template configured"; ?></center>
														</td></tr>
														<?php endif ?>
													    </table>
														</li>
													</ul></li>
													
													<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> SMS Configuration</span>
													<ul>
														<li style="display:none">
														<table class="table table-bordered bg-color-darken txt-color-white">
														<?php if(isset($sms_content) && !empty($sms_content)):?>
														<tr><th><p class="fa fa-location-arrow"> Stage Name</p></th><th><p class="fa  fa-envelope">  Message </p></th></tr>
														<?php foreach($sms_content as $value):?>
														<tr><td><?php echo $value['stage_name'];?></p></td>
														<?php foreach($value['message'] as $msg):?>
														<td><?php echo $msg;?></p></td>
														<?php endforeach?>
														</tr>
														<?php endforeach ?>
														<?php else: ?>
														<tr><td>
														<center><?php echo "No SMS configured"; ?></center>
														</td></tr>
														<?php endif ?>
													    </table>
														</li>
													</ul></li>
													</ul>
												</li>
												
											</ul>
										</li>
									</ul>
									
								</div>
		</div>
							<!-- end widget content -->
		
        </div><!-- ROW -->
		<div>
	<button type="button" class="btn btn-primary pull-right" onclick="window.history.back();">
											Back
										</button>
							
	</div>		

	</div>
	
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S)--> 
<script type="text/javascript">
	
	$(document).ready(function() {
	
		$('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
		$('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch').on('click', function(e) {
			var children = $(this).parent('li.parent_li').find(' > ul > li');
			if (children.is(':visible')) {
				children.hide('fast');
				$(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-lg fa-plus-circle');
			} else {
				children.show('fast');
				$(this).attr('title', 'Collapse this branch').find(' > i').removeClass().addClass('fa fa-lg fa-minus-circle');
			}
			e.stopPropagation();
		});			
	
	})

</script>
<!--<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



<?php 
	//include footer
	include("inc/footer.php"); 
?>