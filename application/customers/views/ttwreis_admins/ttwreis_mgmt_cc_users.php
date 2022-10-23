<?php $current_page="Manage_CC_Users"; ?>
<?php $main_nav="Masters"; ?>
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
                                CREATE NEW COMMAND CENTER USER                             
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
						echo  form_open('ttwreis_mgmt/create_cc_user',$attributes);
						?>                                                       
                            <h2 class="card-inside-title">Please Enter The CC User Information</h2>                      
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="text" class="form-control" placeholder="Name"  name="cc_user_name" id="cc_user_name" value="<?PHP echo set_value('cc_user_name'); ?>"  required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="number" class="form-control" placeholder="Mobile Number" name="cc_user_mob" id="cc_user_mob"value="<?PHP echo set_value('cc_user_mob'); ?>"  required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">                                        	
                                            <input type="number" class="form-control" placeholder="Phone Number" name="cc_user_ph" id="cc_user_ph"value="<?PHP echo set_value('cc_user_ph'); ?>" required/>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="text" class="form-control" placeholder="Password"  name="password" id="password" value="<?PHP echo set_value('password'); ?>" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="<?PHP echo set_value('email'); ?>" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea id="cc_user_addr" name="cc_user_addr" placeholder="Address"class="form-control" ><?php echo set_value('cc_user_addr');?></textarea>
                                        </div>
                                    </div>
                                </div>                                
                            </div>  
                            <div class="button-demo">                            	
                        		<button type="reset" class="btn bg-indigo waves-effect">CLEAR</button>
                        		<button type="submit" class="btn bg-light-green waves-effect submit">CREATE</button>
                        	</div>               
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                             All Command Center Users <span class="badge bg-color-greenLight"><?php if(!empty($cc_count)) {?><?php echo $cc_count;?><?php } else {?><?php echo "0";?><?php }?></span>
                            </h2>                            
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
							<?php if ($cc_users): ?>
							    <thead>
									<tr>
										<th>Name</th>
										<th>Contact Mobile</th>
										<th>Email</th>
										<th>Address</th>
										<th>Action</th>
									</tr>
						        </thead>
						        <tbody>
							    <?php foreach ($cc_users as $cc_user):?>
							    <tr>
									<td><?php echo ucwords($cc_user["username"]) ;?></td>
									<td><?php echo $cc_user["mobile_number"] ;?></td>
									<td><?php echo $cc_user["email"] ;?></td>
									<td><?php echo ucwords($cc_user["company_address"]) ;?></td>
									<td><?php //echo anchor("panacea_mgmt/panacea_mgmt_manage_states/".$hs['_id'], lang('app_edit')) ;?>
									
									<a class='ldelete' href='<?php echo URL."ttwreis_mgmt/ttwreis_mgmt_delete_cc_user/".$cc_user['_id'];?>'>
		                			<?php echo lang('app_delete')?>
		                			</a>
									</td>
								</tr>
								<?php endforeach;?>
								<?php else: ?>
				        			<p>
				          				<?php echo "No command center user entered yet.";?>
				        			</p>
			        			<?php endif ?>
								</tbody>
									<?php if($links):?>
								<tfoot>											
				                    <tr>
				                        <td colspan="5">
				                            <?php echo $links; ?>
				                        </td>
				                    </tr>
							    </tfoot>
		                        <?php endif ?>
							</table>				
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>			
<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>
<script>
$(document).ready(function() {
<?php if($message) {?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message!",
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