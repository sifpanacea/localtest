<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "TTWREIS Dashboard";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa submitted_requests"]["active"] = true;
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
				<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> <?php echo lang('admin_dash_home');?> <span>> <?php echo lang('admin_dash_board');?></span></h1>
			</div>
			
		</div>
		
		
		
		
		<div class="row">
		<div class="col-xs-12 col-sm-4 col-md-10 col-lg-10">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
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
								<header>
									<span class="widget-icon"> <i class="fa fa-comments"></i> </span>
									<h2>Default Tabs with border </h2>
				
								</header>
				
								<!-- widget div-->
								<div>
				
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
				
									</div>
									<!-- end widget edit box -->
				
									<!-- widget content -->
									<div class="widget-body">
				
										
										<hr class="simple">
										<ul id="myTab1" class="nav nav-tabs bordered">
											<li class="active">
												<a href="#s1" data-toggle="tab"> Normal <span class="badge bg-color-blue txt-color-white"><?php if(isset($hs_req_docs) && !empty($hs_req_docs)):?><?php echo count($hs_req_docs);?><?php endif;?></span></a>
											</li>
											<li>
												<a href="#s2" data-toggle="tab"> Emergency <span class="badge bg-color-blue txt-color-white"><?php if(isset($hs_req_emergency) && !empty($hs_req_emergency)):?><?php echo count($hs_req_emergency);?><?php endif;?></span></a>
											</li>
											<li>
												<a href="#s3" data-toggle="tab"> Chronic <span class="badge bg-color-blue txt-color-white"><?php if(isset($hs_req_chronic) && !empty($hs_req_chronic)):?><?php echo count($hs_req_chronic);?><?php endif;?></span></a>
											</li>
										</ul>
				
										<div id="myTabContent1" class="tab-content padding-10">
											<div class="tab-pane fade in active" id="s1">
												<table id="table_id" class="display">
												    <thead>
												        <tr>
												            <th>Unique Id's </th>
												            <th>Name </th>
												            <th>Diseases Type </th>
												            <th>Request Raised Time </th>
												            <th>Doctor Response Time </th>
												            <th>Doctor Name </th>
												            <th>Attachments </th>
												            <th>Access </th>
												        </tr>
												    </thead>
												    <tbody>
												        <?php if(!empty($hs_req_docs)):?>
														<?php foreach($hs_req_docs as $index => $doc ):?>
															<tr>
																<td><?php if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'])):?><?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?><?php else:?><?php echo "Notification Field";?><?php endif;?> </td>
																<td><?php if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref'])):?> <?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref'];?><?php else:?> <?php echo "No Name"; endif;?></td>
																<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'];?>
																<td><?php foreach ($identifiers as $identifier => $values) :?>
																	
																	<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]); ?>
																<?php if(!empty($var123)):?> 
																<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]) : "No Identifier";?>
																
															<?php endif;?>
															<?php endforeach;?></td>
																
																<td> <?php echo $doc['history'][0]['time'];?></td>
																<?php $last_doc = end($doc['history']);
															if(preg_match("/ttwreis.dr/i",$last_doc['submitted_by'])):?>
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
																<td><a href="<?php echo URL.'ttwreis_cc/access_submited_request_docs/'.$doc['doc_properties']['doc_id'].'';?>" class="btn bg-color-greenDark txt-color-white btn-xs">Access</a></td>
															</tr>
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
												            <th>Unique Id's</th>
												            <th>Name </th>
												            <th>Diseases Type</th>
												            <th>Request Raised Time</th>
												            <th>Doctor Response Time</th>
												            <th>Doctor Name</th>
												            <th>Attachments</th>
												            <th>Access</th>
												        </tr>
												    </thead>
												    <tbody>
												        <?php if(!empty($hs_req_emergency)):?>
														<?php foreach($hs_req_emergency as $index => $doc ):?>
															<tr>
																<td><?php if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'])):?><?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?><?php else:?><?php echo "No Unique ID"; endif;?> </td>

																<td><?php if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref'])):?> <?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref'];?><?php else:?> <?php echo "No Name"; endif;?></td>

																<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'];?>
																<td><?php foreach ($identifiers as $identifier => $values) :?>
																	
																	<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]); ?>
																<?php if(!empty($var123)):?> 
																<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]) : "No Identifier";?>
																
															<?php endif;?>
															<?php endforeach;?></td>
																
																<td> <?php echo $doc['history'][0]['time'];?></td>
																<?php $last_doc = end($doc['history']);
															if(preg_match("/ttwreis.dr/i",$last_doc['submitted_by'])):?>
																<td><?php echo $last_doc['time'];?></td> 
																<td><?php echo $last_doc['submitted_by_name'];?></td>
																<?php else:?>
																	<td><?php echo "Nill";?></td>
																	<td><?php echo "Doctor not to yet responded";?></td>
																<?php endif;?>

																<?php if(isset($doc['doc_data']['external_attachments']) && !empty($doc['doc_data']['external_attachments'])):?>
																<td>  <i class="fa fa-paperclip" aria-hidden="true"></i></td>
																<?php else:?>
																	<td>No Attachments</td>
															<?php endif;?>
																<td><a href="<?php echo URL.'ttwreis_cc/access_submited_request_docs/'.$doc['doc_properties']['doc_id'].'';?>" class="btn bg-color-greenDark txt-color-white btn-xs">Access</a></td>
															</tr>
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
												            <th>Unique Id's</th>
												            <th>Name </th>
												            <th>Diseases Type</th>
												            <th>Request Raised Time</th>
												            <th>Doctor Response Time</th>
												            <th>Doctor Name</th>
												            <th>Attachments</th>
												            <th>Access</th>
												        </tr>
												    </thead>
												    <tbody>
												        <?php if(!empty($hs_req_chronic)):?>
														<?php foreach($hs_req_chronic as $index => $doc ):?>
															<tr>
																<td><?php if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'])):?><?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?><?php else:?><?php echo "No Unique ID"; endif;?> </td>

																<td><?php if(isset($doc['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref'])):?> <?php echo $doc['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref'];?><?php else:?> <?php echo "No Name"; endif;?></td>

																<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'];?>
																<td><?php foreach ($identifiers as $identifier => $values) :?>
																	
																	<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]); ?>
																<?php if(!empty($var123)):?> 
																<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]) : "No Identifier";?>
																
															<?php endif;?>
															<?php endforeach;?></td>
																
																<td> <?php echo $doc['history'][0]['time'];?></td>
																<?php $last_doc = end($doc['history']);
															if(preg_match("/ttwreis.dr/i",$last_doc['submitted_by'])):?>
																<td><?php echo $last_doc['time'];?></td> 
																<td><?php echo $last_doc['submitted_by_name'];?></td>
																<?php else:?>
																	<td><?php echo "Nill";?></td>
																	<td><?php echo "Doctor not to yet responded";?></td>
																<?php endif;?>
																<?php if(isset($doc['doc_data']['external_attachments']) && !empty($doc['doc_data']['external_attachments'])):?>
																<td>  <i class="fa fa-paperclip" aria-hidden="true"></i></td>
																<?php else:?>
																	<td>No Attachments</td>
															<?php endif;?>
																<td><a href="<?php echo URL.'ttwreis_cc/access_submited_request_docs/'.$doc['doc_properties']['doc_id'].'';?>" class="btn bg-color-greenDark txt-color-white btn-xs">Access</a></td>
															</tr>
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
			

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>
<script src="<?php echo JS; ?>sweetalert.min.js"></script>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->

<script type="text/javascript" charset="utf8" src="<?php echo JS;?>jquery_new_version.dataTables.min.js"></script>

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