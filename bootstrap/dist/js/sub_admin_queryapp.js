
var page_number=1;
var total_page =null;
var page_number_feedback=1;
var total_page_feedback =null;
var page_number_analytics=1;
var total_page_analytics =null;
var sr =0;
var sr_no =0;
var dataid = '';
var app_name = '';

var getReport_feedback = function(page_number_feedback)
{
	//console.log("report")
if(page_number_feedback==1)
{
$("#previous_feedback").prop('disabled', true);
}
else
{
$("#previous_feedback").prop('disabled', false);
}

if(page_number_feedback==(total_page_feedback) || total_page_feedback == null)
{
$("#next_feedback").prop('disabled', true);
}
else
{
$("#next_feedback").prop('disabled', false);
}

$("#page_number_feedback").text(page_number_feedback);

$.ajax({
	url:"pagination_feedbacks",
	type:"POST",
	dataType: 'json',
	data:'page_number='+page_number_feedback,
	success:function(data)
	{
		//console.log("44444444444444444444");
		//console.log(data);
		
		window.mydata = data;
		total_page_feedback= mydata[0].TotalRows;
		$("#total_page_feedback").text(total_page_feedback);
		var record_par_page = mydata[0].Rows;
		$('.feedbacks').empty();
		$.each(record_par_page, function (key, data) {
		//console.log("44444444444444444444");
		//console.log(data);
		var print_obj = JSON.stringify(data);
		var print = window.btoa(print_obj);
		$('<tr><td><a href="feedback_properties/'+data._id.$id+'">'+data.feedback_name+'</a></td><td>'+data.users.length+'</td><td>'+data.user_filled_forms_count+'</td><td>'+data.description+'</td><td class="text-align-center"><div id="sparkline_feed'+sr_no+'" class="sparkline display-inline" data-sparkline-type="pie" data-sparkline-piecolor="" data-sparkline-offset="90" data-sparkline-piesize="23px"></div><div class="btn-group display-inline pull-right text-align-left hidden-tablet"><button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog fa-lg"></i></button><ul class="dropdown-menu dropdown-menu-xs pull-right"><li><a href="javascript:void(0);" class="print_feedback" print='+print+'><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i> <u>P</u>DF</a></li><li class="divider"></li><li class="text-align-center"><a href="javascript:void(0);">Cancel</a></li></ul></div></td></tr>').appendTo('.feedbacks');
		
		var pieColors = ["#E979BB", "#57889C"] || ["#B4CAD3", "#4490B1", "#98AA56", "#da532c", "#6E9461", "#0099c6", "#990099", "#717D8A"], pieWidthHeight = 23 || 90, pieBorderColor = $this.data('border-color') || '#45494C', pieOffset = 90 || 0;
		
			$('#sparkline_feed'+sr_no+'').sparkline([data.user_filled_forms_count,data.users.length], {
				type : 'pie',
				width : pieWidthHeight,
				height : pieWidthHeight,
				tooltipFormat : '<span style="color: {{color}}">&#9679;</span> ({{percent.1}}%)',
				sliceColors : pieColors,
				offset : 0,
				borderWidth : 1,
				offset : pieOffset,
				borderColor : pieBorderColor
			});
		sr_no = sr_no+1;
		});
		if(page_number_feedback==(total_page_feedback))
		{
		$("#next_feedback").prop('disabled', true);
		}
		else
		{
		$("#next_feedback").prop('disabled', false);
		}
	}
	//'+data.user_filled_forms_count+','+data..users.length+'["#E979BB", "#57889C"]s
});
};

