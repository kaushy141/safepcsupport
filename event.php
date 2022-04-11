<?php include("setup.php"); ?>
<?php
$timeZone = $_REQUEST['tz'];
$start = date("Y-m-d", strtotime(date("Y-m-d", strtotime($_REQUEST['start'])) . " -7 day"));
$end = date("Y-m-d", strtotime(date("Y-m-d", strtotime($_REQUEST['end'])) . " +7 day"));
$EventList = new EventList();
echo $EventList->getList($start, $end, $timeZone);
?>