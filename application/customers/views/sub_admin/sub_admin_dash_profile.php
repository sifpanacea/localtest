<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "My Profile";

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
				<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Home <span>> My profile</span></h1>
			</div>
			
		</div><!--row---btn btn-default btn-circle btn-xl-->
				<?php foreach ($profile_data as $data):?>
				<!-- MAIN CONTENT -->
	<div id="acontent">

		<!-- Bread crumb is created dynamically -->
		<!-- row -->
		<!-- end row -->
		
		<!-- row -->
		<div class="col-sm-12 text-align-right">
		
											<div class="btn-group">
												<a href="<?php echo URL."sub_admin/edit_profile"?>" class="btn btn-sm btn-primary"> <i class="fa fa-edit"></i> Edit Profile</a>
											</div>
		
										</div>
		<div class="row">
		
			<div class="col-sm-12">
		
		
					<div class="well well-sm">
		
						<div class="row">
		
							<div class="col-sm-12 col-md-12 col-lg-6">
								<div class="well well-light well-sm no-margin no-padding">
		
									<div class="row">
		
										<div class="col-sm-12">
											<div id="myCarousel" class="carousel fade profile-carousel carousel slide">
												<div class="air air-top-left padding-10">
													<h4 class="txt-color-white font-md"><?php echo date("d M,y");?></h4>
												</div>
												<ol class="carousel-indicators">
													<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
													<li data-target="#myCarousel" data-slide-to="1" class=""></li>
													<li data-target="#myCarousel" data-slide-to="2" class=""></li>
												</ol>
												<div class="carousel-inner">
													<!-- Slide 1 -->
													<div class="item active">
														<img src="<?php echo IMG; ?>/demo/s1.jpg" alt="">
													</div>
													<!-- Slide 2 -->
													<div class="item">
														<img src="<?php echo IMG; ?>/demo/s2.jpg" alt="">
													</div>
													<!-- Slide 3 -->
													<div class="item">
														<img src="<?php echo IMG; ?>/demo/s3.jpg" alt="">
													</div>
												</div>
											</div>
										</div>
		
										<div class="col-sm-12">
		
											<div class="row">
		                                     <div id="me" class="col-sm-3 profile-pic">
											<?Php $custom_profile_file  = PROFILEUPLOADFOLDER.$email.".png";?>
						                    <?php if(file_exists($custom_profile_file)) { ?>
											<img src="<?php echo PROFILEIMGFOLDER.$email.".png"; ?>" alt="me" /><?php } else { ?>
											<img src="<?php echo IMG; ?>/avatars/male.png" alt="me"  /><?php } ?>
													</div>
												<div class="col-sm-6">
													<h1><span class="semi-bold"><?php echo $data['username'];?></span>
													<br>
													<small> Admin,&nbsp;<?php echo $data['display_company_name'];?></small></h1>
		
													<ul class="list-unstyled">
														<li>
															<p class="text-muted">
																<i class="fa fa-phone"></i>&nbsp;&nbsp;<?php echo $data['phone'];?>
															</p>
														</li>
														<li>
															<p class="text-muted">
																<i class="fa fa-envelope"></i>&nbsp;&nbsp;<?php echo $data['email'];?>
															</p>
														</li>
													</ul>
		
												</div>
		
											</div>
		
										</div>
		
									</div>
		
		
								</div>
								
								
		
									
		
								</div>
		                       <!--Another div start-->
							   <div class="col-sm-12 col-md-12 col-lg-6">
								
								<div class="padding-10">
		
												<ul class="nav nav-tabs tabs-pull-right">
													<li class="active">
														<a href="#a1" data-toggle="tab">Enterprise Details</a>
													</li>
													<li>
														<a href="#a2" data-toggle="tab">Subscription Details</a>
													</li>
												</ul>
		
												<div class="tab-content padding-top-10">
													<div class="tab-pane fade in active" id="a1">
		
														<div class="row">
		                                                   <div class="col-xs-10 col-sm-11">
														   <h6 class="no-margin">
														   <ul class="list-unstyled">
														<li>
															<p class="text-info">
																<i class="fa fa-globe"></i>&nbsp;&nbsp;<?php echo $data['company_website'];?>
															</p>
														</li>
														<li>
															<p class="text-info">
																<i class="fa fa-hand-o-right"></i>&nbsp;&nbsp;<?php echo $data['company_address'];?>
															</p>
														</li>
													</ul></h6></div>
		
															
														</div>
		
													</div>
													<div class="tab-pane fade" id="a2">
		                                            <div class="row">
													
		                                                   <div class="col-xs-10 col-sm-11">
														<div class="table-responsive">
														<table class="table table-bordered">
														 <thead>
														 <tr><th>Subscribed Plan</th><th>Registered on</th><th>Expiry</th></tr>
														 </thead>
														 <tbody>
														 <tr><td><?php echo $data['plan'];?></td><td><?php echo $data['registered_on'];?></td><td><?php echo $data['plan_expiry'];?></td></tr>
														 </tbody>
														</table>
														</div>
														</div>
															<div class="btn-group pull-left" style="margin-left:13px">
												<a href="<?php echo URL."auth/plan_upgrade/".$profile_data[0]['company']?>" class="btn btn-sm btn-primary"> <i class="fa fa-level-up"></i> Upgrade</a>
											</div>
														</li>
													</ul></h6></div>
		
															
														</div>
														
		
													</div><!-- end tab -->
													</div>
		
									
		
								</div>
							   
							</div><!--Another div end-->
						</div>
		
					</div>
		
		
			</div>
		
		</div>
		
		<!-- end row -->

		<div class="jarviswidget jarviswidget-color-greenDark jarviswidget-sortable">
			<div class="table-responsive">								
			<div class='pull-left show'>Recent Activities</div>
			<div class='pull-right'><?php echo "Your last login was"."&nbsp;&nbsp;".$last_login;?></div>
			<hr>
				<table class='table table-bordered' id='data-tab'>
					<tbody>
						<?php $a = 0;?>
						<?php if ($myapps): ?>
						<?php foreach ($myapps as $app):?>
						 <tr>
						 <td>
						   You created an app <i><?php echo $app->app_name;?></i>
						   at <?php echo $app->time;?>
							 </td></tr>
							 <?php $a++;?>
						  <?php endforeach;?>
						  <?php else: ?>
						  <tr><td>
						  
						<?php echo lang('admin_no_apps_created');?>
							   
							   </td></tr>
						  <?php endif ?>
						<tr><td>
						<?php echo $links; ?>
						</td></tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- end row -->
	</div>
	<!-- END aCONTENT -->
               
			
				<?php endforeach;?>
			
	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->
 <input type='hidden' id='company' name='company' value="<?php echo set_value('company', $data['company']);?>" />
<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script>
$(document).ready(function() {
$('.carousel.slide').carousel({
			interval : 3000,
			cycle : true
		});
		$('.carousel.fade').carousel({
			interval : 3000,
			cycle : true
		});
		
		//$(document).on('click','.show',function(e)
		//{
			//e.preventDefault();
			//if($('#data-tab').hasClass('hide'))
			//{	
			//	$('#data-tab').removeClass('hide');
			//}
			//else
			///{
			//	$('#data-tab').addClass('hide');
			//}
		//})
});
</script>

<script>
$(document).ready(function() {
<?php $message = $this->session->flashdata('message'); ?>
	<?php if($message) { ?>
$.smallBox({
				title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
				content   : "<?php echo $message?>",
				color     : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout   : 8000
			});
<?php } ?>
});
</script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>