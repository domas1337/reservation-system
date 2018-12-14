<?php

if (isset($_POST['submit'])) {

	include_once 'dbh.inc.php';

	$name = mysqli_real_escape_string($conn, $_POST['userName']);
	$surname = mysqli_real_escape_string($conn, $_POST['userSurname']);
	$email = mysqli_real_escape_string($conn, $_POST['userEmail']);
	$number = mysqli_real_escape_string($conn, $_POST['userPhoneNumber']);
	$pwd = mysqli_real_escape_string($conn, $_POST['userPassword']);

	//Error handlers
	//Check for empty fields
	if (empty($name) || empty($surname) || empty($email) || empty($pwd) || empty($number)) {
		header("Location: ../signup.php?signup=empty");
		exit();
	}
	//Check if input characters are valid
	if (!preg_match("/^[a-zA-Z]*$/", $name) || !preg_match("/^[a-zA-Z]*$/", $surname) || !preg_match('/^\(?\+?([0-9]{1,4})\)?[-\. ]?(\d{3})[-\. ]?([0-9]{5})$/', trim($number))) {
		header("Location: ../signup.php?signup=invalid");
		exit();
	}
	//Check if email is valid
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header("Location: ../signup.php?signup=invalidEmail");
		exit();
	}

	//Created a template
	$sql = "SELECT * FROM users WHERE userEmail=?";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at signup Email check";
		header("Location: ../signup.php?signup=error");
		exit();
	}

	//Bind parameters to the palceholder
	mysqli_stmt_bind_param($stmt, 's', $email);
	//Run parameters inside databse
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) > 0) {
		header("Location: ../signup.php?signup=emailTaken");
		exit();
	}

	//Hashing the password
	$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
	//Insert the user into the database
	$sql = "INSERT INTO users (userName, userSurname, userEmail, userPassword, userPhoneNumber, roleName) VALUES (?, ?, ?, ?, ?, 'Guest');";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at signup inserting user to database";
		header("Location: ../signup.php?signup=error");
		exit();
	}
	mysqli_stmt_bind_param($stmt, "sssss", $name, $surname, $email, $hashedPwd, $number);
	mysqli_stmt_execute($stmt);
	header("Location: ../signup.php?signup=success");

} else
	header("Location: ../signup.php");