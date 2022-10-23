<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Community Application Properties";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["application"]["sub"]["communityapps"]["sub"][$appcategory]["active"] = true;
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
							<h2>Community Application Properties</h2>
		
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
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Number of pages</p><i class="icon-leaf"><?php echo $pages;?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Version</p><i class="icon-leaf"><?php echo $version;?></i></div>
														</li>-->
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Shared by</p><i class="icon-leaf"><?php echo $createdby;?></i> at <?php echo $createdtime;?></div>
														</li>
													</ul>
												</li>
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