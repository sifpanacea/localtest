<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "BC Welfare EHR";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa reports"]["sub"]["ehr"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<style>
@media only screen and (max-width: 500px) {
	#chat-body
	{
		min-height:150px;
	}
	.typearea textarea 
	{
		overflow:scroll!important;
		min-height: 80px;
	}
}
	#submit_print_request{
    float:right;
    display:block;
    margin-right:7px;
    
	}
</style>
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
<article class="col-sm-12 col-md-10">

<?php if ($docs): ?>
<!-- Widget ID (each widget will need unique ID)-->
<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-editbutton="false">
<header>
<span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
<h2>Matched Document(s) <span class="badge bg-color-greenLight"><?php if(!empty($docscount)) {?><?php echo $docscount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>
<button type="button" style="color: red;" class="btn bg-green waves-effect" data-toggle="tooltip" data-placement="top" title="Print" id="submit_print_request"><i class="material-icons"><b>print</b></i></button>
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
		
		<?php foreach($docs as $doc):?>
		
		<?php if(isset($doc["doc_data"]["widget_data"]["page1"]["Personal Information"]) && isset($doc["doc_data"]["widget_data"]["page2"]["Personal Information"])):?>
			<li>
				<span><i class="fa fa-lg fa-folder-open"></i>&nbsp;<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Name'];?></span>
				<ul>
				
				<!--hs details -->
				<li>
					<span class="label label-success"><i class="fa fa-lg fa-plus-circle"></i> HS Details</span>
					<ul>
					
					<li style="display:none">
					<span class="label label-warning"><i class="fa fa-lg fa-minus-circle"></i> 
					
					
					<?php 

					echo "Healthsupervisor details";
					?></span>
				<ul>
					
					<li>
							<div class="well well-sm ">
								<table id="dt_basic" class="table table-striped table-bordered table-hover">
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
							</li>
							
							
						</ul>
					</ul>
					
					</li>
				<!-- hs details -->
				
				<?php if(isset($docs_requests) && count($docs_requests) > 0):?>
				<li>
					<span class="label label-success"><i class="fa fa-lg fa-plus-circle"></i> Request Follow Ups <span class="badge bg-color-red "> <?php echo count($docs_requests)?> </span></span> 
					<ul>
					<?php foreach ($docs_requests as $request):?>
					<li style="display:none">
					<span class="label label-warning"><i class="fa fa-lg fa-minus-circle"></i> 
					
					
					 <?php $last_stage = end($request['history']); 
					
					$newformat = new DateTime($request['history'][0]['time']);

					//$newformat = new $last_stage['time'];
					$tz = new DateTimeZone('Asia/Kolkata'); // or whatever zone you're after

					$newformat->setTimezone($tz);
					//echo $dt->format('Y-m-d H:i:s');

					echo "Request rised on: ".$newformat->format('Y-m-d H:i:s'). "<strong> <i class='fa fa-lg fa-hand-o-left'></i> Time listed as per IST.</strong>";
					?></span>
					
				<ul>
					
					<li>
							<div class="well well-sm ">
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
									
									</tbody>
								</table>
								</div>
							</li>
							
							
						
							
						<li>
							<div class="well well-sm ">
								<table id="dt_basic" class="table table-striped table-bordered table-hover">
				                    <tbody>
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
									<tr class="hide">
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
									
									</tbody>
								</table>
								</div>
							</li>
							

							
						
						
						<li>
							<span><i class="fa fa-lg fa-plus-circle"></i> Notes on this request <span class="badge bg-color-red "> <?php echo (isset($request['doc_data']["notes_data"])? count($request['doc_data']["notes_data"]) : "0"); ?> </span></span>
							<ul>
								<li style="display:none">
									<div class="well well-sm ">
									<div>

							<div class="widget-body widget-hide-overflow">
								<!-- content goes here -->

								

								<!-- CHAT BODY -->
								<div id="chat-body" class="chat-body custom-scroll">
								
									<ul id="notes_thread_<?php echo $request["doc_properties"]['doc_id'];?>">
									
									<?php if(isset($request['doc_data']["notes_data"]) && count($request['doc_data']["notes_data"]) > 0):?>
									<?php foreach ($request['doc_data']["notes_data"] as $request_note):?>
										<li class="message" id="<?php echo $request_note["note_id"];?>">
											<div class="message-text">
												<time>
													<?php echo $request_note["datetime"];?>
												</time> <a href="javascript:void(0);" class="username"><?php echo $request_note["username"];?></a><p class='message_content'><?php echo $request_note["note"];?></p>&nbsp;&nbsp;<a href='javascript:void(0)' class='text-primary update_note_request pull-right hide'>Edit</a><a class='text-success save_msg hide' href='javascript:void(0)' note_id='<?php echo $request_note['note_id'];?>' doc_id='<?php echo $request['doc_properties']['doc_id'];?>'>Save</a>
												 </div>
												 
												 
										</li>
									<?php endforeach;?>	
									<?php endif ?>	
									</ul>
									

								</div>

								<!-- CHAT FOOTER -->
								<div class="chat-footer">

									<!-- CHAT TEXTAREA -->
									<div class="textarea-div">

										<div class="typearea">
											<textarea placeholder="Write a note..." id="textarea-expand_<?php echo $request["doc_properties"]['doc_id'];?>" class="custom-scroll"></textarea>
											
										</div>

									</div>

									<!-- CHAT REPLY/SEND -->
									<span class="textarea-controls">
										<button class="btn btn-sm btn-primary pull-left post_note_request" id="post_note_request" style="display:block;" doc_id= "<?php echo $request["doc_properties"]['doc_id'];?>">
											Post Note
										</button>  
										<div class="col-md-12" id="posting_note_gif" style="display:none;">
									<center><img src="<?php echo(IMG.'select2-spinner.gif'); ?>" id="gif" ></center>
									
									</span>
								</div>
										

								</div>

								<!-- end content -->
							</div>

						</div>
						<!-- end widget div -->
										
									</div>
								</li>							
							</ul>
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
						<?php if(isset($request['doc_data']['Prescriptions']) && !is_null($request['doc_data']['Prescriptions']) && !empty($request['doc_data']['Prescriptions'])):?>
						<li>
							<span><i class="fa fa-lg fa-plus-circle"></i> Prescriptions Attachments<span class="badge bg-color-greenLight">
								<?php echo (isset($request['doc_data']["Prescriptions"])? count($request['doc_data']["Prescriptions"]) : "0"); ?> </span></span>
							<ul>
							<?php foreach ($request['doc_data']['Prescriptions'] as $attachment):?>
								<li style="display:none">
									<div class="well well-sm ">
									<center>
											<p>Attachment</p>
									<a data-magnify="gallery" data-src="" data-caption="Prescriptions" data-group="a" href="<?php echo URLCustomer.$attachment['file_path'];?>">
      								<img src="<?php echo URLCustomer.$attachment['file_path'];?>" alt="" width="300" >
        							</a>
										</center>
									</div>
								</li>
							<?php endforeach;?>			
							</ul>
						</li>
						<?php endif ?>

						<?php if(isset($request['doc_data']['Lab_Reports']) && !is_null($request['doc_data']['Lab_Reports']) && !empty($request['doc_data']['Lab_Reports'])):?>
						<li>
							<span><i class="fa fa-lg fa-plus-circle"></i> Lab Reports Attachments<span class="badge bg-color-greenLight">
								<?php echo (isset($request['doc_data']["Lab_Reports"])? count($request['doc_data']["Lab_Reports"]) : "0"); ?> </span></span>
							<ul>
							<?php foreach ($request['doc_data']['Lab_Reports'] as $attachment):?>
								<li style="display:none">
									<div class="well well-sm ">
									<center>
											<p>Attachment</p>
											<a data-magnify="gallery" data-src="" data-caption="Lab Reports" data-group="a" href="<?php echo URLCustomer.$attachment['file_path'];?>">
      								<img src="<?php echo URLCustomer.$attachment['file_path'];?>" alt="" width="300" >
        							</a>
										</center>
									</div>
								</li>   
							<?php endforeach;?>			
							</ul>
						</li>
						<?php endif ?>

						<?php if(isset($request['doc_data']['Digital_Images']) && !is_null($request['doc_data']['Digital_Images']) && !empty($request['doc_data']['Digital_Images'])):?>

						<li>    
							<span><i class="fa fa-lg fa-plus-circle"></i> Digital Images <span class="badge bg-color-greenLight">
								<?php echo (isset($request['doc_data']["Digital_Images"])? count($request['doc_data']["Digital_Images"]) : "0"); ?> </span></span>
							<ul>
							<?php foreach ($request['doc_data']['Digital_Images'] as $attachment):?>
								<li style="display:none">
									<div class="well well-sm ">
									<center>
											<p>Attachment</p>
											<a data-magnify="gallery" data-src="" data-caption="Digital Images" data-group="a" href="<?php echo URLCustomer.$attachment['file_path'];?>">
      								<img src="<?php echo URLCustomer.$attachment['file_path'];?>" alt="" width="300" >
        							</a>
										</center>
									</div>
								</li>
							<?php endforeach;?>			
							</ul>
						</li>
						<?php endif ?>

						<?php if(isset($request['doc_data']['Payments_Bills']) && !is_null($request['doc_data']['Payments_Bills']) && !empty($request['doc_data']['Payments_Bills'])):?>

						<li>
							<span><i class="fa fa-lg fa-plus-circle"></i> Payments Bills <span class="badge bg-color-greenLight">
								<?php echo (isset($request['doc_data']["Payments_Bills"])? count($request['doc_data']["Payments_Bills"]) : "0"); ?> </span></span>
							<ul>
							<?php foreach ($request['doc_data']['Payments_Bills'] as $attachment):?>
								<li style="display:none">
									<div class="well well-sm ">
									<center>
											<p>Attachment</p>
											<a data-magnify="gallery" data-src="" data-caption="Payments Bills" data-group="a" href="<?php echo URLCustomer.$attachment['file_path'];?>">
      								<img src="<?php echo URLCustomer.$attachment['file_path'];?>" alt="" width="300" >
        							</a>
										</center>
									</div>
								</li>
							<?php endforeach;?>			
							</ul>
						</li>
						<?php endif ?>

						<?php if(isset($request['doc_data']['Discharge_Summary']) && !is_null($request['doc_data']['Discharge_Summary']) && !empty($request['doc_data']['Discharge_Summary'])):?>

						<li>
							<span><i class="fa fa-lg fa-plus-circle"></i> Discharge Summery <span class="badge bg-color-greenLight">
								<?php echo (isset($request['doc_data']["Discharge_Summary"])? count($request['doc_data']["Discharge_Summary"]) : "0"); ?> </span></span>
							<ul>
							<?php foreach ($request['doc_data']['Discharge_Summary'] as $attachment):?>
								<li style="display:none">
									<div class="well well-sm ">
									<center>
											<p>Attachment</p>
											<a data-magnify="gallery" data-src="" data-caption="Discharge Summery" data-group="a" href="<?php echo URLCustomer.$attachment['file_path'];?>">
      								<img src="<?php echo URLCustomer.$attachment['file_path'];?>" alt="" width="300" >
        							</a>
										</center>
									</div>
								</li>
							<?php endforeach;?>			
							</ul>
						</li>
						<?php endif ?>

						<?php if(isset($request['doc_data']['external_attachments']) && !is_null($request['doc_data']['external_attachments']) && !empty($request['doc_data']['external_attachments'])):?>

						<li>
							<span><i class="fa fa-lg fa-plus-circle"></i> Other Attachments  <span class="badge bg-color-greenLight">
								<?php echo (isset($request['doc_data']["external_attachments"])? count($request['doc_data']["external_attachments"]) : "0"); ?> </span></span>
							
							<ul>
							<?php foreach ($request['doc_data']['external_attachments'] as $attachment):?>
								<li style="display:none">
									<div class="well well-sm ">
									<center>
											<p>Attachment</p>
										<a data-magnify="gallery" data-src="" data-caption="Other Attachments" data-group="a" href="<?php echo URLCustomer.$attachment['file_path'];?>">
      								<img src="<?php echo URLCustomer.$attachment['file_path'];?>" alt="" width="300" >
        							</a>
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
					<span class="label label-success"><i class="fa fa-lg fa-minus-circle"></i> Screenings <span class="badge bg-color-red "> <?php echo (isset($docs)? count($docs) : "0"); ?> </span></span>
					
				<ul>
					<li>
					<span class="label label-warning"><i class="fa fa-lg fa-minus-circle"></i> <?php
					if(isset($doc['doc_data']['widget_data']['page2']['Personal Information']['Date of Exam']) && $doc['doc_data']['widget_data']['page2']['Personal Information']['Date of Exam'] != ""){
					$newformat = new DateTime($doc['doc_data']['widget_data']['page2']['Personal Information']['Date of Exam']);

					//$newformat = new $last_stage['time'];
					$tz = new DateTimeZone('Asia/Kolkata'); // or whatever zone you're after

					$newformat->setTimezone($tz);
					//echo $dt->format('Y-m-d H:i:s');

					echo $newformat->format('Y-m-d H:i:s'). "<strong> <i class='fa fa-lg fa-hand-o-left'></i> Time listed as per IST.</strong>";
					
						//echo $doc['doc_data']['widget_data']['page2']['Personal Information']['Date of Exam'];
					}else{
						$last_stage = array_pop($doc['history']);
						
					$newformat = new DateTime($last_stage['time']);
					//$newformat = new $last_stage['time'];
					$tz = new DateTimeZone('Asia/Kolkata'); // or whatever zone you're after
					$newformat->setTimezone($tz);
					//echo $dt->format('Y-m-d H:i:s');

					echo $newformat->format('Y-m-d H:i:s'). "<strong> <i class='fa fa-lg fa-hand-o-left'></i> Time listed as per IST.</strong>";
						
						//echo $last_stage['time']." <i class='fa fa-lg fa-hand-o-right'></i> Examination date not set so displaying based on document submission";
						};?></span>
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
									<?php if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && !is_null($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && !empty($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']) && isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'])):?>
							<!-- <img src="<?php// echo URLCustomer.$doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];?>" height="125" width="100"/> -->
									<a data-magnify="gallery" data-src="" data-caption="Profile Pic" data-group="a" href="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];?>">
      								<img src="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page1']['Personal Information']['Photo']['file_path'];?>" height="125" width="100"/>
        							</a>
        							<?php else: ?>
									<?php echo "No Photo uploaded";?><?php endif ?>
									</center>
									</td>
									</tr>
									<tr>
										<th>Mobile</th><td><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page1']['Personal Information']['Mobile']['mob_num'])):?><?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Mobile']['mob_num']?><?php else:?><?php echo " ";?><?php endIf;?></i></td>
									</tr>
									<tr>
										<th>Date of Birth</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Date of Birth'];?></i></td>
									</tr>
									<tr>
										<th>Admission Number</th><td><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page2']['Personal Information']['AD No'];?></i></td>
									</tr>
									<tr>
										<th>Hospital Unique ID</th><td colspan="2"><i class="icon-leaf"><?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];?></i></td>
										 <input type="hidden"  id="studentUniqueID" value="<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];?>">
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
										<th class="hidden">General Physician Sign</th>
										<td class="hidden"><?php if(isset($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['General Physician Sign']) && !empty($doc['doc_data']['widget_data']['page5']['Doctor Check Up']['General Physician Sign'])) :?>
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
									<th class="hidden">Opthomologist Sign</th>
									<td class="hidden"><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Opthomologist Sign']) && !is_null($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Opthomologist Sign']) && !empty($doc['doc_data']['widget_data']['page7']['Colour Blindness']['Opthomologist Sign'])) : ?>
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
										<th class="hidden">Audiologist Sign</th>
										<td class="hidden"><?php if(isset($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Audiologist Sign']) && !is_null($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Audiologist Sign']) && !empty($doc['doc_data']['widget_data']['page8'][' Auditory Screening']['Audiologist Sign'])) : ?>
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
									<tr>
										<th class="hidden">DC</th>
										<td class="hidden">DC 11<i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 11']) && !empty($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 11'])):?> <?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 11']?></i><?php endIf;?></br>
										DC 12<i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 12']) && !empty($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 12'])):?> <?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 12']?><?php endIf;?></i></br>
										DC 13<i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 13']) && !empty($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 13'])):?> <?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 13']?><?php endIf;?></i></br>
										DC 14<i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 14']) && !empty($doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 14'])):?> <?php echo $doc['doc_data']['widget_data']['page9']['Dental Check-up']['DC 14']?><?php endIf;?></i></td>
										
									   <th class="hidden">Dentist Sign</th>
									   <td class="hidden"><i class="icon-leaf"><?php if(isset($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Dentist Sign']) && !is_null($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Dentist Sign']) && !empty($doc['doc_data']['widget_data']['page9']['Dental Check-up']['Dentist Sign'])) : ?><img src="<?php echo URLCustomer.$doc['doc_data']['widget_data']['page9']['Dental Check-up']['Dentist Sign']['file_path'];?>" height="100" width="180"/> <?php else : ?><?php echo"Dentist Sign Not Available";?> <?php endIf;?></i></td>
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
									<embed src="<?php echo URLCustomer.$attachment['file_path'];?>" width="500" height="600"/>
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
				
				<!--=================================notes======================================== -->
				
				<li>
					<span class="label label-success"><i class="fa fa-lg fa-plus-circle"></i> Notes</span>
					
					<!-- ==================================================== -->
						<ul>
						<li style="display:none">
						
						<!-- new widget -->
					<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false" data-widget-fullscreenbutton="false">

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

						<!-- widget div-->
						<div>

							<div class="widget-body widget-hide-overflow">
								<!-- content goes here -->

								

								<!-- CHAT BODY -->
								<div id="chat-body" class="chat-body custom-scroll">
								
									<ul id="notes_thread_<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];?>">
									
									<?php if(isset($notes) && count($notes) > 0):?>
									<?php foreach ($notes as $note):?>
										<li class="message" id="<?php echo $note["_id"];?>">
											<div class="message-text">
												<time>
													<?php echo $note["datetime"];?>
												</time> <a href="javascript:void(0);" class="username"><?php echo $note["username"];?></a><?php echo $note["note"];?>
												 </div><br>
												 <button class="btn btn-xs btn-danger pull-right msg_delete" doc_id = "<?php echo $note["_id"];?>" >Delete</button>
												 
										</li>
									<?php endforeach;?>	
									<?php endif ?>	
									</ul>
									

								</div>

								<!-- CHAT FOOTER -->
								<div class="chat-footer">

									<!-- CHAT TEXTAREA -->
									<div class="textarea-div">

										<div class="typearea">
											<textarea placeholder="Write a note..." id="textarea-expand_<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];?>" class="custom-scroll"></textarea>
											
										</div>

									</div>

									<!-- CHAT REPLY/SEND -->
									<span class="textarea-controls">
										<button class="btn btn-sm btn-primary pull-left post_note" id="post_note" style="display:block;" uid= "<?php echo $doc['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID'];?>">
											Post Note
										</button>  
										<div class="col-md-12" id="posting_note_gif" style="display:none;">
									<center><img src="<?php echo(IMG.'select2-spinner.gif'); ?>" id="gif" ></center>
									
									</span>
								</div>
										

								</div>

								<!-- end content -->
							</div>

						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->
						
						</li>
						</ul>
						
						<!-- ==================================================== -->
					<?php if(isset($BMI_report) && !is_null($BMI_report) && !empty($BMI_report)):?>
					<li>
									<span class="label label-success"><i class="fa fa-lg fa-folder-open"></i> BMI Report(s)</span>
									<ul>
									<?php foreach ($BMI_report as $report): ?>
												
									<li>
									<span><i class="icon-leaf">	<table class="table table-bordered table-striped">
									<thead>
									<th>Month</th>
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
													</table> </span>
												
												</li>
											<?php endforeach; ?>
									
							</ul>

						</li>
						<?php endif;?>
						<?php if(isset($hb_report) && !is_null($hb_report) && !empty($hb_report)):?>
					<li>
									<span class="label label-success"><i class="fa fa-lg fa-folder-open"></i> HB Report(s)</span>
									<ul>
									<?php foreach ($hb_report as $report): ?>
												
									<li>
									<span><i class="icon-leaf">	<table class="table table-bordered table-striped">
									<thead>
									<th>Month</th>
									<th>HB</th>
									</thead>
									<tbody>
									<?php foreach($report['doc_data']['widget_data']['page1']['Student Details']['HB_values'] as $hb): ?>
									<tr>
									<td><?php echo $hb['month'];?></td>
									<td><?php echo $hb['hb'];?></td>
									</tr>
									<?php endforeach; ?>
									</tbody>
									</table></span>
									</li>
								    <?php endforeach; ?>
							</ul>
					</li>
					<?php endif;?>
					
					
					
					
					
				<!--============================================================================== -->
				
				</ul>
			</li>
			
			<!-- field officer -->
			<?php endforeach;?>	
		</ul>
	</li>
