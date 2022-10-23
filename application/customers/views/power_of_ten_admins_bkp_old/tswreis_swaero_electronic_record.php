<?php $current_page = ""; ?>
<?php $main_nav = ""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include("inc/sidebar.php"); ?>

<section class="content">
	<div class="container-fluid">
	    <div class="block-header">
	        <h2>Electronic Swaero Record</h2>
	    </div>
	    <div class="row clearfix">
	        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	        	<div class="card">
	        		<div class="header">
	        			<button type="button" class="btn bg-pink waves-effect" data-toggle="tooltip" data-placement="top" title="Back" onclick="window.history.back();"><i class="material-icons">arrow_back</i></button>
	        		</div>
	        		<div class="body">
	        			<h2 class="card-inside-title">Personal Information</h2>
	        			 <div class="row clearfix">
	        			 	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
	                            <p>Photo</p>
	                        </div>
	                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
	                        	<div class="row">
	                        		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                                <div class="form-group">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Student Health ID" id="" value="" placeholder="Swaero Name" readonly/>
		                                    </div>
		                                </div>
	                                </div>
	                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                                <div class="form-group">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Class" value="" placeholder="Date Of Birth" readonly />
		                                    </div>
		                                </div>
	                                </div>
	                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
	                                        <div class="form-group">
	                                            <div class="form-line">
	                                                <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Section" value="" placeholder="Blood Group" readonly />
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
	                                        <div class="form-group">
	                                            <div class="form-line">
	                                                <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Mobile number" value="" placeholder="" readonly />
	                                            </div>
	                                        </div> 
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
	                        	<div class="row">
	                        		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                                <div class="form-group">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Student Name" value="" placeholder="Father Name" readonly />
		                                    </div>
		                                </div>
	                                </div>
	                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                                <div class="form-group">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="School Name" value="" placeholder="Occupation" readonly/>
		                                    </div>
		                                </div>
	                                </div>
	                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                                <div class="form-group">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Gender" value="" placeholder="" readonly/>
		                                    </div>
		                                </div>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
	                        	<div class="row">
	                        		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                                <div class="form-group">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control" data-toggle="tooltip" data-palcement="bottom" title="Father Name" value="" placeholder="Mother Name" readonly/>
		                                    </div>
		                                </div>
	                                </div>
	                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                                <div class="form-group">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Date of Birth" value="" placeholder="" readonly />
		                                    </div>
		                                </div>
	                                </div>
	                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                                <div class="form-group">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Date of Exam" value="" placeholder="" readonly/>
		                                    </div>
		                                </div>
	                                </div>
	                            </div>

	                        </div>
	                    </div><!-- ENd of personal Info row -->

	                    <h2 class="card-inside-title">Swaero information</h2>
	                    <div class="row clearfix">
	                    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                    		<ul class="nav nav-tabs tab-nav-right" role="tablist">
	                    			
	                    			<li role="presentation" class="active"><a href="#professional_info" data-toggle="tab" aria-expanded="true"><button class="btn bg-pink waves-effect" type="button">Professional Info</button></a></li>

	                                <li role="presentation"><a href="#educational_info" data-toggle="tab" ><button class="btn bg-cyan waves-effect" type="button">Educational Info</button></a></li>

	                                <li role="presentation"><a href="#request_pot_info" data-toggle="tab" ><button class="btn bg-red waves-effect" type="button">Request For Power Of Ten</button></a></li>

	                                <li role="presentation"><a href="#helped_info" data-toggle="tab" ><button class="btn bg-green waves-effect" type="button">Helped Info</button></a></li>
	                                
	                                <li role="presentation"><a href="#help_taken" data-toggle="tab" ><button class="btn bg-orange waves-effect" type="button">Help Taken Info</button></a></li>

	                                <li role="presentation"><a href="#contact_info" data-toggle="tab" ><button class="btn bg-teal waves-effect" type="button">Contact Details</button></a></li>

	                        	</ul>
	                            
	                    	</div>
	                    	<div class="tab-content">
		                    	<div id="professional_info" role="tabpanel" class="tab-pane fade in active in active">
		                    		<p>Professional Info</p>
		                    	</div>
		                    	<div id="educational_info" role="tabpanel" class="tab-pane fade">
		                    		<p>Educational Info</p>
		                    	</div>
		                    	<div id="request_pot_info" role="tabpanel" class="tab-pane fade">
		                    		<p>Request For Power Of Ten</p>
		                    	</div>
		                    	<div id="helped_info" role="tabpanel" class="tab-pane fade">
		                    		<p>Helped Info</p>
		                    	</div>
		                    	<div id="help_taken" role="tabpanel" class="tab-pane fade">
		                    		<p>Help Taken Info</p>
		                    	</div>
		                    	<div id="contact_info" role="tabpanel" class="tab-pane fade">
		                    		<p>Contact Details</p>
		                    	</div>
		                    </div>
	                    </div><!-- End of Second Row Tabs-->




	        		</div><!-- End of Body -->
	        	</div>
	        </div>
	    </div>
	</div>
</section>

<?php include('inc/footer_bar.php'); ?>
