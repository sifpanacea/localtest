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
              <!-- <button type="button" id="" class="btn bg-blue waves-effect">
                 Vidya Nidhi
              </button> -->
              <a href='<?php echo URL."panacea_mgmt/swareo_social_network"; ?>'>
                <button type="button" id="" class="btn bg-blue waves-effect">
                  Social Media
                </button>
              </a>
              <!-- <button type="button" id="" class="btn bg-blue waves-effect">
                  Beneficiaries
              </button> -->
              <!-- <a href='<?php //echo URL."panacea_mgmt/swareo_gps_tracking"; ?>'>
                <button type="button" id="" class="btn bg-blue waves-effect">
                    GPS Tracking
                </button>
              </a> -->
              <!-- <button type="button" id="" class="btn bg-blue waves-effect">
                  Support
              </button> -->
              <!-- <button type="button" id="" class="btn bg-blue waves-effect">
                  Events&Activity
              </button>
              <button type="button" id="" class="btn bg-blue waves-effect">
                  Information
              </button> -->
              <a href='<?php echo URL."panacea_mgmt/swaero_todo_list"; ?>'>
                  <button type="button" id="" class="btn bg-blue waves-effect">ToDos</button>
              </a>
              <a href='<?php echo URL."panacea_mgmt/tswreis_swaero_electronic_record"; ?>'>
                  <button type="button" id="" class="btn bg-blue waves-effect">Swaero ID</button>
              </a>
            </div>
        </div>
      <!-- </div> -->
<!-- End FIlters For Admin Dashboard -->
      
     <div class="row clearfix">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-pink hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">group</i>
                </div>
                <div class="content">
                    <div class="text">Total Swaeros</div>
                    <div class="number"><?php echo $total_student_swaroes; ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-cyan hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">school</i>
                </div>
                <div class="content">
                    <div class="text">Like Minded</div>
                    <div class="number"><?php echo $total_like_minded_swaroes; ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-light-green hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">card_membership</i>
                </div>
                <div class="content">
                    <div class="text">Total Verified Swaero's</div>
                    <div class="number"><?php echo $verified_swaroes; ?></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-orange hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">people_outline</i>
                </div>
                <div class="content">
                    <a href="<?php echo URL.'panacea_mgmt/verify_pending_requests'; ?>">
                    <div class="text">Pending Verification</div>
                    <div class="number"><?php echo $not_verified_swaroes; ?></div>
                    </a>
                </div>
            </div>
        </div>
    </div>  


    <!-- Start First Row Information -->
    <div class="row clearfix">
        <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
            <div class="card">
                <!-- <div class="header">
                   <h2>Swaero Registrations</h2>
                </div> -->

                <div class="body">
                   <div id="myPie"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
            <div class="card">
                <!-- <div class="header">
                  <h2>Funds Raised</h2>
                </div> -->
                <div class="body">
                    <figure class="highcharts-figure">
                        <div id="funds_chart"></div>
                    </figure>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12">
            <div class="card">
                <!-- <div class="header">
                  <h2>Like Minded Registrations</h2>
                </div> -->
                <div class="body">
                  <div id="container_like_minded"></div>
                </div>
            </div>
        </div>
        
    </div>
    <!-- End First row Information -->

    <!-- Start Second Row Information -->
    <div class="row clearfix">
        <div class="col-lg-6 col-md-4 col-sm-8 col-xs-12">
          <div class="card">
            <div class="body">
               <div id="alumni_requests"></div>
            </div>
          </div>
         
        </div>
        <div class="col-lg-6 col-md-4 col-sm-8 col-xs-12">
          <div class="card">
            <div class="body">
              <div id="emergency_calls_pie"></div>
            </div>
          </div>
        </div>
    </div>
    <!-- End Second Row Information -->





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


<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="loading_modal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" id="loading">
            <center><img src="<?php echo(IMG.'loader.gif'); ?>" id="gif" ></center>
        </div>
    </div>
</div>

<form style="display: hidden" action="get_day_to_day_glance_data_fetching" method="POST" id="day_to_day_glance_form">
      <input type="hidden" id="to_date" name="today_date" value=""/>
      <input type="hidden" id="day_to_day_status" name="day_to_day_status" value=""/>
</form>

<form style="display: hidden" action="get_registered_swaeros_list" method="POST" id="registration_details">
      <input type="hidden" id="" name="" value=""/>
      <input type="hidden" id="" name="" value=""/>
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

