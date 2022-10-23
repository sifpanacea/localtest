<?php $current_page="Students Sports EHR"; ?>
<?php $main_nav="" ;?>
<?php
include('inc/header_bar.php');
?>
<br>
<br>
<br>
<br>
<br>

<section class="">
<div class="container-fluid">
    <div class="block-header">
        <h2>Electronic Sports Record</h2>
    </div>
    <!-- Input -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">

                    <button type="button" class="btn bg-pink waves-effect" data-toggle="tooltip" data-placement="top" title="Back" onclick="window.history.back();"><i class="material-icons">arrow_back</i></button>

                   <!--  <button type="button" class="btn bg-green waves-effect" data-toggle="tooltip" data-placement="top" title="Print" id="submit_print_request"><i class="material-icons">print</i></button> -->

                    <!-- <button class="btn bg-deep-purple waves-effect pull-right" data-toggle="tooltip" data-placement="top" title="Edit EHR"><i class="material-icons">mode_edit</i></button> -->

                </div>
                
                <div class="body">
                    <h2 class="card-inside-title">Student Personal Information</h2>

                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        	<div class="row">
                        		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                <div class="form-group">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Student ID" id="" value="" placeholder="Student ID" readonly/>
	                                    </div>
	                                </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                <div class="form-group">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Fathers Name" value="" placeholder="Fathers Name" readonly />
	                                    </div>
	                                </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Age" value="" placeholder="Age" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Mobile number" value="" placeholder="Mobile number" readonly />
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
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Date of Birth" value="" placeholder="Student Name" readonly />
	                                    </div>
	                                </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                <div class="form-group">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="School Name" value="" placeholder="School Name" readonly/>
	                                    </div>
	                                </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                <div class="form-group">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="Gender" value="" placeholder="Gender" readonly/>
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
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-palcement="bottom" title="" value="" placeholder="" readonly/>
	                                    </div>
	                                </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                <div class="form-group">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="" value="" placeholder="" readonly />
	                                    </div>
	                                </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                <div class="form-group">
	                                    <div class="form-line">
	                                        <input type="text" class="form-control" data-toggle="tooltip" data-placement="bottom" title="" value="" placeholder="" readonly/>
	                                    </div>
	                                </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                            <p>Student Photo</p>
                        </div>
                    </div>
                   


                    <h2 class="card-inside-title">Student Sports Participate information</h2>
                    <div class="row clearfix">
                    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    		<ul class="nav nav-tabs tab-nav-right" role="tablist">
                    			
                    			<li role="presentation" class="active"><a href="#day_to_day_activities" data-toggle="tab" aria-expanded="true"><button class="btn bg-pink waves-effect" type="button">Day to Day WorkOuts</button></a></li>

                                <li role="presentation"><a href="#achivements_info" data-toggle="tab" ><button class="btn bg-cyan waves-effect" type="button">Achivements</button></a></li>

                                <li role="presentation"><a href="#goals_info" data-toggle="tab" ><button class="btn bg-red waves-effect" type="button">Goals</button></a></li>

                                <li role="presentation"><a href="#media_info" data-toggle="tab" ><button class="btn bg-green waves-effect" type="button">Media and Press Notes</button></a></li>

                                <li role="presentation"><a href="#coach_info" data-toggle="tab" ><button class="btn bg-teal waves-effect" type="button">Coach Details</button></a></li>

                                <li role="presentation"><a href="#status_info" data-toggle="tab" ><button class="btn bg-orange waves-effect" type="button">Status</button></a></li>
                                
                        	</ul>
                            
                    	</div>

<!-- <li role="presentation" class="active"><a href="#home" data-toggle="tab" aria-expanded="true"><button class="btn bg-green btn-lg btn-block waves-effect" type="button">General <span class="badge">3</span></button></a></li> -->
<!-- ================================================================================================================ -->

<!-- SCREENING INFORMATION -->    	
<div class="tab-content">
<div id="day_to_day_activities" role="tabpanel" class="tab-pane fade in active in active">
  


</div> 
<!-- =============================END OF SCREENING INFORMATION SECTION=============================================== -->



<!-- =====================================REQUEST INFORMATION START============================================ -->


<div id="achivements_info" role="tabpanel" class="tab-pane fade">

</div>

<!-- =====================================REQUEST INFORMATION END============================================= -->

<!-- =====================================HB INFORMATION START============================================= -->
                             
<div id="goals_info" role="tabpanel" class="tab-pane fade">

</div>

<!-- =====================================HB INFORMATION END============================================= -->

<!-- =====================================BMI INFORMATION START============================================= -->                                
<div id="media_info" role="tabpanel" class="tab-pane fade">
    
</div>

<!-- =====================================BMI INFORMATION END============================================= -->




<!-- =====================================CALLING INFO START============================================= -->                                
<div id="coach_info" role="tabpanel" class="tab-pane fade" >
   
</div>

<!-- =====================================CALLING INFO END============================================= --> 


<!-- ===================================== NOTES START ============================================= -->                                
<div id="status_info" role="tabpanel" class="tab-pane fade">
   
</div>

<!-- =====================================NOTES END============================================= -->






	                        </div>
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

<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>
<!-- <script src="https://cdn.bootcss.com/prettify/r298/prettify.min.js"></script> -->
<script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
<script src="<?php echo JS; ?>img_options/jquery.magnify.js"></script>


