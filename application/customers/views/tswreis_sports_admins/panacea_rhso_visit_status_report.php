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
            <div class="col-lg-6 col-md-3 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                    		<h2> RHSO Hospital Visit</h2>
                      <br> 
                    	<div class="row">
                        <div class="col-sm-3"> 
                    		 <label>Start date</label>
                           	<?php $d =strtotime("-1 Months"); ?>
                            <input type="text" id="start_date_rhso" name="start_date_rhso" class="datepicker form-control date start_date_rhso" value="<?php echo date('Y-m-d', $d); ?>">
                        </div>    
                        <div class="col-sm-3">
                            <label>End date</label>
                            <input type="text" id="end_date_rhso" name="end_date_rhso" class="datepicker form-control date end_date_rhso" value="<?php echo date('Y-m-d'); ?>">
                        </div> 

                        <div class="col-sm-3">
                            <label>District</label>
                            <select class="form-control show-tick district_filter common_change" id="hopistal_dist">
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

                        <div class="col-sm-3">  
                            <button type="button" id="rhso_hospital_visit_search" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float" style="margin-top: 8px;">
                                <i class="material-icons">search</i>
                            </button>
                        </div>

                           <!--  <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button> -->

                    	</div>
                        
                        
                    </div>
                    <div class="body">
                    	<div id="piechart_3d" style="width: 500px; height: 400px;"></div>
                    </div>
                </div> 
            </div> 
            <div class="col-lg-6 col-md-3 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                    	<h2>RHSO School Visit </h2>
                      <br>
                    	<div class="row">
                        <div class="col-sm-3"> 
                    	    <label>Start date</label>
                          <?php $d =strtotime("-3 Months"); ?>

                            <input type="text" id="start_date_sanitation" name="start_date_sanitation" class="datepicker form-control date start_date_sanitation" value="<?php echo date('Y-m-d', $d); ?>">
                        </div> 
                         <div class="col-sm-3">    

                           <label>End date</label>
                        
                        	<input type="text" id="end_date_sanitation" name="end_date_sanitation" class="datepicker form-control date end_date_sanitation" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                         <div class="col-sm-3">   
                        	<label>District</label>
                          	<select class="form-control show-tick district_filter common_change" id="sanitation_dist">
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
                        <div class="col-sm-3"> 
                          <button type="button" id="rhso_sanitation_visit_search" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float" style="margin-top: 8px;">
                            <i class="material-icons">search</i>
                        	 </button>
                        </div>

                    	</div>
                    	
                        
                       
                        <!-- <ul class="header-dropdown m-r--5 m-t--10">
                            <li>
                        		<div class="form-line">
	                                
                                    <label>Start date</label>
                                    
                                      <?php $d =strtotime("-3 Months"); ?>

                                        <input type="text" id="start_date_sanitation" name="start_date_sanitation" class="datepicker form-control date start_date_sanitation" value="<?php echo date('Y-m-d', $d); ?>">
                                    
                                     <label>End date</label>
                                    
                                        <input type="text" id="end_date_sanitation" name="end_date_sanitation" class="datepicker form-control date end_date_sanitation" value="<?php echo date('Y-m-d'); ?>">
                                   
                                    <label>District</label>
                                    <select class="form-control show-tick district_filter common_change" id="sanitation_dist">
                                        <option value="All"  selected="">All</option>
                                        <?php if(isset($distslist)): ?>
                                            <?php foreach ($distslist as $dist):?>
                                                <option value='<?php echo $dist['dt_name']?>'><?php echo ucfirst($dist['dt_name'])?></option>
                                            <?php endforeach;?>
                                        <?php else: ?>
                                            <option value="1"  disabled="">No district entered yet</option>
                                        <?php endif ?>
                                    </select>
                                       
                                    <button type="button" id="rhso_sanitation_visit_search" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                                        <i class="material-icons">search</i>
                                    </button>
                                   
	                            </div>
                        	</li>
                        	<li>
                        		<button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                        	</li>
                        </ul> -->
                    </div>
                    <div class="body">
                    	<div id="columnchart_values" style="width: 300px; height: 400px;"></div>
                    </div>
                </div> 
            </div>     
        </div>
    </div>
</section>

<form style="display: hidden" action="get_rhso_hospital_followup_data" method="POST" id="rhso_followup_status">
    <input type="hidden" id="hospl_start" name="hos_start_date"/>
    <input type="hidden" id="hospl_end" name="hos_end_date"/>
    <input type="hidden" id="hospl_dist" name="hos_dist"/>
    <input type="hidden" id="from_what" name="from_what"/>
    <input type="hidden" id="hospl_selected" name="hospl_selected"/>
