<?php $current_page="Manage_Doctors"; ?>
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
                                CREATE NEW DOCTOR
                                
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
							echo  form_open('bc_welfare_mgmt/create_doctor',$attributes);
							?>
                            
                            <h2 class="card-inside-title">Please Enter The Doctor Information</h2>                      
                            <div class="row clearfix">
                                <div class="col-sm-3">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="text" class="form-control" placeholder="Doctor Name"  name="doc_name" id="doc_name" value="<?PHP echo set_value('doc_name'); ?>" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="text" class="form-control" placeholder="Qualification" name="qualification" id="qualification" value="<?PHP echo set_value('qualification'); ?>" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="form-line">                                        	
                                            <input type="text" class="form-control" placeholder="Specification" name="specification" id="specification" value="<?PHP echo set_value('specification'); ?>" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="text" class="form-control" placeholder="Password" name="password" id="password" value="<?PHP echo set_value('password'); ?>" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-3">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="number" class="form-control" placeholder="Mobile Number" name="mob_number" id="mob_number" value="<?PHP echo set_value('mob_number'); ?>" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="email" class="form-control" placeholder="Email"  name="email" id="email" value="<?PHP echo set_value('email'); ?>" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="form-line">                                        	
                                            <input type="textarea" class="form-control" placeholder="District" name="district" id="district" value="<?PHP echo set_value('district'); ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                	<div class="form-group">
                                    	<div class="form-line">
                                            <textarea id="address" name="address" class="form-control" placeholder="Address"><?php echo set_value('address');?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="button-demo">                            	
                        		<button type="reset" class="btn bg-indigo waves-effect">CLEAR</button>
                        		<button type="submit" class="btn bg-light-green waves-effect submit">CREATE</button>
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
                              All Doctors <span class="badge bg-color-greenLight"><?php if(!empty($doctorscount)) {?><?php echo $doctorscount;?><?php } else {?><?php echo "0";?><?php }?></span>
                            </h2>                            
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
								<?php if ($doctors): ?>
								<thead>
								<tr>
									<th>Doctor Name</th>
									<th>Qualification</th>
									<th>Specification</th>
									<th>Mobile Number</th>
									<th>Email</th>
									<th>District</th>
									<th>Address</th>
									<th>Action</th>
								</tr>
							    </thead>
							    <tbody>
								<?php foreach ($doctors as $doctor):?>				                
								<tr>
									<td><?php echo ucfirst($doctor["name"]) ;?></td>
									<td><?php echo ucwords($doctor["qualification"]) ;?></td>
									<td><?php echo ucfirst($doctor["specification"]);?></td>
									<td><?php echo $doctor["mobile_number"] ;?></td>
									<td><?php echo $doctor["email"] ;?></td>
									<td><?php echo $doctor["district"] ;?></td>
									<td><?php echo $doctor["company_address"] ;?></td>
									<td><?php //echo anchor("panacea_mgmt/panacea_mgmt_manage_states/".$hs['_id'], lang('app_edit')) ;?>									
									<a class='ldelete' href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_delete_doctor/".$doctor['_id'];?>'>
		                			<?php echo lang('app_delete')?>
		                			</a>
									</td>
								</tr>
								<?php endforeach;?>
								<?php else: ?>
				    			<p>
				      				<?php echo "No doctor entered yet.";?>
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

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
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