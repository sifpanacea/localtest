<?php $current_page=""; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<?php //include('inc/sidebar.php'); ?>
<br>
<br>
<br>
<br>

<section class="">
<div class="container-fluid">
      <!-- Exportable Table -->
  	<div class="row clearfix">
      	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          	<div class="card">
	            <div class="header">
	                  <h2>Regular Followup Cases<span class="badge bg-red"></span></h2>
                        <ul class="header-dropdown m-r--5">
                              <div class="button-demo">
                              <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                              </div>
                        </ul>
	            </div>
	            <div class="body">
	                <div class="row clearfix">
	                	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                		<ul class="nav nav-tabs tab-nav-right" role="tablist" id="ss">

		                        <li role="presentation" class="active"><a href="#today_followups" data-toggle="tab" aria-expanded="true"><button class="btn bg-pink waves-effect" type="button">Today Followups<span class="badge bg-grey"></span></button></a></li>

		                        <li role="presentation"><a href="#pending_followups" data-toggle="tab" ><button class="btn bg-red waves-effect" type="button">Pending Followups<span class="badge bg-grey"></span></button></a></li>

		                        <li role="presentation"><a href="#future_followups" data-toggle="tab" ><button class="btn bg-green waves-effect" type="button">Future Followups<span class="badge bg-grey"></span></button></a></li>
		                  </ul>

		                <div class="tab-content">
              	<!-- Today Followups -->
			                    <div id="today_followups" role="tabpanel" class="tab-pane fade in active in active">
			                        <div class="table-responsive">
			                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
			                            <thead>
			                                 <tr>
			                                 	<th>Unique ID</th>
			                                     <th>Student Name</th>
			                                     <th>Class</th>
			                                     <th>Student Status</th>
			                                     <th>Problem</th>
			                                     <th>EHR</th>
			                                 </tr>
			                            </thead>
			                            <tbody>
                        			        <?php if(!empty($regular_followup_cases)):?>

                        					<?php foreach($regular_followup_cases as $index => $doc ): ?>

                        					<?php if(isset($doc['regular_follow_up']['Follow_Up'])): ?>
                        					<?php $end_val = $doc['regular_follow_up']['Follow_Up']; ?>
                        					<?php $follow_up = end($end_val); ?>
                        					<?php if(isset($follow_up['next_scheduled_date'])) :?>
                        					<?php $date = $follow_up['next_scheduled_date']; ?>
                        					<?php $current_date = date('Y-m-d'); ?>
                        					<?php if($date == $current_date):?>

                        						<tr>
                        							<td><?php if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'])):?><?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?><?php else:?><?php echo "Notification Field";?><?php endif;?> </td>
                        							<td><?php if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref'])):?> <?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref'];?><?php else:?> <?php echo "Nil"; endif;?></td>
                        							<td><?php if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref'])):?> <?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref'];?><?php else:?> <?php echo "Nil"; endif;?></td>

                        							<td><?php if(isset($doc['doc_data']['widget_data']['page2']['Review Info']['Status'])):?> <?php echo $doc['doc_data']['widget_data']['page2']['Review Info']['Status'];?><?php else:?> <?php echo "Nil"; endif;?></td>

                        							<?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == 'Normal'): ?>

                        								<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'];?>
                        								<td><?php foreach ($identifiers as $identifier => $values) :?>
                        									
                        									<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]); ?>
                        								<?php if(!empty($var123)):?> 
                        								<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]) : "No Identifier";?>
                        								
                        								<?php endif;?>
                        								<?php endforeach;?></td>

                        							<?php else: ?>
                        							<?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == 'Emergency'):?>

                        								<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'];?>
                        								<td><?php foreach ($identifiers as $identifier => $values) :?>
                        									
                        									<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]); ?>
                        								<?php if(!empty($var123)):?> 
                        								<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]) : "No Identifier";?>
                        								
                        								<?php endif;?>
                        								<?php endforeach;?></td>
                        							
                        							<?php else: ?>
                        							<?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == 'Chronic'):?>

                        								<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'];?>
                        								<td><?php foreach ($identifiers as $identifier => $values) :?>
                        									
                        									<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]); ?>
                        								<?php if(!empty($var123)):?> 
                        								<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]) : "No Identifier";?>
                        								
                        								<?php endif;?>
                        								<?php endforeach;?></td>

                        						<?php endif;?>
                        						<?php endif;?>
                        						<?php endif;?>

                        						

                        						<form action='<?php echo URL."ttwreis_mgmt/ttwreis_reports_display_ehr_uid" ?>'accept-charset="utf-8" method="POST">

                        	                		<input type="hidden" name="uid" value="<?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>">
                        							<td><button class="btn bg-color-greenDark txt-color-white btn-xs">EHR</button></td>
                        						</form>
                        						
                        						</tr>
                        					<?php endif;?>
                        	                <?php endif;?>
                        	                <?php endif;?>
                        			   		<?php endforeach;?>
                        					<?php else: ?>
                        					<p> No docs found </p>
                        					<?php endif;?>
			                            </tbody>
			                        </table>
			                    	</div>
			                	</div>
			          	<!--Pending followups-->
			          			<div id="pending_followups" role="tabpanel" class="tab-pane fade">
		                        	<div class="table-responsive">
		                          		<table class="table table-bordered table-striped table-hover dataTable js-exportable">
			                             	<thead>
			                                 	<tr>
				                                    <th>Student Name</th>
			                                     <th>Class</th>
			                                     <th>Student Status</th>
			                                     <th>Problem</th>
			                                     <th>EHR</th>
			                                 	</tr>
			                             	</thead>
			                            	 <tbody>
                        			        <?php if(!empty($regular_followup_cases)):?>

                        					<?php foreach($regular_followup_cases as $index => $doc ): ?>

                        					<?php if(isset($doc['regular_follow_up']['Follow_Up'])): ?>
                        					<?php $end_val = $doc['regular_follow_up']['Follow_Up']; ?>
                        					<?php $follow_up = end($end_val); ?>
                        					<?php if(isset($follow_up['next_scheduled_date'])) :?>
                        					<?php $date = $follow_up['next_scheduled_date']; ?>
                        					<?php $current_date = date('Y-m-d'); ?>
                        					<?php if($date < $current_date):?>

                        						<tr>
                        							<td><?php if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'])):?><?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?><?php else:?><?php echo "Notification Field";?><?php endif;?> </td>
                        							<td><?php if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref'])):?> <?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref'];?><?php else:?> <?php echo "Nil"; endif;?></td>
                        							<td><?php if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref'])):?> <?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref'];?><?php else:?> <?php echo "Nil"; endif;?></td>

                        							<td><?php if(isset($doc['doc_data']['widget_data']['page2']['Review Info']['Status'])):?> <?php echo $doc['doc_data']['widget_data']['page2']['Review Info']['Status'];?><?php else:?> <?php echo "Nil"; endif;?></td>

                        							<?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == 'Normal'): ?>

                        								<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'];?>
                        								<td><?php foreach ($identifiers as $identifier => $values) :?>
                        									
                        									<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]); ?>
                        								<?php if(!empty($var123)):?> 
                        								<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]) : "No Identifier";?>
                        								
                        								<?php endif;?>
                        								<?php endforeach;?></td>

                        							<?php else: ?>
                        							<?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == 'Emergency'):?>

                        								<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'];?>
                        								<td><?php foreach ($identifiers as $identifier => $values) :?>
                        									
                        									<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]); ?>
                        								<?php if(!empty($var123)):?> 
                        								<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]) : "No Identifier";?>
                        								
                        								<?php endif;?>
                        								<?php endforeach;?></td>
                        							
                        							<?php else: ?>
                        							<?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == 'Chronic'):?>

                        								<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'];?>
                        								<td><?php foreach ($identifiers as $identifier => $values) :?>
                        									
                        									<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]); ?>
                        								<?php if(!empty($var123)):?> 
                        								<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]) : "No Identifier";?>
                        								
                        								<?php endif;?>
                        								<?php endforeach;?></td>

                        						<?php endif;?>
                        						<?php endif;?>
                        						<?php endif;?>

                        						

                        						<form action='<?php echo URL."ttwreis_mgmt/ttwreis_reports_display_ehr_uid" ?>'accept-charset="utf-8" method="POST">

                        	                		<input type="hidden" name="uid" value="<?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>">
                        							<td><button class="btn bg-color-greenDark txt-color-white btn-xs">EHR</button></td>
                        						</form>
                        						
                        						</tr>
                        					<?php endif;?>
                        	                <?php endif;?>
                        	                <?php endif;?>
                        			   		<?php endforeach;?>
                        					<?php else: ?>
                        					<p> No docs found </p>
                        					<?php endif;?>
			                            </tbody>
			                            </table>
			                        </div>
			                    </div>
			                <!--Future follwup-->
			                	<div id="future_followups" role="tabpanel" class="tab-pane fade">
		                        	<div class="table-responsive">
		                          		<table class="table table-bordered table-striped table-hover dataTable js-exportable">
			                             	<thead>
			                                 	<tr>
				                                    <th>Student Name</th>
			                                     <th>Class</th>
			                                     <th>Student Status</th>
			                                     <th>Problem</th>
			                                     <th>EHR</th>
			                                 	</tr>
			                             	</thead>
				                            <tbody>
	                        			        <?php if(!empty($regular_followup_cases)):?>

	                        					<?php foreach($regular_followup_cases as $index => $doc ): ?>

	                        					<?php if(isset($doc['regular_follow_up']['Follow_Up'])): ?>
	                        					<?php $end_val = $doc['regular_follow_up']['Follow_Up']; ?>
	                        					<?php $follow_up = end($end_val); ?>
	                        					<?php if(isset($follow_up['next_scheduled_date'])) :?>
	                        					<?php $date = $follow_up['next_scheduled_date']; ?>
	                        					<?php $current_date = date('Y-m-d'); ?>
	                        					<?php if($date > $current_date):?>

	                        						<tr>
	                        							<td><?php if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'])):?><?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?><?php else:?><?php echo "Notification Field";?><?php endif;?> </td>
	                        							<td><?php if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref'])):?> <?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref'];?><?php else:?> <?php echo "Nil"; endif;?></td>
	                        							<td><?php if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref'])):?> <?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref'];?><?php else:?> <?php echo "Nil"; endif;?></td>

	                        							<td><?php if(isset($doc['doc_data']['widget_data']['page2']['Review Info']['Status'])):?> <?php echo $doc['doc_data']['widget_data']['page2']['Review Info']['Status'];?><?php else:?> <?php echo "Nil"; endif;?></td>

	                        							<?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == 'Normal'): ?>

	                        								<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'];?>
	                        								<td><?php foreach ($identifiers as $identifier => $values) :?>
	                        									
	                        									<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]); ?>
	                        								<?php if(!empty($var123)):?> 
	                        								<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]) : "No Identifier";?>
	                        								
	                        								<?php endif;?>
	                        								<?php endforeach;?></td>

	                        							<?php else: ?>
	                        							<?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == 'Emergency'):?>

	                        								<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'];?>
	                        								<td><?php foreach ($identifiers as $identifier => $values) :?>
	                        									
	                        									<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]); ?>
	                        								<?php if(!empty($var123)):?> 
	                        								<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]) : "No Identifier";?>
	                        								
	                        								<?php endif;?>
	                        								<?php endforeach;?></td>
	                        							
	                        							<?php else: ?>
	                        							<?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == 'Chronic'):?>

	                        								<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'];?>
	                        								<td><?php foreach ($identifiers as $identifier => $values) :?>
	                        									
	                        									<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]); ?>
	                        								<?php if(!empty($var123)):?> 
	                        								<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]) : "No Identifier";?>
	                        								
	                        								<?php endif;?>
	                        								<?php endforeach;?></td>

	                        						<?php endif;?>
	                        						<?php endif;?>
	                        						<?php endif;?>

	                        						

	                        						<form action='<?php echo URL."ttwreis_mgmt/ttwreis_reports_display_ehr_uid" ?>'accept-charset="utf-8" method="POST">

	                        	                		<input type="hidden" name="uid" value="<?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>">
	                        							<td><button class="btn bg-color-greenDark txt-color-white btn-xs">EHR</button></td>
	                        						</form>
	                        						
	                        						</tr>
	                        					<?php endif;?>
	                        	                <?php endif;?>
	                        	                <?php endif;?>
	                        			   		<?php endforeach;?>
	                        					<?php else: ?>
	                        					<p> No docs found </p>
	                        					<?php endif;?>
				                            </tbody>
			                            </table>
			                        </div>
			                    </div>
			                <!--Close Cases-->
			                	<div id="closed_cases" role="tabpanel" class="tab-pane fade">
		                        	<div class="table-responsive">
		                          		<table class="table table-bordered table-striped table-hover dataTable js-exportable">
			                             	<thead>
			                                 	<tr>
				                                   <th>Student Name</th>
			                                     <th>Class</th>
			                                     <th>Student Status</th>
			                                     <th>Problem</th>
			                                     <th>EHR</th>
			                                 	</tr>
			                             	</thead>
			                            	<tbody>
			                            	</tbody>
			                            </table>
			                        </div>
			                    </div>    	      	
			            	</div>
			            <!--End of tab content  -->
	                	</div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>



<?php include('inc/footer_bar.php'); ?>