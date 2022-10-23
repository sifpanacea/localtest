
  <?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Create Screening";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["screening_report"]["sub"]["create_screening"]["active"] = true;
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
  <h2>Create Screening Report</h2>

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
  echo  form_open_multipart('panacea_cc/submit_screening_report',$attributes);
  ?>

                  <fieldset>
                    <div class="row">
                      <section class="col col-3">
                        <label class="input"> <i class="icon-prepend fa fa-user"></i>
                          <input type="text" name="uniqueid" id="Uniqueid" placeholder="Uniqueid">
                        </label><br>
                        <label class="input"> <i class="icon-prepend fa fa-phone"></i>
                          <input type="tel" name="mobile" id="mobile" placeholder="Mobile" class="valid">
                        </label>
                      </section>
                      <section class="col col-3">
                      <label class="input"> <i class="icon-prepend fa fa-info-circle"></i>
                          <input type="text" name="student_name" id="Student Name" placeholder="Student Name">
                        </label><br>
                        <label for="class" class="input">

                        <!-- <input type="text" name="school_name" id="school_name" value="<?php //echo $school_name;?>" readOnly="true"> -->
                        <input type="text" name="class" placeholder="Student Class">
                      </label>
                        </section>
                      <section class="col col-3">

                        <label class="input"> <i class="icon-prepend fa fa-info-circle"></i>
                          <input type="text" name="father_name" id="father_name" placeholder="Father Name">
                        </label><br>
                        <label class="input"> <i class="icon-prepend fa fa-calendar"></i>
                          <input type="text" name="date_of_birth" id="date_of_birth" placeholder="DOB" class="datepicker hasDatepicker" data-dateformat="dd/mm/yy">
                        </label> 
                        <label class="input"> <i class="icon-prepend fa fa-calendar"></i>
                          <input type="text" name="date_of_birth" id="admission_no" placeholder="DOB" class="datepicker hasDatepicker" data-dateformat="dd/mm/yy">
                        </label>
                      </section>
                      <section class="col col-2">
                        <div class="logo_img logo" style="background-image: url('https://mednote.in/PaaS/bootstrap/dist/img/avatars/male.png');"><h5 class="" id="click_upload"><center>Click here to upload</center></h5></div>
                          <input type="file" id="file" name="logo_file" class="hide logo_file" value="">
                      </section>
                    </div>
                  
                    <div class="row">
                      <section class="col col-4">
                      <label class="input">
                          <!-- <input type="text" name="helath_unique_id" id="helath_unique_id" value="<?php //echo $huniqueid;?>" readOnly="readOnly"> -->
                          <input type="text" name="school_name" id="school_name" placeholder="School Name">
                        </label>
                      </section>

                      <section class="col col-4">
                      <label for="district_name" class="input">

                        <!-- <input type="text" name="school_name" id="school_name" value="<?php //echo $school_name;?>" readOnly="true"> -->
                        <input type="text" name="district_name" id="district_name" placeholder="District">
                      </label>
                    </section>
                    <section class="col col-4">
                      <label for="mobile_number" class="input">

                        <!-- <input type="text" name="school_name" id="school_name" value="<?php //echo $school_name;?>" readOnly="true"> -->
                        <input type="text" name="mobile_number" id="mobile_number" placeholder="Mobile Number">
                      </label>
                    </section>

                    </div>
                    <div class="row">
                      <section class="col col-4">
                      <label class="input" for="admission_no">
                          <!-- <input type="text" name="helath_unique_id" id="helath_unique_id" value="<?php //echo $huniqueid;?>" readOnly="readOnly"> -->
                          <input type="text" name="admission_no" id="admission_no" placeholder="AD NO">
                        </label>
                      </section>

                      <section class="col col-2">
                      <label for="class" class="input">

                        <!-- <input type="text" name="school_name" id="school_name" value="<?php //echo $school_name;?>" readOnly="true"> -->
                        <input type="text" name="class" placeholder="Student Class">
                      </label>
                    </section>
                    <section class="col col-2">
                      <label for="section" name="section" class="input">

                        <!-- <input type="text" name="school_name" id="school_name" value="<?php //echo $school_name;?>" readOnly="true"> -->
                        <input type="text" name="section" placeholder="Student Section">
                      </label>
                    </section>
                    <section class="col col-4">
                      <label class="input">
                          <!-- <input type="text" name="helath_unique_id" id="helath_unique_id" value="<?php //echo $huniqueid;?>" readOnly="readOnly"> -->
                          <input type="text" name="date_of_exam" placeholder="Date of Exam">
                        </label>
                      </section>
                    </div>

                  </fieldset>

                   <fieldset>
                    <div class="form-group">

                      <header class="bg-color-green txt-color-white">Physical Exam</header>
                      <section class="col col-2">
                        <label class="control-label">Height cms</label>
                        <input class="form-control" type="text" name="height" placeholder="height in cms"/>
                      </section>
                       <section class="col col-2">
                        <label class="control-label">Weight kgs</label>
                        <input type="text" class="form-control" name="weight" placeholder="weight in kgs"/>
                      </section>
                      <section class="col col-1">
                        <label class="control-label">BMI</label>
                        <input type="text" class="form-control" name="bmi" placeholder="bmi"/>
                      </section>
                      <section class="col col-2">
                        <label class="control-label">Pulse</label>
                        <input type="text" class="form-control" name="pulse" placeholder="pulse"/>
                      </section>
                       <section class="col col-1">
                        <label class="control-label">B P</label>
                        <input type="text" class="form-control" name="bp" placeholder="B P"/>
                      </section>
                      <section class="col col-1">
                        <label class="control-label">H B</label>
                        <input type="text" class="form-control" name="hb" placeholder="H B" />
                      </section>
                      <section class="col col-2">
                        <label class="control-label">Blood Group</label>
                        <!-- <input type="text" class="form-control" name="height" placeholder="height"/> -->
                        <select class="form-control" name="blood_group">
                          <option value=""></option>
                          <option value="A+">A+</option>
                          <option value="B+">B+</option>
                          <option value="AB+">AB+</option>
                          <option value="O+">O+</option>
                          <option value="A-">A-</option>
                          <option value="B-">B-</option>
                          <option value="AB-">AB-</option>
                          <option value="O-">O-</option>
                        </select>
                      </section>


                    </div>
                 </fieldset>
    <fieldset>
      <header class="bg-color-green txt-color-white">Doctor Check Up</header>
         <div class="form-group">
          <div class="col bg-color-azure">Check the box if normal else describe abnormalities</div><br>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Neurologic">
              <i></i>Neurologic</label>
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="H and N">
              <i></i>H and N</label>
              <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="ENT">
              <i></i>ENT</label>
              <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Lymphatic">
              <i></i>Lymphatic</label>

          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Heart">
              <i></i>Heart</label>
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Lungs">
              <i></i>Lungs</label>
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Abdomen">
              <i></i>Abdomen</label>
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Genetalia">
              <i></i>Genetalia</label>
          </div>

          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Skin">
              <i></i>Skin</label>
            <label class="checkbox">
            <input type="checkbox" id="" name="general_problems[]" value="Pyrexia">
              <i></i>Pyrexia</label>
              <label class="checkbox">
            <input type="checkbox" id="" name="general_problems[]" value="URTI">
              <i></i>URTI</label>
            <label class="checkbox">
            <input type="checkbox" id="" name="general_problems[]" value="Injuries">
              <i></i>Injuries</label>
          </div>

          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="UTI">
              <i></i>UTI</label>
            <label class="checkbox">
          <input type="checkbox" id="" name="general_problems[]" value="Angular Stomatities">
              <i></i>Angular Stomatities</label>
              <label class="checkbox">
          <input type="checkbox" id="" name="general_problems[]" value="Apthous Ulcer">
              <i></i>Apthous Ulcer</label>
              <label class="checkbox">
          <input type="checkbox" id="" name="general_problems[]" value="Glossities">
              <i></i>Glossities</label>
              <label class="checkbox">
          <input type="checkbox" id="" name="general_problems[]" value="Pharyngitis">
              <i></i>Pharyngitis</label>
          </div>

        </div>
      <!-- </fieldset>
        <fieldset> -->
        <div class="form-group">
          <div>Ortho</div>

          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="ortho_problems[]" value="Neck">
              <i></i>Neck</label>
              <label class="checkbox">
              <input type="checkbox" id="" name="ortho_problems[]" value="Knees">
              <i></i>Knees</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="ortho_problems[]" value="Shoulder">
              <i></i>Shoulder</label>
            
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="ortho_problems[]" value="Arms">
              <i></i>Arms</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="ortho_problems[]" value="Hips">
              <i></i>Hips</label>
              <label class="checkbox">
              <input type="checkbox" id="" name="ortho_problems[]" value="Feet">
              <i></i>Feet</label>
          </div>

        </div>
     
       
        <div class="form-group">
          <div>Postural</div>
          <!-- <div class="col">Ortho</div><br> -->
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="postural_problems[]" value="No spinal Abnormality">
              <i></i>No spinal abnormality</label>
              <label class="checkbox">
              <input type="checkbox" id="Moderate" name="postural_problems[]" value="Moderate">
              <i></i>Moderate</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="postural_problems[]" value="Spinal Abnomality">
              <i></i>Spainal abnormality</label>
            <label class="checkbox">
              <input type="checkbox" id="" name="postural_problems[]" value="Referal Made">
              <i></i>Referal Made</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Mild" name="postural_problems[]" value="Mild">
              <i></i>Mild</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Marked" name="postural_problems[]" value="Marked">
              <i></i>Marked</label>
          </div>

        </div>
        <div class="form-group">
          <label for="text-area">Description :
            <textarea rows="5" cols="150" class="col-offset-1" name="general_description"></textarea>
          </label>
        </div>
      </fieldset>
      <fieldset>
        <div class="form-group">

          <div class="col">Ortho</div><br>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="defects_at_birth_problems[]" value="Neural Tube Defect">
              <i></i>Neural Tube Defect</label>
              <label class="checkbox">
              <input type="checkbox" id="Down Syndrome" name="defects_at_birth_problems[]" value="Down Syndome">
              <i></i>Down Syndrome</label>
            <label class="checkbox">
              <input type="checkbox" id="RetinopathyofPrematurity" name="defects_at_birth_problems[]" value="Retinopathy of Prematurity">
              <i></i>RetinopathyofPrematurity</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Cleft lip and palate" name="defects_at_birth_problems[]" value="Cleft Lip and Palate">
              <i></i>Cleft lip and palate</label>
            <label class="checkbox">
              <input type="checkbox" id="" name="defects_at_birth_problems[]" value="Congential Cataract">
              <i></i>Congenital cataract</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Talipes(clubfoot)" name="defects_at_birth_problems[]" value="Talipes Club foot">
              <i></i>Talipes Club foot</label>
              <label class="checkbox">
              <input type="checkbox" id="Congenitaldeafness" name="defects_at_birth_problems[]" value="Congential Deafness">
              <i></i>Congential Deafness</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="DevelopmentalDysplasiaofHip" name="defects_at_birth_problems[]" value="Developmental Dyslpasia of Hip">
              <i></i>Developmental Dyslpasia of Hip</label>
            <label class="checkbox">
              <input type="checkbox" id="CongenitalHeartDiesease" name="defects_at_birth_problems[]" value="Congential Heart Disease">
              <i></i>Congential Heart Disease</label>
          </div>

        </div>
      </fieldset>
      <fieldset>
        <header class="bg-color-green txt-color-white">Deficencies</header>
        <div class="form-group">
          <section>Anemia</section>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Mild" name="deficencies_problems[]" value="Anemia-Mild">
              <i></i>Mild</label>
            <label class="checkbox">
              <input type="checkbox" id="Moderate" name="deficencies_problems[]" value="Anemia-Moderate">
              <i></i>Moderate</label>
              <label class="checkbox">
              <input type="checkbox" id="Severe" name="deficencies_problems[]" value="Anemia-Severe">
              <i></i>Severe</label>
          </div><div class="col col-3">

            <label class="checkbox">
              <input type="checkbox" id="VitaminDefinciency B-complex" name="deficencies_problems[]" value=" Vitamin Deficiency - BComplex">
              <i></i>VitaminDefinciency B-complex</label>
            <label class="checkbox">
              <input type="checkbox" id="Goiter" name="deficencies_problems[]" value="Goiter">
              <i></i>Goiter</label>
              <label class="checkbox">
              <input type="checkbox" id="Under weight" name="deficencies_problems[]" value="Under Weight">
              <i></i>Under weight</label>
          </div><div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Vitamin A Definciency" name="deficencies_problems[]" value="Vitamin A Deficiency">
              <i></i>Vitamin A Definciency</label>
            <label class="checkbox">
              <input type="checkbox" id="SAM/stunting" name="deficencies_problems[]" value="SAM/Stunting">
              <i></i>SAM/stunting</label>
              <label class="checkbox">
              <input type="checkbox" id="Normal weight" name="deficencies_problems[]" value="Normal Weight">
              <i></i>Normal weight</label>
          </div><div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Vitamin C definciency" name="deficencies_problems[]" value="Vitamin C Deficiency">
              <i></i>Vitamin C definciency</label>
            <label class="checkbox">
              <input type="checkbox" id="VitaminDefinciency D" name="deficencies_problems[]" value="Vitamin D Deficiency">
              <i></i>VitaminDefinciency D</label>
            <label class="checkbox">
              <input type="checkbox" id="Over weight" name="deficencies_problems[]" value="Over Weight">
              <i></i>Over weight</label>
            <label class="checkbox">
              <input type="checkbox" id="Obese" name="deficencies_problems[]" value="Obese">
              <i></i>Obese</label>

          </div>
        </div>
      </fieldset>
      <fieldset>
        <div class="form-group">


          <section>Childhood Diseases</section>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Mild" name="childhood_disease_problems[]" value="Cervical Lymph Adenitis">
              <i></i>Cervical Lymph Adenitis</label>
            <label class="checkbox">
              <input type="checkbox" id="Acute Br.Asthma" name="childhood_disease_problems[]" value="Acute Br.Asthama">
              <i></i>Acute Br.Asthma</label>
              <label class="checkbox">
              <input type="checkbox" id="Epilepsy" name="childhood_disease_problems[]" value="Epilepsy">
              <i></i>Epilepsy</label>
            </div>
            <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="VitaminDefinciency B-complex" name="childhood_disease_problems[]" value="ASOM">
              <i></i>ASOM</label>
            <label class="checkbox">
              <input type="checkbox" id="Chronic Br.Asthma" name="childhood_disease_problems[]" value="Chronic Br.Asthma">
              <i></i>Chronic Br.Asthma</label>
              <label class="checkbox">
              <input type="checkbox" id="Icterus" name="childhood_disease_problems[]" value="Icterus">
              <i></i>Icterus</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="CSOM" name="childhood_disease_problems[]" value="CSOM">
              <i></i>CSOM</label>
            <label class="checkbox">
              <input type="checkbox" id="Hypothyrodism" name="childhood_disease_problems[]" value="Hypothyrodism">
              <i></i>Hypothyrodism</label>
              <label class="checkbox">
              <input type="checkbox" id="Hyperthyrodism" name="childhood_disease_problems[]" value="Hyperthyroidism">
              <i></i>Hyperthyrodism</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Rheumatic HeartDiesease" name="childhood_disease_problems[]" value="Rheumatic Heart Disease">
              <i></i>Rheumatic HeartDiesease</label>
            <label class="checkbox">
              <input type="checkbox" id="Type-1 diabetes" name="childhood_disease_problems[]" value="Type-I Diabeties">
              <i></i>Type-1 diabetes</label>
            <label class="checkbox">
            <input type="checkbox" id="Type-2 diabetes" name="childhood_disease_problems[]" value="Type-II Diabeties">
              <i></i>Type-2 diabetes</label>
           </div>
        </div>
      </fieldset>
      <fieldset>
        <div class="form-group">
          <section>Skin Condition</section>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Scabies" name="skin_problems[]" value="Scabies">
              <i></i>Scabies</label>
            <label class="checkbox">
              <input type="checkbox" id="Taenia Corporis" name="skin_problems[]" value="Taenia Corporis">
              <i></i>Taenia Corporis</label>
              <label class="checkbox">
              <input type="checkbox" id="Taenia Facialis" name="skin_problems[]" value="Taenia Facialis">
              <i></i>Taenia Facialis</label>
              <label class="checkbox">
              <input type="checkbox" id="Taenia Cruris" name="skin_problems[]" value="Taenia Cruris">
              <i></i>Taenia Cruris</label>
            </div>
            <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="ECCEMA" name="skin_problems[]" value="ECCEMA">
              <i></i>ECCEMA</label>
            <label class="checkbox">
              <input type="checkbox" id="Psoriasis" name="skin_problems[]" value="Psoriasis">
              <i></i>Psoriasis</label>
              <label class="checkbox">
              <input type="checkbox" id="Allergic Rash" name="skin_problems[]" value="Allergic Rash">
              <i></i>Allergic Rash</label>
              <label class="checkbox">
              <input type="checkbox" id="Molluscum" name="skin_problems[]" value="Molluscum">
              <i></i>Molluscum</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="White Patches on Face" name="skin_problems[]" value="White Patches on Face">
              <i></i>White Patches on Face</label>
            <label class="checkbox">
              <input type="checkbox" id="Acne on face" name="skin_problems[]" value="Acne on Face">
              <i></i>Acne on face</label>
              <label class="checkbox">
              <input type="checkbox" id="Hyper Pigmentation" name="skin_problems[]" value="Hyper Pigmentation">
              <i></i>Hyper Pigmentation</label>
              <label class="checkbox">
              <input type="checkbox" id="Greying of Hair" name="skin_problems[]" value="Greying Hair">
              <i></i>Greying of Hair</label>
              <label class="checkbox">
              <input type="checkbox" id="Cracked Feet" name="skin_problems[]" value="Cracked Feet">
              <i></i>Cracked Feet</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Hypo Pigmentation" name="skin_problems[]" value="Hypo Pigmentation">
              <i></i>Hypo Pigmentation</label>
            <label class="checkbox">
              <input type="checkbox" id="Hansens Disease" name="skin_problems[]" value="Hansens Disease">
              <i></i>Hansens Disease</label>
            <label class="checkbox">
            <input type="checkbox" id="Nail bed disease" name="skin_problems[]" value="Nail Bed Disease">
              <i></i>Nail bed disease</label>
              <label class="checkbox">
            <input type="checkbox" id="Dandruff" name="skin_problems[]" value="Danddruff">
              <i></i>Dandruff</label>
              <label class="checkbox">
            <input type="checkbox" id="Hyperhidrosis" name="skin_problems[]" value="Hyperhidrosis">
              <i></i>Hyperhidrosis</label>
           </div>
        </div>
      </fieldset>
      <fieldset>
        <div class="form-group">
          <section>NAD</section>
          <div class="col col-3">
            <label class="checkbox">
            <input type="checkbox" id="NAD" name="nad[]" value="Yes">
              <i></i>NAD</label>
          </div>
        </div>
      </fieldset>
        <header class="bg-color-green txt-color-white">Slit Lamp Observartion</header>
        <fieldset >
          <div class="form-group">
          <div class="col col-4">Eye lids</div>
          <div class="col col-4">
          <input type="radio" name="eye_lids" value="Normal">Normal</div>
          <div class="col col-4">
          <input type="radio" name="eye_lids" value="Abnormal">Abnormal </div>
          </div>

          <div class="form-group">
          <div class="col col-4">Conjuctiva</div>
          <div class="col col-4">
          <input type="radio" name="conjuctiva" value="Normal">Normal </div>
          <div class="col col-4">
          <input type="radio" name="conjuctiva" value="Abnormal">Abnormal </div>
          </div>

          <div class="form-group">
          <div class="col col-4">Cornea</div>
          <div class="col col-4">
          <input type="radio" name="cornea" value="Normal">Normal </div>
          <div class="col col-4">
          <input type="radio" name="cornea" value="Abnormal">Abnormal </div>
          </div>

          <div class="form-group">
          <div class="col col-4">Pupil</div>
          <div class="col col-4">
          <input type="radio" name="pupil" value="Normal">Normal </div>
          <div class="col col-4">
          <input type="radio" name="pupil" value="Abnormal">Abnormal </div>
          </div>

          <div class="form-group">
          <div class="col col-4">Compliaints</div>
          <div class="col col-4">
          <input type="radio" name="compliaints" value="Yes">Yes</div>
          <div class="col col-4">
          <input type="radio" name="compliaints" value="No">No</div>
          </div>
          <div class="form-group">
          <div class="col col-4">Wearing Spectacles</div>
          <div class="col col-4">
          <input type="radio" name="wearing_spectacles" value="Yes">Yes</div>
          <div class="col col-2">
          <input type="radio" name="wearing_spectacles" value="No">No</div>
          <div class="col col-2">
          <input type="radio" name="wearing_spectacles" value="Broken">Broken</div>
          </div>

        </fieldset>

        <fieldset>
                <header class="bg-color-green txt-color-white">Without Glasses</header>
          <div class="form-group">
            <div class="col col-4">
            <label>Right</label>
            <select class="form-control" name="without_glasses_right">
              <option>6/6</option>
              <option>6/9</option>
              <option>6/12</option>
              <option>6/18</option>
              <option>6/24</option>
              <option>6/36</option>
              <option>6/60</option>
            </select>
            </div>
            <div class="col col-4">
            <label>Left</label>
            <select class="form-control" name="without_glasses_left">
              <option>6/6</option>
              <option>6/9</option>
              <option>6/12</option>
              <option>6/18</option>
              <option>6/24</option>
              <option>6/36</option>
              <option>6/60</option>
            </select>
          </div>
          </div>
        </fieldset>
        <fieldset>
                <header class="bg-color-green txt-color-white">With Glasses</header>
          <div class="form-group">
            <div class="col col-4">
            <label>Right</label>
            <select class="form-control" name="with_glasses_right">
              <option>6/6</option>
              <option>6/9</option>
              <option>6/12</option>
              <option>6/18</option>
              <option>6/24</option>
              <option>6/36</option>
              <option>6/60</option>
            </select>
            </div>
            <div class="col col-4">
            <label>Left</label>
            <select class="form-control" name="with_glasses_left">
              <option>6/6</option>
              <option>6/9</option>
              <option>6/12</option>
              <option>6/18</option>
              <option>6/24</option>
              <option>6/36</option>
              <option>6/60</option>
            </select>
          </div>

          </div>

        </fieldset>
        <fieldset>
          <div class="form-group">
          <div class="col col-4">Subjective Refraction</div>
          <div class="col col-4">
          <input type="radio" name="subjective_refraction" value="Yes">Yes</div>
          <div class="col col-2">
          <input type="radio" name="subjective_refraction" value="No">No</div>

          </div>
        </fieldset>

        <fieldset>
          <header class="bg-color-green txt-color-white">Colour Blindness</header>
          <div class="form-group">
          <div class="col col-4">Right</div>
          <div class="col col-4">
          <input type="radio" name="colour_blindness_right" value="Yes">Yes</div>
          <div class="col col-4">
          <input type="radio" name="colour_blindness_right" value="No">No </div>
          </div>
          <div class="form-group">
          <div class="col col-4">Left</div>
          <div class="col col-4">
          <input type="radio" name="colour_blindness_left" value="Yes">Yes</div>
          <div class="col col-4">
          <input type="radio" name="colour_blindness_left" value="No">No </div>
          </div>
          <div class="form-group">
            <label class="col col-4">Occular Diagnosis</label>
          <input class="col-4 form-control" type="text" name="occular_diagnosis">
          </div>
        <br>
          <div class="form-group">
            <label class="col col-4">Treatment adviced/OHD</label>
          <textarea class="col-4 form-control" type="text" rows="5"  cols="100" name="eye_treatment_description"></textarea>
          </div>
           <div class="form-group">
            <label class="col col-4">Referral Made</label>
          <input type="checkbox"  type="text" name="eye_referral_made[]" value="Yes" /><label>Yes</label>
          </div>
        </fieldset>
        <fieldset>
          <fieldset>
          <header class="bg-color-green txt-color-white">Auditory Screening</header>
          <div class="form-group">
          <div class="col col-4">Right</div>
          <div class="col col-4">
          <input type="radio" name="auditory_screening_right" value="Pass">Pass</div>
          <div class="col col-4">
          <input type="radio" name="auditory_screening_right" value="Fail">Fail </div>
          </div>
          <div class="form-group">
          <div class="col col-4">Left</div>
          <div class="col col-4">
          <input type="radio" name="auditory_screening_left" value="Pass">Pass</div>
          <div class="col col-4">
          <input type="radio" name="auditory_screening_left" value="Fail">Fail </div>
          </div>
          </fieldset>
          <div class="form-group">



          <section>Speech Screening</section>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Normal" name="speech_screening[]" value="Normal">
              <i></i>Normal</label>
            <label class="checkbox">
              <input type="checkbox" id="Fluency" name="speech_screening[]" value="Fluency">
              <i></i>Fluency</label>

            </div>
            <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Deley" name="speech_screening[]" value="Deley">
              <i></i>Deley</label>


          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Misarticulation" name="speech_screening[]" value="Misarticulation">
              <i></i>Misarticulation</label>
            <label class="checkbox">
              <input type="checkbox" id="Tongue Tie" name="speech_screening[]" value="Tongue Tie">
              <i></i>Tongue Tie</label>

          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="voice" name="speech_screening[]" value="voice">
              <i></i>voice</label>
            <label class="checkbox">
              <input type="checkbox" id="Stummering" name="speech_screening[]" value="Stummering">
              <i></i>Stummering</label>

           </div>

        </div>
        <div class="form-group">
          <header>D D and Disabilty</header>
           <div class="col col-6">
            <label class="checkbox">
              <input type="checkbox" id="Language Delay" name="D D and disability[]" value="Language Delay">
              <i></i>Language Delay</label>


          </div>
          <div class="col col-6">
            <label class="checkbox">
              <input type="checkbox" id="Behaviour Disorder" name="D D and disability[]" value="Behaviour Disorder">
              <i></i>Behaviour Disorder</label>


           </div>
        </div>
        <div class="form-group">
            <label class="col col-4">Advice</label>
          <textarea class="col-4 form-control" type="text" rows="5"  cols="100" name="auditory_advice"></textarea>
          </div>
          <div class="form-group">
          <label>Refferal Made</label>
          <input type="checkbox" name="auditory_referral_made[]" value="Yes">Yes
          </div>
        </fieldset>
        <fieldset>
          <header class="bg-color-green txt-color-white">Dental Check-up</header>
          <div class="form-group">
          <div class="col col-4">Oral Hygiene</div>
          <div class="col col-2">
          <input type="radio" name="oral_hygiene" value="Good">Good</div>
          <div class="col col-2">
          <input type="radio" name="oral_hygiene" value="Fair">Fair</div>
         
          <div class="col col-2">
          <input type="radio" name="oral_hygiene" value="Poor">Poor</div>
          </div><br>
          <div class="form-group">
          <div class="col col-4">Carious Teeth</div>
          <div class="col col-4">
          <input type="radio" name="carious_teeth" value="Yes">Yes </div>
          <div class="col col-4">
          <input type="radio" name="carious_teeth" value="No">No</div>
          </div>

          <div class="form-group">
          <div class="col col-4">Fluorosis</div>
          <div class="col col-4">
          <input type="radio" name="fluorosis" value="Yes">Yes </div>
          <div class="col col-4">
          <input type="radio" name="fluorosis" value="No">No </div>
          </div>

          <div class="form-group">
          <div class="col col-4">orthodontics treatment</div>
          <div class="col col-4">
          <input type="radio" name="orthodontics_treatment" value="Yes">Yes </div>
          <div class="col col-4">
          <input type="radio" name="orthodontics_treatment" value="No">No </div>
          </div>

          <div class="form-group">
          <div class="col col-4">indication for extraction</div>
          <div class="col col-4">
          <input type="radio" name="indication_for_extraction" value="Yes">Yes</div>
          <div class="col col-4">
          <input type="radio" name="indication_for_extraction" value="No">No</div>
          </div>
          <div class="form-group">
          <div class="col col-4">Root canal Treatment</div>
          <div class="col col-4">
          <input type="radio" name="root_canal_treatment" value="Yes">Yes</div>
          <div class="col col-2">
          <input type="radio" name="root_canal_treatment" value="No">No</div>

          <div class="form-group">
          <div class="col col-4">Crowns</div>
          <div class="col col-4">
          <input type="radio" name="crowns" value="Yes">Yes</div>
          <div class="col col-2">
          <input type="radio" name="crowns" value="No">No</div>

          <div class="form-group">
          <div class="col col-4">Fixed partial denture</div>
          <div class="col col-4">
          <input type="radio" name="fixed_partial_denture" value="Yes">Yes</div>
          <div class="col col-2">
          <input type="radio" name="fixed_partial_denture" value="No">No</div>

          <div class="form-group">
          <div class="col col-4">Curettage</div>
          <div class="col col-4">
          <input type="radio" name="curettage" value="Yes">Yes</div>
          <div class="col col-2">
          <input type="radio" name="curettage" value="No">No</div>

          <div class="form-group">
          <label for="col text-area">Description :
            <textarea rows="5" cols="150" class="col-offset-1" name="dental_result"></textarea>
          </label>
        </div>
        <div class="form-group">
          <label for="text-area">Estimated Amount :
            <input type="text" name="estimated_amount">
          </label>
        </div>
        <div class="form-group">
          <label>Refferal Made</label>
          <input type="checkbox" name="dental_referral_made[]" name="Yes">Yes
          </div>
        </fieldset>
        <fieldset class="demo-switcher-1">
            <div class="panel panel-default">
            <div class="panel-heading  text-center"><strong>Attachments</strong></div>
            <div class="form-group ">


          <input type="file" id="files"  name="hs req attachments[]" style="display:none;" multiple>
            <label for="files" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
               Browse.....
           </label>
          </div>
          <br>  
           <center><button type="submit" class="btn btn-lg btn-success">Submit</button></center>
          </div>

          
        </fieldset>
        


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
<script src="<?php echo JS; ?>sweetalert.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {

<?php if($this->session->flashdata('success')): ?>

         swal({
                title: "Success!",
                text: "<?php echo $this->session->flashdata('success'); ?>",
                icon: "success",
    
          });
      
<?php endif; ?>

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

<?php
  //include footer
  include("inc/footer.php");
?>
