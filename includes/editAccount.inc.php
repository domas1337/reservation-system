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
	$number = mysqli_real_escape_string($conn, $_POST['userPhoneNumber']);
	$pwdnew = mysqli_real_escape_string($conn, $_POST['userNewPassword']);
	$pwd = mysqli_real_escape_string($conn, $_POST['userPassword']);

	//Error handlers
	//Check for empty fields
	if (empty($name) || empty($surname) || empty($email) || empty($pwd)) {
		header("Location: ../editAccount.php?edit=empty");
		exit();
	}
	//Check if input characters are valid
	if (!preg_match("/^[a-zA-Z]*$/", $name) || !preg_match("/^[a-zA-Z]*$/", $surname) || !preg_match('/^\(?\+?([0-9]{1,4})\)?[-\. ]?(\d{3})[-\. ]?([0-9]{5})$/', trim($number))) {
		header("Location: ../editAccount.php?edit=invalid");
		exit();
	}
	//Check if email is valid
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header("Location: ../editAccount.php?edit=invalidEmail");
		exit();
	}
	//Created a template
	$sql = "SELECT * FROM users WHERE userEmail=?";
	//Check if new email
	if ($_SESSION['userEmail'] != $email) {
		//Create a prepared statement
		$stmt = mysqli_stmt_init($conn);
		//Prepare the prepared statement
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			echo "SQL error at editAccount new Email check";
			header("Location: ../editAccount.php?edit=error");
			exit();
		}
		//Bind parameters to the placeholder
		mysqli_stmt_bind_param($stmt, 's', $email);
		//Run parameters inside databse
		mysqli_stmt_execute($stmt);
		//Get query result
		$result = mysqli_stmt_get_result($stmt);
		//Make sure that there is a result and make sure that the new email isn't taken
		if (mysqli_num_rows($result) > 0) {
			header("Location: ../editAccount.php?edit=takenEmail");
			exit();
		}
	}
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at editAccount current Email check";
		header("Location: ../editAccount.php?edit=error");
		exit();
	}
	//Bind parameters to the placeholder
	mysqli_stmt_bind_param($stmt, 's', $email);
	//Run parameters inside database
	mysqli_stmt_execute($stmt);
	//Get query result
	$result = mysqli_stmt_get_result($stmt);
	//Check if there is an array and to set it to $row
	if ($row = mysqli_fetch_assoc($result)) {

		//Verifying password
		$hashedPwdCheck = password_verify($pwd, $row['userPassword']);
		if ($hashedPwdCheck == false) {
			header("Location: ../editAccount.php?edit=wrongPassword");
			exit();
		}

		//Checking if a new password input is empty
		if (empty($pwdnew))
			//Setting to the current password
			$hashedPwd = $row['userPassword'];
		else
			//Hashing new password
			$hashedPwd = password_hash($pwdnew, PASSWORD_DEFAULT);

		//Gettings user's ID
		$id = $_SESSION['userID'];

		//Created a template
		$sql = "UPDATE users SET userName = ?, userSurname = ?, userEmail = ?, userPassword = ?, userPhoneNumber = ? WHERE users.userID = '$id'";
		//Create a prepared statement
		$stmt = mysqli_stmt_init($conn);
		//Prepare the prepared statement
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			echo "SQL error at editing, updating user in database";
			header("Location: ../editAccount.php?edit=error");
			exit();
		}
		//Bind parameters to the placeholder
		mysqli_stmt_bind_param($stmt, "sssss", $name, $surname, $email, $hashedPwd, $number);
		//Run parameters inside database
		mysqli_stmt_execute($stmt);

		//Updating user's session
		$_SESSION['userName'] = $name;
		$_SESSION['userSurname'] = $surname;
		$_SESSION['userEmail'] = $email;
		$_SESSION['userPhoneNumber'] = $number;

		header("Location: ../editAccount.php?edit=success");
	} else
		header("Location: ../editAccount.php?edit=error");

} else 
	header("Location: ../index.php");