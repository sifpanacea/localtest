<?php $current_page = ""; ?>
<?php $main_nav = ""; ?>
<?php include("inc/header_bar.php"); ?>
<?php include('inc/sidebar.php');?>

<style type="text/css">
	.highcharts-figure, .highcharts-data-table table {
	    min-width: 360px; 
	    max-width: 800px;
	    margin: 1em auto;
	}

	.highcharts-data-table table {
		font-family: Verdana, sans-serif;
		border-collapse: collapse;
		border: 1px solid #EBEBEB;
		margin: 10px auto;
		text-align: center;
		width: 100%;
		max-width: 500px;
	}
	.highcharts-data-table caption {
	    padding: 1em 0;
	    font-size: 1.2em;
	    color: #555;
	}
	.highcharts-data-table th {
		font-weight: 600;
	    padding: 0.5em;
	}
	.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
	    padding: 0.5em;
	}
	.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
	    background: #f8f8f8;
	}
	.highcharts-data-table tr:hover {
	    background: #f1f7ff;
	}
</style>

<section class="content">
	<div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
				<div class="header">
					<h2>Organisational Info</h2>
					<ul class="header-dropdown m-r--5">
                        <div class="button-demo">
                        	<button type="button" class="btn bg-pink waves-effect" onclick="window.history.back();">Back</button>
                        </div>
                    </ul>
				</div>
				<div class="body">
					<div class="row">
						<div class="col-md-6" style="overflow: auto;height: 400px;">
							<h5 class="font-bold col-blue">Website Link :</h5>
							<h5 class="font-bold col-blue">Scocial Media Link :</h5>
							<h5 class="font-bold col-blue">Phone Number :</h5>
							<h5 class="font-bold col-blue">Organisational Details :</h5>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras ultrices lorem lorem, a facilisis urna semper in. Quisque vitae ante varius tellus bibendum aliquet. Phasellus quis sodales orci, nec mollis risus. Nullam neque mi, placerat vitae odio eget, consequat congue nisl. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla ac interdum dui, sit amet malesuada tellus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.

							Ut ligula eros, porttitor feugiat orci id, convallis pulvinar libero. Etiam quis maximus nisl, quis lacinia leo. Cras posuere risus a lectus consectetur posuere. In tincidunt ut ex ac feugiat. Aliquam elementum arcu a mi porttitor condimentum. Nunc finibus tortor nec arcu tempor hendrerit. Proin pulvinar, purus vitae posuere ullamcorper, enim est bibendum elit, non tristique libero mi eu urna. Duis tincidunt dui magna. Nam id egestas est, sed ultricies odio. Integer id dui dictum, aliquam dui eget, gravida metus. Fusce vel bibendum sem.

							Vestibulum luctus, purus eget scelerisque ullamcorper, massa magna pharetra nulla, vitae faucibus sem neque vitae neque. In non leo non risus volutpat hendrerit. Maecenas lacus ligula, dapibus ut nisl nec, interdum condimentum velit. Vivamus sed est malesuada, euismod justo vitae, pellentesque erat. Suspendisse ut velit euismod, aliquet nunc semper, convallis est. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec convallis blandit nisl sed sagittis. Aenean dui urna, faucibus vel auctor sit amet, pretium id ipsum. Nullam congue nulla quis sapien aliquam, id interdum erat pulvinar. Aliquam erat volutpat. Sed gravida convallis venenatis. Suspendisse mauris purus, porta ut justo quis, eleifend convallis leo.

							Vestibulum tristique, justo sed porttitor hendrerit, justo ipsum imperdiet diam, id efficitur arcu ex eu eros. Phasellus tempor quam eget dolor luctus varius a ac ipsum. Sed tincidunt tempor efficitur. Quisque tellus ipsum, blandit nec tellus ac, ullamcorper maximus eros. Proin enim orci, viverra sed massa vel, commodo cursus nisi. Phasellus nec enim mattis, varius massa sit amet, eleifend mi. Nunc laoreet orci et justo pellentesque vulputate. Ut suscipit dignissim nisl vel vestibulum. Sed egestas, sem quis vestibulum hendrerit, erat nulla congue libero, vitae viverra mi erat vel massa. Duis sed nunc tempus, dapibus metus vitae, pharetra mi. Suspendisse sit amet metus leo. In sit amet eleifend lectus, sit amet hendrerit lorem. Nulla sed tortor non metus imperdiet egestas eget viverra urna. Vivamus id varius ante. Duis vulputate metus nec arcu aliquet mollis vehicula vitae lacus. Praesent facilisis rutrum nulla vel placerat.

							Vestibulum tristique, justo sed porttitor hendrerit, justo ipsum imperdiet diam, id efficitur arcu ex eu eros. Phasellus tempor quam eget dolor luctus varius a ac ipsum. Sed tincidunt tempor efficitur. Quisque tellus ipsum, blandit nec tellus ac, ullamcorper maximus eros. Proin enim orci, viverra sed massa vel, commodo cursus nisi. Phasellus nec enim mattis, varius massa sit amet, eleifend mi. Nunc laoreet orci et justo pellentesque vulputate. Ut suscipit dignissim nisl vel vestibulum. Sed egestas, sem quis vestibulum hendrerit, erat nulla congue libero, vitae viverra mi erat vel massa. Duis sed nunc tempus, dapibus metus vitae, pharetra mi. Suspendisse sit amet metus leo. In sit amet eleifend lectus, sit amet hendrerit lorem. Nulla sed tortor non metus imperdiet egestas eget viverra urna. Vivamus id varius ante. Duis vulputate metus nec arcu aliquet mollis vehicula vitae lacus. Praesent facilisis rutrum nulla vel placerat.
						</div>
						<div class="col-md-6">
							<figure class="highcharts-figure">
							    <div id="container"></div>
							    <p class="highcharts-description">
							        Basic line chart showing trends in a dataset.
							    </p>
							</figure>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php include('inc/footer_bar.php');?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script type="text/javascript">
	Highcharts.chart('container', {

	    title: {
	        text: 'Organisation Search Counts'
	    },

	    subtitle: {
	        text: 'Source: Power Of Ten'
	    },

	    yAxis: {
	        title: {
	            text: 'Number of Searches'
	        }
	    },

	    xAxis: {
	        accessibility: {
	            rangeDescription: 'Range: Jan to Dec'
	        }
	    },

	    legend: {
	        layout: 'vertical',
	        align: 'right',
	        verticalAlign: 'middle'
	    },

	    plotOptions: {
	        series: {
	            label: {
	                connectorAllowed: false
	            },
	            pointStart: 2010
	        }
	    },

	    series: [{
	        name: 'Installation',
	        data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
	    }],

	    responsive: {
	        rules: [{
	            condition: {
	                maxWidth: 500
	            },
	            chartOptions: {
	                legend: {
	                    layout: 'horizontal',
	                    align: 'center',
	                    verticalAlign: 'bottom'
	                }
	            }
	        }]
	    }

	});
</script>