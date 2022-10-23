<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Analysis";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["testing"]["active"] = true;
include("inc/nav.php");

?>

<style type="text/css">
.jarviswidget-color-greenLight>header>.jarviswidget-ctrls a,
.jarviswidget-color-greenLight .nav-tabs li:not(.active) a {
	color: #1e1d1d!important
}

.jarviswidget-color-greenLight .nav-tabs li a:hover {
	color: #333!important
}
.text_area
{
	margin: 0px; height: 91px; width: 483px;
}
.file_attach_count 
{
	font-family: Segoe UI;
	font-size: 35px;
	color: green;
}
input[type="file"] {
    display: block;
  }
  .imageThumb {
    max-height: 100px;
    border: 2px solid;
    padding: 1px;
    cursor: pointer;
  }
  .pip {
    display: inline-block;
    margin: 10px 10px 50px 90px;
  }
</style>

<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<link href="<?php echo(CSS.'site.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo(CSS.'jquery.dataTables.min.css'); ?>">

<div id="main" role="main">

<?php
//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
//$breadcrumbs["New Crumb"] => "http://url.com"

	include("inc/ribbon.php");
?>

<div id="content">
	
	<div class="row">
     	<!-- NEW WIDGET START -->
			<article class="col-lg-offset-1 col-sm-12 col-md-12 col-lg-10">

				<!-- Widget ID (each widget will need unique ID)-->
				<div class="jarviswidget jarviswidget-color-greenLight" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">

					<header>
						<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
						<h2>Family Health Condition</h2>
						<span class="pull-right">
							<button type="button" id="" class="btn bg-color-blue txt-color-white btn-md " style="margin-top: -10px;">Print</button>
						</span>
					</header>

				<!-- widget div-->
				<div>

					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->

					</div>
					<!-- end widget edit box --> 

					<!-- widget content -->
					<div class="widget-body no-padding">
						<div class="smart-form">
						<fieldset>
							<div class="row">
								<section class="col col-md-4">
									<label class="label">UNIQUE ID</label>
									<label class="input">
										<input type="text" name="unique_id" id="unique_id" placeholder="Focus to view the tooltip" value="" readOnly>
									</label>
								</section>
								<section class="col col-md-4">
									<label class="label">NAME</label>
									<label class="input"> 
										<input type="text" id='page1_StudentInfo_Name' rel='page1_Personal Information_Name' name='page1_StudentInfo_Name' value="">
									</label>
								</section>

								<section class="col col-md-4">
									<label class="label">CLASS</label>
									<label class="input"> 
										<input type="text" id='page1_StudentInfo_Class' type='number' rel='page2_Personal Information_Class' name='page1_StudentInfo_Class' value="">

									</label>
								</section>

							</div>
							
							<br>
							<div class="row">
								<section class="col col-md-4">
									<label class="label">SECTION</label>
									<label class="input">
										<input type="text" id='page1_StudentInfo_Section' rel='page2_Personal Information_Section' type='text' name='page1_StudentInfo_Section'  value="">
									</label>
								</section>
								<section class="col col-md-4">
									<label class="label">DISTRICT</label>
									<label class="input"> 
										<input type="text" id='page1_StudentInfo_District' rel='page2_Personal Information_District' type='text' name='page1_StudentInfo_District'   value="">

									</label>
								</section>
								<section class="col col-md-4">
									<label class="label">SCHOOL NAME</label>
									<label class="input"> 
										<input type="text" id='page1_StudentInfo_SchoolName' rel='page2_Personal Information_School Name' type='text' name='page1_StudentInfo_SchoolName'  value="">

									</label>
								</section>
							</div>
						
						</fieldset>
						<BR>
						<fieldset>
							<legend><h3 class="text-primary">Current Medical Condition</h3></legend>
							<section>
								<textarea class='form-control' rows="5"  id=''  name=''></textarea>
							</section>
						</fieldset>
						<fieldset>
							<legend><h3 class="text-primary">Parents Medical Condition</h3></legend>
							<section>
								<textarea class='form-control' rows="5"  id="" name=""></textarea>
							</section>
						</fieldset>
						<fieldset>		
							<section>
								<legend><h3 class="text-primary">Advice/Suggestion</h3></legend>
								<textarea class='form-control' rows="5"  id="" name=""></textarea>
							</section>
						</fieldset>
			

				
				


                   

								    <footer>
									<div class="row">
                                  <div class="col-md-7">
                                    
                                    <!-- <button class="btn btn-success col-md-3 submit" type="submit">
									  <i class="fa fa-save"></i>
									  SUBMIT
									</button> -->
                                  </div>
                                  <div class="col-md-4">
									<button type="button" class="btn btn-primary pull-right" onclick="window.history.back();">Back</button>
									</div>
                                </div>
								</footer>
						</div>			
					</div>
							<!-- end widget content -->

				</div>
				<!-- end widget div -->

			</div>
			<!-- end widget -->
		</article>

	</div>
</div>
</div>




<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>
<script src="<?php echo JS; ?>sweetalert.min.js"></script>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->

<script type="text/javascript" charset="utf8" src="<?php echo JS;?>jquery_new_version.dataTables.min.js"></script>


<?php 
	//include footer
	include("inc/footer.php"); 
?>