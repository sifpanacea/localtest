

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
  echo  form_open_multipart('bc_welfare_cc/update_screening_report',$attributes);
  ?>
            <?php foreach ($docs as $doc):?>
              
            <!-- <input type="hidden" name="doc_id" id="doc_id" placeholder="Uniqueid" value="<?php //echo $doc['doc_properties']['doc_id'];?>" readonly> -->

          <?php if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]) && isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"])):?>

                  <fieldset>
                    <div class="row">

                      <section class="col col-3">
                        <label class="input"> <i class="icon-prepend fa fa-user"></i>
                          <input type="text" name="uniqueid" id="uniqueid" placeholder="Uniqueid" value="<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];?>" readonly>
                        </label><br>
                       
                      </section>
                      <section class="col col-3">
                      <label class="input"> <i class="icon-prepend fa fa-info-circle"></i>
                          <input type="text" name="student_name" id="Student Name" placeholder="Student Name" value="<?php if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Name']) && !empty($doc['doc_data']['widget_data']['page1']['Personal Information']['Name'])):?><?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Name']?><?php else:?><?php echo "Name not available";?><?php endif;?>">
                        </label>
                        </section>
                    
                      <section>
                        <?php if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && !is_null($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && !empty($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo'])):?>             
                  <img src="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];?>"  width="100px" height="100px"class="logo_img" id="zoom_id" />
                  <input type='file' id='file' name='logo_file' class="hide logo_file" value=""/> <br>

                  <?php else: ?> 
                  <div class="logo_img_photo logo" style="background-image: url('http://www.paas.com/PaaS/bootstrap/dist/img/avatars/male.png');">
                  <h5 class="" id="click_upload"><center>Click here to upload</center></h5></div>
                  <input type='file' id='file' name='logo_file' class="hide logo_file" value=""/> 
                  <?php endif ;?> 
                      </section>
                  
                    </div>
                  </fieldset>

                  <fieldset>
                    <div class="row">
                      <section class="col col-4">
                      <label class="input">
                          <!-- <input type="text" name="helath_unique_id" id="helath_unique_id" value="<?php //echo $huniqueid;?>" readOnly="readOnly"> -->
                          <input type="text" name="school_name" id="school_name" placeholder="School Name" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'];?>">
                        </label>   
                      </section>
                      
                      <section class="col col-4">
                      <label for="district_name" class="input">
                      
                        <!-- <input type="text" name="school_name" id="school_name" value="<?php //echo $school_name;?>" readOnly="true"> -->
                        <input type="text" name="district_name" id="district_name" placeholder="District" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['District'];?>">
                      </label>
                    </section>
                    <section class="col col-4">
                      <label for="mobile_number" class="input">
                      
                        <!-- <input type="text" name="school_name" id="school_name" value="<?php //echo $school_name;?>" readOnly="true"> -->
                        <input type="text" name="mobile_number" id="mobile_number" placeholder="Mobile Number" value="<?php if(isset($doc['doc_data']['widget_data']["page1"]['Personal Information']['Mobile']['mob_num']) && !empty($doc['doc_data']['widget_data']["page1"]['Personal Information']['Mobile']['mob_num'])):?><?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Mobile']['mob_num']?><?php else:?><?php echo "Mobile Number not available";?><?php endIf;?>">
                      </label>
                    </section>
                    
                    </div>
                    <div class="row">
                    
                      
                      <section class="col col-2">
                      <label for="class" class="input">
                      
                        <!-- <input type="text" name="school_name" id="school_name" value="<?php //echo $school_name;?>" readOnly="true"> -->
                        <input type="text" name="class" placeholder="Student Class" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Class'];?>">
                      </label>
                    </section>
                      <section class="col col-3">
                        
                        <label class="input"> <i class="icon-prepend fa fa-info-circle"></i>
                          <input type="text" name="father_name" id="father_name" placeholder="Father Name" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Father Name'];?>">
                        </label><br>
                        <label class="input"> <i class="icon-prepend fa fa-calendar"></i>
                          <input type="text" name="date_of_birth" id="date_of_birth" placeholder="DOB" class="datepicker hasDatepicker" data-dateformat="dd/mm/yy" value="<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Date of Birth'];?>">
                        </label>
                      </section>
                    <section class="col col-2">
                      <label for="section" class="input">
                      
                        <!-- <input type="text" name="school_name" id="school_name" value="<?php //echo $school_name;?>" readOnly="true"> -->
                        <input type="text" name="section" placeholder="Student Section" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Section'];?>">
                      </label>
                    </section>
                     <section class="col col-4">
                      <label class="input" for="admission_no">
                          <!-- <input type="text" name="helath_unique_id" id="helath_unique_id" value="<?php //echo $huniqueid;?>" readOnly="readOnly"> -->
                          <input type="text" name="admission_no" id="admission_no" placeholder="AD NO" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['AD No'];?>">
                        </label>
                      </section>
                       <section class="col col-4">
                      <label class="input" for="date_of_exam">
                          <!-- <input type="text" name="helath_unique_id" id="helath_unique_id" value="<?php //echo $huniqueid;?>" readOnly="readOnly"> -->
                          <input type="text" name="date_of_exam" id="date_of_exam" placeholder="Date of Exam"  value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Date of Exam'];?>">
                        </label>
                      </section>
                 
                    </div>
                    
                  </fieldset>

                <?php endif; ?>
                 
                   <fieldset>
                    <div class="form-group">

                      <header class="bg-color-green txt-color-white">Physical Exam</header>
                      <section class="col col-1">
                        <label class="control-label">Height cms</label>
                         <input class="form-control" type="text" name="height" placeholder="height in cms" value="<?php if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Height cms']) ?  $doc['doc_data']['widget_data']['page3']['Physical Exam']['Height cms'] : ""); ?>"/>
                      </section>
                       <section class="col col-1">
                        <label class="control-label">Weight kgs</label>
                        <input type="text" class="form-control" name="weight" placeholder="weight in kgs" value="<?php if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs']) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs'] : ""); ?>"/>                        
                      </section>
                      <section class="col col-1">
                        <label class="control-label">BMI</label>

                        <input type="text" class="form-control" name="bmi" placeholder="bmi" value="<?php if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['BMI%']) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['BMI%']: "") ;?>"/>
                      </section>
                      <section class="col col-1">
                        <label class="control-label">Pulse</label>
                        <input type="text" class="form-control" name="pulse" placeholder="pulse" value="<?php if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['Pulse']) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['Pulse'] : "") ;?>"/>
                      </section> 
                      <section class="col col-1">
                        <label class="control-label">B P</label>
                        <input type="text" class="form-control" name="bp" placeholder="bp" value="<?php if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['B P']) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['B P']: "");?>"/>
                      </section> 
                      <section class="col col-1">
                        <label class="control-label">H B</label>
                        <input type="text" class="form-control" name="hb" placeholder="HB" value="<?php if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']['H B']) ? $doc['doc_data']['widget_data']['page3']['Physical Exam']['H B']: "");?>"/>
                      </section>
                      <section class="col col-1">
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
          <div class="col">Check the box if normal else decribe abnormalities</div><br>
          <div class="col col-3">
            
            <label class="checkbox">

              <input type="checkbox" id="" name="general_problems[]" value="Neuologic"<?php if(in_array("Neuologic", isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Neurologic</label>
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="H and N"<?php if(in_array("H and N",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>H and N</label>
              <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="ENT"<?php if(in_array("ENT",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>ENT</label>
              <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Lyphamatic"<?php if(in_array("Lyphamatic",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Lymphatic</label>

          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Heart"<?php if(in_array("Heart",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Heart</label>
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Lungs"<?php if(in_array("Lungs",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Lungs</label>
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Abdomen"<?php if(in_array("Abdomen",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Abdomen</label>
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Genetalia"<?php if(in_array("Genetalia",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Genetalia</label>
          </div>

          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="Skin"<?php if(in_array("Skin",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Skin</label>
            <label class="checkbox">
            <input type="checkbox" id="" name="general_problems[]" value="Pyrexia"<?php if(in_array("Pyrexia",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Pyrexia</label>
              <label class="checkbox">
            <input type="checkbox" id="" name="general_problems[]" value="URTI"<?php if(in_array("URTI",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>URTI</label>
            <label class="checkbox">
            <input type="checkbox" id="" name="general_problems[]" value="Injuries"<?php if(in_array("Injuries",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Injuries</label>
          </div>

          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="general_problems[]" value="UTI"<?php if(in_array("UTI",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>UTI</label>
            <label class="checkbox">
          <input type="checkbox" id="" name="general_problems[]" value="Angular Stomatities"<?php if(in_array("Angular Stomatities",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Angular Stomatities</label>
              <label class="checkbox">
          <input type="checkbox" id="" name="general_problems[]" value="Aphthous Ulcers"<?php if(in_array("Aphthous Ulcers",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Apthous Ulcer</label>
              <label class="checkbox">
          <input type="checkbox" id="" name="general_problems[]" value="Glossities"<?php if(in_array("Glossities",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Glossities</label>
              <label class="checkbox">
          <input type="checkbox" id="" name="general_problems[]" value="Pharyngitis"<?php if(in_array("Pharyngitis",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Pharyngitis</label>
          </div>

        </div>
      </fieldset>
        <fieldset>
        <div class="form-group">
          <div>Ortho</div>
         
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="ortho_problems[]" value="Neck"<?php if(in_array("Neck",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Neck</label>
              <label class="checkbox">
              <input type="checkbox" id="" name="ortho_problems[]" value="Knees"<?php if(in_array("Knees",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Knees</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="ortho_problems[]" value="Shoulders"<?php if(in_array("Shoulders",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Shoulder</label> 
            <label class="checkbox">
              <input type="checkbox" id="" name="ortho_problems[]" value="Feet"<?php if(in_array("Feet",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Feet</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="ortho_problems[]" value="Arms"<?php if(in_array("Arms",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Arms</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="ortho_problems[]" value="Hips"<?php if(in_array("Hips",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Hips</label>
          </div>

        </div>
      </fieldset>
        <fieldset>
        <p class="row col">Postural</p><br>
        <div class="form-group">

          <!-- <div class="col">Ortho</div><br> -->
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="postural_problems[]" value="No spinal Abnormality"<?php if(in_array("No spinal Abnormality",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>No spinal abnormality</label>
              <label class="checkbox">
              <input type="checkbox" id="Moderate" name="postural_problems[]" value="Moderate"<?php if(in_array("Moderate",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Moderate</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="postural_problems[]" value="Spinal Abnomality"<?php if(in_array("Spinal Abnomality",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Spainal abnormality</label> 
            <label class="checkbox">
              <input type="checkbox" id="" name="postural_problems[]" value="Referal Made"<?php if(in_array("Referal Made",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Referal Made</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Mild" name="postural_problems[]" value="Mild"<?php if(in_array("Mild",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Mild</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Marked" name="postural_problems[]" value="Marked"<?php if(in_array("Marked",isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Postural']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Marked</label>
          </div>

        </div>
        <div class="form-group">
          <label for="text-area">Description :
            <textarea rows="5" cols="150" name="general_description" class="col-offset-1"><?php isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Description']) ? $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Description']:"";?></textarea>
          </label>
        </div>
      </fieldset>
      <fieldset>
        <div class="form-group">

          <div class="col">Ortho</div><br>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="" name="defects_at_birth_problems[]" value="Neural Tube Defect"<?php if(in_array("Neural Tube Defect",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Neural Tube Defect</label>
              <label class="checkbox">
              <input type="checkbox" id="Down Syndrome" name="defects_at_birth_problems[]" value="Down Syndome"<?php if(in_array("Down Syndome",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Down Syndrome</label>
            <label class="checkbox">
              <input type="checkbox" id="Retinopathy of Prematurity" name="defects_at_birth_problems[]" value="Retinopathy of Prematurity"<?php if(in_array("Retinopathy of Prematurity",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Retinopathy of Prematurity</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Cleft lip and palate" name="defects_at_birth_problems[]" value="Cleft Lip and Palate"<?php if(in_array("Cleft Lip and Palate",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Cleft lip and palate</label> 
            <label class="checkbox">
              <input type="checkbox" id="" name="defects_at_birth_problems[]" value="Congential Cataract"<?php if(in_array("Congential Cataract",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Congenital cataract</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Talipes(clubfoot)" name="defects_at_birth_problems[]" value="Talipes Club foot"<?php if(in_array("Talipes Club foot",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Talipes(clubfoot)</label>
              <label class="checkbox">
              <input type="checkbox" id="Congenitaldeafness" name="defects_at_birth_problems[]" value="Congential Deafness"<?php if(in_array("Congential Deafness",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Congential Deafness</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="DevelopmentalDyslplasiaofHip" name="defects_at_birth_problems[]" value="Developmental Dyslpasia of Hip"<?php if(in_array("Developmental Dyslpasia of Hip",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Developmental Dyslplasia of Hip</label>
            <label class="checkbox">
              <input type="checkbox" id="Congential Heart Disease" name="defects_at_birth_problems[]" value="Congential Heart Disease"<?php if(in_array("Congential Heart Disease",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Defects at Birth']:array())) { ?> checked="checked" <?php } ?>>
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
              <input type="checkbox" id="Anemia-Mild" name="deficencies_problems[]" value="Anemia-Mild"<?php if(in_array("Anemia-Mild",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Anemia-Mild</label>
            <label class="checkbox">
              <input type="checkbox" id="anemia_moderate" name="deficencies_problems[]" value="Anemia-Moderate"<?php if(in_array("Anemia-Moderate",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Anemia-Moderate</label>
              <label class="checkbox">
              <input type="checkbox" id="anemia_severe" name="deficencies_problems[]" value="Anemia-Severe"<?php if(in_array("Anemia-Severe",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Anemia-Severe</label>
            </div>
          <div class="col col-3">
            
            <label class="checkbox">
              <input type="checkbox" id="VitaminDefinciency B-complex" name="deficencies_problems[]" value="VitaminDefinciency B-complex"<?php if(in_array("Anemia-Mild",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>VitaminDefinciency B-complex</label>
            <label class="checkbox">
              <input type="checkbox" id="Goiter" name="deficencies_problems[]" value="Goiter"<?php if(in_array("Goiter",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Goiter</label>
              <label class="checkbox">
              <input type="checkbox" id="Under weight" name="deficencies_problems[]" value="Under Weight"<?php if(in_array("Under Weight",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Under weight</label>
          </div><div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Vitamin A Definciency" name="deficencies_problems[]" value="Vitamin A Deficiency"<?php if(in_array("Vitamin A Deficiency",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Vitamin A Deficiency</label>
            <label class="checkbox">
              <input type="checkbox" id="SAM/stunting" name="deficencies_problems[]" value="SAM/Stunting"<?php if(in_array("SAM/Stunting",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>SAM/stunting</label>
              <label class="checkbox">
              <input type="checkbox" id="Normal weight" name="deficencies_problems[]" value="Normal Weight"<?php if(in_array("Normal Weight",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Normal weight</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Vitamin C definciency" name="deficencies_problems[]" value="Vitamin C Deficiency"<?php if(in_array("Vitamin C Deficiency",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Vitamin C Deficiency</label>
            <label class="checkbox">
              <input type="checkbox" id="VitaminDefinciency D" name="deficencies_problems[]" value="Vitamin D Deficiency"<?php if(in_array("Vitamin D Deficiency",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Vitamin D Deficiency</label>
            <label class="checkbox">
              <input type="checkbox" id= "Over Weight" name="deficencies_problems[]" value= "Over Weight"<?php if(in_array("Over Weight",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Over Weight</label>
            <label class="checkbox">
              <input type="checkbox" id="obese" name="deficencies_problems[]" value="Obese"<?php if(in_array("Obese",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Obese</label>

          </div>
        </div>
        <fieldset>
        <div class="form-group">

          
          <section>Childhood Diseases</section>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Cervical Lymph Adenitis" name="childhood_disease_problems[]" value="Cervical Lymph Adenitis"<?php if(in_array("Cervical Lymph Adenitis",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Cervical Lymph Adenitis</label>
            <label class="checkbox">
              <input type="checkbox" id="Acute Br.Asthama" name="childhood_disease_problems[]" value="Acute Br.Asthama"<?php if(in_array("Acute Br.Asthama",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Acute Br.Asthama</label>
              <label class="checkbox">
              <input type="checkbox" id="Epilepsy" name="childhood_disease_problems[]" value="Epilepsy"<?php if(in_array("Epilepsy",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Epilepsy</label>
            </div>
            <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="ASOM" name="childhood_disease_problems[]" value="ASOM"<?php if(in_array("ASOM",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>ASOM</label>
            <label class="checkbox">
              <input type="checkbox" id="Chronic Br.Asthma" name="childhood_disease_problems[]" value="Chronic Br.Asthma"<?php if(in_array("Chronic Br.Asthma",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Chronic Br.Asthma</label>
              <label class="checkbox">
              <input type="checkbox" id="Icterus" name="childhood_disease_problems[]" value="Icterus"<?php if(in_array("Icterus",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Icterus</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="CSOM" name="childhood_disease_problems[]" value="CSOM"<?php if(in_array("CSOM",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>CSOM</label>
            <label class="checkbox">
              <input type="checkbox" id="Hypothyrodism" name="childhood_disease_problems[]" value="Hypothyrodism"<?php if(in_array("Hypothyrodism",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Hypothyrodism</label>
              <label class="checkbox">
              <input type="checkbox" id="Hyperthyroidism" name="childhood_disease_problems[]" value="Hyperthyroidism"<?php if(in_array("Hyperthyroidism",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Hyperthyroidism</label>
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Rheumatic Heart Disease" name="childhood_disease_problems[]" value="Rheumatic Heart Disease"<?php if(in_array("Rheumatic Heart Disease",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Rheumatic Heart Disease</label>
            <label class="checkbox">
              <input type="checkbox" id="Type-I Diabeties" name="childhood_disease_problems[]" value="Type-I Diabeties"<?php if(in_array("Type-I Diabeties",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Type-I Diabeties</label>
            <label class="checkbox">  
            <input type="checkbox" id="Type-II Diabeties" name="childhood_disease_problems[]" value="Type-II Diabeties"<?php if(in_array("Type-II Diabeties",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Childhood Diseases']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Type-II Diabeties</label>
           </div>
        </div>
        </fieldset>
      
        <div class="form-group">
          <section>NAD</section>
          <div class="col col-3">
            <label class="checkbox">  
            <input type="checkbox" id="NAD" name="nad[]" value="Yes"<?php if(in_array("Yes",isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['N A D']) ? $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['N A D']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>NAD</label>
          </div>
        </div>
      </fieldset>
      <header class="bg-color-green txt-color-white">Slit Lamp Observartion</header>
        <fieldset >
          <div class="form-group">
          <div class="col col-4">Eye lids</div>
          <div class="col col-4">
          <input type="radio" name="eye_lids" value="Normal"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Eye Lids']) && ($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Eye Lids'] =="Normal")) { ?> checked="checked" <?php } ?>>Normal</div>
          <div class="col col-4">
          <input type="radio" name="eye_lids" value="Abnormal"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Eye Lids']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Eye Lids'] == "") { ?> checked="checked" <?php } ?>>Abnormal </div>
          </div>

          <div class="form-group">
          <div class="col col-4">Conjuctiva</div>
          <div class="col col-4">
          <input type="radio" name="conjuctiva" value="Normal"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Conjunctiva']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Conjunctiva'] == "Normal") { ?> checked="checked" <?php } ?>>Normal </div>
          <div class="col col-4">
          <input type="radio" name="conjuctiva" value="Abnormal"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Conjunctiva']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Conjunctiva'] == "Abnormal") { ?> checked="checked" <?php } ?>>Abnormal </div>
          </div>

          <div class="form-group">
          <div class="col col-4">Cornea</div>
          <div class="col col-4">
          <input type="radio" name="cornea" value="Normal"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Cornea']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Cornea'] == "Normal") { ?> checked="checked" <?php } ?>>Normal </div>
          <div class="col col-4">
          <input type="radio" name="cornea" value="Abnormal"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Cornea']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Cornea'] =="Abnormal") { ?> checked="checked" <?php } ?>>Abnormal </div>
          </div>

          <div class="form-group">
          <div class="col col-4">Pupil</div>
          <div class="col col-4">
          <input type="radio" name="pupil" value="Normal"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Pupil']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Pupil'] == "Normal") { ?> checked="checked" <?php } ?>>Normal </div>
          <div class="col col-4">
          <input type="radio" name="pupil" value="Abnormal"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Pupil']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Pupil'] == "Abnormal") { ?> checked="checked" <?php } ?>>Abnormal </div>
          </div>

          <div class="form-group">
          <div class="col col-4">Complaints</div>
          <div class="col col-4">
          <input type="radio" name="complaints" value="Yes"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Complaints']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Complaints'] =="Yes") { ?> checked="checked" <?php } ?>>Yes</div>
          <div class="col col-4">
          <input type="radio" name="complaints" value="No"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Complaints']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Complaints'] == "No") { ?> checked="checked" <?php } ?>>No</div>
          </div>
          <div class="form-group">
          <div class="col col-4">Wearing Spectacles</div>
          <div class="col col-4">
          <input type="radio" name="wearing_spectacles" value="Yes"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Wearing Spectacles']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Wearing Spectacles'] == "Yes")  { ?> checked="checked" <?php } ?>>Yes</div>
          <div class="col col-2">
          <input type="radio" name="wearing_spectacles" value="No"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Wearing Spectacles']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Wearing Spectacles'] == "No") { ?> checked="checked" <?php } ?>>No</div>
          <div class="col col-2">
          <input type="radio" name="wearing_spectacles" value="Broken"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Wearing Spectacles']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Wearing Spectacles'] =="Broken") { ?> checked="checked" <?php } ?>>Broken</div>
          </div>

        </fieldset>
          <fieldset>
          <div class="form-group">
          <div class="col col-4">Subjective Refraction</div>
          <div class="col col-4">
            

          <input type="radio" name="subjective_refraction" value="Yes"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Subjective Refraction']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Subjective Refraction'] == "Yes") { ?> checked="checked" <?php } ?>>Yes</div>           
          <div class="col col-2">
          <input type="radio" name="subjective_refraction" value="No"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Subjective Refraction']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Subjective Refraction'] == "No") { ?> checked="checked" <?php } ?>>No</div>
          
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
          <input type="radio" name="colour_blindness_right" value="Yes"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Right']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Right'] == "Yes") { ?> checked="checked" <?php } ?>>Yes</div>           
          <div class="col col-4">
          <input type="radio" name="colour_blindness_right" value="No"<?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Right']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Right'] == "No") { ?> checked="checked" <?php } ?>>No </div>           
          </div>
          <div class="form-group">
          <div class="col col-4">Left</div>
          <div class="col col-4">
          <input type="radio" name="colour_blindness_left" value="Yes" <?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Left']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Left'] == "Yes") { ?> checked="checked" <?php } ?>>Yes</div>           
          <div class="col col-4">
          <input type="radio" name="colour_blindness_left" value="No" <?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Left']) && $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Left'] == "No") { ?> checked="checked" <?php } ?>>No </div>           
          </div>
          <div class="form-group">

            <label class="col col-4">Ocular Diagnosis</label>
          <input class="col-4 form-control" type="text" name="ocular_diagnosis" value="<?php if(isset($doc['doc_data']['widget_data']["page7"]['Colour Blindness']['Ocular Diagnosis']) && !empty($doc['doc_data']['widget_data']["page7"]['Colour Blindness']['Ocular Diagnosis'])):?><?php echo $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Ocular Diagnosis']?><?php else:?><?php echo "";?><?php endIf;?>">            
          </div>
        <br>
          <div class="form-group">
            <label class="col col-4">Treatment adviced/OHD</label>
          <textarea class="col-4 form-control" type="text" name="eye_treatment_description"><?php isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Description']) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Description']:""; ?></textarea>            
          </div>
           <div class="form-group">
            <label class="col col-3">Referral Made</label>
          <input type="checkbox" class="col-3" type="text" name="eye_referral_made[]" value="Yes"<?php if(in_array("Yes",isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Referral Made']) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Referral Made']:array())) { ?> checked="checked" <?php } ?>/>            
          </div>
        </fieldset>
        <fieldset>
          <header class="bg-color-green txt-color-white">Auditory Screening</header>
          <div class="form-group">
          <div class="col col-4">Right</div>
          <div class="col col-4">
          <input type="radio" name="auditory_screening_right" value="Pass"<?php if(isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Right']) && $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Right'] == "Pass") { ?> checked="checked" <?php } ?>>Pass</div>           
          <div class="col col-4">
          <input type="radio" name="auditory_screening_right" value="Fail"<?php if(isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Right']) && $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Right'] == "Fail") { ?> checked="checked" <?php } ?>>Fail </div>           
          </div>
          <div class="form-group">
          <div class="col col-4">Left</div>
          <div class="col col-4">
          <input type="radio" name="auditory_screening_left" value="Pass"<?php if(isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Left']) && $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Left'] == "Pass") { ?> checked="checked" <?php } ?>>Pass</div>           
          <div class="col col-4">
          <input type="radio" name="auditory_screening_left" value="Fail"<?php if(isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Left']) && $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Left'] == "Fail") { ?> checked="checked" <?php } ?>>Fail </div>           
          </div> </fieldset>
           <fieldset>
          <div class="form-group">

          
          <section>Speech Screening</section>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Normal" name="speech_screening[]" value="Normal"<?php if(in_array("Normal",isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Normal</label>
            <label class="checkbox">
              <input type="checkbox" id="Fluency" name="speech_screening[]" value="Fluency"<?php if(in_array("Fluency",isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Fluency</label>
              
            </div>
            <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Deley" name="speech_screening[]"  value="Deley"<?php if(in_array("Deley",isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Deley</label>
            
              
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Misarticulation" name="speech_screening[]" value="Misarticulation"<?php if(in_array("Misarticulation",isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Misarticulation</label>
            <label class="checkbox">
              <input type="checkbox" id="Tongue Tie" name="speech_screening[]" value="Tongue Tie"<?php if(in_array("Tongue Tie",isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Tongue Tie</label>
              
          </div>
          <div class="col col-3">
            <label class="checkbox">
              <input type="checkbox" id="Voice" name="speech_screening[]" value="Voice"<?php if(in_array("Voice",isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>voice</label>
            <label class="checkbox">
              <input type="checkbox" id="Stammering" name="speech_screening[]" value="Stammering"<?php if(in_array("Stammering",isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) ? $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Stammering</label>
            
           </div>

        </div>
        <div class="form-group">
          <header>D D and Disabilty</header>
           <div class="col col-6">
            <label class="checkbox">
              <input type="checkbox" id="Language Delay" name="D D and disability[]" value="Language Delay"<?php if(in_array("Language Delay",isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disability']) ? $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disability']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Language Delay</label>
            
              
          </div>
          <div class="col col-6">
            <label class="checkbox">
              <input type="checkbox" id="Behaviour Disorder" name="D D and disability[]" value="Behaviour Disorder"<?php if(in_array("Behaviour Disorder",isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disability']) ? $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disability']:array())) { ?> checked="checked" <?php } ?>>
              <i></i>Behaviour Disorder</label>
            
            
           </div>
        </div>
        <div class="form-group">
            <label class="col col-4">Advice</label>
          <textarea class="col-4 form-control" name="auditory_advice"><?php isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Description']) ? $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Description']:array();?></textarea>            
          </div>
          <div class="form-group">
          <label>Refferal Made</label>
          <input type="checkbox" name="auditory_referral_made[]" value="Yes"<?php if(in_array("Yes",isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made']) ? $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made']:array())) { ?> checked="checked" <?php } ?>>Yes            
          </div>
        </fieldset>
        <fieldset>
          <header class="bg-color-green txt-color-white">Dental Check-up</header>
         <div class="form-group">
          <div class="col col-4">Oral Hygiene</div>
          <div class="col col-2">
          <input type="radio" name="oral_hygiene" value="Good"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene'] == "Good") { ?> checked="checked" <?php } ?>>Good</div>
          <div class="col col-2">
          <input type="radio" name="oral_hygiene" value="Fair"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene'] == "Fair") { ?> checked="checked" <?php } ?>>Fair</div>
         
          <div class="col col-2">
          <input type="radio" name="oral_hygiene" value="Poor"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene'] == "Poor") { ?> checked="checked" <?php } ?>>Poor</div>
          </div><br>
          <div class="form-group">
          <div class="col col-4">Carious Teeth</div>
          <div class="col col-4">
          <input type="radio" name="carious_teeth" value="Yes"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Carious Teeth']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Carious Teeth'] == "Yes") { ?> checked="checked" <?php } ?>>Yes </div>
          <div class="col col-4">
          <input type="radio" name="carious_teeth" value="No"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Carious Teeth']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Carious Teeth'] == "No") { ?> checked="checked" <?php } ?>>No</div>
          </div>

          <div class="form-group">
          <div class="col col-4">Flourosis</div>
          <div class="col col-4">
          <input type="radio" name="flourosis" value="Yes"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Flourosis']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Flourosis'] == "Yes") { ?> checked="checked" <?php } ?>>Yes </div>
          <div class="col col-4">
          <input type="radio" name="flourosis" value="No"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Flourosis']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Flourosis'] == "No") { ?> checked="checked" <?php } ?>>No </div>
          </div>

          <div class="form-group">
          <div class="col col-4">orthodontics treatment</div>
          <div class="col col-4">
          <input type="radio" name="orthodontics_treatment" value="Yes"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Orthodontic Treatment']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Orthodontic Treatment'] == "Yes") { ?> checked="checked" <?php } ?>>Yes </div>
          <div class="col col-4">
          <input type="radio" name="orthodontics_treatment" value="No"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Orthodontic Treatment']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Orthodontic Treatment'] == "No") { ?> checked="checked" <?php } ?>>No </div>
          </div>

          <div class="form-group">
          <div class="col col-4">indication for extraction</div>
          <div class="col col-4">
          <input type="radio" name="indication_for_extraction" value="Yes"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Indication for extraction']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Indication for extraction'] == "Yes") { ?> checked="checked" <?php } ?>>Yes</div>
          <div class="col col-4">
          <input type="radio" name="indication_for_extraction" value="No"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Indication for extraction']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Indication for extraction'] == "No") { ?> checked="checked" <?php } ?>>No</div>
          </div>
          <div class="form-group">
          <div class="col col-4">Root canal Treatment</div>
          <div class="col col-4">
          <input type="radio" name="root_canal_treatment" value="Yes"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Root Canal Treatment']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Root Canal Treatment'] == "Yes") { ?> checked="checked" <?php } ?>>Yes</div>
          <div class="col col-2">
          <input type="radio" name="root_canal_treatment" value="No"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Root Canal Treatment']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Root Canal Treatment'] == "No") { ?> checked="checked" <?php } ?>>No</div>

          <div class="form-group">
          <div class="col col-4">Crowns</div>
          <div class="col col-4">
          <input type="radio" name="crowns" value="Yes"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['CROWNS']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['CROWNS'] == "Yes") { ?> checked="checked" <?php } ?>>Yes</div>
          <div class="col col-2">
          <input type="radio" name="crowns" value="No"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['CROWNS']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['CROWNS'] == "No") { ?> checked="checked" <?php } ?>>No</div>

          <div class="form-group">
          <div class="col col-4">Fixed partial denture</div>
          <div class="col col-4">
          <input type="radio" name="fixed_partial_denture" value="Yes"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Fixed Partial Denture']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Fixed Partial Denture'] == "Yes") { ?> checked="checked" <?php } ?>>Yes</div>
          <div class="col col-2">
          <input type="radio" name="fixed_partial_denture" value="No"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Fixed Partial Denture']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Fixed Partial Denture'] == "No") { ?> checisset() && ked="checked" <?php } ?>>No</div>

           
          <div class="form-group">
          <div class="col col-4">Curettage</div>
          <div class="col col-4">
          <input type="radio" name="curettage" value="Yes"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Curettage']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Curettage'] == "Yes") { ?> checked="checked" <?php } ?>>Yes</div>
          <div class="col col-2">
          <input type="radio" name="curettage" value="No"<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Curettage']) && $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Curettage'] == "No") { ?> checked="checked" <?php } ?>>No</div>
          
          <div class="form-group">
          <label for="col text-area">Description :
            <textarea rows="5" cols="150" class="col-offset-1" name="dental_result"><?php isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Result']) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Result']:"" ;?></textarea>
          </label>
        </div>
        <!-- <div class="form-group">
          <label for="text-area">Estimated Amount :
            <input type="text" name="estimated_amount" value="<?php //echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Estimated Amount'];?>">
          </label>
        </div> -->
        <div class="form-group">
          <label>Refferal Made</label>
          <input type="checkbox" name="dental_referral_made[]" value="Yes"<?php if(in_array("Yes",isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Referral Made']) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Referral Made']:array())) { ?> checked="checked" <?php } ?>>Yes            
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
<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>
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