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
$page_nav["reports"]["sub"]["ehr"]["active"] = true;
include("inc/nav.php");

?>
<style>
.unique_id
{
	width:85px;	
	margin-top: 15px;
	font-size:15px;
	font-family:Segoe UI;
	color:black;
}
.student_code
{
	width: 140px;
    margin-right: 10px;
    border-color: white;
    border-style: solid;
    border-bottom-color: #333;
}
.text_input
{
font-family:Segoe UI;
color:black;
font-size:12px;
width:70px;
height:15px;
}
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Reports"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">
						
<header class="bg-color-orange">
	<span class="widget-icon"> <i class="fa fa-user"></i> </span>
	<h2>Search Student's Dcoument </h2>

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
	echo  form_open('ttwreis_schools/reports_display_ehr_uid',$attributes);
	?>
		<!--<form class="smart-form">-->
			<header>
				<strong>Please Enter The Student Unique ID.</strong>
			</header>
			<fieldset>
			<div class="row">
			<section class="col col-2">
				
				<?php if(isset($district_code) && isset($school_code)):?>
				   <label class='labelform unique_id'><?php echo $district_code."_".$school_code."_";?></label>
				   <input class='text_input student_code' id='student_code' type='text' name='student_code' minlength='3' maxlength='25' required/>
				   <input class='hide' id='student_unique_id' type='text' name='student_unique_id'/>
				   <?php else : ?>
				   <label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="uid" id="uid" value="<?PHP echo set_value('uid'); ?>" required>
					<?php endIf; ?>
				</label>
			</section>
			<section class="col col-3">
				<button type="submit" class="btn bg-color-green txt-color-white submit btn-sm" >
					Search
				</button>
				<button type="reset" class="btn btn-default bg-color-greenLight btn-sm txt-color-white">
					Clear
				</button>
			</section>
			</div>
			
			</fieldset>
			<footer>
				
			</footer>
		<?php echo form_close();?>

	</div>
	<!-- end widget content -->

</div>
<!-- end widget div -->

</div>
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

<script>
$(document).ready(function(){
	$('.submit').click(function(){
	    var query_ref_label = $('.unique_id').text() || '';
		var query_ref_input = $('.student_code').val() || '';
		var query_ref = ""+query_ref_label+""+query_ref_input+"";
		console.log(query_ref);
		$('#student_unique_id').val(query_ref);
		var uid = $('#student_unique_id').val();
		console.log('uid',uid);
	})
	
				
			
})	
</script>