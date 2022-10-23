<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "User Bill";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["customers usage"]["sub"]["customer"]["active"] = true;

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
     				<!-- NEW WIDGET START -->
				<article class="col-sm-12 col-md-8">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-group"></i> </span>
							<h2>Billing for <?php echo $user_details['first_name'];?></h2>
		
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
                            
                            <table id="dt_basic" class="table table-nobordered ">
								<?php if ($expense_details): ?>
								<tbody>
								
								<tr>
									<th>First Name</th><td colspan=3><?php echo ucfirst($user_details['first_name']) ;?></td>
									<th>Last Name</th><td><?php echo ucfirst($user_details['last_name']) ;?></td>
								</tr>
								<tr>
									<th>Phone</th><td colspan=3><?php echo $user_details['phone'] ;?></td>
									<th>Email</th><td><?php echo $user_details['email'] ;?></td>
								</tr>
								<tr>
									<th>Plan Subscribed</th><td colspan=3><?php echo ucfirst($user_details['plan_subscribed']) ;?></td>
									<th>For month of</th><td><?php echo $expense_details["month"] ;?></td>
								</tr>
								<tr>
									<th>Total New Documents Processed</th><td><?php echo $expense_details["total_new_docs"] ;?></td>
									<th>Documents Limit</th><td><?php echo $expense_details["doc_limit"] ;?></td>
									<th>Documents Beyond Limit</th><td><?php echo $expense_details["extra_docs"] ;?></td>
								</tr>
								<tr>
									<th>Cost of New Document Processed</th><td><?php echo $expense_details["new_doc_cost"] ;?></td>
									<th>Revisit with-in a Week</th><td><?php echo $expense_details["in_week_follow_cost"] ;?></td>
									<th>General Revisit</th><td><?php echo $expense_details["general_follow_cost"] ;?></td>
								</tr>
								<tr>
									
									<th>Total Transactions Made</th><td><?php echo $expense_details["total_transactions"] ;?></td>
									<th>Transactions Limit</th><td><?php echo $expense_details["transaction_limit"] ;?></td>
									<th>Transactions Beyond Limit</th><td><?php echo $expense_details["extra_transaction"] ;?></td>
								</tr>
								<tr>
									<th ><h6>Total Cost</h6></th><td colspan=5><h6><?php echo $expense_details['total_cost'] ;?></h6></td>
								</tr>
								<tr>
									<th >Discount(if any)</th><td colspan=5><?php echo $expense_details['discount']."% Effective discount: ". $expense_details['discount_cent'];?></td>
								</tr>
								<tr>
									<th ><h3>Grand Total</h3></th><td colspan=5><h3><?php echo $expense_details['grand_total'] ;?></h3></td>
								</tr>
								
								
								<?php else: ?>
			        			<p>
			          				<?php echo $message;?>
			        			</p>
			        			<?php endif ?>
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