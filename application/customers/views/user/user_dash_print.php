<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
 
require_once("inc/config.ui.php");


/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "DashBoard";

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
<style>
@font-face {
	font-family: Segoe UI;
	src: url('<?php echo(FONT.'SegoeSemibold.ttf'); ?>');
}
	<link href="<?php echo(CSS.'load.css'); ?>" rel="stylesheet" type="text/css" />
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
		
	?>
<?php $message = $this->session->flashdata('message');?>	

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Dashboard </h1>
			</div>
			<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
				<ul id="sparks" class="">
					<li class="sparks-info">
						<h5>Subscription<span class="txt-color-blue"><div id="subdaysleft"></div></span></h5>
					</li>
				</ul>
			</div>
		</div>
				<div class="inbox-nav-bar no-content-padding">
		
			<h1 class="page-title txt-color-blueDark hidden-tablet"><i class="fa fa-fw fa-inbox"></i> Inbox &nbsp;
			<!--<div class="btn-group">
				<a href="#" data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle"><span class="caret single"></span></a>
				<ul class="dropdown-menu">
					<li>
						<a href="#">Action</a>
					</li>
					<li>
						<a href="#">Another action</a>
					</li>
					<li>
						<a href="#">Something else here</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="#">Separated link</a>
					</li>
				</ul>
			</div>--></h1>
		
			<div class="btn-group hidden-desktop visible-tablet">
				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
					Inbox <i class="fa fa-caret-down"></i>
				</button>
				<ul class="dropdown-menu pull-left">
					<li>
						<a href="javascript:void(0);" class="inbox-load">Inbox <i class="fa fa-check"></i></a>
					</li>
					<li>
						<a href="javascript:void(0);">Sent</a>
					</li>
					<li>
						<a href="javascript:void(0);">Trash</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="javascript:void(0);">Spam</a>
					</li>
				</ul>
		
			</div>
		
			<div class="inbox-checkbox-triggered">
		
				<div class="btn-group">
					<a href="javascript:void(0);" rel="tooltip" title="" data-placement="bottom" data-original-title="Mark Important" class="btn btn-default"><strong><i class="fa fa-exclamation fa-lg text-danger"></i></strong></a>
					<a href="javascript:void(0);" rel="tooltip" title="" data-placement="bottom" data-original-title="Move to folder" class="btn btn-default"><strong><i class="fa fa-folder-open fa-lg"></i></strong></a>
					<a href="javascript:void(0);" rel="tooltip" title="" data-placement="bottom" data-original-title="Delete" class="deletebutton btn btn-default"><strong><i class="fa fa-trash-o fa-lg"></i></strong></a>
				</div>
		
			</div>
		
			<a href="javascript:void(0);" id="compose-mail-mini" class="btn btn-primary pull-right hidden-desktop visible-tablet"> <strong><i class="fa fa-file fa-lg"></i></strong> </a>
		
			<div class="btn-group pull-right inbox-paging">
				<button class="btn btn-default btn-sm previous"><strong><i class="fa fa-chevron-left"></i></strong></button>
				<button class="btn btn-default btn-sm next"><strong><i class="fa fa-chevron-right"></i></strong></button>
			</div>
			<span class="pull-right"><strong class="current"></strong> of <strong class="total"></strong></span>
		
		</div>
		<div id="inbox-content" class="inbox-body no-content-padding">
		
			<div class="inbox-side-bar">
		
				<!--<a href="javascript:void(0);" id="compose-mail" class="btn btn-primary btn-block"> <strong>Compose</strong> </a>-->
		
				<h6> Folder <a href="javascript:void(0);" rel="tooltip" title="" data-placement="right" data-original-title="Refresh" class="pull-right txt-color-darken"><i class="fa fa-refresh"></i></a></h6>
		
				<ul class="inbox-menu-lg">
					<li class="active">
						<a class="inbox-load" href="javascript:void(0);"> Inbox <span class="inbox_count"></span> </a>
					</li>
					<li>
						<a href=<?php echo URL."welcome/apps"?>>Applications</a>
					</li>
					<li>
						<a class="read-apps" href="javascript:void(0);">Installed Apps</a>
					</li>
					<li>
						<a href="javascript:void(0);">Draft</a>
					</li>
					<li>
						<a href="javascript:void(0);">Search</a>
					</li>
				</ul>
		
				<h6> Quick Access <a href="javascript:void(0);" rel="tooltip" title="" data-placement="right" data-original-title="Add Another" class="pull-right txt-color-darken"><i class="fa fa-plus"></i></a></h6>
		
				<ul class="inbox-menu-sm">
					<li>
						<a href="javascript:void(0);"> Images (476)</a>
					</li>
					<li>
						<a href="javascript:void(0);">Documents (4)</a>
					</li>
				</ul>
		
				<div class="air air-bottom inbox-space">
		
					3.5GB of <strong>10GB</strong><a href="javascript:void(0);" rel="tooltip" title="" data-placement="top" data-original-title="Empty Spam" class="pull-right txt-color-darken"><i class="fa fa-trash-o fa-lg"></i></a>
		
					<div class="progress progress-micro">
						<div class="progress-bar progress-primary" style="width: 34%;"></div>
					</div>
				</div>
		
			</div>
		
			<div class="table-wrap custom-scroll animated fast fadeInRight">
				<!-- ajax will fill this area -->
				<table id="inbox-table" class="table table-striped table-hover">
				<tbody class="inbox_add">
				</tbody>
				</table>
		
			</div>
		
			<div class="inbox-footer">
		
				<div class="row">
		
					<div class="col-xs-6 col-sm-1">
		
						<div class="txt-color-white hidden-desktop visible-mobile">
							3.5GB of <strong>10GB</strong>
		
							<div class="progress progress-micro">
								<div class="progress-bar progress-primary" style="width: 34%;"></div>
							</div>
						</div>
					</div>
		
					<div class="col-xs-6 col-sm-11 text-right">
						<div class="txt-color-white inline-block">
							<i class="txt-color-blueLight hidden-mobile">Last account activity <i class="fa fa-clock-o"></i> 52 mins ago |</i> Displaying <strong>44 of 259</strong>
						</div>
					</div>
		
				</div>
		
			</div>
		
		</div>
		<!--row---btn btn-default btn-circle btn-xl-->
		
		<!--<div class="well well-sm bg-color-darken txt-color-white text-center">
									<h5>Notifications</h5>
								</div>
								<div class="widget-body">
		<table class="table table-bordered">
           	<tr>
       		<th>
             <center>New Apps</center>
             </th>
        	 </tr>
            <tbody id="update_apps" align="justify">
            <tr height='50px'><td><div align='center'>New applications get populated here in every 60sec</div></td></tr>
            </tbody>
            <tr>
       		<th>
            
       		 <center>New Documents</center>
            
             </th>
        	 </tr>
            <tbody id="update_docs" align="justify">
            <tr><td><div>New documents get populated here in every 60sec</div></td></tr>
            </tbody></table>
		</div>-->
	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->


<?php 
	//include required scripts
	include("inc/scripts.php"); 
	
?>
<script src='<?php echo(JS.'summernote.js'); ?>'></script>
<script src='<?php echo(JS.'select2.min.js'); ?>'></script>   
	
<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script type="text/javascript">
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


<?php 
	//include footer
	include("inc/footer.php"); 
?>