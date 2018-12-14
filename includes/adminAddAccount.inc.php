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


	$name = mysqli_real_escape_string($conn, $_POST['userName']);
	$surname = mysqli_real_escape_string($conn, $_POST['userSurname']);
	$email = mysqli_real_escape_string($conn, $_POST['userEmail']);
	$pwd = mysqli_real_escape_string($conn, $_POST['userPassword']);
	$number = mysqli_real_escape_string($conn, $_POST['userPhoneNumber']);
	$role = mysqli_real_escape_string($conn, $_POST['userRole']);

	//Error handlers
	//Check for empty fields
	if (empty($name) || empty($surname) || empty($email) || empty($pwd) || empty($number) || empty($role)) {
		header("Location: ../adminAddAccount.php?add=empty");
		exit();
	}
	//Check if input characters are valid
	if (!preg_match("/^[a-zA-Z]*$/", $name) || !preg_match("/^[a-zA-Z]*$/", $surname) || !preg_match('/^\(?\+?([0-9]{1,4})\)?[-\. ]?(\d{3})[-\. ]?([0-9]{5})$/', trim($number))) {
		header("Location: ../adminAddAccount.php?add=invalid");
		exit();
	}
	//Check if email is valid
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header("Location: ../adminAddAccount.php?add=invalidEmail");
		exit();
	}

	//Created a template
	$sql = "SELECT * FROM users WHERE userEmail=?";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at signup Email check";
		header("Location: ../adminAddAccount.php?add=error");
		exit();
	}
	//Bind parameters to the palceholder
	mysqli_stmt_bind_param($stmt, 's', $email);
	//Run parameters inside databse
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		header("Location: ../adminAddAccount.php?add=emailTaken");
		exit();
	}

	//Hashing the password
	$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
	//Insert the user into the database
	$sql = "INSERT INTO users (userName, userSurname, userEmail, userPassword, userPhoneNumber, roleName) VALUES (?, ?, ?, ?, ?, ?);";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at signup inserting user to database";
		header("Location: ../adminAddAccount.php?add=error");
	} else {
		mysqli_stmt_bind_param($stmt, "ssssss", $name, $surname, $email, $hashedPwd, $number, $role);
		mysqli_stmt_execute($stmt);
		header("Location: ../adminAddAccount.php?add=success");
	}

} else
	header("Location: ../signup.php");