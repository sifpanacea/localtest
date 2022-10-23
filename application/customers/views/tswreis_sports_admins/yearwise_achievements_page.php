<?php $main_nav = "Reports"; $current_page = "Diseases Counts"; ?>
<?php include("inc/header_bar.php"); ?>
<br><br>
<br><br>
<br><br>
<div class="container-fluid">
   <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Yearwise Acheivements and Medals Data</h2>
                     <ul class="header-dropdown m-r--5">
                        <div class="button-demo">
                        <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                        </div>
                    </ul>                          
                </div>
                <div class="body">
                       <div class="row clearfix">
                        <div class="col-sm-3">
                            <label>Select Academic Year</label>
                        <?php if(isset($academic) && !empty($academic)): ?>
                            <input type="hidden" name="academic_name" id="academic_name" value="<?php echo $academic; ?>">
                            <select id="academic_from_pie" class="form-control academic_year common_change">
                                <option value="2019-20" selected="">2019-2020 AcademicYear</option>
                                <option value="2018-19">2018-2019 AcademicYear</option>
                                <option value="2017-18">2017-2018 AcademicYear</option>
                                <option value="2016-17">2016-2017 AcademicYear</option>
                                <option value="2015-16">2015-2016 AcademicYear</option>
                            </select>

                        <?php else: ?>
                            <select id="academic_year" class="form-control academic_year common_change">
                                <option value="2019-20" selected="">2019-2020 AcademicYear</option>
                                <option value="2018-19">2018-2019 AcademicYear</option>
                                <option value="2017-18">2017-2018 AcademicYear</option>
                                <option value="2016-17">2016-2017 AcademicYear</option>
                                <option value="2015-16">2015-2016 AcademicYear</option>
                            </select>
                        <?php endif; ?>
                        </div>
                        <div class="col-sm-3">
                            <label>Select Level Of Achievement</label>
                            <?php if(isset($abnormality) && !empty($abnormality)): ?>
                            <input type="hidden" name="abnormalities_name" id="abnormalities_name" value="<?php echo $abnormality; ?>">
                                 <select id="abnormalities_from_pie" class="form-control abnormalities common_change">
                                  <option value="International">International</option>
                                  <option value="National">National</option>
                                  <option value="State">State</option>
                                  
                                </select>

                            <?php else: ?>
                             <select id="abnormalities" class="form-control abnormalities common_change">
                                <option value="International">International</option>
                                <option value="National">National</option>
                                <option value="State">State</option>
                                
                            </select>
                            <?php endif; ?>
                      
                        </div>
                        <!-- District Filter -->
                        <div class="col-sm-3">
                            <label>Medals List</label>
                            <select class="form-control show-tick medals_filter common_change" id="medals_filter">
                                <option value="All"  selected="">All</option>
                                <option value="Bronze">Bronze</option>
                                <option value="Gold">Gold</option>
                                <option value="Participation">Participation</option>
                                <option value="Silver">Silver</option>
                                
                            </select>
                        </div>
                       
                    </div>
                       
                    <div class="demo-radio-button">
                        <input name="group5" type="radio" id="radio_30" class="with-gap radio-col-red" value = "pie" checked="">
                        <label for="radio_30" >Pie Chart View</label>
                        <input name="group5" type="radio" id="radio_31" class="with-gap radio-col-pink" value = "table">
                        <label for="radio_31">Table View</label>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-12" id="pie_view">
                            <center>
                                <div id="screen"></div>
                            </center>
                        </div>
                        
                        
                        <div class="col-md-12" id="table_view" style="display: none;">
                            <div id="stud_report">
                            </div>
                        </div>
                    </div>
                    </div>
            </div>
        </div>
    </div>
</div>
            <!-- #END# Exportable Table -->                     
           <!--  <form style="display: hidden" action="<?php// echo URL; ?>tswreis_sports_mgmt/get_requests_by_medals_list" method="POST" id="symptom_list_from">
            
              <input type="hidden" id="academic" name="academic" value=""/>
              <input type="hidden" id="req_type" name="req_type" value=""/>             
              <input type="hidden" id="medal_list" name="medal_list" value=""/>

            </form> -->

