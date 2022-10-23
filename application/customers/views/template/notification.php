<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "App Design";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["designtemplate"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<style>
.article_notify
{
padding-left:0px;
}
.article_right
{
padding-right:0px;
}
.select_div
{
padding-left:13px;
padding-right:13px;
}
</style>
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">
    <div class="row">
    <article class="col-sm-12 col-lg-12 col-xs-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-2" data-widget-editbutton="false" data-widget-deletebutton="false">
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
                        <span class="widget-icon"> <i class="fa fa-paste"></i> </span>
							<h2>Application Design</h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							<div class="widget-body fuelux">
		
								<div class="wizard">
									<ul class="steps">
										<li data-target="" class="">
											<span class="badge">1</span>App Properties<span class="chevron"></span>
										</li>
										<li data-target="" class="">
											<span class="badge">2</span>App Design<span class="chevron"></span>
										</li>
										<li data-target="" >
											<span class="badge">3</span>Work Flow<span class="chevron"></span>
										</li>
                                        <li data-target="" class="active">
											<span class="badge">4</span>App Notifications<span class="chevron"></span>
										</li>
										<!--<li data-target="#step4">
											<span class="badge">4</span>Step 4<span class="chevron"></span>
										</li>
										<li data-target="#step5">
											<span class="badge">5</span>Step 5<span class="chevron"></span>
										</li>-->
									</ul>
									<div class="actions">
										<!--<button type="button" class="btn btn-sm btn-primary btn-skip">
											<i class="fa fa-arrow-right"></i>Skip
										</button>-->
                                        <div class="submit"><a href="../dashboard/to_dashboard" class="btn btn-sm btn-primary btn-skip" style="line-height:1.5">Skip
											<i class="fa fa-angle-double-right"></i>
										</a>
										<button type="submit" name="submit1" class="btn btn-sm btn-success" id="formsub" data-last="">
											Finish<i class="fa fa-arrow-right"></i>
										</button></div>
									</div>
								</div>
								<div class="step-content" style="padding-top:10px">
								<article class="col-sm-6 col-md-6 col-lg-6 article_notify">
								<div class="panel panel-default">
								  <div class="panel-heading">
									<h3 class="panel-title">Notification to form users</h3>
								  </div>
								  <div class="panel-body">
								  <div class="smart-form">
								  <fieldset>
								  <section>
											<label class="label">Select stage</label>
											<label class="select">
												<select class="stage_select">
												<option value=''>Select</option>
                                                <?php foreach($custom_notification as $stage_name => $element_list):?>
									            <option value="<?php echo $stage_name;?>"><?php echo $stage_name;?></option>
									            <?php endforeach?>
												</select> <i></i> </label>
										</section>
										</fieldset>
										</div>
										<div class="select_div">											
												<select class="to_select" id="to_select" multiple="multiple">
												</select>
										</div>
										<div class="smart-form">
										<fieldset>
										<section>
											<label class="label">Message</label>
											<label class="textarea"> 	
											<textarea rows="3" id="message_content" class="custom-scroll"></textarea> 
											</label>
											<div class="note">
											
												Characters left:<span id='txt-length-left'></span>
										    
											<label class="checkbox pull-right">
														<input type="checkbox" name="checkbox" class="elements_check">
														<i></i>Include form elements</label>
											</div>
										</section>
								  </fieldset>
								
								  <footer>
										<button type="button" class="btn btn-primary save_stage">
											Save Stage
										</button>
										<button type="button" class="btn btn-default clear_stage">
											Clear Stage
										</button>
									</footer>
								  </div>
								  </div>
								</div>
								</article>		
								<article class="col-sm-6 col-md-6 col-lg-6 article_notify article_right">
								<div class="panel panel-default">
								  <div class="panel-heading">
									<h3 class="panel-title">Notification to workflow users</h3>
								  </div>
								  <div class="panel-body">
								  <div class="step-pane active" id="step2" style="padding-left:0px;">
                                           <?php 
										   $attributes = array('class' => 'smart-form subfrm');
    								echo form_open_multipart('code_gen/usernotification',$attributes); 
    								?>
                                    <table class="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox">
									<tbody>
									<tr>
                                    <td><label class="checkbox">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<input id="notifyemail" type="checkbox" name="emailall"  value="Send email to all"  <?php echo set_checkbox('notifyemail'); ?>>Send Email notifications to all users
												<i></i> </label></td> 
                                     <tr><td><label class="checkbox">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="notifysms" type="checkbox" name="smsall" value="Send sms to all" <?php echo set_checkbox('notifysms'); ?>>Send SMS notifications to all users <i></i> </label> </td></tr></tbody></table><hr>
                                    <table class="table table-bordered table-striped table-condensed table-hover smart-form has-tickbox"><?php $u = 0;?>
					<?php foreach($userlist as $users => $us):?> 
                   <tbody>
					<tr>
                   <td><label class="checkbox">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<input id="userlist"  type="checkbox" name="userlist[]" value="<?php echo $us?>" <?php echo set_checkbox('userlist[]'); ?>>&nbsp;<?php echo $us;?>
												<i></i> </label></td>
                                                  <td><label class="checkbox">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<input id="email" type="checkbox" name="email[]" value=<?php echo set_checkbox('email[]'); ?>>&nbsp;Send email &nbsp;&nbsp;
												<i></i> </label></td>
                                                  <td><label class="checkbox">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<input id="sms" type="checkbox" name="sms[]" value="sms" <?php echo set_checkbox('sms[]'); ?>>&nbsp;&nbsp;Send SMS
												<i></i> </label></td>
                                                </tr> 
                 
					<?php $u++;?>
					<?php endforeach;?>
                    </tbody>
                                   </table>
									<br /><br /><br />
                            
                            <input type="text" class="hide" name="app_id" id="app_id" value='<?php echo set_value('app_id', (isset($app_id)) ? $app_id : ''); ?>'/>
                            <input type="text" class="hide" name="comp_name" id="comp_name" value='<?php echo set_value('comp_name', (isset($comp_name)) ? $comp_name : ''); ?>'/>
                            <input type="text" class="hide" name="comp_addr" id="comp_addr" value='<?php echo set_value('comp_addr', (isset($comp_addr)) ? $comp_addr : ''); ?>'/>
                            <input type="text" class="hide" name="app_mod" id="app_mod" value='<?php echo set_value('app_mod', (isset($app_mod)) ? $app_mod : ''); ?>'/>
                            <input type="text" class="hide" name="app_name" id="app_name" value='<?php echo set_value('app_name', (isset($app_name)) ? $app_name : ''); ?>'/>
                            <input type="text" class="hide" name="app_des" id="app_des" value='<?php echo set_value('app_des', (isset($app_des)) ? $app_des : ''); ?>'/>
							<input type="hidden" name="sms_content" id="sms_content" value=""/>
								<?php echo form_close();?>              
	
	
                                        </div> <!--step active-->

			
		
		<div class="clearfix"></div>
        
	
								  </div>
								</div>
								</article>
											
								</div><!--/.step-container-->
       									</div>
		
								
								</div>
		
							</div>
						
				<!--		</div>
					
		
					</div>
				-->
		
				</article>
				<!-- WIDGET END -->

		</div><!--row-->
				

	</div>
	<!-- END MAIN CONTENT -->

</div>
<div class="modal fade" tabindex="-1" role="dialog" id="select_field" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
	 <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Form Elements</h4>
      </div>
	  <div class="modal-body">
	  <label>Select Element : </label>
	  <label class="select">
	  <select class="select_elem">
	  </select>
	  </label>
	  </div>
	  <div class="modal-footer">
	  <button class="btn btn-default add_elem">Add element</button>
	  <button class="btn btn-default rem_elem">Cancel</button>
	  </div>
    </div>
  </div>
</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->
<input type='hidden' id='notification' value='<?php echo set_value('notification', (isset($custom_notification)) ? json_encode($custom_notification) : ''); ?>' />
<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
<script src="<?php echo(JS.'bootstrap-multiselect.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo(JS.'notify.js'); ?>"></script>
<script src="<?php echo(JS.'wizard_wrk.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
	
	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	
$(document).ready(function() {
		
		var notify ={};
		var cursor_position;
		
		  $.ajax({
				url: '../dashboard/adminusername',
				type: 'POST',

				success: function (data) {

				users=data;
				div = "";
				user = jQuery.parseJSON(data);
				div = div + "<div>"+user+"</div>";

				$('#adminusername').html(div);
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)
				{
				console.log('error', errorThrown);
				}
		})
		
		var maxLen = 160;
        $('#txt-length-left').html(maxLen);
        $(document).on('keypress','#message_content',function(event){
            var Length = $("#message_content").val().length;
            var AmountLeft = maxLen - Length;
			if (event.which == 8 || event.which == 46) 
			{
				$('#txt-length-left').html(AmountLeft);				
			}
            $('#txt-length-left').html(AmountLeft);
            if(Length >= maxLen){
                if (event.which != 8) {
                    return false;
                }
            }
			
        });
		
		
		$("#to_select").multiselect({
			nonSelectedText: 'Select Notification Number',
			enableClickableOptGroups:true,
			includeSelectAllOption: true,
			enableFiltering: true,
			enableCaseInsensitiveFiltering: true,
			maxHeight: 200,
			buttonWidth: '100%'
        });
		
		$(document).on('click','.add_elem',function()
		{
			var selected_element = $(".select_elem option:selected").text()
			$("#message_content").insertAtCursor('#'+selected_element+'^');
			$('#select_field').modal("hide");
		})
		
		$(document).on('click','.clear_stage',function()
		{
			var notify_empty = $.isEmptyObject(notify);
			if(notify_empty!=true)
			{
				var selected_stage_clear = $( "select option:selected" ).val();
				console.log("no",selected_stage_clear)
				if(selected_stage_clear!=='')
				{
					var check_obj = notify.hasOwnProperty(selected_stage_clear);
					if(check_obj==true)
					{
						delete notify[selected_stage_clear];
						$.smallBox({
						title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
						content   : "The stage notification is successfully cleared",
						color     : "#739E72",
						iconSmall : "fa fa-bolt bounce animated",
						timeout   : 3000
						});
						
					}
					else
					{
						$.smallBox({
						title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
						content   : "The stage notification you are trying to clear is not yet created",
						color     : "#C26565",
						iconSmall : "fa fa-bolt bounce animated",
						timeout   : 3000
						});
					}
				}
			}
			else
			{
				$.smallBox({
						title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
						content   : "The stage notification you are trying to clear is not yet created",
						color     : "#C26565",
						iconSmall : "fa fa-bolt bounce animated",
						timeout   : 3000
						});
			}
		})
		
		$(document).on('click','.save_stage',function()
		{
			var selected_stg = $(".stage_select option:selected").val();
			if(selected_stg!='')
			{
				var current_obj;
				var note = $('#notification').val();
						if(note != '')
						{
							var opt ="";
							var note_ob =$.parseJSON(note);
							var current_stage = note_ob[selected_stg];
							current_obj = current_stage['elements'];
						}
				var select_to = $(".to_select").val();
				var check_list_empty = $.isEmptyObject(select_to);
				var this_mess_check = $("#message_content").val();
				
				if(this_mess_check=='')
				{
					$("#message_content").parent('label').addClass("state-error");
				}
				if(check_list_empty!= true && this_mess_check!='')
				{
					notify[selected_stg]={};
					notify[selected_stg]['send_to']=new Array();
					var selected = [];
					$(select_to).each(function(index, element){
					notify[selected_stg]['send_to'].push(element);
					})
					notify[selected_stg]['message']=new Array();
					notify[selected_stg]['full_message']=new Array();
					var this_mess = $("#message_content").val();
					if(this_mess!='')
					{
						notify[selected_stg]['full_message'].push(this_mess);
						var check_mess = this_mess.search('#')
						if(check_mess==-1)
						{
							notify[selected_stg]['message'].push({"source":"message_text","text":this_mess});
						}
						else
						{
							function string_split(mess_string)
							{
								var start_index = mess_string.indexOf("#");
								start_index = start_index+1
								var end_index = mess_string.indexOf("^");
								end_index = end_index+1
								mess_string_text = mess_string.substring(0,start_index)
								mess_string_text = mess_string_text.replace('#','');
								notify[selected_stg]['message'].push({"source":"message_text","text":mess_string_text});
								mess_string_variable = mess_string.substring(start_index,end_index)
								mess_string_variable = mess_string_variable.replace('^','');
								notify[selected_stg]['message'].push({"source":"message_variable","label":mess_string_variable,"path":current_obj[mess_string_variable]});
								var sub_string_new = mess_string.substring(end_index);
								if(sub_string_new!='')
								{
									var check_new_string = sub_string_new.search('#');
									if(check_new_string == -1)
									{
										notify[selected_stg]['message'].push({"source":"message_text","text":sub_string_new});
									}
									else
									{
										string_split(sub_string_new);
									}
								}
							}
							string_split(this_mess)
						}
						var notify_data = JSON.stringify(notify);
						$('#sms_content').val(notify_data);
						$.smallBox({
							title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
							content   : "Stage notification saved successfully.",
							color     : "#296191",
							iconSmall : "fa fa-bolt bounce animated",
							timeout   : 3000
						});
					}
				}
				$("#message_content").val('');
				$(".stage_select").val('');
				$('.to_select').empty();
				$("#to_select").multiselect('rebuild');
			}
		})
		$(document).on('click','.elements_check',function()
		{
			cursor_position = $("#message_content").getCursorPosition();
			var selected_val = $( "select option:selected" ).val();
			if(selected_val!=='')
			{
				$('.select_elem').empty()
				var checking = $('.elements_check').is(':checked');
				if(checking == true)
				{
					var selected_value = $( "select option:selected" ).val()
					if(selected_value !=='')
					{
						var note = $('#notification').val();
						if(note != '')
						{
							var opt ="";
							var note_ob =$.parseJSON(note);
							var current_stage = note_ob[selected_value];
							var current_obj = current_stage['elements'];
							for(var i in current_obj)
							{
								var label_replace = current_obj[i].replace(/ /g,'#');
								opt= opt+"<option value="+label_replace+">"+i+"</option>";
							}
							$('.select_elem').append(''+opt+'');
						}
					}
					$('#select_field').modal("show");
				}
			}
			else
			{
				$( ".stage_select" ).parent('label').addClass("state-error");
				$('.elements_check').prop('checked',false);
			}
		})
		$("#message_content").change(function ()
		{
			$("#message_content").parent('label').removeClass("state-error");
		})
		$( ".stage_select" ).change(function ()
		{	
			$("#message_content").val('');
			$( ".stage_select" ).parent('label').removeClass("state-error");
			$('.to_select').empty()
			$("#to_select").multiselect('rebuild');
			var selected_val = $( "select option:selected" ).val();
			if(selected_val!='')
			{
				var created_obj = notify.hasOwnProperty(selected_val);
				var note = $('#notification').val();
				if(note != '')
				{
					var opt = "";
					var note_ob = $.parseJSON(note);
					var current_stage = note_ob[selected_val];
					var current_obj = current_stage['to'];
					for(var i in current_obj)
					{
						var label_replace = current_obj[i]['label'].replace(/ /g,'#');
						opt= opt+"<option value="+label_replace+">"+current_obj[i]['name']+"</option>";
					}
					$('.to_select').append(''+opt+'');
					var select_child = $('.to_select').children('option').length
					if(select_child == 0)
					{
						$('#to_select').multiselect({
							nonSelectedText: 'There is no mobile number to select at this stage'
						}); 
						$("#to_select").multiselect('refresh');
					}
					$("#to_select").multiselect('rebuild');
				}
				if(created_obj===true)
				{
					var message_render = notify[selected_val].full_message;
					$("#message_content").val(message_render);
					var sendto_render = notify[selected_val].send_to;
					if(sendto_render!='')
					{
						$('#to_select').multiselect('select', sendto_render);
						$("#to_select").multiselect('refresh');
					}
				}
			}
		});
		
		$(document).on('click','.rem_elem',function()
		{
			$('#select_field').modal("hide");
		});
		$('#select_field').on('hidden.bs.modal', function (e) 
		{
			$('.elements_check').prop('checked',false);
		})
		var check_route = false;
		$(document).on('click','.btn-skip',function(e)
		{
			if(check_route == false)
			{
				e.preventDefault();
					$.SmartMessageBox
					({
						title : "Alert !",
						content : "You're about to finish this application without configuring notification to users.Continue ?",
						buttons : '[No][Yes]'
					}, function(ButtonPressed) {
						if (ButtonPressed === "Yes") 
						{
							check_route = true;
							$('.btn-skip')[0].click();
						}
						if (ButtonPressed === "No")
						{
							
						}
					});
			}
		});
		
	$(document).on("click",'#formsub', function(e)
	{
		function alert_box(message,key)
		{
			$.SmartMessageBox
			({
				title : "Alert !",
				content : message,
				buttons : '[No][Yes]'
				},
				function(ButtonPressed) 
				{
					if (ButtonPressed === "Yes") 
					{
						if(key==true)
						{
							$('.subfrm').submit();
						}
						else if(key==false)
						{
							check_route = true;
							$('.btn-skip')[0].click();
						}
					}
					if (ButtonPressed === "No")
					{
						
					}
				}
			);
		}
		
		var check_workflow_empty = $('input[name="userlist[]"]:checked').serialize();
		var check_empty_notify = $.isEmptyObject(notify);
		
		if(check_empty_notify == true && check_workflow_empty == '')
		{
			alert_box("You're about to finish this application without configuring any notification to users.Continue ?",false)
		}
		else if(check_workflow_empty == '')
		{
			alert_box("You're about to finish this application without configuring notification to workflow users.Continue ?",true)
		}
		else if(check_empty_notify == true)
		{
			alert_box("You're about to finish this application without configuring SMS notification to form users.Continue ?",true)
		}
		else
		{
			$('.subfrm').submit();
		}
	})

})

jQuery.fn.extend({
	setCursorPosition: function(position){
		if(this.length == 0) return this;
		return $(this).setSelection(position, position);
	},

	setSelection: function(selectionStart, selectionEnd) {
		if(this.length == 0) return this;
		input = this[0];

		if (input.createTextRange) {
			var range = input.createTextRange();
			range.collapse(true);
			range.moveEnd('character', selectionEnd);
			range.moveStart('character', selectionStart);
			range.select();
		} else if (input.setSelectionRange) {
			input.focus();
			input.setSelectionRange(selectionStart, selectionEnd);
		}

		return this;
	},

	focusEnd: function(){
		this.setCursorPosition(this.val().length);
		return this;
	},

	getCursorPosition: function() {
		var el = $(this).get(0);
		var pos = 0;
		if('selectionStart' in el) {
			pos = el.selectionStart;
		} else if('selection' in document) {
			el.focus();
			var Sel = document.selection.createRange();
			var SelLength = document.selection.createRange().text.length;
			Sel.moveStart('character', -el.value.length);
			pos = Sel.text.length - SelLength;
		}
		return pos;
	},

	insertAtCursor: function(myValue) {
		return this.each(function(i) {
			if (document.selection) {
			  //For browsers like Internet Explorer
			  this.focus();
			  sel = document.selection.createRange();
			  sel.text = myValue;
			  this.focus();
			}
			else if (this.selectionStart || this.selectionStart == '0') {
			  //For browsers like Firefox and Webkit based
			  var startPos = this.selectionStart;
			  var endPos = this.selectionEnd;
			  var scrollTop = this.scrollTop;
			  this.value = this.value.substring(0, startPos) + myValue + 
							this.value.substring(endPos,this.value.length);
			  this.focus();
			  this.selectionStart = startPos + myValue.length;
			  this.selectionEnd = startPos + myValue.length;
			  this.scrollTop = scrollTop;
			} else {
			  this.value += myValue;
			  this.focus();
			}
	  	})
	}
	
})
</script>
<?php 
	//include footer
	include("inc/footer.php"); 
?>