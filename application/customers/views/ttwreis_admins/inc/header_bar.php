<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>TTWREIS Admin Dashboard</title>
    <!-- Favicon-->
    <link rel="shortcut icon" type="image/ico" href="<?php echo (IMG.'PANACEA.png') ?>"/>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo(MDB_PLUGINS.'bootstrap/css/bootstrap.css'); ?>" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo(MDB_PLUGINS.'node-waves/waves.css'); ?>" rel="stylesheet" />

    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="<?php echo(MDB_PLUGINS.'bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css'); ?>" rel="stylesheet" />

    <!-- Wait Me Css -->
    <link href="<?php echo(MDB_PLUGINS.'waitme/waitMe.css'); ?>" rel="stylesheet" />
    
    <!-- Bootstrap Select Css -->
    <link href="<?php echo(MDB_PLUGINS.'bootstrap-select/css/bootstrap-select.css'); ?>" rel="stylesheet" />
    
    <!-- Animation Css -->
  
    <link href="<?php echo(MDB_PLUGINS.'animate-css/animate.css'); ?>" rel="stylesheet">

    <!-- JQuery DataTable Css -->
    <link href="<?php echo(MDB_PLUGINS.'jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css'); ?>" rel="stylesheet">   

    <!-- Morris Chart Css-->
    <link href="<?php echo(MDB_PLUGINS.'morrisjs/morris.css'); ?>" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo(MDB_CSS.'style.css'); ?>" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="<?php echo(MDB_CSS.'themes/all-themes.css'); ?>" rel="stylesheet" />

    <link href="<?php echo CSS."img_options/jquery.magnify.css"; ?>" rel="stylesheet" />
</head>

