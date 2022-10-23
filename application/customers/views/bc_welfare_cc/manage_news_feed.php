<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Manage News Feed";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["news_feed"]["sub"]["view_nf"]["active"] = true;
include("inc/nav.php");

?>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />



<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "https://url.com"
		$breadcrumbs["News Feeds"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

				<!-- widget grid -->
<section id="widget-grid" class="">

					<!-- row -->
					<div class="row">
						
						<!-- NEW WIDGET START -->
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							
							<!--<div class="alert alert-info">
								<strong>NOTE:</strong> All the data is loaded from a seperate JSON file
							</div>-->

							<!-- Widget ID (each widget will need unique ID)-->
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget" id="wid-id-0" data-widget-editbutton="false">
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
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>Manage News Feed <span class="badge bg-color-greenLight"><?php if(!empty($news_feeds)) {?><?php echo count($news_feeds);?><?php } else {?><?php echo "0";?><?php }?></span></h2>
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body no-padding table-responsive">
								
								<table id="datatable_fixed_column" class="table table-striped table-bordered table-hover" width="100%">
									
							        <thead>
										<tr>
											<th class="hasinput" style="width:20%">
												<input type="text" class="form-control" placeholder="Filter Date" />
											</th>
											<th class="hasinput" style="width:20%">
												<input type="text" class="form-control" placeholder="Filter News Feed" />
											</th>
											<th class="hasinput" style="width:20%">
												<input type="text" class="form-control" placeholder="Filter Created By" />
											</th>
											<th class="hasinput" style="width:20%">
												<input type="text" class="form-control" placeholder="Filter Attachments" />
											</th>
											<th class="hasinput" style="width:20%">
												
											</th>
										</tr>
							            <tr>
						                    <th>Date</th>
											<th>News Feed</th>
											<th>Created By</th>
											<th>Attachments</th>
											<th>Options</th>
							            </tr>
							        </thead>
		 							<tbody>
							        <?php foreach ($news_feeds as $news_feed):?>
                   
										<tr>
											<td><?php echo $news_feed["display_date"] ;?></td>
											<td><?php echo ((strlen($news_feed["news_feed"])>=30)? substr($news_feed["news_feed"], 0,30)." <font size='2' color='blue'>cont...</font>" : $news_feed["news_feed"]) ;?></td>
											<td><?php echo $news_feed["username"] ;?></td>
											<td><?php if(isset($news_feed["file_attachment"])){
												echo '<ul class="gallery clearfix">';
												foreach ($news_feed["file_attachment"] as $attachment){
													echo '<li><a href="../../'.substr($attachment['file_path'], 2).'" rel="attachment[doc]" title="">'.$attachment['file_client_name']."</a></li>";
												}
												echo "</ul>";
											}else{
											echo "No attachments" ;
												}
											?></td>
											<td>
											<?php echo (strtotime($news_feed["display_date"]) >= strtotime(date("Y-m-d H:i:s", strtotime ( date("Y-m-d H:i:s") . '+6 hours' ))))? '<a class="btn btn-warning btn-xs" href="'. URL.'bc_welfare_cc/edit_news_feed_view/'.$news_feed['_id'].'">Edit </a>' : "Time elapsed";?>
											 | 
											<a class="btn btn-danger btn-xs" href="<?php echo URL."bc_welfare_cc/delete_news_feed/".$news_feed['_id'];?>">Delete</a>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
							<!-- end widget content -->
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->

						</article>
						<!-- WIDGET END -->

						
					</div>

					<!-- end row -->

					<!-- row -->

				</section>
				<!-- end widget grid -->		
	</div>
											
<!-- END MAIN PANEL -->


<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
	 	
?>
<script src="<?php echo(JS.'moment.js');?>" type="text/javascript"></script>
<script src="<?php echo(JS.'bootstrap-datetimepicker.js');?>" type="text/javascript"></script>
<script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>
<script src="<?php echo JS; ?>jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script>
$(document).ready(function() {
	
<?php if($message) {?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message!",
				content : "<?php echo $message?>",
				color : "#C79121",
				iconSmall : "fa fa-bell bounce animated"
				
			});
<?php } ?>

/* // DOM Position key index //

l - Length changing (dropdown)
f - Filtering input (search)
t - The Table! (datatable)
i - Information (records)
p - Pagination (paging)
r - pRocessing 
< and > - div elements
<"#id" and > - div with an id
<"class" and > - div with a class
<"#id.class" and > - div with an id and class

Also see: http://legacy.datatables.net/usage/features
*/	

/* BASIC ;*/
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


	$("area[rel^='attachment']").prettyPhoto();
	
	$(".gallery:first a[rel^='attachment']").prettyPhoto({animation_speed:'normal',theme:'pp_default',slideshow:3000, autoplay_slideshow: false});
	$(".gallery:gt(0) a[rel^='attachment']").prettyPhoto({animation_speed:'normal',slideshow:10000, hideflash: true});
	
	$("#custom_content a[rel^='attachment']:first").prettyPhoto({
		custom_markup: '<div id="map_canvas" style="width:260px; height:265px"></div>',
		changepicturecallback: function(){ initialize(); }
	});
	
	$("#custom_content a[rel^='attachment']:last").prettyPhoto({
		custom_markup: '<div id="bsap_1259344" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div><div id="bsap_1237859" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6" style="height:260px"></div><div id="bsap_1251710" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div>',
		changepicturecallback: function(){ _bsap.exec(); }
	});
});

</script>


<?php 
	//include footer
	include("inc/footer.php"); 
?>