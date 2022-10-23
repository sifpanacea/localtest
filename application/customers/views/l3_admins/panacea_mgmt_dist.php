<?php $current_page="Manage_District";?>
<?php $main_nav="Masters";?>
<?php
include('inc/header_bar.php');
include('inc/sidebar.php');
?>
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
                        echo  form_open('panacea_mgmt/create_district',$attributes);
                        ?>                                                       
                            <h2 class="card-inside-title">Please Enter The District Information</h2>
                            <div class="row clearfix">
                                <div class="col-sm-4">
                                	<label>State Name</label>
                                    <select class="form-control show-tick" name="st_name" required>
                                        <option value="" selected="" disabled="" >Select a state</option>
                                        <?php if(isset($statelist)): ?>
                                            <?php foreach ($statelist as $state):?>
                                            <option value='<?php echo $state['_id']?>' ><?php echo ucfirst($state['st_name'])?></option>
                                            <?php endforeach;?>
                                            <?php else: ?>
                                            <option value="1"  disabled="">No state entered yet</option>
                                        <?php endif ?>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                	<div class="form-group">
                                    	<div class="form-line">
                                    		<label>District Code</label>
                                            <input type="text" class="form-control" placeholder="Enter District Code here"  name="dt_code" id="dt_code" value="<?PHP echo set_value('dt_code'); ?>" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <div class="form-line">
                                        	<label>District Name</label>
                                            <input type="text" class="form-control" placeholder="Enter District Name here" name="dt_name" id="dt_name" value="<?PHP echo set_value('dt_name'); ?>" required/>
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
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              All DISTRICTS <span class="badge"><?php if(!empty($distscount)) {?><?php echo $distscount;?><?php } else {?><?php echo "0";?><?php }?></span>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
			                    <?php if ($dists): ?>
								<tr>
									<th>State Name</th>
									<th>District Code</th>
									<th>District Name</th>
									<th>Action</th>
								</tr>
								<?php foreach ($dists as $dist):?>
			                    <tbody>
								<tr>
									<td><?php echo ucwords($dist['st_name']) ;?></td>
									<td><?php echo ucwords($dist['dt_code']) ;?></td>
									<td><?php echo ucwords($dist['dt_name']) ;?></td>
									<td><?php //echo anchor("panacea_mgmt/panacea_mgmt_manage_dists/".$dist['_id'], lang('app_edit')) ;?>
									
									<a class='ldelete' href='<?php echo URL."panacea_mgmt/panacea_mgmt_delete_dists/".$dist['_id'];?>'>
			                			<?php echo lang('app_delete')?></a>
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
