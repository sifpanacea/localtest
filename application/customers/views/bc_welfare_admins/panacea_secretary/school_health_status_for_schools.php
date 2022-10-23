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
                <input type="hidden" name="abnormality" id="abnormality" value="<?php echo $abnormality; ?>">
                <input type="hidden" name="district" id="district" value="<?php echo $district; ?>">
                <input type="hidden" name="zone" id="zone" value="<?php echo $zone; ?>">
                   <div class="card">
                    <div class="header">
                              <div class="row clearfix">
                                 <div class="col-sm-2">
                                    <h3 class="font-bold col-green"><?php echo $zone; ?> Data</h3>
                                 </div>
                                <!--  <div class="collapse" id="multicollapseExample">
                                   <div class="col-sm-2">
                                     <?php $end_date  = date ( "Y-m-d", strtotime ( $end . "-90 days" ) ); ?>
                                        <span id="monitoring_datepicker">
                                         Start Date :
                                        <input type="text" id="passing_date" name="passing_date" class="form-control date" value="<?php echo $start; ?>">
                                        </span>
                                   </div>
                                   <div class="col-sm-2">
                                        <span id="monitoring_datepicker">
                                        End Date :
                                        <input type="text" id="passing_end_date" name="passing_end_date" class="form-control date" value="<?php echo $end; ?>">
                                        </span>
                                   </div>
                                   <div class="col-sm-2">
                                         Select Required:
                                        <div class="form-line">
                                            <input type="text" id="no_of_requests" name="no_of_requests" class="form-control" value="3" > 
                                          <select class="form-control" id="no_of_requests" name="no_of_requests">
                                            <option value="Animals">Admitted</option>
                                            <option value="Rarely">Review</option>
                                            <option value="Rarely">Patient-out</option>
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
                                 </div> -->
                                 <!-- <div class="col-sm-2 pull-right">
                                    <ul class="header-dropdown m-r--5">
                                       <a class="btn bg-green waves-effect" data-toggle="collapse" href="#multicollapseExample" aria-expanded="false" title="Date Filters"
                                              aria-controls="multicollapseExample">
                                           <i class="material-icons">more_vert</i>
                                       </a>
                                   </ul>
                                 </div> -->
                             </div>
                             <ul class="header-dropdown m-r--5">
                                  <div class="button-demo">
                                  <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                                  </div>
                              </ul>  
                        </div>
                     <!-- <div class="header">
                              <div class="row">
                                 <div class="col col-sm-4">
                                     <h5 class="font-bold col-green">Sanitation Data.</h5>
                                 </div>
                                <div class="col col-sm-2">
                                  <?php $end_date  //= date ( "Y-m-d", strtotime ( $today_date . "-90 days" ) ); ?>
                                     <span id="monitoring_datepicker">
                                      Start Date :
                                     <input type="text" id="passing_date" name="passing_date" class="form-control date" value="<?php //echo $end_date; ?>">
                                     </span>
                                 </div>
                                
                                
                                 <div class="col col-sm-2">
                                     <span id="monitoring_datepicker">
                                     End Date :
                                     <input type="text" id="passing_end_date" name="passing_end_date" class="form-control date" value="<?php //echo $today_date; ?>">
                                     </span>
                                 </div> 
                            <div class="col-sm-2">
                             <h2 class="card-inside-title">Select Required</h2>
                             <div class="form-line">
                                <input type="text" id="no_of_requests" name="no_of_requests" class="form-control" value="3" >
                               <select class="form-control" id="no_of_requests" name="no_of_requests">
                                 <option value="Animals">Animals around campus</option>
                                 <option value="Rarely">Rarely submitting schools</option>
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
                     </div> -->
                               <div class="body">
                                  <div id="students_more_req"></div>
                                  <div id="sanitation_filters"></div>


                                  <div id="show_when_serach" style="display: none;">
                                      <form  action="send_text_message_sanitation" method="POST" id="request_form">
                                       <div class="row clearfix">
                                          <div class="col-sm-3">
                                            <p>
                                                <b>Type Message</b>
                                            </p>
                                            <textarea name="message" id="select_hospital"></textarea>
                                            
                                          </div>
                                          
                                            <div class="col-sm-3">
                                              <p>
                                                  <b>Select Date for Schedule</b>
                                              </p>
                                              <div class="form-line">
                                                  <input type="text" id="set_date" name="set_date" class="form-control date" value="" placeholder="Please choose a date...">
                                              </div>
                                          </div>
                                        
                                          <div class="col-sm-3">
                                             <br><br>
                                             <button type="button" class="btn btn-primary waves-effect" id="submit_request" >Submit</button>
                                          </div>
                                          <input type="hidden" id="ehr_data_for_request" name="ehr_data_for_request" value=""/>
                                      </div> 
                                       
                                      </form>
                                  </div>
                               </div>
                           </div>
                    </div>
            </div>
            
             <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="loading_modal">
              <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content" id="loading">
                <center>
                  <div class="card">
                    <img src="<?php echo(IMG.'loader.gif'); ?>" id="gif" >
                    <h4>It Will take some time to load Please Wait</h4>
                    <div class="body">
                      <?php if(!empty($zone)): ?>
                        <?php if($zone == 'Red Zone'): ?>
                          <h5>Criteria For <?php echo $zone; ?></h5>
                          <table style="width:100%">
                            <tr>
                              <th>Scabies:</th>
                            </tr>
                            <tr>
                              <td>Greaterthan 5</td>
                            </tr>
                          </table>
                        <?php endif; ?>
                        
                      <?php endif; ?>
                    </div>
                  </div>
                </center>
                </div>
              </div>
            </div>
           
        </div>
  <!--   </section>
 -->
 
  <form style="display: hidden" action="<?php echo URL; ?>panacea_mgmt/get_students_for_admitted" method ="POST" id="get_students_for_admitted">
    <input type="hidden" id="start_date_new" name="start_date_new" value=""/>
    <input type="hidden" id="end_date_old" name="end_date_old" value=""/>
    <input type="hidden" id="scl_name" name="scl_name" value=""/>
    <input type="hidden" id="requests_admit" name="request_types" value=""/>
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
      
      var abnormal = $('#abnormality').val();
      var dist = $('#district').val();
      var zone = $('#zone').val();

      $("#loading_modal").modal('show');
     /* 
      ('#students_more_req').show();
      ('#sanitation_filters').hide();*/
        $.ajax({
            url : 'get_school_health_status_zone_schools_list',
            type : 'POST',
            data : {"opt_selected" : abnormal, "dist_id": dist, "value_scl_zone":zone},
            success : function(data){

              $("#loading_modal").modal('hide');
                
              var result = $.parseJSON(data);

              data_table_for_filters(result);

              /*if(sanitation == 'Submitted'){
                 console.log(result);
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
                    url : 'get_not_working_sanitation_schools_data',
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

            data_table = '<table class="table table-bordered" id="more_requests"><thead><tr><th>School Name</th><th>Campus</th><th>Kitchen & Wellness center</th><th>Toilets</th><th>Show Photos</th></tr></thead><tbody>';

           $.each(result, function(){

                data_table = data_table+'<tr>';
                data_table = data_table+'<td>'+this.School_Name +'</td>';

                var cam = this.Campus['Cleanliness Of Campus Times'];
                var campus1 = this.Campus['Cleanliness Of Campus'];
                var campus2 = this.Campus['Cleanliness Of Campus Times'];
                var campus3 = this.Campus['Animals Around Campus'];
                var campus4 = this.Campus['Type Of Animal'];
                var campus5 = this.Campus['Other Animal Name'];

                if(cam == ''){

                  data_table = data_table+'<td><span class="label bg-red" data-toggle="popover" data-trigger="hover" data-placement="top" title="Campus Red zone Criteria" data-content="Cleanliness Of Campus:'+campus1 +' <br> Cleanliness Of Campus Times:'+campus2+'<br> Animals Around Campus:'+campus3+'<br> Type Of Animal:'+campus4+'<br> Other Animal Name:'+campus5+'">Red Zone</span></td>';

                }else if(cam == 'Twice' || cam == 'Once'){ 
                      
                      data_table = data_table+'<td><span class="label bg-orange" data-trigger="hover" data-toggle="popover" data-placement="top" title="Campus Orange zone Criteria" data-content="Cleanliness Of Campus:'+campus1 +' <br> Cleanliness Of Campus Times:'+campus2+'<br> Animals Around Campus:'+campus3+'<br> Type Of Animal:'+campus4+'<br> Other Animal Name:'+campus5+'" >Orange Zone</span></td>';


                }else if(cam == 'Thrice'){
                  data_table = data_table+'<td><span class="label bg-green" data-toggle="popover" data-trigger="hover" data-placement="top" title="Campus Green zone Criteria" "Cleanliness Of Campus:'+campus1 +' <br> Cleanliness Of Campus Times:'+campus2+'<br> Animals Around Campus:'+campus3+'<br> Type Of Animal:'+campus4+'<br> Other Animal Name:'+campus5+'">Green Zone</span></td>';
                }

                var kit = this.Kitchen['Cleanliness Of The Kitchen Place In A Day'];
                var kit1 = this.Kitchen['Cleanliness Toilets or Bathrooms'];
                var kit2 = this.Kitchen['Daily Menu Followed'];
                var kit3 = this.Kitchen['Utensils Cleanliness'];
                var kit4 = this.Kitchen['Dining Hall Cleanliness'];
                var kit5 = this.Kitchen['page2_Cleanliness_DiningHalls'];
                var kit6 = this.Kitchen['Hand Gloves Used By Serving People'];
                var kit7 = this.Kitchen['Staffmembers Tasty Food Before Serving Meals'];
                var kit8 = this.Kitchen['Wellness Centre Cleanliness'];
               
                var well = this.Kitchen['Cleanliness Of The Wellness Centre'];


                if((kit == 'Once' && well == 'Once') ||(kit == 'Twice' && well == 'Twice')){
                 data_table = data_table+'<td><span class="label bg-orange" data-toggle="popover" data-trigger="hover" data-placement="top" title="Kitchen & Wellness Orange Zone Criteria" data-content="Cleanliness Of The Kitchen Place In A Day:'+kit+'<br>Cleanliness Toilets or Bathrooms:'+kit1+'<br>Daily Menu Followed:'+kit2+'<br>Utensils Cleanliness:'+kit3+'<br>Dining Hall Cleanliness:'+kit4+'<br>Cleanliness Of DiningHalls:'+kit5+'<br>Hand Gloves Used By Serving People:'+kit6+'<br>Staff Members Tasting Food Before Serving Meals:'+kit7+'<br>Wellness Centre Cleanliness:'+kit8+'">Orange Zone</span></td>';

                }else if(kit == 'Thrice' && well == 'Thrice'){
                   data_table = data_table+'<td><span class="label bg-green" data-toggle="popover" data-trigger="hover" data-placement="top" title="Kitchen & Wellness Green Zone Criteria" data-content="Cleanliness Of The Kitchen Place In A Day:'+kit+'<br>Cleanliness Toilets or Bathrooms:'+kit1+'<br>Daily Menu Followed:'+kit2+'<br>Utensils Cleanliness:'+kit3+'<br>Dining Hall Cleanliness:'+kit4+'<br>Cleanliness Of DiningHalls:'+kit5+'<br>Hand Gloves Used By Serving People:'+kit6+'<br>Staff Members Tasting Food Before Serving Meals:'+kit7+'<br>Wellness Centre Cleanliness:'+kit8+'">Green Zone</span></td>';

                }else if(kit == '' || well == ''){
                  data_table = data_table+'<td><span class="label bg-red" data-toggle="popover" data-trigger="hover" data-placement="top" title="Kitchen & Wellness Red Zone Criteria" data-content="Cleanliness Of The Kitchen Place In A Day:'+kit+'<br>Cleanliness Toilets or Bathrooms:'+kit1+'<br>Daily Menu Followed:'+kit2+'<br>Utensils Cleanliness:'+kit3+'<br>Dining Hall Cleanliness:'+kit4+'<br>Cleanliness Of DiningHalls:'+kit5+'<br>Hand Gloves Used By Serving People:'+kit6+'<br>Staff Members Tasting Food Before Serving Meals:'+kit7+'<br>Wellness Centre Cleanliness:'+kit8+'">Red Zone</span></td>';
                }

                var toi = this.Toilets['Cleanliness Toilets or Bathrooms In A Day'];
                var toi1 = this.Toilets['Cleanliness Toilets or Bathrooms'];
                var toi2 = this.Toilets['Cleanliness Toilets or Bathrooms In A Day'];
                var toi3 = this.Toilets['Any Damages To The Toilets'];

                if(toi == 'Once'){
                  data_table = data_table+'<td><span class="label bg-red" data-toggle="popover" data-trigger="hover" data-placement="top" title="Toilets Red Zone Criteria" data-content="Cleanliness Toilets or Bathrooms:'+toi1+'<br> Cleanliness Toilets or Bathrooms In A Day:'+toi2+'<br> Any Damages To The Toilets:'+toi3+'">Red Zone</span></td>';

                }else if(toi == 'Twice'){
                  data_table = data_table+'<td><span class="label bg-orange" data-toggle="popover" data-trigger="hover" data-placement="top" title="Toilets Orange Zone Criteria" data-content="Cleanliness Toilets or Bathrooms:'+toi1+'<br> Cleanliness Toilets or Bathrooms In A Day:'+toi2+'<br> Any Damages To The Toilets:'+toi3+'">Orange Zone</span></td>';

                }else if(toi == 'Thrice'){
                  data_table = data_table+'<td><span class="label bg-green" data-toggle="popover" data-trigger="hover" data-placement="top" title="Toilets Green Zone Criteria" data-content="Cleanliness Toilets or Bathrooms:'+toi1+'<br> Cleanliness Toilets or Bathrooms In A Day:'+toi2+'<br> Any Damages To The Toilets:'+toi3+'">Green Zone</span></td>';
                }

              data_table = data_table+'<td><button class="btn bg-green btn-sm waves-effect ehrButton" data-toggle="modal" data-target="#photos_for_sani">Photos</button></td></tr>';
            });
            data_table = data_table+'</tbody></table>';

            $('#students_more_req').html(data_table);

              $('#more_requests').DataTable({
                "paging": true,
                "lengthMenu" : [5, 25, 50, 75, 100]
              });
        }

	$(document).ready(function(){
            $('[data-toggle="popover"]').popover({html: true});   
        });

         $("#more_requests").each(function(){
                $('.ehrButton').click(function (){
                     var currentRow=$(this).closest("tr"); 
                     var studentHealthID=currentRow.find("td:eq(0)").text(); // get current row 2nd TD
                    //alert(studentHealthID);
                    $("#more_req_studentHealthID").val(studentHealthID);
                    $("#more_req_students_form").submit();
                });

            });

     };


    function data_table_for_filters(result){

      if(result){

            data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic"><thead><tr><th>School Name</th><th>Count</th><th>Respond '+'<input type="checkbox" id="select_all_chk" class="filled-in chk-col-red">'+
                                    '<label for="select_all_chk">Select All</label>'+'</th></tr></thead><tbody>';
            /*if(requestsCount == 'Animals'){*/
              $.each(result, function(index, value){

                $('#show_when_serach').show();
              data_table = data_table+'<tr>';
              data_table = data_table+'<td>'+value.scl +'</td>';
              data_table = data_table+'<td>'+value.count +'</td>';
              //data_table = data_table+'<td>'+'<button class="btn btn-primary" id="show_studs">Show Students</button>'+'</td>';

              data_table = data_table+'<td>'+'<div class="demo-checkbox">'+
                                                '<input type="checkbox" name="checkboxName[]" id="'+value.scl+'" class="filled-in chk-col-red" />'+
                                                '<label for="'+value.scl+'">Send a message</label>'+
                                            '</div>'+'</td>';


                
              });
            /*}*//*else if(requestsCount == 'Washrooms Required' || requestsCount == 'Not Submitted'){
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
                "lengthMenu" : [5, 25, 50, 75, 100]
              });

              $("#dt_basic").each(function(){
                    $('#show_studs').click(function (){

                      var currentRow=$(this).closest("tr"); 
                      var sclName = currentRow.find("td:eq(0)").text();
                      var starts = $('#start').val();
                      var ends = $('#end').val();
                      var type = $('#request').val();

                     
                            
                      $("#start_date_new").val(starts);
                      $("#end_date_old").val(ends);
                      $("#scl_name").val(sclName);
                      $("#requests_admit").val(type);
                        //$("#request_type_status").val(request_type_status);
                      $("#get_students_for_admitted").submit();
                         
                    });
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

  





