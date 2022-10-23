<?php $current_page=""; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">

<style>
.sk-circle {
  margin: 100px auto;
  width: 60px;
  height: 60px;
  position: relative;
}
.sk-circle .sk-child {
  width: 100%;
  height: 100%;
  position: absolute;
  left: 0;
  top: 0;
}
.sk-circle .sk-child:before {
  content: '';
  display: block;
  margin: 0 auto;
  width: 15%;
  height: 15%;
  background-color: #333;
  border-radius: 100%;
  -webkit-animation: sk-circleBounceDelay 1.2s infinite ease-in-out both;
          animation: sk-circleBounceDelay 1.2s infinite ease-in-out both;
}
.sk-circle .sk-circle2 {
  -webkit-transform: rotate(30deg);
      -ms-transform: rotate(30deg);
          transform: rotate(30deg); }
.sk-circle .sk-circle3 {
  -webkit-transform: rotate(60deg);
      -ms-transform: rotate(60deg);
          transform: rotate(60deg); }
.sk-circle .sk-circle4 {
  -webkit-transform: rotate(90deg);
      -ms-transform: rotate(90deg);
          transform: rotate(90deg); }
.sk-circle .sk-circle5 {
  -webkit-transform: rotate(120deg);
      -ms-transform: rotate(120deg);
          transform: rotate(120deg); }
.sk-circle .sk-circle6 {
  -webkit-transform: rotate(150deg);
      -ms-transform: rotate(150deg);
          transform: rotate(150deg); }
.sk-circle .sk-circle7 {
  -webkit-transform: rotate(180deg);
      -ms-transform: rotate(180deg);
          transform: rotate(180deg); }
.sk-circle .sk-circle8 {
  -webkit-transform: rotate(210deg);
      -ms-transform: rotate(210deg);
          transform: rotate(210deg); }
.sk-circle .sk-circle9 {
  -webkit-transform: rotate(240deg);
      -ms-transform: rotate(240deg);
          transform: rotate(240deg); }
.sk-circle .sk-circle10 {
  -webkit-transform: rotate(270deg);
      -ms-transform: rotate(270deg);
          transform: rotate(270deg); }
.sk-circle .sk-circle11 {
  -webkit-transform: rotate(300deg);
      -ms-transform: rotate(300deg);
          transform: rotate(300deg); }
.sk-circle .sk-circle12 {
  -webkit-transform: rotate(330deg);
      -ms-transform: rotate(330deg);
          transform: rotate(330deg); }
.sk-circle .sk-circle2:before {
  -webkit-animation-delay: -0.84s;
          animation-delay: -0.84s; }
.sk-circle .sk-circle3:before {
  -webkit-animation-delay: -0.84ss;
          animation-delay: -0.84ss; }
.sk-circle .sk-circle4:before {
  -webkit-animation-delay: -0.84ss;
          animation-delay: -0.9s; }
.sk-circle .sk-circle5:before {
  -webkit-animation-delay: -0.8s;
          animation-delay: -0.8s; }
.sk-circle .sk-circle6:before {
  -webkit-animation-delay: -0.7s;
          animation-delay: -0.7s; }
.sk-circle .sk-circle7:before {
  -webkit-animation-delay: -0.6s;
          animation-delay: -0.6s; }
.sk-circle .sk-circle8:before {
  -webkit-animation-delay: -0.5s;
          animation-delay: -0.5s; }
.sk-circle .sk-circle9:before {
  -webkit-animation-delay: -0.4s;
          animation-delay: -0.4s; }
.sk-circle .sk-circle10:before {
  -webkit-animation-delay: -0.3s;
          animation-delay: -0.3s; }
.sk-circle .sk-circle11:before {
  -webkit-animation-delay: -0.2s;
          animation-delay: -0.2s; }
.sk-circle .sk-circle12:before {
  -webkit-animation-delay: -0.1s;
          animation-delay: -0.1s; }

@-webkit-keyframes sk-circleBounceDelay {
  0%, 80%, 100% {
    -webkit-transform: scale(0);
            transform: scale(0);
  } 40% {
    -webkit-transform: scale(1);
            transform: scale(1);
  }
}

