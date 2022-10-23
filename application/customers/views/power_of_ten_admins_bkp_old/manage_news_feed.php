<?php $current_page="Manage_News_Feed"; ?>
<?php $main_nav="News Feed"; ?>
<?php include('inc/header_bar.php'); ?>
<?php include('inc/sidebar.php'); ?>

<section class="content">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
				<div class="header">
					<h2>Manage News Feed <span class="badge bg-color-greenLight"><?php if(!empty($news_feeds)) {?><?php echo count($news_feeds);?><?php } else {?><?php echo "0";?><?php }?></span></h2>
					<ul class="header-dropdown m-r--5">
					    <div class="button-demo">
					    <button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
					    </div>
					</ul>
            	</div>					
				<div class="body">
					<table id="datatable_fixed_column" class="table table-bordered table-striped table-hover dataTable js-exportable">
						<thead>
							<tr>
								<th>Date</th>
								<th>News Feed</th>
								
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
				       
								<?php if(isset($news_feeds)): ?>
									<?php foreach($news_feeds as $data): ?>
							<tr>
									<td><?php echo $data['display_date']; ?></td>
									<td><?php echo $data['news_feed']; ?></td>
									<td><a class="btn bg-red" href="<?php echo URL."power_of_ten_mgmt/delete_news_feed/".$data['_id'];?>">Delete</a></td>
							</tr>
									<?php endforeach; ?>
								<?php endif; ?>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>





								
							


<!-- ==========================CONTENT ENDS HERE ========================== -->

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
	include("inc/footer_bar.php"); 
?>