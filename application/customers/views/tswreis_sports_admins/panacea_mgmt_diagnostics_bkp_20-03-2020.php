<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Panacea Diagnostics";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa mgmt"]["sub"]["diagnostic"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["PANACEA Masters"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	
	<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header>
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Create New Diagnostic Center </h2>

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
	<?php
	$attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
	echo  form_open('panacea_mgmt/create_diagnostic',$attributes);
	?>
		<!--<form class="smart-form">-->
			<header>
				Please Enter The Diagnostic Information.
			</header>
			<fieldset>
			<div class="row">
			<section class="col col-4">
				<label class="label" for="first_name">District Name</label>
				<label class="select">
				<select name="dt_name" required>
					<option value="" selected="" disabled="">Select a district</option>
					<?php if(isset($distslist)): ?>
						<?php foreach ($distslist as $dist):?>
						<option value='<?php echo $dist['_id']?>' ><?php echo ucfirst($dist['dt_name'])?></option>
						<?php endforeach;?>
						<?php else: ?>
						<option value="1"  disabled="">No district entered yet</option>
					<?php endif ?>
				</select> <i></i>
			</label>
			</section>
			<section class="col col-4">
				<label class="label" for="first_name">Diagnostic Center Code</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="number" name="diagnostic_code" id="diagnostic_code" value="<?PHP echo set_value('diagnostic_code'); ?>" required>
				</label>
			</section>
			<section class="col col-4">
				<label class="label" for="first_name">Diagnostic Center Name</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="diagnostic_name" id="diagnostic_name" value="<?PHP echo set_value('diagnostic_name'); ?>" required>
				</label>
			</section>
			</div>
			<div class="row">
			<section class="col col-4">
				<label class="label" for="first_name">Phone Number</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="number" name="diagnostic_ph" id="diagnostic_ph" value="<?PHP echo set_value('diagnostic_ph'); ?>" required>
				</label>
			</section>
			<section class="col col-4">
				<label class="label" for="first_name">Mobile Number</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="number" name="diagnostic_mob" id="diagnostic_mob" value="<?PHP echo set_value('diagnostic_mob'); ?>" required>
				</label>
			</section>
			<section class="col col-4">
				<label class="label" for="first_name">Address</label>
				<label class="textarea textarea-expandable"> <i class="icon-append fa fa-envelope-o"></i>
				<textarea id="diagnostic_addr" name="diagnostic_addr" class="custom-scroll" ><?php echo set_value('diagnostic_addr');?></textarea>
			</section>
			</div>
			
			</fieldset>
			<footer>
				<button type="submit" class="btn bg-color-green txt-color-white submit" >
					Create
				</button>
				<button type="reset" class="btn btn-default">
					Clear
				</button>
			</footer>
		<?php echo form_close();?>

	</div>
	<!-- end widget content -->

</div>
<!-- end widget div -->

</div>
</article>

</div><!-- ROW -->
	
<div class="row">
     				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>All Diagnostic Centers <span class="badge bg-color-greenLight"><?php if(!empty($diagnosticscount)) {?><?php echo $diagnosticscount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body no-padding table-responsive">
					<table id="dt_basic" class="table table-striped table-bordered table-hover">
					<?php if ($diagnostics): ?>
					<thead>
					<tr>
						<th>District Name</th>
						<th>Diagnostic Center Code</th>
						<th>Diagnostic Center Name</th>
						<th>Phone Number</th>
						<th>Mobile Number</th>
						<th>Address</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($diagnostics as $diagnostic):?>
                    <tr>
						<td><?php echo ucwords($diagnostic["dt_name"]) ;?></td>
						<td><?php echo $diagnostic["diagnostic_code"] ;?></td>
						<td><?php echo ucwords($diagnostic["diagnostic_name"]) ;?></td>
						<td><?php echo $diagnostic["diagnostic_ph"] ;?></td>
						<td><?php echo $diagnostic["diagnostic_mob"] ;?></td>
						<td><?php echo ucwords($diagnostic["diagnostic_addr"]) ;?></td>
						<td><?php //echo anchor("panacea_mgmt/panacea_mgmt_diagnostic/".$diagnostic['_id'], lang('app_edit')) ;?>
						
						<a class='ldelete' href='<?php echo URL."panacea_mgmt/panacea_mgmt_delete_diagnostic/".$diagnostic['_id'];?>'>
                			<?php echo lang('app_delete')?>
                			</a>
						</td>
					</tr>
					<?php endforeach;?>
					<?php else: ?>
        			<p>
          				<?php echo "No state entered yet.";?>
        			</p>
        			<?php endif ?>
									</tbody>
									<?php if($links):?>
									<tfoot>
									
                      <tr>
                         <td colspan="5">
                            <?php echo $links; ?>
                         </td>
                      </tr>
					   
				    </tfoot>
                   <?php endif ?>
								</table>
		
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
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

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<?php 
	//include footer
	include("inc/footer.php"); 
?>