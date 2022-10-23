<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Update Doctor";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["refer_doctors"]/*["sub"]["list_doctors"]*/["active"] = true;
include("inc/nav.php");

?>
<style>
.logo
{
	margin-left:10px;
	float:left;
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
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Referral Doctors"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	
	<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Update Doctor Details </h2>

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
	<!--<form id="checkout-form" class="smart-form" novalidate="novalidate">-->
	<?php
	$attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
	echo  form_open_multipart('schoolhealth_sub_admin_portal/update_referral_doctor',$attributes);
	?>
									<?php foreach($refferal_doctor as $ref_doctor): ?>
									<fieldset>
										<div class="row">
											<section class="col col-5">
												<label class="input"> <i class="icon-prepend fa fa-user"></i>
													
													<input type="text" name="name" id="name" placeholder="Name" value = "<?php echo $ref_doctor['name']; ?>" required>
												</label><br>
												<label class="input"> <i class="icon-prepend fa fa-phone"></i>
													<input type="tel" name="mobile" id="mobile" placeholder="Mobile" class="valid"  value = "<?php echo $ref_doctor['mobile']['mob_num']; ?>" required>
												</label>
											</section>
											<section class="col col-5">
												<label class="input"> <i class="icon-prepend fa fa-envelope"></i>
													<input type="text" name="email" id="email" placeholder="Email"  value = "<?php echo $ref_doctor['email']; ?>" required>
												</label><br>
												<label class="input"> <i class="icon-prepend fa  fa-info"></i>
													<input type="text" name="qualification" id="qualification" placeholder="Qualification"  value = "<?php echo $ref_doctor['qualification']; ?>" required>
												</label>
											</section>
											<section class="col col-2">
											<div>
											        <div class="logo_img logo"><img src = "<?php echo URLCustomer.$ref_doctor['profile_pic_path'];?>" width="90px" height="80px"><h5 class="" id="click_upload"></h5></div>
													<input type='file' id='file' name='logo_file' class="hide logo_file" value=""/>
													
													
												</div>
												</section>
										</div>
									</fieldset>
									

									<fieldset>
                                        <div class="row">
										<section class="col col-6">
												<label for="address2" class="input">
												<input type="text" name="address" id="address" placeholder="Address" value = "<?php echo $ref_doctor['address']; ?>" required>
											</label>
											</section>
										<section class="col col-6">
										<label class="select">
												<select name="specialization" required>
														<?php foreach($specializations as $index=>$spec):?>
														<option value="<?php echo $spec['specialization_name'];?>" <?php echo preset_select('specialization',$spec['specialization_name'],(isset($ref_doctor['specialization']))?$ref_doctor['specialization']:'');?>><?php echo $spec['specialization_name'];?></option>
														<?php endForEach; ?>
														
													</select>
										</label>
										</section>
										</div>
									</fieldset>
									
									
									
									<footer>
									<input type="hidden" name="doctor_id" value="<?php echo $ref_doctor['_id']; ?>">
										<button type="submit" class="btn btn-primary">
											Update
										</button>
									</footer>
									<?php endForEach;?>
									<?php echo form_close();?>
								<!--</form>-->
	</div>
	<!-- end widget content -->

</div>
<!-- end widget div -->

</div>
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

<script>
	$(document).ready(function() {
	    
	
	
$(document).on('mouseover','.logo',function(){
$('#click_upload').removeClass('hide');
})

$(document).on('mouseleave','.logo',function(){
if(!$('#click_upload').hasClass("hide"))
{
	$('#click_upload').addClass('hide');
}
})	

//uploading the logo in app creation //
$(document).on('click','.logo',function()
	{
		$('.logo_file').trigger("click");
	});	

	function readURL(input) {
        if (input.files && input.files[0]) {
			//alert("success")
            var reader = new FileReader();
            
            reader.onload = function (e) {
                //$('.logo_img').attr('src', e.target.result);
				$('.logo_img').css("background-image","url("+e.target.result+")");
            }
            
            reader.readAsDataURL(input.files[0]);
        }
		else
		{
			console.log("fail");
		}
    }
	
//upload the logo when the user selects.//
$(document).on('change','.logo_file',function() 
{	
		console.log('logo',this)
		readURL(this);	
		
})

	    
	})
</script>
<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<?php 
	//include footer
	include("inc/footer.php"); 
?>