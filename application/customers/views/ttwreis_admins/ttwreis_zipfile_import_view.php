<?php $current_page="Screening File Import"; ?>
<?php $main_nav="Imports"; ?>
<?php
include('inc/header_bar.php');
include('inc/sidebar.php');
?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<section class="content">
    <div class="row clearfix">
            <!-- start of upload -->
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <div class="card"> 
                <div class="header">
					<h2>Screening Zipfile Import</h2>
					<ul class="header-dropdown m-r--5">
                        <div class="button-demo">
                        <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                        </div>
                    </ul>
                </div>
				<?php 
					$attributes = array('class' => 'smart-form','id'=>'fileform');
					echo form_open_multipart('ttwreis_mgmt/school_screening_file_import',$attributes);?>
				<div class="body">
                    <div class="row clearfix">
                        <div class="col-sm-12">
						<div class="row body">
							<p>To upload zipfile of medical screening data into our database select a file of zip format and press Import button.</p>
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
				<?php echo form_close();?>
			</div>
		</div>
	</div>
</section>

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
</div>                    	
						<!-- <div class="input input-file">
							<span class="button">
								<input type="file" id="file" name="userfile" accept=".zip,.rar" onchange="this.parentNode.nextSibling.value = this.value">Browse</span>
								<input type="text" placeholder="Browse to import in compressed(ZIP) format" class="inputfield" id="btn_file"readonly="">
						</div>
								 -->				
											
	
	
	
				

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script>
$(document).ready(function() {


		$('#fileform').submit(function(e)
		{
			//e.preventDefault();
			console.log("click");
			/* $("#fileform").submit();
			console.log("clickerw"); */
			 $(".inputfield").val("");
			
			$("#btn_file").val("");
		});
});
</script>
<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<?php 
	//include footer
	include("inc/footer_bar.php"); 
?>