<?php $current_page = "homepage"; ?>
<?php $main_nav = ""; ?>
<?php include("inc/header_bar.php"); ?>
<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">

<style type="text/css">
    .status_blink{
       
       color: rgb (0, 137, 226);
       animation: blink 3s infinite;
       padding: 10px;
       font-size: 20px;
   }
    @keyframes blink{
       0%{opacity: 1;}
       75%{opacity: 1;}
       76%{ opacity: 0;}
       100%{opacity: 0;}
}
  body {
  font-family: "Lato", sans-serif;
}

.sidepanel  {
  width: 0;
  position: fixed;
  z-index: 1;
  height: 250px;
  top: 100px;
  right: 0;
  background-color: #42f2f5;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidepanel a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 15px;
  color: black;
  display: block;
  transition: 0.3s;
}


.sidepanel a:hover {
  color: red;
}

.sidepanel .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
}

.openbtn {
  font-size: 15px;
  cursor: pointer;
  background-color: white;
  color: black;
  padding: 5px 5px;
  border: none;
}

.openbtn:hover {
  background-color:white;
}

.highcharts-figure, .highcharts-data-table table {
    min-width: 320px; 
    max-width: 660px;
    margin: 1em auto;
}

.highcharts-data-table table {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #EBEBEB;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}
.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}
.highcharts-data-table th {
    font-weight: 600;
    padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
    padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}
.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

</style>
<br>
<br>
<br>
<br>


<div class="container-fluid">
<!--FIlters For Admin Dashboard-->

    <!-- <button class="btn btn-block btn-lg  waves-effect">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"> 
            <img src="<?php //echo IMG; ?>/demo/img_5.jpg" style="width: 900px;">
        </div>
    </button>
    <br>
    <br> -->


     
    <div class="row clearfix">
        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-pink hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">directions_run</i>
                </div>
                <div class="content">
                    <div class="text">Sports Players</div>
                    <div class="number">2695</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-light-green hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">golf_course</i>
                </div>
                <div class="content">
                    <div class="text">Total Achievements</div>
                    <div class="number">2196</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-cyan hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">directions_bike</i>
                </div>
                <div class="content">
                    <div class="text">Only Paticipated</div>
                    <div class="number">499</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-orange hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">security</i>
                </div>
                <div class="content">
                    <div class="text">International Level Played</div>
                    <div class="number">17</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-light-green hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">rowing</i>
                </div>
                <div class="content">
                    <div class="text">National Level Played</div>
                    <div class="number">394</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-pink hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">gamepad</i>
                </div>
                <div class="content">
                    <div class="text">State Level Played</div>
                    <div class="number">1785</div>
                </div>
            </div>
        </div>
    </div>
       
<!-- End FIlters For Admin Dashboard -->

<!-- Info Cards Requests Information -->
<div class="row clearfix">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="card">
            <div class="body">
                <figure class="highcharts-figure">
                    <div id="sports_participated"></div>
                    <p class="highcharts-description">
                        Here Showing Game Wise Students Who Are in Sports.
                    </p>
                </figure>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="card">
            <div class="body">
                <figure class="highcharts-figure">
                    <!-- <div id="myPie"></div> -->
                    <div id="achievements_of_players"></div>
                    <!-- <div id="achievements_pie"></div> -->
                    <p class="highcharts-description">
                        Here Showing Achivements of Sports Players.
                    </p>
                </figure>
            </div>
        </div>
    </div>
   <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="card">
            <div class="body">
                <div id="zonal_wise_sports"></div>
                <p class="highcharts-description">
                    Here Showing Zonal wise Sports Players.
                </p>
            </div>
        </div>
    </div>
</div>
<!-- End Info Cards Requests Information -->


