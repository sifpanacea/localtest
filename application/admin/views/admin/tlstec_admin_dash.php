<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TLSTEC Admin Dashboard</title>
<link href="<?php echo(CSS.'TLSTEC_admin_dashboard.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo(CSS.'960.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo(CSS.'reset.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo(CSS.'text.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo(CSS.'smoothness/ui.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script type="text/javascript" src=<?php echo(JS.'blend/jquery.blend.js'); ?>"></script>
	<script type="text/javascript" src=<?php echo(JS.'ui.core.js'); ?>"></script>
	<script type="text/javascript" src=<?php echo(JS.'ui.sortable.js'); ?>"></script>
	<script type="text/javascript" src=<?php echo(JS.'ui.dialog.js'); ?>"></script>    
    <script type="text/javascript" src=<?php echo(JS.'ui.datepicker.js'); ?>"></script>
    <script type="text/javascript" src=<?php echo(JS.'ui.effects.js'); ?>"></script>
    <script type="text/javascript" src=<?php echo(JS.'flot/jquery.flot.pack.js'); ?>"></script>
    <!--[if IE]>
    <script language="javascript" type="text/javascript" src="js/flot/excanvas.pack.js"></script>
    <![endif]-->
	<!--[if IE 6]>
	<link rel="stylesheet" type="text/css" href="css/iefix.css" />
	<script src="js/pngfix.js"></script>
    <script>
        DD_belatedPNG.fix('#menu ul li a span span');
    </script>        
    <![endif]-->
    <script id="source" language="javascript" type="text/javascript" src=<?php echo(JS.'graphs.js'); ?>"></script>

</head>

<body>
  	<!--LOGO-->
	<div class="grid_8" id="logo">TLSTEC Admin </div>
    <div class="grid_8">
<!-- USER TOOLS START -->
      <div id="user_tools"><span><a href="#" class="mail">(1)</a> Welcome <a href="#">Admin Username</a>  |  <a href="#">Logout</a></span></div>
    </div>
<!-- USER TOOLS END -->    
<div class="grid_16" id="header">
<!-- MENU START -->
<div id="menu">
	<ul class="group" id="menu_group_main">
		<li class="item first" id="one"><a href="#" class="main current"><span class="outer"><span class="inner dashboard">Dashboard</span></span></a></li>
        <li class="item middle" id="two"><a href="forms_red.html" class="main"><span class="outer"><span class="inner content">Content</span></span></a></li>
        <li class="item middle" id="three"><a href="#"><span class="outer"><span class="inner reports png">Reports</span></span></a></li>
        <li class="item middle" id="four"><a href="#" class="main"><span class="outer"><span class="inner users">Users</span></span></a></li>
		<li class="item middle" id="five"><a href="#" class="main"><span class="outer"><span class="inner media_library">Media Library</span></span></a></li>        
		<li class="item middle" id="six"><a href="#" class="main"><span class="outer"><span class="inner event_manager">Event Manager</span></span></a></li>        
		<li class="item middle" id="seven"><a href="#" class="main"><span class="outer"><span class="inner newsletter">Newsletter</span></span></a></li>        
		<li class="item last" id="eight"><a href="#" class="main"><span class="outer"><span class="inner settings">Settings</span></span></a></li>        
    </ul>
</div>
<!-- MENU END -->
</div>

