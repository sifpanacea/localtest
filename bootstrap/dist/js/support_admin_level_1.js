$(document).ready(function() {

var status = "new";
var start=0,end=12;
var indetail_view_id='', indetail_view_logid='';

		//function for getting username using ajax//
		function username()
		{
			$.ajax({
				url: 'first_level_admin_username',
				type: 'POST',
				success: function (data) 
				{
					users=data;
					div = "";
					user = jQuery.parseJSON(data);
				    div = div + "<div>"+user+"</div>";
				    $('#support_admin_level_1_username').html(div);
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)
				{
				console.log('error', errorThrown);
				}
			})
		}username();
		
		function tableHeightSize() 
		{
			var tableHeight = $(window).height() - 212;
			$('.table-wrap').css('height', tableHeight + 'px');
		}
		
		//to alter table height//
		tableHeightSize()
		$(window).resize(function() {
			tableHeightSize()
		})
		
		/*
		 * LOAD INBOX MESSAGES
		 */
			loadInbox(status);
			//$('.inbox_add').removeClass("inbox_add_external");
			//if(!$('.page_lbl').hasClass('hide'))
			//{
			//$('.page_lbl').addClass('hide');
			//$('.page_no').addClass('hide');		
			//}
			//$('.print_preview').remove();
			//$('.print_view').remove();
			//$('.print_delete').remove();
			//image_string={};
			//start=0;
			//end=12;
			function loadInbox(status)
			{
			//$('.inbox-menu-lg').children('li').removeClass("active");
			//$('.inbox-load').parent('li').addClass("active");
			console.log("status",status)
				$.ajax({
				url: 'fetch_first_level_admin_inbox/'+status,
				type: 'POST',
				async:false,
				beforeSend : function ()
				{
					//beforeload();//loading image
				},
				success: function (data) 
				{
							
						  update_debug_list = jQuery.parseJSON(data);
						  var logcount= update_debug_list.length;
						  if(logcount!==0)
						  {
						  for (var i=0; i<logcount; i++) {
							dev_uniq_no     = update_debug_list[i].device_unique_number;
							log_id          = update_debug_list[i].crash_id;
							app             = update_debug_list[i].crashed_app;
							level           = update_debug_list[i].log_level;
							time 			= update_debug_list[i].log_received_time;
							$('<tr id='+dev_uniq_no+' log='+log_id+' class="unread lists" checked=""><td class="inbox-table-icon"><div class="checkbox del_check"><label><input type="checkbox" id="delcheckbox" class=checkbox style-2"><span></span></label></div></td><td class="inbox-data-from inbox-name hidden-xs hidden-sm"><div>'+dev_uniq_no+'</div></td><td class="inbox-data-message inbox-subject"><div><span><span class="label bg-color-orange">'+app+'</span> Service Request Number :</span> '+log_id+'</div></td><td class="inbox-data-date hidden-xs"><div class="_date">'+time+'</div></td></tr>').appendTo('.inbox_add');
							$('.lists').hide();
						  }
						  }
						  else
						  {
							  
						  }
						  //$('#update_apps').html(div); 
						  
				},
							error: function (XMLHttpRequest, textStatus, errorThrown)
							{
							 console.log('error', errorThrown);
							}
				});
				show_initial();
				
			}
			/*if($('.inbox_add').children('.lists').length==0)
			{
				 $('<pre class="inbox-empty"><div class="inbox-info">Your inbox is empty</div></pre>').appendTo('.inbox_add');	
			}*/
			
				//
		//function for hiding the list and show first 12 for pagination//
		//
		function show_initial()
			{
				if($('.inbox_add').children('.lists').length==0)
				{
				 $('<pre class="inbox-empty"><div class="inbox-info">No New Logs</div></pre>').appendTo('.inbox_add');	
				}
				else
				{
				//$('.printbutton').addClass('hide');
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
				//afterload();
				
			}//show intial end
		
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
		$(document).on('click',".inbox-subject",function()
		{
			var log_id = $(this).parents('tr').attr("log");
			var dev_id = $(this).parents('tr').attr("id");
			in_detail_log(log_id,dev_id);
		})//inbox-subject end
				
		$(document).on('click',".inbox-name",function(e)
		{
			console.log("name");
			console.log($(this).parents('tr').attr("id"))
			var log_id = $(this).parents('tr').attr("log");
			var dev_id = $(this).parents('tr').attr("id");
			in_detail_log(log_id,dev_id);			
		})//inbox-name end
		
		//clicked when inbox-subject/inbox-name//
		function in_detail_log(log_id,dev_id)
		{
			//_app=log_id;
			/* $.ajax({//ajax call for changing the status
					url: 'set_status_apps/'+log_id,
					type: 'POST',
					success: function (data) 
					{
					
					},
					error: function (XMLHttpRequest, textStatus, errorThrown)
					{
					 console.log('error', errorThrown);
					}
				}) */
			$.ajax({
			url: 'get_primary_inbox_entry_in_detail/'+dev_id+'/'+log_id,
			type: 'POST',
			//async:false,
			success: function (data) 
			{
				$('.inbox_add').empty();
				var log_data=jQuery.parseJSON(data);
				
				var firmware_details = log_data.inbox_data.device_firmware_details;
				var userdetails = log_data.user_details;
				var current_active = $('.inbox-menu-lg').children('.active').attr("id");
				
				if(current_active == "resolve_id")
				{
					console.log("true");
					var resolved_lev = log_data.inbox_data.resolved_level;
					console.log(resolved_lev)
						indetail_view_id=dev_id;
						indetail_view_logid=log_id;
						$('<div class="for_remove"><h2 class="email-open-header">'+log_data.inbox_data.crashed_app+'<a href="javascript:void(0);" rel="tooltip" data-placement="left" data-original-title="Print" class="txt-color-darken pull-right"><i class="fa fa-print"></i></a></h2><div class="inbox-info-bar"><div class="row"><div class="col-sm-6"><strong>'+log_data.inbox_data.device_unique_number+'</strong> to <strong>me</strong> on <i>'+log_data.inbox_data.log_received_time+'</i></span></div><div class="col-sm-6 text-right"><div class="btn-group text-left"><button id="log_forward" devi="'+dev_id+'" logi="'+log_id+'"class="btn btn-primary btn-sm" style="margin-right:5px"><i class="fa fa-mail-forward"></i> Forward </button></div></div></div></div><div class="inbox-message" id="'+log_id+'message" service="'+log_id+'" userinfo="'+userdetails.email+'"><p></p><div class="resolved_div"><strong><u>Resolution Details</u></strong><br><br><table class="table table-bordered"><tbody class="rslvd"><tr><td>Resolved By</td><td>'+log_data.inbox_data.resolved_by+'</td></tr><tr><td>Resolved On</td><td>'+log_data.inbox_data.resolved_on+'</td></tr><tr class="success"><td>Resolution</td><td>'+log_data.inbox_data.resolution+'</td></tr></tbody></table></div><br><strong><u>Service Request Number</u></strong><br><i>'+log_id+'</i><br><br><strong><u>Crashed Time</u></strong><br><i>'+log_data.inbox_data.crashed_time+'</i><br><br><strong><u>Device Details</u></strong><br><strong>SDK - </strong>'+firmware_details.SDK+'<br><strong>Release - </strong>'+firmware_details.Release+'<br><strong>Incremental - </strong>'+firmware_details.Incremental+'<br><br><strong><u>Log Details</u></strong><br></div><div class="email-infobox"><div class="well well-sm well-light"><h5><strong><u>User Details</u></strong></h5><ul class="list-unstyled"><li class="log_para"><i class="fa fa-user fa-fw text-success"></i>'+userdetails.username+' ('+userdetails.status+')</li><li class="log_para"><i class="fa fa-envelope fa-fw text-primary"></i>'+userdetails.email+'</li><li><i class="fa fa-phone fa-fw text-warning"></i>'+userdetails.phone+'</li></ul></div><div class="well well-sm well-light"><h5><strong><u>Subscription Details</u></strong></h5><ul class="list-unstyled"><li class="log_para"><i class="fa fa-credit-card fa-fw text-primary"></i> '+userdetails.plan_subscribed+'</li><li><i class="fa fa-building fa-fw text-warning"></i> '+userdetails.subscribed_with+'</li></ul></div></div></div>').appendTo('.table-wrap');
						
						var message_length = log_data.inbox_data.log_details.length-1;
						for(var i=0;i<message_length;i++)
						{
							$('<p class="log_para">'+log_data.inbox_data.log_details[i]+'</p>').appendTo('#'+log_id+'message');
						}
						if(resolved_lev == 2)
						{
							$('<tr><td>Forwarded By</td><td>'+log_data.inbox_data.forwarded_by+'</td></tr><tr><td>Forwarded On</td><td>'+log_data.inbox_data.forwarded_on+'</td></tr>').prependTo('.rslvd')
						}
					
				}
				else if(current_active == "forwarded_id")
				{
					
					indetail_view_id=dev_id;
					indetail_view_logid=log_id;
					$('<div class="for_remove"><h2 class="email-open-header">'+log_data.inbox_data.crashed_app+'<a href="javascript:void(0);" rel="tooltip" data-placement="left" data-original-title="Print" class="txt-color-darken pull-right"><i class="fa fa-print"></i></a></h2><div class="inbox-info-bar"><div class="row"><div class="col-sm-6"><strong>'+log_data.inbox_data.device_unique_number+'</strong> to <strong>me</strong> on <i>'+log_data.inbox_data.log_received_time+'</i></span></div><div class="col-sm-6 text-right"><div class="btn-group text-left"><button id="log_forward" devi="'+dev_id+'" logi="'+log_id+'"class="btn btn-primary btn-sm" style="margin-right:5px"><i class="fa fa-mail-forward"></i> Forward </button></div></div></div></div><div class="inbox-message" id="'+log_id+'message" service="'+log_id+'" userinfo="'+userdetails.email+'"><p></p><br><strong><u>Service Request Number</u></strong><br><i>'+log_id+'</i><br><br><strong><u>Crashed Time</u></strong><br><i>'+log_data.inbox_data.crashed_time+'</i><br><br><strong><u>Device Details</u></strong><br><strong>SDK - </strong>'+firmware_details.SDK+'<br><strong>Release - </strong>'+firmware_details.Release+'<br><strong>Incremental - </strong>'+firmware_details.Incremental+'<br><br><strong><u>Log Details</u></strong><br></div><div class="email-infobox"><div class="well well-sm well-light"><h5><strong><u>User Details</u></strong></h5><ul class="list-unstyled"><li class="log_para"><i class="fa fa-user fa-fw text-success"></i>'+userdetails.username+' ('+userdetails.status+')</li><li class="log_para"><i class="fa fa-envelope fa-fw text-primary"></i>'+userdetails.email+'</li><li><i class="fa fa-phone fa-fw text-warning"></i>'+userdetails.phone+'</li></ul></div><div class="well well-sm well-light"><h5><strong><u>Subscription Details</u></strong></h5><ul class="list-unstyled"><li class="log_para"><i class="fa fa-credit-card fa-fw text-primary"></i> '+userdetails.plan_subscribed+'</li><li><i class="fa fa-building fa-fw text-warning"></i> '+userdetails.subscribed_with+'</li></ul></div><div class="well well-sm well-light"><h5><strong><u>Forwarded Details</u></strong></h5><ul class="list-unstyled"><li class="log_para"><i class="fa fa-mail-forward fa-fw text-primary"></i> '+log_data.inbox_data.forwarded_to+'</li><li><i class="fa fa-clock-o fa-fw text-warning"></i>'+log_data.inbox_data.forwarded_on+'</li><li><i class="fa fa-mail-reply fa-fw text-success"></i>'+log_data.inbox_data.forwarded_by+'</li></ul></div></div></div>').appendTo('.table-wrap');
					var message_length = log_data.inbox_data.log_details.length-1;
					for(var i=0;i<message_length;i++)
					{
						$('<p class="log_para">'+log_data.inbox_data.log_details[i]+'</p>').appendTo('#'+log_id+'message');
					}
				}
				else
				{
					
					indetail_view_id=dev_id;
					indetail_view_logid=log_id;
					$('<div class="for_remove"><h2 class="email-open-header">'+log_data.inbox_data.crashed_app+'<a href="javascript:void(0);" rel="tooltip" data-placement="left" data-original-title="Print" class="txt-color-darken pull-right"><i class="fa fa-print"></i></a></h2><div class="inbox-info-bar"><div class="row"><div class="col-sm-6"><strong>'+log_data.inbox_data.device_unique_number+'</strong> to <strong>me</strong> on <i>'+log_data.inbox_data.log_received_time+'</i></span></div><div class="col-sm-6 text-right"><div class="btn-group text-left"><button id="log_forward" devi="'+dev_id+'" logi="'+log_id+'"class="btn btn-primary btn-sm" style="margin-right:5px"><i class="fa fa-mail-forward"></i> Forward </button><button id="mark_as_resolve" devi="'+dev_id+'" logi="'+log_id+'"class="btn btn-primary btn-sm"><i class="fa fa-thumbs-up"></i> Mark as resolved </button></div></div></div></div><div class="inbox-message" id="'+log_id+'message" service="'+log_id+'" userinfo="'+userdetails.email+'"><p></p><br><strong><u>Service Request Number</u></strong><br><i>'+log_id+'</i><br><br><strong><u>Crashed Time</u></strong><br><i>'+log_data.inbox_data.crashed_time+'</i><br><br><strong><u>Device Details</u></strong><br><strong>SDK - </strong>'+firmware_details.SDK+'<br><strong>Release - </strong>'+firmware_details.Release+'<br><strong>Incremental - </strong>'+firmware_details.Incremental+'<br><br><strong><u>Log Details</u></strong><br></div><div class="email-infobox"><div class="well well-sm well-light"><h5><strong><u>User Details</u></strong></h5><ul class="list-unstyled"><li class="log_para"><i class="fa fa-user fa-fw text-success"></i>'+userdetails.username+' ('+userdetails.status+')</li><li class="log_para"><i class="fa fa-envelope fa-fw text-primary"></i>'+userdetails.email+'</li><li><i class="fa fa-phone fa-fw text-warning"></i>'+userdetails.phone+'</li></ul></div><div class="well well-sm well-light"><h5><strong><u>Subscription Details</u></strong></h5><ul class="list-unstyled"><li class="log_para"><i class="fa fa-credit-card fa-fw text-primary"></i> '+userdetails.plan_subscribed+'</li><li><i class="fa fa-building fa-fw text-warning"></i> '+userdetails.subscribed_with+'</li></ul></div></div></div>').appendTo('.table-wrap');
					var message_length = log_data.inbox_data.log_details.length-1;
					for(var i=0;i<message_length;i++)
					{
						$('<p class="log_para">'+log_data.inbox_data.log_details[i]+'</p>').appendTo('#'+log_id+'message');
					}
				}
			}
			});
		}//indetail end
		
		/*
		 * Buttons (compose mail and inbox load)
		 */
		$(document).on('click',".inbox-load",function() {
			
			$('.inbox_add').empty();
			$('.for_remove').remove();
			$('#inbox-table').nextAll().remove();
			status = "new";
			$('.inbox-menu-lg').children('li').removeClass("active");
			$(this).parent('li').addClass("active");
			loadInbox(status);
		});
		
		$(document).on('click',".forwarded-load",function() {
			
		    $('.inbox_add').empty();
			$('.for_remove').remove();
			$('#inbox-table').nextAll().remove();
			status = "forwarded";
			loadInbox(status);
			$('.inbox-menu-lg').children('li').removeClass("active");
			$(this).parent('li').addClass("active");
		});
		
		$(document).on('click',".resolved-load",function() {
			
		    $('.inbox_add').empty();
			$('.for_remove').remove();
			$('#inbox-table').nextAll().remove();
			status = "resolved";
			loadInbox(status);
			$('.inbox-menu-lg').children('li').removeClass("active");
			$(this).parent('li').addClass("active");
		});
		
		$(document).on('click',".trash-load",function() {
			
		    $('.inbox_add').empty();
			$('.for_remove').remove();
			$('#inbox-table').nextAll().remove();
			status = "trash";
			loadInbox(status);
			$('.inbox-menu-lg').children('li').removeClass("active");
			$(this).parent('li').addClass("active");
		});
		
		
		
		$(document).on('click',"#mark_as_resolve",function() {
			$("#resolution").modal('show');
		})
		
		$(document).on('click',".re_solve",function() {
		
			var resolution_text = $(".resol").val();
			var service_num = $(".inbox-message").attr("service");
			var owner = $(".inbox-message").attr("userinfo");
			
			$(".resolution_form").hide();
			$(".progress_form").removeClass("hide");
		 	$.ajax({
			url:'mark_as_auto_ticket_resolved_level_1',
			type:'post',
			data:{"resolution":resolution_text,"service_number":service_num,"owner":owner},
			success:function(data)
			{
				$('#resolution').modal('hide');
				var return_status
				if(data==1)
				{
					return_status="Ticket resolved successfully"
				}
				else
				{
					return_status="Ticket resolve failed"
				}
				$.smallBox({
							title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
							content   : return_status,
							color     : "#2c699d",
							iconSmall : "fa fa-bell bounce animated",
							timeout   : 4000
				});
				window.location.reload();
			}
		    }) 
		})
		
		//forward button click
		$(document).on('click',"#log_forward",function() {
			
			$('.inbox_add').empty();
			$('.for_remove').remove();
			var dev_id = $(this).attr("devi");
			var log_id = $(this).attr("logi");
			var to_users;
			var inbox_data_in;
			var firmware;
			var log_details;
			var log_forward;
			var base_log_forward;
			$.ajax({
				url: 'pre_forward_to_second_level_admin_inbox/'+dev_id+'/'+log_id,
				type: 'POST',
				async:true,
				success: function (data) 
				{
					var for_ward = jQuery.parseJSON(data)
					to_users = for_ward.users;
					inbox_data_in = for_ward.inbox_data;
					log_details = inbox_data_in.log_details;
					firmware=JSON.stringify(inbox_data_in.device_firmware_details);
					log_forward = JSON.stringify(log_details)
					base_log_forward = window.btoa(log_forward)
					render_()
					
				}
			});
			function render_()
			{
			$('<h2 class="email-open-header">Reply to > Re:'+inbox_data_in.crashed_app+'<a href="javascript:void(0);" rel="tooltip" data-placement="left" data-original-title="Print" class="txt-color-darken pull-right"><i class="fa fa-print"></i></a></h2><form enctype="multipart/form-data" action="forward_to_second_level_admin_inbox" class="forward" method="POST" class="form-horizontal"><div class="inbox-info-bar no-padding"><div class="row"><div class="form-group"><label class="control-label col-md-1"  style="margin-top:7px;"><strong>To</strong></label><div class="col-md-11"><select multiple style="width:100%" class="select2" data-select-search="true"></select><em><a href="javascript:void(0);" class="show-next" rel="tooltip" data-placement="bottom" data-original-title="Attachments"><i class="fa fa-paperclip fa-lg"></i></a></em></div></div></div></div><div class="inbox-message no-padding"><div class="inbox-info-bar attach no-padding hide"><div class="row"><div class="form-group"><label class="control-label col-md-2"><strong>Attachments</strong></label><div class="col-md-10"><input class="form-control fileinput" type="file" name="file"></div></div></div></div><div id="email_static"><strong><u>Device Details</u></strong><br><br><strong>SDK - </strong>'+inbox_data_in.device_firmware_details.SDK+'<br><strong>Release - </strong>'+inbox_data_in.device_firmware_details.Release+'<br><strong>Incremental - </strong>'+inbox_data_in.device_firmware_details.Incremental+'<br><br><strong><u>Log Details</u></strong><br><br></div><div id="emailbody"></div></div></div><div class="inbox-compose-footer"><button class="btn btn-danger discard" type="button">Discard</button><button data-loading-text="&lt;i class="fa fa-refresh fa-spin"&gt;&lt;/i&gt; &nbsp; Sending..." class="btn btn-primary pull-right" type="button" id="send">Send<i class="fa fa-arrow-circle-right fa-lg"></i></button></div><input type="hidden" id="app" name="app" value="'+inbox_data_in.crashed_app+'" /><input type="hidden" id="level" name="level" value="'+base_log_forward+'"/><input type="hidden" id="firmware" name="firmware" value=""/><input type="hidden" id="time" name="time" value="'+inbox_data_in.crashed_time+'" /><input type="hidden" id="cid" name="cid" value="'+inbox_data_in.crash_id+'" /><input type="hidden" id="dun" name="dun" value="'+inbox_data_in.device_unique_number+'"/><input type="hidden" id="forward_users" name="users" value="" /><input type="hidden" id="level1_message" name="level1_message" value=""/></form><div class="email-infobox"><div class="well well-sm well-light"><h5>Related Invoice</h5><ul class="list-unstyled"><li><i class="fa fa-file fa-fw text-success"></i><a href="javascript:void(0);"> #4831 - Paid</a></li><li><i class="fa fa-file fa-fw text-danger"></i><a href="javascript:void(0);"><strong> #4835 - Unpaid</strong></a></li></ul></div><div class="well well-sm well-light"><h5>Upcoming Meetings</h5><p><span class="label label-success"><i class="fa fa-check"></i> <strike>Agenda Review @ 10 AM</strike> </span></p><p><span class="label label-primary"><i class="fa fa-clock-o"></i> Client Meeting @ 2:30 PM</span></p><p><span class="label label-warning"><i class="fa fa-clock-o"></i> Salary Review @ 4:00 PM</span></p></div><ul class="list-inline"><li><img src="<?php echo ASSETS_URL; ?>/img/avatars/5.png" alt="me" width="30px"></li><li><img src="<?php echo ASSETS_URL; ?>/img/avatars/3.png" alt="me" width="30px"></li><li><img src="<?php echo ASSETS_URL; ?>/img/avatars/sunny.png" alt="me" width="30px"></li><li><a href="javascript:void(0);">1 more</a></li></ul></div>').appendTo('.table-wrap');
			var log_details_length = log_details.length;
			for(var i=0;i<log_details_length;i++)
			{
				$('<p>'+log_details[i]+'</p>').appendTo('#email_static');
			}
			$('#firmware').val(firmware);
			iniEmailBody();
			for(i=0;i<to_users.length;i++)
			{
			$('<option value="'+to_users[i]+'">'+to_users[i]+'</option>').appendTo('.select2')
			}
			if ($.fn.select2) {
				$('.select2').each(function() {
				var $this = $(this);
				var width = $this.attr('data-select-width') || '100%';
				
					$this.select2({
						allowClear : true,
						width : width
					})
				})
			}
			$("#send").click(function(){
				var sel_usr = $(".select2").select2('val');
				if(sel_usr!='')
				{
					var fin=JSON.stringify(sel_usr);
					$('#forward_users').val(fin);
					var level_messg = $('#emailbody').code();
					$('#level1_message').val(level_messg)
					$('.forward').submit();
				}
				else
				{
					$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Warning",
				content : "Please select the user",
				color : "#C46A69",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
				}
			});//send end
			}//render end
			});
			
	function iniEmailBody() {
		$('#emailbody').summernote({
			height : 150,
			focus : true,
			tabsize : 2
		});
	}
	$(document).on("click",".show-next",function()
	{
		if($('.attach').hasClass("hide"))
		{
			$('.attach').removeClass("hide");
		}
		else
		{
			$('.attach').addClass("hide");
		}
	});

	$(document).on("click",".discard",function()
	{	
		$('#inbox-table').nextAll().remove();
		status = "new";
		loadInbox(status);
	});

	$(document).on('change','#delcheckbox',function(e) {
		 e.stopPropagation()
		
		if($(this).is(':checked')==true)
		{
			$(this).parents('tr').addClass("del")
		}
		else
		{
			$(this).parents('tr').removeClass("del")
		}
    })
	
	$(document).on("click",".deletebutton",function(e)
	{	
		var del_list=[];
		var func_call;

		if($('.table-wrap').children('div').hasClass('for_remove'))
		{
			indetail_view_id
			indetail_view_logid
			if($('.inbox-menu-lg').children('.active').hasClass("trash"))
			{
				func_call = "delete_entry_level_1";
			}
			else
			{
				func_call = "mark_as_trash_level_1";
			}
			del_list.push(
					{"device_id":indetail_view_id,
					"log_id":indetail_view_logid})
			$.ajax({
				url: ''+func_call+'',
				data:{'del_data':del_list},
				type: 'POST',
				async:true,
				success: function () 
				{
					loadInbox(status);
				}
			}); 
			indetail_view_id='';
			indetail_view_logid='';
		}
		else
		{
			if($('.inbox-menu-lg').children('.active').hasClass("trash"))
			{
				func_call = "delete_entry_level_1";
			}
			else
			{
				func_call = "mark_as_trash_level_1";
			}
			$('.inbox_add').children("tr").each(function ()
			{	
				if($(this).hasClass("del"))
				{
					var dev_id = $(this).attr('id');
					var log_id = $(this).attr('log');
					console.log(dev_id,log_id)
					del_list.push(
					{"device_id":dev_id,
					"log_id":log_id})
				}
		
				$.ajax({
				url: ''+func_call+'',
				data:{'del_data':del_list},
				type: 'POST',
				async:true,
				success: function () 
				{
					var len = del_list.length;
					for(var i=0;i<len;i++)
					{
						$('tr[log='+del_list[i].log_id+']').remove();
					}
				}
				}); 
			})
		}
		//
	});
	
})//document end