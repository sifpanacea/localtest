<?php $current_page = "RHSO"; ?>
<?php $main_nav = ""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            
        </div>
        <!-- Basic Table -->
        <div class="row clearfix">
            <div class="col-lg-6 col-md-3 col-sm-12 col-xs-12">
                 <div class="card">
            <div class="body">
                <figure class="highcharts-figure">
                    <!-- <div id="myPie"></div> -->
                    <div id="container"></div>
                    <!-- <div id="achievements_pie"></div> -->
                    <p class="highcharts-description">
                        
                    </p>
                </figure>
                <center><h3>Criteria</h3></center>
                <h5>Active - Institutions having all RO plants/Incinerators in working status</h5>
                <h5>Inactive - Institution which have RO plants/Incinerators but are not in working stage</h5>
                <h5>Partially Active - Institutions which have both working and not working RO plants/Incinerators</h5>
                <h5>Not available- Institutions which does not have RO plants/Incinerators</h5>
            </div>
        </div>
            </div> 
           <div class="col-lg-6 col-md-3 col-sm-12 col-xs-12">
                  <div class="card">
                      <div class="body">
                          <figure class="highcharts-figure">
                              <div id="container3"></div>
                              <p class="highcharts-description">
                                 
                              </p>
                          </figure>
                      </div>
                  </div>
              </div>   
        </div>
    </div>
</section>

 

<form style="display: hidden" action="get_ro_plants_schools_drill" method="POST" id="ro_plant_drill">
    <input type="hidden" id="ro_plant_status" name="ro_plant_status"/>
   
</form>


<form style="display: hidden" action="get_incinarators_schools_drill" method="POST" id="incinarator_drill">
    <input type="hidden" id="incinarator_status" name="incinarator_status"/>
   
</form>






<?php include('inc/footer_bar.php'); ?>

<!-- Google Charts -->
<script src="<?php echo(MDB_JS.'loader.js'); ?>"></script>

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



<script type="text/javascript">

  var today_date = $('#set_date').val();
  $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
  $('#set_date').change(function(e){
          today_date = $('#set_date').val();
  });
	
 
    get_ro_plant_incinarator_data();

      get_incinarator_pie_data();


    function get_ro_plant_incinarator_data()
    {
        $.ajax({
            url: 'get_ro_plants_incinarators_data',
            type: 'POST',
            data: "",
            success: function (data) {          

                result = $.parseJSON(data);

                console.log(result);
                show_ro_plant_incinarators_data(result);
                        
                },
                error:function(XMLHttpRequest, textStatus, errorThrown)
                {
                 console.log('error', errorThrown);
                }
        });
    }



       function show_ro_plant_incinarators_data(result)
    {
       
       var total = result.totalwork;
       var not_work = result.notwork;
       var partially = result.partialwork;
       var not_available = result.notavailable;
        // Create the chart
        Highcharts.chart('container', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'RO Plants Status'
            },
            subtitle: {
                text: ''
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
                          var seriesName = e.point.series.name;
                        //  alert(seriesName);
                          var pointName = e.point.name;
                         // alert(pointName);  
                          
                          $('#ro_plant_status').val(pointName);
                          $('#ro_plant_drill').submit();
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
                    name: "",
                    colorByPoint: true,
                    data: [
                        {
                            name: "Active RO Plants",
                            y: total,
                            drilldown: "International"
                        },
                        {
                            name: "Inactive RO Plants",
                            y: not_work,
                            drilldown: "National"
                        },
                        {
                            name: "Partially Active RO Plants",
                            y: partially,
                            drilldown: "State"
                        },
                        {
                            name: "Not Available RO Plants",
                            y: not_available,
                            drilldown: "District"
                        }
                       
                    ]
                    
                }
            ]

            /*drilldown: {
                series: [
                    {
                        name: "International Achievements",
                        id: "International",
                        data: [
                            [
                                "Gold",
                                10
                            ],
                            [
                                "Silver",
                                30
                            ],
                            [
                                "Bronze",
                                25
                            ],
                            [
                                "Participations",
                                20
                            ]
                        ]
                    },
                    {
                        name: "National Achievements",
                        id: "National",
                        data: [
                            [
                                "Gold",
                                10
                            ],
                            [
                                "Silver",
                                30
                            ],
                            [
                                "Bronze",
                                20
                            ],
                            [
                                "Participations",
                                50
                            ]
                        ]
                    },
                    {
                        name: "State Achievements",
                        id: "State",
                        data: [
                            [
                                "Gold",
                                25
                            ],
                            [
                                "Silver",
                                3
                            ],
                            [
                                "Bronze",
                                21
                            ],
                            [
                                "Participations",
                                24
                            ]
                        ]
                    }
           
                ]
            }*/

        });
    }




/*   Incinarators Pie start  */

 function get_incinarator_pie_data(){
        $.ajax({
                url: 'get_incinarator_data',
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
       
       var total = result.totalwork;
       var not_work = result.notwork;
       var partially = result.partialwork;
       var not_available = result.notavailable;
        // Create the chart
        Highcharts.chart('container3', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Incinerators Status'
            },
            subtitle: {
                text: ''
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
                          var seriesName = e.point.series.name;
                         // alert(seriesName);
                          var pointName = e.point.name;
                         // alert(pointName);  
                          
                          $('#incinarator_status').val(pointName);
                          $('#incinarator_drill').submit();
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
                    name: "Incinerators",
                    colorByPoint: true,
                    data: [
                        {
                            name: "Active Incinerators",
                            y: total,
                            drilldown: "International"
                        },
                        {
                            name: "Inactive Incinerators",
                            y: not_work,
                            drilldown: "National"
                        },
                        {
                            name: "Partially Active Incinerators",
                            y: partially,
                            drilldown: "State"
                        },
                        {
                            name: "Not Available Incinerators",
                            y: not_available,
                            drilldown: "District"
                        }
                       
                    ]
                    
                }
            ]

         

        });
    }
   
    
    

</script>

