<?php $current_page="Disease_wise_Students_list"; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet" />   
<style>
.sk-circle {
  margin: 100px auto;
  width: 70px;
  height: 70px;
  position: relative;
}
.sk-circle .sk-child {
  width: 100%;
  height: 100%;
  position: absolute;
  left: 0;
  top: 0;
}
.sk-circle .sk-child:before {
  content: '';
  display: block;
  margin: 0 auto;
  width: 15%;
  height: 15%;
  background-color: #333;
  border-radius: 100%;
  -webkit-animation: sk-circleBounceDelay 1.2s infinite ease-in-out both;
          animation: sk-circleBounceDelay 1.2s infinite ease-in-out both;
}
.sk-circle .sk-circle2 {
  -webkit-transform: rotate(30deg);
      -ms-transform: rotate(30deg);
          transform: rotate(30deg); }
.sk-circle .sk-circle3 {
  -webkit-transform: rotate(60deg);
      -ms-transform: rotate(60deg);
          transform: rotate(60deg); }
.sk-circle .sk-circle4 {
  -webkit-transform: rotate(90deg);
      -ms-transform: rotate(90deg);
          transform: rotate(90deg); }
.sk-circle .sk-circle5 {
  -webkit-transform: rotate(120deg);
      -ms-transform: rotate(120deg);
          transform: rotate(120deg); }
.sk-circle .sk-circle6 {
  -webkit-transform: rotate(150deg);
      -ms-transform: rotate(150deg);
          transform: rotate(150deg); }
.sk-circle .sk-circle7 {
  -webkit-transform: rotate(180deg);
      -ms-transform: rotate(180deg);
          transform: rotate(180deg); }
.sk-circle .sk-circle8 {
  -webkit-transform: rotate(210deg);
      -ms-transform: rotate(210deg);
          transform: rotate(210deg); }
.sk-circle .sk-circle9 {
  -webkit-transform: rotate(240deg);
      -ms-transform: rotate(240deg);
          transform: rotate(240deg); }
.sk-circle .sk-circle10 {
  -webkit-transform: rotate(270deg);
      -ms-transform: rotate(270deg);
          transform: rotate(270deg); }
.sk-circle .sk-circle11 {
  -webkit-transform: rotate(300deg);
      -ms-transform: rotate(300deg);
          transform: rotate(300deg); }
.sk-circle .sk-circle12 {
  -webkit-transform: rotate(330deg);
      -ms-transform: rotate(330deg);
          transform: rotate(330deg); }
.sk-circle .sk-circle2:before {
  -webkit-animation-delay: -0.84s;
          animation-delay: -0.84s; }
.sk-circle .sk-circle3:before {
  -webkit-animation-delay: -0.84ss;
          animation-delay: -0.84ss; }
.sk-circle .sk-circle4:before {
  -webkit-animation-delay: -0.84ss;
          animation-delay: -0.9s; }
.sk-circle .sk-circle5:before {
  -webkit-animation-delay: -0.8s;
          animation-delay: -0.8s; }
.sk-circle .sk-circle6:before {
  -webkit-animation-delay: -0.7s;
          animation-delay: -0.7s; }
.sk-circle .sk-circle7:before {
  -webkit-animation-delay: -0.6s;
          animation-delay: -0.6s; }
.sk-circle .sk-circle8:before {
  -webkit-animation-delay: -0.5s;
          animation-delay: -0.5s; }
.sk-circle .sk-circle9:before {
  -webkit-animation-delay: -0.4s;
          animation-delay: -0.4s; }
.sk-circle .sk-circle10:before {
  -webkit-animation-delay: -0.3s;
          animation-delay: -0.3s; }
.sk-circle .sk-circle11:before {
  -webkit-animation-delay: -0.2s;
          animation-delay: -0.2s; }
.sk-circle .sk-circle12:before {
  -webkit-animation-delay: -0.1s;
          animation-delay: -0.1s; }

@-webkit-keyframes sk-circleBounceDelay {
  0%, 80%, 100% {
    -webkit-transform: scale(0);
            transform: scale(0);
  } 40% {
    -webkit-transform: scale(1);
            transform: scale(1);
  }
}

