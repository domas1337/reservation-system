<?php
	include_once 'header.php';
?>

<section class="main-container">
	<div class="main-wrapper">
		<h2>Rezervuoti</h2>
		<?php
			if (isset($_SESSION['userID'])) {
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$role = $_SESSION['userRole'];
				echo '<p>Prisijungta kaip '.$name.' '.$surname.'. Rolė: '.$role.'</p>';
			} else
				echo "Norint rezervuoti, privaloma prisijungti!";

			include_once 'includes/dbh.inc.php';

			$sql = "SELECT * FROM rooms WHERE enabled = 1";
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				echo '<table class="users-table" name="usersTable">
					<tr class="header" name="header">
						<th>Paveikslėlis</th>
						<th>Pavadinimas</th>
						<th>Dydis</th>
						<th>Kaina</th>
				</tr>';
				while ($row = $result->fetch_assoc()) {
					echo '<tr class="clickable-row" data-href="selectedReservation.php?id='.$row['roomID'].'">
							<th><img src="'.$row['roomPictureURL'].'"></th>
							<th>'.$row['roomName'].'</th>
							<th>'.$row['roomSize'].'</th>
							<th>'.$row['roomPrice'].'</th>
					</tr>';
				}
			}
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