<?php include("inc/footer_bar.php"); ?>

<script type="text/javascript">
  // Create the chart
        Highcharts.chart('container_like_minded', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Vidya Nidhi Funding'
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
                    }
                }
            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
            },

            series: [
                {
                    name: "Browsers",
                    colorByPoint: true,
                    data: [
                        {
                            name: "Swaero alumni",
                            y: 69,
                            drilldown: "Swaero alumni"
                        },
                        {
                            name: "Parents",
                            y: 30,
                            drilldown: "Parents"
                        },
                        {
                            name: "Teachers",
                            y: 25,
                            drilldown: "Teachers"
                        },
                        {
                            name: "Swaero professionals",
                            y: 45,
                            drilldown: "Swaero professionals"
                        },
                        {
                            name: "Employees",
                            y: 44,
                            drilldown: "Employees"
                        },
                        {
                            name: "Swaero business man",
                            y: 14,
                            drilldown: "Swaero business man"
                        },
                        {
                            name: "NGO",
                            y: 77,
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
                                1
                            ],
                            [
                                "v57.0",
                                7
                            ],
                            [
                                "v56.0",
                                5
                            ],
                            [
                                "v55.0",
                                4
                            ],
                            [
                                "v54.0",
                                3
                            ],
                            [
                                "v52.0",
                                2
                            ],
                            [
                                "v51.0",
                                8
                            ],
                            [
                                "v50.0",
                                5
                            ],
                            [
                                "v48.0",
                                4
                            ],
                            [
                                "v47.0",
                                6
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
</script>


<script type="text/javascript">
   Highcharts.chart('funds_chart', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'Funds Collection'
    },
    subtitle: {
        text: 'Example.com'
    },
    xAxis: {
        categories: [
          
            'Raised',
            'Used',
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Counts'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [ {
        name: 'Funds',
        color: "pink",
        data: [42, 33]

    }]
});
</script>

<script>
  
draw_registrations_pie();

function draw_registrations_pie(){
 
    $.ajax({
      url : 'get_total_registration',
      type : 'POST',
      data:'',
      success:function(data){
        data = $.parseJSON(data);
        registration_pie("registrations",data,"drill_down_absent_to_districts");
      }

    });
 
}

function registration_pie(heading, data, onClickFn, second_para = false){

  Highcharts.chart('myPie', {
      chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
          type: 'pie'
      },
      title: {
          text: heading
      },
      subtitle: {
          text: 'Click on slides to see district level'
      },
      tooltip: {
          pointFormat: '{series.name}'
      },
      accessibility: {
          point: {
              valueSuffix: ''
          }
      },
      plotOptions: {
          pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              dataLabels: {
                  enabled: true,
                  format: '{point.name}: {point.y}'
              },
              point: {
                        events: {
                        click: function(e){
                          console.log("Segment clicked! See the console for all data passed to the click handler.");
                          console.log(onClickFn);
                         
                          
                          if(onClickFn == "drill_down_absent_to_districts"){
                            console.log(e);
                             var pointName = e.point.name;
                              alert(pointName);
                            //previous_absent_a_value[1] = data;
                            drill_down_absent_to_districts(pointName);
                          }else if(onClickFn == "drill_down_absent_to_schools"){
                            console.log(e);
                            var clickedData = e.point.name;
                            alert(clickedData);
                            drill_down_absent_to_schools(clickedData, heading);
                          }else if(onClickFn == "drill_down_absent_to_students"){
                            var mandalData = e.point.name;
                            alert(mandalData);
                            //search_arr[0] = previous_absent_search[2];
                            //search_arr[1] =  e.data.label;
                            //console.log(search_arr);
                            //console.log("calling student funcccccccccccccccccccccccccc");
                            drill_down_absent_to_students(mandalData);
                          }else{
                            index = onClickFn;
                            //alert("Segment clicked! See the console for all data passed to the click handler.");
                            console.log(e);
                            //previous_screening_a_value[index] = previous_screening_a_value[index];
                            //previous_screening_title_value[index] = previous_screening_title_value[index];
                            //previous_screening_search[index] = previous_screening_title_value[index];
                            console.log("value from previous function -------------------------------------------");
                            //console.log(previous_screening_a_value);
                          
                            if (index == 1){
                              drill_down_absent_to_districts(e);
                            }else if (index == 2){
                              search_arr[0] = previous_absent_search[2];
                              search_arr[1] =  e.data.label;
                              console.log(search_arr);
                              drill_down_absent_to_schools(search_arr);
                            }
                            
                          }
                        }
                      }
                    }
          }
      },
      series: [{
          name: 'Counts',
          colorByPoint: true,
          data: data
      }]
  });


}