var getReport = function(page_number)
{
	//console.log("report")
if(page_number==1)
{
$("#previous").prop('disabled', true);
}
else
{
$("#previous").prop('disabled', false);
}
if(page_number == total_page || total_page == null)
{

$("#next").prop('disabled', true);
}
else
{
		//console.log("rrrrrr")
$("#next").prop('disabled', false);
}



$("#page_number").text(page_number);

$.ajax({
	url:"pagination_events",
	type:"POST",
	dataType: 'json',
	data:'page_number='+page_number,
	success:function(data)
	{
		//console.log(data);
		window.mydata = data;
		total_page= mydata[0].TotalRows;
		$("#total_page").text(total_page);
		var record_par_page = mydata[0].Rows;
		//console.log(record_par_page)
		$(".events_ajax").empty();
		$.each(record_par_page, function (key, data) {
		//console.log(data.description);
		var print_obj = JSON.stringify(data);
		var print = window.btoa(print_obj);
		$('<tr><td><a href="event_properties/'+data._id.$id+'">'+data.title+'</a></td><td>'+data.users.length+'</td><td>'+data.confirmed_users+'</td><td>'+data.start+'</td><td class="text-align-center"><div id="sparkline'+sr+'" class="sparkline display-inline" data-sparkline-type="pie" data-sparkline-piecolor=\'["#E979BB","#57889C","#00FF04"]\' data-sparkline-offset="90" data-sparkline-piesize="23px"></div><div class="btn-group display-inline pull-right text-align-left hidden-tablet"><button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog fa-lg"></i></button><ul class="dropdown-menu dropdown-menu-xs pull-right"><li><a href="javascript:void(0);" class="print_event" print='+print+'><i class="fa fa-file fa-lg fa-fw txt-color-greenLight" ></i> <u>P</u>DF</a></li><li class="divider"></li><li class="text-align-center"><a href="javascript:void(0);">Cancel</a></li></ul></div></td></tr>').appendTo('.events_ajax')
			var pieColors = ["#E979BB","#57889C","#00FF04"] || ["#B4CAD3", "#4490B1", "#98AA56", "#da532c", "#6E9461", "#0099c6", "#990099", "#717D8A"], pieWidthHeight = 23 || 90, pieBorderColor = $this.data('border-color') || '#45494C', pieOffset = 90 || 0;
		
			$('#sparkline'+sr+'').sparkline([data.confirmed_users,data.noreply_users,data.declinded_users], {
				type : 'pie',
				width : pieWidthHeight,
				height : pieWidthHeight,
				tooltipFormat : '<span style="color: {{color}}">&#9679;</span> ({{percent.1}}%)',
				sliceColors : pieColors,
				offset : 0,
				borderWidth : 1,
				offset : pieOffset,
				borderColor : pieBorderColor
			});
			sr=sr+1;
		});
			if(page_number == total_page)
			{

			$("#next").prop('disabled', true);
			}
			else
			{
			$("#next").prop('disabled', false);
			}
	}
	
	//'+data.confirmed_users+','+data.noreply_users+','+data.declinded_users+'
});
}
//-------analytics-----------------------------------------------------------------
var getReport_analytics = function(page_number_analytics)
{
	//console.log("report")
if(page_number_analytics==1)
{
$("#previous_analytics").prop('disabled', true);
}
else
{
$("#previous_analytics").prop('disabled', false);
}

if(page_number_analytics==total_page_analytics || total_page_analytics == null)
{
$("#next_analytics").prop('disabled', true);
}
else
{
$("#next_analytics").prop('disabled', false);
}

$("#page_number_analytics").text(page_number_analytics);

$.ajax({
	url:"pagination_analytics",
	type:"POST",
	dataType: 'json',
	data:'page_number='+page_number_analytics,
	success:function(data)
	{
		//console.log("44444444444444444444");
		//console.log(data);
		
		window.mydata = data;
		total_page_analytics= mydata[0].TotalRows;
		$("#total_page_analytics").text(total_page_analytics);
		var record_par_page = mydata[0].Rows;
		$('.analytics').empty();
		$.each(record_par_page, function (key, data) {
		//console.log("btn btn-warning btn-xs analytics");
		//console.log(data);
		var print_obj = JSON.stringify(data);
		var print = window.btoa(print_obj);
		$('<tr><td>'+data.app_name+'</td><td><button class="btn btn-warning btn-xs analytics_btn" id = '+data._id+'>App Analytics</button></td></tr>').appendTo('.analytics');
		
		});
		if(page_number_analytics==(total_page_analytics))
		{
		$("#next_analytics").prop('disabled', true);
		}
		else
		{
		$("#next_analytics").prop('disabled', false);
		}
	}
	//'+data.user_filled_forms_count+','+data..users.length+'["#E979BB", "#57889C"]s
});
};

