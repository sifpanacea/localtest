<?php $current_page = "swaero mgmt states"; ?>
<?php $main_nav = "Masters"; ?>
<?php include("inc/header_bar.php"); ?>
<?php include("inc/sidebar.php"); ?>

<section class="content">
	<div class="container-fluid">
		<!-- <div class="block-header">
            <h2>Social Network</h2>
        </div> -->
        <div class="row clearfix">
            <div class="col-lg-6 col-md-3 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            MANAGE STATES
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <div class="button-demo">
                            <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                            </div>
                        </ul>
                    </div>
                    <div class="body">
                    <?php
                    $attributes = array('class' => 'smart-form','id'=>'smart-form-register','name'=>'userform');
                    echo  form_open('power_of_ten_mgmt/swaero_create_state',$attributes);
                    ?>                                                       
                        
                        <div class="row clearfix">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Enter State Name</label>
                                        <input type="text" class="form-control" placeholder="Enter State Name here" name="state_name" id="state_name" value="<?PHP echo set_value('state_name'); ?>" required/>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="button-demo">
                            <button type="reset" class="btn bg-pink waves-effect">CLEAR</button>
                            <button type="submit" class="btn bg-blue waves-effect">CREATE</button>
                        </div>
                        <?php echo form_close();?>               
                    </div>
                </div>
            </div>
        </div>
		
		
	</div>
</section>



<?php include("inc/footer_bar.php"); ?>