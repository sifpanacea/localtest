<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

		<title><?php echo $page_title != "" ? $page_title." - " : ""; ?>Admin DashBoard</title>
		<meta name="description" content="">
		<meta name="author" content="">
			
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Basic Styles -->
		<link href="<?php echo(CSS.'bootstrap.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
        <link href="<?php echo(CSS.'font-awesome.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
        <link href="<?php echo(CSS.'smartadmin-production.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
        <link href="<?php echo(CSS.'smartadmin-skins.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
        <link href="<?php echo(CSS.'demo.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
		<link href="<?php echo(CSS.'work_flow.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

		<!-- SmartAdmin RTL Support is under construction
		<link rel="stylesheet" type="text/css" media="screen" href="<?php /*?><?php echo ASSETS_URL; ?><?php */?>/css/smartadmin-rtl.css"> -->

		<!-- We recommend you use "your_style.css" to override SmartAdmin
		     specific styles this will also ensure you retrain your customization with each SmartAdmin update.
		<link rel="stylesheet" type="text/css" media="screen" href="<?php /*?><?php echo ASSETS_URL; ?><?php */?>/css/your_style.css"> -->

	<?php /*?>	<?php

			if ($page_css) {
				foreach ($page_css as $css) {
					echo '<link rel="stylesheet" type="text/css" media="screen" href="'.ASSETS_URL.'/css/'.$css.'">';
				}
			}
		?>
<?php */?>

		<!-- FAVICONS -->
		<link rel="shortcut icon" href="<?php echo IMG; ?>ico/favicon.png" type="image/x-icon">
		<link rel="icon" href="<?php echo IMG; ?>ico/favicon.png" type="image/x-icon">

		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

		<!-- Specifying a Webpage Icon for Web Clip 
			 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
		<link rel="apple-touch-icon" href="<?php echo IMG; ?>splash/sptouch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php echo IMG; ?>splash/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php echo IMG; ?>splash/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php echo IMG; ?>splash/touch-icon-ipad-retina.png">
		
		<!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		
		<!-- Startup image for web apps -->
		<link rel="apple-touch-startup-image" href="<?php echo IMG; ?>splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
		<link rel="apple-touch-startup-image" href="<?php echo IMG; ?>splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
		<link rel="apple-touch-startup-image" href="<?php echo IMG; ?>splash/iphone.png" media="screen and (max-device-width: 320px)">

	</head>
	<body 
		<?php 
			if ($page_body_prop) {
				foreach ($page_body_prop as $prop_name => $value) {
					echo $prop_name.'="'.$value.'" ';
				}
			}

		?>
	>
		<!-- POSSIBLE CLASSES: minified, fixed-ribbon, fixed-header, fixed-width
			 You can also add different skin classes such as "smart-skin-1", "smart-skin-2" etc...-->
		<?php
			if (!$no_main_header) {

		?>
				<!-- HEADER -->
				<header id="header">
					<div id="logo-group">

						<!-- PLACE YOUR LOGO HERE -->
						<span id="logo">
						<?Php $custom_logo_file  = PROFILEUPLOADFOLDER.TENANT.'logo'.'.png';?>
						<?php if(file_exists($custom_logo_file)) { ?>
						<img src="<?php echo LOGO_IMG; ?>" alt=""><?php } else { ?>
						<img src="<?php echo TLSTEC_LOGO_IMG; ?>" alt=""><?php } ?>
						</span>
				    	<!-- END LOGO PLACEHOLDER -->

						<!-- Note: The activity badge color changes when clicked and resets the number to 0
						Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
						<span id="activity" class="activity-dropdown"> <i class="fa fa-user"></i> <!--<b class="badge"> 21 </b> --></span>

						<!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->
						<div class="ajax-dropdown">

							<!-- the ID links are fetched via AJAX to the ajax container "ajax-notifications" -->
							<div class="btn-group btn-group-justified" data-toggle="buttons">
								<label class="btn btn-default">
									<input type="radio" name="activity" id="<?php echo URL; ?>dashboard/app_history">
									Apps </label>
								<label class="btn btn-default" disabled>
									<input type="radio" name="activity" id="<?php echo URL; ?>dashboard/docs_history">
									Docs </label>
								<label class="btn btn-default">
									<input type="radio" name="activity" id="<?php echo URL; ?>dashboard/admin_messages">
									Msgs </label>
							</div>

							<!-- notification content -->
							<div class="ajax-notifications custom-scroll">

								<div class="alert alert-transparent">
									<h4>select a category to show messages here</h4>
								</div>

								<i class="fa fa-lock fa-4x fa-border"></i>

							</div>
							<!-- end notification content -->

							<!-- footer: refresh area 
							<span> Last updated on: 12/12/2013 9:43AM
								<button type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Loading..." class="btn btn-xs btn-default pull-right">
									<i class="fa fa-refresh"></i>
								</button> </span>-->
							<!-- end footer -->

						</div>
						<!-- END AJAX-DROPDOWN -->
					</div>

					
					<!-- pulled right: nav area -->
					<div class="pull-right">

						<!-- collapse menu button -->
						<div id="hide-menu" class="btn-header pull-right">
							<span> <a href="javascript:void(0);" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
						</div>
						<!-- end collapse menu -->

						<!-- logout button -->
						<div id="logout" class="btn-header transparent pull-right">
							<span> <a class="menu-links" href="<?php echo URL; ?>auth/logout" title="Sign Out" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span>
						</div>
						<!-- end logout button -->

						<!-- search mobile button (this is hidden till mobile view port) -->
						<div id="search-mobile" class="btn-header transparent pull-right">
							<span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
						</div>
						<!-- end search mobile button -->

						<!-- profile start-->
						<div id="my-profile" class="btn-header transparent pull-right">
						<span>
						<a class="menu-links" href="<?php echo URL."dashboard/admin_profile" ?>"><i class="glyphicon glyphicon-user"></i> </a>
						</span>
						</div>
						<!-- profile end -->

						<!-- fullscreen button -->
						<!--<div id="fullscreen" class="btn-header transparent pull-right">
							<span> <a href="javascript:void(0);" onclick="launchFullscreen(document.documentElement);" title="Full Screen"><i class="fa fa-fullscreen"></i></a> </span>
						</div>-->
						<!-- end fullscreen button -->
                       </div>
					<!-- end pulled right: nav area -->

				</header>
				<!-- END HEADER -->


		<?php
			}
		?>