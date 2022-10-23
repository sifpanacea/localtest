<?php $current_page = "Symptoms"; ?>
<?php $main_nav = "Reports"; ?>
<?php include("inc/header_bar.php"); ?>

<?php include("inc/sidebar.php"); ?>

<!-- MAIN PANEL -->
<section class="content">

    <div class="container-fluid">
        <div class="block-header">
            <h2>All Symptoms list</h2>
        </div>
        <!-- Basic Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>All Symptoms <span class="badge bg-teal"><?php if(!empty($symptomscount)) {?><?php echo $symptomscount;?><?php } else {?><?php echo "0";?><?php }?></span></h2>
						<ul class="header-dropdown m-r--5">
		                    <div class="button-demo">
		                    <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
		                    <button class="btn bg-teal waves-effect" id="btnExport" onclick="fnExcelReport();"> Excel </button>
		                    </div>
		                </ul>
               
                    </div>
                    <div class="body">
                    	<div class=" table-responsive">
                            <table id="dt_basic" class="table table-striped table-bordered table-hover">
								<?php if ($symptoms): ?>
								<tr>
									<th>Symptom Name</th>
								</tr>
								<?php foreach ($symptoms as $symptom):?>
			                    <tbody>
									<tr>
										<td><?php echo ucwords($symptom["symptom_name"]) ;?></td>
									</tr>
									<?php endforeach;?>
									<?php else: ?>
				        			<p>
				          				<?php echo "No symtom entered yet.";?>
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
	</div>
</section>	

<?php include('inc/footer_bar.php'); ?>


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