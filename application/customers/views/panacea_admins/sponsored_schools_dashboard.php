<?php $current_page = "homepage"; ?>
<?php $main_nav = ""; ?>
<?php include("inc/header_bar.php"); ?>
<!-- Bootstrap Material Datetime Picker Css -->
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">

<style type="text/css">
    .status_blink{
       
       /*color: rgb (0, 137, 226);*/
       animation: blink 3s infinite;
       padding: 10px;
       font-size: 20px;
   }
    @keyframes blink{
       0%{opacity: 1;}
       75%{opacity: 1;}
       76%{ opacity: 0;}
       100%{opacity: 0;}
}
  body {
  font-family: "Lato", sans-serif;
}

.sidepanel  {
  width: 0;
  position: fixed;
  z-index: 1;
  height: 250px;
  top: 100px;
  right: 0;
  background-color: #42f2f5;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidepanel a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 15px;
  color: black;
  display: block;
  transition: 0.3s;
}


.sidepanel a:hover {
  color: red;
}

.sidepanel .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
}

.openbtn {
  font-size: 15px;
  cursor: pointer;
  background-color: white;
  color: black;
  padding: 5px 5px;
  border: none;
}

.openbtn:hover {
  background-color:white;
}

</style>
<br>
<br>
<br>
<br>
<br>

<div class="container-fluid">
<!--FIlters For Admin Dashboard-->
    <div id="collapseExample" class="panel">
        <div class="panel-body">
            <div class="row clearfix">
            <!-- Academic Year Filter -->
                <div class="col-sm-2">
                    <label>Welfares</label>
                    <select class="form-control show-tick welfare_filter common_change" id="academic_filter">
                        <option value="tswreis" selected="">Social Welfare</option>
                        <option value="ttwreis">Tribal Welfare</option>
                        <option value="bcwelfare">BC Welfare</option>
                    </select>
                </div>
            <!-- Academic Year Filter -->
                <div class="col-sm-2">
                    <label>Academic Year</label>
                    <select class="form-control show-tick academic_filter common_change" id="academic_filter">
                        <option value="2021-2022" selected="">2021-2022 AcademicYear</option>
                    </select>
                </div>
            <!-- District Filter -->
                <div class="col-sm-2">
                    <label>District</label>
                    <select class="form-control show-tick district_filter common_change" id="district_filter">
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
                <!-- School FIlter -->
                <div class="col-sm-2">
                    <label>School</label>
                    <select class="form-control show-tick school_filter common_change" id="school_filter" disabled=true >
                      <option value="All" >All</option>  
                    </select>
                </div>
                <!-- <div class="col-sm-2">
                    <label>Gender</label>
                    <select class="form-control show-tick gender_filter common_change" id="gender_filter">
                        <option class="student_type_for_tails" value="All" checked>All</option>
                        <option name="student_type" class="student_type_for_tails" id="student_type_boys" value="Male">Male</option>
                        <option name="student_type" class="student_type_for_tails" id="student_type_girls"value="Female">Female</option>
                    </select>
                </div> -->
                <div class="col-sm-1">
                    <div class="form-line">
                        <button type="button" id="request_date_set_span" class="btn bg-green btn-circle-lg waves-effect waves-circle waves-float">
                            <i class="material-icons">search</i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- End FIlters For Admin Dashboard -->

<div class="row">
    
     <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
       
        <div class="info-box-3 bg-brown">
            <div class="icon">
                <span class="chart chart-line">9,4,6,5,6,4,7,3</span>
            </div>
            <div class="content">
                <div class="text">SCREENED SCHOOLS</div>
                <span class="screened_school_count"></span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box-3 bg-grey">
            <div class="icon">
                <span class="chart chart-line">9,4,6,5,6,4,7,3</span>
            </div>
            <div class="content">
                <div class="text">NOT SCREENED SCHOOLS</div>
                <span class="not_screened_school_count"></span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box-3 bg-blue-grey">
            <div class="icon">
                <div class="chart chart-bar">4,6,-3,-1,2,-2,4,6</div>
            </div>
            <div class="content">
                <div class="text">SCREENED STUDENTS</div>
                <div class="total_screened_stud_count"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="info-box-3 bg-black">
            <div class="icon">
                <div class="chart chart-bar reverse">4,6,-3,-1,2,-2,4,6</div>
            </div>
            <div class="content">
                <div class="text">NOT SCREENED STUDENTS</div>
                <div class="not_screened_stud"></div>
            </div>
        </div>
    </div>