@keyframes sk-circleBounceDelay {
  0%, 80%, 100% {
    -webkit-transform: scale(0);
            transform: scale(0);
  } 40% {
    -webkit-transform: scale(1);
            transform: scale(1);
  }
}
</style>
<section class="content">
  <div class="container-fluid">
    <div class="block-header">
         <!--  <h2>BASIC FORM ELEMENTS</h2> -->
    </div>
    <!-- Input -->
      <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                       Health Track Chart                        
                    </h2> 
                    <ul class="header-dropdown m-r--5">
                      <div class="button-demo">
                          <button type="button" class="btn bg-blue waves-effect getExcel">Get Excel</button>
                          <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                      </div>
                    </ul>                           
                </div>
                <div class="body" >
                    <div class="row clearfix">
                        <!-- <div class="col-sm-3">
                            <div class="form-line">
                              <label>Date</label>
                              <input type="text" id="set_date" name="set_date" class="datepicker form-control date" placeholder="Please choose a date..." value="<?php //echo date('Y-m-d'); ?>">
                            </div>
                        </div> -->
                        <div class="col-sm-2">                       
                            <?php $end_date  = date ( "Y-m-d", strtotime ( date('Y-m-d') . "-30 days" ) ); ?>
                           <span id="monitoring_datepicker">
                            Start Date
                           <input type="text" id="passing_date" name="passing_date" class="form-control date" value="<?php echo $end_date; ?>">
                           </span>
                        </div>
                        <div class="col-sm-2">
                            <span id="monitoring_datepicker">
                           End Date
                           <input type="text" id="passing_end_date" name="passing_end_date" class="form-control date" value="<?php echo date('Y-m-d'); ?>">
                           </span>
                        </div>
                        <div class="col-sm-2">
                          <label>Select District</label>
                          <select id="select_dt_name" class="form-control">
                            <option value="All">All</option>
                            <?php if(isset($district_list)): ?>
                              <?php foreach ($district_list as $district):?>
                              <option value='<?php echo $district['_id']?>' ><?php echo ucfirst($district['dt_name'])?></option>
                              <?php endforeach;?>
                              <?php else: ?>
                              <option value="1"  disabled="">No district entered yet</option>
                            <?php endif ?>
                          </select>
                        </div>
                        <div class="col-sm-2"> 
                            <label>Select School</label>                                 
                            <select class="form-control show-tick" id="school_name" disabled=true >
                                <option value="All"  selected="">All</option>
                            </select>
                        </div>
                        
                        <div class="col-sm-2 button-demo"><br>
                           <button type="submit" class="btn bg-primary waves-effect" id="request_pie_btn">Set</button>
                        </div>
                      </div> 
                  </div>
                  <div id="chartContainer" ></div>
              </div>
          </div>
      </div>
  </div>  
</section>

 
     <center>
    <div class="sk-circle">
      <div class="sk-circle1 sk-child"></div>
      <div class="sk-circle2 sk-child"></div>
      <div class="sk-circle3 sk-child"></div>
      <div class="sk-circle4 sk-child"></div>
      <div class="sk-circle5 sk-child"></div>
      <div class="sk-circle6 sk-child"></div>
      <div class="sk-circle7 sk-child"></div>
      <div class="sk-circle8 sk-child"></div>
      <div class="sk-circle9 sk-child"></div>
      <div class="sk-circle10 sk-child"></div>
      <div class="sk-circle11 sk-child"></div>
      <div class="sk-circle12 sk-child"></div>
    </div>
  </center>
      

						
<!-- <input class="form-control" type="text">
<span class="note"><i class="fa fa-check text-success"></i> Change title to update and save instantly!</span> -->


  <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="loading_modal">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content" id="loading">
      <center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
      </div>
    </div>
  </div>
		<form style="display: hidden" action="get_students_list_based_on_request_symptom" method="POST" id="ehr_form_for_request">
			 <input type="hidden" id="symptomName" name="symptomName" value=""/>
			 <input type="hidden" id="symptomCategory" name="symptomCategory" value=""/>
			 <!-- <input type="hidden" id="selectedMonth" name="selectedMonth" value=""/> -->
       <input type="hidden" id="selectedMonth_start" name="selectedMonth_start" value=""/>
       <input type="hidden" id="selectedMonth_end" name="selectedMonth_end" value=""/>
			 <input type="hidden" id="selectedDistrict" name="selectedDistrict" value=""/>
			 <input type="hidden" id="selectedSchool" name="selectedSchool" value=""/>
		</form>



<script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.fillbetween.min.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.orderBar.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.pie.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.time.min.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.axislabels.js"></script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>

<!-- Jquery Core Js -->
<script src="<?php echo MDB_PLUGINS."jquery/jquery.min.js"; ?>"></script>



<!-- Waves Effect Plugin Js -->
<script src="<?php echo MDB_PLUGINS."node-waves/waves.js"; ?>"></script>

