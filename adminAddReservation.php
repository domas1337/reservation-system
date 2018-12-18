<?php
	include_once 'header.php';
?>
<section class="main-container">
	<div class="main-wrapper">
		<?php
			if ($_SESSION['userRole'] == 'Admin') {

				include_once 'includes/dbh.inc.php';

				echo '<h2>Pridėti paskyrą</h2>';
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$role = $_SESSION['userRole'];
				echo '<p>Prisijungta kaip '.$name.' '.$surname.'. Rolė: '.$role.'</p>';

				echo '<form class="signup-form" action="includes/adminAddReservation.inc.php" method="POST">
					<input type="text" name="roomName" placeholder="Rezervacijos pavadinimas">
					<input type="number" name="roomSize" placeholder="Rezervacijos dydis">
					<input type="number" name="roomPrice" placeholder="Rezervacijos kaina">
					<input type="text" name="roomPictureURL" placeholder="Rezervacijos paveikslėlio URL">
					<button type="submit" name="submit">Patvirtinti</button>
				</form>';
			} else
				header("Location: index.php");
		?>
	</div>
</section>
<?php
	include_once 'footer.php';
?>
