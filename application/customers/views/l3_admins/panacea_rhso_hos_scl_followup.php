<?php $current_page = "RHSO table"; ?>
<?php $main_nav = ""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">

<section class="content">
  <div class="container-fluid">
        <div class="block-header">
            
        </div>
        <!-- Basic Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2> RHSO Hospital Visit</h2>
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
                          <select class="form-control show-tick" id="subtype">
                            
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
    var typee= $('#type_selected').val();
   alert(typee);
    $('#subtype option[value='+typee+']').prop('selected', 'selected').change();
  }
  else
  {
    var typee= $('#type_selected').val();
    
    $('#subtype option[value='+typee+']').prop('selected', 'selected').change();
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
    alert(type);
    var options = $('#subtype');

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


   function rhso_hospital_visits_report()
   {
      var start = $("#subtype").val();
      var end = $("#subtype").val();
      var dist = $("#subtype").val();
      var selected = $("#subtype").val();
      var from = $("#from_val").val();

      if(from == "hospital_visit"){

        $.ajax({

         url : 'get_rhso_hospital_visit_data_based_on_selected',
         type : 'POST',
         data :{'start_date':start, 'end_date':end, 'district':dist, 'select_type':selected},
         success:function(data){
            var fetch_data = $.parseJSON(data);
            console.log(fetch_data);
         }

        });
      }
      else{

        $.ajax({
         url : 'get_rhso_school_visit_data_based_on_selected',
         type : 'POST',
         data :{'start_date':start, 'end_date':end, 'district':dist, 'select_type':selected},
         success:function(data){
            var fetch_data = $.parseJSON(data);
            console.log(fetch_data);
         }

        });
      }

    
   }

  function rhso_hospital_data_table()
  {
    data_table = '<table><thead><tr><th>Student Name</th><th>Hospital Unique ID</th><th>School Name</th></tr></thead><tbody>';

    data_table = data_table+'<tr>';
    data_table = data_table+'<td>rrr</td>';
    data_table = data_table+'<td>ggg</td>';
    data_table = data_table+'<td>ddd</td>';
    data_table = data_table+'</tr></tbody></table>';
   
  $('#get_rhso_data_table') = html(data_table);
  }

  

</script>