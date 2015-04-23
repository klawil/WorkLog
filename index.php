<?php
// 0 - Out
// 1 - In
function ClockIO($Direction, $Time = NULL, $Date = NULL) {
	if ( $Time == NULL ) {
		$Time = time();
	}
	if ( $Date == NULL ) {
		$Date = date('Y-m-d');
	}
	$DBConn = mysqli_connect($GLOBALS['HOST'], $GLOBALS['UN'], $GLOBALS['PW'], $GLOBALS['DB']);
	if ( !$DBConn ) {
		echo 'Connection failed: ' . mysqli_connect_error();
		return 0;
	}
	$ClockVar = (int)(1 - $Direction);
	if ( $ClockVar == 0 ) {
		$Query = mysqli_query($DBConn,'select TimeIn from current');
		if ( !$Query ) {
			echo 'Error - MySQL error: ' . mysqli_error($DBConn) . '.';
			return 0;
		}
		$Data = mysqli_fetch_assoc($Query);
		$TimeIn = $Data['TimeIn'];
		$Query = mysqli_query($DBConn,'select * from Days where Date="' . $Date . '";');
		if ( !$Query ) {
			echo 'Error - MySQL error: ' . mysqli_error($DBConn) . '.';
			return 0;
		}
		$PreWorked = 0;
		$Update = 0;
		if ( mysqli_num_rows($Query) == 1 ) {
			$Data = mysqli_fetch_assoc($Query);
			$PreWorked = $Data['Worked'];
			if ( $PreWorked == NULL ) {
				$PreWorked = 0;
			}
			$Update = 1;
		}
		$TimeWorked = $Time - $TimeIn;
		//echo 'TimeWorked: ' . $TimeWorked . ';';
		$HoursWorked = ceil($TimeWorked * 4 / 3600)/4 + $PreWorked;
		if ( $Update == 1 ) {
			$QueryString = 'update Days set Worked="' . $HoursWorked . '" where Date="' . $Date . '";';
		} else {
			$QueryString = 'insert into Days set Worked="' . $HoursWorked . '", Date="' . $Date . '";';
		}
		$Query = mysqli_query($DBConn,$QueryString);
		if ( !$Query ) {
			echo 'Error - MySQL error: ' . mysqli_error($DBConn) . '.';
			return 0;
		}
		$Time = NULL;
		echo $HoursWorked . ' hours worked today - ';
	}
	$Query = mysqli_query($DBConn,'update current set clocked="' . $ClockVar . '", TimeIn="' . $Time . '";');
	if ( !$Query ) {
		echo 'Error - MySQL error: ' . mysqli_error($DBConn) . '.';
		return 0;
	}
}
$GLOBALS['UN'] = 'TimeLog';
$GLOBALS['PW'] = 'TimeLogPass'; //Credentials to connect to MySQL
$GLOBALS['DB'] = 'TimeLog'; //DB Name - will be changed on a db by db basis
$GLOBALS['HOST'] = 'localhost';
if ( isset($_GET['inout']) ) {
	$DBConn = mysqli_connect($GLOBALS['HOST'], $GLOBALS['UN'], $GLOBALS['PW'], $GLOBALS['DB']);
	if ( !$DBConn ) {
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
} elseif ( isset($_GET['clock']) ) {
	if ( $_GET['clock'] == '1' ) {
		ClockIO(1);
		echo 'Clocked out.';
	} elseif ( $_GET['clock'] == '0' ) {
		ClockIO(0);
		echo 'Clocked in.';
	} else {
		echo 'Incorrect clock variable.';
	}
	return 0;
}
?>
<html>
<head>
<title>Work Time Log</title>
</head>
<body>
<?php echo ceil(4/3600)/4; ?>
<br>Nothing here yet!
</body>
</html>
