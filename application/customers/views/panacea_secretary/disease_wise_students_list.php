<?php $current_page="Disease_wise_Students_list"; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<!-- <?php //include('inc/sidebar.php'); ?> -->

<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet" />   
<br>
<br>
<br>
<br>

<section class="">
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
                       Select Filters                              
                    </h2> 
                    <ul class="header-dropdown m-r--5">
                      <div class="button-demo">
                          <button type="button" class="btn bg-blue waves-effect getExcel">Get Excel</button>
                          <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                      </div>
                    </ul>                           
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-sm-3">
                            <div class="form-line">
                              <label>Date</label>
                              <input type="text" id="set_date" name="set_date" class="datepicker form-control date" placeholder="Please choose a date..." value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-sm-3">
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
                        <div class="col-sm-3"> 
                            <label>Select School</label>                                 
                            <select class="form-control show-tick" id="school_name" disabled=true >
                                <option value="All"  selected="">All</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn bg-blue waves-effect" id="request_pie_btn"  style="margin-top: 20px;">SET</button>
                        </div>
                      </div>

                      
                      
                  </div>
                  
              </div>
              <div id="chartContainer" ></div>
          </div>
      </div>
  </div>  
</section>


						
<!-- <input class="form-control" type="text">
<span class="note"><i class="fa fa-check text-success"></i> Change title to update and save instantly!</span> -->


  <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="loading_modal">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content" id="loading">
      <center>
        <!-- <img src="<?php //echo(IMG.'ajax-loader.gif'); ?>" id="gif" > -->
        <img src="<?php echo(IMG.'loader.gif'); ?>" id="gif" >
      </center>
      </div>
    </div>
  </div>
		<form style="display: hidden" action="get_students_list_based_on_request_symptom" method="POST" id="ehr_form_for_request">
			 <input type="hidden" id="symptomName" name="symptomName" value=""/>
			 <input type="hidden" id="symptomCategory" name="symptomCategory" value=""/>
			 <input type="hidden" id="selectedMonth" name="selectedMonth" value=""/>
			 <input type="hidden" id="selectedDistrict" name="selectedDistrict" value=""/>
			 <input type="hidden" id="selectedSchool" name="selectedSchool" value=""/>
		</form>


<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

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

<?php
	//include footer
	//include("inc/footer_bar.php");
?>

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


      function show_disease_wise_bar_default_on_page_load(){
        //$("#loading_modal").modal('show');
        today_date = $('#set_date').val();
        dt_name     = $("#select_dt_name option:selected").text();
        school_name= $("#school_name").val();
       // alert today_date;
       
         $.ajax({
          url: 'get_month_wise_diseases',
          type: 'POST',
          data: {"selectedMonth" : today_date, "selectedDistrict" : dt_name, "selectedSchool" : school_name},
          success: function (data) {
             //$("#loading_modal").modal('hide');
              var diseases_list = $.parseJSON(data);
              console.log('replyyyyyyyyyyyyyyyyyyyyyyy', diseases_list);
                var chart = new CanvasJS.Chart("chartContainer", {
                  animationEnabled: true,
                   height: 900,
                   width: 1200,

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
                              $("#selectedMonth").val(today_date);
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

    $('.getExcel').click(function(){
    //$("#loading_modal").modal('show');
    var selectedMonth = $('#set_date option:selected').val();
    var selectedDistrict = $("#select_dt_name option:selected").text();
    var selectedSchool= $("#school_name option:selected").text();  
   
    $.ajax({
        url : 'get_symptoms_monthly_tracking_excel',
        type: 'POST',
        data : {"school_name":selectedSchool, "dt_name":selectedDistrict ,"selectedMonth" : today_date},
        success : function(data){
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

   $('#select_dt_name').change(function(e){
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
