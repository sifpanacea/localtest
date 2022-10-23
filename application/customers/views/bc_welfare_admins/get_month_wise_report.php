<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");



$page_title = "Request Pie";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "";
include("inc/header.php");
//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["pa request"]["active"] = true;
include("inc/nav.php");

?>
<style>
.modal-lg{
	width: 1300px;
}
span.one{
    color: rgb(34, 194, 34);border: 2px solid rgb(34, 194, 34);
    font-size: 20px;
}

li.active span.one{
    background: #fff !important;
    border: 2px solid #ddd;
    color: rgb(34, 194, 34);
}

span.two{
     color: rgb(34, 194, 34);border: 2px solid rgb(34, 194, 34);
    font-size: 20px;
}


li.active span.two{
    background: #fff !important;
    border: 2px solid #ddd;
    color: rgb(34, 194, 34);
}

/*#age{
	border-radius: 10px;
	background-color: #ff000f;
}*/
.nav-tabs{
	height: 72px;
}
.square{
	width:170px;
	height:30px;
	font-size: 15px;
}


 @keyframes blink{
	0%{opacity: 1;}
	75%{opacity: 1;}
	76%{ opacity: 0;}
	100%{opacity: 0;}
 }
</style>

<link href="<?php echo(CSS.'admin_dash_js.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo CSS; ?>marquee.css">
<link rel="stylesheet" href="<?php echo CSS; ?>example.css">
<link rel="stylesheet" href="<?php echo CSS; ?>AdminLTE.min.css">
	<link rel="stylesheet" href="<?php echo CSS; ?>AdminLTE.css">
<script src="<?php echo JS; ?>/d3pie/d3.js"></script>



<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
<?php
	
	include("inc/ribbon.php");
?>

<div class="tab-pane fade in active" id="home">
	<div id="content">	
		

    <div class="container col-md-12">

       <!--==================Analytics For Requst Pie Start================================ -->
    		 
				<div class="panel panel-primary">
           		<div class="panel-heading">Month wise request pies.
                <input type="text" id="month_date_btn" name="month_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="yy-mm-dd" value="<?php echo date('Y-m-d')?>">
       			<!-- <input type="text" id="age" name="age"/> -->
       			
           		</div>
           		<form class=smart-form>
    <!--<form class="smart-form">-->
      
    <fieldset>
      <div class="row">
      <section class="col col-4">
        <label class="label" for="first_name">District Name</label>
        <label class="select">
        <select id="select_dt_name" >
          <option value='All' >All</option>
          <?php if(isset($distslist)): ?>
            
            <?php foreach ($distslist as $dist):?>

            <option value='<?php echo $dist['_id']; ?>' ><?php echo ucfirst($dist['dt_name'])?></option>
            <?php endforeach;?>
            <?php else: ?>
            <option value="1"  disabled="">No district entered yet</option>
          <?php endif ?>
        </select> <i></i>
      </label>
      </section>
      <section class="col col-4">
        <label class="label" for="first_name">School Name</label>
        <label class="select">
        <select id="school_name" disabled=true>
            <option value="All" >All</option>
          
          
        </select> <i></i>
      </label>
      </section>
       <input type="hidden" name="school_code" id="school_code"><br>
      
      <label class="label"></label>
        <section class="col col-2">
          <button type="button" class="btn bg-color-pink txt-color-white btn-sm" id="set_button" data-toggle="modal" data-target="#load_waiting" data-backdrop="static" data-keyboard="false">
           Set
          </button>
        </section>
      </div>
                 
</fieldset>
</form>
 <div id="chartContainer"></div>
                    <form style="display: hidden" action="drill_down_chronic_student_list" method="POST" 
                    id="chronic_request_form">
                        <input type="hidden" id="chronic_symtom" name="chronic_symtom" value=""/>               
                    </form>

                      <form style="display: hidden" action="drill_down_emergency_student_list" method="POST" 
                    id="emergency_request_form">
                        <input type="hidden" id="emergency_symtom" name="emergency_symtom" value=""/>
                        
                      </form>
                   
                      <form style="display: hidden" action="drill_down_normal_student_list" method="POST" 
                    id="normal_request_form">
                        <input type="hidden" id="normal_symtom" name="normal_symtom" value=""/>
                        
                      </form>
                        


                        
				</div>
    		  </div>
    	<!--==================Analytics For Requst Pie  end=================================== -->

    	   </div>
		</div>
  	</div>
  </div>
  


<!-- ==========================CONTENT ENDS HERE ========================== -->




