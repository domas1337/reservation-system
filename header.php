<?php
	session_start();
?>

<!DOCTYPE html>
<html>
	
	<head>
		<title>Reservations</title>
		<link rel="stylesheet" type="text/css" href="style.css?version=28">
	</head>

	<body>

		<header>
			<nav>
				<div class="main-wrapper">
					<ul>
						<li><a href="index.php">Pagrindinis</a></li>
						<li><a href="reserve.php">Rezervuoti</a></li>
					</ul>
					<div class="nav-login">
						<?php
							if (isset($_SESSION['userID'])) {

								include_once 'includes/dbh.inc.php';

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
									//Update userName session if new
									if ($_SESSION['userName'] != $row['userName'])
										$_SESSION['userName'] = $row['userName'];
									//Update userSurname session if new
									if ($_SESSION['userSurname'] != $row['userSurname'])
										$_SESSION['userSurname'] = $row['userSurname'];
									//Update userEmail session if new
									if ($_SESSION['userEmail'] != $row['userEmail'])
										$_SESSION['userEmail'] = $row['userEmail'];
									//Update userPhoneNumber session if new
									if ($_SESSION['userPhoneNumber'] != $row['userPhoneNumber'])
										$_SESSION['userPhoneNumber'] = $row['userPhoneNumber'];
									//Update userRole session if new
									if ($_SESSION['userRole'] != $row['roleName'])
										$_SESSION['userRole'] = $row['roleName'];
								} else
									header("Location: ../index.php?check=error");

								if ($_SESSION['userRole'] == 'Admin')
									echo '<form><a name="adminSettings" href="adminSettings.php">
										Administratoriaus parametrai</a></form>
									';
								if ($_SESSION['userRole'] == 'Admin' || $_SESSION['userRole'] == 'Controller' || $_SESSION['userRole'] == 'Junior')
									echo '<form class="menus"><a name="verifyReservations" href="verifyReservations.php">
										Patvirtinti rezervacijas</a></form>
									';
								if ($_SESSION['userRole'] == 'Controller')
									echo '<form class="menus"><a name="verifyReservations" href="adminViewDocuments.php">
										Peržiūrėti dokumentus</a></form>';
								echo '<form class="menus"><a href="myAccount.php" name="myAccount">
									Mano paskyra
								</a></form>';
								echo '<form action="includes/logout.inc.php" method="POST">
									<button type="submit" name="submit">Atsijungti</button>
								</form>';
							} else
								echo '<form action="includes/login.inc.php" method="POST">
									<input type="text" name="userEmail" placehorlder="Elektroninis paštas">
									<input type="password" name="userPassword" placehorlder="Slaptažodis">
									<button type="submit" name="submit">Prisijungti</button>
									</form>
								<a href="signup.php">Registruotis</a>';
						?>
					</div>
				</div>
			</nav>
		</header>
