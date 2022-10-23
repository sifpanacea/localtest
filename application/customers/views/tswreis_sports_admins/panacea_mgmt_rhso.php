<?php $current_page=""; ?>
<?php $main_nav=""; ?>
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
                                CREATE NEW RHSO
                                <button type="button" class="btn bg-pink waves-effect pull-right" onclick="window.history.back();">Back</button>
                            </h2>                            
                        </div>
                        <div class="body">
                         <?php
                            $attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
                            echo  form_open('panacea_mgmt/create_rhso_name',$attributes);
                        ?>                                                       
                            <h2 class="card-inside-title">Please Enter The Rhso Information</h2>                      
                            <div class="row clearfix">
                                <div class="col-sm-3">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="text" class="form-control" placeholder="Rhso Name"  name="rhso_name" id="rhso_name" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="text" class="form-control" placeholder="Qualification" name="qualification" id="qualification"  required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="form-line">                                        	
                                            <input type="text" class="form-control" placeholder="Specialization" name="specification" id="specification"  required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="form-line">                                         
                                            <input type="number" class="form-control" placeholder="Mobile Number" name="mob_number" id="mob_number" required/>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row clearfix">
                                
                                <div class="col-sm-3">
                                	<div class="form-group">
                                    	<div class="form-line">                                    		
                                            <input type="email" class="form-control" placeholder="Email"  name="email" id="email" required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="form-line">                                         
                                            <input type="text" class="form-control" placeholder="Password" name="password" id="password"  required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <div class="form-line">                                        	
                                            <input type="textarea" class="form-control" placeholder="District" name="district" id="district" required />
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
                    RHSO Count <span class="badge bg-color-greenLight"><?php if(!empty($rhsoscount)) {?><?php echo $rhsoscount;?><?php } else {?><?php echo "0";?><?php }?></span>
                            </h2>                            
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
								
								<thead>
								<tr>
									<th>RHSO Name</th>
									<th>Qualification</th>
									<th>Specialization</th>
									<th>Mobile Number</th>
									<th>Email</th>
									<th>District</th>
									
								</tr>
							    </thead>
							    <tbody>

									<?php foreach ($doctor_rhso as $doc): ?>	
                                    	<!-- <?php //echo print_r($rhsos, true); exit();?>	  -->               
								<tr>
									<td><?php echo $doc["Rhso_name"] ;?></td>
									<td><?php echo $doc["Qualification"] ;?></td>
									<td><?php echo $doc["Specification"];?></td>
									<td><?php echo $doc["Mobile_Number"] ;?></td>
									<td><?php echo $doc["Email"] ;?></td>
									<td><?php echo $doc["District"] ;?></td>
									
									<!-- <td><?php //echo anchor("panacea_mgmt/panacea_mgmt_manage_states/".$hs['_id'], lang('app_edit')) ;?>									
									<a class='ldelete' href='<?php //echo URL."panacea_mgmt/panacea_mgmt_delete_rhsos/".$doctor['_id'];?>'>
				            			<?php //echo lang('app_delete')?>
				            			</a>
									</td> -->
								</tr>
								
								  <?php endforeach;?>
				    			
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
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script>
/*$(document).ready(function() {
<?php if($message) {?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message!",
				content : "<?php //echo $message?>",
				color : "#C79121",
				iconSmall : "fa fa-bell bounce animated"
			});
<?php } ?>
});*/
</script>
<?php 
	//include footer
	include("inc/footer_bar.php"); 
?>