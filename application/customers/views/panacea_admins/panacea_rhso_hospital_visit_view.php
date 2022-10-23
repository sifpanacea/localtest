<?php $current_page=""; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<br>
<br>
<br>
<br>
<br>
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">
<!-- Code for data tables -->
<section class="">
<div class="container-fluid">

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
                   <h3 class="font-bold col-green">RHSO Hospital Visits</h3>
                </div>
                <div class="collapse" id="multicollapseExample">
                   <div class="col-sm-2">
                      <span id="monitoring_datepicker">
                      Start Date :
                      <input type="text" id="passing_date" name="passing_date" class="form-control date" value="<?php echo date('Y-m-d'); ?>">
                      </span>
                  </div> 
                  <div class="col-sm-2">
                     <span id="monitoring_datepicker">
                     End Date :
                     <input type="text" id="passing_end_date" name="passing_end_date" class="form-control date" value="<?php echo date('Y-m-d'); ?>">
                     </span>
                  </div> 
                <!--   <div class="col-sm-2">
                                  <h2 class="card-inside-title">Type of Request</h2>
                                  <div class="form-line">
                                  <select class="form-control" id="typoreq" name="typoreq">
                                    <option value="Out Patients">Out Patients</option>Emergency or Admitted
                                    <option value="In Patients">In Patients</option>
                                    <option value="Emergency or Admitted">Emergency or Admitted</option>
                                    <option value="Review Cases">Reviews</option>
                                  </select>
                                </div>
                                </div> -->
                    <div class="col-sm-2">
                     <h2 class="card-inside-title">Select RHSO</h2>
                      <div class="form-line">
                      <select class="form-control" id="no_of_submits" name="no_of_submits">
                        <?php if(isset($rhso_names) && !empty($rhso_names)): ?>
                        <?php foreach($rhso_names as $rhsos): ?>
                          <option value="<?php echo $rhsos['Email']; ?>"><?php echo $rhsos['Rhso_name']; ?></option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                       </select>
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
                <div class="col-sm-2 pull-right">
                    <div class="form-line">    
                       <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#multicollapseExample" aria-controls="multicollapseExample"  data-placement="bottom" title="Date Filters"><img src="<?php echo IMG ;?>/funnel.png"></button>
                       <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                    </div>       
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
                                <th>Hospital ID</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Type of Request</th>
                                <th>Attachments</th>
                                <th>Show EHR</th>
                            </tr>
                        </thead>
                        <tbody>
                             <?php foreach ($rhso_data as  $notes ): ?>
                              <tr> 

                              <td><?php echo $notes['doc_data']['widget_data']['Student Details']['Hospital Unique ID'] ;?></td>                              
                              <td><?php echo $notes['doc_data']['widget_data']['Student Details']['Name'] ;?></td>

                              <td><?php echo $notes['doc_data']['widget_data']['Student Details']['Class'] ;?></td>

                                                           
                              <td><?php echo $notes['doc_data']['widget_data']['type_of_request'] ;?></td>                              
                                                            
                          <?php if(isset($notes['doc_attachments']['external_attachments']) && !empty($notes['doc_attachments']['external_attachments'])):?>
                              <td class=""><a href="javascript:void(0);" class="dropdown-toggle" data-toggle="modal" role="button" data-target="#doctor_visit_attachments"><i class="material-icons" aria-hidden="true">attach_file</i></a></td>
                          <?php else:?>
                              <td>No Attachments</td>
                          <?php endif;?>

                              <!-- <td><button type="button" class="btn bg-teal waves-effect">Show EHR</button></td> -->
                              <td> 
                                <a class='ldelete' href='<?php echo URL."panacea_mgmt/panacea_reports_display_ehr_uid/"?>? id = <?php echo $notes['doc_data']['widget_data']['Student Details']['Hospital Unique ID'];?>'>
                                <button class="btn bg-teal waves-effect">Show EHR</button>
                                </a>              
                              </td>
                                 
                              <!--  <?php //$name = isset($notes['history']['rhso_name']) ? $notes['history']['rhso_name'] : "Nil";  ?>
                                  <td><?php //echo $name; ?></td> -->                             
                              
                              <!--  <td><textarea rows="3" cols="150" readonly=""><?php //echo $notes['note'] ;?></td> -->
                              </tr>
                                 <?php endforeach; ?>                           
                        </tbody>
                    </table>
                </div>
            </div>

           
            </div>
        </div>
    </div>
