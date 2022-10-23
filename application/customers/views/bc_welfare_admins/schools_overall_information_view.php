<?php $current_page=""; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>

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
              <!--   <h2>
                   Student Request Details
              </h2> -->
               <!--  <ul class="header-dropdown m-r--5">
                   <div class="button-demo">
                   <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                   </div>
               </ul> -->
            <div class="row clearfix">
                <div class="col-sm-3">
                   <h3 class="font-bold col-green">Schools Information</h3>
                </div>
                <div class="collapse" id="multicollapseExample">
                   <div class="col-sm-2">
                    <h2 class="card-inside-title">Select District</h2>
                        <div class="form-line">
                            <select id="select_dt_name" class="form-control">
                                <option value="All">All</option>
                                <?php if(isset($distslist)): ?>
                                  
                                    <?php foreach ($distslist as $dist):?>
                                    <option value='<?php echo $dist['dt_name']; ?>' ><?php echo ucfirst($dist['dt_name'])?></option>
                                    <?php endforeach;?>
                                    <?php else: ?>
                                    <option value="1"  disabled="">No District entered yet</option>
                                <?php endif ?>
                            </select>
                          </div>
                        </div>
                   <div class="col-sm-2">
                     <h2 class="card-inside-title">HS Job Type</h2>
                           <div class="form-line">
                            <select id="select_job_type" class="form-control">
                                <option value="All">Select Job Type</option>
                                <option value="Regular">Regular</option>
                                <option value="Part Time">Part Time</option>
                                <option value="CRT Base">Contract Base</option>
                                
                            </select>
                          </div>
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
                                <th>School Name</th>                                                 
                                <th>District</th>                                                 
                                <th>RCO Details</th>                         
                               <th>Principal Details</th>
                               <th>Health Supervisor</th>                                                   
                               <th>Job Type</th>                                
                               <th>DEO Details</th>                                                                                                      
                               <th>School Strength</th>                                                                                                      
                            </tr>
                        </thead>
                         <tbody>
                             <?php foreach ($schools_data as  $data ): ?>                                   
                              <tr>
                                <td><?php echo $data['doc_data']['school_name'] ;?></td>
                                <td><?php echo $data['doc_data']['district'] ;?></td>
                                <td><?php echo $data['doc_data']['rco_details'] ;?></td>                                
                                <td><?php echo $data['doc_data']['principal_details'] ;?></td>
                                <td><?php echo $data['doc_data']['hs_details'] ;?></td>                                   
                                <td><?php echo $data['doc_data']['job_type'] ;?></td>     
                                <td><?php echo $data['doc_data']['deo_details'] ;?></td>                                   
                                <td><?php echo $data['doc_data']['strength'] ;?></td>                                   
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

  

    function data_table_for_filters(result){

      if(result == "No Data Available"){
          $('#sanitation_filters').html('<h4>No Data Available For this school....</h4>');
      }
        else{

            data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic"><thead><tr><th>School Name</th><th>District</th><th>RCO Details</th><th>Principal Details</th><th>Health Supervisor</th><th>Job Type</th><th>DEO Details</th><th>School Strength</th></tr></thead><tbody>';

              $.each(result, function(){

              data_table = data_table+'<tr>';

              data_table = data_table+'<td>'+this.doc_data['school_name']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['district']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['rco_details']+'</td>';             
              data_table = data_table+'<td>'+this.doc_data['principal_details']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['hs_details']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['job_type']+'</td>';              
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