<body class="theme-green">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-green">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <?php
    $attributes = array('class' => 'smart-form','id'=>'create_user','name'=>'userform');
    echo  form_open('ttwreis_mgmt/get_entered_related_data',$attributes);
    ?>
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="Enter Student Health ID or Name..." name="uid" id="uid" value="<?PHP echo set_value('uid'); ?>">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <?php echo form_close();?>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
      <nav class="navbar" style="height:74px;">
        <div class="container-fluid">
            <div class="navbar-header">
             <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <div>
                     <img class="pull-left"  style="height: 60px;margin-top: -5px;margin-right: 5px; border-radius: 30px; " src="<?php echo IMG; ?>/TELANGANA.png" alt="logo" title="" />
                    <img class="pull-left"  style="height: 60px; margin-top: -6px;border-radius: 60px;" src="<?php echo IMG; ?>/TRIBAL_WELFARE_LOGO.jpg" alt="" title="" /> 
                   <img class="pull-left" style="height:60px;margin-left: 4px; margin-top: -5px;width:60px; border-radius: 50px; " src="<?php echo IMG; ?>/Panacea_LOGO_Final.jpg" alt="">
                   <img class="pull-left" style="height:75px;margin-left: -6px; margin-top: -8px;width:80px; border-radius: 100px; " src="<?php echo IMG; ?>/SYNERGY.png" alt="">
                    <!-- <img class="pull-left" style="height: 63px; margin-top: -2px; border-radius: 167px;" src="https://adivasischoolhealth.com/PaaS/bootstrap/dist/img//landing_page_img/Ameya Life New Logo Final copy.jpg" alt="AmeyaLIfe" title=""> -->
                    
                        
                   
                </div>
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <div class="col-sm-offset-6">
                	<a class="navbar-brand" href="#" style="margin-left: 300px"> TTWREIS SCHOOL HEALTH PROGRAM</a>
                </div>
            </div>
            <!-- <script type="text/javascript">
            $(document).ready(function () {
             $("#menu-toggle").click(function(e) {
                  e.preventDefault();
                  $("#leftsidebar").toggleClass("toggled");
            });
             });
            </script> -->
            <!--Also we can add script for toggle -->
            <!-- <script type="text/javascript">
            $(document).ready(function () {
                $('#leftsidebarCollapse').on('click', function () {
                    $('#leftsidebar, #content').toggleClass('active');
                    $('.collapse.in').toggleClass('in');
                    $('a[aria-expanded=true]').attr('aria-expanded', 'false');
                });
            });
            </script> -->

              <!-- <li style="padding-top: 15px"><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">Filter Button</button></li> -->
              <!-- collapse for sidebar -->
                <!-- <div class="icon-button-demo" style="padding-top: 15px;">
                    <button type="button" id="menu-toggle" class="btn btn-success btn-circle-lg waves-effect waves-circle waves-float" data-toggle="collapse" data-target="#leftsidebar" aria-expanded="false">  
                        <i class="material-icons">home</i>
                    </button>
                </div> -->
            <div class="collapse navbar-collapse" id="navbar-collapse">
                
                <ul class="nav navbar-nav navbar-right">
                    <li style="padding-top: 18px"><button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"  data-placement="bottom" title="Academic Year Filters"><img src="<?php echo IMG ;?>/funnel.png"></button></li>

                    <li <?php if($current_page == 'blood_group_wise_search') { echo 'class="active"';} ?>>
                        <a href="<?php echo URL."ttwreis_mgmt/panacea_blood_group_pie"?>" data-toggle="tooltip" data-placement="bottom" title="Blood Donor Data" data-original-title="Search Blodd Group wise">
                            <i class="material-icons">bloodtype</i>
                        </a>
                    </li>

                    <li <?php if($current_page == 'Gender_wise_search') { echo 'class="active"';} ?>>
                        <a href="<?php echo URL."ttwreis_mgmt/gender_wise_student_data"?>" data-toggle="tooltip" data-placement="bottom" title="Gender Wise Search" data-original-title="Search Gender wise">
                            <i class="material-icons">grid_on</i>
                        </a>
                    </li>

                    <li <?php if($current_page == 'maximum_raised_requests') { echo 'class="active"';} ?>>
                        <a href="<?php echo URL."ttwreis_mgmt/maximum_raised_requests"?>" data-toggle="tooltip" data-placement="bottom" title="Maximun Raised Request" data-original-title="Maximun Raised Request">
                            <i class="material-icons">menu</i>
                        </a>
                    </li>
             
                    <!-- Call Search -->

                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                    <!-- #END# Call Search -->
                    <!-- Notifications -->
                    <li class="dropdown hide">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">notifications</i>
                            <span class="label-count">7</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">NOTIFICATIONS</li>
                            <li class="body">
                                <ul class="menu">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">person_add</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>12 new members joined</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 14 mins ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-cyan">
                                                <i class="material-icons">add_shopping_cart</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>4 sales made</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 22 mins ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-red">
                                                <i class="material-icons">delete_forever</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>Nancy Doe</b> deleted account</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 3 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-orange">
                                                <i class="material-icons">mode_edit</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>Nancy</b> changed name</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 2 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-blue-grey">
                                                <i class="material-icons">comment</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>John</b> commented your post</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 4 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-light-green">
                                                <i class="material-icons">cached</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4><b>John</b> updated status</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> 3 hours ago
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <div class="icon-circle bg-purple">
                                                <i class="material-icons">settings</i>
                                            </div>
                                            <div class="menu-info">
                                                <h4>Settings updated</h4>
                                                <p>
                                                    <i class="material-icons">access_time</i> Yesterday
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="javascript:void(0);">View All Notifications</a>
                            </li>
                        </ul>
                    </li>
                    <!-- #END# Notifications -->
                    <!-- Tasks -->
                    <li class="dropdown hide">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">flag</i>
                            <span class="label-count">9</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">TASKS</li>
                            <li class="body">
                                <ul class="menu tasks">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
                                                Footer display issue
                                                <small>32%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-pink" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 32%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
                                                Make new buttons
                                                <small>45%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-cyan" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
                                                Create new dashboard
                                                <small>54%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-teal" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 54%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
                                                Solve transition issue
                                                <small>65%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 65%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">
                                            <h4>
                                                Answer GitHub questions
                                                <small>92%</small>
                                            </h4>
                                            <div class="progress">
                                                <div class="progress-bar bg-purple" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 92%">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="javascript:void(0);">View All Tasks</a>
                            </li>
                        </ul>
                    </li>
                    <li <?php if($current_page == 'from_screening_abnormalities') { echo 'class="active"';} ?>>
                        <a href="<?php echo URL."auth/logout"?>" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="logout">
                            <i class="material-icons">power_settings_new</i>
                        </a>
                    </li>
                    <!-- #END# Tasks -->
                    <!-- Right Sidebar 	Link-->
                    <li class="pull-right hide"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li>
                    
                </ul>
            </div>
        </div>

    </nav>
    <!-- #Top Bar -->

    

   

