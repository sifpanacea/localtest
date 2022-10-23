<?php $current_page="Manage_DMHO"; ?>
<?php $main_nav="Reports"; ?>
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
                              Total DMHO's <span class="badge bg-color-greenLight"><?php if(!empty($dmhonumberscount)) {?><?php echo $dmhonumberscount;?><?php } else {?><?php echo "0";?><?php }?></span>
                            </h2>                            
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
					            
								<thead>
								<tr>
									
									<th>District Code</th>
									<th>DMHO Name</th>
									<th>Phone Number</th>
									<th>Mobile Number</th>
									<th>Address</th>
									
								</tr>
							    </thead>
							    <tbody>
								<?php foreach ($hospitals as $hospital):?>					            
								<tr>
									
									<td><?php echo $hospital["hospital_code"] ;?></td>
									<td><?php echo ucwords($hospital["hospital_name"]) ;?></td>
									<td><?php echo $hospital["hospital_ph"] ;?></td>
									<td><?php echo $hospital["hospital_mob"] ;?></td>
									<td><?php echo ucwords($hospital["hospital_addr"]) ;?></td>
									
								</tr>
								<?php endforeach;?>
								
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
	
		
					
					
        
        

				

	
	





