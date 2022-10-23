<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Assign Events";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["events"]["sub"]["assign_calendar"]["active"] = true;
include("inc/nav.php");

?>
<style>
.bootstrap-timepicker-widget
{
	z-index:1051;
}
</style>
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
		
			<div class="col-sm-12 col-md-12 col-lg-3">
				<!-- new widget -->
				<div class="jarviswidget jarviswidget-color-blueDark">
					<header>
						<h2> Add Events </h2>
					</header>
		
					<!-- widget div-->
					<div>
		
						<div class="widget-body">
							<!-- content goes here -->
		
							<form id="add-event-form">
								<fieldset>
		
									<div class="form-group">
										<label>Select Event Icon</label>
										<div class="btn-group btn-group-sm btn-group-justified" data-toggle="buttons">
											<label class="btn btn-default active">
												<input type="radio" name="iconselect" id="icon-1" value="fa-info" checked>
												<i class="fa fa-info text-muted"></i> </label>
											<label class="btn btn-default">
												<input type="radio" name="iconselect" id="icon-2" value="fa-warning">
												<i class="fa fa-warning text-muted"></i> </label>
											<label class="btn btn-default">
												<input type="radio" name="iconselect" id="icon-3" value="fa-check">
												<i class="fa fa-check text-muted"></i> </label>
											<label class="btn btn-default">
												<input type="radio" name="iconselect" id="icon-4" value="fa-user">
												<i class="fa fa-user text-muted"></i> </label>
											<label class="btn btn-default">
												<input type="radio" name="iconselect" id="icon-5" value="fa-lock">
												<i class="fa fa-lock text-muted"></i> </label>
											<label class="btn btn-default">
												<input type="radio" name="iconselect" id="icon-6" value="fa-clock-o">
												<i class="fa fa-clock-o text-muted"></i> </label>
										</div>
									</div>
		
									<div class="form-group">
										<label>Event Title</label>
										<input class="form-control"  id="title" name="title" maxlength="40" type="text" placeholder="Event Title">
									</div>
									<div class="form-group">
										<label>Event Description</label>
										<textarea class="form-control" placeholder="Please be brief" rows="3" maxlength="40" id="description"></textarea>
										<p class="note">Maxlength is set to 40 characters</p>
									</div>
									
									<div class="form-group">
										<label class="">Select Users</label>
										<select id="users" name="multiselect[]" multiple="multiple"></select>
										<p class="note"></p>
									</div>
									<div class="form-group">
										<label>Select Event Form</label>
										<select id="event_forms" name="multiselect[]"></select>
										<p class="note"></p>
										<input type="hidden" id="selected_event" class="" sel_eve="<?php echo $event;?>" val="<?php echo $event;?>"/>
									</div>
		     						<div class="form-group">
										<label>Event Start Time</label>
											<input type="text" name="start_time" id="event_time" class="timepicker form-control">
									</div>
									<div class="form-group">
										<label>Event End Time</label>
											<input type="text" name="end_time" id="event_end_time" class="timepicker form-control">
									</div>
									<div class="form-group">
										<label>Event Place</label>
										<input class="form-control"  id="event_place" name="place" maxlength="40" type="text" placeholder="Event Place">
									</div>
									<div class="form-group">
										<label>Select Event Color</label>
										<div class="btn-group btn-group-justified btn-select-tick" data-toggle="buttons">
											<label class="btn bg-color-darken active">
												<input type="radio" name="priority" id="option1" value="bg-color-darken txt-color-white" checked>
												<i class="fa fa-check txt-color-white"></i> </label>
											<label class="btn bg-color-blue">
												<input type="radio" name="priority" id="option2" value="bg-color-blue txt-color-white">
												<i class="fa fa-check txt-color-white"></i> </label>
											<label class="btn bg-color-orange">
												<input type="radio" name="priority" id="option3" value="bg-color-orange txt-color-white">
												<i class="fa fa-check txt-color-white"></i> </label>
											<label class="btn bg-color-greenLight">
												<input type="radio" name="priority" id="option4" value="bg-color-greenLight txt-color-white">
												<i class="fa fa-check txt-color-white"></i> </label>
											<label class="btn bg-color-blueLight">
												<input type="radio" name="priority" id="option5" value="bg-color-blueLight txt-color-white">
												<i class="fa fa-check txt-color-white"></i> </label>
											<label class="btn bg-color-red">
												<input type="radio" name="priority" id="option6" value="bg-color-red txt-color-white">
												<i class="fa fa-check txt-color-white"></i> </label>
										</div>
									</div>
		
								</fieldset>
								<div class="form-actions">
									<div class="row">
										<div class="col-md-12">
											<button class="btn btn-default" type="button" id="add-event" >
												Add Event
											</button>
										</div>
									</div>
								</div>
							</form>
		
							<!-- end content -->
						</div>
		
					</div>
					<!-- end widget div -->
				</div>
				<!-- end widget -->
		
				<div class="well well-sm" id="event-container">
					<form>
						<fieldset>
							<legend>
								Draggable Events
							</legend>
							<ul id='external-events' class="list-unstyled">
							</ul>
							<div class="checkbox">
								<label>
									<input type="checkbox" id="drop-remove" class="checkbox style-0" checked="checked">
									<span>remove after drop</span> </label>
			
							</div>
						</fieldset>
					</form>
		
				</div>
			</div>
			<div class="col-sm-12 col-md-12 col-lg-9">
		
				<!-- new widget -->
				<div class="jarviswidget jarviswidget-color-blueDark">
		
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
						<span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
						<h2> My Events </h2>
						<div class="widget-toolbar">

						</div>
					</header>
		
					<!-- widget div-->
					<div>
		
						<div class="widget-body no-padding">
							<div class="widget-body-toolbar">
		</div>
		<br><br>
        <div>
							<div id="calendar"><div id="deletecalendar"><div id ="delcal" class="jarviswidget jarviswidget-color-blueDark hide">
					<header>
						<h2> Edit Event</h2>
					</header>
					<!-- widget div-->
					<div>
		
						<div class="widget-body">
							<!-- content goes here -->
		
							<form id="add-eventt-form">
								<fieldset>
		
									<div class="form-group">
										<input class="form-control"  id="update-title" name="title" maxlength="40" type="text" placeholder="Event Title">
									</div>
									
									<div class="form-group">
										<textarea class="form-control" placeholder="Please be brief" rows="3" maxlength="40" id="update-description"></textarea>
									</div>
									
									<div class="form-group">
										<div class="col-md-6" style="padding-left: 0px;">
										<select class="form-control" id="selected" style="padding-left: 0px;"></select></div>
										<label class="status">Status:</label>
										<label class="user-status"></label>
									</div>
									<div class="form-group">
										<label class="">Event form:&nbsp;&nbsp;<label>
										<label class="" id="event_name"><label>
									</div>	
									<div class="form-group">
										<input type="text" name="start_time" id="event_time_edit" class="timepicker-edit form-control">
									</div>
									<div class="form-group">
										<input type="text" name="" id="event_end_time_edit" class="timepicker-edit form-control">
									</div>
									<div class="form-group">
										<input class="form-control"  id="update-place" name="place" maxlength="40" type="text" placeholder="">
									</div>
									
									</fieldset>
							</form>
		
							<!-- end content -->
						</div>
		
					</div>
					<!-- end widget div --></div></div>
					<div id="event_resize"><div id ="eventcal" class="jarviswidget jarviswidget-color-blueDark hide">
					<header>
						<h2> Event Resize</h2>
					</header>
					<!-- widget div-->
					<div>
		
						<div class="widget-body">
							<!-- content goes here -->
		
							<form id="resize-eventt-form">
								<fieldset>
									<div class="form-group">
										<label class="status">Event End Time:</label>
										<input type="text" name="" id="event_end_time_resize" class="timepicker form-control">
									</div>
								</fieldset>
							</form>
		
							<!-- end content -->
						</div>
		
					</div>
					<!-- end widget div --></div></div>
		
							<!-- end content -->
						</div>
					</div>
					<!-- end widget div -->
				</div>
				<!-- end widget -->
		
			</div>
		
		</div>
		
		<!-- end row -->

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>
<!-- PAGE RELATED PLUGIN(S) -->

