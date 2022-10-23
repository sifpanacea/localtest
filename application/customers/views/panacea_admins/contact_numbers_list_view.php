<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Contacts List";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["contact_numbers"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["PANACEA Masters"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	
	<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2><?php echo $navigation; ?> </h2>
	<button type="button" class="btn btn-primary pull-right" onclick="window.history.back();">Go Back</button>
</header>



</div>
</article>

</div><!-- ROW -->
	
<div class="row">
     				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 HS hide" id="hide">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
							
							<!-- widget content -->
							<div class="widget-body no-padding table-responsive">
								
								<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover display" width="100%">
									
							        <thead>
										
							            <tr>
						                    <th>School Code</th>
						                    <th>School Name</th>
											<th>HealthSupervisors Name</th>
											<th>Mobile Number</th>
											<th>Phone Number</th>
											<th>Email</th>
											
							            </tr>
							        </thead>
		 							<tbody>
		 							<?php if(isset($contacts['hs'])): ?>
							        <?php foreach ($contacts['hs'] as $contact):?>
									<tr>
										<td><?php echo ucwords($contact["school_code"]) ;?></td>
										<td><?php echo ucwords($contact["hs_addr"]) ;?></td>
										<td><?php echo ucwords($contact["hs_name"]) ;?></td>
										<td><?php echo $contact["hs_mob"] ;?></td>
										<td><?php echo $contact["hs_ph"] ;?></td>
										<td><?php echo $contact["email"] ;?></td>
										
									</tr>
									<?php endforeach;?>
								<?php endif; ?>
									</tbody>
								</table>
							</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
					</article>




						<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 Principal hide" id="Principal">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							
							<div class="widget-body no-padding table-responsive">
					<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover display" width="100%">
									
							        <thead>
										
							            <tr>
						                    <th>School Code</th>
											<th>School Name</th>
											<th>Contact Person</th>
											<th>Contact Phone</th>
											<th>Contact Mobile</th>
											<th>Contact Email</th>
							            </tr>
							        </thead>
		 							<tbody>
		 								<?php if(isset($contacts['principal'])): ?>
							        <?php foreach ($contacts['principal'] as $contact):?>
                   
										<tr>
											<td><?php echo $contact["school_code"] ;?></td>
											<td><?php echo ucwords($contact["school_name"]) ;?></td>
											<td><?php echo $contact["contact_person_name"] ;?></td>
											<td><?php echo $contact["school_ph"] ;?></td>
											<td><?php echo $contact["school_mob"] ;?></td>
											<td><?php echo $contact["email"] ;?></td>
										</tr>
										<?php endforeach;?>
									<?php endif; ?>
									</tbody>
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
<script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>

<script>
$(document).ready(function() {
	$('table.display').DataTable( {
        fixedHeader: {
            header: true,
            footer: true
        }
    } );
		
	var type = '<?php echo $type;?>';
	console.log(type);
    $('.'+type+'').removeClass('hide');


});
</script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>