<form style="display: hidden" action="<?php echo URL; ?>tswreis_sports_mgmt/get_requests_by_medals_lis" method="POST" id="symptom_list_from">
    <input type="hidden" id="academic" name="academic" value=""/>
    <input type="hidden" id="req_type" name="req_type" value=""/>             
    <input type="hidden" id="medal_list" name="medal_list" value=""/>
</form>
        
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="loading_modal">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" id="loading">
            <center><img src="<?php echo(IMG.'loader.gif'); ?>" id="gif" ></center>
        </div>
    </div>
</div>
   
    <!-- <form style="display: hidden" action="get_students_by_requests_symptom" method="POST" id="abnormalities_form">
        <input type="hidden" id="symptom" name="symptom" value=""/>
    </form> -->
    <form style="display: hidden" action="get_schools_by_symptom_for_requests" method="POST" id="abnormality_list_clicked">
        <input type="hidden" id="academic_pie" name="academic" value=""/>
        <input type="hidden" id="abnormality_pie" name="symptom_name" value=""/>
        <input type="hidden" id="poname_pie" name="po_name" value=""/>
        <input type="hidden" id="schoolname_pie" name="school_name" value=""/>
        <input type="hidden" id="req_pie" name="req_type" value=""/>
    </form>

<!-- D3 Pie script -->
    <script src="<?php echo JS; ?>/d3pie/d3.js"></script>
    <script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
    
 <!-- Jquery Core Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery/jquery.min.js'); ?>"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo(MDB_PLUGINS.'bootstrap/js/bootstrap.js'); ?>"></script>


    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery-slimscroll/jquery.slimscroll.js'); ?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'node-waves/waves.js'); ?>"></script>
 
  <!-- Jquery DataTable Plugin Js -->
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/jquery.dataTables.js"; ?>'></script>
    <script src="<?php echo MDB_PLUGINS."jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"; ?>"></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/dataTables.buttons.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.flash.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/jszip.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/pdfmake.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/vfs_fonts.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.html5.min.js"; ?>'></script>
    <script src='<?php echo MDB_PLUGINS."jquery-datatable/extensions/export/buttons.print.min.js"; ?>'></script>

    <script src="<?php echo(MDB_JS.'admin.js'); ?>"></script>
    <script src='<?php echo MDB_JS."pages/tables/jquery-datatable.js"; ?>'></script>

    <!-- Demo Js -->
    <script src="<?php echo(MDB_JS.'demo.js'); ?>"></script>
    <script type="text/javascript">
     
