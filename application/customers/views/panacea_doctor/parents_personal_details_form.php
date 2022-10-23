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


<link href="<?php echo(CSS.'site.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo(CSS.'jquery.dataTables.min.css'); ?>">
<!-- <link href="<?php //echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" /> -->
<link href="<?php echo(CSS.'smartadmin-production.min.css'); ?>"  rel="stylesheet" type="text/css" />
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
	<?php if(isset($family_data)): ?>
		<?php foreach($family_data as $data): ?>

			<div class="row">
				<section class="col col-md-3">
					<div class="row">
                        <section class="col col-md-12">
							<label class="label">UNIQUE ID</label>
							<label class="input">
								<input type="text" name="unique_id" id="unique_id" placeholder="Focus to view the tooltip" value="<?php echo $data['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']; ?>" readOnly>
							</label>
						</section>
						<section class="col col-md-12">
							<label class="label">FATHER NAME</label>
							<label class="input">
								<input type="text" id='page1_StudentInfo_Section' rel='page2_Personal Information_Section' type='text' name='page1_StudentInfo_Section'  value="<?php echo $data['doc_data']['widget_data']['page1']['Personal Information']['Father Name']; ?>" readOnly>
							</label>
						</section>
						<!-- <section class="col col-md-12">
							<label class="label">Total Family No</label>
							<label class="input">
								<input type="text" id='page1_StudentInfo_Section' rel='page2_Personal Information_Section' type='text' name='page1_StudentInfo_Section'  value="<?php //echo $data['doc_data']['widget_data']['page2']['Family Health Info']['Family Counts']['Total Family No']; ?>" readOnly>
							</label>
						</section> -->
					</div>
				</section>
				<section class="col col-md-3">
					<div class="row">
						<section class="col col-md-12">
							<label class="label">NAME</label>
							<label class="input"> 
							<input type="text" id='page1_StudentInfo_Name' rel='page1_Personal Information_Name' name='page1_StudentInfo_Name' value="<?php echo $data['doc_data']['widget_data']['page1']['Personal Information']['Name']; ?>" readOnly>
							</label>
						</section>
						<section class="col col-md-12">
							<label class="label">DISTRICT</label>
							<label class="input"> 
								<input type="text" id='page1_StudentInfo_District' rel='page2_Personal Information_District' type='text' name='page1_StudentInfo_District'   value="<?php echo $data['doc_data']['widget_data']['page1']['Personal Information']['District']; ?>" readOnly>
							</label>
						</section>
						<section class="col col-md-12">
							<label class="label">No Of Sisters</label>
							<label class="input"> 
								<input type="text" id='page1_StudentInfo_District' rel='page2_Personal Information_District' type='text' name='page1_StudentInfo_District'   value="<?php echo $data['doc_data']['widget_data']['page2']['Family Health Info']['Family Counts']['No Of Sisters']; ?>" readOnly>

							</label>
						</section>
					</div>
				</section>
				<section class="col col-md-3">
					<div class="row">
						<section class="col col-md-12">
							<label class="label">CLASS</label>
							<label class="input"> 
								<input type="text" id='page1_StudentInfo_Class' type='number' rel='page2_Personal Information_Class' name='page1_StudentInfo_Class' value="<?php echo $data['doc_data']['widget_data']['page1']['Personal Information']['Class']; ?>" readOnly>
							</label>
						</section>
						<section class="col col-md-12">
							<label class="label">SCHOOL NAME</label>
							<label class="input"> 
								<input type="text" id='page1_StudentInfo_SchoolName' rel='page2_Personal Information_School Name' type='text' name='page1_StudentInfo_SchoolName'  value="<?php echo $data['doc_data']['widget_data']['page1']['Personal Information']['School Name']; ?>" readOnly>

							</label>
						</section>
						<section class="col col-md-12">
							<label class="label">No Of Brothers</label>
							<label class="input"> 
								<input type="text" id='page1_StudentInfo_SchoolName' rel='page2_Personal Information_School Name' type='text' name='page1_StudentInfo_SchoolName'  value="<?php echo $data['doc_data']['widget_data']['page2']['Family Health Info']['Family Counts']['No Of Brothers']; ?>" readOnly>

							</label>
						</section>
						
					</div>
				</section>
				<section class="col col-md-3">
					<div class="row">
						<section class="col col-md-12">
							<p>Student Photo</p>
							<?php foreach($docs as $doc):?>
								<?php if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && !is_null($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && !empty($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'])):?>
								<img src="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];?>" height="150" width="150"/><?php else: ?><?php echo "No Photo uploaded";?><?php endif ?>
							<?php endforeach; ?>
							
						</section>
					</div>
				</section>
				<!-- <section class="col col-md-2">
					<br>
						<button class="btn btn-primary btn-lg hide" id="student_search_btn" value="" style=" height: 35px;
   						 width:-webkit-fill-available; margin-top: 5px;">Search</button>
				</section>

				<section class="pull-right">
					
						<div id="student_image"> </div>
					</section> -->

				

				

			</div>
			
			<br>
			<!-- <div class="row">
				
				
				
			</div>

			<br>
			<h4>Family Counts</h4>
			<div class="row">

				
				
				
			</div> -->

		<?php endforeach; ?>
	<?php endif; ?>
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
				<a href="#health_record" data-toggle="tab">EHR<span class="badge bg-color-blue txt-color-white"></span></a>
			</li>
			<li>
				<a href="#family_details" data-toggle="tab">Family Details<span class="badge bg-color-blue txt-color-white"></span></a>
			</li>
			<li>
				<a href="#doctor_analysis" data-toggle="tab">Doctor Analysis<span class="badge bg-color-blue txt-color-white"></span></a>
			</li>
			
			
		</ul>

		<div id="myTabContent1" class="tab-content padding-10">
		<?php if(isset($family_data)): ?>
			<?php foreach($family_data as $data): ?>
			<div class="tab-pane fade" id="family_details">
				About Family Details
				<legend><h4 class="text-primary">Father Details</h4></legend>
				<div class="row">
					<section class="col col-md-4">
						<label class="label">Father Name</label>
						<label class="input">
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="<?php if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['Father Name']) && (!empty($data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['Father Name']))) : ?><?php echo $data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['Father Name']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?>" readOnly>
						</label>
					</section>
					<section class="col col-md-4">
						<label class="label">Age</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="<?php if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['Age']) && (!empty($data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['Age']))) : ?><?php echo $data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['Age']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?>" readOnly>

						</label>
					</section>
					<!-- <section class="col col-md-4">
						<label class="label">Mobile</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="<?php //if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['Mobile']) && (!empty($data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['Mobile']))) : ?><?php //echo $data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['Mobile']; ?><?php //else : ?><?php //echo "Nill"; ?><?php //endif; ?>" readOnly>

						</label>
					</section> -->
				<?php if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['Medical Data'])): ?>
					<section class="col col-md-12">
					<legend><h5 class="text-primary">Medical History</h5></legend>
					<?php $med = $data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['Medical Data']; ?>
					<table class="table table-striped table-bordered table-hover">
						<?php foreach($med as $key=>$value): ?>
						<tr>
							<th><?php echo $key; ?></th>
							<?php if(is_array($value)): ?>
							<td><?php echo implode(',', $value); ?></td>
							<?php else: ?>
							<td><?php echo $value; ?></td>
							<?php endif; ?>
						</tr>
						<?php endforeach; ?>
					</table>
					</section>
				<?php endif; ?>
					
				</div>
				<legend><h5 class="text-primary">Father Medical History</h5></legend>
				<section>
					<textarea class='form-control' rows="5"  id=''  name='' readOnly><?php if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['Description']) && (!empty($data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['Description']))) : ?><?php echo $data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['Description']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></textarea>
				</section>
				
				<?php if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['attachments'])): ?>
					<legend><h5 class="text-primary">Father Attachments</h5></legend>
						<?php foreach($data['doc_data']['widget_data']['page2']['Family Health Info']['Father Data']['attachments'] as $pic):?>
					<section class="col col-md-3">

							<a href="<?php echo URLCustomer.$pic['file_path'];?>" data-sub-html="Demo Description">
							    <img class="img-responsive thumbnail" src="<?php echo URLCustomer.$pic['file_path'];?>" style = "height: 150px; width: 150px;">
							</a>
					</section>
							<?php endforeach; ?>
						<?php else:?>
							  <span class="text-center">No Attachments Found</span>
				<?php endif; ?>

			<hr>
	<!-- Mother Details -->
				<legend><h4 class="text-primary">Mother Details</h4></legend>
				<div class="row">
					<section class="col col-md-4">
						<label class="label">Mother Name</label>
						<label class="input">
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="<?php if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['Mother Name']) && (!empty($data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['Mother Name']))) : ?><?php echo $data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['Mother Name']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?>" readOnly>
						</label>
					</section>
					<section class="col col-md-4">
						<label class="label">Age</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="<?php if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['Age']) && (!empty($data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['Age']))) : ?><?php echo $data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['Age']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?>" readOnly>

						</label>
					</section>
					<!-- <section class="col col-md-4">
						<label class="label">Mobile</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="<?php //if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['Mobile']) && (!empty($data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['Mobile']))) : ?><?php //echo $data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['Mobile']; ?><?php //else : ?><?php //echo "Nill"; ?><?php //endif; ?>" readOnly>

						</label>
					</section> -->
					<?php if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['Medical Data'])): ?>
					<section class="col col-md-12">
					<legend><h5 class="text-primary">Medical History</h5></legend>
					<?php $med = $data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['Medical Data']; ?>
					<table class="table table-striped table-bordered table-hover">
						<?php foreach($med as $key=>$value): ?>
						<tr>
							<th><?php echo $key; ?></th>
							<?php if(is_array($value)): ?>
							<td><?php echo implode(',', $value); ?></td>
							<?php else: ?>
							<td><?php echo $value; ?></td>
							<?php endif; ?>
						</tr>
						<?php endforeach; ?>
					</table>
					</section>
				<?php endif; ?>
				</div>
				<legend><h5 class="text-primary">Mother Medical History</h5></legend>
				<section>
					<textarea class='form-control' rows="5"  id=''  name='' readOnly><?php if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['Description']) && (!empty($data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['Description']))) : ?><?php echo $data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['Description']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></textarea>
				</section>
				<?php if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['attachments'])): ?>
					<legend><h5 class="text-primary">Mother Attachments</h5></legend>
						<?php foreach($data['doc_data']['widget_data']['page2']['Family Health Info']['Mother Data']['attachments'] as $pic):?>
					<section class="col col-md-3">

							<a href="<?php echo URLCustomer.$pic['file_path'];?>" data-sub-html="Demo Description">
							    <img class="img-responsive thumbnail" src="<?php echo URLCustomer.$pic['file_path'];?>" style = "height: 150px; width: 150px;">
							</a>
					</section>
							<?php endforeach; ?>
						<?php else:?>
							  <span class="text-center">No Attachments Found</span>
				<?php endif; ?>
			<hr>
		<!-- End Mother Data -->
		<!-- Guardian Data -->
		<!-- <?php //if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data'])): ?>
				<legend><h4 class="text-primary">Guardian Details</h4></legend>
				<div class="row">
					<section class="col col-md-4">
						<label class="label">Guardian Name</label>
						<label class="input">
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="<?php //if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['Guardian Name']) && (!empty($data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['Guardian Name']))) : ?><?php //echo $data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['Guardian Name']; ?><?php //else : ?><?php //echo "Nill"; ?><?php //endif; ?>" readOnly>
						</label>
					</section>
					<section class="col col-md-4">
						<label class="label">Age</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="<?php //if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['Age']) && (!empty($data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['Age']))) : ?><?php //echo $data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['Age']; ?><?php //else : ?><?php //echo "Nill"; ?><?php //endif; ?>" readOnly>

						</label>
					</section>
					<section class="col col-md-4">
						<label class="label">Mobile</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="<?php //if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['Mobile']) && (!empty($data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['Mobile']))) : ?><?php //echo $data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['Mobile']; ?><?php //else : ?><?php //echo "Nill"; ?><?php //endif; ?>" readOnly>

						</label>
					</section>
					<?php //if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['Medical Data'])): ?>
					<section class="col col-md-12">
					<legend><h5 class="text-primary">Medical History</h5></legend>
					<?php $med// = $data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['Medical Data']; ?>
					<table class="table table-striped table-bordered table-hover">
						<?php// foreach($med as $key=>$value): ?>
						<tr>
							<th><?php //echo $key; ?></th>
							<?php //if(is_array($value)): ?>
							<td><?php //echo implode(',', $value); ?></td>
							<?php// else: ?>
							<td><?php //echo $value; ?></td>
							<?php //endif; ?>
						</tr>
						<?php// endforeach; ?>
					</table>
					</section>
				<?php //endif; ?>
				</div>
				<legend><h5 class="text-primary">Guardian Medical History</h5></legend>
				<section>
					<textarea class='form-control' rows="5"  id=''  name='' readOnly><?php //if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['Description']) && (!empty($data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['Description']))) : ?><?php //echo $data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['Description']; ?><?php //else : ?><?php //echo "Nill"; ?><?php //endif; ?></textarea>
				</section>
				<?php //if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['attachments'])): ?>
					<legend><h5 class="text-primary">Guardian Attachments</h5></legend>
						<?php// foreach($data['doc_data']['widget_data']['page2']['Family Health Info']['Guardian Data']['attachments'] as $pic):?>
					<section class="col col-md-3">

							<a href="<?php //echo URLCustomer.$pic['file_path'];?>" data-sub-html="Demo Description">
							    <img class="img-responsive thumbnail" src="<?php //echo URLCustomer.$pic['file_path'];?>" style = "height: 150px; width: 150px;">
							</a>
					</section>
							<?php //endforeach; ?>
						<?php //else:?>
							  <span class="text-center">No Attachments Found</span>
				<?php //endif; ?>
			
		<?php// endif; ?> -->

		<hr>

		<!-- End Guardian Data -->
		<!-- Sisters Data -->
		<?php if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Sisters Data'])): ?>
			<?php foreach($data['doc_data']['widget_data']['page2']['Family Health Info']['Sisters Data'] as $key => $value): ?>
				<?php if(isset($value['Sister Name']) && !empty($value['Sister Name'])): ?>
				<legend><h4 class="text-primary"><?php echo $key; ?> Details</h4></legend>
				<div class="row">
					<section class="col col-md-4">
						<label class="label"><?php echo $key; ?> Name</label>
						<label class="input">
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="<?php if(isset($value['Sister Name']) && (!empty($value['Sister Name']))) : ?><?php echo $value['Sister Name']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?>" readOnly>
						</label>
					</section>
					 <section class="col col-md-4">
						<label class="label">Age</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="<?php if(isset($value['Age']) && (!empty($value['Age']))) : ?><?php echo $value['Age']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?>" readOnly>

						</label>
					</section>
					<!-- <section class="col col-md-4">
						<label class="label">Mobile</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="<?php //if(isset($value['Mobile']) && (!empty($value['Mobile']))) : ?><?php //echo $value['Mobile']; ?><?php //else : ?><?php //echo "Nill"; ?><?php //endif; ?>" readOnly>
						</label>
					</section> -->
					<?php if(isset($value['Medical Data'])): ?>
					<section class="col col-md-12">
					<legend><h5 class="text-primary">Medical History</h5></legend>
					<?php $med = $value['Medical Data']; ?>
					<table class="table table-striped table-bordered table-hover">
						<?php foreach($med as $key=>$value): ?>
						<tr>
							<th><?php echo $key; ?></th>
							<?php if(is_array($value)): ?>
							<td><?php echo implode(',', $value); ?></td>
							<?php else: ?>
							<td><?php echo $value; ?></td>
							<?php endif; ?>
						</tr>
						<?php endforeach; ?>
					</table>
					</section>
				<?php endif; ?>
				</div>
				 <legend><h5 class="text-primary"><?php echo $key; ?> Medical History</h5></legend>
				<section>
					<textarea class='form-control' rows="5"  id=''  name='' readOnly><?php if(isset($value['Description']) && (!empty($value['Description']))) : ?><?php echo $value['Description']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></textarea>
				</section>
				<!-- Attachements -->
				<?php if(isset($value['attachments'])): ?>
					<legend><h5 class="text-primary"><?php echo $key; ?> Attachments</h5></legend>
						<?php foreach($value['attachments'] as $pic):?>
					<section class="col col-md-3">

							<a href="<?php echo URLCustomer.$pic['file_path'];?>" data-sub-html="Demo Description">
							    <img class="img-responsive thumbnail" src="<?php echo URLCustomer.$pic['file_path'];?>" style = "height: 150px; width: 150px;">
							</a>
					</section>
							<?php endforeach; ?>
						<?php else:?>
							  <span class="text-center">No Attachments Found</span>
				<?php endif; ?>
			<hr>
				<!-- End Attachements -->
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<!-- End Sisters Data -->
		<!-- Brothers Data -->
			
		<?php if(isset($data['doc_data']['widget_data']['page2']['Family Health Info']['Brothers Data'])): ?>
			<?php foreach($data['doc_data']['widget_data']['page2']['Family Health Info']['Brothers Data'] as $key => $value): ?>
				<?php if(isset($value['Brother Name']) && !empty($value['Brother Name'])): ?>
				<legend><h4 class="text-primary"><?php echo $key; ?> Details</h4></legend>
				<div class="row">
					<section class="col col-md-4">
						<label class="label"><?php echo $key; ?> Name</label>
						<label class="input">
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="<?php if(isset($value['Brother Name']) && (!empty($value['Brother Name']))) : ?><?php echo $value['Brother Name']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?>" readOnly>
						</label>
					</section>
					 <section class="col col-md-4">
						<label class="label">Age</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="<?php if(isset($value['Age']) && (!empty($value['Age']))) : ?><?php echo $value['Age']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?>" readOnly>

						</label>
					</section>
					<!-- <section class="col col-md-4">
						<label class="label">Mobile</label>
						<label class="input"> 
							<input type="text" id='' rel='page2_Personal Information_Section' name=''  value="<?php //if(isset($value['Mobile']) && (!empty($value['Mobile']))) : ?><?php //echo $value['Mobile']; ?><?php //else : ?><?php //echo "Nill"; ?><?php //endif; ?>" readOnly>

						</label>
					</section>  -->
					<?php if(isset($value['Medical Data'])): ?>
					<section class="col col-md-12">
					<legend><h5 class="text-primary">Medical History</h5></legend>
					<?php $med = $value['Medical Data']; ?>
					<table class="table table-striped table-bordered table-hover">
						<?php foreach($med as $key=>$value): ?>
						<tr>
							<th><?php echo $key; ?></th>
							<?php if(is_array($value)): ?>
							<td><?php echo implode(',', $value); ?></td>
							<?php else: ?>
							<td><?php echo $value; ?></td>
							<?php endif; ?>
						</tr>
						<?php endforeach; ?>
					</table>
					</section>
				<?php endif; ?>
				</div>
				 <legend><h5 class="text-primary"><?php echo $key; ?> Medical History</h5></legend>
				<section>
					<textarea class='form-control' rows="5"  id=''  name='' readOnly><?php if(isset($value['Description']) && (!empty($value['Description']))) : ?><?php echo $value['Description']; ?><?php else : ?><?php echo "Nill"; ?><?php endif; ?></textarea>
				</section>
				<!-- Attachements -->
				<?php if(isset($value['attachments'])): ?>
					<legend><h5 class="text-primary"><?php echo $key; ?> Attachments</h5></legend>
						<?php foreach($value['attachments'] as $pic):?>
					<section class="col col-md-3">

							<a href="<?php echo URLCustomer.$pic['file_path'];?>" data-sub-html="Demo Description">
							    <img class="img-responsive thumbnail" src="<?php echo URLCustomer.$pic['file_path'];?>" style = "height: 150px; width: 150px;">
							</a>
					</section>
							<?php endforeach; ?>
						<?php else:?>
							  <span class="text-center">No Attachments Found</span>
				<?php endif; ?>
			<hr>
				<!-- End Attachements -->
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		
		<!-- End Brothers Data -->
				
		</div>
	<?php endforeach; ?>
	<?php else: ?>
		<h4>No Data Available</h4>
	<?php endif; ?>

	<!-- Doctor Analysis -->
	
			<div class="tab-pane fade" id="doctor_analysis">
				<div class="row">
					<?php if(isset($docs)): ?>
				<?php foreach($docs as $doc): ?>
					<?php if(isset($doc['doctors_medical_reports'])): ?>
					<legend><h3 class="text-primary">Reports</h3></legend>
					<table class="table table-striped table-bordered table-hover">
						<tbody>
							<tr>
								<th>Date</th>
								<th>Action</th>
								<!-- <th>Current Medical Condition</th>
								<th>Parent Medical Condition</th>
								<th>Advice/Suggestion</th> -->
							</tr>
						<?php foreach($doc['doctors_medical_reports']['reports'] as $report): ?>
							<tr>
								<td><?php echo $report['Date'];?></td>
								<td><button type="button" class="btn btn-info btn-lg valShow" current = "<?php echo $report['Current Condition'];?>" parent="<?php echo $report['Parent Condition'];?>" doc ="<?php echo $report['Doc Report'];?>" date="<?php echo $report['Date'];?>" data-toggle="modal" data-target="#myModal">Show Report</button></td>

								<!-- <td><?php //echo $report['Current Condition'];?></td>
								<td><?php //echo $report['Parent Condition'];?></td>
								<td><?php //echo $report['Doc Report'];?></td> -->
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table> 
					<?php else: ?>
					<h3><?php echo "No Previous Data"; ?></h3>
				<?php endif; ?>

				<?php endforeach; ?>
				<?php endif; ?>
				<center><button type="button" class="btn btn-primary" id="generate_report">Generate Report</button></center>
				</div>

				
				
				<div class="row" id="doc_report_show" style="display: none;">
					<p>Doctor Analysis about Condition</p>
					<fieldset>
						<legend><h3 class="text-primary">Current Medical Condition</h3></legend>
						<section>

							<textarea class='form-control' rows="5"  id='current_med'  name='current_medical_conditon' value=""></textarea>
						</section>
					</fieldset>
					<fieldset>
						<legend><h3 class="text-primary">Parents Medical Condition</h3></legend>
						<section>
							<textarea class='form-control' rows="5"  id="parent_med" name="parent_medical_condition" value=""></textarea>
						</section>
					</fieldset>
					<fieldset>		
						<section>
							<legend><h3 class="text-primary">Advice/Suggestion</h3></legend>
							<textarea class='form-control' rows="5"  id="doc_advice" name="doctor_advice" value=""></textarea>
						</section>
					</fieldset>
					<fieldset>
						<section class="col col-6">
							<legend><h3 class="text-primary">Select Zone</h3></legend>
							<label class="select">
								<select id="stud_zone" >
									<option value="Nil"> Select Zone</option>
									<option value="Green Zone" >Green Zone</option>
									<option value="Orange Zone" >Orange Zone</option>
									<option value="Red Zone">Red Zone</option>
								</select> <i></i>
							</label>
						</section>
					</fieldset>
					
					<br>
					<br>      
			            
			        <center><button type="button" class="btn btn-primary" id="doc_analyse_res">Submit Report</button></center>	
				</div>

			</div>
