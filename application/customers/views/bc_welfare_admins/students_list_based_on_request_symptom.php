<?php $current_page=""; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<br>
<br>
<br>
<br>
<br>

<!--  Bootstrap Material Datetime Picker Css -->
    <link href='<?php echo MDB_PLUGINS."bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"; ?>' rel="stylesheet" />

<!-- Code for data tables -->
<section class="">
<div class="container-fluid">
<div class="block-header">
   <!--  <h2>Anemia Health Requests</h2> -->
            <ul class="header-dropdown m-r--5">
                <div class="button-demo" style="text-align:right">
                <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                </div>
            </ul>
</div>
<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">          
            <div class="row">               
                <article class="col-sm-12">
                    <div class="alert alert-danger fade in">
                      
                        <strong><?php echo $selectedMonth_start; ?></strong> =>
                        <strong><?php echo $selectedMonth_end; ?></strong> =>
                        <strong><?php echo $symptomName; ?></strong> =>
              <span class="badge bg-color-darken"> <?php if(isset($students) && !empty($students)):?><?php echo count($students);?><?php else:?><?php echo 0; ?><?php endif;?> </span>
                       
                    </div>
                </article>               
            </div>

            <div class="body">
                <div class="table-responsive" id="table_hide">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>Unique ID</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>School Name</th>
                                <th>Symptom</th>
                                <th>Request Raised Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                                        <?php foreach ($students as $student):?>
                                            <tr>
                                                <td><?php echo $student['doc_data']['widget_data']["page1"]['Student Info']['Unique ID'] ;?></td>
                                                <td><?php echo $student['doc_data']['widget_data']["page1"]['Student Info']['Name']['field_ref'] ;?></td>
                                                <td><?php echo $student['doc_data']['widget_data']["page1"]['Student Info']['Class']['field_ref'] ;?></td>
                                                <td><?php echo $student['doc_data']['widget_data']["page1"]['Student Info']['School Name']['field_ref'];?></td>
                                                <td><span class="label label-danger"><?php echo $symptomName; ?></span></td>
                                                  <td> <?php echo $student['history'][0]['time'];?></td>
                                                <td><a class='ldelete' href='<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_display_ehr_uid/"?>? id = <?php echo $student['doc_data']['widget_data']["page1"]['Student Info']['Unique ID'];?>'>
                                                    <button class="btn btn-primary btn-xs">Show EHR</button>
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

<?php include('inc/footer_bar.php'); ?>

<script type="text/javascript">

    $('#date_set').click(function(){
            var startDate = $('#passing_date').val();
            var endDate = $('#passing_end_date').val();
    /*
    alert(startDate);
    alert(endDate);
    */         
                $.ajax({
                    url : 'get_anemia_records_based_on_span',
                    type : 'POST',
                    data : {"start_date" : startDate, "end_date": endDate},
                    success : function(data){
                      $("#loading_modal").modal('hide');                     
                        $('#students_more_req').empty();
                        var result = $.parseJSON(data);
                        //console.log(result);
                        data_table_for_filters(result);
                        $('#show_when_serach').show();
                        $('#table_hide').hide();
                    }
                });

        });


    function data_table_for_filters(result){

      if(result == "No Data Available"){
          $('#sanitation_filters').html('<h4>No Data Available For this Field Officer.</h4>');
      }
        else{

            data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic"><thead><tr><th>Health ID</th><th>Student Name</th><th>Class</th><th>Request Raised Time</th><th>Doctor Response Time</th><th>Doctor Name</th></tr></thead><tbody>';

              $.each(result, function(){

              data_table = data_table+'<tr>';

              data_table = data_table+'<td>'+this.doc_data.widget_data.page1['Student Info']['Unique ID']+'</td>';
              data_table = data_table+'<td>'+this.doc_data.widget_data.page1['Student Info']['Name']['field_ref']+'</td>';
              data_table = data_table+'<td>'+this.doc_data.widget_data.page1['Student Info']['Class']['field_ref']+'</td>';
              data_table = data_table+'<td>'+this.history['0']['time']+'</td>';          
              data_table = data_table+'<td>'+this.history['time']+'</td>';
              data_table = data_table+'<td>'+this.history['submitted_by_name']+'</td>';

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

    $("#submit_request").click(function(){

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


</script>
