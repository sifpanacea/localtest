<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

		<title><?php echo $page_title != "" ? $page_title." - " : ""; ?>Admin DashBoard</title>
		<meta name="description" content="">
		<meta name="author" content="">
			
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Basic Styles -->
		<link href="<?php echo(CSS.'bootstrap.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
        <link href="<?php echo(CSS.'font-awesome.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
        <link href="<?php echo(CSS.'smartadmin-production.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
        <link href="<?php echo(CSS.'smartadmin-skins.min.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
        <link href="<?php echo(CSS.'demo.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="<?php echo CSS; ?>prettyPhoto.css"/>

        <link href="https://cdn.bootcss.com/balloon-css/0.5.0/balloon.min.css" rel="stylesheet">
		 <link href="<?php echo CSS; ?>img_options/jquery.magnify.css" rel="stylesheet">
		 <link href="<?php echo CSS; ?>img_options/snack.css" rel="stylesheet">
		 <link href="<?php echo CSS; ?>img_options/snack-helper.css" rel="stylesheet">
		 <link href="<?php echo CSS; ?>img_options/docs.css" rel="stylesheet">

       <!-- <lnk rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.2/jquery.fancybox.css">-->
		<!-- SmartAdmin RTL Support is under construction
		<link rel="stylesheet" type="text/css" media="screen" href="<?php /*?><?php echo ASSETS_URL; ?><?php */?>/css/smartadmin-rtl.css"> -->

		<!-- We recommend you use "your_style.css" to override SmartAdmin
		     specific styles this will also ensure you retrain your customization with each SmartAdmin update.
		<link rel="stylesheet" type="text/css" media="screen" href="<?php /*?><?php echo ASSETS_URL; ?><?php */?>/css/your_style.css"> -->

	<?php /*?>	<?php

			if ($page_css) {
				foreach ($page_css as $css) {
					echo '<link rel="stylesheet" type="text/css" media="screen" href="'.ASSETS_URL.'/css/'.$css.'">';
				}
			}
		?>
<?php */?>

		<!-- FAVICONS -->
		<link rel="shortcut icon" href="<?php echo IMG; ?>ico/favicon.png" type="image/x-icon">
		<link rel="icon" href="<?php echo IMG; ?>ico/favicon.png" type="image/x-icon">

		<!-- GOOGLE FONT -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

		<!-- Specifying a Webpage Icon for Web Clip 
			 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
		<link rel="apple-touch-icon" href="<?php echo IMG; ?>splash/sptouch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php echo IMG; ?>splash/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php echo IMG; ?>splash/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php echo IMG; ?>splash/touch-icon-ipad-retina.png">
		
		<!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		
		<!-- Startup image for web apps -->
		<link rel="apple-touch-startup-image" href="<?php echo IMG; ?>splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
		<link rel="apple-touch-startup-image" href="<?php echo IMG; ?>splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
		<link rel="apple-touch-startup-image" href="<?php echo IMG; ?>splash/iphone.png" media="screen and (max-device-width: 320px)">
		<link href="<?php echo(CSS.'custom-media.css'); ?>" media="screen" rel="stylesheet" type="text/css" />
		<style type="text/css">
		.smart-style-3 #header{
			    display: block;
    			margin: 0;
			    padding: 0 13px 0 0;
			    background-color: #f3f3f3;
			    background-image: -moz-linear-gradient(top, #f3f3f3, #e2e2e2);
			    background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#f3f3f3), to(#e2e2e2));
			    background-image: -webkit-linear-gradient(top, #f3f3f3, #e2e2e2);
			    background-image: -o-linear-gradient(top, #f3f3f3, #e2e2e2);
			    background-image: linear-gradient(to bottom, #f3f3f3, #e2e2e2);
			    background-repeat: repeat-x;
		}

body {
    background-image: linear-gradient(to right , #cb2d8e , #ef473a);
}

.searchBox {
    position: absolute;
    top: 10%;
    left: 80%;
    transform:  translate(-50%,50%);
    background: teal;
    height: 60px;
    border-radius: 40px;
    padding: 5px;

}

.searchBox:hover > .searchInput {
    width: 240px;
    padding: 0 6px;
}

.searchBox:hover > .searchButton {
  background: teal;
  color : #2f5896;
}

.searchButton {
    color: white;
    float: right;
    width: 50px;
    height: 40px;
    border-radius: 50%;
    background: #e91e63;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: 0.4s;
}

.searchInput {
    border:none;
    background: none;
    outline:none;
    float:left;
    padding: 0;
    color: blue;
    font-size: 16px;
    transition: 0.4s;
    line-height: 40px;
    width: 0px;

}

@media screen and (max-width: 620px) {
.searchBox:hover > .searchInput {
    width: 150px;
    padding: 0 6px;
}
}

#get_search {
  border: 1px solid #FF00;
  padding: 10px;
  border-radius: 5px;
}
#get_val {
  border: 2px solid #FF0000;
  padding: 7px;
}

  

		</style>
	</head>
	
	<body 
		<?php 
			if ($page_body_prop) {
			
				foreach ($page_body_prop as $prop_name => $value) {
					echo $prop_name.'="'.$value.'" ';
				}
			}

		?> class="desktop-detected pace-done fixed-header fixed-navigation smart-style-3"
	>
		<!-- POSSIBLE CLASSES: minified, fixed-ribbon, fixed-header, fixed-width
			 You can also add different skin classes such as "smart-skin-1", "smart-skin-2" etc...-->
		<?php
			if (!$no_main_header) {

		?>
				<!-- HEADER -->
				<header id="header">
				    <div id="logo-group">
						<!-- PLACE YOUR LOGO HERE -->
						<span id="">
						<img src="<?php echo TS_GOVT_LOGO; ?>" alt="" class="logo_1" style="">
						</span>
						<span id="">
						<img class="logo_2" src="<?php echo PANACEA_LOGO; ?>" alt="" style="">
						</span>
						<span id="">
						<img class="logo_3" src="https://mednote.in/PaaS/bootstrap/dist/img/sif.png" alt="" style="">
						</span>
				    	<!-- END LOGO PLACEHOLDER -->
					</div>
					<!-- <?php
                                        //$attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
                                       // echo  form_open('panacea_mgmt/panacea_ehr_display_uid_name',$attributes);
                                        ?> -->
					<div class="panacea-custom-title" style="">
					<p class="panacea-headertext-top" style="">PANACEA </p>
					<p class="panacea-headertext-bottom" style="">SCHOOL HEALTH PROGRAM</p>
					</div>
					<div class="searchBox" >
					    <input type="text"  id="get_search" placeholder="Search">
			            <button type="submit" id="close_val" class="searchButton bg-color-pink">Clear</button>
			             <button type="submit" id="get_val" class="searchButton bg-color-red">Search</button>
					</div>
                   // <?php //echo form_close();?>
					<!-- pulled right: nav area -->
					<div class="pull-right"> 
						<div class="right_logo"  id="logo-group">
						<!-- PLACE YOUR LOGO HERE -->
						<span id="">
						
						<a href="http://www.sifhyd.org" target="_blank"><img src="https://mednote.in/PaaS/bootstrap/dist/img/sif.png" alt="" style="width: 95px;"></a>
						
						</span>
				    	<!-- END LOGO PLACEHOLDER -->
					</div>
						<!-- collapse menu button -->
						<div id="hide-menu" class="btn-header pull-right">
							<span> <a href="javascript:void(0);" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
						</div>
						<!-- end collapse menu -->

						<!-- logout button -->
						<div id="logout" class="btn-header transparent pull-right">
							<span> <a class="menu-links" href="<?php echo URL; ?>auth/logout" title="Sign Out" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span>
						</div>
						<!-- end logout button -->

						<!-- search mobile button (this is hidden till mobile view port) -->
						<div id="search-mobile" class="hide btn-header transparent pull-right">
							<span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
						</div>
						<!-- end search mobile button -->
						
						
						<!-- profile end -->

						<!-- fullscreen button -->
						<!--<div id="fullscreen" class="btn-header transparent pull-right">
							<span> <a href="javascript:void(0);" onclick="launchFullscreen(document.documentElement);" title="Full Screen"><i class="fa fa-fullscreen"></i></a> </span>
						</div>-->
						<!-- end fullscreen button -->
                       </div>
					<!-- end pulled right: nav area -->
					   <div class="project-title">
					   <p>PANACEA SCHOOL HEALTH PROGRAM</p>
					   </div>
				</header>
				<!-- END HEADER -->


		<?php
			}
		?>

        <script type="text/javascript">
               $('#get_val').click(function(){

              //  $('#request_btn').hide();
                    $("#loading_modal").modal('show');
                   var search = $('#get_search').val();
                 //alert(search);
                   $.ajax({

                       url:'get_searched_student_sick_requests',
                       type: 'POST',
                       data:{"search_value":search},
                       success: function(data){
                        $("#loading_modal").modal('hide');
                       // $('#search_btn').show();
                        result = $.parseJSON(data);
                           //console.log(result);
                          display_data_table(result);
                       },
                       error:function(XMLHttpRequest, textStatus, errorThrown)
                       {
                        console.log('error', errorThrown);
                       }
                   });

               });

               function display_data_table(result)
               {
                  if(result.length > 0){
                      data_table = '<table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%"> <thead> <tr> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter STUDENT NAME" /> </th> <th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter UNIQUE ID" /></th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter STUDENT SCHOOL" /> </th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter CLASS" /></th><th class="hasinput" style="width:17%"> <input type="text" class="form-control" placeholder="Filter Mobile Number" /></th> </tr> <tr> <th>STUDENT NAME</th><th>HOSPITAL UNIQUE ID</th><th>STUDENT SCHOOL</th> <th>CLASS</th> <th>Mobile Number</th><th>Action</th></tr> </thead> <tbody>';

                      $.each(result, function() {
                          //console.log(this.doc_data.widget_data["page2"]['Personal Information']['AD No']);
                          data_table = data_table + '<tr>';
                          data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Name'] + '</td>';
                          data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Hospital Unique ID'] + '</td>'; 
                          data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['School Name'] + '</td>';                 
                          data_table = data_table + '<td>'+this.doc_data.widget_data["page2"]['Personal Information']['Class'] + '</td>'; 
                          data_table = data_table + '<td>'+this.doc_data.widget_data["page1"]['Personal Information']['Mobile']['mob_num'] + '</td>'; 
                        var urlLink = "https://mednote.in/PaaS/healthcare/index.php/";
                        var obj = Object.values(this['_id']);
                        data_table = data_table + '<td><a class="btn btn-primary btn-xs" href="'+urlLink+'panacea_mgmt/panacea_reports_display_ehr_uid/?id = '+this.doc_data.widget_data["page1"]['Personal Information']['Hospital Unique ID']+'">Show EHR</a></td>';
                     
                          data_table = data_table + '</tr>';
                        
                      });
                      data_table = data_table +'</tbody></table>';
                      
                      $("#searched_stud_list").html(data_table);

                        //=========================================data table functions=====================================
                        
                                    /* BASIC ;*/
                            var responsiveHelper_dt_basic = undefined;
                            var responsiveHelper_datatable_fixed_column = undefined;
                            var responsiveHelper_datatable_col_reorder = undefined;
                            var responsiveHelper_datatable_tabletools = undefined;
                            
                            var breakpointDefinition = {
                                tablet : 1024,
                                phone : 480
                            };
                      
                            $('#dt_basic').dataTable({
                                "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
                                    "t"+
                                    "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
                                "autoWidth" : true,
                                "preDrawCallback" : function() {
                                    // Initialize the responsive datatables helper once.
                                    if (!responsiveHelper_dt_basic) {
                                        responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
                                    }
                                },
                                "rowCallback" : function(nRow) {
                                    responsiveHelper_dt_basic.createExpandIcon(nRow);
                                },
                                "drawCallback" : function(oSettings) {
                                    responsiveHelper_dt_basic.respond();
                                }
                            });
                      
                        /* END BASIC */
                        var js_url = "<?php echo JS; ?>";
                        /* COLUMN FILTER  */
                          var otable = $('#datatable_fixed_column').DataTable({
                          
                            "autoWidth" : true,
                            "preDrawCallback" : function() {
                                // Initialize the responsive datatables helper once.
                                if (!responsiveHelper_datatable_fixed_column) {
                                    responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
                                }
                            },
                            "rowCallback" : function(nRow) {
                                responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
                            },
                            "drawCallback" : function(oSettings) {
                                responsiveHelper_datatable_fixed_column.respond();
                            }       
                        
                          });
                          
                          // custom toolbar
                          //$("div.toolbar").html('<div class="text-right"><img src="img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');
                               
                          // Apply the filter
                          $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
                            
                              otable
                                  .column( $(this).parent().index()+':visible' )
                                  .search( this.value )
                                  .draw();
                                  
                          } );
                          /* END COLUMN FILTER */   
                          $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
                        
                        otable
                            .column( $(this).parent().index()+':visible' )
                            .search( this.value )
                            .draw();
                            
                    } );
                

                      //=====================================================================================================
                      }else{
                          $("#stud_report").html('<h5>No students to display for this school</h5>');
                      }
                   }

               //$('#search_btn').hide();
               $('#close_val').click(function(){
              // $('#search_btn').hide();
               $('#request_btn').hide();
               });

        </script>
