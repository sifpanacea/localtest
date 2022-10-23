<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Add Clinic";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["clinic management"]["sub"]["create_clinic"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Clinic Management"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	
	
	<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Create New Clinic </h2>

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
	echo  form_open('schoolhealth_admin_portal/create_clinic',$attributes);
	?>
		<!--<form class="smart-form">-->
			<header>
				Please Enter The Clinic Information.
			</header>
			<fieldset>
			<div class="row">
			<section class="col col-3">
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
			<section class="col col-3">
				<label class="label" for="first_name">District Name</label>
				<label class="select">
				<select name="dt_name" id="dt_name" required>
					<option value="" selected="" disabled="">Select a district</option>
					
				</select> <i></i>
			</label>
			</section>
			
			<section class="col col-3">
				<label class="label" for="first_name">Clinic Name</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="clinic_name" id="clinic_name" value="<?PHP echo set_value('clinic_name'); ?>" required>
				</label>
			</section>
			
			
			<section class="col col-3">
				<label class="label" for="first_name">Address</label>
				<label class="textarea textarea-expandable"> <i class="icon-append fa fa-envelope-o"></i>
				<textarea id="clinic_addr" name="clinic_addr" class="custom-scroll" ><?php echo set_value('clinic_addr');?></textarea>	
			</section>
			</div>
			<div class="row">
			<section class="col col-3">
				<label class="label" for="first_name">Email</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="email" name="clinic_email" id="clinic_email" value="<?PHP echo set_value('clinic_email'); ?>" required>
				</label>
			</section>
			
			<section class="col col-3">
				<label class="label" for="school_password">Password</label>
					<label class="input"> <i class="icon-append fa fa-lock"></i>
					<input type="text" name="clinic_password" id="clinic_password" value="<?PHP echo set_value('clinic_password'); ?>" required>
				</label>
			</section>
			
			<section class="col col-3">
				<label class="label" for="mobile_number">Mobile Number</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="number" name="clinic_mob" id="clinic_mob" value="<?PHP echo set_value('clinic_mob'); ?>" required>
				</label>
			</section>
			
			<section class="col col-3">
				<label class="label" for="contact_person">Contact Person Name</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="contact_person" id="contact_person" value="<?PHP echo set_value('contact_person'); ?>" required>
				</label>
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