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
                       
                        <ul class="header-dropdown m-r--5">
                            <div class="button-demo">
                            <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                            </div>
                        </ul>
                      <br> 
                      <div class="row">  

                        <div class="col-sm-4">
                                 <h3 class="font-bold col-green">Organ Transplantation Hospitals Details</h3>
                        </div>

                         <div class="col-sm-2">
                            <label></label>
                            <select class="form-control show-tick organ_name_select" id="organ_name_select">
                                <option value="Choose" selected="0" >Select Organ Name</option>
                                <option value="All" >All</option>
                                <option value="Heart" >Heart</option>
                                <option value="Eye" >Eye</option>
                                <option value="Bone Marrow" >Bone Marrow</option>
                                <option value="Kidney" >Kidney</option>
                                <option value="Liver" >Liver</option>
                                <option value="Lungs" >Lungs</option> 
                            </select>
                        </div>                        

                        <div class="col-sm-1">  
                          <button type="button" id="organ_search" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float" style="margin-top: 8px;">
                                <i class="material-icons">search</i>
                            </button>
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
                                <th>Transplantation Hospital Name</th>
                                <th>Contact Number</th>
                                <th>Organ Name</th>
                                <th>Address</th>
                                <th>District</th>
                               
                            </tr>
                        </thead>
                        <tbody>
              <?php foreach ($organ_details as $index => $doc ):?>
              <tr>    
                <td><?php echo $doc['doc_data']['hospital name'] ;?></td>
                <td><?php echo $doc['doc_data']['contact number'] ;?></td>
                <td><?php echo $doc['doc_data']['organ name'] ;?></td>              
                <td><?php echo $doc['doc_data']['address'] ;?></td>               
                <td><?php echo $doc['doc_data']['district'] ;?></td>              
                
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

        $('#organ_search').click(function(){

            var organ_name  = $('#organ_name_select').val(); 
           
                $.ajax({
                    url : 'get_organ_name_hospital_based_on_span',
                    type : 'POST',
                    data : {"organ" : organ_name},
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

            data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic"><thead><tr><th>Transplantation Hospital Name</th><th>Contact Number</th><th>Organ Name</th><th>Address</th><th>District</th></tr></thead><tbody>';
           
              $.each(result, function(){

                $('#show_when_serach').show();

              data_table = data_table+'<tr>'; 

              data_table = data_table+'<td>'+this.doc_data['hospital name']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['contact number']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['organ name']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['address']+'</td>';               
              data_table = data_table+'<td>'+this.doc_data['district']+'</td>';
               
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