</div>
<!-- End Info Cards Requests Information -->

<!-- Screening and requests cards Info -->
<div class="row clearfix">
    <!-- Screening PIE -->
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="header">
                <h2>Screening Pie</h2>
                <!-- <input type="radio" name="screening_pie_radio" id="screening_pie" value="screening_pie" class="with-gap radio-col-pink" checked/>
                <label for="screening_pie"><b>Screening Pie</b></label>
                <input type="radio" name="screening_pie_radio" id="request_pie" value="request_pie" class="with-gap radio-col-pink"/>
                <label for="request_pie"><b>Request Pie</b></label> -->
                <ul class="header-dropdown m-r--5">
                    <!-- <li>
                        <button id="refresh_screening">
                            <i class="material-icons">loop</i>
                        </button>
                    </li> -->
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">info_outline</i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <h5 class="text-center"><span class="badge bg-teal">Screening Pie Criteria</span></h5>
                            <table class="table">
                                <td>Pie is based on Screening done at schools by Screening team.It displays the screening data of the students and can get drilled up to students Electronic Health Record.</td>
                            </table>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div id="piechart" style=" height: 300px;"></div>
                <!-- <div id="piechart_3d" style="display: none;"></div> -->
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="header">
                <b>HB</b>
            </div>
            <div class="body"> 
            <div id="hb_overall"></div> 
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="header">
                <b>BMI</b>
            </div>
            <div class="body"> 
            <div id="bmi_overall"></div> 
            </div>
        </div>
    </div>
   
</div>
<!-- End Screening and requests cards Info -->

<!-- Second row card Info HB and BMI -->
<div class="row clearfix hidden">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="header" style="padding: 10px">
                <h2>Chronic Diseases</h2>
               
                <ul class="header-dropdown m-r--5">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="material-icons">info_outline</i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <h5 class="text-center"><span class="badge bg-teal">Request Pie Criteria</span></h5>
                            <table class="table">
                                <td>Request pie is shown based on health requests.It is displayed Year wise and can be fetched applying all global filters.</td>
                            </table>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div id="piechart_3d"></div>
            </div>
        </div>
    </div>
   
</div>
<!-- End Second row card Info HB and BMI -->

</div> <!-- Container Div End -->

<!-- Info criteria for hb and bmi -->

<div class="modal fade" id="HB_and_BMI_Criteria" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Criteria for HB and BMI status</h4>
            </div>
            <div class="modal-body">
                <div id="carousel-example-generic_2" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic_2" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic_2" data-slide-to="1"></li>
                    </ol>
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <!-- <img src="<?php //echo IMG ;?>/demo/s1.jpg" /> -->
                            <div class="card">
                                <div class="body bg-grey" style="height: 320px;">
                                    <div class="font-bold m-b--35" style="text-align: center;">HB CALCULATION
                                    <br>
                                    <br>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <td>Category</td>
                                                    <td>Range</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                 <tr>
                                                    <td>Very Severe</td>
                                                    <td> <= 6</td>
                                                </tr>
                                                <tr>
                                                    <td>Severe</td>
                                                    <td> Between 6 to 8</td>
                                                </tr>
                                                <tr>
                                                    <td>Moderate</td>
                                                    <td> Between 8.1 TO 10</td>
                                                </tr>
                                                <tr>
                                                    <td>Mild</td>
                                                    <td> Between 10.1 To 12</td>
                                                </tr>
                                                <tr>
                                                    <td>Normal</td>
                                                    <td> >=12</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card">
                                <div class="body bg-grey" style="height: 320px;">
                                    <div class="font-bold m-b--35" style="text-align: center;">BMI CALCULATION
                                    <br>
                                    <br>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <td>Category</td>
                                                    <td>Range</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Under Weight</td>
                                                    <td> <= 18.5</td>
                                                </tr>
                                                <tr>
                                                    <td>Normal</td>
                                                    <td> Between 18.5 TO 24.9</td>
                                                </tr>
                                                <tr>
                                                    <td>Over Weight</td>
                                                    <td> Between 25 To 29.9</td>
                                                </tr>
                                                <tr>
                                                    <td>Obese</td>
                                                    <td> >30</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Controls -->
                    <a class="left carousel-control" href="#carousel-example-generic_2" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic_2" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="modal-footer">
               
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="loading_modal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" id="loading">
            <center><img src="<?php echo(IMG.'loader.gif'); ?>" id="gif" ></center>
        </div>
    </div>
