<?php $current_page = " "; ?>
<?php $main_nav = ""; ?>
<?php include('inc/header_bar.php');?>
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
	                	<div class="row clearfix">
	                		<ul class="nav nav-tabs tab-nav-right" role="tablist">
					            <li role="presentation" class="active"><a href="#screenfirst" data-toggle="tab"><button class="btn bg-cyan waves-effect" type="button">Screening Information 2018-2019</button></a></li>
					            <li role="presentation"><a href="#screensecond" data-toggle="tab"><button class="btn bg-cyan waves-effect" type="button">Screening Information 2019-2020</button></a></li>
					        </ul>
					        <div class="tab-content">
					        	<div role="tabpanel" class="tab-pane fade in active" id="screenfirst">
            						<b>Screening Information 2018-2019</b>
           	<!-- Screening one personal Info -->
           					<div class="row clearfix">
           					<?php if ($docs): ?><!-- This is start for (if) total Screening -->

                   	 		<?php foreach($docs as $doc): ?><!-- This is start for (foreach) total Screening -->

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
		                       
		                        </div>

		                    <?php endforeach; ?>
		                    <?php endif; ?>

                   	 		
		                    </div>
           	<!-- End Screening one personal Info -->
									<h2 class="card-inside-title">Student Screening information 2018-2019</h2>
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
				                    	
				                    	<div class="tab-content">

<!----------------------------- Start Screening Info ------------------------------------>
	<div id="student_screening_info" role="tabpanel" class="tab-pane fade in active in active">
	    <div class="row">
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
	                	<!--Start Physical Examination Info  -->
	                	<div id="physical_animation" role="tabpanel" class="tab-pane animated fadeInRight active">
	                		<div class="body table-responsive">
							    <table class="table table-striped" id="">
							        <tbody>
							            <tr>
							                <th>Height cms</th><td><i class="icon-leaf"></i></td>
							                <th>Weight kgs</th><td><i class="icon-leaf"></i></td>
							            </tr>
							            <tr>
							                <th>BMI%</th><td><i class="icon-leaf"></i></td>
							                <th>Pulse</th><td><i class="icon-leaf"></i></td>
							            </tr>
							            <tr>
							                <th>H B</th><td><i class="icon-leaf"></i></td>
							                <th>B P</th><td><i class="icon-leaf"></i></td>
							            </tr>
							            <tr>
							                <th>Blood Group</th><td><i class="icon-leaf"></i></td>
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
	                	<!-- End Physical Examination Info -->
	                	<!--Start Doctor Info  -->
	                	<div id="doctor_animation" role="tabpanel" class="tab-pane animated fadeInRight">
	                		<table id="" class="table table-striped table-bordered table-hover">
							    <tbody>
							        <tr>
							            <th>Abnormalities</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Ortho</th><td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Description</th><td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Advice</th><td><i class="icon-leaf"></i></td>
							            <th>Postural</th><td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Skin conditions</th><td><i class="icon-leaf"></i></td>
							            <th>Defects at Birth</th><td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Deficencies</th><td><i class="icon-leaf"></i></td>
							            <th>Childhood Diseases</th><td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>N A D</th><td><i class="icon-leaf"></i></td>
							            <th class="hidden">General Physician Sign</th>
							            <td class="hidden"></td>
							        </tr>
							    </tbody>
							</table> 
	                	</div>
	                	<!-- End Doctor Info -->
	                	<!--Start Vision Info  -->
	                	<div id="vision_animation" role="tabpanel" class="tab-pane animated fadeInRight">
	                		<table id="" class="table table-striped table-bordered table-hover">
							    <tbody>
							        <tr>
							            <th rowspan="2">Without Glasses</th>
							            <th>Right</th>
							            <td><i class="icon-leaf"></i></td>
							            <th rowspan="2">
							            <th>Right</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Left</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Left</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Colour Blindness</th><!--<th>Right</th>-->
							            <td><i class="icon-leaf"></i></td>
							            <th>Description</th>
							            <td colspan="2"><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <!--<th>Left</th><td><i class="icon-leaf"><?php// echo $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Left'];?></i></td>-->
							            <th rowspan="2">Slit Lamp Examination</th>
							            <th>Conjunctiva</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Eye Lids</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>                                   
							        <tr>
							            <th>Cornea</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Pupil</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>                                        
							            <th>Complaints</th>
							            <td><i class="icon-leaf"></i></td>
							            <th colspan="2">Wearing Spectacles</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>    
							            <th>Subjective Refraction</th>
							            <td><i class="icon-leaf"></i></td>
							            <th colspan="2">Ocular Diagnosis</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Referral Made</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th class="hidden">Opthomologist Sign</th>
							            <td class="hidden"><i class="icon-leaf"></i></td>
							        </tr>
							    </tbody>
							</table>
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
	                	<!-- End Vision Info -->
	                	<!--Start Auditory Info  -->
	                	<div id="auditory_animation" role="tabpanel" class="tab-pane animated fadeInRight">
	                		<table id="" class="table table-striped table-bordered table-hover">
					            <tbody>
						            <tr>
						                <th rowspan="2">Auditory Screening</th>
						                <th>Right</th>
						                <td><i class="icon-leaf"></i></td>
						                <th>Speech Screening</th>
						                <td><i class="icon-leaf"></i></td>
						            </tr>
						            <tr>
						                <th>Left</th>
						                <td><i class="icon-leaf"></i></td>
						                <th>D D and disability</th>
						                <td><i class="icon-leaf"></i></td>
						            </tr>
						            <tr>
						                <th>Description</th>
						                <td><i class="icon-leaf"></i></td>
						                <th>Referral Made</th>
						                <td><i class="icon-leaf"></i></td>
						            </tr>
						        </tbody>
						    </table>
	                	</div>
	                	<!-- End Auditory Info -->
	                	<!--Start Dental Info  -->
	                	<div id="dental_animation" role="tabpanel" class="tab-pane animated fadeInRight">
	                		<table id="" class="table table-striped table-bordered table-hover">
							    <tbody>
							        <tr>
							            <th>Oral Hygiene</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Carious Teeth</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Flourosis</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Orthodontic Treatment</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Indication for extraction</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Root Canal Treatment</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Curettage</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Estimated Amount</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Result</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Referral Made</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							    </tbody>
							</table>
	                	</div>
	                	<!-- End Dental Info -->
	                	<!--Start Other Mediacal Attachments Info  -->
	                	<div id="othermedicalattach_animation" role="tabpanel" class="tab-pane animated fadeInRight">
	                		<p>Attachment</p>
	                	</div>
	                	<!-- End Other Mediacal Attachments Info -->
	                </div>    
	               
	            </div>
	        </div>
	    </div>
		
	</div>