<!-- Autosize Plugin Js -->
<script src="<?php echo MDB_PLUGINS."autosize/autosize.js"; ?>"></script>


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

  //Date Filter Script
    var today_date = $('#set_date').val();
    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#set_date').change(function(e){
            today_date = $('#set_date').val();
    });

      show_disease_wise_bar_default_on_page_load();

      $('#request_pie_btn').click(function(e){
         /*selectedMonth = $('#set_date').val();
         selectedDistrict     = $("#select_dt_name option:selected").text();
         selectedSchool= $("#school_name").val();*/
        show_disease_wise_bar_default_on_page_load();
     });

      $('.sk-circle').hide();
      function show_disease_wise_bar_default_on_page_load(){
        $('.sk-circle').show();
        //$("#loading_modal").modal('show');
        //today_date = $('#set_date').val();
        var todayDate = $('#passing_date').val();
        var endDate = $('#passing_end_date').val();
        dt_name     = $("#select_dt_name option:selected").text();
        school_name= $("#school_name").val();
       // alert today_date;
       
         $.ajax({
          url: 'get_month_wise_diseases',
          type: 'POST',
          data: {"start_date" : todayDate, "end_date": endDate , "selectedDistrict" : dt_name, "selectedSchool" : school_name},
          success: function (data) {
            $('.sk-circle').hide();
             //$("#loading_modal").modal('hide');
              var diseases_list = $.parseJSON(data);
              console.log('replyyyyyyyyyyyyyyyyyyyyyyy', diseases_list);
                var chart = new CanvasJS.Chart("chartContainer", {
                  animationEnabled: false,
                   height: 1200,
                   width: 1225,

                  title: {
                      text: "Monthly Students Health Report Chart",
                      fontSize: 20
                  },
                  axisX: {
                      interval: 1,
                      labelFontSize: 19
                  },
                  axisY: {
                      title: "No of students",
                      labelFontSize: 16
                  },
                  data: [{
                      type: "bar",
                      indexLabelFontSize: 16,
                      indexLabelFormatter: function(e){     
                          return e.dataPoint.y;
                          },
                      dataPoints: diseases_list,

                      click: function(e){
                              //alert("Clicked Label: "+ e.dataPoint.label);
                              $("#symptomName").val(e.dataPoint.label);
                              $("#symptomCategory").val(e.dataPoint.symptom_category);
                              //alert(e.dataPoint.symptom_categoty);
                              //$("#selectedMonth").val(today_date);
                              $("#selectedMonth_start").val(todayDate);
                              $("#selectedMonth_end").val(endDate);
                              $("#selectedDistrict").val(dt_name);
                              $("#selectedSchool").val(school_name);
                              $("#ehr_form_for_request").submit();
                          },

                  }]
              });
              chart.render();
            
              },
              error:function(XMLHttpRequest, textStatus, errorThrown)
              {
               console.log('error', errorThrown);
              }
          });
    };

  //GetExcel
  $('.sk-circle').hide();
    $('.getExcel').click(function(){
      $('.sk-circle').show();
    //$("#loading_modal").modal('show');
    var selectedMonth = $('#set_date option:selected').val();
    var selectedDistrict = $("#select_dt_name option:selected").text();
    var selectedSchool= $("#school_name option:selected").text();  
   
    $.ajax({
        url : 'get_symptoms_monthly_tracking_excel',
        type: 'POST',
        data : {"school_name":selectedSchool, "dt_name":selectedDistrict ,"selectedMonth" : today_date},
        success : function(data){
          $('.sk-circle').hide();
         //$("#loading_modal").modal('hide');
            console.log(data);
            window.location = data;
        },
        error:function(XMLHttpRequest, textStatus, errorThrown)
        {
         console.log('error', errorThrown);
        }
    });
  });

  //Get schools using district code
  $('.sk-circle').hide();
   $('#select_dt_name').change(function(e){
    $('.sk-circle').show();
    /*var datas = $('#select_dt_name').val();
         alert(datas);*/
    dist = $('#select_dt_name').val();
    dt_name = $("#select_dt_name option:selected").text();
    //alert(dist);
    var options = $("#school_name");
    options.prop("disabled", true);
    
    options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching schools list..."));
    $.ajax({
      url: 'get_schools_list',
      type: 'POST',
      data: {"dist_id" : dist},
      success: function (data) { 
      $('.sk-circle').hide();     

        result = $.parseJSON(data);
        console.log(result)

        options.prop("disabled", false);
        options.empty();
        options.append($("<option />").val("All").prop("selected", true).text("All"));
        $.each(result, function() {
            options.append($("<option />").val(this.school_name).text(this.school_name));
        });
            
        },
          error:function(XMLHttpRequest, textStatus, errorThrown)
        {
         console.log('error', errorThrown);
          }
      });
  });

  
</script>





