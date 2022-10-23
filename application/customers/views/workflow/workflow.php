<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "App Design";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["designtemplate"]["active"] = true;
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
    <article class="col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="wid-id-2" data-widget-editbutton="false" data-widget-deletebutton="false">
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
							<h2>Application Design</h2>
		
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
		
								<div class="wizard">
									<ul class="steps">
										<li data-target="" class="">
											<span class="badge">2</span>App Design<span class="chevron"></span>
										</li>
										<li data-target="#step3" class="active">
											<span class="badge">3</span>Work Flow<span class="chevron"></span>
										</li>
                                        <li data-target="#step3">
											<span class="badge">4</span>App Notifications<span class="chevron"></span>
										</li>
										<!--<li data-target="#step4">
											<span class="badge">4</span>Step 4<span class="chevron"></span>
										</li>
										<li data-target="#step5">
											<span class="badge">5</span>Step 5<span class="chevron"></span>
										</li>-->
									</ul>
									<div class="actions">
										<!--<button type="button" class="btn btn-sm btn-primary btn-prev">
											<i class="fa fa-arrow-left"></i>Prev
										</button>-->
                                        <div class="submit">
										<button type="button" class="btn btn-sm btn-success" id="jsonsave" data-last="Finish">
											Next<i class="fa fa-arrow-right"></i>
										</button></div>
									</div>
								</div>
								<div class="step-content">
								<!--	<form class="form-horizontal" id="fuelux-wizard" method="post">-->
		
										<!--<div class="step-pane" id="step1">
											<h3><strong>App Properties</strong> - Validation states</h3>
                                            
										</div>

										<div class="step-pane" id="step2">
											<h3><strong>App Template</strong> - Alerts</h3>
		
       									</div>-->

										<div class="step-pane active" id="step2">
											
                                                       
	<div id="content" class="span10">
	<!-- start: Content -->
		<div>
		
<div id="divfrm" class="divf">
<div class="row branch">
<!--<table>
<tbody>
<tr>
<td>
<div id="square"></div></td><td><div class="hr-line"></div></td></tr>
<tr><td><div class="vertical-linebsc"></div></td><td></td></tr></tbody></table>-->
<!--<table>
<tbody>
<tr>
<td><div class="vertical-linebs"></div></td>
<td class="line"><div class="hr-line"></div></td>
<td><div id="square" class="breadcrumb"></div></td>
<td class="line"><div class="hr-line"></div></td>
<td><div class="vertical-linebs"></div></td>
</tr>
<tr>
<td><div class="vertical-linebinter"></div></td>
<td></td>
<td><div class="center-line vertical-linebinter"></div></td>
<td></td>
<td><div class="vertical-linebinter"></div></td>
</tr>
<tr>
<td class="line"><div class="vertical-lineb"></div></td>
<td></td>
<td><div class="center-line vertical-lineb"></div></td>
<td></td>
<td class="line"><div class="vertical-lineb"></div></td>
</tr>
<tr>
<td></td>
<td><div class="hr-line"></div></td>
<td><div class="hr-line center-hr-line"></div></td>
<td><div class="hr-line"></div></td>
<td></td>
</tr>
</tbody>
</table>-->
</div>
<!--<div class="hr-line"></div>
<div id="square" class="breadcrumb">
<button id="sss" class="btn btn-default btn-xs">adas</button>
</div><div class="hr-line"></div>
</div>-->
<div class="row">
<button class="btn bg-color-green txt-color-white btn-circle btflow" disabled><?php echo lang('workflow_start');?></button> 
</div>
<div class="infopop" id="dynamicdiv" align="">
<div id="clickline0" class="clickline active">
<div class="vertical-line">

