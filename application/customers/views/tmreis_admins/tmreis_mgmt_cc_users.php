<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "tmreis CC Users";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa mgmt"]["sub"]["cc_users"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["tmreis Masters"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	
	<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Create New Command Center User </h2>

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
	echo  form_open('tmreis_mgmt/create_cc_user',$attributes);
	?>
		<!--<form class="smart-form">-->
			<header>
				Please Enter The CC User Information.
			</header>
			<fieldset>
			<div class="row">
			<section class="col col-4">
				<label class="label" for="first_name">Name</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="cc_user_name" id="cc_user_name" value="<?PHP echo set_value('cc_user_name'); ?>" required>
				</label>
			</section>
			<section class="col col-4">
				<label class="label" for="first_name">Mobile Number</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="number" name="cc_user_mob" id="cc_user_mob" value="<?PHP echo set_value('cc_user_mob'); ?>" required>
				</label>
			</section>
			<section class="col col-4">
				<label class="label" for="first_name">Phone Number</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="number" name="cc_user_ph" id="cc_user_ph" value="<?PHP echo set_value('cc_user_ph'); ?>" required>
				</label>
			</section>
			</div>
			<div class="row">
			<section class="col col-4">
				<label class="label" for="first_name">Password</label>
					<label class="input"> <i class="icon-append fa fa-lock"></i>
					<input type="text" name="password" id="password" value="<?PHP echo set_value('password'); ?>" required>
				</label>
			</section>
			<section class="col col-4">
				<label class="label" for="first_name">Email</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="email" name="email" id="email" value="<?PHP echo set_value('email'); ?>" required>
				</label>
			</section>
			<section class="col col-4">
				<label class="label" for="first_name">Address</label>
				<label class="textarea textarea-expandable"> <i class="icon-append fa fa-envelope-o"></i>
				<textarea id="cc_user_addr" name="cc_user_addr" class="custom-scroll" ><?php echo set_value('cc_user_addr');?></textarea>
			
				
			</section>
			</div>
			
			</fieldset>
			<footer>
				<button type="submit" class="btn bg-color-green txt-color-white submit" >
					Create
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
</article>

</div><!-- ROW -->
	
<div class="row">
     				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>All Command Center Users <span class="badge bg-color-greenLight"><?php if(!empty($cc_count)) {?><?php echo $cc_count;?><?php } else {?><?php echo "0";?><?php }?></span></h2>
		
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
					<?php if ($cc_users): ?>
					<tr>
						<th>Name</th>
						<th>Contact Mobile</th>
						<th>Email</th>
						<th>Address</th>
						<th>Action</th>
					</tr>
					<?php foreach ($cc_users as $cc_user):?>
                    <tbody>
					<tr>
						<td><?php echo ucwords($cc_user["username"]) ;?></td>
						<td><?php echo $cc_user["mobile_number"] ;?></td>
						<td><?php echo $cc_user["email"] ;?></td>
						<td><?php echo ucwords($cc_user["company_address"]) ;?></td>
						<td><?php //echo anchor("tmreis_mgmt/tmreis_mgmt_manage_states/".$hs['_id'], lang('app_edit')) ;?>
						
						<a class='ldelete' href='<?php echo URL."tmreis_mgmt/tmreis_mgmt_delete_cc_user/".$cc_user['_id'];?>'>
                			<?php echo lang('app_delete')?>
                			</a>
						</td>
					</tr>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo "No command center user entered yet.";?>
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
<script>
$(document).ready(function() {
<?php if($message) {?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message!",
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