<!-- CONTENT START -->
    <div class="grid_16" id="content">
    <!--  TITLE START  --> 
    <div class="grid_9">
    <h1 class="dashboard">Dashboard</h1>
    </div>
    <!--RIGHT TEXT/CALENDAR-->
    <div class="grid_6" id="eventbox"><a href="#" class="inline_calendar">You don't have any events for today! Yay!</a>
    	<div class="hidden_calendar"></div>
    </div>
    <!--RIGHT TEXT/CALENDAR END-->
    <div class="clear">
    </div>
    <!--  TITLE END  -->    
    <!-- #PORTLETS START -->
    <div id="portlets">
    <!-- FIRST SORTABLE COLUMN START -->
      <div class="column" id="left">
      <!--THIS IS A PORTLET-->
		<div class="portlet">
            <div class="portlet-header"><img src="images/icons/chart_bar.gif" width="16" height="16" alt="Reports" /> Visitors - Last 30 days</div>
            <div class="portlet-content">
            <!--THIS IS A PLACEHOLDER FOR FLOT - Report & Graphs -->
            <div id="placeholder" style="width:auto; height:250px;"></div>
            </div>
        </div>      
      <!--THIS IS A PORTLET-->
        <div class="portlet">
		<div class="portlet-header">Anything  (no icon too if you like it better)</div>

		<div class="portlet-content">
		  <p>This can be any content you want. I placed a basic form here with text editor so you can see the functionality of the forms too.</p>
		  <h3>This is a form</h3>
		  <form id="form1" name="form1" method="post" action="">
		    <label>Some title</label>
		     <input type="text" name="textfield" id="textfield" class="smallInput" />
			<label>Large input box</label>
            <input type="text" name="textfield2" id="textfield2" class="largeInput" />
            <label>This is a textarea</label>
		    <textarea name="textarea" cols="45" rows="3" class="smallInput" id="textarea"></textarea>
            <a class="button"><span>Submit in red</span></a>
            <a class="button_grey"><span>Submit this form</span></a>
		  </form>
		  <p>&nbsp;</p>
		</div>
        </div>
      </div>
      <!-- FIRST SORTABLE COLUMN END -->
      <!-- SECOND SORTABLE COLUMN START -->
      <div class="column">
      <!--THIS IS A PORTLET-->        
      <div class="portlet">
		<div class="portlet-header"><img src="images/icons/comments.gif" width="16" height="16" alt="Comments" />Latest Comments</div>

		<div class="portlet-content">
         <p class="info" id="success"><span class="info_inner">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</span></p>
    <p class="info" id="error"><span class="info_inner">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</span></p>
    <p class="info" id="warning"><span class="info_inner">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</span></p>
