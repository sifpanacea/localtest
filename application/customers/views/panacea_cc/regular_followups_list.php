<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Panacea Dashboard";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa regular_followups"]["active"] = true;
include("inc/nav.php");

?>

<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->

<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<link href="<?php echo(CSS.'site.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo(CSS.'jquery.dataTables.min.css'); ?>">
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">
	<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> <?php echo lang('admin_dash_home');?> <span> <?php echo lang('admin_dash_board');?></span></h1>
			</div>
			
		</div>	
		
		<div class="row">
		<div class="col-xs-12 col-sm-4 col-md-10 col-lg-10">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
							
								<header>
									<span class="widget-icon"> <i class="fa fa-comments"></i> </span>
									<h2>Default Tabs with border </h2>
				
								</header>
				
								<!-- widget div-->
								<div>
				
									<!-- widget content -->
		<div class="widget-body">

		<hr class="simple">
		<ul id="myTab1" class="nav nav-tabs bordered">
		<li class="active">
		<a href="#s1" data-toggle="tab"> Today Followups <span class="badge bg-color-blue txt-color-white"><?php if(isset($hs_req_docs) && !empty($hs_req_docs)):?><?php echo count($hs_req_docs);?><?php endif;?></span></a>
		</li>
		<li>
		<a href="#s2" data-toggle="tab"> Pending Followups <span class="badge bg-color-blue txt-color-white"><?php if(isset($hs_req_emergency) && !empty($hs_req_emergency)):?><?php echo count($hs_req_emergency);?><?php endif;?></span></a>
		</li>
		<li>
		<a href="#s3" data-toggle="tab"> Future Followups <span class="badge bg-color-blue txt-color-white"><?php if(isset($hs_req_chronic) && !empty($hs_req_chronic)):?><?php echo count($hs_req_chronic);?><?php endif;?></span></a>
		</li>
		</ul>

		<div id="myTabContent1" class="tab-content padding-10">
		<div class="tab-pane fade in active" id="s1">
		<table id="table_id" class="display">
		    <thead>
		        <tr>
		            <th>Unique Id's </th>
		            <th>Name </th>
		            <th>Class </th>
		            <th>Student Status </th>
		            <th>Diseases Type </th>
		            <th>Next Followup Date</th>
		            <th>Alloted To</th>
		            <th>Feed Data </th>
		            <th>EHR </th>
		            <th>Close Case </th>
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
					
					<?php $follows = end($doc['regular_follow_up']['Follow_Up']); ?>
					<?php $current_date = date('Y-m-d'); ?>
					<td> 
						  <?php if($follows['next_scheduled_date'] == $current_date): ?>
						    <span class="btn bg-color-red txt-color-white btn-xs">
						     Follow-up Today.
						    </span>
						   
						    <?php else :?>
						    <span class="btn bg-color-greenDark txt-color-white btn-xs">
						        <?php echo $follows['next_scheduled_date'];?>
						    </span>
						  
						<?php endif;?>
						
					</td>
					<td><?php echo $doc['regular_follow_up']['CC_follow_name']; ?></td>
					<?php if(isset($doc['regular_follow_up']['Follow_Up']) && !empty($doc['regular_follow_up']['Follow_Up'])){  ?>
					  <?php $follow_up = end($doc['regular_follow_up']['Follow_Up']); ?>
					 
					  <?php $medicine_detailss = $follow_up['medicine_details']; ?>
					  <?php $description_detailss = $follow_up['followup_desc']; ?>
					  
					<?php } ?>
										
					 <td><a href="javascript:void('0')" uid='<?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>' cid='<?php echo $doc['doc_properties']['doc_id'];?>' hs_num='<?php if(isset($doc['doc_data']['school_contact_details']['health_supervisor']['mobile'])):?> <?php echo $doc['doc_data']['school_contact_details']['health_supervisor']['mobile'];?><?php else:?> <?php echo "No HS"; endif;?>' prc_num='<?php if(isset($doc['doc_data']['school_contact_details']['principal']['mobile'])):?> <?php echo $doc['doc_data']['school_contact_details']['principal']['mobile'];?><?php else:?> <?php echo "No Principal"; endif;?>' medicine = '<?php if(isset($medicine_detailss)):?><?php echo $medicine_detailss;?><?php else:?><?php echo "Nil";?> <?php endif;?>' description = '<?php if(isset($description_detailss)):?><?php echo $description_detailss;?><?php else:?><?php echo "Nil";?> <?php endif;?>' class='schedule_followup'><button class="btn bg-color-greenDark txt-color-white btn-xs">Feed Data</button></a></td>

					<form action='<?php echo URL."panacea_cc/panacea_reports_display_ehr_uid" ?>'accept-charset="utf-8" method="POST">

                		<input type="hidden" name="uid" value="<?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>">
						<td><button class="btn bg-color-greenDark txt-color-white btn-xs">EHR</button></td>
					</form>
					<!-- <td><a href="" class="btn bg-color-greenDark txt-color-white btn-xs">EHR</a></td> -->
					
					<form action='<?php echo URL."panacea_cc/close_followup_request" ?>' accept-charset="utf-8" method="POST">
					    <input type="hidden" name="followupcid" value="<?php echo $doc['doc_properties']['doc_id']; ?>">
					    <td><button class="btn bg-color-red txt-color-white btn-xs" onClick="return confirm('Are you sure, you want to close this Case from Follow Up list?');">close</button></td>
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
					<div class="tab-pane fade" id="s2">
		<table id="table_id" class="display">
		    <thead>
		        <tr>
		            <th>Unique Id's </th>
		            <th>Name </th>
		            <th>Class </th>
		            <th>Student Status </th>
		            <th>Diseases Type </th>
		            <th>Next Followup Date</th>
		            <th>Alloted To</th>
		            <th>Feed Data </th>
		            <th>EHR </th>
		            <th>Close Case </th>
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
					
					<?php $follows = end($doc['regular_follow_up']['Follow_Up']); ?>
					<?php $current_date = date('Y-m-d'); ?>
					<td> 
						  <?php if($follows['next_scheduled_date'] == $current_date): ?>
						    <span class="btn bg-color-red txt-color-white btn-xs">
						     Follow-up Today.
						    </span>
						   
						    <?php else :?>
						    <span class="btn bg-color-greenDark txt-color-white btn-xs">
						        <?php echo $follows['next_scheduled_date'];?>
						    </span>
						  
						<?php endif;?>
						
					</td>
					<td><?php echo $doc['regular_follow_up']['CC_follow_name']; ?></td>
					<?php if(isset($doc['regular_follow_up']['Follow_Up']) && !empty($doc['regular_follow_up']['Follow_Up'])){  ?>
					  <?php $follow_up = end($doc['regular_follow_up']['Follow_Up']); ?>
					 
					  <?php $medicine_detailss = $follow_up['medicine_details']; ?>
					  <?php $description_detailss = $follow_up['followup_desc']; ?>
					  
					<?php } ?>

					 <td><a href="javascript:void('0')" uid='<?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>' cid='<?php echo $doc['doc_properties']['doc_id'];?>' hs_num='<?php if(isset($doc['doc_data']['school_contact_details']['health_supervisor']['mobile'])):?> <?php if(isset($doc['doc_data']['school_contact_details']['health_supervisor']['mobile'])):?> <?php echo $doc['doc_data']['school_contact_details']['health_supervisor']['mobile'];?><?php else:?> <?php echo "No HS"; endif;?><?php else:?> <?php echo "No HS"; endif;?>' prc_num='<?php if(isset($doc['doc_data']['school_contact_details']['principal']['mobile'])):?> <?php echo $doc['doc_data']['school_contact_details']['principal']['mobile'];?><?php else:?> <?php echo "No Principal"; endif;?>' medicine = '<?php if(isset($medicine_detailss)):?><?php echo $medicine_detailss;?><?php else:?><?php echo "Nil";?> <?php endif;?>' description = '<?php if(isset($description_detailss)):?><?php echo $description_detailss;?><?php else:?><?php echo "Nil";?> <?php endif;?>' class='schedule_followup'><button class="btn bg-color-greenDark txt-color-white btn-xs">Feed Data</button></a></td>

					
					<form action='<?php echo URL."panacea_cc/panacea_reports_display_ehr_uid" ?>'accept-charset="utf-8" method="POST">

                		<input type="hidden" name="uid" value="<?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>">
						<td><button class="btn bg-color-greenDark txt-color-white btn-xs">EHR</button></td>
					</form>
					
					<form action='<?php echo URL."panacea_cc/close_followup_request" ?>' accept-charset="utf-8" method="POST">
					    <input type="hidden" name="followupcid" value="<?php echo $doc['doc_properties']['doc_id']; ?>">
					    <td><button class="btn bg-color-red txt-color-white btn-xs" onClick="return confirm('Are you sure, you want to close this Case from Follow Up list?');">close</button></td>
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
	<div class="tab-pane fade" id="s3">
		<table id="table_id" class="display">
		   <thead>
		        <tr>
		           
		            <th>Unique Id's </th>
		            <th>Name </th>
		            <th>Class </th>
		            <th>Student Status </th>
		            <th>Diseases Type </th>
		            <th>Next Followup Date</th>
		            <th>Alloted To</th>
		            <th>Feed Data </th>
		            <th>EHR </th>
		            <th>Close Case </th>
		     
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
					
					<?php $follows = end($doc['regular_follow_up']['Follow_Up']); ?>
					<?php $current_date = date('Y-m-d'); ?>
					<td> 
						  <?php if($follows['next_scheduled_date'] == $current_date): ?>
						    <span class="btn bg-color-red txt-color-white btn-xs">
						     Follow-up Today.
						    </span>
						   
						    <?php else :?>
						    <span class="btn bg-color-greenDark txt-color-white btn-xs">
						        <?php echo $follows['next_scheduled_date'];?>
						    </span>
						  
						<?php endif;?>
						
					</td>
					<td><?php echo $doc['regular_follow_up']['CC_follow_name']; ?></td>
					<?php if(isset($doc['regular_follow_up']['Follow_Up']) && !empty($doc['regular_follow_up']['Follow_Up'])){  ?>
					  <?php $follow_up = end($doc['regular_follow_up']['Follow_Up']); ?>
					 
					  <?php $medicine_detailss = $follow_up['medicine_details']; ?>
					  <?php $description_detailss = $follow_up['followup_desc']; ?>
					  
					<?php } ?>

					 <td><a href="javascript:void('0')" uid='<?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>' cid='<?php echo $doc['doc_properties']['doc_id'];?>' hs_num='<?php if(isset($doc['doc_data']['school_contact_details']['health_supervisor']['mobile'])):?> <?php echo $doc['doc_data']['school_contact_details']['health_supervisor']['mobile'];?><?php else:?> <?php echo "No HS"; endif;?>' prc_num='<?php if(isset($doc['doc_data']['school_contact_details']['principal']['mobile'])):?> <?php echo $doc['doc_data']['school_contact_details']['principal']['mobile'];?><?php else:?> <?php echo "No Principal"; endif;?>' medicine = '<?php if(isset($medicine_detailss)):?><?php echo $medicine_detailss;?><?php else:?><?php echo "Nil";?> <?php endif;?>' description = '<?php if(isset($description_detailss)):?><?php echo $description_detailss;?><?php else:?><?php echo "Nil";?> <?php endif;?>' class='schedule_followup'><button class="btn bg-color-greenDark txt-color-white btn-xs">Feed Data</button></a></td>

					
					<form action='<?php echo URL."panacea_cc/panacea_reports_display_ehr_uid" ?>'accept-charset="utf-8" method="POST">

                		<input type="hidden" name="uid" value="<?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>">
						<td><button class="btn bg-color-greenDark txt-color-white btn-xs">EHR</button></td>
					</form>
					
					<form action='<?php echo URL."panacea_cc/close_followup_request" ?>' accept-charset="utf-8" method="POST">
					    <input type="hidden" name="followupcid" value="<?php echo $doc['doc_properties']['doc_id']; ?>">
					    <td><button class="btn bg-color-red txt-color-white btn-xs" onClick="return confirm('Are you sure, you want to close this Case from Follow Up list?');">close</button></td>
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

			</div>
			<!-- end widget content -->
			
		</div>
		<!-- end widget div -->

	</div>
	<br>
	<br>
							
		<!-- end widget div -->
				
		</div>
		</article>
		</div>
		<!-- end widget -->
  </div>