</div>
    </section>

<div class="modal fade" id="doctor_visit_attachments" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Attachments</h4>
            </div>
            <div class="modal-body">
                <div id="carousel-example-generic_2" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic_2" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic_2" data-slide-to="1"></li>
                    </ol>
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">

                        <div class="item active">
                          <?php if(isset($notes['doc_attachments']['external_attachments']) && !empty($notes['doc_attachments']['external_attachments'])): ?>

                              <?php foreach ($notes['doc_attachments']['external_attachments'] as $attachment):?>
                                    <img src="<?php echo URLCustomer.$attachment['file_path']; ?>" alt="" width="300" >
                              <?php endforeach;?>

                                  <!-- <img class="" src="<?php //echo URLCustomer.$notes['doc_attachments']['external_attachments'];?>" height="125" width="150" alt="Attachments"> -->
                          <?php else: ?>
                                  <img class="" src="<?php echo IMG; ?>/demo/Student_photo.png" alt="Student Photo">
                          <?php endif; ?>
                            
                        </div>
                        <div class="item">
                          <?php if(isset($notes['doc_attachments']['Prescriptions']) && !empty($notes['doc_attachments']['Prescriptions'])): ?>
                            <?php foreach ($notes['doc_attachments']['Prescriptions'] as $attachment):?>
                                <img src="<?php echo URLCustomer.$attachment['file_path']; ?>" alt="" width="300" >
                            <?php endforeach;?>
                            <?php else: ?>
                                  <img class="" src="<?php echo IMG; ?>/demo/Student_photo.png" alt="Student Photo">
                          <?php endif; ?>  
                        </div>
                        <div class="item">
                          <?php if(isset($notes['doc_attachments']['Lab_Reports']) && !empty($notes['doc_attachments']['Lab_Reports'])): ?>
                            <?php foreach ($notes['doc_attachments']['Lab_Reports'] as $attachment):?>
                                <img src="<?php echo URLCustomer.$attachment['file_path']; ?>" alt="" width="300" >
                            <?php endforeach;?>
                            <?php else: ?>
                                  <img class="" src="<?php echo IMG; ?>/demo/Student_photo.png" alt="Student Photo">
                          <?php endif; ?>  
                        </div>
                        <div class="item">
                          <?php if(isset($notes['doc_attachments']['Digital_Images']) && !empty($notes['doc_attachments']['Digital_Images'])): ?>
                            <?php foreach ($notes['doc_attachments']['Digital_Images'] as $attachment):?>
                                <img src="<?php echo URLCustomer.$attachment['file_path']; ?>" alt="" width="300" >
                            <?php endforeach;?>
                            <?php else: ?>
                                  <img class="" src="<?php echo IMG; ?>/demo/Student_photo.png" alt="Student Photo">
                          <?php endif; ?>  
                        </div>
                        <div class="item">
                          <?php if(isset($notes['doc_attachments']['Payments_Bills']) && !empty($notes['doc_attachments']['Payments_Bills'])): ?>
                            <?php foreach ($notes['doc_attachments']['Payments_Bills'] as $attachment):?>
                                <img src="<?php echo URLCustomer.$attachment['file_path']; ?>" alt="" width="300" >
                            <?php endforeach;?>
                            <?php else: ?>
                                  <img class="" src="<?php echo IMG; ?>/demo/Student_photo.png" alt="Student Photo">
                          <?php endif; ?>  
                        </div>
                        <div class="item">
                          <?php if(isset($notes['doc_attachments']['Discharge_Summary']) && !empty($notes['doc_attachments']['Discharge_Summary'])): ?>
                            <?php foreach ($notes['doc_attachments']['Discharge_Summary'] as $attachment):?>
                                <img src="<?php echo URLCustomer.$attachment['file_path']; ?>" alt="" width="300" >
                            <?php endforeach;?>
                            <?php else: ?>
                                  <img class="" src="<?php echo IMG; ?>/demo/Student_photo.png" alt="Student Photo">
                          <?php endif; ?>  
                        </div>
                    </div>
                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-example-generic_2" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic_2" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
               
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>    
 <!-- Modal -->
             <!--  <div class="modal fade" id="load_waiting" tabindex="-1" role="dialog" >
               <div class="modal-dialog">                  
                 <div class="modal-content">
                   <div class="modal-header">
                     <h4 class="modal-title" id="myModalLabel">Lab Reports</h4>                     
                   </div>
                   <div class="modal-body">
                     <div class="row">                   
                       <div class="col-lg-12">                 
                         <div class="show_lab_reports">                            
                         </div>
                       </div>
                     </div>
                   </div>/.modal-content
                 </div>/.modal-dialog
               </div>/.modal
             </div> -->

                      <!-- Modal -->

