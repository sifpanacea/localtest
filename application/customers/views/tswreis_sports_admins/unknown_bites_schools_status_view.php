<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Bites Schools Status";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa school pie"]["sub"]["bites_pie"]["active"] = true;
include("inc/nav.php");

?>
<style>


</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->

<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<div id="main" role="main">
    <?php
        //configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
        //$breadcrumbs["New Crumb"] => "http://url.com"
        include("inc/ribbon.php");
    ?>
    <!-- MAIN CONTENT -->
    <div id="content">
        
        <div class="row">
                
                <div class="col-xs-12 col-sm-6 col-md-10 col-lg-6">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <!-- new widget -->
                    <!-- Widget ID (each widget will need unique ID)-->
                    <div class="jarviswidget" id="wid-id-60" data-widget-editbutton="false">
                       
                       <!--  <header>
                           <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                           <h2>Schools Status</h2> 
                           <input type="radio" name="gender" class="student_type" value="" checked> Total Students
                           <input type="radio" name="gender" class="student_type" id="student_type_boys" value="Male"> Male
                           <input type="radio" name="gender" class="student_type" id="student_type_girls"value="Female"> Female
                       </header> -->

                        <!-- widget div-->
                        <div>

                            <!-- widget edit box -->
                            <div class="jarviswidget-editbox">
                                <!-- This area used as dropdown edit box -->

                            </div>
                            <!-- end widget edit box -->

                            <!-- widget content -->
                            <div class="col-md-12" id="loading_request_pie" style="display:none;">
                                    <center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
                                </div>
                                
                                <div id="request_pies">
                                <div class="row">
                                <br>
                                
                                <div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
                                    <div class="well well-sm well-light">
                                        <br>
                                        <div >
                                        
                                        <!-- widget content -->
                                        <div class="col-md-12" id="loading_request_pie" style="display:none;">
                                                <center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
                                            </div>
                                        
                                            <center><div id="pie_request"></div></center>
                                            <i><label id="request_note" style="display:none;">Note : Write something.</label></i>
                                            <form style="display: hidden" action="drill_down_chronic_request_to_students_load_ehr" method="POST" id="ehr_form">
                                              <input type="hidden" id="ehr_data" name="ehr_data" value=""/>
                                              <input type="hidden" id="ehr_navigation" name="ehr_navigation" value=""/>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                </div>
                                </div>
                                <!-- end widget content -->

                        </div>
                        <!-- end widget div -->

                    </div>
                    <!-- end widget -->
                    <!-- Widget ID (each widget will need unique ID)-->
                            <div class="jarviswidget" id="wid-id-4" data-widget-editbutton="false" hidden="hidden">
                              
                                <header>
                                    <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                                    <h2>Stacked Bar Graph </h2>

                                </header>

                                <!-- widget div-->
                                <div>

                                    <!-- widget edit box -->
                                    <div class="jarviswidget-editbox">
                                        <!-- This area used as dropdown edit box -->

                                    </div>
                                    <!-- end widget edit box -->

                                    <!-- widget content -->
                                    <div class="widget-body no-padding">

                                        <div id="stacked-bar-graph" class="chart no-padding"></div>

                                    </div>
                                    <!-- end widget content -->

                                </div>
                                <!-- end widget div -->

                            </div>
                            <br><br>
                            <!-- end widget -->
                    </article>
                    </div>
                
                    <div class="col-xs-12 col-sm-6 col-md-10 col-lg-6" id="school_info">
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <!-- new widget -->
                    <!-- Widget ID (each widget will need unique ID)-->
                    <div class="jarviswidget" id="wid-id-60" data-widget-editbutton="false">
                
                        <header>
                            <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                            <h2>Schools Info</h2>
                        </header>
                        <!-- widget div-->
                        <div>
                            <!-- widget edit box -->
                            <div class="jarviswidget-editbox">
                                <!-- This area used as dropdown edit box -->
                            </div>
                            <!-- end widget edit box -->

                            <!-- widget content -->
                            <div class="col-md-12" id="loading_request_pie" style="display:none;">
                                    <center><img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" ></center>
                                </div>
                                <div id="request_pies">
                                <div class="row">
                                <br>
                                
                                <div class="col-xs-12 col-sm-3 col-md-12 col-lg-12">
                                    <div class="well well-sm well-light">
                                        <br>
                                        <div >
                                        
                                            <div id="schools_name_table"></div>
                                            <i><label id="request_note" style="display:none;">Note : Write something.</label></i>
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                </div>
                                </div>
                                <!-- end widget content -->

                        </div>
                        <!-- end widget div -->

                    </div>
                    <!-- end widget -->
                    <!-- end widget -->
                    </article>
                    
                    </div>
            <!-- row -->
            <!-- end row -->
            </div>
        <!-- widget grid -->
                <!-- Modal -->
    <div class="modal fade" id="show_criteria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    School Status
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                
                </div>
                <div class="modal-body" id="show_criteria_modal_body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
            
    </div>
    <!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
            

