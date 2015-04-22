<?php
if ( isset($_POST['inout']) ) {
	echo '1';
	return 0;
}
?>
<html>
<head>
<title>Work Time Log</title>
</head>
<body>
Nothing here yet!
<?php
foreach ( $_POST as $key => $value ) {
	echo $key . "=>" . $value . "
";
}
?>
</body>
</html>