<!-- Model -->


	<!-- End Doctor Analysis -->


	<?php if($docs): ?>
		<?php foreach($docs as $doc): ?>
			<div class="tab-pane fade in active" id="health_record">
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
							<?php if(isset($docs_requests) && count($docs_requests)> 0): ?>
							<?php $requestCount = 1; ?>
								<label><span class="label label-success"><i class="fa fa-lg fa-plus-circle"></i> Request Follow Ups <span class="badge bg-red"> <?php echo count($docs_requests)?> </span></span></label>
								<?php foreach($docs_requests as $request): ?>
									<div class="card">
										<div class="header bg-red" style="padding: 12px;">
											 <b>Sick Request- <?php echo $requestCount++; ?></b>
										</div>
										<div class="body">
											<table id="dt_basic" class="table table-striped table-bordered table-hover">
							                    <tbody>
												<tr>
													<th>Request Type</th><td><i class="icon-leaf"><?php echo $request['doc_data']['widget_data']['page2']['Review Info']['Request Type'];?></i></td>
												</tr>
							                    <tr>
													<th>Follow Up Status</th><td><i class="icon-leaf"><?php echo $request['doc_data']['widget_data']['page2']['Review Info']['Status'];?></i></td>
												</tr>
												<tr>
													<th colspan=2 align="center"><h4>Problem Information</h4></th>
												</tr>
												<tr>
													<th>Problem Information</th>
													<?php $identifiers_normal = $request['doc_data']['widget_data']['page1']['Problem Info']['Normal']; ?>
													<?php $identifiers_emergency = $request['doc_data']['widget_data']['page1']['Problem Info']['Emergency']; ?>
													<?php $identifiers_chronic = $request['doc_data']['widget_data']['page1']['Problem Info']['Chronic']; ?>

																			<td><?php foreach ($identifiers_normal as $identifier => $values) :?>
																				
																				<?php $var123 = implode (", ",$request['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]); ?>
																			<?php if(!empty($var123)):?> 
																			<?php echo (gettype($request['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier])=="array")? implode (", ",$request['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]) : "No Identifier";?>
																			
																		<?php endif; endforeach;?>

																		<?php foreach ($identifiers_emergency as $identifier => $values) :?>
																				
																		<?php $var123 = implode (", ",$request['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]); ?>
																			<?php if(!empty($var123)):?> 
																			<?php echo (gettype($request['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier])=="array")? implode (", ",$request['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]) : "No Identifier";?>
																			
																		<?php  endif; endforeach;?>
																		<?php foreach ($identifiers_chronic as $identifier => $values) :?>
																				
																<?php $var123 = implode (", ",$request['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]); ?>
																			<?php if(!empty($var123)):?> 
																			<?php echo (gettype($request['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier])=="array")? implode (", ",$request['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]) : "No Identifier";?>
																			
																		<?php endif; endforeach;?></td>
																	
													
												</tr>
												<tr>
													<th>Description</th><td><i class="icon-leaf"><?php echo $request['doc_data']['widget_data']['page2']['Problem Info']['Description'];?></i></td>
												</tr>
												
												<tr>
													<th colspan=2 align="center"><h4>Diagnosis Information</h4></th>
												</tr>
												<tr>
													<th>Doctor Summary</th><td><i class="icon-leaf"><?php echo $request['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Summary'];?></i></td>
												</tr>
												<tr>
													<th>Doctor's Advice</th><td><i class="icon-leaf"><?php echo $request['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice'];?></i></td>
												</tr>
												<tr>
													<th>Prescription</th><td><i class="icon-leaf"><?php echo $request['doc_data']['widget_data']['page2']['Diagnosis Info']['Prescription'];?></i></td>
												</tr>

												<tr>
													<th colspan=2 align="center"><h4>Doctor Details</h4></th>
												</tr>
												<tr>
												<th>Doctor's Name</th><td><i class="icon-leaf"><?php echo (isset($request['history']["1"]['submitted_by_name'])? print_r($request['history']["1"]['submitted_by_name'],true) : "Doctor's information not available");?></i></td>
												</tr>
												<tr>
												<th>Doctor Submit Time</th><td><i class="icon-leaf"><?php if(isset($request['history']["1"]['time'])){
													$newformat = new DateTime($request['history']["1"]['time']);
													$tz = new DateTimeZone('Asia/Kolkata'); // or whatever zone you're after

													$newformat->setTimezone($tz);

													echo $newformat->format('Y-m-d H:i:s');
												}else{
													echo "Doctor's information not available";
													};?></i></td>
												</tr>
												<tr>
												<th>Last Stage (HS stage) Time</th><td><i class="icon-leaf"><?php if(isset($request['history']["last_stage"]['time'])){
													$newformat = new DateTime($request['history']["last_stage"]['time']);
													$tz = new DateTimeZone('Asia/Kolkata'); // or whatever zone you're after

													$newformat->setTimezone($tz);

													echo $newformat->format('Y-m-d H:i:s');
												}else{
													echo "Not yet processed";
													};?></i></td>
												</tr>
												<tr>
												<th>Last Update Details</th>
												<td><i class="icon-leaf">
												
												<?php $last_stage = array_pop($request['history']);
												echo "Last update on: ".$last_stage['time']; 
												echo "<br>";
									echo "Last updated by: ";?><?php if(isset($last_stage['submitted_by'])):?>
									<?php echo $last_stage['submitted_by'];?><?php else:?><?php echo""; endif;?>
												</i></td>
												</tr>

										<!-- Attachemtns -->
										
										<!-- End Attachmmrnts -->

												</tbody>
											</table>
										<!-- Attachemtns -->
											<div class="row clearfix">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<!-- Nav tabs -->
													<ul class="nav nav-tabs tab-nav-right" role="tablist">
													    <li role="presentation" class="active"><a href="#prescription_attach" data-toggle="tab"><button class="btn bg-green btn-lg btn-block waves-effect" type="button">Prescription <span class="badge"><?php if(isset($request['doc_data']['Prescriptions'])): echo count($request['doc_data']['Prescriptions']); else: echo "0"; endif; ?></span></button></a></li>
													    <li role="presentation"><a href="#lab_report_attch" data-toggle="tab"><button class="btn bg-red btn-lg btn-block waves-effect" type="button">Lab reports<span class="badge"><?php if(isset($request['doc_data']['Lab_Reports'])): echo count($request['doc_data']['Lab_Reports']); else: echo "0"; endif; ?></span></button></a></li>
													    <li role="presentation"><a href="#xmdigital_attach" data-toggle="tab"><button class="btn bg-blue btn-lg btn-block waves-effect" type="button">X-ray/MRI/Digital Images <span class="badge"><?php if(isset($request['doc_data']['Digital_Images'])): echo count($request['doc_data']['Digital_Images']); else: echo "0"; endif; ?></span></button></a></li>
													    <li role="presentation"><a href="#bills_attach" data-toggle="tab"><button class="btn bg-teal btn-lg btn-block waves-effect" type="button">Payments/Bills <span class="badge"><?php if(isset($request['doc_data']['Payments_Bills'])): echo count($request['doc_data']['Payments_Bills']); else: echo "0"; endif; ?></span></button></a></li>
													    <li role="presentation"><a href="#discharge_summary" data-toggle="tab"><button class="btn bg-orange btn-lg btn-block waves-effect" type="button">Discharge Summary <span class="badge"><?php if(isset($request['doc_data']['Discharge_Summary'])): echo count($request['doc_data']['Discharge_Summary']); else: echo "0"; endif; ?></span></button> </a></li>
													    <li role="presentation"><a href="#others_doc_attach" data-toggle="tab"><button class="btn bg-purple btn-lg btn-block waves-effect" type="button">Other Attachments <span class="badge"><?php if(isset($request['doc_data']['external_attachments'])): echo count($request['doc_data']['external_attachments']); else: echo "0"; endif; ?></span></button> </a></li>
													</ul>
										  <!-- Tab panes -->
										  <div class="tab-content">
										    
										      <div role="tabpanel" class="tab-pane fade in active" id="prescription_attach">
										       
										          <b>Prescription Attachments</b>
										          <p>
										                  <?php if(isset($request['doc_data']['Prescriptions'])): ?>
										                 
										                  <?php foreach($request['doc_data']['Prescriptions'] as $data):?>

										                  <a href="<?php echo URLCustomer.$data['file_path'];?>" data-sub-html="Demo Description">
										                      <img class="img-responsive thumbnail" src="<?php echo URLCustomer.$data['file_path'];?>">
										                      <?php endforeach ?>
										                  </a>
										                  <?php else:?>
										                    <span class="text-center">No Attachments Found</span>
										                  <?php endif ?>
										          </p>
										         </div>
										     
										      <div role="tabpanel" class="tab-pane fade" id="lab_report_attch">
										          <b>Lab report Attachments</b>
										         <p>
										                  <?php if(isset($request['doc_data']['Lab_Reports'])): ?>

										                  <?php foreach($request['doc_data']['Lab_Reports'] as $data):?>

										                  <a href="<?php echo URLCustomer.$data['file_path'];?>" data-sub-html="Demo Description">
										                      <img class="img-responsive thumbnail" src="<?php echo URLCustomer.$data['file_path'];?>">
										                      <?php endforeach ?>
										                  </a>
										                  <?php else:?>
										                    <span class="text-center">No Attachments Found</span>
										                  <?php endif ?>
										          </p>
										      </div>
										      <div role="tabpanel" class="tab-pane fade" id="xmdigital_attach">
										          <b>X-ray/MRI/Digital Attachments</b>
										         <p>
										                  <?php if(isset($request['doc_data']['Digital_Images'])): ?>

										                  <?php foreach($request['doc_data']['Digital_Images'] as $data):?>

										                  <a href="<?php echo URLCustomer.$data['file_path'];?>" data-sub-html="Demo Description">
										                      <img class="img-responsive thumbnail" src="<?php echo URLCustomer.$data['file_path'];?>">
										                      <?php endforeach ?>
										                  </a>
										                  <?php else:?>
										                    <span class="text-center">No Attachments Found</span>
										                  <?php endif ?>
										          </p>
										      </div>
										      <div role="tabpanel" class="tab-pane fade" id="bills_attach">
										          <b>Payments/Bills Attachments</b>
										          <p>
										                  <?php if(isset($request['doc_data']['Payments_Bills'])): ?>

										                  <?php foreach($request['doc_data']['Payments_Bills'] as $data):?>

										                  <a href="<?php echo URLCustomer.$data['file_path'];?>" data-sub-html="Demo Description">
										                      <img class="img-responsive thumbnail" src="<?php echo URLCustomer.$data['file_path'];?>">
										                      <?php endforeach ?>
										                  </a>
										                  <?php else:?>
										                    <span class="text-center">No Attachments Found</span>
										                  <?php endif ?>
										          </p>
										      </div>
										      <div role="tabpanel" class="tab-pane fade" id="discharge_summary">
										         <b>Discharge Summary Attachments</b>
										         <p>
										                  <?php if(isset($request['doc_data']['Discharge_Summary'])): ?>

										                  <?php foreach($request['doc_data']['Discharge_Summary'] as $data):?>

										                  <a href="<?php echo URLCustomer.$data['file_path'];?>" data-sub-html="Demo Description">
										                      <img class="img-responsive thumbnail" src="<?php echo URLCustomer.$data['file_path'];?>">
										                      <?php endforeach ?>
										                  </a>
										                  <?php else:?>
										                    <span class="text-center">No Attachments Found</span>
										                  <?php endif ?>
										          </p>
										      </div>
										       <div role="tabpanel" class="tab-pane fade" id="others_doc_attach">
										      
										          <p>
										                  <?php if(isset($request['doc_data']['external_attachments'])): ?>

										                  <?php foreach($request['doc_data']['external_attachments'] as $data):?>

										                  <a href="<?php echo URLCustomer.$data['file_path'];?>" data-sub-html="Demo Description">
										                      <img class="img-responsive thumbnail" src="<?php echo URLCustomer.$data['file_path'];?>">
										                      <?php endforeach ?>
										                  </a>
										                  <?php else:?>
										                    <span class="text-center">No Attachments Found</span>
										                  <?php endif ?>
										          </p>
										      </div>
										 
												</div>

												</div>
											</div>
										<!-- End Attachemtns -->
										</div>
									</div>

								<?php endforeach; ?>
							<?php endif; ?>
						</div>
						<!-- Other Attachements -->
						<div class="tab-pane fade" id="Other_Attachments">
							<div class="well well-sm ">
								<label class="label"><h3>Attachments</h3></label>
								<?php if(isset($docs)): ?>
									<?php foreach($docs as $doc): ?>
										<?php if(isset($doc['doc_data']['external_attachments']) && !is_null($doc['doc_data']['external_attachments']) && !empty($doc['doc_data']['external_attachments'])):?>
										<?php foreach ($doc['doc_data']['external_attachments'] as $attachment):?>
											<img src="<?php echo URLCustomer.$attachment['file_path'];?>" width="50" height="50"/>
										<?php endforeach; ?>
										<?php endif; ?>
									<?php endforeach; ?>
								<?php endif; ?>
								
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

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="badge bg-teal" id="doc_date"></i>Report</h4>
        
      </div>
      <div class="modal-body">
      	<h4 class="modal-title">Current Condition</h4>
        <p id="cur"></p>
        <h4 class="modal-title">Parent Condition</h4>
        <p id="par"></p>
        <h4 class="modal-title">Doctor Analysis</h4>
        <p id="doc_val"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- End Model -->

<!-- <div class="modal fade" id="show_dr_reports" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h4 class="modal-title">Doctors Report</h4>
		      </div>
		      <div class="modal-body">
		      
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		</div>
	</div> -->

	<?php 
//include required scripts
	include("inc/scripts.php"); 
	?> 

	<?php 
//include footer
	include("inc/footer.php"); 
	?>

<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <!-- <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script> -->
  <!-- <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdn.bootcss.com/prettify/r298/prettify.min.js"></script>
  <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
  -->
  <script src="<?php echo JS; ?>img_options/jquery.magnify.js"></script>

  <script src="<?php echo(JS.'bootstrap-multiselect.js'); ?>" type="text/javascript"></script>
  

<!-- PAGE RELATED PLUGIN(S) 
	<script src="..."></script>-->
	<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->


	<script type="text/javascript">

        //show_if_hospital_checked();
                      /*  var today_date = $('#set_date').val();
                        //$('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
                        $('#set_date').change(function(e){
                                today_date = $('#set_date').val();;
                        });   */
    $('.valShow').click(function(){
    	var current_position = $(this).attr('current');
    	var parent_position = $(this).attr('parent');
        var doc_position = $(this).attr('doc');
        var select_date = $(this).attr('date');

        $('#cur').text(current_position);
        $('#par').text(parent_position);
        $('#doc_val').text(doc_position);
        $('#doc_date').text(select_date);
    });

    $('#doc_analyse_res').click(function(){
    	var current = $('#current_med').val();
    	var parent = $('#parent_med').val();
    	var advice = $('#doc_advice').val();
    	var unique = $('#unique_id').val();
    	var zones =$('#stud_zone').val();

    	//alert(zones);
    	
    	var urlLink = "https://mednote.in/PaaS/healthcare/index.php/panacea_doctor/";

    	$.ajax({
    		url: ''+urlLink+'doctor_analysis_for_student_family',
    		type: 'POST',
    		data: {'current_condition':current, 'parent_condition':parent, 'advice_condition':advice, 'uid': unique, 'zone':zones},
    		success:function(data){
    			 window.location.reload(1);
    			var result = $.parseJSON(data);
    			console.log(result);
    		}
    	});
    });

    $("#generate_report").click(function(){
    $("#doc_report_show").toggle();
  	});

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



  </script>
  
	
