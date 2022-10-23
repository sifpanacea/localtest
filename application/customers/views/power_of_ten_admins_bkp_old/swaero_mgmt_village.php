<?php $current_page = "swaero_mgmt_village"; ?>
<?php $main_nav = "Masters"; ?>
<?php include("inc/header_bar.php"); ?>
<?php include("inc/sidebar.php"); ?>

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
                            CREATE NEW VILLAGE                                
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
                    echo  form_open('panacea_mgmt/swaero_create_village',$attributes);
                    ?>                                                       
                        <h2 class="card-inside-title">Please Enter The Village Information</h2>
                        <div class="row clearfix">
                            <div class="col-sm-3">
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
                            <div class="col-sm-3">
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
                            <div class="col-sm-3">
                                <label>District Name</label>
                                <select class="form-control show-tick" name="district_name" required>
                                    <option value="" selected="" disabled="" >Select a state</option>
                                    <?php if(isset($district)): ?>
                                        <?php foreach ($district as $districts):?>
                                        <option value='<?php echo $districts['district_name']?>' ><?php echo ucfirst($districts['district_name'])?></option>
                                        <?php endforeach;?>
                                        <?php else: ?>
                                        <option value="1"  disabled="">No districts entered yet</option>
                                    <?php endif ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label>Mandal Name</label>
                                <select class="form-control show-tick" name="mandal_name" required>
                                    <option value="" selected="" disabled="" >Select a Mandal</option>
                                    <?php if(isset($mandal)): ?>
                                        <?php foreach ($mandal as $mandals):?>
                                        <option value='<?php echo $mandals['mandal_name']?>' ><?php echo ucfirst($mandals['mandal_name'])?></option>
                                        <?php endforeach;?>
                                        <?php else: ?>
                                        <option value="1"  disabled="">No Mandal entered yet</option>
                                    <?php endif ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Village Name</label>
                                        <input type="text" class="form-control" placeholder="Enter Village Name here" name="village_name" id="village_name" value="<?PHP echo set_value('village_name'); ?>" required/>
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