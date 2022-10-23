<?php $current_page="Add_News_Feed"; ?>
<?php $main_nav="News Feed"; ?>
<?php include('inc/header_bar.php');?>
<?php include("inc/sidebar.php"); ?>

<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">

<!-- ==========================CONTENT STARTS HERE ========================== -->

<section class="content">
    <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
				<div class="header">
					<h2>Add a News Feed</h2>
					<ul class="header-dropdown m-r--5">
					    <div class="button-demo">
					    <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
					    </div>
					</ul>
            	</div>
					<?php 
						$attributes = array('class' => 'smart-form','id'=>'news_feed_form');
						echo form_open_multipart((isset($news_feed)? 'ttwreis_mgmt/update_news_feed' : 'ttwreis_mgmt/add_news_feed'),$attributes);
					?>
											
				<div class="body">
					<div class="form-line">
					    <label>News Feed</label>
					    <textarea cols="30" rows="3" name="news_feed" id="news_feed" class="form-control no-resize" required="" aria-required="true">
					    	<?php echo (isset($news_feed["news_feed"])? $news_feed["news_feed"] : "");?>
					    </textarea>
					</div>

                    <div class="form-line">
                        <label>Choose Date</label>
                        <input type="text" id="set_date" name="time" class="datepicker form-control date set_date" value="<?php echo date('yy-m-d'); ?>" required>
                        
                    </div><br>

                    <div id="news_body"></div>
                    <input type="hidden" name="delete_files" id="delete_files" value = ""/>
                    <div class="">
                    	<input type="hidden" name="news_id" value='<?php echo (isset($news_feed["_id"])? $news_feed["_id"] : "");?>'/>
                    </div>
                	
					<div class="form-line">
	                    <form action="/" id="frmFileUpload" class="dropzone" method="post" enctype="multipart/form-data">
	                        <div class="dz-message fallback">
	                        	<label>File Upload(optional)</label>
	                            <span class="button"><input name="file[]" type="file" style=" border: 1px solid #ccc;display: inline-block;padding: 6px 58px;cursor: pointer;  border-radius: 5px;" data-max-size="2202009" multiple />
	                           	</span>
	                        </div><br>
	                
		                    <button type="submit" class="btn bg-indigo waves-effect" name="submit" id="add" data-toggle="modal" data-target="#import_waiting" data-backdrop="static" data-keyboard="false">
		                    ADD
		                    </button>
						</form>
			    	</div>
				</div>

					<?php echo form_close();?> 
			</div>
		</div>
	</div>
</section>

<div class="modal fade" id="import_waiting" tabindex="-1" role="dialog" >
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Add in progress</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" style="display: block; margin: 0 auto; width: 100px;">
					</div>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<!-- Jquery Core Js -->
   <script src="<?php echo MDB_PLUGINS."jquery/jquery.min.js"; ?>"></script> 

    <!-- Waves Effect Plugin Js -->
   <!--  <script src="<?php //echo MDB_PLUGINS."node-waves/waves.js"; ?>"></script> -->
    
    <!-- Moment Plugin Js -->
    <script src="<?php echo MDB_PLUGINS."momentjs/moment.js"; ?>"></script>

    <!-- Custom Js -->
    <!-- <script src="<?php //echo(MDB_JS.'admin.js'); ?>"></script>
    <script src="<?php //echo(MDB_JS.'pages/forms/basic-form-elements.js'); ?>"></script> -->
    
    <!-- Demo Js -->
    <script src="<?php echo(MDB_JS.'demo.js'); ?>"></script>

    <!-- Bootstrap Datepicker Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script>

<script>
$(document).ready(function() {

	var today_date = $('#set_date').val();

    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });

    $('#set_date').change(function(e){
            today_date = $('#set_date').val();
    });



	 /*$('#datetimepicker3').datetimepicker({
         format: 'Y-MM-DD H:mm:ss',
			ignoreReadonly: true,
			minDate:new Date()
     });

$('#datetimepicker3').data("DateTimePicker").date(new Date('<?php //echo (isset($news_feed["display_date"])? $news_feed["display_date"] : "");?>'));
*/
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
	include("inc/footer_bar.php"); 
?>