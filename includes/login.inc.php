<?php

if (isset($_POST['submit'])) {

	//connection to database
	include 'dbh.inc.php';

	//setting variables from inputs
	$email = mysqli_real_escape_string($conn, $_POST['userEmail']);
	$pwd = mysqli_real_escape_string($conn, $_POST['userPassword']);

	//Error handlers
	//Check if inputs are empty
	if (empty($email) || empty($pwd)) {
		header("Location: ../index.php?login=empty");
		exit();
	}

	//Created a template
	$sql = "SELECT * FROM users WHERE userEmail=?";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at login Email check";
		header("Location: ../index.php?login=error");
		exit();
	}

	//Bind parameters to the palceholder
	mysqli_stmt_bind_param($stmt, 's', $email);
	//Run parameters inside databse
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) < 0) {
		header("Location: ../index.php?login=error");
		exit();
	}

	//Check if there is an array and to set it to $row
	if ($row = mysqli_fetch_assoc($result)) {
		//Verifying the password
		$hashedPwdCheck = password_verify($pwd, $row['userPassword']);
		if ($hashedPwdCheck == false) {
			header("Location: ../index.php?login=error");
			exit();
		} else if ($hashedPwdCheck == true) {
			//Log in the user here
			session_start();
			$_SESSION['userID'] = $row['userID'];
			$_SESSION['userName'] = $row['userName'];
			$_SESSION['userSurname'] = $row['userSurname'];
			$_SESSION['userEmail'] = $row['userEmail'];
			$_SESSION['userPhoneNumber'] = $row['userPhoneNumber'];
			$_SESSION['userRole'] = $row['roleName'];

			header("Location: ../index.php?login=success");
			exit();
		}
	} else
		header("Location: ../index.php?login=error");
} else
	header("Location: ../index.php?login=error");