$(document).on('click','.analytics_btn',function()
	{
	var query_app_id = $(this).attr('id');
	$('.analytics_table').hide();
	$('.analytics_row').hide();
	$.ajax({
		url: 'query',
		type: 'POST',
		dataType:"json",
		data: {"query_app_id" : query_app_id},
		success: function (data) 
		{
			
			var table="";
			 if(data)
			 {
			   var docs = JSON.parse(data);
			 }
			 app_name = docs.app_name;
			 docs = docs.app_template;
				for( var i in docs )
				{
					single = docs[i];
					for(var j in single)
					{
						var size = Object.keys(single[j]).length;
						var si = 1;
										
						if(Object.keys(single[j])!="dont_use_this_name")
			            {
			                var ke = Object.keys(single[j]);
			                for(var p=0;p<size;p++)
			                {
			                    if(ke[p]!="dont_use_this_name")
			                    {
			                             			
			                    }
			                }
			                             	
							for(var s in single[j])
						    {
							   if(s != 'dont_use_this_name')
							   {
									element=single[j][s];
									if(si != size)
									{
										if(element.type=="text" || element.type=="number")
										{
											if(element.key=="TRUE")
											{
												s_for_id = btoa(i+"."+j+"."+s);
											     table=table+"<tr><td><label search_val="+s_for_id+">"+s+"</label></td><td><input class="+s_for_id+" id='sear' type=''/><td><select class='andor' id="+s_for_id+"><option value='OR'>OR</option><option value='AND'>AND</option></select></td></tr>";
										    }
									    }
									}
									else
									{
										if(element.type=="text")
										{
										         table=table+"<tr><td><label search_val="+s_for_id+">"+s+"</label></td><td><input class="+s_for_id+" id='sear' type=''/></tr>";
										}
									}
									si++;

							    }
			                 }
						}
				     }
				}
								
				$('#analytics_query').html('<table class="table table-bordered"><tbody class="analytics_tbody">'+table+'</tbody></table><div id="analyticsbtn"><button class="btn btn-default" id="searchquery" style="float: right;margin-right: 20px;margin-bottom: 10px;">Query</button></div>');

				dataid = query_app_id;
			
				
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
				console.log('error', errorThrown);
			}
		});
		//$('#query_modal').modal("show");	
	});

function save_pattern_query()
{
	var dataname=app_name;
	//console.log("aaaaaaaaaaaa",dataname);
	var labe_larray=[];
	var arra_strng='';
	$('.analytics_tbody').children('tr').each(function()
	{
		var labeltext = $(this).find('label').attr('search_val');
		var queryvalue= $(this).find('input').val();
		var opt=$(this).find('select option:selected').val();	
		if(opt != 0)
		{
			labe_larray.push({labelname:labeltext,value:queryvalue,option:opt});
		}
		else
		{
			labe_larray.push({labelname:labeltext,value:queryvalue});
		}									
	});
		
	arra_strng=JSON.stringify(labe_larray);
	//console.log(arra_strng);
	//console.log(dataid);
	$('#save_query').val(arra_strng);
	$('#app_id').val(dataid);
	$('#app_name').val(dataname);
	$('#pattern').show();
}