<script src="<?php echo(MDB_PLUGINS.'jquery/jquery.min.js'); ?>"></script>
<script src="<?php echo(MDB_PLUGINS.'bootstrap/js/bootstrap.js'); ?>"></script>
 <script src="<?php echo(MDB_PLUGINS.'jquery-slimscroll/jquery.slimscroll.js'); ?>"></script>
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
  $(document).on('click','.ShowReports',function()
  {    
    alert('yoga');
     var uid = $(this).attr('uid');  
     alert(uid);
   //  selected_date = $('#set_data').val();
       var urlLink = "<?php echo URL;?>";        
          $.ajax({
              url : urlLink+'panacea_mgmt/panacea_rhso_hospital_reports',
              type : 'POST',
              data : {'patient_id' : uid},
              success : function(data){
                  var docs = $.parseJSON(data);  

                  alert(docs);
                  console.log(docs, "docsssssss");              
                 /* if(docs['0'].Lab_Reports > 0)
                  {*/
                    var img_url = "<?php echo URLCustomer;?>";
                      data_table = "";                    
                      $.each(docs['0'].Lab_Reports, function(index, values)
                      {  
                        data_table = data_table + '<iframe  src = '+img_url+values.file_path + ' &embedded=true style=width:550px;height:450px; frameborder="0"></iframe>';
                      });
                      $('.show_lab_reports').html(data_table);
                 
              },
              error:function(XMLHttpRequest, textStatus, errorThrown)
              {
               console.log('error', errorThrown);
              }
        });
       
     
  });

</script>

<script type="text/javascript">

    $('#date_set').click(function(){
            var startDate = $('#passing_date').val();
            var endDate = $('#passing_end_date').val();
            //var typorequest = $('#typoreq').val();
            var rhso = $('#no_of_submits').val();
   
   // alert(typorequest);
   // alert(endDate);
    alert(rhso);
     
                $.ajax({
                    url : 'get_rhso_hospital_records_based_on_span',
                    type : 'POST',
                    data : {"start_date" : startDate, "end_date": endDate, "rhso_mail":rhso},
                    success : function(data){
                     
                        $('#students_more_req').empty();
                        var result = $.parseJSON(data);
                        //console.log(result);
                        data_table_for_filters(result);
                       
                         $('#table_hide').hide();
                    }
                });

        });


    function data_table_for_filters(result){

      if(result == "No Data Available"){
          $('#sanitation_filters').html('<h4>No Data Available For this RHSO......</h4>');
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
var today_date = $('#passing_end_date').val();
    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#passing_end_date').change(function(e){
            today_date = $('#passing_end_date').val();;
    });

</script>
<?php include('inc/footer_bar.php'); ?> 
