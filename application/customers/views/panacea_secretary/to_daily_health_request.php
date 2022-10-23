<?php $current_page=""; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<br>
<br>
<br>
<br>
<br>

<!-- Code for data tables -->
<section class="">
<div class="container-fluid">
<div class="block-header">
    <h2>Daily Health Requests</h2>
</div>
<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                     Student Request Details
                </h2>
                <ul class="header-dropdown m-r--5">
                    <div class="button-demo">
                    <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                    </div>
                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>Unique ID</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Disease type</th>
                                <th>Request Raised Time</th>
                                <th>Doctor Response Time</th>
                                <th>Doctor Name</th>
                                <th>Attachments</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
							<?php foreach ($students_details as $index => $doc ):?>
							<tr>
								<td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Unique ID'] ;?></td>
								<td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Name']['field_ref'] ;?></td>
								<td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Class']['field_ref'] ;?></td>

								    <?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Normal"):?>
									<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'];?>

								<td><span class="badge bg-green">
									<?php foreach ($identifiers as $identifier => $values) :?>
										<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]); ?>
										<?php if(!empty($var123)):?> 
											<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]) : "No Identifier";?>

										<?php endif;?>
									<?php endforeach;?>												
									</span>
								</td>

							 		<?php elseif($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Emergency"):?>
						            <?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'];?>

						        <td><span class="badge bg-red">
						            <?php foreach ($identifiers as $identifier => $values) :?>
						                <?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]); ?>
						                <?php if(!empty($var123)):?> 
				      						<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]) : "No Identifier";?>
						            	<?php endif;?>
						    		<?php endforeach;?>
						    		</span>
						    	</td>


									<?php elseif($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Chronic"):?>
							        <?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'];?>

								<td><span class="badge bg-amber">
									<?php foreach ($identifiers as $identifier => $values) :?>
						 				<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]); ?>
										<?php if(!empty($var123)):?> 
											<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]) : "No Identifier";?>
										<?php endif;?>
									<?php endforeach;?>
									</span>
								</td>

									<?php endif;?>
							
								<td> <?php echo $doc['history'][0]['time'];?></td>

									<?php $last_doc = end($doc['history']); 
									if(preg_match("/panacea.dr/i",$last_doc['submitted_by'])):?>

							    <td><?php echo $last_doc['time'];?></td>
							    <td><?php echo $last_doc['submitted_by_name'];?></td>
									<?php else:?>

								<td><?php echo "Nill";?></td>
								<td><?php echo "Doctor not to yet responded";?></td>

								<?php endif;?>

									<?php if(isset($doc['doc_data']['external_attachments']) && !empty($doc['doc_data']['external_attachments'])):?>
								<td class="text-center"><i class="fa fa-paperclip fa-2x" aria-hidden="true"></i></td>

									<?php else:?>
								<td>No Attachments</td>
									<?php endif;?>

							    <td> 
									<a class='ldelete' href='<?php echo URL."panacea_secretary/panacea_reports_display_ehr_uid/"?>? id = <?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Unique ID'];?>'>
									<button class="btn bg-teal waves-effect">Show EHR</button>
									</a>        			
								</td>
							</tr>
							<?php endforeach;?>
						</tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
    </section>

<?php include('inc/footer_bar.php'); ?> 