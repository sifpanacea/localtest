

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
	width: 150px;
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
#get_search {  
border: 3px solid #99AB66;                                
padding: 20px;  
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
			<div class="row" id="search_bar">
		        <div class="row clearfix" >
		            <div class="col-sm-4">
		                <div class="form-group">
		                    <div class="form-line" >
		                    <input type="text" id='get_search' class="form-control" placeholder="To Update Request Search with Name or ID" style="height: 45px;">
		                    </div>
		                </div>
		            </div>
		            <button type="submit" id="get_val" class="btn btn-success waves-effect" style="padding: 9px">Get</button>
		            <button type="submit" id="close_val" class="btn btn-info waves-effect" style="padding: 9px">Clear</button>
		        </div>
		        <div class="row clearfix" id="search_btn">
		            <p>
		                <b>Matched Requests</b>
		                <br>
		                Other wise raise a New request on this student...
		                    <button class="btn bg-color-teal waves-effect" id="news_req">New Reqest</button>
		               <br>
		                <div id="stud_report"></div>
		            </p>
		        </div> 
		    </div>

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-greenLight show_requests_form"  id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" style="display: none;">

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
			echo  form_open_multipart('ttwreis_cc/initiate_hs_request',$attributes);
			?>
			<fieldset><section class="col-lg-10">
			<div class="row">
				<!-- <section class="col col-md-7"> -->
				<label class="col-md-2" style="margin:15px; font-size: large;"><b>UNIQUE ID</b></label>
					<input type="text" class="col-md-offset-3 col-md-5 student_code" id="page1_StudentDetails_HospitalUniqueID" name="page1_StudentDetails_HospitalUniqueID" required>
				
					<button type="button" class="btn btn-primary  col-md-offset-2 col-md-2 retriever_search" id="searchIdBtn"  field_ref='page1_Personal_Information_Hospital_Unique_ID'><i class="fa fa-search"> SEARCH</i></button>

					<button type="submit" class="btn btn-primary col-md-offset-2 col-md-2" id="go_search">Open Search Bar</button>
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
	                    <label class="checkbox">
	                    	<input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Cold">
	                    <i></i>Cold</label>
                    </div>
                    <div class="col col-md-4">
                    	<label class="checkbox">
                    		<input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Loss Of Appetite">
                    		<i></i>Loss Of Appetite</label>
                    	<label class="checkbox">
                    		<input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Weight Loss">
                    		<i></i>Weight Loss</label>
                    	<label class="checkbox">
                    		<input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Rashes">
                    		<i></i>Rashes</label>
                    </div>
                    <div class="col col-md-4">
	                    <label class="checkbox">
		                    <input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Night Sweats">
		                    <i></i>Night Sweats</label>
	                    <label class="checkbox">
		                    <input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Fever With Rash">
		                    <i></i>Fever With Rash</label>
	                    <label class="checkbox">
		                    <input type="checkbox" id="normal_identifier" name="normal_general_identifier[]" value="Fever with Chills">
		                    <i></i>Fever with Chills</label>
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
		                <div class="col col-md-4">
			                <label class="checkbox">
			                <input type="checkbox" id="normal_identifier" name="normal_head_identifier[]" value="Head Swelling">
			                <i></i>Head Swelling</label>
			                <label class="checkbox">
			                <input type="checkbox" id="normal_identifier" name="normal_head_identifier[]" value="Seizures">
			                <i></i>Seizures</label>
		                </div>
		                <div class="col col-md-4">
			                <label class="checkbox">
			                <input type="checkbox" id="normal_identifier" name="normal_head_identifier[]" value="Head Injury">
			                <i></i>Head Injury</label>
		                </div>
	                </div>
                </div>
        <!----EYES Related ----->
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
		                <div class="col col-md-4">
			                <label class="checkbox">
			                <input type="checkbox" id="normal_identifier" name="normal_eyes_identifier[]" value="Blurring Of Vission">
			                <i></i>Blurring Of Vission</label>
			                <label class="checkbox">
			                <input type="checkbox" id="normal_identifier" name="normal_eyes_identifier[]" value="Double Vision">
			                <i></i>Double Vision</label>
		                </div>
		                <div class="col col-md-4">
			                <label class="checkbox">
			                <input type="checkbox" id="normal_identifier" name="normal_eyes_identifier[]" value="Conjunctivitis">
			                <i></i>Conjunctivitis</label>
			                <label class="checkbox">
			                <input type="checkbox" id="normal_identifier" name="normal_eyes_identifier[]" value="Stye">
			                <i></i>Stye</label>
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
			                <label class="checkbox">
			                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Tonsillitis">
			                <i></i>Tonsillitis</label>
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
			                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Hoarseness of voice">
			                <i></i>Hoarseness of voice</label>
			                <label class="checkbox">
			                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="ASOM">
			                <i></i>ASOM</label>
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
			                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Painful swallowing(Odynophagia)">
			                <i></i>Painful swallowing(Odynophagia)</label>
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
			                <input type="checkbox" id="normal_identifier" name="normal_ent_identifier[]" value="Difficulty in swallowing(Dysphagia)">
			                <i></i>Difficulty in swallowing(Dysphagia)</label>
		                </div>
	                </div>
                </div>

        <!--RS related information -->

                <div class="row tab-pane fade" id="rs">
	                <div class="col col-md-4">
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Cough">
		                <i></i>Cough</label>
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Shortness Of Breath">
		                <i></i>Shortness Of Breath</label>
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Sputum From Mouth">
		                <i></i>Sputum From Mouth</label>
	                </div>
	                <div class="col col-md-4">
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Blood From Mouth">
		                <i></i>Blood From Mouth</label>
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Wheezing">
		                <i></i>Wheezing</label>
	                </div>
	                <div class="col col-md-4">
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Dry Cough">
		                <i></i>Dry Cough</label>
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_rs_identifier[]" value="Wet Cough">
		                <i></i>Wet Cough(Productive cough)</label>
	                </div>
                </div>

        <!--cvs related information-->

                <div class="row tab-pane fade" id="cvs">
	                <div class="col col-md-4">
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_cvs_identifier[]" value="Chest Pain">
		                <i></i>Chest Pain</label>
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_cvs_identifier[]" value="Edema Of Feet">
		                <i></i>Edema oF Feet</label>
	                </div>
	                <div class="col col-md-4">
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_cvs_identifier[]" value="Shortness of Breath">
		                <i></i>Shortness of Breath</label>
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_cvs_identifier[]" value="Cyanosis">
		                <i></i>Cyanosis</label>
	                </div>
                </div>

	    <!--GI related information -->

	            <div class="row tab-pane fade" id="gi">
		            <div class="col col-md-4">
			            <label class="checkbox">
			            <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Dysphagia(difficulty in swallowing)">
			            <i></i>Dysphagia(difficulty in swallowing)</label>
			            <label class="checkbox">
			            <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Nausea">
			            <i></i>Nausea</label>
			            <label class="checkbox">
			            <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Vomitings">
			            <i></i>Vomitings</label>
			            <label class="checkbox">
			            <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Abdominal Pain">
			            <i></i>Abdominal Pain</label>
		            </div>
		            <div class="col col-md-4">
			            <label class="checkbox">
			            <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Blood in vomiting(Hematemesis)">
			            <i></i>Blood in vomiting(Hematemesis)</label>
			            <label class="checkbox">
			            <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Diarrhoea">
			            <i></i>Diarrhoea</label>
			            <label class="checkbox">
			            <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Constipation">
			            <i></i>Constipation</label>
			            <label class="checkbox">
			            <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Piles">
			            <i></i>Piles(Blood in Stools)</label>
		            </div>
		            <div class="col col-md-4">
			            <label class="checkbox">
			            <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Melena(dark stools)">
			            <i></i>Melena(dark stools)</label>
			            <label class="checkbox">
			            <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Blood Per Rectum">
			            <i></i>Blood Per Rectum</label>
			            <label class="checkbox">
			            <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Perineal swelling">
			            <i></i>Perineal swelling</label>
			            <label class="checkbox">
			            <input type="checkbox" id="normal_identifier" name="normal_gi_identifier[]" value="Incontinence Of Stools">
			            <i></i>Incontinence Of Stools</label>						
		            </div>
	            </div>

        <!--GU related information -->

                <div class="row tab-pane fade" id="gu">
	                <div class="col col-md-4">
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Pain On Micturition">
		                <i></i>Pain On Micturition</label>
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Increase Micturition">
		                <i></i>Increase Micturition</label>
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Burning Micturition">
		                <i></i>Burning Micturition</label>
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
		                <input type="checkbox" id="normal_identifier" name="normal_gu_identifier[]" value="Suprapubic Pain">
		                <i></i>Suprapubic Pain</label>
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
		                <input type="checkbox" id="normal_identifier" name="normal_gyn_identifier[]" value="Absence Of Periods">
		                <i></i>Absence Of Periods</label>
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
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_gyn_identifier[]" value="White Discharge">
		                <i></i>White Discharge</label>
	                </div>
                </div>

        <!--ENDO CRINOLOGY related information -->

                <div class="row tab-pane fade" id="endo_cri">
	                <div class="col col-md-4">
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_cri_identifier[]" value="Polyuria">
		                <i></i>Polyuria</label>
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_cri_identifier[]" value="Polyphagia">
		                <i></i>Polyphagia</label>
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_cri_identifier[]" value="Heat Intolerance">
		                <i></i>Heat Intolerance</label>
	                </div>
	                <div class="col col-md-4">
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_cri_identifier[]" value="cold intolerance">
		                <i></i>cold intolerance</label>
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_cri_identifier[]" value="Fatigue">
		                <i></i>Fatigue</label>
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
			                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Ankle pain">
			                <i></i>Ankle pain</label>
			                <label class="checkbox">
			                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Finger Pain">
			                <i></i>Finger Pain</label>
			                <label class="checkbox">
			                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Knee Pain">
			                <i></i>Knee Pain</label>
			                <label class="checkbox">
			                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Leg Pain">
			                <i></i>Leg Pain</label>
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
			                <label class="checkbox">
			                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Hand Pain">
			                <i></i>Hand Pain</label>
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
			                <label class="checkbox">
			                <input type="checkbox" id="normal_identifier" name="normal_msk_identifier[]" value="Injury">
			                <i></i>Injury</label>
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
	                <i></i>Abnormal Gait</label>
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
	                <i></i>Abnormal Sleep</label>
	                </div>
	            </div>
        <!-- PSYCHIARTIC-->
                <div class="row tab-pane fade" id="psychiartic">
	                <div class="col col-md-4">
		                <label class="checkbox">
		                <input type="checkbox" id="normal_identifier" name="normal_psychiartic_identifier[]" value="Psychiatrist">
		                <i></i>Psychiatrist</label>
	                </div>
                </div>
           	</div>
		</div>
	</div>

	<!--Start EMERGENCY-->
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
					        <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Covid-19">
					        <i></i>Covid-19</label>
				        <label class="checkbox">
					        <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Epistaxis">
					        <i></i>Epistaxis</label>
				        <label class="checkbox">
					        <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Seizures">
					        <i></i>Seizures</label>
				        <label class="checkbox">
					        <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Mesenteric lymphadenitis">
					        <i></i>Mesenteric lymphadenitis</label>
				        <label class="checkbox">
					        <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Blood in Sputum">
					        <i></i>Blood in Sputum</label>
				        <label class="checkbox">
					        <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Asthama">
					        <i></i>Asthama</label>
					    <label class="checkbox">
					        <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Acute Appendicitis">
					        <i></i>Acute Appendicitis</label>    
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
				        <label class="checkbox">
				        <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Gall Stones">
				        <i></i>Gall Stones</label>
				        <label class="checkbox">
				        <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Pancreatitis">
				        <i></i>Pancreatitis</label>
				        <label class="checkbox">
				        <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Epilepsy">
				        <i></i>Epilepsy</label>
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
				        <input type="checkbox" id="emergency_identifier" name="emergency_identifier[]" value="Falls and Trauma">
				        <i></i>Falls and Trauma</label>
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
					        <label class="checkbox">
					        <input type="checkbox" id="emergency_identifier" name="emergency_bites_identifier[]" value="Unknown Bite">
					        <i></i>Unknown Bite</label>
				        </div>

			        </div>
		        </div>
	        <!--  END EAR PROBLEMS  ------------------------->
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
        <div id="myTabContent_chronic" class="tab-content padding-10">	
            <div class="tab-pane fade in active" id="chronic_eyes">
	            <div class="row">
	            <div class="col col-md-4">
		            <label class="checkbox">
		            <input type="checkbox" id="chronic_identifier" name="chronic_eyes_identifier[]" value="Glaucoma">
		            <i></i>Glaucoma</label>
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
				        <i></i>Penidontal Disease</label>
				        <label class="checkbox">
				        <input type="checkbox" id="chronic_identifier" name="chronic_ent_identifier[]" value="Acute Otitis Media">
				        <i></i>Acute Otitis Media</label>
			        </div>
		        </div>
            </div>

    <!--  END EAR PROBLEMS  ------------------------->

    <!--  CNS PROBLEMS  ------------------------->

            <div class="tab-pane fade" id="chronic_cns">
	            <div class="row">
		            <div class="col col-md-4">
			            <label class="checkbox">
			            <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Epilepsy">
			            <i></i>Epilepsy</label>
			            <label class="checkbox">
			            <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Myasthenia gravis">
			            <i></i>Myasthenia gravis</label>
			            <label class="checkbox">
			            <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Migraine">
			            <i></i>Migraine</label>
			            <label class="checkbox">
			            <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="OCD">
			            <i></i>OCD</label>
			            <label class="checkbox">
			            <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Personality Disorders">
			            <i></i>Personality Disorders</label>
		            </div>
		            <div class="col col-md-4">
			            <label class="checkbox">
			            <input type="checkbox" id="chronic_identifier" name="chronic_cns_identifier[]" value="Anxiety">
			            <i></i>Anxiety</label>
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
			            <input type="checkbox" name="chronic_cvs_identifier[]" value="VSD">
			            <i></i>VSD</label>
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_cvs_identifier[]" value="RHD">
			            <!---doubt in cvs -->					
			            <i></i>RHD</label>
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_cvs_identifier[]" value="MR">
			            <i></i>MR</label>
		            </div>
		            <div class="col col-md-4">
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_cvs_identifier[]" value="ASD">
			            <i></i>ASD</label>
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_cvs_identifier[]" value="CHD">
			            <i></i>CHD</label>
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
			            <input type="checkbox" name="chronic_gi_identifier[]" value="Jaundice">
			            <i></i>Jaundice</label>
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
			            <input type="checkbox" name="chronic_blood_identifier[]" value="Anemia">
			            <i></i>Anemia</label>


			            <label class="checkbox">
			            <input type="checkbox" name="chronic_blood_identifier[]" value="Mild">
			            <i></i>Mild</label>
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_blood_identifier[]" value="Moderate">
			            <i></i>Moderate</label>
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_blood_identifier[]" value="Severe">
			            <i></i>Severe</label>
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_blood_identifier[]" value="Iron Deficiency">
			            <i></i>Iron Deficiency</label>
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_blood_identifier[]" value="B 12 Deficieny">
			            <i></i>B 12 Deficieny</label>
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_blood_identifier[]" value="Aplastic Anemia">
			            <i></i>Aplastic Anemia</label>
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_blood_identifier[]" value="Sickle Cell Anemia">
			            <i></i>Sickle Cell Anemia</label>
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_blood_identifier[]" value="Anemia of chronic disease">
			            <i></i>Anemia of chronic disease</label>
		            </div>
		            <div class="col col-md-3">
			            <p><strong>PLATELET DISORDER</strong></p>
			            <br>
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_blood_identifier[]" value="Vwb Disorder">
			            <i></i>Vwb Disorder</label>
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_blood_identifier[]" value="Hemophilia">
			            <i></i>Hemophilia</label>
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
			            <input type="checkbox" name="chronic_blood_identifier[]" value="Leukemia">
			            <i></i>Leukemia</label>
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
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_kidney_identifier[]" value="Nephritic Syndrome">
			            <i></i>Nephritic Syndrome</label>
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
			            <input type="checkbox" name="chronic_vandm_identifier[]" value="Vitamins B12">
			            <i></i>Vitamins B12</label>
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
			            <input type="checkbox" name="chronic_bones_identifier[]" value="Fracture">
			            <i></i>Fracture</label>
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
	            <div class="row">
		            <div class="col col-4">
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_endo_identifier[]" value="Diabetes Milletus Type 1">
			            <i></i>Diabetes Milletus Type 1</label>
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_endo_identifier[]" value="Others">
			            <i></i>Others</label>
		            </div>
		            <div class="col col-4">
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_endo_identifier[]" value="Hypothyroidism">
			            <i></i>Hypo Thyroidism</label>
			            <label class="checkbox">
			            <input type="checkbox" name="chronic_endo_identifier[]" value="Hyperthyroidism">
			            <i></i>Hyper Thyroidism</label>
		            </div>
	            </div>
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
			            <label class="checkbox">
			                <input type="checkbox"  name="chronic_others_identifier[]" value="Any Abscess">
			                <i></i>Any Abscess</label>
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
						<input type="checkbox" name="chronic_others_identifier[]" value="Typhoid">
						<i></i>Typhoid</label>
						<label class="checkbox">
						<input type="checkbox" name="chronic_others_identifier[]" value="Cholera">
						<i></i>Cholera</label>
						<label class="checkbox">
						<input type="checkbox"  name="chronic_others_identifier[]" value="Others">
						<i></i>Others</label>
					</div>
	        	</div>			
        	</div>
        	<!--END OTHER CHRONIC PROBLEMS---------------------------------------------->
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
							<select name="page2 ReviewInfo Status" id="selected_status">
								<option value="0">Choose an option</option>
								<option selected value="Initiated">Initiated</option>
                                 <option value="Out-Patient">Out-Patient</option>
								<option value="Prescribed">Prescribed</option>
                                 <option value="Review">Review</option>
								<option value="Follow-up">Follow-up</option>
								<option value="Hospitalized">Hospitalized</option>
								<option value="Under Medication">Under Medication</option>
                                <option value="Surgery-Needed">Surgery-Needed</option>
                                <option value="Discharge">Discharge</option>
								<option value="Cured">Cured</option>
                                 <option value="Expired">Expired</option>
							</select> <i></i> </label>
						</section>
					</fieldset>
					
					 <!--  Show Hospitalised cases Joining and discharge dates -->
                       
                        <div class="container">                  
                        <div class="row">
                            <div id="date_of_join" style="display: none;">                               
                                <section class="col col-sm-3">
                                    <label>Hospital Name</label>                                       
                                    <textarea rows="2" class="form-control no-resize auto-growth" id="std_join_hospital_name" name="std_join_hospital_name"><?php echo set_value('std_join_hospital_name', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['Hospital Name'])) ? htmlspecialchars_decode($doc['student_request']['doc_data']['widget_data']['page2']['Hospital Info']['Hospital Name']) : ''); ?></textarea>
                                </section>
                    <section class="col col-sm-3">
                                     <label class="label">Hospital Type</label>
                                        <label class="select">
                    <select name="std_join_hospital_type" id="std_join_hospital_type">
                            <option value=''><?php echo lang('web_choose_option')?></option>
                        <option value="Government" <?php echo  preset_select('std_join_hospital_type', 'Government', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['Hospital Type'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['Hospital Type'] : ''  ) ?>> Government </option>
                        <option value="Private" <?php echo  preset_select('std_join_hospital_type', 'Private', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['Hospital Type'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['Hospital Type'] : ''  ) ?>> Private </option>
                    </select>
                                                     <i></i> 
                                                 </label>
                </section>
                                <section class="col col-sm-3">
                                     <label class="label">Hospital-District Name</label>
                                        <label class="select">
                                        <select name="std_join_hospital_dist" id="std_join_hospital_dist">
                                        <option value=''><?php echo lang('web_choose_option')?></option>
                        <option value="ADILABAD" <?php echo  preset_select('std_join_hospital_dist', 'ADILABAD', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>> ADILABAD </option>
                    <option value="BHADRADRI" <?php echo  preset_select('std_join_hospital_dist', 'BHADRADRI', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>BHADRADRI</option>
                    <option value="GADWAL" <?php echo  preset_select('std_join_hospital_dist', 'GADWAL', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>GADWAL</option>
                    <option value="HYDERABAD" <?php echo  preset_select('std_join_hospital_dist', 'HYDERABAD', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>HYDERABAD</option>
                    <option value="JAGITYAL" <?php echo  preset_select('std_join_hospital_dist', 'JAGITYAL', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>JAGITYAL</option>
                    <option value="JANGAON" <?php echo  preset_select('std_join_hospital_dist', 'JANGAON', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>JANGAON</option>
                    <option value="JAYASHANKAR" <?php echo  preset_select('std_join_hospital_dist', 'JAYASHANKAR', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>JAYASHANKAR</option>
                    <option value="KAMAREDDY" <?php echo  preset_select('std_join_hospital_dist', 'KAMAREDDY', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>KAMAREDDY</option>
                    <option value="KARIMNAGAR" <?php echo  preset_select('std_join_hospital_dist', 'KARIMNAGAR', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>KARIMNAGAR</option>
                    <option value="KHAMMAM" <?php echo  preset_select('std_join_hospital_dist', 'KHAMMAM', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>KHAMMAM</option>
                    <option value="KOMURAMBHEEM" <?php echo  preset_select('std_join_hospital_dist', 'KOMURAMBHEEM', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>KOMURAMBHEEM</option>
                    <option value="MAHABUBABAD" <?php echo  preset_select('std_join_hospital_dist', 'MAHABUBABAD', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>MAHABUBABAD</option>
                    <option value="MAHABUBNAGAR" <?php echo  preset_select('std_join_hospital_dist', 'MAHABUBNAGAR', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>MAHABUBNAGAR</option>
                    <option value="MANCHERIAL" <?php echo  preset_select('std_join_hospital_dist', 'MANCHERIAL', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>MANCHERIAL</option>
                    <option value="MEDAK" <?php echo  preset_select('std_join_hospital_dist', 'MEDAK', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>MEDAK</option>
                    <option value="MEDCHAL" <?php echo  preset_select('std_join_hospital_dist', 'MEDCHAL', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>MEDCHAL</option>
                    <option value="NAGARKURNOOL" <?php echo  preset_select('std_join_hospital_dist', 'NAGARKURNOOL', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>NAGARKURNOOL</option>
                    <option value="NALGONDA" <?php echo  preset_select('std_join_hospital_dist', 'NALGONDA', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>NALGONDA</option>
                    <option value="NIRMAL" <?php echo  preset_select('std_join_hospital_dist', 'NIRMAL', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>NIRMAL</option>
                    <option value="NIZAMABAD" <?php echo  preset_select('std_join_hospital_dist', 'NIZAMABAD', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>NIZAMABAD</option>
                    <option value="PEDDAPALLI" <?php echo  preset_select('std_join_hospital_dist', 'PEDDAPALLI', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>PEDDAPALLI</option>
                    <option value="RAJANNA" <?php echo  preset_select('std_join_hospital_dist', 'RAJANNA', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>RAJANNA</option>
                    <option value="RANGAREDDY" <?php echo  preset_select('std_join_hospital_dist', 'RANGAREDDY', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>RANGAREDDY</option>
                    <option value="SANGAREDDY" <?php echo  preset_select('std_join_hospital_dist', 'SANGAREDDY', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>SANGAREDDY</option>
                    <option value="SIDDIPET" <?php echo  preset_select('std_join_hospital_dist', 'SIDDIPET', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>SIDDIPET</option>
                     <option value="SURYAPET" <?php echo  preset_select('std_join_hospital_dist', 'SURYAPET', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>SURYAPET</option>
                    <option value="VIKARABAD" <?php echo  preset_select('std_join_hospital_dist', 'VIKARABAD', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>VIKARABAD</option>
                    <option value="WANAPARTHY" <?php echo  preset_select('std_join_hospital_dist', 'WANAPARTHY', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>WANAPARTHY</option>
                    <option value="WARANGAL RURAL" <?php echo  preset_select('std_join_hospital_dist', 'WARANGAL RURAL', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>WARANGAL RURAL</option>
                    <option value="WARANGAL URBAN" <?php echo  preset_select('std_join_hospital_dist', 'WARANGAL URBAN', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>WARANGAL URBAN</option>
                    <option value="YADADRI" <?php echo  preset_select('std_join_hospital_dist', 'YADADRI', (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'])) ? $doc['doc_data']['widget_data']['page2']['Hospital Info']['District Name'] : ''  ) ?>>YADADRI</option>
                                                    </select>
                                                     <i></i> 
                                                 </label>
                                </section>                  
                                <section class="col col-sm-2">
                                   <label>Hospital Join Date</label>
                                           <div id="bs_datepicker_container">
                                             <input type="text" id="hospitalised_date" name="hospitalised_date" class="form-control date" value="<?php echo (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['Hospital Join Date']) ? ($doc['student_request']['doc_data']['widget_data']['page2']['Hospital Info']['Hospital Join Date']): date('Y-m-d')); ?>">
                                        <!--  <?php //echo $doc['student_request']['doc_data']['widget_data']['page2']['Hospital Info']['Hospital Join Date']; ?>
                                             -->                                           
                                           </div>                               
                            </section>
                            <?php //if($doc['student_request']['doc_data']['widget_data']['page2']['Review Info']['Status'] == 'Hospitalized') : ?>
                               <!--  <div class="col col-sm-4">
                                   <input type="checkbox" name="hospital_transfer_followup" id="hospital_transfer_id" class="filled-in chk-col-red" value="hospital_transfer_followup" />
                                   <label for="hospital_transfer_id"><b style="color: red">Check If Hospital Transfered</b></label>
                               </div> -->
                            <?php// endif; ?>
                                <div class="col-sm-12" id="hospital_transfer" style="display: none;">
                                    <div class="col col-sm-3">
                                        <label>Transferred Hospital Name</label>
                                        <div class="form-line">
                                            <textarea rows="2" class="form-control no-resize auto-growth" id="transfer_join_hospital_name" name="transfer_join_hospital_name"></textarea>
                                        </div>
                                    </div>
                                    <div class="col col-sm-4">
                                             <label>Transeffered Hopital Join Date</label>
                                        <div class="form-line" id="bs_datepicker_container">
                                          <input type="text" id="transfer_hospitalised_date" name="transfer_hospitalised_date" class="form-control date" value="" placeholder="Please choose a date...">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="col-sm-3" id="date_of_discharge" style="display: none;">
                                  <label>Discharge Date</label>
                                <div class="form-line" id="bs_datepicker_container">
                                    <input type="text" id="discharge_date" name="discharge_date" class="form-control date" value="<?php echo (isset($doc['doc_data']['widget_data']['page2']['Hospital Info']['Hospital Join Date']) ? ($doc['student_request']['doc_data']['widget_data']['page2']['Hospital Info']['Hospital Join Date']): date('Y-m-d')); ?>" >
                                </div>
                            </div>
                        </div>
                        </div>
    
                    <!--  ENd Show Hospitalised cases Joining and discharge dates -->   


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
					<!--<div class="well bg-color-blue pull-right hs attachments"><h5 class="" id="click upload"><center><i class="fa fa-paperclip"></i> Click here to attach files</center></h5></div><input type='file' id='hs req attachments' name='hs req attachments[]' class="hide hs req attachments" value="" multiple="multiple"/> 
				  <div class="file_attach_count note pull-right"></div>-->

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
<script src="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script>

<script type="text/javascript">
   // get_searched_documents();

    $('#news_req').click(function(){
        $('.show_requests_form').show();
        
        $('#search_bar').hide();
    });

    $('#go_search').click(function(){
        $('#search_bar').show();
        $('.show_requests_form').hide();
    });

    $('#get_val').click(function(){

        $('#request_btn').hide();

        var search = $('#get_search').val();
        //alert(search);
      
        $.ajax({

            url:'get_searched_student_sick_requests',
            type: 'POST',
            data:{"search_value":search},
            success: function(data){
                $('#search_btn').show();
                result = $.parseJSON(data);
                console.log(result);
               display_data_table(result);
            },
            error:function(XMLHttpRequest, textStatus, errorThrown)
            {
             console.log('error', errorThrown);
            }
        });

    });

    function display_data_table(result)
    {
       if(result.length > 0){
           data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="screened_Students"><thead><tr><th>Unique ID</th><th class="hide">doc ID</th><th>Student Name</th><th>Class</th><th>Request Raised Time</th><th>Access</th></tr></thead><tbody>';

           $.each(result, function() {
               
               data_table = data_table + '<tr>';

               data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Student Info']['Unique ID'] + '</td>';
               data_table = data_table + '<td class="hide">'+this.doc_properties['doc_id'] + '</td>';
               data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Student Info']['Name']['field_ref'] + '</td>';
               data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Student Info']['Class']['field_ref'] + '</td>';
               data_table = data_table+'<td>'+this.history['0']['time']+'</td>';

               data_table = data_table + '<td><button class="btn bg-green btn-sm waves-effect ehrButton">Update</button></td>';
            
               data_table = data_table + '</tr>';
             
           });
           data_table = data_table +'</tbody></table>';
           
           $("#stud_report").html(data_table);

           $('#screenexcel_btn').html('<button class="btn bg-green btn-sm waves-effect getExcel">Get EXcel</button>');

           $("#stud_report").each(function(){
           $('.ehrButton').click(function(){
               var currentRow = $(this).closest("tr");
               var studentHealthID=currentRow.find("td:eq(1)").text();
               //alert(studentHealthID);
               //$("#student_id").val(studentHealthID);
                window.location = '<?php echo URL; ?>ttwreis_cc/access_submited_request_docs/'+studentHealthID;
              // $("#request_form").submit();

               });
           });

            $('#screened_Students').DataTable({
               "paging": true,
               dom: 'Bfrtip',
               buttons: [
                       'copy', 'csv', 'excel', 'pdf', 'print'
                       ]
               });

           //=====================================================================================================
           }else{
               $("#stud_report").html('<h5>There is No requests for in this student , so please raise a new request</h5>');
           }
        }

    $('#search_btn').hide();
    $('#close_val').click(function(){
        $('#search_btn').hide();
        $('#request_btn').show();
    });

</script>

 
    <script type="text/javascript">                                 
        //show_if_hospital_checked();
        var today_date = $('#set_date').val();
        //$('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
        $('#set_date').change(function(e){
                today_date = $('#set_date').val();;
        });   

    $('#hospital_transfer_id').click(function(){
        if($(this).is(":checked")){
            $('#hospital_transfer').show();
        }else{
            $('#hospital_transfer').hide();
        }
    });

    $('#selected_status').change(function(){
        var status = $('#selected_status').val();      
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
        var status = $('#selected_status').val();
        if(status == 'Hospitalized'){
            $('#date_of_join').show();
        }else{
            $('#date_of_join').hide();
        };
    }

  </script>

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
			 // debugger;
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

		}

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
	<?php 
//include footer
	include("inc/footer.php"); 
	?>