</div>

</div>
<!-- END MAIN PANEL -->

        <!-- Modal For Feed Data -->
<?php
    $attributes = array('class' => '','id'=>'followup_form','name'=>'userform');
    echo  form_open('panacea_cc/update_regular_followup_feed_data',$attributes);
 ?>
     <div class="modal fade" id="followup_modal" tabindex="-1" role="dialog">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="defaultModalLabel">Followup Data Feeding</h4>
        </div>
        <div class="modal-body">
            <div class="row clearfix">
                  <div class="list-group">
                <a href="javascript:void(0);" class="list-group-item active">
                    <h4 class="list-group-item-heading">Previous Follow-up Data</h4>
                   
                </a>
                <a href="javascript:void(0);" class="list-group-item">
                    <h4 class="list-group-item-heading">Student Health ID</h4>
                    <p class="list-group-item-text" id="student_health_id">
                     
                    </p>
                </a> 
                <a href="javascript:void(0);" class="list-group-item">
                    <h4 class="list-group-item-heading">HS Number</h4>
                    <p class="list-group-item-text" id="student_hs_num">                     
                    </p>
                </a> 
                <a href="javascript:void(0);" class="list-group-item">
                    <h4 class="list-group-item-heading">Principal Number</h4>
                    <p class="list-group-item-text" id="student_prc_num">                     
                    </p>
                </a>
                <a href="javascript:void(0);" class="list-group-item">
                    <h4 class="list-group-item-heading">Medicine Details</h4>
                    <p class="list-group-item-text" id="medicine">
                     
                    </p>
                </a>
                <a href="javascript:void(0);" class="list-group-item">
                    <h4 class="list-group-item-heading">Description</h4>
                    <p class="list-group-item-text" id="description">
                         
                    </p>
                </a>
            </div>
            <hr>
                <input type="hidden" name="case_id" id="case_id">
                <input type="hidden" name="student_hs_numb" id="student_hs_numb">
                <input type="hidden" name="student_prc_numb" id="student_prc_numb">
                <input type="hidden" name="student_id" id="student_id">

                <div class="col-sm-12">
                     <label>Date :</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" name="feeding_date" class="form-control" class="hasDatepicker" value="<?php echo date("Y-m-d");?>" placeholder="Select Date" readonly/>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-12">
                     <label>Medicine Details :</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" name="medicine_details" class="form-control" placeholder="Enter Medicine Details If any" required="required" />
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                     <label>Description If any :</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="text" name="followup_desc" class="form-control" placeholder="Give Description If any" required="required"  />
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                     <label>Next Follow-up Date :</label>
                    <div class="form-group">
                            <div class="form-line">
                            <input type="text" id="next_scheduled_date" name="next_scheduled_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="" required="required">
                            </div>
                    </div>
                </div>
               
            </div>

        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-link waves-effect">Update</button>
            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal" id="reset_close">CLOSE</button>
        </div>
    </div>
</div>
</div>
<?php form_close(); ?>
			

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>
<script src="<?php echo JS; ?>sweetalert.min.js"></script>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->

<script type="text/javascript" charset="utf8" src="<?php echo JS;?>jquery_new_version.dataTables.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('click','.schedule_followup',function(){

		 var uid = $(this).attr('uid');
		 //alert(uid);
		 var cid = $(this).attr('cid');
		 var hs_num = $(this).attr('hs_num');
		 var prc_num = $(this).attr('prc_num');
		  //alert(hs_num);
		
		 var medicine = $(this).attr('medicine');
		 var description = $(this).attr('description');
		 console.log("medicine", medicine);

		 $('#student_health_id').text(uid);
		 $('#student_id').val(uid);

		 $('#case_id').val(cid);
		 $('#student_hs_num').text(hs_num);
		 $('#student_hs_numb').val(hs_num);
		 $('#student_prc_num').text(prc_num);
		 $('#student_prc_numb').val(prc_num);
		 $('#medicine').text(medicine);
		 $('#description').text(description);
		 $("#followup_modal").modal("show")
		  
		});
	});
</script>
<script>
$(document).ready( function () {
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
    $('.display').DataTable({
    	"ordering":false
    });
} );


</script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>
