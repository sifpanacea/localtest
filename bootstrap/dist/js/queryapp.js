$(document).ready(function() {
$('#pattern').hide();
$('.loading').hide();
$('#queryres').hide();
$('#queryres1').hide();
$('#appmicrochart').hide();
$('#analytics').show();
$('#analyticsbtn').show();
var height_name ='';
var weight_name ='';
var school_list = [];
var sector_name = '';
var sector_value = '';
var dob='';
var dateof_exam='';
var $btn
var pattern_to_save = '';
/* chart colors default */
	var $chrt_border_color = "#efefef";
	var $chrt_grid_color = "#DDD"
	var $chrt_main = "#E24913";
	/* red       */
	var $chrt_second = "#6595b4";
	/* blue      */
	var $chrt_third = "#FF9F01";
	/* orange    */
	var $chrt_fourth = "#7e9d3a";
	/* green     */
	var $chrt_fifth = "#BD362F";
	/* dark red  */
	var $chrt_mono = "#000";

function drawing_graph(opt_type,res_data)
{
	    $btn.button('reset')
		$('#analytics').hide();
		$('#analyticsbtn').hide();
		$('#pattern').show();
		$('#queryres').show();
		$('#queryres1').show();
		$('#appmicrochart').show();
		
		console.log("opt_type",opt_type);
		
	 if(opt_type == "pie")
	 {
		var total_docs = res_data.docs.sector;
		var matched_docs = 0;
		matched_docs = res_data.docs;
		var length = res_data.docs.query.length || 0;
		var data_val = [];
		var seleceted_color = [];
		var newColor = '';
		var res_data_docs = res_data.docs.query
		for(var j=0;j< length; j++)
		{
			for(var newclr=0; newclr<10;newclr++)
				{
					newColor = '#'+(0x1000000+(Math.random())*0xffffff).toString(16).substr(1,6);
					
					if($.inArray(newColor,seleceted_color) < 0 )
					{
						seleceted_color.push(newColor)
						break
					}
				}
				
				var per = (res_data_docs[j]['value']/total_docs)*100;
				per = Math.round(per)
				
				var ticks_new = res_data_docs[j]['field'].substring(res_data_docs[j]['field'].lastIndexOf('.')+1);
				data_val.push({
					value: per,
					realcount :res_data_docs[j]['value'],
					color: newColor,
					highlight: "#FF5A5E",
					label: ticks_new
				})
				if(length == 1)
				{
					newColor = '#'+(0x1000000+(Math.random())*0xffffff).toString(16).substr(1,6);					
					var total= 100;
					total = total - per;
					//console.log(total)
					data_val.push({
						value: total,
						workaround:true,
						color: newColor,
						highlight: "#FF5A5E",
						label: total//ticks_new
					})
				}
		}
		
		
		var pie_chart = '<div class="row"><div class="col-xs-8 col-sm-2 col-md-2 col-lg-4 labelinfo"><canvas id="canvas" class="percentage" height="250" width="250"></canvas></div><div id="legend" class="col-lg-6"></div></div>'
		$('#queryres1').html(pie_chart);
		$('<div><h3>Sector : '+sector_name+'</h3><h3>'+sector_value+':'+total_docs+'</h3></div>').appendTo('.labelinfo');
		 var myPie = new Chart(document.getElementById("canvas").getContext("2d")).Pie(data_val, { 		               
        animationSteps: 100,
 		animationEasing: 'easeInOutQuart'	});
		//legend(document.getElementById("legend"), data_val, myPie);		
		document.getElementById('legend').innerHTML = myPie.generateLegend();
		
		//------------------pie chart-----------------------------------------------------------
		
	//var pie_chart = '<div class="row"><div class="col-xs-8 col-sm-2 col-md-2 col-lg-1"><div class="chart" style="height:310px;"><canvas id="canvas" class="percentage"></canvas><div class="label label-warning">Documents Matched</div></div></div><div class="col-xs-8 col-sm-2 col-md-2 col-lg-2"><ul style="margin-top:50px;"><li>Matched documents &nbsp;('+matched_docs+')</li><li>Total documents &nbsp;('+total_docs+')</li></ul></div></div>'
	
	//<canvas class="percentage" data-percent="'+match_percent+'"><b><span>'+match_percent+'</span></b>%</canvas>						
	//----------------------table-------------------------------------------------------------------------
	/* var table="";
	for(var doc in res_data.docs[0]){
		for(var doc_section in res_data.docs[0][doc]['doc_data']['widget_data']['page1']){
			for(var name_value in res_data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section]){
				
				if(typeof(res_data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value])=='string' || typeof(res_data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value])=='string'){
					table=table+"<tr><td><label search_val="+name_value+">"+name_value+"</label></td><td><label search_val="+res_data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value]+">"+res_data.docs[0][doc]['doc_data']['widget_data']['page1'][doc_section][name_value]+"</label></td></tr>";
				}
			}
			
			table = table+'<tr><td></td><td></td></tr>';
		}
	} */
	//var table_data = '<div class="row"><div class="col-xs-12 col-sm-2 col-md-2 col-lg-12"><table class="table table-bordered" style="margin-top:15px;"><tbody class="analytics_tbody">'+table+'</tbody></table></div></div>'
	
	//$('#queryres1').html(pie_chart);
	//$('#queryres1').html(table_data);
	//$('#queryres1').append(table_data);
	
	 /* var pieData = [{
                value : matched_docs,
                color : "#F38630",
                label : 'Matched Docs',
                labelColor : 'white',
                labelFontSize : '16'
            },
                  {
                value : total_docs,
                color : "#F34353",
                label : 'Total Docs',
                labelColor : 'white',
                labelFontSize : '16'
            }];

    var myPie = new Chart(document.getElementById("canvas").getContext("2d")).Pie(pieData, { 		               
        animationSteps: 100,
 		animationEasing: 'easeInOutQuart'	}); */
/* }); */
	
	/* $('.percentage').easyPieChart({
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
	});	 */

    }
    else if(opt_type == "bar")
	{

        var total_docs = res_data.count;
		var matched_docs = res_data.docs//.length;
		
		var x_ticks = [];
		
        /* bar chart */

		if ($("#queryres1").length) {

		     //console.log("matched_docs================",matched_docs);
		     
		     var doc_len = matched_docs.length;
			 //need to check the undefined for matched_docs.length
			 //console.log("doc_len================",doc_len);
			 var ds_sample = [];
			 var ds_       = [];

			var total_inner_values  = 0;
			//need to check the undefined for matched_docs[0]['values'].length
			var check_data_len = matched_docs[0]['values'].length;
			//console.log(check_data_len)
			for(var ij=1;ij<=check_data_len;ij++)
			{
				ds_sample['data'+ij+''] = [];
				total_inner_values = ij
			}			
			for(var ik=1;ik<=total_inner_values;ik++)
			{
				for(var il=0;il<doc_len;il++)
				{
					//push the data
					ds_sample['data'+ik+''].push([il,matched_docs[il]['values'][ik-1]])
					if(ik<2)
					{
					   x_ticks.push([il,matched_docs[il]['xaxis']]);
					}
				}
				ds_.push({
			    data : ds_sample['data'+ik+''],
				bars : {
					show : true,
					barWidth :0.1,
					order : ik,
					numbers: 
						{
							show : true,
							yAlign: function(y) { return y + 2; },
							
						},
					},
				label: matched_docs[ik-1]['labels'][ik-1]
				});
			}			 
			//Display graph
			$.plot($("#queryres1"), ds_, {
				colors : [$chrt_second, $chrt_fourth, "#666", "#BBB"],
				grid : {
					show : true,
					hoverable : true,
					clickable : true,
					tickColor : $chrt_border_color,
					borderWidth : 0,
					borderColor : $chrt_border_color,
				},
				legend: {
                noRows: total_inner_values,
                labelBoxBorderColor: "#000000",
				container: $("#legend-container")
				},
				yaxis : {
		
					axisLabel: "No. Of. Students",
					axisLabelUseCanvas: true,
                    axisLabelFontFamily: 'Verdana, Arial',
               },
				xaxis : {
					axisLabel: "Schools",// get from xaxis input
					axisLabelUseCanvas: true,
                    ticks:x_ticks
				}

			});
			function showTooltip(x, y, contents, z) {
				$('<div id="flot-tooltip">' + contents + '</div>').css({
					top: y - 20,
					left: x - 50,
					'border-color': z,
				}).appendTo("body").show();
			}
			  $("#queryres1").bind("plothover", function (event, pos, item) {
				console.log("item",item)
		        if (item) {
		            if (previousPoint != item.datapoint) {
		                previousPoint = item.datapoint;
		                $("#flot-tooltip").remove();
		 
		                var originalPoint;
		                 
		                if (item.datapoint[0] == item.series.data[0][3]) {
							console.log("1")
		                    originalPoint = item.series.data[0][0];
		                } else if (item.datapoint[0] == item.series.data[1][3]){
			console.log("2")
		                    originalPoint = item.series.data[1][0];
		                } else if (item.datapoint[0] == item.series.data[2][3]){
			console.log("3")
		                    originalPoint = item.series.data[2][0];
		                } else if (item.datapoint[0] == item.series.data[3][3]){
			console.log("4")
		                    originalPoint = item.series.data[3][0];
		                } /* else if (typeof item.series.data[4][3] !== "undefined")
						{
							if (item.datapoint[0] == item.series.data[4][3])
							{
								console.log("5")
								originalPoint = item.series.data[4][0];
			                }
						} */
						else
						{
							originalPoint = item.datapoint[0];
						}
		                var x = originalPoint;
		                y = item.datapoint[1];
		                z = item.series.color;
		 
		                showTooltip(item.pageX, item.pageY,
		                    "<b>" + item.series.label + "</b><br /> " + y + "",
		                    z);
		            }
		        } else {
		            $("#flot-tooltip").remove();
		            previousPoint = null;
		        }
		    });

		}

		/* end bar chart */
	}
	else if(opt_type == "bmi")
	{
		var total_docs = res_data.count;
		var matched_docs = res_data.docs;
		
		var x_ticks = [[0, "Under Weight"], [1, "Normal"], [2, "Over Weight"], [3, "Obesity"],/*  [4, "Not Calculated"] */];
		
        /* bar chart */

		if ($("#queryres1").length) {
			 //need to check the undefined for matched_docs.length
			 var ds_sample = [];
	
				ds_sample.push({data:[[0,matched_docs.underweight]], color:$chrt_second})
				ds_sample.push({data:[[1,matched_docs.normal]], color:$chrt_fourth})
				ds_sample.push({data:[[2,matched_docs.overweight]], color:"#666"})
				ds_sample.push({data:[[3,matched_docs.obese]], color:"#BBB"})
				/* ds_sample.push({data:[[4,matched_docs.notcalc]], color:"#5482FF"}) */
						
			//Display graph
			$.plot($("#queryres1"), ds_sample, {
			    series:{
				  bars : {
						show    : true,
						align   : "center",
						barWidth: 0.2,
						numbers: 
						{
							show : true,
							yAlign: function(y) { return y + 20; },
						},
						},
				},
				grid : {
					show        : true,
					hoverable   : true,
					clickable   : true,
					tickColor   : $chrt_border_color,
					borderWidth : 0,
					borderColor : $chrt_border_color,
				},
				legend: {
				show:true,
				noColumns: 0,
                labelBoxBorderColor: "#000000",
                position: "nw",
				container: $("#legend-container")
				},
				yaxis : {
		
					axisLabel: "No. Of. Students",
					axisLabelUseCanvas: true,
                    axisLabelFontFamily: 'Verdana, Arial',
               },
				xaxis : {
					axisLabel: ''+matched_docs.schoolname+' : '+total_docs+'',
                    ticks:x_ticks
			   }

			});
			
			function showTooltip(x, y, contents, z) {
				$('<div id="flot-tooltip">' + contents + '</div>').css({
					top: y - 20,
					left: x - 50,
					'border-color': z,
				}).appendTo("body").show();
			}
			  $("#queryres1").bind("plothover", function (event, pos, item) {
				//console.log("item",item)
		        if (item) {
		            if (previousPoint != item.datapoint) {
		                previousPoint = item.datapoint;
		                $("#flot-tooltip").remove();
		 
		                var originalPoint;
		                 
		                if (item.datapoint[0] == item.series.data[0][3]) 
						{
							
		                    originalPoint = item.series.data[0][0];
		                } 
						else
						{
							originalPoint = item.datapoint[0];
						}
		                var x = originalPoint;
		                y = item.datapoint[1];
		                z = item.series.color;
		 
		                showTooltip(item.pageX, item.pageY,
		                    "<b>" + item.series.xaxis.ticks[x].label + "</b><br /> " + y + "",
		                    z);
		            }
		        } else {
		            $("#flot-tooltip").remove();
		            previousPoint = null;
		        }
		    });

		}

		/* end bar chart */
	}
	else if(opt_type == "summary_graph")
	{
		var total_docs   = res_data.count;
		var matched_docs = res_data.docs;
		var x_ticks = [];
		var ds_sample = [];
	    var inc=0;
		for(var i in matched_docs)
		{
			console.log(matched_docs[i])
			console.log(i)
			if(matched_docs[i]!=0 && i!="schoolname")
			{
				var newColor = '#'+(0x1000000+(Math.random())*0xffffff).toString(16).substr(1,6);
				//var color = '#'+(Math.random()*0xFFFFFF<<0).toString(16);
				var ticks_new = i.replace(/_/g," ");
				ds_sample.push({data:[[inc,matched_docs[i]]], color:newColor})//$chrt_second})
				x_ticks.push([inc, ticks_new]);
				inc++;
			}
			
		}
		//var x_ticks = [[0, "Anaemia"], [1, "Vitamin Deficiences"], [2, "Asthma"], [3, "General Problems"], [4, "Diabetes"],[5, "NAD"],[6, "Ear Problems"],[7, "Eye Problems"],[8, "Speech Problems"],[9, "Ortho Problems"],[10, "Dental Problems"]];
		
        /* bar chart */

		if ($("#queryres1").length) {
			 //need to check the undefined for matched_docs.length
				/* var ds_sample = [];
	
				ds_sample.push({data:[[0,matched_docs.anaemia]], color:$chrt_second})
				ds_sample.push({data:[[1,matched_docs.vitdef]], color:$chrt_fourth})
				ds_sample.push({data:[[2,matched_docs.asthma]], color:"#666"})
				ds_sample.push({data:[[3,matched_docs.general]], color:"#BBB"})
				ds_sample.push({data:[[4,matched_docs.diabetes]], color:"#5482FF"})
				ds_sample.push({data:[[5,matched_docs.nad]], color:"#5C5C5C"})
				ds_sample.push({data:[[6,matched_docs.ear]], color:"#5482FF"})
				ds_sample.push({data:[[7,matched_docs.eye]], color:"#5482FF"})
				ds_sample.push({data:[[8,matched_docs.speech]], color:"#5482FF"})
				ds_sample.push({data:[[9,matched_docs.ortho]], color:"#5482FF"})
				ds_sample.push({data:[[10,matched_docs.dental]], color:"#5482FF"}) */
						
			//Display graph
			$.plot($("#queryres1"), ds_sample, {
			    series:{
				  bars : {
						show    : true,
						align   : "center",
						barWidth: 0.2,
						numbers: 
						{
							show : true,
							yAlign: function(y) { return y + 20; },
							
						},
						},
				},
				grid : {
					show        : true,
					hoverable   : true,
					clickable   : true,
					tickColor   : $chrt_border_color,
					borderWidth : 0,
					borderColor : $chrt_border_color,
				},
				legend: {
				show:true,
				noColumns: 0,
                labelBoxBorderColor: "#000000",
                position: "nw",
				container: $("#legend-container")
				},
				yaxis : {
		
					axisLabel: "No. Of. Students",
					axisLabelUseCanvas: true,
                    axisLabelFontFamily: 'Verdana, Arial',
               },
				xaxis : {
					axisLabel: ''+matched_docs.schoolname+' : '+total_docs+'',
                    ticks:x_ticks
			   }

			});
			
			function showTooltip(x, y, contents, z) {
				$('<div id="flot-tooltip">' + contents + '</div>').css({
					top: y - 20,
					left: x - 50,
					'border-color': z,
				}).appendTo("body").show();
			}
			  $("#queryres1").bind("plothover", function (event, pos, item) {
				//console.log("item",item)
		        if (item) {
		            if (previousPoint != item.datapoint) {
		                previousPoint = item.datapoint;
		                $("#flot-tooltip").remove();
		 
		                var originalPoint;
		                 
		                if (item.datapoint[0] == item.series.data[0][3]) 
						{
							
		                    originalPoint = item.series.data[0][0];
		                } 
						else
						{
							originalPoint = item.datapoint[0];
						}
		                var x = originalPoint;
		                y = item.datapoint[1];
		                z = item.series.color;
		 
		                showTooltip(item.pageX, item.pageY,
		                    "<b>" + item.series.xaxis.ticks[x].label + "</b><br /> " + y + "",
		                    z);
		            }
		        } else {
		            $("#flot-tooltip").remove();
		            previousPoint = null;
		        }
		    });

		}
	}
	else if(opt_type == "detailed_graph")
	{
		var total_docs   = res_data.count;
		var matched_docs = res_data.docs;
		var x_ticks = [];
		 var ds_sample = [];
		 //var inc=0;
		 var data_val = [];
		 var seleceted_color = [];
		var newColor = '';
		for(var i in matched_docs)
		{
			//console.log(matched_docs[i])
			//console.log(i)
			if(matched_docs[i]!=0 && i!="schoolname")
			{
				for(var newclr=0; newclr<10;newclr++)
				{
					newColor = '#'+(0x1000000+(Math.random())*0xffffff).toString(16).substr(1,6);
					
					if($.inArray(newColor,seleceted_color) < 0 )
					{
						seleceted_color.push(newColor)
						break
					}
				}
				//var newColor = '#'+(0x1000000+(Math.random())*0xffffff).toString(16).substr(1,6);
				//var color = '#'+(Math.random()*0xFFFFFF<<0).toString(16);
				var per = (matched_docs[i]/total_docs)*100;
				console.log(per)
				per = Math.round(per)
				var ticks_new = i.replace(/_/g," ");
				data_val.push({
					value: per,
					realcount : matched_docs[i],
					color: newColor,
					highlight: "#FF5A5E",
					label: ticks_new
				})
				//ds_sample.push({data:[[inc,matched_docs[i]]], color:newColor})//$chrt_second})
				//x_ticks.push([inc, ticks_new]);
				//inc++;
			}
			//console.log(seleceted_color)
			
		}
		var pie_chart = '<div class="row"><div class="col-xs-8 col-sm-2 col-md-2 col-lg-6"><canvas id="canvas" class="percentage" height="400" width="400"></canvas></div><div id="legend" class="col-lg-6"></div></div>'
		$('#queryres1').html(pie_chart);
		//console.log("data_val",JSON.stringify(data_val))
		console.log("data_val",data_val)
		var myPie = new Chart(document.getElementById("canvas").getContext("2d")).Pie(data_val, 
		{ 		               
			animationSteps: 100,
			//legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<data_val.length; i++){%><li><span style=\"background-color:<%=data_val[i].color%>\"></span><%if(data_val[i].label){%><%=data_val[i].label%><%}%></li><%}%></ul>",
			animationEasing: 'easeInOutQuart'	
		});
		document.getElementById('legend').innerHTML = myPie.generateLegend();
		//legend(document.getElementById("legend"), data_val, myPie);
	}
	else if(opt_type == "detailed_graph")
	{
		var total_docs   = res_data.count;
		var matched_docs = res_data.docs;
		var x_ticks = [];
		var ds_sample = [];
		var seleceted_color = [];
		var inc=0;
		var newColor = '';
		for(var i in matched_docs)
		{
			//console.log(matched_docs[i])
			//console.log(i)
			if(matched_docs[i]!=0 && i!="schoolname")
			{
				for(var newclr=0; newclr<10;newclr++)
				{
					newColor = '#'+(0x1000000+(Math.random())*0xffffff).toString(16).substr(1,6);
					
					if($.inArray(newColor,seleceted_color) <0 )
					{
						seleceted_color.push(newColor)
						break
					}
				}
				
				//var color = '#'+(Math.random()*0xFFFFFF<<0).toString(16);
				var ticks_new = i.replace(/_/g," ");
				ds_sample.push({data:[[inc,matched_docs[i]]], color:newColor})//$chrt_second})
				x_ticks.push([inc, ticks_new]);
				inc++;
			}
			
		}
		/* bar chart */

		if ($("#queryres1").length) {
			//Display graph
			$.plot($("#queryres1"), ds_sample, {
			    series:{
				  bars : {
						show    : true,
						align   : "center",
						barWidth: 0.2,
						numbers: 
						{
							show : true,
							yAlign: function(y) { return y + 20; },
						},
						},
				},
				grid : {
					show        : true,
					hoverable   : true,
					clickable   : true,
					tickColor   : $chrt_border_color,
					borderWidth : 0,
					borderColor : $chrt_border_color,
				},
				legend: {
				show:true,
				noColumns: 0,
                labelBoxBorderColor: "#000000",
                position: "nw",
				container: $("#legend-container")
				},
				yaxis : {
		
					axisLabel: "No. Of. Students",
					axisLabelUseCanvas: true,
                    axisLabelFontFamily: 'Verdana, Arial',
               },
				xaxis : {
					axisLabel: ''+matched_docs.schoolname+' : '+total_docs+'',
                    ticks:x_ticks
			   }

			});
			
			function showTooltip(x, y, contents, z) {
				$('<div id="flot-tooltip">' + contents + '</div>').css({
					top: y - 20,
					left: x - 50,
					'border-color': z,
				}).appendTo("body").show();
			}
			  $("#queryres1").bind("plothover", function (event, pos, item) {
				//console.log("item",item)
		        if (item) {
		            if (previousPoint != item.datapoint) {
		                previousPoint = item.datapoint;
		                $("#flot-tooltip").remove();
		 
		                var originalPoint;
		                 
		                if (item.datapoint[0] == item.series.data[0][3]) 
						{
							
		                    originalPoint = item.series.data[0][0];
		                } 
						else
						{
							originalPoint = item.datapoint[0];
						}
		                var x = originalPoint;
		                y = item.datapoint[1];
		                z = item.series.color;
		 
		                showTooltip(item.pageX, item.pageY,
		                    "<b>" + item.series.xaxis.ticks[x].label + "</b><br /> " + y + "",
		                    z);
		            }
		        } else {
		            $("#flot-tooltip").remove();
		            previousPoint = null;
		        }
		    });

		}
	}
	else if(opt_type == "age_weight")
	{
		var total_docs   = res_data.count;
		var matched_docs = res_data.docs;
		var x_ticks = [[0,"5(17.7KG)"],[1,"6(19.5KG)"],[2,"7(21.8KG)"],[3,"8(24.8KG)"],[4,"9(28.5KG)"],[5,"10(32.5KG)"],[6,"11(33.7KG)"],[7,"12(38.7KG)"],[8,"13(44.0KG)"],[9,"14(48.0KG)"],[10,"15(51.5KG)"],[11,"16(53.0KG)"],[12,"17(54.0KG)"],[13,"18(54.4KG)"]];
		var ds_sample = [];
		var graph_data = matched_docs.graphdata;
		ds_sample['dataunderweight'] =[];
		ds_sample['datanormalweight'] =[];
		ds_sample['dataoverweight'] =[];
		var inc=0;
		for(var i in graph_data)
		{
				ds_sample['dataunderweight'].push([inc,graph_data[i]['underweight']])
				ds_sample['datanormalweight'].push([inc,graph_data[i]['normalweight']])
				ds_sample['dataoverweight'].push([inc,graph_data[i]['overweight']])
				inc++;			
		}
        /* bar chart */
		var ds_ = [];
		ds_.push({
			    data : ds_sample['dataunderweight'],
				bars : {
					show : true,
					barWidth :0.1,
					order : 1,
					numbers: 
						{
							show : true,
							yAlign: function(y) { return y + 2; },
							//xAlign: function(x) { return x - 0.5; },
							//align:"center"
						},
					},
				label: "Under Weight"
				});
				ds_.push({
			    data : ds_sample['datanormalweight'],
				bars : {
					show : true,
					barWidth :0.1,
					order : 2,
					numbers: 
						{
							show : true,
							yAlign: function(y) { return y + 2; },
						},
					},
				label: "Ideal Weight"
				});
				ds_.push({
			    data : ds_sample['dataoverweight'],
				bars : {
					show : true,
					barWidth :0.1,
					order : 3,
					numbers: 
						{
							show : true,
							yAlign: function(y) { return y + 2; },
						},
					},
				label: "Over Weight"
				});
				console.log("ds_",ds_)
		if ($("#queryres1").length) {
						
			//Display graph
			$.plot($("#queryres1"), ds_, {
				colors : [$chrt_second, $chrt_fourth, "#666", "#BBB"],
				grid : {
					show        : true,
					hoverable   : true,
					clickable   : true,
					tickColor   : $chrt_border_color,
					borderWidth : 0,
					borderColor : $chrt_border_color,
				},
				legend: {
				show:true,
				noColumns: 0,
                labelBoxBorderColor: "#000000",
                position: "nw",
				container: $("#legend-container")
				},
				yaxis : {
		
					axisLabel: "No. Of. Students",
					axisLabelUseCanvas: true,
                    axisLabelFontFamily: 'Verdana, Arial',
               },
				xaxis : {
					axisLabel: ''+matched_docs.schoolname+' : '+total_docs+'',
                    ticks:x_ticks
			   }

			});
			
			function showTooltip(x, y, contents, z) {
				$('<div id="flot-tooltip">' + contents + '</div>').css({
					top: y - 20,
					left: x - 50,
					'border-color': z,
				}).appendTo("body").show();
			}
			  $("#queryres1").bind("plothover", function (event, pos, item) {
				//console.log("item",item)
		        if (item) {
		            if (previousPoint != item.datapoint) {
		                previousPoint = item.datapoint;
		                $("#flot-tooltip").remove();
		 
		                var originalPoint;
		                 
						if (item.datapoint[0] == item.series.data[0][3]) {
							
		                    originalPoint = item.series.data[0][0];
		                } else if (item.datapoint[0] == item.series.data[1][3]){
			
		                    originalPoint = item.series.data[1][0];
		                } else if (item.datapoint[0] == item.series.data[2][3]){
	
		                    originalPoint = item.series.data[2][0];
		                } else if (item.datapoint[0] == item.series.data[3][3]){
		
		                    originalPoint = item.series.data[3][0];
		                } else if (item.datapoint[0] == item.series.data[4][3]){
								originalPoint = item.series.data[4][0];
			             }
						
		                var x = originalPoint;
		                y = item.datapoint[1];
		                z = item.series.color;
		                showTooltip(item.pageX, item.pageY,
		                    "<b>" + item.series.label + "</b><br /> " + y + "",
		                    z);
		            }
		        } else {
		            $("#flot-tooltip").remove();
		            previousPoint = null;
		        }
		    });

		}
	}
	else if(opt_type == "age_height")
	{
		var total_docs   = res_data.count;
		var matched_docs = res_data.docs;
		var x_ticks = [[0,"5(108.4CM)"],[1,"6(114.6CM)"],[2,"7(120.6CM)"],[3,"8(126.4CM)"],[4,"9(132.2CM)"],[5,"10(138.3CM)"],[6,"11(142.0CM)"],[7,"12(148.0CM)"],[8,"13(150.0CM)"],[9,"14(155.0CM)"],[10,"15(161.0CM)"],[11,"16(162.0CM)"],[12,"17(163.0CM)"],[13,"18(164.0CM)"]];
		var ds_sample = [];
		var graph_data = matched_docs.graphdata;
		ds_sample['dataunderheight'] =[];
		ds_sample['datanormalheight'] =[];
		ds_sample['dataoverheight'] =[];
		var inc=0;
		for(var i in graph_data)
		{
				ds_sample['dataunderheight'].push([inc,graph_data[i]['underheight']])
				ds_sample['datanormalheight'].push([inc,graph_data[i]['normalheight']])
				ds_sample['dataoverheight'].push([inc,graph_data[i]['overheight']])
				inc++;			
		}
        /* bar chart */
		var ds_ = [];
		ds_.push({
			    data : ds_sample['dataunderheight'],
				bars : {
					show : true,
					barWidth :0.1,
					order : 1,
					numbers: 
						{
							show : true,
							yAlign: function(y) { return y + 2; },
						},
					},
				label: "Under Height"
				});
				ds_.push({
			    data : ds_sample['datanormalheight'],
				bars : {
					show : true,
					barWidth :0.1,
					order : 2,
					numbers: 
						{
							show : true,
							yAlign: function(y) { return y + 2; },
						},
					},
				label: "Ideal Height"
				});
				ds_.push({
			    data : ds_sample['dataoverheight'],
				bars : {
					show : true,
					barWidth :0.1,
					order : 3,
					numbers: 
						{
							show : true,
							yAlign: function(y) { return y + 2; },
						},
					},
				label: "Over Height"
				});
				console.log("ds_",ds_)
		if ($("#queryres1").length) {
						
			//Display graph
			$.plot($("#queryres1"), ds_, {
				colors : [$chrt_second, $chrt_fourth, "#666", "#BBB"],
				grid : {
					show        : true,
					hoverable   : true,
					clickable   : true,
					tickColor   : $chrt_border_color,
					borderWidth : 0,
					borderColor : $chrt_border_color,
				},
				legend: {
				show:true,
				noColumns: 0,
                labelBoxBorderColor: "#000000",
                position: "nw",
				container: $("#legend-container")
				},
				yaxis : {
		
					axisLabel: "No. Of. Students",
					axisLabelUseCanvas: true,
                    axisLabelFontFamily: 'Verdana, Arial',
               },
				xaxis : {
					axisLabel: ''+matched_docs.schoolname+' : '+total_docs+'',
                    ticks:x_ticks
			   }

			});
			
			function showTooltip(x, y, contents, z) {
				$('<div id="flot-tooltip">' + contents + '</div>').css({
					top: y - 20,
					left: x - 50,
					'border-color': z,
				}).appendTo("body").show();
			}
			  $("#queryres1").bind("plothover", function (event, pos, item) {
				//console.log("item",item)
		        if (item) {
		            if (previousPoint != item.datapoint) {
		                previousPoint = item.datapoint;
		                $("#flot-tooltip").remove();
		 
		                var originalPoint;
		                 
						if (item.datapoint[0] == item.series.data[0][3]) {
							
		                    originalPoint = item.series.data[0][0];
		                } else if (item.datapoint[0] == item.series.data[1][3]){
			
		                    originalPoint = item.series.data[1][0];
		                } else if (item.datapoint[0] == item.series.data[2][3]){
	
		                    originalPoint = item.series.data[2][0];
		                } else if (item.datapoint[0] == item.series.data[3][3]){
		
		                    originalPoint = item.series.data[3][0];
		                } else if (item.datapoint[0] == item.series.data[4][3]){
								originalPoint = item.series.data[4][0];
			             }
						
		                var x = originalPoint;
		                y = item.datapoint[1];
		                z = item.series.color;
		                showTooltip(item.pageX, item.pageY,
		                    "<b>" + item.series.label + "</b><br /> " + y + "",
		                    z);
		            }
		        } else {
		            $("#flot-tooltip").remove();
		            previousPoint = null;
		        }
		    });

		}
	}

	
}


		

var data=$('#queryapp').val();
$('#queryapp').val("");

  $(document).on('change','.selectpicker',function()
		  {
	  var opt=$('#appdocs option:selected').val();
	  
	  $.ajax({
			url: 'docss/'+opt,
			type: 'POST',
			success: function (data) {
				
				$('#totaldocs').html(data);
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
		  });

  var table="";
 
 if(data)
 {
   var docs = JSON.parse(data);
 }  
  
					
if ( docs != 0)
{
	$('#analytics').html('<div class="row" style="margin:13px;" id="elements-build"><div class="col col-lg-4"><div class="panel panel-default"><div class="panel-heading" role="tab" id=""><h4 class="panel-title">Section Lists</h4></div><div class="panel-body" style="height:500px;overflow-y: scroll;"><div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"></div></div></div></div><div class="col col-lg-8"><div class="panel panel-default"><div class="panel-heading" role="tab" id=""><h4 class="panel-title">Selected Elements</h4></div><div class="panel-body" style="min-height:500px"><div class="row" style="margin:13px;"><label class="col col-lg-offset-2 col-lg-3"><strong>Select chart type : </strong></label><select class="col col-lg-5 graph_type" id="grapth_type_id"><option value="0">Select chart type</option><option value="pie">Pie chart</option><option value="bar">Bar chart</option><option value="bmi">BMI chart</option><option value="age_weight">Weight chart</option><option value="age_height">Height chart</option><option value="summary_graph">School summary chart</option><option value="detailed_graph">Detialed school chart</option></select></div><table class="table table-bordered"><tbody class="analytics_tbody" id="selected-list"></tbody></table></div></div></div></div>');
	//<ul class="list-group" id="selected-list"></ul>
	
	$('#elements-build').hide();
	
	var panel='';
	var id_rand = 1;
	var section_array = []
	var current_section = '';
	var that = '';
	var index_i = 1;
	for( var i in docs )
	{
		single = docs[i];
		for(var j in single)
		{
			var size = Object.keys(single[j]).length;
			var si = 1;
			if($.inArray(''+j+'', section_array) == -1)
			{
				current_section = j;
				section_array.push(j)
				//console.log(section_array)
				if(that!='')
				{
					//console.log("that")
					var find_append = $(that).attr("id-rand");
					$(that).appendTo($('#section'+find_append+'').find('#'+find_append+''))
				}
				
				$('<div class="panel panel-default"><div class="panel-heading" role="tab" id="headingOne'+si+'"><h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#section'+id_rand+'" aria-expanded="true" aria-controls="section'+id_rand+'">'+j+'</a></h4></div><div id="section'+id_rand+'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne'+si+'"><div class="panel-body" id="'+id_rand+'"></div></div></div>').appendTo('#accordion');
				
				that = $('<ul class="list-group" id-rand="'+id_rand+'" id="id'+id_rand+'"></ul>');
			}				
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
					    var school='';
						element=single[j][s];
						//console.log("single[j][s]",single[j][s])
						//console.log("[s]",s.toLowerCase().indexOf("date of exam"))
						if (s.toLowerCase().indexOf("school") >= 0)
						{
							//console.log("schoooooooooooooo");
							school = "school";
							var dataid=$('#queryid').val();
							var fieldname =  btoa(i+"."+j+"."+s);
							$.ajax({
									url: '../../../school/fetch_school_names',
									type: 'POST',
									dataType:"json",
									async:true,
									data: {"dataid" : dataid,"field_name":fieldname},
									success: function (res_data) 
									{
										school_list = res_data;
										console.log("school_list",school_list);
												
									},
									error:function(XMLHttpRequest, textStatus, errorThrown)
									{
										console.log('error', errorThrown);
									}
								});
						}
						else if(s.toLowerCase().indexOf("height") >= 0)
						{
							height_name = btoa(i+"."+j+"."+s);
						}
						else if(s.toLowerCase().indexOf("weight") >= 0)
						{
							weight_name = btoa(i+"."+j+"."+s);
						}
						else if(s.toLowerCase().indexOf("date of exam") >= 0)
						{
							dateof_exam = btoa(i+"."+j+"."+s);
						}
						else if(s.toLowerCase().indexOf("date of birth") >= 0)
						{
							dob = btoa(i+"."+j+"."+s);
						}
						
						if(si != size)
						{
							
							if(element.type=="text" || element.type=="number" || element.type=="textarea")
							{
								if(element.key=="TRUE")
								{
									s_for_id = btoa(i+"."+j+"."+s);
									$('<li class="list-group-item text search_elem" id="'+index_i+'" status="true">'+s+'<button class="btn btn-xs btn-default pull-right add_elem" element_name="'+s+'" value="'+s_for_id+'" id="'+school+'">Add</button></li> ').appendTo(that)
								    /*  table=table+"<tr checkbox='false'><td><label search_val="+s_for_id+">"+s+"</label></td><td><input class="+s_for_id+" id='great' type='' placeholder='Greater than'/></td><td><input class="+s_for_id+" id='less' type='' placeholder='Less than'/></td><td><input class="+s_for_id+" id='equal' type='' placeholder='Equal To'/></td><td><select class='andor' id="+s_for_id+"><option value='OR'>OR</option><option value='AND'>AND</option></select></td></tr>"; */
									index_i++;
								}
						    }
							else if(element.type=="checkbox" || element.type=="radio")
							{						
								var option_list = '';
								
								for(var ele_opt=0; ele_opt < element.options.length; ele_opt++)
								{
									var new_value = element.options[ele_opt].label
									new_value = new_value.replace(/\s+/g,"`");
									option_list += '<option value='+new_value+'>'+element.options[ele_opt].label+'</option>'
									
								}
								//console.log(option_list)
								s_for_id = btoa(i+"."+j+"."+s);
								var class_type = "check";
								if(element.type=="radio")
								{
									class_type = "radio";
								}
								$('<li class="list-group-item '+class_type+' search_elem" id="'+index_i+'"  status="true">'+s+'<button class="btn btn-xs btn-default pull-right add_elem" element_name="'+s+'" opt_values="'+option_list+'" value="'+s_for_id+'">Add</button></li> ').appendTo(that)
								//table=table+"<tr checkbox='true'><td><label search_val="+s_for_id+">"+s+"</label></td><td><input class='"+s_for_id+" disable' id='great' type='' placeholder='Greater than' style='cursor:not-allowed;' disabled/></td><td><input class='"+s_for_id+" disable' id='less' type='' placeholder='Less than' style='cursor:not-allowed;' disabled/></td><td><select class='options multiselect' id="+s_for_id+" multiple='multiple'>"+option_list+"</select></td><td><select class='andor' id="+s_for_id+"><option value='OR'>OR</option><option value='AND'>AND</option></select></td></tr>";
								index_i++;
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
					//index_i++;
                 }
			}
			id_rand++;
	     }
		 
	}
		if(that!='')
		{
			//console.log("that")
			var find_append = $(that).attr("id-rand");
			$(that).appendTo($('#section'+find_append+'').find('#'+find_append+''))
		}
		$('#elements-build').show();
}
else
{
		$('#analytics').html("<h2>No results found.</h2>");
}

// graph type on change //
var type_flag = 0;
$(document).on('change','#grapth_type_id',function()
{
	var opt_type=$('#grapth_type_id option:selected').val();
	console.log(opt_type);
	if(opt_type == "pie")
	{
		type_flag = 1;
		$('#selected-list').empty();
		$('.search_elem').attr("status","true");
	}
	else if(opt_type == "bar")
	{
		type_flag = 1;
		$('#selected-list').empty();
		$('.search_elem').attr("status","true");
		//console.log($('#accordion').find('#school'))
		$('#accordion').find('#school').trigger("click");
		$('#selected-list').find('.axis_type').val("xaxis")
	}
	else if(opt_type == "bmi")
	{
		type_flag = 1;
		$('#selected-list').empty();
		$('.search_elem').attr("status","true");
		//console.log("sbbbbbbbbbbbbbbbb")
		//console.log($('#accordion').find('#school'))
		$('#accordion').find('#school').trigger("click");
		/* var that = $('#selected-list').find('.axis_type')
		//console.log(that)
		$(that).val("xaxis")
		$('<select class="options school_list" id="mult" style="padding-left:5px;"></select>').insertAfter(that)
		var school_list_arr = [];
		for(var sl=0;sl<school_list.length;sl++)
		{
			school_list_arr.push({
				label:school_list[sl],
				value:school_list[sl]
				})
		}
		$('.school_list').multiselect({buttonWidth: '150px',nonSelectedText:'Select'});
		$('.school_list').multiselect('dataprovider', school_list_arr); */
	}
	else if(opt_type == "summary_graph")
	{
		type_flag = 1;
		$('#selected-list').empty();
		$('.search_elem').attr("status","true");
		$('#accordion').find('#school').trigger("click");
		/* var that = $('#selected-list').find('.axis_type')
		$(that).val("xaxis")
		$('<select class="options school_list" id="mult" style="padding-left:5px;"></select>').insertAfter(that)
		var school_list_arr = [];
		for(var sl=0;sl<school_list.length;sl++)
		{
			school_list_arr.push({
				label:school_list[sl],
				value:school_list[sl]
				})
		}
		$('.school_list').multiselect({buttonWidth: '150px',nonSelectedText:'Select'});
		$('.school_list').multiselect('dataprovider', school_list_arr); */
	}
	else if(opt_type == "detailed_graph")
	{
		type_flag = 1;
		$('#selected-list').empty();
		$('.search_elem').attr("status","true");
		$('#accordion').find('#school').trigger("click");
		/* var that = $('#selected-list').find('.axis_type')
		$(that).val("xaxis")
		$('<select class="options school_list" id="mult" style="padding-left:5px;"></select>').insertAfter(that)
		var school_list_arr = [];
		for(var sl=0;sl<school_list.length;sl++)
		{
			school_list_arr.push({
				label:school_list[sl],
				value:school_list[sl]
				})
		}
		$('.school_list').multiselect({buttonWidth: '150px',nonSelectedText:'Select'});
		$('.school_list').multiselect('dataprovider', school_list_arr); */
	}
	else if(opt_type == "age_weight")
	{
		type_flag = 1;
		$('#selected-list').empty();
		$('.search_elem').attr("status","true");
		$('#accordion').find('#school').trigger("click");
		/* var that = $('#selected-list').find('.axis_type')
		$(that).val("xaxis")
		$('<select class="options school_list" id="mult" style="padding-left:5px;"></select>').insertAfter(that)
		var school_list_arr = [];
		for(var sl=0;sl<school_list.length;sl++)
		{
			school_list_arr.push({
				label:school_list[sl],
				value:school_list[sl]
				})
		}
		$('.school_list').multiselect({buttonWidth: '150px',nonSelectedText:'Select'});
		$('.school_list').multiselect('dataprovider', school_list_arr); */
	}
	else if(opt_type == "age_height")
	{
		type_flag = 1;
		$('#selected-list').empty();
		$('.search_elem').attr("status","true");
		$('#accordion').find('#school').trigger("click");
		/* var that = $('#selected-list').find('.axis_type')
		$(that).val("xaxis")
		$('<select class="options school_list" id="mult" style="padding-left:5px;"></select>').insertAfter(that)
		var school_list_arr = [];
		for(var sl=0;sl<school_list.length;sl++)
		{
			school_list_arr.push({
				label:school_list[sl],
				value:school_list[sl]
				})
		}
		$('.school_list').multiselect({buttonWidth: '150px',nonSelectedText:'Select'});
		$('.school_list').multiselect('dataprovider', school_list_arr); */
	}
})
	  
	
// add_elem function

$(document).on("click",'.add_elem',function()
{
	if(type_flag != 0)
	{
		var school_list_arr = [];
		for(var sl=0;sl<school_list.length;sl++)
		{
			school_list_arr.push({
				label:school_list[sl],
				value:school_list[sl]
				})
		}
		if($(this).parent('li').attr("status") == "true" )
		{
			var opt_type = $('#grapth_type_id option:selected').val();
			//console.log(opt_type)
			if($(this).parent('li').hasClass("text") == true)
			{
				 var elem_name = $(this).attr("element_name")
				 var elem_id = $(this).attr("id")
				 var elem_val = $(this).attr("value")
				 var ref_ul_id = $(this).parents('ul').attr("id");
				 var ref_li_id = $(this).parent('li').attr("id");
				 
				 
				 if(opt_type == "pie")
				 {
					$(this).parent('li').attr("status","false")
					 
					$("<tr checkbox='false'><td><label search_val="+elem_val+">"+elem_name+"</label></td><td><input class='"+elem_val+" sear_inp' id='great' type='' placeholder='Greater than'/></td><td><input class='"+elem_val+" sear_inp' id='less' type='' placeholder='Less than'/></td><td><input class='"+elem_val+" sear_inp' id='equal' type='' placeholder='Equal To'/></td><td><select class='andor' id="+elem_val+"><option value='sector'>Sector</option><option value='individual'>Individual</option><option value='OR'  disabled>OR</option><option value='AND' disabled>AND</option></select></td><td><button class='btn btn-xs btn-default rem_elem' element_name='"+elem_name+"' ul_id='"+ref_ul_id+"'  li_id='"+ref_li_id+"' value='"+elem_val+"'>Remove</button></td></tr>").appendTo('#selected-list');
				 }
				 else if(opt_type == "bar")
				 {
					 $(this).parent('li').attr("status","false")
					 
					 $("<tr checkbox='false'><td><label search_val="+elem_val+">"+elem_name+"</label></td><td colspan='2'><label><strong>Select AXIS</strong></label><select class='andor axis_type' id="+elem_val+"><option value='select'>SELECT</option><option value='xaxis'>X-AXIS</option><option value='yaxis'>Y-AXIS</option><option value='value'>VALUE</option></select></td><td><button class='btn btn-xs btn-default rem_elem' element_name='"+elem_name+"' ul_id='"+ref_ul_id+"'  li_id='"+ref_li_id+"' value='"+elem_val+"'>Remove</button></td></tr>").appendTo('#selected-list');
				 }
				 else if(opt_type == "bmi")
				 {
					 if(elem_id == "school")
					 {
					 $(this).parent('li').attr("status","false")
					 
					 $("<tr checkbox='false'><td><label search_val="+elem_val+">"+elem_name+"</label></td><td colspan='2'><label><strong>Select AXIS</strong></label><select class='andor axis_type' id="+elem_val+"><option value='select'>SELECT</option><option value='xaxis' selected>X-AXIS</option><option value='yaxis' disabled>Y-AXIS</option><option value='value' disabled>VALUE</option></select><select class='options school_list' id='mult' style='padding-left:5px;'></select></td><td><button class='btn btn-xs btn-default rem_elem' element_name='"+elem_name+"' ul_id='"+ref_ul_id+"'  li_id='"+ref_li_id+"' value='"+elem_val+"'>Remove</button></td></tr>").appendTo('#selected-list');
					$('.school_list').multiselect({buttonWidth: '150px',nonSelectedText:'Select'});
					$('.school_list').multiselect('dataprovider', school_list_arr);
					 }
					 
				 }
				 else if(opt_type == "summary_graph")
				 {
					 if(elem_id == "school")
					 {
					 $(this).parent('li').attr("status","false")
					 
					 $("<tr checkbox='false'><td><label search_val="+elem_val+">"+elem_name+"</label></td><td colspan='2'><label><strong>Select AXIS</strong></label><select class='andor axis_type' id="+elem_val+"><option value='select'>SELECT</option><option value='xaxis' selected>X-AXIS</option><option value='yaxis' disabled>Y-AXIS</option><option value='value' disabled>VALUE</option></select><select class='options school_list' id='mult' style='padding-left:5px;'></select></td><td><button class='btn btn-xs btn-default rem_elem' element_name='"+elem_name+"' ul_id='"+ref_ul_id+"'  li_id='"+ref_li_id+"' value='"+elem_val+"'>Remove</button></td></tr>").appendTo('#selected-list');
					$('.school_list').multiselect({buttonWidth: '150px',nonSelectedText:'Select'});
					$('.school_list').multiselect('dataprovider', school_list_arr);					 
					}
				 }
				 else if(opt_type == "detailed_graph")
				 {
					 if(elem_id == "school")
					 {
					 $(this).parent('li').attr("status","false")
					 
					 $("<tr checkbox='false'><td><label search_val="+elem_val+">"+elem_name+"</label></td><td colspan='2'><label><strong>Select AXIS</strong></label><select class='andor axis_type' id="+elem_val+"><option value='select'>SELECT</option><option value='xaxis' selected>X-AXIS</option><option value='yaxis' disabled>Y-AXIS</option><option value='value' disabled>VALUE</option></select><select class='options school_list' id='mult' style='padding-left:5px;'></select></td><td><button class='btn btn-xs btn-default rem_elem' element_name='"+elem_name+"' ul_id='"+ref_ul_id+"'  li_id='"+ref_li_id+"' value='"+elem_val+"'>Remove</button></td></tr>").appendTo('#selected-list');
					$('.school_list').multiselect({buttonWidth: '150px',nonSelectedText:'Select'});
					$('.school_list').multiselect('dataprovider', school_list_arr);					 
					}
				 }
				 else if(opt_type == "age_weight")
				 {
					 if(elem_id == "school")
					 {
					 $(this).parent('li').attr("status","false")
					 
					 $("<tr checkbox='false'><td><label search_val="+elem_val+">"+elem_name+"</label></td><td colspan='2'><label><strong>Select AXIS</strong></label><select class='andor axis_type' id="+elem_val+"><option value='select'>SELECT</option><option value='xaxis' selected>X-AXIS</option><option value='yaxis' disabled>Y-AXIS</option><option value='value' disabled>VALUE</option></select><select class='options school_list' id='mult' style='padding-left:5px;'></select></td><td><button class='btn btn-xs btn-default rem_elem' element_name='"+elem_name+"' ul_id='"+ref_ul_id+"'  li_id='"+ref_li_id+"' value='"+elem_val+"'>Remove</button></td></tr>").appendTo('#selected-list');
					$('.school_list').multiselect({buttonWidth: '150px',nonSelectedText:'Select'});
					$('.school_list').multiselect('dataprovider', school_list_arr);					 
					}
				 }
				 else if(opt_type == "age_height")
				 {
					 if(elem_id == "school")
					 {
					 $(this).parent('li').attr("status","false")
					 
					 $("<tr checkbox='false'><td><label search_val="+elem_val+">"+elem_name+"</label></td><td colspan='2'><label><strong>Select AXIS</strong></label><select class='andor axis_type' id="+elem_val+"><option value='select'>SELECT</option><option value='xaxis' selected>X-AXIS</option><option value='yaxis' disabled>Y-AXIS</option><option value='value' disabled>VALUE</option></select><select class='options school_list' id='mult' style='padding-left:5px;'></select></td><td><button class='btn btn-xs btn-default rem_elem' element_name='"+elem_name+"' ul_id='"+ref_ul_id+"'  li_id='"+ref_li_id+"' value='"+elem_val+"'>Remove</button></td></tr>").appendTo('#selected-list');
					$('.school_list').multiselect({buttonWidth: '150px',nonSelectedText:'Select'});
					$('.school_list').multiselect('dataprovider', school_list_arr);					 
					}
				 }
				 else if(opt_type == "0")	
				 {
					 message_type()
				 }
			}
			else if($(this).parent('li').hasClass("check") == true || $(this).parent('li').hasClass("radio") == true)
			{
				 var elem_name = $(this).attr("element_name")
				 var elem_val = $(this).attr("value")
				 var elem_opt_val = $(this).attr("opt_values")
				 var ref_ul_id = $(this).parents('ul').attr("id");
				 var ref_li_id = $(this).parent('li').attr("id");
				 //console.log(elem_opt_val)
				 var check_value_attr = "check_box";
				 var multiple_type = 'multiple="multiple"';
				 if($(this).parent('li').hasClass("radio") == true)
				 {
					 //console.log("radioooooooooooo add")
					 check_value_attr = "radio";
					 multiple_type = '';
				 }
				 
				 if(opt_type == "pie")
				 {
					$(this).parent('li').attr("status","false")
					 
					$("<tr checkbox='"+check_value_attr+"'><td><label search_val="+elem_val+">"+elem_name+"</label></td><td><input class='"+elem_val+" disable' id='great' type='' placeholder='Greater than' style='cursor:not-allowed;' disabled/></td><td><input class='"+elem_val+" disable' id='less' type='' placeholder='Less than' style='cursor:not-allowed;' disabled/></td><td><select class='options multiselect' id='multi"+ref_li_id+"' "+multiple_type+">"+elem_opt_val+"</select></td><td><select class='andor' id="+elem_val+"><option value='sector'>Sector</option><option value='individual'>Individual</option><option value='OR' disabled>OR</option><option value='AND' disabled>AND</option></select></td><td><button class='btn btn-xs btn-default rem_elem' element_name='"+elem_name+"' ul_id='"+ref_ul_id+"'  li_id='"+ref_li_id+"' value='"+elem_val+"'>Remove</button></td></tr>").appendTo('#selected-list')
					$('#multi'+ref_li_id+'').multiselect({buttonWidth: '150px',nonSelectedText:'Select'});
				 }
				 else if(opt_type == "bar")
				 {
					 $(this).parent('li').attr("status","false")
					 
					 $("<tr checkbox='"+check_value_attr+"'><td><label search_val="+elem_val+">"+elem_name+"</label></td><td><label><strong>Select AXIS</strong></label><select class='andor axis_type' id="+elem_val+"><option value='select'>SELECT</option><option value='xaxis' disabled>X-AXIS</option><option value='yaxis' disabled>Y-AXIS</option><option value='value'>VALUE</option></select></td><td><select class='options multiselect' id='multi"+ref_li_id+"' "+multiple_type+">"+elem_opt_val+"</select></td><td><button class='btn btn-xs btn-default rem_elem' element_name='"+elem_name+"' ul_id='"+ref_ul_id+"'  li_id='"+ref_li_id+"' value='"+elem_val+"'>Remove</button></td></tr>").appendTo('#selected-list')
					$('#multi'+ref_li_id+'').multiselect({buttonWidth: '150px',nonSelectedText:'Select'});
				 }
				 else if(opt_type == "0")
				 {
					 message_type()
				 }

			}
			
		}
	}
	else
	{
		function message_type()
		{
			$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
				content : "Please select the graph type",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
		}
		message_type();
	}
})

//removing elements from selected elements div //
$(document).on("click",'.rem_elem',function()
{	
	var ul_id = $(this).attr("ul_id")
	var li_id = $(this).attr("li_id")
	$('#'+ul_id+'').find('#'+li_id+'').attr("status","true")
	$(this).parents('tr').remove();
})

// on change for axis select box
$(document).on("change",'.axis_type',function()
{
	var sel_val = $(this).val();
	var check_attribute = $(this).parents('tr').attr("checkbox")
	//console.log(check_attribute)
	if(sel_val == "value" && check_attribute=="false") 
	{
		$("<span class='value_addon' style='margin-left:5px'><select><option value='equalto'>Is equal to</option><option value='like'>Like</option><option value='greaterthan'>Greater than</option><option value='lessthan'>Less than</option></select><input type='text' placeholder='Value' style='margin-left:5px;height:22px;'/></span>").insertAfter($(this));
	}
	else if(sel_val == "value" && check_attribute=="check_box") 
	{
		$("<span class='value_addon' style='margin-left:5px'><select><option value='separate'>Individual</option><option value='AND'>And</option><option value='OR'>Or</option></select></span>").insertAfter($(this));
	}
	else
	{
		$(this).parent('td').find('.value_addon').remove();
	}
})

function save_pattern_query()
{
	var dataid=$('#queryid').val();
	var dataname=$('#appname').val();
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
	$('#save_query').val(arra_strng);
	$('#app_id').val(dataid);
	$('#app_name').val(dataname);
	
}

function message(msg_title,msg_content)
{
	$.SmartMessageBox({
				title   : msg_title,
				content : msg_content,
				buttons : '[OK]'
			}, function(ButtonPressed) {
				if (ButtonPressed === "OK") 
				{
					
				}
				
	       });
}
$('#searchquery').on('click',function()
{
	/* if($(this).hasClass("create") == false)
	{ */
	save_pattern_query();
	var dataid=$('#queryid').val();
	var success = true
	var xax_is = false;
	var val_ue = false;
	var sector_val = false;
	labelarray=[];
	strng='';
	//loading state
	$btn = $(this).button('loading')
    var opt_type = $('#grapth_type_id option:selected').val();
    if(opt_type=="bmi")
	{
		
		var labeltext = $('.analytics_tbody').find('label').attr('search_val');
		var slected_school = $('.analytics_tbody').find('.school_list').val();
		labelarray.push({school_value:slected_school,school_field_name:labeltext,height_field_name:height_name,weight_field_name:weight_name});
	}
	else if(opt_type=="summary_graph")
	{
		var labeltext = $('.analytics_tbody').find('label').attr('search_val');
		var slected_school = $('.analytics_tbody').find('.school_list').val();
		labelarray.push({school_value:slected_school,school_field_name:labeltext});
	}
	else if(opt_type=="detailed_graph")
	{
		var labeltext = $('.analytics_tbody').find('label').attr('search_val');
		var slected_school = $('.analytics_tbody').find('.school_list').val();
		labelarray.push({school_value:slected_school,school_field_name:labeltext});
	}
	else if(opt_type=="age_weight")
	{
		var labeltext = $('.analytics_tbody').find('label').attr('search_val');
		var slected_school = $('.analytics_tbody').find('.school_list').val();
		labelarray.push({school_value:slected_school,school_field_name:labeltext,weight_field_name:weight_name,dob:dob,doe:dateof_exam,});
	}
	else if(opt_type=="age_height")
	{
		var labeltext = $('.analytics_tbody').find('label').attr('search_val');
		var slected_school = $('.analytics_tbody').find('.school_list').val();
		labelarray.push({school_value:slected_school,school_field_name:labeltext,height_field_name:height_name,dob:dob,doe:dateof_exam,});
	}
	else
	{
		$('.analytics_tbody').children('tr').each(function()
		{
			var labeltext = $(this).find('label').attr('search_val');
			//console.log(labeltext)
			var check_checkbox = $(this).attr('checkbox');
			//console.log("check_checkbox",check_checkbox);
			if(check_checkbox == "false")
			{
				if(opt_type == "pie")
				{
					//console.log("check_checkbox = FALSE",check_checkbox);
					var query_great= $(this).find('#great').val();
					var query_less= $(this).find('#less').val();
					var query_equal= $(this).find('#equal').val();			
					if(query_great != '' || query_less != '' || query_equal != '')
					{
						var opt=$(this).find('select option:selected').val();
						if(opt == "sector" && sector_val == false)
						{
							if(query_great != '')
							{
								sector_value = query_great;
							}
							else if(query_less != '')
							{
								sector_value = query_less;
							}
							else if(query_equal != '')
							{
								sector_value = query_equal;
							}
							var sec_label = atob(labeltext)
							sec_label = sec_label.substring(sec_label.lastIndexOf('.')+1);
							sector_name = sec_label;
							sector_val = true;
							console.log(sector_val)
						}
						else if(opt == "sector" && sector_val == true)
						{
							sector_val = "exceed"
						}
						if(opt != 0)
						{
							//labelarray.push({labelname:labeltext,value:queryvalue,option:opt,greaterthan:query_great,lessthan:query_less,equalto:query_equal});
							labelarray.push({labelname:labeltext,value:query_equal,option:opt,greaterthan:query_great,lessthan:query_less});
						}
						else
						{
							labelarray.push({labelname:labeltext,value:queryvalue});
						}
					}
				}
				else if(opt_type == "bar")
				{
						var opt=$(this).find('.andor option:selected').val();
						if(opt == "xaxis"){
							xax_is = true;
						}else if(opt == "value"){
							val_ue = true;
						}
						if(opt != "value")
						{
							labelarray.push({labelname:labeltext,value:"",option:opt});
						}
						else
						{
							var elem_name = $(this).find('.rem_elem').attr("element_name");
							var comp_operator = $(this).find('.value_addon').find('select option:selected').val()				
							var value = $(this).find('.value_addon').find('input').val();
							labelarray.push({labelname:labeltext,value:value,option:opt,comparison_opt:comp_operator,field_name:elem_name});
						}
				}
			}
			else if(check_checkbox == "radio" || check_checkbox == "check_box")
			{
					var option_selected = [];
					var sel_opt =$(this).find('.options').val() || '';
					var sel_opt_or =$(this).find('.andor option:selected').val();
					var field_name_radio = '';
					if(sel_opt != '' && check_checkbox == "check_box")
					{
						for(var sel_i=0; sel_i<sel_opt.length;sel_i++)
						{
							var new_value = sel_opt[sel_i]
							new_value = new_value.replace(/`/g," ");
							 option_selected.push(new_value);
						}
						field_name_radio = option_selected
					}
					else
					{
						option_selected = '';
						option_selected = sel_opt;
						var elem_name = $(this).find('.rem_elem').attr("element_name");
						field_name_radio = elem_name;
					}
				
				if(opt_type == "pie")
				{
					labelarray.push({labelname:labeltext,value:option_selected,option:sel_opt_or,greaterthan:query_great,lessthan:query_less});
				}	
				else if(opt_type == "bar")
				{
					val_ue = true;
					var comp_operator = $(this).find('.value_addon').find('select option:selected').val()	|| "equalto";
							
							if(comp_operator == "separate")
							{
								for(var sep=0;sep<option_selected.length;sep++)
								{
									labelarray.push({labelname:labeltext,value:option_selected[sep],option:"value",comparison_opt:"equalto",field_name:option_selected[sep]});
								}	
							}
							else
							{
								labelarray.push({labelname:labeltext,value:option_selected,option:"value",comparison_opt:comp_operator,field_name:field_name_radio});
							}
				}
			}
			//})
														
		})
	}
		
	strng=JSON.stringify(labelarray);
	console.log("strng",strng);
	if(opt_type == "bar")
	{
		var msg_title = "Analytics";
		var msg_content = "Field values should not be empty, please fill the values."
		
		if(xax_is == false)
		{
			success = false
			msg_content = "Please select the XAXIS."
			message(msg_title,msg_content)
		}
		else if(val_ue == false)
		{
			success = false
			msg_content = "Atleast ONE values field should be created."
			message(msg_title,msg_content)
		}
		
	}
	else if(opt_type == "pie")
	{
		console.log(sector_val)
		if(sector_val == "exceed" || sector_val == false)
		{
			success = false
			var msg_title = "Analytics";
			var msg_content = "Please select only one sector option."
			message(msg_title,msg_content)
		}
		if(strng == '[]')
		{
			var msg_title = "Analytics";
			var msg_content = "Field values should not be empty, please fill the values."
			message(msg_title,msg_content)
		}
	}
	if(strng != '[]' && success == true)
	{
		var appdef_name = $('#appname').val()
		appdef_name = btoa(appdef_name)
		var a_id = btoa(dataid);
		var str_val = btoa(strng)
		$('#save_query').val(str_val);
		$('#app_id').val(a_id);
		$('#app_name').val(appdef_name);
		$('#graphtyp').val(opt_type);
		pattern_to_save = strng;

		$.ajax({
			//url: '../../searching',
			url: '../../analytics',
			type: 'POST',
			dataType:"json",
			data: {"strng" : strng,"dataid" : dataid,"graph_type":opt_type},
			beforeSend: function() {
				$('.loading').show();
			},
			success: function (res_data) 
			{
				$('.loading').hide();
				drawing_graph(opt_type,res_data)		
						
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
				$('.loading').hide();
				var msg_title = "Analytics";
		        var msg_content = "Something went wrong.. Please try again !"
				message(msg_title,msg_content)
				console.log('error', errorThrown);
			}
		});
	}
	/* else
	{
		var msg_title = "Analytics";
		var msg_content = "Field values should not be empty, please fill the values."

		message(msg_title,msg_content)
	} */
});

// Query from saved pattern tab
$(document).on('click','.query',function()
{
	$btn = $(this).button('loading')
	$('#queryres').hide();
	$('#appmicrochart').hide();
	$('#analytics').show();
	$('#pattern').hide();
	$('#analyticsbtn').show();

	$('.analytics_table').hide();
	$('.analytics_row').hide();
	
	$('#analytics').hide();
	$('.table-condensed').hide();
	$('#analyticsbtn').hide();
	$('#queryres').show();
	$('#queryres1').show();
	$('#appmicrochart').show();
	
	var query_app_id  = $(this).attr('id');
	var query_pattern = $(this).attr('pattern');
	var graph_type    = $(this).attr('gtype');
	query_pattern     = atob(query_pattern);
	
	var full_url = window.location.href;
	var url = full_url.substring(0, full_url.search("index.php"));
	$.ajax({
		url: url+'index.php/dashboard/analytics',
		type: 'POST',
		dataType:"json",
		data: {"strng" : query_pattern,"dataid" : query_app_id,"graph_type":graph_type},
		beforeSend: function() {
				$('.loading').show();
			},
			success: function (res_data) 
			{
				$('.loading').hide();
				drawing_graph(graph_type,res_data)		
						
			},
			error:function(XMLHttpRequest, textStatus, errorThrown)
			{
				$('.loading').hide();
				var msg_title = "Analytics";
		        var msg_content = "Something went wrong.. Please try again !"
				message(msg_title,msg_content)
				console.log('error', errorThrown);
			}
	});
	//$('#query_modal').modal("show");	
});

	// Delete query 
	$('#deletequery a').click(function(e) {
		//get the link
		var $this = $(this);
		$.delURL = $this.attr('href');

		// ask verification
		$.SmartMessageBox({
			title : "<i class='fa fa-minus-square txt-color-orangeDark'></i> Do you want to delete this query ?",
			buttons : '[No][Yes]'

		}, function(ButtonPressed) {
			if (ButtonPressed == "Yes") {
				setTimeout(deletequery, 1000)
			}

		});
		e.preventDefault();
	});

	/*
	 * Delete My apps ACTION
	 */

	function deletequery() {
		window.location = $.delURL;
	}

});