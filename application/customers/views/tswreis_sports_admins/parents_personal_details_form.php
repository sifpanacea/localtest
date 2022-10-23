<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Initiate Request";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa int_req"]["active"] = true;
include("inc/nav.php");

?>

<style type="text/css">
.jarviswidget-color-greenLight>header>.jarviswidget-ctrls a,
.jarviswidget-color-greenLight .nav-tabs li:not(.active) a {
	color: #1e1d1d!important
}

.jarviswidget-color-greenLight .nav-tabs li a:hover {
	color: #333!important
}
.text_area
{
	margin: 0px; height: 91px; width: 483px;
}
.file_attach_count 
{
	font-family: Segoe UI;
	font-size: 35px;
	color: green;
}
input[type="file"] {
    display: block;
  }
  .imageThumb {
    max-height: 100px;
    border: 2px solid;
    padding: 1px;
    cursor: pointer;
  }
  .pip {
    display: inline-block;
    margin: 10px 10px 50px 90px;
  }
</style>

<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<link href="<?php echo(CSS.'site.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo(CSS.'jquery.dataTables.min.css'); ?>">
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
//$breadcrumbs["New Crumb"] => "http://url.com"

	include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> <?php echo lang('admin_dash_home');?> <span>> <?php echo lang('admin_dash_board');?></span></h1>
			</div>
			
		</div>


		<div class="row">
			<article class="col-lg-offset-1 col-sm-12 col-md-12 col-lg-10">

				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-greenLight" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
<!-- widget options:
usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

data-widget-colorbutton="false"
data-widget-editbutton="false"
data-widget-togglebutton="false"
data-widget-deletebutton="false"
data-widget-fullscreenbutton="false"
data-widget-custombutton="false"
data-widget-collapsed="true"
data-widget-sortable="false"

-->
<header>
	<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
	<h2>Student Personal Info </h2>
	<span class="pull-right">
		<!-- <?php //foreach ($hs_req_docs as $unique):?> -->
		<form action='<?php echo URL."panacea_doctor/reports_display_ehr_uid_new_html_static_hs";?>' accept-charset="utf-8" method="POST">
			<input type="text" class ="hide" name="student_unique_id" id="student_unique_id" placeholder="Focus to view the tooltip" value="">
			
		<!-- <button type="submit" id="show_ehr" class="btn bg-color-greenDark txt-color-white btn-md show_ehr" style="margin-top: -10px;">Show EHR</button> -->
		<button type="button" class="btn btn-primary pull-right" onclick="window.history.back();">Back</button>
	</form>
<!-- <?php //endforeach;?> -->
	</span>
	

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
		echo  form_open_multipart('panacea_doctor/doctor_submit_request_docs',$attributes);
		?> 
		<fieldset>
		<!-- 	<?php //foreach ($hs_req_docs as $doc):?> -->
				
			<div class="row">
				<section class="col col-md-4">
					<label class="label">UNIQUE ID</label>
					<label class="input">
						<input type="text" name="unique_id" id="unique_id" placeholder="Focus to view the tooltip" value="" readOnly>
					</label>
				</section>
				<!-- <section class="col col-md-2">
					<br>
						<button class="btn btn-primary btn-lg hide" id="student_search_btn" value="" style=" height: 35px;
   						 width:-webkit-fill-available; margin-top: 5px;">Search</button>
				</section>

				<section class="pull-right">
					
						<div id="student_image"> </div>
					</section> -->

				<section class="col col-md-4">
					<label class="label">NAME</label>
					<label class="input"> 
			<input type="text" id='page1_StudentInfo_Name' rel='page1_Personal Information_Name' name='page1_StudentInfo_Name' value="">
					</label>
				</section>

				<section class="col col-md-4">
					<label class="label">CLASS</label>
					<label class="input"> 
						<input type="text" id='page1_StudentInfo_Class' type='number' rel='page2_Personal Information_Class' name='page1_StudentInfo_Class' value="">

					</label>
				</section>

			</div>
			
			<br>
			<div class="row">
				<section class="col col-md-4">
					<label class="label">SECTION</label>
					<label class="input">
						<input type="text" id='page1_StudentInfo_Section' rel='page2_Personal Information_Section' type='text' name='page1_StudentInfo_Section'  value="">
					</label>
				</section>
				<section class="col col-md-4">
					<label class="label">DISTRICT</label>
					<label class="input"> 
						<input type="text" id='page1_StudentInfo_District' rel='page2_Personal Information_District' type='text' name='page1_StudentInfo_District'   value="">

					</label>
				</section>
				<section class="col col-md-4">
					<label class="label">SCHOOL NAME</label>
					<label class="input"> 
						<input type="text" id='page1_StudentInfo_SchoolName' rel='page2_Personal Information_School Name' type='text' name='page1_StudentInfo_SchoolName'  value="">

					</label>
				</section>
			</div>
			
		</fieldset>

