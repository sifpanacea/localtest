<?php $current_page="";?>
<?php $main_nav=""; ?>
<?php
include('inc/header_bar.php');

?>
<br>
<br>
<br>
<br>
<br>
 <!-- <section class="content"> -->
        <div class="container-fluid">
           <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Disease wise counts from Screening</h2>
                             <ul class="header-dropdown m-r--5">
                                <div class="button-demo">
                                <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                                </div>
                            </ul>
                          
                        </div>
                        <div class="body">
                               <div class="row clearfix">
                                <div class="col-sm-3">
                                    <select id="screening_pie_span" class="form-control screening_pie_span">
                                        <option value="2021-2022">2021-2022 Academic Year</option>
                                        <option value="2020-2021">2020-2021 Academic Year</option>
                                        <option value="2019-2020">2019-2020 Academic Year</option>
                                        <option value="2018-2019">2018-19 Academic Year</option>
                                        <option value="2017-2018">2017-18 Academic Year</option>
                                        <option value="2016-2017">2016-17 Academic Year</option>
                                        <option value="2015-2016">2015-16 Academic Year</option>
                                    </select>
                                
                                </div>
                                <div class="col-sm-3">
                                    <?php if(isset($abnormality) && !empty($abnormality)): ?>
                                    <input type="hidden" name="abnormalities_name" id="abnormalities_name" value="<?php echo $abnormality; ?>">
                                        <select id="abnormalities_from_pie" class="form-control abnormalities">
                                            <option value="Ortho Abnormalities">Ortho Abnormalities</option>
                                            <option value="Postural Abnormalities">Postural Abnormalities</option>
                                            <option value="Defects At Birth">Defects At Birth</option>
                                            <option value="Deficiencies">Deficiencies</option>
                                            <option value="Childhood Diseases">Childhood Diseases</option>
                                            <option value="General Abnormalities">General Abnormalities</option>
                                            <option value="Dental Abnormalities">Dental Abnormalities</option>
                                            <option value="Eye Abnormalities">Eye Abnormalities</option>
                                            <option value="Auditory And Speech Abnormalities">Auditory And Speech Abnormalities</option>
                                        </select> <i></i> </label>
                                    <?php else: ?>
                                    
                                        <select id="abnormalities" class="form-control abnormalities">
                                            <option value="Ortho Abnormalities">Ortho Abnormalities</option>
                                            <option value="Postural Abnormalities">Postural Abnormalities</option>
                                            <option value="Defects At Birth">Defects At Birth</option>
                                            <option value="Deficiencies">Deficiencies</option>
                                            <option value="Childhood Diseases">Childhood Diseases</option>
                                            <option value="General Abnormalities">General Abnormalities</option>
                                            <option value="Dental Abnormalities">Dental Abnormalities</option>
                                            <option value="Eye Abnormalities">Eye Abnormalities</option>
                                            <option value="Auditory And Speech Abnormalities">Auditory And Speech Abnormalities</option>
                                        </select> <i></i> </label>
                                        <?php endif; ?>
                                </div>
                    <div class="col-sm-3">
                         <select id="select_dt_name" class="form-control select_dt_name">
                            <option value='All' selected="">All</option>
                                <?php if(isset($distslist)): ?>
                                    <?php foreach ($distslist as $dist):?>
                                    <option value='<?php echo $dist['_id']?>' ><?php echo ucfirst($dist['dt_name'])?></option>
                                    <?php endforeach;?>
                                    <?php else: ?>
                                    <option value="1"  disabled="">No district entered yet</option>
                                <?php endif ?>
                        </select>
                    </div>
                                <div class="col-sm-3">
                                     <select id="school_name" class="form-control" disabled=true>
                                        <option value="All_po" selected="">All</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <span id="screenedSchoolsCountBySelectedDistrict"></span>
                                </div>
                                <div class="demo-radio-button">
                                    <input name="group5" type="radio" id="radio_30" class="with-gap radio-col-pink" value = "pie" checked="">
                                    <label for="radio_30" >Pie Chart View</label>
                                    <input name="group5" type="radio" id="radio_31" class="with-gap radio-col-pink" value = "table">
                                    <label for="radio_31">Table View</label>
                                </div>
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
               <form style="display: hidden" action="<?php echo URL; ?>bc_welafre_mgmt/get_schools_by_symptom" method="POST" id="symptom_list_from">
              <input type="hidden" id="symptom_name" name="symptom_name" value=""/>
              <input type="hidden" id="academic" name="academic" value=""/>
              <input type="hidden" id="po" name="po_name" value=""/>
              <input type="hidden" id="school" name="school_name" value=""/>
            </form>

            <form style="display: hidden" action="<?php echo URL; ?>bc_welfare_mgmt/get_students_by_symptom" method="POST" id="symptom_students_list">
              <input type="hidden" id="academic_stud_btn" name="academic_year" value=""/>
              <input type="hidden" id="symptom_name_stud_btn" name="symptom_name" value=""/>
              <input type="hidden" id="school_stud_btn" name="school_name" value=""/>
            </form>
        </div>
        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="loading_modal">
              <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content" id="loading">
                    <center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
                    </div>
              </div>
        </div>
 <!--</section> -->
    <form style="display: hidden" action="get_abnormal_students_list" method="POST" id="abnormalities_form">
        <input type="hidden" id="symptom" name="symptom" value=""/>
    </form>
    <form style="display: hidden" action="get_schools_by_symptom" method="POST" id="abnormality_list_clicked">
        <input type="hidden" id="academic_pie" name="academic" value=""/>
        <input type="hidden" id="abnormality_pie" name="symptom_name" value=""/>
        <input type="hidden" id="poname_pie" name="po_name" value=""/>
        <input type="hidden" id="schoolname_pie" name="school_name" value=""/>
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

