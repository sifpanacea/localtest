<?php

//initilize the page
//require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Calendar";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["calendar"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
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
							<!-- add: non-hidden - to disable auto hide -->
							<!--<div class="btn-group">
								<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
									Showing <i class="fa fa-caret-down"></i>
								</button>
								<ul class="dropdown-menu js-status-update pull-right">
									<li>
										<a href="javascript:void(0);" id="mt">Month</a>
									</li>
									<li>
										<a href="javascript:void(0);" id="ag">Agenda</a>
									</li>
									<li>
										<a href="javascript:void(0);" id="td">Today</a>
									</li>
								</ul>
							</div>-->
						</div>
					</header>
		
					<!-- widget div-->
					<div>
		
						<div class="widget-body no-padding">
							<!-- content goes here -->
							<!--<div class="widget-body-toolbar">
		
								<div id="calendar-buttons">
		
									<div class="btn-group">
										<a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-prev"><i class="fa fa-chevron-left"></i></a>
										<a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-next"><i class="fa fa-chevron-right"></i></a>
									</div>
								</div>
							</div>-->
							<div class="widget-body-toolbar">
				<div class="well well-sm bg-color-blueDark txt-color-white text-center">
               <input type="checkbox" checked="checked" onclick="reloadCal()" name="e1" id="e1" />
                <label for="e1">
                    My Events</label>&nbsp;&nbsp;
                <input type="checkbox" checked="checked" onclick="reloadCal()" name="e2" id="e2" />
                <label for="e2">
                    App Created Schedule</label>&nbsp;&nbsp;
                <input type="checkbox" checked="checked" onclick="reloadCal()" name="e3" id="e3" />
                <label for="e3">
                   App Expiry Schedule</label>&nbsp;&nbsp;
            
            <div style="clear: both; height: 15px;">
            </div>
        </div>
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
		
							<form id="add-event-form">
								<fieldset>
		
									<div class="form-group">
										
										<input class="form-control"  id="update-title" name="title" maxlength="40" type="text" placeholder="Event Title">
									</div>
									<div class="form-group">
										
										<textarea class="form-control" placeholder="Please be brief" rows="3" maxlength="40" id="update-description"></textarea>
									</div></fieldset>
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

<script type="text/javascript">
     "use strict";
            var id = 0;
            var date = new Date();
		    var d = date.getDate();
		    var m = date.getMonth();
		    var y = date.getFullYear();
			
			$.ajax({
      		url: 'calendar/get_recent_event_id',
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
                    url: 'calendar/get_calendar_events',
                    type: 'POST',
                    cache: true,
                    error: function() {},
                    className: 'myevent'
                };
source[1] = {
                    url: 'calendar/get_appcreated_details',
                    type: 'POST',
                    cache: true,
                    error: function() {},
                    className: 'appcreated'
                };
source[2] =	{
                    url: 'calendar/get_appexpiry_details',
                    type: 'POST',
                    cache: true,
                    error: function() {},
                    className: 'appexpiry'
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
                    					// use the element's children as the event class
		        };
		        
		        e.data('eventObject', eventObject);
		
		    e.draggable({
		            zIndex: 999,
		            revert: true, // will cause the event to go back to its
		            revertDuration: 0 //  original position after the drag
		        });
		}
		
 var save_calendar = function(obj)
{
	$.ajax({
      		url: 'calendar/save_calendar_events',
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
           var addEvent = function (title, priority, description, icon) {
		        title = title.length === 0 ? "Untitled Event" : title;
		        description = description.length === 0 ? "No Description" : description;
		        icon = icon.length === 0 ? " " : icon;
		        priority = priority.length === 0 ? "label label-default" : priority;
		
		        var html = $('<li><span class="' + priority + '" data-description="' + description + '" data-icon="' +
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
		            icon = $('input:radio[name=iconselect]:checked').val();
		
		        addEvent(title, priority, description, icon);
		    });

			
function loadCal() {
    $('#calendar').fullCalendar({
	   header: hdr,
       eventSources : [ source[0],source[1],source[2]],
		editable: true,
		droppable: true,
		drop: function (date, allDay) { // this function is called when something is dropped
		
		            
		            var originalEventObject = $(this).data('eventObject');
		            var copiedEventObject = $.extend({}, originalEventObject);
		            var start = $.fullCalendar.formatDate(date, "yyyy-MM-dd HH:mm:ss");
		            
		            copiedEventObject.start = start;
		            copiedEventObject.allDay = allDay;
					 copiedEventObject.id = id+1;
		             save_calendar(copiedEventObject);
					  console.log(copiedEventObject);
		            $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
		
		            // is the "remove after drop" checkbox checked?
		            if ($('#drop-remove').is(':checked')) {
		                // if so, remove the element from the "Draggable Events" list
		                $(this).remove();
		            }
		
		        },
				select: function (start, end, allDay) {
		            var title = prompt('Event Title:');
		            if (title) {
		                calendar.fullCalendar('renderEvent', {
		                        title: title,
		                        start: start,
		                        end: end,
		                        allDay: allDay
		                    }, true // make the event "stick"
		                );
		            }
		            calendar.fullCalendar('unselect');
		        },
		  eventClick: function(calEvent, jsEvent, view) {
				 if(calEvent.source.className[0]=="myevent") {
				  $('#delcal').removeClass("hide");
                 var fid= calEvent.id;
				 $('#update-title').val(calEvent.title);
				 $('#update-description').val(calEvent.description);
				 $('#deletecalendar').show();
			  $( "#deletecalendar" ).dialog({
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
							 console.log(updatedtitle);
							 $.ajax({
                                 url:  'calendar/edit_event',
                                 data: 'title='+ updatedtitle+'&description='+ updateddescription +'&id='+ fid,
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
				},
				{
				         text: "Delete"
					  , 'class': "btn-danger"
					  , click: function() {
					       var updatedtitle       = $('#update-title').val();
							 var updateddescription = $('#update-description').val();
							 $.ajax({
                                 url:  'calendar/delete_calendar_events',
                                 data: 'id='+fid,
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
        eventRender: function (event, element, icon) {
		            if (!event.description == "") {
		                element.find('.fc-event-title').append("<br/><span class='ultra-light'>" + event.description +
		                    "</span>");
		            }
		            if (!event.icon == "") {
		                element.find('.fc-event-title').append("<i class='air air-top-right fa " + event.icon +
		                    " '></i>");
		            }
					
				  	 
                },
	    eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc) {
		  var eventtype = event.source.className[0];
		  if(eventtype=="myevent" || eventtype=="appexpiry") {
		   var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
		   var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
		   var startofevent = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd");
		   var presentdate = $.fullCalendar.formatDate(date, "yyyy-MM-dd");
		   if(startofevent == presentdate || startofevent > presentdate)
		   {
		 $.ajax({
		   url:  'calendar/update_calendar_events',
		   data: 'title='+ event.title+'&start='+ start +'&end='+ end+'&id='+ event._id+'&eventtype='+eventtype,
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
		 
         eventResize: function(event, dayDelta, minuteDelta, revertFunc) {
		 var eventtype = event.source.className[0];
		 if(eventtype=="myevent")
		 {
			  var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
   var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
    $.ajax({
   url:  'calendar/update_calendar_events',
   data: 'title='+ event.title+'&start='+ start +'&end='+ end+'&id='+ event._id+'&eventtype='+eventtype,
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
      
         
</script>