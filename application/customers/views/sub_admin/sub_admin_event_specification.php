<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Event Properties";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["events"]["sub"]["user_reply"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		$breadcrumbs["Events"] = "";
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
     				<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->

							</div>
							<!-- end widget edit box -->
                                 <!-- NEW WIDGET START -->
				<article class="col-sm-12 col-md-12">
		
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-tlsGrey" id="wid-id-1" data-widget-editbutton="false">
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
							<span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
							<h2>Event Properties</h2>
		
						</header>
		
						<!-- widget div-->
						<div>
		
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
		
							</div>
							<!-- end widget edit box -->
		
							<!-- widget content -->
							
							
							
							
								<div class="tree smart-form">
									<ul>
										<li>
											<span><i class="fa fa-lg fa-folder-open"></i>&nbsp;<?php echo $event[0]['title'];?></span>
											<ul>
											<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Properties</span>
													<ul>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Description</p><i class="icon-leaf"><?php echo $event[0]['description'];?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Event Form</p><i class="icon-leaf"><?php echo $event[0]['event_name'];?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Event Starting Date</p><i class="icon-leaf"><?php echo substr($event[0]['start'], 0,10);?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Event Start Time</p><i class="icon-leaf"><?php echo $event[0]['event_end_time'];?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Event End Time</p><i class="icon-leaf"><?php echo $event[0]['event_time'];?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Event Place</p><i class="icon-leaf"><?php echo $event[0]['event_place'];?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Event Ending</p><i class="icon-leaf">
															<?php if(isset($event[0]['end']))
																	{
																		if($event[0]['end'] !='')
																		{
																		echo $event[0]['end'];
																		}else{
																			echo 'Single day event.';
																		}
																	}else
																	{
																		echo 'Single day event.';
																	};
															?></i></div>
														</li>
													</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> Event Form Properties</span>
													<ul>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Event Form</p><i class="icon-leaf"><?php echo $event_form[0]['event_name'];?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Form Description</p><i class="icon-leaf"><?php echo $event_form[0]['event_desc'];?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Expiry</p><i class="icon-leaf"><?php echo $event_form[0]['event_expiry'];?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Created by</p><i class="icon-leaf"><?php echo $event_form[0]['created_by'];?></i> at <?php echo $event_form[0]['req_time'];?></div>
														<!--<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Number of elements</p><i class="icon-leaf"><?php echo "15";?></i></div>
														</li>-->
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Number of pages</p><i class="icon-leaf"><?php echo $event_form[0]['pages'];?></i></div>
														</li>
														<li style="display:none">
															<div class="well well-sm bg-color-darken txt-color-white text-center"><p>Version</p><i class="icon-leaf"><?php echo $event_form[0]['_version'];?></i></div>
														</li>
														</li>
													</ul>
												</li>
												<li>
													<span><i class="fa fa-lg fa-plus-circle"></i> User Status</span>
													<ul>
													<li style="display:none">
														<table id="dt_basic" class="table table-striped table-bordered table-hover">
														<tr>
															<th>User ID</th>
															<th>Status</th>
															<th>Form</th>
														</tr>
														
													
													
													
													<?php foreach($user_details as $user_id => $user_details):?>
														
														
														<tr>
															<td><?php echo str_replace("#","@",$user_id); ?></td>
															<td><?php echo $user_details[0]['reply'];?></td>
															<td>
															<?php if($user_details[0]['reply'] == 'Yes' || $user_details[0]['reply'] == 'Join' ||$user_details[0]['reply'] == "May be" || $user_details[0]['reply'] == "Confirmed" || $user_details[0]['reply'] == "Commented" || $user_details[0]['reply'] == "Comment reply" || $user_details[0]['reply'] == 'Event modified')
																	{?>
																		<!--echo anchor("printer/user_form_printing/".base64_encode($user_id)."/".$user_details[0]['id'], 'View Filled Form');-->
																		<a href="#" class="view_form" id="<?php echo base64_encode($user_id);?>" events="<?php echo $user_details[0]['id'];?>">View Filled Form</a>
																	<?php }else
																	{
																		echo 'No form to display.';
																	};
															?>
															</td>
														</tr>
														
													<?php endforeach?>
													</tbody>
													</table>
													</li>	
													</ul>
												</li>
												</ul>
												</li>
												
											</ul>
										</li>
									</ul>
									
								</div>
		</div>
							<!-- end widget content -->
		
        </div><!-- ROW -->
		<div>
	<button type="button" class="btn btn-primary pull-right" onclick="window.history.back();">
											Back
										</button>
							
	</div>		
		<div class="modal fade bs-example-modal-sm" id="view_modal">
			<div class="modal-dialog" style="width:480px;height:550px;">
				<div class="modal-content">
					<div class="modal-header" style="padding:8px;">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><strong>View Form<strong></h4>
					</div>
					<div class="modal-body">
						<!-- data will be added using AJAX-->
					</div>
					<div class="modal-footer" style="padding:8px;">
						<button type="button" class="btn btn-default btn-xs" id="confirm">Confirm</button>
						<button type="button" class="btn btn-default btn-xs" id="comment">Comment</button>
						<button type="button" class="btn btn-default btn-xs" id="previous">Previous</button>
						<button type="button" class="btn btn-default btn-xs" id="next">Next</button>
					</div>	
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	</div>
	
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S)--> 
<script type="text/javascript">
	
	$(document).ready(function() {
	
		$('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
		$('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch').on('click', function(e) {
			var children = $(this).parent('li.parent_li').find(' > ul > li');
			if (children.is(':visible')) {
				children.hide('fast');
				$(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-lg fa-plus-circle');
			} else {
				children.show('fast');
				$(this).attr('title', 'Collapse this branch').find(' > i').removeClass().addClass('fa fa-lg fa-minus-circle');
			}
			e.stopPropagation();
		});

		//
		//AJAX call for stroke background image
		//
		var prev_id;
		var user_id;
		var events_id;
		$(document).on('click','.view_form',function (e)
		{
			e.preventDefault()
			//alert("d");
			var id = $(this).attr('id');
			user_id = id;
			//alert(prev_id);
			if(prev_id != id)
			{
				prev_id = id;
				var events = $(this).attr('events');
				events_id = events;
				console.log(id);
				console.log(events);
				$.ajax({
					url: '../../printer/user_form_printing_event/'+id+'/'+events,
					type: 'POST',
					async:true,
					timeout: 10000 ,
					beforeSend: function()
					{
						
					},
					success: function (data) 
					{
					    console.log(data);
						var result_=JSON.parse(data);
						var image_length = result_.imag_str.length;
						$('.modal-body').empty();
						for(i=0;i<image_length;i++)
						{
							console.log("2222222222222222222222222222222222222222222222222",result_.imag_str[i].print_image)
							var j=i;
							j++;
							$('<div class="image_results" id="'+j+'"><img src="'+result_.imag_str[i].print_image+'" height="550" width="450"/></div>').appendTo('.modal-body');
							$('.image_results').hide();
							console.log("aaaaaaaaaaaa")
						}
						var show_id = 1;
						show_dialog(show_id);
					},
					error: function (XMLHttpRequest, textStatus, errorThrown)
					{
					 console.log('error', errorThrown);
					}
				})//ajax end//GIFT-1132-YFR09-POAP GIFT-REGIS-OGKSV-MUBU
			
			}
			else
			{	
				if($('.modal-body').children('div').hasClass('comments'))
				{
					$('#comment').show()
					$('#confirm').show()
					$('#previous').show()
					$('#next').show()
					$('.modal-body').children('.comments').hide();
				}
				var show_id = 1;
				$('.image_results').hide();
				show_dialog(show_id);
			}
			function show_dialog(show_id)
			{	
				$('#'+show_id+'').show();
				$('.image_results').removeClass("active");
				$('#'+show_id+'').addClass("active");
				$('#view_modal').modal("show");
			}

		})//view form end..

		$(document).on('click','#next',function()
		{
			var current_id = $('.modal-body').children('.active').attr('id');
			var image_div_length = $('.modal-body').children('div').length;
			//console.log(image_div_length)
			if(current_id < image_div_length)
			{
				$('.image_results').removeClass("active");
				$('#'+current_id+'').hide();
				$('#'+current_id+'').next('div').show();
				$('#'+current_id+'').next('div').addClass("active")
			}
			else
			{
				console.log("No more pages");
			}
		});//next end..

		$(document).on('click','#previous',function()
		{
			var current_id = $('.modal-body').children('.active').attr('id');
			if(current_id != "1")
			{
				$('.image_results').removeClass("active");
				$('#'+current_id+'').hide();
				$('#'+current_id+'').prev('div').show();
				$('#'+current_id+'').prev('div').addClass("active")
			}
			else
			{
				console.log("No more pages");
			}			
		});//previous end..
		
		$(document).on('click','#confirm',function()
		{
			var that = $(this)
			$.ajax({
				url:'../../sub_admin/event_confirm/'+user_id+'/'+events_id,
				type:'POST',
				success:function(data)
				{
					window.location.reload();
					alert(data)
					$(that).parents('td').children('td').text(data);
					$('#view_modal').modal("hide");
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)
				{
					console.log('error', errorThrown);
				}
			})
		});// confirm end......,....
		
		$(document).on('click','#comment',function()
		{	
			$('.image_results').hide();
			if($('.modal-body').children('div').hasClass('comments'))
			{
				$('#comment').hide()
				$('#confirm').hide()
				$('#previous').hide()
				$('#next').hide()
				$('.modal-body').children('.comments').show();
			}
			else
			{
			$('<div class="comments" id="comments"><div class="form-group"><label class="">Comment</label><textarea class="form-control" id="comments_text" rows="4" name="message"></textarea></div><div class="form-group"><button class="btn btn-default btn-xs pull-right" id="submit_comment">submit</button></div></div>').appendTo('.modal-body');
				$('#comment').hide()
				$('#confirm').hide()
				$('#previous').hide()
				$('#next').hide()
			}
		});
		
		$(document).on('click','#submit_comment',function()
		{
			var comment = $('#comments_text').val();
			$.ajax({
				url:'../../sub_admin/event_comment/'+user_id+'/'+events_id,
				type:'POST',
				data:{'comments':comment},
				success:function()
				{
					window.location.reload();
				},
				error: function (XMLHttpRequest, textStatus, errorThrown)
				{
					console.log('error', errorThrown);
				}
				
			})
		})			
	})

</script>
<!--<script src="..."></script>-->
<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->



<?php 
	//include footer
	include("inc/footer.php"); 
?>