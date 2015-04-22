<?php
$UN = 'TimeLog';
$PW = 'TimeLogPass'; //Credentials to connect to MySQL
$DB = 'TimeLog'; //DB Name - will be changed on a db by db basis
$HOST = 'localhost';
if ( isset($_GET['inout']) ) {
	$DBConn = mysqli_connect($HOST, $UN, $PW, $DB);
	if ( !$GLOBALS['DBConn'] ) {
		echo 'Connection failed: ' . mysqli_connect_error();
		return 0;
	}
	$Query = mysqli_query($DBConn,'select clocked from current;');
	if ( !$Query ) {
		echo 'Error - MySQL error: ' . mysqli_error($DBConn) . '.';
		return 0;
	}
	$Data = mysqli_fetch_assoc($Query);
	echo $Data['clocked'];
	return 0;
}
?>
<html>
<head>
<title>Work Time Log</title>
</head>
<body>
Nothing here yet!
</body>
</html>
