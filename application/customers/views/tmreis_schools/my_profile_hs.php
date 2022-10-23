<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Update Profile";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["my_profile"]["active"] = true;
include("inc/nav.php");

?>
<style>
.logo
{
	margin-left:10px;
	
	height:80px;
	width:90px;
	background-repeat: no-repeat;
	background-size:100%;
	border:1px dashed lightgrey;
}


#click_upload
{
	background-color:rgb(80, 77, 77);
	color: white;
	font-size: 12px;
	margin-top:60px;
}
#edit_photo
{
	border:none;
	height : 29px;
	margin-top : 10px;
	
}
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
<?php
//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
//$breadcrumbs["New Crumb"] => "https://url.com"
include("inc/ribbon.php");
?>

<!-- MAIN CONTENT -->
<div id="content">

<div class="row">
<!-- widget div-->
<div>

<!-- widget edit box -->
<div class="jarviswidget-editbox">
	<!-- This area used as dropdown edit box -->

</div>
<!-- end widget edit box -->
	 <!-- NEW WIDGET START -->
<article class="col-sm-12 col-md-8">


<!-- Widget ID (each widget will need unique ID)-->
<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-editbutton="false">
<header>
<span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>

</header>

<!-- widget div-->
<div>

<!-- widget edit box -->
<div class="jarviswidget-editbox">
	<!-- This area used as dropdown edit box -->

</div>
<!-- end widget edit box -->

<!-- widget content -->
  <?php
	$attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
	echo  form_open_multipart('tmreis_schools/update_principal_hs_profile',$attributes);
	?>

	<div class="tree smart-form">
	<ul>
		
		
		<li>
				<span><i class="fa fa-lg fa-folder-open"></i>&nbsp;<?php echo $principal_name;?></span>
				
				<ul>
				<li>
						<span class="label label-primary"><i class="fa fa-lg fa-minus-circle"></i> Personal Information</span>
						<ul>
							<li style="">
								<div class="well well-sm ">
								<table id="dt_basic" class="table table-striped table-bordered table-hover">
				                    <tbody>
				                    <tr>
										<th>School Name</th><td colspan="2"><i class="icon-leaf"><?php echo $school_name;?></i></td>
									</tr>

				                    <tr>
										<th>Principal Name</th><td><i class="icon-leaf"><input class="form-control" type="text" name="name" value="<?php if(isset($principal_name) && !empty($principal_name)):?><?php echo $principal_name?><?php else:?><?php echo "Name not available";?><?php endif;?>"></i></td>
									</tr>
									<tr>
										<th>Principal Mobile</th><td><i class="icon-leaf"><input class="form-control" type="number" name="mobile" value="<?php if(isset($principal_mob) && !empty($principal_mob)):?><?php echo $principal_mob;?><?php else:?><?php echo "Mobile Number not available";?><?php endIf;?>"></i></td>
									</tr>
									

									<tr>
										<th>HS Name</th><td><i class="icon-leaf"><input class="form-control" type="text" name="hs_name" value="<?php if(isset($hs_name) && !empty($hs_name)):?><?php echo $hs_name?><?php else:?><?php echo "Name not available";?><?php endif;?>"></i></td>
									</tr>
									<tr>
										<th>HS Mobile</th><td><i class="icon-leaf"><input class="form-control" type="number" name="hs_mobile" value="<?php if(isset($hs_mob) && !empty($hs_mob)):?><?php echo $hs_mob;?><?php else:?><?php echo "Mobile Number not available";?><?php endIf;?>"></i></td>
									</tr>
									
									</tbody>
								</table>
								
								</div>
							</li>							
						</ul>
					</li>
					</ul>
					
					</li>
						</ul>
						
<button type="button" class="btn btn-primary pull-right btn-sm" onclick="window.history.back();"style="margin-right:15px">Back</button>
<button type="submit" class="btn btn-success pull-right btn-sm" style="margin-right:6px">Update</button> <br><br><br>
</div>				
</div>
<!-- end widget content -->

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
<script src="<?php echo JS; ?>sweetalert.min.js"></script>
<!-- PAGE RELATED PLUGIN(S)--> 

<script type="text/javascript">
$(document).ready(function() {

<?php if($this->session->flashdata('success')): ?>

        	 swal({
                title: "Good job!",
                text: "<?php echo $this->session->flashdata('success'); ?>",
                icon: "success",
    
         	 });
      		 <?php elseif($this->session->flashdata('fail')): ?>
       		swal({
                title: "Failed!",
                text: "<?php echo $this->session->flashdata('fail'); ?>",
                icon: "error",
    
         	 });
       	<?php endif;?>
});
</script>
<!--<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<?php 
//include footer
include("inc/footer.php"); 
?>