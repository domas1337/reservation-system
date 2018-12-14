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


	$id = $_GET['id'];
	$name = mysqli_real_escape_string($conn, $_POST['userName']);
	$surname = mysqli_real_escape_string($conn, $_POST['userSurname']);
	$email = mysqli_real_escape_string($conn, $_POST['userEmail']);
	$number = mysqli_real_escape_string($conn, $_POST['userPhoneNumber']);
	$pwdnew = mysqli_real_escape_string($conn, $_POST['userNewPassword']);
	$role = mysqli_real_escape_string($conn, $_POST['userRole']);
	$enabled = mysqli_real_escape_string($conn, $_POST['enabled']);

	//Error handlers
	//Check for empty fields
	if (empty($name) || empty($surname) || empty($email) || empty($number)) {
		header("Location: ../adminEditAccount.php?id=".$id."&edit=empty");
		exit();
	}
	//Check if input characters are valid
	if (!preg_match("/^[a-zA-Z]*$/", $name) || !preg_match("/^[a-zA-Z]*$/", $surname) || !preg_match('/^\(?\+?([0-9]{1,4})\)?[-\. ]?(\d{3})[-\. ]?([0-9]{5})$/', trim($number))) {
		header("Location: ../adminEditAccount.php?id=".$id."&edit=invalid");
		exit();
	}
	//Check if email is valid
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		header("Location: ../adminEditAccount.php?id=".$id."&edit=invalidEmail");
		exit();
	}
	//Check if Enabled or Disabled
	if ($enabled != 'Enabled' && $enabled != 'Disabled') {
		header("Location: ../adminEditAccount.php?id=".$id."&edit=invalidEnabled");
		exit();
	} else if ($enabled == 'Enabled')
		$enabled = 1;
	else
		$enabled = 0;

	//Created a template
	$sql = "SELECT * FROM users WHERE userEmail=?";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at editAccount Email check";
		header("Location: ../adminEditAccount.php?id=".$id."&edit=error");
		exit();
	}
	//Bind parameters to the placeholder
	mysqli_stmt_bind_param($stmt, 's', $email);
	//Run parameters inside database
	mysqli_stmt_execute($stmt);
	//Get query result
	$result = mysqli_stmt_get_result($stmt);
	//Get array
	$currentRow = mysqli_fetch_assoc($result);
	//Get user's current email
	$currentEmail = $currentRow['userEmail'];

	//Created a template
	$sql = "SELECT * FROM users WHERE userEmail=?";
	//Create a prepared statement
	$stmt = mysqli_stmt_init($conn);
	//Prepare the prepared statement
	if (!mysqli_stmt_prepare($stmt, $sql)) {
		echo "SQL error at editAccount Email check";
		header("Location: ../adminEditAccount.php?id=".$id."&edit=error");
		exit();
	}
	//Bind parameters to the placeholder
	mysqli_stmt_bind_param($stmt, 's', $email);
	//Run parameters inside database
	mysqli_stmt_execute($stmt);
	//Get query result
	$result = mysqli_stmt_get_result($stmt);
	//Make sure that there is a result and make sure that the new email isn't taken
	if (mysqli_num_rows($result) > 0 && $currentEmail != $email) {
		header("Location: ../adminEditAccount.php?id=".$id."&edit=takenEmail");
		exit();
	}

	//Check if there is an array and to set it to $row
	if ($row = mysqli_fetch_assoc($result)) {

		//Checking if a new password input is empty
		if (empty($pwdnew))
			//Setting to the current password
			$hashedPwd = $row['userPassword'];
		else
			//Hashing new password
			$hashedPwd = password_hash($pwdnew, PASSWORD_DEFAULT);

		//Created a template
		$sql = "UPDATE users SET userName = ?, userSurname = ?, userEmail = ?, userPassword = ?, userPhoneNumber = ?, roleName = ?, enabled = ? WHERE users.userID = '$id'";
		//Create a prepared statement
		$stmt = mysqli_stmt_init($conn);
		//Prepare the prepared statement
		if (!mysqli_stmt_prepare($stmt, $sql)) {
			echo "SQL error at editing, updating user in database";
			header("Location: ../editAccount.php?id=".$id."&edit=error");
			exit();
		}
		//Bind parameters to the placeholder
		mysqli_stmt_bind_param($stmt, "ssssssi", $name, $surname, $email, $hashedPwd, $number, $role, $enabled);
		//Run parameters inside database
		mysqli_stmt_execute($stmt);
		header("Location: ../adminEditAccount.php?id=".$id."&edit=success");
	} else
		header("Location: ../adminEditAccount.php?id=".$id."&edit=error");

} else
	header("Location: ../index.php");