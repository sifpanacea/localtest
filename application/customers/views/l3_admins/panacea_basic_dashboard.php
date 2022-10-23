<?php $current_page = "homepage"; ?>
<?php $main_nav = ""; ?>
<?php include("inc/header_bar.php"); ?>
<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src = "https://code.highcharts.com/highcharts.js"></script>

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
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

#map {
  height: 100%;
}

/* Optional: Makes the sample page fill the window. */
html,
body {
  height: 100%;
  margin: 0;
  padding: 0;
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
    <!-- <button class="btn btn-block btn-lg bg-pink waves-effect">
           <?php //if(!empty($news)): ?>
               <marquee  direction="left" onmouseover="this.stop();" onmouseout="this.start();" onclick ="">
               <?php //foreach($news as $new): ?>
              <img src="<?php //echo IMG; ?>/Panacea_small.png"><span class="label bg-green"><?php //echo $new['username']; ?></span><?php //echo $new['news_feed']; ?> <a href="#" class="open_news" news_data="<?php //echo $new['_id']; ?>" style="color:#0a0a0a"></a>&nbsp; &nbsp;
           <?php //endforeach; ?>
           </marquee>
           <?php //else: ?>
               <marquee  direction="left"><img src="<?php //echo IMG; ?>/Panacea_small.png">No News Today</marquee>
           <?php //endif; ?>
       </button> -->

       <!--FIlters For Admin Dashboard-->
      <!-- <div id="" class="panel"> -->
        <div class="panel-body">
            <div class="row clearfix">
              <button type="button" id="" class="btn bg-blue waves-effect">
                 Vidya Nidhi
              </button>
              <button type="button" id="" class="btn bg-blue waves-effect">
                Social Media
              </button>
              <button type="button" id="" class="btn bg-blue waves-effect">
                  Beneficiaries
              </button>
              <button type="button" id="" class="btn bg-blue waves-effect">
                  GPS Tracking
              </button>
              <button type="button" id="" class="btn bg-blue waves-effect">
                  Support
              </button>
              <button type="button" id="" class="btn bg-blue waves-effect">
                  Events&Activity
              </button>
              <button type="button" id="" class="btn bg-blue waves-effect">
                  Information
              </button>
              <button type="button" id="" class="btn bg-blue waves-effect">
                  Swaero Community
              </button>
              <a href='<?php echo URL."l3_mgmt/tswreis_swaero_electronic_record"; ?>'>
                  <button type="button" id="" class="btn bg-blue waves-effect">Swaero ID</button>
              </a>
            </div>
        </div>
      <!-- </div> -->
<!-- End FIlters For Admin Dashboard -->
      
     <div class="row clearfix">
        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-pink hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">group</i>
                </div>
                <div class="content">
                    <div class="text">Total Swaeros</div>
                    <div class="number">500</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-light-green hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">card_membership</i>
                </div>
                <div class="content">
                    <div class="text">Total Registered Swaeros</div>
                    <div class="number">50</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-indigo hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">account_box</i>
                </div>
                <div class="content">
                    <div class="text">Total NGO's</div>
                    <div class="number">15</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-orange hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">people_outline</i>
                </div>
                <div class="content">
                    <div class="text">Parents Of Swaeros</div>
                    <div class="number">30</div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-teal hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">assignment_ind</i>
                </div>
                <div class="content">
                    <div class="text">Teachers of Swaeros</div>
                    <div class="number">15</div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-blue hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">school</i>
                </div>
                <div class="content">
                    <div class="text">professionals</div>
                    <div class="number">15</div>
                </div>
            </div>
        </div>
    </div>  


<!-- Info Cards Requests Information -->
<div class="row clearfix">

    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
        <div class="card">
            <div class="header">
               <h2>Power Of Ten Registrations</h2>
            </div>

            <div class="body">
                <div id="container"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
        <div class="card">
            <div class="header">
              <h2>Funds Raised</h2>
            </div>
            <div class="body">
                <div id="container1"></div>
                <p class="highcharts-description">
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
        <div class="card">
            <div class="header">
              <h2>Professtion wise Requests</h2>
            </div>
            <div class="body">
               <div id="chart_div" style="width: 400px; height: 400px;"></div> 
            </div>
        </div>
    </div>
    
</div>
<!-- End Info Cards Requests Information -->

<!-- Screening and requests cards Info -->
<div class="row clearfix">
    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
        <div class="card">
            <div class="header">
              <h2>Requests</h2>
            </div>
            <div class="body">
                <div id="barchart_values" style="width: 250px; height: 300px;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
      <div class="card">
        
        <div class="body bg-grey">
            <!-- <div id="map">
            </div> -->
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3807.1784403590777!2d78.44836071487656!3d17.403222188069112!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb973fd9ec85f9%3A0xd795df8b06a63210!2sDSS%20Bhavan%2C%20Owaisi%20Pura%2C%20Masab%20Tank%2C%20Hyderabad%2C%20Telangana%20500028!5e0!3m2!1sen!2sin!4v1615031094435!5m2!1sen!2sin" width="900" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>
    </div>

    
</div>
<!-- End Screening and requests cards Info -->

<!-- Second row card Info HB and BMI -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <div class="card">
         <div class="header">
            <h2>Social Network</h2>
         </div>
         <div class="body">
            <div class="row clearfix">
              <div class="col-md-4">
                  <a class="twitter-timeline" data-width="400" data-height="400" data-theme="white" href="https://twitter.com/RSPraveenSwaero?ref_src=twsrc%5Etfw">Tweets by RSPraveenSwaero</a>
              </div>
              <div class="col-md-4">
                  <iframe width="500" height="380" src="https://www.youtube-nocookie.com/embed/wdiJkpm3vlo" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              </div>
              <div class="col-md-4"></div>
            </div>
        </div>
      </div>
    </div>
</div>
<!-- End Second row card Info HB and BMI -->

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

<form style="display: hidden" action="get_day_to_day_glance_data_fetching" method="POST" id="day_to_day_glance_form">
      <input type="hidden" id="to_date" name="today_date" value=""/>
      <input type="hidden" id="day_to_day_status" name="day_to_day_status" value=""/>
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

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

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
<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC6Y8nEdDezRjxDeNtHFbtD1eCyLdhvskI&callback=initMap&libraries=&v=weekly"
      async
    >
</script>

<?php include("inc/footer_bar.php"); ?>

<script type="text/javascript">
    show_registered_data();

    function show_registered_data()
    {
        // Create the chart
        Highcharts.chart('container', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Power of 10 Registrations'
            },
            subtitle: {
                text: 'Click on slides to see district level'
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
                        format: '{point.name}: {point.y:.1f}%'
                    }
                }
            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
            },

            series: [
                {
                    name: "Browsers",
                    colorByPoint: true,
                    data: [
                        {
                            name: "Swaero alumni",
                            y: 62.74,
                            drilldown: "Swaero alumni"
                        },
                        {
                            name: "Parents",
                            y: 10.57,
                            drilldown: "Parents"
                        },
                        {
                            name: "Teachers",
                            y: 7.23,
                            drilldown: "Teachers"
                        },
                        {
                            name: "Swaero professionals",
                            y: 5.58,
                            drilldown: "Swaero professionals"
                        },
                        {
                            name: "Employees",
                            y: 4.02,
                            drilldown: "Employees"
                        },
                        {
                            name: "Swaero business man",
                            y: 1.92,
                            drilldown: "Swaero business man"
                        },
                        {
                            name: "NGO",
                            y: 7.62,
                            drilldown: "NGO"
                        }
                    ]
                }
            ],
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
                            ],
                            [
                                "Ranga Reddy",
                                25000
                            ],
                            [
                                "Khammam",
                                12000
                            ],
                            [
                                "Gadwal",
                                2500
                            ],
                            [
                                "Bhadradri",
                                45000
                            ],
                            [
                                "Medak",
                                12000
                            ],
                            [
                                "v58.0",
                               6000
                            ],
                            [
                                "Nalgonda",
                               8000
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
                            ],
                            [
                                "v57.0",
                                7.36
                            ],
                            [
                                "v56.0",
                                0.35
                            ],
                            [
                                "v55.0",
                                0.11
                            ],
                            [
                                "v54.0",
                                0.1
                            ],
                            [
                                "v52.0",
                                0.95
                            ],
                            [
                                "v51.0",
                                0.15
                            ],
                            [
                                "v50.0",
                                0.1
                            ],
                            [
                                "v48.0",
                                0.31
                            ],
                            [
                                "v47.0",
                                0.12
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
                            ],
                            [
                                "v9.0",
                                0.27
                            ],
                            [
                                "v8.0",
                                0.47
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
                            ],
                            [
                                "v10.1",
                                0.96
                            ],
                            [
                                "v10.0",
                                0.36
                            ],
                            [
                                "v9.1",
                                0.54
                            ],
                            [
                                "v9.0",
                                0.13
                            ],
                            [
                                "v5.1",
                                0.2
                            ]
                        ]
                    },
                    {
                        name: "Edge",
                        id: "Edge",
                        data: [
                            [
                                "v16",
                                2.6
                            ],
                            [
                                "v15",
                                0.92
                            ],
                            [
                                "v14",
                                0.4
                            ],
                            [
                                "v13",
                                0.1
                            ]
                        ]
                    },
                    {
                        name: "Opera",
                        id: "Opera",
                        data: [
                            [
                                "v50.0",
                                0.96
                            ],
                            [
                                "v49.0",
                                0.82
                            ],
                            [
                                "v12.1",
                                0.14
                            ]
                        ]
                    }
                ]
            }
        });
    }
