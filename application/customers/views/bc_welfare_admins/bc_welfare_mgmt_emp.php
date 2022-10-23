<?php $current_page="Manage_Employees"; ?>
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
                                CREATE NEW EMPLOYEE                                
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
						echo  form_open('bc_welfare_mgmt/create_emp',$attributes);
						?>                                                        
                            <h2 class="card-inside-title">Please Enter The Employee Information</h2>                      
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="number" id="emp_code" name="emp_code" class="form-control" placeholder="Employee Code" value="<?PHP echo set_value('emp_code'); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="text" id="emp_name" class="form-control" name="emp_name" placeholder="Employee Name" value="<?PHP echo set_value('emp_name'); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">                                        	
                                            <input type="number" id="emp_mob" class="form-control" name="emp_mob" placeholder="Mobile Number" value="<?PHP echo set_value('emp_mob'); ?>"/>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="email" id="emp_email" class="form-control" name="emp_email" placeholder="Email" value="<?PHP echo set_value('emp_email'); ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">
                                            <textarea id="emp_addr" name="emp_addr" placeholder="Address"class="form-control" ><?php echo set_value('emp_addr');?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">                                        	
                                            <input type="textarea" id="emp_qualification" name="emp_qualification" class="form-control" placeholder="Qualification" value="<?PHP echo set_value('emp_qualification'); ?>" />
                                        </div>
                                    </div>
                                </div>                                
                            </div>  
                            <div class="button-demo">                            	
                        		<button type="reset" class="btn bg-indigo waves-effect">CLEAR</button>
                        		<button type="submit" class="btn bg-light-green waves-effect submit">CREATE</button>
                        	</div>
                            <?php echo form_close();  ?>               
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                             All Employees <span class="badge bg-color-greenLight"><?php if(!empty($empcount)) {?><?php echo $empcount;?><?php } else {?><?php echo "0";?><?php }?></span>
                            </h2>                            
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
								<?php if ($emps): ?>
								<thead>
								<tr>
									<th>Employee Code</th>
									<th>Employee Name</th>
									<th>Mobile Number</th>
									<th>Email</th>
									<th>Address</th>
									<th>Qualification</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
								<?php foreach ($emps as $emp):?>
			                    
								<tr>
									<td><?php echo $emp["emp_code"] ;?></td>
									<td><?php echo ucwords($emp["emp_name"]) ;?></td>
									<td><?php echo $emp["emp_mob"] ;?></td>
									<td><?php echo $emp["emp_email"] ;?></td>
									<td><?php echo ucwords($emp["emp_addr"]) ;?></td>
									<td><?php echo ucwords($emp["emp_qualification"]) ;?></td>
									<td><?php //echo anchor("panacea_mgmt/panacea_mgmt_manage_emp/".$emp['_id'], lang('app_edit')) ;?>
									
									<a class='ldelete' href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_delete_emp/".$emp['_id'];?>'>
                					<?php echo lang('app_delete')?>
                					</a>
									</td>
								</tr>
								<?php endforeach;?>
								<?php else: ?>
			        			<p>
			          				<?php echo "No employee entered yet.";?>
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
<?php 
	//include footer
	include("inc/footer_bar.php"); 
?>