</div>

<!-- Card  Modal For Total School List info -->

<div class="modal fade" id="total_schools_listss" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h4 class="modal-title" id="defaultModalLabel">List of Institutions</h4>
           </div>
           <div class="modal-body" id="data_Scls">              
           </div>
           <div class="modal-footer">
               <button type="button" class="btn bg-teal waves-effect" data-dismiss="modal">CLOSE</button>
           </div>
       </div>
   </div>
</div>
<!-- End Card Model schools List -->


<form style="display: hidden" action="get_day_to_day_glance_data_fetching" method="POST" id="day_to_day_glance_form">
      <input type="hidden" id="to_date" name="today_date" value=""/>
      <input type="hidden" id="day_to_day_status" name="day_to_day_status" value=""/>
</form> 

<form style="display: hidden" action="tswreis_diseases_counts_report" method="POST" id="abnormality_list_clicked">
    <input type="hidden" id="abnormality_name" name="abnormality_name" value=""/>
    <input type="hidden" id="screen_academic_year" name="academic_year" value=""/>
    <input type="hidden" id="welfare_name" name="welfare_name" value=""/>
</form>

<form style="display: hidden" action="get_hospital_type_data_table" method="POST" id="hospital_type_table">
    <input type="hidden" id="start_date_type" name="start_date_type" value=""/>
        <input type="hidden" id="end_date_type" name="end_date_type" value=""/>
    <input type="hidden" id="hospital_value" name="hospital_value" value=""/>
</form>

<form style="display: hidden" action="get_school_health_status_zone_schools" method="POST" id="school_health_table">
    <input type="hidden" id="value_scl_zone" name="school_zone" value=""/>
    <input type="hidden" id="dist_id" name="dist_id">
    <input type="hidden" id="opt_selected" name="status">
</form>

<form style="display: hidden" action="get_hospitalized_data_table" method="POST" id="hospitalized_table">
   <!--  <input type="hidden" id="date_hospitalized" name="date_hospitalized" value=""/> -->
   <input type="hidden" id="start_date" name="start_date" value=""/>
   <input type="hidden" id="end_date" name="end_date" value=""/>
    <input type="hidden" id="request_type" name="request_type" value=""/>
    <input type="hidden" id="hospital_district" name="hospital_district" value=""/>
</form>

<form style="display: hidden" action="get_sanitation_report_school_wise" method="POST" id="sanitation_table">
    <input type="hidden" id="date_sani" name="today_date" value=""/>
    <input type="hidden" id="value_sani" name="sanitation_type" value=""/>
</form>

<form style="display: hidden" action="get_attendance_data_for_bar_schools" method="POST" id="attendance_table">
    <input type="hidden" id="value_attn" name="value_attn" value=""/>
    <input type="hidden" id="date_attendance" name="date_attendance" value=""/>
    <input type="hidden" id="atten_dist_id" name="dist_id" value=""/>
    <input type="hidden" id="atten_scl_id" name="scl_id" value=""/>
