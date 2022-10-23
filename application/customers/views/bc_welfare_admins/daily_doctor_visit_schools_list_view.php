<?php $current_page="";?>
<?php $main_nav=""; ?>
<?php
include('inc/header_bar.php');
?>
     <link href='<?php echo MDB_PLUGINS."jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css"; ?>' rel="stylesheet">
     <link href='<?php echo MDB_PLUGINS."sweetalert/sweetalert.css"; ?>' rel="stylesheet">
     <!--  Bootstrap Material Datetime Picker Css -->
    <link href='<?php echo MDB_PLUGINS."bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"; ?>' rel="stylesheet" /> 
    <!-- Bootstrap DatePicker Css -->
    


<br><?php $current_page=""; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<br>
<br>
<br>
<!-- Code for data tables -->
<section class="">
<div class="container-fluid">
<!-- <div class="block-header">
    <h2>Doctor Visiting Submitted Schools List</h2>
</div> -->

<div class="block-header">
   <!--  <h2>Surgery Needed Health Requests</h2> -->
           <!--  <ul class="header-dropdown m-r--5">
               <div class="button-demo" style="text-align:right">
               <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
               </div>
           </ul> -->
</div>
<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
               <!--  <h2>
                    Student Request Details
               </h2>
               <ul class="header-dropdown m-r--5">
                   <div class="button-demo">
                   <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                   </div>
               </ul> -->
               <div class="row clearfix">
                   <div class="col-sm-2">
                      <h3 class="font-bold col-green">Dr Visit Schools</h3>
                   </div>
                   <div class="collapse" id="multicollapseExample">
                     <div class="col-sm-3">
                       <?php $end_date  = date ( "Y-m-d", strtotime ( date('yy-m-d') . "-90 days" ) ); ?>
                          <span id="monitoring_datepicker">
                           Start Date :
                          <input type="text" id="passing_date" name="passing_date" class="form-control date" value="<?php echo $end_date; ?>">
                          </span>
                     </div>
                     <div class="col-sm-3">
                          <span id="monitoring_datepicker">
                          End Date :
                          <input type="text" id="passing_end_date" name="passing_end_date" class="form-control date" value="<?php echo date('yy-m-d'); ?>">
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
                   <div class="col-sm-2 pull-right">
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
                               <th>Students Counts</th>                         
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($doctor_visit as $key => $value ): ?>
                            <tr>                                                          
                                <td><?php echo $key ;?></td>
                                <td><?php echo $value;?></td>
                                <form action='<?php echo URL."bc_welfare_mgmt/visiting_doctor_students_data" ?>'accept-charset="utf-8" method="POST">

                                   <input type="hidden" name="school_name" value="<?php echo $key; ?>">                               
                                   <input type="hidden" name="dr_visit" value="<?php echo $dr_visit; ?>">
                                   <input type="hidden" name="date" value="<?php echo $date; ?>">

                                   <td><button class="btn bg-teal waves-effect">Show Students</button></td>
                                </form>  
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

    
  <form style="display: hidden" action="<?php echo URL;?>bc_welfare_mgmt/time_span_visiting_doctor_students_data" method="POST" id="submit_form">
  <input type="hidden" name="scl_id" id="scl_id" value=""/>
  <input type="hidden" name="start" id="start" value=""/>
  <input type="hidden" name="end" id="end" value=""/>
  </form>

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


<?php include('inc/footer_bar.php'); ?> 

   <script type="text/javascript">
    var today_date = $('#passing_end_date').val();

    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });

    $('#passing_end_date').change(function(e){
            today_date = $('#passing_end_date').val();;
    });


    $('#date_set').click(function(){
            var startDate = $('#passing_date').val();
            var endDate = $('#passing_end_date').val();
    /*
    alert(startDate);
    alert(endDate);
    */         
                $.ajax({
                    url : 'get_dr_visit_schools_based_on_span',
                    type : 'POST',
                    data : {"start_date" : startDate, "end_date": endDate},
                    success : function(data){
                      $("#loading_modal").modal('hide');                     
                        $('#students_more_req').empty();
                        var result = $.parseJSON(data);
                        console.log(result);
                        data_table_for_filters(result);
                        $('#show_when_serach').show();
                        $('#table_hide').hide();
                    }
                });

        });


    function data_table_for_filters(result){

      if(result == "No Data Available"){
          $('#sanitation_filters').html('<h4>No Data Available For this Field Officer.</h4>');
      }
        else{

            data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic"><thead><tr><th>School Name</th><th>Student Count </th><th>Action</th></tr></thead><tbody>';

              $.each(result, function(key,value){

              data_table = data_table+'<tr>';
              data_table = data_table+'<td>'+key+'</td>';
              data_table = data_table+'<td>'+value+'</td>';
              //var urlLink = "<?php //echo URL;?>";
              //data_table = data_table + '<td><a class="btn btn-primary btn-xs" href="'+urlLink+'panacea_mgmt/time_span_visiting_doctor_students_data/?get_id = '+result+'">Show</a></td>';
              data_table = data_table+'<td><button type="button" class="btn-primary btn-xs get_stud">Show Student</button></td>'           
              });

              data_table = data_table+'</tr></tbody></table>';

            $('#sanitation_filters').html(data_table);

           
            $('#dt_basic').each(function(){
              $('.get_stud').click(function(){
                var currentRow = $(this).closest('tr');
                var scl_name = currentRow.find('td:eq(0)').text();
                var startDate = $('#passing_date').val();
                var endDate = $('#passing_end_date').val();
                /* alert(scl_name);
                 alert(startDate);
                 alert(endDate);*/
                 $('#scl_id').val(scl_name);
                 $('#start').val(startDate);
                 $('#end').val(endDate);
                 $('#submit_form').submit();
              });
            });

              $('#dt_basic').DataTable({
                "paging": true,
                "lengthMenu" : [10, 25, 50, 75, 100]
              });

           /* $("#select_all_chk").click(function(){

                var oTable = $('#dt_basic').dataTable();
                var allPages = oTable.fnGetNodes();
                $('input[type="checkbox"]', allPages).prop('checked', $(this).is(':checked'));
            });*/
        }

         

    };

 /*   $("#submit_request").click(function(){

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
*/

</script>
