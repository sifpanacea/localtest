<?php $current_page="swaero_mgmt_dis_co_od";?>
<?php $main_nav="Masters";?>
<?php 
include('inc/header_bar.php');
include('inc/sidebar.php');
?>

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
                            CREATE NEW DISTRICT CO-ORDINATOR
                            
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <div class="button-demo">
                            <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                            </div>
                        </ul>
                    </div>
                    <div class="body">
                        <?php
                        $attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
                        echo  form_open('power_of_ten_mgmt/swaero_create_district_coordinator',$attributes);
                        ?>
                                                   
                        <h2 class="card-inside-title">Please Enter The District Co-ordinator Information</h2>
                        
                        <div class="row clearfix">         
                            <div class="col-sm-4">
                            	<div class="form-group">
                                	<div class="form-line">                                    		
                                        <input type="text" class="form-control" placeholder="District Co-Ordinator Name" name="district_coordinator_name" id="district_coordinator_name" value="<?PHP echo set_value('district_coordinator_name'); ?>" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div class="form-line">                                         
                                        <input type="text" class="form-control" placeholder="Enter District" name="district_coordinator_dist" id="district_coordinator_dist" value="<?PHP echo set_value('district_coordinator_dist'); ?>" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div class="form-line">                                        	
                                        <input type="number" class="form-control" placeholder="Mobile Number" name="district_coordinator_mob" id="district_coordinator_mob" value="<?PHP echo set_value('district_coordinator_mob'); ?>" required/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                             <div class="col-sm-4">
                                <div class="form-group">
                                    <div class="form-line">                                         
                                        <input type="email" class="form-control" placeholder="Email" name="district_coordinator_email" id="district_coordinator_email" value="<?PHP echo set_value('district_coordinator_email'); ?>" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                            	<div class="form-group">
                                	<div class="form-line">                                    		
                                        <input type="text" class="form-control" placeholder="Password" name="district_coordinator_password" id="district_coordinator_password" value="<?PHP echo set_value('district_coordinator_password'); ?>" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div class="form-line">
                                        <textarea id="district_coordinator_addr" class="form-control" placeholder="Address" name="district_coordinator_addr" class="custom-scroll" ><?php echo set_value('district_coordinator_addr');?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="button-demo">                            	
                    		<button type="reset" class="btn bg-indigo waves-effect">CLEAR</button>
                    		<button type="submit" class="btn bg-light-green waves-effect">CREATE</button>
                    	</div>
                        <?php echo form_close(); ?>               
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                          All District Co-ordinators <span class="badge bg-color-greenLight"><?php echo $district_coordinator_counts; ?></span>		
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
						        <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>District</th>
                                        <th>Mobile Number</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Accept</th>
                                        <th>Decline</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($coordinators) && ($coordinators != "No data")): ?>
                                        <?php foreach($coordinators as $cood): ?>
                                    <tr>
                                        <td><?php echo $cood['username']; ?></td>
                                        <td><?php echo $cood['district']; ?></td>
                                        <td><?php echo $cood['phone_no']; ?></td>
                                        <td><?php echo $cood['email']; ?></td>
                                        <td><?php echo $cood['active']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-success waves-effect" id="accept_btn">
                                                <i class="material-icons">done</i>
                                            </button>
                                        </td>
                                        <td><button type="button" class="btn btn-danger waves-effect" id="decline_btn">
                                            <i class="material-icons">delete</i>
                                            </button>
                                        </td>
                                    </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td>No Data found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>																			
							</table>
						</div>		
					</div>		
				</div>					
			</div>
		</div>
	</div>
</section>

<?php 
	//include footer
	include('inc/footer_bar.php'); 
?>