 
 <?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Edit Profile";

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
        <article class="col-sm-12 col-md-12 col-lg-6">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-user"></i> </span>
							<h2><?php echo lang('edit_profile');?> </h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
                            
							<div class="widget-body no-padding">
                            <?php
						 	$attributes = array('class' => 'smart-form');
							echo  form_open_multipart(uri_string(),$attributes);
							?>
		      					<!--<form class="smart-form">-->
									<header>
										 Edit Your Profile Here.
									</header>
									<fieldset>
                                    <section>
                            			<label class="label" for="email"><?php echo lang('edit_profile_email_label', 'email');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<?php echo form_input($email);?>
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="phone"><?php echo lang('edit_profile_mobile_label', 'phone');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<?php echo form_input($phone);?>
										</label>
		     						</section>
                                    <section>
                            			<label class="label" for="username"><?php echo lang('edit_profile_username_label', 'username');?></label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<?php echo form_input($username);?>
										</label>
		     						</section>
									<section>
		     							<label class="label" for="profile_image"><?php echo lang('edit_profile_image_label', 'profile_image');?></label>
		     							<div class="input input-file">
		     							<span class="button"><input type="file" id="profile_image" name="profile_image" onchange="this.parentNode.nextSibling.value = this.value">Browse</span><input type="text" placeholder="Include profile image (optional)" readonly="">
		     						</div>
		     						</section>
									</fieldset>
									<footer>
										<button type="submit" class="btn bg-color-greenDark txt-color-white">
											Submit
										</button>
										<button type="button" class="btn btn-default" onclick="window.history.back();">
											Back
										</button>
									</footer>
								</form><?php echo form_close();?>
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
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
<script>
function username()
			{
			$.ajax({
				url: '../../username',
				type: 'POST',
				
				success: function (data) 
				{
							
						users=data;
							div = "";
						
						  user = jQuery.parseJSON(data);  
						  div = div + "<div>"+user+"</div>";
						  
						  $('#webuser_username').html(div);
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)
				{
				console.log('error', errorThrown);
				}
			})
			}username();
</script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>