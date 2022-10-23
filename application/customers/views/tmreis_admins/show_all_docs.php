<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "All Docs";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css, jsoneditor.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["docs_comp"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'jsoneditor.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
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
							<h2>Document Compare</h2>
		
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
							
						<form class="smart-form form_submit" action="<?php echo URL?>tmreis_mgmt/diff_docs" method="post">
							
							<fieldset>
							<div class="row" id="doc_row">
								
							</div>
							</fieldset>
						
							<footer>
								<button type="submit" class="btn btn-primary">Diff</button>
								<button type="button" class="btn btn-default" onclick="window.history.back();">Back</button>
							</footer>
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
var docs = <?php echo $docs?>;
var doc_sec = "";
for(key in docs){
	doc_count = parseInt(key)+1;
	doc_sec = '<section class="col col-6"><label class="label">Document ' + doc_count + '</label><input name="doc_ids[]" id="check" value="'+ docs[key]['_id']['$id'] +'" type="checkBox" /><div id="editor_'+doc_count+'" class="json-editor"></div></section>';

	var opt = {
			change: function(data) { /* called on every change */ },
		    propertyclick: function(path) { /* called when a property is clicked with the JS path to that property */ }
	};
		//opt.propertyElement = '<input type="text" readonly>';  // element of the property field, <input> is default
		//opt.valueElement = '<input type="text" readonly>';   // element of the value field, <input> is default
		opt.matchedElements = [];
		opt.matchedElements_css = '';
		//console.log(opt.matchedElements);
		//console.log("doneeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee");
		$('#doc_row').append(doc_sec);
		$('#editor_'+doc_count+'').jsonEditor(docs[key]['doc_data']['widget_data'], opt);

	
}

$("#doc_row input:checkbox").click(function() {
    HandleCheckboxSelection("#doc_row", 2);
});

function GetSelectedCheckboxCount(selector) {
    var selectedCheckboxCount = 0;
    $(selector + " input:checkbox").each(function() {
        if ($(this).is(":checked")) {
            selectedCheckboxCount++;
        }
    });
    
    return selectedCheckboxCount;
}

function HandleCheckboxSelection(selector, selectionLimit) {
    
    if (GetSelectedCheckboxCount(selector) == selectionLimit) {
        $(selector + " .samplePromo").removeAttr("disabled");
        $(selector + " input:checkbox").each(function() {
            if ($(this).is(":checked")) {
                $(this).removeAttr("disabled");
            }
            else {
                $(this).attr("disabled", "true");
            }
        });
    }
    else {
        $(selector + " .samplePromo").attr("disabled", "true");
        $(selector + " input:checkbox").each(function() {
            $(this).removeAttr("disabled");
        });    
    }
}

    
});

</script>