@keyframes sk-circleBounceDelay {
  0%, 80%, 100% {
    -webkit-transform: scale(0);
            transform: scale(0);
  } 40% {
    -webkit-transform: scale(1);
            transform: scale(1);
  }
}
</style>
<br>
<br>
<br>
<br>
<br>

<!-- Code for data tables -->
<section class="">
<div class="container-fluid">

<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
             
            <div class="row clearfix">
                <div class="col-sm-3">
                   <h3 class="font-bold col-green">Covid-19 Cases</h3>
                </div>
                <div class="collapse" id="multicollapseExample">
                  <div class="col-sm-2">
                    <div class="form-line">
                    <?php $end_date  = date ( "Y-m-d", strtotime ( date('yy-m-d') . "-30 days" ) ); ?>
                       <span id="monitoring_datepicker">
                        Start Date :
                       <input type="text" id="passing_date" name="passing_date" class="form-control date" value="<?php echo $end_date; ?>">
                       </span>
                     </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-line">
                       <span id="monitoring_datepicker">
                       End Date :
                       <input type="text" id="passing_end_date" name="passing_end_date" class="form-control date" value="<?php echo date('yy-m-d'); ?>">
                       </span>
                     </div>
                  </div>
                  <div class="col-sm-1">
                      <div class="form-line">
                            <button type="button" id="date_set" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                            <i class="material-icons">search</i>
                            </button>
                      </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-line">
                       <button type="button" id="get_excel" class="btn bg-green btn-sm waves-effect">Get Excel</button>
                     </div>
                  </div>
                </div>
                
                <div class="col-sm-2 pull-right">
                    <ul class="header-dropdown m-r--5">
                       <li><button class="btn btn-default" type="button" data-toggle="collapse" data-target="#multicollapseExample" aria-controls="multicollapseExample"  data-placement="bottom" title="Date Filters"><img src="<?php echo IMG ;?>/funnel.png"></button></li>
                       <li><button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button></li>
                   </ul>
                 </div>
                 <center>
                  <div class="sk-circle">
                    <div class="sk-circle1 sk-child"></div>
                    <div class="sk-circle2 sk-child"></div>
                    <div class="sk-circle3 sk-child"></div>
                    <div class="sk-circle4 sk-child"></div>
                    <div class="sk-circle5 sk-child"></div>
                    <div class="sk-circle6 sk-child"></div>
                    <div class="sk-circle7 sk-child"></div>
                    <div class="sk-circle8 sk-child"></div>
                    <div class="sk-circle9 sk-child"></div>
                    <div class="sk-circle10 sk-child"></div>
                    <div class="sk-circle11 sk-child"></div>
                    <div class="sk-circle12 sk-child"></div>
                  </div>
                </center>
            </div> 
            </div>
            <div class="body">
                <div id="students_more_req"></div>
                <div id="sanitation_filters"></div>
            </div>
            <div class="body">
                <div class="table-responsive" id="table_hide">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>

                                <th>Unique ID</th>

                                <th>Student Name</th>
                               
                                <th>Class</th> 

                                <th>School Name</th>

                                <th>Disease Type</th>
                               
                                 <th>Request Raised Time</th>
                                 
                                 <th>Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                             <?php foreach ($covid_details as $index => $doc ):?>
                              <tr>                               
                                <td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Unique ID'] ;?></td>
                                <td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Name']['field_ref'] ;?></td>
                                <td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Class']['field_ref'] ;?></td>

                                <td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['School Name']['field_ref'] ;?></td>

                               <?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Normal"):?>
                                    <?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'];?>

                                <td><span class="badge bg-green">
                                    <?php foreach ($identifiers as $identifier => $values) :?>
                                        <?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]); ?>
                                        <?php if(!empty($var123)):?> 
                                            <?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]) : "No Identifier";?>

                                        <?php endif;?>
                                    <?php endforeach;?>                                             
                                    </span>
                                </td>

                                    <?php elseif($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Emergency"):?>
                                    <?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'];?>

                                <td><span class="badge bg-red">
                                    <?php foreach ($identifiers as $identifier => $values) :?>
                                        <?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]); ?>
                                        <?php if(!empty($var123)):?> 
                                            <?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]) : "No Identifier";?>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                    </span>
                                </td>


                                    <?php elseif($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Chronic"):?>
                                    <?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'];?>

                                <td><span class="badge bg-amber">
                                    <?php foreach ($identifiers as $identifier => $values) :?>
                                        <?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]); ?>
                                        <?php if(!empty($var123)):?> 
                                            <?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]) : "No Identifier";?>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                    </span>
                                </td>

                                    <?php endif;?>
                            
                                

                                <td> <?php echo $doc['history'][0]['time'];?></td>
                                <td> 
                                    <a class='ldelete' href='<?php echo URL."panacea_mgmt/panacea_reports_display_ehr_uid/"?>? id = <?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Unique ID'];?>'>
                                    <button class="btn bg-teal waves-effect">Show EHR</button>
                                    </a>                    
                                </td> 
                              </tr>
                                 <?php endforeach;?>                           
                        </tbody>
                    </table>
                </div>
            </div>
           
            </div>
        </div>
    </div>
