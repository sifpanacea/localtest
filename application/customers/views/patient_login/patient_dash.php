<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Patient Dashboard";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["home"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<style>
.txt-color-bluee
{
color:#214e75;!important
}


</style>



<link href="<?php echo CSS; ?>user_dash.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="<?php echo CSS; ?>smartadmin-production.css"/>
<div id="main" role="main">
<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
	<div id="content">
	<div class="row">
	<div class="col-md-8 col-lg-8 table_data">
<div class="table-responsive">
<table class="table table-bordered">
<thead>
<tr>
<th>Application Name</th>
<th>Hospital Name</th>
<th>Doctor's Email</th>
<th>Attach Files</th>
<th>options</th>
<th>Attachments</th>
</tr>
</thead>
<tbody>
<?php foreach($documents as $document):?>
<tr>
<td><?php echo $document['app_name'];?></td>
<td><?php echo $document['doc_owner'];?></td>
<td><?php echo $document['doc_user'];?></td>
<td><a href="#" doc_id="<?php echo $document['doc_id'];?>" app_id="<?php echo $document['app_id'];?>" class="files_attach">Attach</a></td>
<td><a href="#" cnt="<?php echo $document['doc_count'];?>" doc="<?php echo $document['doc_id'];?>" app="<?php echo $document['app_id'];?>" class="view">View</a></td>
<td>
<ul class="gallery clearfix">
<?php foreach($document['doc_ext_attachment'] as $attachment):?>
<li>
<a href="../../<?php echo substr($attachment['file_path'], 2);?>" rel="attachment[doc]" title=""><?php echo $attachment['file_client_name'];?></a></li>

<?php endforeach ?>
</ul>
</td>
</tr>
<?php endforeach ?>

</tbody>
</table>
</div>
</div>
<div class="inbox_addd">
<div class="printing" id="printingg">
</div>
</div>

</div>




<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Attach your files</h4>
      </div>
      <div class="modal-body">
        <div class="widget-body">
        <form action="../patient_login/upload_attachments" class="dropzone dz-clickable" id="mydropzone">
        <input type="hidden" id="app_id" name="app_id" value="" />
        <input type="hidden" id="doc_id" name="doc_id" value="" />
        
        </form>
        </div>
      </div>
      <div class="modal-footer">
      	
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>



</div>
<!-- END MAIN PANEL -->
			

<!-- ==========================CONTENT ENDS HERE ========================== -->
<input type='hidden' id='queryapp' value='<?php echo set_value('queryapp', (isset($template->app_template)) ? json_encode($template->app_template) : ''); ?>' /><input type='hidden' id='queryid' value='<?php echo set_value('queryid', (isset($template->_id)) ? ($template->_id) : ''); ?>' /><input type='hidden' id='appname' value='<?php echo set_value('appname', (isset($template->app_name)) ? ($template->app_name) : ''); ?>' /><input type="hidden" id="get_pattern"/>
<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script>

