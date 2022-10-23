<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Panacea Screening";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array. 
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["screening_report"]["sub"]["update_screening"]["active"] = true;
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
		//$breadcrumbs["New Crumb"] => "https://url.com"
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
	<h2>Search Student's Document by Hospital Unique ID </h2>

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
	echo  form_open('panacea_cc/search_screening_info_by_unique_id',$attributes);
	?>
		<!--<form class="smart-form">-->
			<header>
				Please Enter The Hospital Unique ID.
			</header>
			<fieldset>
			<div class="row">
			<section class="col col-6">
				<label class="label" for="first_name">Hospital Unique ID</label>
				
					<input type="text" name="unique_id" id="unique_id" required>
					
				</label>
			</section>
			</div>
			
			</fieldset>
			<footer>
				<button type="submit" class="btn bg-color-blue txt-color-white submit" >
					Search
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
	

				

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>
<script>
$(document).ready(function(){
<?php if($message) {?>
	
			$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message!",
				content : "<?php echo $message?>",
				color : "#C79121",
				iconSmall : "fa fa-bell bounce animated"
			});
			<?php } ?>
})
</script>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<?php 
	//include footer
	include("inc/footer.php"); 
?>
<script src="<?php echo JS; ?>sweetalert.min.js"></script>
<script>
$(document).ready(function(){
	/*$('.submit').click(function(){
	    var query_ref_label = $('.unique_id').text() || '';
		var query_ref_input = $('.student_code').val() || '';
		var query_ref = ""+query_ref_label+""+query_ref_input+"";
		console.log(query_ref);
		$('#student_unique_id').val(query_ref);
		var uid = $('#student_unique_id').val();
		console.log('uid',uid);
	})
	
				*/
		<?php if($this->session->flashdata('success')): ?>

         swal({
                title: "Success!",
                text: "<?php echo $this->session->flashdata('success'); ?>",
                icon: "success",
    
          });
      
<?php endif; ?>
			
})	
</script>