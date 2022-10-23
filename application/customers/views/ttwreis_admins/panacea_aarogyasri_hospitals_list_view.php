<?php $current_page=""; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<!-- <?php //include('inc/sidebar.php'); ?> -->

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
                <div class="row clearfix" id="time_span_filter_hb_overall">
                    <div class="col-sm-3">
                        <h3 class="font-bold col-green">ArogyaSri Hospitals Info..</h3>
                    </div>
                    <div class="col-sm-6 collapse" id="arogyamulticollapseExample">

                        <div class="col-sm-4">   
                            <label>District</label>
                            <select class="form-control show-tick district_filter common_change" id="select_dt_name">
                              <option value="All"  selected="">All</option>
                              <?php if(isset($distslist)): ?>
                                  <?php foreach ($distslist as $dist):?>
                                      <option value='<?php echo $dist['dt_name']?>'><?php echo ucfirst($dist['dt_name'])?></option>
                                  <?php endforeach;?>
                              <?php else: ?>
                                  <option value="1"  disabled="">No district entered yet</option>
                              <?php endif ?>
                          </select>
                        </div>

                        <div class="col-sm-4">
                            <label>Hospital Type</label>
                            <select class="form-control" id="hospital_type" name="hospital_type">
                                <option value="All"  selected="">All</option>
                                <option value="Private">Private</option>
                                <option value="Government">Government</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-line">
                                <button type="button" id="aarogyasri_btn" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                                <i class="material-icons">search</i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2" style="float: right;">
                        <ul class="header-dropdown m-l--20">
                            <li>
                                <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#arogyamulticollapseExample" aria-controls="arogyamulticollapseExample"  data-placement="bottom" title="Date Filters"><img src="<?php echo IMG ;?>/funnel.png"></button>
                            </li>
                            <li>
                                <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                            </li>
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
                                <th>Hospital Name</th>                                                 
                                <th>Specialities</th>                                                 
                                <th>MD/CEO Name</th>                                                 
                                <th>Phone</th>                                                 
                                <th>Arrogya Mitra</th>                                                 
                                <th>District Co-ordinator</th>                                                 
                                <th>District Manager</th>                                                 
                                <th>Team Leader</th>                                                 
                                <th>Hospital Type</th>                                                 
                                <th>District</th>                                                   
                            </tr>
                        </thead>
                         <tbody>    
                             <?php foreach ($aarogyasri_hospitals as  $data ): ?>                                   
                              <tr>
                                <td><?php echo $data['doc_data']['hospital name'] ;?></td>
                                <td><?php echo $data['doc_data']['specialities'] ;?></td>
                                <td><?php echo $data['doc_data']['md name'] ;?></td>
                                <td><?php echo $data['doc_data']['contact number'] ;?></td>
                                <td><?php echo $data['doc_data']['arrogya mitra'] ;?></td>
                                <td><?php echo $data['doc_data']['district co-ordinator'] ;?></td>
                                <td><?php echo $data['doc_data']['district manager'] ;?></td>
                                <td><?php echo $data['doc_data']['team leader'] ;?></td>
                                <td><?php echo $data['doc_data']['hospital type'] ;?></td>
                                <td><?php echo $data['doc_data']['district'] ;?></td>
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


     $('#aarogyasri_btn').click(function(){
            var dt_name = $('#select_dt_name').val();
            var hospital_type = $('#hospital_type').val();                   
           
                $.ajax({
                    url : 'get_aarogyasri_hospital_based_on_span',
                    type : 'POST',
                    data : {"district" : dt_name, "hospital_name": hospital_type},
                    success : function(data){
                      $("#loading_modal").modal('hide');
                        $('#students_more_req').empty();
                        var result = $.parseJSON(data);
                        data_table_for_filters(result);
                        $('#table_hide').hide();
                        $('#show_when_serach').show();
                    }
                });          

        });


     function data_table_for_filters(result){
       
       if(result){

           data_table = '<table class="table table-bordered" id="more_requests"><thead><tr><th>Hospital Name</th><th>Specialities</th><th>md name</th><th>contact number</th><th>arrogya mitra</th><th>district co-ordinator</th><th>district manager</th><th>team leader</th><th>hospital type</th><th>District</th></tr></thead><tbody>';

             $.each(result, function(){
              
               $('#show_when_serach').show();
            
            data_table = data_table+'<tr>';
                   
            data_table = data_table+'<td>'+this.doc_data['hospital name']+'</td>';
            data_table = data_table+'<td>'+this.doc_data['specialities']+'</td>';
            data_table = data_table+'<td>'+this.doc_data['md name']+'</td>';
            data_table = data_table+'<td>'+this.doc_data['contact number']+'</td>';
            data_table = data_table+'<td>'+this.doc_data['arrogya mitra']+'</td>';
            data_table = data_table+'<td>'+this.doc_data['district co-ordinator']+'</td>';
            data_table = data_table+'<td>'+this.doc_data['district manager']+'</td>';
            data_table = data_table+'<td>'+this.doc_data['team leader']+'</td>';
            data_table = data_table+'<td>'+this.doc_data['hospital type']+'</td>';
            data_table = data_table+'<td>'+this.doc_data['district']+'</td>';
           


             });

           data_table = data_table+'</tr></tbody></table>';

           $('#students_more_req').html(data_table);

             $('#more_requests').DataTable({
               "paging": true,
               "lengthMenu" : [5, 25, 50, 75, 100]
             });
       }

        $("#more_requests").each(function(){
               $('.ehrButton').click(function (){
                    var currentRow=$(this).closest("tr"); 
                    var studentHealthID=currentRow.find("td:eq(0)").text(); // get current row 2nd TD
                   alert(studentHealthID);
                  // $("#more_req_studentHealthID").val(studentHealthID);
                   //$("#more_req_students_form").submit();
               });

           });

      

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