</form>

<form style="display: hidden" action="to_daily_health_request" method="POST" id="get_table">
    <input type="hidden" id="val_id" name="val_id" value=""/>
    <input type="hidden" id="date_id" name="date_id" value=""/>
    <input type="hidden" id="req_dist_id" name="dist_id" value=""/>
    <input type="hidden" id="scl_id" name="scl_id" value=""/>
</form>

 <form style="display: hidden" action="get_daily_updated_health_request" method="POST" id="get_table_update">
        <input type="hidden" id="sart_id" name="sart_id" value=""/>
        <input type="hidden" id="end_id" name="end_id" value=""/>
        <input type="hidden" id="district_id" name="district_id" value=""/>
        <input type="hidden" id="school_id" name="school_id" value=""/>        
        <input type="hidden" id="value_id" name="value_id" value=""/>        
    </form>

<form style="display: hidden" action="total_request_page" method="POST" id="get_school_for_request">
    <input type="hidden" id="val_req" name="val_req" value=""/>
   <!--  <input type="hidden" id="date_id" name="date_id" value=""/> -->
    <input type="hidden" id="req_dist" name="req_dist" value=""/>
    <input type="hidden" id="req_scl" name="req_scl" value=""/>
    <input type="hidden" id="req_academic" name="req_academic" value=""/>
</form>

<form style="display: hidden" action="get_chronic_students_from_pie" method="POST" id="chronic_table">
    <input type="hidden" id="chronic_symptom" name="chronic_symptom" value=""/>       
</form>

<form style="display: hidden" action="get_hb_overall_data_table_for_sponsored" method="POST" id="hb_overall_table">
    <input type="hidden" id="hb_welfare_name" name="welfare_name" value=""/>
    <input type="hidden" id="academic_year" name="academic_year" value=""/>
    <input type="hidden" id="hb_overall_type" name="hb_overall_type" value=""/>       
    <!-- <input type="hidden" id="hb_gender" name="hb_gender" value=""/>       --> 
</form>

<form style="display: hidden" action="get_hb_gender_wise_data_table" method="POST" id="hb_gender_wise_table">
    <input type="hidden" id="start_date_hb" name="start_date_hb" value=""/>
    <input type="hidden" id="end_date_hb" name="end_date_hb" value=""/>
    <input type="hidden" id="hb_type" name="hb_type" value=""/>       
    <input type="hidden" id="hb_gender" name="hb_gender" value=""/>       
</form>

 <form style="display: hidden" action="get_bmi_gender_wise_data_table_for_sponsored" method="POST" id="bmi_gender_wise_table">
   <input type="hidden" id="bmi_welfare_name" name="welfare_name" value=""/>
    <input type="hidden" id="bmi_academic_year" name="academic_year" value=""/>
    <input type="hidden" id="bmi_overall_type" name="bmi_overall_type" value=""/>       
</form>

<form style="display: none;" action="get_doc_res_students_daily_req" method="POST" id="get_students_with_doctor">
    <input type="hidden" name="get_stud_with_doc" id="get_stud_with_doc" value=""/>
    <input type="hidden" name="res_date_doc" id="res_date_doc" value=""/>
</form>

<form style="display: none;" action="get_admitted_students_school_with_span" method="POST" id="admitted_show_form">
    <input type="hidden" name="start_date_new" id="start_date_new" value=""/>
    <input type="hidden" name="end_date_old" id="end_date_old" value=""/>
    <input type="hidden" name="request_type_newsss" id="request_type_newsss" value=""/>
</form>

<form style="display: hidden" action="anemia_daily_health_request" method="POST" id="anemia_get_table">  
   <input type="hidden" id="hs_date" name="hs_date" value=""/>
   <input type="hidden" id="hs_responce" name="hs_responce" value=""/>
</form>

<form style="display: hidden" action="surgery_daily_health_request" method="POST" id="surgery_get_table">  
    <input type="hidden" id="date" name="date" value=""/>
    <input type="hidden" id="hs_surgery" name="hs_surgery" value=""/>
