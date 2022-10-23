<?php $current_page="";?>
<?php $main_nav=""; ?>
<?php
include('inc/header_bar.php');
?>
     <link href='<?php echo MDB_PLUGINS."jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css"; ?>' rel="stylesheet">
     <link href='<?php echo MDB_PLUGINS."sweetalert/sweetalert.css"; ?>' rel="stylesheet">
     <!--  Bootstrap Material Datetime Picker Css -->
    <link href='<?php echo MDB_PLUGINS."bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"; ?>' rel="stylesheet" /> 
    <!-- Bootstrap DatePicker Css -->
    <link href='<?php echo MDB_PLUGINS."bootstrap-datepicker/css/bootstrap-datepicker.css"; ?>' rel="stylesheet" />

<br><?php $current_page=""; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<br>
<br>
<br>
<br>

<!-- Code for data tables -->
<section class="">
<div class="container-fluid">
<div class="block-header">
    <center><h2>HB Submitted Students List</h2></center>
</div>
<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                 <span class="font-bold col-teal">
                        <ol class="breadcrumb" style="background: beige">
                        <!-- <li data-toggle="tooltip" data-placement="top" title="" data-original-title="Today Date"><?php //echo $date; ?></li> -->
                        <li data-toggle="tooltip" data-placement="top" title="" data-original-title="status type"> : <?php echo $type; ?></li> 
                        
                        <li data-toggle="tooltip" data-placement="top" title="" data-original-title="school name"> : <?php echo $school_name; ?></li>
                        <li data-toggle="tooltip" data-placement="top" title="" data-original-title="Symptom Count"><span class="badge bg-red"><?php if(!empty($students_count)) {?><?php echo $students_count;?><?php } else {?><?php echo "0";?><?php }?></span> =>> Students</li>
                        </ol>
                    </span>
                    <ul class="header-dropdown m-r--5">
                        <div class="button-demo">
                        <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                        </div>
                    </ul>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>Unique ID</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>School Name</th>
                                <th>Submitted Month</th>                             
                                <th>HB Value</th>                                 
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($students_details as $index => $doc ):?>
                            <tr>     

                                <td><?php echo $doc['doc_data']['widget_data']['page1']['Student Details']['Hospital Unique ID'] ;?></td>
                                <td><?php echo $doc['doc_data']['widget_data']['page1']['Student Details']['Name']['field_ref'] ;?></td>
                                <td><?php echo $doc['doc_data']['widget_data']['page1']['Student Details']['Class']['field_ref'] ;?></td>
                                <td><?php echo $doc['doc_data']['widget_data']['school_details']['School Name'] ;?></td>
                                <td><?php echo $doc['doc_data']['widget_data']['page1']['Student Details']['HB_latest']['month'] ;?></td>
                                <td><span class="badge badge-warning" style="background-color:red"><?php echo $doc['doc_data']['widget_data']['page1']['Student Details']['HB_latest']['hb'] ;?></span></td>
                             
                                <td> 
                                    <a class='ldelete' href='<?php echo URL."ttwreis_mgmt/ttwreis_reports_display_ehr_uid/"?>? id = <?php echo $doc['doc_data']['widget_data']["page1"]['Student Details']['Hospital Unique ID'];?>'>
                                            <button class="btn bg-teal waves-effect">Show EHR</button>
                                    </a>                    
                                </td>
                                
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
    </section>

<?php include('inc/footer_bar.php'); ?> 
<br>
<br>
<br> 
    
 <!-- Jquery Core Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery/jquery.min.js'); ?>"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo(MDB_PLUGINS.'bootstrap/js/bootstrap.js'); ?>"></script>
    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery-slimscroll/jquery.slimscroll.js'); ?>"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'node-waves/waves.js'); ?>"></script>
    <script src="<?php echo(MDB_PLUGINS.'sweetalert/sweetalert.min.js'); ?>"></script>

     <!-- Moment Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'momentjs/moment.js'); ?>"></script>

    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script> 

    <!-- Bootstrap Datepicker Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-datepicker/js/bootstrap-datepicker.js'); ?>"></script> 

    <!-- Bootstrap Colorpicker Js -->
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-colorpicker/js/bootstrap-colorpicker.js'); ?>"></script>

 
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

    var today_date = $('#set_date').val();
    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#set_date').change(function(e){
            today_date = $('#set_date').val();
    }); 

    </script>
 