<script src="<?php echo JS; ?>fullcalendar/jquery.fullcalendar.min.js"></script>
<script src="<?php echo JS; ?>bootstrap-timepicker.min.js"></script>
<script type="text/javascript">
     "use strict";
            var id = 0;
            var date = new Date();
		    var d = date.getDate();
		    var m = date.getMonth();
		    var y = date.getFullYear();
			
			$.ajax({
      		url: 'sub_admin_calendar/get_recent_event_id',
		    type: 'POST',
	  	    success: function (data) {
			if(data)
			{
			var con = JSON.parse(data);
			id = con[0].id;
			id=parseInt(id);
			}
			},
           error: function (XMLHttpRequest, textStatus, errorThrown){}
		})
		
var newSource = [];
var source = [];
$(document).ready(loadCal);
source[0] = {
                    url: 'sub_admin_calendar/get_calendar_events',
                    type: 'POST',
                    cache: true,
                    error: function() {},
                    className: 'myevent'
                };
				
				var hdr = {
		        left: 'title',
		        center: 'month,agendaWeek,agendaDay',
		        right: 'prev,today,next'
		    };
			
var initDrag = function (e) {
		        
		
		        var eventObject = {
		            title: $.trim(e.children().text()), // use the element's text as the event title
		            description: $.trim(e.children('span').attr('data-description')),
		            icon: $.trim(e.children('span').attr('data-icon')),
		            className: $.trim(e.children('span').attr('class')),
		            users: $.trim(e.children('span').attr('users')),
					event_name: $.trim(e.children('span').attr('event_name')),
					event_id: $.trim(e.children('span').attr('event_id')),
					event_time: $.trim(e.children('span').attr('event_time')),
					event_end_time: $.trim(e.children('span').attr('event_end_time')),
					event_place: $.trim(e.children('span').attr('event_place')),
                    					
		        };
		        
		        e.data('eventObject', eventObject);
		
		    e.draggable({
		            zIndex: 999,
		            revert: true, // will cause the event to go back to its
		            revertDuration: 0 //  original position after the drag
		        });
		}
