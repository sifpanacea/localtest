

<?php

//initilize the page
//require once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page title = "Custom Title" */

$page_title = "Initiate Request";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page css array.
//Note: all css files are inside css/ folder
$page_css[] = "your style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["initiate_req"]["active"] = true;
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
/*.file_attach_count 
{
	font-family: Segoe UI;
	font-size: 35px;
	color: green;
}*/
  input[type="file"] {
    display: block;
  }
  .imageThumb {
    max-height: 75px;
    border: 2px solid;
    padding: 1px;
    cursor: pointer;
  }
  .pip {
    display: inline-block;
    margin: 10px 10px 50px 90px;
  }

   #student_photo
{
  width: 148px;
    height: 130px;
    border: 3px solid;
    border-color: green;
}

#dist_school_code
{
	width:122px;	
	margin: 11px;
	font-size: x-large;
}
#page1_StudentDetails_HospitalUniqueID
{
	width: 80px;
	height: 25px;
    margin: 11px;
    border-color: white;
    border-style: solid;
    border-color: #333;
}
#searchIdBtn{
    margin:8px;
    padding: 5px;
}
.invalid{
	color:red;
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

<header>
	<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
	<h2>STUDENT INFORMATION</h2>

</header>

<!-- widget div-->
<div>

	
	<!-- end widget edit box -->

	<!-- widget content -->
	<div class="widget-body no-padding">

		<!-- <h3 class="text-primary" style="text-align: -webkit-center; margin-top:10px;">Student Info</h3> -->

		<?php
		$attributes = array('class' => 'smart-form','id'=>'web_view','name'=>'userform');
		echo  form_open_multipart('tmreis_schools/initiate_hs_request',$attributes);
		?>
		<fieldset><section class="col-lg-10">
			<div class="row">
				<!-- <section class="col col-md-7"> -->
					<label class="col-md-2" style="margin:15px; font-size: large;"><b>UNIQUE ID</b></label>
					<label class="input col-md-5 labelform unique_id" id="dist_school_code" ><?php echo$district_code."_".$school_code."_";?></label>
						<input type="number" class="col-md-2 student_code" id="page1_StudentDetails_HospitalUniqueID" name="page1_StudentDetails_HospitalUniqueID" required>
					
					
				
			<button type="button" class="btn btn-primary  col-md-offset-3 col-md-3 retriever_search" id="searchIdBtn"  field_ref='page1_Personal_Information_Hospital_Unique_ID'><i class="fa fa-search"> SEARCH</i></button>

			</div>
			
			<br>
			<div class="row">
				<section class="col col-md-3">
					<label class="label"><b>NAME</b></label>
					<label class="input"> 
						<input type="text" id='page1_StudentInfo_Name' rel='page1_Personal_Information_Name' name='page1_StudentInfo_Name'  minlength='1' maxlength='123' readonly>
					</label>
				</section>

				<section class="col col-md-3">
					<label class="label"><b>CLASS</b></label>
					<label class="input"> 
						<input type="text" id='page1_StudentInfo_Class' type='number' rel='page2 Personal Information Class' name='page1_StudentInfo_Class' minlength='1' maxlength='123' readonly>

					</label>
				</section>

				<section class="col col-md-5">
					<label class="label"><b>SECTION</b></label>
					<label class="input">
						<input type="text" id='page1_StudentInfo_Section' rel='page2_Personal_Information_Section' type='text' name='page1_StudentInfo_Section'  minlength='1' maxlength='123' readonly>
					</label>
				</section>
			</div>
			<div class="row"> 
				<section class="col col-md-6">
					<label class="label"><b>DISTRICT</b></label>
					<label class="input"> 
						<input type="text" id='page1_StudentInfo_District' rel='page2_Personal_Information_District' type='text' name='page1_StudentInfo_District'  minlength='1' maxlength='123'readonly>

					</label>
				</section>
				<section class="col col-md-5">
					<label class="label"><b>SCHOOL NAME</b></label>
					<label class="input"> 
						<input type="text" id='page1_StudentInfo_SchoolName' rel='page2_Personal_Information_School_Name' type='text' name='page1_StudentInfo_SchoolName'  minlength='1' maxlength='123' readonly>

					</label>
				</section>
			</div>
			</section>
			<section class="col-lg-2">
					
						<div id="student_image"> 
							</div>
					<div id="image_logo"></div>
					</section>

		</fieldset>

<fieldset>
	<h3 style="color: red;">Select Request Type and Identifiers</h3>
	<br> 
	<div class="row">
		<div class="col col-md-4">
			<label class="radio radio-inline">	<input type="radio"  class="radiobox" id="normal" name="test1" value="Normal"><span><strong>NORMAL</strong></span>
			</label>
		</div>
		<div class="col col-md-4">
			<label class="radio radio-inline">			
				<input type="radio"  class="radiobox" name="test1" id="emergency" value="Emergency"><span><strong>EMERGENCY</strong></span>
			</label>
		</div>
		<div class="col col-md-4">
			<label class="radio radio-inline">			
				<input type="radio"  class="radiobox" name="test1" id="chronic" value="Chronic"><span><strong>CHRONIC</strong></span>
			</label>
		</div>
	</div>

	<div class="general_related">
		<div class="widget-body">
			<hr class="simple">
			<ul id="myTab1" class="nav nav-tabs bordered">
				<li class="active">
					<a href="#general" data-toggle="tab"> GENERAL</a>
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

			<div id="myTabContent normal" class="tab-content padding-10">
				<div class="row tab-pane fade in active" id="general">
					<div class="col col-md-4">
						<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Fever">
							<i></i>Fever</label>
						<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Chills">
							<i></i>Chills</label>

					</div>
					<div class="col col-md-4">
						<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Lots Of Appetite">
							<i></i>Lots Of Appetite</label>
						<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Weight Loss">
							<i></i>Weight Loss</label>
					</div>

					<div class="col col-md-4">
						<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Night Sweats">
							<i></i>Night Sweats</label>
						<label class="checkbox">
					<input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Fever With Rash">
							<i></i>Fever With Rash</label>
					</div>
				</div>
				<div class="tab-pane fade" id="head_gn">
					<div class="row">
						<div class="col col-md-4">
							<label class="checkbox">
								<input type="checkbox" id="normal_identifier" name="normal_head_identifier[]" value="Headache">
							<i></i>Headache</label>
							<label class="checkbox">
								<input type="checkbox" id="normal_identifier" name="normal_head_identifier[]" value="Dizziness">
							<i></i>Dizziness</label>
						</div>
						<div class="col col-md-6">
							<label class="checkbox">
								<input type="checkbox" id="normal_identifier" name="normal_head_identifier[]" value="Head Swelling">
							<i></i>Head Swelling</label>
							<label class="checkbox">
								<input type="checkbox" id="normal_identifier" name="normal_head_identifier[]" value="Seizures">
							<i></i>Seizures</label>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="eyes">
					<div class="row">
						<div class="col col-md-4">
							<label class="checkbox">
					<input type="checkbox" id="normal_identifier" name="normal_eyes_identifier[]" value="Eye Pain">
							<i></i>Eye Pain</label>
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_eyes_identifier[]" value="Eye Discharge">
							<i></i>Eye Discharge</label>
						</div>
						<div class="col col-md-6">
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_eyes_identifier[]" value="Blurring Of Vission">
							<i></i>Blurring Of Vission</label>
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_eyes_identifier[]" value="Double Vision">
							<i></i>Double Vision</label>
						</div>
					</div>
			</div>

<!-- ent related information -->

				<div class="tab-pane fade" id="ent">
					<div class="row">
						<div class="col col-md-3">
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Ear Pain">
							<i></i>Ear Pain</label>
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Ear Discharge">
							<i></i>Ear Discharge</label>
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Watering Of Eyes">
							<i></i>Watering Of Eyes</label>
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Vertigo">
							<i></i>Vertigo</label>
						</div>
						<div class="col col-md-3">
							
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Tinnitus">
							<i></i>Tinnitus</label>
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Nose Bleeding">
							<i></i>Nose Bleeding</label>
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Nose Discharge">
							<i></i>Nose Discharge</label>
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Horness Of Voice">
							<i></i>Horness Of Voice</label>
						</div>
						<div class="col col-md-3">
							
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Throat Pain">
							<i></i>Throat Pain</label>
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Bad Smell">
							<i></i>Bad Smell</label>
							
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Neck Swelling">
							<i></i>Neck Swelling</label>
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Pain On Swallowing(Dysphagia)">
							<i></i>Pain On Swallowing(Dysphagia)</label>
						</div>
						<div class="col col-md-3">
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Cracked Angles Of Mouth">
							<i></i>Cracked Angles Of Mouth</label>
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Ulcerated Lip">
							<i></i>Ulcerated Lip</label>
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Bleeding Gums">
							<i></i>Bleeding Gums</label>
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Swollen Gums">
							<i></i>Swollen Gums</label>
							<label class="checkbox">
							<input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Difficulty In Swallowing(Odynophagia)">
							<i></i>Difficulty In Swallowing(Odynophagia)</label>
						</div>
					</div>

				</div>
<!--rs related information -->
				<div class="row tab-pane fade" id="rs">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Cough">
						<i></i>Cough</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Shortness Of Breath">
						<i></i>Shortness Of Breath</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Spectrum From Mouth">
						<i></i>Spectrum From Mouth</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Blood From Mouth">
						<i></i>Blood From Mouth</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Wheezing">
						<i></i>Wheezing</label>
					</div>
				</div>
<!--cvs related information-->
				<div class="row tab-pane fade" id="cvs">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_cvs_identifier[]" value="Chest Pain">
						<i></i>Chest Pain</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_cvs_identifier[]" value="Edoma Of Feet">
						<i></i>Edoma Of Feet</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_cvs_identifier[]" value="Cyanosis">
						<i></i>Cyanosis</label>
					</div>
				</div>
<!--GI related information -->
				<div class="row tab-pane fade" id="gi">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Dyspharia(Difficulty In Eating)">
						<i></i>Dyspharia(Difficulty In Eating)</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Nausea">
						<i></i>Nausea</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Vomtings">
						<i></i>Vomtings</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Blood In Vomiting(Haematemesis)">
						<i></i>Blood In Vomiting(Haematemesis)</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Diarrhoea">
						<i></i>Diarrhoea</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Constipation">
						<i></i>Constipation</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Melena(Block Stools)">
						<i></i>Melena(Block Stools)</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Blood Per Rectum">
						<i></i>Blood Per Rectum</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Perianel Pain">
						<i></i>Perianel Pain</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Incontinence Of Stools">
						<i></i>Incontinence Of Stools</label>						
					</div>
				</div>
<!--GU related information -->
				<div class="row tab-pane fade" id="gu">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Pain On Micturation">
						<i></i>Pain On Micturation</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Increase Micturation">
						<i></i>Increase Micturation</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Burning Micturation">
						<i></i>Burning Micturation</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Dribbling Of Urine">
						<i></i>Dribbling Of Urine</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Unable To Pass Urine">
						<i></i>Unable To Pass Urine</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Blood In Urine">
						<i></i>Blood In Urine</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Flank Pain">
						<i></i>Flank Pain</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Testis Swelling">
						<i></i>Testis Swelling</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Suprapuvic Pain">
						<i></i>Suprapuvic Pain</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Incontinence Of Urine">
						<i></i>Incontinence Of Urine</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Urine Retention">
						<i></i>Urine Retention</label>
						<label class="checkbox">
						<input type="checkbox"  id="normal_identifier" name="normal_gu_identifier[]" value="Testis Pain">
						<i></i>Testis Pain</label>
						<label class="checkbox">
						<input type="checkbox"  id="normal_identifier" name="normal_gu_identifier[]" value="Bed Wetting">
						<i></i>Bed Wetting</label>
					</div>
				</div>									
<!--gyn related information -->
				<div class="row tab-pane fade" id="gyn">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gyn_identifier[]" value="Vaginal Bleeding">
						<i></i>Vaginal Bleeding</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gyn_identifier[]" value="Absent Of Periods">
						<i></i>Absent Of Periods</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gyn_identifier[]" value="Painful Periods">
						<i></i>Painful Periods</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gyn_identifier[]" value="Breast Lump">
						<i></i>Breast Lump</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_gyn_identifier[]" value="Nipple Discharge">
						<i></i>Nipple Discharge</label>
					</div>
				</div>
<!--ENDO CRINOLOGY related information -->
				<div class="row tab-pane fade" id="endo_cri">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_cri_identifier[]" value="Poly Uria">
						<i></i>Poly Uria</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_cri_identifier[]" value="Polyphagia">
						<i></i>Polyphagia</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_cri_identifier[]" value="Heat Intolerance">
						<i></i>Heat Intolerance</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_cri_identifier[]" value="Cold Intolerence">
						<i></i>Cold Intolerence</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_cri_identifier[]" value="Fatique">
						<i></i>Fatique</label>
					</div>
				</div>

<!--msk related information-->
				<div class="tab-pane fade" id="msk">
					<div class="row">
					<div class="col col-md-3">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Neck Pain">
						<i></i>Neck Pain</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Back Pain">
						<i></i>Back Pain</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Shoulder Pain">
						<i></i>Shoulder Pain</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Elbow Pain">
						<i></i>Elbow Pain</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Wrist Pain">
						<i></i>Wrist Pain</label>
					</div>
					<div class="col col-md-3">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Hip Pain">
						<i></i>Hip Pain</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Ancle Pain">
						<i></i>Ancle Pain</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Finger Pain">
						<i></i>Finger Pain</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Knee Pain">
						<i></i>Knee  Pain</label>
					</div>
					<div class="col col-md-3">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Toe Pain">
						<i></i>Toe Pain</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Muscle Pain">
						<i></i>Muscle Pain</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Lower Back Pain">
						<i></i>Lower Back Pain</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Body Pain">
						<i></i>Body Pain</label>
					</div>
					<div class="col col-md-3">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Numbness">
						<i></i>Numbness</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Tingling">
						<i></i>Tingling</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Morning Stiffness">
						<i></i>Morning Stiffness</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Night Pain">
						<i></i>Night Pain</label>
					</div>
					</div>
				</div>
<!--cns related information -->
				<div class="row tab-pane fade" id="cns">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_cns_identifier[]" value="Loss Of Consciousness">
						<i></i>Loss Of Consciousness</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_cns_identifier[]" value="Abnormal_Gait">
						<i></i>Abnormal_Gait</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_cns_identifier[]" value="Aphasia">
						<i></i>Aphasia</label>
					</div>
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_cns_identifier[]" value="Insufficient Sleep">
						<i></i>Insufficient Sleep</label>
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_cns_identifier[]" value="Abnormal_Sleep">
						<i></i>Abnormal_Sleep</label>
					</div>
				</div>
<!-- PSYCHIARTIC-->
				<div class="row tab-pane fade" id="psychiartic">
					<div class="col col-md-4">
						<label class="checkbox">
						<input type="checkbox" id="normal_identifier" name="normal_psychiartic_identifier[]" value="PSYCHIARTIC">
						<i></i>PSYCHIARTIC</label>
						
					</div>
				</div>
			</div>

				</div>

			</div>

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

					<!-- EYE PROBLEMS --------------------------------------->		
							<div id="myTabContent1" class="tab-content padding-10">	
							<div class="tab-pane fade in active" id="emergency_disease">
								<div class="row">
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Falls and Trauma">
										<i></i>Falls and Trauma</label>
										<label class="checkbox">
										<input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Epi Staxsis">
										<i></i>Epi Staxsis</label>
										<label class="checkbox">
										<input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Seizures">
										<i></i>Seizures</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Giddiness">
										<i></i>Giddiness</label>
										<label class="checkbox">
										<input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Chest Pain">
										<i></i>Chest Pain</label>
										<label class="checkbox">
										<input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Breathing Problem">
										<i></i>Breathing Problem</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Swelling">
										<i></i>Swelling</label>
										<label class="checkbox">
										<input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Suicides">
										<i></i>Suicides</label>
										<label class="checkbox">
										<input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Burns"><i></i>Burns</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Abdomen Pain">
										<i></i>Abdomen Pain</label>
										<label class="checkbox">
										<input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Others">
										<i></i>Others</label>
										
									</div>
								</div>
							</div>

				<!-- END EYE PROBLEMS --------------------------------------->	

				<!--  EAR PROBLEMS  ------------------------->			

							<div class="tab-pane fade" id="emergency_bites">
								<div class="row">
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" id="emergency_identifier" name="emergency_bites_identifier[]" value="Scorpion">
										<i></i>Scorpion</label>
										<label class="checkbox">
										<input type="checkbox" id="emergency_identifier" name="emergency_bites_identifier[]" value="Snake">
										<i></i>Snake</label>
										<label class="checkbox">
										<input type="checkbox"  id="emergency_identifier" name="emergency_bites_identifier[]" value="Honey Bee">
										<i></i>Honey Bee</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" id="emergency_identifier" name="emergency_bites_identifier[]" value="Monkey">
										<i></i>Monkey</label>
										<label class="checkbox">
										<input type="checkbox" id="emergency_identifier" name="emergency_bites_identifier[]" value="Dog">
										<i></i>Dog</label>
										
									</div>
									
								</div>
							</div>

				<!--  END EAR PROBLEMS  ------------------------->

				
								</div>
					
							</div>
						</div>
			<!--END EMERGENCY-->
			

		<!-- CHRONIC RELATED INFORMATION (ON CLICK RADIO) -->
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
							<div id="myTabContent_chronic" class="tab-content padding-10">	
							<div class="tab-pane fade in active" id="chronic_eyes">
								<div class="row">
									<div class="col col-md-4">
										<label class="checkbox">
					<input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Glacouma">
										<i></i>Glacouma</label>
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Proptosis">
										<i></i>Proptosis</label>
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Ptosis">
										<i></i>Ptosis</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Night Blindness">
										<i></i>Night Blindness</label>
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Refractive Errors">
										<i></i>Refractive Errors</label>
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Retinal Pathology">
										<i></i>Retinal Pathology</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Squints">
										<i></i>Squints</label>
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Cataract">
										<i></i>Cataract</label>
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Others">
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
										<input type="checkbox" id="chronic_identifier" name="chronic_ent_identifier[]" value="Csom">
										<i></i>Csom</label>
										<label class="checkbox">
										<input type="checkbox"  id="chronic_identifier" name="chronic_ent_identifier[]" value="Hearing Loss">
										<i></i>Hearingloss</label>
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_ent_identifier[]" value="Sinusitis">
										<i></i>Sinusitis</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_ent_identifier[]" value="Rhinitis">
										<i></i>Rhinitis</label>
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_ent_identifier[]" value="Dental Caries">
										<i></i>Dental Caries</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_ent_identifier[]" value="Penidontal Disease">
										<i></i>Acate Otitis Media</label>
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_ent_identifier[]" value="Acate Otitis Media">
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
										<input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Epilepy">
										<i></i>Epilepy</label>
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Mysthenia Gravis">
										<i></i>Mysthenia gravis</label>
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Migrane">
										<i></i>Migrane</label>
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Ocd">
										<i></i>Ocd</label>
										<label class="checkbox">
									<input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Personality Disorders">
										<i></i>Personality Disorders</label>
									</div>

									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Anxiesty">
										<i></i>Anxiesty</label>
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Depression Disorders">
										<i></i>Depression Disorders</label>
										
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Bipolar Disorders">
										<i></i>Bipolar Disorders</label>
										<input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Dementia">
										<i></i>Dementia</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Schizophrenia">
										<i></i>Schizophrenia</label>
										<label class="checkbox">
							<input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Neuro-Developmental Disorders">
										<i></i>Neuro-Developmental Disorders</label>
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Phobic Disorders">
										<i></i>Phobic Disorders</label>
										<label class="checkbox">
										<input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Others">
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
									<input type="checkbox" name="chronic_rs_identifier[]" value="Asthma">
									<i></i>Asthma</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_rs_identifier[]" value="Pneumonia">
									<i></i>Pneumonia</label>
								</div>
								<div class="col col-4">
									<label class="checkbox">
									<input type="checkbox" name="chronic_rs_identifier[]" value="Emphysema">
									<i></i>Emphysema</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_rs_identifier[]" value="Chronic Bronchitis">
									<i></i>Chronic Bronchitis</label>
								</div>
								<div class="col col-4">
									<label class="checkbox">
									<input type="checkbox" name="chronic_rs_identifier[]" value="Bronchiectasis">
									<i></i>Bronchiectasis</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_rs_identifier[]" value="Others">
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
										<input type="checkbox" name="chronic_cvs_identifier[]" value="Vsd">
										<i></i>Vsd</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_cvs_identifier[]" value="Rhd">
<!---doubt in cvs -->					<i></i>Rhd</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_cvs_identifier[]" value="Mr">
										<i></i>Mr</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_cvs_identifier[]" value="Asd">
										<i></i>Asd</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_cvs_identifier[]" value="Others">
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
										<input type="checkbox" name="chronic_gi_identifier[]" value="Acid Peptic Disease">
										<i></i>Acid Peptic Disease</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_gi_identifier[]" value="Appendicitis">
										<i></i>Appendicitis</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_gi_identifier[]" value="Jaundie">
										<i></i>Jaundie</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_gi_identifier[]" value="Ascites">
										<i></i>Ascites</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_gi_identifier[]" value="Others">
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
										<input type="checkbox" name="chronic_blood_identifier[]" value="Anaemia">
										<i></i>Anaemia</label>
										
										
										<label class="checkbox">
										<input type="checkbox" name="chronic_blood_identifier[]" value="Mild">
										<i></i>Mild</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_blood_identifier[]" value="Moderate">
										<i></i>Moderate</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_blood_identifier[]" value="Seveare">
										<i></i>Seveare</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_blood_identifier[]" value="Iron Deficency">
										<i></i>Iron Deficency</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_blood_identifier[]" value="B 12 Deficieny">
										<i></i>B 12 Deficieny</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_blood_identifier[]" value="A Plastic Anemia">
										<i></i>A Plastic Anemia</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_blood_identifier[]" value="Sickle Cell Anemia">
										<i></i>Sickle Cell Anemia</label>
										<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="Anaemia Of Chronic Disease">
										<i></i>Anaemia Of Chronic Disease</label>
									</div>
									<div class="col col-md-3">
									<p><strong>PLATELET DISORDER</strong></p>
									<br>
								
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="Vwb Disorder">
									<i></i>Vwb Disorder</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="Hemophibia">
									<i></i>Hemophibia</label>
									</div>
									<div class="col col-md-3">
								<p><strong>BLOOD CANCER</strong></p>
									<br>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="Lymphoma">
									<i></i>Lymphoma</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="Polycythemia Vera">
									<i></i>Polycythemia Vera</label>
									</div>
									<div class="col col-md-3">
									
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="Leukaemia">
									<i></i>Leukaemia</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="CLL">
									<i></i>CLL</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="ALL">
									<i></i>ALL</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="AML">
									<i></i>AML</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="CML">
									<i></i>CML</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_blood_identifier[]" value="Others">
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
										<input type="checkbox" name="chronic_kidney_identifier[]" value="CKD(Chronic Kidney Disease)">
										<i></i>CKD(Chronic Kidney Disease)</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_kidney_identifier[]" value="ARF(Acute Renal Failure)">
										<i></i>ARF(Acute Renal Failure)</label>
										</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_kidney_identifier[]" value="Renal Stones">
										<i></i>Renal Stones</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_kidney_identifier[]" value="Nephrotic Syndrome">
										<i></i>Nephrotic Syndrome</label>
									</div>
									<div class="col col-md-4">
											<label class="checkbox">
										<input type="checkbox" name="chronic_kidney_identifier[]" value="Polycystic Kidney Disease">
											<i></i>Polycystic Kidney Disease</label>
										<label class="checkbox">
									<input type="checkbox" name="chronic_kidney_identifier[]" value="Urinary Tract Infections">
										<i></i>Urinary Tract Infections</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_kidney_identifier[]" value="Others">
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
									<input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamins D">
									<i></i>Vitamin D</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamin B2">
									<i></i>Vitamin B2</label>
								</div>

								<div class="col col-md-4">
									<label class="checkbox">
									<input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamin A">
									<i></i>Vitamin A</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamin C">
									<i></i>Vitamin C</label>
									<label class="checkbox">
								    <input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamin B Complex">
									<i></i>vitamin B Complex</label>
								</div>
							</div>
								
							</div>
				<!--END VITAMINS & MINERALS DEFECIENCY PROBLEMS -------------------------------------------->

				<!--  BONES PROBLEMS  -------------------------------------------------------->
							<div class="tab-pane fade" id="chronic_bones_chronic">
								<div class="row">
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_bones_identifier[]" value="Osteoporosis">
										<i></i>Osteoporosis</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_bones_identifier[]" value="Fractured">
										<i></i>Fractured</label>
									</div>
									<div class="col col-md-4">
										<label class="checkbox">
										<input type="checkbox" name="chronic_bones_identifier[]" value="Gout">
										<i></i>Gout</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_bones_identifier[]" value="Bone Tumours">
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
									<input type="checkbox" name="chronic_skin_identifier[]" value="Acne">
									<i></i>Acne</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Eczema">
									<i></i>Eczema</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Psoriasis">
									<i></i>Psoriasis</label>
								</div>
								<div class="col col-md-3">
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Vitiligo">
									<i></i>Vitiligo</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Measles">
									<i></i>Measles</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Scabies">
									<i></i>Scabies</label>
								</div>
								<div class="col col-md-3">
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Chicken Pox">
									<i></i>Chicken Pox</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Warts">
									<i></i>Warts</label>
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Cancers">
									<i></i>Cancers</label>
								</div>
								<div class="col col-md-1">
									<label class="checkbox">
									<input type="checkbox" name="chronic_skin_identifier[]" value="Others">
									<i></i>Others</label>
								</div>
							</div>
							</div>
				<!-- END SKIN PROBLEMS  -------------------------------------------------------->

			
							<div class="tab-pane fade" id="chronic_endo_chronic">
								<label class="checkbox">
							  <input type="checkbox" name="chronic_endo_identifier[]" value="Diabetes Milletus Type 1">
								<i></i>Diabetes Milletus Type 1</label>
								<label class="checkbox">
								<input type="checkbox" name="chronic_endo_identifier[]" value="Hypothyroidism">
								<i></i>Hypothyroidism</label>
								<label class="checkbox">
								<input type="checkbox" name="chronic_endo_identifier[]" value="Others">
								<i></i>Others</label>
							</div>

				
				<!--OTHER CHRONIC PROBLEMS  -------------------------------------------------------->
							<div class="tab-pane fade" id="others_chronic">
								<div class="row">
									<div class="col col-3">
										<label class="checkbox">
									  <input type="checkbox" name="chronic_others_identifier[]" value="HIV">
										<i></i>HIV</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="TB">
										<i></i>TB</label>
									
									<label class="checkbox">
									<input type="checkbox" name="chronic_others_identifier[]" value="XDR">
									<i></i>XDR</label>
									</div>
									<div class="col col-3">
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="MDR">
										<i></i>MDR</label>
										
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="Leprosy">
										<i></i>LEPROSY</label>
									</div>
									<div class="col col-3">
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="Polio">
										<i></i>Polio</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="Dysentry">
										<i></i>Dysentry</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="Malaria">
										<i></i>Malaria</label>
									</div>
									<div class="col col-3">
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="Typhiod">
										<i></i>Typhiod</label>
										<label class="checkbox">
										<input type="checkbox" name="chronic_others_identifier[]" value="Cholera">
										<i></i>Cholera</label>
										<label class="checkbox">
										<input type="checkbox"  name="chronic_others_identifier[]" value="Any Abscess">
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
				<legend><h3 class="text-primary">PROBLEM INFO - DESCRIPTION</h3></legend>
				<section>
					<label class="textarea">
						<textarea rows="8" name="page2_ProblemInfo_Description" id="page2_ProblemInfo_Description" class="custom-scroll" placeholder="Describe problem details..."></textarea> 
					</label>

				</section>
			</fieldset>

			<fieldset>

				<legend><h3 class="text-primary">REVIEW INFO</h3></legend>
				<section>
					<label class="label">REQUEST TYPE</label>
					<label class="select">
						<select name="page2_ReviewInfo_RequestType" id="page2_ReviewInfo_RequestType">
							<option value="0">Choose an option</option>
							<option value="Normal">Normal</option>
							<option value="Emergency">Emergency</option>
							<option value="Chronic">Chronic</option>
							<option value="Deficiency">Deficiency</option>
							<option value="Defects">Defects</option>
						</select> <i></i> </label>
					</section>

					<section>
						<label class="label">STATUS</label>
						<label class="select">
							<select name="page2 ReviewInfo Status" id="page2 ReviewInfo Status">
								<option value="0">Choose an option</option>
								<option selected value="Initiated">Initiated</option>
								<option value="Prescribed">Prescribed</option>
								<option value="Follow-up">Follow-up</option>
								<option value="Under Medication">Under Medication</option>
								<option value="Cured">Cured</option>
								<option value="Hospitalized">Hospitalized</option>
							</select> <i></i> </label>
						</section>
					</fieldset>

					 <fieldset class="demo-switcher-1">
                            <div class="panel panel-default">
                            <div class="panel-heading  text-center"><strong>Attachments</strong>
                            </div>
                           
					<ul class="nav nav-tabs md-tabs nav-justified primary-color" role="tablist">
						<li class="nav-item">
					    <a class="nav-link" data-toggle="tab" href="#panel668" role="tab">
					    <!-- <i class="fa fa-heart pr-2"> </i>-->Prescriptions</a>
					  </li>
					  <li class="nav-item">
					    <a class="nav-link active" data-toggle="tab" href="#panel555" role="tab">
					    <!-- <i class="fa fa-user pr-2"></i> -->Lab Reports</a>
					  </li>
					  <li class="nav-item">
					    <a class="nav-link" data-toggle="tab" href="#panel666" role="tab">
					    <!-- <i class="fa fa-heart pr-2"> </i>-->X-ray/MRI/Digital Images</a>
					  </li>
					  <li class="nav-item">
					    <a class="nav-link" data-toggle="tab" href="#panel667" role="tab">
					    <!-- <i class="fa fa-heart pr-2"> </i>-->Payments/Bills</a>
					  </li>
					   <li class="nav-item">
					    <a class="nav-link" data-toggle="tab" href="#panel669" role="tab">
					    <!-- <i class="fa fa-heart pr-2"> </i>-->Discharge Summary</a>
					  </li>
					  <li class="nav-item">
					    <a class="nav-link" data-toggle="tab" href="#panel770" role="tab">
					    <!-- <i class="fa fa-heart pr-2"> </i>-->Others</a>
					  </li>
					</ul>
					<!-- Nav tabs -->

					<!-- Tab panels -->
					<div class="tab-content">

					  <!-- Panel 1 -->
					  <div class="tab-pane fade" id="panel555" role="tabpanel">

					    <!-- Nav tabs -->
					    
			<input type="file" id="files_labs"  name="Lab_Reports[]" style="display:none;" multiple/>
					                         
						     <label for="files_labs" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
						       Labs Reports.....
						   </label>

					                           

					  </div>
					  <!-- Panel 1 -->

					  <!-- Panel 2 -->
					  <div class="tab-pane fade" id="panel666" role="tabpanel" >

					  <input type="file" id="files_xray"  name="Digital_Images[]" style="display:none;" multiple>
					   
					                         
							<label for="files_xray" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
							   X-ray/MRI/ Digital Images.....

							</label>

					  </div>
					  <!-- Panel 2 -->
					  <!-- Panel 2 -->
					  <div class="tab-pane fade" id="panel667" role="tabpanel">

					 
					    <input type="file" id="files_bills"  name="Payments_Bills[]" style="display:none;" multiple>
					                         
							<label for="files_bills" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
							   Payments Bills attachments.....
							</label>

					  </div>
					  <div class="tab-pane fade" id="panel669" role="tabpanel">

					 
					    <input type="file" id="files_ds"  name="Discharge_Summary[]" style="display:none;" multiple>
					                         
							<label for="files_ds" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
							   Discharge Summary.....
							</label>

					  </div>
					  <!-- Panel 2 -->
					  <!-- Panel 2 -->
					  <div class="tab-pane fade" id="panel668" role="tabpanel">

					 
					    <input type="file" id="files_prescriptions"  name="Prescriptions[]" style="display:none;" multiple>
					                         
							<label for="files_prescriptions" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
							   Prescriptions.....
							</label>

					  </div>
					   <div class="tab-pane fade"  id="panel770" role="tabpanel">

					 
					   <input type="file" id="files"  name="hs_req_attachments[]" style="display:none;" multiple>
					                         
							<label for="files" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
							   Others.....
							</label>

					  </div>
					  <!-- Panel 2 -->

					</div>
				<!-- Tab panels -->
				
                          </div>
                        
                        </fieldset>	


								<footer>
									 <div class="row">
                                  <div class="col-md-7">
                                    
                            <button class="btn btn-success col-md-3 submit" type="submit" id="sub_mit">
									  <i class="fa fa-save"></i>
									  SUBMIT
									</button>
                                  </div>
                                  <div class="col-md-4">
									<button type="button" class="btn btn-primary pull-right" onclick="window.history.back();">Back</button>
									</div>
                                </div>
                                <br><br>
									<br><br>
									<!-- <button type="submit" class="btn btn-success">
										Submit
									</button> -->
								</footer>
								<?php echo form_hidden('student_code',"");?>
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
<script src="<?php echo JS; ?>sweetalert.min.js"></script>
<!-- PAGE RELATED PLUGIN(S) 
	<script src="..."></script>-->
	<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->

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
			<?php endif; ?>
			$('.submit').prop('disabled',true);
			$('.general_related').hide();
			$('.emergency_related').hide();
			$('.chronic_related').hide();

			$('.file_attach_count').text('0 files attached');

			$('#normal').click(function(){

				$('.general_related').show();
				$('.emergency_related').hide();

				$('#chronic_eyes').attr('checked',false);
				$('.chronic_related').hide();
				

				/*var oldoptions = [];

				$("[type=radio]").on('click', function () {
				    $("#page2_ReviewInfo_RequestType").append(oldoptions);
				    oldoptions = $("#page2_ReviewInfo_RequestType option:not(:contains(" + $(this).val() + "))").detach();
				});*/


			});

			$("input[id='normal']").on('change',function() {
			         $('#sub_mit').prop('disabled', true);
		         if($(this).is(':checked') && $(this).val() == 'Normal')
		         {
		           $('#page2_ReviewInfo_RequestType').empty()
		          
		            $('#page2_ReviewInfo_RequestType').append('<option value="Normal">Normal</option>'+'<option value="Defects">Defects</option>'+'<option value="Deficiency">Deficiency</option>');
		                      
		         }  
		        if($("input[type='checkbox']").is(':checked'))
		         {
		         	console.log('is checked');
		         	$("input[id='emergency_identifier']").prop('checked',false);
		         	$("input[id='chronic_identifier']").prop('checked',false);
		        // $('#myTabContent_chronic').next('div').next('div').next('.checkbox').next("input[type='checkbox']").prop('checked',false);
		        
		         }

			 });

			$("input[id='emergency']").on('change',function() {
			         $('#sub_mit').prop('disabled', true);
		         if($(this).is(':checked') && $(this).val() == 'Emergency')
		         {
		         	 swal({
			                title: "Notice!",			                 
			                text: "This is not a Emergency handling system. Make sure you Contact PANACEA DOCTORS or Others Officials to take care the Students in Emergency Situation. Do not dependeing on this system.",
			                icon: "success",
			    
			         	 });
		           $('#page2_ReviewInfo_RequestType').empty()
		          
		            $('#page2_ReviewInfo_RequestType').append('<option value="Emergency">Emergency</option>'+'<option value="Defects">Defects</option>'+'<option value="Deficiency">Deficiency</option>');
		                          
		         }
		          if($("input[type='checkbox']").is(':checked'))
		         {
		         	//console.log('is checked');
		         	$("input[id='normal_identifier']").prop('checked',false);
		         	$("input[id='chronic_identifier']").prop('checked',false);
		        // $('#myTabContent_chronic').next('div').next('div').next('.checkbox').next("input[type='checkbox']").prop('checked',false);
		        
		         }
		              
			 });

			$("input[id='chronic']").on('change',function() {
			      $('#sub_mit').prop('disabled', true);
		         if($(this).is(':checked') && $(this).val() == 'Chronic')
		         {
		           $('#page2_ReviewInfo_RequestType').empty()
		          
		           $('#page2_ReviewInfo_RequestType').append('<option value="Chronic">Chronic</option>'+'<option value="Defects">Defects</option>'+'<option value="Deficiency">Deficiency</option>');
		                          
		         }
		          if($("input[type='checkbox']").is(':checked'))
		         {
		         	
		         	$("input[id='emergency_identifier']").prop('checked',false);
		         	$("input[id='normal_identifier']").prop('checked',false);
		        // $('#myTabContent_chronic').next('div').next('div').next('.checkbox').next("input[type='checkbox']").prop('checked',false);
		        
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


			//setting unique_id value during submission //
		    $(document).on('click','.submit',function(e)
		    {
		      var field_ref = $(".retriever_search").attr("field_ref") || '';
		      console.log('field_ref',field_ref);
		      if($(".retriever_search").prev('input').prev('label').hasClass('unique_id'))
		      {
		        var query_ref_label = $(".retriever_search").prev('input').prev('label').text() || '';
		        var query_ref_input = $(".retriever_search").prev('input').val() || '';
		        var query_ref  = ""+query_ref_label+""+query_ref_input+""
		      }
		      else
		      {
		        var query_ref  = $(".retriever_search").prev('input').val() || '';
		      }
		      
		      var stu_code = $('input[name="student_code"]').val(query_ref );
		      $('sub_mit').hide();
		     
		    });

			 var checkedbox = $('input[type="checkbox"]');
		     var button_submit = $('#sub_mit');
				checkedbox.click(function() {
		  		button_submit.attr("disabled", !checkedbox.is(":checked"));
				});

			$('#searchIdBtn').click(function (){

			    var field_ref = $(this).attr("field_ref") || '';
			      if($(this).prev('input').prev('label').hasClass('unique_id'))
			      {
			        var query_ref_label = $(this).prev('input').prev('label').text() || '';
			        var query_ref_input = $(this).prev('input').val() || '';
			        var query_ref = ""+query_ref_label+""+query_ref_input+""
			            $('#student_unique_id').val(query_ref);
			        console.log(query_ref,"unique_id");
			      }
			      else
			      {
			        var query_ref = $(this).prev('input').val() || '';
			            $('#student_unique_id').val(query_ref);
			        console.log(query_ref,"unique_id");
			      }

			    var uniqueId = $('#page1_StudentDetails_HospitalUniqueID').val();
			  
			    $.ajax({
			      url: 'fetch_student_info',
			      type: 'POST',
			      data: {'page1_StudentDetails_HospitalUniqueID':query_ref },
			      success:function(data){
			       
			     if(data == 'NO DATA AVAILABLE')
			        {

			            var uniqueIdField = $("input#page1_StudentDetails_HospitalUniqueID").val();
			            $('#web_view').trigger('reset');
			            $("input#page1_StudentDetails_HospitalUniqueID").val(uniqueIdField);
						
						swal({
						  text: "No student deatils available for this Unique ID: " + query_ref,
						  icon: "warning",
						});
			        }
			        else{

			          data = $.parseJSON(data);
								get_data = data.get_data;
								console.log('get_data',get_data);
								$.each(get_data, function() {
									$("#page1_StudentInfo_Name").val(this['doc_data']['widget_data']['page1']['Personal Information']['Name']);
									$("#page1_StudentInfo_District").val(this['doc_data']['widget_data']['page2']['Personal Information']['District']);
									$("#page1_StudentInfo_SchoolName").val(this['doc_data']['widget_data']['page2']['Personal Information']['School Name']);
									$("#page1_StudentInfo_Class").val(this['doc_data']['widget_data']['page2']['Personal Information']['Class']);
									$("#page1_StudentInfo_Section").val(this['doc_data']['widget_data']['page2']['Personal Information']['Section']);
									
								    if(typeof(this['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path']) != "undefined" && this['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'] !== null)
						           {
						             var photo_student = this['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];
						          // $('#student_image').append('<img src='<?php //echo URLCustomer;?>+photo student+'/>');
						           $('#student_image').show();
						           $('#image_logo').hide();
						           
						               
						             $('#student_image').html('<img id="student_photo" src="<?php echo URLCustomer;?>'+photo_student+'">');
						                    
						                     // $('#image_logo').show();
						                    
						           }

						           else {
						                  //$('#image_logo').load(location.href + ' #image_logo');
						             
						             $('#image_logo').show();
						             $('#image_logo').text( "No Image Found").css({"color":"red","text-align":"center"});
						             $('#student_image').hide();
						            

						           }

									
								});


			        }

			        

			      },
			      error:function(XMlHttpRequest, textStatus, errorThrown) {
			        console.log('error',errorThrown);

			      }
			    })

    return false;

    });


  runAllForms();

           $.validator.addMethod('fileminsize', function(value, element, param) {
             return this.optional(element) || (element.files[0].size >= param) 
          });

          
           $.validator.addMethod('filemaxsize', function(value, element, param) {
             return this.optional(element) || (element.files[0].size <= param) 
          });

		/*if (window.File && window.FileList && window.FileReader) 
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
	            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
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

		}*/

		   var readOnlyLength = $('#dist_school_code').val().length;

		$('#dist_school_code').on('keypress, keydown', function(event) {
		  var $field = $(this);
		  // $('#output').text(event.which + '-' + this.selectionStart);
		  if ((event.which != 37 && (event.which != 39)) &&
		    ((this.selectionStart < readOnlyLength) ||
		      ((this.selectionStart == readOnlyLength) && (event.which == 8)))) {
		    return false;
		  }
		});

		$(function() {
          // Validation
          $("#web_view").validate({
          ignore: "",
          // Rules for form validation
          rules : {
                      page1_StudentDetails_HospitalUniqueID:{required:true,minlength:1,maxlength:123},
                      page2_ProblemInfo_Description:{required:true},
                    },
       
       //Messages for form validation
          messages : {

                        page1_StudentDetails_HospitalUniqueID:{required:"Hospital Unique ID field is required"},
                        page2_ProblemInfo_Description : {required : "Please Enter Valid Description"},
                        
                        },onkeyup: false, //turn off auto validate whilst typing
                                    // Do not change code below
                                          errorPlacement : function(error, element) {
                                            error.insertAfter(element.parent());
                                    }
                          });

            });

		});
 
	</script>
	<script type="text/javascript">
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
	<script type="text/javascript">
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
		     /*$("input:file").html("#files_prescriptions");*/
		 	
		 	//var files = $(".imageThumb").array(); 
		});

		}
	</script>
	
	<script>
		if (window.File && window.FileList && window.FileReader) 
		{
				
		//var numFiles = $("input:file")[0].files.length;
		 $("#files_ds").on("change", function(e) {
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
	<script>
		if (window.File && window.FileList && window.FileReader) 
		{
				
		//var numFiles = $("input:file")[0].files.length;
		 $("#files").on("change", function(e) {
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
	            "</span>").prependTo("#panel770");
		          $(".remove").on('click',function(){
		            $(this).parent(".pip").remove();
		          });
		        });
        		 fileReader.readAsDataURL(f);

		    /* var size = $("input:file")[0].files[j].size;*/
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
	<script >
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
		})
		}
	</script>
	<?php 
//include footer
	include("inc/footer.php"); 
	?>