</div>
</div>
</div>
<div class="row">
<button class="btn btn-danger btn-circle btflow" disabled><?php echo lang('workflow_end');?></button>
</div>
</div><!--//end of divfrm-->
<input type="text" class="hide" name"wrkjdata" id="wrkjdata"/>
<input type="text" class="hide" name="app_con" id="app_con" value='<?php echo set_value('app_con', (isset($controller_name)) ? $controller_name : ''); ?>'/>
<input type="text" class="hide" name="workflow" id="workflow" value='<?php echo set_value('workflow', (isset($workflow)) ? json_encode($workflow) : ''); ?>'/>
<input type="text" class="hide" name="app_mod" id="app_mod" value='<?php echo set_value('app_mod', (isset($model_name)) ? $model_name : ''); ?>'/>
<input type="text" class="hide" name="app_name" id="app_name" value='<?php echo set_value('app_name', (isset($appName)) ? $appName : ''); ?>'/>
<input type="text" class="hide" name="app_type" id="app_type" value='<?php echo set_value('app_type', (isset($apptype)) ? $apptype : ''); ?>'/>
<input type="text" class="hide" name="app_des" id="app_des" value='<?php echo set_value('app_des', (isset($appDescription)) ? $appDescription : ''); ?>'/>
<input type="text" class="hide" name="comp_name" id="comp_name" value='<?php echo set_value('comp_name', (isset($companyname)) ? $companyname : ''); ?>'/>
<input type="text" class="hide" name="comp_addr" id="comp_addr" value='<?php echo set_value('comp_addr', (isset($companyaddress)) ? $companyaddress : ''); ?>'/>
<input type="text" class="hide" name="workflow_mode" id="workflow_mode" value='<?php echo set_value('workflow_mode', (isset($updType)) ? $updType : ''); ?>'/>
<select  style="visibility:hidden" name='scaffold_model_type' id='scaffold_model_type'>
  <option value="activerecord">Codeigniter Active Record Class</option>
  <option value="phpactiverecord">PHP-ActiveRecord</option>
</select>
<input type='hidden' checked name='scaffold_delete_bd' id='scaffold_delete_bd' value="<?php echo set_value('scaffold_delete_bd', '1'); ?>" />
<input type='hidden' checked name='scaffold_bd' id='scaffold_bd' value='<?php echo set_value('scaffold_bd' , '1'); ?>' />
<input type='hidden' checked name='scaffold_routes' id='scaffold_routes' value='<?php echo set_value('scaffold_routes' , '1'); ?>' />
<input type='hidden' checked name='create_controller' id='create_controller' value='<?php echo set_value('create_controller', '1'); ?>' /> 
<input type='hidden' checked name='create_model' id='create_model' value='<?php echo set_value('create_model', '1'); ?>' />
<input type='hidden' checked name='create_view_create' id='create_view_create' value='<?php echo set_value('create_view_create', '1'); ?>' />
<input type='hidden' checked name='create_view_list' id='create_view_list' value='<?php echo set_value('create_view_list', '1'); ?>' />
<!--<button id="jsonsave" class="btn btn-sm btn-success pull-right"><i class="glyphicon glyphicon-floppy-disk"></i><?php echo lang('workflow_save');?></button>-->




</div>
			
		
		<div class="clearfix"></div>
        
        
				
		</div><!--/.fluid-container-->
		
       									</div>
		
								<!--</form>-->
								</div>
		
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
		
				</article>
				<!-- WIDGET END -->

		</div><!--row-->
				

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
		<script src="<?php echo(JS.'bootstrap-tagsinput.js');?>" type="text/javascript"></script>
        <script src="<?php echo(JS.'Workflowjson.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo(JS.'workflow.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo(JS.'wizard_wrk.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo(JS.'bootstrap-multiselect.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
	
	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	
$(document).ready(function() {
		// fuelux wizard
		  var wizard = $('.wizard').wizard();
		  
		  wizard.on('finished', function (e, data) {
		    //$("#fuelux-wizard").submit();
		    //console.log("submitted!");
		    $.smallBox({
		      title: "Congratulations! Your form was submitted",
		      content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
		      color: "#5F895F",
		      iconSmall: "fa fa-check bounce animated",
		      timeout: 4000
		    });
		    
		  });
})

</script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>