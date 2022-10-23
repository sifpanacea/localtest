<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Imports";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["imports"]["active"] = true;
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
       <article class="col-sm-12 col-lg-6">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-10" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
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
							<h2>Imports </h2>
		
							<!--<div class="widget-toolbar hidden-phone">
								<div class="smart-form">
									<label class="checkbox">
										<input type="checkbox" name="checkbox">
										<i></i>Add Padding</label>
								</div>
							</div>-->
		
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
		
								<div class="panel-group smart-accordion-default" id="accordion-2">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#sql"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> SQL Imports </a></h4>
										</div>
										<div id="sql" class="panel-collapse collapse in">
											<div class="panel-body">
												<fieldset>
                                    				<section class="col col-5">
														<p>To upload SQL database in .json file press Import
														Collection name will the same as name of the file you upload.</p>
                                                    </section>
                                                    <section class="col col-5">   
                                                        <input type="file" class="btn btn-default"/>
													</section>
                                                </fieldset>
											 </div>
                                             <div class="panel-footer">
                                             	<input type="submit" class="btn bg-color-greenDark txt-color-white" name="submit" value="Import"/>
                                            </div> 
										</div>
									</div>
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#nosql" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> NoSQL Imports </a></h4>
										</div>
										<div id="nosql" class="panel-collapse collapse">
											<div class="panel-body">
                                                <fieldset>
                                    				<section class="col col-5">
														<p>To upload No-SQL database in .json file press Import
														Collection name will the same as name of the file you upload.</p>
                                                    </section>
                                                    <section class="col col-5">   
                                                        <input type="file" class="btn btn-default"/>
													</section>
                                                </fieldset>
											 </div>
                                             <div class="panel-footer">
                                             	<input type="submit" class="btn bg-color-greenDark txt-color-white" name="submit" value="Import"/>
                                            </div> 
										</div>
									</div>
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#pic" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> Document Imports </a></h4>
										</div>
										<div id="pic" class="panel-collapse collapse">
											<div class="panel-body">
                                            <fieldset>
                                            <section class="col col-5">
												<p>To upload forms attached with "pdf" "gif", "jpeg", "jpg", "png" files in database press Import</p>
                                            </section>
                                            <section class="pull-right">
                                            <label class="select">
												<select id='appss'>
													<?php if ($apps): ?>
													<?php foreach ($apps as $app):?>
													<option value='<?php echo $app->_id?>' ><?php echo ucfirst($app->app_name)?></option>
													<?php endforeach;?>
													<?php else: ?>
					        						<option value='#' ><?php echo lang('admin_no_apps');?></option>
					        						<?php endif ?>
                                                </select> <i></i> </label>
                                              </section>
                                              <section>
                                              <ul class="nav nav-pills">
		              <li class="dropdown">
		                <a class="dropdown-toggle" id="drop4" role="button" data-toggle="dropdown" href="#">Elements <b class="caret"></b></a>
		                <ul id="menu1" class="dropdown-menu" role="menu" aria-labelledby="drop4">
		                  <li role="presentation"><a role="menuitem" tabindex="-1" id="single" >Single line</a></li>
		                  <li role="presentation"><a role="menuitem" tabindex="-1" id="multi" >Multi line</a></li>
		                  <li role="presentation"><a role="menuitem" tabindex="-1" id="date" >Date</a></li>
		                </ul>
		              </li>
		            </ul>
		            
		            <div id="mainpage" class="mainpage">
					
				    </div><!-- div mainpage-->
                                              </section>
                                              <section class="col col-5">   
                                                        
													</section>
                                                </fieldset>
                                               </div>
                                             <div class="panel-footer">
                                             	  <?php echo form_open_multipart('dashboard/uploadSingle');?>
                                                  
                     <section>
		            <input type="file" class="btn btn-default" name="file" id="file"/>
                    <input type="submit" name="submit" id="sbt" class="btn bg-color-greenDark txt-color-white" value="Import"/ style="margin-top:10px;">
                    </section>
		            <input type="hidden" id="appid"  name="appid"  class='textarea' />
					<input type="hidden" id="code"  name="code"  class='textarea' />
                   <!-- <section>
					<input type="submit" name="submit" id="sbt" class="btn bg-color-greenDark txt-color-white" value="Import"/>
                    </section>-->
		            <?php echo form_close();?>
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
				<!-- WIDGET END -->
        
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
<script src="<?php echo(JS.'dynamic-add-import.js'); ?>" type="text/javascript"></script>
<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



<?php 
	//include footer
	include("inc/footer.php"); 
?>