<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "SQL Import";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["tools"]["sub"]["sqlimport"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Tools"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
       <article class="col-sm-12 col-md-12 col-lg-6">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-10" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
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
							<span class="widget-icon"> <i class="fa fa-cloud-upload"></i> </span>
							<h2>SQL Import </h2>
		                </header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
											<?php 
											$attributes = array('class' => 'smart-form');
											echo form_open_multipart('dashboard/uploadSQL',$attributes);?>
											<div class="panel-body">
												<fieldset>
                                    				<section >
														<p>To upload a SQL table into our database select a file of .json format, select a application and press Import button.
														</p>
                                                    </section>
                                                <div class='row'>
	                                            <section class="pull-right">
	                                            <label class="select">
													<select id='appss' class='input-sm'>
														<?php if ($apps): ?>
														<?php foreach ($apps as $app):?>
														<option value='<?php echo $app->_id?>' ><?php echo ucfirst($app->app_name)?></option>
														<?php endforeach;?>
														<?php else: ?>
						        						<option value='#' ><?php echo lang('admin_no_apps');?></option>
						        						<?php endif ?>
	                                                </select> <i></i> </label>
                                              	</section>
                                              	</div>
                                              	
                                              	
                                              	<section>
													<div class="input input-file">
														<span class="button"><input type="file" id="file" name="file" onchange="this.parentNode.nextSibling.value = this.value">Browse</span><input type="text" placeholder="Include some files" readonly="">
													</div>
													<input type="hidden" id="appid"  name="appid"  class='textarea' />
		            								<input type="hidden" id="appname"  name="appname"  class='textarea' />
												</section>
												
                                                </fieldset>
											 </div>
                                            <footer>
												<button type="submit" class="btn bg-color-greenDark txt-color-white" name="submit" id="sbt">
                                             	Import
                                             	</button>
											</footer>
											<?php echo form_close();?>
											
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
	 	
?>
<script src="<?php echo(JS.'dynamic-add-import.js'); ?>" type="text/javascript"></script>
<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script>
$(document).ready(function() {
<?php if($message) {?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
				content : "<?php echo $message?>",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
<?php } ?>
});

$(document).on('click','#sbt',function()//create json
{
	document.getElementById('appid').value = $('#appss').val();
	document.getElementById('appname').value = $('#appss :selected').text();
});
</script>


<?php 
	//include footer
	include("inc/footer.php"); 
?>