<!-- Strat International Level Achivements -->
<div class="row clearfix">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Social Network</h2>
            </div>
            <div class="body">
                    <a class="twitter-timeline" data-width="400" data-height="400" data-theme="white" href="https://twitter.com/RSPraveenSwaero?ref_src=twsrc%5Etfw">Tweets by RSPraveenSwaero</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Year Wise Sports Information</h2>
                <ul class="header-dropdown m-r--5  m-t--10">
                    <li>
                        <select class="form-control show-tick academic_filter common_change" id="academic_filter">
                            <option value="2019-20" selected="">2019-2020 AcademicYear</option>
                            <option value="2018-19">2018-2019 AcademicYear</option>
                            <option value="2017-18">2017-2018 AcademicYear</option>
                            <option value="2016-17">2016-2017 AcademicYear</option>
                            <option value="2015-16">2015-2016 AcademicYear</option>
                        </select>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div id="piechart_for_sports"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Sports Academies</h2>
                <ul class="header-dropdown m-r--5">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">more_vert</i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="javascript:void(0);">Action</a></li>
                            <li><a href="javascript:void(0);">Another action</a></li>
                            <li><a href="javascript:void(0);">Something else here</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover dashboard-task-infos">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Academy Name</th>
                                <th>Institution Venue</th>
                                <th>District</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Kabaddi</td>
                                <td><span class="label bg-green">TSWRS/JC (B) Rajapet</span></td>
                                <td>Yadadri Bhongir</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Kabaddi</td>
                                <td><span class="label bg-blue">TSWRS/JC (G) Nallakanche</span></td>
                                <td>Rangareddy</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Hand Ball</td>
                                <td><span class="label bg-light-blue">TSWRS/JC (B) Ghanpur</span></td>
                                <td>Jangaon</td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Hand Ball</td>
                                <td><span class="label bg-orange">TSWRS/JC (G)  Rayaparthi</span></td>
                                <td>Warangal</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Volley Ball</td>
                                <td>
                                    <span class="label bg-red">TSWRS/JC (B ) Achampet</span>
                                </td>
                                <td>Nagarkurnool</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Volley Ball</td>
                                <td>
                                    <span class="label bg-purple">TSWRS/JC (G) Chintakunta</span>
                                </td>
                                <td>Karimnagar</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Athletics</td>
                                <td>
                                    <span class="label bg-indigo">TSWRS/JC (B) Uppalwai</span>
                                </td>
                                <td>Nizamabad</td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="<?php echo URL."tswreis_sports_mgmt/get_sports_academies_list"; ?>">
                    	<button class="btn bg-blue waves-effect">More</button>
                    </a>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End International Level Achivements -->




</div> <!-- Container Div End -->

<!-- Info criteria for hb and bmi -->

<div class="modal fade" id="HB_and_BMI_Criteria" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Criteria for HB and BMI status</h4>
            </div>
            <div class="modal-body">
                <div id="carousel-example-generic_2" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic_2" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic_2" data-slide-to="1"></li>
                    </ol>
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <!-- <img src="<?php //echo IMG ;?>/demo/s1.jpg" /> -->
                            <div class="card">
                                <div class="body bg-grey" style="height: 320px;">
                                    <div class="font-bold m-b--35" style="text-align: center;">HB CALCULATION
                                    <br>
                                    <br>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <td>Category</td>
                                                    <td>Range</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Severe</td>
                                                    <td> <= 8</td>
                                                </tr>
                                                <tr>
                                                    <td>Moderate</td>
                                                    <td> Between 8.1 TO 10</td>
                                                </tr>
                                                <tr>
                                                    <td>Mild</td>
                                                    <td> Between 10.1 To 12</td>
                                                </tr>
                                                <tr>
                                                    <td>Normal</td>
                                                    <td> >=12</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card">
                                <div class="body bg-grey" style="height: 320px;">
                                    <div class="font-bold m-b--35" style="text-align: center;">BMI CALCULATION
                                    <br>
                                    <br>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <td>Category</td>
                                                    <td>Range</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Under Weight</td>
                                                    <td> <= 18.5</td>
                                                </tr>
                                                <tr>
                                                    <td>Normal</td>
                                                    <td> Between 18.5 TO 24.9</td>
                                                </tr>
                                                <tr>
                                                    <td>Over Weight</td>
                                                    <td> Between 25 To 29.9</td>
                                                </tr>
                                                <tr>
                                                    <td>Obese</td>
                                                    <td> >30</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-example-generic_2" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic_2" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
               
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<!-- School Health status Criteria Modal -->