</ul>
<button type="button" class="btn btn-primary pull-right btn-sm" onclick="window.history.back();">Back</button><br><br><br>
</div>				
</div>
<!-- end widget content -->

</div><!-- ROW -->
<?php else: ?>
	<p>
		<?php echo "Searching value is not found.";?>
	</p>
	<button type="button" class="btn btn-primary pull-right btn-sm" onclick="window.history.back();">Back</button><br>
	<?php endif ?>
	
<div>

			
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
<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <!-- <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script> -->
  <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdn.bootcss.com/prettify/r298/prettify.min.js"></script>
  <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="<?php echo JS; ?>img_options/jquery.magnify.js"></script>

<!-- PAGE RELATED PLUGIN(S)--> 
<script type="text/javascript">

$(document).ready(function() {
$("a[rel^='prettyPhoto']").prettyPhoto();
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
//scrollToBottom('chat-body');
$('.post_note').click(function(e){
	
	var uid = $(this).attr('uid');
	var note = $('#'+'textarea-expand_'+uid).val();
	var count = $('#'+'textarea-expand_'+uid).val().length;
	if(count > 0){
		$('#post_note').hide();
		$('#posting_note_gif').show();
		
		var url_parts = window.location.href.split('/');
	
	var currentdate = new Date(); 
	var datetime = currentdate.getFullYear() + "-"
				+ ("0" + (currentdate.getMonth() + 1))  + "-"
				+  currentdate.getDate("dd") + " "
				+ ("0" + currentdate.getHours()).slice(-2) + ":"
				+ ("0" + currentdate.getMinutes()).slice(-2) + ":"
				+ ("0" + currentdate.getSeconds()).slice(-2);
	
	
	$.ajax({
		url: url_parts[0]+"//"+url_parts[2]+"/"+url_parts[3]+'/'+url_parts[4]+'/'+url_parts[5]+'/'+url_parts[6]+'/post_note',
		type: 'POST',
		data: {"datetime" : datetime, "note" : note,"username" : "BC Welfare Admin", "uid" : uid},
										
		success: function (data) {
			console.log(data);
			
			message = '<li class="message"  id="'+data+'"><div class="message-text"><time>' + datetime + '</time> <a href="javascript:void(0);" class="username">' + 'BC Welfare Admin' + '</a>' + note + '</div><br><button class="btn btn-xs btn-danger pull-right msg_delete" doc_id = "'+data+'">Delete</button></li>';
	
			$('#'+'notes_thread_'+uid).append(message);
			
			//scrollToBottom('chat-body');
			
			$('#post_note').show();
			$('#posting_note_gif').hide();
			$('#'+'textarea-expand_'+uid).val('');
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}else{
		alert("Please entre note..")
	}
	
});

//====notes for request===================
$('.post_note_request').click(function(e){
	
	var doc_id = $(this).attr('doc_id');
	var note = $('#'+'textarea-expand_'+doc_id).val();
	var count = $('#'+'textarea-expand_'+doc_id).val().length;
	if(count > 0){
		$('#post_note_request').hide();
		$('#posting_note_gif').show();
		
		var url_parts = window.location.href.split('/');
	
	var currentdate = new Date(); 
	var datetime = currentdate.getFullYear() + "-"
				+ ("0" + (currentdate.getMonth() + 1))  + "-"
				+  currentdate.getDate("dd") + " "
				+ ("0" + currentdate.getHours()).slice(-2) + ":"
				+ ("0" + currentdate.getMinutes()).slice(-2) + ":"
				+ ("0" + currentdate.getSeconds()).slice(-2);
	
	
	$.ajax({
		url: url_parts[0]+"//"+url_parts[2]+"/"+url_parts[3]+'/'+url_parts[4]+'/'+url_parts[5]+'/'+url_parts[6]+'/post_note_request',
		type: 'POST',
		data: {"datetime" : datetime, "note" : note,"username" : "BC Welfare Admin","doc_id":doc_id},
										
		success: function (data) {
			console.log(data);
			
			message = '<li class="message"  id="'+data+'"><div class="message-text"><time>' + datetime + '</time> <a href="javascript:void(0);" class="username">' + 'BC Welfare Admin' + '</a>' + note + '</div></li>';
	
			$('#'+'notes_thread_'+doc_id).append(message);
			
			//scrollToBottom('chat-body');
			
			$('#post_note_request').show();
			$('#posting_note_gif').hide();
			$('#'+'textarea-expand_'+doc_id).val('');
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
	}else{
		alert("Please entre note..")
	}
	
});

$(document).on('click','.save_msg',function(){
	
	var doc_id  = $(this).attr('doc_id');
	var note_id = $(this).attr('note_id');
	var note    = $(this).parent('.message-text').find('textarea').val();
	$(this).parent('.message-text').find('textarea').replaceWith("<p class='message_content'>"+note+"</p>");
	$(this).addClass('hide');
	$(this).parent('.message-text').find('.update_note_request').removeClass('hide');

	var url_parts = window.location.href.split('/');
	
	$.ajax({
		url    : url_parts[0]+"//"+url_parts[2]+"/"+url_parts[3]+'/'+url_parts[4]+'/'+url_parts[5]+'/'+url_parts[6]+'/update_note_request',
		type   : 'POST',
		data   : {"note_id" : note_id,"note" : note,"doc_id" : doc_id},
		success: function (data) 
		{
			if(data=='REQUIRED_PARAMS_MISSING')
			{

			}
			
		},
	    error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
	    }
	});
})

/* update notes */
$('.update_note_request').click(function(e){
	
	var note = $(this).prev('p').text();
	$(this).prev('p').replaceWith("<textarea rows='3' class='message_content custom-scroll' style='width:550px;'>"+note+"</textarea>");
	$(this).addClass('hide');
	$(this).parent('.message-text').find('.save_msg').removeClass('hide');
});

})

$(document).on('click','.msg_delete', function (){
	
	var doc_id = $(this).attr('doc_id');
	// alert(doc_id);
	var url_parts = window.location.href.split('/');
	
	$.ajax({
		url: url_parts[0]+"//"+url_parts[2]+"/"+url_parts[3]+'/'+url_parts[4]+'/'+url_parts[5]+'/'+url_parts[6]+'/delete_note',
		type: 'POST',
		data: {"doc_id" : doc_id},
										
		success: function (data) {
			console.log(data);
			$('#'+doc_id).remove();
			// scrollToBottom('chat-body');
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
	});
	
});

function scrollToBottom(cls) {
	$('.' + cls).scrollTop($('.' + cls + ' ul li').last().position().top + $('.' + cls + ' ul li').last().height());
}

//$("img").popImg();

$("#submit_print_request").click(function(){
    var studentUniqueID = $('#studentUniqueID').val();
    var url_parts = window.location.href.split('/');
    console.log(url_parts, "urllllllllllllllllllllllllllllllllllllll"); 
    $.ajax({
        url    : 'https://mednote.in/PaaS/healthcare/index.php/bc_welfare_mgmt/init_health_summary_report_process',
        type   : 'POST',
        data   : {"unique_id" : studentUniqueID},
        success: function (data) {          
            console.log(data);  
            
            /*newWin= window.open("");
            newWin.document.write("<style>@media print{.page_break{float: none !important; display:block;position: relative !important;page-break-after: always;}table{width:100%}tr{width:100%}td{padding:5px;width:50%;border:1px solid black;}.physical_information{border:1px solid black;padding:5px;margin-bottom:5px;}.eye_abnormalities{border:1px solid black;padding:5px;margin-bottom:5px;}.auditory_abnormalities{border:1px solid black;padding:5px;margin-bottom:5px;}.dental_abnormalities{border:1px solid black;padding:5px;margin-bottom:5px;}.doctor_signature{border:0px solid black;padding:5px;margin-bottom:5px;}.note{border:1px solid black;padding:5px;margin-bottom:5px;clear:both;}.general_abnormalities{border:1px solid black;padding:5px;margin-bottom:5px;}.school_name{}.title{font-size:110%;font-weight:bold;}label{margin:5px!important;display:inline-block;}}</style>");
            newWin.document.write(data);
            newWin.print();
            newWin.close();*/
            
            var mywindow = window.open("");
            var is_chrome = Boolean(mywindow.chrome);
            mywindow.document.write("<style>@media print{.page_break{float: none !important; display:block;position: relative !important;page-break-after: always;}table{width:100%}tr{width:100%}td{padding:5px;width:50%;border:1px solid black;}.physical_information{border:1px solid black;padding:5px;margin-bottom:3px;}.req_information{border:1px solid black;padding:5px;margin-bottom:3px;}.eye_abnormalities{border:1px solid black;padding:5px;margin-bottom:3px;}.auditory_abnormalities{border:1px solid black;padding:5px;margin-bottom:3px;}.dental_abnormalities{border:1px solid black;padding:5px;margin-bottom:3px;}.doctor_signature{border:0px solid black;padding:5px;margin-bottom:3px;}.note{border:1px solid black;padding:5px;margin-bottom:3px;clear:both;}.general_abnormalities{border:1px solid black;padding:5px;margin-bottom:3px;}.school_name{}.title{font-size:100%;font-weight:bold;}label{margin:5px!important;display:inline-block;}.Havik{margin-left:0px;}}</style>");
            mywindow.document.write(data);
            mywindow.document.close(); // necessary for IE >= 10 and necessary before onload for chrome

            if (is_chrome) {
                mywindow.onload = function() { // wait until all resources loaded 
                    mywindow.focus(); // necessary for IE >= 10
                    mywindow.print();  // change window to mywindow
                    mywindow.close();// change window to mywindow
                };
            }
            else {
                mywindow.document.close(); // necessary for IE >= 10
                mywindow.focus(); // necessary for IE >= 10
                mywindow.print();
                mywindow.close();
            }
        },
        error  :function(XMLHttpRequest, textStatus, errorThrown)
        {
         console.log('error', errorThrown);
         }
    });
    
    //$("#ehr_data_for_request").val(JSON.stringify(id_array));
    //$("#init_health_summary_report").submit();
});


</script>
<!--<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



<?php 
//include footer
include("inc/footer.php"); 
?>
