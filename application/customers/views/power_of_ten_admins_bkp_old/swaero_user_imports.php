<?php $current_page = "swaero_user_imports"; ?>
<?php $main_nav = "Imports"; ?>
<?php include("inc/header_bar.php"); ?>
<?php include("inc/sidebar.php"); ?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->

<section class="content">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
						<div class="header">
							<h2>Swaero Users Import </h2>
							<ul class="header-dropdown m-r--5">
							    <div class="button-demo">
							    <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
							    </div>
							</ul>
						</div>
							<?php 
							$attributes = array('class' => 'smart-form','id' => 'form');
							echo form_open_multipart('power_of_ten_mgmt/import_users',$attributes);?>
							<div class="body">
                        	<div class="row clearfix">
                           		 <div class="col-sm-4">
                                		<select class="form-control show-tick">
                                        	<option value="" selected="" disabled="">Select a district</option>
										<?php if(isset($distslist)): ?>
											<?php foreach ($distslist as $dist):?>
											<option value='<?php echo $dist['_id']?>' ><?php echo ucfirst($dist['dt_name'])?></option>
											<?php endforeach;?>
											<?php else: ?>
											<option value="1"  disabled="">No district entered yet</option>
										<?php endif ?>
									</select> <i></i>
								<div class="row body">
									<form action="/" id="frmFileUpload" class="dropzone" method="post" enctype="multipart/form-data">
                                	<div class="dz-message fallback">
                                    <span class="button"><input name="file" type="file" style=" border: 1px solid #ccc;display: inline-block;padding: 6px 58px;cursor: pointer;  border-radius: 5px;" multiple />
                                   </span>
                                </div><br>
                        
	                            <button type="submit" class="btn bg-indigo waves-effect" name="submit" id="sbt" data-toggle="modal" data-target="#import_waiting" data-backdrop="static" data-keyboard="false">
	                            Import
                            	</button>
                          	</form>
                          </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>
					<!-- Modal -->
					<div class="modal fade" id="import_waiting" tabindex="-1" role="dialog" >
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title" id="myModalLabel">Import in progress</h4>
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
					</div><!-- /.modal -->
									
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
	 	
?>
<script src="<?php echo(JS.'dynamic-add-import.js'); ?>" type="text/javascript"></script>
<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script>
$(document).ready(function() {
<?php if($message) {?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Import Failed!",
				content : "<?php echo $message?>",
				color : "#C79121",
				iconSmall : "fa fa-bell bounce animated"
			});
<?php } ?>
});

$('#form').submit(function() {
	//$('#gif').css('visibility', 'visible');
    $('#sbt').attr('disabled', true);
    //var img_url = <?php echo(IMG.'ajax-loader.gif'); ?>;
    //$('#form_div').html('<img src="img_url" id="gif" style="display: block; margin: 0 auto; width: 100px;">');
    return true;
});
</script>


<?php 
	//include footer
	include("inc/footer_bar.php"); 
?>