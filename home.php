<!DOCTYPE html>
<html>
<head>
<link href="style.css" type="text/css" rel="stylesheet" />
<script src="calendarClient.js" type="text/javascript"></script>
<script src="jquery.js" type="text/javascript"></script>
</head>

<body>

<h1>PHP Code - Sample Calendar</h1>
<div style="color:red">* Please click the date to see the particular date events</div>

<?php
include 'calender.php';
 
$calendar = new Calendar();
 
echo $calendar->renderCalender();
?>
<div style="position:absolute;width:1000px;height:500px;background-color:#EEE;left:150px;top:80px;display:none;border-width:1px;border-style:solid;padding:5px" id="popupDiv">
	<div onclick="closePopup()" align="right" style="margin-right:10px;margin-top:10px;cursor:pointer;color:red">
		X 
	</div>
	<div onclick="addEvent()" align="right" class="link">
		Add Event
	</div>
	<div style="font-size:16px;font-style:italic;" align="center" >
		<b>Events on date <span id="eventDate">-</span></b>
	</div>
	<hr/>
	
	<div id="EventsDiv">
	</div>
</div>

</body>
</html>