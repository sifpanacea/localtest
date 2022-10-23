<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "ttwreis Doctors";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa mgmt"]["sub"]["doctors"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["ttwreis Masters"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	
	<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Create New Doctor </h2>

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
	echo  form_open('ttwreis_mgmt/create_doctor',$attributes);
	?>
		<!--<form class="smart-form">-->
			<header>
				Please Enter The Doctor Information.
			</header>
			<fieldset>
			<div class="row">
			<section class="col col-3">
				<label class="label" for="doc_name">Doctor Name</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="doc_name" id="doc_name" value="<?PHP echo set_value('doc_name'); ?>" required>
				</label>
			</section>
			<section class="col col-3">
				<label class="label" for="qualification">Qualification</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="qualification" id="qualification" value="<?PHP echo set_value('qualification'); ?>" required>
				</label>
			</section>
			<section class="col col-3">
				<label class="label" for="specification">Specification</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="specification" id="specification" value="<?PHP echo set_value('specification'); ?>" required>
				</label>
			</section>
			<section class="col col-3">
				<label class="label" for="password">Password</label>
					<label class="input"> <i class="icon-append fa fa-lock"></i>
					<input type="text" name="password" id="password" value="<?PHP echo set_value('password'); ?>" required>
				</label>
			</section>
			</div>
			<div class="row">
			<section class="col col-3">
				<label class="label" for="mob_number">Mobile Number</label>
					<label class="input"> <i class="icon-append fa fa-lock"></i>
					<input type="number" name="mob_number" id="mob_number" value="<?PHP echo set_value('mob_number'); ?>" required>
				</label>
			</section>
			<section class="col col-3">
				<label class="label" for="email">Email</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="email" name="email" id="email" value="<?PHP echo set_value('email'); ?>" required>
				</label>
			</section>
			<section class="col col-3">
				<label class="label" for="district">District</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="district" id="district" value="<?PHP echo set_value('district'); ?>" required>
				</label>
			</section>
			<section class="col col-3">
				<label class="label" for="address">Address</label>
				<label class="textarea textarea-expandable"> <i class="icon-append fa fa-envelope-o"></i>
				<textarea id="address" name="address" class="custom-scroll" ><?php echo set_value('address');?></textarea>
			
				
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
							<h2>All Doctors <span class="badge bg-color-greenLight"><?php if(!empty($doctorscount)) {?><?php echo $doctorscount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>
		
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
					<?php if ($doctors): ?>
					<tr>
						<th>Doctor Name</th>
						<th>Qualification</th>
						<th>Specification</th>
						<th>Mobile Number</th>
						<th>Email</th>
						<th>District</th>
						<th>Address</th>
						<th>Action</th>
					</tr>
					<?php foreach ($doctors as $doctor):?>
                    <tbody>
					<tr>
						<td><?php echo ucfirst($doctor["name"]) ;?></td>
						<td><?php echo ucwords($doctor["qualification"]) ;?></td>
						<td><?php echo ucfirst($doctor["specification"]);?></td>
						<td><?php echo $doctor["mobile_number"] ;?></td>
						<td><?php echo $doctor["email"] ;?></td>
						<td><?php echo $doctor["district"] ;?></td>
						<td><?php echo $doctor["company_address"] ;?></td>
						<td><?php //echo anchor("ttwreis_mgmt/ttwreis_mgmt_manage_states/".$hs['_id'], lang('app_edit')) ;?>
						
						<a class='ldelete' href='<?php echo URL."ttwreis_mgmt/ttwreis_mgmt_delete_doctor/".$doctor['_id'];?>'>
                			<?php echo lang('app_delete')?>
                			</a>
						</td>
					</tr>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo "No doctor entered yet.";?>
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
<br>
			<br>
			<br>
			<br>
			<br>
			<br>
				

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