$(document).on('click','#searchquery',function()
{
	$('#analytics_query').hide();
	save_pattern_query();
	labelarray=[];
	strng='';
	
	$('.analytics_tbody').children('tr').each(function()
	{
		//$(this).children('td').each(function()
		//{
		var labeltext = $(this).find('label').attr('search_val');
		var queryvalue= $(this).find('input').val();
		//$(this).find('option'+ 'option:selected').val();
		//var opt=$(this).find('#'+labeltext+' option:selected').val();
		var opt=$(this).find('select option:selected').val();
		//console.log(labeltext);	console.log(queryvalue);	console.log(opt);	
		if(opt != 0)
		{
			labelarray.push({labelname:labeltext,value:queryvalue,option:opt});
		}
		else
		{
			labelarray.push({labelname:labeltext,value:queryvalue});
		}
		//})
													
	})
		
	strng=JSON.stringify(labelarray);
	//console.log(strng);				
	$.ajax({
		url: 'searching',
		type: 'POST',
		dataType:"json",
		data: {"strng" : strng,"dataid" : dataid},
		success: function (data) 
		{
			//console.log(data);		
						
			$('#analytics').hide();
		    $('#analyticsbtn').hide();
			$('#queryres').show();
			$('#queryres1').show();
			$('#appmicrochart').show();
			
			var total_docs = data.count;
			var matched_docs = data.docs[0].length;
			//console.log(total_docs);
			//console.log(matched_docs);
			var match_percent = 100;
			if(total_docs != 0){
				var match_percent = (matched_docs/total_docs)*100;
			}
			
			//console.log(match_percent);
			
			//------------------pie chart-----------------------------------------------------------
			
		var pie_chart = '<div class="row"><div class="col-xs-8 col-sm-2 col-md-2 col-lg-1"><div class="chart" style="height:110px;"><div class="percentage" data-percent="'+match_percent+'"><span>'+match_percent+'</span><sup>%</sup></div><div class="label label-warning">Documents Matched</div></div></div><div class="col-xs-8 col-sm-2 col-md-2 col-lg-2"><ul style="margin-top:50px;"><li>Matched documents &nbsp;('+matched_docs+')</li><li>Total documents &nbsp;('+total_docs+')</li></ul></div></div>'

		//----------------------table-------------------------------------------------------------------------
		var table="";
		for(var doc in data.docs[0]){
			for(var doc_section in data.docs[0][doc]['doc_data']['widget_data']['page1']){
				for(var name_value in data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section]){
					//console.log(name_value);
					if(typeof(data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value])=='string' || typeof(data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value])=='string'){
						table=table+"<tr><td><label search_val="+name_value+">"+name_value+"</label></td><td><label search_val="+data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value]+">"+data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value]+"</label></td></tr>";
					}
				}
				
				table = table+'<tr><td></td><td></td></tr>';
			}
		}
		var table_data = '<div class="row"><div class="col-xs-12 col-sm-2 col-md-2 col-lg-12"><table class="table table-bordered" style="margin-top:15px;"><tbody class="analytics_tbody">'+table+'</tbody></table></div></div>'
		
		$('#queryres1').html(pie_chart);
		$('#queryres1').append(table_data);
		
		$('.percentage').easyPieChart({
		  animate: 1000,
		  lineWidth: 8,
		  barColor: '#398E72',
		  lineCap: 'square',
		  scaleColor:false,
		  onStep: function(value) {
		    this.$el.find('span').text(Math.round(match_percent));
		  },
		  onStop: function(value, to) {
		    this.$el.find('span').text(Math.round(match_percent));
		  }
		});				
					
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
							 // console.log('error', errorThrown);
		}
	});
});

$(document).on('click','.query_sub_admin',function()
{
	$('#queryres').hide();
	$('#appmicrochart').hide();
	$('#analytics').show();
	$('#pattern').hide();
	$('#analyticsbtn').show();
	
	var query_app_id = $(this).attr('id');
	var query_pattern = $(this).attr('pattern');
	query_pattern=atob(query_pattern);
	$.ajax({
		url: 'searching',
		type: 'POST',
		dataType:"json",
		data: {"strng" : query_pattern,"dataid" : query_app_id},
		success: function (data) 
		{
			//console.log(data);
			
			$('#analytics').hide();
			$('.analytics_table').hide();
			$('.analytics_row').hide();
		    $('#analyticsbtn').hide();
			$('#queryres').show();
			$('#queryres1').show();
			$('#appmicrochart').show();
			
			var total_docs = data.count;
			var matched_docs = data.docs[0].length;
			//console.log(total_docs);
			//console.log(matched_docs);
			var match_percent = 100;
			if(total_docs != 0){
				var match_percent = (matched_docs/total_docs)*100;
			}
			
			//console.log(match_percent);
			
			//------------------pie chart-----------------------------------------------------------
			
			var pie_chart = '<div class="row"><div class="col-xs-8 col-sm-2 col-md-2 col-lg-1"><div class="chart" style="height:110px;"><div class="percentage" data-percent="'+match_percent+'"><span>'+match_percent+'</span><sup>%</sup></div><div class="label label-warning">Documents Matched</div></div></div><div class="col-xs-8 col-sm-2 col-md-2 col-lg-2"><ul style="margin-top:50px;"><li>Matched documents &nbsp;('+matched_docs+')</li><li>Total documents &nbsp;('+total_docs+')</li></ul></div></div>'
			
			//----------------------table-------------------------------------------------------------------------
			var table="";
			for(var doc in data.docs[0]){
				for(var doc_section in data.docs[0][doc]['doc_data']['widget_data']['page1']){
					for(var name_value in data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section]){
						//console.log(name_value);
						if(typeof(data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value])=='string' || typeof(data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value])=='string'){
							table=table+"<tr><td><label search_val="+name_value+">"+name_value+"</label></td><td><label search_val="+data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value]+">"+data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value]+"</label></td></tr>";
						}
					}
					
					table = table+'<tr><td></td><td></td></tr>';
				}
			}
			var table_data = '<div class="row"><div class="col-xs-12 col-sm-2 col-md-2 col-lg-12"><table class="table table-bordered" style="margin-top:15px;"><tbody class="analytics_tbody">'+table+'</tbody></table></div></div>'
			
			$('#queryres1').html(pie_chart);
			$('#queryres1').append(table_data);
			
			$('.percentage').easyPieChart({
				  animate: 1000,
				  lineWidth: 8,
				  barColor: '#398E72',
				  lineCap: 'square',
				  scaleColor:false,
				  onStep: function(value) {
				    this.$el.find('span').text(Math.round(match_percent));
				  },
				  onStop: function(value, to) {
				    this.$el.find('span').text(Math.round(match_percent));
				  }
				});	
			
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
			console.log('error', errorThrown);
		}
	});
	//$('#query_modal').modal("show");	
});

