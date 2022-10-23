<?php $current_page = "RHSO"; ?>
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
           
            <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
              <div class="card">
                <div class="header">
                    <h2>Blood Donors With Blood Groups</h2>
                    <br>
                    <div class="row">

                         <div class="col-sm-5">   
                          <label>District</label>
                            <select class="form-control show-tick district_filter common_change" id="blood_dist">
                              <option value="All"  selected="">All</option>
                              <?php if(isset($distslist)): ?>
                                  <?php foreach ($distslist as $dist):?>
                                      <option value='<?php echo $dist['dt_name']?>'><?php echo ucfirst($dist['dt_name'])?></option>
                                  <?php endforeach;?>
                              <?php else: ?>
                                  <option value="1"  disabled="">No district entered yet</option>
                              <?php endif ?>
                          </select>
                        </div>


                        <div class="col-sm-5 hidden">

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


                        <div class="col-sm-2"> 
                          <button type="button" id="blood_group_search" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float" style="margin-top: 8px;">
                            <i class="material-icons">search</i>
                           </button>
                        </div>

                      </div>

                      <br>                      
                      
                    </div>
                    <div class="body">
                      <div id="columnchart_values" style="width: 280px; height: 400px;"></div>
                    </div>
              </div>
                
            </div>     
        </div>
    </div>
</section>


 <form style="display: hidden" action="get_blood_group_clicking_data" method="POST" id="blood_group_click">

    <input type="hidden" id="blood_group_dist" name="blood_group_dist"/>
    <input type="hidden" id="blood_group_name" name="blood_group_name"/>
         
</form>
   

<?php include('inc/footer_bar.php'); ?>

<!-- Google Charts -->
<script src="<?php echo(MDB_JS.'loader.js'); ?>"></script>

<!-- Moment Plugin Js -->
<script src="<?php echo MDB_PLUGINS."momentjs/moment.js"; ?>"></script>

<!-- Custom Js -->
<script src="<?php echo(MDB_JS.'admin.js'); ?>"></script>
<script src="<?php echo(MDB_JS.'pages/forms/basic-form-elements.js'); ?>"></script>

<!-- Demo Js -->
<script src="<?php echo(MDB_JS.'demo.js'); ?>"></script>

<!-- Bootstrap Datepicker Plugin Js -->
<script src="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script> 


<script type="text/javascript">

  var today_date = $('#set_date').val();
  $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
  $('#set_date').change(function(e){
          today_date = $('#set_date').val();
  });
	
  
    
    blood_group_wise_details();

    
    $('#blood_group_search').click(function(){
        blood_group_wise_details();
    });
   

   

  /*
      Bar charts for blood group
  */

    function blood_group_wise_details()
    {


        var blood_group_district = $('#blood_dist').val();

        var blood_group_name = $('#blood_group_select').val();
       
        
        $.ajax({
            url:'get_blood_group_wise_data',
            type:'POST',
            data:{ 'district' : blood_group_district, 'blood': blood_group_name },
            success:function(data){
                var datas = $.parseJSON(data);
                console.log(datas);
                panacea_blood_group_data(datas, blood_group_district);
            }
        });
    }

    function panacea_blood_group_data(datas, blood_group_district)
    {
      var Apositive = datas.Apositive;
      var Anegative = datas.Anegative;
      var ABpositive = datas.ABpositive;
      var ABnegative = datas.ABnegative;
      var Bpositive = datas.Bpositive;
      var Bnegative = datas.Bnegative;
      var Opositive = datas.Opositive;
      var Onegative = datas.Onegative;
      

      if(Apositive == 0 && Anegative == 0 && ABpositive == 0 && ABnegative == 0 && Bpositive == 0 && Bnegative == 0 && Opositive == 0 && Onegative == 0 )
      {
        $("#columnchart_values").html("<h3>No Data Found</h3>");
      }
      else
      {
        google.charts.load("current", {packages:['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
          var data = google.visualization.arrayToDataTable([
            ["Status", "Count", { role: "style" } ],
            ["O+ve", Opositive, "#B71C1C"],
            ["A+ve", Apositive, "#F44336"],
            ["B+ve", Bpositive, "#FF6F00"],
            ["AB+ve", ABpositive, "#1A237E"],
            ["O-ve", Onegative, "#1A237E"],
            ["A-ve", Anegative, "#ffa500"],
            ["B-ve", Bnegative, "#5E35B1"],
            ["AB-ve", ABnegative, "#7B1FA2"]
          ]);

          var view = new google.visualization.DataView(data);
          view.setColumns([0, 1,
                           { calc: "stringify",
                             sourceColumn: 1,
                             type: "string",
                             role: "annotation" },
                           2]);

          var options = {
            title: ''+blood_group_district+' Blood Donors',
            width: 900,
            height: 400,
            bar: {groupWidth: "80%"},
            legend: { position: "none" },
          };
          var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));

           function selectHandler() {
                  var selectedItem = chart.getSelection()[0];
                  if (selectedItem) {
                    var value = data.getValue(selectedItem.row, 0);                    
                    var blood_group = $('#blood_group_select').val();
                    var district = $('#blood_dist').val();                    
                    $('#blood_group_name').val(blood_group);
                    $('#blood_group_dist').val(district);                    
                    $('#blood_group_click').submit();
                    
                  }
              }

          google.visualization.events.addListener(chart, 'select', selectHandler);
          chart.draw(view, options);
      }
    }
  }
    

</script>

