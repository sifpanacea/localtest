<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "User Billing";

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
				<article class="col-sm-12 col-md-6">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-group"></i> </span>
							<h2>Usage billing for <?php echo $user_details['first_name'];?></h2>
		
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
                            <?php
						 	$attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
							echo  form_open('admin_dash/create_user_billing',$attributes);
							?>
		      					<!--<form class="smart-form">-->
									<header>
										Please Enter Billing Plan.
									</header>
									<fieldset>
                                    <section>
                            			<label class="label" for="daily_docs_limit">Daily Document Limit</label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="number" name="daily_docs_limit" id="daily_docs_limit" value="<?PHP echo $daily_docs_limit; ?>" required>
										</label>
		     						</section>
		     						<section>
                            			<label class="label" for="cost_beyond_document_limit">Cost Beyond Document Limit</label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="number" name="cost_beyond_document_limit" id="cost_beyond_document_limit" value="<?PHP echo $cost_beyond_document_limit; ?>" required>
										</label>
		     						</section>									
                                    <section>
                            			<label class="label" for="daily_transaction_limit">Daily Transaction Limit</label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="number" name="daily_transaction_limit" id="daily_transaction_limit" value="<?PHP echo $daily_transaction_limit; ?>" required>
										</label>
		     						</section>
		     						<section>
                            			<label class="label" for="cost_beyond_transaction_limit">Cost Beyond Transaction Limit</label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="number" name="cost_beyond_transaction_limit" id="cost_beyond_transaction_limit" value="<?PHP echo $cost_beyond_transaction_limit; ?>" required>
										</label>
		     						</section>
		     						<section>
                            			<label class="label" for="cost_per_visit">Cost Per Visit</label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="number" name="cost_per_visit" id="cost_per_visit" value="<?PHP echo $cost_per_visit; ?>" required>
										</label>
		     						</section>
		     						<section>
                            			<label class="label" for="general_follow_up">General Follow Up</label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="number" name="general_follow_up" id="general_follow_up" value="<?PHP echo $general_follow_up; ?>" required>
										</label>
		     						</section>
		     						<section>
                            			<label class="label" for="in_week_follow_up">Follow Up(With in a week)</label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="number" name="in_week_follow_up" id="in_week_follow_up" value="<?PHP echo $in_week_follow_up; ?>" required>
										</label>
		     						</section>
		     						<section>
                            			<label class="label" for="discount">Discount(if any)</label>
                                			<label class="input"> <i class="icon-append fa fa-pencil"></i>
											<input type="number" name="discount" id="discount" value="<?PHP echo $discount; ?>" required>
										</label>
		     						</section>
		     						<input type="hidden" name="email" id="email" value="<?php echo $user_details['email'];?>">
		     						<input type="hidden" name="plan_type" id="plan_type" value="transaction_based">
		     						<input type="hidden" name="time" id="time" value="<?php echo date('Y-m-d H:i:s');?>">
                                   
									</fieldset>
									<footer>
										<button type="submit" class="btn bg-color-green txt-color-white submit">
											Submit
										</button>
										<button type="reset" class="btn btn-default">
											Clear
										</button>
									</footer>
								<?php echo form_close();?>
		
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