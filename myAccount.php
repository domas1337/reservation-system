<?php
	include_once 'header.php';
?>
<section class="main-container">
	<div class="main-wrapper">
		<?php
			if (isset($_SESSION['userID'])) {
				echo '<h2>My account</h2>';
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$role = $_SESSION['userRole'];
				echo '<p>Logged in as '.$name.' '.$surname.'. Role: '.$role.'</p>';
				echo '<form class="settings-form" action="editAccount.php">
					<button type="submit" name="adminEditAccount">Edit account</button>
				</form>';
				if ($_SESSION['userRole'] == 'Guest') {
					echo '<form class="settings-form" action="viewDocuments.php">
						<button type="submit" name="viewDocuments">View documents</button>
					</form>';
					echo '<form class="settings-form" action="viewUnverifiedReservations.php">
						<button type="submit" name="viewUnverifiedReservations">View unverified reservations</button>
					</form>';
				}
			} else
				header("Location: index.php");
		?>
	</div>

</section>
<?php
	include_once 'footer.php';
?>