</form>

 <form style="display: hidden" action="get_rhso_hospital_followup_data" method="POST" id="rhso_school_visit">
    <input type="hidden" id="school_start_date" name="school_start_date"/>
    <input type="hidden" id="school_end_date" name="school_end_date"/>
    <input type="hidden" id="school_dist" name="school_dist"/>
    <input type="hidden" id="from_what_scl" name="from_what"/>
    <input type="hidden" id="school_selected" name="school_selected"/>

    
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
	
  /*
      Pie chart for Hospitals follwoup
  */

    rhso_hospital_followup_data();
    rhso_sanitation_report();

    $('#rhso_hospital_visit_search').click(function(){
         rhso_hospital_followup_data();
    });
    $('#rhso_sanitation_visit_search').click(function(){
        rhso_sanitation_report();
    });
    function rhso_hospital_followup_data()
    {
        var hospital_start = $('#start_date_rhso').val();
        var hospital_end = $('#end_date_rhso').val();
        var hospital_district = $('#hopistal_dist').val();

        $.ajax({
            url:'get_rhso_hospital_foolowup_data_with_span',
            type:'POST',
            data:{'start_date' : hospital_start, 'end_date': hospital_end, 'district': hospital_district},
            success:function(data){
                var datas = $.parseJSON(data);
                rhso_followup_data(datas, hospital_district);
               
            }
        });
    }

    function rhso_followup_data(datas, hospital_district)
    {
      var emer = datas.Emergency_or_Admitted;
      var out = datas.Out_Patients;
      var review = datas.Review_Cases;

      if(emer == 0 && out == 0 && review == 0)
      {
        $("#piechart_3d").html("<h3>No Data Found</h3>");
      }
      else
      {
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
          var data = google.visualization.arrayToDataTable([
            ['Request Type', 'Followup Count'],
            ['Emergency or Admitted',     emer],
            ['Out Patients',      out],
            ['Review Cases',  review]
          ]);

          var options = {
            title: ''+hospital_district+' District Data ',
            is3D: true,
          };

          var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));

            function selectHandler() {
                  var selectedItem = chart.getSelection()[0];
                  if (selectedItem) {
                    var value = data.getValue(selectedItem.row, 0);
                    //alert('The user selected ' + value);
                    var hospital_start = $('#start_date_rhso').val();
                    var hospital_end = $('#end_date_rhso').val();
                    var hospital_district = $('#hopistal_dist').val();
                    $('#hospl_start').val(hospital_start);
                    $('#hospl_end').val(hospital_end);
                    $('#hospl_dist').val(hospital_district);
                    $('#hospl_selected').val(value);
                    $('#from_what').val("hospital_visit");
                    $('#rhso_followup_status').submit();

                    
                  }
              }

          google.visualization.events.addListener(chart, 'select', selectHandler);
          chart.draw(data, options);


          
        }
      }

    }
      

  /*
      Bar charts for Sanitation Rhso submission
  */

    function rhso_sanitation_report()
    {
        var sanitation_start = $('#start_date_sanitation').val();
        var sanitation_end = $('#end_date_sanitation').val();
        var sanitation_district = $('#sanitation_dist').val();
        
        $.ajax({
            url:'get_rhso_sanitation_data_with_span',
            type:'POST',
            data:{ 'start_date' : sanitation_start, 'end_date': sanitation_end, 'district': sanitation_district },
            success:function(data){
                var datas = $.parseJSON(data);
                console.log(datas);
                rhso_sanitation_data(datas, sanitation_district);
            }
        });
    }

    function rhso_sanitation_data(datas, sanitation_district)
    {
      var Red = datas.red;
      var Orange = datas.orange;
      var Green = datas.green;
      var Empty = datas.empty;

      if(Red == 0 && Orange == 0 && Green == 0 && Empty == 0)
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
            ["Red", Red, "#eb1109"],
            ["Orange", Orange, "#ffa500"],
            ["Green", Green, "#11c408"],
            ["Empty", Empty, "color: #071aab"]
          ]);

          var view = new google.visualization.DataView(data);
          view.setColumns([0, 1,
                           { calc: "stringify",
                             sourceColumn: 1,
                             type: "string",
                             role: "annotation" },
                           2]);

          var options = {
            title: ''+sanitation_district+' District Sanitation Data',
            width: 500,
            height: 400,
            bar: {groupWidth: "80%"},
            legend: { position: "none" },
          };
          var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));

           function selectHandler() {
                  var selectedItem = chart.getSelection()[0];
                  if (selectedItem) {
                    var value = data.getValue(selectedItem.row, 0);
                    //alert('The user selected ' + value);
                    var sanitation_start = $('#start_date_sanitation').val();
                    var sanitation_end = $('#end_date_sanitation').val();
                    var sanitation_district = $('#sanitation_dist').val();
                    $('#school_start_date').val(sanitation_start);
                    $('#school_end_date').val(sanitation_end);
                    $('#school_dist').val(sanitation_district);
                    $('#school_selected').val(value);
                    $('#from_what_scl').val("school_visit");
                    $('#rhso_school_visit').submit();
                    
                  }
              }

          google.visualization.events.addListener(chart, 'select', selectHandler);
          chart.draw(view, options);
      }
    }
  }
    

</script>

