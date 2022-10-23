<?php $current_page = ""; ?>
<?php $main_nav = ""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Organisations</h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
				    <div class="header">
				        <h2>
				            All Swaero Organisations
				        </h2>
				        <ul class="header-dropdown m-r--5">
				            <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
				        </ul>
				    </div>
				    <div class="body">
				        <div class="row">
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
				            		    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/life_line_league.jpg" alt="" title="" />
				            		    <div class="caption">
				            		        <h3>Life Line League</h3>
				            		        <input type="hidden" name="org_names" value="Life Line League">
				            		        <p>
				            		            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
				            		            text ever since the 1500s
				            		        </p>

				            		       <p>
				            		       	<button type="submit" class="btn btn-primary waves-effect">Get Info</button>
				            		       </p>
				            		    </div>
				            		</div>
				            	</form>
				               
				            </div>
				            <div class="col-sm-6 col-md-3">
				            	<form action="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info" ?>" method="post" enctype="multipart/form-data">
					                <div class="thumbnail">
					                    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/fit_india_foundation.jpg" alt="" title="" />
					                    <div class="caption">
					                        <h3>Fit India Foundation</h3>
					                        <input type="hidden" name="org_names" value="Fit India Foundation">
					                        <p>
					                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
					                            text ever since the 1500s
					                        </p>
					                        <p>
					                            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
					                        </p>
					                    </div>
					                </div>
				                </form>
				            </div>
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
					                    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/swaeroes.png" alt="" title="" />
					                    <div class="caption">
					                        <h3>Swaero International</h3>
					                        <input type="hidden" name="org_names" value="Swaero International">
					                        <p>
					                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
					                            text ever since the 1500s
					                        </p>
					                        <p>
					                            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
					                        </p>
					                    </div>
					                </div>
				            	</form>
				            </div>
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
				            		    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/swaero_circle.png" alt="" title="" />
				            		    <div class="caption">
				            		        <h3>Swaero Circle</h3>
				            		        <input type="hidden" name="org_names" value="Swaero Circle">
				            		        <p>
				            		            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
				            		            text ever since the 1500s
				            		        </p>
				            		        <p>
				            		            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
				            		        </p>
				            		    </div>
				            		</div>
				            	</form>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
				            		    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/TGPA.jpg" alt="" title="" />
				            		    <div class="caption">
				            		        <h3>TGPA</h3>
				            		        <input type="hidden" name="org_names" value="TGPA">
				            		        <p>
				            		            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
				            		            text ever since the 1500s
				            		        </p>
				            		        <p>
				            		            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
				            		        </p>
				            		    </div>
				            		</div>
				            	</form>
				            </div>
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
				            		    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/SAFE.png" alt="" title="" />
				            		    <div class="caption">
				            		        <h3>SAFE</h3>
				            		        <input type="hidden" name="org_names" value="SAFE">
				            		        <p>
				            		            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
				            		            text ever since the 1500s
				            		        </p>
				            		        <p>
				            		            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
				            		        </p>
				            		    </div>
				            		</div>
				            	</form>
				            </div>
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
				            		    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/TTC.png" alt="" title="" />
				            		    <div class="caption">
				            		        <h3>TTC</h3>
				            		        <input type="hidden" name="org_names" value="TTC">
				            		        <p>
				            		            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
				            		            text ever since the 1500s
				            		        </p>
				            		        <p>
				            		            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
				            		        </p>
				            		    </div>
				            		</div>
				            	</form>
				            </div>
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
				            		    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/swaero_employee_association.jpg" alt="" title="" />
				            		    <div class="caption">
				            		        <h3>Swaeroes Employee Association</h3>
				            		        <input type="hidden" name="org_names" value="Swaeroes Employee Association">
				            		        <p>
				            		            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
				            		            text ever since the 1500s
				            		        </p>
				            		        <p>
				            		            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
				            		        </p>
				            		    </div>
				            		</div>
				            	</form>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
				            		    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/SSU.png" alt="" title="" />
				            		    <div class="caption">
				            		        <h3>SSU</h3>
				            		        <input type="hidden" name="org_names" value="SSU">
				            		        <p>
				            		            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
				            		            text ever since the 1500s
				            		        </p>
				            		        <p>
				            		            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
				            		        </p>
				            		    </div>
				            		</div>
				            	</form>
				            </div>
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
				            		    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/swaero_times.png" alt="" title="" />
				            		    <div class="caption">
				            		        <h3>Swaero Times</h3>
				            		        <input type="hidden" name="org_names" value="Swaero Times">
				            		        <p>
				            		            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
				            		            text ever since the 1500s
				            		        </p>
				            		        <p>
				            		            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
				            		        </p>
				            		    </div>
				            		</div>
				            	</form>
				            </div>
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
				            		    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/SMACCS.png" alt="" title="" />
				            		    <div class="caption">
				            		        <h3>SMACCS</h3>
				            		        <input type="hidden" name="org_names" value="SMACCS">
				            		        <p>
				            		            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
				            		            text ever since the 1500s
				            		        </p>
				            		        <p>
				            		            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
				            		        </p>
				            		    </div>
				            		</div>
				            	</form>
				            </div>
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
				            		    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/TS-Gurukulam-Logo.jpg" alt="" title="" />
				            		    <div class="caption">
				            		        <h3>Swaeroes Educational Foundation</h3>
				            		        <input type="hidden" name="org_names" value="Swaeroes Educational Foundation">
				            		        <p>
				            		            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
				            		            text ever since the 1500s
				            		        </p>
				            		        <p>
				            		            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
				            		        </p>
				            		    </div>
				            		</div>
				            	</form>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
				            		    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/swaeroes.png" alt="" title="" />
				            		    <div class="caption">
				            		        <h3>Swaero Lawyer Association</h3>
				            		        <input type="hidden" name="org_names" value="Swaero Lawyer Association">
				            		        <p>
				            		            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
				            		            text ever since the 1500s
				            		        </p>
				            		        <p>
				            		            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
				            		        </p>
				            		    </div>
				            		</div>
				            	</form>
				            </div>
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
				            		    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/swaeroes.png" alt="" title="" />
				            		    <div class="caption">
				            		        <h3>Confidential Entrepreneur Eco System</h3>
				            		        <input type="hidden" name="org_names" value="Confidential Entrepreneur Eco System">
				            		        <p>
				            		            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
				            		            text ever since the 1500s
				            		        </p>
				            		        <p>
				            		            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
				            		        </p>
				            		    </div>
				            		</div>
				            	</form>
				            </div>
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
				            		    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/swaeroes.png" alt="" title="" />
				            		    <div class="caption">
				            		        <h3>SDC(Digital Communication)</h3>
				            		        <input type="hidden" name="org_names" value="SDC(Digital Communication)">
				            		        <p>
				            		            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
				            		            text ever since the 1500s
				            		        </p>
				            		        <p>
				            		            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
				            		        </p>
				            		    </div>
				            		</div>
				            	</form>
				            </div>
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
				            		    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/swaeroes.png" alt="" title="" />
				            		    <div class="caption">
				            		        <h3>Swaero Star Alumni Association</h3>
				            		        <input type="hidden" name="org_names" value="Swaero Star Alumni Association">
				            		        <p>
				            		            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
				            		            text ever since the 1500s
				            		        </p>
				            		        <p>
				            		            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
				            		        </p>
				            		    </div>
				            		</div>
				            	</form>
				            </div>
				        </div>
				        <div class="row">
				            <div class="col-sm-6 col-md-3">
				            	<form method="post" action ="<?php echo URL."power_of_ten_mgmt/get_organisation_wise_info"; ?>" enctype = "multipart/form-data" >
				            		<div class="thumbnail">
				            		    <img class=""  style="height: 100px;" src="<?php echo IMG; ?>/swaeroes.png" alt="" title="" />
				            		    <div class="caption">
				            		        <h3>Ap state and Karnataka</h3>
				            		        <input type="hidden" name="org_names" value="Ap state and Karnataka">
				            		        <p>
				            		            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy
				            		            text ever since the 1500s
				            		        </p>
				            		        <p>
				            		            <button type="submit" class="btn btn-primary waves-effect">Get Info</button>
				            		        </p>
				            		    </div>
				            		</div>
				            	</form>
				            </div>
				        </div>
				    </div>
            	</div>
            </div>
        </div>
    </div>
</section>


<?php include('inc/footer_bar.php'); ?>





