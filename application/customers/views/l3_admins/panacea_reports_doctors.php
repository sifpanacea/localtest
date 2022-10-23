<?php $current_page="Doctor_Reports";?>
<?php $main_nav="Reports";?>
<?php
include('inc/header_bar.php');
include('inc/sidebar.php');
?>
	<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>All School Reports</h2>
            </div>
            <!-- Input -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                    	<div class="header">
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>All Doctors <span class="badge bg-color-greenLight"><?php if(!empty($doccount)) {?><?php echo $doccount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>
							<ul class="header-dropdown m-r--5">
                              <div class="button-demo">
                                  <button class="btn bg-teal waves-effect" id="btnExport" onclick="fnExcelReport();"> Excel </button>
                                  <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                              </div>
                            </ul>
						</header>
						</div>
						
						<div>
							<div class="body">
                            <div class="table-responsive">
                               <table id="dt_basic" class="table table-striped table-bordered table-hover">
										<?php if ($doctors): ?><thead>
										<tr>
											<th>Doctor Name</th>
											<th>Email</th>
											<th>Mobile Number</th>
											<th>Doctor Address</th>
											<th>Qualification</th>
											<th>Specialization</th>
										</tr></thead><tbody>
										<?php foreach ($doctors as $doctor):?>
										<tr>
											<td><?php echo $doctor['name'];?></td>
											<td><?php echo $doctor['email'];?></td>
											<td><?php echo $doctor['mobile_number'];?></td>
											<td><?php echo $doctor['company_address'];?></td>
											<td><?php echo $doctor['qualification'];?></td>
											<td><?php echo $doctor['specification'];?></td>
										</tr>
										<?php endforeach;?>
										<?php else: ?>
					        			<p>
					          				<?php echo "No doctor entered yet.";?>
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
					</article>
	           </div>
			</div>
		</div>
	</div>
</section>

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