<?php $current_page="";?>
<?php $main_nav=""; ?>
<?php
include('inc/header_bar.php');
?>
<br>
<br>
<br>
<br>
<link href='<?php echo MDB_PLUGINS."jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css"; ?>' rel="stylesheet">
<link href='<?php echo MDB_PLUGINS."sweetalert/sweetalert.css"; ?>' rel="stylesheet">
<!--  Bootstrap Material Datetime Picker Css -->
<link href='<?php echo MDB_PLUGINS."bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"; ?>' rel="stylesheet" /> 
<!-- Bootstrap DatePicker Css -->
<link href='<?php echo MDB_PLUGINS."bootstrap-datepicker/css/bootstrap-datepicker.css"; ?>' rel="stylesheet" />


<!-- Code for data tables -->
<section class="">
<div class="container-fluid">

<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>Students List</h2>
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach($students as $data): ?>
                            
                            <tr>     

                                <td><?php echo $data['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']; ?></td>
                                <td><?php echo $data['doc_data']['widget_data']['page1']['Personal Information']['Name']; ?></td>
                                <td><?php echo $data['doc_data']['widget_data']['page2']['Personal Information']['Class']; ?></span></td>
                                
                                <td><?php echo $data['doc_data']['widget_data']['page2']['Personal Information']['School Name']; ?></td>
                                <td> 
                                    <form action="panacea_reports_display_ehr_uid" method="post">
                                        <input type="hidden" name="uid" value="<?php echo $data['doc_data']['widget_data']['page1']['Personal Information']['Hospital Unique ID']; ?>">
                                        <button type="submit" class="btn bg-teal waves-effect">Show EHR</button>
                                    </form>
                                         
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
 

