<?php $current_page = "RHSO table"; ?>
<?php $main_nav = ""; ?>
<?php include('inc/header_bar.php'); ?>


<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">

<br>
<br>
<br>
<section class="">
  <div class="container-fluid">
        <div class="block-header">
            
        </div>
        <!-- Basic Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">                      
                       
                      <br> 
                      <div class="row">  

                        <div class="col-sm-4">
                                 <h3 class="font-bold col-green">L3 Help Line Districts Wise Information</h3>
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
                      <div class="form-line">
                            <button type="button" id="filter_search" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
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
                      <div id="get_rhso_data_table"></div>
                      <div id="sanitation_filters"></div>
                    </div>

                      <div class="body">
                <div class="table-responsive" id="table_hide">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>  
                                <th>Patient Name</th>
                                <th>Age</th>
                                <th>District</th>
                                <th>Place</th>
                                <th>Contact Number</th>
                                <th>Patient Illeness</th>
                                <th>Doctor Advice</th>
                                <th>Date</th>
                                <th>Call Received By</th>
                            </tr>
                        </thead>
                        <tbody>
              <?php foreach ($l3_districts as $index => $doc ):?>
              <tr>    
                <td><?php echo $doc['doc_data']['patient_name'] ;?></td>
                <td><?php echo $doc['doc_data']['age'] ;?></td>
                <td><?php echo $doc['doc_data']['district'] ;?></td>              
                <td><?php echo $doc['doc_data']['place'] ;?></td>              
                <td><?php echo $doc['doc_data']['contact_number'] ;?></td>               
                <td><?php echo $doc['doc_data']['patient_illeness'] ;?></td>              
                <td><?php echo $doc['doc_data']['doctor_advice'] ;?></td>              
                <td><?php echo $doc['doc_data']['date'] ;?></td>              
                <td><?php echo $doc['doc_data']['call_receiver'] ;?></td>
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

<!-- Moment Plugin Js -->
<script src="<?php echo MDB_PLUGINS."momentjs/moment.js"; ?>"></script>

<!-- Custom Js -->
<script src="<?php echo(MDB_JS.'admin.js'); ?>"></script>
<script src="<?php echo(MDB_JS.'pages/forms/basic-form-elements.js'); ?>"></script>

<!-- Demo Js -->
<script src="<?php echo(MDB_JS.'demo.js'); ?>"></script>

<!-- Bootstrap Datepicker Plugin Js -->
<script src="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script>

<?php include('inc/footer_bar.php'); ?>

<script type="text/javascript">

  var today_date = $('#set_date').val();
  $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
  $('#set_date').change(function(e){
          today_date = $('#set_date').val();
  });  

        $('#filter_search').click(function(){

            var district_name  = $('#select_dt_name').val(); 
                                 
                $.ajax({
                    url : 'get_l3_help_line_data_based_on_span',
                    type : 'POST',
                    data : {"district" : district_name},
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

   if(result == "No Data Available"){
          $('#sanitation_filters').html('<h4>No Data Available ......</h4>');
      }
        else{

            data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic"><thead><tr><th>Patient Name</th><th>Age</th><th>District</th><th>Place</th><th>Contact Number</th><th>Patient Illeness</th><th>Doctor Advice</th><th>Date</th><th>Call Received By</th></tr></thead><tbody>';
           
              $.each(result, function(){

                $('#show_when_serach').show();

              data_table = data_table+'<tr>'; 

              data_table = data_table+'<td>'+this.doc_data['patient_name']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['age']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['district']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['place']+'</td>';               
              data_table = data_table+'<td>'+this.doc_data['contact_number']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['patient_illeness']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['doctor_advice']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['date']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['call_receiver']+'</td>';
               
              });
          
            data_table = data_table+'</tbody></table>';

            $('#get_rhso_data_table').html(data_table);

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

  

</script>