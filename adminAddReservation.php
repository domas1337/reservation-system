<?php
	include_once 'header.php';
?>
<section class="main-container">
	<div class="main-wrapper">
		<?php
			if ($_SESSION['userRole'] == 'Admin') {

				include_once 'includes/dbh.inc.php';

				echo '<h2>Add account</h2>';
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$role = $_SESSION['userRole'];
				echo '<p>Logged in as '.$name.' '.$surname.'. Role: '.$role.'</p>';

				echo '<form class="signup-form" action="includes/adminAddReservation.inc.php" method="POST">
					<input type="text" name="roomName" placeholder="Reservation Name">
					<input type="number" name="roomSize" placeholder="Reservation Size">
					<input type="number" name="roomPrice" placeholder="Reservation Price">
					<input type="text" name="roomPictureURL" placeholder="Reservation Picture URL">
					<button type="submit" name="submit">Confirm</button>
				</form>';
			} else
				header("Location: index.php");
		?>
	</div>
</section>
<?php
	include_once 'footer.php';
?>