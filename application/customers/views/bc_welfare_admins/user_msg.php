<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Single Messaging";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["chat"]['sub']['user_msg']["active"] = true;
include("inc/nav.php");

?>
<link rel="stylesheet" href="<?php echo(CSS.'bootstrap-multiselect.css'); ?>" type="text/css"/>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["BC Welfare Masters"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
	
	
	
	
<div class="row">
     				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-0" data-widget-editbutton="false">
						
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>Messaging </h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body">
				             
				             <form class="smart-form">
									<header>
										Sending message to `Single User`
									</header>
										
									<fieldset>
									<section>
										<label class="">Select your name from the below recipients and send a message</label>
									</section>
									
									<div class="row">
										<section class="col col-6">
											<label class="label">Select user groups</label>
											<label class="select select-multiple">
												<select id="select_group_name" multiple="" class="custom-scroll">
												<option value='admin' >Admin</option>
												<option value='doctors' >Doctors</option>
												<option value='hs' >Health Supervirors</option>
												<option value='ha' >Health Assistant</option>
												<option value='superior' >Superior</option>
											</select> </label>
										</section>
										<section class="col col-6">
											<label class="label">Select a user</label>
											<label class="select">
												<select id="users" name="users" required>
												<option value=0 >Select group to get users</option>
											</select> <i></i> </label>
										</section>
									</div>
										<section>
											<label class="label">Type your message</label>
											<label class="textarea"> 										
												<textarea rows="3" class="custom-scroll" id="send_to_single"></textarea> 
											</label>
										</section>
									</fieldset>
									<footer>
										<button type="button" id = "send_to_single_user" class="btn btn-primary">
											Send
										</button><img src="<?php echo(IMG.'loading.gif'); ?>" id="loader_single" class="loader"/>
										<button type="button" class="btn btn-default" onclick="window.history.back();">
											Back
										</button>
									</footer>
								</form>
				             
                			</div>
                
							</div>
							</div>
							<!-- end widget content -->
		
						</div>
						<!-- end widget div -->
		
					</div>
					<!-- end widget -->
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
<script src="<?php echo(JS.'bootstrap-multiselect.js'); ?>" type="text/javascript"></script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>

<script>
var user_id = '<?= $admin_id ?>';
$(document).ready(function () {
	$('#loader_single').hide();
		 
		 //$('#select_group_name').multiselect();
		 var	userslist = <?php echo json_encode($users); ?>;
		 $('#select_group_name').change(function(e) {
			 var values = [];
				 		var selected_group = $('#select_group_name').val();
				 		var usersList = [];
				 		for (var i = 0; i < selected_group.length; i++) {
					 		for(var j = 0; j < userslist[selected_group[i]].length; j++){
					 			usersList.push(userslist[selected_group[i]][j]);
					 		}
					 	}
				 		var opt = "";
				 		for (var i = 0; i < usersList.length; i++) {
		        			 opt = opt + '<option value="'+usersList[i].email+'">'+usersList[i].username+'</option>';
		        		}
				 		$("#users").html(opt);

						 });

         $('#send_to_single_user').on('click', function () {
             var msg = $('#send_to_single').val();
             var to = $('#users').val();
             
             if (msg.trim().length === 0) {
            	 $.smallBox({
     				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message!",
     				content : "Enter a message",
     				color : "#C46A69",
     				iconSmall : "fa fa-bell bounce animated",
     			});
                 return;
             }
             $('#loader_single').show();

             $.post("users/" + btoa(to) + '/message',
                     {user_id: user_id, message: msg},
             function (data) {
                         data = JSON.parse(data);
                 if (data.error === false) {
                	 $('#send_to_single').val('');
                     $('#loader_single').hide();
                     $.smallBox({
          				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message!",
          				content : "Push notification sent successfully! User should see a Toast message on device.",
          				color : "#C46A69",
          				iconSmall : "fa fa-bell bounce animated",
          			});
                     
                 } else {
                     $.smallBox({
           				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message!",
           				content : "Sorry! Unable to post message.",
           				color : "#C46A69",
           				iconSmall : "fa fa-bell bounce animated",
           			});
                 }
             }).done(function () {

             }).fail(function () {
                 $.smallBox({
        				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message!",
        				content : "Sorry! Unable to post message.",
        				color : "#C46A69",
        				iconSmall : "fa fa-bell bounce animated",
        			});
             }).always(function () {
                 $('#loader_single').hide();
             });
         });
	
});
</script>