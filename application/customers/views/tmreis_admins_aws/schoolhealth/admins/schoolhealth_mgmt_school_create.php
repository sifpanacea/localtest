<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Add School";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["school management"]["sub"]["add_school"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["school management"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	
	<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Create New School </h2>

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
	echo  form_open('schoolhealth_admin_portal/create_school',$attributes);
	?>
		<!--<form class="smart-form">-->
			<header>
				Please Enter The School Information.
			</header>
			<fieldset>
			<div class="row">
			<section class="col col-4">
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
			</label>
			</section>
			<section class="col col-4">
				<label class="label" for="first_name">District Name</label>
				<label class="select">
				<select name="dt_name" id="dt_name" required>
					<option value="" selected="" disabled="">Select a district</option>
					
				</select> <i></i>
			</label>
			</section>
			<section class="col col-4">
				<label class="label" for="first_name">School Code</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="number" name="school_code" id="school_code" value="<?PHP echo set_value('school_code'); ?>" required>
				</label>
			</section>
			</div>
			<div class="row">
			<section class="col col-4">
				<label class="label" for="first_name">School Name</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="school_name" id="school_name" value="<?PHP echo set_value('school_name'); ?>" required>
				</label>
			</section>
			
			<section class="col col-4">
				<label class="label" for="first_name">Address</label>
				<label class="textarea textarea-expandable"> <i class="icon-append fa fa-envelope-o"></i>
				<textarea id="school_addr" name="school_addr" class="custom-scroll" ><?php echo set_value('school_addr');?></textarea>	
			</section>
			
			
			<section class="col col-4">
				<label class="label" for="first_name">Email</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="email" name="school_email" id="school_email" value="<?PHP echo set_value('school_email'); ?>" required>
				</label>
			</section>
			</div>
			<div class="row">
			<section class="col col-4">
				<label class="label" for="school_password">Password</label>
					<label class="input"> <i class="icon-append fa fa-lock"></i>
					<input type="text" name="school_password" id="school_password" value="<?PHP echo set_value('school_password'); ?>" required>
				</label>
			</section>
			<section class="col col-4">
				<label class="label" for="mobile_number">Mobile Number</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="number" name="school_mob" id="school_mob" value="<?PHP echo set_value('school_mob'); ?>" required>
				</label>
			</section>
			
			<section class="col col-4">
				<label class="label" for="contact_person_name">Contact Person Name</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="contact_person" id="contact_person" value="<?PHP echo set_value('contact_person_name'); ?>" required>
				</label>
			</section>
			
			<section class="input-group col col-6">
									
										
											              <label class="select">
				<select name="sub_admin" id="sub_admin" required>
					<option value="" selected="" disabled="">Select a sub Admin</option>
					
				</select> <i></i>
			</label>
													<span class="input-group-addon">
																<span class="onoffswitch">
																	<input type="checkbox" name="sub_admin_check" class="sub_admin_check onoffswitch-checkbox" id="st3">
																	<label class="onoffswitch-label" for="st3"> 
																		<span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> 
																		<span class="onoffswitch-switch"></span> 
																	</label> 
																</span>
															</span>
			</section>
			
			<!--<section class="col col-3">
				<label class="checkbox" for="contact_person_name">Need Sub Admin?</label>
					<input type="checkbox" name="contact_person_name" id="contact_person_name" value="<?PHP echo set_value('contact_person_name'); ?>" required>
				</label>
			</section> 
			                  <section class="col-sm-10">
										
														<div class="input-group">
															<input class="form-control" placeholder="With switch" type="text">
															<span class="input-group-addon">
																<span class="onoffswitch">
																	<input type="checkbox" name="start_interval" class="onoffswitch-checkbox" id="st3">
																	<label class="onoffswitch-label" for="st3"> 
																		<span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> 
																		<span class="onoffswitch-switch"></span> 
																	</label> 
																</span>
															</span>
														</div>
													
														</section>
													
			
			
			<section>
			<label class="checkbox" for="first_name">Need sickroom ?</label>
			<input type="checkbox" name="contact_person_name" id="contact_person_name" value="" required>
			</section> -->
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
	
if($(".sub_admin_check").is(':checked'))
   $("#sub_admin").prop("disabled",false);  // checked
else
   $("#sub_admin").prop("disabled",true);   // unchecked
	   
	$('.sub_admin_check').change(function() {
	if($(this).is(":checked")) 
	{
		$("#sub_admin").prop("disabled",false);
	}
	else
	{
	   $("#sub_admin").prop("disabled",true);
	}
	});
	
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