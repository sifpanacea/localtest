<?php $current_page=""; ?>
<?php $main_nav="" ;?>
<?php
include('inc/header_bar.php');
?>
<br>
<br>
<br>
<br>
<br>

<section class="">
<div class="container-fluid">
    <div class="block-header">
        <h2>Electronic Health Record</h2>
    </div>
    <!-- Input -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">

                    <button type="button" class="btn bg-pink waves-effect" data-toggle="tooltip" data-placement="top" title="Back" onclick="window.history.back();"><i class="material-icons">arrow_back</i></button>

                    <button type="button" class="btn bg-green waves-effect" data-toggle="tooltip" data-placement="top" title="Print" id="submit_print_request"><i class="material-icons">print</i></button>

                    <!-- <button class="btn bg-deep-purple waves-effect pull-right" data-toggle="tooltip" data-placement="top" title="Edit EHR"><i class="material-icons">mode_edit</i></button> -->

                </div>
                
                <div class="body">
                    <?php if ($docs): ?><!-- This is start for (if) total Screening -->

                    <?php foreach($docs as $doc): ?><!-- This is start for (foreach) total Screening -->

                    <h2 class="card-inside-title">Personal Information</h2>

                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        	<div class="row">
                        		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                <div class="form-group">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Student Health ID" id="" value="<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];?>" readonly/>
	                                    </div>
	                                </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                <div class="form-group">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Class" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Class']; ?>" readonly />
	                                    </div>
	                                </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Section" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Section'];?>" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Mobile number" value="<?php echo(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Mobile']['mob_num'])) ? $doc['doc_data']['widget_data']['page1']['Personal Information']['Mobile']['mob_num'] : "" ;?>" readonly />
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        	<div class="row">
                        		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                <div class="form-group">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Student Name" value="<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Name'];?>" readonly />
	                                    </div>
	                                </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                <div class="form-group">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="School Name" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'];?>" readonly/>
	                                    </div>
	                                </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                <div class="form-group">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Gender" value="<?php echo(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Gender'])) ? $doc['doc_data']['widget_data']['page1']['Personal Information']['Gender'] : "" ;?>" readonly/>
	                                    </div>
	                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        	<div class="row">
                        		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                <div class="form-group">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-palcement="bottom" title="Father Name" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Father Name'];
                                             ?>" readonly/>
	                                    </div>
	                                </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                <div class="form-group">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Date of Birth" value="<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Date of Birth']; ?>" readonly />
	                                    </div>
	                                </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                <div class="form-group">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Date of Exam" value="<?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Date of Exam'];?>" readonly/>
	                                    </div>
	                                </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <td rowspan="4"><center>
                                <p>Photo</p>
                            <?php if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && !is_null($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && !empty($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'])):?>
                            <!-- <a href="<?php //echo URLCustomer.$doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];?>" rel="prettyPhoto"> -->
                            <a data-magnify="gallery" data-src="" data-caption="Profile pic" data-group="a" href="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];?>">
                                <img src="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];?>" style="height: 170px;width: 180px; border: 4px solid darkgrey;"/>
                            
                            </a>
                            <?php else: ?>
                            <?php echo "No Photo uploaded";?><?php endif ?>
                            </center>
                            </td>
                         <!-- <?php //if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path']) && !empty($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path']) && !is_null($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'])): ?>

                            <div id="student_image">
                               <?php ///if($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'] == "No Image Found")  : ?>
                                    <a href="#">
                                        <img class="img-rounded" src="<?php //echo IMG; ?>/demo/abchospitals.png" alt="Photo Not Available">
                                    </a>
                                    <?php //echo "No Photo uploaded";?>
                                <?php //else: ?>
                                    <a href="<?php //echo URLcustomer.$doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path']; ?>" data-magnify="gallery" data-src="" data-caption="Profile Pic" data-group="a">

                                        <img src="<?php //echo URLcustomer.$doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path']; ?>" alt="Student Photo">
                                    </a>
                               <?php //endif; ?> 
                            </div>

                        <?php //endif; ?> -->
                        </div>
                    </div>
                   


                    <h2 class="card-inside-title">Student Screening information</h2>
                    <div class="row clearfix">
                    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    		<ul class="nav nav-tabs tab-nav-right" role="tablist">
                    			
                    			<li role="presentation" class="active"><a href="#student_screening_info" data-toggle="tab" aria-expanded="true"><button class="btn bg-pink waves-effect" type="button">Screening Info</button></a></li>

                                <li role="presentation"><a href="#request_info" data-toggle="tab" ><button class="btn bg-cyan waves-effect" type="button">Request Info</button></a></li>

                                <li role="presentation"><a href="#student_hb_info" data-toggle="tab" ><button class="btn bg-red waves-effect" type="button">HB Info</button></a></li>

                                <li role="presentation"><a href="#student_bmi_info" data-toggle="tab" ><button class="btn bg-green waves-effect" type="button">BMI Info</button></a></li>
                                
                                <!-- <li role="presentation"><a href="#regular_followup" data-toggle="tab" ><button class="btn bg-orange waves-effect" type="button">Feild Officers</button></a></li> -->

                                <li role="presentation"><a href="#calling_info" data-toggle="tab" ><button class="btn bg-teal waves-effect" type="button">Doctor Report</button></a></li>

                                <li role="presentation"><a href="#hs_details" data-toggle="tab" ><button class="btn bg-deep-purple waves-effect" type="button">HS details</button></a></li>

                        	</ul>
                            
                    	</div>

<!-- <li role="presentation" class="active"><a href="#home" data-toggle="tab" aria-expanded="true"><button class="btn bg-green btn-lg btn-block waves-effect" type="button">General <span class="badge">3</span></button></a></li> -->
<!-- ================================================================================================================ -->

<!-- SCREENING INFORMATION -->    	
<div class="tab-content">
<div id="student_screening_info" role="tabpanel" class="tab-pane fade in active in active">
   <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        <li role="presentation" class="active"><a href="#physical_animation" data-toggle="tab">Physical Exam</a></li>
                        <li role="presentation"><a href="#doctor_animation" data-toggle="tab">Doctor Check Up</a></li>
                        <li role="presentation"><a href="#vision_animation" data-toggle="tab">Vision Screening</a></li>
                        <li role="presentation"><a href="#auditory_animation" data-toggle="tab">Auditory Screening</a></li>
                        <li role="presentation"><a href="#dental_animation" data-toggle="tab">Dental Check Up</a></li>
                        <li role="presentation"><a href="#othermedicalattach_animation" data-toggle="tab">Other Medical Attachments</a></li>
                    </ul>
                </div>

<div class="tab-content">

<!-- PHYSICAL EXAMINATION SECTION --> 
    
    <?php if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"])):?>
        <div id="physical_animation" role="tabpanel" class="tab-pane animated fadeInRight active">
        <div class="body table-responsive">
            <table class="table table-striped" id="">

                <!-- <?php //if(isset($doc['doc_data']['widget_data']['page3']['Physical Exam']) && !empty($doc['doc_data']['widget_data']['page3']['Physical Exam'])): ?> -->
               
                    <tbody>
                        <tr>
                            <th>Height cms</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['Height cms'];?></i></td>
                            <th>Weight kgs</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs']; ?></i></td>
                        </tr>
                        <tr>
                            <th>BMI%</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['BMI%']; ?></i></td>
                            <th>Pulse</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['Pulse']; ?></i></td>
                        </tr>
                        <tr>
                            <th>H B</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['H B']; ?></i></td>
                            <th>B P</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['B P']; ?></i></td>
                        </tr>
                        <tr>
                            <th>Blood Group</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group']; ?></i></td>
                            <!-- <th>SPO2</th><td><i class="icon-leaf"><?php //echo $doc['']['']['']['']['']; ?></i></td> -->
                        </tr>
                        <tr>
                            <!-- <th>Ni Gluc</th><td><i class="icon-leaf"><?php //echo $doc['']['']['']['']['']; ?></i></td>
                            <th>H R</th><td><i class="icon-leaf"><?php //echo $doc['']['']['']['']['']; ?></i></td> -->
                        </tr>
                    </tbody>
               
            </table>
        </div>
    </div>
 <?php endif; ?>
<!-- End if for physical examination -->


<!-- ===========DOCTOR CHECKUP SECTION================= -->

    <?php if (isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']) && isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up'])): ?>

        <div id="doctor_animation" role="tabpanel" class="tab-pane animated fadeInRight">
            <p>
            <table id="" class="table table-striped table-bordered table-hover">
                <tbody>
                    <tr>
                        <th>Abnormalities</th>
                        <td><i class="icon-leaf">
                            <?php if(isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) && !empty($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'])) :?> 
                           <?php echo (gettype($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'];?><?php endif; ?> </i></td>
                        <th>Ortho</th><td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho']) : $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Ortho'];?></i></td>
                    </tr>
                    
                    <tr>
                        <th>Description</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Description'];?></i></td>
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
                        <th>Deficencies</th><td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies']) : $doc['doc_data']['widget_data']['page5']['Doctor Check Up']['Deficencies'];?></i></td>
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
            </p>    
        </div>

    <?php endif; ?>


<!--===================END OF DOCTOR CHECK UP SECTION =================--> 
    
    
<!-- VISION SCREENING SECTION -->
<?php if(isset($doc['doc_data']['widget_data']['page6']['With Glasses']) && isset($doc['doc_data']['widget_data']['page6']['Without Glasses']) && isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']) ): ?>

    <div id="vision_animation" role="tabpanel" class="tab-pane animated fadeInRight">
        <!-- <b>Vision Screening</b> -->
        <p>
        <table id="" class="table table-striped table-bordered table-hover">
        <tbody>
            <tr>
                <th rowspan="2">Without Glasses</th>
                <th>Right</th>
                <td><i class="icon-leaf"><?php echo (isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'] : ""?></i></td>
                <th rowspan="2">
                <th>Right</th>
                <td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page6']['With Glasses']['Right'];?></i></td>
            </tr>
            <tr>
                <th>Left</th>
                <td><i class="icon-leaf"></i><?php echo(isset($doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'])) ? $doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'] : "" ?></td>
                <th>Left</th>
                <td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page6']['With Glasses']['Left'];?></i></td>
            </tr>
            <tr>
                <th>Colour Blindness</th><!--<th>Right</th>-->
                <td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Right'];?></i></td>
                <th>Description</th>
                <td colspan="2"><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Description'];?></i></td>
            </tr>
            <tr>
                <!--<th>Left</th><td><i class="icon-leaf"><?php// echo $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Left'];?></i></td>-->
                <th rowspan="2">Slit Lamp Examination</th>
                <th>Conjunctiva</th>
                <td><i class="icon-leaf"><?php echo (isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Conjunctiva'])) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Conjunctiva'] : "" ;?></i></td>
                <th>Eye Lids</th>
                <td><i class="icon-leaf"><?php echo (isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Eye Lids'])) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Eye Lids'] : "" ;?></i></td>
            </tr>                                   
            <tr>
                <th>Cornea</th>
                <td><i class="icon-leaf"><?php echo (isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Cornea'])) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Cornea'] : "" ;?></i></td>
                <th>Pupil</th>
                <td><i class="icon-leaf"><?php echo (isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Pupil'])) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Pupil'] : "" ;?></i></td>
            </tr>
            <tr>                                        
                <th>Complaints</th>
                <td><i class="icon-leaf"><?php echo (isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Complaints'])) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Complaints'] : "" ;?></i></td>
                <th colspan="2">Wearing Spectacles</th>
                <td><i class="icon-leaf"><?php echo (isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Wearing Spectacles'])) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Wearing Spectacles'] : "" ;?></i></td>
            </tr>
            <tr>    
                <th>Subjective Refraction</th>
                <td><i class="icon-leaf"><?php echo (isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Subjective Refraction'])) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Subjective Refraction'] : "" ;?></i></td>
                <th colspan="2">Ocular Diagnosis</th>
                <td><i class="icon-leaf"><?php echo (isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Ocular Diagnosis'])) ? $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Ocular Diagnosis'] : "" ;?></i></td>
            </tr>
            <tr>
                <th>Referral Made</th>
                <td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Referral Made'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page7']['Colour Blindness']['Referral Made']) : $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Referral Made'];?></i></td>
            </tr>
            <tr>
            <th class="hidden">Opthomologist Sign</th>
            <td class="hidden"><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Opthomologist Sign']) && !is_null($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Opthomologist Sign']) && !empty($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Opthomologist Sign'])) : ?>
                <img src="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page7']['Colour Blindness']['Opthomologist Sign']['file_path'];?>" height="100" width="180" /> <?php else :?> <?php echo "Opthomologist Sign Not Available";?> <?php endIf;?></i></td>
            </tr>
        </tbody>
        </table>
        </p>
        <div class="demo-checkbox">
            <input type="checkbox" id="myopia_id" class="filled-in chk-col-red" name="" value="Myopia">
            <label for="myopia_id">Myopia</label>

            <input type="checkbox" id="hyperopia_id" class="filled-in chk-col-red" name="" value="Hyperopia">
            <label for="hyperopia_id">Hyperopia</label>

             <input type="checkbox" id="leftsquint_id" class="filled-in chk-col-red" name="" value="Left Squnit">
            <label for="leftsquint_id">Left Eye Squint</label>

            <input type="checkbox" id="rightsquint_id" class="filled-in chk-col-red" name="" value="Right Squnit">
            <label for="rightsquint_id">Right Eye Squint</label>

             <input type="checkbox" id="leftred_id" class="filled-in chk-col-red" name="" value="Redness of Eye Left">
            <label for="leftred_id">Left Red Eye</label>

            <input type="checkbox" id="rightred_id" class="filled-in chk-col-red" name="" value="Redness of Eye Right">
            <label for="rightred_id">Right Red Eye</label>

             <input type="checkbox" id="forgin_id" class="filled-in chk-col-red" name="" value="Forign Boady">
            <label for="forgin_id">forgin Boady</label>

            <input type="checkbox" id="trauma_id" class="filled-in chk-col-red" name="" value="Trauma">
            <label for="trauma_id">Trauma</label>

            <input type="checkbox" id="stye_id" class="filled-in chk-col-red" name="" value="Stye">
            <label for="stye_id">Stye</label>

             <input type="checkbox" id="chalazion_id" class="filled-in chk-col-red" name="" value="Chalazion">
            <label for="chalazion_id">Chalazion</label>

            <input type="checkbox" id="blepharitis_id" class="filled-in chk-col-red" name="" value="Blepharitis">
            <label for="blepharitis_id">Blepharitis</label>

            <input type="checkbox" id="pterizium_id" class="filled-in chk-col-red" name="" value="Pterizium">
            <label for="pterizium_id">Pterizium</label>

            <input type="checkbox" id="bitotsnb_id" class="filled-in chk-col-red" name="" value="Yes">
            <label for="bitotsnb_id">Bitot Sports/Night Blindness</label>
        </div>
    </div>

<?php endif; ?>

<!-- AUDITORY SCREENING SECTION -->

<?php if(isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening'])):?>
            
    <div id="auditory_animation" role="tabpanel" class="tab-pane animated fadeInRight">
        <!-- <b>AUDITORY SCREENING</b> -->
        <p>
        <table id="" class="table table-striped table-bordered table-hover">
            <tbody>
            <tr>
                <th rowspan="2">Auditory Screening</th>
                <th>Right</th>
                <td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Right'];?></i></td>
                <th>Speech Screening</th>
                <td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) : $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening'];?></i></td>
            </tr>
            <tr>
                <th>Left</th>
                <td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Left'];?></i></td>
                <th>D D and disability</th>
                <td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disability'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disability']) : $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disability'];?></i></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Description'];?></i></td>
                <th>Referral Made</th>
                <td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made']) : $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made'];?></i></td>
            </tr>
        </tbody>
    </table>
    </p>
    </div>