<!-------------------- End Screening Info ------------------------------->

<!-------------------- Start Request Info -------------------------->
	<div id="request_info" role="tabpanel" class="tab-pane fade">
		<!-- <h2 class="card-inside-title">Requests Information</h2> -->
		<div class="row clearfix">
		    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		        <!-- Nav tabs -->
		        <ul class="nav nav-tabs tab-nav-right" role="tablist">
		          
		           <li role="presentation" class="active"><a href="#requests_sick" data-toggle="tab">Sick Requests<span class="badge bg-pink"></span></a></li>
		       
		           <!--  <li role="presentation"><a href="#hbmonth_animation_2" data-toggle="tab">HB</a></li>
		           <li role="presentation"><a href="#bmimonth_animation_2" data-toggle="tab">BMI</a></li> -->
		        </ul>

		    <!-- Tab panes -->
		        <div class="tab-content">
		            <div role="tabpanel" class="tab-pane animated fadeInRight active" id="requests_sick">
		                <p>
		                    <div class="card">
		                        <div class="header bg-red" style="padding: 12px;">
		                            <b>Sick Request-</b>
		                        </div>
		                        <div class="body">
		                            <table id="dt_basic" class="table table-striped table-bordered table-hover">
		                            <tbody>
		                                <tr>
		                                    <th>Request Type</th><td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th>Follow Up Status</th><td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th colspan=2 ><h4 style="color: green;">Problem Information</h4></th>
		                                </tr>
		                                <tr>
		                                    <th>Problem Information</th>
		                                    <td></td>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th>Description</th>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th colspan=2 ><h4 style="color: green;">Diagnosis Information</h4></th>
		                                </tr>
		                                <tr>
		                                    <th>Doctor Summary</th>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th>Doctor's Advice</th>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th>Prescription</th>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>

		                            </tbody>
		                        </table>
		                        <table id="" class="table table-striped table-bordered table-hover">
		                            <tbody>
		                                <tr>
		                                    <th>Doctor's Name</th>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th>Doctor Submit Time</th>
		                                    <td><i class="icon-leaf">
		                                        </i>
		                                    </td>
		                                </tr>
		                                <tr>
		                                    <th>Last Stage (HS stage) Time</th>
		                                    <td><i class="icon-leaf">
		                                        </i>
		                                    </td>
		                                </tr>
		                                <tr>
		                                    <th>Last Update Details</th>
		                                    <td><i class="icon-leaf">
		                                       
		                                        </i>
		                                    </td>
		                                </tr>
		                            </tbody>
		                        </table>

		                 
		                        
		                        <table id="dt_basics" class="table table-striped table-bordered table-hover">
		                            <tbody>
		                               
		                                <tr>
		                                    <th colspan=2 ><h4 style="color: green;">Regular Followups</h4></th>
		                                </tr>
		                           
		                                <tr>
		                                    <th class="badge bg-teal"></th>
		                                </tr>
		                                <tr>
		                                    <th>Medicine Details</th>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th>Followup Description</th>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>
		                            
		                            </tbody>
		                        </table>

		                       
		                      
		                        <div class="row clearfix">
		                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                            <!-- Nav tabs -->
		                            <ul class="nav nav-tabs tab-nav-right" role="tablist">

		                        
		                            
		                                <li role="presentation" class="active"><a href="#prescription_attach_<?php //echo $ran; ?>" data-toggle="tab"><button class="btn bg-green btn-lg btn-block waves-effect" type="button">Prescription<span class="badge"></span></button></a></li>

		                                <li role="presentation"><a href="#lab_report_attch_<?php //echo $ran; ?>" data-toggle="tab"><button class="btn bg-red btn-lg btn-block waves-effect" type="button">Lab reports<span class="badge"></span></button></a></li>

		                                <li role="presentation"><a href="#xmdigital_attach_<?php //echo $ran; ?>" data-toggle="tab"><button class="btn bg-blue btn-lg btn-block waves-effect" type="button">X-ray/MRI/Digital Images<span class="badge"></span></button></a></li>

		                                <li role="presentation"><a href="#bills_attach_<?php //echo $ran; ?>" data-toggle="tab"><button class="btn bg-teal btn-lg btn-block waves-effect" type="button">Payments/Bills <span class="badge"></span></button></a></li>

		                                <li role="presentation"><a href="#discharge_summary_<?php //echo $ran; ?>" data-toggle="tab"><button class="btn bg-orange btn-lg btn-block waves-effect" type="button">Discharge Summary<span class="badge"></span></button> </a></li>

		                                <li role="presentation"><a href="#others_doc_attach_<?php //echo $ran; ?>" data-toggle="tab"><button class="btn bg-purple btn-lg btn-block waves-effect" type="button">Other Attachments <span class="badge"></span></button> </a></li>

		                            </ul>

		                            <!-- Tab panes -->
		                            <div class="tab-content">
		                              
		                                <div role="tabpanel" class="tab-pane fade in active" id="prescription_attach_<?php //echo $ran; ?>">
		                                

		                                 <b>Prescription Attachments</b>
		                                    <p>
		                                     

		                                            <a data-magnify="gallery" data-src="" data-caption="Prescriptions" data-group="a" href=""
		                                            alt="" width="300" >
		                                            </a>
		                                        
		                                    </p>
		                              
		                                </div>
		                               
		                                <div role="tabpanel" class="tab-pane fade" id="lab_report_attch_<?php //echo $ran; ?>">
		                               

		                                    <b>Lab report Attachments</b>
		                                    <p>
		                                       
		                                            <a data-magnify="gallery" data-src="" data-caption="Lab Reports" data-group="a" href="">
		                                                <img src="" alt="" width="300">
		                                            </a>
		                                    
		                                    </p>

		                                
		                                </div>

		                                <div role="tabpanel" class="tab-pane fade" id="xmdigital_attach_<?php //echo $ran; ?>">
		                                
		                                    
		                                    <b>X-ray/MRI/Digital Attachments</b>
		                                    <p>      
		                                        <a data-magnify="gallery" data-src="" data-caption="Digital Images" data-group="a" href="">
		                                            <img src="" alt="" width="300" >
		                                        </a>
		                                    </p>

		                               
		                                </div>

		                                <div role="tabpanel" class="tab-pane fade" id="bills_attach_<?php //echo $ran; ?>">
		                               
		                                    <b>Payments/Bills Attachments</b>
		                                    <p>       
		                                        <a data-magnify="gallery" data-src="" data-caption="Payments Bills" data-group="a" href="">
		                                            <img src="" alt="" width="300" >
		                                        </a>
		                                    </p>
		                                
		                                </div>

		                                <div role="tabpanel" class="tab-pane fade" id="discharge_summary_<?php //echo $ran; ?>">
		                                
		                                    
		                                   <b>Discharge Summary Attachments</b>
		                                    <p>
		                                        <a data-magnify="gallery" data-src="" data-caption="Discharge Summery" data-group="a" href="">
		                                            <img src="" alt="" width="300" >
		                                        </a>
		                                    </p>
		                                 
		                                </div>

		                                <div role="tabpanel" class="tab-pane fade" id="others_doc_attach_<?php //echo $ran; ?>">
		                                    <b>Other Attachments</b>
		                                    <p>
		                                        <a data-magnify="gallery" data-src="" data-caption="Other Attachments" data-group="a" href="">
		                                            <img src="" alt="" width="300" >
		                                        </a>  
		                                    </p>
		                                </div>
		                           
		                            </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                    
		                </p>
		            </div>
		            <div role="tabpanel" class="tab-pane animated fadeInRight hide" id="hbmonth_animation_2">
		                <b>Monthly HB</b>
		               
		                <b>Student Blood Group : </b>
		                 
		                <p>
		                    <table class="table table-bordered table-striped">
		                        <thead>
		                        <th>Month</th>
		                        <th>HB</th>
		                        </thead>
		                        <tbody>
		                        
		                        <tr>
		                        <td></td>
		                        <td></td>
		                    </tr>
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
		                   
		                    <tr>
		                        <td></td>
		                        <td></td>
		                        <td></td>
		                        <td></td>
		                    </tr>
		                </tbody>
		                </table>
		                </p>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
