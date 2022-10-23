<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Show BMI";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa feed_bmi"]["sub"]["show_bmi_graph"]["active"] = true;
include("inc/nav.php");

?>
<style>
.unique_id
{
	width:85px;	
	margin-top: 15px;
	font-size:15px;
	font-family:Segoe UI;
	color:black;
}
.student_code
{
	width: 140px;
    margin-right: 10px;
    border-color: white;
    border-style: solid;
    border-bottom-color: #333;
}
.text_input
{
font-family:Segoe UI;
color:black;
font-size:12px;
width:70px;
height:15px;
}
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Reports"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-colorbutton="true" data-widget-editbutton="true" data-widget-custombutton="true">


<!-- widget div-->
<div>

	<!-- widget edit box -->
	<div class="jarviswidget-editbox">
		<!-- This area used as dropdown edit box -->

	</div>
	<!-- end widget edit box -->

	<!-- widget content -->
	
	<div class="widget-body no-padding">
	
		<div class="smart-form">
			<header>
				Monthly Student BMI Graph.
			</header>
			<fieldset>
			<div class="row">
			<section class="col col-6">
				<label class="label" for="first_name">Hospital Unique ID</label>
				<?php if(isset($district_code) && isset($school_code)):?>
				   <label class='labelform unique_id'><?php echo $district_code."_".$school_code."_";?></label>
				   <input class='text_input student_code' id='student_code' type='text' name='student_code' minlength='3' maxlength='25' required/>
				   <input class='hide' id='student_unique_id' type='text' name='student_unique_id'/>
				   <?php else : ?>
				   <label class="input"> <i class="icon-append fa fa-pencil"></i>
					<input type="text" name="uid" id="uid" value="<?PHP echo set_value('uid'); ?>" required>
					<?php endIf; ?>
				</label>
			</section>
			</div>
			
			</fieldset>
			<footer>
				<button type="submit" class="btn bg-color-blue txt-color-white submit" >
					Search
				</button>
				<button type="reset" class="btn btn-default clear_id">
					Clear
				</button>
			</footer>
		</div>

	</div>
	<!-- end widget content -->
	<div class="row">
	<div class="col-lg-9">
	<h2>Student BMI Graph</h2>
	<div class="line_graph" id="line_graph" style="width:700px;height:300px;">
	</div>
	<div id="legend"></div>
	</div>
	<div class="col-lg-3 bmi_tip hide">
	<h5>Reference by</h5>
	<span>
	<img src="../../uploaddir/public/images.png" alt="" style="height:80px;margin-top:6px;margin-left:6px;">
	 </span>
	 <h5>BMI Interpretation</h5>
	 <span>
	<img src="../../uploaddir/public/bmi_range.jpg" alt="" style="width:250px;height:120px;margin-top:6px;margin-left:6px;">
	 </span>
	<h6><span>Source : <a href="http://apps.who.int/bmi/index.jsp?introPage=intro_3.html" target="_blank">World Health Organisation</a></span></h6>
	</div>
	</div>
	
</div>
<!-- end widget div -->

</div>
</article>

</div><!-- ROW -->
	

				

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

<script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.barnumbers.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.orderBar.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.axislabels.js"></script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
$(document).ready(function(){
	var month_data = "";
		$(".submit").click(function(){
			
	   var query_ref_label = $('.unique_id').text() || '';
		var query_ref_input = $('.student_code').val() || '';
		var query_ref = ""+query_ref_label+""+query_ref_input+"";
		console.log(query_ref);
		graph_values = [];
			$.ajax({
				url  : "student_bmi_graph",
			    type : "POST",
				data : { 'unique_id': query_ref },
				success : function(response){
					if(response=='NO_GRAPH')
					{
						
				$.SmartMessageBox({
					title : "Smart Alert!",
					content : "For This Unique ID No BMI Graph",
					buttons : '[OK]'
				})
						$('#line_graph,#legend').empty();
						$('.bmi_tip').addClass('hide');
						
					}
					else
					{
				try
				{
					var line_chart_data = JSON.parse(response);
					var graph_data = line_chart_data.graph_data;
					month_data = line_chart_data.month_data;
					console.log("GD==",graph_data);
					console.log("MD==",month_data);
					var obj = {'label':'BMI Graph','data':graph_data};
					graph_values.push(obj);
					
		          
					$.plot($("#line_graph"), graph_values, {
					series: 
					{
					lines : {show: true},
					points: {show: true}
					},
					xaxis : {
					mode: 'time',
					tickSize: [1, "month"],
					timeformat:"%b %y",
					axisLabel: "Months",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					axisLabelPadding: 20
					},
					grid: 
					{
					borderColor: 'black',
					borderWidth: 1
					},
					legend: 
					{
					show: true,
					container: '#legend'    
					},
					grid: { hoverable: true, clickable: true },
					yaxis : {
					min: 0,
					max: 50,
					tickSize:5,
					axisLabel:"BMI",
					axisLabelUseCanvas: true,
					axisLabelFontSizePixels: 12,
					axisLabelFontFamily: 'Verdana, Arial',
					axisLabelPadding: 10
					}
					});
					line_graph_plothover();
					
					$('.bmi_tip').removeClass('hide');
				
       
 
       
				}
				catch(e)
				{
				
				} 
					}
					
				}
				
			})
			
			
		})
		
		 function showTooltip(x, y, color, contents) {
            $('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y - 40,
                left: x - 120,
                border: '2px solid ' + color,
                padding: '3px',
                'font-size': '9px',
                'border-radius': '5px',
                'background-color': '#fff',
                'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                opacity: 0.9
            }).appendTo("body").fadeIn(200);
        }
		
		function line_graph_plothover(){
			
			var previousPoint = null, previousLabel = null;
			
		$("#line_graph").bind("plothover", function (event, pos, item) {
				console.log("ITEM==",item);
                if (item) {
					
                    if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                        previousPoint = item.dataIndex;
                        previousLabel = item.series.label;
                        $("#tooltip").remove();
 
                        var x = item.datapoint[0];
                        var y = item.datapoint[1];
 
                        var color = item.series.color;
                       
						showTooltip(item.pageX,
                            item.pageY,
                            color,
                            "<strong>Height : </strong>" + month_data[0][x]['height'] + " cm<br><strong>Weight : </strong>" + month_data[0][x]['weight'] + " kg<br><strong>BMI : </strong>" + y + "");
                        
                    }
                } else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            });
		}

		$('.clear_id').click(function(){
			$('#student_code').val("");
		});
		
})	
</script>