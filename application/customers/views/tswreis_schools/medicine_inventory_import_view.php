<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Medicine List Import";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["medicine"]["sub"]["medicine_import"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		//$breadcrumbs["PANACEA Imports"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
       <article class="col-sm-12 col-md-12 col-lg-6">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-10" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-cloud-upload"></i> </span>
							<h2>Medicine List Import </h2>
		                </header>
						<div>
							<div class="jarviswidget-editbox">
							</div>
					<?php $attributes = array('class' => 'smart-form'); echo form_open_multipart('tswreis_schools/import_medicine_inventory',$attributes);?>
											<div class="panel-body">
												<fieldset>
                                    				<section>
													    <p>To upload medicine data into our school select a file of excel format and press Import button.</p>
                                                    </section>                                              	
                                              	
                                              	<section>
													<div class="input input-file">
														<span class="button"><input type="file" id="file" name="file" accept=".xls,.xlsx" onchange="this.parentNode.nextSibling.value = this.value" required>Browse</span><input type="text" placeholder="Browse to import in excel format" readonly="">
													</div>
												</section>
												
                                                </fieldset>
											 </div>
                                            <footer>
												<button type="submit" class="btn bg-color-greenDark txt-color-white" name="submit" id="sbt" data-toggle="modal" data-target="#import_waiting" data-backdrop="static" data-keyboard="false">
                                             	Import
                                             	</button>
											</footer>
											<?php echo form_close();?>
											
										</div>
								</div>
						</article>
					</div>
					
					<!-- Modal -->
					<div class="modal fade" id="import_waiting" tabindex="-1" role="dialog" >
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title" id="myModalLabel">Import in progress</h4>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-12">
											<img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" style="display: block; margin: 0 auto; width: 100px;">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
			</div>
											
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
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Import Failed!",
				content : "<?php echo $message?>",
				color : "#C79121",
				iconSmall : "fa fa-bell bounce animated"
			});
<?php } ?>
});
</script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>
