<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Access Request";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "";
include("inc/header.php");
//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa home"]["active"] = true;
include("inc/nav.php");

?>
<style>

</style>
<link href="<?php echo(CSS.'smartadmin-production-plugins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo(CSS.'admin_dash_js.css'); ?>" media="screen" rel="stylesheet" type="text/css" />

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

				
			<button id="app_open" class="hide"></button>
			</div>
			<!-- END MAIN CONTENT -->
			
		</div>
		<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<!-- PAGE FOOTER -->
<?php
	// include page footer
	include("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) -->
<script src="<?php echo JS; ?>datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo JS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo JS; ?>datatable-responsive/datatables.responsive.min.js"></script>

<script>
$(document).ready(function() {
		var start=0,end=12;
		var _app,_doc;
		var _startpage=1;
		var _offset=2;
		var result_;
		var image_string;
		var print_image_string={};
		var disapprove_data;
		var print_final;
		var async=false;
		var print_docid='', print_appid='';
		var init;
		var _company_ ='';
		var doc_id = '<?php echo $doc_id;?>';
		// PAGE RELATED SCRIPTS
	
		/*
		 * Fixed table height
		 */
		
		tableHeightSize()
		
		$(window).resize(function() {
			tableHeightSize()
		})
		
		
		function tableHeightSize() {
			var tableHeight = $(window).height() - 212;
			$('.table-wrap').css('height', tableHeight + 'px');
		}
		
		$(".next").on("click",function(e)
		{
				if($('.inbox_add').children('.lists').last().css('display')=='table-row')
				{
					$(".next").attr("disabled","disabled")
				}
				else
				{
					$(".next").removeAttr("disabled");
				}
				
				if($(".next").attr("disabled")=="disabled")
				{
					e.preventDefault();    
				}
				else
				{
					$(".previous").removeAttr("disabled");
					$('.lists').hide();
					start=end;
					end=end+12;
					var tot=$('.inbox_add').children('.lists').length;
					var end_;
					if(end>=tot)
					{
						end_=tot;
					}
					else
					{
						end_=end;
					}
					var start_;
					start_=start+1;
					$('.current').text(''+start_+'-'+end_+'')
					$('.inbox_add').children('.lists').slice(start,end).show();
				}
			})
			
			$(".previous").on("click",function(e)
			{
				if($('.inbox_add').children('.lists').first().css('display')=='table-row')
				{
					$(".previous").attr("disabled","disabled")
				}
				else
				{
					$(".previous").removeAttr("disabled");
				}
				
				if($(".previous").attr("disabled")=="disabled")
				{
					e.preventDefault();    
				}
				else
				{
					$(".next").removeAttr("disabled");
					$('.lists').hide();
					end=start;
					start=start-12;
					var start_;
					start_=start+1;
					$('.current').text(''+start_+'-'+end+'')
					$('.inbox_add').children('.lists').slice(start,end).show();
					
				}
			})
		
		//
		//open application when pressed open button
		//		
		$(document).on('click','#app_open',function(e)
		{
			
			var to_search = $(this).parents('tr').children('td:first').text()
			$('#content').hide();
			$('<div id="content" class="app_render"><div class="table-wrap custom-scroll animated fast fadeInRight"><table id="inbox-table" class="table table-striped table-hover"><tbody class="inbox_add"></tbody></table></div>').appendTo('#main');
			
			init=true;
			$('.printbutton').addClass('hide');
			var _url=$(this).attr('url');
			if($(this).hasClass('a_list'))
			{
				_app=$(this).attr('aid');
			}
			
			$('.inbox_add').empty();
			$('<div class="device" style="border: 0px outset #575757;box-shadow: 0px 0px 0px #DFDFDF;border-radius: 0px; margin-left:20px"></div>').appendTo('.inbox_add');
			_url = url;
			_app = apd__;
			
			//console.log("ssss",_url)
			$( ".device" ).load( _url, function(response)
			{
				
				if(response == "false")
				{
				host=window.location.origin

				window.location.href = ''+host+'/PaaS';
				}	
				$(".previous").attr("disabled","disabled")
				$(".next").attr("disabled","disabled")
				$('.for_remove').remove();
				var res=get_images() || '';
				//console.log("res",res)
				if(res!='')
				{
					$('.indexpages').css("background-image", "url("+res.imag_str[0].print_image+")");
					image_string=res;
					$('<footer><div class="row col col-12 menubotom pull-left"><button class="btn btn-default btn-labeled menubt col col-12" type="button" id="prev_pg"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span></>Previous</button><button class="btn btn-default menubt btn-labeled col col-12" type="button" id="btnsubmit" data-loading-text="Submitting..." onClick="" value="Delete Page"><span class="btn-label"><i class="fa fa-check-square"></i></span></>Submit</button><button class="btn btn-default btn-labeled menubt col col-12" type="button" id="nxt_pg" value="Next">Next</><span class="btn-label btn-label-right"><i class="glyphicon glyphicon-chevron-right"></i></span></button></div></footer>').appendTo('.device');
					$('.page').hide();
					$('.navbar').hide();
					$('#submit').hide();
					$('#disapprove').hide();
					pagecount_();
					if($('.indexpages').children('.page').first().css('display')=='none')
					{
						$('.indexpages').children('.page').first().show();
						$('.indexpages').children('.page').first().addClass('active');
						var first_section=$('.page1').children('div').first();
						$(first_section).children('.section').addClass('firstsec');
						
											
						//
						//animate div
						//
						$('.device').css({
							'transition': 'all 1s',
							'transform': 'scale(0.6)',
						});
						$('.device').animate({'margin-top': "-200px"},1000);
						$('.device').animate({'margin-left': "150px"},1000);
						//afterload();
						$('.page_lbl').removeClass('hide');
						$('.page_no').removeClass('hide');
						$('.page_no').text($('.indexpages').children('.active').attr('id'));
						//#search
						$('#search').prev('input').val(to_search);
						$('#search').trigger("click");
					}
				}
				else
				{
					console.log("empty string")
				}
			});
			
		})
		
		//
		//AJAX call for stroke background image
		//
		function get_images()
		{	
			var type=false;
			$.ajax({
				url: '.../printer/index/'+_doc+'/'+_app+'/'+_startpage+'/'+_offset+'/'+type+'/'+init,
				type: 'POST',
				async:async,
				timeout: 10000 ,
				beforeSend: function()
				{
					
				},
				success: function (data) 
				{
				  //console.log("data=====643",data);
				  if(data!= '')
				  {
					  result_ = JSON.parse(data);
					  
					  if(result_.imag_str[result_.imag_str.length-1].hasOwnProperty("chart_image"))
					  {
						chart_img = result_.imag_str[result_.imag_str.length-1].chart_image;
					  }
					  if(result_.imag_str[result_.imag_str.length-1].hasOwnProperty("graph_image"))
					  {
						graph_img = result_.imag_str[result_.imag_str.length-1].graph_image;
					  }
									
									
					  if(typeof(image_string) == "object" && typeof(image_string) != "undefined" && Object.keys(image_string).length 
						  !== 0)
						   {
							  afterload();
							 
							  
									
							  var len=image_string.length
							  
							  len=len-1;
							  
								  for(i=0;i<result_.imag_str.length;i++)
								  {
								
									image_string.imag_str.push({'print_image':result_.imag_str[i].print_image})
									
									
								  }
						   }
					}
					else
					{
						console.log("empty data")
					}
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
				}
			})//ajax end//
		  return result_;
		}
		
		//
		//Total page count//
		//
		function pagecount_()
		{
			var count = $('.indexpages').children('.page').length;
			return count;
		}
		
		//
		//open document when pressed Access button
		//
		var notification='';
		var current_stage='';
		$(document).on('click','#doc_open',function(e)
		{
		init=false;
		//e.preventDefault();
		$('.printbutton').addClass('hide');
		var _url=$(this).attr('doc');
		notification=$(this).attr('notific_par');
		current_stage=$(this).attr('stage');
		//console.log(_url);
			beforeload();
			$('<div class="device"></div>').appendTo('.inbox_add');
			
			//
			//loading the view page using the file URL
			//
			$( ".device" ).load( _url, function(response) 
			{
						if(response == "false")
			  {
				host=window.location.origin
				window.location.href = ''+host+'/PaaS';
			  }	
			  $(".previous").attr("disabled","disabled")
			  $(".next").attr("disabled","disabled")
				$('.for_remove').remove();
				var res=get_images();
				$('.indexpages').css("background-image", "url("+res.imag_str[0].print_image+")");
				image_string=res;
				
				//
				//loading the next previous button//
				//
				$('<footer><div class="row col col-12 menubottom pull-left"><button class="btn btn-default btn-labeled menubtn col col-12" type="button" id="prev_pg"><span class="btn-label"><i class="glyphicon glyphicon-chevron-left"></i></span></>Previous</button><button class="btn btn-default menubtn btn-labeled col col-12" type="button" id="btnsubmit" onClick="" value="Delete Page"><span class="btn-label"><i class="fa fa-check-square"></i></span></>Submit</button><button class="btn btn-default btn-labeled menubtn col col-12 disapprove" type="button" id="disapproved" value="disapprove">disapprove</><span class="btn-label btn-label-right"><i class="glyphicon glyphicon-minus-sign"></i></span></button><button class="btn btn-default btn-labeled menubtn col col-12" type="button" id="nxt_pg" value="Next">Next</><span class="btn-label btn-label-right"><i class="glyphicon glyphicon-chevron-right"></i></span></button></div></footer>').appendTo('.device');
				$('.page').hide();
				$('.navbar').hide();
				$('#submit').hide();
				$('#disapprove').hide();
				pagecount_();
				
				//
				//Showing the first page//
				//
				if($('.indexpages').children('.page').first().css('display')=='none')
				{
					$('.indexpages').children('.page').first().show();
					$('.indexpages').children('.page').first().addClass('active');//
					var first_section=$('.page1').children('div').first();
					$(first_section).children('.section').addClass('firstsec');
					
										
					//
					//animate div
					//
					$('.device').css({
						'transition': 'all 1s',
						'transform': 'scale(0.5)',
					});
					$('.device').animate({'margin-top': "-300px"},1000);
					$('.device').animate({'margin-left': "100px"},1000);
					//afterload();4009472
					$('.page_lbl').removeClass('hide');
					$('.page_no').removeClass('hide');
					$('.page_no').text($('.indexpages').children('.active').attr('id'));
								  $(".previous").attr("disabled","disabled")
			  $(".next").attr("disabled","disabled")
				}
				afterload();
				$('.inbox_add').addClass("inbox_add_external");
				
				/* $('<div class="files_ext"><div class="panel panel-darken"><div class="panel-heading"><h3 class="panel-title external_font">Externally attached files</h3></div><div class="panel-body no-padding text-align-center"><table class="table table-bordered"><tbody class="files_attached"></tbody></table></div></div></div>').appendTo('.indexpages'); */
				
				/* $('.external_file_attachments').children().each(function (index)
				{
					var href_ = $(this).attr("href")
					var name_ = $(this).attr("name")
					var in_val = index+1;
					if(in_val%2==0)
					{
					$('<tr style="height:55px;"><td class="ind_val"><p></p></td><td class="word_break"><a target="_blank" class="external_font" href="'+href_+'">'+name_+'</a></td></tr>').appendTo('.files_attached');
					}
					else
					{
					$('<tr class="active" style="height:55px;"><td class="ind_val"><p></p></td><td class="word_break"><a target="_blank" class="external_font" href="'+href_+'">'+name_+'</a></td></tr>').appendTo('.files_attached');	
					}
				})
				if($('.files_attached').children('tr').length==0)
				{
					$('<tr class="" style="height:55px;"><td class=""><h1><b>No external files attached<b></h1></td></tr>').appendTo('.files_attached');	
				}
			    $('.files_ext').css({
						'transition': 'all 1s',
						'transform': 'scale(0.5)',
				});
				
				$('.files_ext').animate({'margin-left': "-350px"},1000); */
				
			});//load url end
		})//doc open end//
		
		// chart open new tab
		$(document).on('click','.chart_link',function(e)
		{
			var chart_image_data = $(this).attr("chart_data");
			var img = new Image();
			img.src=chart_image_data;
			var new_win = window.open("");
			new_win.document.write(img.outerHTML);
		})
				
		//
		//previous button click
		//
		$(document).on('click','#prev_pg',function(e)
		{
			var id_=$('.indexpages').children('.active').attr('id');
			var count_=$('.indexpages').children('.page').length
			id_=parseInt(id_)
			if(id_>1)
			{
				$('.indexpages').children('#'+id_+'').hide()
				$('.indexpages').children('#'+id_+'').removeClass('active')
				id_=id_-1;
				var id_pos=id_-1
				$('.indexpages').css("background-image", "url("+image_string.imag_str[id_pos].print_image+")");
				$('.indexpages').children('#'+id_+'').show()
				$('.indexpages').children('#'+id_+'').addClass('active');
				$('.page_no').text($('.indexpages').children('.active').attr('id'));
			}
			else
			{
				console.log("no")
			}
		})
		
		//
		//next button click
		//
		$(document).on('click','#nxt_pg',function(e)
		{
			var id_=$('.indexpages').children('.active').attr('id');
			var count_=pagecount_()
			//console.log('cccccccccccccccccccccccccccccccccccccccccc',count_)
			id_=parseInt(id_)
			if(id_>=1 && id_<count_)
			{
				$('.indexpages').children('#'+id_+'').hide()
				$('.indexpages').css("background-image", "url("+image_string.imag_str[id_].print_image+")");
				$('.indexpages').children('#'+id_+'').removeClass('active')
				id_=id_+1;
					if(image_string.imag_str.length == id_)
					{
						if(count_!= id_)
						{
						beforeload();
						$('.modal-loading').css({top:'80%'});
						_startpage=_startpage+1;
						async=true;
						get_images();
						}
					}
				//console.log('iiiiiiiiiiddddddddddddd',id_)
				$('.indexpages').children('#'+id_+'').show()
				//afterload();
				$('.indexpages').children('#'+id_+'').addClass('active')
				$('.page_no').text($('.indexpages').children('.active').attr('id'));
			}
			else
			{
			console.log("noooooooooooooooooo")
			}
		})
		
		//
		//submitting the document
		//
		$(document).on('click','#btnsubmit',function(e)
		{
		    //$(this).button('loading');
			$(this).prop('disabled', true);
            $('#submit').trigger( "click" );
		});
		
		
		//
		//Bootstrap modal code for disapprove
		//
		$('<div class="modal fade" id="disapprove_stage"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h4 class="modal-title">Disapprove</h4></div><div class="modal-body"></div><div class="modal-footer"><label class="float-left error"></label><button type="button" class="btn btn-default" id="disapprove_submit">Submit</button><button type="button" class="btn btn-default" id="closed">Close</button></div></div><!-- /.modal-content --></div><!-- /.modal-dialog --></div><!-- /.modal -->').appendTo('#content');
		
		$('#disapprove_stage').modal('hide');
		
		//
		//disapprove Modal close button//
		//
		$(document).on('click','#closed',function()
		{
			$('#disapprove_stage').modal('hide');
		})
		
		//
		//disapprove Modal close button//
		//
		$(document).on('click','#disapprove_submit',function()
		{
			var usermail,stage,reason_value_;
			var select_value = '';
			select_value = $('.allstages option:selected').val();
			if(select_value == '')//checking the select stage option value
			{
				$('.error').text("Please select the stage");			
			}
			else
			{
				//console.log('aaadssssssssssssssssss',select_value)
				stage = $('.allstages option:selected').val();
				stage = stage.replace(" ","#");
				var user_index = $('.allstages option:selected').attr('class');
				usermail = disapprove_data[user_index].submitted_by;
				//console.log('aaadssssssssssssssssss',usermail)			
		
			}
			
			if($('.reason').val()== '')//checking the reason textarea value
			{
				$('.error').text("Please give the reason");
			}
			else
			{
				var reason_value_=$('.reason').val();
			}
			
			//ajax for disapporve post//
			var company_folder=_company_;//_app.replace(/[0-9]/g, '');
			var urL = '../'+company_folder+'/'+_app+'_con/web_disapprove';

			$.ajax
				({
				url: urL,
				type: 'POST',
				data:{'redirected_stage':stage,'current_stage':current_stage,'user':usermail,'reason':reason_value_,'docid':_doc,'notification_param':notification},
				async:false,
				beforeSend:function ()
				{
					
				},
				complete:function ()
				{	
					
				},
				success: function (data) 
				{
				   $('#disapprove_stage').hide();
				   //loadInbox();
				   window.location.reload();
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)
				{
					console.log('error', errorThrown);
				}
				})//ajax call end
			
		})
		
		//
		//user click on disapprove
		//
		$(document).on('click','#disapproved',function()
		{
			$('#disapprove_stage').one('show.bs.modal', function () 
			{
			  if($('.modal-body').children().length == 0)
			  {
				var company_folder=_company_;//_app.replace(/[0-9]/g, '');
				
				$.ajax
				({
				url: '../../index.php/'+company_folder+'/'+_app+'_con/list_previous_stages_for_disapprove/'+_doc,
				type: 'POST',
				async:false,
				success: function (data) 
				{
								if(data == "false")
			  {
				host=window.location.origin
				window.location.href = ''+host+'/PaaS';
			  }	
					 data=JSON.parse(data)
					 //console.log("daaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",data)
					
					 disapprove_data=data;
					 $('<fieldset><div class="form-group"><label class="col-md-3 control-label select_label">Select Stage</label><div class="col-md-8" style="margin-bottom: 10px;"><select class="form-control allstages"><option class="select" value="">select stage</option></select></div></div><div class="form-group"><label class="col-md-3 control-label usermailid">User</label><div class="col-md-8" style="margin-bottom: 10px;"><label class="form-control username" value="">User Name</label></div></div><div class="form-group"><label class="col-md-3 control-label reason_label">Reason</label><div class="col-md-8"><textarea class="form-control reason"></textarea></div></div></fieldset>').appendTo('.modal-body');
					 var stages_length=data.length;
					 if(stages_length>0)
					 {
						for(var i=0; i < stages_length;i++)
						{	
							
							var select_stage_name = data[i].current_stage.replace(/ /g,'#');
							//console.log(select_stage_name)
							
							$('<option class='+i+' value='+select_stage_name+'>'+data[i].current_stage+'</option>').appendTo('.allstages')
						}
					 }
					 else
					 {
						console.log("no previous stages found");
					 }
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)
				{
					console.log('error', errorThrown);
				}
				})//ajax call end
			  }//if end//
			}).modal('show');//modal shown end
		})//#disapproved end
		$(document).on('change','.allstages',function()
		{
			var value_ = $('.allstages option:selected').val();
			if(value_ == 'select')
			{
				$('.username').text("User Name");
			}
			else
			{
				var class_ = $('.allstages option:selected').attr('class');
				//console.log("classsss",class_);
				var user_name=disapprove_data[class_].submitted_by;
				user_name = user_name.replace("#","@");
				$('.username').text(user_name);
			}
			
		})
		
		//
		// View for search and print the document// trigger when clicked the search menu
		//
		$(document).on('click','.search',function()
		{
		
			$('.inbox_add').removeClass("inbox_add_external");
			if(!$('.page_lbl').hasClass('hide'))
			{
			$('.page_lbl').addClass('hide');
			$('.page_no').addClass('hide');		
			}		
			$('.inbox-menu-lg').children('li').removeClass("active");
			$('.search').parent('li').addClass("active");
			beforeload();
			$('.print_preview').remove();
			$('.print_view').remove();
			$('.print_delete').remove();
			$('.inbox_add').empty();
			$('.for_remove').remove();
			print_image_string = {} ;
			image_string={};
			var url = 'doc_search';
			$( ".inbox_add" ).load( url, function(response) 
			{
						if(response == "false")
			  {
				host=window.location.origin
				//console.log(host);
				window.location.href = ''+host+'/PaaS';
			  }	
				//console.log("sssssssssssssssssssssssssssssssssssssssssssssssssssssss");
				afterload();
				$( ".inbox_add" ).children('.lists').hide();
				show_initial();
				//$('.for_remove').remove();
							
			});
		});//.search end
		
		//
		//opening the search document link
		//
		
		$(document).on('click','.open_doc',function(e)
		{
			e.preventDefault();
			beforeload();
			$( ".inbox_add" ).empty();
			var url = $(this).attr('href')//'doc_search';
			$( ".inbox_add" ).load( url, function(response) 
			{
							if(response == "false")
			  {
				host=window.location.origin
				//console.log(host);
				window.location.href = ''+host+'/PaaS';
			  }	
			});
			afterload();
		});//.open_doc end
		
		
		//
		//AJAX call for images to print_preview and document view search//
		//
			function preview_images(start_page, off_set, asyn, create_preview)
			{	
				
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
						//console.log(data)
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
		
		
		
		//
		//opening the document for print preview//
		//
		$(document).on('click','.print_open',function(e)
		{
			e.preventDefault();
			print_docid = $(this).attr("did")
			print_appid = $(this).attr("aid")
			var total_cnt = $(this).attr("cnt")
			total_cnt = JSON.parse(total_cnt);
			var previous_length;
			var start = 1, off = 2, asyn = false;
			$('.inbox_add').empty();
			$('<div id="prinT" class="print_preview"></div>').appendTo('.inbox_add');
			$('<div id="print_final" class="print_view hide"></div>').appendTo('body');
			$('<div class="print_delete hide"></div>').appendTo('body');
			beforeload();
			for(var j = 0; j < total_cnt; j++)
			{
				if($.isEmptyObject(print_image_string))
				{	
					j++;
					beforeload();
					preview_images(start, off, asyn, function(print)
					{
						afterload();
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
					beforeload();
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
			
		})//print_open end
		
		
		//
		//opening the document for doc_view//
		//
		$(document).on('click','.doc_view',function(e)
		{
			e.preventDefault();
		    beforeload();
			print_docid = $(this).attr("did")
			print_appid = $(this).attr("aid")
			var total_cnt = $(this).attr("cnt")
			total_cnt = JSON.parse(total_cnt);
			var start = 1, off = 2, asyn = false;
			$('.inbox_add').empty();
			$('<div id="prinT" class="print_preview"></div>').appendTo('.inbox_add');
			$('<div id="print_final" class="print_view hide"></div>').appendTo('body');
			$('<div class="print_delete hide"></div>').appendTo('body');
			var previous_length;
			
			for(var j = 0; j < total_cnt; j++)
			{
				if($.isEmptyObject(print_image_string))
				{	
					j++;
					console.log("pages",start,off)
					preview_images(start, off, asyn, function(print)
					{
						afterload();
						for(var i=0; i < print.imag_str.length; i++)
						{
							var pagenum=i+1;
							if(print.imag_str[i].hasOwnProperty("print_image")==true)
							{
								$('<div id="'+i+'" class="div_panel col-xs-12 col-sm-4 col-md-2"><div id="'+i+'" class="panel-thumb-view panel panel-greenLight"><div class="panel-heading-custom panel-heading"><h3 class="panel-title">Page'+pagenum+'</h3></div><div id="'+i+'" class="panel-body no-padding text-align-center"><img class="'+i+'" src='+print.imag_str[i].print_image+' height="140" width="140"/></div><div class="panel-footer no-padding"></div></div></div>').appendTo('.print_preview');
							
								$('<div id="'+i+'" class="'+pagenum+'"><img class="'+i+'" src='+print.imag_str[i].print_image+' height="1120" width="790"/></div>').appendTo('.print_view');
							}
							else if(print.imag_str[i].hasOwnProperty("chart_image")==true)
							{
								$('<div id="'+i+'" class="div_panel col-xs-12 col-sm-4 col-md-2"><div id="'+i+'" class="panel-thumb-view panel panel-greenLight chart_img"><div class="panel-heading-custom panel-heading"><h3 class="panel-title">Page'+pagenum+'</h3></div><div id="'+i+'" class="panel-body no-padding text-align-center"><img class="'+i+'" src='+print.imag_str[i].chart_image+' height="140" width="140"/></div><div class="panel-footer no-padding"></div></div></div>').appendTo('.print_preview');
							
								$('<div id="'+i+'" class="'+pagenum+'"><img class="'+i+'" src='+print.imag_str[i].chart_image+' height="1120" width="790"/></div>').appendTo('.print_view');
							}
							else if(print.imag_str[i].hasOwnProperty("graph_image")==true)
							{
								$('<div id="'+i+'" class="div_panel col-xs-12 col-sm-4 col-md-2"><div id="'+i+'" class="panel-thumb-view panel panel-greenLight graph_img"><div class="panel-heading-custom panel-heading"><h3 class="panel-title">Page'+pagenum+'</h3></div><div id="'+i+'" class="panel-body no-padding text-align-center"><img class="'+i+'" src='+print.imag_str[i].graph_image+' height="140" width="140"/></div><div class="panel-footer no-padding"></div></div></div>').appendTo('.print_preview');
							
								$('<div id="'+i+'" class="'+pagenum+'"><img class="'+i+'" src='+print.imag_str[i].graph_image+' height="1120" width="790"/></div>').appendTo('.print_view');
							}
						}
						print_image_string = print;
						previous_length = print_image_string.imag_str.length;
					});
				}
				else
				{
					beforeload();
					j++;
					//console.log("*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/previous_length",previous_length)
					console.log("before",start)
					start = start+1;
					console.log("after",start)
					console.log("pagesss",start,off)
					asyn = false;
					preview_images(start, off, asyn, function(print)
					{
						afterload();
						var print_length = print.imag_str.length;
						for(var i=0; i < print_length; i++)
						{
						  var stringi=JSON.stringify(print_image_string);
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
								$('<div id="'+k+'" class="div_panel col-xs-12 col-sm-4 col-md-2"><div id="'+k+'" class="panel-thumb-view panel panel-greenLight"><div class="panel-heading-custom panel-heading"><h3 class="panel-title">Page'+pagenum+'</h3></div><div id="'+k+'" class="panel-body no-padding text-align-center"><img class="'+k+'" src='+print_image_string.imag_str[k].print_image+' height="140" width="140"/></div><div class="panel-footer no-padding"></div></div></div>').appendTo('.print_preview');
						
								$('<div id="'+k+'" class="'+pagenum+'"><img class="'+k+'" src='+print_image_string.imag_str[k].print_image+' height="1120" width="790"/></div>').appendTo('.print_view');						
							}
							else if(print_image_string.imag_str[k].hasOwnProperty("chart_image")==true)
							{
								$('<div id="'+k+'" class="div_panel col-xs-12 col-sm-4 col-md-2"><div id="'+k+'" class="panel-thumb-view panel panel-greenLight chart_img"><div class="panel-heading-custom panel-heading"><h3 class="panel-title">Page'+pagenum+'</h3></div><div id="'+k+'" class="panel-body no-padding text-align-center"><img class="'+k+'" src='+print_image_string.imag_str[k].chart_image+' height="140" width="140"/></div><div class="panel-footer no-padding"></div></div></div>').appendTo('.print_preview');
						
								$('<div id="'+k+'" class="'+pagenum+'"><img class="'+k+'" src='+print_image_string.imag_str[k].chart_image+' height="1120" width="790"/></div>').appendTo('.print_view');
							}
							else if(print_image_string.imag_str[k].hasOwnProperty("graph_image")==true)
							{
								$('<div id="'+k+'" class="div_panel col-xs-12 col-sm-4 col-md-2"><div id="'+k+'" class="panel-thumb-view panel panel-greenLight graph_img"><div class="panel-heading-custom panel-heading"><h3 class="panel-title">Page'+pagenum+'</h3></div><div id="'+k+'" class="panel-body no-padding text-align-center"><img class="'+k+'" src='+print_image_string.imag_str[k].graph_image+' height="140" width="140"/></div><div class="panel-footer no-padding"></div></div></div>').appendTo('.print_preview');
						
								$('<div id="'+k+'" class="'+pagenum+'"><img class="'+k+'" src='+print_image_string.imag_str[k].graph_image+' height="1120" width="790"/></div>').appendTo('.print_view');
							}
						}
						previous_length = print_image_string.imag_str.length;
					});
				}
			}			
		})//doc_view end
		
		
		
		//
		//on click for image panel to show the particular image
		//
		
		$(document).on('click','.panel-thumb',function(e)
		{
			if( !$(e.target).is("input, label") ) 
			{
				var id=$(this).attr('id');
				var page_index=parseInt(id);
				page_index=page_index+1;
				if(!$(this).hasClass('chart_img'))
				{
					$('<div class="modal-image"><div id="'+id+'" class="div_main col-xs-12 col-sm-4 col-md-2"><div id="'+id+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title">Page'+page_index+'<button type="button" class="close pull-right">&times;</button></h3></div><div id="'+id+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[id].print_image+' height="600" width="400"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
				}
				else if($(this).hasClass('chart_img'))
				{
					$('<div class="modal-image"><div id="'+id+'" class="div_main chrt col-xs-12 col-sm-4 col-md-2"><div id="'+id+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title">Page'+page_index+'<button type="button" class="close pull-right">&times;</button></h3></div><div id="'+id+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[id].chart_image+' height="600" width="400"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
				}
				else if($(this).hasClass('graph_img'))
				{
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
					$('<div class="modal-image"><div id="'+id+'" class="div_main col-xs-12 col-sm-4 col-md-2 chrt"><div id="'+id+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title"><span class="left btn-lg glyphicon glyphicon-chevron-left"></span>Page'+page_index+'<span class="right btn-lg glyphicon glyphicon-chevron-right"></span><button type="button" class="close pull-right">&times;</button></h3></div><div id="'+id+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[id].chart_image+' height="600" width="100%"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
				}
				else if($(this).hasClass('graph_img'))
				{
					$('<div class="modal-image"><div id="'+id+'" class="div_main col-xs-12 col-sm-4 col-md-2 chrt"><div id="'+id+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title"><span class="left btn-lg glyphicon glyphicon-chevron-left"></span>Page'+page_index+'<span class="right btn-lg glyphicon glyphicon-chevron-right"></span><button type="button" class="close pull-right">&times;</button></h3></div><div id="'+id+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[id].graph_image+' height="600" width="100%"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
				}
				$('.modal-image').hide();
				$('.modal-image').toggle("slow","linear");

		})
		
		//
		//previous for image doc_view
		//
		$(document).on('click','.left',function(e)
		{
				var id=$(this).parents('.div_main').attr('id');
				var page_index=parseInt(id);
				if(page_index>=0)
				{
					//console.log("lllllllllllleeeeeeeeeeeeeeeffffffffft",id)
					page_index=page_index-1;
					
					$('.modal-image').remove();
					if(print_image_string.imag_str[page_index].hasOwnProperty("print_image")==true)
					{
						$('<div class="modal-image"><div id="'+page_index+'" class="div_main col-xs-12 col-sm-4 col-md-2"><div id="'+page_index+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title"><span class="left btn-lg glyphicon glyphicon-chevron-left"></span>Page'+id+'<span class="right btn-lg glyphicon glyphicon-chevron-right"></span><button type="button" class="close pull-right">&times;</button></h3></div><div id="'+page_index+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[page_index].print_image+' height="600" width="100%"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
					}
					else if(print_image_string.imag_str[page_index].hasOwnProperty("chart_image")==true)
					{
						$('<div class="modal-image"><div id="'+page_index+'" class="div_main chrt col-xs-12 col-sm-4 col-md-2"><div id="'+page_index+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title"><span class="left btn-lg glyphicon glyphicon-chevron-left"></span>Page'+id+'<span class="right btn-lg glyphicon glyphicon-chevron-right"></span><button type="button" class="close pull-right">&times;</button></h3></div><div id="'+page_index+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[page_index].chart_image+' height="600" width="400"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
					}
					else if(print_image_string.imag_str[page_index].hasOwnProperty("graph_image")==true)
					{
						$('<div class="modal-image"><div id="'+page_index+'" class="div_main chrt col-xs-12 col-sm-4 col-md-2"><div id="'+page_index+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title"><span class="left btn-lg glyphicon glyphicon-chevron-left"></span>Page'+id+'<span class="right btn-lg glyphicon glyphicon-chevron-right"></span><button type="button" class="close pull-right">&times;</button></h3></div><div id="'+page_index+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[page_index].graph_image+' height="600" width="400"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
					}
					$('.modal-image').hide();
					$('.modal-image').toggle("slow","linear");
				}
				else
				{
					$('.modal-image').remove();
				}
		})
		

		//
		//Next for image doc_view
		//
		$(document).on('click','.right',function(e)
		{
				var id=$(this).parents('.div_main').attr('id');
				var page_index=parseInt(id);
				//console.log("riiiiiigggght",id,print_final.imag_str.length)
				page_index=page_index+1;
				var nxtpag=page_index+1;
				if(nxtpag <= print_image_string.imag_str.length)
				{
					$('.modal-image').remove();
					if(print_image_string.imag_str[page_index].hasOwnProperty("print_image")==true)
					{
						$('<div class="modal-image"><div id="'+page_index+'" class="div_main col-xs-12 col-sm-4 col-md-2"><div id="'+page_index+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title"><span class="left btn-lg glyphicon glyphicon-chevron-left"></span>Page'+nxtpag+'<span class="right btn-lg glyphicon glyphicon-chevron-right"></span><button type="button" class="close pull-right">&times;</button></h3></div><div id="'+page_index+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[page_index].print_image+' height="600" width="100%"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
					}
					else if(print_image_string.imag_str[page_index].hasOwnProperty("chart_image")==true)
					{
						$('<div class="modal-image"><div id="'+page_index+'" class="div_main chrt col-xs-12 col-sm-4 col-md-2"><div id="'+page_index+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title"><span class="left btn-lg glyphicon glyphicon-chevron-left"></span>Page'+nxtpag+'<span class="right btn-lg glyphicon glyphicon-chevron-right"></span><button type="button" class="close pull-right">&times;</button></h3></div><div id="'+page_index+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[page_index].chart_image+' height="600" width="400"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
					}
					else if(print_image_string.imag_str[page_index].hasOwnProperty("graph_image")==true)
					{
						$('<div class="modal-image"><div id="'+page_index+'" class="div_main chrt col-xs-12 col-sm-4 col-md-2"><div id="'+page_index+'" class="panel panel-greenLight"><div class="panel-heading"><h3 class="panel-title"><span class="left btn-lg glyphicon glyphicon-chevron-left"></span>Page'+nxtpag+'<span class="right btn-lg glyphicon glyphicon-chevron-right"></span><button type="button" class="close pull-right">&times;</button></h3></div><div id="'+page_index+'" class="panel-body no-padding text-align-center"><img class="" src='+print_image_string.imag_str[page_index].graph_image+' height="600" width="400"/></div><div class="panel-footer no-padding"></div></div></div></div>').appendTo('body')
					}
					$('.modal-image').hide();
					$('.modal-image').toggle("slow","linear");
				}
				else
				{
					$('.modal-image').remove();
				}
		})

		
		
		
		//
		//Closing the image dialog model
		//
		
		$(document).on('click','.close',function()
		{
		
			$('.modal-image').remove();
			
		})
		
		//
		// loading image function for AJAX call
		//
		
		function beforeload()
		{
			//console.log("loading......................");
            if($('.loading_header').children('div').length == 0)
            {
				$('<div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>').prependTo('.loading_header');
            }
		}
		
		function afterload()
		{
			//console.log("loading complete.......................");
			$('.loading_header').empty();
		}
		
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

		$(document).ajaxStart(function () {
		beforeload();
		}).ajaxStop(function () {
		afterload();
		});
		$(document).on('click','.refresh',function(event) 
		{
			$('.inbox-menu-lg').children('.active').children('a').trigger("click");
		});
		
		//photo element
		$(document).on('click','.photo_elem',function()
		{
			//var current_elem = $(this).attr("for")
			$(this).next("input[type=file]").trigger("click");
			
		})
		
		//upload the logo when the user selects.//
		$(document).on('change','.upload_photo',function() 
		{
			var that= this;
			if (this.files && this.files[0]) 
			{
				var reader = new FileReader();
				
				reader.onload = function (e) 
				{
				
					$(that).prev('.photo_elem').css("background-image","url("+e.target.result+")");
				}
				
				reader.readAsDataURL(this.files[0]);
			}
			else
			{
				console.log("fail");
			}
		})
		
		var URL      = '<?php echo URLC;?>';
		var apd__    = "healthcare201610114435690";
		var app_con  = "healthcare201610114435690_con";
		//var url = "https://mednote.in/PaaS/healthcare/index.php/healthcare/healthcare201610114435690_con/doc_access/"+doc_id+"";
		var url = ""+URL+"/"+app_con+"/doc_access/"+doc_id+"";
		
	});	
</script>

<script>
$(document).ready(function()
{
	$('#app_open').trigger("click");
	
	//$("#search").remove();
	//$("#inbox-table").remove();
	//$(".hs_attachments").remove();
	// $(".table-bordered").remove();
	// $(".external_files_show").remove();
	// $("#page1_StudentInfo_UniqueID").prop( "readonly", true );
	// $("#page1_StudentInformation_Class").prop( "readonly", true );
	// $("#page1_StudentInfo_Name").prop( "readonly", true );
	// $("#page1_StudentInfo_District").prop( "readonly", true );
	// $("#page1_StudentInfo_SchoolName").prop( "readonly", true );
	// $("#page1_StudentInfo_Section").prop( "readonly", true );
	
	// $('.desktop-detected').contents(':gt(1)').remove();
	//'<form method="post" action="../hs_req_extend" id="form_extend"><input type="file" id="attachments[]" name="attachments[]" class="" multiple/><input type="hidden" id="form_data" name="form_data"/></form>'
	
	
	//$('#2').after('<br><br><input type="file" id="attachments[]" name="attachments[]" class="" multiple/><button type="button" id="form_sbt" class="btn btn-primary pull-right">Submit</button>');
	//$('#submit').after('<br><br><form method="POST" enctype="multipart/form-data" action="../hs_req_extend" id="form_extend"><input type="file" id="attachments[]" name="attachments[]" class="" multiple/><button type="button" id="form_sbt" class="btn btn-primary pull-right">Submit</button><input type="hidden" id="form_data" name="form_data"/></form>');
	



  

})
</script>