</div>
    </section>
 
<?php include('inc/footer_bar.php'); ?> 
<!-- Jquery Core Js -->
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
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script>
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script src="<?php echo MDB_PLUGINS.'bootstrap-notify/bootstrap-notify.js'; ?>"></script>

<script>
  var today_date = $('#passing_end_date').val();
   $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
   $('#passing_end_date').change(function(e){
    today_date = $('#passing_end_date').val();;
   });
</script>
<script type="text/javascript">

    $('.sk-circle').hide();
     //$('.table_hide').hide()
    $('#date_set').click(function(){
            $('.sk-circle').show();
             $('#dt_basic').hide();

            var startDate = $('#passing_date').val();
            var endDate = $('#passing_end_date').val();
           // var status =$('#covid').val();
             // alert(startDate);
             // alert(endDate);
                $.ajax({
                    url : 'get_covid_cases_students',
                    type : 'POST',
                    data : {"start_date" : startDate, "end_date": endDate},
                    success : function(data){
                      $("#loading_modal").modal('hide');
                        $('#students_more_req').empty();
                        var result = $.parseJSON(data);
                        //console.log(result);
                        data_table_for_filters(result);
                        $('#show_when_serach').show();
                         $('#table_hide').hide();
                         $('.sk-circle').hide();
                         $('#dt_basic').show();
                       
                    }
                });

        });


    function data_table_for_filters(result){

      if(result == "No Data Available"){
          $('#sanitation_filters').html('<h4>No Data Available For this Field Officer.</h4>');
      }
        else{

            data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic"><thead><tr><th>Health ID</th><th>Student Name</th><th>Class</th><th>School Name</th><th>Request Raised Time</th><th>Action</th></tr></thead><tbody>';

              $.each(result, function(){

             data_table = data_table+'<tr>';

             data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['Unique ID']+'</td>';
             data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['Name']['field_ref']+'</td>';
             data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['Class']['field_ref']+'</td>';
             
             data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['School Name']['field_ref']+'</td>';

             data_table = data_table+'<td>'+this.history['0']['time']+'</td>'; 
              var urlLink = "https://mednote.in/PaaS/healthcare/index.php/";
             
             data_table = data_table + '<td><a class="btn btn-primary btn-xs" href="'+urlLink+'panacea_mgmt/panacea_reports_display_ehr_uid/?id = '+this.doc_data.widget_data.page1['Student Info']['Unique ID']+'">Show EHR</a></td>';


              });
               

              data_table = data_table+'</tbody></table>';

            $('#sanitation_filters').html(data_table);

              $('#dt_basic').DataTable({
                "paging": true,
                "lengthMenu" : [10, 25, 50, 75, 100]
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

$('#get_excel').click(function(){
$('.sk-circle').show();
     
      var startDate = $('#passing_date').val();
      var endDate = $('#passing_end_date').val();
      //alert(startDate);
      //alert(endDate);

      $.ajax({
        
        url:'get_excel_covid_cases',
        type:'POST',
        data:{"start_date" : startDate, "end_date": endDate},
        success : function(data){
                  console.log(data);
                  window.location = data;
                  $('.sk-circle').hide();
              },
              error:function(XMLHttpRequest, textStatus, errorThrown)
              {
               console.log('error', errorThrown);
              }

      });
      
    });

</script>
