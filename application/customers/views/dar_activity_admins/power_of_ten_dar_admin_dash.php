<?php $current_page = "homepage"; ?>
<?php $main_nav = ""; ?>
<?php include("inc/header_bar.php"); ?>
<?php include("inc/sidebar.php"); ?>

<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">
<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src = "https://code.highcharts.com/highcharts.js"></script>

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<style type="text/css">
    .status_blink{
       
       color: rgb(0, 137, 226);
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

<section class="content">
<div class="container-fluid">
     <!-- <div class="row clearfix">
        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <div class="info-box bg-pink hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">assignment_ind</i>
                </div>
                <div class="content">
                    <div class="text">Total Registrations</div>
                    <div class="number">50</div>
                </div>
            </div>
         </div>   
         <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12"> 
            <div class="info-box bg-orange hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">assignment_turned_in</i>
                </div>
                <div class="content">
                    <div class="text">Total Verified Swaero's</div>
                    <div class="number">42</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <div class="info-box bg-cyan hover-zoom-effect">
                <div class="icon">
                    <i class="material-icons">assignment_late</i>
                </div>
                <div class="content">
                    <div class="text">Pending Registrations</div>
                    <div class="number">8</div>
                </div>
            </div>
        </div>
    </div> -->  


    <!-- Start First Row Information -->
    <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
           <div class="card">
                <div class="body">
                   <div id="conformed_registrations"></div>
                </div>
            </div> 
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                  <div id="district_registrations"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- End First row Information -->


</div> <!-- Container Div End -->
</section>
<!-- Info criteria for hb and bmi -->

<div class="modal fade" id="HB_and_BMI_Criteria" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Criteria for HB and BMI status</h4>
            </div>
            <div class="modal-body">
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

<form style="display: hidden" action="get_registritations_confirmed_swaeros" method="POST" id="registrations_done">
      <input type="hidden" id="district_name_completed" name="district_name_completed"/>
</form>

<form style="display: hidden" action="get_registrations_pending_swaeros" method="POST" id="registration_at_district">
      <input type="hidden" id="district_name_pending" name="district_name_pending"/>
</form>








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

draw_received_district_coordinators_pie();

function draw_received_district_coordinators_pie(){
 
    $.ajax({
      url : 'get_total_received_district_coordinators',
      type : 'POST',
      data:'',
      success:function(data){
        data = $.parseJSON(data);
        received_district_coordinators_pie(data);
      }

    });
 
}

function received_district_coordinators_pie(data)
{

  // Create the chart
        Highcharts.chart('conformed_registrations', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Received From District Co-ordinators'
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
                          $('#district_name_completed').val(pointName);
                          $('#registrations_done').submit();
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
                    name: "Browsers",
                    colorByPoint: true,
                    data: data
                        
                    
                }
            ],
            drilldown: {
                series: [
                    
                ]
            }
        });
}

draw_pending_district_coordinators_pie();

function draw_pending_district_coordinators_pie(){
 
    $.ajax({
      url : 'get_total_pending_district_coordinators',
      type : 'POST',
      data:'',
      success:function(data){
        data = $.parseJSON(data);
        pending_district_coordinators_pie(data);
      }

    });
 
}

function pending_district_coordinators_pie(data)
{

    // Create the chart
    Highcharts.chart('district_registrations', {
    chart: {
        type: 'pie'
    },
    title: {
        text: 'pending at District Co-ordinators'
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
                  $('#district_name_pending').val(pointName);
                  $('#registration_at_district').submit();
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
            name: "Browsers",
            colorByPoint: true,
            data: data
        }
    ],
    drilldown: {
        series: [
            
        ]
    }
});
}
</script>