<script>
$(document).ready(function() {

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
    if(myAcademic == '2019-2020')
    {

        myOption.append($('<option> /').prop("selected", true).val('2018-19 Academic Year').html('2018-2019 Academic Year'));
    }else{
        myOption.append($('<option> /').prop("selected", true).val('2018-19 Academic Year').html('2019-2020 Academic Year'));
    }

    $('#select_dt_name').change(function(e){
        dist = $('#select_dt_name').val();
        //alert(dist);
        var options = $("#school_name");
        options.prop("disabled", true);
        
    if( dist != "All" ){
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
                //options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Select school"));
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

// Table and Screening Pie

    //var vacademicYear = $('#academic_year').val();
    var academicYear = $('.screening_pie_span option:selected').val();
    var Abnormalitie = $('.abnormalities').val();
    var poName = $('.select_dt_name').val();
    var schoolName = $('#school_name').val();

    
    //dist = $('#select_dt_name option:selected').text();
    //alert(academicYear);
        //alert(Abnormalitie);
        $("#loading_modal").modal('show');
    $.ajax({
        url : 'get_all_screening_diseases_counts',
        type : 'POST',
        data : {"academic_year": academicYear, "abnormalities": Abnormalitie, "po_name": poName, "school_name": schoolName},
        success: function(data){
           $("#loading_modal").modal('hide');
            //result = $.parseJSON(data);
             //console.log(result);

            result = $.parseJSON(data);
            if(result == "No Problems found")
            {
                $('#stud_report').html('<h4 class="text-success">'+result+'</h4>');
                $('#screen').html('<h4 class="text-success">'+result+'</h4>');

            }
            else
            {
                //console.log('+school_name+',result);
                display_data_table(result, schoolName);
                screen_piechart(result);
            };

           
        }

    });

    $('.abnormalities').change(function(e){

         $("#loading_modal").modal('show');
        var academicYear = $('.screening_pie_span').val();
        var Abnormalitie = $('.abnormalities').val();
        var poName = $('.select_dt_name').val();
        var schoolName = $('#school_name').val();

        $.ajax({
        url : 'get_all_screening_diseases_counts',
        type : 'POST',
        data : {"academic_year": academicYear, "abnormalities": Abnormalitie, "po_name": poName, "school_name": schoolName},
        success: function(data){

             $("#loading_modal").modal('hide');
           
            result = $.parseJSON(data);
             //console.log(result);

            result = $.parseJSON(data);
            if(result == "No Problems found")
            {
                $('#stud_report').html('<h4 class="text-success">'+result+'</h4>');
                $('#screen').html('<h4 class="text-success">'+result+'</h4>');

            }
            else
            {
                //console.log('+school_name+',result);
                display_data_table(result, schoolName);
                screen_piechart(result);
            }
           
        }

    });

    });

     $('.screening_pie_span').change(function(e){

        $("#loading_modal").modal('show');
        var academicYear = $('.screening_pie_span').val();
        var Abnormalitie = $('.abnormalities').val();
        var poName = $('.select_dt_name').val();
        var schoolName = $('#school_name').val();


        //alert(academicYear);
        //alert(Abnormalitie);
        $.ajax({
        url : 'get_all_screening_diseases_counts',
        type : 'POST',
        data : {"academic_year": academicYear, "abnormalities": Abnormalitie, "po_name": poName, "school_name": schoolName},
        success: function(data){

            $("#loading_modal").modal('hide');
           
            result = $.parseJSON(data);
             //console.log(result);

            result = $.parseJSON(data);
            if(result == "No Problems found")
            {
                $('#stud_report').html('<h4 class="text-success">'+result+'</h4>');
                $('#screen').html('<h4 class="text-success">'+result+'</h4>');

            }
            else
            {
                //console.log('+school_name+',result);
                display_data_table(result, schoolName);
                screen_piechart(result);
            }
           
        }

    });

    });

    //PO Change
    $('.select_dt_name').change(function(e){
        $("#loading_modal").modal('show');
        var academicYear = $('.screening_pie_span option:selected').val();
        var Abnormalitie = $('.abnormalities').val();
        var poName = $('.select_dt_name').val();
        var schoolName = $('#school_name').val();

            $.ajax({
            url : 'get_all_screening_diseases_counts',
            type : 'POST',
            data : {"academic_year": academicYear, "abnormalities": Abnormalitie, "po_name": poName, "school_name": schoolName},
            success: function(data){
                $("#loading_modal").modal('hide');
               
                result = $.parseJSON(data);
                 //console.log(result);

                result = $.parseJSON(data);
                if(result == "No Problems found")
                {
                    $('#stud_report').html('<h4 class="text-success">'+result+'</h4>');
                    $('#screen').html('<h4 class="text-success">'+result+'</h4>');

                }
                else
                {
                    //console.log('+school_name+',result);
                    display_data_table(result, schoolName);
                    screen_piechart(result);
                }
               
            }

        });

    });

    //School Change
    $('#school_name').change(function(e){
        $("#loading_modal").modal('show');
        var academicYear = $('.screening_pie_span option:selected').val();
        var Abnormalitie = $('.abnormalities').val();
        var poName = $('.select_dt_name').val();
        var schoolName = $('#school_name').val();

       /* alert(academicYear);
        alert(Abnormalitie);
        alert(poName);
        alert(schoolName);*/

            $.ajax({
            url : 'get_all_screening_diseases_counts',
            type : 'POST',
            data : {"academic_year": academicYear, "abnormalities": Abnormalitie, "po_name": poName, "school_name": schoolName},
            success: function(data){
                $("#loading_modal").modal('hide');
               
                result = $.parseJSON(data);
                 //console.log(result);

                result = $.parseJSON(data);
                if(result == "No Problems found")
                {
                    $('#stud_report').html('<h4 class="text-success">'+result+'</h4>');
                    $('#screen').html('<h4 class="text-success">'+result+'</h4>');

                }
                else
                {
                    //console.log('+school_name+',result);
                    display_data_table(result, schoolName);
                    screen_piechart(result);

                }
               
            }

        });

    });


  // ===================
     function display_data_table(result, schoolName){
             if(result.length > 0){
                 data_table = '<table class="table table-bordered table-striped table-hover dataTable js-exportable" id="symptom_list"><thead><tr><th>S.No</th><th>Symptom Name</th><th>No.of Students</th></tr></thead><tbody>';

                for(var i=0; i<result.length; i++)
                 {
                     data_table = data_table + '<tr>';
                     data_table = data_table + '<td>'+(i+1)+ '</td>';
                     data_table = data_table + '<td>'+result[i].label+'</td>';
                     data_table = data_table + '<td><span class="badge bg-teal">'+result[i].value+'</span></td>';
                     if((schoolName == 'All') || (schoolName == 'All_po') || (schoolName == false) || (schoolName == null) || (schoolName == "") ){

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

                     academicYear = $('.screening_pie_span').val();
                     var poName = $('.select_dt_name').val();
                     var schoolName = $('#school_name').val();
                     $("#academic").val(academicYear);
                     $("#po").val(poName);
                     $("#school").val(schoolName);

                      var currentRow=$(this).closest("tr"); 
                      var symptom=currentRow.find("td:eq(1)").text(); // get current row 2nd TD
                     $("#symptom_name").val(symptom);
                    
                     $("#symptom_list_from").submit();
                 });

                 $('.btnSchool').click(function(){

                     academicYear = $('.screening_pie_span').val();
                     //var poName = $('.select_dt_name').val();
                     var schoolName = $('#school_name').val();
                     $("#academic_stud_btn").val(academicYear);
                     //$("#po").val(poName);
                     $("#school_stud_btn").val(schoolName);

                      var currentRow=$(this).closest("tr"); 
                      var symptom=currentRow.find("td:eq(1)").text(); // get current row 2nd TD
                     $("#symptom_name_stud_btn").val(symptom);
                     
                     $("#symptom_students_list").submit();

                 });
              });
         }

  //PIe chart code
     function screen_piechart(result){

             $('#screen').empty();

                 var pie = new d3pie("screen", {

                     "header": {
                             "title": {
                                 "text": "Screening Pie",
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
                              var label_name = dats.label;
                              //alert(label_name);
                              displayData(label_name);
                             
                              function displayData(label_name) {
                                     var academicYear = $('.screening_pie_span').val();
                                     var Abnormalitie = $('.abnormalities').val();
                                     var poName = $('.select_dt_name').val();
                                     var schoolName = $('#school_name').val();
                                     
                                     $('#academic_pie').val(academicYear);
                                     $("#abnormality_pie").val(label_name);
                                     $("#poname_pie").val(poName);
                                     $("#schoolname_pie").val(schoolName);
                                     $("#abnormality_list_clicked").submit();
                                 }
                             
                         }
                     }
                         
         });
     }



  //================================

    

});

</script>
<!-- <?php //include("inc/message_status.php"); ?> -->
 





 