<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Panacea Dashboard";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa home"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<style>
#searchquery
{

}
header: {
  title: {
    text:     "",
    color:    "#333333",
    fontSize: 18,
    font:     "arial"
  },
  subtitle: {
    text:     "",
    color:    "#666666",
    fontSize: 14,
    font:     "arial"
  },
  location: "top-center",
  titleSubtitlePadding: 8
},
footer: {
  text:     "",
  color:    "#666666",
  fontSize: 14,
  font:     "arial",
  location: "left"
},
size: {
  canvasHeight: 500,
  canvasWidth: 500,
  pieInnerRadius: "0%",
  pieOuterRadius: null
},
data: {
  sortOrder: "none",
  ignoreSmallSegments: {
    enabled: false,
    valueType: "percentage",
    value: null
  },
  smallSegmentGrouping: {
    enabled: false,
    value: 1,
    valueType: "percentage",
    label: "Other",
    color: "#cccccc"
  },
  content: []
},
labels: {
  outer: {
    format: "label",
    hideWhenLessThanPercentage: null,
    pieDistance: 30
  },
  inner: {
    format: "percentage",
    hideWhenLessThanPercentage: null
  },
  mainLabel: {
    color: "#333333",
    font: "arial",
    fontSize: 10
  },
  percentage: {
    color: "#dddddd",
    font: "arial",
    fontSize: 10,
    decimalPlaces: 0
  },
  value: {
    color: "#cccc44",
    font: "arial",
    fontSize: 10
  },
  lines: {
    enabled: true,
    style: "curved",
    color: "segment"
  },
  truncation: {
    enabled: false,
    length: 30
  }
},
effects: {
  load: {
    effect: "default",
    speed: 1000
  },
  pullOutSegmentOnClick: {
    effect: "bounce",
    speed: 300,
    size: 10
  },
  highlightSegmentOnMouseover: true,
  highlightLuminosity: -0.2
},
tooltips: {
  enabled: false,
  type: "placeholder", // caption|placeholder
  string: "",
  placeholderParser: null,
  styles: {
    fadeInSpeed: 250,
    backgroundColor: "#000000",
    backgroundOpacity: 0.5,
    color: "#efefef",
    borderRadius: 2,
    font: "arial",
    fontSize: 10,
    padding: 4
  }
},
misc: {
  colors: {
    background: null,
    segments: [
      "#2484c1", "#65a620", "#7b6888", "#a05d56", "#961a1a", "#d8d23a", "#e98125", "#d0743c", "#635222", "#6ada6a",
      "#0c6197", "#7d9058", "#207f33", "#44b9b0", "#bca44a", "#e4a14b", "#a3acb2", "#8cc3e9", "#69a6f9", "#5b388f",
      "#546e91", "#8bde95", "#d2ab58", "#273c71", "#98bf6e", "#4daa4b", "#98abc5", "#cc1010", "#31383b", "#006391",
      "#c2643f", "#b0a474", "#a5a39c", "#a9c2bc", "#22af8c", "#7fcecf", "#987ac6", "#3d3b87", "#b77b1c", "#c9c2b6",
      "#807ece", "#8db27c", "#be66a2", "#9ed3c6", "#00644b", "#005064", "#77979f", "#77e079", "#9c73ab", "#1f79a7"
    ],
    segmentStroke: "#ffffff"
  },
  gradient: {
    enabled: false,
    percentage: 95,
    color: "#000000"
  },
  canvasPadding: {
    top: 5,
    right: 5,
    bottom: 5,
    left: 5
  },
  pieCenterOffset: {
    x: 0,
    y: 0
  },
  cssPrefix: null
},
callbacks: {
  onload: null,
  onMouseoverSegment: null,
  onMouseoutSegment: null,
  onClickSegment: null
}


