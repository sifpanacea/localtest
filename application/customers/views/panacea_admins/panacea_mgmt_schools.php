<?php $current_page="Manage_Schools";?>
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
                                CREATE NEW SCHOOL                                
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <div class="button-demo">
                                <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                                </div>
                            </ul>
                        </div>
                        <?php
                            $attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
                            echo  form_open('panacea_mgmt/create_school',$attributes);
                        ?>
                        <div class="body">

                            <h2 class="card-inside-title">Please Enter The School Information</h2>
                                                                      
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <select class="form-control show-tick" name="dt_name" required>
                                        <option value=""  selected="" disabled="">-- Please select District Name --</option>
						                <?php if(isset($distslist)): ?>
											<?php foreach ($distslist as $dist):?>
											    <option value='<?php echo $dist['_id']?>'><?php echo ucfirst($dist['dt_name'])?></option>
											<?php endforeach;?>
										<?php else: ?>
											<option value="1"  disabled="">No district entered yet</option>
										<?php endif ?>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" placeholder="School Code" name="school_code" id="school_code" value="<?PHP echo set_value('school_code'); ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="form-control" placeholder="School Name"  name="school_name" id="school_name" value="<?PHP echo set_value('school_name'); ?>" required/>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">
                                            <textarea id="school_addr" name="school_addr" class="form-control" placeholder="Adress"><?php echo set_value('school_addr');?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="email" class="form-control" placeholder="Email" name="school_email" id="school_email" value="<?PHP echo set_value('school_email'); ?>" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">                                        	
                                            <input type="text" class="form-control" placeholder="Password" name="school_password" id="school_password" value="<?PHP echo set_value('school_password'); ?>" required/>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="number" class="form-control"  placeholder="Phone Number" name="school_ph" id="school_ph" value="<?PHP echo set_value('school_ph'); ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="number" class="form-control" maxlength="10" minlength="3" placeholder="Mobile Number"  name="school_mob" id="school_mob" value="<?PHP echo set_value('school_mob'); ?>" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">                                        	
                                            <input type="text" class="form-control" placeholder="Contact Person Name" name="contact_person_name" id="contact_person_name" value="<?PHP echo set_value('contact_person_name'); ?>" required />
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="button-demo">                            	
                        		<button type="reset" class="btn bg-red waves-effect">CLEAR</button>
                        		<button type="submit" class="btn bg-teal waves-effect" style="float: right;">CREATE</button>
                        	</div>
                            <?php echo form_close();?>               
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              All Schools <span class="badge bg-color-greenLight"><?php if(!empty($schoolscount)) {?><?php echo $schoolscount;?><?php } else {?><?php echo "0";?><?php }?></span>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
						        <?php if ($schools): ?>
								<thead>
								<tr>
									<th>District</th>
									<th>School Code</th>
									<th>School Name</th>
									<th>School Address</th>
									<th>Contact Email</th>
									<th>Contact Phone</th>
									<th>Contact Mobile</th>
									<th>Contact Person</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
								<?php foreach ($schools as $school):?>			                   
								<tr>
									<td><?php echo ucwords($school["dt_name"]) ;?></td>
									<td><?php echo $school["school_code"] ;?></td>
									<td><?php echo ucwords($school["school_name"]) ;?></td>
									<td><?php echo $school["school_addr"] ;?></td>
									<td><?php echo $school["email"] ;?></td>
									<td><?php echo $school["school_ph"] ;?></td>
									<td><?php echo $school["school_mob"] ;?></td>
									<td><?php echo $school["contact_person_name"] ;?></td>
									<td><?php //echo anchor("panacea_mgmt/panacea_mgmt_manage_school/".$school['_id'], lang('app_edit')) ;?>
									
									<a class='ldelete' href='<?php echo URL."panacea_mgmt/panacea_mgmt_delete_school/".$school['_id'];?>'>
			                			<?php echo lang('app_delete')?>
			                		</a>
									</td>
								</tr>
								<?php endforeach;?>
								<?php else: ?>
			        			<p>
			          				<?php echo "No state entered yet.";?>
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
	//include footer
	include("inc/footer_bar.php"); 
?>