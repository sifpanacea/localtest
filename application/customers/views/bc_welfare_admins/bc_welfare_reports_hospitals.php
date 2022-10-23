<?php $current_page="Hospital_Reports";?>
<?php $main_nav="Reports";?>
<?php
include('inc/header_bar.php');
include('inc/sidebar.php');
?>
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Hospital Reports</h2>
            </div>
			       <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                              All HOSPITALS LIST  <span class="badge"><?php if(!empty($hospitalcount)) {?><?php echo $hospitalcount;?><?php } else {?><?php echo "0";?><?php }?></span>
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
                    						<th>Hospital Code</th>
                    						<th>Hospital Name</th>
                    						<th>Hospital Address</th>
                    						<th>Hospital Phone</th>
                    						<!-- <th>Action</th> -->
                    					</tr>
                    					</thead>
                    					<tbody>
                    					<?php foreach ($hospitals as $hospital):?>
                    					<tr>
                    						<td><?php echo $hospital['hospital_code'];?></td>
                    						<td><?php echo $hospital['hospital_name'];?></td>
                    						<td><?php echo $hospital['hospital_addr'];?></td>
                    						<td><?php echo $hospital['hospital_ph'];?></td>
                            						<!-- <td>
                                <?php //echo anchor("panacea_mgmt/panacea_mgmt_diagnostic/".$diagnostic['_id'], lang('app_edit')) ;?>
        						
        						            <a class='ldelete' href='<?php //echo URL."panacea_mgmt/panacea_mgmt_delete_diagnostic/".$diagnostic['_id'];?>'>
                        			<?php //echo lang('app_delete')?>
                        			</a>
						                </td> -->
                    					</tr>
                    					<?php endforeach;?>
                    					
                    					</tbody>
                    						
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- ROW -->
<!-- ==========================CONTENT ENDS HERE ========================== -->
</section>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->

        
 <script>
$(document).ready(function() {
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

  

});
</script>
 
<?php 
  //include footer
  include("inc/footer_bar.php"); 
?>