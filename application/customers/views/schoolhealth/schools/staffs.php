<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Staffs";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["masters"]["sub"]["staffs"]["active"] = true;
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
	<h2>Create New Staff </h2>

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
	echo  form_open('schoolhealth_school_portal/create_staff',$attributes);
	?>

									<fieldset>
										<div class="row">
											<section class="col col-5">
											<label class="input"> <i class="icon-prepend fa fa-user"></i>
													<input type="text" name="staff_code" id="staff_code" placeholder="Emp Code">
												</label><br>
												<label class="input"> <i class="icon-prepend fa fa-user"></i>
													<input type="text" name="staff_name" id="staff_name" placeholder="Name">
												</label><br>
												<label class="input state-success"> <i class="icon-prepend fa fa-phone"></i>
													<input type="tel" name="staff_mob" id="staff_mob" placeholder="Mobile" data-mask="(999) 999-9999" class="valid">
												</label>
												
											</section>
											<section class="col col-5">
												<label class="input"> <i class="icon-prepend fa fa-key"></i>
													<input type="text" name="health_unique_id" value="MBK_100_STAFF1000" disabled>
												</label><br>
												<label class="input"> <i class="icon-prepend fa fa-calendar"></i>
													<input type="text"  placeholder="DOB" class="datepicker hasDatepicker" name="staff_dob" id="staff_dob" data-dateformat="dd/mm/yy">
												</label>
												<br>
												<label class="input">
													<input type="email" name="staff_email" placeholder="Email">
												</label>
											</section>
											<section class="col col-2">
											
											        <div class="logo_img logo" style="background-image: url('http://www.paas.com/PaaS/bootstrap/dist/img/avatars/male.png');"><h5 class="" id="click_upload"><center>Click here to upload</center></h5></div>
													<input type='file' id='file' name='logo_file' class="hide logo_file" value=""/>
													
													
											
											</section>
										</div>
									</fieldset>

									<fieldset>
										<div class="row">
											<section class="col col-4">
												<label class="select">
													<select name="category">
														<option value="0" selected="" disabled="">Category</option>
														<option value="teaching">Teaching</option>
														<option value="non_teaching">Non Teaching</option>
													</select>
												</label>
											</section>
											<section class="col col-4">
												<label class="input">
													<input type="text" name="staff_qualification" id="emp_qualification" placeholder="Emp Qualification">
												</label><br>
						
											</section>
											<section class="col col-4">
												
												<label class="input">
													<input type="text" name="staff_addr" id="emp_addr" placeholder="Emp Address" >
												</label>
											</section>
                                        </div>
                                    </fieldset>
									<footer>
										<button type="submit" class="btn btn-primary">
											Create Staff EHR
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
							<h2>All Staffs <span class="badge bg-color-greenLight"><?php if(!empty($staffcount)) {?><?php echo $staffcount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>
		
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
					<?php if ($emps): ?>
					<tr>
						<th>Employee Code</th>
						<th>Employee Name</th>
						<th>Mobile Number</th>
						<th>Email</th>
						<th>Address</th>
						<th>Qualification</th>
						<th>Photo</th>
						<th>Action</th>
					</tr>
					<?php foreach ($emps as $emp):?>
                    <tbody>
					<tr>
						<td><?php echo $emp["staff_code"] ;?></td>
						<td><?php echo ucwords($emp["staff_name"]) ;?></td>
						<td><?php echo $emp["staff_mob"] ;?></td>
						<td><?php echo $emp["staff_email"] ;?></td>
						<td><?php echo ucwords($emp["staff_addr"]) ;?></td>
						<td><?php echo ucwords($emp["staff_qualification"]) ;?></td>
						<td><img src="<?php echo $emp['staff_photo'];?>"/></td>
						<td><?php //echo anchor("panacea_mgmt/panacea_mgmt_manage_emp/".$emp['_id'], lang('app_edit')) ;?>
						
						<a class='ldelete' href='<?php echo URL."schoolhealth_school_portal/delete_staff/".$emp['_id'];?>'>
                			<?php echo lang('app_delete')?>
                			</a>
						</td>
					</tr>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo "No employee entered yet.";?>
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

<script>
	//uploading the logo in app creation //
$(document).ready(function() {
	
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

	    if($(".siblings_check").is(':checked'))
           $("#siblings").prop("disabled",false);  // checked
		else
		   $("#siblings").prop("disabled",true);   // unchecked
	   
	    $('.siblings_check').change(function() {
        if($(this).is(":checked")) 
		{
            $("#siblings").prop("disabled",false);
        }
		else
		{
	       $("#siblings").prop("disabled",true);
		}
        });
	});
	</script>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<?php 
	//include footer
	include("inc/footer.php"); 
?>