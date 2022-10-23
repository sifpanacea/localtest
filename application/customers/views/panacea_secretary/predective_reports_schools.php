<?php $current_page = "max_raised_requests"; ?>
   <?php $main_nav = ""; ?>
    <?php include("inc/header_bar.php"); ?>
    <link href='<?php echo MDB_PLUGINS."jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css"; ?>' rel="stylesheet">
   <!--  Bootstrap Material Datetime Picker Css -->
    <link href='<?php echo MDB_PLUGINS."bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"; ?>' rel="stylesheet" /> 
    <!-- Bootstrap DatePicker Css -->
    <link href='<?php echo MDB_PLUGINS."bootstrap-datepicker/css/bootstrap-datepicker.css"; ?>' rel="stylesheet" /> 

<br>
<br>
<br>
<br>

   <!--  <?php //include("inc/sidebar.php"); ?> -->
      <!--  <section class="content"> -->
        <div class="container-fluid">
           
           <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               
                <input type="hidden" name="years" id="years" value="<?php echo $academic; ?>">
                <input type="hidden" name="districts" id="districts" value="<?php echo $dist; ?>">
                <input type="hidden" name="schools" id="schools" value="<?php echo $scl; ?>">
                <input type="hidden" name="zones" id="zones" value="<?php echo $predi_zone; ?>">

                   <div class="card">
                    <div class="header">
                             <div class="row clearfix">
                                 <div class="col-sm-2">
                                    <h3 class="font-bold col-green">Schools</h3>
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
           
        </div>
  <!--   </section>
 -->
 
    <form style="display: hidden" action="<?php echo URL; ?>panacea_secretary/get_predictive_students_selected_school" method ="POST" id="get_students">
    <input type="hidden" id="select_scl" name="select_scl" value=""/>
    <input type="hidden" id="select_date" name="select_date" value=""/>
    <input type="hidden" id="select_zone" name="select_zone" value=""/>
    </form>


<?php include("inc/message_status.php"); ?>
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



<!--  Last 3 months students requests Monitoring   -->
<!-- selecting Date and Requests Count  Range -->
<script type="text/javascript">

last_three_months_more_req_students();

    var today_date = $('#set_date').val();
    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#set_date').change(function(e){
            today_date = $('#set_date').val();;
    });

   /* var startDate = $('#passing_date').val();
    var endDate = $('#passing_end_date').val();
    var requestsCount = $('#no_of_requests').val();*/

    //var date_for = $('#date').val();
   // var sanitation = $('#sanitation').val();

   
   

     function last_three_months_more_req_students(){
      
      var date_for = $('#years').val();
      var district = $('#districts').val();
      var school = $('#schools').val();
      var zone = $('#zones').val();

     /* alert(date_for);
      alert(districts);
      alert(schools);*/

     /* 
      ('#students_more_req').show();
      ('#sanitation_filters').hide();*/
        $.ajax({
            url : 'school_wise_predictive_zones',
            type : 'POST',
            data : {"predictive_academic" : date_for, "predictive_dist": district, "predictive_scl":school, "value_zone":zone},
            success : function(data){

             // $("#loading_modal").modal('hide');
                
                var result = $.parseJSON(data);

               data_table_for_filters(result);


              }
        });

     };

  

    function data_table_for_filters(result){

      if(result){

            data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic"><thead><tr><th>School Name</th><th>Count</th><th>Action</th></tr></thead><tbody>';
            
              $.each(result, function(index, value){

              data_table = data_table+'<tr>';
              data_table = data_table+'<td>'+index +'</td>';
              data_table = data_table+'<td>'+value +'</td>';

              data_table = data_table+'<td><button type="button" class="btn bg-green show_scl" id="show_scl">Show</button></td>';

              });
            
            
            data_table = data_table+'</tr></tbody></table>';

            $('#sanitation_filters').html(data_table);

              $('#dt_basic').DataTable({
                "paging": true,
                "lengthMenu" : [5, 25, 50, 75, 100]
              });

                $('#dt_basic').each(function(){
                $('#show_scl').click(function(){
                    var currentRow=$(this).closest("tr"); 
                    var scl_name_select=currentRow.find("td:eq(0)").text();

                    var date_for_select = $('#years').val();
                    var zone_for_select = $('#zones').val();

                   /* alert(scl_name_select);
                    alert(date_for_select);
                    alert(zone_for_select);*/

                    $('#select_scl').val(scl_name_select);
                    $('#select_date').val(date_for_select);
                    $('#select_zone').val(zone_for_select);
                    $('#get_students').submit();
                    
                    
               });
            });

           /* $("#select_all_chk").click(function(){
              
                var oTable = $('#dt_basic').dataTable();
                var allPages = oTable.fnGetNodes();
                $('input[type="checkbox"]', allPages).prop('checked', $(this).is(':checked'));
            });*/
        }

    };


</script>

<!--  End Last 3 months students requests Monitoring   -->

  





