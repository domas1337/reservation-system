<?php

if (isset($_POST['submit'])) {
	$roomID = $_GET['id'];
	session_start();
	if (isset($_SESSION['userID']) == FALSE) {
		header("Location: ../selectedReservation.php?id=".$roomID."&reserve=notLoggedIn.php");
		exit();
	}
	$userID = $_SESSION['userID'];

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
	}

	$checkIn = mysqli_real_escape_string($conn, $_POST['checkIn']);
	$checkOut = mysqli_real_escape_string($conn, $_POST['checkOut']);

	if ($checkIn == $checkOut || $checkOut < $checkIn || $checkIn < date('Y-m-d')) {
		header("Location: ../selectedReservation.php?id=".$roomID."&reserve=wrongDates");
		exit();
	}

	//Created a template
	$sql = "SELECT * FROM rooms WHERE roomID=?";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error";
		header("Location: ../selectedReservation.php?id=".$roomID."&reserve=error");
		exit();
	}
	//Bind parameters to the placeholder
	mysqli_stmt_bind_param($stmt, 's', $roomID);
	//Run parameters inside databse
	mysqli_stmt_execute($stmt);
	//Get query result
	$result = mysqli_stmt_get_result($stmt);
	//Checks if there's a result
	if (mysqli_num_rows($result) != 1) {
		header("Location: ../selectedReservation.php?id=".$roomID."&reserve=roomNotFound");
		exit();
	}

	//Created a template
	$sql = "SELECT * FROM rooms_users WHERE roomID=?";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error";
		header("Location: ../selectedReservation.php?id=".$roomID."&reserve=error");
		exit();
	}
	//Bind parameters to the placeholder
	mysqli_stmt_bind_param($stmt, 's', $roomID);
	//Run parameters inside databse
	mysqli_stmt_execute($stmt);
	//Get query result
	$result = mysqli_stmt_get_result($stmt);
	//Checks if there's a result
	if (mysqli_num_rows($result) > 0) {
		while ($row = $result->fetch_assoc()) {
			if ($checkIn < $row['checkOut'] && $checkOut > $row['checkIn']) {
				header("Location: ../selectedReservation.php?id=".$roomID."&reserve=dateOverlaps");
				exit();
			}
		}
	}

	//Created a template
	$sql = "SELECT * FROM toverify_rooms_users WHERE roomID=? AND userID=?";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at selectedReservation.php";
		header("Location: ../selectedReservation.php?id=".$roomID."&reserve=error");
		exit();
	}
	//Bind parameters to the placeholder
	mysqli_stmt_bind_param($stmt, 'ss', $roomID, $userID);
	//Run parameters inside databse
	mysqli_stmt_execute($stmt);
	//Get query result
	$result = mysqli_stmt_get_result($stmt);
	//Checks if there's a result
	if (mysqli_num_rows($result) > 0) {
		while ($row = $result->fetch_assoc()) {
			if ($checkIn < $row['checkOut'] && $checkOut > $row['checkIn']) {
				header("Location: ../selectedReservation.php?id=".$roomID."&reserve=alreadyRequested");
				exit();
			}
		}
	}


	//Created a template
	$sql = "INSERT INTO toverify_rooms_users (roomID, userID, checkIn, checkOut) VALUES (?, ?, ?, ?)";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at insert rooms_users";
		header("Location: ../selectedReservation.php?id=".$roomID."&reserve=error");
		exit();
	}
	//Bind parameters to the placeholder
	mysqli_stmt_bind_param($stmt, 'ssss', $roomID, $userID, $checkIn, $checkOut);
	//Run parameters inside databse
	mysqli_stmt_execute($stmt);

	header("Location: ../selectedReservation.php?id=".$roomID."&reserve=success");

} else 
	header("Location: ../index.php");