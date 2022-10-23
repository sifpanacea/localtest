<?php $current_page=""; ?>
<?php $main_nav=""; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>
<link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet">

<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
    
<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
           <div class="card">
                <div class="header">
                    <h2>Add a News Scroll</h2>
                    <ul class="header-dropdown m-r--5">
                        <div class="button-demo">
                        <button type="button" class="btn bg-pink waves-effect pull-right" onclick="window.history.back();">Back</button>
                        </div>
                    </ul>
                  </div>
                    <br>                 
                  <?php 
                    $attributes = array('class' => 'smart-form','id'=>'news_feed_form');
                    echo form_open_multipart('panacea_mgmt/post_quotation_form',$attributes);?>
                <div class="body">
                     <div class="form-line">
                        <label>News Scroll</label>
                        <textarea cols="30" rows="3" name="news_feed" id="news_feed" class="form-control no-resize" required="" aria-required="true">
                        </textarea>
                     </div>
                      <br>
                      <div class="form-line">
                        <button type='submit' class="btn btn-danger" id="set_date"  name='submit'>
                            Submit
                        </button>
                      </div>
                       </div> 
                       <?php form_close();?>
                     </div>
                      <br>
 
                      <div class="card">
                   <label>Choose Date</label>
                      <div class="row clearfix">
                          <div class="col-sm-2">

                               <span id="monitoring_datepicker">
                                Start Date :
                               <input type="text" id="start_date" name="passing_date" class="form-control date" value="<?php echo date('Y-m-d'); ?>">
                               </span>
                          </div>
                          <div class="col-sm-2">
                               <span id="monitoring_datepicker">
                               End Date :
                               <input type="text" id="end_date" name="passing_end_date" class="form-control date" value="<?php echo date('Y-m-d'); ?>">
                               </span>
                          </div>
                          <div class="col-sm-2">                           
                               <button type='button' class="btn btn-danger" id="set_date1"  name='set_date1'>SET</button>                           
                               <button type="button" id="get_excel" class="btn bg-green btn-sm waves-effect">Get Excel</button>
                            </div>
                         
                         
                        </div>
                        <div class="body">
                           <div id="students_more_req"></div>
                           <div id="sanitation_filters"></div>
                        </div> 

                      </div>

                   </div>
                   
                </div>
           
      </section>

    </div>

    
<!-- Jquery Core Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery/jquery.min.js'); ?>"></script>

    <!-- Bootstrap Core Js -->
    <script src="<?php echo(MDB_PLUGINS.'bootstrap/js/bootstrap.js'); ?>"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'jquery-slimscroll/jquery.slimscroll.js'); ?>"></script>

    <!-- Bootstrap Notify Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-notify/bootstrap-notify.js'); ?>"></script>

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
    <script src='<?php echo MDB_JS."pages/ui/modals.js"; ?>' ></script>

    <!-- Demo Js -->
    <script src="<?php echo(MDB_JS.'demo.js'); ?>"></script>
    <!-- Moment Plugin Js -->
    <script src="<?php echo(MDB_PLUGINS.'momentjs/moment.js'); ?>"></script>
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js'); ?>"></script>
    <script src="<?php echo(MDB_PLUGINS.'bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script src="<?php echo MDB_PLUGINS.'bootstrap-notify/bootstrap-notify.js'; ?>"></script>


 <script type="text/javascript">

  get_data_for_billing();

 $('#set_date1').click(function(){
    //alert(set_date);
    $('#dt_basic').hide();
    get_data_for_billing();
    
  });

  function get_data_for_billing(){

    var from_date = $('#start_date').val();
    var to_date = $('#end_date').val();

    //alert(from_date);
   //alert(to_date);

  
    $.ajax({
                url : 'datewise_quotation_notes_list',
                type : 'POST',
                data : {"start_date" : from_date,"end_date" : to_date},
                success : function(data){
                  $("#loading_modal").modal('hide');
                    $('#students_more_req').empty();
                    
                    var result = $.parseJSON(data);                
                  
                    console.log(result);
                    data_table_for_filters(result);
                }

            });  


var today_date = $('#passing_end_date').val();
    $('.date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
    $('#passing_end_date').change(function(e){
            today_date = $('#passing_end_date').val();;
    });
   
    
   
      $('#get_excel').click(function(){
     
      var from_date = $('#start_date').val();
      var to_date = $('#end_date').val();
     // alert(from_date);
     // alert(to_date);

      $.ajax({
        
        url:'get_excel_for_quotation_notes',
        type:'POST',
        data:{'start_date':from_date, 'end_date':to_date},
        success : function(data){
                  console.log(data);
                  window.location = data;
              },
              error:function(XMLHttpRequest, textStatus, errorThrown)
              {
               console.log('error', errorThrown);
              }
      });
    });

 function data_table_for_filters(result){

    if(result == 'No Data Available'){
      $('#sanitation_filters').html('<h4>No Data Available</h4>');
    }else{

      data_table = '<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%"><thead><tr><th>Created on</th><th>News Feed</th></tr></thead><tbody>';

      $.each(result, function(){
        data_table = data_table+'<tr>';
       
        data_table = data_table + '<td>' +this.Created_on+'</td>';

       data_table = data_table + '<td>' +this.News_Feed+'</td>';
        
        
        data_table = data_table+'</tr>';
      });

      data_table = data_table+'</tbody></table>';

      $('#sanitation_filters').html(data_table);

      $('#dt_basic').DataTable({
                "paging": true,
                "lengthMenu" : [10, 25, 50, 100,500]
              });

       $("#select_all_chk").click(function(){

                var oTable = $('#dt_basic').dataTable();
                var allPages = oTable.fnGetNodes();
                $('input[type="checkbox"]', allPages).prop('checked', $(this).is(':checked'));
            });

    }


    $('#more_requests').DataTable({
      "paging": true,
      "lengthMenu" : [10, 25, 50, 75, 100]
    });

    

  }

   }

   
   </script>


<?php 
  //include footer
  include("inc/footer_bar.php"); 
?>