<div class="modal fade" id="defaultModalshs" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">School Health Status Criteria</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                           <th>Type</th> 
                           <th>Red Zone</th> 
                           <th>Orange Zone</th> 
                           <th>Green Zone</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                           <th>Scabies</th>
                           <td>>5</td> 
                           <td>>2 & <5</td> 
                           <td><2 or No Cases</td> 
                           <!-- <td><span class="badge bg-teal">>5</span></td> 
                           <td><span class="badge bg-teal">>2 & <5</span></td> 
                           <td><span class="badge bg-teal"><2 or No Cases</span></td> --> 
                        </tr>
                        <tr>
                           <th>Abnormalities</th> 
                           <td>>6</td> 
                           <td>>2 & <5</td> 
                           <td><2 or No Cases</td> 
                        </tr>
                        <tr>
                           <th>HB status</th> 
                           <td>Severe aneamia >6 or all aneamic students in the school >10</td> 
                           <td>All aneamic cases(mild+moderate+severe) >25</td> 
                           <td><6</td> 
                        </tr>
                        <tr>
                           <th>BMI Status</th> 
                           <td>underweight bmi >6 or total bmi cases >17</td> 
                           <td>>25</td> 
                           <td><6</td>
                        </tr>
                        <tr>
                           <th>Bites</th> 
                           <td>>6</td> 
                           <td>>2 & <5</td> 
                           <td><2 or No Cases</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal For Attendance Submitted and Not Submitted Schools -->

<div class="modal fade" id="absent_sent_school_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Absent Report Submitted Schools List</h4>
            </div>
            <div id="absent_sent_school_modal_body" class="modal-body">
                <!-- Table is written in Script -->  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-teal waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="absent_not_sent_school_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Absent Report Not Submitted Schools List</h4>
            </div>
            <div id="absent_not_sent_school_modal_body" class="modal-body">
                <!-- Table is written in Script -->  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-teal waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<!-- End for Attendance modals-->

<!-- Modal for Sanitation Submitted and Not Sbmitted Schools list -->

<div class="modal fade" id="sani_repo_sent_school_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Sanitation Submitted Schools List</h4>
            </div>
            <div id="sani_repo_sent_school_modal_body" class="modal-body"></div>
            <!-- Table is written in script -->
            <div class="modal-footer">
                <button type="button" class="btn bg-teal waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="sani_repo_not_sent_school_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Sanitation Not Submitted Schools List</h4>
            </div>
            <div id="sani_repo_not_sent_school_modal_body" class="modal-body">
                <!-- Table is written in Script -->  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-teal waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<!-- End Sanitation Modal-->

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="loading_modal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" id="loading">
            <center><img src="<?php echo(IMG.'loader.gif'); ?>" id="gif" ></center>
        </div>
    </div>
</div>

<!-- Card  Modal For Total School List info -->

<div class="modal fade" id="total_schools_listss" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h4 class="modal-title" id="defaultModalLabel">Total Schools List</h4>
           </div>
           <div class="modal-body" id="data_Scls">              
           </div>
           <div class="modal-footer">
               <button type="button" class="btn bg-teal waves-effect" data-dismiss="modal">CLOSE</button>
           </div>
       </div>
   </div>
</div>
<!-- End Card Model schools List -->

<div class="modal fade" id="doc_res_names" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Today Doctor Responses For Requests</h4>
            </div>
            <div class="modal-body">
              <div id="doc_res_table_view"></div>
            </div>
            <div class="modal-footer">
               
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<form style="display: hidden" action="get_achievements_wise_data" method="POST" id="achievements_wise_data">
      <input type="hidden" id="achieve_data" name="achieve_data"/>      
      <input type="hidden" id="achievement_type" name="achievement_type"/>      
</form> 

<form style="display: hidden" action="get_sport_type_data" method="POST" id="sports_name_data">
    <input type="hidden" id="sport_name" name="sport_name"/>   
</form>

<form style="display: hidden" action="get_zonal_wise_data" method="POST" id="zone_wise_data">
    <input type="hidden" id="" name="" value=""/>
        <input type="hidden" id="" name="" value=""/>
    <input type="hidden" id="" name="" value=""/>
</form>

