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
                                  <button class="btn bg-teal waves-effect" id="btnExport" onclick="fnExcelReport();"> Excel </button>
                                  <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                              </div>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table id="dt_basic" class="table table-striped table-bordered table-hover">

					                   <?php if ($hospitals): ?>
					                     <thead>
					                     <tr>
                    						<th>Hospital Code</th>
                    						<th>Hospital Name</th>
                    						<th>Hospital Address</th>
                    						<th>Hospital Phone</th>
                    						<th>Action</th>
                    					</tr>
                    					</thead>
                    					<tbody>
                    					<?php foreach ($hospitals as $hospital):?>
                    					<tr>
                    						<td><?php echo $hospital['hospital_code'];?></td>
                    						<td><?php echo $hospital['hospital_name'];?></td>
                    						<td><?php echo $hospital['hospital_addr'];?></td>
                    						<td><?php echo $hospital['hospital_ph'];?></td>
                            						<td>
                                <?php //echo anchor("panacea_mgmt/panacea_mgmt_diagnostic/".$diagnostic['_id'], lang('app_edit')) ;?>
        						
        						            <a class='ldelete' href='<?php echo URL."panacea_mgmt/panacea_mgmt_delete_diagnostic/".$diagnostic['_id'];?>'>
                        			<?php echo lang('app_delete')?>
                        			</a>
						                </td>
                    					</tr>
                    					<?php endforeach;?>
                    					<?php else: ?>
                            			<p>
                              				<?php echo "No hospital entered yet.";?>
                            			</p>
                            			<?php endif ?>
                    					</tbody>
                    						<?php if($links):?>
                    					<tfoot>	
                              <tr>
                                 <td colspan="5">
                                    <?php echo $links; ?>
                                 </td>
                              </tr>
				                    </tfoot>
                   <?php endif ?>
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
  
function fnExcelReport()
{
      var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
      var textRange; var j=0;
      tab = document.getElementById('dt_basic'); // id of table

      for(j = 0 ; j < tab.rows.length ; j++)
      {    
            tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
            //tab_text=tab_text+"</tr>";
      }

      tab_text=tab_text+"</table>";


      var ua = window.navigator.userAgent;
      var msie = ua.indexOf("MSIE ");

      if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
      {
         txtArea1.document.open("txt/html","replace");
         txtArea1.document.write(tab_text);
         txtArea1.document.close();
         txtArea1.focus();
         sa=txtArea1.document.execCommand("SaveAs",true,"Global View Task.xls");
      } 
      else //other browser not tested on IE 11
         sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text)); 
        return (sa);
}
</script>
<?php 
	//include footer
	include("inc/footer_bar.php"); 
?>