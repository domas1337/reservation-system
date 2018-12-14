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

	$verifyID = $_GET['id'];

	//Created a template
	$sql = "DELETE FROM toverify_rooms_users WHERE verifyID=?";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at delete toverify";
		header("Location: ../viewUnverifiedReservation.php?id=".$verifyID."&delete=error");
		exit();
	}
	//Bind parameters to the placeholder 
	mysqli_stmt_bind_param($stmt, 's', $verifyID);
	//Run parameters inside databse
	mysqli_stmt_execute($stmt);
	header("Location: ../viewUnverifiedReservations.php?delete=success");

} else 
	header("Location: ../index.php");