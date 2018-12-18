<?php
	include_once 'header.php';
?>
<section class="main-container">
	<div class="main-wrapper">
		<?php
			if ($_SESSION['userRole'] == 'Admin') {

				echo '<h2>Administratoriaus parametrai</h2>';
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$role = $_SESSION['userRole'];
				echo '<p>Prisijungta kaip '.$name.' '.$surname.'. Rolė: '.$role.'</p>';
				echo '<form class="settings-form" action="adminAddAccount.php">
					<button type="submit" name="adminAddAccount">Pridėti paskyrą</button>
				</form>';
				echo '<form class="settings-form" action="adminEditAccounts.php">
					<button type="submit" name="adminEditAccounts">Redaguoti paskyras</button>
				</form>';
				echo '<form class="settings-form" action="adminAddReservation.php">
					<button type="submit" name="adminAddReservation">Pridėti rezervaciją</button>
				</form>';
				echo '<form class="settings-form" action="adminEditReservations.php">
					<button type="submit" name="adminEditReservations">Redaguoti rezervaciją</button>
				</form>';
				echo '<form class="settings-form" action="adminViewDocuments.php">
					<button type="submit" name="adminViewDocuments">Peržiūrėti dokumentus</button>
				</form>';
			} else 
				header("Location: index.php");
		?>
	</div>
</section>
<?php
	include_once 'footer.php';
?>