<form style="display: hidden" action="get_year_wise_sports_data_drill" method="POST" id="year_wise_sports_data">
    <input type="hidden" id="spot_academic" name="spot_academic" value=""/>
    <input type="hidden" id="value_level" name="value_level" value=""/>    
</form>

<form style="display: hidden" action="get_hospitalized_data_table" method="POST" id="hospitalized_table">
   <input type="hidden" id="start_date" name="start_date" value=""/>
   <input type="hidden" id="end_date" name="end_date" value=""/>
    <input type="hidden" id="request_type" name="request_type" value=""/>
    <input type="hidden" id="hospital_district" name="hospital_district" value=""/>
</form>

<form style="display: hidden" action="get_sanitation_report_school_wise" method="POST" id="sanitation_table">
    <input type="hidden" id="date_sani" name="today_date" value=""/>
    <input type="hidden" id="value_sani" name="sanitation_type" value=""/>
</form>


<script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.fillbetween.min.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.orderBar.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.pie.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.time.min.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.axislabels.js"></script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
<!-- Google Charts -->
<script src="<?php echo(MDB_JS.'loader.js'); ?>"></script>

<!-- Jquery Core Js -->
<script src="<?php echo MDB_PLUGINS."jquery/jquery.min.js"; ?>"></script>

<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
<script src="<?php echo JS; ?>/d3pie/d3.js"></script>


<!-- Waves Effect Plugin Js -->
<script src="<?php echo MDB_PLUGINS."node-waves/waves.js"; ?>"></script>

<!-- Autosize Plugin Js -->
<script src="<?php echo MDB_PLUGINS."autosize/autosize.js"; ?>"></script>

<script src="<?php echo(MDB_JS.'exporting.js'); ?>"></script>

 <script src="<?php echo(MDB_JS.'export-data.js'); ?>"></script>
 
<script src="<?php echo(MDB_JS.'accessibility.js'); ?>"></script>


<!-- Moment Plugin Js -->
<script src="<?php echo MDB_PLUGINS."momentjs/moment.js"; ?>"></script>

<!-- Custom Js -->
<script src="<?php echo(MDB_JS.'admin.js'); ?>"></script>
<script src="<?php echo(MDB_JS.'pages/forms/basic-form-elements.js'); ?>"></script>

<!-- Demo Js -->
<script src="<?php echo(MDB_JS.'demo.js'); ?>"></script>

<!-- Bootstrap Datepicker Plugin Js -->
<script src="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script> 

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>


<?php include("inc/footer_bar.php"); ?>

<script type="text/javascript">
    
