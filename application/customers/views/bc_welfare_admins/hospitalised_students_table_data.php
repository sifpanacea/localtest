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
    <h2>Students Information Admitted in Hospital Details</h2>
    <ul class="header-dropdown">
       <div class="button-demo" style="text-align:right">
       <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
       </div>
    </ul>
</div>
<!-- Input -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <div class="row">               
                   <article class="col-sm-12">
                       <div class="alert alert-danger fade in">                                     
                           <strong><?php echo $request_type; ?></strong> =>
                           <strong><?php echo $hospital_district; ?></strong> =>
                           <span class="badge bg-color-darken"> <?php if(isset($students_details) && !empty($students_details)):?><?php echo count($students_details);?><?php else:?><?php echo 0; ?><?php endif;?> </span>                                      
                       </div>
                   </article>                   
                </div>
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
                                <th>Disease type</th> 
                                <th>Request Raised Time</th>                      
                                <th>Hospital Type</th>
                                <th>Hospital Name</th>  
                                <th>District Name</th>  
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($students_details as $index => $doc ):?>
                            <tr>                                         
                                <td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Unique ID'] ;?></td>
                                <td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Name']['field_ref'] ;?></td>
                                <td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Class']['field_ref'] ;?></td>
                                <td><?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['School Name']['field_ref'] ;?></td>

                                <?php if($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Normal"):?>
                                <?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'];?>

                                <td><span class="badge bg-green">
                                    <?php foreach ($identifiers as $identifier => $values) :?>
                                        <?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]); ?>
                                        <?php if(!empty($var123)):?> 
                                            <?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Normal'][$identifier]) : "No Identifier";?>

                                        <?php endif;?>
                                    <?php endforeach;?>                                             
                                    </span>
                                </td>

                                    <?php elseif($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Emergency"):?>
                                    <?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'];?>

                                <td><span class="badge bg-red">
                                    <?php foreach ($identifiers as $identifier => $values) :?>
                                        <?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]); ?>
                                        <?php if(!empty($var123)):?> 
                                            <?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Emergency'][$identifier]) : "No Identifier";?>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                    </span>
                                </td>


                                    <?php elseif($doc['doc_data']['widget_data']['page2']['Review Info']['Request Type'] == "Chronic"):?>
                                    <?php $identifiers = $doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'];?>

                                <td><span class="badge bg-amber">
                                    <?php foreach ($identifiers as $identifier => $values) :?>
                                        <?php $var123 = implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]); ?>
                                        <?php if(!empty($var123)):?> 
                                            <?php echo (gettype($doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier])=="array")? implode (", ",$doc['doc_data']['widget_data']['page1']['Problem Info']['Chronic'][$identifier]) : "No Identifier";?>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                    </span>
                                </td>
                                    <?php endif;?>
                            <td> <?php echo $doc['history'][0]['time'];?></td>


                            <?php $hType = isset($doc['doc_data']['widget_data']["page2"]['Hospital Info']['Hospital Type']) ? $doc['doc_data']['widget_data']["page2"]['Hospital Info']['Hospital Type'] : "Nil";  ?>

                            <td><?php echo $hType; ?></td>

                            <?php $hName = isset($doc['doc_data']['widget_data']["page2"]['Hospital Info']['Hospital Name']) ? $doc['doc_data']['widget_data']["page2"]['Hospital Info']['Hospital Name'] : "Nil";  ?>
                         
                            <td><?php echo $hName; ?></td>
                          
                            <td><?php echo isset($doc['doc_data']['widget_data']["page2"]['Hospital Info']['District Name']) ? $doc['doc_data']['widget_data']["page2"]['Hospital Info']['District Name'] : "Nil"; ?></td>
                                <td> 
                                    <a class='ldelete' href='<?php echo URL."bc_welfare_mgmt/bc_welfare_reports_display_ehr_uid/"?>? id = <?php echo $doc['doc_data']['widget_data']["page1"]['Student Info']['Unique ID'];?>'>
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
 

