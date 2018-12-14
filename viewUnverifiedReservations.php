<?php
	include_once 'header.php';
?>

<section class="main-container">
	<div class="main-wrapper">
		<h2>Unverified Reservations</h2>
		<?php
			if (isset($_SESSION['userID'])) {
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$role = $_SESSION['userRole'];
				echo '<p>Logged in as '.$name.' '.$surname.'. Role: '.$role.'</p>';

				include_once 'includes/dbh.inc.php';

				//Created a template
				$sql = "SELECT * FROM toverify_rooms_users WHERE userID=?";
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
				if (mysqli_num_rows($result) > 0) {
					echo '<table class="users-table" name="usersTable">
						<tr class="header" name="header">
							<th>RoomID</th>
							<th>CheckIn</th>
							<th>CheckOut</th>
							<th>Price</th>
					</tr>';
					while ($row = $result->fetch_assoc()) {
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
						echo '<tr class="clickable-row" data-href="viewUnverifiedReservation.php?id='.$row['verifyID'].'">
								<th>'.$row['roomID'].'</th>
								<th>'.$row['checkIn'].'</th>
								<th>'.$row['checkOut'].'</th>
								<th>'.$price.'</th>
						</tr>';
					}
				} else
					echo '<p>No unverified reservations</p>';
			} else
				header("Location: index.php");
		?>
	</div>
</section>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

<script>
	jQuery(document).ready(function($) {
	    $(".clickable-row").click(function() {
	        window.location = $(this).data("href");
	    });
	});
</script>
<?php
	include_once 'footer.php';
?>