</form>

<form style="display: hidden" action="daily_doctor_visit_schools_list" method="POST" id="dr_visit_get_schools">  
   <input type="hidden" id="visiting_date" name="visiting_date" value=""/>
   <input type="hidden" id="doctor_visit" name="doctor_visit" value=""/>
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
<!-- Google Charts -->
<script src="<?php echo(MDB_JS.'loader.js'); ?>"></script>

<!-- Jquery Core Js -->
<script src="<?php echo MDB_PLUGINS."jquery/jquery.min.js"; ?>"></script>

<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
<script src="<?php echo JS; ?>/d3pie/d3.js"></script>


<!-- Waves Effect Plugin Js -->
<script src="<?php echo MDB_PLUGINS."node-waves/waves.js"; ?>"></script>

<!-- Autosize Plugin Js -->
<script src="<?php echo MDB_PLUGINS."autosize/autosize.js"; ?>"></script>

<script src="<?php echo(MDB_JS.'exporting.js'); ?>"></script>

 <script src="<?php echo(MDB_JS.'export-data.js'); ?>"></script>
 
<script src="<?php echo(MDB_JS.'accessibility.js'); ?>"></script>


<!-- Moment Plugin Js -->
<script src="<?php echo MDB_PLUGINS."momentjs/moment.js"; ?>"></script>

<!-- Custom Js -->
<script src="<?php echo(MDB_JS.'admin.js'); ?>"></script>
<script src="<?php echo(MDB_JS.'pages/forms/basic-form-elements.js'); ?>"></script>

<!-- Demo Js -->
<script src="<?php echo(MDB_JS.'demo.js'); ?>"></script>

<!-- Bootstrap Datepicker Plugin Js -->
<script src="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script> 

<?php include("inc/footer_bar.php"); ?>

<script type="text/javascript">

    $(document).on('click','.total_schools_list',function(){
    var uid = $(this).attr('uid');

       var urlLink = "<?php echo URL;?>";
       
       $.ajax({
           url : urlLink+'panacea_mgmt/getschoolsInformation',
           type : 'POST',
           data : '',
           success : function(data){
          
               var docs = $.parseJSON(data);

            data_table = '<table class="table table-bordered" id="more_requests"><thead><tr><th>District Name</th><th>Boys Schools</th><th>Girls Schools</th></tr></thead><tbody>';

             $.each(docs, function(){
               
             data_table = data_table+'<tr>';
             data_table = data_table+'<td>'+this.dist +'</td>';
             data_table = data_table+'<td>'+this.boy_scl +'</td>';
             data_table = data_table+'<td>'+this.grl_scl +'</td>';
             //data_table = data_table+'<td><button type="button" class="btn bg-green show_scl" id="show_scl">Show</button></td>';
             });
           data_table = data_table+'</tr></tbody></table>';
            $('#more_requests').each(function(){
            $('#show_scl').click(function(){
                var row = $('$this').closest("tr");
                var dist = row.find("td:eq(0)").text();
                alert(dist);
           });
        });
           
           $('#data_Scls').html(data_table);
           $("#total_schools_listss").modal("show");

           },
           error:function(XMLHttpRequest, textStatus, errorThrown)
           {
            console.log('error', errorThrown);
           }
       });

       $("#total_schools_list").modal("show")  
});

</script>

<script type="text/javascript">