//--------------item based analyticsssssssssssssssss------------------------------------------
$(document).on('click','#itemanalytics',function()
{
	var queryvalue= $('#tiem_search-fld').val();
	alert(queryvalue);
	$.ajax({
		url: 'item_analytics',
		type: 'POST',
		dataType:"json",
		data: {"value" : queryvalue},
		success: function (data) 
		{
			
			var total_docs = data.count;
			var matched_docs = data.docs[0].length;
			//console.log(total_docs);
			//console.log(matched_docs);
			var match_percent = 100;
			if(total_docs != 0){
				var match_percent = (matched_docs/total_docs)*100;
			}
			
			//console.log(match_percent);
			
			//------------------pie chart-----------------------------------------------------------
			
		var pie_chart = '<div class="row"><div class="col-xs-8 col-sm-2 col-md-2 col-lg-1"><div class="chart" style="height:110px;"><div class="percentage" data-percent="'+match_percent+'"><span>'+match_percent+'</span><sup>%</sup></div><div class="label label-warning">Documents Matched</div></div></div><div class="col-xs-8 col-sm-2 col-md-2 col-lg-2"><ul style="margin-top:50px;"><li>Matched documents &nbsp;('+matched_docs+')</li><li>Total documents &nbsp;('+total_docs+')</li></ul></div></div>'

		//----------------------table-------------------------------------------------------------------------
		var table="";
		for(var doc in data.docs[0]){
			for(var doc_section in data.docs[0][doc]['doc_data']['widget_data']['page1']){
				for(var name_value in data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section]){
					//console.log(name_value);
					if(typeof(data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value])=='string' || typeof(data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value])=='string'){
						table=table+"<tr><td><label search_val="+name_value+">"+name_value+"</label></td><td><label search_val="+data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value]+">"+data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value]+"</label></td></tr>";
					}
				}
				
				table = table+'<tr><td></td><td></td></tr>';
			}
		}
		var table_data = '<div class="row"><div class="col-xs-12 col-sm-2 col-md-2 col-lg-12"><table class="table table-bordered" style="margin-top:15px;"><tbody class="analytics_tbody">'+table+'</tbody></table></div></div>'
		
		$('#queryres1').html(pie_chart);
		$('#queryres1').append(table_data);
		
		$('.percentage').easyPieChart({
		  animate: 1000,
		  lineWidth: 8,
		  barColor: '#398E72',
		  lineCap: 'square',
		  scaleColor:false,
		  onStep: function(value) {
		    this.$el.find('span').text(Math.round(match_percent));
		  },
		  onStop: function(value, to) {
		    this.$el.find('span').text(Math.round(match_percent));
		  }
		});				
					
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
							 // console.log('error', errorThrown);
		}
	});
});