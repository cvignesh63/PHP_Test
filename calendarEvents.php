<?php
include 'calender.php';
$calendar = new Calendar();

$action = $_POST['action'];
if($action == "getEvents")
{
	echo $calendar->getDateEvents();
} else if($action == "updateEvent")
{
	echo $calendar->updateEvent();
} else if($action == "saveEvent")
{
	echo $calendar->saveDateEvent();
}

?>