$(document).ready(function(){

get_requests();

    $("input[type='radio']").click(function(){
        var check = $("input[type='radio']:checked").val();
         if(check == 'pie'){
        $('#table_view').hide();
        $('#pie_view').show();
        }else{
            $('#table_view').show();
            $('#pie_view').hide();
        }

    });

    var myOptions = $('#abnormalities_name').val();
    //alert(myOptions);
    var mySelect = $('#abnormalities_from_pie');
    mySelect.append($('<option></option>').prop("selected", true).val(myOptions).html(myOptions));

    var myAcademic = $('#academic_name').val();

    var myOption = $('#academic_from_pie');
    if(myAcademic == 'mh_screening_report_col')
    {

        myOption.append($('<option> /').prop("selected", true).val('2018-2019').html('2018-2019 Academic Year'));
    }else{
        myOption.append($('<option> /').prop("selected", true).val('2019-2020').html('2019-2020 Academic Year'));
    }



$('.common_change').change(function(){
    get_requests();
});

function get_requests()
{
    var academicYear = $('.academic_year').val();
    var reqType = $('.abnormalities').val();
    var medal = $('.medals_filter').val();
   
    //alert(academicYear);
    //alert(reqType);
   // alert(medal);
    /*alert(academicYear);
    alert(reqType);
    alert(dist);
    alert(schoolName);*/
   
    $("#loading_modal").modal('show');
    $.ajax({
        url : 'total_achievements_pie_with_filters',
        type : 'POST',
        data : {"req_academic": academicYear, "req_type": reqType, "medal_select": medal},
        success: function(data){
           $("#loading_modal").modal('hide');
            //result = $.parseJSON(data);
             //console.log(result);

            result = $.parseJSON(data);

            console.log(result);

            if(result == "No Data Available")
            {
                $('#stud_report').html('<h4 class="text-success">'+result+'</h4>');
                $('#screen').html('<h4 class="text-success">'+result+'</h4>');

            }
            else
            {
                //console.log('+school_name+',result);
                display_data_table(result, medal);
                screen_piechart(result);
            };

           
        }

    });

}

    

  
    $('.select_dt_name').change(function(e){
        dist = $('.select_dt_name').val();
        //academicYear = $('.academic_year').val();
        //alert(academicYear);
        var options = $("#school_name");
        options.prop("disabled", true);
        
    if( dist != "All" ){
        options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching schools list..."));
        $.ajax({
            url: 'get_schools_list_with_dist_name',
            type: 'POST',
            data: {"dist_id" : dist},
            success: function (data) {    
                result = $.parseJSON(data);
               // var data =result.screened_schools_list.school;
               /* var screenedSchoolsCount = result.screened_schools_count;
                $('#screenedSchoolsCountBySelectedDistrict').html("<strong><i class='material-icons'>info_outline</i>Total Screened Schools Count in "+dist+" PO : <span class='badge bg-orange'>"+screenedSchoolsCount+"</span></strong>");*/
                options.prop("disabled", false);
                options.empty();
                //options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Select school"));
                options.append($("<option />").val("All").text("All"));
                $.each(result,function(){
                    options.append($("<option />").val(this.school_name).text(this.school_name));
                });  
                },
                error:function(XMLHttpRequest, textStatus, errorThrown)
                {
                 console.log('error', errorThrown);
                }
            });
        }else{
            
            $.SmartMessageBox({
                title : "Alert !",
                content : "This will take long time to display students report. Are you sure you want to continue.",
                buttons : '[No][Yes]'
            }, function(ButtonPressed) {
                if (ButtonPressed === "Yes") 
                {
                    $.ajax({
                        url: 'get_schools_list',
                        type: 'POST',
                        data: {"dist_id" : dist},
                        success: function (data) {          
                            console.log(data);
                            result = $.parseJSON(data);
                            console.log(result);
                            display_data_table(result);
                                    
                            },
                            error:function(XMLHttpRequest, textStatus, errorThrown)
                            {
                             console.log('error', errorThrown);
                            }
                        });
                }
                if (ButtonPressed === "No")
                {
                    
                }
                
           });
            
        }
    });

 
function display_data_table(result, schoolName){

    //alert(schoolName);
        if(result.length > 0){
            data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="symptom_list"><thead><tr><th>S.No</th><th>Sport Name</th><th>No.of Students</th><th>Action</th></tr></thead><tbody>';

           for(var i=0; i<result.length; i++)
            {
                data_table = data_table + '<tr>';
                data_table = data_table + '<td>'+(i+1)+ '</td>';
                data_table = data_table + '<td>'+result[i].label+'</td>';
                data_table = data_table + '<td><span class="badge bg-teal">'+result[i].value+'</span></td>';
                if((schoolName == 'All') || (schoolName == 'medal_select') || (schoolName == false) || (schoolName == null) || (schoolName == "") ){

                data_table = data_table + '<td><button type="button" class="btn bg-teal waves-effect btnShow">Show Schools</button></td>';
            }else{
                data_table = data_table + '<td><button type="button" class="btn bg-teal waves-effect btnSchool">Show Students</button></td>';
            }


                data_table = data_table + '</tr>';
            }
            data_table = data_table + '</tbody></table>';
            $("#stud_report").html(data_table);

          /*  $('#symptom_list').DataTable({
            "paging": true,
            dom: 'Bfrtip',
            buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
            });*/
            //=====================================================================================================
            }else{
                $("#stud_report").html('<h5>No students to display for this school</h5>');
            }
            $("#symptom_list").each(function(){
       
            $('.btnShow').click(function (){


                var academicYear = $('.academic_year').val();
                var reqType = $('.abnormalities').val();
                var medal = $('.medals_filter').val();
              
                $("#academic").val(academicYear);
                $("#req_type").val(reqType);
                $("#medal_list").val(medal);             

                 var currentRow=$(this).closest("tr"); 
                 var symptom=currentRow.find("td:eq(1)").text(); // get current row 2nd TD
                $("#symptom_name").val(symptom);
               
                $("#symptom_list_from").submit();
            });

            $('.btnSchool').click(function(){

                 var currentRow=$(this).closest("tr"); 
                 var symptom=currentRow.find("td:eq(1)").text(); // get current row 2nd TD
                 students_with_scl_name(symptom);
              

            });
         });
    }

    function students_with_scl_name(symptom){

    	var academicYear = $('.academic_year').val();
        var distName = $('.select_dt_name').val();
        var schoolName = $('#school_name').val();
        var reqType = $('.abnormalities').val();
        $("#scl_academic").val(academicYear);
        $("#scl_po").val(distName);
        $("#scl_req").val(reqType);
        $("#scl_school").val(schoolName);
        $("#scl_symptom_name").val(symptom);
    	$("#symptom_students_list").submit();
    }

     //PIe chart code
        function screen_piechart(result){

                $('#screen').empty();

                    var pie = new d3pie("screen", {

                        "header": {
                                "title": {
                                    "text": "Yearly Sports Pie",
                                    "fontSize": 24,
                                    "font": "open sans"
                                },
                                "subtitle": {
                                    "text": "Based on selected Filters.",
                                    "color": "#999999",
                                    "fontSize": 12,
                                    "font": "open sans"
                                },
                                "titleSubtitlePadding": 9
                            },
                        
                        "footer": {
                            "color": "#999999",
                            "fontSize": 10,
                            "font": "open sans",
                            "location": "bottom-left"
                        },
                        "size": {
                            "canvasWidth": 600,
                            //"pieOuterRadius": "90%"
                            canvasHeight: 500,
                           
                        },
                        "data": {
                            "sortOrder": "value-desc",
                            "content": result
                        },
                        "labels": {
                            "outer": {
                                "format": "label-value2",
                                "pieDistance": 32
                            },
                            "inner": {
                            "format": "value",
                            "hideWhenLessThanPercentage": 3
                            },
                            "mainLabel": {
                                "color": "#d71d1d",
                                "fontSize": 10
                            },
                            "percentage": {
                                "color": "#ffffff",
                                "decimalPlaces": 0
                            },
                            "value": {
                                "color": "#28b83a",
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
                                 var symptom = dats.label;
                                 //alert(symptom);
                                 displayData(symptom);
                                
                                 function displayData(symptom) {
                                        var academicYear = $('.academic_year').val();
                                        var Abnormalitie = $('.abnormalities').val();
                                        var poName = $('.select_dt_name').val();
                                        var schoolName = $('#school_name').val();
                                        var reqType = $('.abnormalities').val();
                                        
                                        $('#academic_pie').val(academicYear);
                                        $("#abnormality_pie").val(symptom);
                                        $("#poname_pie").val(poName);
                                        $("#schoolname_pie").val(schoolName);
                                        $("#req_pie").val(reqType);
                                        if(schoolName == 'All_dist' || schoolName == 'All'){
                                        	$("#abnormality_list_clicked").submit();
                                        }else{
                                        	students_with_scl_name(symptom);
                                        }
                                    }
                                
                            }
                        }
                            
            });
        }
    });
</script>
<?php include("inc/message_status.php"); ?>
 





 