<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>
<script src="<?php echo JS; ?>/d3pie/d3pie.js"></script>
<script src="<?php echo JS; ?>jquery-ui.min - pie.js"></script>
 <script src="<?php echo JS; ?>flot/jquery.flot.cust.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.resize.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.tooltip.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.barnumbers.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.orderBar.js"></script>
<script src="<?php echo JS; ?>flot/jquery.flot.axislabels.js"></script>
<script src="<?php echo JS; ?>jquery.prettyPhoto.js"></script>
<script src="<?php echo JS; ?>jquery.bootstrap.newsbox.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo JS; ?>marquee.js" type="text/javascript" charset="utf-8"></script>

<script src="<?php echo JS; ?>admin_dash.js"></script>
<script src="<?php echo JS; ?>highcharts.js"></script>
<script src="<?php echo JS; ?>highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="<?php echo JS; ?>drilldown.js"></script>

<script type="text/javascript">
  
  $(document).ready(function(){
         $('.datepicker').datepicker({
            minDate: new Date(1900, 10 - 1, 25)
        });
      month_date = $("#month_date_btn").val();
      dt_name = $("#select_dt_name option:selected").text();
   school_name = $('#school_name').val();
   
        $.ajax({
            url:"monthly_request_report_chart",
            type:"POST",
            data:{"month_date": month_date,"school_name": school_name,"dt_name":dt_name},
            success:function(responce){
                var res = JSON.parse(responce);
                monthdata(res);
            },
            error:function(){

            }
        })
       });