/*==========Start Achievements Of Players=========*/

    draw_registrations_pie();

    function draw_registrations_pie(){
     
        $.ajax({
          url : 'get_achievements_participation_wise',
          type : 'POST',
          data:'',
          success:function(data){
            data = $.parseJSON(data);
            registration_pie("registrations",data,"drill_down_to_medal_achievement");
          }

        });
     
    }

    function registration_pie(heading, data, onClickFn, second_para = false){

      // place bar chart code
            
            // Create the chart
            Highcharts.chart('achievements_of_players', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Achievements'
                },
                subtitle: {
                    text: 'Click on slides to see achievements level'
                },

                accessibility: {
                    announceNewData: {
                        enabled: true
                    },
                    point: {
                        valueSuffix: ''
                    }
                },

                plotOptions: {
                    series: {
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}: {point.y}'
                        },
                        point: {
                                    events: {
                                        click: function(e){
                                        console.log(onClickFn);
                                   
                                    
                                        if(onClickFn == "drill_down_to_medal_achievement"){
                                          console.log(e);
                                           var pointName = e.point.name;
                                            //alert(pointName);
                                          //previous_absent_a_value[1] = data;
                                          drill_down_to_medal_achievement(pointName);
                                          
                                        }else if(onClickFn == "drill_down_to_achieved_students"){
                                          console.log(e);
                                          var clickedData = e.point.name;
                                          //alert(clickedData);
                                          //alert(heading);
                                          $('#achieve_data').val(clickedData);
                                          $('#achievement_type').val(heading);
                                          $('#achievements_wise_data').submit();
                                          drill_down_to_achieved_students(clickedData, heading);
                                        }
                                      }
                                    }
                                }
                    }
                },  

                tooltip: {
                    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
                },

                series: [
                    {
                        name: "Achievements",
                        colorByPoint: true,
                        data: data
                        
                    }
                ]
               
            });


    }

    function drill_down_to_medal_achievement(pointName){
      console.log(pointName);
      
      $.ajax({
        url: 'drilldown_selected_to_medals',
        type: 'POST',
        data: {"data" : pointName},
        success: function (data) {
          console.log(data);
          var content = $.parseJSON(data);
          console.log(content);
         //  $( "#achievements_of_players" ).empty();
         // $("#achievements_of_players").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_absent_back(1);"> Back </button>');
         // $("#achievements_of_players").append('<button class="btn btn-primary pull-right" id="absent_back_btn" ind= "1"> Back </button>');

          registration_pie(pointName,content,"drill_down_to_achieved_students");
          
          },
            error:function(XMLHttpRequest, textStatus, errorThrown)
          {
           console.log('error', errorThrown);
            }
        });
      }

    function drill_down_to_achieved_students(clickedData, heading){
    }




    $(document).on("click",'#absent_back_btn',function(e){
        var index = $(this).attr("ind");
        console.log("in back functionnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn-----------");
        console.log(index);
        $( "#achievements_of_players" ).empty();
        if(index>1){
          var ind = index - 1;
        $("#achievements_of_players").append('<button class="btn btn-primary pull-right" id="absent_back_btn" ind= "' + ind + '"> Back </button>');
        }
        registration_pie(previous_absent_title_value[index], previous_absent_a_value[index], index);
    });
/*==========End Achievements Of Players=========*/


/*==========Start Yearwise Achievements data==========*/

    
    default_filters();

    $('.common_change').change(function(){
    default_filters();
    });


   function default_filters(){

        var academicYear = $('.academic_filter').val();
         $.ajax({
        url:'get_yearwise_achievements_pie',
        type:'POST',
        data:{'academic_year': academicYear},
        success:function(data){
            var data = $.parseJSON(data);
             show_yearwise_achievements_bar(data);
            }
        });

    }

   function show_yearwise_achievements_bar(data){

    $('#piechart_for_sports').empty();
    var International = data.International;
    var National = data.National;
    var State = data.State;
    

    if(International == 0 && National == 0 && State == 0){
        $('#piechart_for_sports').html("No Data Found")
    }else{

    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Requests', 'Count'],
          ['International', International],
          ['National', National],
          ['State', State],
          
        ]);

        var options = {
         // title: 'Request Pie Info',
          pieSliceText: 'value',
          is3D: true,
          width: 450,
          height: 400, 
          tooltip: {
                text: 'value'
            }
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_for_sports'));

        function selectHandler() {
            var selectedItem = chart.getSelection()[0];
            if (selectedItem) {
              var value = data.getValue(selectedItem.row, 0);
             //alert('The user selected ' + value);
              //var date = $('#set_date').val();
             // var district = $('.district_filter').val();
             // var school = $('.school_filter').val();
              var academicYear = $('.academic_filter').val();
              //var gender =  $('.gender_filter').val();
              //$('#date_req').val(date);
              $('#value_level').val(value);
              //$('#req_dist').val(district);
              //$('#req_scl').val(school);
              $('#spot_academic').val(academicYear);
              $('#year_wise_sports_data').submit();
             
            }
        }

        google.visualization.events.addListener(chart, 'select', selectHandler);

        chart.draw(data, options);

      }


    }

    $(window).resize(function(){
    drawChart();
    }); 
    
};

/*=======END Yeraly Achievements data==========*/

