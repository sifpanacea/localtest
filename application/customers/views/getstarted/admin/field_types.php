<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Field Types";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["Getstarted"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Getstarted"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT --><div id="content">
	<div class="well well-sm bg-color-darken txt-color-white text-center">
									<h5>Field Types</h5>
									
								</div>
								<div class="modal-dialog demo-modal">
									<div class="modal-content">
										<div class="modal-body">
										<span class="fa fa-share"> Application Design > App Builder > Field Types </span>
										<br><br>
										The two main categories of field types we have are,<br><br>
										- Typable fields <br><br>
										- Writable fields <br><br>
										<div class="alert alert-info fade in">
						                <i class="fa-fw fa fa-info"></i>
						                <strong>Info !</strong> In our design, any form is considered to be organised as multiple sections
					                    </div>
										<br><b><i> Typable fields </i></b><br><br>
										<u><strong> Section Break </strong></u><br>
										     This field is used for providing visual breaks in form ( Forms are organized as multiple sections )
											 <br><br>Properties : <br>
											 <table class="table table-bordered">
										     <tr>
											 <td>
												Field Name
											 </td>
											 <td> Give any user readable name </td>
											 </tr>
											 <tr>
											 <td>
												Description
											 </td>
											 <td> Description about this section </td>
											 </tr>
											 </table>
											 <u><strong> New Line </strong></u><br>
											  This field is used to give volunteer space ( used for alignment, to match form with background predefined template )
											 <br><br>Properties : <br>
											 No Properties for new line element<br><br>
											 <u><strong> Instruction </strong></u><br>
										     This field is used to display information. ( static text, such as terms and conditions or other messages to users )
											 <br><br>Properties : <br>
											 <table class="table table-bordered">
										     <tr>
											 <td>
												Field Name
											 </td>
											 <td> Give any user readable name </td>
											 </tr>
											 <tr>
											 <td>
												Instructions
											 </td>
											 <td> Your static information goes here </td>
											 </tr>
											 <tr>
											 <td>
												Description
											 </td>
											 <td> Description about this field </td>
											 </tr>
											 </table>
											 <u><strong> One Line Text </strong></u><br>
										     This field is used to enter single line of data
											 <br><br>Properties : <br>
											 <table class="table table-bordered">
										     <tr>
											 <td>
												Field Name
											 </td>
											 <td> Give any user readable name </td>
											 </tr>
											 <tr>
											 <td>
												Description
											 </td>
											 <td> Description about this field </td>
											 </tr>
											 <tr>
											 <td>
												Required
											 </td>
											 <td> Check this option if you want to make this field as mandatory </td>
											 </tr>
											 <tr>
											 <td>
												Notify
											 </td>
											 <td> Check this option if you want to make this field as notification field </td>
											 </tr>
											 <tr>
											 <td>
												Min
											 </td>
											 <td> Default value is 0. You can give any value depends upon your requirement </td>
											 </tr>
											 <tr>
											 <td>
												Max
											 </td>
											 <td> Default value is 60. You can give any value depends upon your requirement </td>
											 </tr>
											 </table>
											 <u><strong> Number </strong></u><br>
										     This field is used to collect data that is in numerical format
											 <br><br>Properties : <br>
											 <table class="table table-bordered">
										     <tr>
											 <td>
												Field Name
											 </td>
											 <td> Give any user readable name </td>
											 </tr>
											 <tr>
											 <td>
												Description
											 </td>
											 <td> Description about this field </td>
											 </tr>
											 <tr>
											 <td>
												Required
											 </td>
											 <td> Check this option if you want to make this field as mandatory </td>
											 </tr>
											 <tr>
											 <td>
												Notify
											 </td>
											 <td> Check this option if you want to make this field as notification field </td>
											 </tr>
											 <tr>
											 <td>
												Min
											 </td>
											 <td> Default value is 0. You can give any value depends upon your requirement </td>
											 </tr>
											 <tr>
											 <td>
												Max
											 </td>
											 <td> Default value is 60. You can give any value depends upon your requirement </td>
											 </tr>
											 </table>
											 <u><strong> Radio </strong></u><br>
										     This field allows to choose only one of a predefined set of options
											 <br><br>Properties : <br>
											 <table class="table table-bordered">
										     <tr>
											 <td>
												Field Name
											 </td>
											 <td> Give any user readable name </td>
											 </tr>
											 <tr>
											 <td>
												Options
											 </td>
											 <td> Default values are value1,value2,value3. You can give any values as you wish </td>
											 </tr>
											 <tr>
											 <td>
												Description
											 </td>
											 <td> Description about this field </td>
											 </tr>
											 <tr>
											 <td>
												Required
											 </td>
											 <td> Check this option if you want to make this field as mandatory </td>
											 </tr>
											 <tr>
											 <td>
												Notify
											 </td>
											 <td> Check this option if you want to make this field as notification field </td>
											 </tr>
											 </table>
											 <u><strong> Multi Line Text </strong></u><br>
										     This field is used to enter multiple lines of data
											 <br><br>Properties : <br>
											 <table class="table table-bordered">
										     <tr>
											 <td>
												Field Name
											 </td>
											 <td> Give any user readable name </td>
											 </tr>
											 <tr>
											 <td>
												Description
											 </td>
											 <td> Description about this field </td>
											 </tr>
											 <tr>
											 <td>
												Required
											 </td>
											 <td> Check this option if you want to make this field as mandatory </td>
											 </tr>
											 <tr>
											 <td>
												Notify
											 </td>
											 <td> Check this option if you want to make this field as notification field </td>
											 </tr>
											 <tr>
											 <td>
												Min
											 </td>
											 <td> Default value is 0. You can give any value depends upon your requirement </td>
											 </tr>
											 <tr>
											 <td>
												Max
											 </td>
											 <td> Default value is 250. You can give any value depends upon your requirement </td>
											 </tr>
											 </table>
											 <u><strong> File Upload </strong></u><br>
										     This field allows to attach a file to form submission
											 <br><br>Properties : <br>
											 <table class="table table-bordered">
										     <tr>
											 <td>
												Field Name
											 </td>
											 <td> Give any user readable name </td>
											 </tr>
											 <tr>
											 <td>
												Description
											 </td>
											 <td> Description about this field </td>
											 </tr>
											 <tr>
											 <td>
												Required
											 </td>
											 <td> Check this option if you want to make this field as mandatory </td>
											 </tr>
											 <tr>
											 </table>
											 <div class="alert alert-success alert-block">
						        <h6 class="alert-heading">Note :-</h6>
						                <table class="table">
										<tr>
											<td><B>
												Allowable File Types
											</B></td>
											<td> All files types are allowed</td>
										</tr>
										<tr>
											<td><b>
												  Upload Limitations
											</b></td>
											<td> Each file uploaded field is limited to 2 MB per submission </td>
										</tr>
										</table>
					                    </div>
										<u><strong> DropDown </strong></u><br>
										    This field allows to select one option from a list of options
											 <br><br>Properties : <br>
											 <table class="table table-bordered">
										     <tr>
											 <td>
												Field Name
											 </td>
											 <td> Give any user readable name </td>
											 </tr>
											 <tr>
											 <td>
												Options
											 </td>
											 <td> Default values are value,value,value. You can give any values as you wish </td>
											 </tr>
											 <tr>
											 <td>
												Description
											 </td>
											 <td> Description about this field </td>
											 </tr>
											 <tr>
											 <td>
												Required
											 </td>
											 <td> Check this option if you want to make this field as mandatory </td>
											 </tr>
											 <tr>
											 <td>
												Notify
											 </td>
											 <td> Check this option if you want to make this field as notification field </td>
											 </tr>
											 </table>
											<u><strong> CheckBoxes </strong></u><br>
										    This field allows to select one option from a list of options
											 <br><br>Properties : <br>
											 <table class="table table-bordered">
										     <tr>
											 <td>
												Field Name
											 </td>
											 <td> Give any user readable name </td>
											 </tr>
											 <tr>
											 <td>
												Options
											 </td>
											 <td> Default values are value,value,value. You can give any values as you wish </td>
											 </tr>
											 <tr>
											 <td>
												Description
											 </td>
											 <td> Description about this field </td>
											 </tr>
											 <tr>
											 <td>
												Required
											 </td>
											 <td> Check this option if you want to make this field as mandatory </td>
											 </tr>
											 <tr>
											 <td>
												Notify
											 </td>
											 <td> Check this option if you want to make this field as notification field </td>
											 </tr>
											 </table>
											<u><strong> Date </strong></u><br>
										     This field accepts valid dates
											 <br><br>Properties : <br>
											 <table class="table table-bordered">
										     <tr>
											 <td>
												Field Name
											 </td>
											 <td> Give any user readable name </td>
											 </tr>
											 <tr>
											 <td>
												Description
											 </td>
											 <td> Description about this field </td>
											 </tr>
											 <tr>
											 <td>
												Required
											 </td>
											 <td> Check this option if you want to make this field as mandatory </td>
											 </tr>
											 <tr>
											 <td>
												Notify
											 </td>
											 <td> Check this option if you want to make this field as notification field </td>
											 </tr>
											 </table><br>
											 <div class="alert alert-warning fade in">
						                <i class="fa-fw fa fa-warning"></i>
						                <strong>Warning !</strong> In app builder,some elements are not available now. So that elements are disabled
					                    </div>
											 <br><b><i> Writable fields </i></b><br><br>	
                                             <u><strong> One Line Text </strong></u><br>
										     This field is used to write single line of data
											 <br><br>Properties : <br>
											 <table class="table table-bordered">
										     <tr>
											 <td>
												Field Name
											 </td>
											 <td> Give any user readable name </td>
											 </tr>
											 <tr>
											 <td>
												Description
											 </td>
											 <td> Description about this field </td>
											 </tr>
											 </table>
											 <u><strong> Multi Line Text </strong></u><br>
										     This field is used to write multiple lines of data
											 <br><br>Properties : <br>
											 <table class="table table-bordered">
										     <tr>
											 <td>
												Field Name
											 </td>
											 <td> Give any user readable name </td>
											 </tr>
											 <tr>
											 <td>
												Description
											 </td>
											 <td> Description about this field </td>
											 </tr>
											 </table>
                                              <i>Note : </i> Required,Min,Max fields are disabled since writable fields <br><br>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-right" onclick="window.history.back();">
											Back
										</button>
										</div>
									</div><!-- /.modal-content -->
								</div>
	<div class="well well-sm bg-color-darken txt-color-white text-center">						
								</div>
	</div><!--END MAIN CONTENT-->
	

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



<?php 
	//include footer
	include("inc/footer.php"); 
?>