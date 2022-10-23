<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Add News Feed";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["news_feed"]["sub"]["add_nf"]["active"] = true;
include("inc/nav.php");

?>

<link href="<?php echo(CSS.'bootstrap-datetimepicker.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "https://url.com"
		$breadcrumbs["News Feeds"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
       <article class="col-sm-12 col-md-12 col-lg-6">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-10" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
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
							<span class="widget-icon"> <i class="fa fa-cloud-upload"></i> </span>
							<h2>Add a news feed </h2>
		                </header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
											
											<?php 
											$attributes = array('class' => 'smart-form','id'=>'news_feed_form');
											echo form_open_multipart((isset($news_feed)? 'panacea_cc/update_news_feed' : 'panacea_cc/add_news_feed'),$attributes);?>
											<div class="panel-body">
												<fieldset>
                                    				
                                              	<section>
													<label class="label">News Feed</label>
													<label class="textarea textarea-resizable"> 										
														<textarea rows="3" class="custom-scroll" name="news_feed" id="news_feed" required><?php echo (isset($news_feed["news_feed"])? $news_feed["news_feed"] : "");?></textarea> 
													</label>
												</section>
												
												<section>
													<div class='input-group date' id='datetimepicker3'>
														<input type='text' class="form-control input-group-addon" required name="time"  id="time"  />
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-time"></span>
														</span>
													</div>
												</section>
												<section>
												<div id="news_body">
												</div></section>
												<input type="hidden" name="delete_files" id="delete_files" value = ""/>
												<section>					
														<input type="hidden" name="news_id" value='<?php echo (isset($news_feed["_id"])? $news_feed["_id"] : "");?>'/>
												</section>
												
													<section>
														<label class="label">File input (Optional)</label>
														<div class="input input-file">
															<input type="file" id="file" name="file[]"  multiple="multiple" data-max-size="2202009">
														</div>
													</section>
                                                </fieldset>
											 </div>
                                            <footer>
												<button type="submit" class="btn bg-color-greenDark txt-color-white" id="add">
                                             	Add
                                             	</button>
											</footer>
											<?php echo form_close();?>
											
											
										</div>
								</div>
						</article>
					</div>
					
			</div>
											
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
	 	
?>
<script src="<?php echo(JS.'moment.js');?>" type="text/javascript"></script>
<script src="<?php echo(JS.'bootstrap-datetimepicker.js');?>" type="text/javascript"></script>
<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script>
$(document).ready(function() {

	 $('#datetimepicker3').datetimepicker({
         format: 'Y-MM-DD H:mm:ss',
			ignoreReadonly: true,
			minDate:new Date()
     });

$('#datetimepicker3').data("DateTimePicker").date(new Date('<?php echo (isset($news_feed["display_date"])? $news_feed["display_date"] : "");?>'));

file_encrypted_name = [];
var news_details = '';
var news_data = '<?php echo (isset($news_feed)? base64_encode(json_encode($news_feed)) : "");?>';
if(news_data != ''){
	news_obj = JSON.parse(atob(news_data));
if (news_obj.hasOwnProperty('file_attachment')){
	news_details = news_details + '<ul>';
	news_obj.file_attachment.forEach(function(entry) {
	    console.log(entry);
	   news_details = news_details + '<li>'+entry.file_client_name+' | <a class="delete_file" file_name="'+entry.file_encrypted_name+'"><i class="fa fa-trash-o"></i></a></li>';
	});
	news_details = news_details + '</ul>';
	}

$("#news_body").html(news_details);
}


	
<?php if($message) {?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message!",
				content : "<?php echo $message?>",
				color : "#C79121",
				iconSmall : "fa fa-bell bounce animated"
				
			});
<?php } ?>
});

$(function () {

	$('body').on('click', '.delete_file', function() {
		$(this).closest('li').remove();
		file_encrypted_name.push($(this).attr("file_name"));
		
	});
               

                var fileInput = $('#file');
                var maxSize = fileInput.data('max-size');
                $('.smart-form').submit(function(e){
                	$("#delete_files").val(file_encrypted_name.join("^^"));
            		alert('sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss');
                    if(fileInput.get(0).files.length){
                        var fileSize = fileInput.get(0).files[0].size; // in bytes
                        alert('File size  ' + fileSize + ' bytes');
                        if(fileSize>maxSize){
                            alert('File size is more than ' + maxSize + ' bytes');
                            return false;
                        }else{
                            alert('File size is correct- '+fileSize+' bytes');
                        }
                    }else{
                       // alert('Choose file, please');
                      //  return false;
                    }

                });
 });



</script>


<?php 
	//include footer
	include("inc/footer.php"); 
?>