/*======= Sports Participated PIE============= */
    show_registered_data_two();

    function show_registered_data_two(){
        $.ajax({
                url: 'get_registered_students_data',
                type: 'POST',
                data: "",
                success: function (data) {          

                    result = $.parseJSON(data);

                    console.log(result);
                    get_registered_data(result);
                            
                    },
                    error:function(XMLHttpRequest, textStatus, errorThrown)
                    {
                     console.log('error', errorThrown);
                    }
            });
    }
    

    function get_registered_data(result)
    {
        // Create the chart
        Highcharts.chart('sports_participated', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Sports Participated'
            },
            subtitle: {
                text: 'Click on slides to see district level'
            },

            accessibility: {
                announceNewData: {
                    enabled: true
                },
                point: {
                    valueSuffix: ''
                }
            },

            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.y}'
                    },
                    point: {
                        events: {
                        click: function(e){
                          //var seriesName = e.point.series.name;
                          var pointName = e.point.name;
                          //alert(seriesName);
                          //alert(pointName);
                           $("#sport_name").val(pointName);
                          $('#sports_name_data').submit();
                        }
                      }
                    }
                }
            },  

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
            },

            series: [
                {
                    name: "Participated",
                    colorByPoint: true,
                    data: result   
                }
            ]/*,
            drilldown: {
                series: [
                    {
                        name: "Swaero alumni",
                        id: "Swaero alumni",
                        data: [
                            [
                                "Adilabad",
                                1000
                            ],
                            [
                                "Hyderabad",
                                3000
                            ]
                        ]
                    },
                    {
                        name: "Firefox",
                        id: "Firefox",
                        data: [
                            [
                                "v58.0",
                                1.02
                            ]
                        ]
                    },
                    {
                        name: "Internet Explorer",
                        id: "Internet Explorer",
                        data: [
                            [
                                "v11.0",
                                6.2
                            ],
                            [
                                "v10.0",
                                0.29
                            ]
                        ]
                    },
                    {
                        name: "Safari",
                        id: "Safari",
                        data: [
                            [
                                "v11.0",
                                3.39
                            ]
                        ]
                    }
                ]
            }*/
        });
    }

 /*=========End Sports Participated===============*/   
   
</script>

<!-- ===== Start Zonal Wise Reports======== -->
<script type="text/javascript">
    Highcharts.chart('zonal_wise_sports', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'Zonal Wise Sports Students'
    },
    subtitle: {
        text: 'Click the slices to view versions. Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
    },

    accessibility: {
        announceNewData: {
            enabled: true
        },
        point: {
            valueSuffix: '%'
        }
    },

    plotOptions: {
        series: {
            dataLabels: {
                enabled: true,
                format: '{point.name}: {point.y}'
            },
            point: {
                events: {
                click: function(e){
                  var seriesName = e.point.series.name;
                  var pointName = e.point.name;
                  alert(seriesName);
                  alert(pointName);
                  $('#zone_wise_data').submit();
                  
                }
              }
            }
        }
    }, 

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
    },

    series: [
        {
            name: "Zones",
            colorByPoint: true,
            data: [
                {
                    name: "Zone 1",
                    y: 62,
                    drilldown: "Zone 1"
                },
                {
                    name: "Zone 2",
                    y: 12,
                    drilldown: "Zone 2"
                },
                {
                    name: "Zone 3",
                    y: 75,
                    drilldown: "Zone 3"
                },
                {
                    name: "Zone 4",
                    y: 90,
                    drilldown: "Zone 4"
                }
            ]
        }
    ],
    drilldown: {
        series: [
            {
                name: "Zone 1",
                id: "Zone 1",
                data: [
                    [
                        "Adilabad",
                        54
                    ],
                    [
                        "Karimnagar",
                        42
                    ]
                ]
            },
            {
                name: "Zone 2",
                id: "Zone 2",
                data: [
                    [
                        "Khammam",
                        74
                    ],
                    [
                        "Warangal",
                        56
                    ]
                ]
            },
            {
                name: "Zone 3",
                id: "Zone 3",
                data: [
                    [
                        "Hyderabad",
                        56
                    ],
                    [
                        "Ranga Reddy",
                        25
                    ],
                    [
                        "Mahabubnagar",
                        38
                    ],
                    [
                        "Nalgonda",
                        65
                    ]
                ]
            },
            {
                name: "Zone 4",
                id: "Zone 4",
                data: [
                    [
                        "Nizamabad",
                        65
                    ],
                    [
                        "Medak",
                        21
                    ]
                ]
            }
        ]
    }
});
</script>
<!-- ===== End Zonal Wise Reports======== -->