//Date Filter Script
var today_date = $('#set_date').val();
$('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
$('#set_date').change(function(e){
        today_date = $('#set_date').val();
});

var today_date = $('#date_for_sani_attend').val();
//End Date Filter Script

/* Default Load functions calling */
default_filters();

/*Get Schools List Based on District*/
$('#district_filter').change(function(e){
    dist = $('#district_filter').val();
    dt_name = $("#district_filter option:selected").text();
   // alert(dist);
    var options = $("#school_filter");
    options.prop("disabled", true);
     $("#loading_modal").modal('show');
    $.ajax({
        url: 'get_schools_list_with_dist_name',
        type: 'POST',
        data: {"dist_id" : dist},
        success: function (data) {          
             $("#loading_modal").modal('hide');
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
/*End Get Schools List Based on District*/


$('.common_change').change(function(){
    default_filters();
});


$('#update_date_set').click(function(){

    get_day_to_day_update_request_for_bar();

});


function default_filters(){
   
    var district = $('.district_filter').val();
    var school = $('.school_filter').val();
    var academicYear = $('.academic_filter').val();
    var requestAcademicYear = $('.request_academic_filter').val();
    var gender =  $('.gender_filter').val();
    var welfares =  $('.welfare_filter').val();

    /* Screening Pie */
     $.ajax({

         url : 'get_screening_pie_values_for_sponsered',
         type: 'POST',
         data: {'academic_year':academicYear, 'welfare':welfares},
         success: function(data){
            $('#piechart').empty();
            $('#piechart_3d').empty();
            // $('#screen_pie_new_one').empty();
             var datas = $.parseJSON(data);
             new_screening_pie_data(datas);
             
         }

    });
    /*End Screening Pie */

    /*Cards Data*/
    $.ajax({
        url:'get_data_for_cards_for_csr',
        type:'POST',
        data:{'academic_year': academicYear, 'district_name':district, 'school_name':school, 'welfare':welfares},
        success: function(data){
            var data = $.parseJSON(data);
            
            show_cards_info(data);
        }
    });
    /*End Cards Data*/

   /*$.ajax({
        url:'get_chronic_counts_requests_pie',
        type:'POST',
        data:{},
        success:function(data){
            var datas = $.parseJSON(data);
           //console.log(data);
             show_chronic_request_bar(datas);
        }
    })*/
    /*End Chronic-Selected Pie*/

    get_bmi_gender_wise_bar();
    get_hb_gender_wise_bar();

};

 /*End Attendance Pie*/

$('#bmi_date_set').click(function(){
    get_bmi_gender_wise_bar();
});


/*Screening Pie*/
function new_screening_pie_data(datas){
    $('#piechart').empty();
    //$('#piechart_3d').empty();
   // $('#piechart_3d').hide();
    var pie = new d3pie("piechart", {
        "footer": {
            "color": "#999999",
            "fontSize": 10,
            "font": "open sans",
            "location": "bottom-left"
        },
        "size": {
            "canvasWidth": 450,
            //"pieOuterRadius": "90%"
            "canvasHeight": 350,
        },
        "data": {
            "sortOrder": "value-desc",
            "content": datas
        },
        "labels": {
            "outer": {
                "format": "label-value2",
                "pieDistance": 32
            },
            "inner": {
                "format": "value",
                "color": "#28b83a",
                "hideWhenLessThanPercentage": 3
            },
            "mainLabel": {
                "color": "#28b83a",
                "fontSize": 10
            },
            "percentage": {
                "color": "#ffffff",
                "decimalPlaces": 0
            },
            "value": {
                "color": "#FF0000",
                "fontSize": 10
            },
            "lines": {
                "enabled": true
            }
        },
        "tooltips": {
            "enabled": true,
            "type": "placeholder",
            "string": "{label}: {value}"
        },
        "effects": {
            "pullOutSegmentOnClick": {
                "effect": "linear",
                "speed": 400,
                "size": 8
            }
        },
        "misc": {
            "gradient": {
                "enabled": true,
                "percentage": 100
            },
            "canvasPadding": {
                "right": 3
            }
        },

        callbacks: {
            onClickSegment: function(a) {
                
                 var dats = a.data;
                 var label_name = dats.label;
                 //alert(label_name);
                 displayData(label_name);
                
                 function displayData(label_name) {
                        var academicYear = $('.academic_filter').val();
                        var welfares =  $('.welfare_filter').val();
                       
                        $('#screen_academic_year').val(academicYear);
                         $("#abnormality_name").val(label_name);
                         $("#welfare_name").val(welfares);
                        
                         $("#abnormality_list_clicked").submit();
                    }
                
            }
        }
                    
    });
};

/*End Screening Pie*/

/*Requests Pie*/
function show_total_request_bar(data){
    $('#piechart_3d').empty();
    var normal = data.normal;
    var emergency = data.emergency;
    var chronic = data.chronic;
    var cured = data.cured;

    if(normal == 0 && emergency == 0 && chronic == 0){
        $('#piechart_3d').html("No Data Found")
    }else{

        google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Requests', 'Count'],
          ['General',     normal],
          ['Emergency',  emergency],
          ['Chronic',  chronic],
          ['Cured',  cured],
          
        ]);

        var options = {
          title: 'Request Pie Info',
          pieSliceText: 'value',
          is3D: true,
          width: 450,
          height: 250, 
          tooltip: {
                text: 'value'
            }
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));

        function selectHandler() {
            var selectedItem = chart.getSelection()[0];
            if (selectedItem) {
              var value = data.getValue(selectedItem.row, 0);
             //alert('The user selected ' + value);
              //var date = $('#set_date').val();
              var district = $('.district_filter').val();
              var school = $('.school_filter').val();
              var academicYear = $('.requestAcademicYear').val();
              //var gender =  $('.gender_filter').val();
              //$('#date_req').val(date);
              $('#val_req').val(value);
              $('#req_dist').val(district);
              $('#req_scl').val(school);
              $('#req_academic').val(academicYear);
              $('#get_school_for_request').submit();
             
            }
        }

        google.visualization.events.addListener(chart, 'select', selectHandler);

        chart.draw(data, options);

      }


    }

    $(window).resize(function(){
    drawChart();
    }); 
    
};
/*End Requests Pie*/

/*Chronic Pie Info*/
function show_chronic_request_bar(datas){
    $('#chronic_pie_disease').empty();
    if(datas == "No Data Found"){
        $('#chronic_pie_disease').html('<h4>No Requests are there</h4>');
    }
    var chronic_pie_chart = Morris.Donut({
        element: chronic_pie_disease,
        data: datas,
        colors: ['rgb(233, 30, 99)', 'rgb(0, 188, 212)', 'rgb(255, 152, 0)', 'rgb(0, 150, 136)', 'rgba(0, 0, 255, 1)', 'rgb(60, 60, 60)', 'rgba(255, 99, 71, 0.6)', 'rgb(238, 130, 238)','rgba(0, 104, 0, 1)','rgba(255, 255, 0, 1)','rgb(255, 87, 51)'],
        formatter: function (y) {
            return y + ''
        }
        
     }).on('click', function (i, row) {  
        // Do your actions
        // Example:
        displayData(i, row);

    // Selects the element in the Donut
    chronic_pie_chart.select(i);
    // Display the corresponding data
    displayData(i, chronic_pie_chart.data[i]);

    function displayData(i, row) {        
        $('#chronic_symptom').val(row.label);
        $('#chronic_table').submit();
    }
    }); 
};

/*End Chronic Pie Info*/


/*Cards Data Info Table*/
function show_cards_info(data){
    
    $('.total_screened_stud_count').empty();
    $('.not_screened_stud').empty();
    $('.screened_school_count').empty();
    $('.not_screened_school_count').empty();
    
    var totalScreened_stud = data.screened_students;
    var notScreened_stud = data.not_screened_students;
    var screenedSchool = data.screened_schools;
    var notscreenedSchool = data.not_screened_schools;

    $('.total_screened_stud_count').html('<div class="number">'+totalScreened_stud+'</div>');
    $('.not_screened_stud').html('<div class="number">'+notScreened_stud+'</div>');
    $('.screened_school_count').html('<div class="number">00</div>');
    $('.not_screened_school_count').html('<div class="number">00</div>');

};
/*End Cards Data Info Table*/

// HB  Genderwise barchart

function get_hb_gender_wise_bar(){
    var academicYear = $('.academic_filter').val();
    var gender =  $('.gender_filter').val();
    var welfares =  $('.welfare_filter').val();
  
    $.ajax({
        url:'get_hb_gender_wise_data_count_from_screening',
        type:'POST',
        data : {"academic_year" : academicYear, "gender": gender, "welfares":welfares},
        success:function(data){
            var data = $.parseJSON(data);
            show_hb_overall_bar(data);
        }
    });
};

/*HB overall Bar Info*/
function show_hb_overall_bar(data){
    
    var severe       = data.severe_male;
    var moderate     = data.moderate_male;
    var mild         = data.mild_male;
    var normal       = data.normal_male;
      
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Count", { role: "style" } ],
        ["SEVERE", severe, "Red"],
        ["MODERATE", moderate, "Orange"],
        ["MILD", mild, "pink"],
        ["NORMAL", normal, "Green"]
      
      ]);


       var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        //title: "HB-Overall",
        width: 460,
        height: 255,
        bar: {groupWidth: "66%"},
       vAxis: {title: "Counts"},
        legend: { position: "none" },
      };

        var chart = new google.visualization.ColumnChart(document.getElementById('hb_overall'));      

        function selectHandler() {
            var selectedItem = chart.getSelection()[0];
            if (selectedItem) {
              var value = data.getValue(selectedItem.row, 0);
              var welfares =  $('.welfare_filter').val();
              var academicYear = $('.academic_filter').val();
              $('#hb_overall_type').val(value);
              $('#hb_welfare_name').val(welfares);
              $('#academic_year').val(academicYear);
              $('#hb_overall_table').submit();
             
            }
        }

        google.visualization.events.addListener(chart , 'select' ,selectHandler);

         chart.draw(view, options);
      }

    $(window).resize(function(){
        drawChart();
    });
};
/*End HB overall Bar Info*/

