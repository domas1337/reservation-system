<?php
	include_once 'header.php';
?>
<section class="main-container">
	<div class="main-wrapper">
		<?php
			if ($_SESSION['userRole'] == 'Admin') {

				echo '<h2>Admin Settings</h2>';
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$role = $_SESSION['userRole'];
				echo '<p>Logged in as '.$name.' '.$surname.'. Role: '.$role.'</p>';
				echo '<form class="settings-form" action="adminAddAccount.php">
					<button type="submit" name="adminAddAccount">Add account</button>
				</form>';
				echo '<form class="settings-form" action="adminEditAccounts.php">
					<button type="submit" name="adminEditAccounts">Edit accounts</button>
				</form>';
				echo '<form class="settings-form" action="adminAddReservation.php">
					<button type="submit" name="adminAddReservation">Add reservation</button>
				</form>';
				echo '<form class="settings-form" action="adminEditReservations.php">
					<button type="submit" name="adminEditReservations">Edit reservations</button>
				</form>';
				echo '<form class="settings-form" action="adminViewDocuments.php">
					<button type="submit" name="adminViewDocuments">View documents</button>
				</form>';
			} else 
				header("Location: index.php");
		?>
	</div>
</section>
<?php
	include_once 'footer.php';
?>