</script> 
	<script type="text/javascript">

  $('#select_dt_name').change(function(e){
    dist = $('#select_dt_name').text();
    
    dt_name = $("#select_dt_name option:selected").text();
  
    //alert(dist);
    var options = $("#school_name");
    options.prop("disabled", true);

    options.append($("<option />").val("0").prop("disabled", true).prop("selected", true).text("Fetching schools list..."));
    $.ajax({
      url: 'get_schools',
      type: 'POST',
      data: {"dist_id" : dt_name},
      success: function (data) {      

        result = $.parseJSON(data);
        console.log(result)

        options.prop("disabled", false);
        options.empty();
        options.append($("<option />").val("All").prop("selected", true).text("All"));
        $.each(result, function() {
          options.append($("<option />").val(this.school_name).text(this.school_name));
        })    
        },
          error:function(XMLHttpRequest, textStatus, errorThrown)
        {
         console.log('error', errorThrown);
          }
      });
    
  });

   month_date = $("#month_date_btn").val();

      dt_name = $("#select_dt_name option:selected").text();

        
        $("#set_button").click(function(){
          school_name = $('#school_name option:selected').text();
          month_date = $("#month_date_btn").val();
          debugger;
         $('.datepicker').datepicker({
            minDate: new Date(1900, 10 - 1, 25)
        });
        $.ajax({
            url:"monthly_request_report_chart",
            type:"POST",
            data:{"month_date": month_date,"school_name": school_name,"dt_name":dt_name},
            success:function(responce){
                var res = JSON.parse(responce);
                monthdata(res);
            },
            error:function(){

            }
        })
       });
    
      function monthdata(res){
                //normal data
                
                var disease_normal = res.data.disease_normal;
                if(disease_normal  == undefined){

                }
                else{
                var array_disease_normal = [];

                $.each(disease_normal,function(index,val){
                   $.each(val,function(i,v){
                     var disease1 = [];
                   if(v.length>0){
                 
                 array_disease_normal.push(disease1.concat(v));
                 
                   }
                   })

                })
                var disease_normal_flat = array_disease_normal.flat();
                var  count_normal = {};
                disease_normal_flat.forEach(function(i) { count_normal[i] = (count_normal[i]||0) + 1;});
                var disease_normal = [];
                var disease_normal_count = [];
                $.each(count_normal,function(a,b){
                   disease_normal.push(a);
                    disease_normal_count.push(b);
                    
                })
                   
                var disease_data_normal = disease_normal.map((disease_name,index)=> [disease_name,disease_normal_count[index]]);

            } 
                    //chronic data
                var disease_chronic = res.data.disease_chronic;

                if(disease_chronic == undefined )
                {

                }else
                {
                    var array_disease_chronic = [];

                    $.each(disease_chronic,function(index,val){
                        $.each(val,function(i,v){
                             var disease1_con_chronic= [];
                           if(v.length>0){
                         
                         array_disease_chronic.push(disease1_con_chronic.concat(v));
                         
                           }
                        })

                    })
                    var disease_chronic_flat = array_disease_chronic.flat();
                    var  count_chronic = {};
                    disease_chronic_flat.forEach(function(i) { count_chronic[i] = (count_chronic[i]||0) + 1;});
                    var disease_chronic = [];
                    var disease_chronic_count = [];
                    $.each(count_chronic,function(a,b){
                       disease_chronic.push(a);
                        disease_chronic_count.push(b);
                        
                    })
                       
                    var disease_data_chronic = disease_chronic.map((disease_name,index)=> [disease_name,disease_chronic_count[index]]);
                }
                    
                    //emergecy data 
                var disease_emergency = res.data.disease_emergency;
                if(disease_emergency == undefined){

                }else{
                var array_disease_emergency = [];

                $.each(disease_emergency,function(index,val){
                    $.each(val,function(i,v){
                         var disease1_con_emergency= [];
                       if(v.length>0){
                     
                     array_disease_emergency.push(disease1_con_emergency.concat(v));
                     
                       }
                    })

                })

                var disease_emergency_flat = array_disease_emergency.flat();
                var  count_emergency = {};
                disease_emergency_flat.forEach(function(i) { count_emergency[i] = (count_emergency[i]||0) + 1;});
                var disease_emergency = [];
                
                var disease_emergency_count = [];
                $.each(count_emergency,function(a,b){
                   disease_emergency.push(a);
                    disease_emergency_count.push(b);
                    
                })
                   
                var disease_data_emergency = disease_emergency.map((disease_name,index)=> [disease_name,disease_emergency_count[index]]);
              }
                   
                    Highcharts.chart('chartContainer', {
                        chart: {
                            type: 'bar',
                            height : "70%"
                        },
                        title: {
                            text: 'Monthly Request Bar Chart'
                        },
                        xAxis: {
                            type: 'category'
                        },

                        legend: {
                            enabled: false
                        },

                        plotOptions: {
                            series: {
                                borderWidth: 0,
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        },

                        series: [{
                            name: 'Request Count',
                            colorByPoint: true,
                            data: [{
                                name: 'Emergency',
                                y: res.data.emergency,
                                drilldown: 'emergency',
                                color:"#D98368"
                            }, {
                                name: 'Chronic',
                                y: res.data.chronic,
                                drilldown: 'chronic',
                                color:"#FF8056"
                            }, {
                                name: 'Normal',
                                y: res.data.normal,
                                drilldown: 'normal',
                                color:"#61B0B7"
                            }]
                        }],
                        drilldown: {
                            series: [
                                {
                                id: 'emergency',
                                events:{
                                      click: function (event) {
                                           var ad = []
                                           var dd ;
                                           var ds = res.data.disease_emergency;
                                           if(ds == undefined){

                                           }else{
                                             $.each(ds,function(i,v) {
                                                $.each(v,function(key,val){
                                                  if(val.length > 0){
                                              if(event.point.name == val[0]){ 
                                                  var a  = key+"."+val[0];
                                                  ad.push(a);

                                                  }
                                              }
                                                 
                                                })
                                             })
                                             emergency_request(ad[0]);
                                        }
                                     } 
                                },
                                data: disease_data_emergency
                                 }, 
                                 {
                                id: 'chronic',
                                events:{
                                      click: function (event) {
                                           //chronic_request(event);
                                           var ad = []
                                           var dd ;
                                           var ds = res.data.disease_chronic;
                                           $.each(ds,function(i,v) {
                                              $.each(v,function(key,val){
                                                if(val.length > 0){
                                            if(event.point.name == val[0]){ 
                                                var a  = key+"."+val[0];
                                                ad.push(a);

                                                }
                                            }
                                               
                                              })
                                           })
                                         chronic_request(ad[0]);
                                      }
                                },
                                data: disease_data_chronic
                              
                            }, {    
                                id: 'normal',
                                events:{
                                      click: function (event) {
                                           //normal_request(event);

                                           var ad = []
                                           var dd ;
                                           var ds = res.data.disease_normal;
                                           $.each(ds,function(i,v) {
                                              $.each(v,function(key,val){
                                                if(val.length > 0){
                                            if(event.point.name == val[0]){ 
                                                var a  = key+"."+val[0];
                                                ad.push(a);

                                                }
                                            }
                                               
                                              })
                                           })
                                         normal_request(ad[0]);

                                      }
                                },
                                data: disease_data_normal
                            }]
                        }
                    });

         
        function emergency_request(event){
                
                var final_1 = event+"."+month_date+"."+dt_name+"."+school_name;
               
                    $("#emergency_symtom").val(final_1);
                    
                    $("#emergency_request_form").submit();
                }
        function chronic_request(event){
        
        var final_1 = event+"."+month_date+"."+dt_name+"."+school_name;
       
            $("#chronic_symtom").val(final_1);
            
            $("#chronic_request_form").submit();
           
        }
        function normal_request(event){
                        var final_1 = event+"."+month_date+"."+dt_name+"."+school_name;

                        $("#normal_symtom").val(final_1);
                        $("#normal_request_form").submit();
                      }

}
       
   </script>
   
  
  <!--  <script type="text/javascript">
    function monthdata(result){
        var month_date = $("#month_date_btn").val();
        var data = <?php //echo $data; ?>;

        var disease_normal = <?php //echo $disease_normal; ?>;
        var array_disease_normal = [];

        $.each(disease_normal,function(index,val){
           $.each(val,function(i,v){
             var disease1 = [];
           if(v.length>0){
         
         array_disease_normal.push(disease1.concat(v));
         
           }
           })

        })
    var disease_normal_flat = array_disease_normal.flat();
    var  count_normal = {};
    disease_normal_flat.forEach(function(i) { count_normal[i] = (count_normal[i]||0) + 1;});
    var disease_normal = [];
    var disease_normal_count = [];
    $.each(count_normal,function(a,b){
       disease_normal.push(a);
        disease_normal_count.push(b);
        
    })
       
    var disease_data_normal = disease_normal.map((disease_name,index)=> [disease_name,disease_normal_count[index]]);
    //emergecy data 
    var disease_emergency = <?php //echo $disease_emergency; ?>;

    var array_disease_emergency = [];

    $.each(disease_emergency,function(index,val){
        $.each(val,function(i,v){
             var disease1_con_emergency= [];
           if(v.length>0){
         
         array_disease_emergency.push(disease1_con_emergency.concat(v));
         
           }
        })

    })
    var disease_emergency_flat = array_disease_emergency.flat();
    var  count_emergency = {};
    disease_emergency_flat.forEach(function(i) { count_emergency[i] = (count_emergency[i]||0) + 1;});
    var disease_emergency = [];
    var disease_emergency_count = [];
    $.each(count_emergency,function(a,b){
       disease_emergency.push(a);
        disease_emergency_count.push(b);
        
    })
       
    var disease_data_emergency = disease_emergency.map((disease_name,index)=> [disease_name,disease_emergency_count[index]]);
    //chronic data
    var disease_chronic = <?php //echo $disease_chronic; ?>;

    var array_disease_chronic = [];

    $.each(disease_chronic,function(index,val){
        $.each(val,function(i,v){
             var disease1_con_chronic= [];
           if(v.length>0){
         
         array_disease_chronic.push(disease1_con_chronic.concat(v));
         
           }
        })

    })
    var disease_chronic_flat = array_disease_chronic.flat();
    var  count_chronic = {};
    disease_chronic_flat.forEach(function(i) { count_chronic[i] = (count_chronic[i]||0) + 1;});
    var disease_chronic = [];
    var disease_chronic_count = [];
    $.each(count_chronic,function(a,b){
       disease_chronic.push(a);
        disease_chronic_count.push(b);
        
    })
       
    var disease_data_chronic = disease_chronic.map((disease_name,index)=> [disease_name,disease_chronic_count[index]]);


    Highcharts.chart('chartContainer', {
    chart: {
        type: 'bar',
        height : "50%"
    },
    title: {
        text: 'Monthly Request Bar Chart'
    },
    xAxis: {
        type: 'category'
    },

    legend: {
        enabled: false
    },

    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true
            }
        }
    },

    series: [{
        name: 'Request Count',
        colorByPoint: true,
        data: [{
            name: 'Emergency',
            y: data.emergency,
            drilldown: 'emergency',
            color:"#D98368"
        }, {
            name: 'Chronic',
            y: data.chronic,
            drilldown: 'chronic',
            color:"#FF8056"
        }, {
            name: 'Normal',
            y: data.normal,
            drilldown: 'normal',
            color:"#61B0B7"
        }]
    }],
    drilldown: {
        series: [{
            id: 'emergency',
           
            data: disease_data_emergency//[{name:'brething', y:4,drilldown:"brething"}, ['Giddiness', 2], ['Chest Pain', 1], ['Breathing Problem', 2], ['Suicides', 1] ]
             }, 
             {
            id: 'chronic',
            data: disease_data_chronic
        }, {    
            id: 'normal',
            data: disease_data_normal
        }]
    }
});
}
    </script> -->