<!--------------------- End Request Info ----------------------------->

<!------------------ Start HB Info ------------------------------>
	<div id="student_hb_info" role="tabpanel" class="tab-pane fade">
		<!-- <b>HB Information</b> -->
	    <p>
	    <table id="" class="table table-striped table-bordered table-hover" style="width: 40%;">
	        <thead>
	            <tr>
	                <th>HB Submitted Month</th>
	                <th>HB</th>
	            </tr>
	        </thead>
	        <tbody>
	           
	            <tr>
	            <td></td>
	            <td></td>
	            </tr>
	        </tbody>
	    </table>
	    </p>
	</div>
<!------------------ End HB Info --------------------------------->

<!----------------------------------- Start BMI Info ----------------------------------->
	<div id="student_bmi_info" role="tabpanel" class="tab-pane fade">
		<!-- <b>BMI Information</b> -->
		<p>
		<table class="table table-bordered table-striped" style="width: 60%;">
		    <thead>
		        <th>BMI Submitted Month</th>
		        <th>Height</th>
		        <th>Weight</th>
		        <th>BMI</th>
		    </thead>
		    <tbody>
		        <tr>
		            <td></td>
		            <td></td>
		            <td></td>
		            <td></td>
		        </tr>
		    </tbody>
		</table>
		</p>
	</div>
