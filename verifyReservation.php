<?php
	include_once 'header.php';
?>

<section class="main-container">
	<div class="main-wrapper">
		<h2>Verify Reservation</h2>
		<?php
			if (isset($_SESSION['userID'])) {
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$role = $_SESSION['userRole'];
				echo '<p>Prisijungta kaip '.$name.' '.$surname.'. Rolė: '.$role.'</p>';

				include_once 'includes/dbh.inc.php';

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
					header("Location: verifyReservations.php?select=error");
					exit();
				}
				//Bind parameters to the palceholder
				mysqli_stmt_bind_param($stmt, 'ssss', $roomID, $userID, $checkIn, $checkOut);
				//Run parameters inside databse
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				if (mysqli_num_rows($result) == 0) {
					header("Location: verifyReservations.php?select=notFound");
					exit();
				}

				//Created a template
				$sql = "SELECT * FROM users WHERE userID = ?";
				//Create a prepared statement
				$stmt = mysqli_stmt_init($conn);
				//Prepare the prepared statement
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					echo "SQL error at selectedReservation roomID check";
					header("Location: verifyReservations.php?select=error");
					exit();
				}
				//Bind parameters to the palceholder
				mysqli_stmt_bind_param($stmt, 's', $userID);
				//Run parameters inside databse
				mysqli_stmt_execute($stmt);

				//Created a template
				$sql = "SELECT * FROM rooms WHERE roomID = ?";
				//Create a prepared statement
				$stmt = mysqli_stmt_init($conn);
				//Prepare the prepared statement
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					echo "SQL error at selectedReservation roomID check";
					header("Location: verifyReservations.php?select=error");
					exit();
				}
				//Bind parameters to the palceholder
				mysqli_stmt_bind_param($stmt, 's', $roomID);
				//Run parameters inside databse
				mysqli_stmt_execute($stmt);
				$resultRoom = mysqli_stmt_get_result($stmt);

				//Created a template
				$sql = "SELECT * FROM users WHERE userID = ?";
				//Create a prepared statement
				$stmt = mysqli_stmt_init($conn);
				//Prepare the prepared statement
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					echo "SQL error at selectedReservation roomID check";
					header("Location: verifyReservations.php?select=error");
					exit();
				}
				//Bind parameters to the palceholder
				mysqli_stmt_bind_param($stmt, 's', $userID);
				//Run parameters inside databse
				mysqli_stmt_execute($stmt);

				$resultUser = mysqli_stmt_get_result($stmt);
				if ($row = $result->fetch_assoc()) {
					$days = (strtotime($checkOut) - strtotime($checkIn))/(60*60*24);
					if ($rowRoom = $resultRoom->fetch_assoc()) {
						$price = $rowRoom['roomPrice'] * $days;
						if ($rowUser = $resultUser->fetch_assoc()) {
							echo '<p>Vartotojui: '.$rowUser['userName'].' '.$rowUser['userSurname'].'</p>
								<form class="reservation-form" action="includes/confirmReservation.inc.php?roomID='.$roomID.'&userID='.$userID.'&checkIn='.$checkIn.'&checkOut='.$checkOut.'" method="POST">
								<p>Kambario pavadinimas</p>
								<input type="text" name="roomName" placeholder="Kambario pavadinimas" value="'.$rowRoom['roomName'].'" readonly="readonly">
								<p>Kambario dydis</p>
								<input type="text" name="roomSize" placeholder="Kambario dydis" value="'.$rowRoom['roomSize'].'" readonly="readonly">
								<p>Kambario kaina (€'.$rowRoom['roomPrice'].' už naktį)</p>
								<input type="text" name="roomPrice" placeholder="Kambario kaina" value="€'.$price.'" readonly="readonly">
								<p>Registracijos data</p>
								<input type="date" id="checkIn" name="checkIn" value="'.date('Y-m-d').'" readonly="readonly">
								<p>Išvykimo data</p>
								<input type="date" id="checkOut" name="checkOut" value="'.date("Y-m-d", strtotime('tomorrow')).'" readonly="readonly">
								<img src="'.$rowRoom['roomPictureURL'].'">
								<button type="submit" name="submit">Patvirtinti rezervaciją</button>
							</form>';
						}
					}
				}
			}
		?>
	</div>
</section>
<?php
	include_once 'footer.php';
?>
