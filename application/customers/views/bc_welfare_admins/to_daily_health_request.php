<?php $current_page=""; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<br>
<br>
<br>
<br>
<br>

<!-- Code for data tables -->
<section class="">
<div class="container-fluid">
<!-- Input -->
    <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
           
                    <div class="card">
                      <div class="header">
                          <div class="row clearfix">
                              <div class="col-sm-2">
                                 <h3 class="font-bold col-green">Dr. Responded data</h3>
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
                                       <h2 class="card-inside-title">Select Dr.</h2>
                                        <div class="form-line">
                                        <select class="form-control" id="no_of_requests" name="no_of_requests">
                                          <?php if(isset($doctor_name) && !empty($doctor_name)): ?>
                                          <?php foreach($doctor_name as $doctors): ?>
                                            <option value="<?php echo $doctors['email']; ?>"><?php echo $doctors['username']; ?></option>
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
                                    <div class="col-sm-2">
                                       <div class="form-line">
                                          <button type="button" id="get_excel" class="btn bg-green btn-sm waves-effect">Get Excel</button>
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
             
            <div class="body" id="table_hide">
                <div class="table-responsive" >
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>Unique ID</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Disease type</th>
                                <th>Request Raised Time</th>
                                <th>Doctor Response Time</th>
                                <th>Doctor Name</th>
                                <th>Attachments</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
							<?php foreach ($students_details as $index => $doc ):?>
							<tr>
								<td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Unique ID'] ;?></td>
								<td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Name']['field_ref'] ;?></td>
								<td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Class']['field_ref'] ;?></td>

								    <?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Normal"):?>
									<?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'];?>

								<td><span class="badge bg-green">
									<?php foreach ($identifiers as $identifier => $values) :?>
										<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]); ?>
										<?php if(!empty($var123)):?> 
											<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]) : "No Identifier";?>

										<?php endif;?>
									<?php endforeach;?>												
									</span>
								</td>

							 		<?php elseif($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Emergency"):?>
						            <?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'];?>

						        <td><span class="badge bg-red">
						            <?php foreach ($identifiers as $identifier => $values) :?>
						                <?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]); ?>
						                <?php if(!empty($var123)):?> 
				      						<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]) : "No Identifier";?>
						            	<?php endif;?>
						    		<?php endforeach;?>
						    		</span>
						    	</td>


									<?php elseif($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Chronic"):?>
							        <?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'];?>

								<td><span class="badge bg-amber">
									<?php foreach ($identifiers as $identifier => $values) :?>
						 				<?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]); ?>
										<?php if(!empty($var123)):?> 
											<?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]) : "No Identifier";?>
										<?php endif;?>
									<?php endforeach;?>
									</span>
								</td>

									<?php endif;?>
							
								<td> <?php echo $doc['history'][0]['time'];?></td>

									<?php $last_doc = end($doc['history']); 
									if(preg_match("/bcwelfare.dr/i",$last_doc['submitted_by'])):?>

							    <td><?php echo $last_doc['time'];?></td>
							    <td><?php echo $last_doc['submitted_by_name'];?></td>
									<?php else:?>

								<td><?php echo "Nill";?></td>
								<td><?php echo "Doctor not to yet responded";?></td>

								<?php endif;?>

									<?php if(isset($doc['doc_data']['external_attachments']) && !empty($doc['doc_data']['external_attachments'])):?>
								<td class="text-center"><i class="fa fa-paperclip fa-2x" aria-hidden="true"></i></td>

									<?php else:?>
								<td>No Attachments</td>
									<?php endif;?>

							    <td> 
									<a class='ldelete' href='<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_display_ehr_uid/"?>? id = <?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Unique ID'];?>'>
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


<script type="text/javascript">

last_three_months_more_req_students();

    var today_date = $('#set_date').val();
    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#set_date').change(function(e){
            today_date = $('#set_date').val();;
    });

  
     function last_three_months_more_req_students(){
      
      var date_for = $('#date').val();
      var status = $('#status').val();
 
        $.ajax({
            url : 'get_field_officer_wise_submitted_docs',
            type : 'POST',
            data : {"today_date" : date_for, "status_type": status},
            success : function(data){      
                
                var result = $.parseJSON(data);
                data_table_req(result);
           

              }
        });

     }

    $('#date_set').click(function(){
            var startDate = $('#passing_date').val();
            var endDate = $('#passing_end_date').val();
            var requestsCount = $('#no_of_requests').val();
           
           // $("#loading_modal").modal('show');
                $.ajax({
                    url : 'get_dr_submitted_records_based_on_span',
                    type : 'POST',
                    data : {"start_date" : startDate, "end_date": endDate, "request_data": requestsCount},
                    success : function(data){
                      $("#loading_modal").modal('hide');
                        $('#students_more_req').empty();
                        var result = $.parseJSON(data);
                        data_table_for_filters(result, requestsCount);
                        $('#table_hide').hide();
                        $('#show_when_serach').show();
                    }
                });
          

        });

      $('#get_excel').click(function(){
     
      var start = $('#passing_date').val();
      var end = $('#passing_end_date').val();
      var dr_login = $('#no_of_requests').val();
    
      $.ajax({
        
        url:'get_excel_for_dr_responded_reports',
        type:'POST',
        data:{'start_date':start, 'end_date':end ,'no_of_requests': dr_login},
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
          $('#sanitation_filters').html('<h4>No Data Available For this Doctor....</h4>');
      }
        else{

            data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic"><thead><tr><th>Health ID</th><th>Student Name</th><th>Class</th><th>School Name</th><th>Request Raised Time</th><th>Dr. Response time</th><th>Action</th></tr></thead><tbody>';
           
              $.each(result, function(){

              data_table = data_table+'<tr>';
                   
              data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['Unique ID']+'</td>';
              data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['Name']['field_ref']+'</td>';
              data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['Class']['field_ref']+'</td>';
              data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['School Name']['field_ref']+'</td>';
              data_table = data_table+'<td>'+this.history['0']['time']+'</td>'; 

              var last = this.history;             
              var last_doc = last[last.length - 1];             
              data_table = data_table+'<td>'+last_doc['time']+'</td>';
              
              var urlLink = "https://mednote.in/PaaS/healthcare/index.php/";
              
              data_table = data_table + '<td><a class="btn btn-primary btn-xs" href="'+urlLink+'panacea_mgmt/panacea_reports_display_ehr_uid/?id = '+this.doc_data.widget_data.page1['Student Info']['Unique ID']+'">Show EHR</a></td>';
               
              });
          
            data_table = data_table+'</tbody></table>';

            $('#sanitation_filters').html(data_table);

              $('#dt_basic').DataTable({
                "paging": true,
                "lengthMenu" : [20, 50, 100 ,250 ,500]
              });

           /* $("#select_all_chk").click(function(){
              
                var oTable = $('#dt_basic').dataTable();
                var allPages = oTable.fnGetNodes();
                $('input[type="checkbox"]', allPages).prop('checked', $(this).is(':checked'));
            });*/
        }

    };


 /*$("#submit_request").click(function(){
     
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