<fieldset>
	<!-- <div class="row">
		<div class="col col-md-2">
			<label class="radio radio-inline">	<input type="radio"  class="radiobox" id="normal" name="test1" value="Normal" ><span>EHR</span>
			</label>
		</div>
		<div class="col col-md-2">
			<label class="radio radio-inline">			
				<input type="radio"  class="radiobox" name="test1" id="emergency" value="Emergency"
				><span>Father Medical Info</span>
			</label>
		</div>
		<div class="col col-md-2">
			<label class="radio radio-inline">			
				<input type="radio"  class="radiobox" name="test1" id="chronic" value="Chronic"
				><span>Mother Medical Info</span>
			</label>
		</div>
		<div class="col col-md-2">
			<label class="radio radio-inline">			
				<input type="radio"  class="radiobox" name="test1" id="chronic" value="Chronic"
				><span>Sister Medical Info</span>
			</label>
		</div>
		<div class="col col-md-2">
			<label class="radio radio-inline">			
				<input type="radio"  class="radiobox" name="test1" id="chronic" value="Chronic"
				><span>Brother Medical Info</span>
			</label>
		</div>
	</div> -->

	<!-- Emergency Start-->
	
	<h4>About Students Family</h4>

	<div class="widget-body">
				
										
		<hr class="simple">
		<ul id="myTab1" class="nav nav-tabs bordered">
			<li class="active">
				<a href="#family_details" data-toggle="tab">Family Details<span class="badge bg-color-blue txt-color-white"></span></a>
			</li>
			<li>
				<a href="#doctor_analysis" data-toggle="tab">Doctor Analysis<span class="badge bg-color-blue txt-color-white"></span></a>
			</li>
			<li>
				<a href="#health_record" data-toggle="tab">EHR<span class="badge bg-color-blue txt-color-white"></span></a>
			</li>
			<!-- <li>
				<a href="#s4" data-toggle="tab"> Admitted <span class="badge bg-color-blue txt-color-white"></span></a>
			</li>
			<li>
				<a href="#s5" data-toggle="tab"> Out-Patient <span class="badge bg-color-blue txt-color-white"></span></a>
			</li>
			<li>
				<a href="#s6" data-toggle="tab"> Review <span class="badge bg-color-blue txt-color-white"></span></a>
			</li> -->
		</ul>

		<div id="myTabContent1" class="tab-content padding-10">
			<div class="tab-pane fade in active" id="family_details">
				About Family Details
				<legend><h4 class="text-primary">Father Details</h4></legend>
				<div class="row">
					<section class="col col-md-4">
						<label class="label">Father Name</label>
						<label class="input">
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="">
						</label>
					</section>
					<section class="col col-md-4">
						<label class="label">Age</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="">

						</label>
					</section>
					<section class="col col-md-4">
						<label class="label">Date Of Birth</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="">

						</label>
					</section>
				</div>
				<legend><h5 class="text-primary">Father Medical History</h></legend>
				<section>
					<textarea class='form-control' rows="5"  id=''  name=''></textarea>
				</section>
				<legend><h4 class="text-primary">Mother Details</h4></legend>
				<div class="row">
					<section class="col col-md-4">
						<label class="label">Mother Name</label>
						<label class="input">
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="">
						</label>
					</section>
					<section class="col col-md-4">
						<label class="label">Date of Birth</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="">

						</label>
					</section>
					<section class="col col-md-4">
						<label class="label">Age</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="">

						</label>
					</section>
				</div>
				<legend><h5 class="text-primary">Mother Medical History</h></legend>
				<section>
					<textarea class='form-control' rows="5"  id=''  name=''></textarea>
				</section>
				<legend><h4 class="text-primary">Brother Details</h4></legend>
				<div class="row">
					<section class="col col-md-4">
						<label class="label">Brother Name</label>
						<label class="input">
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="">
						</label>
					</section>
					<section class="col col-md-4">
						<label class="label">Date of Birth</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="">

						</label>
					</section>
					<section class="col col-md-4">
						<label class="label">Age</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="">

						</label>
					</section>
				</div>
				<legend><h5 class="text-primary">Brother Medical History</h></legend>
				<section>
					<textarea class='form-control' rows="5"  id=''  name=''></textarea>
				</section>
				<legend><h4 class="text-primary">Sister Details</h4></legend>
				<div class="row">
					<section class="col col-md-4">
						<label class="label">Sister Name</label>
						<label class="input">
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="">
						</label>
					</section>
					<section class="col col-md-4">
						<label class="label">Date of Birth</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="">

						</label>
					</section>
					<section class="col col-md-4">
						<label class="label">Age</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="">

						</label>
					</section>
				</div>
				<legend><h5 class="text-primary">Sister Medical History</h></legend>
				<section>
					<textarea class='form-control' rows="5"  id=''  name=''></textarea>
				</section>
			</div>
			<div class="tab-pane fade" id="doctor_analysis">
				<p>Doctor Analysis about Condition</p>
				<fieldset>
					<legend><h3 class="text-primary">Current Medical Condition</h3></legend>
					<section>
						<textarea class='form-control' rows="5"  id=''  name=''></textarea>
					</section>
				</fieldset>
				<fieldset>
					<legend><h3 class="text-primary">Parents Medical Condition</h3></legend>
					<section>
						<textarea class='form-control' rows="5"  id="" name=""></textarea>
					</section>
				</fieldset>
				<fieldset>		
					<section>
						<legend><h3 class="text-primary">Advice/Suggestion</h3></legend>
						<textarea class='form-control' rows="5"  id="" name=""></textarea>
					</section>
				</fieldset>
			</div>

	<?php if($docs): ?>
		<?php foreach($docs as $doc): ?>
			<div class="tab-pane fade" id="health_record">
				<div class="widget-body">
					<hr class="simple">
					<ul id="myTab" class="nav nav-tabs bordered">
						<li class="active">
							<a href="#Physical_Exam" data-toggle="tab">Physical Exam<span class="badge bg-color-blue txt-color-white"></span></a>
						</li>
						<li>
							<a href="#Doctor_Check_Up" data-toggle="tab">Doctor Check Up<span class="badge bg-color-blue txt-color-white"></span></a>
						</li>
						<li>
							<a href="#Vision_Screening" data-toggle="tab">Vision Screening<span class="badge bg-color-blue txt-color-white"></span></a>
						</li>
						<li>
							<a href="#Auditory_Screening" data-toggle="tab">Auditory Screening<span class="badge bg-color-blue txt-color-white"></span></a>
						</li>
						<li>
							<a href="#Dental_Checkup" data-toggle="tab">Dental Checkup<span class="badge bg-color-blue txt-color-white"></span></a>
						</li>
						<li>
							<a href="#Requests" data-toggle="tab">Requests<span class="badge bg-color-blue txt-color-white"></span></a>
						</li>
						<li>
							<a href="#Other_Attachments" data-toggle="tab">Other Attachments<span class="badge bg-color-blue txt-color-white"></span></a>
						</li>
					</ul>
					<div id="myTabContent" class="tab-content padding-10">
					<!-- Physical Exam -->
						<div class="tab-pane fade in active" id="Physical_Exam">
							<div class="well well-sm ">
								<table id="dt_basic" class="table table-striped table-bordered table-hover">
				                    <tbody>
									<tr>
										<th>Height cms</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Height cms']) && (!empty($doc['doc_data']['widget_data']['page3']['Physical Exam']['Height cms']))) : ?><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['Height cms']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
										<th>Weight kgs</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs']) && (!empty($doc['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs']))) : ?><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
									</tr>
									<tr>
										<th>BMI%</th><td><i class="icon-leaf"></i></td>
										<?php if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['BMI%']) && (!empty($doc['doc_data']['widget_data']['page3']['Physical Exam']['BMI%']))) : ?><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['BMI%'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?>
										<th>Pulse</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Pulse']) && (!empty($doc['doc_data']['widget_data']['page3']['Physical Exam']['Pulse']))) :?><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['Pulse'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
									</tr>
									
									<tr>
										<th>B P</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['B P']) && (!empty($doc['doc_data']['widget_data']['page3']['Physical Exam']['B P']))) :?><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['B P']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>

										<th>Blood Group</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group']) && (!empty($doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group']))) : ?><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
									</tr>
									
									<tr>
										<th>H B</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['H B']) && (!empty($doc['doc_data']['widget_data']['page3']['Physical Exam']['H B']))) :?><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['H B'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
									</tr>
									
									</tbody>
								</table>
							</div>
						</div>
					<!-- Doctor Check up -->
						<div class="tab-pane fade" id="Doctor_Check_Up">
							<div class="well well-sm ">
								<table id="dt_basic" class="table table-striped table-bordered table-hover">
				                    <tbody>
									<tr>
										<th>Abnormalities</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) && !empty($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'])) :?> 
										<?php echo (gettype($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'];?><?php endif; ?> </i></td>
										<th>Ortho</th><td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) : $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho'];?></i></td>
									</tr>
										
										<tr>
											<th>Description</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Description']) && (!empty($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Description']))) :?><?php echo $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Description'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
										</tr>
										<tr>
											<th>Advice</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Advice']) && !empty($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Advice'])) :?><?php echo $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Advice'];?> <?php else : ?> <?php echo "";?><?php endIf;?></i></td>
											<th>Postural</th><td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) : $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural'];?></i></td>
										</tr>
										<tr>
											<th>Skin conditions</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Skin conditions']) && !empty($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Skin conditions'])) :?> 
											<?php echo (gettype($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Skin conditions'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Skin conditions']) : $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Skin conditions'];?><?php endIf; ?> </i></td>
											<th>Defects at Birth</th><td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) : $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth'];?></i></td>
										</tr>
										<tr>
											<th>Deficencies</th><td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) : $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'];?></i>
											</td>
											<th>Childhood Diseases</th><td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) : $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases'];?></i></td>
										</tr>
										<tr>
											<th>N A D</th><td><i class="icon-leaf"><?php echo (isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['N A D']))?(gettype($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['N A D'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page5']['Doctor Check Up']['N A D']) : $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['N A D'] : "";?></i></td>
											<th class="hidden">General Physician Sign</th>
											<td class="hidden"><?php if(isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['General Physician Sign']) && !empty($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['General Physician Sign'])) :?>
											<img src="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page5']['Doctor Check Up']['General Physician Sign']['file_path'];?>" height="100" width="180"/><?php else: ?><?php echo "General Physician Sign Not Available";?><?php endif ;?></td>
										</tr>
										</tbody>
									</table>
								</div>
							</div>
						<!--Vision Screening  -->
						<div class="tab-pane fade" id="Vision_Screening">
							<div class="well well-sm ">
								<table id="dt_basic" class="table table-striped table-bordered table-hover">
						             <tbody>
									<tr>
										<th rowspan="2"><!--Without Glasses--> Presenting Vision </th><th>Right</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Right']) && (!empty($doc['doc_data']['widget_data']['page6']['Without Glasses']['Right']))) :?><?php echo $doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>

										<th rowspan="2">With Glasses</th><th>Right</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Right']) && (!empty($doc['doc_data']['widget_data']['page6']['With Glasses']['Right']))) :?><?php echo $doc['doc_data']['widget_data']['page6']['With Glasses']['Right'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
									</tr>
									<tr>
										<th>Left</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Left']) && (!empty($doc['doc_data']['widget_data']['page6']['Without Glasses']['Left']))) :?><?php echo $doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
										<th>Left</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Left']) && (!empty($doc['doc_data']['widget_data']['page6']['With Glasses']['Left']))) :?><?php echo $doc['doc_data']['widget_data']['page6']['With Glasses']['Left'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
									</tr>
									<tr>
										<th>Colour Blindness</th><!--<th>Right</th>--><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Right']) && (!empty($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Right']))) :?><?php echo $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Right'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
										<th>Description</th><td colspan="2"><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Description']) && (!empty($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Description']))) :?><?php echo $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Description'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
									</tr>
									<tr>
										<!--<th>Left</th><td><i class="icon-leaf"><?php// echo $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Left'];?></i></td>-->
										<th rowspan="2">Slit Lamp Examination</th><th>Conjunctiva</th><td><i class="icon-leaf"></i></td><th>Eye Lids</th><td><i class="icon-leaf"></i></td>
									</tr>									
									<tr>
										<th>Cornea</th><td><i class="icon-leaf"></i></td>
										<th>Pupil</th><td><i class="icon-leaf"></i></td>
									</tr>
									<tr>										
										<th>Complaints</th><td><i class="icon-leaf"></i></td>
										<th colspan="2">Wearing Spectacles</th><td><i class="icon-leaf"></i></td>
									</tr>
									<tr>	
										<th>Subjective Refraction</th>
										<th colspan="2">Ocular Diagnosis</th>
									</tr>
									<tr>	
										<th>Left</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Left'];?></i></td>
										<th>Referral Made</th><td colspan="2"><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Referral Made'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page7']['Colour Blindness']['Referral Made']) : $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Referral Made'];?></i></td>
									</tr>
									<tr>
										<th class="hidden">Opthomologist Sign</th>
										<td class="hidden"><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Opthomologist Sign']) && !is_null($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Opthomologist Sign']) && !empty($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Opthomologist Sign'])) : ?>
										<img src="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page7']['Colour Blindness']['Opthomologist Sign']['file_path'];?>" height="100" width="180" /> <?php else :?> <?php echo "Opthomologist Sign Not Available";?> <?php endIf;?></i></td>
									</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!-- Auditory Screening -->
						<div class="tab-pane fade" id="Auditory_Screening">
							<div class="well well-sm ">
									<table id="dt_basic" class="table table-striped table-bordered table-hover">
					                    <tbody>
										<tr>
											<th rowspan="2">Auditory Screening</th><th>Right</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Right']) && (!empty($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Right']))) :?>	<?php echo $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Right'];?><?php else : ?><?php echo "Nill"; ?>		<?php endif; ?></i></td>
											<th>Speech Screening</th><td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) : $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening'];?></i></td>
										</tr>
										<tr>
											<th>Left</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Left']) && (!empty($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Left']))) :?><?php echo $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Left'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>

											<th>D D and disability</th><td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disability'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disability']) : $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disability'];?></i></td>
										</tr>
										<tr>
											<th>Description</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Description']) && (!empty($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Description']))) :?><?php echo $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Description'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>

											<th>Referral Made</th><td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made']) : $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made'];?></i></td>
										</tr>
										<tr>
											<th class="hidden">Audiologist Sign</th>
											<td class="hidden"><?php if(isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Audiologist Sign']) && !is_null($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Audiologist Sign']) && !empty($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Audiologist Sign'])) : ?>
											<img src="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Audiologist Sign']['file_path'];?>" height="100" width="180"/> <?php else :?><?php echo "Audiologist Sign Not Available";?> <?php endIf;?></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!-- Dental Screening -->
								<div class="tab-pane fade" id="Dental_Checkup">
									<div class="well well-sm ">
										<table id="dt_basic" class="table table-striped table-bordered table-hover">
						                    <tbody>
						                    	
											<tr>
												<th>Oral Hygiene</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene']) && (!empty($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene']))) :?><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
												<th>Carious Teeth</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Carious Teeth']) && (!empty($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Carious Teeth']))) :?><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Carious Teeth'] ;?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
											</tr>
											<tr>
												<th>Flourosis</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Flourosis']) && (!empty($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Flourosis']))) :?><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Flourosis'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
												<th>Orthodontic Treatment</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Orthodontic Treatment']) && (!empty($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Orthodontic Treatment']))) :?><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Orthodontic Treatment'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
											</tr>
											<tr>
												<th>Indication for extraction</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Indication for extraction']) && (!empty($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Indication for extraction']))) :?><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Indication for extraction'];?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></i></td>
												<th>Root Canal Treatment</th><td><i class="icon-leaf"></i></td>
											</tr>
											<tr>
												<th>CROWNS</th><td><i class="icon-leaf"></i></td>
												<th>Fixed Partial Denture</th><td><i class="icon-leaf"></i></td>
											</tr>
											<tr>
												<th>Curettage</th><td><i class="icon-leaf"></i></td>
												<th>Estimated Amount</th><td><i class="icon-leaf"></i></td>
												
											</tr>
											<tr>
												<th>Result</th><td><i class="icon-leaf"></i></td>
												<th>Referral Made</th><td><i class="icon-leaf"></i></td>
											</tr>
											
											</tbody>
										</table>
									</div>
								</div>
						<!-- Reuquests  -->
						<div class="tab-pane fade" id="Requests">
							Requests
						</div>
						<!-- Other Attachements -->
						<div class="tab-pane fade" id="Other_Attachments">
							<div class="well well-sm ">
								<center>
									<p>Attachment</p>
								<img src="<?php //echo URLCustomer.$attachment['file_path'];?>" width="50" height="50"/>
								</center>
							</div>
						</div>

						<!-- End Data -->

						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
			
		<!-- End EHR Info -->
			</div>
		</div>
	</div>
