<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Third Party";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["thirdparty"]["sub"]["3rd_party"]["active"] = true;
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

<?php if($this->session->flashdata('message')) { ?>	
<div class="alert alert-success alert-block">
						<a class="close" data-dismiss="alert" href="#">×</a>
						<h4 class="alert-heading">Message!</h4>
						<?php echo $this->session->flashdata('message'); ?>
					</div><?php } ?>
	<!-- MAIN CONTENT -->
	<div id="content">
<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-6">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-user"></i> </span>
							<h2>Third Party Integration</h2>
		
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
                            
                            		<a class="btn btn-default btn-lg btn-block" href="<?php echo (URL.'example/request_dropbox')?>">
				<i class="icon-dropbox"></i>
				Drop Box
				</a>
				
				<a class="btn btn-default btn-lg btn-block href="<?php echo (URL.'api/api')?>">
				<i class="icon-google-plus"></i>
				Google Drive
				</a>
				
				<a class="btn btn-default btn-lg btn-block" href="<?php echo (URL.'api/api')?>">
				<i class="icon-cloud"></i>
				Cloud 9
				</a>
				
				
				<a class="btn btn-default btn-lg btn-block" href="<?php echo (URL.'api/api_paas')?>">
				<i class="icon-leaf"></i>
				PAAS API
				</a>
                          
							</div>
		
  				
		
				
				
				

  						</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					
			</article>
        
        </div><!-- ROW -->


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

<script>

	$(document).ready(function() {
		// PAGE RELATED SCRIPTS
	})

</script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>