<p class="info" id="info"><span class="info_inner">Lorem ipsum dolor sit amet, consectetuer adipiscing elit</span></p>
        Lorem ipsum dolor sit amet, consectetuer adipiscing elit</div>
       </div>    
      <!--THIS IS A PORTLET--> 
      <div class="portlet">
		<div class="portlet-header"><img src="images/icons/feed.gif" width="16" height="16" alt="Feeds" />Your selected News source					</div>
		<div class="portlet-content">
        <ul class="news_items">
        	<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean  adipiscing massa quis arcu interdum scelerisque. Duis vitae nunc nisi.  Quisque eget leo a nibh gravida vulputate ut sed nulla. <a href="#">Donec quis  lectus turpis, sed mollis nibh</a>. Donec ut mi eu metus ultrices  porttitor. Phasellus nec elit in nisi</li>
            <li>Nunc convallis, enim quis tincidunt dictum, ante ipsum  interdum massa, consequat sodales arcu magna nec eros.<a href="#"> Vivamus nec  placerat odio.</a> Sed nec mi sed orci mattis feugiat. Etiam est dui,  rutrum nec dictum vel, accumsan id sem. </li>
            <li>Nunc convallis, enim quis tincidunt dictum, ante ipsum  interdum massa, consequat sodales arcu magna nec eros.<a href="#"> Vivamus nec  placerat odio.</a> Sed nec mi sed orci mattis feugiat. Etiam est dui,  rutrum nec dictum vel, accumsan id sem. </li>
            <li>Nunc convallis, enim quis tincidunt dictum, ante ipsum  interdum massa, consequat sodales arcu magna nec eros.<a href="#"> Vivamus nec  placerat odio.</a> Sed nec mi sed orci mattis feugiat. Etiam est dui,  rutrum nec dictum vel, accumsan id sem. </li>
            <li>Nunc convallis, enim quis tincidunt dictum, ante ipsum  interdum massa, consequat sodales arcu magna nec eros.<a href="#"> Vivamus nec  placerat odio.</a> Sed nec mi sed orci mattis feugiat. </li>
        </ul>
        <a href="#">&raquo; View all news items</a>
        </div>
       </div>                         
    </div>
	<!--  SECOND SORTABLE COLUMN END -->
    <div class="clear"></div>
    <!--THIS IS A WIDE PORTLET-->
    <div class="portlet">
        <div class="portlet-header fixed"><img src="images/icons/user.gif" width="16" height="16" alt="Latest Registered Users" /> Last Registered users Table Example</div>
		<div class="portlet-content nopadding">
        <form action="" method="post">
          <table width="100%" cellpadding="0" cellspacing="0" id="box-table-a" summary="Employee Pay Sheet">
            <thead>
              <tr>
                <th width="34" scope="col"><input type="checkbox" name="allbox" id="allbox" onclick="checkAll()" /></th>
                <th width="136" scope="col">Name</th>
                <th width="102" scope="col">Username</th>
                <th width="109" scope="col">Date</th>
                <th width="129" scope="col">Location</th>
                <th width="171" scope="col">E-mail</th>
                <th width="123" scope="col">Phone</th>
                <th width="90" scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td width="34"><label>
                    <input type="checkbox" name="checkbox" id="checkbox" />
                </label></td>
                <td>Stephen C. Cox</td>
                <td>stephen</td>
                <td>20.06.2009</td>
                <td>Los Angeles, CA</td>
                <td>address1@yahoo.com</td>
                <td>332-5447879</td>
                <td width="90"><a href="#" class="approve_icon" title="Approve"></a> <a href="#" class="reject_icon" title="Reject"></a> <a href="#" class="edit_icon" title="Edit"></a> <a href="#" class="delete_icon" title="Delete"></a></td>
              </tr>
              <tr>
                <td width="34"><input type="checkbox" name="checkbox2" id="checkbox2" /></td>
                <td>Josephin Tan</td>
                <td>josephin</td>
                <td>20.06.2009</td>
                <td>Los Angeles, CA</td>
                <td>address1@yahoo.com</td>
                <td>332-5447879</td>
                <td width="90"><a href="#" class="approve_icon" title="Approve"></a> <a href="#" class="reject_icon" title="Reject"></a> <a href="edit_modal.html" class="edit_icon" title="Edit"></a> <a href="#" class="delete_icon" title="Delete"></a></td>
              </tr>
              <tr>
                <td width="34"><input type="checkbox" name="checkbox3" id="checkbox3" /></td>
                <td>Joyce Ming</td>
                <td>joyce_m</td>
                <td>20.06.2009</td>
                <td>Los Angeles, CA</td>
                <td>address1@yahoo.com</td>
                <td>332-5447879</td>
                <td width="90"><a href="#" class="approve_icon" title="Approve"></a> <a href="#" class="reject_icon" title="Reject"></a> <a href="#" class="edit_icon" title="Edit"></a> <a href="#" class="delete_icon" title="Delete"></a></td>
              </tr>
              <tr>
                <td width="34"><input type="checkbox" name="checkbox4" id="checkbox4" /></td>
                <td>James A. Pentel</td>
                <td>james_pent</td>
                <td>20.06.2009</td>
                <td>Los Angeles, CA</td>
                <td>address1@yahoo.com</td>
                <td>332-5447879</td>
                <td width="90"><a href="#" class="approve_icon" title="Approve"></a> <a href="#" class="reject_icon" title="Reject"></a> <a href="#" class="edit_icon" title="Edit"></a> <a href="#" class="delete_icon" title="Delete"></a></td>
              </tr>
              <tr class="footer">
                <td colspan="4"><a href="#" class="edit_inline">Edit all</a><a href="#" class="delete_inline">Delete all</a><a href="#" class="approve_inline">Approve all</a><a href="#" class="reject_inline">Reject all</a></td>
                <td align="right">&nbsp;</td>
                <td colspan="3" align="right">
				<!--  PAGINATION START  -->             
                    <div class="pagination">
                    <span class="previous-off">&laquo; Previous</span>
                    <span class="active">1</span>
                    <a href="query_41878854">2</a>
                    <a href="query_8A8058C2">3</a>
                    <a href="query_2823E521">4</a>
                    <a href="query_B322F5B7">5</a>
                    <a href="query_3A2A444D">6</a>
                    <a href="query_912D14DB">7</a>
                    <a href="query_41878854" class="next">Next &raquo;</a>
                    </div>  
                <!--  PAGINATION END  -->       
                </td>
              </tr>
            </tbody>
          </table>
        </form>
		</div>
      </div>
<!--  END #PORTLETS -->  
   </div>
    <div class="clear"> </div>
<!-- END CONTENT-->    
  </div>
<div class="clear"> </div>

</div>
<!-- WRAPPER END -->
<!-- FOOTER START -->
<footer>
		<?php echo lang('common_copy_rights');?>
		</footer>
<!-- FOOTER END -->
</body>
</html>
