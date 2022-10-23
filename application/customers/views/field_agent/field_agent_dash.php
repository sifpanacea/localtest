<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "DMS";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css, jsoneditor.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["home"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs[""] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
     				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-group"></i> </span>
							<h2>Matched Document</h2>
		
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
							
						<form class="smart-form">
							
							<fieldset id="doc_list">
							</fieldset>
							
						</form>
							
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

<script src="<?php echo(JS.'jquery.jsoneditor.js');?>" type="text/javascript"></script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script type="text/javascript">
<!--

//-->
$(document).ready(function() {
	
	var docs = <?php echo($docs);?>;
	//console.log(docs);
	var doc_tab = "";
	for(var doc in docs){
		//console.log(docs[doc]);
		var count = parseInt(doc) + 1;
		var value = docs[doc];

		doc_tab = doc_tab + '<a id = "doc_span_'+count+'" href="doc_comp/' + value['document1'] + '/' + value['document2'] + '"<div class="row"><section class="col col-12"><label class="label">' + count + ')</label>' +  value['doc_id'] + ' Matchs --> ' + value['matched_count'] + '</a><a href=\'show_all_docs/' + value['ad_no'] + '\'>Open all matched documents</a></section></div>'
	}
	$('#doc_list').append(doc_tab);
});

</script>

