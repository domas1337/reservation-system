<?php
	include_once 'header.php';
?>

<section class="main-container" onload="sortTable()">
	<div class="main-wrapper">
		<h2>Verify Reservations</h2>
		<?php
			if ($_SESSION['userRole'] == 'Admin' || $_SESSION['userRole'] == 'Controller' || $_SESSION['userRole'] == 'Junior' ) {
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$role = $_SESSION['userRole'];
				echo '<p>Logged in as '.$name.' '.$surname.'. Role: '.$role.'</p>';

				include_once 'includes/dbh.inc.php';

				$sql = "SELECT * FROM toverify_rooms_users";
				$result = mysqli_query($conn, $sql);
				if (mysqli_num_rows($result) == 0)
					echo '<p> No reservations to verify</p>';
				else {
					echo '<table class="users-table" name="usersTable">
						<tr class="header" name="header">
							<th>Room ID</th>
							<th>User ID</th>
							<th>Check In</th>
							<th>Check Out</th>
					</tr>';
					while ($row = $result->fetch_assoc()) {
						echo '<tr class="clickable-row" data-href="verifyReservation.php?roomID='.$row['roomID'].'&userID='.$row['userID'].'&checkIn='.$row['checkIn'].'&checkOut='.$row['checkOut'].'">
								<th>'.$row['roomID'].'</th>
								<th>'.$row['userID'].'</th>
								<th>'.$row['checkIn'].'</th>
								<th>'.$row['checkOut'].'</th>
						</tr>';
					}
				}

			} else
				header("Location: index.php");
		?>
	</div>
</section>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

<script>
	function sortTable() {

	}
	jQuery(document).ready(function($) {
	    $(".clickable-row").click(function() {
	        window.location = $(this).data("href");
	    });
	});
</script>
<?php
	include_once 'footer.php';
?>