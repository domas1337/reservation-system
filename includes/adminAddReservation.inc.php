<?php

if (isset($_POST['submit'])) {

	session_start();

	include_once 'dbh.inc.php';

	//Created a template
	$sql = "SELECT * FROM users WHERE userID=?";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at header.php userID check";
		header("Location: index.php?check=error");
		exit();
	}
	//Bind parameters to the placeholder
	$id = $_SESSION['userID'];
	mysqli_stmt_bind_param($stmt, 's', $id);
	//Run parameters inside databse
	mysqli_stmt_execute($stmt);
	//Get query result
	$result = mysqli_stmt_get_result($stmt);
	//Check if there is an array and to set it to $row
	if ($row = mysqli_fetch_assoc($result)) {
		if ($row['enabled'] == 0) {
			session_unset();
			session_destroy();
			header("Location: index.php?disabled=true");
			exit();
		}
	} else {
		header("Location: ../index.php");
		exit();
	}

	$name = mysqli_real_escape_string($conn, $_POST['roomName']);
	$size = mysqli_real_escape_string($conn, $_POST['roomSize']);
	$price = mysqli_real_escape_string($conn, $_POST['roomPrice']);
	$url = mysqli_real_escape_string($conn, $_POST['roomPictureURL']);

	//Error handlers
	//Check for empty fields
	if (empty($name) || empty($size) || empty($price) || empty($url)) {
		header("Location: ../adminAddReservation.php?add=empty");
		exit();
	}
	//Check if input characters are valid
	if (!preg_match("/^[a-zA-Z]*$/", $name) || !preg_match("/^[0-9]*$/", $size) || !preg_match("/^[0-9]*$/", $price)) {
		header("Location: ../adminAddReservation.php?add=invalid");
		exit();
	}
	//Check if numbers are not correct size
	if ($size > 99 || $size < 1 || $price > 999 || $price < 1) {
		header("Location: ../adminAddReservation.php?add=invalidNumbers");
		exit();
	}
	//Check if $url is not a url
	if (filter_var($url, FILTER_VALIDATE_URL) == false) {
		header("Location: ../adminAddReservation.php?add=invalidUrl");
		exit();
	}

	//Created a template
	$sql = "INSERT INTO rooms (roomName, roomSize, roomPrice, roomPictureURL) VALUES (?, ?, ?, ?);";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at reservation add";
		header("Location: ../adminAddReservation.php?add=error");
		exit();
	}
	//Bind parameters to the palceholder
	mysqli_stmt_bind_param($stmt, 'ssss', $name, $size, $price, $url);
	//Run parameters inside databse
	mysqli_stmt_execute($stmt);
	header("Location: ../adminAddReservation.php?add=success");
} else
	header("Location: ../index.php");