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
                <div class="col-sm-2">
                   <h3 class="font-bold col-green">RHSO Reports</h3>
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

                                <th>campus</th>
                               
                                <th>Kitchen</th> 
                               
                                 <th>Tiolets</th>
                                 
                                   <th>Dormitory</th>

                                   <th>Status</th>

                                   <th>RHSO NAME</th>
                                   
                                
                            </tr>
                        </thead>
                        <tbody>
                             <?php foreach ($rhso_data as  $notes ): ?>
                              <tr>                               
                                <td><?php echo $notes['School Name'] ;?></td>

                                 <?php $campus = isset($notes['Campus']['Description']) ? $notes['Campus']['Description'] : "Nil";  ?>
                                  <td><?php echo $campus; ?></td>
                              
                                <?php $kitchen = isset($notes['Kitchen']['Description']) ? $notes['Kitchen']['Description'] : "Nil";  ?>
                                  <td><?php echo $kitchen; ?></td>

                                  <?php $toilet = isset($notes['Toilets']['Description']) ? $notes['Toilets']['Description'] : "Nil";  ?>
                                  <td><?php echo $toilet; ?></td>

                                   <?php $Dormitory = isset($notes['Dormitory']['Description']) ? $notes['Dormitory']['Description'] : "Nil";  ?>
                                  <td><?php echo $Dormitory; ?></td>
                                  
                                   <?php $status = isset($notes['history']['status']) ? $notes['history']['status'] : "Nil";  ?>
                                  <td><?php echo $status; ?></td>

                                   <?php $name = isset($notes['history']['rhso_name']) ? $notes['history']['rhso_name'] : "Nil";  ?>
                                  <td><?php echo $name; ?></td>                                                                  
                              
                               <!--  <td><textarea rows="3" cols="150" readonly=""><?php //echo $notes['note'] ;?></td> -->
                               
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
              url : urlLink+'panacea_mgmt/panacea_rhso_reports',
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
            var rhso = $('#no_of_submits').val();
   
    //alert(startDate);
   // alert(endDate);
    alert(rhso);
     
                $.ajax({
                    url : 'get_rhso_records_based_on_span',
                    type : 'POST',
                    data : {"start_date" : startDate, "end_date": endDate,"rhso_mail":rhso},
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
          $('#sanitation_filters').html('<h4>No Data Available For this Doctor....</h4>');
      }
        else{

            data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic"><thead><tr><th>School Name</th><th>Campus</th><th>Kitchen</th><th>Toilets</th><th>Dormitory</th><th>Status</th><th>Submitted By</th></tr></thead><tbody>';

              $.each(result, function(index, value){

              data_table = data_table+'<tr>';

             /* data_table = data_table+'<td>'+this.School Name+'</td>';
              data_table = data_table+'<td>'+this.Campus['Description']+'</td>';
              data_table = data_table+'<td>'+this.Kitchen['Description']+'</td>';
              data_table = data_table+'<td>'+this.Toilets['Description']+'</td>';          
              data_table = data_table+'<td>'+this.Dormitory['Description']+'</td>';
              data_table = data_table+'<td>'+this.history['status']+'</td>';
              data_table = data_table+'<td>'+this.history['rhso_name']+'</td>';
              data_table = data_table+'</tr>';*/
             
              var data = "";
            
                 data_table = data_table+'<td>'+value['School Name']+'</td>';
                 if(value.Campus != undefined)
                 {
                    data_table = data_table +'<td>'+value.Campus['Description']+'</td>';
                 }else{
                  data_table = data_table +'<td> No Data </td>';
                 }
                 if(value.Kitchen != undefined)
                 {
                    data_table = data_table +'<td>'+value.Kitchen['Description']+'</td>';
                 }else{
                  data_table = data_table +'<td> No Data </td>';
                 }
                 if(value.Toilets != undefined)
                 {
                    data_table = data_table +'<td>'+value.Toilets['Description']+'</td>';
                 }else{
                  data_table = data_table +'<td> No Data </td>';
                 }
                 if(value.Dormitory != undefined)
                 {
                    data_table = data_table +'<td>'+value.Dormitory['Description']+'</td>';
                 }else{
                  data_table = data_table +'<td> No Data </td>';
                 }               
             
              data_table = data_table+'<td>'+this.history['status']+'</td>';
              data_table = data_table+'<td>'+this.history['rhso_name']+'</td>';

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
