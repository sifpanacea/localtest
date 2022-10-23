<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Create Events";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["events"]["sub"]["create_calendar"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Events"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="jarviswidget jarviswidget-sortable col-md-6" id="wid-id-6" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
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
				<header role="heading"></header>

				<!-- widget div-->
				<div role="content">
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body no-padding">
						
						<?php
						 	$attributes = array('class' => 'smart-form');
							echo  form_open_multipart('sub_admin/request_create_event',$attributes);
							?>
							<header>
								Event Creation
							</header>

							<fieldset>
									<section>
										<label class="label">Name</label>
										<label class="input"> <i class="icon-append fa fa-edit"></i>
											<input type="text" name="name">
										</label>
									</section>

								<section>
									<label class="label">Form definition</label>
									<label class="textarea"> <i class="icon-append fa fa-comment"></i> 										<textarea rows="4" name="description"></textarea> </label>
								</section>
								<section>
                            			<label class="label"><?php echo lang('app_expiry');?></label>
                                                    <label class="input" id="datepickerr">
                                                    	<i class="icon-append fa fa-calendar"></i>
														<input type="text" name="eventexpiry"  value="" class="datepicker" data-dateformat="yy-mm-d" id="date">
													</label>
		     					</section>
		     					<section>
		     						<label class="label" for="profile_image">Attach (optional)</label>
		     						<div class="input input-file">
		     							<span class="button"><input type="file" id="samplefile" name="samplefile" onchange="this.parentNode.nextSibling.value = this.value">Browse</span><input type="text" placeholder="Include sample form image, max size 10 MB!" readonly="">
		     						</div>
		     					</section>
							</fieldset>

							<footer>
								<button type="submit" name="submit" class="btn btn-primary">
									Submit
								</button>
							</footer>

							<div class="message">
								<i class="fa fa-check fa-lg"></i>
								<p>
									Your comment was successfully added!
								</p>
							</div>
						<?php echo form_close();?>
						
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
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

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->

<script>
	$(function() {
		// Validation
		$(".smart-form").validate({
			// Rules for form validation
			rules : {
				name : {
					required : true,
				},
				description : {
					required : true,
				},
				eventexpiry : {
					required : true,
				}
			},

			// Messages for form validation
			messages : {
				name : {
					required : "Please enter event title"
		
				},
				description : {
					required : "Please enter description"
				},
				eventexpiry : {
					required : "Please enter expiry date"
				}
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});

	});
	$(document).ready(function() {
		
		// PAGE RELATED SCRIPTS
	})

</script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>