<?php $current_page=""; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>

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

  <link href='<?php echo MDB_PLUGINS."bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"; ?>' rel="stylesheet" />
<!-- Code for data tables -->
<section class="">
<div class="container-fluid">
<div class="block-header">
   <!--  <h2>Surgery Needed Health Requests</h2> -->
</div>
<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
             
            <div class="row clearfix">
                <div class="col-sm-4">
                   <h3 class="font-bold col-green">Staff Covid Cases Information</h3>
                </div>
                
                <div class="col-sm-3 pull-right">
                   <ul class="header-dropdown m-r--5">                     
                      <li><button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button></li>
                  </ul>
                </div>                
            </div> 
            </div>
           
       
        <div class="card">
            <div class="body">
                <figure class="highcharts-figure">
                    <div id="staff_covid_cases_pie"></div>                    
                </figure>
            </div>
        </div>
    
            </div>
        </div>
    </div>
</div>
    </section>

<form style="display: hidden" action="get_staff_covid_cases_district_drill" method="POST" id="staff_covid_cases">
  <input type="hidden" id="district_name" name="district_name"/>   
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

<script src="<?php echo(MDB_PLUGINS.'jquery/jquery.min.js'); ?>"></script>

        <!-- Bootstrap Core Js -->
        <script src="<?php echo(MDB_PLUGINS.'bootstrap/js/bootstrap.js'); ?>"></script>

        <!-- Slimscroll Plugin Js -->
        <script src="<?php echo(MDB_PLUGINS.'jquery-slimscroll/jquery.slimscroll.js'); ?>"></script>

        <!-- Bootstrap Notify Plugin Js -->
        <script src="<?php echo(MDB_PLUGINS.'bootstrap-notify/bootstrap-notify.js'); ?>"></script>

        <!-- Waves Effect Plugin Js -->
        <script src="<?php echo(MDB_PLUGINS.'node-waves/waves.js'); ?>"></script>
     
      <!-- Jquery DataTable Plugin Js -->
        <script src='<?php echo MDB_PLUGINS."jquery-datatable/jquery.dataTables.js"; ?>'></script>
        <script src="<?php echo MDB_PLUGINS."jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"; ?>"></script>
        <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/dataTables.buttons.min.js"; ?>'></script>
        <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.flash.min.js"; ?>'></script>
        <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/jszip.min.js"; ?>'></script>
        <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/pdfmake.min.js"; ?>'></script>
        <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/vfs_fonts.js"; ?>'></script>
        <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.html5.min.js"; ?>'></script>
        <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.print.min.js"; ?>'></script>

        <script src="<?php echo(MDB_JS.'admin.js'); ?>"></script>
        <script src='<?php echo MDB_JS."pages/tables/jquery-datatable.js"; ?>'></script>
        <script src='<?php echo MDB_JS."pages/ui/modals.js"; ?>' ></script>

        <!-- Demo Js -->
        <script src="<?php echo(MDB_JS.'demo.js'); ?>"></script>
        <!-- Moment Plugin Js -->
        <script src="<?php echo(MDB_PLUGINS.'momentjs/moment.js'); ?>"></script>
        <script src="<?php echo(MDB_PLUGINS.'bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
        <script src="<?php echo MDB_PLUGINS.'bootstrap-notify/bootstrap-notify.js'; ?>"></script>
       



<script type="text/javascript">

   show_registered_data_two();

   var today_date = $('#passing_end_date').val();
    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#passing_end_date').change(function(e){
            today_date = $('#passing_end_date').val();;
    });

    $('#date_set').click(function(){
            var district_name = $('#select_dt_name').val();
            var hs_job_type = $('#select_job_type').val();
    
   /* alert(startDate);
    alert(endDate);*/
            
                $.ajax({
                    url : 'get_hs_jobtype_based_on_span',
                    type : 'POST',
                    data : {"dt_name" : district_name, "hs_job": hs_job_type},
                    success : function(data){
                      $("#loading_modal").modal('hide');
                        $('#students_more_req').empty();
                        var result = $.parseJSON(data);
                        //console.log(result);
                        data_table_for_filters(result);
                        $('#show_when_serach').show();
                         $('#table_hide').hide();
                    }
                });

        });

  

  /*======= Start staff covid cases PIE============= */

    function show_registered_data_two(){
        $.ajax({
                url: 'get_staff_covid_cases_data',
                type: 'POST',
                data: "",
                success: function (data) {          

                    result = $.parseJSON(data);

                    console.log(result);

                    get_staff_covid_cases(result);
                            
                    },
                    error:function(XMLHttpRequest, textStatus, errorThrown)
                    {
                     console.log('error', errorThrown);
                    }
            });
    }
    

    function get_staff_covid_cases(result)
    {
        // Create the chart
        Highcharts.chart('staff_covid_cases_pie', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Covid Cases PIE'
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
                           $("#district_name").val(pointName);
                          $('#staff_covid_cases').submit();
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
            ]
        });
    }

 /*=========End L3 Help Line Services ===============*/   



    function data_table_for_filters(result){

      if(result == "No Data Available"){
          $('#sanitation_filters').html('<h4>No Data Available For this school....</h4>');
      }
        else{

            data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic"><thead><tr><th>School Name</th><th>District</th><th>RCO Details</th><th>RHSO Details</th><th>Principal Details</th><th>Health Supervisor</th><th>Job Type</th><th>Care Taker</th><th>DEO Details</th><th>School Strength</th></tr></thead><tbody>';

              $.each(result, function(){

              data_table = data_table+'<tr>';

              data_table = data_table+'<td>'+this.doc_data['school_name']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['district']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['rco_details']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['rhso_details']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['principal_details']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['hs_details']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['job_type']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['care_taker_details']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['deo_details']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['strength']+'</td>';
            
             

              });

              data_table = data_table+'</tbody></table>';

            $('#sanitation_filters').html(data_table);

              $('#dt_basic').DataTable({
                "paging": true,
                "lengthMenu" : [10, 25, 50, 100,500]
              });

            $("#select_all_chk").click(function(){

                var oTable = $('#dt_basic').dataTable();
                var allPages = oTable.fnGetNodes();
                $('input[type="checkbox"]', allPages).prop('checked', $(this).is(':checked'));
            });
        }
    };

    $("#submit_request").click(function(){

    //$(this).prop('disabled', true);
    var id_array = [];
    var oTable = $('#dt_basic').dataTable();
    var rowcollection =  oTable.$("input[name='checkboxName[]']:checked", {"page": "all"});
    rowcollection.each(function(index,elem){
        var checkbox_value = $(elem).attr("id");
        id_array.push(checkbox_value);
    });

    $("#ehr_data_for_request").val(JSON.stringify(id_array));
    $("#request_form").submit();
});


</script>