<?php endif; ?>


<!-- DENTAL CHECKUP SECTION --> 
<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up'])): ?>
      
    <div id="dental_animation" role="tabpanel" class="tab-pane animated fadeInRight">
       <!--  <b>DENTAL CHECK UP</b> -->
        <p>
    <table id="" class="table table-striped table-bordered table-hover">
        <tbody>
            <tr>
                <th>Oral Hygiene</th>
                <td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene']; ?></i></td>
                <th>Carious Teeth</th>
                <td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Carious Teeth']; ?></i></td>
            </tr>
            <tr>
                <th>Flourosis</th>
                <td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Flourosis']; ?></i></td>
                <th>Orthodontic Treatment</th>
                <td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Orthodontic Treatment']; ?></i></td>
            </tr>
            <tr>
                <th>Indication for extraction</th>
                <td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Indication for extraction']; ?></i></td>
                <th>Root Canal Treatment</th>
                <td><i class="icon-leaf"><?php echo (isset( $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Root Canal Treatment'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Root Canal Treatment'] : "";?></i></td>
            </tr>
            <tr>
                <th>Curettage</th>
                <td><i class="icon-leaf"><?php echo (isset( $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Curettage'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Curettage'] : "";?></i></td>
                <th>Estimated Amount</th>
                <td><i class="icon-leaf"><?php echo (isset( $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Estimated Amount'])) ? $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Estimated Amount'] : "";?></i></td>
            </tr>
            <tr>
                <th>Result</th>
                <td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Result'];?></i></td>
                <th>Referral Made</th>
                <td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made']) : $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made'];?></i></td>
            </tr>
        </tbody>
    </table>
    </p>
    </div>

<?php endif; ?>

<!-- OTHER MEDICAL ATTACHMENTS SECTION -->

 <?php if(isset($doc['doc_data']['external_attachments']) && !is_null($doc['doc_data']['external_attachments']) && !empty($doc['doc_data']['external_attachments'])): ?>  
    <?php foreach ($doc['doc_data']['external_attachments'] as $attachment):?>
        <div id="othermedicalattach_animation" role="tabpanel" class="tab-pane animated fadeInRight">
            <p>Attachment</p>
            <img src="<?php echo URLCustomer.$attachment['file_path'];?>" width="500" height="600"/>
        </div>
    <?php endforeach;?>   
<?php endif; ?>      

<!-- -------------End of other medical attachments---------------- -->
<!-- <?php //endif; ?> --><!-- End if for physical examination -->

    </div>

   
    </div>
 </div>
</div>


</div> 
<!-- =============================END OF SCREENING INFORMATION SECTION=============================================== -->



<!-- =====================================REQUEST INFORMATION START============================================ -->


<div id="request_info" role="tabpanel" class="tab-pane fade">
<div id="bdiv">
    <!-- <h2 class="card-inside-title">Requests Information</h2> -->
<div class="row clearfix">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs tab-nav-right" role="tablist">
           <?php if(isset($docs_requests) && count($docs_requests) > 0):?>
           <li role="presentation" class="active"><a href="#requests_sick" data-toggle="tab">Sick Requests<span class="badge bg-pink"> <?php echo count($docs_requests)?> </span></a></li>
           <?php endif; ?>
           <!--  <li role="presentation"><a href="#hbmonth_animation_2" data-toggle="tab">HB</a></li>
           <li role="presentation"><a href="#bmimonth_animation_2" data-toggle="tab">BMI</a></li> -->
        </ul>

    <!-- Tab panes -->
<div class="tab-content">
<?php if(isset($docs_requests) && count($docs_requests) > 0):?>

<div role="tabpanel" class="tab-pane animated fadeInRight active" id="requests_sick">

    <?php $requestCount = 1; ?>
    <?php foreach ($docs_requests as $request):?>
<p>
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
                    <th colspan=2 ><h4 style="color: green;">Problem Information</h4></th>
                </tr>
                <tr>
                    <th>Problem Information</th>
                    <?php if(isset($request['doc_data']['widget_data']['page1']['Problem Info']['Normal'])):?>
                    
                        <?php $identifiers_normal = (isset($request['doc_data']['widget_data']['page1']['Problem Info']['Normal']))? ($request['doc_data']['widget_data']['page1']['Problem Info']['Normal']) : ""; ?>

                        <?php $identifiers_emergency = (isset($request['doc_data']['widget_data']['page1']['Problem Info']['Emergency'])) ? ($request['doc_data']['widget_data']['page1']['Problem Info']['Emergency']) : "" ;?>

                        <?php $identifiers_chronic = (isset($request['doc_data']['widget_data']['page1']['Problem Info']['Chronic'])) ? ($request['doc_data']['widget_data']['page1']['Problem Info']['Chronic']) : "";?>
                      
                    <td>
                        <?php if(isset($identifiers_normal) && !empty($identifiers_normal)) : 
                                       foreach ($identifiers_normal as $identifier => $values) :?>

                            <?php $var123 = implode (", ",$request['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]); ?> 

                            <?php if(!empty($var123)):?> 

                            <?php echo (gettype($request['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier])=="array")? implode (", ",$request['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]) : "No Identifier";?>

                        <?php endif; endforeach; endif;?>   

                        <?php if(isset($identifiers_emergency) && !empty($identifiers_emergency)) : foreach($identifiers_emergency as $identifier => $values) : ?> 

                            <?php $var123 = implode("," , $request['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]) ?>

                            <?php if(!empty($var123)) : ?> 

                            <?php echo (gettype($request['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]) =="array")? implode("," , $request['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]) : "No Identifier"; ?>

                        <?php endif; endforeach; endif; ?>

                        <?php if(isset($identifiers_chronic) && !empty($identifiers_chronic)) : foreach($identifiers_chronic as $identifier => $values) : ?>

                            <?php $var123 = implode("," , $request['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier])?>

                            <?php if(!empty($var123)) :?> 

                            <?php echo (gettype($request['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier] ) =="array") ? implode(",",$request['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]) : "No Identifier"; ?>
                            
                        <?php endif; endforeach; endif; ?>        

                    </td>
                    <?php else :?>
                    <td><i class="icon-leaf">
                        <?php echo (gettype($request['doc_data']['widget_data']['page1']['Problem Info']['Identifier'])=="array")? implode (", ",$request['doc_data']['widget_data']['page1']['Problem Info']['Identifier']) : $request['doc_data']['widget_data']['page1']['Problem Info']['Identifier'];?></i>
                    </td>
                    <?php endif;?>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><i class="icon-leaf"><?php echo $request['doc_data']['widget_data']['page2']['Problem Info']['Description'];?></i></td>
                </tr>
                <tr>
                    <th colspan=2 ><h4 style="color: green;">Diagnosis Information</h4></th>
                </tr>
                <tr>
                    <th>Doctor Summary</th>
                    <td><i class="icon-leaf"><?php echo $request['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Summary'];?></i></td>
                </tr>
                <tr>
                    <th>Doctor's Advice</th>
                    <td><i class="icon-leaf"></i><?php echo $request['doc_data']['widget_data']['page2']['Diagnosis Info']['Doctor Advice'];?></td>
                </tr>
                <tr>
                    <th>Prescription</th>
                    <td><i class="icon-leaf"><?php echo $request['doc_data']['widget_data']['page2']['Diagnosis Info']['Prescription'];?></i></td>
                </tr>

            </tbody>
        </table>
        <table id="" class="table table-striped table-bordered table-hover">
            <tbody>
                <tr>
                    <th>Doctor's Name</th>
                    <td><i class="icon-leaf"></i><?php echo(isset($request['history']["1"]['submitted_by_name']) ? print_r($request['history']["1"]['submitted_by_name'], true) : "Doctors Information Not Available") ?></td>
                </tr>
                <tr>
                    <th>Doctor Submit Time</th>
                    <td><i class="icon-leaf">
                        <?php if(isset($request['history']["1"]['time']))
                            {
                                $newformat = new DateTime($request['history']["1"]['time']);
                                $tz = new DateTimeZone('Asia/Kolkata'); // or whatever zone you're after

                                $newformat->setTimezone($tz);

                                echo $newformat->format('Y-m-d H:i:s');
                            }else{
                                echo "Doctor's information not available";
                                };
                        ?></i>
                    </td>
                </tr>
                <tr>
                    <th>Last Stage (HS stage) Time</th>
                    <td><i class="icon-leaf">
                        <?php if(isset($request['history']["last_stage"]['time']))
                            {
                                $newformat = new DateTime($request['history']["last_stage"]['time']);
                                $tz = new DateTimeZone('Asia/Kolkata'); // or whatever zone you're after

                                $newformat->setTimezone($tz);

                                echo $newformat->format('Y-m-d H:i:s');
                            }else{
                                echo "Not yet processed";
                                };
                        ?></i>
                    </td>
                </tr>
                <tr>
                    <th>Last Update Details</th>
                    <td><i class="icon-leaf">
                        <?php $last_stage = array_pop($request['history']);
                            echo "Last update on: ".$last_stage['time']; 
                            echo "<br>";
                            echo "Last updated by: ";?>
                        <?php if(isset($last_stage['submitted_by'])):?>
                            <?php echo $last_stage['submitted_by'];?>
                        <?php else:?>
                        <?php echo""; endif;?>
                        </i>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php if(isset($request['regular_follow_up'])): ?>
        
        <table id="dt_basics" class="table table-striped table-bordered table-hover">
            <tbody>
               
                <tr>
                    <th colspan=2 ><h4 style="color: green;">Regular Followups</h4></th>
                </tr>
            <?php foreach($request['regular_follow_up']['Follow_Up'] as $follows): ?>
                <tr>
                    <th class="badge bg-teal"><?php echo $follows['created_time'];?></th>
                </tr>
                <tr>
                    <th>Medicine Details</th>
                    <td><i class="icon-leaf"></i><?php echo $follows['medicine_details'];?></td>
                </tr>
                <tr>
                    <th>Followup Description</th>
                    <td><i class="icon-leaf"><?php echo $follows['followup_desc'];?></i></td>
                </tr>
             <?php endforeach; ?>
            </tbody>
        </table>

       
        <?php endif;?>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs tab-nav-right" role="tablist">

            <?php $ran = rand(1,10); ?>
            
                <li role="presentation" class="active"><a href="#prescription_attach_<?php echo $ran; ?>" data-toggle="tab"><button class="btn bg-green btn-lg btn-block waves-effect" type="button">Prescription<span class="badge"><?php if(isset($request['doc_data']['Prescriptions'])): echo count($request['doc_data']['Prescriptions']); else: echo "0"; endif; ?></span></button></a></li>

                <li role="presentation"><a href="#lab_report_attch_<?php echo $ran; ?>" data-toggle="tab"><button class="btn bg-red btn-lg btn-block waves-effect" type="button">Lab reports<span class="badge"><?php if(isset($request['doc_data']['Lab_Reports'])): echo count($request['doc_data']['Lab_Reports']); else: echo "0"; endif; ?></span></button></a></li>

                <li role="presentation"><a href="#xmdigital_attach_<?php echo $ran; ?>" data-toggle="tab"><button class="btn bg-blue btn-lg btn-block waves-effect" type="button">X-ray/MRI/Digital Images<span class="badge"><?php if(isset($request['doc_data']['Digital_Images'])): echo count($request['doc_data']['Digital_Images']); else: echo "0"; endif; ?></span></button></a></li>

                <li role="presentation"><a href="#bills_attach_<?php echo $ran; ?>" data-toggle="tab"><button class="btn bg-teal btn-lg btn-block waves-effect" type="button">Payments/Bills <span class="badge"><?php if(isset($request['doc_data']['Payments_Bills'])): echo count($request['doc_data']['Payments_Bills']); else: echo "0"; endif; ?></span></button></a></li>

                <li role="presentation"><a href="#discharge_summary_<?php echo $ran; ?>" data-toggle="tab"><button class="btn bg-orange btn-lg btn-block waves-effect" type="button">Discharge Summary<span class="badge"><?php if(isset($request['doc_data']['Discharge_Summary'])): echo count($request['doc_data']['Discharge_Summary']); else: echo "0"; endif; ?></span></button> </a></li>

                <li role="presentation"><a href="#others_doc_attach_<?php echo $ran; ?>" data-toggle="tab"><button class="btn bg-purple btn-lg btn-block waves-effect" type="button">Other Attachments <span class="badge"><?php if(isset($request['doc_data']['external_attachments'])): echo count($request['doc_data']['external_attachments']); else: echo "0"; endif; ?></span></button> </a></li>

            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
              
                <div role="tabpanel" class="tab-pane fade in active" id="prescription_attach_<?php echo $ran; ?>">
                <?php if(isset($request['doc_data']['Prescriptions']) && !is_null($request['doc_data']['Prescriptions']) && !empty($request['doc_data']['Prescriptions'])):?>

                 <b>Prescription Attachments</b>
                    <p>
                        <?php foreach ($request['doc_data']['Prescriptions'] as $attachment):?>

                            <a data-magnify="gallery" data-src="" data-caption="Prescriptions" data-group="a" href="<?php echo URLCustomer.$attachment['file_path'];?>">
                            <img src="<?php echo URLCustomer.$attachment['file_path'];?>" alt="" width="300" >
                            </a>
                        
                        <?php endforeach; ?>

                    </p>
                <?php endif; ?>
                </div>
               
                <div role="tabpanel" class="tab-pane fade" id="lab_report_attch_<?php echo $ran; ?>">
                <?php if(isset($request['doc_data']['Lab_Reports']) && !is_null($request['doc_data']['Lab_Reports']) && !empty($request['doc_data']['Lab_Reports'])):?> 

                    <b>Lab report Attachments</b>
                    <p>
                       <?php foreach ($request['doc_data']['Lab_Reports'] as $attachment):?>
                            <a data-magnify="gallery" data-src="" data-caption="Lab Reports" data-group="a" href="<?php echo URLCustomer.$attachment['file_path'];?>">
                                <img src="<?php echo URLCustomer.$attachment['file_path'];?>" alt="" width="300">
                            </a>
                        <?php endforeach;?>  
                    </p>

                <?php endif; ?>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="xmdigital_attach_<?php echo $ran; ?>">
                <?php if(isset($request['doc_data']['Digital_Images']) && !is_null($request['doc_data']['Digital_Images']) && !empty($request['doc_data']['Digital_Images'])):?>
                    
                    <b>X-ray/MRI/Digital Attachments</b>
                    <p>
                        <?php foreach ($request['doc_data']['Digital_Images'] as $attachment):?>
                                
                            <a data-magnify="gallery" data-src="" data-caption="Digital Images" data-group="a" href="<?php echo URLCustomer.$attachment['file_path'];?>">
                                <img src="<?php echo URLCustomer.$attachment['file_path'];?>" alt="" width="300" >
                            </a>
                                        
                        <?php endforeach;?>
                    </p>

                <?php endif; ?>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="bills_attach_<?php echo $ran; ?>">
                <?php if(isset($request['doc_data']['Payments_Bills']) && !is_null($request['doc_data']['Payments_Bills']) && !empty($request['doc_data']['Payments_Bills'])):?>
                    
                    <b>Payments/Bills Attachments</b>
                    <p>
                        <?php foreach ($request['doc_data']['Payments_Bills'] as $attachment):?>
                                
                            <a data-magnify="gallery" data-src="" data-caption="Payments Bills" data-group="a" href="<?php echo URLCustomer.$attachment['file_path'];?>">
                                <img src="<?php echo URLCustomer.$attachment['file_path'];?>" alt="" width="300" >
                            </a>
                                        
                        <?php endforeach;?>
                    </p>
                <?php endif; ?>   
                </div>

                <div role="tabpanel" class="tab-pane fade" id="discharge_summary_<?php echo $ran; ?>">
                <?php if(isset($request['doc_data']['Discharge_Summary']) && !is_null($request['doc_data']['Discharge_Summary']) && !empty($request['doc_data']['Discharge_Summary'])):?>
                    
                   <b>Discharge Summary Attachments</b>
                   <p>
                        <?php foreach ($request['doc_data']['Discharge_Summary'] as $attachment):?>
                            <a data-magnify="gallery" data-src="" data-caption="Discharge Summery" data-group="a" href="<?php echo URLCustomer.$attachment['file_path'];?>">
                                <img src="<?php echo URLCustomer.$attachment['file_path'];?>" alt="" width="300" >
                            </a>     
                        <?php endforeach;?>
                   </p>
                <?php endif; ?>   
                </div>

                <div role="tabpanel" class="tab-pane fade" id="others_doc_attach_<?php echo $ran; ?>">
                <?php if(isset($request['doc_data']['external_attachments']) && !is_null($request['doc_data']['external_attachments']) && !empty($request['doc_data']['external_attachments'])):?>

                    <b>Other Attachments</b>
                    <p>
                        <?php foreach ($request['doc_data']['external_attachments'] as $attachment):?>
                            <a data-magnify="gallery" data-src="" data-caption="Other Attachments" data-group="a" href="<?php echo URLCustomer.$attachment['file_path'];?>">
                                <img src="<?php echo URLCustomer.$attachment['file_path'];?>" alt="" width="300" >
                            </a>
                                       
                        <?php endforeach;?>   
                    </p>

                <?php endif; ?>
                </div>
           
            </div>
            </div>
        </div>
    </div>
</div>
    
</p>
<?php endforeach;?>
</div>
    <?php endif; ?>
</div>
    <div role="tabpanel" class="tab-pane animated fadeInRight hide" id="hbmonth_animation_2">
        <b>Monthly HB</b>
        <?php foreach($hb_docs as $bloodgroup): ?>
        <b>Student Blood Group : <?php echo $bloodgroup['doc_data']['widget_data']['page1']['Student Details']['bloodgroup']; ?></b>
         <?php endforeach; ?>
        <p>
            <table class="table table-bordered table-striped">
                <thead>
                <th>Month</th>
                <th>HB</th>
                </thead>
                <tbody>
                <?php foreach($hb_docs as $hb): ?>
                    <?php foreach($hb['doc_data']['widget_data']['page1']['Student Details']['HB_values'] as $hb_report): ?>
                <tr>
                <td><?php echo $hb_report['month'];?></td>
                <td><?php echo $hb_report['hb'];?></td>
            </tr>
            <?php endforeach; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
    </p>
    </div>
    <div role="tabpanel" class="tab-pane animated fadeInRight hide" id="bmimonth_animation_2">
        <b>Monthly BMI</b>
        <p>
           <table class="table table-bordered table-striped">
            <thead>
                <th>Month</th>
                <th>Height</th>
                <th>Weight</th>
                <th>BMI</th>
            </thead>
            <tbody>
            <?php foreach($bmi_docs as $bmi): ?>
                <?php foreach($bmi['doc_data']['widget_data']['page1']['Student Details']['BMI_values'] as $bmi_report): ?>
            <tr>
                <td><?php echo $bmi_report['month'];?></td>
                <td><?php echo $bmi_report['height'];?></td>
                <td><?php echo $bmi_report['weight'];?></td>
                <td><?php echo $bmi_report['bmi'];?></td>
            </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
        </tbody>
        </table>
        </p>
    </div>
            </div>
        </div>
    </div>
</div>

<!-- =====================================REQUEST INFORMATION END============================================= -->

<!-- =====================================HB INFORMATION START============================================= -->
                             
<div id="student_hb_info" role="tabpanel" class="tab-pane fade">
    <!-- <b>HB Information</b> -->
    <?php if(isset($hb_report) && !is_null($hb_report) && !empty($hb_report)):?>
    <p>
    <table id="" class="table table-striped table-bordered table-hover" style="width: 40%;">
        <?php foreach ($hb_report as $report): ?>
        <thead>
            <tr>
                <th>HB Submitted Month</th>
                <th>HB</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($report['doc_data']['widget_data']['page1']['Student Details']['HB_values'] as $hb): ?>
            <tr>
            <td><?php echo $hb['month'];?></td>
            <td><?php echo $hb['hb'];?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
            
        <?php endforeach; ?>
    </table>
    </p>

    <?php endif; ?>
</div>

<!-- =====================================HB INFORMATION END============================================= -->

<!-- =====================================BMI INFORMATION START============================================= -->                                
<div id="student_bmi_info" role="tabpanel" class="tab-pane fade">
    <?php if(isset($BMI_report) && !is_null($BMI_report) && !empty($BMI_report)):?>
    <!-- <b>BMI Information</b> -->
    <p>
        <table class="table table-bordered table-striped">
            <?php foreach ($BMI_report as $report): ?>
            <thead>
                <th>Submitted Month</th>
                <th>Height</th>
                <th>Weight</th>
                <th>BMI</th>
            </thead>
            <tbody>
                <?php foreach($report['doc_data']['widget_data']['page1']['Student Details']['BMI_values'] as $bmi): ?>
                <tr>
                    <td><?php echo $bmi['month'];?></td>
                    <td><?php echo $bmi['height'];?></td>
                    <td><?php echo $bmi['weight'];?></td>
                    <td><?php echo $bmi['bmi'];?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <?php endforeach; ?>
        </table>
    </p>
    <?php endif; ?>
</div>

<!-- =====================================BMI INFORMATION END============================================= -->


<!-- =====================================FIELD OFFIERS START============================================= -->                                
<div id="regular_followup" role="tabpanel" class="tab-pane fade">
    <b>Feild Officer</b>
    <p></p>



</div>

<!-- =====================================FIELD OFFIERS END============================================= -->


<!-- =====================================CALLING INFO START============================================= -->                                
<div id="calling_info" role="tabpanel" class="tab-pane fade" >
    
    <?php if(isset($docs)): ?>
        <?php foreach($docs as $doc): ?>
            <?php if(isset($doc['doctors_medical_reports'])): ?>
            <legend><h3 class="text-primary">Reports</h3></legend>
            <table class="table table-striped table-bordered table-hover">
                <tbody>
                    <tr>
                        <th>Date</th>
                        <th>Current Medical Condition</th>
                        <th>Parent Medical Condition</th>
                        <th>Advice/Suggestion</th>
                        <th>Status</th>
                    </tr>
                <?php foreach($doc['doctors_medical_reports']['reports'] as $report): ?>
                    <tr>
                        <td><?php echo $report['Date'];?></td>
                        <td><?php echo $report['Current Condition'];?></td>
                        <td><?php echo $report['Parent Condition'];?></td>
                        <td><?php echo $report['Doc Report'];?></td>
                        <td><?php echo $report['Student Status'];?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table> 
            <?php else: ?>
            <h3><?php echo "No Previous Data"; ?></h3>
        <?php endif; ?>

        <?php endforeach; ?>
        <?php endif; ?>
</div>

<!-- =====================================CALLING INFO END============================================= --> 

<!-- =====================================Health superindent INFO START============================================= -->                                
<div id="hs_details" role="tabpanel" class="tab-pane fade" >
    <!-- <b>Health Supervisor details</b> -->
    <table id="" class="table table-striped table-bordered table-hover">
        <tbody>
            <tr>
                <th>HS Name</th><td><i class="icon-leaf"><?php echo $hs['hs_name'];?></i></td>
            </tr>
            <tr>
                <th>HS Mobile</th><td><i class="icon-leaf"><?php echo $hs['hs_mob'];?></i></td>
            </tr>
        </tbody>
    </table>
</div>

<!-- =====================================Health Superindent INFO END============================================= --> 




	                        </div>
                       </div>	
                   <?php endforeach; ?><!-- End foreach for entire body -->
               <?php endif; ?><!-- End if for entire body(started in body) -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> 
        
<?php 
	//include footer
	include("inc/footer_bar.php"); 
?>

<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>
<script src="https://cdn.bootcss.com/prettify/r298/prettify.min.js"></script>
<script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
<script src="<?php echo JS; ?>img_options/jquery.magnify.js"></script>