var current_id='';	
 var save_calendar = function(obj)
{
	$.ajax({
      		url: 'sub_admin_calendar/save_calendar_events',
		    type: 'POST',
			'Content-Type': 'application/json',
			data:{"obj":obj},
	  	    success: function () {
			$.smallBox({
				title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
				content : "<?php echo "Event Created Successfully";?>",
				color : "#296191",
				iconSmall : "fa fa-bolt bounce animated",
				timeout : 4000
			});
			document.location.reload(true);
			},
           error: function (XMLHttpRequest, textStatus, errorThrown){}
		})

}
           var addEvent = function (title, priority, description, icon, users, event_name, event_id, event_time, event_end_time, event_place) {
		        title = title.length === 0 ? "Untitled Event" : title;
		        description = description.length === 0 ? "No Description" : description;
		        icon = icon.length === 0 ? " " : icon;
				event_time = event_time.length === 0 ? " " : event_time;
				event_end_time = event_end_time.length === 0 ? " " : event_end_time;
				event_place = event_place.length === 0 ? " " : event_place;
		        priority = priority.length === 0 ? "label label-default" : priority;
		        var html = $('<li><span class="' + priority + '" data-description="' + description + '" users="'+users+'" event_name="'+event_name+'" event_id="'+event_id+'" event_time="'+event_time+'" event_end_time="'+event_end_time+'" event_place="'+event_place+'" data-icon="' +
		            icon + '">' + title + '</span></li>').prependTo('ul#external-events').hide().fadeIn();
		
		        $("#event-container").effect("highlight", 800);
		
		        initDrag(html);
		    };
		
		    /* initialize the external events
			 -----------------------------------------------------------------*/
		
		    $('#external-events > li').each(function () {
		        initDrag($(this));
		    });
		
		    $('#add-event').click(function () {
		        var title = $('#title').val(),
		            priority = $('input:radio[name=priority]:checked').val(),
		            description = $('#description').val(),
					event_time = $('#event_time').val(),
					event_end_time = $('#event_end_time').val(),
					event_place = $('#event_place').val(),
		            icon = $('input:radio[name=iconselect]:checked').val();
			        var brands = $('#users option:selected');
			        var users = [];
			        $(brands).each(function(index, brand){
				        if($(this).val()!="multiselect-all")
				        {
			        	users.push([$(this).val()]);
				        }
			        });
					var eventss = $('#event_forms option:selected')
					var event_name, event_id;
					$(eventss).each(function(index, brand){
				        if($(this).val()!="multiselect-all")
				        {
			        	event_id = [$(this).val()];
						event_name = [$(this).text()];
				        }
			        });
				if(title!='' && description!='' && users!='[]' && event_name !== undefined && event_id !== undefined && event_place!='')
				{
					//console.log("successssssss");
					addEvent(title, priority, description, icon, users, event_name, event_id, event_time, event_end_time, event_place);
				}
				else
				{
					$.SmartMessageBox({
					title : "Alert !",
					content : "Fields should not be empty",
					buttons : '[Ok]'
					},function(ButtonPressed) {
						if (ButtonPressed === "Ok") 
						{
						 
						}
					});
				}
		    });

			