<!----------------------------------- End BMI Info ----------------------------------->

<!-- =====================================FIELD OFFIERS START============================================= -->
<div id="regular_followup" role="tabpanel" class="tab-pane fade">
    <b>Feild Officer</b>
    <p></p>
</div>
<!-- =====================================FIELD OFFIERS END============================================= -->


<!----------------------------------- Start Calling Info ----------------------------------->
	<div id="calling_info" role="tabpanel" class="tab-pane fade" >
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
               
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table> 
            <h3><?php ///echo "No Previous Data"; ?></h3>
		
	</div>
<!----------------------------------- End Calling Info ----------------------------------->

<!----------------------------------- Start HS Info ----------------------------------->
	<div id="hs_details" role="tabpanel" class="tab-pane fade" >
		<!-- <b>Health Supervisor details</b> -->
	    <table id="" class="table table-striped table-bordered table-hover" style="width: 60%;">
	        <tbody>
	            <tr>
	                <th>HS Name</th><td><i class="icon-leaf"></i></td>
	            </tr>
	            <tr>
	                <th>HS Mobile</th><td><i class="icon-leaf"></i></td>
	            </tr>
	        </tbody>
	    </table>
	</div>
<!----------------------------------- End HS Info ----------------------------------->
			</div><!-- End of Screening details tab -->
			</div>
        </div>

    </div><!-- End of screening 2018-2019 tab -->
    
	<div role="tabpanel" class="tab-pane fade" id="screensecond">
		<b>Screening Information 2019-2020</b>
		<!-- Screening two personal Information -->
	<div class="row clearfix">
		<?php if ($docs_two): ?><!-- Screening 2020-2021 year personal info -->

        <?php foreach($docs_two as $doc_two): ?><!-- Screening 2020-2021 year personal info -->
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        	<div class="row">
        		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Student Health ID" id="" value="<?php echo $doc_two['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];?>" readonly/>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Class" value="<?php echo $doc_two['doc_data']['widget_data']['page2']['Personal Information']['Class']; ?>" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Section" value="<?php echo $doc_two['doc_data']['widget_data']['page2']['Personal Information']['Section'];?>" readonly />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Mobile number" value="<?php echo(isset($doc_two['doc_data']['widget_data']['page1']['Personal Information']['Mobile']['mob_num'])) ? $doc_two['doc_data']['widget_data']['page1']['Personal Information']['Mobile']['mob_num'] : "" ;?>" readonly />
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
                            <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Student Name" value="<?php echo $doc_two['doc_data']['widget_data']['page1']['Personal Information']['Name'];?>" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="School Name" value="<?php echo $doc_two['doc_data']['widget_data']['page2']['Personal Information']['School Name'];?>" readonly/>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Gender" value="<?php echo(isset($doc_two['doc_data']['widget_data']['page1']['Personal Information']['Gender'])) ? $doc_two['doc_data']['widget_data']['page1']['Personal Information']['Gender'] : "" ;?>" readonly/>
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
                            <input type="text" class="form-control" data-toggle="tooltip" data-palcement="bottom" title="Father Name" value="<?php echo $doc_two['doc_data']['widget_data']['page2']['Personal Information']['Father Name'];
                             ?>" readonly/>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Date of Birth" value="<?php echo $doc_two['doc_data']['widget_data']['page1']['Personal Information']['Date of Birth']; ?>" readonly />
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Date of Exam" value="<?php echo $doc_two['doc_data']['widget_data']['page2']['Personal Information']['Date of Exam'];?>" readonly/>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <td rowspan="4"><center>
                <p>Photo</p>
            <?php if(isset($doc_two['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && !is_null($doc_two['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && !empty($doc_two['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && isset($doc_two['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'])):?>
            <!-- <a href="<?php //echo URLCustomer.$doc_two['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];?>" rel="prettyPhoto"> -->
            <a data-magnify="gallery" data-src="" data-caption="Profile pic" data-group="a" href="<?php echo URLCustomer.$doc_two['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];?>">
                <img src="<?php echo URLCustomer.$doc_two['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];?>" style="height: 170px;width: 180px; border: 4px solid darkgrey;"/>
            
            </a>
            <?php else: ?>
            <?php echo "No Photo uploaded";?><?php endif ?>
            </center>
            </td>
       
        </div>

    <?php endforeach; ?>
<?php endif; ?>
    </div>
		<!-- ENd Screening two personal Information -->
		<h2 class="card-inside-title">Student Screening information 2019-2020</h2>
		<div class="row clearfix">
        	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        		<ul class="nav nav-tabs tab-nav-right" role="tablist">
        			
        			<li role="presentation" class="active"><a href="#student_screening_info_scnd" data-toggle="tab" aria-expanded="true"><button class="btn bg-pink waves-effect" type="button">Screening Info</button></a></li>

                    <li role="presentation"><a href="#request_info_scnd" data-toggle="tab" ><button class="btn bg-cyan waves-effect" type="button">Request Info</button></a></li>

                    <li role="presentation"><a href="#student_hb_info_scnd" data-toggle="tab" ><button class="btn bg-red waves-effect" type="button">HB Info</button></a></li>

                    <li role="presentation"><a href="#student_bmi_info_scnd" data-toggle="tab" ><button class="btn bg-green waves-effect" type="button">BMI Info</button></a></li>
                    
                    <!-- <li role="presentation"><a href="#regular_followup" data-toggle="tab" ><button class="btn bg-orange waves-effect" type="button">Feild Officers</button></a></li> -->

                    <li role="presentation"><a href="#calling_info_scnd" data-toggle="tab" ><button class="btn bg-teal waves-effect" type="button">Doctor Report</button></a></li>

                    <li role="presentation"><a href="#hs_details_scnd" data-toggle="tab" ><button class="btn bg-deep-purple waves-effect" type="button">HS details</button></a></li>
            	</ul>
        	
        	<div class="tab-content">

<!----------------------------- Start Screening Info ------------------------------------>
	<div id="student_screening_info_scnd" role="tabpanel" class="tab-pane fade in active in active">
	    <div class="row">
	        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	            <div class="card">
	                <div class="header">
	                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
	                        <li role="presentation" class="active"><a href="#physical_animation_scnd" data-toggle="tab">Physical Exam</a></li>
	                        <li role="presentation"><a href="#doctor_animation_scnd" data-toggle="tab">Doctor Check Up</a></li>
	                        <li role="presentation"><a href="#vision_animation_scnd" data-toggle="tab">Vision Screening</a></li>
	                        <li role="presentation"><a href="#auditory_animation_scnd" data-toggle="tab">Auditory Screening</a></li>
	                        <li role="presentation"><a href="#dental_animation_scnd" data-toggle="tab">Dental Check Up</a></li>
	                        <li role="presentation"><a href="#othermedicalattach_animation_scnd" data-toggle="tab">Other Medical Attachments</a></li>
	                    </ul>
	                </div>


	                <div class="tab-content">
	                	<!--Start Physical Examination Info  -->
	                	<div id="physical_animation_scnd" role="tabpanel" class="tab-pane animated fadeInRight active">
	                		<div class="body table-responsive">
							    <table class="table table-striped" id="">
							        <tbody>
							            <tr>
							                <th>Height cms</th><td><i class="icon-leaf"></i></td>
							                <th>Weight kgs</th><td><i class="icon-leaf"></i></td>
							            </tr>
							            <tr>
							                <th>BMI%</th><td><i class="icon-leaf"></i></td>
							                <th>Pulse</th><td><i class="icon-leaf"></i></td>
							            </tr>
							            <tr>
							                <th>H B</th><td><i class="icon-leaf"></i></td>
							                <th>B P</th><td><i class="icon-leaf"></i></td>
							            </tr>
							            <tr>
							                <th>Blood Group</th><td><i class="icon-leaf"></i></td>
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
	                	<!-- End Physical Examination Info -->
	                	<!--Start Doctor Info  -->
	                	<div id="doctor_animation_scnd" role="tabpanel" class="tab-pane animated fadeInRight">
	                		<table id="" class="table table-striped table-bordered table-hover">
							    <tbody>
							        <tr>
							            <th>Abnormalities</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Ortho</th><td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Description</th><td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Advice</th><td><i class="icon-leaf"></i></td>
							            <th>Postural</th><td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Skin conditions</th><td><i class="icon-leaf"></i></td>
							            <th>Defects at Birth</th><td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Deficencies</th><td><i class="icon-leaf"></i></td>
							            <th>Childhood Diseases</th><td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>N A D</th><td><i class="icon-leaf"></i></td>
							            <th class="hidden">General Physician Sign</th>
							            <td class="hidden"></td>
							        </tr>
							    </tbody>
							</table> 
	                	</div>
	                	<!-- End Doctor Info -->
	                	<!--Start Vision Info  -->
	                	<div id="vision_animation_scnd" role="tabpanel" class="tab-pane animated fadeInRight">
	                		<table id="" class="table table-striped table-bordered table-hover">
							    <tbody>
							        <tr>
							            <th rowspan="2">Without Glasses</th>
							            <th>Right</th>
							            <td><i class="icon-leaf"></i></td>
							            <th rowspan="2">
							            <th>Right</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Left</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Left</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Colour Blindness</th><!--<th>Right</th>-->
							            <td><i class="icon-leaf"></i></td>
							            <th>Description</th>
							            <td colspan="2"><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <!--<th>Left</th><td><i class="icon-leaf"><?php// echo $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Left'];?></i></td>-->
							            <th rowspan="2">Slit Lamp Examination</th>
							            <th>Conjunctiva</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Eye Lids</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>                                   
							        <tr>
							            <th>Cornea</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Pupil</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>                                        
							            <th>Complaints</th>
							            <td><i class="icon-leaf"></i></td>
							            <th colspan="2">Wearing Spectacles</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>    
							            <th>Subjective Refraction</th>
							            <td><i class="icon-leaf"></i></td>
							            <th colspan="2">Ocular Diagnosis</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Referral Made</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th class="hidden">Opthomologist Sign</th>
							            <td class="hidden"><i class="icon-leaf"></i></td>
							        </tr>
							    </tbody>
							</table>
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
	                	<!-- End Vision Info -->
	                	<!--Start Auditory Info  -->
	                	<div id="auditory_animation_scnd" role="tabpanel" class="tab-pane animated fadeInRight">
	                		<table id="" class="table table-striped table-bordered table-hover">
					            <tbody>
						            <tr>
						                <th rowspan="2">Auditory Screening</th>
						                <th>Right</th>
						                <td><i class="icon-leaf"></i></td>
						                <th>Speech Screening</th>
						                <td><i class="icon-leaf"></i></td>
						            </tr>
						            <tr>
						                <th>Left</th>
						                <td><i class="icon-leaf"></i></td>
						                <th>D D and disability</th>
						                <td><i class="icon-leaf"></i></td>
						            </tr>
						            <tr>
						                <th>Description</th>
						                <td><i class="icon-leaf"></i></td>
						                <th>Referral Made</th>
						                <td><i class="icon-leaf"></i></td>
						            </tr>
						        </tbody>
						    </table>
	                	</div>
	                	<!-- End Auditory Info -->
	                	<!--Start Dental Info  -->
	                	<div id="dental_animation_scnd" role="tabpanel" class="tab-pane animated fadeInRight">
	                		<table id="" class="table table-striped table-bordered table-hover">
							    <tbody>
							        <tr>
							            <th>Oral Hygiene</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Carious Teeth</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Flourosis</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Orthodontic Treatment</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Indication for extraction</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Root Canal Treatment</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Curettage</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Estimated Amount</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							        <tr>
							            <th>Result</th>
							            <td><i class="icon-leaf"></i></td>
							            <th>Referral Made</th>
							            <td><i class="icon-leaf"></i></td>
							        </tr>
							    </tbody>
							</table>
	                	</div>
	                	<!-- End Dental Info -->
	                	<!--Start Other Mediacal Attachments Info  -->
	                	<div id="othermedicalattach_animation_scnd" role="tabpanel" class="tab-pane animated fadeInRight">
	                		<p>Attachment</p>
	                	</div>
	                	<!-- End Other Mediacal Attachments Info -->
	                </div>    
	               
	            </div>
	        </div>
	    </div>
		
	</div>
<!-------------------- End Screening Info ------------------------------->

<!-------------------- Start Request Info -------------------------->
	<div id="request_info_scnd" role="tabpanel" class="tab-pane fade">
		<!-- <h2 class="card-inside-title">Requests Information</h2> -->
		<div class="row clearfix">
		    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		        <!-- Nav tabs -->
		        <ul class="nav nav-tabs tab-nav-right" role="tablist">
		          
		           <li role="presentation" class="active"><a href="#requests_sick_scnd" data-toggle="tab">Sick Requests<span class="badge bg-pink"></span></a></li>
		       
		           <!--  <li role="presentation"><a href="#hbmonth_animation_2" data-toggle="tab">HB</a></li>
		           <li role="presentation"><a href="#bmimonth_animation_2" data-toggle="tab">BMI</a></li> -->
		        </ul>

		    <!-- Tab panes -->
		        <div class="tab-content">
		            <div role="tabpanel" class="tab-pane animated fadeInRight active" id="requests_sick_scnd">
		                <p>
		                    <div class="card">
		                        <div class="header bg-red" style="padding: 12px;">
		                            <b>Sick Request-</b>
		                        </div>
		                        <div class="body">
		                            <table id="dt_basic" class="table table-striped table-bordered table-hover">
		                            <tbody>
		                                <tr>
		                                    <th>Request Type</th><td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th>Follow Up Status</th><td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th colspan=2 ><h4 style="color: green;">Problem Information</h4></th>
		                                </tr>
		                                <tr>
		                                    <th>Problem Information</th>
		                                    <td></td>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th>Description</th>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th colspan=2 ><h4 style="color: green;">Diagnosis Information</h4></th>
		                                </tr>
		                                <tr>
		                                    <th>Doctor Summary</th>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th>Doctor's Advice</th>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th>Prescription</th>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>

		                            </tbody>
		                        </table>
		                        <table id="" class="table table-striped table-bordered table-hover">
		                            <tbody>
		                                <tr>
		                                    <th>Doctor's Name</th>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th>Doctor Submit Time</th>
		                                    <td><i class="icon-leaf">
		                                        </i>
		                                    </td>
		                                </tr>
		                                <tr>
		                                    <th>Last Stage (HS stage) Time</th>
		                                    <td><i class="icon-leaf">
		                                        </i>
		                                    </td>
		                                </tr>
		                                <tr>
		                                    <th>Last Update Details</th>
		                                    <td><i class="icon-leaf">
		                                       
		                                        </i>
		                                    </td>
		                                </tr>
		                            </tbody>
		                        </table>

		                 
		                        
		                        <table id="dt_basics" class="table table-striped table-bordered table-hover">
		                            <tbody>
		                               
		                                <tr>
		                                    <th colspan=2 ><h4 style="color: green;">Regular Followups</h4></th>
		                                </tr>
		                           
		                                <tr>
		                                    <th class="badge bg-teal"></th>
		                                </tr>
		                                <tr>
		                                    <th>Medicine Details</th>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>
		                                <tr>
		                                    <th>Followup Description</th>
		                                    <td><i class="icon-leaf"></i></td>
		                                </tr>
		                            
		                            </tbody>
		                        </table>

		                       
		                      
		                        <div class="row clearfix">
		                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                            <!-- Nav tabs -->
		                            <ul class="nav nav-tabs tab-nav-right" role="tablist">

		                        
		                            
		                                <li role="presentation" class="active"><a href="#prescription_attach_scnd<?php //echo $ran; ?>" data-toggle="tab"><button class="btn bg-green btn-lg btn-block waves-effect" type="button">Prescription<span class="badge"></span></button></a></li>

		                                <li role="presentation"><a href="#lab_report_attch_scnd<?php //echo $ran; ?>" data-toggle="tab"><button class="btn bg-red btn-lg btn-block waves-effect" type="button">Lab reports<span class="badge"></span></button></a></li>

		                                <li role="presentation"><a href="#xmdigital_attach_scnd<?php //echo $ran; ?>" data-toggle="tab"><button class="btn bg-blue btn-lg btn-block waves-effect" type="button">X-ray/MRI/Digital Images<span class="badge"></span></button></a></li>

		                                <li role="presentation"><a href="#bills_attach_scnd<?php //echo $ran; ?>" data-toggle="tab"><button class="btn bg-teal btn-lg btn-block waves-effect" type="button">Payments/Bills <span class="badge"></span></button></a></li>

		                                <li role="presentation"><a href="#discharge_summary_scnd<?php //echo $ran; ?>" data-toggle="tab"><button class="btn bg-orange btn-lg btn-block waves-effect" type="button">Discharge Summary<span class="badge"></span></button> </a></li>

		                                <li role="presentation"><a href="#others_doc_attach_scnd<?php //echo $ran; ?>" data-toggle="tab"><button class="btn bg-purple btn-lg btn-block waves-effect" type="button">Other Attachments <span class="badge"></span></button> </a></li>

		                            </ul>

		                            <!-- Tab panes -->
		                            <div class="tab-content">
		                              
		                                <div role="tabpanel" class="tab-pane fade in active" id="prescription_attach_scnd<?php //echo $ran; ?>">
		                                

		                                 <b>Prescription Attachments</b>
		                                    <p>
		                                     

		                                            <a data-magnify="gallery" data-src="" data-caption="Prescriptions" data-group="a" href=""
		                                            alt="" width="300" >
		                                            </a>
		                                        
		                                    </p>
		                              
		                                </div>
		                               
		                                <div role="tabpanel" class="tab-pane fade" id="lab_report_attch_scnd<?php //echo $ran; ?>">
		                               

		                                    <b>Lab report Attachments</b>
		                                    <p>
		                                       
		                                            <a data-magnify="gallery" data-src="" data-caption="Lab Reports" data-group="a" href="">
		                                                <img src="" alt="" width="300">
		                                            </a>
		                                    
		                                    </p>

		                                
		                                </div>

		                                <div role="tabpanel" class="tab-pane fade" id="xmdigital_attach_scnd<?php //echo $ran; ?>">
		                                
		                                    
		                                    <b>X-ray/MRI/Digital Attachments</b>
		                                    <p>      
		                                        <a data-magnify="gallery" data-src="" data-caption="Digital Images" data-group="a" href="">
		                                            <img src="" alt="" width="300" >
		                                        </a>
		                                    </p>

		                               
		                                </div>

		                                <div role="tabpanel" class="tab-pane fade" id="bills_attach_scnd<?php //echo $ran; ?>">
		                               
		                                    <b>Payments/Bills Attachments</b>
		                                    <p>       
		                                        <a data-magnify="gallery" data-src="" data-caption="Payments Bills" data-group="a" href="">
		                                            <img src="" alt="" width="300" >
		                                        </a>
		                                    </p>
		                                
		                                </div>

		                                <div role="tabpanel" class="tab-pane fade" id="discharge_summary_scnd<?php //echo $ran; ?>">
		                                
		                                    
		                                   <b>Discharge Summary Attachments</b>
		                                    <p>
		                                        <a data-magnify="gallery" data-src="" data-caption="Discharge Summery" data-group="a" href="">
		                                            <img src="" alt="" width="300" >
		                                        </a>
		                                    </p>
		                                 
		                                </div>

		                                <div role="tabpanel" class="tab-pane fade" id="others_doc_attach_scnd<?php //echo $ran; ?>">
		                                    <b>Other Attachments</b>
		                                    <p>
		                                        <a data-magnify="gallery" data-src="" data-caption="Other Attachments" data-group="a" href="">
		                                            <img src="" alt="" width="300" >
		                                        </a>  
		                                    </p>
		                                </div>
		                           
		                            </div>
		                            </div>
		                        </div>
		                    </div>
		                </div>
		                    
		                </p>
		            </div>
		            <div role="tabpanel" class="tab-pane animated fadeInRight hide" id="hbmonth_animation_2">
		                <b>Monthly HB</b>
		               
		                <b>Student Blood Group : </b>
		                 
		                <p>
		                    <table class="table table-bordered table-striped">
		                        <thead>
		                        <th>Month</th>
		                        <th>HB</th>
		                        </thead>
		                        <tbody>
		                        
		                        <tr>
		                        <td></td>
		                        <td></td>
		                    </tr>
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
		                   
		                    <tr>
		                        <td></td>
		                        <td></td>
		                        <td></td>
		                        <td></td>
		                    </tr>
		                </tbody>
		                </table>
		                </p>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
<!--------------------- End Request Info ----------------------------->

<!------------------ Start HB Info ------------------------------>
	<div id="student_hb_info_scnd" role="tabpanel" class="tab-pane fade">
		<!-- <b>HB Information</b> -->
	    <p>
	    <table id="" class="table table-striped table-bordered table-hover" style="width: 40%;">
	        <thead>
	            <tr>
	                <th>HB Submitted Month</th>
	                <th>HB</th>
	            </tr>
	        </thead>
	        <tbody>
	           
	            <tr>
	            <td></td>
	            <td></td>
	            </tr>
	        </tbody>
	    </table>
	    </p>
	</div>
<!------------------ End HB Info --------------------------------->

<!----------------------------------- Start BMI Info ----------------------------------->
	<div id="student_bmi_info_scnd" role="tabpanel" class="tab-pane fade">
		<!-- <b>BMI Information</b> -->
		<p>
		<table class="table table-bordered table-striped" style="width: 60%;">
		    <thead>
		        <th>BMI Submitted Month</th>
		        <th>Height</th>
		        <th>Weight</th>
		        <th>BMI</th>
		    </thead>
		    <tbody>
		        <tr>
		            <td></td>
		            <td></td>
		            <td></td>
		            <td></td>
		        </tr>
		    </tbody>
		</table>
		</p>
	</div>
<!----------------------------------- End BMI Info ----------------------------------->

<!-- =====================================FIELD OFFIERS START============================================= -->
<div id="regular_followup" role="tabpanel" class="tab-pane fade">
    <b>Feild Officer</b>
    <p></p>
</div>
<!-- =====================================FIELD OFFIERS END============================================= -->


<!----------------------------------- Start Calling Info ----------------------------------->
	<div id="calling_info_scnd" role="tabpanel" class="tab-pane fade" >
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
               
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table> 
            <h3><?php ///echo "No Previous Data"; ?></h3>
		
	</div>
<!----------------------------------- End Calling Info ----------------------------------->

<!----------------------------------- Start HS Info ----------------------------------->
	<div id="hs_details_scnd" role="tabpanel" class="tab-pane fade" >
		<!-- <b>Health Supervisor details</b> -->
	    <table id="" class="table table-striped table-bordered table-hover" style="width: 60%;">
	        <tbody>
	            <tr>
	                <th>HS Name</th><td><i class="icon-leaf"></i></td>
	            </tr>
	            <tr>
	                <th>HS Mobile</th><td><i class="icon-leaf"></i></td>
	            </tr>
	        </tbody>
	    </table>
	</div>
<!----------------------------------- End HS Info ----------------------------------->
										</div><!-- End of Screening details tab -->
										</div>
				                    </div>
            					</div><!-- second screening end tab -->
					        </div>
	                	</div>
	                </div><!-- End of body -->
	            </div>
	        </div>
	    </div>
	</div>
</section>

<?php include('inc/footer_bar.php');?>