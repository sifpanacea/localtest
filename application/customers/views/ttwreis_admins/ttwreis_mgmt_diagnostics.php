<?php $current_page="Manage_Diagnostic";?>
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
                                CREATE NEW DIAGNOSTIC CENTER
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
						echo  form_open('ttwreis_mgmt/create_diagnostic',$attributes);
						?>        
                            <h2 class="card-inside-title">Please Enter The Diagnostic Information</h2>
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                    <div class="form-group">                                	
                                        <div class="form-line">                                 
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
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="text" class="form-control" placeholder="Diagnostic Center Code " name="diagnostic_code" id="diagnostic_code" value="<?PHP echo set_value('diagnostic_code'); ?>" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">                                        	
                                            <input type="text" class="form-control" placeholder="Diagnostic Center Name" name="diagnostic_name" id="diagnostic_name" value="<?PHP echo set_value('diagnostic_name'); ?>" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="number" class="form-control" pattern="[10]" placeholder="Phone Number" name="diagnostic_ph" id="diagnostic_ph" value="<?PHP echo set_value('diagnostic_ph'); ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="number" class="form-control" placeholder="Mobile Number" name="diagnostic_mob" id="diagnostic_mob" value="<?PHP echo set_value('diagnostic_mob'); ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <textarea id="diagnostic_addr" name="diagnostic_addr"class="form-control" placeholder="Address" ><?php echo set_value('diagnostic_addr');?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <div class="button-demo">                            	
                        		<button type="reset" class="btn bg-indigo waves-effect">CLEAR</button>
                        		<button type="submit" class="btn bg-light-green waves-effect submit">CREATE</button>
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
                              All DIAGNOSTIC CENTER <span class="badge"><?php if(!empty($diagnosticscount)) {?><?php echo $diagnosticscount;?><?php } else {?><?php echo "0";?><?php }?></span>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
			                    <?php if ($diagnostics): ?>
								<thead>
								<tr>
									<th>District Name</th>
									<th>Diagnostic Center Code</th>
									<th>Diagnostic Center Name</th>
									<th>Phone Number</th>
									<th>Mobile Number</th>
									<th>Address</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
								<?php foreach ($diagnostics as $diagnostic):?>
			                    <tr>
									<td><?php echo ucwords($diagnostic["dt_name"]) ;?></td>
									<td><?php echo $diagnostic["diagnostic_code"] ;?></td>
									<td><?php echo ucwords($diagnostic["diagnostic_name"]) ;?></td>
									<td><?php echo $diagnostic["diagnostic_ph"] ;?></td>
									<td><?php echo $diagnostic["diagnostic_mob"] ;?></td>
									<td><?php echo ucwords($diagnostic["diagnostic_addr"]) ;?></td>
									<td><?php //echo anchor("panacea_mgmt/panacea_mgmt_diagnostic/".$diagnostic['_id'], lang('app_edit')) ;?>
									
									<a class='ldelete' href='<?php echo URL."ttwreis_mgmt/ttwreis_mgmt_delete_diagnostic/".$diagnostic['_id'];?>'>
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