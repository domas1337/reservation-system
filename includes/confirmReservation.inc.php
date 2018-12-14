<?php

if (isset($_POST['submit'])) {

	session_start();

	//connection to database
	include 'dbh.inc.php';

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


	$roomID = $_GET['roomID'];
	$userID = $_GET['userID'];
	$checkIn = $_GET['checkIn'];
	$checkOut = $_GET['checkOut'];

	//Created a template
	$sql = "SELECT * FROM toverify_rooms_users WHERE roomID=? AND userID=? AND checkIn=? AND checkOut=?";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at selectedReservation roomID check";
		header("Location: ../verifyReservations.php?select=error");
		exit();
	}
	//Bind parameters to the palceholder
	mysqli_stmt_bind_param($stmt, 'ssss', $roomID, $userID, $checkIn, $checkOut);
	//Run parameters inside databse
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) == 0) {
		header("Location: ../verifyReservations.php?select=notFound1");
		exit();
	}

	//Created a template
	$sql = "SELECT * FROM rooms WHERE roomID=?";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at selectedReservation roomID check";
		header("Location: ../verifyReservations.php?select=error");
		exit();
	}
	//Bind parameters to the palceholder
	mysqli_stmt_bind_param($stmt, 's', $roomID);
	//Run parameters inside databse
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) == 0) {
		header("Location: ../verifyReservations.php?select=notFound2");
		exit();
	}

	$row = mysqli_fetch_assoc($result);
	$price = $row['roomPrice'] * ((strtotime($checkOut) - strtotime($checkIn))/(60*60*24));

	//Created a template
	$sql = "INSERT INTO documents (roomID, userID, checkIn, checkOut, price) VALUES (?, ?, ?, ?, ?)";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at insert documents";
		header("Location: ../verifyReservation.php?roomID=".$roomID.'&userID='.$userID.'&checkIn='.$checkIn.'&checkOut='.$checkOut."&reserve=error2");
		exit();
	}
	//Bind parameters to the placeholder
	mysqli_stmt_bind_param($stmt, 'sssss', $roomID, $userID, $checkIn, $checkOut, $price);
	//Run parameters inside databse
	mysqli_stmt_execute($stmt);

	//Created a template
	$sql = "INSERT INTO rooms_users (roomID, userID, checkIn, checkOut) VALUES (?, ?, ?, ?)";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at insert rooms_users";
		header("Location: ../verifyReservation.php?roomID=".$roomID.'&userID='.$userID.'&checkIn='.$checkIn.'&checkOut='.$checkOut."&reserve=error3");
		exit();
	}
	//Bind parameters to the placeholder
	mysqli_stmt_bind_param($stmt, 'ssss', $roomID, $userID, $checkIn, $checkOut);
	//Run parameters inside databse
	mysqli_stmt_execute($stmt);

	//Created a template
	$sql = "SELECT * FROM toverify_rooms_users WHERE roomID=?";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at selectedReservation roomID check";
		header("Location: ../verifyReservations.php?select=error");
		exit();
	}
	//Bind parameters to the palceholder
	mysqli_stmt_bind_param($stmt, 's', $roomID);
	//Run parameters inside databse
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		echo 'To delete:<br>';
		while($row = $result->fetch_assoc()) {
			if ($checkIn < $row['checkOut'] && $checkOut > $row['checkIn']) {
				//Created a template
				$sql = "DELETE FROM toverify_rooms_users WHERE roomID=? AND userID=? AND checkIn=? AND checkOut=?;";
				//Create a prepared statement
				$stmt = mysqli_stmt_init($conn);
				//Prepare the prepared statement
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					echo "SQL error at delete toverify";
					header("Location: ../verifyReservation.php?roomID=".$roomID.'&userID='.$userID.'&checkIn='.$checkIn.'&checkOut='.$checkOut."&delete=error");
					exit();
				}
				$rowUserID = $row['userID'];
				$rowCheckOut = $row['checkOut'];
				$rowCheckIn = $row['checkIn'];
				//Bind parameters to the placeholder
				mysqli_stmt_bind_param($stmt, 'ssss', $roomID, $rowUserID, $rowCheckIn, $rowCheckOut);
				//Run parameters inside databse
				mysqli_stmt_execute($stmt);
			}
		}
	}
	header("Location: ../verifyReservations.php?reserve=success");


} else
	header("Location: ../index.php");