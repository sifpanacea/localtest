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
<br>

   <!--  <?php //include("inc/sidebar.php"); ?> -->
      <!--  <section class="content"> -->
        <div class="container-fluid">
           
           <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="hidden" name="date" id="date" value="<?php echo $today_date; ?>">
                <input type="hidden" name="status" id="status" value="<?php echo $status; ?>">
                    <div class="card">
                      <div class="header">
                          <div class="row clearfix">
                              <div class="col-sm-2">
                                 <h3 class="font-bold col-green">Field Officers Data</h3>
                              </div>
                              <div class="collapse" id="multicollapseExample">
                                  <div class="col-sm-2">
                                    <?php $end_date  = date ( "Y-m-d", strtotime ( $today_date . "-90 days" ) ); ?>
                                       <span id="monitoring_datepicker">
                                        Start Date :
                                       <input type="text" id="passing_date" name="passing_date" class="form-control date" value="<?php echo $end_date; ?>">
                                       </span>
                                    </div>
                                  
                                  
                                    <div class="col-sm-2">
                                       <span id="monitoring_datepicker">
                                       End Date :
                                       <input type="text" id="passing_end_date" name="passing_end_date" class="form-control date" value="<?php echo $today_date; ?>">
                                       </span>
                                    </div> 
                                    <div class="col-sm-2">
                                       <h2 class="card-inside-title">Select FO</h2>
                                        <div class="form-line">
                                        <select class="form-control" id="no_of_requests" name="no_of_requests">
                                          <?php if(isset($field_offers) && !empty($field_offers)): ?>
                                          <?php foreach($field_offers as $officer): ?>
                                            <option value="<?php echo $officer['email']; ?>"><?php echo $officer['username']; ?></option>
                                          <?php endforeach; ?>
                                          <?php endif; ?>
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
 
    <form style="display: hidden" action="<?php echo URL; ?>maharashtra_doctor/maharashtra_reports_display_ehr_uid" method ="POST" id="more_req_students_form">
    <input type="hidden" id="more_req_studentHealthID" name="uid" value=""/>
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
      
      var date_for = $('#date').val();
      var status = $('#status').val();

    //  var requestsCount = $('#sanitation').val();

     /* 
      ('#students_more_req').show();
      ('#sanitation_filters').hide();*/
        $.ajax({
            url : 'get_field_officer_wise_submitted_docs',
            type : 'POST',
            data : {"today_date" : date_for, "status_type": status},
            success : function(data){

             // $("#loading_modal").modal('hide');
                
                var result = $.parseJSON(data);
                data_table_for_filters(result);
            /*  if(sanitation == 'Submitted'){
                 //console.log(result);
                data_table_req(result);
              }else{

               data_table_for_filters(result, requestsCount);

              
            }*/

              }
        });

     }

    $('#date_set').click(function(){
            var startDate = $('#passing_date').val();
            var endDate = $('#passing_end_date').val();
            var requestsCount = $('#no_of_requests').val();

           
           // $("#loading_modal").modal('show');
                $.ajax({
                    url : 'get_fo_records_based_on_span',
                    type : 'POST',
                    data : {"start_date" : startDate, "end_date": endDate, "request_data": requestsCount},
                    success : function(data){
                      $("#loading_modal").modal('hide');
                        $('#students_more_req').empty();
                        var result = $.parseJSON(data);
                        data_table_for_filters(result, requestsCount);

                        $('#show_when_serach').show();
                    }
                });
          

        });

     function data_table_req(result){
        
        if(result){

            data_table = '<table class="table table-bordered" id="more_requests"><thead><tr><th>Field Officer Name</th><th>Count</th><th>Show students</th></tr></thead><tbody>';

              $.each(result, function(){
               
                $('#show_when_serach').show();
              data_table = data_table+'<tr>';
              data_table = data_table+'<td>'+this.label +'</td>';
              data_table = data_table+'<td>'+this.value +'</td>';

              //data_table = data_table+'<td>'+'<label for="'+this.email+'">Show students</label>'+'</div>'+'</td>';
              data_table = data_table+'<td><button class="btn bg-cyan waves-effect m-b-15 ehrButton" type="button" data-toggle="collapse" data-target="'+'#'+this.label+'" aria-expanded="false" aria-controls="'+this.label+'" uid="'+this.email+'">Show Students</button></td>';

              data_table = data_table+'<tr><td><div class="collapse" id="'+this.label+'"><div class="well">'+this.label+'.</div></div></td></tr>';

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


    function data_table_for_filters(result, requestsCount){

      if(result == "No Data Available"){
          $('#sanitation_filters').html('<h4>No Data Available For this Field Officer.</h4>');
      }
        else{

            data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic"><thead><tr><th>Health ID</th><th>Student Name</th><th>Request Type</th><th>Visited Date</th><th>Hospital Name</th><th>Problem Info</th><th>Action</th></tr></thead><tbody>';
            /*if(requestsCount == 'Animals')<th>Respond '+'<input type="checkbox" id="select_all_chk" class="filled-in chk-col-red">'+
                                    '<label for="select_all_chk">Select All</label>'+'</th>{*/
              $.each(result, function(){

              data_table = data_table+'<tr>';

              data_table = data_table+'<td>'+this.doc_data.widget_data['Student Details']['Hospital Unique ID']+'</td>';
              data_table = data_table+'<td>'+this.doc_data.widget_data['Student Details']['Name']+'</td>';
              data_table = data_table+'<td>'+this.doc_data.widget_data['type_of_request']+'</td>';
              data_table = data_table+'<td>'+this.history.last_stage['time']+'</td>';

             
              if(this.doc_data.widget_data['Out Patient']['hospialt_name'] != ''){
                data_table = data_table+'<td>'+this.doc_data.widget_data['Out Patient']['hospialt_name']+'</td>';
              }else if(this.doc_data.widget_data['Emergency or Admitted']['hospialt_name'] != ''){
                data_table = data_table+'<td>'+this.doc_data.widget_data['Emergency or Admitted']['hospialt_name']+'</td>';
              }else if(this.doc_data.widget_data['Review Cases']['hospialt_name'] != ''){
                data_table = data_table+'<td>'+this.doc_data.widget_data['Review Cases']['hospialt_name']+'</td>';
              }else{
                data_table = data_table+'<td>No Data</td>';
              }

              if(this.doc_data.widget_data['Out Patient']['hospialt_name'] != ''){
                data_table = data_table+'<td>'+this.doc_data.widget_data['Out Patient']['patient_details']+'</td>';
              }else if(this.doc_data.widget_data['Emergency or Admitted']['hospialt_name'] != ''){
                data_table = data_table+'<td>'+this.doc_data.widget_data['Emergency or Admitted']['patient_details']+'</td>';
              }else if(this.doc_data.widget_data['Review Cases']['hospialt_name'] != ''){
                data_table = data_table+'<td>'+this.doc_data.widget_data['Review Cases']['patient_details']+'</td>';
              }else{
                data_table = data_table+'<td>No Data</td>';
              }

               var urlLink = "https://mednote.in/PaaS/healthcare/index.php/";
                              
              data_table = data_table + '<td><a class="btn btn-primary btn-xs" href="'+urlLink+'panacea_mgmt/panacea_reports_display_ehr_uid/?id = '+this.doc_data.widget_data['Student Details']['Hospital Unique ID']+'">Show EHR</a></td>';
           
              });
            /*}else if(requestsCount == 'Washrooms Required' || requestsCount == 'Not Submitted'){
              $('#show_when_serach').show();
               $.each(result, function(index, value){

              data_table = data_table+'<tr>';
              data_table = data_table+'<td>'+index +'</td>';
              data_table = data_table+'<td>'+value +'</td>';

              data_table = data_table+'<td>'+'<div class="demo-checkbox">'+
                                                '<input type="checkbox" name="checkboxName[]" id="'+value+'" class="filled-in chk-col-red" />'+
                                                '<label for="'+value+'">Send a message</label>'+
                                            '</div>'+'</td>';


                
              });

            } else{
              $.each(result, function(index, value){
               
              data_table = data_table+'<tr>';
              data_table = data_table+'<td>'+value.school +'</td>';
              data_table = data_table+'<td>'+value.count +'</td>';

              data_table = data_table+'<td>'+'<div class="demo-checkbox">'+
                                                '<input type="checkbox" name="checkboxName[]" id="'+value.school+'" class="filled-in chk-col-red" />'+
                                                '<label for="'+value.school+'">Send a message</label>'+
                                            '</div>'+'</td>';


                
              });
            }*/
            
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
 
  //  console.log(id_array);  if($("#YourTextAreaID").val().trim().length < 1)
    //var is_hospital_selected = $('#select_hospital :selected').val();
    //var doctor_id = $('#doctor_list').val();
    
    /*if($("#select_hospital").val().trim().length < 1){
        swal("Required!", "Please type your messaeg");
        $(this).prop('disabled', false);
        return;
    }
     if((today_date.length == 0) ){
        swal("Required!", "Please Select Date for Schedule");
        $(this).prop('disabled', false);
        return;
    }*/
    $("#ehr_data_for_request").val(JSON.stringify(id_array));
    $("#request_form").submit();
});


</script>

<!--  End Last 3 months students requests Monitoring   -->

  





