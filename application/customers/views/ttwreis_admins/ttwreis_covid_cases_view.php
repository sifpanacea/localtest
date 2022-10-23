<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Covid Cases";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");
//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["bmi_pie"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<script src="<?php echo JS; ?>/d3pie/d3.js"></script>

<div id="main" role="main">
<?php
      //configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
      //$breadcrumbs["New Crumb"] => "https://url.com"
      include("inc/ribbon.php");
    ?>
<div id="content">
<!-- ==========================CONTENT STARTS HERE ========================== -->
        <!-- MAIN PANEL -->
        <section id="widget-grid" class="content">
<div class="container-fluid">

<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
            
            <div class="row clearfix" style="margin-bottom: 10px;">
                <div class="col-sm-2">
                   
                </div>              
                  <div class="col-sm-3">              
            <input type="date" class="form-control date" id="passing_date" name="passing_date" data-dateformat="Y-m-d" value="<?php echo date('Y-m-d');?>" min="2019-01-01" max="2030-12-31">       
                  </div>
                  <div class="col-sm-3"> 
          <input type="date" class="form-control date" id="passing_end_date" name="passing_end_date" data-dateformat="Y-m-d" value="<?php echo date('Y-m-d');?>" min="2019-01-01" max="2030-12-31">                    
                  </div>
                  <div class="col-sm-2">
                    <div class="input-group-btn">
                    <button type="button" class="btn btn-success button_field" id="date_set" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
                      Set date
                      </button>
                    </div>
                  </div>
                  <div class="col-sm-1">
                      <button type="button" id="get_excel" class="btn bg-green btn-sm waves-effect">Get Excel</button>
                  </div>
                  <div class="col-sm-1">
                  <button type="button" class="btn bg-red waves-effect" onclick="window.history.back();">Back</button>
                </div>
             
            </div> 
            </div>
            <div class="body">
                <div id="students_more_req"></div>
                <div id="sanitation_filters"></div>
            </div>
            <br>
            <br>
            <br>           
         
            </div>
        </div>
    </div>
</div>

    </section>
  </div>
  </div>
       
<!-- END PAGE FOOTER -->

<?php 
    //include required scripts
    include("inc/scripts.php"); 
?>
<?php
    // include page footer
    include("inc/footer.php");
?>

<!-- Jquery Core Js -->
<script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>


<script>
$(document).ready(function() {
  show_requests_notes();

   $('#date_set').click(function(){
          
       show_requests_notes();
    });

    function show_requests_notes(){
       var startDate = $('#passing_date').val();
       var endDate = $('#passing_end_date').val();
  
       $.ajax({
           url : 'get_covid_cases_reports_span',
           type : 'POST',
           data : {"start_date" : startDate, "end_date": endDate},
           success : function(data){
            console.log(data, "priyaa");
             $("#loading_modal").modal('hide');
               $('#students_more_req').empty();
               var result = $.parseJSON(data);
               data_table_for_filters(result);
               //console.log(result);
               $('#show_when_serach').show();
                $('#table_hide').hide();
           }
       });
    }   
      $('#get_excel').click(function(){
      $('.sk-circle').show();
     
      var startDate = $('#passing_date').val();
      var endDate = $('#passing_end_date').val();
      $.ajax({
        
        url:'get_excel_covid_notes',
        type:'POST',
        data:{"start_date" : startDate, "end_date": endDate},
        success : function(data){
                  console.log(data);
                  window.location = data;
                  $('.sk-circle').hide();
              },
              error:function(XMLHttpRequest, textStatus, errorThrown)
              {
               console.log('error', errorThrown);
              }
      });
    });

    function data_table_for_filters(result){

      if(result == "No Data Available"){
          $('#students_more_req').html('<h4>No Data Available.</h4>');
      }
        else{

            data_table = '<table class="table table-striped table-bordered" id="datatable_fixed_column"><thead><tr><th>Health ID</th><th>Name</th><th>Class</th><th>School Name</th><th>Request Raised Time</th><th>Action</th></tr></thead><tbody>';
               

              $.each(result, function(){

              data_table = data_table+'<tr>';
              data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['Unique ID']+'</td>';
              data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['Name']['field_ref']+'</td>';
              data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['Class']['field_ref']+'</td>';
              data_table = data_table+'<td>'+this.doc_data.widget_data['page1']['Student Info']['School Name']['field_ref']+'</td>';
              data_table = data_table+'<td>'+this.history['0']['time']+'</td>'; 

             var urlLink = "https://mednote.in/PaaS/healthcare/index.php/";
             

             data_table = data_table + '<td><a class="btn btn-primary btn-xs" href="'+urlLink+'ttwreis_mgmt/ttwreis_reports_display_ehr_uid/?id = '+this.uid+'">Show EHR</a></td>';      
             
             data_table = data_table+'</tr>';
              });

              data_table = data_table+'</tbody></table>';

            $('#students_more_req').html(data_table);
           
      
        //=========================================data table functions=====================================
      
            /* BASIC ;*/
        var responsiveHelper_dt_basic = undefined;
        var responsiveHelper_datatable_fixed_column = undefined;
        var responsiveHelper_datatable_col_reorder = undefined;
        var responsiveHelper_datatable_tabletools = undefined;
        
        var breakpointDefinition = {
          tablet : 1024,
          phone : 480
        };
    
        $('#dt_basic').dataTable({
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });
    
      /* END BASIC */
      var js_url = "<?php echo JS; ?>";
      /* COLUMN FILTER  */
        var otable = $('#datatable_fixed_column').DataTable({
          //"bFilter": false,
          //"bInfo": false,
          //"bLengthChange": false
          //"bAutoWidth": false,
          //"bPaginate": false,
          //"bStateSave": true, // saves sort state using localStorage
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
         "oTableTools": {
               "aButtons": [
                    {
                     "sExtends": "xls",
                     "sTitle": "TLSTEC Schools Report",
                     "sPdfMessage": "TLSTEC Schools Excel Export",
                     "sPdfSize": "letter"
                   },
                  {
                    "sExtends": "print",
                    "sMessage": "TLSTEC Schools Printout <i>(press Esc to close)</i>"
                }],
               "sSwfPath": js_url+"datatables/swf/copy_csv_xls_pdf.swf"
            },
        "autoWidth" : true,
        "preDrawCallback" : function() {
          // Initialize the responsive datatables helper once.
          if (!responsiveHelper_datatable_fixed_column) {
            responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
          }
        },
        "rowCallback" : function(nRow) {
          responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
        },
        "drawCallback" : function(oSettings) {
          responsiveHelper_datatable_fixed_column.respond();
        }   
      
        });
        
        // custom toolbar
        //$("div.toolbar").html('<div class="text-right"><img src="img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');
             
        // Apply the filter
        $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
          
            otable
                .column( $(this).parent().index()+':visible' )
                .search( this.value )
                .draw();
                
        } );
        /* END COLUMN FILTER */   
      
      
      //=====================================================================================================
      
    }
  }
});

  

</script>
