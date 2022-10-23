<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "RHSO Import XL-Sheet";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["import_rhso_report_xl"]["sub"]["import_XL_Report"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["RHSO Imports"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
       <article class="col-sm-12 col-md-12 col-lg-6">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-10" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
						<!-- widget options:
						usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
		
						data-widget-colorbutton="false"
						data-widget-editbutton="false"
						data-widget-togglebutton="false"
						data-widget-deletebutton="false"
						data-widget-fullscreenbutton="false"
						data-widget-custombutton="false"
						data-widget-collapsed="true"
						data-widget-sortable="false"
		
						-->
						<header class="bg-color-greenDark txt-color-white">
							<span class="widget-icon"> <i class="fa fa-cloud-upload"></i> </span>
							<h2><strong>Import RHSO XL SHEET </strong></h2>
		                </header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
											<?php 
											$attributes = array('class' => 'smart-form');
											echo form_open_multipart('rhso_users/import_rhso_report_xl_sheet_v3',$attributes);?>
											<div class="panel-body">
												<fieldset>
												
												
												<section>
												<label class="label" for="first_name">School Name</label>
												<label class="select">
													<select class="form-control" id="page1_SchoolInfo_SchoolName" name="page1_SchoolInfo_SchoolName">
                                      <option value="">Select School </option>

                                      <?php foreach($schools_list as $school): ?>
                                        
                                          <option value="<?php echo $school['school_name']; ?>"><?php echo $school['school_name']; ?></option>
                                      <?php endforeach; ?>
                                    </select>
												<i></i>
											</label>
											</section>
                                    				<section >
														<p>To upload xl report of schools  data into our database select a file of excel format and press Import button.
														</p>
                                                    </section>
                                              	
                                              	
                                              	<section>
													<div class="input input-file">
														<span class="button"><input type="file" id="file" name="file" accept=".xls,.xlsx" onchange="this.parentNode.nextSibling.value = this.value" required>Browse</span><input type="text" placeholder="Browse to import in excel format" readonly="" required>
													</div>
												</section>
												
                                                </fieldset>
											 </div>
                                            <footer>
												<button type="submit" class="btn bg-color-greenDark txt-color-white" name="submit" id="sbt" data-toggle="modal" data-target="#import_waiting" data-backdrop="static" data-keyboard="false">
                                             	Import
                                             	</button>
											</footer>
											<?php echo form_close();?>
											
										</div>
								</div>
						</article>
					</div>
					
					<!-- Modal -->
					<div class="modal fade" id="import_waiting" tabindex="-1" role="dialog" >
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title" id="myModalLabel">Import in progress</h4>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-12">
											<img src="<?php echo(IMG.'ajax-loader.gif'); ?>" id="gif" style="display: block; margin: 0 auto; width: 100px;">
										</div>
									</div>
								</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					
					
			</div>
											
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
	 	
?>
<!--<script src="<?php //echo(JS.'dynamic-add-import.js'); ?>" type="text/javascript"></script>-->
<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<!-- <script>

$ ('#select_dt_name').change(function(e){
        dist = $('#select_dt_name').val();
        //alert(dist);
        var options = $("#school_name");
        options.prop("disabled", true);
        
    if( dist != "All" ){
        options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching schools list..."));
        $.ajax({
            url: 'get_schools_list_by_district',
            type: 'POST',
            data: {"dist_id" : dist},
            success: function (data) {          

                result = $.parseJSON(data);
                console.log(result)

                options.prop("disabled", false);
                options.empty();
                options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Select school"));
                options.append($("<option />").val("All").text("All"));
                $.each(result, function() {
                    options.append($("<option />").val(this.school_name).text(this.school_name));
                });
                        
                },
                error:function(XMLHttpRequest, textStatus, errorThrown)
                {
                 console.log('error', errorThrown);
                }
            });
        }
    });
</script> -->


<?php 
	//include footer
	include("inc/footer.php"); 
?>