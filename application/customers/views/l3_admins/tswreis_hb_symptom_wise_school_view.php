<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "HB Case Report";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa school pie"]["sub"]["hb_pie"]["active"] = true;
include("inc/nav.php");

?>
<style>
.checkbox_view_only {
    cursor: not-allowed;
}
</style>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
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
        <div class="row">

            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">

                    <header class ="bg-color-orange txt-color-white">
                        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                        <h2>HB Cases Report <span class="badge bg-color-greenLight"></span>
                        </h2>
                        <button type="button" class="btn btn-primary pull-right btn-sm" onclick="window.history.back();">Back</button>

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
                            <div class="panel-group smart-accordion-default" id="accordion-2">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseOne-1"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> Severe HB Cases Report </a></h4>
                                    </div>
                                    <div id="collapseOne-1" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <table id="chronic_report_table" class="table table-striped table-bordered table-hover">
                                                <?php $a = 0;?>

                                                <thead>
                                                    <tr>
                                                        <th>Hospital Unique ID</th>
                                                        <th>Student Name</th>
                                                        <th>School</th>
                                                        <th>Class</th>
                                                        <th>Section</th>                                                        
                                                        <th>Submitted Month</th>                                                        
                                                        <th>HB Value</th>                                                        
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                        
                                <?php foreach ($all as $chronics) : ?>
                                    <?php if(isset($chronics['all_severe_hb_cases_docs']) && !empty($chronics['all_severe_hb_cases_docs']) && !is_null($chronics['all_severe_hb_cases_docs'])): ?>
                                    <?php foreach ($chronics['all_severe_hb_cases_docs'] as $chronic) : ?>
                                        <tr>
                                            <td><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'] ?></td>
                                            <td><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref']; ?></td>
                                            <td><?php echo $chronic['doc_data']['widget_data']['school_details']['School Name']; ?></td>
                                            <td><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref']; ?></td>
                                            <td><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref']; ?></td>
                                            <td><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['HB_latest']['month']; ?></td>
                                            <td><span class="badge badge-warning" style="background-color:red"><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['HB_latest']['hb']; ?></span></td>
                                        <td> <a class='ldelete' href='<?php echo URL."panacea_mgmt/panacea_reports_display_ehr_uid/"?>? id = <?php echo $chronic['doc_data']['widget_data']["page1"]['Student Details']['Hospital Unique ID'];?>'>
                                <button class="btn btn-primary btn-xs">Show EHR</button>
                                                </a>                                                    
                                                </td>
                                        </tr>
                                                        <?php endforeach; ?>
                                                
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>

                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseTwo-1" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> Moderate HB Cases Report </a></h4>
                                    </div>
                                    <div id="collapseTwo-1" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <table id="chronic_report_table" class="table table-striped table-bordered table-hover">
                                                <?php $a = 0;?>

                                                <thead>
                                                    <tr>
                                                        <th>Hospital Unique ID</th>
                                                        <th>Student Name</th>
                                                        <th>School</th>
                                                        <th>Class</th>
                                                        <th>Section</th>
                                                        <th>Submitted Month</th>                                                        
                                                        <th>HB Value</th>
                                                        <th>Action</th>

                                                    </tr>
                                                </thead>
                    <tbody>                                                
                        <?php foreach ($all as $chronics) : ?>
                            <?php if(isset($chronics['all_moderate_hb_cases_docs']) && !empty($chronics['all_moderate_hb_cases_docs']) && !is_null($chronics['all_moderate_hb_cases_docs'])): ?>
                                    <?php foreach ($chronics['all_moderate_hb_cases_docs'] as $chronic) : ?>
                                        <tr>
                                            <td><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID']; ?></td>
                                            <td><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref']; ?></td>
                                            <td><?php echo $chronic['doc_data']['widget_data']['school_details']['School Name']; ?></td>
                                            <td><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref']; ?></td>
                                            <td><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref']; ?></td>
                                            <td><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['HB_latest']['month']; ?></td>
                                            <td><span class="badge badge-warning" style="background-color:red"><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['HB_latest']['hb']; ?></span></td>
                                            <td> <a class='ldelete' href='<?php echo URL."panacea_mgmt/panacea_reports_display_ehr_uid/"?>? id = <?php echo $chronic['doc_data']['widget_data']["page1"]['Student Details']['Hospital Unique ID'];?>'>
                                            <button class="btn btn-primary btn-xs">Show EHR</button>
                                            </a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                    </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#collapseThree-1" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> Mild HB Cases Report </a></h4>
                                    </div>
                                    <div id="collapseThree-1" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <table id="chronic_report_table" class="table table-striped table-bordered table-hover">
                                                <?php $a = 0;?>
                                                
                                                <thead>
                                                    <tr>
                                                        <th>Hospital Unique ID</th>
                                                        <th>Student Name</th>
                                                        <th>School</th>
                                                        <th>Class</th>
                                                        <th>Section</th>
                                                        <th>Submitted Month</th>                                                        
                                                        <th>HB Value</th>
                                                        <th>Action</th>                                                        
                                                    </tr>
                                                </thead>
                                <tbody>                                                
                                    <?php foreach ($all as $chronics) : ?>
                                        <?php if(isset($chronics['all_mild_hb_cases_docs']) && !empty($chronics['all_mild_hb_cases_docs']) && !is_null($chronics['all_mild_hb_cases_docs'])): ?>
                                        <?php foreach ($chronics['all_mild_hb_cases_docs'] as $chronic) : ?>
                                            <tr>
                                                <td><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID']; ?></td>
                                                <td><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref']; ?></td>
                                                <td><?php echo $chronic['doc_data']['widget_data']['school_details']['School Name']; ?></td>
                                                <td><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref']; ?></td>
                                                <td><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['Section']['field_ref']; ?></td>
                                                <td><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['HB_latest']['month']; ?></td>
                                                <td><span class="badge badge-warning" style="background-color:red"><?php echo $chronic['doc_data']['widget_data']['page1']['Student Details']['HB_latest']['hb']; ?></span></td>
                                                    <td> <a class='ldelete' href='<?php echo URL."panacea_mgmt/panacea_reports_display_ehr_uid/"?>? id = <?php echo $chronic['doc_data']['widget_data']["page1"]['Student Details']['Hospital Unique ID'];?>'>
                                                    <button class="btn btn-primary btn-xs">Show EHR</button>
                                                    </a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>                                
                            </table>
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
            </article>

        </div><!-- ROW -->

    

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
    <script src="<?php echo(JS.'bootstrap-datepicker.js');?>" type="text/javascript"></script>
    <script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
    <script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
    <script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>
    
    <script>

        $(document).ready(function() {
        // PAGE RELATED SCRIPTS
        
        /* BASIC ;*/
        var responsiveHelper_dt_basic = undefined;
        var responsiveHelper_datatable_fixed_column = undefined;
        var responsiveHelper_datatable_col_reorder = undefined;
        var responsiveHelper_datatable_tabletools = undefined;
        
        var breakpointDefinition = {
            tablet : 1024,
            phone : 480
        };

        $('#chronic_report_table').dataTable({
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
            "autoWidth" : true,
            "preDrawCallback" : function() {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_dt_basic) {
                    responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#chronic_report_table'), breakpointDefinition);
                }
            },
            "rowCallback" : function(nRow) {
                responsiveHelper_dt_basic.createExpandIcon(nRow);
            },
            "drawCallback" : function(oSettings) {
                responsiveHelper_dt_basic.respond();
            },
            'mRender': function (data, type, full) {
                if (full[7] !== null) {
                    return full[7]; 
                }else{
                    return '';
                }
            }
        });
        
        var js_url = "<?php echo JS; ?>";
        /* COLUMN FILTER  */

        var otable = $('#datatable_fixed_column').DataTable({});

            // Apply the filter
            $("#chronic_report_table thead th input[type=text]").on('keyup change', function () {
                
                otable
                .column( $(this).parent().index()+':visible' )
                .search( this.value )
                .draw();

            } );
            /* END COLUMN FILTER */ 

            /* END BASIC */
        });

    </script>
    
    <?php 
    //include footer
    include("inc/footer.php"); 
    ?>
