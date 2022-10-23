<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Feedback Design";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
if ($updType == 'create'){
	$page_nav["feedbacks"]["sub"]["feedbackrequests"]["active"] = true;
}elseif ($updType == 'edit'){
	$page_nav["feedbacks"]["sub"]["managefeedbackapp"]["active"] = true;
}

include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Feedbacks"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
    <div class="row">
    <article class="col-sm-12 col-lg-6">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-2" data-widget-editbutton="false" data-widget-deletebutton="false">
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
                        <span class="widget-icon"> <i class="fa fa-paste"></i> </span>
							<h2><?php if ($updType == 'create'){
								echo lang('feedback_create_heading');
							}elseif ($updType == 'edit'){
								echo lang('feedback_edit_heading');
							}?></h2>
		              
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body fuelux">
		
								<div class="step-content">
										<div class="step-pane active" id="step2">
		
                            	<table align="right" class="hide"><td><?php echo lang('template_element_count');?>
                                    <input id="weight" type="button" name="weight" class="fill-params btn btn-success btn-sm" maxlength="1" disabled value="5"/></td>
                                    <td>Page no.
                                    <!--<input id="divcount" type="button" class="fill-params btn btn-success btn-sm" maxlength="1" disabled value="" />-->
                                    <input id="totaldivcount" type="button" class="fill-params btn btn-success btn-sm" maxlength="1" disabled value="" />
                                    </td></table>
									<div class="row">
									<input id="divcount" type="button" class="fill-params btn btn-success btn-sm float-right" maxlength="1" disabled value="" />
									<label class='page_ float-right'>Page No</label>
									</div>
                            <div class="device">
                                    <center><label id="apptitle" name="apptitle" class="control-label apptit"><?php echo $feedback_name; ?></label></center>
    									<div id="mainpage" class="mainpage">
										</div><!-- div mainpage-->
                                        <div class="foraddbutton">
                                        <center><button type="button" id="AddMoreFileBox" class="btn btn-labeled btn-success"><span class="btn-label"><i class="glyphicon glyphicon-plus"></i></span></><?php echo lang('template_add_field_btn');?></button>
                                        <div id="FldCtrl">
                                        </div><!--//end of FldCtrl--></center>
                                        </div><!--for addbutton-->
                                          <?php 
    								$attributes = array('class' => 'tform', 'id' => 'designtemplate');
    								echo form_open_multipart('sub_app_builder/save_feedback_form', $attributes);?>
                               <footer><div class="row col col-12 menubottom pull-left"><button class="btn btn-default btn-labeled menubtn col col-12" type="button" id="prev"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span></>Previous</button><button class="btn btn-default menubtn btn-labeled col col-12" type="button" id="Deletepage" onClick="return confirm('<?php echo lang('delete_confirm')?>')" value="Delete Page"><span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span></>Delete</button><button class="btn btn-default btn-labeled menubtn col col-12" type="button" id="nxt" value="Next">Next</><span class="btn-label btn-label-right"><i class="glyphicon glyphicon-chevron-right"></i></span></button></div></footer>
    					  
	
		<input type="hidden" id="feedback_code"  name="feedback_code"  class='scaffold_textarea' value="<?php echo set_value('feedback_code', ''); ?>" />
 	<input type='hidden' name='pagenumber' id='pagenumber' value="" />
	<input id="feedback_id" type="hidden" name="feedback_id" value="<?php echo $feedback_id;?>"/>
 	
      <input type='hidden' id='jsondef' value='<?php echo set_value('jsondef', (isset($template)) ? json_encode($template) : ''); ?>' />
    <input type='hidden' id='updType' name='updType' value="<?php echo set_value('updType', $updType); ?>" />
    </div>
													<div class="row">
														<div class="col-sm-12">
															<button class="btn btn-primary btn-sm submit" id="">Submit</button>
														</div>
													</div>
											
								<?php echo form_close();?>
								
									</div>
								</div>
		
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
		
				</article>
				<!-- WIDGET END -->
				<article class="col-sm-12 col-lg-4">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-2" data-widget-editbutton="false" data-widget-deletebutton="false">
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
						<span class="widget-icon"> <i class="fa fa-paste"></i> </span>
                        <h2>Form Definition</h2> 
						</header>
		
						<!-- widget div-->
						<div>
		
							
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body fuelux">
		
								<div class="step-content">
										<div class="step-pane active" id="step2">
                            
											<div class="row">
												<div class="col-sm-12">
												<div class="row">
												Definition:
												</div>
												<div class="row">
												<textarea class="custom-scroll form-control col-md-6" id="" type="text" value="" placeholder="Description" disabled><?php echo set_value('feedback_desc', (isset($feedback_desc)) ? $feedback_desc : ''); ?></textarea>
												</div>
												<div class="row">
												Comment:
												</div>
												<div class="row">
												<textarea class="custom-scroll form-control col-md-6" id="" type="text" value="" placeholder="Description" disabled><?php echo set_value('feedback_comment', (isset($feedback_comment)) ? $feedback_comment : ''); ?></textarea>
												</div>
												</div>
											</div>
								
									</div>
								</div>
		
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
		
				</article>

		</div><!--row-->
				

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
<div class='clearfix'></div>
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
		<script src="<?php echo(JS.'bootstrap-tagsinput.js');?>" type="text/javascript"></script>
        <script src="<?php echo(JS.'feedback_template.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo(JS.'feedback_def.js'); ?>" type="text/javascript"></script>
      
		
<script type="text/javascript">
	
	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	$(document).ready(function() {
	
	})//document end

</script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>