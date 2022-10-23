<?php $current_page = "swaero_mgmt_district"; ?>
<?php $main_nav = "Masters"; ?>
<?php include("inc/header_bar.php"); ?>
<?php include("inc/sidebar.php"); ?>

<link href="../../plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
           <!--  <h2>BASIC FORM ELEMENTS</h2> -->
        </div>
        <!-- Input -->
        
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            CREATE NEW DISTRICT                                
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
                    echo  form_open('panacea_mgmt/swaero_create_district',$attributes);
                    ?>                                                       
                        <h2 class="card-inside-title">Please Enter The District Information</h2>
                        <div class="row clearfix">
                            <div class="col-sm-4">
                                <label>State Name</label>
                                <select class="form-control show-tick" name="state_name" required>
                                    <option value="" selected="" disabled="" >Select a state</option>
                                    <?php if(isset($state)): ?>
                                        <?php foreach ($state as $states):?>
                                        <option value='<?php echo $states['state_name']?>' ><?php echo ucfirst($states['state_name'])?></option>
                                        <?php endforeach;?>
                                        <?php else: ?>
                                        <option value="1"  disabled="">No state entered yet</option>
                                    <?php endif ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label>Zonal Name</label>
                                <select class="form-control show-tick" name="zonal_name" required>
                                    <option value="" selected="" disabled="" >Select Zone</option>
                                    <?php if(isset($zone)): ?>
                                        <?php foreach ($zone as $zones):?>
                                        <option value='<?php echo $zones['zonal_name']?>' ><?php echo ucfirst($zones['zonal_name'])?></option>
                                        <?php endforeach;?>
                                        <?php else: ?>
                                        <option value="1"  disabled="">No Zone entered yet</option>
                                    <?php endif ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div class="form-line">
                                    	<label>District Name</label>
                                        <input type="text" class="form-control" placeholder="Enter District Name here" name="district_name" id="district_name" value="<?PHP echo set_value('district_name'); ?>" required/>
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