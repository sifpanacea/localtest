$(document).ready(function() {

		/*
		* RUN PAGE GRAPHS
		*/

		/* TAB 1: UPDATING CHART */
		// For the demo we use generated data, but normally it would be coming from the server
		var y;
		var previous_count=0,current_count,document_count;
		var url=window.location.href;
		var base_url;//=url.lastIndexOf("/",0);
		base_url=url.substring(0, url.lastIndexOf('.'));
		var doc_cnt;

		var data_cnt = [], totalPoints = 105, $UpdatingChartColors = $("#updating-chart").css('color');

		function getRandomData() {
			if (data_cnt.length > 0)
				data_cnt = data_cnt.slice(1);
				//console.log("daaaaaaaataaaaaaaaaaaaaaaaaaaaaaaaaaa");
				//console.log(data_cnt);
				//console.log(data_cnt.length);

			// do a random walk
			if(y==undefined)
			{
			//console.log("yyyyyyyyyyyyyyyyyyyyyyyy",y)
				$.ajax({
					url: base_url+'.php/dashboard/docs_count',
					type: 'POST',
					
					success: function (data)
					{
						var data_obj=$.parseJSON(data);
						//console.log('successssssssssssssssssssssssssssssss', data_obj);						
						doc_cnt=data_obj.total_docs;
						//doc_cnt=data_obj.time;
						$.each(data_obj, function (i, obj) 
						{
							var dc_cnt=obj.total_docs	
							//console.log('successssssssssssssssssssssssssssssss',dc_cnt);
							y=dc_cnt;
							if(previous_count==0)
							{
							previous_count=dc_cnt;
							dc_cnt=dc_cnt-previous_count;
							}
							data_cnt.push(dc_cnt);
							return false;
						})
												
					},
					error: function (XMLHttpRequest, textStatus, errorThrown)
					{
					console.log('error', errorThrown);
					}
				})

			while (data_cnt.length < totalPoints) {
				var prev = data_cnt.length > 0 ? data_cnt[data_cnt.length - 1] : 50;
				// console.log(prev)
				// console.log(Math.random() * 10 - 5)
				// console.log(prev + Math.random() * 10 - 5)
				//y = prev + Math.random() * 10 - 5;
				if (y < 0)
					y = 0;
				if (y > 100)
					y = 100;
				//console.log(y);
				data_cnt.push(y);
			}
			}
			else
			{
				//console.log("eeeeeeeeeeeeellllllllllllllseeeeeeeeeeeeeeeee")
				$.ajax({
					url: base_url+'.php/dashboard/docs_count',
					type: 'POST',
					
					success: function (data)
					{
						var data_obj=$.parseJSON(data);
						//console.log('successssssssssssssssssssssssssssssss', data_obj);						
						doc_cnt=data_obj.total_docs;
						//doc_cnt=data_obj.time;
						$.each(data_obj, function (i, obj) 
						{
							var dc_cnt=obj.total_docs	
							//console.log('successssssssssssssssssssssssssssssss',dc_cnt);
							y=dc_cnt;
							document_count=dc_cnt-previous_count;
							//console.log('successssssssssssssssssssssssssssssss',document_count);
							if (document_count < 0)
							document_count = 0;
							if (document_count > 100)
							document_count = 100;
							data_cnt.push(document_count);
							previous_count=dc_cnt;
							return false;
							
						})
												
					},
					error: function (XMLHttpRequest, textStatus, errorThrown)
					{
					console.log('error', errorThrown);
					}
				})
			}

			// zip the generated y values with the x values
			var res = [];
			for (var i = 0; i < data_cnt.length; ++i)
				res.push([i, data_cnt[i]])
				//console.log("ressssssssssssssssssss");
				//console.log([i, data_cnt[i]]);
				//console.log(res);
			return res;
		}

		// setup control widget
		var updateInterval = 1000;
		$("#updating-chart").val(updateInterval).change(function() {

			var v = $(this).val();
			console.log("vvvv");
			console.log(v)
			if (v && !isNaN(+v)) {
				//console.log(v)
				updateInterval = +v;
				//console.log(v)
				//console.log(updateInterval)								
				$(this).val("" + updateInterval);
			}

		});

		// setup plot
		var options = {
			yaxis : {
				min : 0,
				max : 100
			},
			xaxis : {
				min : 0,
				max : 100
			},
			colors : [$UpdatingChartColors],
			series : {
				lines : {
					lineWidth : 1,
					fill : true,
					fillColor : {
						colors : [{
							opacity : 0.4
						}, {
							opacity : 0
						}]
					},
					steps : false

				}
			}
		};

		var plot = $.plot($("#updating-chart"), [getRandomData()], options);
		/* live switch */
		$('input[type="checkbox"]#start_interval').click(function() {
			if ($(this).prop('checked')) {
				$on = true;
				updateInterval = 1500;
				update();
			} else {
				clearInterval(updateInterval);
				$on = false;
			}
		});

		function update() {
			if ($on == true) {
				plot.setData([getRandomData()]);
				plot.draw();
				setTimeout(update, updateInterval);

			} else {
				clearInterval(updateInterval)
			}

		}

		var $on = false;

		/*end updating chart*/

	});