<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "SMS DashBoard";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["sms"]["sub"]["sms_dashboard"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["SMS"] = "";
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
				<header role="heading"><h2>Send SMS</h2></header>

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
						 	$attributes = array('class' => 'sms');
							echo  form_open('sub_admin/send_sms',$attributes);
							?>
							
							<fieldset>
									<div class="form-group">
										<label class="col-md-12">Message</label>
										<textarea class="form-control" id="send-txt" rows="4" name="message"></textarea>
										<div class="note">
												Characters left:<span id='txt-length-left'></span>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-md-12">Select Users</label>
										<select id="users" class="form-controls" name="multiselect[]" multiple="multiple"></select>
										<p class="note"></p>
									</div>
							</fieldset>

							<div class="form-actions">
								<button type="" name="" id="formsub" class="btn btn-primary">
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
		var usersss = [];
		var userss = [];
		var users=[];
		var groupname=[];
		var grup=[];
		var grupss=[];
		var groupnamess=new Array();
		var grouplist={};

		var maxLen = 160;
		//var AmountLeft;
        $('#txt-length-left').html(maxLen);
        $(document).on('keypress','#send-txt',function(event){
            var Length = $("#send-txt").val().length;
            var AmountLeft = maxLen - Length;
			if (event.which == 8 || event.which == 46) 
			{
				console.log("ssssssssssssss");
					$('#txt-length-left').html(AmountLeft);				
			}
            $('#txt-length-left').html(AmountLeft);
            if(Length >= maxLen){
                if (event.which != 8) {
					console.log("ss")
                    return false;
                }
            }
			
        });
		$(document).on('click','#formsub',function(event){
           event.preventDefault();
		   var Length = $("#send-txt").val().length;
		   console.log(Length);
		   var userslen = $("#users").val();
		   console.log(userslen)
		   if(Length!=0 && userslen!=null)
		   {
				console.log("subbbbbmmmmmmmmiiiiiiiitttttttt");
			   $('.sms').submit();
		   }
		   else
		   {
			   $.SmartMessageBox({
				title : "Alert !",
				content : "Message and users should not be empty",
				buttons : '[Ok]'
			    }, function(ButtonPressed) {
					if (ButtonPressed === "Ok") 
					{
					 
					}
				});
		   }
        });
		$.ajax({
		url: 'get_group_list',
		type: 'POST',
		async:false,
		dataType:"json",
		success: function (data) {
			console.log(data)
			groupnamess=data;
			console.log("groupnameeeee",groupnamess);
		for(var i in groupnamess)
		{
		 grup[i]=groupnamess[i];
		 userss.push({
				label:groupnamess[i],
				value:groupnamess[i]
		 });
				var group=groupnamess[i];
				var current_grp = group.replace(/\s+/g, '');
			 
		$('<optgroup label="'+groupnamess[i]+'" id="'+current_grp+'">').appendTo('#users')
		grupss.push(groupnamess[i]);
		}
		for(var i in grupss)

		{
			//console.log("geeeeeeeeeeeeeeeeeeeeeeeeee",grupss[i]);
			//console.log("geeeeeeeeeeeeeeeeeeeeeeeeee",groupnamess[i]);
			 $.ajax({
				url: 'get_user_list',
				type: 'POST',
				async:false,
				dataType:"json",
				data:{'name':groupnamess[i]},	
				success: function (data) {
					console.log("55555555555555555555"+data);
					if(data!='[]')
					{
						var group=groupnamess[i];
						//console.log("cccccccccccccccccccccc"+group);
						var current_grp = group.replace(/\s+/g, '');
						grouplist[group]=new Array();
						//console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"+data);
						var usrs=data;
						for(var j in usrs)
						{
							grouplist[group].push(usrs[j]);
							$('<option value="'+usrs[j]+'">'+usrs[j]+'</option>').appendTo('#'+current_grp+'');
							//console.log("rrrrrrrrrrrrrrr"+usrs[j]);
						}
						//console.log('ooooooooooooooooooooooooooo', grouplist);
																
					}
				},
				error:function(XMLHttpRequest, textStatus, errorThrown)
				{
					console.log('error', errorThrown);
				}
			});	
			// console.log("eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee");
		}	

		users=userss;
		//console.log('successddddddd', users);
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

<?php 
	//include footer
	include("inc/footer.php"); 
?>