/*BMI Bar Info*/
function get_bmi_gender_wise_bar(){
    var academicYear = $('.academic_filter').val();
    var gender =  $('.gender_filter').val();
    var welfares =  $('.welfare_filter').val();
    $.ajax({
        url:'get_bmi_gender_wise_data_count_from_screening',
        type:'POST',
        data : {"academic_year" : academicYear, "gender": gender, "welfares":welfares},
        success:function(data){
            var data = $.parseJSON(data);
            show_bmi_overall_bar(data);
        }
    });
};

/*HB overall Bar Info*/
function show_bmi_overall_bar(data){
    
    var severe       = data.under_weight;
    var moderate     = data.over_weight;
    var mild         = data.obese;
    var normal       = data.normal;
      
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Count", { role: "style" } ],
        ["UNDER", severe, "Red"],
        ["OVER", moderate, "Orange"],
        ["OBESE", mild, "pink"],
        ["NORMAL", normal, "Green"]
      
      ]);


       var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        //title: "HB-Overall",
        width: 460,
        height: 255,
        bar: {groupWidth: "66%"},
       vAxis: {title: "Counts"},
        legend: { position: "none" },
      };

        var chart = new google.visualization.ColumnChart(document.getElementById('bmi_overall'));      

        function selectHandler() {
            var selectedItem = chart.getSelection()[0];
            if (selectedItem) {
              var value = data.getValue(selectedItem.row, 0);
              var welfares =  $('.welfare_filter').val();
              var academicYear = $('.academic_filter').val();
              
              $('#bmi_welfare_name').val(welfares);
              $('#bmi_academic_year').val(academicYear);
              $('#bmi_overall_type').val(value);
              $('#bmi_gender_wise_table').submit();
             
            }
        }

        google.visualization.events.addListener(chart , 'select' ,selectHandler);

         chart.draw(view, options);
      }

    $(window).resize(function(){
        drawChart();
    });
};
$('#hb_gender_wise_pie').click(function(){
    get_hb_gender_wise_bar();
});

$('#bmi_gender_wise_pie').click(function(){
    get_bmi_gender_wise_bar();
});

</script>

