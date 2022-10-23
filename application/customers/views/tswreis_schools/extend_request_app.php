<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Extend Request";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "";
include("inc/header.php");
//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["extend_request"]["active"] = true;
include("inc/nav.php");

?>
<style>

</style>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo(CSS.'admin_dash_js.css'); ?>" media="screen" rel="stylesheet" type="text/css" />



<!-- ==========================CONTENT STARTS HERE ========================== -->
		<!-- MAIN PANEL -->
		<div id="main" role="main">
		
		<div id="content" class="app_render1"><div class="table-wrap custom-scroll animated fast fadeInRight"><table id="inbox-table" class="table table-striped table-hover"><tbody class="inbox_add">
		
		<div class="device1" style="border: 0px outset #575757;box-shadow: 0px 0px 0px #DFDFDF;border-radius: 0px; margin-left:20px">
		
		
		
		


		
		<?php echo $hs_page; ?>
		
		
		
		
		
		
		
		
		</div>
		
		
		</tbody></table></div>
		
		
			
		</div>


<!-- ==========================CONTENT ENDS HERE ========================== -->


<!-- PAGE FOOTER -->
<?php
	// include page footer
	include("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<script>
$(document).ready(function() {
	<?php if($message) { ?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; <?php echo lang('common_message');?>",
				content : "<?php echo $message;?>",
				color : "#C46A69",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
<?php } ?>

	$("#search").remove();
	//$("#inbox-table").remove();
	$(".hs_attachments").remove();
	$(".table-bordered").remove();
	$(".external_files_show").remove();
	$("#page1_StudentInfo_UniqueID").prop( "readonly", true );
	$("#page1_StudentInformation_Class").prop( "readonly", true );
	$("#page1_StudentInfo_Name").prop( "readonly", true );
	$("#page1_StudentInfo_District").prop( "readonly", true );
	$("#page1_StudentInfo_SchoolName").prop( "readonly", true );
	$("#page1_StudentInfo_Section").prop( "readonly", true );
	
	// $('.desktop-detected').contents(':gt(1)').remove();
	//'<form method="post" action="../hs_req_extend" id="form_extend"><input type="file" id="attachments[]" name="attachments[]" class="" multiple/><input type="hidden" id="form_data" name="form_data"/></form>'
	
	
	//$('#2').after('<br><br><input type="file" id="attachments[]" name="attachments[]" class="" multiple/><button type="button" id="form_sbt" class="btn btn-primary pull-right">Submit</button>');
	$('#2').after('<br><br><form method="POST" enctype="multipart/form-data" action="../hs_req_extend" id="form_extend"><input type="file" id="attachments[]" name="attachments[]" class="" multiple/><button type="button" id="form_sbt" class="btn btn-primary pull-right">Submit</button><input type="hidden" id="form_data" name="form_data"/></form>');
	
	$('#form_sbt').on('click', function() {
  var data = $('#web_view').serializeArray()
  
  // var data = $('#web_view').serializeArray().reduce(function(obj, item) {
    // obj[item.name] = item.value;
    // return obj;
	// }, {});
	var formData = new FormData($('#web_view')[0]);
	console.log(formData)
	var data_chk = data.reduce(function(obj, item) {
    
	if(item.name == "ac_page1_ProblemInfo_Identifier[]"){
		if(obj[item.name] === undefined){
			obj[item.name] = item.value;
		}else{
			var ext_val = obj[item.name];
			var vall = ext_val+"^^"+item.value
			obj[item.name] = vall;
		}
		
	}else{
		obj[item.name] = item.value;
	}
    return obj;
	}, {});
	
  console.log(data)
  console.log(data_chk)
  $("#form_data").val(JSON.stringify(data_chk));
  $("#form_extend").submit();
  
});
	
	//web_view
})
</script>