function loadCal() {
    $('#calendar').fullCalendar({
	   header: hdr,
       eventSources : [ source[0]],
		editable: true,
		droppable: true,
		drop: function (edate, allDay) { // this function is called when something is dropped
				var presentdate = $.fullCalendar.formatDate(date, "yyyy-MM-dd");
				var startofevent = $.fullCalendar.formatDate(edate, "yyyy-MM-dd");
			   if(startofevent == presentdate || startofevent > presentdate)
			   {
		            var originalEventObject = $(this).data('eventObject');
					console.log(originalEventObject);
		            var copiedEventObject = $.extend({}, originalEventObject);
		            var start = $.fullCalendar.formatDate(edate, "yyyy-MM-dd HH:mm:ss");
		            
		            copiedEventObject.start = start;
		            copiedEventObject.allDay = allDay;
					 
		             save_calendar(copiedEventObject);
					  
		            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
		
		            // is the "remove after drop" checkbox checked?
		            if ($('#drop-remove').is(':checked')) {
		                // if so, remove the element from the "Draggable Events" list
		                $(this).remove();
		            }
			   }
			   else
			   {
				    //revertFunc();
					$.smallBox({
								title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
								content : "<?php echo "You cannot place the event before the present day";?>",
								color : "#C46A69",
								iconSmall : "fa fa-thumbs-down bounce animated",
								timeout : 3000
							}); 
			   }
		        },
				select: function (start, end, allDay, users) {//alert("select");
		            var title = prompt('Event Title:');
		   
		            if (title) {
		                calendar.fullCalendar('renderEvent', {
		                        title: title,
		                        start: start,
		                        end: end,
		                        allDay: allDay,
		                        users:users
		                    }, true // make the event "stick"
		                );
		            }
		            calendar.fullCalendar('unselect');
		        },
		        //this event will trigger if the user clicks the particular event//
		  eventClick: function(calEvent, jsEvent, view) {//alert("click");
		  console.log(calEvent);
				 if(calEvent.source.className[0]=="myevent") {
					 $('#event_name').text("");
				  $('#delcal').removeClass("hide");
				  $('.user-status').text("");
                 var fid= calEvent.id;
                 current_id= fid;
                 //console.log("aaaaaassssssssssssssssssssssssss",calEvent.users)
                 var user_array=[];
                 user_array = calEvent.users;
				 var event_name = calEvent.event_name;
				 //var event_id = calEvent.event_id;
                 $('#selected').empty();
                 $('<option class="none">Select User</option>').appendTo('#selected');
                 for(var i in user_array)
                 {
                     var user_email = user_array[i].replace("#","@");
                     $('<option>'+user_email+'</option>').appendTo('#selected');
                 }
				 $('#update-title').val(calEvent.title);
				 $('#update-description').val(calEvent.description);
				 $('#event_time_edit').timepicker('setTime', calEvent.event_time);
				 $('#event_end_time_edit').timepicker('setTime', calEvent.event_end_time);
				 $('#update-place').val(calEvent.event_place);
				 $('#event_name').text(event_name);
				 $('#deletecalendar').show();
				 
			     $("#deletecalendar").dialog({
                  resizable: false,
                  height:350,
                  width:400,
                  modal: true,
                  title: 'Want you want to do?',
				  buttons: [
                 {
						text: "Close"
					  , 'class': "btn-primary"
					  , click: function() {
						  $(this).dialog("close");
						}
                },
				{
				         text: "Edit"
					  , 'class': "btn-success"
					  , click: function() {
					       var updatedtitle       = $('#update-title').val();
							 var updateddescription = $('#update-description').val();
							 var update_time = $('#event_time_edit').val();
							 var update_end_time = $('#event_end_time_edit').val();
							 var update_place = $('#update-place').val();
							 if(update_time!='' && update_place!='' && updatedtitle!='' && updateddescription!='' && update_end_time!='')
							 {
						 	 $.ajax({
                                 url:  'sub_admin_calendar/edit_event',
                                 data: 'title='+ updatedtitle+'&description='+ updateddescription +'&id='+ fid+'&user='+user_array+'&event_time='+update_time+'&event_end_time='+update_end_time+'&event_place='+update_place,
                                 type: "POST",
                                 success: function() {
                                 $.smallBox({
										title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
										content   : "<?php echo "Event edited Successfully";?>",
										color     : "#296191",
										iconSmall : "fa fa-bolt bounce animated",
										timeout   : 3000
									});
									//$(this).dialog("close");
									document.location.reload(true);
									}
									}) 
							 }
							 else
							 {
								 $.SmartMessageBox({
									title : "Alert !",
									content : "Values should not be empty",
									buttons : '[Ok]'
									}, function(ButtonPressed) {
										if (ButtonPressed === "Ok") 
										{
										 
										}
									});
							 } 
						}
				},
				{
				         text: "Delete"
					  , 'class': "btn-danger"
					  , click: function() {
					       var updatedtitle       = $('#update-title').val();
							 var updateddescription = $('#update-description').val();
							 $.ajax({
                                 url:  'sub_admin_calendar/delete_calendar_events',
                                 data: 'id='+fid+'&user='+user_array,
                                 type: "POST",
                                 success: function() {
                                 $.smallBox({
										title     : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
										content   : "<?php echo "Event Deleted Successfully";?>",
										color     : "#296191",
										iconSmall : "fa fa-bolt bounce animated",
										timeout   : 3000
									});
									$("#deletecalendar").dialog( "close" );
									$('#calendar').fullCalendar('removeEvents',fid);
									 document.location.reload(true);

							    }
							});
						  
						}
				}] 
				})
				}
				},
        eventRender: function (event, element, icon) {//alert("render");
            		if(!event.users == ""){
            			element.find('.fc-event-title').attr("users",event.users);
            		}
		            if (!event.description == "") {
		                element.find('.fc-event-title').append("<br/><span class='ultra-light'>" + event.description +
		                    "</span>");
		            }
		            if (!event.icon == "") {
		                element.find('.fc-event-title').append("<i class='air air-top-right fa " + event.icon +
		                    " '></i>");
		            }
					
				  	 
                },
	    eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc) {alert("drop");console.log(event);
		  var eventtype = event.source.className[0];
		  if(eventtype=="myevent" || eventtype=="appexpiry") {
		   var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
		   var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
		   var startofevent = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd");
		   var presentdate = $.fullCalendar.formatDate(date, "yyyy-MM-dd");
		   var users = event.users;
		   var event_name = event.event_name;
		   var event_id = event.event_id;
		   var event_place = event.event_id;
		   var event_time = event.event_time;	
		   var event_end_time = event.event_end_time;
		   if(startofevent == presentdate || startofevent > presentdate)
		   {
		 $.ajax({
		   url:  'sub_admin_calendar/update_calendar_events',
		   data: 'title='+ event.title+'&start='+ start +'&end='+ end+'&id='+ event._id+'&eventtype='+eventtype+'&users='+users+'&event_name='+event_name+'&event_id='+event_id+',&event_place='+event_place+'&event_time='+event_time+'&event_end_time='+event_end_time,
		   type: "POST",
		   success: function() {
			$.smallBox({
						title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
						content : "<?php echo "Event Updated Successfully";?>",
						color : "#296191",
						iconSmall : "fa fa-bolt bounce animated",
						timeout : 3000
					});
		   }
		   });
		   }
		   else
		   {
		    revertFunc();
			$.smallBox({
						title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
						content : "<?php echo "You cannot place the event before the present day";?>",
						color : "#C46A69",
						iconSmall : "fa fa-thumbs-down bounce animated",
						timeout : 3000
					}); 
		   }
		   }
		   else
		   {
		     revertFunc();
		     $.smallBox({
						title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
						content : "<?php echo "You cannot update the app created schedule";?>",
						color : "#C46A69",
						iconSmall : "fa fa-thumbs-down bounce animated",
						timeout : 3000
					});
		   }
   },
		 
         eventResize: function(event, dayDelta, minuteDelta, revertFunc) {//alert("resize");
		 var eventtype = event.source.className[0];
		 if(eventtype=="myevent")
		 {
			var users = event.users;
			console.log(users);
			$('#eventcal').removeClass("hide");
			$("#event_resize").show();
			$("#event_resize").dialog({
                  resizable: false,
                  height:300,
                  width:400,
                  modal: true,
                  title: 'Want you want to do?',
				  buttons: [
                 {
						text: "Close"
					  , 'class': "btn-primary"
					  , click: function() {
						  revertFunc();
						  $(this).dialog("close");
						}
                },
				{
				         text: "Ok"
					  , 'class': "btn-success"
					  , click: function() {
							 var end_time_resize = $('#event_end_time_resize').val();
							 if(end_time_resize!='')
							 {
							   var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
							   var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
								$.ajax({
							   url:  'sub_admin_calendar/update_calendar_events',
							   data: 'title='+ event.title+'&start='+ start +'&end='+ end+'&id='+ event._id+'&eventtype='+eventtype+'&end_time='+end_time_resize+'&users='+ users,
							   type: "POST",
							   success: function() {

								$.smallBox({
											title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
											content : "<?php echo "Updated Successfully";?>",
											color : "#296191",
											iconSmall : "fa fa-bolt bounce animated",
											timeout : 3000
										});
							   }
							   });
							   								
							 }
							 else
							 {
								 $.SmartMessageBox({
									title : "Alert !",
									content : "Event End Time should not be empty",
									buttons : '[Ok]'
									}, function(ButtonPressed) {
										if (ButtonPressed === "Ok") 
										{
										 
										}
									});
							 }
								$('#eventcal').addClass("hide");
								$(this).dialog("close");							 
						}
				}] 
				})
   }
   else if(eventtype=="appcreated")
   {
     revertFunc();
	 $.smallBox({
						title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
						content : "<?php echo "You cannot update the app created schedule";?>",
						color : "#C46A69",
						iconSmall : "fa fa-thumbs-down bounce animated",
						timeout : 3000
					});
   }
   else if(eventtype=="appexpiry")
   {
     revertFunc();
	 $.smallBox({
						title : "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;Message",
						content : "<?php echo "Only You can drag and drop the app expiry schedule on a particular day";?>",
						color : "#C46A69",
						iconSmall : "fa fa-thumbs-down bounce animated",
						timeout : 3000
					});
   }
   },
		 windowResize: function (event, ui) {
		            $('#calendar').fullCalendar('render');
		        }
    });
}

