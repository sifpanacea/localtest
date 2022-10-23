<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Add Sub Admin";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["sub admin management"]["sub"]["add_sub_admin"]["active"] = true;
include("inc/nav.php");

?>
<style>
.logo
{
	margin-left:10px;
	float:left;
	height:125px;
	width:125px;
	background-repeat: no-repeat;
	background-size:100%;
	border:1px dashed lightgrey;
}

#click_upload
{
	background-color:rgb(80, 77, 77);
	color: white;
	font-size: 12px;
	margin-top:70px;
}

</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Manage Sub Admins"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	
	<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Create New Sub Admin</h2>

</header>

<!-- widget div-->
<div>

	<!-- widget edit box -->
	<div class="jarviswidget-editbox">
		<!-- This area used as dropdown edit box -->

	</div>
	<!-- end widget edit box -->

	<!-- widget content -->
	<?php $message = $this->session->flashdata('message');?>
	<div class="widget-body no-padding">
	<!--<form id="checkout-form" class="smart-form" novalidate="novalidate">-->
	<?php
	$attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
	echo  form_open_multipart('schoolhealth_admin_portal/create_sub_admin',$attributes);
	?>

									<fieldset>
										<div class="row">
											<section class="col col-5">
											<label class="label" for="first_name">State Name</label>
											<label class="select">
											<select name="st_name" id="st_name" required>
												<option value="" selected="" disabled="" >Select a state</option>
												<?php if(isset($statelist)): ?>
													<?php foreach ($statelist as $state):?>
													<option value='<?php echo $state['_id']?>' ><?php echo ucfirst($state['st_name'])?></option>
													<?php endforeach;?>
													<?php else: ?>
													<option value="1"  disabled="">No state entered yet</option>
												<?php endif ?>
											</select> <i></i>
										</label><br>
												<label class="label" for="first_name">Organization Name</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="organization_name" id="organization_name" value="<?PHP echo set_value('organization_name'); ?>" required>
				</label>
										</section>
											<section class="col col-5">
				<label class="label" for="first_name">District Name</label>
				<label class="select">
				<select name="dt_name" id="dt_name" required>
					<option value="" selected="" disabled="">Select a district</option>
					
				</select> <i></i>
			</label><br>
												<label class="label" for="first_name">Contact Person</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="contact_person" id="contact_person" value="<?PHP echo set_value('contact_person'); ?>">
				</label>
			</section>
			<section class="col col-2">
											<div>
													<div class="logo_img logo" style="background-image: url('http://www.paas.com/PaaS/bootstrap/dist/img/Library Filled-100.png');"><h5 class="" id="click_upload">Click here to upload logo&nbsp;&nbsp;( dimensions must be 246*52 px )</h5></div>
													<input type='file' id='file' name='logo_file' class="hide logo_file" value=""/>
													
												</div>
												</section>
							
										</div>
									</fieldset>

									<fieldset>
										<div class="row">
											<section class="col col-6">
				<label class="label" for="first_name">Email</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="email" name="email" id="email" value="<?PHP echo set_value('email'); ?>" required>
				</label>
			</section>
			
			<section class="col col-6">
				<label class="label" for="password">Password</label>
					<label class="input"> <i class="icon-append fa fa-lock"></i>
					<input type="text" name="password" id="password" value="<?PHP echo set_value('password'); ?>" required>
				</label>
			</section>
                                        </div>
                                        <div class="row">
										<section class="col col-6">
				<label class="label" for="mobile_number">Mobile Number</label>
					<label class="input"> <i class="icon-append fa fa-mobile"></i>
					<input type="number" name="mobile" id="mobile" value="<?PHP echo set_value('mobile'); ?>" required>
				</label>
			</section>
			
			<section class="col col-6">
				<label class="label" for="first_name">Address</label>
				<label class="textarea textarea-expandable"> <i class="icon-append fa fa-map-marker"></i>
				<textarea id="address" name="address" class="custom-scroll" ><?php echo set_value('address');?></textarea>	
			</section>
			
									   </div>
									</fieldset>
									
									<footer>
										<button type="submit" class="btn btn-primary">
											Create Sub Admin
										</button>
									</footer>
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
	
	<?php if($message) {?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
				content : "<?php echo $message?>",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
<?php } ?>

	    
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
		readURL(this);	
		
})
	

	
	$('#st_name').change(function(e){
	
	    var dist_option = "";
		state = $('#st_name option:selected').val();
		$('#dt_name').empty();
		$.ajax({
				url: 'get_districts_list_for_state',
				type: 'POST',
				data: {"state" : state},
				success: function (data) {	
                    console.log(data)	
                    data = data.trim()	
					
	                if(data != 'NO_DISTRICTS')
					{
					   $('#dt_name').removeAttr("disabled");
					   result = $.parseJSON(data);
					   console.log(result);
					   for(var i in result)
					   {
						  dist_option+= "<option value="+result[i]['_id']['$id']+">"+result[i]['dt_name']+"</option>"; 
						  $('#dt_name').html(dist_option)
					   }
					}
					else
					{
						dist_option = "<option value='no_districts'>No Districts</option>";
						$('#dt_name').html(dist_option)
						$('#dt_name').attr("disabled","disabled");
					}
					
					
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
		
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