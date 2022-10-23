<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "EHR";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["ehr"]["active"] = true;
include("inc/nav.php");

?>
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
<!-- widget div-->
<div>

<!-- widget edit box -->
<div class="jarviswidget-editbox">
	<!-- This area used as dropdown edit box -->

</div>
<!-- end widget edit box -->
	 <!-- NEW WIDGET START -->
<article class="col-sm-12 col-md-8">

<?php if ($docs): ?>
<!-- Widget ID (each widget will need unique ID)-->
<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-editbutton="false">
<header>
<span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
<h2>Matched Document(s) <span class="badge bg-color-greenLight"><?php if(!empty($docscount)) {?><?php echo $docscount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>

</header>

<!-- widget div-->
<div>

<!-- widget edit box -->
<div class="jarviswidget-editbox">
	<!-- This area used as dropdown edit box -->

</div>
<!-- end widget edit box -->

<!-- widget content -->

	<div class="tree smart-form">
		<ul>
		<?php foreach ($docs as $doc):?>
		
		<?php if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]) && isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"])):?>
			<li>
				<span><i class="fa fa-lg fa-folder-open"></i>&nbsp;<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Name'];?></span>
				<ul>
				
				<?php if(isset($docs_requests) && !empty($docs_requests)):?>
				<li>
					<span class="label label-success"><i class="fa fa-lg fa-plus-circle"></i> Request Follow Ups</span>
					<ul>
					<?php foreach ($docs_requests as $request):?>
					<li style="display:none">
					<span class="label label-warning"><i class="fa fa-lg fa-minus-circle"></i> 
					
					
					<?php $last_stage = array_pop($request['history']);
					echo "Last update on: ".$last_stage['time'];
					?></span>
				<ul>
					
					<li>
							<div class="well well-sm ">
								<table id="dt_basic" class="table table-striped table-bordered table-hover">
				                    <tbody>
				                    <tr>
										<th>Follow Up Status</th><td><i class="icon-leaf"><?php echo $request['doc_data']['widget_data']['page2']['Review Info']['Status'];?></i></td>
									</tr>
									<tr>
										<th colspan=2 align="center"><h4>Problem Information</h4></th>
									</tr>
									<tr>
										<th>Problem Information</th><td><i class="icon-leaf"><?php echo (gettype($request['doc_data']['widget_data']['page1']['Problem Info']['Identifier'])=="array")? implode (", ",$request['doc_data']['widget_data']['page1']['Problem Info']['Identifier']) : $request['doc_data']['widget_data']['page1']['Problem Info']['Identifier'];?></i></td>
									</tr>
									<tr>
										<th>Description</th><td><i class="icon-leaf"><?php echo $request['doc_data']['widget_data']['page2']['Problem Info']['Description'];?></i></td>
									</tr>
									<?php if($request['doc_data']['widget_data']['page2']['Review Info']['Status'] != "Initiated"):?>
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
									<?php endif ?>
									</tbody>
								</table>
								</div>
							</li>
							
						<?php if(isset($request['doc_data']['chart_data']) && !is_null($request['doc_data']['chart_data']) && !empty($request['doc_data']['chart_data'])):?>
						<li>
							<span><i class="fa fa-lg fa-plus-circle"></i> Chart Attached</span>
							<ul>
								<li style="display:none">
									<div class="well well-sm ">
									<center>
											<p>Chart</p>
										<?php if(isset($request['doc_data']['chart_data']['chart_image']) && !is_null($request['doc_data']['chart_data']['chart_image']) && !empty($request['doc_data']['chart_data']['chart_image'])):?>
										<img src="<?php echo URLCustomer.$request['doc_data']['chart_data']['chart_image']['file_path'];?>" width="500"/><?php else: ?><?php echo "No Photo uploaded";?><?php endif ?>
										</center>
										
									</div>
								</li>							
							</ul>
						</li>
						<?php endif ?>
						<?php if(isset($request['doc_data']['external_attachments']) && !is_null($request['doc_data']['external_attachments']) && !empty($request['doc_data']['external_attachments'])):?>
						<li>
							<span><i class="fa fa-lg fa-plus-circle"></i> Other Attachments</span>
							<ul>
							<?php foreach ($request['doc_data']['external_attachments'] as $attachment):?>
							
								<li style="display:none">
									<div class="well well-sm ">
									<center>
											<p>Attachment</p>
										<embed src="<?php echo URLCustomer.$attachment['file_path'];?>" width="500"/>
										</center>
									</div>
								</li>
							<?php endforeach;?>			
							</ul>
						</li>
						<?php endif ?>
							
							
						</ul>
						<?php endforeach;?>	
					</ul>
					
					</li>
					<?php endif ?>
					<li>
					<span class="label label-success"><i class="fa fa-lg fa-minus-circle"></i> Screenings</span>
					
				<ul>
					<li>
					<span class="label label-warning"><i class="fa fa-lg fa-minus-circle"></i> <?php
					if(isset($doc['doc_data']['widget_data']['page2']['Personal Information']['Date of Exam']) && $doc['doc_data']['widget_data']['page2']['Personal Information']['Date of Exam'] != ""){
						echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Date of Exam'];
					}else{
						$last_stage = array_pop($doc['history']);
						echo $last_stage['time']." --> Examination date not set so displaying based on document submission";};?></span>
				<ul>
				
				
				<li>
						<span class="label label-primary"><i class="fa fa-lg fa-minus-circle"></i> Personal Information</span>
						<ul>
							<li style="">
								<div class="well well-sm ">
								<table id="dt_basic" class="table table-striped table-bordered table-hover">
				                    <tbody>
									<tr>
										<th>Name</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Name'];?></i></td>
										<td rowspan="4"><center>
										<p>Photo</p>
									<?php if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && !is_null($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && !empty($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo'])):?>
									<img src="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];?>" height="125" width="100"/><?php else: ?><?php echo "No Photo uploaded";?><?php endif ?>
									</center>
									</td>
									</tr>
									<tr>
										<th>Mobile</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Mobile']['mob_num']) && !empty($doc['doc_data']['widget_data']['page1']['Personal Information']['Mobile']['mob_num'])):?><?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Mobile']['mob_num']?><?php else:?><?php echo "Mobile number not provided";?><?php endIf;?></i></td>
									</tr>
									<tr>
										<th>Date of Birth</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Date of Birth'];?></i></td>
									</tr>
									<tr>
										<th>Admission Number</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['AD No'];?></i></td>
									</tr>
									<tr>
										<th>Hospital Unique ID</th><td colspan="2"><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];?></i></td>
									</tr>
									<tr>
										<th>School Name</th><td colspan="2"><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['School Name'];?></i></td>
									</tr>
									<tr>
										<th>Class</th><td colspan="2"><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Class'];?></i></td>
									</tr>
									<tr>
										<th>Section</th><td colspan="2"><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Section'];?></i></td>
									</tr>
									<tr>
										<th>Father Name</th><td colspan="2"><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Father Name'];?></i></td>
									</tr>
									<tr>
										<th>Date of Exam</th><td colspan="2"><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Date of Exam'];?></i></td>
									</tr>
									</tbody>
								</table>
								</div>
							</li>							
						</ul>
					</li>
					<?php endif;?>
					<?php if(isset($doc["doc_data"]["widget_data"]["page3"]["Physical Exam"])):?>
					
					<li>
						<span><i class="fa fa-lg fa-plus-circle"></i> Physical Exam</span>
						<ul>
							<li style="display:none">
							<div class="well well-sm ">
								<table id="dt_basic" class="table table-striped table-bordered table-hover">
				                    <tbody>
									<tr>
										<th>Height cms</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['Height cms'];?></i></td>
										<th>Weight kgs</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['Weight kgs'];?></i></td>
									</tr>
									<tr>
										<th>BMI%</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['BMI%'];?></i></td>
										<th>Pulse</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['Pulse'];?></i></td>
									</tr>
									<tr>
										<th>B P</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['B P'];?></i></td>
										<th>Blood Group</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['Blood Group'];?></i></td>
									</tr>
									<tr>
										<th>H B</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page3']['Physical Exam']['H B'];?></i></td>
									</tr>
									</tbody>
								</table>
								</div>
							</li>
						</ul>
					</li>
					<?php endif;?>
					<?php if(isset($doc["doc_data"]["widget_data"]["page4"]["Doctor Check Up"]) && isset($doc["doc_data"]["widget_data"]["page5"]["Doctor Check Up"])):?>
					
					<li>
						<span><i class="fa fa-lg fa-plus-circle"></i> Doctor Check Up</span>
						<ul>
						
						<li style="display:none">
							<div class="well well-sm ">
								<table id="dt_basic" class="table table-striped table-bordered table-hover">
				                    <tbody>
									<tr>
										<th>Abnormalities</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) && !empty($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'])) :?> 
										<?php echo (gettype($doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities']) : $doc['doc_data']['widget_data']['page4']['Doctor Check Up']['Check the box if normal else describe abnormalities'];?><?php endIf; ?> </i></td>
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
										<th>General Physician Sign</th>
										<td><?php if(isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['General Physician Sign']) && !empty($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['General Physician Sign'])) :?>
										<img src="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page5']['Doctor Check Up']['General Physician Sign']['file_path'];?>" height="100" width="180"/><?php else: ?><?php echo "General Physician Sign Not Available";?><?php endif ;?></td>
									</tr>
									</tbody>
								</table>
								</div>
							</li>
						</ul>
					</li>
					<?php endif;?>
					<?php if(isset($doc["doc_data"]["widget_data"]["page6"]["Without Glasses"]) && isset($doc["doc_data"]["widget_data"]["page6"]["With Glasses"]) && isset($doc['doc_data']['widget_data']['page7']['Colour Blindness'])):?>
					
					<li>
						<span><i class="fa fa-lg fa-plus-circle"></i> Vision Screening</span>
						<ul>
						<li style="display:none">
							<div class="well well-sm ">
								<table id="dt_basic" class="table table-striped table-bordered table-hover">
				                    <tbody>
									<tr>
										<th rowspan="2">Without Glasses</th><th>Right</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page6']['Without Glasses']['Right'];?></i></td><th rowspan="2">With Glasses</th><th>Right</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page6']['With Glasses']['Right'];?></i></td>
									</tr>
									<tr>
										<th>Left</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page6']['Without Glasses']['Left'];?></i></td><th>Left</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page6']['With Glasses']['Left'];?></i></td>
									</tr>
									<tr>
										<th rowspan="2">Colour Blindness</th><th>Right</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Right'];?></i></td><th>Description</th><td colspan="2"><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Description'];?></i></td>
									</tr>
									<tr>
										<th>Left</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Left'];?></i></td><th>Referral Made</th><td colspan="2"><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Referral Made'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page7']['Colour Blindness']['Referral Made']) : $doc['doc_data']['widget_data']['page7']['Colour Blindness']['Referral Made'];?></i></td>
									</tr>
									<tr>
									<th>Opthomologist Sign</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Opthomologist Sign']) && !is_null($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Opthomologist Sign']) && !empty($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Opthomologist Sign'])) : ?>
										<img src="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page7']['Colour Blindness']['Opthomologist Sign']['file_path'];?>" height="100" width="180" /> <?php else :?> <?php echo "Opthomologist Sign Not Available";?> <?php endIf;?></i></td>
										</tr>
									</tbody>
								</table>
								</div>
							</li>
						</ul>
					</li>
					<?php endif;?>
					<?php if(isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening'])):?>
					
					<li>
						<span><i class="fa fa-lg fa-plus-circle"></i> Auditory Screening</span>
						<ul>
						
							<li style="display:none">
								<div class="well well-sm ">
									<table id="dt_basic" class="table table-striped table-bordered table-hover">
					                    <tbody>
										<tr>
											<th rowspan="2">Auditory Screening</th><th>Right</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Right'];?></i></td><th>Speech Screening</th><td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening']) : $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Speech Screening'];?></i></td>
										</tr>
										<tr>
											<th>Left</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Left'];?></i></td><th>D D and disability</th><td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disability'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disability']) : $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['D D and disability'];?></i></td>
										</tr>
										<tr>
										<th>Description</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Description'];?></i></td>
										<th>Referral Made</th><td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made']) : $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made'];?></i></td>
									</tr>
									<tr>
										<th>Audiologist Sign</th><td><?php if(isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Audiologist Sign']) && !is_null($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Audiologist Sign']) && !empty($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Audiologist Sign'])) : ?>
										<img src="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Audiologist Sign']['file_path'];?>" height="100" width="180"/> <?php else :?><?php echo "Audiologist Sign Not Available";?> <?php endIf;?></td>
										</tr>
										</tbody>
									</table>
									</div>
								</li>
						</ul>
					</li>
					<?php endif;?>
					<?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up'])):?>
					
					<li>
						<span><i class="fa fa-lg fa-plus-circle"></i> Dental Check-up</span>
						<ul>
						
							<li style="display:none">
							<div class="well well-sm ">
								<table id="dt_basic" class="table table-striped table-bordered table-hover">
				                    <tbody>
									<tr>
										<th>Oral Hygiene</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Oral Hygiene'];?></i></td>
										<th>Carious Teeth</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Carious Teeth'];?></i></td>
									</tr>
									<tr>
										<th>Flourosis</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Flourosis'];?></i></td>
										<th>Orthodontic Treatment</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Orthodontic Treatment'];?></i></td>
									</tr>
									<tr>
										<th>Indication for extraction</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Indication for extraction'];?></i></td>
										<th>Result</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['Result'];?></i></td>
									</tr>
									<tr>
										<th>Referral Made</th><td><i class="icon-leaf"><?php echo (gettype($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made'])=="array")? implode (", ",$doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made']) : $doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Referral Made'];?></i></td>
									</tr>
									<tr class="hide">
										<th>DC</th>
										
										<td>DC 11<i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 11']) && !empty($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 11'])):?> <?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 11']?></i><?php endIf;?>
										
										DC 12<i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 12']) && !empty($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 12'])):?> <?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 12']?><?php endIf;?></i>
										DC 13<i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 13']) && !empty($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 13'])):?> <?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 13']?><?php endIf;?></i></br>
										DC 14<i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 14']) && !empty($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 14'])):?> <?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 14']?><?php endIf;?></i></td>
										</tr>
										<tr>
									   <th>Dentist Sign</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Dentist Sign']) && !is_null($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Dentist Sign']) && !empty($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Dentist Sign'])) : ?><img src="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page9']['Dental Check-up']['Dentist Sign']['file_path'];?>" height="100" width="180"/> <?php else : ?><?php echo"Dentist Sign Not Available";?> <?php endIf;?></i></td>
									</tr>
									</tbody>
								</table>
								</div>
							</li>
						</ul>
					</li>
					<?php endif;?>
					
					<?php if(isset($doc['doc_data']['chart_data']) && !is_null($doc['doc_data']['chart_data']) && !empty($doc['doc_data']['chart_data'])):?>
					<li>
						<span><i class="fa fa-lg fa-plus-circle"></i> Chart Attached</span>
						<ul>
							<li style="display:none">
								<div class="well well-sm ">
								<center>
										<p>Chart</p>
									<?php if(isset($doc['doc_data']['chart_data']['chart_image']) && !is_null($doc['doc_data']['chart_data']['chart_image']) && !empty($doc['doc_data']['chart_data']['chart_image'])):?>
									<img src="<?php echo URLCustomer.$doc['doc_data']['chart_data']['chart_image']['file_path'];?>" width="500"/><?php else: ?><?php echo "No Photo uploaded";?><?php endif ?>
									</center>
									
								</div>
							</li>							
						</ul>
					</li>
					<?php endif ?>
					<?php if(isset($doc['doc_data']['external_attachments']) && !is_null($doc['doc_data']['external_attachments']) && !empty($doc['doc_data']['external_attachments'])):?>
					<li>
						<span><i class="fa fa-lg fa-plus-circle"></i> Other Attachments</span>
						<ul>
						<?php foreach ($doc['doc_data']['external_attachments'] as $attachment):?>
							<li style="display:none">
								<div class="well well-sm ">
								<center>
										<p>Attachment</p>
									<embed src="<?php echo URLCustomer.$attachment['file_path'];?>" width="500"/>
									</center>
								</div>
							</li>
						<?php endforeach;?>			
						</ul>
					</li>
					<?php endif ?>
					
					
					
				</ul>
				</li>
				</ul>
				</li>
				</ul>
			</li>
			<?php endforeach;?>	
		</ul>
	</li>
</ul>
</div>
</div>
<!-- end widget content -->

</div><!-- ROW -->
<?php else: ?>
	<p>
		<?php echo "Searching value is not found.";?>
	</p>
	<?php endif ?>
<div>
<button type="button" class="btn btn-primary pull-right" onclick="window.history.back();">
							Back
						</button>
			
</div>		

</div>

<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
//include required scripts
include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S)--> 
<script type="text/javascript">

$(document).ready(function() {

$('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
$('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch').on('click', function(e) {
var children = $(this).parent('li.parent_li').find(' > ul > li');
if (children.is(':visible')) {
children.hide('fast');
$(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-lg fa-plus-circle');
} else {
children.show('fast');
$(this).attr('title', 'Collapse this branch').find(' > i').removeClass().addClass('fa fa-lg fa-minus-circle');
}
e.stopPropagation();
});			

})

</script>
<!--<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



<?php 
//include footer
include("inc/footer.php"); 
?>