</div>

		

</fieldset>
			

	<footer>
		<div class="row">
       		<!-- <div class="col-md-7">
        		<form action="">
				<a class="btn btn-primary btn-xs" href="https://mednote.in/PaaS/healthcare/index.php/panacea_doctor/doctor_analysis">Show Analysis</a>
				</form>
      		</div> -->

	      	<div class="col-md-4">
				<!-- <button type="button" class="btn btn-primary pull-right" onclick="window.history.back();">Back</button> -->
			</div>
    	</div>
	</footer>
							<!-- 	<?php //endforeach;?> -->
								<?php echo form_close();?>

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

	<script type="text/javascript">                                 
                        show_if_hospital_checked();
                      /*  var today_date = $('#set_date').val();
                        //$('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
                        $('#set_date').change(function(e){
                                today_date = $('#set_date').val();;
                        });   */

    $('#hospital_transfer_id').click(function(){

        if($(this).is(":checked")){
            $('#hospital_transfer').show();
        }else{
            $('#hospital_transfer').hide();
        }
    });

    $('.selected_status').change(function(){
    	
        var status = $('.selected_status').val();
         // alert(status);
        if(status == 'Hospitalized' || status == 'Out-Patient' || status == 'Review'){        	
            $('#date_of_join').show();
        }else{
            $('#date_of_join').hide();
        };
        if(status == 'Discharge'){
            $('#date_of_discharge').show();
        }else{
            $('#date_of_discharge').hide();
        }

    });

    function show_if_hospital_checked(){
        var status = $('.selected_status').val();
        if(status == 'Hospitalized' || status == 'Out-Patient' || status == 'Review'){
            $('#date_of_join').show();
        }else{
            $('#date_of_join').hide();
        };
        if(status == 'Discharge'){
            $('#date_of_discharge').show();
        }else{
            $('#date_of_discharge').hide();
        }
    }

  </script>
  
