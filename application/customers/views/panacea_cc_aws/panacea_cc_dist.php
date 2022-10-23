<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Panacea District";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa reports"]["sub"]["district"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["PANACEA Reports"] = "";
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
							<h2>All Districts <span class="badge bg-color-greenLight"><?php if(!empty($distscount)) {?><?php echo $distscount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>
		
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
					<?php if ($dists): ?>
					<tr>
						<th>State Name</th>
						<th>District Code</th>
						<th>District Name</th>
					</tr>
					<?php foreach ($dists as $dist):?>
                    <tbody>
					<tr>
						<td><?php echo ucwords($dist['st_name']) ;?></td>
						<td><?php echo ucwords($dist['dt_code']) ;?></td>
						<td><?php echo ucwords($dist['dt_name']) ;?></td>
						
					</tr>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo "No state entered yet.";?>
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

<script type="text/javascript">
	// Validation
	$(function() {
		// Validation
		$("#smart-form-register").validate({

			// Rules for form validation
			rules : {
				companyname : {
					required : true,
					minlength : 5,
					maxlength : 25
				},
				comp_type : {
					required : true
				},
				companywebsite : {
					required : true
				},
				companyaddress : {
					required : true
				},

				username : {
					required : true
				},
				email : {
					required : true,
					email : true
				},
				password : {
					required : true,
					minlength : 8,
					maxlength : 20
				},
				confirmpassword : {
					required : true,
					minlength : 8,
					maxlength : 20,
					equalTo : '#password'
				},
				mobile : {
					required : true,
					number : true
				},
				customer : {
					required : true
				},
				terms : {
					required : true
				}
			},

			// Messages for form validation
			messages : {
				companyname : {
					required : <?php echo lang('common_comp_name_req');?>,
					minlength : <?php echo lang('common_comp_name_min');?>,
					maxlength : <?php echo lang('common_comp_name_max');?>
				},
				comp_type : {
					required : <?php echo lang('common_type_req');?>
				},
				companywebsite : {
					required : <?php echo lang('common_comp_site_req');?>
				},
				companyaddress : {
					required : <?php echo lang('common_comp_addr_req');?>
				},
				username : {
					required : <?php echo lang('common_user_req');?>
				},
				email : {
					required : <?php echo lang('common_email_req');?>,
					email : <?php echo lang('common_email_valid');?>
				},
				password : {
					required : <?php echo lang('common_pass_req');?>,
					minlength : <?php echo lang('common_pass_min');?>,
					maxlength : <?php echo lang('common_pass_max');?>
				},
				confirmpassword : {
					required : <?php echo lang('common_con_pass_req');?>,
					minlength : <?php echo lang('common_con_pass_min');?>,
					maxlength : <?php echo lang('common_con_pass_max');?>,
					equalTo : <?php echo lang('common_con_pass_equal');?>
				},
				mobile : {
					required : <?php echo lang('common_mobile_req');?>,
					number : <?php echo lang('common_mobile_no');?>
				},
				customer : {
					required : <?php echo lang('common_customer_req');?>
				},
				terms : {
					required : <?php echo lang('common_tc_req');?>
				}
			},

			// Ajax form submition
			submitHandler : function(form) {
				$(form).ajaxSubmit({
					success : function() {
						$("#smart-form-register").addClass('submited');
					}
				});
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});

	});
</script>