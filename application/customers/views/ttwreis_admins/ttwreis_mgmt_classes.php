<?php $current_page="Manage_classes";?>
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
                            CREATE NEW CLASS                                
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
					echo  form_open('ttwreis_mgmt/create_class',$attributes);
					?>                                                       
                        <h2 class="card-inside-title">Please Enter The Class</h2>                            
                        <div class="row clearfix">                                
                            <div class="col-sm-12">
                            	<div class="form-group">
                                	<div class="form-line">                                    		
                                        <input type="text" class="form-control" placeholder="Enter Class Name"  name="class_name" id="class_name" value="<?PHP echo set_value('class_name'); ?>" required/>
                                    </div>
                                </div>
                            </div>                                
                        </div> 
                        <div class="button-demo">
                    		<button type="submit" class="btn btn-danger waves-effect submit"  style="float: right;">CREATE</button>
                    		<button type="reset" class="btn btn-info waves-effect">CLEAR</button>
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
                          All Classes <span class="badge bg-color-greenLight"><?php if(!empty($classescount)) {?><?php echo $classescount;?><?php } else {?><?php echo "0";?><?php }?></span>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
						        <?php if ($classes): ?>
						        <thead>	
								<tr>
									<th>Classes Name</th>
									<th>Action</th>
								</tr>
								</thead>
								
						        <tbody>
						        <?php foreach ($classes as $class):?>
								<tr>
									<td><?php echo ucwords($class["class_name"]) ;?></td>
									<td><?php //echo anchor("panacea_mgmt/panacea_mgmt_manage_class/".$class['_id'], lang('app_edit')) ;?>
									
									    <a class='ldelete' href='<?php echo URL."ttwreis_mgmt/ttwreis_mgmt_delete_class/".$class['_id'];?>'>
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