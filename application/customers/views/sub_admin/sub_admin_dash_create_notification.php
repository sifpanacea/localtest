<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Create Notification";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");


//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["notification"]["sub"]["create_notification"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Notification"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="jarviswidget jarviswidget-sortable col-md-6" id="wid-id-6" data-widget-editbutton="false" data-widget-custombutton="false" role="widget">
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
				<header role="heading"><h2>Notification</h2></header>

				<!-- widget div-->
				<div role="content">
					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
						<!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body">
						
						<?php
						 	$attributes = array('class' => '','id'=>'sub_admin_notification');
							echo  form_open('sub_admin/send_notification',$attributes);
							?>


							<fieldset>
									<div class="form-group">
									<label class="">Message</label>
									
									<textarea class="form-control" rows="4" id="send-txt" name="message"></textarea>
									
									</div>
									
									<div class="form-group">
										<label class="col-md-12">Select Users</label>
										<select id="users" class="form-controls" name="multiselect[]" multiple="multiple"></select>
										<p class="note"></p>
									</div>
							</fieldset>

							<div class="form-actions">
								<button type="submit" name="submit" class="btn btn-primary">
									Submit
								</button>
							</div>
						<?php echo form_close();?>
						
					</div>
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>

	</div>

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

		var usersss   = [];
		var userss    = [];
		var users     = [];
		var user_s    = [];
		var groupname = [];
		var grup      = [];
		var grupss    = [];
		var groupnamess = new Array();
		var grouplist = {};

		$.ajax({
		url: 'get_group_list',
		type: 'POST',
		async:false,
		dataType:"json",
		success: function (data) {
			groupnamess=data;
		for(var i in groupnamess)
		{
		 grup[i]=groupnamess[i];
		 userss.push({
				label:groupnamess[i],
				value:groupnamess[i]
			  });
			   var current_name = groupnamess[i]
			  current_name = current_name.replace(/\s+/g, '');
		$('<optgroup label='+groupnamess[i]+' id='+current_name+'>').appendTo('#users')
		grupss.push(groupnamess[i]);
		}
		for(var i in grupss)
        {
			$.ajax({
				url: 'get_user_list',
				type: 'POST',
				async:false,
				dataType:"json",
				data:{'name':groupnamess[i]},	
				success: function (data) {
					if(data!='[]')
					{
						var group=groupnamess[i];
						var current_grp = group.replace(/\s+/g, '');
						grouplist[group]=new Array();
						
						var usrs=data;
						for(var j in usrs)
						{
							grouplist[group].push(usrs[j]);
							user_s[j] = usrs[j].replace('#','@');
							$('<option value="'+usrs[j]+'">'+user_s[j]+'</option>').appendTo('#'+current_grp+'');
							
						}
						
					}
				},
				error:function(XMLHttpRequest, textStatus, errorThrown)
				{
					console.log('error', errorThrown);
				}
			});	
			
		}	

		users=userss;
		
		},
		error:function(XMLHttpRequest, textStatus, errorThrown)
		{
		 console.log('error', errorThrown);
		}
	});

	$("#users").multiselect({
			nonSelectedText: 'Select Users',
			enableClickableOptGroups:true,
			includeSelectAllOption: true,
			enableFiltering: true,
			enableCaseInsensitiveFiltering: true,
			maxHeight: 300,
			buttonWidth: '250px'
        });
	})

</script>
<script>
 $(function(){
    $('#sub_admin_notification').submit(function(){
	    var Length = $("#send-txt").val().length;
		var userslen = $("#users").val();
		
		if(Length==0)
		{
		    $.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
				content : "Message should not be empty",
				color : "#C46A69",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
            return false;
		
		}
		 
        if(userslen == null)
		{
            $.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp; Message",
				content : "Select any user to send notification",
				color : "#C46A69",
				iconSmall : "fa fa-bell bounce animated",
				timeout : 4000
			});
             return false;
        }
		
    });
}); 
</script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>