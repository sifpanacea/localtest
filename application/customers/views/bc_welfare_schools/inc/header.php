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
        <link rel="stylesheet" type="text/css" href="<?php echo CSS; ?>prettyPhoto.css"/>

        <link href="https://cdn.bootcss.com/balloon-css/0.5.0/balloon.min.css" rel="stylesheet">
		 <link href="<?php echo CSS; ?>img_options/jquery.magnify.css" rel="stylesheet">
		 <link href="<?php echo CSS; ?>img_options/snack.css" rel="stylesheet">
		 <link href="<?php echo CSS; ?>img_options/snack-helper.css" rel="stylesheet">
		 <link href="<?php echo CSS; ?>img_options/docs.css" rel="stylesheet">
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
				<header id="header" style="margin-top: 0px;height: 95px;" class="bg-color-orange txt-white">
					<div id="logo-group">
						<!-- PLACE YOUR LOGO HERE -->
						<span id="">
						<img src="<?php echo TS_GOVT_LOGO; ?>" alt="" style="width: 75px;height:80px;margin-top:6px;margin-left:6px;">
						</span>
						<span id="">
						<img src="<?php echo BCWELFARE_LOGO_IMG; ?>" alt="" style="width: 80px;height:120px;margin-top: -14px;margin-left:6px;">
						</span>
				    	<!-- END LOGO PLACEHOLDER -->
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 col-md-offset-3" id="school_name">
					<span>
					<?php $CI = &get_instance();?>
					<?php $cus = $CI->session->userdata("customer");?>
					
					<?php $useremail = $cus['school_name']; ?>
					
					<h3><?php echo $useremail;?></h3>
					
					</span>
					</div>
					
					<!-- pulled right: nav area -->
					<div class="pull-right">

						<img style="height:75px; margin-top: 5px;width:65px; border-radius: 100px; " src="<?php echo IMG; ?>/Panacea_LOGO_Final.jpg" alt="">

						<img style="height:80px;margin-left: -6px; margin-top: 8px;width:80px; border-radius: 100px; " src="<?php echo IMG; ?>/SYNERGY.png" alt="">

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
