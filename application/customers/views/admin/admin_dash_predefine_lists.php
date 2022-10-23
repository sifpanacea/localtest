<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Predefined Lists";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["tools"]["sub"]["predefinedlists"]["active"] = true;
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
     				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>Predefined Lists <span class="badge bg-color-greenLight"><?php if(!empty($lists)) {?><?php echo count($lists);?><?php } else {?><?php echo "0";?><?php }?></span></h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
								
		
							</div>
							<!-- end widget edit box -->
		                    <div class="panel-footer">
							Available Lists
							</div>
							<table id="dt_basic" class="table table-striped table-bordered table-hover">
					<?php $a = 0;?>
					<?php if ($lists): ?>
					<?php foreach ($lists as $app):?>
                    <tbody>
					<tr>
						<td><?php echo $app->list_name ;?></td>
					</tr>
					<?php $a++;?>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo lang('admin_no_predefinedlists');?>
        			</p>
        			<?php endif ?>
									</tbody>
								</table>
							<!-- widget content -->
							<!--<div class="widget-body no-padding">-->
							  <?php echo form_open_multipart('dashboard/create_list');?>
							  <div class="panel-footer">
							Create a new list
							</div>
					<table id="dt_basic" class="table table-bordered">
					<tr>
					<td>List Name </td><td><input id="list_name" name="list_name" type="text"/></td>
					</tr>
					<tr>
					<td> List Values </td><td><textarea id="list_values" name="list_values"></textarea></td>
					</tr>
					<tr>
					<td align="center"><input type="submit" value="create"/></td>
					</tr>
					</table>
					<?php echo form_close();?>
					<p align="center">(or)</p>
					
					<p>Upload a list file </p>
					<div class="panel-footer">
                                             	  <?php echo form_open_multipart('dashboard/upload_list');?>
                                                  
                     <section>
		            <input type="file" class="btn btn-default" name="file" id="file"/>
                    <input type="submit" name="submit" id="sbt" class="btn bg-color-greenDark txt-color-white" value="Upload"/ style="margin-top:10px;">
                    </section>
		            <input type="hidden" id="appid"  name="appid"  class='textarea' />
					<input type="hidden" id="code"  name="code"  class='textarea' />
                   <!-- <section>
					<input type="submit" name="submit" id="sbt" class="btn bg-color-greenDark txt-color-white" value="Import"/>
                    </section>-->
		            <?php echo form_close();?>
											</div>
					
		
							<!--</div>-->
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
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


<?php 
	//include footer
	include("inc/footer.php"); 
?>