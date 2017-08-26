<!-- Plugin CSS -->
<link rel="stylesheet" type="text/css" href="vendor/plugins/calendar/fullcalendar.css" media="screen">

  
    <div class="container">
      <div class="row">
        <div class="hidden-xs hidden-sm col-md-3">
          <div class="panel panel-visible">
            <div class="panel-heading">
              <div class="panel-title"> <span class="glyphicon glyphicon-pencil"></span> Create Event </div>
            </div>
            <div class="panel-body">
              <div id="external-events">
                <div class='external-event' data-length="2">Example Event 2</div>
                <div class='external-event'>Example Event 2</div>
                <div class='external-event'>Example Event 3</div>
              </div>
            </div>
            <div class="panel-footer">
              <div id="create_event" class=" margin-top">
                <form id="create-event-form">
                  <div class="form-group">
                    <input type="text" class="form-control event-name" placeholder="Event Name">
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control colorpicker margin-top-none" placeholder="Select a Color" value="">
                  </div>
                  <div class="form-group">
                    <textarea class="form-control event-desc" placeholder="Event Description"></textarea>
                  </div>
                  <div class="form-group margin-bottom-none pull-right">
                    <button type="submit" class="create-event-form btn btn-default btn-gradient">Create Event</button>
                  </div>
                  <div class="clearfix"></div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-9">
          <div class="panel panel-visible">
            <div class="panel-heading">
              <div class="panel-title"> <span class="glyphicon glyphicon-calendar"></span> Calendar </div>
            </div>
            <div class="panel-body">
              <div id='calendar'></div>
            </div>
          </div>
        </div>
      </div>
    </div>

<!-- End: Main --> 

<script type="text/javascript" src="js/fullcalendar.min.js"></script>
<!--<script type="text/javascript" src="vendor/plugins/calendar/fullcalendar.min.js"></script></script> -- Local Option -->

<!-- Plugins -->
<script type="text/javascript" src="vendor/plugins/calendar/gcal.js"></script><!-- Calendar Addon -->

<script type="text/javascript">
 jQuery(document).ready(function() {

	// Init Theme Core 	
	Core.init();
	
	// Init Calendar Plugin
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		editable: true,
		droppable: true, // this allows things to be dropped onto the calendar !!!
		drop: function(date, allDay, jsEvent, ui ) { // this function is called when something is dropped
		
			// retrieve the dropped element's stored Event Object
			var originalEventObject = $(this).data('eventObject');
			
			// we need to copy it, so that multiple events don't have a reference to the same object
			var copiedEventObject = $.extend({}, originalEventObject);
			
			// assign it the date that was reported
			copiedEventObject.start = date;
			copiedEventObject.allDay = allDay;
		
			// render the event on the calendar
			// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
			$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
			
			// is the "remove after drop" checkbox checked?
			// if so, remove the element from the "Draggable Events" list
			$(this).remove();
			
		},
		events: [
			{
				title: 'All Day Event',
				start: new Date(y, m, 9),
				color: '#008aaf '
			},
			{
				title: 'Long Event',
				start: new Date(y, m, d-5),
				end: new Date(y, m, d-3)
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: new Date(y, m, d+3, 16, 0),
				allDay: false
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: new Date(y, m, d+10, 16, 0),
				allDay: false
			},
			{
				title: 'Meeting',
				start: new Date(y, m, d, 10, 30),
				allDay: false,
				color: '#0070ab'
			},
			{
				title: 'Lunch',
				start: new Date(y, m, d, 12, 0),
				end: new Date(y, m, d, 14, 0),
				allDay: false,
				color: '#0070ab'
			},
			{
				title: 'Birthday Party',
				start: new Date(y, m, d+1, 19, 0),
				end: new Date(y, m, d+1, 22, 30),
				allDay: false
			},
			{
				title: 'Mandatory!',
				start: new Date(y, m, 22),
				color: '#d10011'
			}
		]
	});
	
	// Init external calendar events
	function FCexternals() {
	  $('#external-events div.external-event').each(function(index) {
					  
		  // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
		  // it doesn't need to have a start or end
		  var eventObject = {
			  title: $.trim($(this).text()), // use the element's text as the event title
			  color: ($(this).attr('color')),
		  };

		  // store the Event Object in the DOM element so we can get to it later
		  $(this).data('eventObject', eventObject);

		  // make the event draggable using jQuery UI
		  $(this).draggable({
			  zIndex: 999,
			  revert: true,      // will cause the event to go back to its
			  revertDuration: 0  //  original position after the drag
		  });
		  
	  });
	}

	var count = 0;
	
	// Populate custom external event with form data then
	// run externals() to repopulate event object
	$(".create-event-form").click(function( event ) {
		event.preventDefault();
		count += 1;
		var color = $("#create-event-form .colorpicker").val(),
			title1 = $("#create-event-form input").val();
			
			if (title1 === "" ) {var title1 = "Example Title";}

		
		$("#external-events").append("<div class='external-event' color='" + color + "' style='background:" + color + "'>" + title1 + "</div>");
		title1 = $("#create-event-form input").val("");
		FCexternals();				
	});

	FCexternals();

 });

</script>