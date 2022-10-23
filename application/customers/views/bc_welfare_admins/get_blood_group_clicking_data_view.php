<?php $current_page = "RHSO table"; ?>
<?php $main_nav = ""; ?>
<?php include('inc/header_bar.php'); ?>


<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">

<br>
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

                        <div class="col-sm-3">
                            <label>District</label>
                            <select class="form-control show-tick blood_dist" id="blood_dist">
                                <option value="All" >All</option>
                                <?php if(isset($distslist)): ?>
                                    <?php foreach ($distslist as $dist):?>
                                        <option value='<?php echo $dist['dt_name']?>'><?php echo ucfirst($dist['dt_name'])?></option>
                                    <?php endforeach;?>
                                <?php else: ?>
                                    <option value="1"  disabled="">No district entered yet</option>
                                <?php endif ?>
                            </select>

                        </div> 

                         <div class="col-sm-3">

                            <label>Blood Group</label>

                            <select class="form-control show-tick blood_group_select" id="blood_group_select">

                                <option value="All" selected="0" >All</option>
                                <option value="A+ve" >A+ve</option>
                                <option value="A-ve" >A-ve</option>
                                <option value="AB+ve" >AB+ve</option>
                                <option value="AB-ve" >AB-ve</option>
                                <option value="B+ve" >B+ve</option>
                                <option value="B-ve" >B-ve</option>
                                <option value="O+ve" >O+ve</option>
                                <option value="O-ve" >O-ve</option>
                               
                            </select>

                        </div> 
                       

                        <div class="col-sm-1">  
                            <button type="button" id="rhso_hospital_visit_search" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float" style="margin-top: 8px;">
                                <i class="material-icons">search</i>
                            </button>
                        </div>

                           <!--  <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button> -->

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
                                <th>Donor Name</th>
                                <th>Contact Number</th>
                                <th>Blood Group</th>
                                <th>District</th>
                                <th>Address</th>
                               
                            </tr>
                        </thead>
                        <tbody>
              <?php foreach ($blood_group_details as $index => $doc ):?>
              <tr>    
                <td><?php echo $doc['doc_data']['donor name'] ;?></td>
                <td><?php echo $doc['doc_data']['primary contact'] ;?></td>
                <td><?php echo $doc['doc_data']['blood group'] ;?></td>              
                <td><?php echo $doc['doc_data']['district'] ;?></td>               
                <td><?php echo $doc['doc_data']['address'] ;?></td>              
                
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

  change_request_type();

  for_dist_select();

  function for_dist_select()
  {
    var dis_sele = $('#dist').val();

    $('#blood_dist option[value='+dis_sele+']').prop('selected', 'selected').change();

    var options = $("#from_val").val();
   

  if( options == "hospital_visit")
  {
    var typee= JSON.stringify($('#type_selected').val());
   
    $('.subtype option[value='+typee+']').prop('selected', 'selected').change();
    
  }
  else
  {
    var typee= $('#type_selected').val();
   
    $('.subtype option[value='+typee+']').prop('selected', 'selected').change();
  }
  }
   

  $('#request_type').change(function(){
    change_request_type();
  });

  function change_request_type(){
    var type = $('#from_val').val();    
    var options = $('.subtype');
    
  }

  blood_group_wise_data_information();

  $("#rhso_hospital_visit_search").click(function(){
      blood_group_wise_data_information();
  });

   function blood_group_wise_data_information()
   {

      var blood_group_district = $('#blood_dist').val();

      var blood_group_name = $('#blood_group_select').val();



        $.ajax({

         url : 'get_blood_groupwise_data_based_on_selected',
         type : 'POST',
         data:{ 'district' : blood_group_district, 'blood': blood_group_name },
         success:function(data){

            var result = $.parseJSON(data);
            //console.log(fetch_data);
            blood_group_wise_table(result, blood_group_name);
            $('#table_hide').hide();
            $('#show_when_serach').show();
         }

        });      
      
   }

  function blood_group_wise_table(result, blood_group_name){

   if(result == "No Data Available"){
          $('#sanitation_filters').html('<h4>No Data Available For this Doctor....</h4>');
      }
        else{

            data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic"><thead><tr><th>Donor Name</th><th>Contact Number</th><th>Blood Group</th><th>District</th><th>Address</th></tr></thead><tbody>';
           
              $.each(result, function(){

                $('#show_when_serach').show();

              data_table = data_table+'<tr>'; 

              data_table = data_table+'<td>'+this.doc_data['donor name']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['primary contact']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['blood group']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['district']+'</td>';
              data_table = data_table+'<td>'+this.doc_data['address']+'</td>';               
               
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