</style>
<script src="<?php echo JS; ?>/d3pie/d3.js"></script>
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>
	<!-- MAIN CONTENT -->
	<div id="content">
		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> <?php echo lang('admin_dash_home');?> <span>> <?php echo lang('admin_dash_board');?></span></h1>
			</div>
			
		</div>
		
		<!-- widget grid -->
		<section id="widget-grid" class="">
		<div class="row">
				<article class="col-sm-6">
					<!-- new widget -->
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="wid-id-60" data-widget-editbutton="false">
						<!-- widget options:
						usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

						data-widget-colorbutton="false"
						data-widget-editbutton="false"
						data-widget-togglebutton="false"
						data-widget-deletebutton="false"
						data-widget-fullscreenbutton="false"
						data-widget-custombutton="false"
						data-widget-collapsed="true"
						data-widget-sortable="false"

						-->
						<header>
							<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
							<h2>Today's Absent Report</h2>

						</header>

						<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->

							</div>
							<!-- end widget edit box -->

							<!-- widget content -->
							<div class="widget-body no-padding">
								<div class="well well-sm well-light">
								<div id="pie_absent"></div>
								<form style="display: hidden" action="drill_down_absent_to_students_load_ehr" method="GET" id="ehr_form_for_absent">
									<input type="hidden" id="ehr_data_for_absent" name="ehr_data_for_absent" value=""/>
								</form>
								</div>
							</div>
							<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->
					<!-- end widget -->
					</article>
					
					
					<article class="col-sm-6">
					<!-- new widget -->
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="wid-id-61" data-widget-editbutton="false">
						<!-- widget options:
						usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

						data-widget-colorbutton="false"
						data-widget-editbutton="false"
						data-widget-togglebutton="false"
						data-widget-deletebutton="false"
						data-widget-fullscreenbutton="false"
						data-widget-custombutton="false"
						data-widget-collapsed="true"
						data-widget-sortable="false"

						-->
						<header>
							<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
							<h2>Screening Follow Up Pie Chart</h2>

						</header>

						<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->

							</div>
							<!-- end widget edit box -->

							<!-- widget content -->
							<div class="widget-body no-padding">
								<div class="well well-sm well-light">
								<div id="pie_absent"></div>
								<form style="display: hidden" action="drill_down_absent_to_students_load_ehr" method="GET" id="ehr_form_for_absent">
									<input type="hidden" id="ehr_data_for_absent" name="ehr_data_for_absent" value=""/>
								</form>
								</div>
							</div>
							<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->
					<!-- end widget -->
					</article>
					
                 </div>
				
				
				
			<!-- row -->
			
			
			
			<div class="row">
				<article class="col-sm-12">
					<!-- new widget -->
					<div class="jarviswidget" id="wid-id-100" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
						<!-- widget options:
						usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

						data-widget-colorbutton="false"
						data-widget-editbutton="false"
						data-widget-togglebutton="false"
						data-widget-deletebutton="false"
						data-widget-fullscreenbutton="false"
						data-widget-custombutton="false"
						data-widget-collapsed="true"
						data-widget-sortable="false"

						-->
						<header>
							<span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
							<h2>Request and Screening Pie Chats </h2>

						</header>

						<!-- widget div-->
						<div class="no-padding">
							<!-- widget edit box -->
							<!-- end widget edit box -->

							<div class="widget-body">
								<!-- content -->
								<div id="myTabContent" class="tab-content">
								<div class="row">
								<br>
								<div class="col-xs-12 col-sm-3 col-md-4 col-lg-4">
									<div class="well well-sm well-light">
										<br>
										<div >
											<div id="pie"></div>
											<form style="display: hidden" action="drill_down_identifiers_to_students_load_ehr" method="GET" id="ehr_form_for_identifiers">
												<input type="hidden" id="ehr_data_for_identifiers" name="ehr_data_for_identifiers" value=""/>
											</form>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-3 col-md-4 col-lg-4">
									<div class="well well-sm well-light">
										<br>
										<div >
											<div id="pie_request"></div>
											<form style="display: hidden" action="drill_down_request_to_students_load_ehr" method="GET" id="ehr_form_for_request">
												<input type="hidden" id="ehr_data_for_request" name="ehr_data_for_request" value=""/>
											</form>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-3 col-md-4 col-lg-4">
									<div class="well well-sm well-light">
										<br>
										<div >
											<div id="pie_screening"></div>
											<form style="display: hidden" action="drill_down_screening_to_students_load_ehr" method="POST" id="ehr_form">
											  <input type="hidden" id="ehr_data" name="ehr_data" value=""/>
											</form>
										</div>
									</div>
								</div>
								
								
								
							</div>
								<!-- end content -->
							</div>

						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->
                 </div>
				</article>
				
			<!-- row -->

			<!-- end row -->
				</div>
			</div><!-- end row -->
			</section>
	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
			

