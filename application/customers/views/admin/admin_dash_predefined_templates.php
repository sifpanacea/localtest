<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Predefined Templates";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["tools"]["sub"]["predefinedtemplates"]["active"] = true;
include("inc/nav.php");

?>
<style>
.dropzone
{
	min-height:200px;!important
}
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Tools"] = "";
		include("inc/ribbon.php");
	?>
	
	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
    
        	<article class="col-sm-12 drop">

			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-0" data-widget-editbutton="false">
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
					<span class="widget-icon"> <i class="fa fa-cloud"></i> </span>
					<h2>My Dropzone! </h2>

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

					<!--	<form action="Upload/upload" class="dropzone" id="mydropzone"></form>-->
    								<?php $attributes = array('class' => 'dropzone', 'id' => 'mydropzone');
    								echo form_open_multipart('template_upload/upload', $attributes); ?>
                                     <input type="hidden" id="title" name="title" value="" />
                                     <input type="hidden" id="description" name="description" value="" />
                                    <?php echo form_close();?>
                                    <div class="modal fade bs-example-modal-sm" id="namme">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" id="closed_d"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Upload</h4>
      </div>
      <div class="modal-body">
                                         <div class="form-group">
                                         	<!--<label class="input">-->
											<input type="text" class="form-control" id="title_modal" placeholder="Title" required="required">
                                           <!-- </label>-->
							             </div>                        
                                         <div class="form-group">
                                         	<!--<label class="input">-->
											<input type="text" class="form-control" id="desc_modal" placeholder="Description" required="">
                                           <!-- </label>-->
							             </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="closed">Close</button>
        <button type="button" class="btn btn-primary" id="save_desc">Upload</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
                 <!--   <div class="modal fade" id="add-desc" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true" style="display: none;"> 
                                      <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                         <div class="form-group">
											<input type="text" class="form-control" placeholder="Title" required="required">
							             </div>                        
                                         <div class="form-group">
											<input type="text" class="form-control" placeholder="Description" required="">
							             </div>
                                         <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                            Cancel
                                        </button>
                                        <button type="button" class="btn btn-primary">
                                            Save
                                        </button>
                                   		</div>                    
                                        </div>
                                      </div>
						</div><!--End Modal-->
                        
					</div>
					<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->

		</article>
		<!-- WIDGET END -->
        </div><!-- ROW -->
        
<!-- row -->
<div class="row">

	<!-- SuperBox -->
	<div class="superbox col-sm-12">
    <?php $a=0;?>
    <?php foreach ($files as $file):?>
					
					
						<div class="superbox-list" id="<?php echo $file->file_name;?>"><img src="<?php echo URI.TENANT.TEMPLATE.'thumb_'.$file->file_name;?> " data-img="<?php echo URI.TENANT.TEMPLATE.$file->file_name;?>" description="<?php echo $file->file_description;?>" alt="<?php echo $file->file_title;?>" title="<?php echo $file->file_title;?>" class="superbox-img"></div>
						
					
					<?php $a++;?>
					<?php endforeach;?>
       
		<div class="superbox-float"></div>
        <?php echo $links; ?>
	</div>
	<!-- /SuperBox -->
	
	<div class="superbox-show" style="height:300px; display: none"></div>

</div>
				

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
	 	
?>
<script src="<?php echo(JS.'dropzone.js'); ?>" type="text/javascript"></script>
<script src="<?php echo(JS.'superbox.js'); ?>" type="text/javascript"></script>
<!-- PAGE RELATED PLUGIN(S) RTVPNP4GZNZRG8
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script>
$(document).ready(function() {
	<?php if($message) {?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
				content : "<?php echo $message?>",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
<?php } ?>

		$('.superbox').SuperBox();
		var myDropzone;
		Dropzone.autoDiscover = false;
		$("#mydropzone").dropzone
		({
			addRemoveLinks : true,
			maxFilesize: 256,
			autoProcessQueue: false,
			dictRemoveFile: "Upload",
			dictResponseError: 'Error uploading file!',
			maxFiles:1,
			init:function()
			{
				myDropzone=this;
			}
		});
		var closeButton = document.querySelector("#closed");
        closeButton.addEventListener("click", function() {
			$('#namme').modal('hide');
			$('#title_modal').attr("placeholder","Title")
			myDropzone.removeAllFiles();
        });
		var closeButton = document.querySelector("#closed_d");
        closeButton.addEventListener("click", function() {
			$('#namme').modal('hide');
			$('#title_modal').attr("placeholder","Title")
			myDropzone.removeAllFiles();
        });
		$(document).on('click','.image-delete',function()
		{
			var id=$('.superbox').find('.active').attr('id')
			$.ajax({
			url: 'delete_image',
			type: 'POST',
			data:  'id='+id,
			async: false,
			success: function (sdata) 
			{
					document.location.reload(true);
			},
			 error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
			}
			})
			
		})
	
		$(document).on('click','#save_desc',function()
		{
			var title='';
			title=$('#title_modal').val()
			var desc=$('#desc_modal').val()
			console.log("ssssssssssssssssssssss",title)
			if(title!=null && title!='')
			{
				$('#description').val(desc);
				$('#title').val(title);
				console.log("rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr")
				$('#namme').modal('hide');
				myDropzone.processQueue();
				myDropzone.on("complete", function(file) {
 				document.location.reload(true);
				});
			}
			else
			{
				console.log("else")
				//$('#title_modal').parent('label').addClass("state-error");
				$('#title_modal').attr("placeholder","Required*")
			}
		})
		
});
</script>



<?php 
	//include footer
	include("inc/footer.php"); 
?>