$(document).ready(function() {
	//console.log("ready")
	/* <?php if($message) { ?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
				content : "<?php echo $message?>",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
<?php } ?>
 */
		$(document).on("click",'.files_attach',function(e)
		{
			console.log('in functionnnnnnnnnnn');
			var appId = $(this).attr("app_id");
			var docId = $(this).attr("doc_id");
			
			console.log(appId);
			console.log(docId);
			$('#app_id').val(appId);
			$('#doc_id').val(docId);
			$('#myModal').modal("show");
			})
			
			Dropzone.autoDiscover = false;
			$("#mydropzone").dropzone({
				//url: "/file/post",
				addRemoveLinks : true,
				maxFilesize: 100,
				acceptedFiles : 'application/pdf,image/*,text/*,audio/*,video/*,application/powerpoint,application/vnd.ms-powerpoint,application/excel,application/vnd.ms-excel,application/msexcel,application/x-shockwave-flash,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/zip,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/msword',
				dictDefaultMessage: '<span class="text-center"><span class="font-lg visible-xs-block visible-sm-block visible-lg-block"><span class="font-lg"><i class="fa fa-caret-right text-danger"></i> Drop files <span class="font-xs">to upload</span></span><span>&nbsp&nbsp<h4 class="display-inline"> (Or Click)</h4></span>',
				
				dictResponseError: 'Error uploading file!'
				
			});

			$(document).on("click",'.remove_attachment',function(e)
			{
				
				var app_id = $(this).attr("app_id");
				var doc_id = $(this).attr("doc_id");
				var doc_id = $(this).attr("doc_id");
				
				
			});
			
	
		//
		//AJAX call for images to print_preview and document view search//
		//
		
		function preview_images(start_page, off_set, asyn,print_docid,print_appid,create_preview)
		{	
			console.log("privewwwwwww");
			var pgstart = start_page, pgoffset = off_set, asyn_val = asyn;
			
			var iniit = false;
			var type=true;
			console.log("ttttttttttttttttttttttttttttttttttttttttt",pgstart,pgoffset,asyn_val)
			$.ajax 
			({
				url: '../printer/index/'+print_docid+'/'+print_appid+'/'+pgstart+'/'+pgoffset+'/'+type+'/'+iniit,
				type: 'POST',
				async:asyn_val,
				beforeSend: function() {
					console.log("before send",pgstart,pgoffset,asyn_val)
				},
				success: function (data) 
				{
					$('.for_remove').remove();
					console.log(data)
					data=JSON.parse(data);
					console.log(data);
					//console.log(data.imag_str.length);
					print_final=data;
					create_preview(print_final);
					//print_final = '';
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)
				{
					console.log('error', errorThrown);
				}
			})//AJAX call end
			
			//return print_final;
		}
		var print_image_string={};
		$(document).on("click",'.view',function(e)
		{
			$('.table_data').hide();
			console.log("aaaaaaaaaaaaaaaddd");
			e.preventDefault();
			console.log("aaaaaaaaaaaaaaaddd");
			//var previous_length;
			var start = 1, off = 2, asyn = false;
			var doc_id = $(this).attr("doc");
			var app_id = $(this).attr("app");
			var total_cnt = $(this).attr("cnt")
			//total_cnt = JSON.parse(total_cnt);
			var previous_length;console.log("aaaaaaaaaaaaaaaddd");
			var start = 1, off = 2, asyn = false;
			$('.inbox_addd').empty();
			$('<div id="prinT" class="print_preview"></div>').appendTo('.inbox_addd');
			$('<div id="print_final" class="print_view hide"></div>').appendTo('body');
			$('<div class="print_delete hide"></div>').appendTo('body');
			//beforeload();
			for(var j = 0; j < total_cnt; j++)
			{
				if($.isEmptyObject(print_image_string))
				{	
					j++;
					//beforeload();
					preview_images(start, off, asyn,doc_id,app_id,function(print)//preview_images(start, off, asyn, function(print)
					{
						//afterload();
						for(var i=0; i < print.imag_str.length; i++)
						{
							var pagenum=i+1;
							if(print.imag_str[i].hasOwnProperty("print_image")==true)
							{
								$('<div id="'+i+'" class="div_panel col-xs-12 col-sm-4 col-md-2"><div id="'+i+'" class="panel-thumb panel panel-greenLight"><div class="panel-heading-custom panel-heading"><h3 class="panel-title">Page'+pagenum+'<label id="'+i+'" class="panel-checkbox float-right"><input type="checkbox" name="checkbox-inline" checked="checked"><i></i></label></h3></div><div id="'+i+'" class="panel-body no-padding text-align-center"><img class="'+i+'" src='+print.imag_str[i].print_image+' height="140" width="140"/></div><div class="panel-footer no-padding"></div></div></div>').appendTo('.print_preview');
							
								$('<div id="'+i+'" class="'+pagenum+'"><img class="'+i+'" src='+print.imag_str[i].print_image+' height="1120" width="790"/></div>').appendTo('.print_view');
							}
							else if(print.imag_str[i].hasOwnProperty("chart_image")==true)
							{
								$('<div id="'+i+'" class="div_panel col-xs-12 col-sm-4 col-md-2"><div id="'+i+'" class="panel-thumb panel panel-greenLight chart_img"><div class="panel-heading-custom panel-heading"><h3 class="panel-title">Page'+pagenum+'<label id="'+i+'" class="panel-checkbox float-right"><input type="checkbox" name="checkbox-inline" checked="checked"><i></i></label></h3></div><div id="'+i+'" class="panel-body no-padding text-align-center"><img class="'+i+'" src='+print.imag_str[i].chart_image+' height="140" width="140"/></div><div class="panel-footer no-padding"></div></div></div>').appendTo('.print_preview');
							
								$('<div id="'+i+'" class="'+pagenum+'"><img class="'+i+'" src='+print.imag_str[i].chart_image+' height="1120" width="790"/></div>').appendTo('.print_view');
							}
							else if(print.imag_str[i].hasOwnProperty("graph_image")==true)
							{
								$('<div id="'+i+'" class="div_panel col-xs-12 col-sm-4 col-md-2"><div id="'+i+'" class="panel-thumb panel panel-greenLight graph_img"><div class="panel-heading-custom panel-heading"><h3 class="panel-title">Page'+pagenum+'<label id="'+i+'" class="panel-checkbox float-right"><input type="checkbox" name="checkbox-inline" checked="checked"><i></i></label></h3></div><div id="'+i+'" class="panel-body no-padding text-align-center"><img class="'+i+'" src='+print.imag_str[i].graph_image+' height="140" width="140"/></div><div class="panel-footer no-padding"></div></div></div>').appendTo('.print_preview');
							
								$('<div id="'+i+'" class="'+pagenum+'"><img class="'+i+'" src='+print.imag_str[i].graph_image+' height="1120" width="790"/></div>').appendTo('.print_view');
							}
						}
						print_image_string = print;
						previous_length = print_image_string.imag_str.length;
						$('.printbutton').removeClass('hide');
					});
				}
				else
				{
					//beforeload();
					j++;
					
					start = start+1;
					asyn = true;
					preview_images(start, off, asyn, function(print)
					{
						afterload();
						var print_length = print.imag_str.length;
						for(var i=0; i < print_length; i++)
						{
						  var stringi=JSON.stringify(print_image_string);
						  //console.log("ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss",stringi)
						  if(print.imag_str[i].hasOwnProperty("print_image")==true)
							{
								print_image_string.imag_str.push({'print_image':print.imag_str[i].print_image})
							}
							else if(print.imag_str[i].hasOwnProperty("chart_image")==true)
							{
								print_image_string.imag_str.push({'chart_image':print.imag_str[i].chart_image})
							}
							else if(print.imag_str[i].hasOwnProperty("graph_image")==true)
							{
								print_image_string.imag_str.push({'graph_image':print.imag_str[i].graph_image})
							}
						}
						for(var k = previous_length; k < print_image_string.imag_str.length; k++)
						{	
							var pagenum=k+1;
							if(print_image_string.imag_str[k].hasOwnProperty("print_image")==true)
							{
								$('<div id="'+k+'" class="div_panel col-xs-12 col-sm-4 col-md-2"><div id="'+k+'" class="panel-thumb panel panel-greenLight"><div class="panel-heading-custom panel-heading"><h3 class="panel-title">Page'+pagenum+'<label id="'+k+'" class="panel-checkbox float-right"><input type="checkbox" name="checkbox-inline" checked="checked"><i></i></label></h3></div><div id="'+k+'" class="panel-body no-padding text-align-center"><img class="'+k+'" src='+print_image_string.imag_str[k].print_image+' height="140" width="140"/></div><div class="panel-footer no-padding"></div></div></div>').appendTo('.print_preview');
							
								$('<div id="'+k+'" class="'+pagenum+'"><img class="'+k+'" src='+print_image_string.imag_str[k].print_image+' height="1120" width="790"/></div>').appendTo('.print_view');
							}
							else if(print_image_string.imag_str[k].hasOwnProperty("chart_image")==true)
							{
								$('<div id="'+k+'" class="div_panel col-xs-12 col-sm-4 col-md-2"><div id="'+k+'" class="panel-thumb panel panel-greenLight chart_img"><div class="panel-heading-custom panel-heading"><h3 class="panel-title">Page'+pagenum+'<label id="'+k+'" class="panel-checkbox float-right"><input type="checkbox" name="checkbox-inline" checked="checked"><i></i></label></h3></div><div id="'+k+'" class="panel-body no-padding text-align-center"><img class="'+k+'" src='+print_image_string.imag_str[k].chart_image+' height="140" width="140"/></div><div class="panel-footer no-padding"></div></div></div>').appendTo('.print_preview');
							
								$('<div id="'+k+'" class="'+pagenum+'"><img class="'+k+'" src='+print_image_string.imag_str[k].chart_image+' height="1120" width="790"/></div>').appendTo('.print_view');
							}
							else if(print_image_string.imag_str[k].hasOwnProperty("graph_image")==true)
							{
								$('<div id="'+k+'" class="div_panel col-xs-12 col-sm-4 col-md-2"><div id="'+k+'" class="panel-thumb panel panel-greenLight graph_img"><div class="panel-heading-custom panel-heading"><h3 class="panel-title">Page'+pagenum+'<label id="'+k+'" class="panel-checkbox float-right"><input type="checkbox" name="checkbox-inline" checked="checked"><i></i></label></h3></div><div id="'+k+'" class="panel-body no-padding text-align-center"><img class="'+k+'" src='+print_image_string.imag_str[k].graph_image+' height="140" width="140"/></div><div class="panel-footer no-padding"></div></div></div>').appendTo('.print_preview');
							
								$('<div id="'+k+'" class="'+pagenum+'"><img class="'+k+'" src='+print_image_string.imag_str[k].graph_image+' height="1120" width="790"/></div>').appendTo('.print_view');
							}
						}
						previous_length = print_image_string.imag_str.length;
					});
				}
			}
			$('<div class="for_button" style="clear: both;display: block;margin-left: 10px;"><button class="btn btn-primary btn-xs printbutton">print</button><button class="btn btn-primary btn-xs back" style="margin-left:5px">back</button></div>').appendTo('.row')
		})
		$(document).on('click','.panel-thumb',function(e)
		{
			$('.modal-image').remove();
			console.log("ppppppppppppppp")
			if( !$(e.target).is("input, label") ) 
			{
				var id=$(this).attr('id');
				var page_index=parseInt(id);
				//console.log(print_image_string.imag_str[id].graph_image);
				page_index=page_index+1;
				if(!$(this).hasClass('chart_img') && !$(this).hasClass('graph_img'))
				{
					$('<div class="modal-image"><div id="'+id+'" class="div_main col-xs-12 col-sm-4 col-md-2"><div id="'+id+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title">Page'+page_index+'<button type="button" class="close pull-right">&times;</button></h3></div><div id="'+id+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[id].print_image+' height="600" width="100%"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
				}
				else if($(this).hasClass('chart_img'))
				{
					$('<div class="modal-image"><div id="'+id+'" class="div_main chrt col-xs-12 col-sm-4 col-md-2"><div id="'+id+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title">Page'+page_index+'<button type="button" class="close pull-right">&times;</button></h3></div><div id="'+id+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[id].chart_image+' height="600" width="400"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
				}
				else if($(this).hasClass('graph_img'))
				{
					console.log("id.................",id)
					$('<div class="modal-image"><div id="'+id+'" class="div_main chrt col-xs-12 col-sm-4 col-md-2"><div id="'+id+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title">Page'+page_index+'<button type="button" class="close pull-right">&times;</button></h3></div><div id="'+id+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[id].graph_image+' height="600" width="400"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
				}
				$('.modal-image').hide();
				$('.modal-image').toggle("slow","linear");
			}
			
		})
		//
		// modal image preview for document //view//
		//
		$(document).on('click','.panel-thumb-view',function(e)
		{
				$('.modal-image').remove();
				var id=$(this).attr('id');
				var page_index=parseInt(id);
				page_index=page_index+1;
				if(!$(this).hasClass('chart_img'))
				{
					$('<div class="modal-image"><div id="'+id+'" class="div_main col-xs-12 col-sm-4 col-md-2"><div id="'+id+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title"><span class="left btn-lg glyphicon glyphicon-chevron-left"></span>Page'+page_index+'<span class="right btn-lg glyphicon glyphicon-chevron-right"></span><button type="button" class="close pull-right">&times;</button></h3></div><div id="'+id+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[id].print_image+' height="600" width="100%"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
				}
				else if($(this).hasClass('chart_img'))
				{
					$('<div class="modal-image"><div id="'+id+'" class="div_main col-xs-12 col-sm-4 col-md-2 chrt"><div id="'+id+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title"><span class="left btn-lg glyphicon glyphicon-chevron-left"></span>Page'+page_index+'<span class="right btn-lg glyphicon glyphicon-chevron-right"></span><button type="button" class="close pull-right">&times;</button></h3></div><div id="'+id+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[id].chart_image+' height="600" width="400"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
				}
				else if($(this).hasClass('graph_img'))
				{
					$('<div class="modal-image"><div id="'+id+'" class="div_main col-xs-12 col-sm-4 col-md-2 chrt"><div id="'+id+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title"><span class="left btn-lg glyphicon glyphicon-chevron-left"></span>Page'+page_index+'<span class="right btn-lg glyphicon glyphicon-chevron-right"></span><button type="button" class="close pull-right">&times;</button></h3></div><div id="'+id+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[id].graph_image+' height="600" width="400"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
				}
				$('.modal-image').hide();
				$('.modal-image').toggle("slow","linear");

		})
		//
		//Closing the image dialog model
		//
		
		$(document).on('click','.close',function()
		{
		
			$('.modal-image').remove();
			
		})
		$(document).on('click','.back',function()
		{
		
			window.location.reload();
			
		})
		
		//
		// function for print button
		//
		
		$(document).on('click','.printbutton',function()
		{	
			var printContents = document.getElementById('print_final').innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;
		})
		//
		// Add/Remove pages for print check box change function
		//
		$(document).on('change','[name^="checkbox-inline"]',function(event) 
		{
			if ($(this).is(":checked"))
			{
				 var page_id=$(this).parents('.div_panel').attr('id');
				 $(this).parents('.panel-thumb').removeClass("panel-redLight");
				 $(this).parents('.panel-thumb').addClass("panel-greenLight")
				 $('.print_delete').children('.'+page_id+'').detach().appendTo($('.print_view').children('#'+page_id+''));
			}
			else
			{
				 var page_id=$(this).parents('.div_panel').attr('id');
				 $(this).parents('.panel-thumb').removeClass("panel-greenLight");
				 $(this).parents('.panel-thumb').addClass("panel-redLight");
				 $('.print_view').children('#'+page_id+'').children('img').detach().appendTo('.print_delete');
			}
		});
});
</script>

<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
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

<script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
<!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->
<script src="<?php echo JS; ?>vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo JS; ?>vectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?php echo JS; ?>demograph.js"></script>
<script src="<?php echo JS; ?>jquery.easy-pie-chart.min.js"></script>
<script src="<?php echo JS; ?>sub_admin_queryapp.js"></script>
<script src="<?php echo JS; ?>save_pattern_modal.js"></script>
<script src="<?php echo JS; ?>dropzone.js"></script>
<script src="<?php echo JS; ?>jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>


<?php 
	//include footer
	include("inc/footer.php"); 
?>

