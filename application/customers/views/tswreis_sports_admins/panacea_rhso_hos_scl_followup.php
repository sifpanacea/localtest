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

                        <h2> RHSO <?php echo $from; ?></h2>
                        <ul class="header-dropdown m-r--5">
                            <div class="button-demo">
                            <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                            </div>
                        </ul>
                      <br> 
                      <div class="row">
                        <div class="col-sm-2"> 
                         <label>Start date</label>
                            <?php $d =strtotime("-1 Months"); ?>
                            <input type="text" id="start_date_rhso" name="start_date_rhso" class="datepicker form-control date start_date_rhso" value="<?php echo $start_date; ?>">
                        </div>    
                        <div class="col-sm-2">
                            <label>End date</label>
                            <input type="text" id="end_date_rhso" name="end_date_rhso" class="datepicker form-control date end_date_rhso" value="<?php echo $end_date; ?>">
                        </div> 

                        <div class="col-sm-3">
                            <label>District</label>
                            <select class="form-control show-tick hopistal_dist" id="hopistal_dist">
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
                          <label>Select</label>
                          <select class="form-control show-tick subtype" id="subtype">
                            
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
                    </div>
                </div> 
            </div>
        </div>
    </div>


    <input type="hidden" name="from_val" id="from_val" value="<?php echo $from; ?>">
    <input type="hidden" name="dist" id="dist" value="<?php echo $common_district; ?>">
    <input type="hidden" name="start_date" id="start_date" value="<?php echo $start_date; ?>">
    <input type="hidden" name="end_date" id="end_date" value="<?php echo $end_date; ?>">
    <input type="hidden" name="type_selected" id="type_selected" value="<?php echo $type_selected; ?>">
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

    $('#hopistal_dist option[value='+dis_sele+']').prop('selected', 'selected').change();

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

    //$('#hopistal_dist option[value="Hyderabad"]').attr("selected", "selected");

    
    //$('.hopistal_dist select>option:eq("Hyderabad")').prop('selected', true);
    //$('#hopistal_dist [value="Hyderabad"]').attr('selected', 'true');

  $('#request_type').change(function(){
    change_request_type();
  });

  function change_request_type(){
    var type = $('#from_val').val();
    
    var options = $('.subtype');

    if( type == "hospital_visit")
    {
      options.empty();
      options.append($("<option />").val("Emergency or Admitted").text("Emergency or Admitted"));
      options.append($("<option />").val("Out Patients").text("Out Patients"));
      options.append($("<option />").val("Review Cases").text("Review Cases"));
    }
    
    else
    {
      options.empty();
      options.append($("<option />").val("Red").text("Red"));
      options.append($("<option />").val("Orange").text("Orange"));
      options.append($("<option />").val("Green").text("Green"));
      options.append($("<option />").val("Empty").text("Empty"));
    }
  }

  rhso_hospital_visits_report();

  $("#rhso_hospital_visit_search").click(function(){
      rhso_hospital_visits_report();
  });


   function rhso_hospital_visits_report()
   {
      var start = $("#start_date_rhso").val();
      var end = $("#end_date_rhso").val();
      var dist = $("#hopistal_dist").val();
      var selected = $(".subtype").val();
      var from = $("#from_val").val();

        $.ajax({

         url : 'get_rhso_data_based_on_selected',
         type : 'POST',
         data :{'start_date':start, 'end_date':end, 'district':dist, 'select_type':selected, 'get_from':from},
         success:function(data){
            var fetch_data = $.parseJSON(data);
            //console.log(fetch_data);

            rhso_hospital_data_table(fetch_data, from);
         }

        });
      
      
   }

  function rhso_hospital_data_table(fetch_data, from)
  {
    
    if(from == "hospital_visit")
    {
      data_table = '<table id="more_requests" class="table table-striped table-bordered" width="100%"><thead><tr><th>Unique ID</th><th>Student Name</th><th>Hospital Name</th><th>Investigation</th><th>Medication</th><th>Review Date</th><th>Problem Info</th><th>Action</th></tr></thead><tbody>';

      $.each(fetch_data, function(){
        data_table = data_table+'<tr>';
        data_table = data_table+'<td>'+this.doc_data.widget_data['Student Details']['Hospital Unique ID']+'</td>';
        data_table = data_table+'<td>'+this.doc_data.widget_data['Student Details']['Name']+'</td>';

        var typeReq = this.doc_data.widget_data['type_of_request'];

        
        if(typeReq == "Review Cases")
        {
          data_table = data_table+'<td>'+this.doc_data.widget_data['Review Cases']['hospialt_name']+'</td>';
          data_table = data_table+'<td>'+this.doc_data.widget_data['Review Cases']['investigations']+'</td>';
          data_table = data_table+'<td>'+this.doc_data.widget_data['Review Cases']['medication']+'</td>';
          data_table = data_table+'<td>'+this.doc_data.widget_data['Review Cases']['review_date']+'</td>';
          data_table = data_table+'<td>'+this.doc_data.widget_data['Review Cases']['patient_details']+'</td>';
        }else if(typeReq == "Emergency or Admitted"){
          data_table = data_table+'<td>'+this.doc_data.widget_data['Emergency or Admitted']['hospialt_name']+'</td>';
          data_table = data_table+'<td>'+this.doc_data.widget_data['Emergency or Admitted']['investigations']+'</td>';
          data_table = data_table+'<td>'+this.doc_data.widget_data['Emergency or Admitted']['medication']+'</td>';
          data_table = data_table+'<td>'+this.doc_data.widget_data['Emergency or Admitted']['review_date']+'</td>';
          data_table = data_table+'<td>'+this.doc_data.widget_data['Emergency or Admitted']['patient_details']+'</td>';
        }else if(typeReq == "Out Patients"){
          data_table = data_table+'<td>'+this.doc_data.widget_data['Out Patient']['hospialt_name']+'</td>';
          data_table = data_table+'<td>'+this.doc_data.widget_data['Out Patient']['investigations']+'</td>';
          data_table = data_table+'<td>'+this.doc_data.widget_data['Out Patient']['medication']+'</td>';
          data_table = data_table+'<td>'+this.doc_data.widget_data['Out Patient']['review_date']+'</td>';
          data_table = data_table+'<td>'+this.doc_data.widget_data['Out Patient']['patient_details']+'</td>';
        }

        var urlLink = "https://mednote.in/PaaS/healthcare/index.php/";
        var obj = Object.values(this['_id']);
        data_table = data_table + '<td><a class="btn btn-primary" href="'+urlLink+'panacea_mgmt/panacea_reports_display_ehr_uid/?id = '+this.doc_data.widget_data["Student Details"]['Hospital Unique ID']+'">Show EHR</a></td>';
        
        data_table = data_table+'</tr>';
      });
      

      data_table = data_table+'</tbody></table>';
    }
    else
    {
      data_table = '<table id="more_requests" class="table table-striped table-bordered" width="100%"><thead><tr><th>School Name</th><th>Submitted Date</th><th>Campus</th><th>Kitchen</th><th>Toilets</th><th>Dormitory</th><th>Show Attachements</th></tr></thead><tbody>';
      $.each(fetch_data, function(index, val){

         data_table = data_table+'<tr>';
         data_table = data_table+'<td>'+val['School Name']+'</td>';
         data_table = data_table+'<td>'+val['Date']+'</td>';

         if(val.Campus != undefined)
         {
            data_table = data_table +'<td>'+val.Campus['Description']+'</td>';
         }else{
          data_table = data_table +'<td> No Data </td>';
         }
         if(val.Kitchen != undefined)
         {
            data_table = data_table +'<td>'+val.Kitchen['Description']+'</td>';
         }else{
          data_table = data_table +'<td> No Data </td>';
         }
         if(val.Toilets != undefined)
         {
            data_table = data_table +'<td>'+val.Toilets['Description']+'</td>';
         }else{
          data_table = data_table +'<td> No Data </td>';
         }
         if(val.Dormitory != undefined)
         {
            data_table = data_table +'<td>'+val.Dormitory['Description']+'</td>';
         }else{
          data_table = data_table +'<td> No Data </td>';
         }               
         
         data_table = data_table+'<td><button>Show Attachements</button</td>'
         data_table = data_table+'</tr>';
      });

      data_table = data_table+'</tbody></table>';
    }
    
   
  $('#get_rhso_data_table').html(data_table);

    $('#more_requests').DataTable({
      "paging": true,
      "lengthMenu" : [10, 20, 50, 75, 100]
    });
  }

  

</script>