<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "School List";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["school management"]["sub"]["control_school"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Manage Schools"] = "";
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
							<h2>All Schools <span class="badge bg-color-greenLight">
							<?php if(!empty($schoolscount))
								{?>
							<?php echo $schoolscount;?><?php 
							} else 
							{
								?><?php echo "0";?><?php 
								}?>
								</span></h2>
		
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
					<table id="dt_basic" class="table table-striped table-bordered table-hover">
					<?php if ($schools): ?>
					<tr>
						<th>School Name</th>
						<th>Email</th>
						<th>Mobile</th>
						<th>Contact Person</th>
						<th colspan = "3">Action</th>
					</tr>
					<?php foreach ($schools as $school):?>
                    <tbody>
					<tr>
						
						<td><?php echo ucwords($school["school_name"]) ;?></td>
						<td><?php echo $school["email"] ;?></td>
						<td><?php echo $school["mobile"] ;?></td>
						<td><?php echo $school["contact_person"] ;?></td>
					    <td>
							<?php echo ($school['active']) ? anchor("schoolhealth_admin_portal/deactivate_school/".$school['_id'], lang('index_deactivate_link')) : anchor("schoolhealth_admin_portal/activate_school/". $school['_id'], lang('index_activate_link'));?>
                        </td>
						
					</tr>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo "No schools entered yet.";?>
        			</p>
        			<?php endif ?>
									</tbody>
									<?php if($links):?>
									<tfoot>
									
                      <tr>
                         <td colspan="5">
                            <?php echo $links; ?>
                         </td>
                      </tr>
					   
				    </tfoot>
                   <?php endif ?>
								</table>
		
							</div>
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
<?php 
	//include footer
	include("inc/footer.php"); 
?>