</script>
<script type="text/javascript">
    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Density", { role: "style" } ],
        ["Completed", 8, "#4CAF50"],
        ["Pending", 10, "#FF9800"],
        ["Rejected", 19, "#F44336"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Alumini Requests",
        width: 400,
        height: 250,
        bar: {groupWidth: "70%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
      chart.draw(view, options);
  }


  Highcharts.chart('container1', {

    title: {
        text: 'Funds Raised by Swaeros'
    },

    subtitle: {
        text: 'Source: Used Year Wise'
    },

    yAxis: {
        title: {
            text: 'counts'
        }
    },

    xAxis: {
        accessibility: {
            rangeDescription: 'Range: 2010 to 2017'
        }
    },

    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
            pointStart: 2010
        }
    },

    series: [{
        name: 'Vidya Nidhi',
        data: [439, 525, 577, 696, 971, 1191, 1373, 1545]
    }, {
        name: 'Sports',
        data: [24, 244, 297, 291, 320, 382, 321, 404]
    }, {
        name: 'Events & Activities',
        data: [114, 177, 165, 191, 201, 243, 321, 393]
    }, {
        name: 'Opportunities',
        data: [null, null, 798, 121, 151, 222, 340, 347]
    }, {
        name: 'Making Employees',
        data: [108, 58, 85, 118, 89, 116, 174, 181]
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

});
</script>
<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([
          ['Month', 'IPS', 'Doctor', 'Police','Lawyer','Engineers', 'IAS'],
          ['2004/05',  165,      938,         522,             998,           450,      614.6],
          ['2005/06',  135,      1120,        599,             1268,          288,      682],
          ['2006/07',  157,      1167,        587,             807,           397,      623],
          ['2007/08',  139,      1110,        615,             968,           215,      609.4],
          ['2008/09',  136,      691,         629,             1026,          366,      569.6]
        ]);

        var options = {
          title : 'Professions',
          vAxis: {title: ''},
          hAxis: {title: 'Month'},
          seriesType: 'bars',
          series: {5: {type: 'line'}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
</script>
<script type="text/javascript">
  // Initialize and add the map
      function initMap() {
        // The location of Uluru
        const uluru = { lat: 18.1124, lng: 79.0193 };
        // The map, centered at Uluru
        const map = new google.maps.Map(document.getElementById("map"), {
          zoom: 4,
          center: uluru,
        });
        // The marker, positioned at Uluru
        const marker = new google.maps.Marker({
          position: uluru,
          map: map,
        });
      }
</script>


