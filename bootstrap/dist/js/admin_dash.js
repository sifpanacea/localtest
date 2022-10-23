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
		var async=true;
		var print_docid='', print_appid='';
		var init;
		var _company_ ='';
		var chart_img='';
		var graph_img='';
		// PAGE RELATED SCRIPTS
	
		/*
		 * Fixed table height
		 */
		
		tableHeightSize()
		
		$(window).resize(function() {
			tableHeightSize()
		})
		
		/* function username()
			{
			$.ajax({
				url: 'username',
				type: 'POST',
				
				success: function (data) {
							
							// console.log('usernameeeeeeeeeeeeeeeee',data);
							users=data;
							div = "";
						  // console.log('success', data);
						  user = jQuery.parseJSON(data);
						  // console.log('username', user);
						  div = div + "<div>"+user+"</div>";
						  
						  $('#webuser_username').html(div);
						 },
				error: function (XMLHttpRequest, textStatus, errorThrown)
				{
				console.log('error', errorThrown);
				}
			})
			}username();
			 */
			
		function tableHeightSize() {
			var tableHeight = $(window).height() - 212;
			$('.table-wrap').css('height', tableHeight + 'px');
		}
		
		/*
		 * LOAD INBOX MESSAGES
		 */
		/* function loadInbox() 
		{	
			$('.inbox_add').removeClass("inbox_add_external");
			if(!$('.page_lbl').hasClass('hide'))
			{
			$('.page_lbl').addClass('hide');
			$('.page_no').addClass('hide');		
			}
			$('.print_preview').remove();
			$('.print_view').remove();
			$('.print_delete').remove();
			image_string={};
			/* applist(); */
			//start=0;
			//end=12;
			/* function applist()
			{
			$('.inbox-menu-lg').children('li').removeClass("active");
			$('.inbox-load').parent('li').addClass("active");
			$.ajax({
      		url: 'get_update_apps',
		    type: 'POST',
			async:false,
			beforeSend : function ()
			{
				beforeload();//loading image
			},
			success: function (data) {
						
					  div = "";
					  if(data == "false")
					  {
						host=window.location.origin
						window.location.href = ''+host+'/PaaS';
					  }
					  update_apps_list = jQuery.parseJSON(data);
					  var appcount= update_apps_list.length;
					  if(appcount!==0)
					  {
					  for (var i=0; i<appcount; i++) {
						app_name        = update_apps_list[i].app_name;
						app_description = update_apps_list[i].app_description;
						app_created     = update_apps_list[i].app_created;
						app_id 			= update_apps_list[i].app_id;
						$('<tr id='+app_id+' class="unread lists" category="app" appid='+app_id+'><td class="inbox-table-icon"><div class="checkbox"><label><input type="checkbox" class=checkbox style-2"><span></span></label></div></td><td class="inbox-data-from inbox-name hidden-xs hidden-sm"><div>'+app_name+'</div></td><td class="inbox-data-message inbox-subject"><div><span><span class="label bg-color-orange">Application</span> '+app_description+'</span></div></td><td class="hide inbox-data-attachment hidden-xs"><div><a href="javascript:void(0);" rel="tooltip" data-placement="left" data-original-title="FILES: rocketlaunch.jpg, timelogs.xsl" class="txt-color-darken"><i class="fa fa-paperclip fa-lg"></i></a></div></td><td class="inbox-data-date hidden-xs"><div class="_date">'+app_created+'</div></td></tr>').appendTo('.inbox_add');$('.lists').hide();
					  }
					  }
					  else
					  {
						  
					  }
					  //$('#update_apps').html(div);
					  
				     },
						error: function (XMLHttpRequest, textStatus, errorThrown)
						{
						// console.log('error', errorThrown);
						}
			}); 
			doclist(); 
			} */
			/* function doclist()
			{
			$.ajax({
				url: 'get_update_docs',
				type: 'POST',
				async:false,
				success: function (data) 
				{
				if(data){
				update_docs_list = jQuery.parseJSON(data);
				var doc_coll_details = update_docs_list.doc;
                var doc_widget_details = update_docs_list.widget;


				 var doccount= doc_coll_details.length;
					  if(doccount!=0)
					  {
					  for (var i=0; i<doccount; i++) 
					  {
						app_name        = doc_coll_details[i].app_name;
						app_id 			= doc_coll_details[i].app_id;
						stg_name    	= doc_coll_details[i].stg_name;
						doc_id 			= doc_coll_details[i].doc_id;
						time 			= doc_coll_details[i].doc_received_time;
						notification    = doc_coll_details[i].notification_param;
						
					   $('<tr id='+doc_id+' class="unread lists" category="doc" appid='+app_id+' docid='+doc_id+'><td class="inbox-table-icon"><div class="checkbox"><label><input type="checkbox" class=checkbox style-2"><span></span></label></div></td><td class="inbox-data-from inbox-name hidden-xs hidden-sm"><div>'+app_name+'</div></td><td class="inbox-data-message inbox-subject"><div><span class="notify_class'+doc_id+'"><span class="label bg-color-orange">Document</span></span></div></td><td class="hide inbox-data-attachment hidden-xs"><div><a href="javascript:void(0);" rel="tooltip" data-placement="left" data-original-title="FILES: rocketlaunch.jpg, timelogs.xsl" class="txt-color-darken"><i class="fa fa-paperclip fa-lg"></i></a></div></td><td class="inbox-data-date hidden-xs"><div class="_date">'+time+'</div></td></tr>').appendTo('.inbox_add');
					   
					   for(var param in notification)
						{
							var param_name = param;
							var param_value = notification[param];
					   	$('<span>   '+param_name+' : '+param_value+'    </span>').appendTo('.notify_class'+doc_id+'');
						}

					   $('.lists').hide();
				   }
				   }

				}
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)
				{
				// console.log('error', errorThrown);
				}
			})
			show_initial();
			} */
			/*if($('.inbox_add').children('.lists').length==0)
			{
				 $('<pre class="inbox-empty"><div class="inbox-info">Your inbox is empty</div></pre>').appendTo('.inbox_add');	
			}*/
		//}
		/* loadInbox(); */
		//
		//function for hiding the list and show first 12 for pagination//
		//
		/* function show_initial()
			{
				if($('.inbox_add').children('.lists').length==0)
				{
				 $('<pre class="inbox-empty"><div class="inbox-info">Your inbox is empty</div></pre>').appendTo('.inbox_add');	
				}
				else
				{
				$('.printbutton').addClass('hide');
				$(".next").removeAttr("disabled");
				
				start=0;
				end=12;
				$('.inbox_add').children('.lists').slice(start,end).show();
				$(".previous").attr("disabled","disabled")
				var tot=$('.inbox_add').children('.lists').length;
				$('.total').text(tot);
				if($('.inbox_add').children('.lists').hasClass('unread'))
				{
				var tot_=$('.inbox_add').children('.lists').length
				$('.inbox_count').text('('+tot_+')');
				}
				var start_;
				start_=start+1;
				var join=''+start_+'-'+end+'';
				$('.current').text(join);
				//deleting the loading image
				}
				afterload();
			} */
		
		
		
		/*
		 * Buttons (compose mail and inbox load)
		 */
		/* $(document).on('click',".inbox-load",function() {
			
		    $('.inbox_add').empty();
			$('.for_remove').remove();
			loadInbox();
		}); */
	
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
					//console.log("dffffffffff");
				}
			})
		
		
		//$('#hide-menu').find('a').trigger("click");
		
		
		//
		// View for Application// trigger when clicked the application menu
		//
		/* $(document).on('click','.application',function()
		{
			$('.inbox_add').removeClass("inbox_add_external");
			if(!$('.page_lbl').hasClass('hide'))
			{
			$('.page_lbl').addClass('hide');
			$('.page_no').addClass('hide');		
			}
			$('.inbox-menu-lg').children('li').removeClass("active");
			$('.application').parent('li').addClass("active");
			beforeload();
			$( ".inbox_add" ).empty();
			$('.for_remove').remove();
			$('.print_preview').remove();
			$('.print_view').remove();
			$('.print_delete').remove();
			print_image_string = {} ;
			image_string={};
			var url = 'apps';
			$( ".inbox_add" ).load( url, function(response) 
			{
				
			if(response == "false")
			  {
				host=window.location.origin
				//console.log(host);
				window.location.href = ''+host+'/PaaS';
			  }
				afterload();
				//$('.for_remove').remove();
					$( ".inbox_add" ).children('.lists').hide();
					show_initial();		
			});
			//show_initial();
		}); *///.application end
		
		/* $(document).on("click",".read-apps",function()
		{
			$('.inbox_add').removeClass("inbox_add_external");
			if(!$('.page_lbl').hasClass('hide'))
			{
			$('.page_lbl').addClass('hide');
			$('.page_no').addClass('hide');		
			}
			$('.inbox-menu-lg').children('li').removeClass("active");
			$('.read-apps').parent('li').addClass("active");
			$('.inbox_add').empty();
			$('.for_remove').remove();
			image_string={};
			read_applist();
			
		}) */
		
		/* function read_applist()
		{
			$.ajax({
      		url: 'get_update_read_apps',
		    type: 'POST',
			async:false,
			timeout: 10000 ,
			success: function (data) {
					
			if(data == "false")
			  {
				host=window.location.origin
				//console.log(host);
				window.location.href = ''+host+'/PaaS';
			  }					
					  users=data;
			          update_read_apps_list = jQuery.parseJSON(data);
					  var appcount= update_read_apps_list.length;
					  if(appcount>0)
					  {
						  for (var i=0; i<appcount; i++) 
						  {
							app_name        = update_read_apps_list[i].app_name;
							app_description = update_read_apps_list[i].app_description;
							app_created     = update_read_apps_list[i].app_created;
							app_id 			= update_read_apps_list[i].app_id;
							$('<tr id='+app_id+' class="read lists" category="app" appid='+app_id+'><td class="inbox-table-icon"><div class="checkbox"><label><input type="checkbox" class=checkbox style-2"><span></span></label></div></td><td class="inbox-data-from inbox-name hidden-xs hidden-sm"><div>'+app_name+'</div></td><td class="inbox-data-message inbox-subject"><div><span><span class="label bg-color-orange">Application</span> '+app_description+'</span></div></td><td class="inbox-data-date hidden-xs"><div class="_date">'+app_created+'</div></td></tr>').appendTo('.inbox_add');
							//$('.lists').hide();
						  }
					  }
					  else
					  {
						 /*app="No new apps for you";
						 $('<tr id="msg1" class="unread"><td class="inbox-table-icon"><div class="checkbox"><label><input type="checkbox" class=checkbox style-2"><span></span></label></div></td><td class="inbox-data-from hidden-xs hidden-sm"><div>'+app+'</div></td><td class="inbox-data-message"><div><span><span class="label bg-color-orange">Application</span> Karjua Marou</span> New server for datacenter needed</div></td><td class="inbox-data-attachment hidden-xs"><div><a href="javascript:void(0);" rel="tooltip" data-placement="left" data-original-title="FILES: rocketlaunch.jpg, timelogs.xsl" class="txt-color-darken"><i class="fa fa-paperclip fa-lg"></i></a></div></td><td class="inbox-data-date hidden-xs"><div>10:23 am</div></td></tr>').appendTo('.inbox_add')
						  
					  }
				  
				     },
						error: function (XMLHttpRequest, textStatus, errorThrown)
						{
						 console.log('error', errorThrown);
						}
			});
			show_initial();
		} */
		
		/* $(document).on('click',".inbox-subject",function()
		{
			//$this = $(this);
			var _type = $(this).parents('tr').attr('category');
			if(_type=="app")
			{
			//$('.inbox_add').empty();
			var app_id=$(this).parents('tr').attr('appid');
			in_detail_app(app_id)
			}
			else
			{
			var app_id=$(this).parents('tr').attr('appid');
			var doc_id=$(this).parents('tr').attr('docid');
			in_detail_doc(app_id,doc_id);
			}
		}) */
				
		/* $(document).on('click',".inbox-name",function(e)
		{
			//$this = $(this);
			if($(e.target).is("button")) 
			{
				//console.log("bbbbbbbbbbbbbbbuuuuuuuuuttttttttttttttnnnnnnnn")
			}
			{
				var _type = $(this).parents('tr').attr('category');
				if(_type=="app")
				{
				//$('.inbox_add').empty();
				var app_id=$(this).parents('tr').attr('appid');
				in_detail_app(app_id)
				}
				else
				{
				var app_id=$(this).parents('tr').attr('appid');
				var doc_id=$(this).parents('tr').attr('docid');
				in_detail_doc(app_id,doc_id);
				}
			}
		}) */
		
		//
		//one step opened view of app with ajax call
		//				
		/* function in_detail_app(app_id)
		{
		_app=app_id;
		$('.printbutton').addClass('hide');
		$.ajax({//ajax call for changing the status
				url: 'set_status_apps/'+app_id,
				type: 'POST',
				success: function (data) 
				{
					
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
				}
			})
		$.ajax({
		url: 'get_application_details/'+app_id,
		type: 'POST',
		//async:false,
		success: function (data) 
		{
			if(data == "false")
			  {
				host=window.location.origin
				//console.log(host);
				window.location.href = ''+host+'/PaaS';
			  }
			  $(".previous").attr("disabled","disabled")
			  $(".next").attr("disabled","disabled")
			$('.inbox_add').empty();
			var j_data=jQuery.parseJSON(data);
			
			var japp_data  = j_data.app_details;
			var juser_data = j_data.user_details;
			
			// version and admin
			version       = japp_data._version;
			created_admin = japp_data.created_by;
			
			var protocol = $(location).attr('protocol');
			var hostname = $(location).attr('hostname');
			var pathname = $(location).attr('pathname');
		
			var fin_path=pathname.split('/',3)
            fin_path=fin_path.toString();
            var path=fin_path.replace(/,/g , "/");
            var from_app= juser_data.company;//app_id.replace(/[0-9]/g, '');
            var index_="index.php"
			var url=''+protocol+'//'+hostname+''+path+'/'+index_+'/'+from_app+'/'+app_id+'_con/create';
			
			
			$('<div class="for_remove"><h2 class="email-open-header">'+japp_data.app_name+' <span class="label txt-color-white">Application</span><a href="javascript:void(0);" rel="tooltip" data-placement="left" data-original-title="Print" class="txt-color-darken pull-right hide"><i class="fa fa-print"></i></a></h2><div class="inbox-info-bar" style="margin-right: 20px;"><div class="row"><div class="col-sm-9"><i>[TLSTEC]</i>&nbsp;<span class="hidden-mobile"><strong>'+created_admin+'</strong> to <strong>me</strong> on <i>'+japp_data.app_created+'</i></span></div><div class="col-sm-3 text-right"><div class="btn-group text-left"><button id="app_open" url='+url+' class="btn btn-primary btn-sm replythis"><i class="fa fa-pencil-square-o"></i> Open </button></div></div></div></div><div class="inbox-message" style="margin-right: 20px;"><p> Hey '+juser_data.username+' ! <br><br>&nbsp;&nbsp;&nbsp;&nbsp;I have created the application - <strong>'+japp_data.app_name+'</strong><br><br> &nbsp;&nbsp;&nbsp;&nbsp;version :&nbsp;&nbsp;<strong>'+version+'</strong><br><br> &nbsp;&nbsp;&nbsp;&nbsp;Description : <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+japp_data.app_description+'</p><br><br>Thanks,<br><strong>'+created_admin+'</strong><br><br><small>Admin<br>'+juser_data.company+'<br></small><br></div><div class="inbox-download hide">2 attachment(s) — <a href="javascript:void(0);">Download all attachments</a><ul class="inbox-download-list "><li><div class="well well-sm"><span><img src=""></span><br><strong>rocketlaunch.jpg</strong><br>400 kb<br><a href="javascript:void(0);"> Download</a>  | <a href="javascript:void(0);"> View</a></div></li><li><div class="well well-sm"><span><i class="fa fa-file"></i></span><br><strong>timelogs.xsl</strong><br>1.3 mb<br><a href="javascript:void(0);"> Download</a> | <a href="javascript:void(0);"> Share</a></div></li></ul></div><div class="email-infobox" style="border-bottom-width:0px;"><div class="well well-sm well-light hide"><h5>App Version</h5><ul class="list-unstyled"><li><a href="javascript:void(0);"> version - '+version+'</a></li></ul></div><div class="well well-sm well-light hide"><h5>Upcoming Meetings</h5><p><span class="label label-success"><i class="fa fa-check"></i> <strike>Agenda Review @ 10 AM</strike> </span></p><p><span class="label label-primary"><i class="fa fa-clock-o"></i> Client Meeting @ 2:30 PM</span></p><p><span class="label label-warning"><i class="fa fa-clock-o"></i> Salary Review @ 4:00 PM</span></p></div><ul class="list-inline hide"><li><img src="" alt="me" width="30px"></li><li><img src="" alt="me" width="30px"></li><li><img src="" alt="me" width="30px"></li><li><a href="javascript:void(0);">1 more</a></li></ul></div></div>').appendTo('.table-wrap');
		}
		});
		} */
		
		//
		//one step opened view of document with ajax call
		//		
		/* function in_detail_doc(app_id,doc_id)
		{
		$('.printbutton').addClass('hide');
		_app=app_id;
		_doc=doc_id;
		$.ajax({
		url: 'get_document_details/'+app_id+'/'+doc_id,
		type: 'POST',
		//async:false,
		beforeSend : function ()
		{
			//beforeload(); //loading image
		},
		success: function (data) 
		{
			if(data == "false")
			  {
				host=window.location.origin
				//console.log(host);
				window.location.href = ''+host+'/PaaS';
			  }	
			$('.inbox_add').empty();
			var j_data=jQuery.parseJSON(data);
			//console.log(data);
			var jdoc_data = j_data.doc_details;
			var juser_data = j_data.user_details;
			
			var protocol = $(location).attr('protocol');
			var hostname = $(location).attr('hostname');
			var pathname = $(location).attr('pathname');

			var fin_path=pathname.split('/',3)
			//console.log(fin_path)
			fin_path=fin_path.toString();
			var path=fin_path.replace(/,/g , "/");
			//var from_app=app_id.replace(/[0-9]/g, '');
			var from_app=juser_data.company;
			_company_ = from_app;
			var from_user = jdoc_data.from_user.replace('#', '@');
			//console.log('appppppppppp',from_app)
			var index_="index.php"
			var status = "approved";
			if(jdoc_data.approval == "false")
			{
				status = "disapproved";
			}
			$(".previous").attr("disabled","disabled")
			$(".next").attr("disabled","disabled")
			var url=''+protocol+'//'+hostname+''+path+'/'+index_+'/'+from_app+'/'+app_id+'_con/doc_access/'+doc_id+'';
			//console.log('ddddddddddddddddddddddweeeeeeeeeeeeeee',url)
			var notific = btoa(JSON.stringify(jdoc_data.notification_param));
			$('<div class="for_remove"><h2 class="email-open-header">[TLSTEC]&nbsp;&nbsp;'+jdoc_data.app_name+'&nbsp;&nbsp;<span class="label txt-color-white">Document</span><a href="javascript:void(0);" rel="tooltip" data-placement="left" data-original-title="Print" class="txt-color-darken pull-right hide"><i class="fa fa-print"></i></a></h2><div class="inbox-info-bar"><div class="row"><div class="col-sm-9"><strong>'+from_user+'</strong><span class="hidden-mobile">&nbsp;&nbsp;to&nbsp;&nbsp;<strong>me</strong> on <i>'+jdoc_data.doc_received_time+'</i></span></div><div class="col-sm-3 text-right"><div class="btn-group text-left"><button id="doc_open" class="btn btn-primary btn-sm replythis" doc='+url+' stage='+jdoc_data.stg_name+' notific_par='+notific+'><i class="fa fa-reply"></i> Access</button><ul class="dropdown-menu pull-right"><li><a href="javascript:void(0);" class="replythis"><i class="fa fa-reply"></i> Reply</a></li><li><a href="javascript:void(0);" class="replythis"><i class="fa fa-mail-forward"></i> Forward</a></li><li><a href="javascript:void(0);"><i class="fa fa-print"></i> Print</a></li><li class="divider"></li><li><a href="javascript:void(0);"><i class="fa fa-ban"></i> Mark as spam!</a></li><li><a href="javascript:void(0);"><i class="fa fa-trash-o"></i> Delete forever</a></li></ul></div></div></div></div><div class="inbox-message"><p>Hey '+juser_data.username+',</p><br><p> I have forwaded a document for application <strong>'+jdoc_data.app_name+'</strong>. Please proceed further.&nbsp;<i class="fa fa-smile-o"></i></p><br><p class="for_reason"></p><br><br>Thanks,<br><strong>'+from_user+'</strong><br>'+juser_data.company+'<br><br><br></div><div class="inbox-download hide">2 attachment(s) — <a href="javascript:void(0);">Download all attachments</a><ul class="inbox-download-list"><li><div class="well well-sm"><span><img src=""></span><br><strong>rocketlaunch.jpg</strong><br>400 kb<br><a href="javascript:void(0);"> Download</a>  | <a href="javascript:void(0);"> View</a></div></li><li><div class="well well-sm"><span><i class="fa fa-file"></i></span><br><strong>timelogs.xsl</strong><br>1.3 mb<br><a href="javascript:void(0);"> Download</a> | <a href="javascript:void(0);"> Share</a></div></li></ul></div><div class="email-infobox"><div class="well well-sm well-light"><h5>Current stage info</h5><ul class="list-unstyled"><li><a href="javascript:void(0);">stage name : '+jdoc_data.stg_name+'</a></li><li><a href="javascript:void(0);">User : '+juser_data.identity+'</a></li></ul></div><div class="well well-sm well-light"><h5>Previous stage info</h5><ul class="list-unstyled"><li><a href="javascript:void(0);">Stage name : '+jdoc_data.from_stage+'</a></li><li><a href="javascript:void(0);">User : '+from_user+'</a></li><li><a href="javascript:void(0);">Status : '+status+'</a></li></ul></div><ul class="list-inline hide"><li><img src="" alt="me" width="30px"></li><li><img src="" alt="me" width="30px"></li><li><img src="" alt="me" width="30px"></li><li><a href="javascript:void(0);">1 more</a></li></ul></div></div>').appendTo('.table-wrap');
			
			if(jdoc_data.approval == "false")
			{
				status = "disapproved";
				var reason = jdoc_data.reason || '';
				console.log(reason)
				$('<p><strong>Reason :</strong><br>'+reason+'</p>').appendTo('.for_reason');
			}
		}
		});
		} */
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
			
			if($(this).hasClass("attendance"))
			{
		
		      /* apd__ = "healthcare2016620153753258";
		        _url = "https://mednote.in/PaaS/healthcare/index.php/healthcare/healthcare2016620153753258_con/create"; */
				
		        apd__ = "healthcare201651317373988";
		        _url = "https://mednote.in/PaaS/healthcare/index.php/healthcare/healthcare201651317373988_con/create";
				 
			}	
			
			//console.log("ssss",_url)
			$( ".device" ).load( _url, function(response)
			{
				console.log("response",response)
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
				url: '../printer/index/'+_doc+'/'+_app+'/'+_startpage+'/'+_offset+'/'+type+'/'+init,
				type: 'POST',
				async:async,
				timeout: 10000 ,
				beforeSend: function()
				{
					
				},
				success: function (data) 
				{
				  console.log("data=====643",data);
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
				//console.log(host);
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
				
				$('<div class="files_ext" style="width:650px;"><div class="panel panel-darken"><div class="panel-heading"><h3 class="panel-title external_font">Externally attached files</h3></div><div class="panel-body no-padding text-align-center"><table class="table table-bordered"><tbody class="files_attached"></tbody></table></div></div><div class="panel panel-darken"><div class="panel-heading"><h3 class="panel-title external_font">Immunisation Record</h3></div><div class="panel-body no-padding text-align-center"><table class="table table-bordered"><tbody class="chart_attached"></tbody></table></div></div></div>').appendTo('.inbox_add');
				$('.external_file_attachments').children().each(function (index)
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
				
				$('.files_ext').animate({'margin-left': "-350px"},1000);
				
				//chart
				/* $('<div class="chart_ext" style="width:650px;"><div class="panel panel-darken"><div class="panel-heading"><h3 class="panel-title external_font">Immunization Chart</h3></div><div class="panel-body no-padding text-align-center"><table class="table table-bordered"><tbody class="chart_attached"></tbody></table></div></div></div>').appendTo('.inbox_add'); */
				
				var chart_value = $('#chart_value').attr('chart');
				
				if(chart_value=="true")
				{
				    
					$('<tr style="height:55px;"><td class="ind_val"><p></p></td><td class="word_break"><a  class="external_font chart_link" chart_data="'+chart_img+'" href="#">View Chart</a></td></tr>').appendTo('.chart_attached');
				}
				
				if($('.chart_attached').children('tr').length==0)
				{
					$('<tr class="" style="height:55px;"><td class=""><h1><b>No charts attached<b></h1></td></tr>').appendTo('.chart_attached');	
				}
				
				/* $('.chart_ext').css({
						'transition': 'all 1s',
						'transform': 'scale(0.5)',
				});
				
				$('.chart_ext').animate({'margin-left': "-350px"},1000); */

                //graph
                var graph_value = $('#graph_value').attr('graph');
				console.log("graph_value",graph_value);
                if(graph_value == "true")
				{
				  /* <option value="weight_length">Weight Vs. Length Percentile Graph</option>   */
				  $('<div class="graph_view" style="width:300px;margin-left: -185px;margin-top: -70px;"></div>').appendTo('.inbox_add');
				  $('<label>Select the graph to view :</label><select id="graph_category" name="graph_category"><option value="none">-select-</option><option value="weight_age">Weight Vs. Age Percentile Graph</option><option value="length_age">Length Vs. Age Percentile Graph</option><option value="headcircum_age">Head Circum. Vs. Age Percentile Graph</option></select>').appendTo('.graph_view');
				  
				  $('<div class="graph_heading_panel"><h3 class="panel-title-flow" style="margin-left:-185px; margin-top: -30px;margin-bottom: -30px;"></h3><div id="graph_panel" style="width:500px;height:400px"></div></div>').appendTo('.inbox_add');
				  
				  $( "#graph_category" ).change(function() 
				  {
				      var graph_heading = $("#graph_category option:selected").text();
					  var graph_type    = $("#graph_category option:selected").val();
					  
					  // Update the graph name
					  $('.panel-title-flow').text(graph_heading);
					   
					   //ajax for fetching graph values//
					   var company_folder=_company_;
					   var graph_urL_ = '../'+company_folder+'/'+_app+'_con/fetch_graph_values';

		               // GRAPH PLOT
                         
						$.ajax
						({
						url: graph_urL_,
						type: 'POST',
						data:{'docid':_doc,'graph_type':graph_type},
						async:false,
						beforeSend:function ()
						{

						},
						complete:function ()
						{	
							
						},
						success: function (success_data) 
						{ 
						   // DRAWING ACCORDING TO THE GRAPH TYPE
							switch(graph_type)
							{
							   case 'weight_age':
							  
							   if(success_data!="")
							   {
								   var g_data = JSON.parse(success_data);
								   
								   var x_axis_obj = g_data.x_axis;
								   var y_axis_obj = g_data.y_axis;
								   var values_obj = g_data.values; 
								   
								   // X-AXIS
								   var x_axis_start = x_axis_obj.start;
								   var x_axis_end   = x_axis_obj.end;
								   var x_axis_label = x_axis_obj.label;
								   var x_axis_size  = x_axis_obj.size;
								   
								   // Y-AXIS
								   var y_axis_start = y_axis_obj.start;
								   var y_axis_end   = y_axis_obj.end;
								   var y_axis_label = y_axis_obj.label;
								   var y_axis_size  = y_axis_obj.size;
								   
								   //VALUES
								   var default_val = values_obj.default_axis_val;
								   var current_val = values_obj.current_axis_val;
								   
								   var minDate="2015-06";
								   var maxDate="2016-05";
								   
								   $.plot($("#graph_panel"), [{data:default_val},{data:current_val}], {
						
									series: 
									{
										  lines : {show: true},
										  points: {show: true}
									},
									xaxis : 
									{
										mode: "time",
										ticks: 12,
										timeformat:"%b %Y",
										minTickSize:[x_axis_size,"month"],
										axisLabel: x_axis_label,
										axisLabelUseCanvas: true,
										axisLabelFontSizePixels: 12,
										axisLabelFontFamily: 'Verdana, Arial',
										axisLabelPadding: 10,
										min:x_axis_start,
										max:x_axis_end
									},
                                    grid: 
									{
										borderColor: 'black',
										borderWidth: 1
									},
									yaxis : 
									{
										min: y_axis_start,
										max: y_axis_end,
										tickSize:y_axis_size,
										axisLabel:y_axis_label,
										axisLabelUseCanvas: true,
										axisLabelFontSizePixels: 12,
										axisLabelFontFamily: 'Verdana, Arial',
										axisLabelPadding: 10
									}
									}); 
							
									$('#graph_panel').css({
											'transition': 'all 1s',
											'transform': 'scale(0.8)',
									}); 
									
									$('#graph_panel').animate({'margin-left': "-250px"},1000);
							   }
							   else
							   {
							     $('#graph_panel').html('<div class="alert alert-info fade in" style="width:600px;height:300px"><h4><i class="fa-fw fa fa-info"></i><strong>Info!</strong><center>No graph to display</center></h4></div>');
								 
								 $('#graph_panel').css({
											'transition': 'all 1s',
											'transform': 'scale(0.8)',
								 }); 
								
								$('#graph_panel').animate({'margin-left': "-250px"},1000);
							   }
							   break;
							   
							   case 'length_age':
							   if(success_data!="")
							   {
								   var g_data = JSON.parse(success_data);
								 
								   var x_axis_obj = g_data.x_axis;
								   var y_axis_obj = g_data.y_axis;
								   var values_obj = g_data.values; 
								   
								   // X-AXIS
								   var x_axis_start = x_axis_obj.start;
								   var x_axis_end   = x_axis_obj.end;
								   var x_axis_label = x_axis_obj.label;
								   var x_axis_size  = x_axis_obj.size;
								   
								   // Y-AXIS
								   var y_axis_start = y_axis_obj.start;
								   var y_axis_end   = y_axis_obj.end;
								   var y_axis_label = y_axis_obj.label;
								   var y_axis_size  = y_axis_obj.size;
								   
								   //VALUES
								   var default_val = values_obj.default_axis_val;
								   var current_val = values_obj.current_axis_val;
								   
								   $.plot($("#graph_panel"), [ {data:default_val},{data:current_val} ], {
						
									series: 
									{
										  lines : {show:true},
										  points: {show:true}
									},
									xaxis : 
									{
										mode: "time",
										ticks: 12,
										timeformat:"%b %Y",
										minTickSize: [x_axis_size, "month"],
										axisLabel: x_axis_label,
										axisLabelUseCanvas: true,
										axisLabelFontSizePixels: 12,
										axisLabelFontFamily: 'Verdana, Arial',
										axisLabelPadding: 10,
										min: x_axis_start,
										max: x_axis_end
									},
									grid: 
									{
										borderColor: 'black',
										borderWidth: 1
									},
									yaxis : 
									{
										min: y_axis_start,
										max: y_axis_end,
										tickSize:y_axis_size,
										axisLabel: y_axis_label,
										axisLabelUseCanvas: true,
										axisLabelFontSizePixels: 12,
										axisLabelFontFamily: 'Verdana, Arial',
										axisLabelPadding: 10
									}
									}); 
							
									$('#graph_panel').css({
											'transition': 'all 1s',
											'transform': 'scale(0.8)',
									}); 
									
									$('#graph_panel').animate({'margin-left': "-250px"},1000);
							   }
							   else
							   {
							      $('#graph_panel').html('<div class="alert alert-info fade in" style="width:600px;height:300px"><h4><i class="fa-fw fa fa-info"></i><strong>Info!</strong><center>No graph to display</center></h4></div>');
								 
								  $('#graph_panel').css({
											'transition': 'all 1s',
											'transform': 'scale(0.8)',
								  }); 
								
							      $('#graph_panel').animate({'margin-left': "-250px"},1000);
							   }
							   break;
							   
							   case 'weight_length':
							   if(success_data!="")
							   {
								   var g_data = JSON.parse(success_data);
								   
								   var x_axis_obj = g_data.x_axis;
								   var y_axis_obj = g_data.y_axis;
								   var values_obj = g_data.values; 
								   
								   // X-AXIS
								   var x_axis_start = x_axis_obj.start;
								   var x_axis_end   = x_axis_obj.end;
								   var x_axis_label = x_axis_obj.label;
								   var x_axis_size  = x_axis_obj.size;
								   
								   // Y-AXIS
								   var y_axis_start = y_axis_obj.start;
								   var y_axis_end   = y_axis_obj.end;
								   var y_axis_label = y_axis_obj.label;
								   var y_axis_size  = y_axis_obj.size;
								   
								   //VALUES
								   var default_val = values_obj.default_axis_val;
								   var current_val = values_obj.current_axis_val;
								   
								   $.plot($("#graph_panel"), [ {data:default_val},{data:current_val} ], {
						
									series: 
									{
										  lines : {show: true},
										  points: {show: true}
									},
									xaxis : 
									{
									    min: x_axis_start,
										max: x_axis_end,
										tickSize:x_axis_size,
										axisLabel: x_axis_label,
										axisLabelUseCanvas: true,
										axisLabelFontSizePixels: 12,
										axisLabelFontFamily: 'Verdana, Arial',
										axisLabelPadding: 10
									},
									grid: 
									{
										borderColor: 'black',
										borderWidth: 1
									},
									yaxis : 
									{
										min: y_axis_start,
										max: y_axis_end,
										tickSize:y_axis_size,
										axisLabel: y_axis_label,
										axisLabelUseCanvas: true,
										axisLabelFontSizePixels: 12,
										axisLabelFontFamily: 'Verdana, Arial',
										axisLabelPadding: 10
									}
									}); 
							
									$('#graph_panel').css({
											'transition': 'all 1s',
											'transform': 'scale(0.8)',
									}); 
									
									$('#graph_panel').animate({'margin-left': "-250px"},1000);
							   }
							   else
							   {
							     $('#graph_panel').html('<div class="alert alert-info fade in" style="width:600px;height:300px"><h4><i class="fa-fw fa fa-info"></i><strong>Info!</strong><center>No graph to display</center></h4></div>');
								 
							     $('#graph_panel').css({
										'transition': 'all 1s',
										'transform': 'scale(0.8)',
							     }); 
								
							     $('#graph_panel').animate({'margin-left': "-250px"},1000);
							   }
							   break;
							   
							   case 'headcircum_age':
							   if(success_data!="")
							   {
								   var g_data = JSON.parse(success_data);
								   
								   var x_axis_obj = g_data.x_axis;
								   var y_axis_obj = g_data.y_axis;
								   var values_obj = g_data.values; 
								   
								   // X-AXIS
								   var x_axis_start = x_axis_obj.start;
								   var x_axis_end   = x_axis_obj.end;
								   var x_axis_label = x_axis_obj.label;
								   var x_axis_size  = x_axis_obj.size;
								   
								   // Y-AXIS
								   var y_axis_start = y_axis_obj.start;
								   var y_axis_end   = y_axis_obj.end;
								   var y_axis_label = y_axis_obj.label;
								   var y_axis_size  = y_axis_obj.size;
								   
								   //VALUES
								   var default_val = values_obj.default_axis_val;
								   var current_val = values_obj.current_axis_val;
								   
								   $.plot($("#graph_panel"), [ {data:default_val},{data:current_val} ], {
						
									series: 
									{
										  lines : {show: true},
										  points: {show: true}
									},
									xaxis : 
									{
										mode: "time",
										ticks: 12,
										timeformat:"%b %Y",
										minTickSize: [x_axis_size, "month"],
										axisLabel: x_axis_label,
										axisLabelUseCanvas: true,
										axisLabelFontSizePixels: 12,
										axisLabelFontFamily: 'Verdana, Arial',
										axisLabelPadding: 10,
										min: x_axis_start,
										max: x_axis_end
									},
									grid: 
									{
										borderColor: 'black',
										borderWidth: 1
									},
									yaxis : 
									{
										min: y_axis_start,
										max: y_axis_end,
										tickSize:y_axis_size,
										axisLabel: y_axis_label,
										axisLabelUseCanvas: true,
										axisLabelFontSizePixels: 12,
										axisLabelFontFamily: 'Verdana, Arial',
										axisLabelPadding: 10
									}
									}); 
							
									$('#graph_panel').css({
											'transition': 'all 1s',
											'transform': 'scale(0.8)',
									}); 
									
									$('#graph_panel').animate({'margin-left': "-250px"},1000);
							   }
							   else
							   {
							      $('#graph_panel').html('<div class="alert alert-info fade in" style="width:600px;height:300px"><h4><i class="fa-fw fa fa-info"></i><strong>Info!</strong><center>No graph to display</center></h4></div>');
								 
							      $('#graph_panel').css({
										'transition': 'all 1s',
										'transform': 'scale(0.8)',
							      }); 
								
							      $('#graph_panel').animate({'margin-left': "-250px"},1000);
							   }
							   break;
							   
							   default:
							   $('#graph_panel').html('<div class="alert alert-info fade in" style="width:600px;height:300px"><h4><i class="fa-fw fa fa-info"></i><strong>Info!</strong><center>No graph to display</center></h4></div>');
								 
							   $('#graph_panel').css({
										'transition': 'all 1s',
										'transform': 'scale(0.8)',
							   }); 
								
							   $('#graph_panel').animate({'margin-left': "-250px"},1000);
							   break;
							}
						},
						error: function (XMLHttpRequest, textStatus, errorThrown)
						{
							console.log('error', errorThrown);
						}
						})//ajax call end
					
					});
					
				}	
				
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
			var start = 1, off = 2, asyn = true;
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
		
		var apd__ = "healthcare2016531124515424";
		var url = "https://mednote.in/PaaS/healthcare/index.php/healthcare/healthcare2016531124515424_con/create";
		
		//update the schools list of selected district.//
		/*$(document).on('change','#page1_AttendenceDetails_District',function() 
		{
		  var school_list = "";
		  var selec_district_schools  = "";
		  var unselec_district_schools = "";
		  
          var district = $(this).val();
          
		  $('#page1_AttendenceDetails_SelectSchool > option').each(function(){
		  
		      var school = $(this).val();
			  $(this).removeClass("hide");
			  
			  if (school.indexOf(district) > -1) 
			  {
			    selec_district_schools+="<option value='"+$(this).val()+"'>"+$(this).val()+"</option>";
			  }
			  else
			  {
		        unselec_district_schools+="<option class='hide' value='"+$(this).val()+"'>"+$(this).val()+"</option>";
 			  }
			  
			  $('#page1_AttendenceDetails_SelectSchool').empty();
			 
			  school_list += selec_district_schools;
			  school_list += unselec_district_schools;
			  $('#page1_AttendenceDetails_SelectSchool').append(school_list);
			  
			  })
        });*/
		
	});	