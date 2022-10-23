<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Student Health Track Chart";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["disease wise students list"]["active"] = true;
include("inc/nav.php");

?>
<style>
#chartContainer{
  margin-bottom: 2000px;
}
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->


<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">

   <!-- row -->
			<div class="row">

				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-6 col-md-6 col-lg-12">

					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-pink" id="wid-id-0">

						<header>
							<h2><strong>Search</strong> <i>Filters</i></h2>
              <button class="btn bg-green btn-sm getExcel" style="float: right; margin-right: 60px;">Get EXcel</button>

						</header>

						<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
								<input class="form-control" type="text">
								<span class="note"><i class="fa fa-check text-success"></i> Change title to update and save instantly!</span>

							</div>
							<!-- end widget edit box -->

							<!-- widget content -->
							<div class="widget-body">
                <div class="row">
                  <form class='smart-form'>
                  <fieldset style="padding-top: 0px; padding-bottom: 0px;">
                  <section class="col col-3">
                  <label class="label">Select Month</label>
                    <div class="form-group">
                      <div class="input-group">
                     <input type="text" id="set_date" name="set_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo date('Y-m-d'); ?>"> 
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      </div>
                    </div>
                  </section>
                  <section class="col col-3">
                    <label class="label">Select District</label>
                    <label class="select">
                    <select id="select_dt_name" class="form-control">
                      <option value="All">All</option>
                      <?php if(isset($district_list)): ?>
                        <?php foreach ($district_list as $district):?>
                        <option value='<?php echo $district['_id']?>' ><?php echo ucfirst($district['dt_name'])?></option>
                        <?php endforeach;?>
                        <?php else: ?>
                        <option value="1"  disabled="">No district entered yet</option>
                      <?php endif ?>
                    </select> <i></i>
                    </label>
                  </section>
                  <!-- <section class="col col-3">
                    <label class="label" for="first_name">School Name</label>
                          <label class="select">
                          <select id="school_name" class="form-control" disabled=true>
                             <option value='All'>All</option> 
                          </select> <i></i>
                  </section> -->
                  <section class="col col-3">
                    <label class="label" for="first_name">School Name</label>
                    <label class="select">
                    <select id="school_name" disabled=true>
                      <!-- <option value="0" selected="" disabled="">Select a district first</option> -->
                      <option value='All'>All</option> 
                      
                    </select> <i></i>
                  </label>
                  </section>
								</fieldset>
                </form>
                <section class="col col-2"> 
                    <button type="submit" class="btn bg-color-pink txt-color-white btn-sm" id="request_pie_btn" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
                      Set
                    </button>
                </section>
                </div>
							<!-- end widget content -->


						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->

					</div><!-- end row -->
        </article>
        <!-- end article -->

		</div><!-- end row -->


		<div id="chartContainer" ></div>
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
			 <input type="hidden" id="selectedMonth" name="selectedMonth" value=""/>
			 <input type="hidden" id="selectedDistrict" name="selectedDistrict" value=""/>
			 <input type="hidden" id="selectedSchool" name="selectedSchool" value=""/>
		</form>

  </div>
  <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->



<!-- ==========================CONTENT ENDS HERE ========================== -->
<?php
	//include required scripts
	include("inc/scripts.php");
?>


<!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->


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

<?php
	//include footer
	include("inc/footer.php");
?>

<script>
$(document).ready(function() {

  var today_date = $('#set_date').val();
  var dt_name = $('#select_dt_name').val();
  var school_name = $('#school_name').val(); 
  
  $('#set_date').change(function(e){
        today_date = $('#set_date').val();

        console.log('php222222222222222', today_date);
    });


  show_disease_wise_bar_default_on_page_load();

  function show_disease_wise_bar_default_on_page_load()
  {
      $("#loading_modal").modal('show');
      selectedMonth = $('#set_date').val();
      selectedDistrict     = $("#select_dt_name option:selected").text();
      selectedSchool= $("#school_name").val();
   
      $.ajax({
          url: 'get_month_wise_diseases',
          type: 'POST',
          data: {"selectedMonth" : today_date, "selectedDistrict" : dt_name, "school_name" : selectedSchool},
          success: function (data) {
              $("#loading_modal").modal('hide');
              var diseases_list = $.parseJSON(data);
              console.log('replyyyyyyyyyyyyyyyyyyyyyyy', diseases_list);
              var chart = new CanvasJS.Chart("chartContainer", {
                  animationEnabled: true,
                   height: 2000,
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
                              $("#selectedMonth").val(selectedMonth);
                              $("#selectedDistrict").val(selectedDistrict);
                              $("#selectedSchool").val(selectedSchool);
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
  }
  $('#request_pie_btn').click(function(e){
      $("#loading_modal").modal('show');

       selectedMonth = $('#set_date').val();
       selectedDistrict     = $("#select_dt_name option:selected").text();
       selectedSchool= $("#school_name").val();
      
      $.ajax({
          url: 'get_month_wise_diseases',
          type: 'POST',
          data: {"selectedMonth" : today_date, "selectedDistrict" : dt_name, "school_name" : selectedSchool},
          success: function (data) {
              $("#loading_modal").modal('hide');
              var diseases_list = $.parseJSON(data);
              console.log('replyyyyyyyyyyyyyyyyyyyyyyy', diseases_list);
              var chart = new CanvasJS.Chart("chartContainer", {
                  animationEnabled: true,
                   height: 2000,
                  title: {
                      text: "Monthly Students Health Diseases",
                      fontSize: 20
                  },
                  axisX: {
                      interval: 1,
                       labelFontSize: 16
                  },
                  axisY: {
                      title: "No of students",
                      labelFontSize: 16
                  },
                  data: [{
                      type: "bar",
                      indexLabelFontSize: 18,
                      indexLabelFormatter: function(e){     
                          return e.dataPoint.y;
                          },
                      dataPoints: diseases_list,

                      click: function(e){                             
                     $("#symptomName").val(e.dataPoint.label);
                    $("#symptomCategory").val(e.dataPoint.symptom_category);
                    //alert(e.dataPoint.symptom_categoty);
                    $("#selectedMonth").val(selectedMonth);
                    $("#selectedDistrict").val(selectedDistrict);
                    $("#selectedSchool").val(selectedSchool);
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
  });

  $('.getExcel').click(function(){
  $("#loading_modal").modal('show');
  var selectedMonth = $('#set_date option:selected').val();
  var selectedDistrict = $("#select_dt_name option:selected").text();
  var selectedSchool= $("#school_name option:selected").text();  
 
  $.ajax({
      url : 'get_symptoms_monthly_tracking_excel',
      type: 'POST',
      data : {"school_name":selectedSchool, "dt_name":selectedDistrict ,"selectedMonth" : today_date},
      success : function(data){
        $("#loading_modal").modal('hide');
          console.log(data);
          window.location = data;
      },
      error:function(XMLHttpRequest, textStatus, errorThrown)
      {
       console.log('error', errorThrown);
      }
  });
});

$('#select_dt_name').change(function(e){
	dist = $('#select_dt_name').val();
	dt_name = $("#select_dt_name option:selected").text();
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
			//options.append($("<option />").val("select").prop("selected", true).text("All"));
        options.append($("<option />").val("All").text("All"));
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

});

//===================================end of dril down pie================
</script>