<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <!-- <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script> -->
  <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdn.bootcss.com/prettify/r298/prettify.min.js"></script>
  <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="<?php echo JS; ?>img_options/jquery.magnify.js"></script>

<!-- PAGE RELATED PLUGIN(S) 
	<script src="..."></script>-->
	<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
	
	<script type="text/javascript">
		$('.datepicker').datepicker({
			minDate: new Date(1900, 10 - 1, 25)
		});
	</script>
	<script type="text/javascript">
		$(function () {
        $("#followup_id").click(function () {
            if ($(this).is(":checked")) {
                $("#follow_date").show();
            } else {
                $("#follow_date").hide();
            }
        });
    });
	</script>

	<script type="text/javascript">
		$(document).ready(function() {
			$("a[rel^='prettyPhoto']").prettyPhoto();
			var normal = $("#normal").val();
			if(typeof normal != "undefined")
			{
				$('.general_related').show();
			}
			//$('.general_related').hide();
			$('.emergency_related').hide();
			$('.chronic_related').hide();

			$('#normal').click(function(){

				$('.general_related').show();
				$('.emergency_related').hide();
				$('.chronic_related').hide();

				var oldoptions = [];

				$("[type=radio]").on('click', function () {
				    $("#page2_ReviewInfo_RequestType").append(oldoptions);
				    oldoptions = $("#page2_ReviewInfo_RequestType option:not(:contains(" + $(this).val() + "))").detach();
				});

			});

			$("input[id='normal']").on('change',function() {
			         
		         if($(this).is(':checked') && $(this).val() == 'Normal')
		         {
		           $('#page2_ReviewInfo_RequestType').empty()
		          
		            $('#page2_ReviewInfo_RequestType').append('<option value="Normal">Normal</option>');
		                          
		         }  
			 });

			$("input[id='emergency']").on('change',function() {
			         
		         if($(this).is(':checked') && $(this).val() == 'Emergency')
		         {
		           $('#page2_ReviewInfo_RequestType').empty()
		          
		            $('#page2_ReviewInfo_RequestType').append('<option value="Emergency">Emergency</option>');
		                          
		         }
		              
			 });

			$("input[id='chronic']").on('change',function() {
			         
		         if($(this).is(':checked') && $(this).val() == 'Chronic')
		         {
		           $('#page2_ReviewInfo_RequestType').empty()
		          
		            $('#page2_ReviewInfo_RequestType').append('<option value="Chronic">Chronic</option>');
		                          
		         }
		              
			 });


			$('#emergency').click(function(){
				
				$('.emergency_related').show();
				$('.general_related').hide();
				$('.chronic_related').hide();

			});
			//CHRONIC RELATED SCRIPT
			
			$('#chronic').click(function(){
				
				$('.chronic_related').show();
				$('.general_related').hide();
				$('.emergency_related').hide();

			});

			$('#student_search_btn').click(function() {
				var unique_id = $('#unique_id').val();

//$('#unique_id').val(unique_id);
$.ajax({
	url: 'fetch_student_info',
	type: 'POST',
	data: {'unique_id':unique_id },
	success:function(data){

		data = $.parseJSON(data);
		get_data = data.get_data;
		console.log('get_data',get_data);
		$.each(get_data, function() {
			$("#page1_StudentInfo_Name").val(this['doc_data']['widget_data']['page1']['Personal Information']['Name']);
			$("#page1_StudentInfo_District").val(this['doc_data']['widget_data']['page2']['Personal Information']['District']);
			$("#page1_StudentInfo_SchoolName").val(this['doc_data']['widget_data']['page2']['Personal Information']['School Name']);
			$("#page1_StudentInfo_Class").val(this['doc_data']['widget_data']['page2']['Personal Information']['Class']);
			$("#page1_StudentInfo_Section").val(this['doc_data']['widget_data']['page2']['Personal Information']['Section']);
			
			$('<img style="height:100px; width:100px; border-radius:10px; border:solid 2px grey"/>')
                        .attr('src', "<?php echo URLCustomer;  ?>" + this['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'] + "")
                        
                        .appendTo($('#student_image'));

			
		});

	},
	error:function(XMlHttpRequest, textStatus, errorThrown) {
		console.log('error',errorThrown);

	}
})

return false;

});
			$('.external_file_attachments').children().each(function (index)
				{
					var href_ = $(this).attr("href")
					var name_ = $(this).attr("name")
					var in_val = index+1;
					if(in_val%2==0)
					{
					
					$("<span style=\"height:55px;\"><b class=\"ind_val\"></b><b class=\"word_break\"><a href="+href_+" rel='prettyPhoto[gal]'>&nbsp;<img class=\"img-thumbnail\" src=\"" + href_ + "\" style =\"width:150px;height:150px;\" title=\"" + name_ + "\"/>&nbsp;</a></b></span>").appendTo('.files_attached');
					}
					else
					{
					
					$("<span class=\"active\" style=\"height:55px;\"><b class=\"ind_val\"></b><b class=\"word_break\"><a href="+href_+" rel='prettyPhoto[gal]'>&nbsp;<img class=\"img-thumbnail\" src=\"" + href_ + "\" style =\"width:150px;height:150px;\" title=\"" + name_ + "\"/> &nbsp;</a></b></span>").appendTo('.files_attached');	
					}
				})
				$("a[rel^='prettyPhoto']").prettyPhoto();
				if($('.files_attached').children('span').length==0)
				{
					$('<tr class="" style="height:55px;"><td class=""><h1><b>No external files attached<b></h1></td></tr>').appendTo('.files_attached');	
				}

			
		    

		});
	</script>
	<script type="text/javascript">
		$("a[rel^='prettyPhoto']").prettyPhoto();
		if (window.File && window.FileList && window.FileReader) 
		{
				
		//var numFiles = $("input:file")[0].files.length;
		 $("#files_prescriptions").on("change", function(e) {
			
			var files = e.target.files,
	        filesLength = files.length;
	        console.log('filesLength',filesLength);
		    for(var j=0;j<filesLength;j++)
		    {
		    	var f = files[j]
		        var fileReader = new FileReader();
		        fileReader.onload = (function(e) {
		          var file = e.target;
		          $("<span   class=\"pip\">" +
	            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
	            "<br/><span class=\"remove\">Remove image</span>" +
	            "</span>").prependTo("#panel668");
		          $(".remove").on('click',function(){
		            $(this).parent(".pip").remove();
		          });
		        });
        		 fileReader.readAsDataURL(f);

		     var size = f.size;
			 if(size > 2000000 )
			 {
		        $.bigBox({
					title   : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message !",
					content : "Attach file less than 2 MB !",
					color   : "#C46A69",
					icon    : "fa fa-warning shake animated",
					timeout : 8000
			    });
				
				e.preventDefault();
				$("input:file").val(""); 
				var no_of_files = $("input:file")[0].files.length;
			    var count = no_of_files+' files attached';
			    $('.file_attach_count').text(count);
          		$(this).removeClass("hide");
				break;
				
			 }
		    }
		     $("input:file").html("#files_prescriptions");
		 	
		 	//var files = $(".imageThumb").array(); 
		});

		}
	</script>
	<script type="text/javascript">
		$("a[rel^='prettyPhoto']").prettyPhoto();
		if (window.File && window.FileList && window.FileReader) 
		{
				
		//var numFiles = $("input:file")[0].files.length;
		 $("#files_labs").on("change", function(e) {
			$(".remove").parent(".pip").remove();
			var files = e.target.files,
	        filesLength = files.length;
	        console.log('filesLength',filesLength);
		    for(var j=0;j<filesLength;j++)
		    {
		    	var f = files[j]
		        var fileReader = new FileReader();
		        fileReader.onload = (function(e) {
		          var file = e.target;
		          $("<span   class=\"pip\">" +
	            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
	            "<br/><span class=\"remove\">Remove image</span>" +
	            "</span>").prependTo("#panel555");
		          $(".remove").on('click',function(){
		            $(this).parent(".pip").remove();
		          });
		        });
        		 fileReader.readAsDataURL(f);

		     var size = f.size;
			 if(size > 2000000 )
			 {
		        $.bigBox({
					title   : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message !",
					content : "Attach file less than 2 MB !",
					color   : "#C46A69",
					icon    : "fa fa-warning shake animated",
					timeout : 8000
			    });
				
				e.preventDefault();
				$("input:file").val(""); 
				var no_of_files = $("input:file")[0].files.length;
			    var count = no_of_files+' files attached';
			    $('.file_attach_count').text(count);
          		$(this).removeClass("hide");
				break;
				
			 }
		    }
		     //$("input:file").html("#files_labs");
		 	
		 	//var files = $(".imageThumb").array(); 
		});

		}
	</script>
	<script type="text/javascript">
		$("a[rel^='prettyPhoto']").prettyPhoto();
		if (window.File && window.FileList && window.FileReader) 
		{
	
			$("#files_xray").on("change", function(e) {
			var files = e.target.files,
	           filesLength = files.length;
	        console.log('filesLength',filesLength);
		    for(var j=0;j<filesLength;j++)
		    {
		    	var f = files[j]
		        var fileReader = new FileReader();
		        fileReader.onload = (function(e) {
		          var file = e.target;
		          $("<span   class=\"pip\">" +
	            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
	            "<br/><span class=\"remove\">Remove image</span>" +
	            "</span>").prependTo("#panel666");
		          $(".remove").on('click',function(){
		            $(this).parent(".pip").remove();
		          });
		        });
        		 fileReader.readAsDataURL(f);

		     var size = f.size;
			 if(size > 2000000 )
			 {
		        $.bigBox({
					title   : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message !",
					content : "Attach file less than 2 MB !",
					color   : "#C46A69",
					icon    : "fa fa-warning shake animated",
					timeout : 8000
			    });
				
				e.preventDefault();
				$("input:file").val(""); 
				var no_of_files = $("input:file")[0].files.length;
			    var count = no_of_files+' files attached';
			    $('.file_attach_count').text(count);
          		$(this).removeClass("hide");
				break;
				
			 }
		    }
		     //$("input:file").html("#files_xray");
		 	
		 	//var files = $(".imageThumb").array(); 
		});

		}
	</script>
	<script>
		$("a[rel^='prettyPhoto']").prettyPhoto();
		if (window.File && window.FileList && window.FileReader) 
		{
				
		//var numFiles = $("input:file")[0].files.length;
		 $("#files_bills").on("change", function(e) {
			var files = e.target.files,
			filesLength = files.length;

	        console.log("dsfdfsfsdfsfsdfds",files);
	        console.log('filesLength',filesLength);
		    for(var j=0;j<filesLength;j++)
		    {
		    	var f = files[j];
		        var fileReader = new FileReader();
		        fileReader.onload = (function(e) {
		          var file = e.target;
		          $("<span   class=\"pip\">" +
	            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
	            "<br/><span class=\"remove\">Remove image</span>" +
	            "</span>").prependTo("#panel667");
		          $(".remove").on('click',function(){
		            $(this).parent(".pip").remove();
		          });
		        });
        		 fileReader.readAsDataURL(f);

		     //var size = $("input:file")[0].files[j].size;
		     var size = f.size;
			 if(size > 2000000 )
			 {
		        $.bigBox({
					title   : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message !",
					content : "Attach file less than 2 MB !",
					color   : "#C46A69",
					icon    : "fa fa-warning shake animated",
					timeout : 8000
			    });
				
				e.preventDefault();
				$("input:file").val(""); 
				var no_of_files = $("input:file")[0].files.length;
			    var count = no_of_files+' files attached';
			    $('.file_attach_count').text(count);
          		$(this).removeClass("hide");
				break;
				
			 }
		    }
		     
		});

		}
	</script>
	<script>
		$("a[rel^='prettyPhoto']").prettyPhoto();
		if (window.File && window.FileList && window.FileReader) 
		{
				
		//var numFiles = $("input:file")[0].files.length;
		 $("#files_ds").on("change", function(e) {
			var files = e.target.files,
			filesLength = files.length;

	       
	        
		    for(var j=0;j<filesLength;j++)
		    {
		    	var f = files[j];
		        var fileReader = new FileReader();
		        fileReader.onload = (function(e) {
		          var file = e.target;
		          $("<span   class=\"pip\">" +
	            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
	            "<br/><span class=\"remove\">Remove image</span>" +
	            "</span>").prependTo("#panel669");
		          $(".remove").on('click',function(){
		            $(this).parent(".pip").remove();
		          });
		        });
        		 fileReader.readAsDataURL(f);

		     //var size = $("input:file")[0].files[j].size;
		     var size = f.size;
			 if(size > 2000000 )
			 {
		        $.bigBox({
					title   : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message !",
					content : "Attach file less than 2 MB !",
					color   : "#C46A69",
					icon    : "fa fa-warning shake animated",
					timeout : 8000
			    });
				
				e.preventDefault();
				$("input:file").val(""); 
				var no_of_files = $("input:file")[0].files.length;
			    var count = no_of_files+' files attached';
			    $('.file_attach_count').text(count);
          		$(this).removeClass("hide");
				break;
				
			 }
		    }
		     
		});

		}
	</script>
	<script type="text/javascript">
		$("a[rel^='prettyPhoto']").prettyPhoto();
		if (window.File && window.FileList && window.FileReader) 
		{
				
		//var numFiles = $("input:file")[0].files.length;
		 $("#files").on("change", function(e) {
			
			var files = e.target.files,
	        filesLength = files.length;
	        console.log('filesLength',filesLength);
		    for(var j=0;j<filesLength;j++)
		    {
		    	var f = files[j]
		        var fileReader = new FileReader();
		        fileReader.onload = (function(e) {
		          var file = e.target;
		          $("<span   class=\"pip\">" +
	            "<img class=\"imageThumb wow\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
	            "<br/><span class=\"remove\">Remove image</span>" +
	            "</span>").prependTo("#panel770");
		          $(".remove").on('click',function(){
		            $(this).parent(".pip").remove();
		          });
		        });
        		 fileReader.readAsDataURL(f);

		     var size = f.size;
			 if(size > 2000000 )
			 {
		        $.bigBox({
					title   : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message !",
					content : "Attach file less than 2 MB !",
					color   : "#C46A69",
					icon    : "fa fa-warning shake animated",
					timeout : 8000
			    });
				
				e.preventDefault();
				$("input:file").val(""); 
				var no_of_files = $("input:file")[0].files.length;
			    var count = no_of_files+' files attached';
			    $('.file_attach_count').text(count);
          		$(this).removeClass("hide");
				break;
				
			 }
		    }
		     $("input:file").html("#files_prescriptions");
		 	
		 	//var files = $(".imageThumb").array(); 
		});

		}
	</script>
	<?php 
//include footer
	include("inc/footer.php"); 
	?>
