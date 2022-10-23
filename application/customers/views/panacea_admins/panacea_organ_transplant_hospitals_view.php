<?php $current_page = "RHSO"; ?>
<?php $main_nav = ""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

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

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            
        </div>
        <!-- Basic Table -->
        <div class="row clearfix">
           
            <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
              <div class="card">
                <div class="header">
                    <h2>Organ Transplantation Hospitals</h2>
                    <br>
                    <br>                      
                    </div>
                    <div class="body">
                       <figure class="highcharts-figure">
                    <div id="container3"></div>
                   
                </figure>
                    </div>
              </div>
                
            </div>     
        </div>
    </div>
</section>


 <form style="display: hidden" action="get_organ_name_clicking_data" method="POST" id="organ_name_data">

    <input type="hidden" id="organ" name="organ"/>   
         
</form>
   

<?php include('inc/footer_bar.php'); ?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
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


<script type="text/javascript">

  var today_date = $('#set_date').val();
  $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
  $('#set_date').change(function(e){
          today_date = $('#set_date').val();
  });
	
    show_organ_transplant_hospitals();
    
   /* Organ Transplant Hospitals PIE */

    function show_organ_transplant_hospitals(){
        $.ajax({
                url: 'get_organ_transplant_hospitals_data',
                type: 'POST',
                data: "",
                success: function (data) {          

                    result = $.parseJSON(data);

                    console.log(result);
                    get_fetching_data(result);
                            
                    },
                    error:function(XMLHttpRequest, textStatus, errorThrown)
                    {
                     console.log('error', errorThrown);
                    }
            });
    }
    

    function get_fetching_data(result)
    {
        // Create the chart
        Highcharts.chart('container3', {
            chart: {
                type: 'pie'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: 'Click on slides to see hospitals level'
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
                           $("#organ").val(pointName);
                          $('#organ_name_data').submit();
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
            }
        });
    }
    

</script>

