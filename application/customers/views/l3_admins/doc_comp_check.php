<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Doc Compare";

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
							
						<form class="smart-form">
							
							<fieldset>
							<div class="row">
								<section class="col col-6"><label class="label">Document 1</label>
								<div id="editor_1" class="json-editor"></div>	
								</section>

								<section class="col col-6 pull-right"><label class="label">Document 2</label>
								<div id="editor_2" class="json-editor"></div>
								</section>
							</div>
							</fieldset>
							
						</form>
						
						<form class="smart-form form_submit" action="../../delete_dup_doc" method="post">
							
							<fieldset>
							<div class="row">
								<section class="col col-6">
								<label class="label">Which document to keep ?</label>
											<div class="inline-group">
												<label class="radio">
													<input type="radio" name="doc_id" id="doc_id1" value="<?php echo $doc2_doc_id?>">
													<i></i>Document 1</label>
												<label class="radio">
													<input type="radio" name="doc_id" id="doc_id2" value="<?php echo $doc1_doc_id?>">
													<i></i>Document 2</label>
											</div>
								</section>

								
							</div>
							</fieldset>
							<footer>
								<button type="submit" class="btn btn-primary">Submit</button>
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
var doc_id_of_doc1 = "<?php echo $doc1_doc_id?>";
var doc_id_of_doc2 = "<?php echo $doc2_doc_id?>";

var doc_json1 = <?php echo($doc1);?>;
var doc_json2 = <?php echo($doc2);?>;
var matched_array = [];
var json_path = [];

construct(doc_json1, doc_json2);

var opt = { 
    change: function(data) { /* called on every change */ },
    propertyclick: function(path) { /* called when a property is clicked with the JS path to that property */ }
};
//opt.propertyElement = '<input type="text" readonly>';  // element of the property field, <input> is default
//opt.valueElement = '<input type="text" readonly>';   // element of the value field, <input> is default
opt.matchedElements = matched_array;
opt.matchedElements_css = 'STYLE="background-color: #72A4D2;"';
//console.log(opt.matchedElements);
//console.log("doneeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee");
$('#editor_1').jsonEditor(doc_json1, opt);
$('#editor_2').jsonEditor(doc_json2, opt);


	function construct(document1, document2) {
		
		var key;
	    for (key in document1) {//isEmptyObject({});
	        if (!document1.hasOwnProperty(key) && !document2.hasOwnProperty(key)) continue;
			
			console.log('keyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy');
			console.log(key);
			
			//if (!document2.hasOwnProperty(key)) continue;

	        //if (isObject(document1[key]) || isArray(document1[key])) 
		    {
	        	json_path.push(key);
		        //console.log(json_path);
	        }
	        
	        if (!isObject(document1[key])) {
	            if(!isArray(document1[key])){
	            	var val_doc1 = stringify(document1[key]);
	                var val_doc2 = stringify(document2[key]);
	                // console.log("val_doc1");
	                // console.log(val_doc1);
	                // console.log("val_doc2");
	                // console.log(val_doc2);
	                if(val_doc1.localeCompare(val_doc2) != 0){
	                    //console.log(key);
	                	matched_array.push(key);
	                	var back_track_array = back_track_fn(json_path, document1[key], doc_json1);
	                	//console.log(back_track_array);
	                }else{
	                	var index = json_path.indexOf(key);
	                	//console.log("________iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii__________________");
	                	//console.log(index);
	                	if (index > -1) {
	                		//console.log("________eeeeeeeeeeeeeeeeeeee__________________");
	                		json_path.splice(index, 1);
	                	}
		            }
	            }
	        }
	        
	        if (isObject(document1[key]) || isArray(document1[key])) {
				if (!$.isEmptyObject(document1[key]) && !$.isEmptyObject(document2[key]))
	            construct(document1[key], document2[key]);
	        }
	        //json_path = [];
	    }
	}
	function isObject(o) { return Object.prototype.toString.call(o) == '[object Object]'; }
    function isArray(o) { return Object.prototype.toString.call(o) == '[object Array]'; }
    function stringify(obj) {
        var res;
        try { res = JSON.stringify(obj); }
        catch (e) { res = 'null'; error('JSON stringify failed.'); }
        return res;
    }

    function back_track_fn(json_path, value, document1){
        //var doc_json1 = doc_json1;
        //console.log(value);
        //console.log("==============================================");
        

    	for (key in document1) {
	        if (!document1.hasOwnProperty(key)) continue;
	        //console.log("innnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn");
			//console.log(key);
	        if(document1[key] == value){
	        	//doc_json1
	        	//console.log("________________________________________________________");
	        	//console.log(key);
		        
	        }
	        
	        if (isObject(document1[key]) || isArray(document1[key])) {
	        	back_track_fn(json_path, value, document1[key]);
	        }
	    }
    	
//     	var value_location = json_path.search(value);
//     	var string = json_path.substr(0, value_location);
//     	var back_track = string.split(".");
//     	return back_track;
    }

//     $('.submit_doc').on('click',function(){
//         alert("hiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiii");
//     });
    
});

</script>

