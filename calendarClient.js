function showEvents(date)
{
	if(typeof date=="undefined"||date=="")
		return;
	$("#EventsDiv").empty();
	
	eventsData = getEvents(date);
	updateEvents(eventsData.data);
	$("#eventDate").text(date);
	$("#popupDiv").slideDown();
}

var sampleData="{data:[{title:'title1',description:'desc1',time:'time1'},{title:'title2',description:'desc1',time:'time1'},{title:'title3',description:'desc1',time:'time1'}]}";
function getEvents(date)
{
	var message;
		$.ajax({
			url: 'calendarEvents.php',
			type: 'POST',
			async:false,
			data: {action:"getEvents",date:date},
			success: function(jsonData){
				message = JSON.parse(jsonData);
			},
			error: function(){
			}
		});
	return message;
}

function updateEvents(data)
{
	for(i=0;i<data.length;i++)
	{
		event = data[i];
		var content = '<div class="popupEventDiv" id="'+event.eventId+'">'+
			'<div>'+
				'<div>'+event.title+'</div><div class="link" align="right" onclick="editEvent(this)" style="margin-top:-12px" >[Edit]</div>'+
				'<div> '+event.description+' </div>'+
				'<div align="right">'+event.time+'</div>'+
			'</div>'+
			'<div style="display:none">'+
				'<div style="margin-top:5px">Title: <input type="text" value="'+event.title+'" id="titleText" /></div>'+
				'<div style="margin-top:5px">Desc: <input type="text" value="'+event.description+'" id="descText" /></div>'+
				'<div style="margin-top:5px">Time: <input type="text" value="'+event.time+'" id="timeText" /></div>'+
				'<div style="margin-top:5px"> <input type="button" value="Update" onclick="updateEvent(\''+event.eventId+'\')" style="margin-left:10px" /></div>'+
			'</div>'+
			'</div>'+
		'<hr/>';
		$("#EventsDiv").append(content);
	}
}

function updateEvent(eventId)
{
	var message;
	var title = $("#titleText").val();
	var desc = $("#descText").val();
	var time = $("#timeText").val();
	$.ajax({
		url: 'calendarEvents.php',
		type: 'POST',
		async:false,
		data: {action:"updateEvent",eventId:eventId, title: title, description:desc, time:time},
		success: function(jsonData){
			message = jsonData;
		},
		error: function(){
		}
	});
	showEvents($("#eventDate").text());
}

function editEvent(element)
{
	$(element).parent().parent().children().last().show();
	$(element).parent().parent().children().first().hide();
}

function addEvent()
{
	var content = '<div align="left" style="padding:5px">'+
					'<div style="padding:10px">Title: <input type="text" id="addTitle" /></div>'+
					'<div style="padding:10px">Desc: <input type="text" id="addDesc" /></div>'+
					'<div style="padding:10px">Time: <input type="text" id="addTime" /> * please enter this in hh:mm:ss format</div>'+
					'<div style="padding-left:100px"> <input type="button" value="Add" onclick="addEventToDB()"/></div>'+
				'</div><hr/>';
	$("#EventsDiv").prepend($("#EventsDiv").first(),content);

}

function addEventToDB()
{
	var message;
		$.ajax({
			url: 'calendarEvents.php',
			type: 'POST',
			async:false,
			data: {action:"saveEvent", title:$("#addTitle").val(),desc:$("#addDesc").val(),time:$("#addTime").val(),date:$("#eventDate").text()},
			success: function(jsonData){
				message = jsonData;
			},
			error: function(){
			}
		});
	showEvents($("#eventDate").text());
}

function closePopup()
{
	$("#popupDiv").hide();
}