function reloadCal() {

newSource[0] = $('#e1').is(':checked') ? source[0] : "";
newSource[1] = $('#e2').is(':checked') ? source[1] : "";
newSource[2] = $('#e3').is(':checked') ? source[2] : "";

    $('#calendar')
        .fullCalendar('removeEventSource', source[0])
        .fullCalendar('removeEventSource', source[1])
		.fullCalendar('removeEventSource', source[2])
        .fullCalendar('refetchEvents')
        .fullCalendar('addEventSource', newSource[0])
        .fullCalendar('addEventSource', newSource[1])
		.fullCalendar('addEventSource', newSource[2])
        .fullCalendar('refetchEvents');
}

/* hide default buttons */
		    $('.fc-header-right, .fc-header-center').hide();

		
			$('#calendar-buttons #btn-prev').click(function () {
			    $('.fc-button-prev').click();
			    return false;
			});
			
			$('#calendar-buttons #btn-next').click(function () {
			    $('.fc-button-next').click();
			    return false;
			});
			
			$('#calendar-buttons #btn-today').click(function () {
			    $('.fc-button-today').click();
			    return false;
			});
			
			$('#mt').click(function () {
			    $('#calendar').fullCalendar('changeView','month');
			});
			
			$('#ag').click(function () {
			    $('#calendar').fullCalendar('changeView','agendaWeek');
			});
			
			$('#td').click(function () {
			    $('#calendar').fullCalendar('changeView','agendaDay');
			});
      		//getting the user status for event//
			$("#selected").change(function(){
			    //alert($(this).val());
			    $('.user-status').text("");
				var check = $(this).find('option:selected').attr("class");
				var user = $(this).val();
				//alert(check);
				if(check!="none")
				{
				//alert("sssss");
				 $.ajax({
				 url:  'sub_admin_calendar/get_user_events',
				 data: 'user='+ user+'&id='+ current_id,
				 type: "POST",
				 success: function(data) {
							//alert(data);
							$('.user-status').text(data);
					}
					})
				}
				});

				
</script>
<script src="<?php echo JS; ?>subadmin_calendar.js"></script>