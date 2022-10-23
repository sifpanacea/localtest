<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Update EHR";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["screening_report"]["sub"]["update_screening"]["active"] = true;
include("inc/nav.php");

?>

<style>
.logo
{
  margin-left:10px;
  float:left;
 
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
  <h2>Screening Report</h2>

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
  echo  form_open_multipart('ttwreis_cc/update_screening_report',$attributes);
  ?>
            <?php foreach ($docs as $doc):?>
            
            <!-- <input type="hidden" name="doc_id" id="doc_id" placeholder="Uniqueid" value="<?php //echo $doc['doc_properties']['doc_id'];?>" readonly> -->

          <?php if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]) && isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"])):?>

                  <fieldset>
                    <div class="row">

                      <section class="col col-4">
                        <label class="input"> <i class="icon-prepend fa fa-user"></i>
                          <input type="text" name="uniqueid" id="uniqueid" placeholder="Uniqueid" value="<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];?>" readonly>
                        </label><br>
                        <label for="class" class="input">
                      <input type="text" name="class" placeholder="Student Class" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Class'];?>">
                      </label><br>
                       <label class="input">
                          
                          <input type="text" name="school_name" id="school_name" placeholder="School Name" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'];?>">
                        </label>
                        <br>
                        <label class="input"> <i class="icon-prepend fa fa-info-circle"></i>
                          <input type="text" name="father_name" id="father_name" placeholder="Father Name" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Father Name'];?>">
                        </label>
                      </section>
                      <section class="col col-4">
                      <label class="input"> <i class="icon-prepend fa fa-info-circle"></i>
                          <input type="text" name="student_name" id="Student Name" placeholder="Student Name" value="<?php if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Name']) && !empty($doc['doc_data']['widget_data']['page1']['Personal Information']['Name'])):?><?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Name']?><?php else:?><?php echo "Name not available";?><?php endif;?>">
                        </label>
                        <br>
                        <label for="section" class="input">
                      <input type="text" name="section" placeholder="Student Section" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Section'];?>">
                      </label><br>
                        <label for="district_name" class="input">
                      <input type="text" name="district_name" id="district_name" placeholder="District" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['District'];?>">
                      </label>
                      <br>
                      <label class="input"> <i class="icon-prepend fa fa-calendar"></i>
                          <input type="text" name="date_of_birth" id="date_of_birth" placeholder="DOB" class="datepicker hasDatepicker" data-dateformat="dd/mm/yy" value="<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Date of Birth'];?>">
                        </label>
                        </section>
                    
                      <section class="col col-4">
                        <?php if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && !is_null($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && !empty($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo'])):?>             
                  <img src="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];?>"  width="100px" height="100px"class="logo_img" id="zoom_id" />
                  <input type='file' id='file' name='logo_file' class="hide logo_file" value=""/> <br>

                  <?php else: ?> 
                  <div class="logo_img_photo logo" style="background-image: url('http://www.paas.com/PaaS/bootstrap/dist/img/avatars/male.png');">
                  <h5 class="" id="click_upload"><center>Click here to upload</center></h5></div>
                  <input type='file' id='file' name='logo_file' class="hide logo_file" value=""/> 
                  <?php endif ;?> 
                  <br>
                  <label for="mobile_number" class="input">
                      <input type="text" name="mobile_number" id="mobile_number" placeholder="Mobile Number" value="<?php if(isset($doc['doc_data']['widget_data']["page1"]['Personal Information']['Mobile']['mob_num']) && !empty($doc['doc_data']['widget_data']["page1"]['Personal Information']['Mobile']['mob_num'])):?><?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Mobile']['mob_num']?><?php else:?><?php echo "Mobile Number not available";?><?php endIf;?>">
                      </label>
                      <br>
                      <label class="input" for="date_of_exam">
                          
                          <input type="text" name="date_of_exam" id="date_of_exam" placeholder="Date of Exam"  value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Date of Exam'];?>">
                        </label>
                      </section>
                  
                    </div>
                  </fieldset>

                  <!--admision number <fieldset>
                    
                    <div class="row">
                    <section class="col col-4">
                      <label class="input" for="admission_no">
                          
                          <input type="text" name="admission_no" id="admission_no" placeholder="AD NO" value="<?php //echo $doc['doc_data']['widget_data']['page2']['Personal Information']['AD No'];?>">
                        </label>
                      </section>
                    </div>
                    
                  </fieldset> -->

                <?php endif; ?>
                 
                   <fieldset>
                    <div class="form-group">


                      <header class="bg-color-green txt-color-white">Physical Exam</header>
                      <section class="col col-2">
                        <label class="control-label">Height cms</label>
                        <input class="form-control" type="text" name="height" placeholder="height in cms" value="<?php echo isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Height cms']) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['Height cms'] : ''; ?>"/>
                      </section>
                       <section class="col col-2">
                        <label class="control-label">Weight kgs</label>
                        <input type="text" class="form-control" name="weight" placeholder="weight in kgs" value="<?php echo isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs']) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs'] : ''?>"/>
                      </section>
                      <section class="col col-2">
                        <label class="control-label">BMI</label>
                        <input type="text" class="form-control" name="bmi" placeholder="bmi" value="<?php echo isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['BMI%']) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['BMI%'] : '' ?>"/>
                      </section>
                      <section class="col col-2">
                        <label class="control-label">Pulse</label>
                        <input type="text" class="form-control" name="pulse" placeholder="pulse" value="<?php echo isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Pulse']) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['Pulse'] : ''?>"/>
                      </section> 
                      <section class="col col-2">
                        <label class="control-label">B P</label>
                        <input type="text" class="form-control" name="bp" placeholder="bp" value="<?php echo isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['B P']) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['B P'] : ''?>"/>
                      </section> 
                      <section class="col col-2">
                        <label class="control-label">H B</label>
                        <input type="text" class="form-control" name="hb" placeholder="pulse" value="<?php echo isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['H B']) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['H B'] : ''?>"/>
                      </section>
                      <section class="col col-2">
                        <label class="control-label">Blood Group</label>
                        <!-- <input type="text" class="form-control" name="height" placeholder="height"/> -->
                        <select class="form-control" name="blood_group">
                          <option value=""></option>
                          <option value="A+"<?php echo  preset_select('Blood Group', 'A+', (isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'])) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'] : ''  ) ?>>A+</option>

                          <option value="B+"<?php echo  preset_select('Blood Group', 'B+', (isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'])) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'] : ''  ) ?>>B+</option>

                          <option value="AB+"<?php echo  preset_select('Blood Group', 'AB+', (isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'])) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'] : ''  ) ?>>AB+</option>

                          <option value="O+"<?php echo  preset_select('Blood Group', 'O+', (isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'])) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'] : ''  ) ?>>O+</option>

                          <option value="A-"<?php echo  preset_select('Blood Group', 'A-', (isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'])) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'] : ''  ) ?>>A-</option>

                          <option value="B-"<?php echo  preset_select('Blood Group', 'B-', (isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'])) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'] : ''  ) ?>>B-</option>

                          <option value="AB-"<?php echo  preset_select('Blood Group', 'AB-', (isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'])) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'] : ''  ) ?>>AB-</option>

                          <option value="O-"<?php echo  preset_select('Blood Group', 'O-', (isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'])) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'] : ''  ) ?>>O-</option>
                        </select>
                      </section>

                      
                    </div>
                 </fieldset>
    <fieldset>

      <header class="bg-color-green txt-color-white">Doctor Check Up</header>
         <div class="form-group"> 
          <div class="col">Check the box if normal else describe abnormalities</div><br>
          <div class="col col-3">
            
            <label class="checkbox">
            
              <input type="checkbox"  id="" name="general_problems[]" value="Neuologic" <?php echo set_checkbox("general_problems","Neuologic", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("Neuologic", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?> >
              <i></i>Neurologic</label>
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="H and N" <?php echo set_checkbox("general_problems","H and N", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("H and N", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?> >
              <i></i>H and N</label>
              <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="ENT"<?php echo set_checkbox("general_problems","ENT", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("ENT", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?> >
              <i></i>ENT</label>
              <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Lyphamatic" <?php echo set_checkbox("general_problems","Lyphamatic", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("Lyphamatic", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?> >
              <i></i>Lymphatic</label>

          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox"type="checkbox" id="" name="general_problems[]" value="Heart" <?php echo set_checkbox("general_problems","Heart", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("Heart", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?> >
              <i></i>Heart</label>
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Lungs" <?php echo set_checkbox("general_problems","Lungs", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("Lungs", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?> >
              <i></i>Lungs</label>
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Abdomen" <?php echo set_checkbox("general_problems","Abdomen", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("Abdomen", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?> >
              <i></i>Abdomen</label>
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Genetalia" <?php echo set_checkbox("general_problems","Genetalia", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("Genetalia", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?> >
              <i></i>Genetalia</label>
          </div>

          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Skin" <?php echo set_checkbox("general_problems","Skin", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("Skin", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?>> 
              <i></i>Skin</label>
            <label class="checkbox">
            <input type="checkbox" id="" name="general_problems[]" value="Pyrexia" <?php echo set_checkbox("general_problems","Pyrexia", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("Pyrexia", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?>> 
              <i></i>Pyrexia</label>
              <label class="checkbox">
            <input type="checkbox" id="" name="general_problems[]" value="URTI" <?php echo set_checkbox("general_problems","URTI", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("URTI", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?>> 
              <i></i>URTI</label>
            <label class="checkbox">
            <input type="checkbox" id="" name="general_problems[]" value="Injuries" <?php echo set_checkbox("general_problems","Injuries", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("Injuries", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?>> 
              <i></i>Injuries</label>
          </div>

          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="UTI" <?php echo set_checkbox("general_problems","UTI", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("UTI", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?>>
              <i></i>UTI</label>
            <label class="checkbox">
          <input type="checkbox" id="" name="general_problems[]" value="Angular Stomatities" <?php echo set_checkbox("general_problems","Angular Stomatities", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("Angular Stomatities", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?>>
              <i></i>Angular Stomatities</label>
              <label class="checkbox">
          <input type="checkbox" id="" name="general_problems[]" value="Aphthous Ulcers" <?php echo set_checkbox("general_problems","Aphthous Ulcers", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("Aphthous Ulcers", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?>>
              <i></i>Apthous Ulcer</label>
              <label class="checkbox">
          <input type="checkbox" id="" name="general_problems[]" value="Glossities" <?php echo set_checkbox("general_problems","Glossities", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("Glossities", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?>>
              <i></i>Glossities</label>
              <label class="checkbox">
          <input type="checkbox" id="" name="general_problems[]" value="Pharyngitis" <?php echo set_checkbox("general_problems","Pharyngitis", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? in_array("Pharyngitis", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : "" ); ?>>
              <i></i>Pharyngitis</label>
          </div>

        </div>
      </fieldset>
        <fieldset>
        <div class="form-group">
          <div>Ortho</div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" name="ortho_problems[]" value="Neck" <?php echo set_checkbox("ortho_problems","Neck", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) ? in_array("Neck", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) : "" ); ?>>
              <i></i>Neck</label>
              <label class="checkbox">
              <input  type="checkbox" name="ortho_problems[]" value="Knees" <?php echo set_checkbox("ortho_problems","Knees", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) ? in_array("Knees", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) : "" ); ?>>
              <i></i>Knees</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input  type="checkbox" name="ortho_problems[]" value="Shoulders"<?php echo set_checkbox("ortho_problems","Shoulders", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) ? in_array("Shoulders", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) : "" ); ?>>
              <i></i>Shoulders</label> 
            <label class="checkbox">
              <input  type="checkbox"  name="ortho_problems[]" value="Feet"<?php echo set_checkbox("ortho_problems","Feet", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) ? in_array("Feet", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) : "" ); ?>>
              <i></i>Feet</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input  type="checkbox"  name="ortho_problems[]" value="Arms"<?php echo set_checkbox("ortho_problems","Arms", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) ? in_array("Arms", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) : "" ); ?>>
              <i></i>Arms</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input  type="checkbox" name="ortho_problems[]" value="Hips"<?php echo set_checkbox("ortho_problems","Hips", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) ? in_array("Hips", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) : "" ); ?>>
              <i></i>Hips</label>
          </div>

        </div>
      </fieldset>
        <fieldset>
        <p class="row col">Postural</p><br>
        <div class="form-group">

         
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox"  id="" name="postural_problems[]" value="No spinal Abnormality" <?php echo set_checkbox("postural_problems","No spinal Abnormality", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) ? in_array("No spinal Abnormality", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) : "" ); ?> >
              <i></i>No spinal abnormality</label>
              <label class="checkbox">
              <input type="checkbox"  id="Moderate" name="postural_problems[]" value="Moderate" <?php echo set_checkbox("postural_problems","Moderate", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) ? in_array("Moderate", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) : "" ); ?> >
              <i></i>Moderate</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox"  id="" name="postural_problems[]" value="Spinal Abnomality" <?php echo set_checkbox("postural_problems","Spinal Abnomality", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) ? in_array("Spinal Abnomality", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) : "" ); ?> >
              <i></i>Spainal abnormality</label> 
            <label class="checkbox">
              <input type="checkbox"  id="" name="postural_problems[]" value="Referal Made" <?php echo set_checkbox("postural_problems","Referal Made", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) ? in_array("Referal Made", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) : "" ); ?> >
              <i></i>Referal Made</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox"  id="Mild" name="postural_problems[]" value="Mild" <?php echo set_checkbox("postural_problems","Mild", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) ? in_array("Mild", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) : "" ); ?> >
              <i></i>Mild</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox"  id="Marked" name="postural_problems[]" value="Marked" <?php echo set_checkbox("postural_problems","Marked", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) ? in_array("Marked", $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) : "" ); ?> >
              <i></i>Marked</label>
          </div>

        </div>
        <div class="form-group">

          <label for="text-area">Description :
            <textarea rows="5" cols="150" name="general_description" class="col-offset-1"><?php if(isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Description'])): ?>
                 
              <?php echo $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Description'];?>
                <?php endif; ?></textarea>
          </label>
        </div>
      </fieldset>
      <fieldset>
        <div class="form-group">

          <div class="col">Ortho</div><br>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox"  id="" name="defects_at_birth_problems[]" value="Neural Tube Defect" <?php echo set_checkbox("defects_at_birth_problems","Neural Tube Defect", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? in_array("Neural Tube Defect", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) : "" ); ?> >
              <i></i>Neural Tube Defect</label>
              <label class="checkbox">
              <input type="checkbox"  id="Down Syndrome" name="defects_at_birth_problems[]" value="Down Syndome" <?php echo set_checkbox("defects_at_birth_problems","Down Syndome", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? in_array("Down Syndome", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) : "" ); ?> >
              <i></i>Down Syndrome</label>
            <label class="checkbox">
              <input type="checkbox"  id="Retinopathy of Prematurity" name="defects_at_birth_problems[]" value="Retinopathy of Prematurity" <?php echo set_checkbox("defects_at_birth_problems","Retinopathy of Prematurity", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? in_array("Retinopathy of Prematurity", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) : "" ); ?> >
              <i></i>Retinopathy of Prematurity</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox"  id="Cleft lip and palate" name="defects_at_birth_problems[]" value="Cleft Lip and Palate" <?php echo set_checkbox("defects_at_birth_problems","Cleft Lip and Palate", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? in_array("Cleft Lip and Palate", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) : "" ); ?> >
              <i></i>Cleft lip and palate</label> 
            <label class="checkbox">
              <input type="checkbox"  id="" name="defects_at_birth_problems[]" value="Congential Cataract" <?php echo set_checkbox("defects_at_birth_problems","Congential Cataract", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? in_array("Congential Cataract", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) : "" ); ?> >
              <i></i>Congenital cataract</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox"  id="Talipes(clubfoot)" name="defects_at_birth_problems[]" value="Talipes Club foot" <?php echo set_checkbox("defects_at_birth_problems","Talipes Club foot", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? in_array("Talipes Club foot", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) : "" ); ?> >
              <i></i>Talipes(clubfoot)</label>
              <label class="checkbox">
              <input type="checkbox"  id="Congenitaldeafness" name="defects_at_birth_problems[]" value="Congential Deafness" <?php echo set_checkbox("defects_at_birth_problems","Congential Deafness", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? in_array("Congential Deafness", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) : "" ); ?> >
              <i></i>Congential Deafness</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox"  id="DevelopmentalDyslplasiaofHip" name="defects_at_birth_problems[]" value="Developmental Dyslpasia of Hip" <?php echo set_checkbox("defects_at_birth_problems","Developmental Dyslpasia of Hip", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? in_array("Developmental Dyslpasia of Hip", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) : "" ); ?> >
              <i></i>Developmental Dyslplasia of Hip</label>
            <label class="checkbox">
              <input type="checkbox"  id="Congential Heart Disease" name="defects_at_birth_problems[]" value="Congential Heart Disease" <?php echo set_checkbox("defects_at_birth_problems","Congential Heart Disease", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? in_array("Congential Heart Disease", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) : "" ); ?> >
              <i></i>Congenital Heart Disease</label>
          </div>

        </div>
      </fieldset>
      <fieldset>
        <header class="bg-color-green txt-color-white">Deficencies</header>
        <div class="form-group">
          <section>Anemia</section>
          <div class="col col-3">
            <label class="checkbox">
              <input type = "checkbox"  id="Anemia-Mild" name="deficencies_problems[]" value="Anemia-Mild"  <?php echo set_checkbox("deficencies_problems","Anemia-Mild", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? in_array("Anemia-Mild", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) : "" ); ?> >
              <i></i>Anemia-Mild</label>
            <label class="checkbox">
              <input type = "checkbox"  id="anemia_moderate" name="deficencies_problems[]" value="Anemia-Moderate"  <?php echo set_checkbox("deficencies_problems","Anemia-Moderate", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? in_array("Anemia-Moderate", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) : "" ); ?> >
              <i></i>Anemia-Moderate</label>
              <label class="checkbox">
              <input type = "checkbox"  id="anemia_severe" name="deficencies_problems[]" value="Anemia-Severe"  <?php echo set_checkbox("deficencies_problems","Anemia-Severe", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? in_array("Anemia-Severe", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) : "" ); ?> >
              <i></i>Anemia-Severe</label>
            </div>
          <div class="col col-3">
            
            <label class="checkbox">
              <input type = "checkbox"  id="VitaminDefinciency B-complex" name="deficencies_problems[]" value="VitaminDefinciency B-complex"  <?php echo set_checkbox("deficencies_problems","VitaminDefinciency B-complex", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? in_array("VitaminDefinciency B-complex", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) : "" ); ?> >
              <i></i>VitaminDefinciency B-complex</label>
            <label class="checkbox">
              <input type = "checkbox"  id="Goiter" name="deficencies_problems[]" value="Goiter"  <?php echo set_checkbox("deficencies_problems","Goiter", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? in_array("Goiter", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) : "" ); ?> >
              <i></i>Goiter</label>
              <label class="checkbox">
              <input type = "checkbox"  id="Under weight" name="deficencies_problems[]" value="Under Weight"  <?php echo set_checkbox("deficencies_problems","Under Weight", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? in_array("Under Weight", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) : "" ); ?> >
              <i></i>Under weight</label>
          </div><div class="col col-3">
            <label class="checkbox">
              <input type = "checkbox"  id="Vitamin A Definciency" name="deficencies_problems[]" value="Vitamin A Deficiency"  <?php echo set_checkbox("deficencies_problems","Vitamin A Deficiency", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? in_array("Vitamin A Deficiency", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) : "" ); ?> >
              <i></i>Vitamin A Deficiency</label>
            <label class="checkbox">
              <input type = "checkbox"  id="SAM/stunting" name="deficencies_problems[]" value="SAM/Stunting"  <?php echo set_checkbox("deficencies_problems","SAM/Stunting", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? in_array("SAM/Stunting", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) : "" ); ?> >
              <i></i>SAM/stunting</label>
              <label class="checkbox">
              <input type = "checkbox"  id="Normal weight" name="deficencies_problems[]" value="Normal Weight"  <?php echo set_checkbox("deficencies_problems","Normal Weight", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? in_array("Normal Weight", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) : "" ); ?> >
              <i></i>Normal weight</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type = "checkbox"  id="Vitamin C definciency" name="deficencies_problems[]" value="Vitamin C Deficiency"  <?php echo set_checkbox("deficencies_problems","Vitamin C Deficiency", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? in_array("Vitamin C Deficiency", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) : "" ); ?> >
              <i></i>Vitamin C Deficiency</label>
            <label class="checkbox">
              <input type = "checkbox"  id="VitaminDefinciency D" name="deficencies_problems[]" value="Vitamin D Deficiency"  <?php echo set_checkbox("deficencies_problems","Vitamin D Deficiency", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? in_array("Vitamin D Deficiency", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) : "" ); ?> >
              <i></i>Vitamin D Deficiency</label>
            <label class="checkbox">
              <input type = "checkbox"  id= "Over Weight" name="deficencies_problems[]" value= "Over Weight"  <?php echo set_checkbox("deficencies_problems","Over Weight", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? in_array("Over Weight", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) : "" ); ?> >
              <i></i>Over Weight</label>
            <label class="checkbox">
              <input type = "checkbox"  id="obese" name="deficencies_problems[]" value="Obese"  <?php echo set_checkbox("deficencies_problems","Obese", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? in_array("Obese", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) : "" ); ?> >
              <i></i>Obese</label>

          </div>
        </div>
        <fieldset>
        <div class="form-group">

          
          <section>Childhood Diseases</section>
          <div class="col col-3">
            <label class="checkbox">
              <input  type="checkbox" id="Cervical Lymph Adenitis" name="childhood_disease_problems[]" value="Cervical Lymph Adenitis"   <?php echo set_checkbox("childhood_disease_problems","Cervical Lymph Adenitis", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? in_array("Cervical Lymph Adenitis", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) : "" ); ?> >
              <i></i>Cervical Lymph Adenitis</label>
            <label class="checkbox">
              <input  type="checkbox" id="Acute Br.Asthama" name="childhood_disease_problems[]" value="Acute Br.Asthama"  <?php echo set_checkbox("childhood_disease_problems","Acute Br.Asthama", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? in_array("Acute Br.Asthama", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) : "" ); ?> >
              <i></i>Acute Br.Asthama</label>
              <label class="checkbox">
              <input  type="checkbox" id="Epilepsy" name="childhood_disease_problems[]"   value="Epilepsy" <?php echo set_checkbox("childhood_disease_problems","Epilepsy", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? in_array("Epilepsy", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) : "" ); ?> >
              <i></i>Epilepsy</label>
            </div>
            <div class="col col-3">
            <label class="checkbox">
              <input  type="checkbox" id="ASOM" name="childhood_disease_problems[]"   value="ASOM" <?php echo set_checkbox("childhood_disease_problems","ASOM", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? in_array("ASOM", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) : "" ); ?> >
              <i></i>ASOM</label>
            <label class="checkbox">
              <input  type="checkbox" id="Chronic Br.Asthma" name="childhood_disease_problems[]"   value="Chronic Br.Asthma" <?php echo set_checkbox("childhood_disease_problems","Chronic Br.Asthma", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? in_array("Chronic Br.Asthma", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) : "" ); ?> >
              <i></i>Chronic Br.Asthma</label>
              <label class="checkbox">
              <input  type="checkbox" id="Icterus" name="childhood_disease_problems[]"   value="Icterus" <?php echo set_checkbox("childhood_disease_problems","Icterus", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? in_array("Icterus", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) : "" ); ?> >
              <i></i>Icterus</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input  type="checkbox" id="CSOM" name="childhood_disease_problems[]"   value="CSOM" <?php echo set_checkbox("childhood_disease_problems","CSOM", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? in_array("CSOM", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) : "" ); ?> >
              <i></i>CSOM</label>
            <label class="checkbox">
              <input  type="checkbox" id="Hypothyrodism" name="childhood_disease_problems[]"   value="Hypothyrodism" <?php echo set_checkbox("childhood_disease_problems","Hypothyrodism", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? in_array("Hypothyrodism", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) : "" ); ?> >
              <i></i>Hypothyrodism</label>
              <label class="checkbox">
              <input  type="checkbox" id="Hyperthyroidism" name="childhood_disease_problems[]"   value="Hyperthyroidism" <?php echo set_checkbox("childhood_disease_problems","Hyperthyroidism", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? in_array("Hyperthyroidism", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) : "" ); ?> >
              <i></i>Hyperthyroidism</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input  type="checkbox" id="Rheumatic Heart Disease" name="childhood_disease_problems[]"   value="Rheumatic Heart Disease" <?php echo set_checkbox("childhood_disease_problems","Rheumatic Heart Disease", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? in_array("Rheumatic Heart Disease", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) : "" ); ?> >
              <i></i>Rheumatic Heart Disease</label>
            <label class="checkbox">
              <input  type="checkbox" id="Type-I Diabeties" name="childhood_disease_problems[]"   value="Type-I Diabeties" <?php echo set_checkbox("childhood_disease_problems","Type-I Diabeties", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? in_array("Type-I Diabeties", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) : "" ); ?> >
              <i></i>Type-I Diabeties</label>
            <label class="checkbox">  
            <input  type="checkbox" id="Type-II Diabeties" name="childhood_disease_problems[]" value="Type-II Diabeties" <?php echo set_checkbox("childhood_disease_problems","Type-II Diabeties", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? in_array("Type-II Diabeties", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) : "" ); ?> >
              <i></i>Type-II Diabeties</label>
           </div>
        </div>
        </fieldset>
      
        <div class="form-group">

          <section>NAD</section>
          <div class="col col-3">
            <label class="checkbox">  
            <input type="checkbox" id="NAD" name="nad[]" value="Yes"<?php echo set_checkbox("nad","Yes", isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['N A D']) ? in_array("Yes", $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['N A D']) : "" ); ?>>
              <i></i>NAD</label>
          </div>
        </div>
      </fieldset>
      <header class="bg-color-green txt-color-white">Slit Lamp Observartion</header>
        <fieldset >
          <div class="form-group">
          <div class="col col-4">Eye lids</div>
          <div class="col col-4">
            
          <input class="icheck_radio" type='radio' name="eye_lids" value="Normal" <?php echo preset_radio('eye_lids','Normal',(isset($doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Eye Lids'])) ? $doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Eye Lids'] : "" );?>><label class="normal" for="normal"> Normal </label>
            </div>
          <div class="col col-4">
          <input type="radio" name="eye_lids" value="Abnormal" <?php echo preset_radio('eye_lids','Abnormal', (isset($doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Eye Lids'])) ? $doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Eye Lids'] : ""); ?>>
          <label class="abnormal" for="abnormal">Abnormal </label> 
          </div>
          </div>

          <div class="form-group">
          <div class="col col-4">Conjuctiva</div>
          <div class="col col-4">
          <input type="radio" name="conjuctiva" value="Normal"<?php echo preset_radio('conjuctiva','Normal', (isset($doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Conjunctiva'])) ? $doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Conjunctiva'] : ""); ?>>Normal </div>
          <div class="col col-4">
          <input type="radio" name="conjuctiva" value="Abnormal"<?php echo preset_radio('conjuctiva','Abnormal', (isset($doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Conjunctiva'])) ? $doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Conjunctiva'] : ""); ?>>Abnormal </div>
          </div>

          <div class="form-group">
          <div class="col col-4">Cornea</div>
          <div class="col col-4">
          <input type="radio" name="cornea" value="Normal" <?php echo preset_radio('cornea','Normal', (isset($doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Cornea'])) ? $doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Cornea'] : ""); ?>>Normal </div>
          <div class="col col-4">
          <input type="radio" name="cornea" value="Abnormal"<?php echo preset_radio('cornea','Abnormal', (isset($doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Cornea'])) ? $doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Cornea'] : ""); ?>>Abnormal </div>
          </div>


          <div class="form-group">
          <div class="col col-4">Pupil</div>
          <div class="col col-4">
          <input type="radio"  name="pupil" value="Normal" <?php echo preset_radio('pupil','Normal', (isset($doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Pupil'])) ? $doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Pupil'] : ""); ?>>Normal </div>
         <div class="col col-4">
          <input type="radio" name="pupil" value="Abnormal" <?php echo preset_radio('pupil','Abnormal', (isset($doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Pupil'])) ? $doc['doc_data']['widget_data']['page6']['Slit Lamp Observartion']['Pupil'] : ""); ?>>Abnormal </div>
          </div>

          <div class="form-group">
          <div class="col col-4">Complaints</div>
          <div class="col col-4">
          <input type="radio" name="complaints" value="Yes" <?php echo preset_radio('complaints','Yes', (isset($doc['doc_data']['widget_data']['page7']['Slit Lamp Observartion']['Complaints'])) ? $doc['doc_data']['widget_data']['page7']['Slit Lamp Observartion']['Complaints'] : ""); ?>>Yes</div>
          <div class="col col-4">
          <input type="radio" name="complaints" value="No" <?php echo preset_radio('complaints','No', (isset($doc['doc_data']['widget_data']['page7']['Slit Lamp Observartion']['Complaints'])) ? $doc['doc_data']['widget_data']['page7']['Slit Lamp Observartion']['Complaints'] : ""); ?> >No</div>
          </div>
          <div class="form-group">
          <div class="col col-4">Wearing Spectacles</div>
          <div class="col col-4">
          <input type="radio" name="wearing_spectacles" value="Yes" <?php echo preset_radio('wearing_spectacles','Yes', (isset($doc['doc_data']['widget_data']['page7']['Slit Lamp Observartion']['Wearing Spectacles'])) ? $doc['doc_data']['widget_data']['page7']['Slit Lamp Observartion']['Wearing Spectacles'] : ""); ?>>Yes</div>
          <div class="col col-2">
          <input type="radio" name="wearing_spectacles" value="No" <?php echo preset_radio('wearing_spectacles','No', (isset($doc['doc_data']['widget_data']['page7']['Slit Lamp Observartion']['Wearing Spectacles'])) ? $doc['doc_data']['widget_data']['page7']['Slit Lamp Observartion']['Wearing Spectacles'] : ""); ?> >No</div>
          <div class="col col-2">
          <input type="radio" name="wearing_spectacles" value="Broken" <?php echo preset_radio('wearing_spectacles','Broken', (isset($doc['doc_data']['widget_data']['page7']['Slit Lamp Observartion']['Wearing Spectacles'])) ? $doc['doc_data']['widget_data']['page7']['Slit Lamp Observartion']['Wearing Spectacles'] : ""); ?>>Broken</div>
          </div>

        </fieldset>
          <fieldset>
          <div class="form-group">
          <div class="col col-4">Subjective Refraction</div>
          <div class="col col-4">
            

          <input type="radio" name="subjective_refraction" value="Yes"<?php echo preset_radio('subjective_refraction','Yes', (isset($doc['doc_data']['widget_data']['page7']['Slit Lamp Observartion']['Subjective Refraction'])) ? $doc['doc_data']['widget_data']['page7']['Slit Lamp Observartion']['Subjective Refraction'] : ""); ?>>Yes</div>           
          <div class="col col-2">
          <input type="radio" name="subjective_refraction" value="No" <?php echo preset_radio('subjective_refraction','No', (isset($doc['doc_data']['widget_data']['page7']['Slit Lamp Observartion']['Subjective Refraction'])) ? $doc['doc_data']['widget_data']['page7']['Slit Lamp Observartion']['Subjective Refraction'] : ""); ?> >No</div>
          
          </div>
        </fieldset>

        <fieldset>
                <header class="bg-color-green txt-color-white">Without Glasses</header>
          <div class="form-group">
            <div class="col col-4">
            <label>Right</label>
            <select class="form-control" name="without_glasses_right">
              <option value="6/6"<?php echo  preset_select('Right', '6/6', (isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'] : ''  ) ?>>6/6</option>

              <option value="6/9"<?php echo  preset_select('Right', '6/9', (isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'] : ''  ) ?>>6/9</option>

              <option value="6/12"<?php echo  preset_select('Right', '6/12', (isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'] : ''  ) ?>>6/12</option>

              <option value="6/18"<?php echo  preset_select('Right', '6/18', (isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'] : ''  ) ?>>6/18</option>

              <option value="6/24"<?php echo  preset_select('Right', '6/24', (isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'] : ''  ) ?>>6/24</option>

              <option value="6/36"<?php echo  preset_select('Right', '6/36', (isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'] : ''  ) ?>>6/36</option>

              <option value="6/60"<?php echo  preset_select('Right', '6/60', (isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'] : ''  ) ?>>6/60</option>
            </select>
            </div>
            <div class="col col-4">
            <label>Left</label>
            <select class="form-control" name="without_glasses_left">
              <option value="6/6"<?php echo  preset_select('Left', '6/6', (isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'] : ''  ) ?>>6/6</option>

              <option value="6/9"<?php echo  preset_select('Left', '6/9', (isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'] : ''  ) ?>>6/9</option>

              <option value="6/12"<?php echo  preset_select('Left', '6/12', (isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'] : ''  ) ?>>6/12</option>

              <option value="6/18"<?php echo  preset_select('Left', '6/18', (isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'] : ''  ) ?>>6/18</option>

              <option value="6/24"<?php echo  preset_select('Left', '6/24', (isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'] : ''  ) ?>>6/24</option>

              <option value="6/36"<?php echo  preset_select('Left', '6/36', (isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'] : ''  ) ?>>6/36</option>

              <option value="6/60"<?php echo  preset_select('Left', '6/60', (isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'] : ''  ) ?>>6/60</option>
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
             <option value="6/6"<?php echo  preset_select('Right', '6/6', (isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Right'])) ? $doc['doc_data']['widget_data']['page6']['With Glasses']['Right'] : ''  ) ?>>6/6</option>

              <option value="6/9"<?php echo  preset_select('Right', '6/9', (isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Right'])) ? $doc['doc_data']['widget_data']['page6']['With Glasses']['Right'] : ''  ) ?>>6/9</option>

              <option value="6/12"<?php echo  preset_select('Right', '6/12', (isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Right'])) ? $doc['doc_data']['widget_data']['page6']['With Glasses']['Right'] : ''  ) ?>>6/12</option>

              <option value="6/18"<?php echo  preset_select('Right', '6/18', (isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Right'])) ? $doc['doc_data']['widget_data']['page6']['With Glasses']['Right'] : ''  ) ?>>6/18</option>

              <option value="6/24"<?php echo  preset_select('Right', '6/24', (isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Right'])) ? $doc['doc_data']['widget_data']['page6']['With Glasses']['Right'] : ''  ) ?>>6/24</option>

              <option value="6/36"<?php echo  preset_select('Right', '6/36', (isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Right'])) ? $doc['doc_data']['widget_data']['page6']['With Glasses']['Right'] : ''  ) ?>>6/36</option>

              <option value="6/60"<?php echo  preset_select('Right', '6/60', (isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Right'])) ? $doc['doc_data']['widget_data']['page6']['With Glasses']['Right'] : ''  ) ?>>6/60</option>
            </select>
            </div>
            <div class="col col-4">
            <label>Left</label>
            <select class="form-control" name="with_glasses_left">
             <option value="6/6"<?php echo  preset_select('Left', '6/6', (isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Left'])) ? $doc['doc_data']['widget_data']['page6']['With Glasses']['Left'] : ''  ) ?>>6/6</option>

              <option value="6/9"<?php echo  preset_select('Left', '6/9', (isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Left'])) ? $doc['doc_data']['widget_data']['page6']['With Glasses']['Left'] : ''  ) ?>>6/9</option>

              <option value="6/12"<?php echo  preset_select('Left', '6/12', (isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Left'])) ? $doc['doc_data']['widget_data']['page6']['With Glasses']['Left'] : ''  ) ?>>6/12</option>

              <option value="6/18"<?php echo  preset_select('Left', '6/18', (isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Left'])) ? $doc['doc_data']['widget_data']['page6']['With Glasses']['Left'] : ''  ) ?>>6/18</option>

              <option value="6/24"<?php echo  preset_select('Left', '6/24', (isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Left'])) ? $doc['doc_data']['widget_data']['page6']['With Glasses']['Left'] : ''  ) ?>>6/24</option>

              <option value="6/36"<?php echo  preset_select('Left', '6/36', (isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Left'])) ? $doc['doc_data']['widget_data']['page6']['With Glasses']['Left'] : ''  ) ?>>6/36</option>

              <option value="6/60"<?php echo  preset_select('Left', '6/60', (isset($doc['doc_data']['widget_data']['page6']['With Glasses']['Left'])) ? $doc['doc_data']['widget_data']['page6']['With Glasses']['Left'] : ''  ) ?>>6/60</option>
            </select>
          </div>
          
          </div>
          
        </fieldset>


        <fieldset>
          <header class="bg-color-green txt-color-white">Colour Blindness</header>
          <div class="form-group">
          <div class="col col-4">Right</div>
          <div class="col col-4">
          <input type="radio" name="colour_blindness_right" value="Yes"<?php echo preset_radio('colour_blindness_right','Yes', (isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Right'])) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Right'] : ""); ?>>Yes</div>           
          <div class="col col-4">
          <input type="radio" name="colour_blindness_right" value="No"<?php echo preset_radio('colour_blindness_right','No', (isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Right'])) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Right'] : ""); ?>>No </div>           
          </div>
          <div class="form-group">
          <div class="col col-4">Left</div>
          <div class="col col-4">
          <input type="radio" name="colour_blindness_left" value="Yes" <?php echo preset_radio('colour_blindness_left','Yes', (isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Left'])) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Left'] : ""); ?>>Yes</div>           
          <div class="col col-4">
          <input type="radio" name="colour_blindness_left" value="No" <?php echo preset_radio('colour_blindness_left','No', (isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Left'])) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Left'] : ""); ?>>No </div>           
          </div>
          <div class="form-group">

            <label class="col col-4">Ocular Diagnosis</label>
          <input class="col-4 form-control" type="text" name="ocular_diagnosis" value="<?php if(isset($doc['doc_data']['widget_data']["page7"]['Colour Blindness']['Ocular Diagnosis']) && !empty($doc['doc_data']['widget_data']["page7"]['Colour Blindness']['Ocular Diagnosis'])):?><?php echo $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Ocular Diagnosis']?><?php else:?><?php echo ''; ?><?php endif;?>">            
          </div>
        <br>
          <div class="form-group">
            <label class="col col-4">Treatment adviced/OHD</label>
          <textarea class="col-4 form-control" type="text" name="eye_treatment_description">
           <?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Description'])): ?>
                 
              <?php echo $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Description'];?>
                <?php endif; ?>
          </textarea>            
          </div>
           <div class="form-group">
             <div class="col col-4">
            <label class="col col-3">Referral Made</label>
             <div class="col col-4">
          <input type="radio" class="col-3" name="eye_referral_made[]" value="Yes"<?php echo preset_radio("eye_referral_made","Yes", isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Referral Made']) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Referral Made']  : "" ); ?>> Yes       
          </div>
          <div class="col col-4">
          <input type="radio" class="col-3" name="eye_referral_made[]" value="No"<?php echo preset_radio("eye_referral_made","No", isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Referral Made']) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Referral Made']  : "" ); ?>> No       
          </div>
        </div>
          </div>  
        </fieldset>
        <fieldset>
          <header class="bg-color-green txt-color-white">Auditory Screening</header>
          <div class="form-group">
          <div class="col col-4">Right</div>
          <div class="col col-4">
          <input type="radio" name="auditory_screening_right" value="Pass" <?php echo preset_radio('auditory_screening_right','Pass', (isset($doc['doc_data']['widget_data']['page8']['Auditory Screening']['Right'])) ? $doc['doc_data']['widget_data']['page8']['Auditory Screening']['Right'] : ""); ?>>Pass</div>           
          <div class="col col-4">
          <input type="radio" name="auditory_screening_right" value="Fail" <?php echo preset_radio('auditory_screening_right','Fail', (isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Right'])) ? $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Right'] : ""); ?> >Fail </div>           
          </div>
          <div class="form-group">
          <div class="col col-4">Left</div>
          <div class="col col-4">
          <input type="radio" name="auditory_screening_left" value="Pass"<?php echo preset_radio('auditory_screening_left','Pass', (isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Left'])) ? $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Left'] : ""); ?>>Pass</div>           
          <div class="col col-4">
          <input type="radio" name="auditory_screening_left" value="Fail" <?php echo preset_radio('auditory_screening_left','Fail', (isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Left'])) ? $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Left'] : ""); ?> >Fail </div>           
          </div> </fieldset>
           <fieldset>
          <div class="form-group">

          
          <section>Speech Screening</section>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox"  id="Normal" name="speech_screening[]" value="Normal"<?php echo set_checkbox("speech_screening","Normal", isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? in_array("Normal", $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) : "" ); ?>  > 
              <i></i>Normal</label>
            <label class="checkbox">
              <input type="checkbox"  id="Fluency" name="speech_screening[]" value="Fluency"<?php echo set_checkbox("speech_screening","Fluency", isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? in_array("Fluency", $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) : "" ); ?>  > 
              <i></i>Fluency</label>
              
            </div>
            <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox"  id="Deley" name="speech_screening[]"  value="Deley"<?php echo set_checkbox("speech_screening","Deley", isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? in_array("Deley", $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) : "" ); ?>  > 
              <i></i>Deley</label>
            
              
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox"  id="Misarticulation" name="speech_screening[]" value="Misarticulation"<?php echo set_checkbox("speech_screening","Misarticulation", isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? in_array("Misarticulation", $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) : "" ); ?>  > 
              <i></i>Misarticulation</label>
            <label class="checkbox">
              <input type="checkbox"  id="Tongue Tie" name="speech_screening[]" value="Tongue Tie" <?php echo set_checkbox("speech_screening","Tongue Tie", isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? in_array("Tongue Tie", $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) : "" ); ?>  > 
              <i></i>Tongue Tie</label>
              
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox"  id="Voice" name="speech_screening[]" value="Voice" <?php echo set_checkbox("speech_screening","Voice", isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? in_array("Voice", $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) : "" ); ?>  > 
              <i></i>voice</label>
            <label class="checkbox">
              <input type="checkbox"  id="Stammering" name="speech_screening[]" value="Stammering" <?php echo set_checkbox("speech_screening","Stammering", isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? in_array("Stammering", $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) : "" ); ?>  > 
              <i></i>Stammering</label>
            
           </div>

        </div>
        <div class="form-group">
          <header>D D and Disabilty</header>
           <div class="col col-6">
            <label class="checkbox">
              <input type="checkbox"  id="Language Delay" name="D D and disability[]" value="Language Delay" <?php echo set_checkbox("speech_screening","Language Delay", isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? in_array("Language Delay", $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) : "" ); ?>  > 
              <i></i>Language Delay</label>
            
              
          </div>
          <div class="col col-6">
            <label class="checkbox">
              <input type="checkbox"  id="Behaviour Disorder" name="D D and disability[]" value="Behaviour Disorder"<?php echo set_checkbox("speech_screening","Behaviour Disorder", isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? in_array("Behaviour Disorder", $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) : "" ); ?>  > 
              <i></i>Behaviour Disorder</label>
            
            
           </div>
        </div>
        <div class="form-group">
            <label class="col col-4">Advice</label>
          <textarea class="col-4 form-control" name="auditory_advice">
            <?php if(isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Description'])): ?>
                 
              <?php echo $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Description'];?>
                <?php endif; ?>
          </textarea>            
          </div>
          <div class="form-group">
          <label>Refferal Made</label>
          <input type="checkbox"  name="auditory_referral_made[]" value="Yes" <?php echo set_checkbox("speech_screening","Yes", isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? in_array("Yes", $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) : "" ); ?>  >          </div>
        </fieldset>
        <fieldset>
          <header class="bg-color-green txt-color-white">Dental Check-up</header>
         <div class="form-group">
          <div class="col col-4">Oral Hygiene</div>
          <div class="col col-2">
          <input type="radio" name="oral_hygiene" value="Good" <?php echo preset_radio('oral_hygiene','Good', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene'] : ""); ?> >Good</div>
          <div class="col col-2">
          <input type="radio" name="oral_hygiene" value="Fair" <?php echo preset_radio('oral_hygiene','Fair', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene'] : ""); ?> >Fair</div>
         
          <div class="col col-2">
          <input type="radio" name="oral_hygiene" value="Poor" <?php echo preset_radio('oral_hygiene','Poor', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene'] : ""); ?>>Poor</div>
          </div><br>
          <div class="form-group">
          <div class="col col-4">Carious Teeth</div>
          <div class="col col-4">
          <input type="radio" name="carious_teeth" value="Yes"<?php echo preset_radio('carious_teeth','Yes', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Carious Teeth'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Carious Teeth'] : ""); ?>>Yes </div>
          <div class="col col-4">
          <input type="radio" name="carious_teeth" value="No" <?php echo preset_radio('carious_teeth','No', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Carious Teeth'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Carious Teeth'] : ""); ?> >No</div>
          </div>

          <div class="form-group">
          <div class="col col-4">Flourosis</div>
          <div class="col col-4">
          <input type="radio" name="flourosis" value="Yes"<?php echo preset_radio('flourosis','Yes', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Flourosis'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Flourosis'] : ""); ?>>Yes </div>
          <div class="col col-4">
          <input type="radio" name="flourosis" value="No" <?php echo preset_radio('flourosis','No', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Flourosis'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Flourosis'] : ""); ?>>No </div>
          </div>

          <div class="form-group">
          <div class="col col-4">orthodontics treatment</div>
          <div class="col col-4">
          <input type="radio" name="orthodontics_treatment" value="Yes" <?php echo preset_radio('orthodontics_treatment','Yes', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Orthodontic Treatment'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Orthodontic Treatment'] : ""); ?>>Yes </div>
          <div class="col col-4">
          <input type="radio" name="orthodontics_treatment" value="No"<?php echo preset_radio('orthodontics_treatment','No', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Orthodontic Treatment'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Orthodontic Treatment'] : ""); ?>>No </div>
          </div>

          <div class="form-group">
          <div class="col col-4">indication for extraction</div>
          <div class="col col-4">
          <input type="radio" name="indication_for_extraction" value="Yes"<?php echo preset_radio('indication_for_extraction','Yes', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Indication for extraction'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Indication for extraction'] : ""); ?>>Yes</div>
          <div class="col col-4">
          <input type="radio" name="indication_for_extraction" value="No" <?php echo preset_radio('indication_for_extraction','No', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Indication for extraction'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Indication for extraction'] : ""); ?>>No</div>
          </div>
          <div class="form-group">
          <div class="col col-4">Root canal Treatment</div>
          <div class="col col-4">
          <input type="radio" name="root_canal_treatment" value="Yes"<?php echo preset_radio('root_canal_treatment','Yes', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['root_canal_treatment'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['root_canal_treatment'] : ""); ?>>Yes</div>
          <div class="col col-2">
          <input type="radio" name="root_canal_treatment" value="No"<?php echo preset_radio('root_canal_treatment','No', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['root_canal_treatment'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['root_canal_treatment'] : ""); ?>>No</div>

          <div class="form-group">
          <div class="col col-4">Crowns</div>
          <div class="col col-4">
          <input type="radio" name="crowns" value="Yes"<?php echo preset_radio('crowns','Yes', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['CROWNS'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['CROWNS'] : ""); ?>>Yes</div>
          <div class="col col-2">
          <input type="radio" name="crowns" value="No"<?php echo preset_radio('crowns','No', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['CROWNS'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['CROWNS'] : ""); ?>>No</div>

          <div class="form-group">
          <div class="col col-4">Fixed partial denture</div>
          <div class="col col-4">
          <input type="radio" name="fixed_partial_denture" value="Yes"<?php echo preset_radio('fixed_partial_denture','Yes', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Fixed Partial Denture'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Fixed Partial Denture'] : ""); ?>>Yes</div>
          <div class="col col-2">
          <input type="radio" name="fixed_partial_denture" value="No"<?php echo preset_radio('fixed_partial_denture','No', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Fixed Partial Denture'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Fixed Partial Denture'] : ""); ?>>No</div>

           
          <div class="form-group">
          <div class="col col-4">Curettage</div>
          <div class="col col-4">
          <input type="radio" name="curettage" value="Yes"<?php echo preset_radio('curettage','Yes', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Curettage'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Curettage'] : ""); ?>>Yes</div>
          <div class="col col-2">
          <input type="radio" name="curettage" value="No"<?php echo preset_radio('curettage','No', (isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Curettage'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Curettage'] : ""); ?>>No</div>
          
          <div class="form-group">
          <label for="col text-area">Description :
            <textarea rows="5" cols="150" class="col-offset-1" name="dental_result">
              <?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Result'])): ?>
                 
              <?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Result'];?>
                <?php endif; ?>
              </textarea>

          </label>
        </div>
        <!-- <div class="form-group">
          <label for="text-area">Estimated Amount :
            <input type="text" name="estimated_amount" value="<?php //echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Estimated Amount'];?>">
          </label>
        </div> -->
        <div class="form-group">
          <label>Refferal Made</label>
          <input type="checkbox" name="dental_referral_made[]" value="Yes"
          <?php echo set_checkbox("dental_referral_made","Yes", isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Referral Made']) ? in_array("Yes", $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Referral Made']) : "" ); ?>
          
          >Yes            
          </div>

          <div class="form-group">
            <fieldset class="demo-switcher-1">
              <div class="panel panel-default">
              <div class="panel-heading  text-center"><strong>Attachments</strong></div>
              <div class="form-group ">
                <input type="file" id="files"  name="external_attachments[]" style="display:none;" multiple>
                  <label for="files" class="btn btn-primary btn-lg" style="margin-left: 100px; width: -webkit-fill-available; margin-right: 100px;">
                     Browse.....
                 </label>
                </div>
              </div>
            </fieldset>
          </div>

  <!-- MEF Attchments -->
        <div class="form-group">
          <header class="bg-color-green txt-color-white">MEF Attachments</header>
          <br>
          <input type="file" id="mef_files" name="mef_files[]" multiple>
        </div>


        <center><button type="submit" class="btn btn-lg btn-success">Update</button></center>     
        </fieldset>
       
      <?php endforeach; ?>

                  
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
<script type="text/javascript">
  
$(document).ready(function (){

  // START AND FINISH DATE


  $(document).on('click','.logo',function()
  {
    $('.logo_file').trigger("click");
  }); 

  function readURL(input) {
        if (input.files && input.files[0]) {
      //alert("success")
            var reader = new FileReader();
      
            reader.onload = function (e) {      
        $('.logo_img').attr('src', e.target.result);
        $('.logo_img_photo').css("background-image","url("+e.target.result+")");
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
    
});
  
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
   
})
  
</script>

<?php 
  //include footer
  include("inc/footer.php"); 
?>