<?php $current_page=""; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

  <link href='<?php echo MDB_PLUGINS."bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"; ?>' rel="stylesheet" />
<!-- Code for data tables -->
<section class="content">
<div class="container-fluid">
<div class="block-header">
   <!--  <h2>Surgery Needed Health Requests</h2> -->
</div>
<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
              <!--   <h2>
                   Student Request Details
              </h2> -->
               <!--  <ul class="header-dropdown m-r--5">
                   <div class="button-demo">
                   <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                   </div>
               </ul> -->
            <div class="row clearfix">
                <div class="col-sm-2">
                   <h3 class="font-bold col-green">Requests Notes Students</h3>
                </div>
                <div class="collapse" id="multicollapseExample">
                  <div class="col-sm-2">
                  <?php $end_date  = date ( "Y-m-d", strtotime ( date('Y-m-d') . "-90 days" ) ); ?>
                       <span id="monitoring_datepicker">
                        Start Date :
                      <input type="text" id="passing_date" name="passing_date" class="form-control date" value="<?php echo $end_date; ?>">
                       </span>
                  </div>
                  <div class="col-sm-2">
                       <span id="monitoring_datepicker">
                       End Date :
                       <input type="text" id="passing_end_date" name="passing_end_date" class="form-control date" value="<?php echo date('Y-m-d'); ?>">
                       </span>
                  </div>
                  <div class="col-sm-2">
                      <div class="form-line">
                            <button type="button" id="date_set" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                            <i class="material-icons">search</i>
                            </button>
                      </div>
                  </div>
                </div>
                <div class="col-sm-3 pull-right">
                   <ul class="header-dropdown m-r--5">
                      <li><button class="btn btn-default" type="button" data-toggle="collapse" data-target="#multicollapseExample" aria-controls="multicollapseExample"  data-placement="bottom" title="Date Filters"><img src="<?php echo IMG ;?>/funnel.png"></button></li>
                      <li><button type="button" id="get_excel" class="btn bg-green btn-sm waves-effect">Get Excel</button></li>
                      <li><button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button></li>
                  </ul>
                </div>                
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
                                <th>Health ID</th>                                                 
                                <th>Name</th>                                                 
                                <th>Class</th>                                                 
                                <th>School Name</th>                                                 
                               <th>Date of Submit</th>
                               <th>Remarks</th>
                               <th>Action</th>                       
                            </tr>
                        </thead>
                         <tbody>
                             <?php foreach ($notes_data as  $data ): ?>                                   
                              <tr>
                                <td><?php echo $data['uid'] ;?></td>
                                <td><?php echo $data['Name'] ;?></td>
                                <td><?php echo $data['Class'] ;?></td>
                                <td><?php echo $data['School_Name'] ;?></td>
                                <td><?php echo $data['datetime'] ;?></td>
                                <td><?php echo $data['note'] ;?></td>
                                <td>              
                                    <a class='ldelete' href='<?php echo URL."panacea_mgmt/panacea_reports_display_ehr_uid/"?> ? id = <?php echo $data['uid'];?>'>                  
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

    <?php //include('inc/footer_bar.php'); ?>

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

<script type="text/javascript">

   var today_date = $('#passing_end_date').val();
    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#passing_end_date').change(function(e){
            today_date = $('#passing_end_date').val();;
    });

    $('#date_set').click(function(){
            var startDate = $('#passing_date').val();
            var endDate = $('#passing_end_date').val();
    
   /* alert(startDate);
    alert(endDate);*/
            
                $.ajax({
                    url : 'get_request_notes_based_on_time_span',
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
                    }
                });

        });

    $('#get_excel').click(function(){
   
    var start = $('#passing_date').val();
    var end = $('#passing_end_date').val();
   // alert(start);
   // alert(end);

    $.ajax({
      
      url:'get_excel_for_request_notes',
      type:'POST',
      data:{'start_date':start, 'end_date':end},
      success : function(data){
                console.log(data);
                window.location = data;
            },
            error:function(XMLHttpRequest, textStatus, errorThrown)
            {
             console.log('error', errorThrown);
            }
    });
  });


    function data_table_for_filters(result){

      if(result == "No Data Available"){
          $('#sanitation_filters').html('<h4>No Data Available For this Field Officer.</h4>');
      }
        else{

            data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic"><thead><tr><th>Health ID</th><th>Name</th><th>Class</th><th>School Name</th><th>Date of Submit</th><th>Remarks</th><th>Action</th></tr></thead><tbody>';

              $.each(result, function(){

              data_table = data_table+'<tr>';

              data_table = data_table+'<td>'+this.uid+'</td>';
              data_table = data_table+'<td>'+this.Name+'</td>';
              data_table = data_table+'<td>'+this.Class+'</td>';
              data_table = data_table+'<td>'+this.School_Name+'</td>';
              data_table = data_table+'<td>'+this.datetime+'</td>';
              data_table = data_table+'<td>'+this.note+'</td>';
                          
             var urlLink = "https://mednote.in/PaaS/healthcare/index.php/";
             
             data_table = data_table + '<td><a class="btn btn-primary btn-xs" href="'+urlLink+'panacea_mgmt/panacea_reports_display_ehr_uid/?id = '+this.uid+'">Show EHR</a></td>';      
             

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
