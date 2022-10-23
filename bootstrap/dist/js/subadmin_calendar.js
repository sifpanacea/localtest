$(document).ready(function() {
	


var usersss = [];
var userss = [];
var users=[];
var groupname=[];
var grup=[];
var grupss=[];
var groupnamess=new Array();
var grouplist={};
	
	$.ajax({
		url: 'sub_admin_calendar/get_group_list',
		type: 'POST',
		async:false,
		dataType:"json",
		success: function (data) {
			groupnamess=data;
			console.log("groupnameeeee",groupnamess);
		for(var i in groupnamess)
		{
		 grup[i]=groupnamess[i];
		 userss.push({
				label:groupnamess[i],
				value:groupnamess[i]
			  });
			  var current_name = groupnamess[i]
			  current_name = current_name.replace(/\s+/g, '');
			  console.log(current_name)
		$('<optgroup label="'+groupnamess[i]+'" id="'+current_name+'">').appendTo('#users')
		grupss.push(groupnamess[i]);
		}
		for(var i in grupss)

		{
			 $.ajax({
				url: 'sub_admin_calendar/get_user_list',
				type: 'POST',
				async:false,
				dataType:"json",
				data:{'name':grupss[i]},	
				success: function (data) 
				{
					console.log(data);
					
					if(data!='[]')
					{
						var group= grupss[i];
						var current_grp = group.replace(/\s+/g, '');
						grouplist[group]=new Array();
						var usrs=data;
						for(var j in usrs)
						{
							grouplist[group].push(usrs[j]);
						$('<option value="'+usrs[j]+'">'+usrs[j]+'</option>').appendTo('#'+current_grp+'');
							
						}
						
					}
				},
				error:function(XMLHttpRequest, textStatus, errorThrown)
				{
					console.log('error', errorThrown);
				}
			});	
			
		}	

		users=userss;
		
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
		}
	});

	$("#users").multiselect({
			nonSelectedText: 'Select Users',
			enableClickableOptGroups:true,
			includeSelectAllOption: true,
			enableFiltering: true,
			enableCaseInsensitiveFiltering: true,
			maxHeight: 200,
			buttonWidth: '100%'
        });
	
				 $.ajax({
				 url:  'sub_admin/get_sub_admin_events',
				 type: "POST",
				 async:true,
				 success: function(data) 
				 {
							
							var events_data = JSON.parse(data);
							for(var i=0;i<events_data.length;i++)
							{
								var name_app = events_data[i].event_name
								var id = events_data[i].id
								$('<option value="'+id+'">'+name_app+'</option>').appendTo("#event_forms");
								
							}
							event_();
					}
					})
		function event_()
		{
		$("#event_forms").multiselect({
			nonSelectedText: 'Select Event Form',
			enableFiltering: true,
			enableCaseInsensitiveFiltering: true,
			maxHeight: 200,
			buttonWidth: '100%'
        });
		var selected = $('#selected_event').attr('sel_eve');
		
			if(selected!=='')
			{
				$('#event_forms').multiselect('select',selected);
			}
		}
		
		$('.timepicker').timepicker({
                minuteStep: 1,
                appendWidgetTo: 'body',
                showSeconds: true,
                showMeridian: false,
                defaultTime: 'current'
            });
		$('#event_time_edit').timepicker({
                minuteStep: 1,
				//template:'modal',
                //appendWidgetTo: 'body',
                showSeconds: true,
                showMeridian: false,
            });
		$('#event_end_time_edit').timepicker({
                minuteStep: 1,
				//template:'modal',
                //appendWidgetTo: 'body',
                showSeconds: true,
                showMeridian: false,
            });			
});