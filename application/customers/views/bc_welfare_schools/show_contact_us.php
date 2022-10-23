<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Contact Us";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["contact_us"]["active"] = true;
include("inc/nav.php");

?>
<style>


</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->

<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">
		
		
		
		
		<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<!-- new widget -->
					<!-- Widget ID (each widget will need unique ID)-->
					<div  class="jarviswidget" id="wid-id-60" data-widget-editbutton="false">
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
						<header>
							<span class="widget-icon"> <i class="fa fa-phone"></i> </span>
							<h2>Contact Details</h2>

						</header>
					
							
						
						<!-- widget div-->
						<div>
							<address>							      
							<strong><p>DSS BHAVAN, 4TH floor,</p>
							<p>Opposite Chacha Nehru Park,</p>
							<p>Masabtank, Hyderabad</p>
							<p>Land Linenumber:&nbsp 040-45511222,</p>
							<p>Mobile Number : 7337388803, 7337388802</p>
							<p> <i class="fa fa-envelope" aria-hidden="true"></i>Gmail Id: tswreis.panacea@gmail.com</p></strong> 
							</address>
						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->
					<!-- end widget -->
					</article>
			</div>		
					
					
					
					<div  class="jarviswidget" id="wid-id-60" data-widget-editbutton="false">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3807.1800774034323!2d78.44823271487658!3d17.403143588069145!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb973fd9ec85f9%3A0xd795df8b06a63210!2sDSS+Bhavan%2C+Owaisi+Pura%2C+Masab+Tank%2C+Hyderabad%2C+Telangana+500028!5e0!3m2!1sen!2sin!4v1521974930042" width="1250" height="300" frameborder="0" style="border:0" allowfullscreen>
								</iframe>
						</div>
					</div>	
			</div>
				
					
				
			<!-- row -->
			<!-- end row -->
			
		<!-- widget grid -->
			
			
	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
			

<!-- ==========================CONTENT ENDS HERE ========================== -->
<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>


<?php 
	//include footer
	include("inc/footer.php"); 
?>