<!-- ==========================CONTENT ENDS HERE ========================== -->
<?php 
    //include required scripts
    include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



<!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->

<script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.fillbetween.min.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.orderBar.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.pie.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.time.min.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.axislabels.js"></script>
<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
<script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>
<script src="<?php echo JS; ?>/morris/raphael.min.js"></script>
<script src="<?php echo JS; ?>/morris/morris.min.js"></script>

<?php 
    //include footer
    include("inc/footer.php"); 
?>

<script>
    $('#school_info').hide();
$(document).ready(function() {
    
    $("select").each(function () {
        $(this).val($(this).find('option[selected]').val());
    });
    
    var request_data = "";
    var request_navigation = [];
    previous_request_a_value = [];
    previous_request_fn = [];
    previous_request_title_value = [];
    previous_request_search = [];
    request_search_arr = [];
    var request_report = <?php echo $request_report?>;
    initialize_variables(<?php echo $request_report?>);

    function initialize_variables(request_report){

    init_request_pie(request_report);
    }
    
draw_request_pie();
draw_bar_chart_for_screening();

function init_request_pie(request_report){
    request_data = request_report;
    request_navigation = [];
    previous_request_a_value = [];
    previous_request_fn = [];
    previous_request_title_value = [];
    previous_request_search = [];
    request_search_arr = [];
}


function draw_request_pie(){
    if(request_data == 1){
        $("#pie_request").append('No positive values to dispaly');
    }else{
        request_navigation.push("Bites Health Status PIE");
    request_pie(request_data,"drill_down_chronic_issues_school_level");
}
}

function draw_bar_chart_for_screening()
{
    $.ajax({
        url : "get_counts_for_bar",
        type : "POST",
        success: function(doc_data){
            console.log('data=========',doc_data);
            result = $.parseJSON(doc_data);
            
        if ($('#stacked-bar-graph').length) {
             $.each(result,function(index, val){
                lable = val.label;
                count    = val.value;
                console.log(index);
                console.log(val.label);
                console.log(val);
            Morris.Bar({
                        element : 'stacked-bar-graph',
                        axes : false,
                        grid : false,
                        data : [{
                            year : '2015-2016',
                            y : count,
                            z : count,
                            a : count
                        }, {
                            year : '2011 Q2',
                            y : count,
                            z : count,
                            a : count
                        }, {
                            year : '2011 Q3',
                            y : count,
                            z : count,
                            a : count
                        }, {
                            year : '2011 Q4',
                            y : count,
                            z : count,
                            a : count
                        }],
                        xkey : 'year',
                        ykeys : ['y', 'z', 'a'],
                        labels : [lable, lable, lable],
                        stacked : true
                    });
            });
        }
        
        },
        error:function(XMLHttpRequest, textStatus, errorThrown)
        {
         console.log('error', errorThrown);
        }
    });
}
$(".student_type").each(function(){
    $(this).click(function (){
    var type = $(this).val();
    console.log("typeeeeee",type);
    $.ajax({
        url : "get_unknown_bite_students_values",
        method : "POST",
        data : {"student_type" : type},
        success:function(request_report){
            var data = $.parseJSON(request_report);         
            $("#pie_request" ).empty();
            request_pie(data,"drill_down_chronic_issues_school_level");
        //var naresh = $.parseJSON(request_report);
        
            //init_request_pie(request_report);
        },
        error:function(XMLHttpRequest, textStatus, errorThrown)
        {
         console.log('error', errorThrown);
        }
    });
})
});



function request_pie(data, onClickFn){
    var pie = new d3pie("pie_request", {
        header: {
            title: {
                text: request_navigation.join(" / ")
            }
        },
        size: {
            canvasHeight: 500,
            canvasWidth: 590
        },
        data: {
          content: data
        },
        misc: {
        colors: {
            segments: [
                "#C70039", "#42BF55", "#FFC300"
            ]
        }
    },
        labels: {
            
            "outer": {
            "pieDistance": 32
        },
            inner: {
                format: "value"
            },
            
            "mainLabel": {
            "fontSize": 11
        },
            
        },
        tooltips: {
            enabled: true,
            type: "placeholder",
            string: "{label}, {value}"
         },
         callbacks: {
                onClickSegment: function(a) {
                    console.log("Segment clicked! See the console for all data passed to the click handler.");
                    console.log(onClickFn);
                    if(onClickFn == "drill_down_chronic_issues_school_level"){
                        previous_request_a_value[0] = data.label;
                        previous_request_fn[0] = "drill_down_chronic_issues_school_level";
                        drill_down_chronic_issues_school_level(a);
                    }
                    
                }
            }
          
        });
}


function drill_down_chronic_issues_school_level(pie_data){
    $('#school_info').show();
    var schoolNames = pie_data.data.school_name;
    var criteria = pie_data.data.criteria;
    
    var table = "<table class='table display projects-table' id='schools_list_table'><thead><th>school_name</th><th>Show</th><th>Show</th></thead><tbody>"
    criteria.forEach(function(element) {
        
            table = table + '<tr>';
            table = table + '<td>'+element[0]+ '</td>';
            table = table + '<td class="details-control"><form action = "https://mednote.in/PaaS/healthcare/index.php/panacea_mgmt/get_unknown_bite_student_docs" method="post"><input type="hidden" name="labelColor" value=""><input type="hidden" name="schoolName" value="'+element[0]+'"><button  class="btn btn-primary btn-xs type="submit" >Show Students</button></td></form>';
            
                table = table + '<td><button class="btn btn-primary btn-xs" data-id="'+element+'" value="'+element+'">Show Criteria</button></td>';
            table = table + '</tr>';
    });
    $(document).on('click', 'button[data-id]', function (e)
    {
        var requested_to = $(this).attr('data-id');
        
        $('#show_criteria_modal_body').empty();
        var criteriaTable = requested_to.split(",");
        var finalStatus = criteriaTable.join("<br><hr>");
        
        var table="";
        var tr="";
        table += "<table class='table table-bordered'><thead><tr><th> Criteria </th></tr></thead><tbody>";
        
        table += "<tr><td>"+finalStatus+"<br></td></tr>"
        table += "</tbody></table>";
        $(table).appendTo('#show_criteria_modal_body'); 
        $('#show_criteria').modal('show');
    });
    table = table + '</tbody></table>';
    $("#schools_name_table").html(table);   
    $('#schools_list_table').DataTable();

    $.ajax({
        url: 'drill_down_chronic_issues_school_level',
        type: 'POST',
        data: {"data" : JSON.stringify(pie_data.data) },
        success: function (data) {
            //console.log(data);
            var content = $.parseJSON(data);
            //console.log(content);
            $( "#pie_request" ).empty();
            $("#pie_request").append('<button class="btn btn-primary pull-right" id="request_back_btn" ind= "0"> Back </button>');

            request_navigation.push(pie_data.data.label);
            request_pie(content,"drilldown_chronic_request_to_districts");
            
            },
            error:function(XMLHttpRequest, textStatus, errorThrown)
            {
             console.log('error', errorThrown);
            }
    });
}

function drilldown_chronic_request_to_districts(pie_data){
    status_type = $('#status_type').val();
    $.ajax({
        url: 'drilldown_chronic_request_to_districts',
        type: 'POST',
        data: {"data" : JSON.stringify(pie_data), "status_type" : status_type},
        success: function (data) {
            //console.log(data);
            var content = $.parseJSON(data);
            //console.log(content);
            $( "#pie_request" ).empty();
            $("#pie_request").append('<button class="btn btn-primary pull-right" id="request_back_btn" ind= "1"> Back </button>');

            request_navigation.push(pie_data[1]);
            request_pie(content,"drilldown_chronic_request_to_school");
            
            },
            error:function(XMLHttpRequest, textStatus, errorThrown)
            {
             console.log('error', errorThrown);
            }
    });
}

function drilldown_chronic_request_to_school(pie_data){
    status_type = $('#status_type').val();
    $.ajax({
        url: 'drilldown_chronic_request_to_school',
        type: 'POST',
        data: {"data" : JSON.stringify(pie_data), "status_type" : status_type},
        success: function (data) {
            console.log(data);
            var content = $.parseJSON(data);
            console.log(content);
            $( "#pie_request" ).empty();
            $("#pie_request").append('<button class="btn btn-primary pull-right" id="request_back_btn" ind= "2"> Back </button>');

            request_navigation.push(pie_data[1]);
            request_pie(content,"drilldown_chronic_request_to_students");
            
            },
            error:function(XMLHttpRequest, textStatus, errorThrown)
            {
             console.log('error', errorThrown);
            }
    });
}

function drilldown_chronic_request_to_students(pie_data){
    status_type = $('#status_type').val();
    $.ajax({
        url: 'drilldown_chronic_request_to_students',
        type: 'POST',
        data: {"data" : JSON.stringify(pie_data), "status_type" : status_type},
        success: function (data) {
            console.log(data);
            $("#ehr_data").val(data);
            request_navigation.push(pie_data[1]);
            $("#ehr_navigation").val(request_navigation.join(" / "));
            
            $("#ehr_form").submit();
            
            },
            error:function(XMLHttpRequest, textStatus, errorThrown)
            {
             console.log('error', errorThrown);
            }
        });
}

//stacked bar
            
});     
        

//===================================drill down pie======================
//===================================end of dril down pie================
</script>

