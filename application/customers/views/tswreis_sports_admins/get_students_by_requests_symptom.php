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

<br>
<br>
<br>
<br>
<br>
   
 
<div class="container-fluid">
    <!-- Exportable Table -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <span class="font-bold col-teal">
                    <ol class="breadcrumb" style="background: beige">
                         <li data-toggle="tooltip" data-placement="top" title="" data-original-title="Academic Year"><?php echo $academic; ?></li>
                        
                        <li data-toggle="tooltip" data-placement="top" title="" data-original-title="School Name"><?php echo $school; ?></li>
                        <li data-toggle="tooltip" data-placement="top" title="" data-original-title="Problem Info"><?php echo strtoupper($symptom_name); ?> <span class="badge bg-red"><?php if(!empty($students_count)) {?><?php echo $students_count;?><?php } else {?><?php echo "0";?><?php }?></span></li>
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
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dt_basic">
                            <?php $a = 0;?>
                            <?php if ($students_list): ?>
                            <thead>
                                <tr>
                                    <th>Student Health ID</th>
                                    <th>Student Name</th>
                                    <th>Class</th>
                                    <th>Problem Info</th>
                                    <th>School Name</th>
                                    <th>EHR</th>
                                    <!-- <th>Select Student(s)</th> -->
                                </tr>
                            </thead>
                            <tbody>
                               <?php foreach ($students_list as $case):?>
                                    <tr>
                                      <td class='unique_id'><?php echo $case['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?></td>
                                     
                                      <td class="name"><?php echo $case['doc_data']['widget_data']['page1']['Student Info']['Name']['field_ref']; ?></td>

                                      <td class="class"><?php echo $case['doc_data']['widget_data']['page1']['Student Info']['Class']['field_ref'];?></td>

                                      <td class="class"><span class="badge bg-red"><?php echo $symptom_name; ?></span></td>    

                                      <td class="class"><?php echo $case['doc_data']['widget_data']['page1']['Student Info']['School Name']['field_ref'];?></td>                            
                                      
                                      <form action='<?php echo URL."panacea_mgmt/panacea_reports_display_ehr_uid" ?>'accept-charset="utf-8" method="POST">
                                         <input type="hidden" name="uid" value="<?php echo $case['doc_data']['widget_data']['page1']['Student Info']['Unique ID'];?>">
                                      <td><button class="btn bg-teal btn-sm waves-effect">Show EHR</button></td>
                                      </form>
                                    </tr>
                                  <?php endforeach;?>
                                <?php else: ?>
                                <p>
                                    <center><label>No students found</label></center>
                                </p>
                                <?php endif ?>
                               
                            </tbody>
                        </table>
                    </div>
                    <!-- <hr>
                    <div>
                        <form  action="forward_request" method="POST" id="request_form">
                        <h5>Select all or select EHR from above table to forward to doctors for schedule</h5>
                        <div class="demo-checkbox">
                            <input type="checkbox" id="select_all_chk" class="filled-in chk-col-red">
                            <label for="select_all_chk">Select All</label>
                        </div>
                    </div> -->
                   <!--   <hr>
                         <div class="row clearfix">
                            <div class="col-sm-3">
                                <p>
                                    <b>Select Hospital</b>
                                </p>
                             <select id="select_hospital" name="select_hospital" class="form-control">
                                <option value="" selected="0" disabled="">Select Hospital</option>
                                <?php //if(isset($hospitals)): ?>
                                    <?php //foreach ($hospitals as $hospital):?>
                                    <option value="<?php //echo $hospital['_id']; ?>"><?php //echo ucfirst($hospital['hospital_name'])?></option>
                                    <?php //endforeach;?>
                                    <?php //else: ?>
                                    <option value="1"  disabled="">No Hospital entered yet</option>
                                <?php //endif ?>
                            </select>
                            </div>
                             <div class="col-sm-3">
                                <p>
                                    <b>Select Doctor</b>
                                </p>
                                 <select id="doctor_list" name="doctor_list" class="form-control" disabled=true>
                                    <option value="0" selected="" disabled="">Select a Hosptal first</option>
                                </select>
                            </div>
                              <div class="col-sm-3">
                                <p>
                                    <b>Select Date for Schedule</b>
                                </p>
                                <div class="form-line">
                                    <input type="text" id="set_date" name="set_date" class="form-control date" value="" placeholder="Please choose a date...">
                                </div>
                            </div>
                            <input type="hidden" id="identifier" name="identifier" value="<?php //echo $symptom_name; ?>"/>
                            <input type="hidden" id="po_name" name="po_name" value="<?php //echo $students_list[0]['doc_data']['widget_data']['page2']['Personal Information']['District']; ?>"/>
                            <input type="hidden" id="school_name" name="school_name" value="<?php //echo $students_list[0]['doc_data']['widget_data']['page2']['Personal Information']['School Name']; ?>"/>
                            <div class="col-sm-3">
                               <br><br>
                               <button type="button" class="btn btn-primary waves-effect" id="submit_request" disabled>Submit</button>
                            </div>
                             <input type="hidden" id="ehr_data_for_request" name="ehr_data_for_request" value=""/>
                    </div> -->
        </div>
    </div>
    <!-- #END# Exportable Table -->
</div>
   
    
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
   $(document).ready(function() {
    // PAGE RELATED SCRIPTS
    
    /* BASIC ;*/
    //$("a[rel^='prettyPhoto']").prettyPhoto();
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
    
    
  /*  var type = '<?php //echo strtolower($symptom_type);?>';
    console.log('TYPE',type)
    $('.'+type+'').removeClass('');
 */
  });

    </script>
 <!--    <?php //include("inc/message_status.php"); ?> -->
