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
<br>

<!-- Code for data tables -->
<section class="">
<div class="container-fluid">
<div class="block-header">
    <h2>Doctor Visiting Submitted Schools List</h2>
</div>
<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                     Student Request Details
                </h2>
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
                                <th>School Name</th>                                                  
                               <th>Students Counts</th>                         
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($doctor_visit as $key => $value ): ?>
                            <tr>                                                          
                                <td><?php echo $key ;?></td>
                                <td><?php echo $value;?></td>
                                <form action='<?php echo URL."panacea_mgmt/visiting_doctor_students_data" ?>'accept-charset="utf-8" method="POST">

                                   <input type="hidden" name="school_name" value="<?php echo $key; ?>">                               
                                   <input type="hidden" name="dr_visit" value="<?php echo $dr_visit; ?>">
                                   <input type="hidden" name="date" value="<?php echo $date; ?>">

                                    <td><button class="btn bg-teal waves-effect">Show Students</button></td>
                                </form>  
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
 

