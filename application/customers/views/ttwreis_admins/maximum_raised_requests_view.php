<?php $current_page = "max_raised_requests"; ?>
   <?php $main_nav = ""; ?>
    <?php include("inc/header_bar.php"); ?>
    <link href='<?php echo MDB_PLUGINS."jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css"; ?>' rel="stylesheet">
   <!--  Bootstrap Material Datetime Picker Css -->
    <link href='<?php echo MDB_PLUGINS."bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"; ?>' rel="stylesheet" /> 
    <!-- Bootstrap DatePicker Css -->
    <link href='<?php echo MDB_PLUGINS."bootstrap-datepicker/css/bootstrap-datepicker.css"; ?>' rel="stylesheet" /> 

    <?php include("inc/sidebar.php"); ?>
       <section class="content">
        <div class="container-fluid">
           
           <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                   <div class="card">
                               <div class="header">
                                       <div class="row">
                                           <div class="col col-sm-4">
                                               <h4 class="font-bold col-green">Maximum Requests Raised Students Monitoring.</h4>
                                           </div>
                                          <div class="col col-sm-2">
                                            <?php $end_date  = date ( "Y-m-d", strtotime ( $today_date . "-30 days" ) ); ?>
                                               <span id="monitoring_datepicker">
                                                Start Date :
                                               <input type="text" id="passing_date" name="passing_date" class="form-control date" value="<?php echo $end_date; ?>">
                                               </span>
                                           </div>
                                          
                                          
                                           <div class="col col-sm-2">
                                               <span id="monitoring_datepicker">
                                               End Date :
                                               <input type="text" id="passing_end_date" name="passing_end_date" class="form-control date" value="<?php echo $today_date; ?>">
                                               </span>
                                           </div> 

                                   <div class="col-sm-2">
                                       <h2 class="card-inside-title">Max Requests</h2>
                                       <div class="form-line">
                                           <input type="text" id="no_of_requests" name="no_of_requests" class="form-control" value="3" >
                                       </div>
                                   </div>
                                   <div class="col-sm-1">
                                       <div class="form-line">
                                           <button type="button" id="date_set" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                                       <i class="material-icons">search</i>
                                   </button>
                                       </div>
                                   </div>

                                   </div>
                               </div>
                               <div class="body">
                                   <div id="students_more_req"></div>
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
    </section>

    <form style="display: hidden" action="<?php echo URL; ?>maharashtra_doctor/maharashtra_reports_display_ehr_uid" method ="POST" id="more_req_students_form">
    <input type="hidden" id="more_req_studentHealthID" name="uid" value=""/>
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

    <!--  Last 3 months students requests Monitoring   -->
<!-- selecting Date and Requests Count  Range -->
<script type="text/javascript">
    var today_date = $('#set_date').val();
    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#set_date').change(function(e){
            today_date = $('#set_date').val();;
    });

    var startDate = $('#passing_date').val();
    var endDate = $('#passing_end_date').val();
    var requestsCount = $('#no_of_requests').val();

     last_three_months_more_req_students();

     function last_three_months_more_req_students(){
        var todayDate = $('#passing_date').val();
        var endDate = $('#passing_end_date').val();
        var requestsCount = $('#no_of_requests').val();
        $("#loading_modal").modal('show');
        $.ajax({
            url : 'maximum_raised_requests_script',
            type : 'POST',
            data : {"start_date" : startDate, "end_date": endDate, "request_count": requestsCount},
            success : function(data){

              $("#loading_modal").modal('hide');
                
                var result = $.parseJSON(data);
                data_table_req(result);
              
            }
        });

     }

    $('#date_set').click(function(){
            var startDate = $('#passing_date').val();
            var endDate = $('#passing_end_date').val();
            var requestsCount = $('#no_of_requests').val();
            $("#loading_modal").modal('show');
                $.ajax({
                    url : 'maximum_raised_requests_script',
                    type : 'POST',
                    data : {"start_date" : startDate, "end_date": endDate, "request_count": requestsCount},
                    success : function(data){
                      $("#loading_modal").modal('hide');
                        $('#students_more_req').empty();
                        var result = $.parseJSON(data);
                        data_table_req(result);
                    }
                });
          

        });

     function data_table_req(result){
         console.log("result", result);

        if(result){


            data_table = '<table class="table table-bordered" id="more_requests"><thead><tr><th>Health ID</th><th>Name</th><th>Class</th><th>School</th><th>EHR</th></tr></thead><tbody>';

            $.each(result, function(){

                data_table = data_table+'<tr>';
                data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['Unique ID'] +'</td>';
                data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['Name']['field_ref'] +'</td>';
                data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['Class']['field_ref']+'</td>';
                data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['School Name']['field_ref'] +'</td>';
                
                 var urlLink = "https://mednote.in/PaaS/healthcare/index.php/";
              
                data_table = data_table + '<td><a class="btn btn-primary btn-xs" href="'+urlLink+'ttwreis_mgmt/ttwreis_reports_display_ehr_uid/?id = '+this.doc_data.widget_data.page1['Student Info']['Unique ID']+'">Show EHR</a></td>';
                
            });
            data_table = data_table+'</tbody></table>';

            $('#students_more_req').html(data_table);

              $('#more_requests').DataTable({
                "paging": true,
                "lengthMenu" : [15, 25, 50, 75, 100]
              });
        }

         $("#more_requests").each(function(){
                $('.ehrButton').click(function (){
                     var currentRow=$(this).closest("tr"); 
                     var studentHealthID=currentRow.find("td:eq(0)").text(); // get current row 2nd TD
                    //alert(studentHealthID);
                    $("#more_req_studentHealthID").val(studentHealthID);
                    $("#more_req_students_form").submit();
                });

                });

     }

</script>

<!--  End Last 3 months students requests Monitoring   -->

  





