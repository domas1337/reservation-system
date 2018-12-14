<?php
	include_once 'header.php';
?>

<section class="main-container">
	<div class="main-wrapper">
		<h2>Reservation</h2>
		<?php
			if (isset($_SESSION['userID'])) {
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$role = $_SESSION['userRole'];
				echo '<p>Logged in as '.$name.' '.$surname.'. Role: '.$role.'</p>';

				include_once 'includes/dbh.inc.php';

				//Created a template
				$sql = "SELECT * FROM toverify_rooms_users WHERE verifyID=? and userID=?";
				//Create a prepared statement
				$stmt = mysqli_stmt_init($conn);
				//Prepare the prepared statement
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					echo "SQL error at header.php userID check";
					header("Location: index.php?check=error");
					exit();
				}
				//Bind parameters to the placeholder
				$verifyID = $_GET['id'];
				$userID = $_SESSION['userID'];
				mysqli_stmt_bind_param($stmt, 'ss', $verifyID, $userID);
				//Run parameters inside databse
				mysqli_stmt_execute($stmt);
				//Get query result
				$result = mysqli_stmt_get_result($stmt);
				if ($row = $result->fetch_assoc()) {
					//Created a template
					$sql = "SELECT * FROM rooms WHERE roomID=?";
					//Create a prepared statement
					$stmt = mysqli_stmt_init($conn);
					//Prepare the prepared statement
					if (!mysqli_stmt_prepare($stmt, $sql)) {
						echo "SQL error at header.php userID check";
						header("Location: index.php?check=error");
						exit();
					}
					//Bind parameters to the placeholder
					$id = $row['roomID'];
					mysqli_stmt_bind_param($stmt, 's', $id);
					//Run parameters inside databse
					mysqli_stmt_execute($stmt);
					//Get query result
					$result = mysqli_stmt_get_result($stmt);
					$rowRoom = mysqli_fetch_assoc($result);
					$price = $rowRoom['roomPrice'] * ((strtotime($row['checkOut']) - strtotime($row['checkIn']))/(60*60*24)); 
					echo '<form class="reservation-form" action="includes/cancelReservation.inc.php?id='.$verifyID.'" method="POST">
						<p>Room Name</p>
						<input type="text" name="roomName" placeholder="Room Name" value="'.$rowRoom['roomName'].'" readonly="readonly">
						<p>Room Size</p>
						<input type="text" name="roomSize" placeholder="Room Size" value="'.$rowRoom['roomSize'].'" readonly="readonly">
						<p>Room Price (€'.$rowRoom['roomPrice'].' per night)</p>
						<input type="text" name="roomPrice" placeholder="Room Price" value="€'.$price.'" readonly="readonly">
						<p>Check-In Date</p>
						<input type="date" id="checkIn" name="checkIn" value="'.date('Y-m-d').'" readonly="readonly">
						<p>Check-Out Date</p>
						<input type="date" id="checkOut" name="checkOut" value="'.date("Y-m-d", strtotime('tomorrow')).'" readonly="readonly">
						<img src="'.$rowRoom['roomPictureURL'].'">
						<button type="submit" name="submit">Cancel reservation</button>
					</form>';
				} else
					header("Location: viewUnverifiedReservations.php?view=notFound"); 
			} else
				header("Location: index.php");
		?>
	</div>
</section>
<?php
	include_once 'footer.php';
?>