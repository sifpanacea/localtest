<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Create Student";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["masters"]["sub"]["students"]["active"] = true;
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
		$breadcrumbs["Masters"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	
	<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Create New Student </h2>

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
	echo  form_open_multipart('bc_welfare_schools/add_student_ehr',$attributes);
	?>

									<fieldset>
										<div class="row">
											<section class="col col-5">
												<label class="input"> <i class="icon-prepend fa fa-user"></i>
													<input type="text" name="name" id="name" placeholder="Name">
												</label><br>
												<label class="input"> <i class="icon-prepend fa fa-phone"></i>
													<input type="tel" name="mobile" id="mobile" placeholder="Mobile" class="valid">
												</label>
											</section>
											<section class="col col-5">
												<label class="input"> <i class="icon-prepend fa fa-info-circle"></i>
													<input type="text" name="ad_no" id="ad_no" placeholder="Admission Number">
												</label><br>
												<label class="input"> <i class="icon-prepend fa fa-calendar"></i>
													<input type="text" name="date_of_birth" id="date_of_birth" placeholder="DOB" class="datepicker hasDatepicker" data-dateformat="dd/mm/yy">
												</label>
											</section>
											<section class="col col-2">
											
											        <div class="logo_img logo" style="background-image: url('https://mednote.in/PaaS/bootstrap/dist/img/avatars/male.png');"><h5 class="" id="click_upload"><center>Click here to upload</center></h5></div>
													<input type='file' id='file' name='logo_file' class="hide logo_file" value=""/>
													
													
											
											</section>
										</div>
									</fieldset>

									<fieldset>
										<div class="row">
											<section class="col col-6">
											<label class="input">
													<input type="text" name="helath_unique_id" id="helath_unique_id" value="<?php echo $huniqueid;?>" readOnly="readOnly">
												</label>	 
											</section>

											<section class="col col-6">
												<label class="input">
													<input type="text" name="father_name" id="father_name" placeholder="Father Name">
												</label>
											</section>
                                        </div>
                                        <div class="row">
										<section class="col col-2">
												<label class="select">
													<select name="class" id="class" required>
														<option value="0" selected="" disabled="">Select Class</option>
														<?php foreach($classes as $index=>$class):?>
														<option value="<?php echo $class['class_name'];?>"><?php echo $class['class_name'];?></option>
														<?php endForEach;?>
													</select>
												</label>
											</section>
										<section class="col col-2">
												<label class="select">
													<select name="section" id="section">
														<option value="0" selected="" disabled="">Select Section</option>
														<?php foreach($sections as $index=>$section):?>
														<option value="<?php echo $section['section_name'];?>"><?php echo $section['section_name'];?></option>
														<?php endForEach;?>
													</select>
												</label>
											</section>
											<section class="col col-4">
												<label for="district" class="input">
											
												<input type="text" name="district" id="district" value="<?php echo $district;?>" readOnly="true">
											
											</label>
											</section>
										<section class="col col-4">
											<label for="school_name" class="input">
											
												<input type="text" name="school_name" id="school_name" value="<?php echo $school_name;?>" readOnly="true">
											
											</label>
										</section>
									   </div>
									</fieldset>
									<footer>
										<button type="submit" class="btn btn-primary">
											Create Student EHR
										</button>
									</footer>
									<br>
									<br>
									<br>
									<br>
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
	    
		<?php if($message) { ?>
	$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
				content : "<?php echo $message?>",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
	<?php } ?>
	
	    
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