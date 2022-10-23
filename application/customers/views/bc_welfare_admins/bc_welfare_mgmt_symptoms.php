<?php $current_page="Manage_Symptoms"; ?>
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
                                CREATE NEW SYMPTOM                               
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
						echo  form_open('bc_welfare_mgmt/create_symptoms',$attributes);
						?>                                                     
                            <h2 class="card-inside-title">Please Enter The Symptom</h2>                            
                            <div class="row clearfix">                                
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="text" class="form-control" placeholder="Enter Symptom Name" name="symptom_name" id="symptom_name" value="<?PHP echo set_value('symptom_name'); ?>" required/>
                                        </div>
                                    </div>
                                </div> 
                                <div class="button-demo">
                                    <button type="submit" class="btn bg-pink waves-effect submit">CREATE</button>
                                    <button type="reset" class="btn bg-blue waves-effect" >CLEAR</button>
                                </div>                               
                            </div> 
                            
                            <?php echo form_close();     ?>               
                        </div>
                    </div>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              All Symptoms <span class="badge bg-color-greenLight"><?php if(!empty($symptomscount)) {?><?php echo $symptomscount;?><?php } else {?><?php echo "0";?><?php }?></span>
                            </h2>                            
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            	<?php if ($symptoms): ?>
								<tr>
									<th>Symptom Name</th>
									<th>Action</th>
								</tr>
								<?php foreach ($symptoms as $symptom):?>
			                    <tbody>
								<tr>
									<td><?php echo ucwords($symptom["symptom_name"]) ;?></td>
									<td><?php //echo anchor("panacea_mgmt/panacea_mgmt_manage_symptoms/".$symptom['_id'], lang('app_edit')) ;?>
									
										<a class='ldelete' href='<?php echo URL."bc_welfare_mgmt/bc_welfare_mgmt_delete_symptoms/".$symptom['_id'];?>'>
			                			<?php echo lang('app_delete')?>
			                			</a>
									</td>
								</tr>
								<?php endforeach;?>
								<?php else: ?>
			        			<p>
			          				<?php echo "No symtom entered yet.";?>
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