function drill_down_absent_to_districts(pointName){
  console.log(pointName);
  
  $.ajax({
    url: 'drilldown_selected_to_districts',
    type: 'POST',
    data: {"data" : pointName},
    success: function (data) {
      console.log(data);
      var content = $.parseJSON(data);
      console.log(content);
      $( "#myPie" ).empty();
      //$("#pie_absent").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_absent_back(1);"> Back </button>');
     /* $("#myPie").append('<button class="btn btn-primary pull-right" id="absent_back_btn" ind= "1"> Back </button>');*/

      registration_pie(pointName,content,"drill_down_absent_to_schools");
      
      },
        error:function(XMLHttpRequest, textStatus, errorThrown)
      {
       console.log('error', errorThrown);
        }
    });
  }

function drill_down_absent_to_schools(pie_data, heading){
  
  console.log(pie_data);
  $.ajax({
    url: 'drilling_districts_to_mandals',
    type: 'POST',
    data: {"data" : pie_data,"heading" : heading},
    success: function (data) {
      var content = $.parseJSON(data);
      $( "#pie_absent" ).empty();
      //$("#pie_absent").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_absent_back(2);"> Back </button>');
      /*$("#pie_absent").append('<button class="btn btn-primary pull-right" id="absent_back_btn" ind= "2"> Back </button>');*/

      registration_pie(pie_data,content,"drill_down_absent_to_students", heading);
      
      },
        error:function(XMLHttpRequest, textStatus, errorThrown)
      {
       console.log('error', errorThrown);
        }
    });
}

function drill_down_absent_to_students(pie_data){

  $.ajax({
    url: 'drill_down_absent_to_students',
    type: 'POST',
    data: {"data" : JSON.stringify(pie_data), "today_date" : today_date, "dt_name" : dt_name, "school_name" : school_name},
    success: function (data) {
      console.log(data);
      $("#ehr_data_for_absent").val(data);
      //window.location = "drill_down_screening_to_students_load_ehr/"+data;
      //alert(data);
      
      $("#ehr_form_for_absent").submit();
      
      },
        error:function(XMLHttpRequest, textStatus, errorThrown)
      {
       console.log('error', errorThrown);
        }
    });
}


$(document).on("click",'#absent_back_btn',function(e){
    var index = $(this).attr("ind");
    console.log("in back functionnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn-----------");
    console.log(index);
    $( "#myPie" ).empty();
    if(index>1){
      var ind = index - 1;
    $("#myPie").append('<button class="btn btn-primary pull-right" id="absent_back_btn" ind= "' + ind + '"> Back </button>');
    }
    registration_pie(previous_absent_title_value[index], previous_absent_a_value[index], index);
}); 
</script>
<script type="text/javascript">
  Highcharts.chart('alumni_requests', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Alumini Requests'
    },
    subtitle: {
        text: 'Example.com'
    },
    xAxis: {
        categories: [
          
            'Completed',
            'Pending',
            'Rejected'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Counts'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [ {
        name: 'Requests',
        data: [42, 33, 34]

    }]
});



 Highcharts.chart('emergency_calls_pie', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Emergency Calls'
    },
    xAxis: {
        categories: ['Emergency calls']
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total Calls'
        },
        stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: ( // theme
                    Highcharts.defaultOptions.title.style &&
                    Highcharts.defaultOptions.title.style.color
                ) || 'gray'
            }
        }
    },
    legend: {
        align: 'right',
        x: -30,
        verticalAlign: 'top',
        y: 25,
        floating: true,
        backgroundColor:
            Highcharts.defaultOptions.legend.backgroundColor || 'white',
        borderColor: '#CCC',
        borderWidth: 1,
        shadow: false
    },
    tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
    },
    plotOptions: {
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: true
            }
        }
    },
    series: [{
        name: 'Received',
        data: [5]
    }, {
        name: 'Missed',
        data: [2]
    }, {
        name: 'Benefited',
        data: [3]
    }]
}); 
</script>
