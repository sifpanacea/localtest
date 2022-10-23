<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Manage State";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["masters"]["sub"]["state"]["active"] = true;
include("inc/nav.php");

?>
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
	<h2>Create New State </h2>

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
	$attributes = array('class' => 'smart-form','id'=>'smart-form-register','name'=>'userform');
	echo  form_open('schoolhealth_admin_portal/create_state',$attributes);
	?>
		<!--<form class="smart-form">-->
			<header>
				Please Enter The State Information.
			</header>
			<fieldset>
			<div class="row">
			
			<section class="col col-4">
				<label class="label" for="first_name">State Code</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="st_code" id="st_code" value="<?PHP echo set_value('st_code'); ?>" required>
				</label>
			</section>
			<section class="col col-4">
				<label class="label" for="first_name">State Name</label>
					<label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="st_name" id="st_name" value="<?PHP echo set_value('st_name'); ?>" required>
				</label>
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
							<h2>All States<span class="badge bg-color-greenLight"><?php if(!empty($statcount)) {?><?php echo $statcount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>
		
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
					<table id="dt_basic" class="table table-striped table-bordered table-hover">
					<?php if ($states): ?>
					<tr>
						<th>State Name</th>
						<th>State Code</th>
						
						<th>Action</th>
					</tr>
					<?php foreach ($states as $dist):?>
                    <tbody>
					<tr>
						<td><?php echo ucwords($dist['st_name']) ;?></td>
						<td><?php echo ucwords($dist['st_code']) ;?></td>
						
						<td id="deletestate">
						<a class='ldelete' href='<?php echo URL."schoolhealth_admin_portal/delete_state/".$dist['_id'];?>'>
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
<script type="text/javascript">

	// Delete My Apps 
	$('#deletestate a').click(function(e) {
	//get the link
	var $this = $(this);
	$.delURL = $this.attr('href');

	// ask verification
	$.SmartMessageBox({
		title : "<i class='fa fa-minus-square txt-color-orangeDark'></i> Do you want to delete this state ?",
		buttons : '[No][Yes]'

	}, function(ButtonPressed) {
		if (ButtonPressed == "Yes") {
			setTimeout(deletestate, 1000)
		}

	});
	e.preventDefault();
	});

	/*
	 * Delete State
	 */

	function deletestate() {
		window.location = $.delURL;
	}
</script>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<?php 
	//include footer
	include("inc/footer.php"); 
?>