<!-- ==========================CONTENT ENDS HERE ========================== -->
<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



<!-- Vector Maps Plugin: Vectormap engine, Vectormap language -->

<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>



<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
$(document).ready(function() {

	<?php if($message) { ?>
$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
				content : "<?php echo $message?>",
				color : "#296191",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
<?php } ?>
});

	var screening_data = <?php echo $screening_report?>;
	previous_screening_a_value = [];
	previous_screening_title_value = [];
	previous_screening_search = [];
	search_arr = [];

	var absent_data = <?php echo $absent_report?>;
	var today_date = new Date().toJSON().slice(0,10);
	previous_absent_a_value = [];
	previous_absent_title_value = [];
	previous_absent_search = [];
	absent_search_arr = [];

	var request_data = <?php echo $request_report?>;
	previous_request_a_value = [];
	previous_request_title_value = [];
	previous_request_search = [];
	search_arr = [];

	var identifiers_data = <?php echo $symptoms_report?>;
	previous_identifiers_a_value = [];
	previous_identifiers_title_value = [];
	previous_identifiers_search = [];
	search_arr = [];

	if(absent_data == 1){
		console.log('in false of abbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
		$("#pie_absent").append('No positive values to dispaly');
	}else{
	var pie = new d3pie("pie_absent", {
	header: {
		title: {
			text: today_date
		}
	},
	size: {
        canvasHeight: 300,
        canvasWidth: 600
    },
    data: {
      content: absent_data
    },
    tooltips: {
        enabled: true,
        type: "placeholder",
        string: "{label}, {value}, {percentage}%"
     },
     callbacks: {
			onClickSegment: function(a) {
				//alert("Segment clicked! See the console for all data passed to the click handler.");
				console.log(a);
				previous_absent_a_value[1] = absent_data;
				previous_absent_title_value[1] = today_date;
				console.log(previous_absent_a_value);
				drill_down_absent_to_districts(a);
			}
		}
      
	});
}

function drill_down_absent_to_districts(pie_data){
	console.log("asdsfdsfdsvsdfavfdbfdbfbfdbfdbv f                fdbfdbfdv");
	console.log(pie_data);

	$.ajax({
		url: 'drilldown_absent_to_districts',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data)},
		success: function (data) {
			console.log(data);
			var contant = $.parseJSON(data);
			console.log(contant);
			$( "#pie_absent" ).empty();
			$("#pie_absent").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_absent_back(1);"> Back </button>');
			var pie = new d3pie("pie_absent", {
				header: {
					title: {
						text: pie_data.data.label
					}
				},
				size: {
			        canvasHeight: 300,
			        canvasWidth: 600
			    },
			    data: {
			      content: contant
			    },
			    tooltips: {
			        enabled: true,
			        type: "placeholder",
			        string: "{label}, {value}, {percentage}%"
			     },
			     callbacks: {
						onClickSegment: function(a) {
							//alert("Segment clicked! See the console for all data passed to the click handler.");
							console.log(a);
							previous_absent_a_value[2] = contant;
							previous_absent_title_value[2] = pie_data.data.label;
							previous_absent_search[2] = pie_data.data.label;
							console.log(previous_absent_a_value);
							search_arr[0] = previous_absent_search[2];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_absent_to_schools(search_arr);

							
						}
					}
			      
			});

			
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

function drill_down_absent_to_schools(pie_data){
	console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaschooooooooooooooooooooooooooooooooool");
	console.log(pie_data);
	$.ajax({
		url: 'drilling_absent_to_schools',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data)},
		success: function (data) {
			console.log(data);
			var contant = $.parseJSON(data);
			console.log(contant);
			$( "#pie_absent" ).empty();
			$("#pie_absent").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_absent_back(2);"> Back </button>');
			var pie = new d3pie("pie_absent", {
				header: {
					title: {
						text: pie_data[1]
					}
				},
				size: {
			        canvasHeight: 300,
			        canvasWidth: 600
			    },
			    data: {
			      content: contant
			    },
			    tooltips: {
			        enabled: true,
			        type: "placeholder",
			        string: "{label}, {value}, {percentage}%"
			     },
			     callbacks: {
						onClickSegment: function(a) {
							//alert("Segment clicked! See the console for all data passed to the click handler.");
							
							search_arr[0] = previous_absent_search[2];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_absent_to_students(search_arr);
						}
					}
			      
			});
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

function drill_down_absent_to_students(pie_data){

	$.ajax({
		url: 'drill_down_absent_to_students',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data)},
		success: function (data) {
			console.log(data);
			$("#ehr_data_for_absent").val(data);
			//window.location = "drill_down_screening_to_students_load_ehr/"+data;
			//alert(data);
			
			$("#ehr_form_for_absent").submit();
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

function pie_absent_back(index){
	console.log("in back functionnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn-----------");
	console.log(index);
	$( "#pie_absent" ).empty();
	if(index>1){
		var ind = index - 1;
	$("#pie_absent").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_absent_back(' + ind + ');"> Back </button>');
	}
	var pie = new d3pie("pie_absent", {
		header: {
			title: {
				text: previous_absent_title_value[index]
			}
		},
		size: {
	        canvasHeight: 300,
	        canvasWidth: 600
	    },
	    data: {
	      content: previous_absent_a_value[index]
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}, {percentage}%"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					//alert("Segment clicked! See the console for all data passed to the click handler.");
					console.log(a);
					//previous_screening_a_value[index] = previous_screening_a_value[index];
					//previous_screening_title_value[index] = previous_screening_title_value[index];
					//previous_screening_search[index] = previous_screening_title_value[index];
					console.log("value from previous function -------------------------------------------");
					//console.log(previous_screening_a_value);

					if (index == 1){
						drill_down_absent_to_districts(a);
					}else if (index == 2){
						search_arr[0] = previous_absent_search[2];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						drill_down_absent_to_schools(search_arr);
					}

					
					
				}
			}
	      
	});
}

	/* end pie chart */
if(identifiers_data == 1){
		console.log('in false of abbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
		$("#pie").append('No positive values to dispaly');
	}else{
	var pie = new d3pie("pie", {
		header: {
			title: {
				text: "Identifiers Pie Chart"
			}
		},
		size: {
	        canvasHeight: 300,
	        canvasWidth: 400
	    },
	    data: {
	      content: identifiers_data
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}, {percentage}%"
	     },

	     callbacks: {
				onClickSegment: function(a) {
					//alert("Segment clicked! See the console for all data passed to the click handler.");
					console.log(a);
					previous_identifiers_a_value[1] = identifiers_data;
					previous_identifiers_title_value[1] = "Identifiers Pie Chart";
					console.log(previous_identifiers_a_value);
					drill_down_identifiers_to_districts(a);
				}
			}
	      
	});
}

	function drill_down_identifiers_to_districts(pie_data){
		console.log("asdsfdsfdsvsdfavfdbfdbfbfdbfdbv f                fdbfdbfdv");
		console.log(pie_data);

		$.ajax({
			url: 'drilldown_identifiers_to_districts',
			type: 'POST',
			data: {"data" : JSON.stringify(pie_data.data)},
			success: function (data) {
				console.log(data);
				var contant = $.parseJSON(data);
				console.log(contant);
				$( "#pie" ).empty();
				$("#pie").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_identifiers_back(1);"> Back </button>');
				var pie = new d3pie("pie", {
					header: {
						title: {
							text: pie_data.data.label
						}
					},
					size: {
				        canvasHeight: 300,
				        canvasWidth: 400
				    },
				    data: {
				      content: contant
				    },
				    tooltips: {
				        enabled: true,
				        type: "placeholder",
				        string: "{label}, {value}, {percentage}%"
				     },
				     callbacks: {
							onClickSegment: function(a) {
								//alert("Segment clicked! See the console for all data passed to the click handler.");
								console.log(a);
								previous_identifiers_a_value[2] = contant;
								previous_identifiers_title_value[2] = pie_data.data.label;
								previous_identifiers_search[2] = pie_data.data.label;
								console.log(previous_identifiers_a_value);
								search_arr[0] = previous_identifiers_search[2];
								search_arr[1] =  a.data.label;
								console.log(search_arr);
								drill_down_identifiers_to_schools(search_arr);

								
							}
						}
				      
				});

				
				
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
	}

	function drill_down_identifiers_to_schools(pie_data){
		console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaschooooooooooooooooooooooooooooooooool");
		console.log(pie_data);
		$.ajax({
			url: 'drilling_identifiers_to_schools',
			type: 'POST',
			data: {"data" : JSON.stringify(pie_data)},
			success: function (data) {
				console.log(data);
				var contant = $.parseJSON(data);
				console.log(contant);
				$( "#pie" ).empty();
				$("#pie").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_identifiers_back(2);"> Back </button>');
				var pie = new d3pie("pie", {
					header: {
						title: {
							text: pie_data[1]
						}
					},
					size: {
				        canvasHeight: 300,
				        canvasWidth: 400
				    },
				    data: {
				      content: contant
				    },
				    tooltips: {
				        enabled: true,
				        type: "placeholder",
				        string: "{label}, {value}, {percentage}%"
				     },
				     callbacks: {
							onClickSegment: function(a) {
								//alert("Segment clicked! See the console for all data passed to the click handler.");
								
								search_arr[0] = previous_identifiers_search[2];
								search_arr[1] =  a.data.label;
								console.log(search_arr);
								drill_down_identifiers_to_students(search_arr);
							}
						}
				      
				});
				
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
	}

	function drill_down_identifiers_to_students(pie_data){

		$.ajax({
			url: 'drill_down_identifiers_to_students',
			type: 'POST',
			data: {"data" : JSON.stringify(pie_data)},
			success: function (data) {
				console.log(data);
				$("#ehr_data_for_identifiers").val(data);
				//window.location = "drill_down_screening_to_students_load_ehr/"+data;
				//alert(data);
				
				$("#ehr_form_for_identifiers").submit();
				
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
	}

	function pie_identifiers_back(index){
		console.log("in back functionnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn-----------");
		console.log(index);
		$( "#pie" ).empty();
		if(index>1){
			var ind = index - 1;
		$("#pie").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_identifiers_back(' + ind + ');"> Back </button>');
		}
		var pie = new d3pie("pie", {
			header: {
				title: {
					text: previous_identifiers_title_value[index]
				}
			},
			size: {
		        canvasHeight: 300,
		        canvasWidth: 400
		    },
		    data: {
		      content: previous_identifiers_a_value[index]
		    },
		    tooltips: {
		        enabled: true,
		        type: "placeholder",
		        string: "{label}, {value}, {percentage}%"
		     },
		     callbacks: {
					onClickSegment: function(a) {
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						console.log(a);
						//previous_screening_a_value[index] = previous_screening_a_value[index];
						//previous_screening_title_value[index] = previous_screening_title_value[index];
						//previous_screening_search[index] = previous_screening_title_value[index];
						console.log("value from previous function -------------------------------------------");
						//console.log(previous_screening_a_value);

						if (index == 1){
							drill_down_identifiers_to_districts(a);
						}else if (index == 2){
							search_arr[0] = previous_identifiers_search[2];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_identifiers_to_schools(search_arr);
						}

						
						
					}
				}
		      
		});
	}

	if(request_data == 1){
		console.log('in false of abbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
		$("#pie_request").append('No positive values to dispaly');
	}else{
	var pie = new d3pie("pie_request", {
		header: {
			title: {
				text: "Request Pie Chart"
			}
		},
		size: {
	        canvasHeight: 300,
	        canvasWidth: 400
	    },
	    data: {
	      content: request_data
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}, {percentage}%"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					//alert("Segment clicked! See the console for all data passed to the click handler.");
					console.log(a);
					previous_request_a_value[1] = request_data;
					previous_request_title_value[1] = "Request Pie Chart";
					console.log(previous_request_a_value);
					drill_down_request_to_districts(a);
				}
			}
	      
	});
}

	function drill_down_request_to_districts(pie_data){
		console.log("asdsfdsfdsvsdfavfdbfdbfbfdbfdbv f                fdbfdbfdv");
		console.log(pie_data);

		$.ajax({
			url: 'drilldown_request_to_districts',
			type: 'POST',
			data: {"data" : JSON.stringify(pie_data.data)},
			success: function (data) {
				console.log(data);
				var contant = $.parseJSON(data);
				console.log(contant);
				$( "#pie_request" ).empty();
				$("#pie_request").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_request_back(1);"> Back </button>');
				var pie = new d3pie("pie_request", {
					header: {
						title: {
							text: pie_data.data.label
						}
					},
					size: {
				        canvasHeight: 300,
				        canvasWidth: 400
				    },
				    data: {
				      content: contant
				    },
				    tooltips: {
				        enabled: true,
				        type: "placeholder",
				        string: "{label}, {value}, {percentage}%"
				     },
				     callbacks: {
							onClickSegment: function(a) {
								//alert("Segment clicked! See the console for all data passed to the click handler.");
								console.log(a);
								previous_request_a_value[2] = contant;
								previous_request_title_value[2] = pie_data.data.label;
								previous_request_search[2] = pie_data.data.label;
								console.log(previous_request_a_value);
								search_arr[0] = previous_request_search[2];
								search_arr[1] =  a.data.label;
								console.log(search_arr);
								drill_down_request_to_schools(search_arr);

								
							}
						}
				      
				});

				
				
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
	}

	function drill_down_request_to_schools(pie_data){
		console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaschooooooooooooooooooooooooooooooooool");
		console.log(pie_data);
		$.ajax({
			url: 'drilling_request_to_schools',
			type: 'POST',
			data: {"data" : JSON.stringify(pie_data)},
			success: function (data) {
				console.log(data);
				var contant = $.parseJSON(data);
				console.log(contant);
				$( "#pie_request" ).empty();
				$("#pie_request").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_request_back(2);"> Back </button>');
				var pie = new d3pie("pie_request", {
					header: {
						title: {
							text: pie_data[1]
						}
					},
					size: {
				        canvasHeight: 300,
				        canvasWidth: 400
				    },
				    data: {
				      content: contant
				    },
				    tooltips: {
				        enabled: true,
				        type: "placeholder",
				        string: "{label}, {value}, {percentage}%"
				     },
				     callbacks: {
							onClickSegment: function(a) {
								//alert("Segment clicked! See the console for all data passed to the click handler.");
								
								search_arr[0] = previous_request_search[2];
								search_arr[1] =  a.data.label;
								console.log(search_arr);
								drill_down_request_to_students(search_arr);
							}
						}
				      
				});
				
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
	}

	function drill_down_request_to_students(pie_data){

		$.ajax({
			url: 'drill_down_request_to_students',
			type: 'POST',
			data: {"data" : JSON.stringify(pie_data)},
			success: function (data) {
				console.log(data);
				$("#ehr_data_for_request").val(data);
				//window.location = "drill_down_screening_to_students_load_ehr/"+data;
				//alert(data);
				
				$("#ehr_form_for_request").submit();
				
				},
			    error:function(XMLHttpRequest, textStatus, errorThrown)
				{
				 console.log('error', errorThrown);
			    }
			});
	}

	function pie_request_back(index){
		console.log("in back functionnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn-----------");
		console.log(index);
		$( "#pie_request" ).empty();
		if(index>1){
			var ind = index - 1;
		$("#pie_request").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_request_back(' + ind + ');"> Back </button>');
		}
		var pie = new d3pie("pie_request", {
			header: {
				title: {
					text: previous_request_title_value[index]
				}
			},
			size: {
		        canvasHeight: 300,
		        canvasWidth: 400
		    },
		    data: {
		      content: previous_request_a_value[index]
		    },
		    tooltips: {
		        enabled: true,
		        type: "placeholder",
		        string: "{label}, {value}, {percentage}%"
		     },
		     callbacks: {
					onClickSegment: function(a) {
						//alert("Segment clicked! See the console for all data passed to the click handler.");
						console.log(a);
						//previous_screening_a_value[index] = previous_screening_a_value[index];
						//previous_screening_title_value[index] = previous_screening_title_value[index];
						//previous_screening_search[index] = previous_screening_title_value[index];
						console.log("value from previous function -------------------------------------------");
						//console.log(previous_screening_a_value);

						if (index == 1){
							drill_down_request_to_districts(a);
						}else if (index == 2){
							search_arr[0] = previous_request_search[2];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_request_to_schools(search_arr);
						}

						
						
					}
				}
		      
		});
	}



	if(screening_data == 1){
		console.log('in false of abbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
		$("#pie_screening").append('No positive values to dispaly');
	}else{

	var pie = new d3pie("pie_screening", {
		header: {
			title: {
				text: "Screening Pie Chart"
			}
		},
		size: {
	        canvasHeight: 300,
	        canvasWidth: 400
	    },
	    data: {
	      content: screening_data
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}, {percentage}%"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					//alert("Segment clicked! See the console for all data passed to the click handler.");
					console.log(a);
					previous_screening_a_value[1] = screening_data;
					previous_screening_title_value[1] = "Screening Pie Chart";
					console.log(previous_screening_a_value);
					drill_down_screening_to_abnormalities(a);
				}
			}
	      
	});
}



function drill_down_screening_to_abnormalities(pie_data){
	console.log("in drill_down_screening_to_abnormalities-------------aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa-------------");
	$.ajax({
		url: 'drilling_screening_to_abnormalities',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data)},
		success: function (data) {
			console.log(data);
			var contant = $.parseJSON(data);
			console.log(contant);
			$( "#pie_screening" ).empty();
			$("#pie_screening").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_screening_back(1);"> Back </button>');
			var pie = new d3pie("pie_screening", {
				header: {
					title: {
						text: "Screening - "+pie_data.data.label
					}
				},
				size: {
			        canvasHeight: 300,
			        canvasWidth: 400
			    },
			    data: {
			      content: contant
			    },
			    tooltips: {
			        enabled: true,
			        type: "placeholder",
			        string: "{label}, {value}, {percentage}%"
			     },
			     callbacks: {
						onClickSegment: function(a) {
							//alert("Segment clicked! See the console for all data passed to the click handler.");
							console.log(a);
							previous_screening_a_value[2] = contant;
							previous_screening_title_value[2] = "Screening - "+pie_data.data.label;
							previous_screening_search[2] = pie_data.data.label;
							console.log(previous_screening_a_value);
							drill_down_screening_to_districts(a);
						}
					}
			});
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

function drill_down_screening_to_districts(pie_data){
	console.log("in drill_down_screening_to_districts----------------dddddddddddddddddddddddddddddddddddddddddddddd-----");
	console.log(pie_data);

	$.ajax({
		url: 'drilling_screening_to_districts',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data.data)},
		success: function (data) {
			console.log(data);
			var contant = $.parseJSON(data);
			console.log(contant);
			$( "#pie_screening" ).empty();
			$("#pie_screening").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_screening_back(2);"> Back </button>');
			var pie = new d3pie("pie_screening", {
				header: {
					title: {
						text: "Screening - "+pie_data.data.label
					}
				},
				size: {
			        canvasHeight: 300,
			        canvasWidth: 400
			    },
			    data: {
			      content: contant
			    },
			    tooltips: {
			        enabled: true,
			        type: "placeholder",
			        string: "{label}, {value}, {percentage}%"
			     },
			     callbacks: {
						onClickSegment: function(a) {
							//alert("Segment clicked! See the console for all data passed to the click handler.");
							console.log(a);
							previous_screening_a_value[3] = contant;
							previous_screening_title_value[3] = "Screening - "+pie_data.data.label;
							previous_screening_search[3] = pie_data.data.label;
							console.log(previous_screening_a_value);
							search_arr[0] = previous_screening_search[3];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_screening_to_schools(search_arr);

							
						}
					}
			      
			});

			
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

function drill_down_screening_to_schools(pie_data){
	console.log("in school pieeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee---------------------pie data");
	console.log(pie_data);
	$.ajax({
		url: 'drilling_screening_to_schools',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data)},
		success: function (data) {
			console.log(data);
			var contant = $.parseJSON(data);
			console.log(contant);
			$( "#pie_screening" ).empty();
			$("#pie_screening").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_screening_back(3);"> Back </button>');
			var pie = new d3pie("pie_screening", {
				header: {
					title: {
						text: "Screening - "+pie_data[1]
					}
				},
				size: {
			        canvasHeight: 300,
			        canvasWidth: 400
			    },
			    data: {
			      content: contant
			    },
			    tooltips: {
			        enabled: true,
			        type: "placeholder",
			        string: "{label}, {value}, {percentage}%"
			     },
			     callbacks: {
						onClickSegment: function(a) {
							//alert("Segment clicked! See the console for all data passed to the click handler.");
							
							search_arr[0] = previous_screening_search[3];
							search_arr[1] =  a.data.label;
							console.log(search_arr);
							drill_down_screening_to_students(search_arr);
						}
					}
			      
			});
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}

function pie_screening_back(index){
	console.log("in back functionnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnnn-----------");
	console.log(index);
	$( "#pie_screening" ).empty();
	if(index>1){
		var ind = index - 1;
	$("#pie_screening").append('<button class="btn btn-primary pull-right" id="btnExport" onclick="pie_screening_back(' + ind + ');"> Back </button>');
	}
	var pie = new d3pie("pie_screening", {
		header: {
			title: {
				text: previous_screening_title_value[index]
			}
		},
		size: {
	        canvasHeight: 300,
	        canvasWidth: 400
	    },
	    data: {
	      content: previous_screening_a_value[index]
	    },
	    tooltips: {
	        enabled: true,
	        type: "placeholder",
	        string: "{label}, {value}, {percentage}%"
	     },
	     callbacks: {
				onClickSegment: function(a) {
					//alert("Segment clicked! See the console for all data passed to the click handler.");
					console.log(a);
					//previous_screening_a_value[index] = previous_screening_a_value[index];
					//previous_screening_title_value[index] = previous_screening_title_value[index];
					//previous_screening_search[index] = previous_screening_title_value[index];
					console.log("value from previous function -------------------------------------------");
					//console.log(previous_screening_a_value);

					if(index == 1){
						drill_down_screening_to_abnormalities(a);
					}else if (index == 2){
						drill_down_screening_to_districts(a);
					}else if (index == 3){
						search_arr[0] = previous_screening_search[3];
						search_arr[1] =  a.data.label;
						console.log(search_arr);
						drill_down_screening_to_schools(search_arr);
					}

					
					
				}
			}
	      
	});
}

function drill_down_screening_to_students(pie_data){

	$.ajax({
		url: 'drill_down_screening_to_students',
		type: 'POST',
		data: {"data" : JSON.stringify(pie_data)},
		success: function (data) {
			console.log(data);
			$("#ehr_data").val(data);
			//window.location = "drill_down_screening_to_students_load_ehr/"+data;
			//alert(data);
			
			$("#ehr_form").submit();
			
			},
		    error:function(XMLHttpRequest, textStatus, errorThrown)
			{
			 console.log('error', errorThrown);
		    }
		});
}
			
		

//===================================drill down pie======================
//===================================end of dril down pie================
</script>

