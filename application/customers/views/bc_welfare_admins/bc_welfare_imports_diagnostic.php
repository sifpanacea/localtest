<?php $current_page = "Diagnostics"; ?>
<?php $main_nav = "Imports"; ?>
<?php include("inc/header_bar.php"); ?>
<?php include("inc/sidebar.php"); ?>

<section class="content">
        <div class="row clearfix">
                <!-- start of upload -->
            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                <div class="card"> 
                    <div class="header">
                        <h2>
                            Diagnostic Center Import 
                        </h2>
                    </div>  
                        <?php 
						$attributes = array('class' => 'smart-form');
						echo form_open_multipart('bc_welfare_mgmt/import_diagnostic',$attributes);
						?>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-sm-4">
                            	<label>Select District</label>
                                <select class="form-control show-tick">
                                        <option value="" selected="" disabled="">Select a district</option>
                                        <?php if(isset($distslist)): ?>
                                            <?php foreach ($distslist as $dist):?>
                                            <option value='<?php echo $dist['_id']?>' ><?php echo ucfirst($dist['dt_name'])?></option>
                                            <?php endforeach;?>
                                            <?php else: ?>
                                            <option value="1"  disabled="">No district entered yet</option>
                                        <?php endif ?>
                                    </select>
                                </div> 
                            </div>                    
                        <div class="row body">
                        	<p>To upload diagnostic center data into our database select a file of excel format and press Import button.
														</p>
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
            <!-- end of upload priya -->
            <!-- <div class="row">
                <div class="col-sm-4">
                        <div class="input input-file">
                            <span class="button"><input type="file" id="file" name="file" accept="" onchange="this.parentNode.nextSibling.value = this.value" required>Browse</span><input type="text" placeholder="Browse to import in excel format" readonly="">
                        </div>
                </div>
            </div> -->
                        
            </div>
        </div>
</section>


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
</script>


<?php 
    //include footer
    include("inc/footer_bar.php"); 
?>