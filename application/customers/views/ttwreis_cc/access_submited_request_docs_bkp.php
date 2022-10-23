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
$page_nav["pa submitted_requests"]["active"] = true;
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
/*.text_area
{
	margin: 0px; height: 91px; width: 483px;
}*/
textarea {
    -webkit-box-sizing: border-box !important;
    -moz-box-sizing: border-box !important;
    box-sizing: border-box !important;
	 width: 80% !important;
}
.file_attach_count 
{
	font-family: Segoe UI;
	font-size: 35px;
	color: green;
}
.attachment_files
{
	margin-left: 50px;
	margin-right: 50px;
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
			<article class="col-lg-offset-1 col-sm-12 col-md-12 col-lg-10">

				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-greenLight" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">

<header class="bg-color-orange txt-color-white">
	<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
	<h2>Initiate Request</h2>

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

	<!-- 	<h3 class="text-primary" style="text-align: -webkit-center; margin-top:10px;">Student Info</h3> -->

		<?php
		$attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
		echo  form_open_multipart('ttwreis_cc/update_request_and_submit',$attributes);
		?>
		<fieldset>
			<?php foreach ($hs_req_docs as $doc):?>
				
			<div class="row">
				<section class="col col-md-7">
					<label class="label">UNIQUE ID</label>
					<label class="input">
						<input type="text" name="unique_id" id="unique_id" placeholder="Focus to view the tooltip" value="<?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>" readOnly>
					</label>
				</section>
				<section class="col col-md-2">
					<br>
						<button class="btn btn-primary btn-lg hide" id="student_search_btn" value="" style=" height: 35px;
   						 width:-webkit-fill-available; margin-top: 5px;">Search</button>
				</section>

				<section class="pull-right">
					
						<div id="student_image"> </div>
					</section>
			</div>
			
			<br>
			<div class="row">
				<section class="col col-md-3">
					<label class="label">NAME</label>
					<label class="input"> 
			<input type="text" id='page1_StudentInfo_Name' rel='page1_Personal Information_Name' name='page1_StudentInfo_Name' value="<?php echo set_value('page1_StudentInfo_Name',(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref']) && !empty($doc['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref']))) ?  $doc['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref'] :  "" ;?>">
					</label>
				</section>

				<section class="col col-md-3">
					<label class="label">CLASS</label>
					<label class="input"> 
						<input type="text" id='page1_StudentInfo_Class' type='number' rel='page2_Personal Information_Class' name='page1_StudentInfo_Class' value="<?php echo set_value('page1_StudentInfo_Class',(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref']) && !empty($doc['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref']))) ?  $doc['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref'] :  "" ;?>">

					</label>
				</section>

				<section class="col col-md-3">
					<label class="label">SECTION</label>
					<label class="input">
						<input type="text" id='page1_StudentInfo_Section' rel='page2_Personal Information_Section' type='text' name='page1_StudentInfo_Section'  value="<?php echo set_value('page1_StudentInfo_Section',(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Section']['field_ref']) && !empty($doc['doc_data']['widget_data']['page1']['Student Info']['Section']['field_ref']))) ?  $doc['doc_data']['widget_data']['page1']['Student Info']['Section']['field_ref'] :  "" ;?>">
					</label>
				</section>
			</div>
			<div class="row"> 
				<section class="col col-md-3">
					<label class="label">DISTRICT</label>
					<label class="input"> 
						<input type="text" id='page1_StudentInfo_District' rel='page2_Personal Information_District' type='text' name='page1_StudentInfo_District'   value="<?php echo set_value('page1_StudentInfo_District',(isset($doc['doc_data']['widget_data']['page1']['Student Info']['District']['field_ref']) && !empty($doc['doc_data']['widget_data']['page1']['Student Info']['District']['field_ref']))) ?  $doc['doc_data']['widget_data']['page1']['Student Info']['District']['field_ref'] :  "" ;?>">

					</label>
				</section>
				<section class="col col-md-6">
					<label class="label">SCHOOL NAME</label>
					<label class="input"> 
						<input type="text" id='page1_StudentInfo_SchoolName' rel='page2_Personal Information_School Name' type='text' name='page1_StudentInfo_SchoolName'  value="<?php echo set_value('page1_StudentInfo_SchoolName',(isset($doc['doc_data']['widget_data']['page1']['Student Info']['School Name']['field_ref']) && !empty($doc['doc_data']['widget_data']['page1']['Student Info']['School Name']['field_ref']))) ?  $doc['doc_data']['widget_data']['page1']['Student Info']['School Name']['field_ref'] :  "" ;?>">

					</label>
				</section>
			</div>
		</fieldset>

<fieldset>
	<div class="row">
		<div class="col col-md-4">
			<label class="radio radio-inline">	<input type="radio"  class="radiobox" id="normal" name="test1" value="Normal" <?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Normal") { ?> checked="checked" <?php } ?>><span>Normal</span>
			</label>
		</div>
		<div class="col col-md-4">
			<label class="radio radio-inline">			
				<input type="radio"  class="radiobox" name="test1" id="emergency" value="Emergency"
				<?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Emergency") {?> checked="checked" <?php }?>><span>Emergency</span>
			</label>
		</div>
		<div class="col col-md-4">
			<label class="radio radio-inline">			
				<input type="radio"  class="radiobox" name="test1" id="chronic" value="Chronic"
				<?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] =="Chronic") { ?> checked="checked" <?php } ?>><span>Chronic</span>
			</label>
		</div>
	</div>

	<div class="general_related">
		<div class="widget-body">
			<hr class="simple">
			<ul id="myTab1" class="nav nav-tabs bordered">
				<li class="active">
					<a href="#general" data-toggle="tab">GENERAL</a>
				</li>
				<li>
					<a href="#head_gn" data-toggle="tab"> HEAD</a>
				</li>
				<li>
					<a href="#eyes" data-toggle="tab"> EYES</a>
				</li>
				<li>
					<a href="#ent" data-toggle="tab"> ENT</a>
				</li>
				<li>
					<a href="#rs" data-toggle="tab"> RS</a>
				</li>
				<li>
					<a href="#cvs" data-toggle="tab"> CVS</a>
				</li>
				<li>
					<a href="#gi" data-toggle="tab"> GI</a>
				</li>
				<li>
					<a href="#gu" data-toggle="tab"> GU</a>
				</li>
				<li>
					<a href="#gyn" data-toggle="tab"> GYN</a>
				</li>
				<li>
					<a href="#endo_cri" data-toggle="tab"> ENDO CRINOLOGY</a>
				</li>
				<li>
					<a href="#msk" data-toggle="tab"> MSK</a>
				</li>
				<li>
					<a href="#cns" data-toggle="tab">CNS</a>
				</li>
				<li>
					<a href="#psychiartic" data-toggle="tab">PSYCHIARTIC</a>
				</li>
				
			</ul>

			<div id="myTabContent1" class="tab-content padding-10">
				<div class="row tab-pane fade in active" id="general">
					<div class="col col-md-4">
						<label class="checkbox">
							<input type="checkbox" name="normal_general_identifier[]" value="Fever" 
							<?php if(in_array("Fever",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['General'])) { ?> checked="checked" <?php } ?>>
							<i></i>Fever</label>
						<label class="checkbox">
							<input type="checkbox" name="normal_general_identifier[]" value="Chills"
							<?php if(in_array("Chills",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['General'])) { ?> checked="checked" <?php } ?>>
							<i></i>Chills</label>

					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_general_identifier[]" value="Lots Of Appetite" <?php if(in_array("Lots Of Appetite",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['General'])) { ?> checked="checked" <?php } ?>>
							<i></i>Lots Of Appetite</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_general_identifier[]" value="Weight Loss"
						<?php if(in_array("Weight Loss",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['General'])) { ?> checked="checked" <?php } ?>>
							<i></i>Weight Loss</label>
					</div>

					<div class="col col-md-4">
						<label class="checkbox">
							<input type="checkbox" name="normal_general_identifier[]" value="Night Sweats" <?php if(in_array("Night Sweats",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['General'])) { ?> checked="checked" <?php } ?>>
							<i></i>Night Sweats</label>
						<label class="checkbox">
							<input type="checkbox" name="normal_general_identifier[]" value="Fever With Rash" <?php if(in_array("Fever With Rash",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['General'])) { ?> checked="checked" <?php } ?>>
							<i></i>Fever With Rash</label>
					</div>
				</div>
				<div class="tab-pane fade" id="head_gn">
					<div class="row">
						<div class="col col-md-4">
							<label class="checkbox">
								<input type="checkbox" name="normal_head_identifier[]" value="Headache"
								<?php if(in_array("Headache",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Head'])) { ?> checked="checked" <?php } ?>>
							<i></i>Headache</label>
							<label class="checkbox">
								<input type="checkbox" name="normal_head_identifier[]" value="Dizziness"
								<?php if(in_array("Dizziness",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Head'])) { ?> checked="checked" <?php } ?>>
							<i></i>Dizziness</label>
						</div>
						<div class="col col-md-6">
							<label class="checkbox">
								<input type="checkbox" name="normal_head_identifier[]" value="Head Swelling" <?php if(in_array("Head Swelling",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Head'])) { ?> checked="checked" <?php } ?>>
							<i></i>Head Swelling</label>
							<label class="checkbox">
								<input type="checkbox" name="normal_head_identifier[]" value="Seizures"
								<?php if(in_array("Seizures",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Head'])) { ?> checked="checked" <?php } ?>>
							<i></i>Seizures</label>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="eyes">
					<div class="row">
						<div class="col col-md-4">
							<label class="checkbox">
							<input type="checkbox" name="normal_eyes_identifier[]" value="Eye Pain" 
							<?php if(in_array("Eye Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Eyes'])) { ?> checked="checked" <?php } ?>>
							<i></i>Eye Pain</label>
							<label class="checkbox">
							<input type="checkbox" name="normal_eyes_identifier[]" value="Eye Discharge"
							<?php if(in_array("Eye Discharge",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Eyes'])) { ?> checked="checked" <?php } ?>>
							<i></i>Eye Discharge</label>
						</div>
						<div class="col col-md-6">
							<label class="checkbox">
							<input type="checkbox" name="normal_eyes_identifier[]" value="Blurring Of Vission" <?php if(in_array("Blurring Of Vission",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Eyes'])) { ?> checked="checked" <?php } ?>>
							<i></i>Blurring Of Vission</label>
							<label class="checkbox">
							<input type="checkbox" name="normal_eyes_identifier[]" value="Double Vision"
							<?php if(in_array("Double Vision",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Eyes'])) { ?> checked="checked" <?php } ?>>
							<i></i>Double Vision</label>
						</div>
					</div>
			</div>

<!-- ent related information -->

				<div class="tab-pane fade" id="ent">
					<div class="row">
						<div class="col col-md-3">
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Ear Pain"
							<?php if(in_array("Ear Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Ear Pain</label>
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Ear Discharge"
							<?php if(in_array("Ear Discharge",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Ear Discharge</label>
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Watering Of Eyes" <?php if(in_array("Watering Of Eyes",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Watering Of Eyes</label>
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Vertigo"
							<?php if(in_array("Vertigo",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Vertigo</label>
						</div>
						<div class="col col-md-3">
							
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Tinnitus"
							<?php if(in_array("Tinnitus",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Tinnitus</label>
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Nose Bleeding"
							<?php if(in_array("Nose Bleeding",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Nose Bleeding</label>
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Nose Discharge"
							<?php if(in_array("Nose Discharge",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Nose Discharge</label>
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Horness Of Voice"
							<?php if(in_array("Horness Of Voice",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Horness Of Voice</label>
						</div>
						<div class="col col-md-3">
							
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Throat Pain"
							<?php if(in_array("Throat Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Throat Pain</label>
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Bad Smell"
							<?php if(in_array("Bad Smell",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Bad Smell</label>
							
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Neck Swelling"
							<?php if(in_array("Neck Swelling",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Neck Swelling</label>
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Pain On Swallowing(Dysphagia)"
							<?php if(in_array("Pain On Swallowing(Dysphagia)",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Pain On Swallowing(Dysphagia)</label>
						</div>
						<div class="col col-md-3">
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Cracked Angles Of Mouth" <?php if(in_array("Cracked Angles Of Mouth",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>><i></i>Cracked Angles Of Mouth</label>
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Ulcerated Lip" <?php if(in_array("Ulcerated Lip",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Ulcerated Lip</label>
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Bleeding Gums" <?php if(in_array("Bleeding Gums",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Bleeding Gums</label>
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Swollen Gums"
							<?php if(in_array("Swollen Gums",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Swollen Gums</label>
							<label class="checkbox">
							<input type="checkbox" name="normal_ent_identifier[]" value="Difficulty In Swallowing(Odynophagia)" <?php if(in_array("Difficulty In Swallowing(Odynophagia)",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Ent'])) { ?> checked="checked" <?php } ?>>
							<i></i>Difficulty In Swallowing(Odynophagia)</label>
						</div>
					</div>

				</div>
<!--rs related information -->
				<div class="row tab-pane fade" id="rs">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_rs_identifier[]" value="Shortness Of Breath" <?php if(in_array("Shortness Of Breath",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Respiratory_system'])) { ?> checked="checked" <?php } ?>>
						<i></i>Shortness Of Breath</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_rs_identifier[]" value="Spectrum From Mouth"
						<?php if(in_array("Spectrum From Mouth",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Respiratory_system'])) { ?> checked="checked" <?php } ?>>
						<i></i>Spectrum From Mouth</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_rs_identifier[]" value="Cough"
						<?php if(in_array("Cough",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Respiratory_system'])) { ?> checked="checked" <?php } ?>>
						<i></i>Cough</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_rs_identifier[]" value="Blood From Mouth"
						<?php if(in_array("Blood From Mouth",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Respiratory_system'])) { ?> checked="checked" <?php } ?>>
						<i></i>Blood From Mouth</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_rs_identifier[]" value="Wheezing"
						<?php if(in_array("Wheezing",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Respiratory_system'])) { ?> checked="checked" <?php } ?>>
						<i></i>Wheezing</label>
					</div>
				</div>
<!--cvs related information-->
				<div class="row tab-pane fade" id="cvs">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_cvs_identifier[]" value="Chest Pain"
						<?php if(in_array("Chest Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Cardio_vascular_system'])) { ?> checked="checked" <?php } ?>>
						<i></i>Chest Pain</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_cvs_identifier[]" value="Edoma Of Feet"
						<?php if(in_array("Edoma Of Feet",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Cardio_vascular_system'])) { ?> checked="checked" <?php } ?>>
						<i></i>Edoma Of Feet</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_cvs_identifier[]" value="Cyanosis"
						<?php if(in_array("Cyanosis",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Cardio_vascular_system'])) { ?> checked="checked" <?php } ?>>
						<i></i>Cyanosis</label>
					</div>
				</div>
<!--GI related information -->
				<div class="row tab-pane fade" id="gi">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_gi_identifier[]" value="Dyspharia(Difficulty In Eating)"
						<?php if(in_array("Dyspharia(Difficulty In Eating)",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Gastro_intestinal'])) { ?> checked="checked" <?php } ?>>
						<i></i>Dyspharia(Difficulty In Eating)</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gi_identifier[]" value="Nausea"
						<?php if(in_array("Nausea",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Gastro_intestinal'])) { ?> checked="checked" <?php } ?>>
						<i></i>Nausea</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gi_identifier[]" value="Vomtings"
						<?php if(in_array("Vomtings",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Gastro_intestinal'])) { ?> checked="checked" <?php } ?>>
						<i></i>Vomtings</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_gi_identifier[]" value="Blood In Vomiting(Haematemesis)"
						<?php if(in_array("Blood In Vomiting(Haematemesis)",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Gastro_intestinal'])) { ?> checked="checked" <?php } ?>>
						<i></i>Blood In Vomiting(Haematemesis)</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gi_identifier[]" value="Diarrhoea"
						<?php if(in_array("Diarrhoea",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Gastro_intestinal'])) { ?> checked="checked" <?php } ?>>
						<i></i>Diarrhoea</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gi_identifier[]" value="Constipation"
						<?php if(in_array("Constipation",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Gastro_intestinal'])) { ?> checked="checked" <?php } ?>>
						<i></i>Constipation</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_gi_identifier[]" value="Melena(Block Stools)"
						<?php if(in_array("Melena(Block Stools)",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Gastro_intestinal'])) { ?> checked="checked" <?php } ?>>
						<i></i>Melena(Block Stools)</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gi_identifier[]" value="Blood Per Rectum"
						<?php if(in_array("Blood Per Rectum",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Gastro_intestinal'])) { ?> checked="checked" <?php } ?>>
						<i></i>Blood Per Rectum</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gi_identifier[]" value="Perianel Pain"
						<?php if(in_array("Perianel Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Gastro_intestinal'])) { ?> checked="checked" <?php } ?>>
						<i></i>Perianel Pain</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gi_identifier[]" value="Incontinence Of Stools"
						<?php if(in_array("Incontinence Of Stools",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Gastro_intestinal'])) { ?> checked="checked" <?php } ?>>
						<i></i>Incontinence Of Stools</label>						
					</div>
				</div>
<!--GU related information -->
				<div class="row tab-pane fade" id="gu">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_gu_identifier[]" value="Pain On Micturation"
						<?php if(in_array("Pain On Micturation",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Genito_urinary'])) { ?> checked="checked" <?php } ?>>
						<i></i>Pain On Micturation</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gu_identifier[]" value="Increase Micturation"
						<?php if(in_array("Increase Micturation",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Genito_urinary'])) { ?> checked="checked" <?php } ?>>
						<i></i>Increase Micturation</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gu_identifier[]" value="Burning Micturation"
						<?php if(in_array("Burning Micturation",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Genito_urinary'])) { ?> checked="checked" <?php } ?>>
						<i></i>Burning Micturation</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gu_identifier[]" value="Dribbling Of Urine"
						<?php if(in_array("Dribbling Of Urine",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Genito_urinary'])) { ?> checked="checked" <?php } ?>>
						<i></i>Dribbling Of Urine</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_gu_identifier[]" value="Unable To Pass Urine"
						<?php if(in_array("Unable To Pass Urine",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Genito_urinary'])) { ?> checked="checked" <?php } ?>>
						<i></i>Unable To Pass Urine</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gu_identifier[]" value="Blood In Urine"
						<?php if(in_array("Blood In Urine",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Genito_urinary'])) { ?> checked="checked" <?php } ?>>
						<i></i>Blood In Urine</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gu_identifier[]" value="Flank Pain"
						<?php if(in_array("Flank Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Genito_urinary'])) { ?> checked="checked" <?php } ?>>
						<i></i>Flank Pain</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gu_identifier[]" value="Testis Swelling"
						<?php if(in_array("Testis Swelling",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Genito_urinary'])) { ?> checked="checked" <?php } ?>>
						<i></i>Testis Swelling</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_gu_identifier[]" value="Suprapuvic Pain"
						<?php if(in_array("Suprapuvic Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Genito_urinary'])) { ?> checked="checked" <?php } ?>>
						<i></i>Suprapuvic Pain</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gu_identifier[]" value="Incontinence Of Urine"
						<?php if(in_array("Incontinence Of Urine",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Genito_urinary'])) { ?> checked="checked" <?php } ?>>
						<i></i>Incontinence Of Urine</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gu_identifier[]" value="Urine Retention"
						<?php if(in_array("Urine Retention",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Genito_urinary'])) { ?> checked="checked" <?php } ?>>
						<i></i>Urine Retention</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gu_identifier[]" value="Testis Pain"
						<?php if(in_array("Testis Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Genito_urinary'])) { ?> checked="checked" <?php } ?>>
						<i></i>Testis Pain</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gu_identifier[]" value="Bed Wetting"
						<?php if(in_array("Bed Wetting",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Genito_urinary'])) { ?> checked="checked" <?php } ?>>
						<i></i>Bed Wetting</label>
					</div>
				</div>									
<!--gyn related information -->
				<div class="row tab-pane fade" id="gyn">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_gyn_identifier[]" value="Vaginal Bleeding"
						<?php if(in_array("Vaginal Bleeding",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Gynaecology'])) { ?> checked="checked" <?php } ?>>
						<i></i>Vaginal Bleeding</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gyn_identifier[]" value="Absent Of Periods"
						<?php if(in_array("Absent Of Periods",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Gynaecology'])) { ?> checked="checked" <?php } ?>>
						<i></i>Absent Of Periods</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gyn_identifier[]" value="Painful Periods"
						<?php if(in_array("Painful Periods",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Gynaecology'])) { ?> checked="checked" <?php } ?>>
						<i></i>Painful Periods</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_gyn_identifier[]" value="Breast Lump"
						<?php if(in_array("Breast Lump",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Gynaecology'])) { ?> checked="checked" <?php } ?>>
						<i></i>Breast Lump</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_gyn_identifier[]" value="Nipple Discharge"
						<?php if(in_array("Nipple Discharge",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Gynaecology'])) { ?> checked="checked" <?php } ?>>
						<i></i>Nipple Discharge</label>
					</div>
				</div>
<!--ENDO CRINOLOGY related information -->
				<div class="row tab-pane fade" id="endo_cri">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_cri_identifier[]" value="Poly Uria"
						<?php if(in_array("Poly Uria",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Endo_crinology'])) { ?> checked="checked" <?php } ?>>
						<i></i>Poly Uria</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_cri_identifier[]" value="Polyphagia"
						<?php if(in_array("Polyphagia",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Endo_crinology'])) { ?> checked="checked" <?php } ?>>
						<i></i>Polyphagia</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_cri_identifier[]" value="Heat Intolerance"
						<?php if(in_array("Heat Intolerance",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Endo_crinology'])) { ?> checked="checked" <?php } ?>>
						<i></i>Heat Intolerance</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_cri_identifier[]" value="Cold Intolerence"
						<?php if(in_array("Cold Intolerence",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Endo_crinology'])) { ?> checked="checked" <?php } ?>>
						<i></i>Cold Intolerence</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_cri_identifier[]" value="Fatique"
						<?php if(in_array("Fatique",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Endo_crinology'])) { ?> checked="checked" <?php } ?>>
						<i></i>Fatique</label>
					</div>
				</div>

<!--msk related information-->
				<div class="tab-pane fade" id="msk">
					<div class="row">
					<div class="col col-md-3">
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Neck Pain"
						<?php if(in_array("Neck Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Neck Pain</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Back Pain"
						<?php if(in_array("Back Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Back Pain</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Shoulder Pain"
						<?php if(in_array("Shoulder Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Shoulder Pain</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Elbow Pain"
						<?php if(in_array("Elbow Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Elbow Pain</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Wrist Pain"
						<?php if(in_array("Wrist Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Wrist Pain</label>
					</div>
					<div class="col col-md-3">
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Hip Pain"
						<?php if(in_array("Hip Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Hip Pain</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Ancle Pain"
						<?php if(in_array("Ancle Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Ancle Pain</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Finger Pain"
						<?php if(in_array("Finger Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Finger Pain</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Knee Pain"
						<?php if(in_array("Knee Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Knee Pain</label>
					</div>
					<div class="col col-md-3">
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Toe Pain"
						<?php if(in_array("Toe Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Toe Pain</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Muscle Pain"
						<?php if(in_array("Muscle Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Muscle Pain</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Lower Back Pain"
						<?php if(in_array("Lower Back Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Lower Back Pain</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Body Pain"
						<?php if(in_array("Body Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Body Pain</label>
					</div>
					<div class="col col-md-3">
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Numbness"
						<?php if(in_array("Numbness",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Numbness</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Tingling"
						<?php if(in_array("Tingling",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Tingling</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Morning Stiffness"
						<?php if(in_array("Morning Stiffness",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Morning Stiffness</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_msk_identifier[]" value="Night Pain"
						<?php if(in_array("Night Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Musculo_skeletal_syatem'])) { ?> checked="checked" <?php } ?>>
						<i></i>Night Pain</label>
					</div>
					</div>
				</div>
<!--cns related information -->
				<div class="row tab-pane fade" id="cns">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_cns_identifier[]" value="Loss Of Consciousness"
						<?php if(in_array("Loss Of Consciousness",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
						<i></i>Loss Of Consciousness</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_cns_identifier[]" value="Abnormal Gait"
						<?php if(in_array("Abnormal Gait",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
						<i></i>Abnormal Gait</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_cns_identifier[]" value="Aphasia"
						<?php if(in_array("Aphasia",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
						<i></i>Aphasia</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_cns_identifier[]" value="Insufficient Sleep"
						<?php if(in_array("Insufficient Sleep",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
						<i></i>Insufficient Sleep</label>
						<label class="checkbox">
						<input type="checkbox" name="normal_cns_identifier[]" value="Abnormal Sleep"
						<?php if(in_array("Abnormal Sleep",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
						<i></i>Abnormal Sleep</label>
					</div>
				</div>
<!-- PSYCHIARTIC-->
				<div class="row tab-pane fade" id="psychiartic">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" name="normal_psychiartic_identifier[]" value="Psychiartic"
						<?php if(in_array("Psychiartic",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal']['Psychiartic'])) { ?> checked="checked" <?php } ?>>
						<i></i>Psychiartic</label>
						
					</div>
				</div>
			</div>

				</div>

			</div>
			<!-- Emergency Start-->
			<div class="emergency_related">

						<div class="widget-body">

							<hr class="simple">
							<ul id="myTab1" class="nav nav-tabs bordered">
								<li class="active">
									<a href="#emergency_disease" data-toggle="tab"> EMERGENCY</a>
								</li>
								<li>
									<a href="#emergency_bites" data-toggle="tab"> BITES</a>
								</li>
														
							</ul>

					<!-- EMERGENCY ATTACK DISEASE --------------------------------------->		
							<div id="myTabContent1" class="tab-content padding-10">	
							<div class="tab-pane fade in active" id="emergency_disease">
								<div class="row">
									<div class="col col-md-4">
										<label class="checkbox">
								<input type="checkbox" name="emergency_identifier[]" value="Falls and Trauma" <?php if(in_array("Falls and Trauma",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Disease'])) { ?> checked="checked" <?php }?>>
										<i></i>Falls And Trauma</label>
										<label class="checkbox">
										<input type="checkbox" name="emergency_identifier[]" value="Epi Staxsis" <?php if(in_array("Epi Staxsis",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Disease'])) { ?> checked="checked" <?php }?>>
										<i></i>Epi Staxsis</label>
										<label class="checkbox">
										<input type="checkbox" name="emergency_identifier[]" value="Seizures" <?php if(in_array("Seizures",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Disease'])) { ?> checked="checked" <?php }?>>
										<i></i>Seizures</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="emergency_identifier[]" value="Giddiness" <?php if(in_array("Giddiness",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Disease'])) { ?> checked="checked" <?php }?>>
										<i></i>Giddiness</label>
										<label class="checkbox">
										<input type="checkbox" name="emergency_identifier[]" value="Chest Pain" <?php if(in_array("Chest Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Disease'])) { ?> checked="checked" <?php }?>>
										<i></i>Chest Pain</label>
										<label class="checkbox">
										<input type="checkbox" name="emergency_identifier[]" value="Breathing Problem" <?php if(in_array("Breathing Problem",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Disease'])) { ?> checked="checked" <?php }?>>
										<i></i>Breathing Problem</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="emergency_identifier[]" value="Swelling" <?php if(in_array("Swelling",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Disease'])) { ?> checked="checked" <?php }?>> 
										<i></i>Swelling</label>
										<label class="checkbox">
										<input type="checkbox" name="emergency_identifier[]" value="Suicides" <?php if(in_array("Suicides",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Disease'])) { ?> checked="checked" <?php }?>>
										<i></i>Suicides</label>
										<label class="checkbox">
										<input type="checkbox" name="emergency_identifier[]" value="Burns" <?php if(in_array("Burns",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Disease'])) { ?> checked="checked" <?php }?>>
										<i></i>Burns</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="emergency_identifier[]" value="Abdomen Pain" <?php if(in_array("Abdomen Pain",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Disease'])) { ?> checked="checked" <?php }?>>
										<i></i>Abdomen Pain</label>
										<label class="checkbox">
										<input type="checkbox" name="emergency_identifier[]" value="Others" <?php if(in_array("Others",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Disease'])) { ?> checked="checked" <?php }?>>
										<i></i>Others</label>
									</div>
								</div>
							</div>

				

				<!--  BITES PROBLEMS  ------------------------->			

							<div class="tab-pane fade" id="emergency_bites">
								<div class="row">
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="emergency_bites_identifier[]" value="Scorpion" <?php if(in_array("Scorpion",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Bites'])) { ?> checked="checked" <?php }?>>
										<i></i>Scorpion</label>
										<label class="checkbox">
										<input type="checkbox" name="emergency_bites_identifier[]" value="Snake" <?php if(in_array("Snake",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Bites'])) { ?> checked="checked" <?php }?>>
										<i></i>Snake</label>
										<label class="checkbox">
										<input type="checkbox" name="emergency_bites_identifier[]" value="Honey Bee" <?php if(in_array("Honey Bee",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Bites'])) { ?> checked="checked" <?php }?>>
										<i></i>Honey Bee</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="emergency_bites_identifier[]" value="Monkey" <?php if(in_array("Monkey",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Bites'])) { ?> checked="checked" <?php }?>>
										<i></i>Monkey</label>
										<label class="checkbox">
										<input type="checkbox" name="emergency_bites_identifier[]" value="Dog" <?php if(in_array("Dog",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency']['Bites'])) { ?> checked="checked" <?php }?>>
										<i></i>Dog</label>
									</div>
									
								</div>
							</div>

				

				
								</div>
					
							</div>
						</div>
			<!--END EMERGENCY-->

		<!-- CHRONIC RELATED INFORMATION (ON CLICK RADIO) -->



					<div class="chronic_related">

						<div class="widget-body">

							<hr class="simple">
							<ul id="myTab1" class="nav nav-tabs bordered">
								<li class="active">
									<a href="#chronic_eyes" data-toggle="tab"> EYES</a>
								</li>
								<li>
									<a href="#chronic_ent" data-toggle="tab"> EAR</a>
								</li>
								<li>
									<a href="#chronic_cns" data-toggle="tab">CNS</a>
								</li>
								<li>
									<a href="#chronic_rs" data-toggle="tab"> RS</a>
								</li>
								<li>
									<a href="#chronic_cvs" data-toggle="tab"> CVS</a>
								</li>
								<li>
									<a href="#chronic_gi" data-toggle="tab"> GI</a>
								</li>
								<li>
									<a href="#chronic_blood" data-toggle="tab"> BLOOD</a>
								</li>
								<li>
									<a href="#chronic_kidney" data-toggle="tab"> KIDNEY</a>
								</li>
								<li>
									<a href="#chronic_vandm" data-toggle="tab"> VITAMINS &MINERAL RELATED</a>
								</li>
								<li>
									<a href="#chronic_bones_chronic" data-toggle="tab"> BONES RELATED</a>
								</li>
								<li>
									<a href="#chronic_skin_chronic" data-toggle="tab">SKIN</a>
								</li>
								<li>
									<a href="#chronic_endo_chronic" data-toggle="tab">ENDO</a>
								</li>
								<li>
									<a href="#others_chronic" data-toggle="tab">OTHERS</a>
								</li>						
							</ul>

					<!-- EYE PROBLEMS --------------------------------------->		
							<div id="myTabContent1" class="tab-content padding-10">	
							<div class="tab-pane fade in active" id="chronic_eyes">
								<div class="row">
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_eyes_identifier[]" value="Glacouma" <?php if(in_array("Glacouma",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Eyes'])) { ?> checked="checked" <?php } ?> 
										/>
										<i></i>Glacouma</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_eyes_identifier[]" value="Proptosis" <?php if(in_array("Proptosis",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Eyes'])) { ?> checked="checked" <?php } ?>>
										<i></i>Proptosis</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_eyes_identifier[]" value="Ptosis" <?php if(in_array("Ptosis",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Eyes'])) { ?> checked="checked" <?php } ?>>
										<i></i>Ptosis</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_eyes_identifier[]" value="Night Blindness" <?php if(in_array("Night Blindness",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Eyes'])) { ?> checked="checked" <?php } ?>>
										<i></i>Night Blindness</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_eyes_identifier[]" value="Refractive Errors" <?php if(in_array("Refractive Errors",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Eyes'])) { ?> checked="checked" <?php } ?> >
										<i></i>Refractive Errors</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_eyes_identifier[]" value="Retinal Pathology" <?php if(in_array("Retinal Pathology",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Eyes'])) { ?> checked="checked" <?php } ?>>
										<i></i>Retinal Pathology</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_eyes_identifier[]" value="Squints" <?php if(in_array("Squints",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Eyes'])) { ?> checked="checked" <?php } ?>>
										<i></i>Squints</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_eyes_identifier[]" value="Cataract" <?php if(in_array("Cataract",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Eyes'])) { ?> checked="checked" <?php } ?>>
										<i></i>Cataract</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_eyes_identifier[]" value="Others" <?php if(in_array("Others",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Eyes'])) { ?> checked="checked" <?php } ?>>
										<i></i>Others</label>
									</div>
								</div>
							</div>

				<!-- END EYE PROBLEMS --------------------------------------->	

				<!--  EAR PROBLEMS  ------------------------->			

							<div class="tab-pane fade" id="chronic_ent">
								<div class="row">
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_ent_identifier[]" value="Csom" <?php if(in_array("Csom",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Ent'])) { ?> checked="checked" <?php } ?>>
										<i></i>Csom</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_ent_identifier[]" value="Hearing Loss" <?php if(in_array("Hearing Loss",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Ent'])) { ?> checked="checked" <?php } ?>>
										<i></i>Hearing Loss</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_ent_identifier[]" value="Sinusitis" <?php if(in_array("Sinusitis",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Ent'])) { ?> checked="checked" <?php } ?>>
										<i></i>Sinusitis</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_ent_identifier[]" value="Rhinitis" <?php if(in_array("Rhinitis",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Ent'])) { ?> checked="checked" <?php } ?>>
										<i></i>Rhinitis</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_ent_identifier[]" value="Dental Caries" <?php if(in_array("Dental Caries",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Ent'])) { ?> checked="checked" <?php } ?>>
										<i></i>Dental Caries</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										
										<input type="checkbox" name="chronic_ent_identifier[]" value="Penidontal Disease" <?php if(in_array("Penidontal Disease",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Ent'])) { ?> checked="checked" <?php } ?>>
										<i></i>Penidontal Disease</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_ent_identifier[]" value="Acate Otitis Media" <?php if(in_array("Acate Otitis Media",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Ent'])) { ?> checked="checked" <?php } ?>>
										<i></i>Acate Otitis Media</label>
									</div>
								</div>
							</div>

				<!--  END EAR PROBLEMS  ------------------------->

				<!--  CNS PROBLEMS  ------------------------->

						<div class="tab-pane fade" id="chronic_cns">
								<div class="row">
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_cns_identifier[]" value="Epilepy" <?php if(in_array("Epilepy",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Epilepy</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_cns_identifier[]" value="Mysthenia Gravis" <?php if(in_array("Mysthenia Gravis",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Mysthenia Gravis</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_cns_identifier[]" value="Migrane" <?php if(in_array("Migrane",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Migrane</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_cns_identifier[]" value="Ocd" <?php if(in_array("Ocd",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Ocd</label>
										<label class="checkbox">
									<input type="checkbox" name="chronic_cns_identifier[]" value="Personality Disorders" <?php if(in_array("Personality Disorders",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Personality Disorders</label>
									</div>

									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_cns_identifier[]" value="Anxiesty" <?php if(in_array("Anxiesty",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Anxiesty</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_cns_identifier[]" value="Depression Disorders" <?php if(in_array("Depression Disorders",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Depression Disorders</label>
										<!--label class="checkbox">
										<input type="checkbox" name="chronic_cns_identifier[]" value="Anxiesty" <?php if(in_array("Anxiesty",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Anxiesty</label-->
										<label class="checkbox">
										<input type="checkbox" name="chronic_cns_identifier[]" value="Bipolar Disorders" <?php if(in_array("Bipolar Disorders",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Bipolar Disorders</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_cns_identifier[]" value="Schizophrenia" <?php if(in_array("Schizophrenia",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Schizophrenia</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_cns_identifier[]" value="Neuro-Developmental Disorders" <?php if(in_array("Neuro-Developmental Disorders",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Neuro-Developmental Disorders</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_cns_identifier[]" value="Phobic Disorders" <?php if(in_array("Phobic Disorders",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Phobic Disorders</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_cns_identifier[]" value="Others" <?php if(in_array("Others",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Central_nervous_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Others</label>
									</div>
								</div>
							</div>

				<!--  END CNS PROBLEMS  ------------------------->
				
				<!-- RS PROBLEMS -------------------------------------->
							<div class="tab-pane fade" id="chronic_rs">
							<div class="row">
								<div class="col col-4">
									<label class="checkbox">
									<input type="checkbox" name="chronic_rs_identifier[]" value="Asthma" <?php if(in_array("Asthma",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Respiratory_system'])) { ?> checked="checked" <?php } ?>>
									<i></i>Asthma</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_rs_identifier[]" value="Pneumonia" <?php if(in_array("Pneumonia",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Respiratory_system'])) { ?> checked="checked" <?php } ?>>
									<i></i>Pneumonia</label>
								</div>
								<div class="col col-4">
									<label class="checkbox">
									<input type="checkbox" name="chronic_rs_identifier[]" value="Emphysema" <?php if(in_array("Emphysema",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Respiratory_system'])) { ?> checked="checked" <?php } ?>>
									<i></i>Emphysema</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_rs_identifier[]" value="Chronic Bronchitis" <?php if(in_array("Chronic Bronchitis",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Respiratory_system'])) { ?> checked="checked" <?php } ?>>
									<i></i>Chronic Bronchitis</label>
								</div>
								<div class="col col-4">
									<label class="checkbox">
									<input type="checkbox" name="chronic_rs_identifier[]" value="Bronchiectasis" <?php if(in_array("Bronchiectasis",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Respiratory_system'])) { ?> checked="checked" <?php } ?>>
									<i></i>Bronchiectasis</label>
									<label class="checkbox">
				<input type="checkbox" name="chronic_rs_identifier[]" value="Others" <?php if(in_array("Others",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Respiratory_system'])) { ?> checked="checked" <?php } ?>>
								<i></i>Others</label>
								</div>
							</div>
								
							</div>

				<!--END RS PROBLEMS -------------------------------------->	

				<!-- CVS PROBLEMS -------------------------------------->

							<div class="tab-pane fade" id="chronic_cvs">
								<div class="row">
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_cvs_identifier[]" value="Vsd" <?php if(in_array("Vsd",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Cardio_vascular_system'])) { ?> checked="checked" <?php } ?> >
										<i></i>Vsd</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_cvs_identifier[]" value="Rhd" <?php if(in_array("Rhd",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Cardio_vascular_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Rhd</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_cvs_identifier[]" value="Mr" <?php if(in_array("Mr",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Cardio_vascular_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Mr</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_cvs_identifier[]" value="Asd" <?php if(in_array("Asd",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Cardio_vascular_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Asd</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_cvs_identifier[]" value="Others" <?php if(in_array("Others",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Cardio_vascular_system'])) { ?> checked="checked" <?php } ?>>
										<i></i>Others</label>
									</div>
								</div>
							</div>

				<!-- END CVS PROBLEMS -------------------------------------->

				<!-- GI PROBLEMS ------------------------------------------->

							<div class="tab-pane fade" id="chronic_gi">
								<div class="row">
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_gi_identifier[]" value="Acid Peptic Disease" <?php if(in_array("Acid Peptic Disease",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Gastro_intestinal'])) { ?> checked="checked" <?php } ?>>
										<i></i>Acid Peptic Disease</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_gi_identifier[]" value="Appendicitis" <?php if(in_array("Appendicitis",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Gastro_intestinal'])) { ?> checked="checked" <?php } ?>>
										<i></i>Appendicitis</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_gi_identifier[]" value="Jaundie" <?php if(in_array("Jaundie",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Gastro_intestinal'])) { ?> checked="checked" <?php } ?>>
										<i></i>Jaundie</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_gi_identifier[]" value="Ascites" <?php if(in_array("Ascites",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Gastro_intestinal'])) { ?> checked="checked" <?php } ?>>
										<i></i>Ascites</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_gi_identifier[]" value="Others" <?php if(in_array("Others",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Gastro_intestinal'])) { ?> checked="checked" <?php } ?>>
										<i></i>Others</label>
									</div>
								</div>
							</div>

				<!-- END GI PROBLEMS ------------------------------------------->

				<!-- BLOOD PROBLEMS -------------------------------------------->

							<div class="tab-pane fade" id="chronic_blood">
								<div class="row">
									<div class="col col-md-3">
										<label class="checkbox">
										<input type="checkbox" name="chronic_blood_identifier[]" value="Anaemia" <?php if(in_array("Anaemia",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
										<i></i>Anaemia</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_blood_identifier[]" value="Mild" <?php if(in_array("Mild",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
										<i></i>Mild</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_blood_identifier[]" value="Moderate" <?php if(in_array("Moderate",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
										<i></i>Moderate</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_blood_identifier[]" value="Seveare" <?php if(in_array("Seveare",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
										<i></i>Seveare</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_blood_identifier[]" value="Iron Deficency" <?php if(in_array("Iron Deficency",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
										<i></i>Iron Deficency</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_blood_identifier[]" value="B 12 Deficieny"  <?php if(in_array("B 12 Deficieny",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
										<i></i>B 12 Deficieny</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_blood_identifier[]" value="A Plastic Anemia" <?php if(in_array("A Plastic Anemia",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
										<i></i>A Plastic Anemia</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_blood_identifier[]" value="Sickle Cell Anemia" <?php if(in_array("Sickle Cell Anemia",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
										<i></i>Sickle Cell Anemia</label>
										<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="Anaemia" <?php if(in_array("Anaemia",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
										<i></i>Anaemia</label>
									</div>
									<div class="col col-md-3">
									<p><strong>PLATELET DISORDER</strong></p>
									<br>
								
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="Vwb Disorder" <?php if(in_array("Vwb Disorder",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
									<i></i>Vwb Disorder</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="Hemophibia" <?php if(in_array("Hemophibia",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
									<i></i>Hemophibia</label>
									</div>
									<div class="col col-md-3">
								<p><strong>BLOOD CANCER</strong></p>
									<br>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="Lymphoma" <?php if(in_array("Lymphoma",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
									<i></i>Lymphoma</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="Polycythemia Vera" <?php if(in_array("Polycythemia Vera",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
									<i></i>Polycythemia Vera</label>
									</div>
									<div class="col col-md-3">
									<p><strong>LEUKAEMIA</strong></p>
									<br>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="Leukaemia" <?php if(in_array("Leukaemia",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
									<i></i>Leukaemia</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="CLL" <?php if(in_array("CLL",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
									<i></i>CLL</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="ALL" <?php if(in_array("ALL",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
									<i></i>ALL</label>

									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="AML" <?php if(in_array("AML",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
									<i></i>AML</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="CML" <?php if(in_array("CML",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
									<i></i>CML</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="Others" <?php if(in_array("Others",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Blood'])) { ?> checked="checked" <?php } ?>>
									<i></i>Others</label>
								</div>
								</div>
							</div>

				<!--END  BLOOD PROBLEMS -------------------------------------------->

				<!--KIDNEY PROBLEMS -------------------------------------------->
							<div class="tab-pane fade" id="chronic_kidney">
								<div class="row">
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_kidney_identifier[]" value="CKD(Chronic Kidney  Disease)" <?php if(in_array("CKD(Chronic Kidney  Disease)",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Kidney'])) { ?> checked="checked" <?php } ?>>
										<i></i>CKD(Chronic Kidney  Disease)</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_kidney_identifier[]" value="ARF(Acute Renal Failure)" <?php if(in_array("ARF(Acute Renal Failure)",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Kidney'])) { ?> checked="checked" <?php } ?>>
										<i></i>ARF(Acute Renal Failure)</label>
										</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_kidney_identifier[]" value="Renal Stones" <?php if(in_array("Renal Stones",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Kidney'])) { ?> checked="checked" <?php } ?>>
										<i></i>Renal Stones</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_kidney_identifier[]" value="Nephrotic Syndrome" <?php if(in_array("Nephrotic Syndrome",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Kidney'])) { ?> checked="checked" <?php } ?>>
										<i></i>Nephrotic Syndrome</label>
									</div>
									<div class="col col-md-4">
											<label class="checkbox">
										<input type="checkbox" name="chronic_kidney_identifier[]" value="Polycystic Kidney Disease" <?php if(in_array("Polycystic Kidney Disease",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Kidney'])) { ?> checked="checked" <?php } ?>>
											<i></i>Polycystic Kidney Disease</label>
										<label class="checkbox">
									<input type="checkbox" name="chronic_kidney_identifier[]" value="Urinary Tract Infections" <?php if(in_array("Urinary Tract Infections",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Kidney'])) { ?> checked="checked" <?php } ?>>
										<i></i>Urinary Tract Infections</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_kidney_identifier[]" value="Others" <?php if(in_array("Others",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Kidney'])) { ?> checked="checked" <?php } ?>>
										<i></i>Others</label>
									</div>
								</div>
							</div>

				<!--END KIDNEY PROBLEMS -------------------------------------------->

				<!--VITAMINS & MINERALS DEFECIENCY PROBLEMS -------------------------------------------->
							<div class="tab-pane fade" id="chronic_vandm">
								<div class="row">
									<div class="col col-md-4">
									<label class="checkbox">
									<input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamin D" <?php if(in_array("Vitamin D",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['VandM'])) { ?> checked="checked" <?php } ?>>
									<i></i>Vitamin D</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamin B2" <?php if(in_array("Vitamin B2",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['VandM'])) { ?> checked="checked" <?php } ?>>
									<i></i>Vitamin B2</label>
								</div>

								<div class="col col-md-4">
									<label class="checkbox">
									<input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamin A" <?php if(in_array("Vitamin A",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['VandM'])) { ?> checked="checked" <?php } ?>>
									<i></i>Vitamin A</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamin C" <?php if(in_array("Vitamin C",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['VandM'])) { ?> checked="checked" <?php } ?>>
									<i></i>Vitamin C</label>
									<label class="checkbox">
								    <input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamin B Complex" <?php if(in_array("Vitamin B Complex",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['VandM'])) { ?> checked="checked" <?php } ?>>
									<i></i>Vitamin B Complex</label>
								</div>
							</div>
								
							</div>
				<!--END VITAMINS & MINERALS DEFECIENCY PROBLEMS -------------------------------------------->

				<!--  BONES PROBLEMS  -------------------------------------------------------->
							<div class="tab-pane fade" id="chronic_bones_chronic">
								<div class="row">
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_bones_identifier[]" value="Osteoporosis" <?php if(in_array("Osteoporosis",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Bones'])) { ?> checked="checked" <?php } ?>>
										<i></i>Osteoporosis</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_bones_identifier[]" value="Fractured" <?php if(in_array("Fractured",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Bones'])) { ?> checked="checked" <?php } ?>>
										<i></i>Fractured</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_bones_identifier[]" value="Gout" <?php if(in_array("Gout",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Bones'])) { ?> checked="checked" <?php } ?>>
										<i></i>Gout</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_bones_identifier[]" value="Bone Tumours" <?php if(in_array("Bone Tumours",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Bones'])) { ?> checked="checked" <?php } ?>>
										<i></i>Bone Tumours</label>
									</div>
								</div>
							</div>
				<!-- END BONES PROBLEMS  -------------------------------------------------------->

				<!-- SKIN PROBLEMS  -------------------------------------------------------->

							<div class="tab-pane fade" id="chronic_skin_chronic">
								<div class="row">
									<div class="col col-md-3">
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Acne" <?php if(in_array("Acne",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Skin'])) { ?> checked="checked" <?php } ?>>
									<i></i>Acne</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Eczema" <?php if(in_array("Eczema",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Skin'])) { ?> checked="checked" <?php } ?>>
									<i></i>Eczema</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Psoriasis" <?php if(in_array("Psoriasis",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Skin'])) { ?> checked="checked" <?php } ?>>
									<i></i>Psoriasis</label>
								</div>
								<div class="col col-md-3">
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Vitiligo" <?php if(in_array("Vitiligo",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Skin'])) { ?> checked="checked" <?php } ?>>
									<i></i>Vitiligo</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Measles" <?php if(in_array("Measles",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Skin'])) { ?> checked="checked" <?php } ?>>
									<i></i>Measles</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Scabies" <?php if(in_array("Scabies",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Skin'])) { ?> checked="checked" <?php } ?>>
									<i></i>Scabies</label>
								</div>
								<div class="col col-md-3">
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Chicken Pox" <?php if(in_array("Chicken Pox",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Skin'])) { ?> checked="checked" <?php } ?>>
									<i></i>Chicken Pox</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Warts" <?php if(in_array("Warts",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Skin'])) { ?> checked="checked" <?php } ?>>
									<i></i>Warts</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Cancers" <?php if(in_array("Cancers",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Skin'])) { ?> checked="checked" <?php } ?>>
									<i></i>Cancers</label>
								</div>
								<div class="col col-md-1">
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Others" <?php if(in_array("Others",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Skin'])) { ?> checked="checked" <?php } ?>>
									<i></i>Others</label>
								</div>
							</div>
							</div>
				<!-- END SKIN PROBLEMS  -------------------------------------------------------->

			
							<div class="tab-pane fade" id="chronic_endo_chronic">
								<label class="checkbox">
							  <input type="checkbox" name="chronic_endo_identifier[]" value="Diabetes Milletus Type 1" <?php if(in_array("Diabetes Milletus Type 1",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Endo'])) { ?> checked="checked" <?php } ?>>
								<i></i>Diabetes Milletus Type 1</label>
								<label class="checkbox">
								<input type="checkbox" name="chronic_endo_identifier[]" value="Hypothyroidism" <?php if(in_array("Hypothyroidism",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Endo'])) { ?> checked="checked" <?php } ?>>
								<i></i>Hypothyroidism</label>
								<label class="checkbox">
								<input type="checkbox" name="chronic_endo_identifier[]" value="Others" <?php if(in_array("Others",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Endo'])) { ?> checked="checked" <?php } ?>>
								<i></i>Others</label>
							</div>

				
				<!--OTHER CHRONIC PROBLEMS  -------------------------------------------------------->
							<div class="tab-pane fade" id="others_chronic">
								<div class="row">
									<div class="col col-3">
										<label class="checkbox">
									  <input type="checkbox" name="chronic_others_identifier[]" value="HIV" <?php if(in_array("HIV",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Others'])) { ?> checked="checked" <?php } ?>>
										<i></i>HIV</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="TB" <?php if(in_array("TB",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Others'])) { ?> checked="checked" <?php } ?>>
										<i></i>TB</label>
									
									<label class="checkbox">
									<input type="checkbox" name="chronic_others_identifier[]" value="XDR" <?php if(in_array("XDR",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Others'])) { ?> checked="checked" <?php } ?>>
									<i></i>XDR</label>
									</div>
									<div class="col col-3">
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="MDR" <?php if(in_array("MDR",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Others'])) { ?> checked="checked" <?php } ?>>
										<i></i>MDR</label>
										
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="Leprosy" <?php if(in_array("Leprosy",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Others'])) { ?> checked="checked" <?php } ?>>
										<i></i>Leprosy</label>
									</div>
									<div class="col col-3">
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="Polio" <?php if(in_array("Polio",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Others'])) { ?> checked="checked" <?php } ?>>
										<i></i>Polio</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="Dysentry" <?php if(in_array("Dysentry",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Others'])) { ?> checked="checked" <?php } ?>>
										<i></i>Dysentry</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="Malaria" <?php if(in_array("Malaria",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Others'])) { ?> checked="checked" <?php } ?>>
										<i></i>Malaria</label>
									</div>
									<div class="col col-3">
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="Typhiod" <?php if(in_array("Typhiod",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Others'])) { ?> checked="checked" <?php } ?>>
										<i></i>Typhiod</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="Cholera" <?php if(in_array("Cholera",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Others'])) { ?> checked="checked" <?php } ?>>
										<i></i>Cholera</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="Any Abscess" <?php if(in_array("Any Abscess",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic']['Others'])) { ?> checked="checked" <?php } ?>>
										<i></i>Any Abscess</label>
									</div>
								</div>
					
							</div>

				<!--END OTHER CHRONIC PROBLEMS  -------------------------------------------------------->
							</div>
							</div>
						</div>
						<!-- Chornic End-->

		</fieldset>
			<BR>
			<fieldset>
				<legend><h3 class="text-primary">Problem Info - Description</h3></legend>
				<section>
					<textarea rows="4" cols="100" name="page2_ProblemInfo_Description" id="page2_ProblemInfo_Description"  placeholder="Describe problem details..." data-gramm="true" data-txt_gramm_id="150f3472-434b-b806-53bd-2473d7b9a45e" data-gramm_id="150f3472-434b-b806-53bd-2473d7b9a45e" spellcheck="false" data-gramm_editor="true" >
						<?php echo set_value('page2_ProblemInfo_Description', (isset($doc['doc_data']['widget_data']['page2']['Problem Info']['Description'])) ? htmlspecialchars_decode($doc['doc_data']['widget_data']['page2']['Problem Info']['Description']) : ''); ?>
					</textarea>
					<!-- <textarea class='text_area' id='page2_ProblemInfo_Description'  name='page2_ProblemInfo_Description'>
                 
                  </textarea> -->
				</section>
			</fieldset>
			<fieldset>
			<legend><h3 class="text-primary">Diagnosis Summary</h3></legend>
			<section>
				<textarea class='text_area' rows="5" id="page2_DiagnosisInfo_DoctorSummary" name="page2_DiagnosisInfo_DoctorSummary" readonly="readonly"> <?php echo set_value('page2_DiagnosisInfo_DoctorSummary', (isset($doc['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Summary'])) ? htmlspecialchars_decode($doc['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Summary']) : ''); ?></textarea>
			</section>
				<label class="label">Doctor Advice</label>
				<section>
					<label class="select">
					<select name="page2_DiagnosisInfo_DoctorAdvice" id="page2_DiagnosisInfo_DoctorAdvice" readonly="readonly">
							<option value=''>
		                    <?php echo lang('web_choose_option')?>
		                    </option>
		                    <option value='Prescription' <?php echo  preset_select('page2_DiagnosisInfo_DoctorAdvice', 'Prescription', (isset($doc['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice'])) ? $doc['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice'] : ''  ) ?>>
                    Prescription
                    </option>
                    <option value='Advice' <?php echo  preset_select('page2_DiagnosisInfo_DoctorAdvice', 'Advice', (isset($doc['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice'])) ? $doc['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice'] : ''  ) ?>>
                    Advice
                    </option>
                    <option value='Refer 2 Hospital' <?php echo  preset_select('page2_DiagnosisInfo_DoctorAdvice', 'Refer 2 Hospital', (isset($doc['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice'])) ? $doc['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice'] : ''  ) ?>>
                    Refer 2 Hospital
                    </option>
					</select> <i></i> </label>
					</section>
					<section>
				<legend><h3 class="text-primary">Prescription</h3></legend>
				<textarea class='text_area'  rows="5" id="page2_DiagnosisInfo_Prescription" name="page2_DiagnosisInfo_Prescription" readonly="readonly"><?php echo set_value('page2_DiagnosisInfo_Prescription', (isset($doc['doc_data']['widget_data']['page2']['Diagnosis Info']['Prescription'])) ? htmlspecialchars_decode($doc['doc_data']['widget_data']['page2']['Diagnosis Info']['Prescription']) : ''); ?></textarea>
			</section>
				</fieldset>
			<fieldset>

				<legend><h3 class="text-primary">Review Info</h3></legend>
				<section>
					<label class="label">Request Type</label>
					<label class="select">
						<select name="page2_ReviewInfo_RequestType" id="page2_ReviewInfo_RequestType">
							
							<option value=''>
                    <?php echo lang('web_choose_option')?>
                    </option>
                    
	
                    <option value='Normal' <?php echo  preset_select('page2_ReviewInfo_RequestType', 'Normal', (isset($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'])) ? $doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] : ''  ) ?>>
                    Normal
                    </option>
                    <option value='Emergency' <?php echo  preset_select('page2_ReviewInfo_RequestType', 'Emergency', (isset($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'])) ? $doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] : ''  ) ?>>
                    Emergency
                    </option> 
                    <option value='Chronic' <?php echo  preset_select('page2_ReviewInfo_RequestType', 'Chronic', (isset($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'])) ? $doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] : ''  ) ?>>
                    Chronic
                    </option>
                    <option value='Deficiency' <?php echo  preset_select('page2_ReviewInfo_RequestType', 'Deficiency', (isset($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'])) ? $doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] : ''  ) ?>>
                    Deficiency
                    </option>
                    <option value='Defects' <?php echo  preset_select('page2_ReviewInfo_RequestType', 'Defects', (isset($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'])) ? $doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] : ''  ) ?>>
                    Defects
                    </option>

						</select> <i></i> </label>
					</section>

					<section>
						<label class="label">Status</label>
						<label class="select">
							<select name="page2_ReviewInfo_Status">
								<option value=''>
                    <?php echo lang('web_choose_option')?>
                    </option>
                    
	
                    <option value='Initiated' <?php echo  preset_select('page2_ReviewInfo_Status', 'Initiated', (isset($doc['doc_data']['widget_data']['page2']['Review Info']['Status'])) ? $doc['doc_data']['widget_data']['page2']['Review Info']['Status'] : ''  ) ?>>
                    Initiated
                    </option>
                    <option value='Prescribed' <?php echo  preset_select('page2_ReviewInfo_Status', 'Prescribed', (isset($doc['doc_data']['widget_data']['page2']['Review Info']['Status'])) ? $doc['doc_data']['widget_data']['page2']['Review Info']['Status'] : ''  ) ?>>
                    Prescribed
                    </option>
                    <option value='Follow-up' <?php echo  preset_select('page2_ReviewInfo_Status', 'Follow-up', (isset($doc['doc_data']['widget_data']['page2']['Review Info']['Status'])) ? $doc['doc_data']['widget_data']['page2']['Review Info']['Status'] : ''  ) ?>>
                    Follow-up
                    </option>
                    <option value='Under Medication' <?php echo  preset_select('page2_ReviewInfo_Status', 'Under Medication', (isset($doc['doc_data']['widget_data']['page2']['Review Info']['Status'])) ? $doc['doc_data']['widget_data']['page2']['Review Info']['Status'] : ''  ) ?>>
                    Under Medication
                    </option>
                    <option value='Hospitalized' <?php echo  preset_select('page2_ReviewInfo_Status', 'Hospitalized', (isset($doc['doc_data']['widget_data']['page2']['Review Info']['Status'])) ? $doc['doc_data']['widget_data']['page2']['Review Info']['Status'] : ''  ) ?>>
                    Hospitalized
                    </option>
								
							</select> <i></i> </label>
						</section>
					</fieldset>
					<?php if(isset($doc['doc_data']['external_attachments'])): ?>
                <div class='external_file_attachments'>
                <?php foreach($doc['doc_data']['external_attachments'] as $data): ?>	
                <?php $pathvar = str_replace('/','=',$data['file_path']); ?>
                <a target='_blank' href="<?php echo URL;?>healthcare/healthcare2016531124515424_con/web_file_download/<?php echo $pathvar;?>" name="<?php echo $data['file_client_name']?>"></a>
                <?php endforeach ?>
                </div>
				<br><br>
				<div class="external_files_show"><div class="panel panel-darken"><div class="panel-heading"><h3 class="panel-title external_font">Externally attached files</h3></div><div class="panel-body no-padding attachment_files"><table class="table table-bordered"><tbody class="files_attached"></tbody></table></div></div></div>
                <?php endif ?>
					<!-- <input type="text" name="doc_id" id="doc_id" class="hide" value='<?php //echo $doc['doc_properties']['doc_id'];?>'> -->
					<input type="text" class="hide" id='doc_id' rel='doc_id' name='doc_id' value="<?php echo set_value('doc_id',(isset($doc['doc_properties']['doc_id']) && !empty($doc['doc_properties']['doc_id']))) ?  $doc['doc_properties']['doc_id'] :  "" ;?>">
								<br>
							<!--<div class="well bg-color-blue pull-left hs_attachments"><h5 class="" id="click_upload"><center><i class="fa fa-paperclip"></i> Click here to attach files</center></h5></div><input type='file' id='hs_req_attachments' name='hs_req_attachments[]' class="hide hs_req_attachments" value="" multiple="multiple"/>
							<div class="file_attach_count note pull-left"></div>-->
							<fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading  text-center"><strong>Attachments</strong></div>
                            <div class="form-group ">
                              
                         
                          <input type="file" id="files"  name="hs_req_attachments[]" style="display:none;" multiple>
                            <label for="files" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                               Browse.....
                           </label>
                          </div>
                   
                          </div>
                        
                        </fieldset>
								    <footer>
									<div class="row">
                                  <div class="col-md-7">
                                    
                                    <button class="btn btn-success col-md-3 submit" type="submit">
									  <i class="fa fa-save"></i>
									  SUBMIT
									</button>
                                  </div>
                                </div>
								</footer>
								<?php endforeach;?>
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
<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>
<!-- PAGE RELATED PLUGIN(S) 
	<script src="..."></script>-->
	<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->

	<script type="text/javascript">
		$(document).ready(function() {

	$("a[rel^='prettyPhoto']").prettyPhoto();

			var normal = $("#normal").val();
			var emergency = $("#emergency").val();
			var chronic = $("#chronic").val();
			if(typeof normal != "undefined")
			{
				$('.general_related').show();
			}
			if(typeof emergency != "undefined")
			{
				$('.emergency_related').show();
			}
			if(typeof chronic != "undefined")
			{
				$('.chronic_related').show();
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
					/*$('<tr style="height:55px;"><td class="ind_val"><p></p></td><td class="word_break"><a target="_blank" class="external_font" href="'+href_+'">'+name_+'</a></td></tr>').appendTo('.files_attached');*/
					$("<span style=\"height:55px;\"><b class=\"ind_val\"></b><b class=\"word_break\"><a href="+href_+" rel='prettyPhoto[gal]'>&nbsp;<img class=\"img-thumbnail\" src=\"" + href_ + "\" style =\"width:150px;height:150px;\" title=\"" + name_ + "\"/>&nbsp;</a></b></span>").appendTo('.files_attached');
					/*$("<span   class=\"pip\">" +  
	            "<a><img class=\"img-thumbnail\" src=\"" + href_ + "\" style =\"width:150px;height:150px;\" title=\"" + name_ + "\"/></a>"  +
	            "<br/><span class=\"remove\">Remove image</span>" +
	            "</span>").appendTo(".files_attached");*/
					}
					else
					{
					/*$('<span class="active" style="height:55px;"><b class="ind_val"><p></p></b><b class="word_break"><a target="_blank" class="external_font" href="'+href_+'">'+name_+'</a></b></span>').appendTo('.files_attached');*/

					$("<span class=\"active\" style=\"height:55px;\"><b class=\"ind_val\"></b><b class=\"word_break\"><a href="+href_+" rel='prettyPhoto[gal]'>&nbsp;<img class=\"img-thumbnail\" src=\"" + href_ + "\" style =\"width:150px;height:150px;\" title=\"" + name_ + "\"/> &nbsp;</a></b></span>").appendTo('.files_attached');

					/*$("<span   class=\"pip\">" +  
	            "<a><img class=\"img-thumbnail\" src=\"" + href_ + "\" style =\"width:150px;height:150px;\" title=\"" + name_ + "\"/></a>"  +
	            "<br/><span class=\"remove\">Remove image</span>" +
	            "</span>").appendTo(".files_attached");	*/
					}
				})
				$("a[rel^='prettyPhoto']").prettyPhoto();
				if($('.files_attached').children('span').length==0)
				{
					$('<span class="" style="height:55px;"><b class=""><h1><b>No external files attached<b></h1></b></span>').appendTo('.files_attached');	
				}

			/*	//uploading the logo in app creation //
		$(document).on('click','.hs_attachments',function()
			{
				$('.hs_req_attachments').trigger("click");
		});	
		
		$("input:file").change(function(ev) {
            var numFiles = $("input:file")[0].files.length;
			var count    = numFiles+' files attached';
			$('.file_attach_count').text(count);
				
		});
		var numFiles = $("input:file")[0].files.length;
		    for(var j=0;j<numFiles;j++)
		    {
		     var size = $("input:file")[0].files[j].size;
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
		    }*/

		     if (window.File && window.FileList && window.FileReader) 
			{
				
		//var numFiles = $("input:file")[0].files.length;
		 $("#files").on("change", function(e) {
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
	            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" style=\"width:150px; height:150px;\" title=\"" + file.name + "\"/>" +
	            "<br/><span class=\"remove\">Remove image</span>" +
	            "</span>").insertAfter("#files");
		          $(".remove").on('click',function(){
		            $(this).parent(".pip").remove();
		          });
		        });
        		 fileReader.readAsDataURL(f);

		     var size = $("input:file")[0].files[j].size;
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
		     $("input:file").html("#files");
		 	
		 	//var files = $(".imageThumb").array(); 
		});

		}
		});
	</script>
	<?php 
//include footer
	include("inc/footer.php"); 
	?>
