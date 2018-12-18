<?php
	include_once 'header.php';
?>
<section class="main-container">
	<div class="main-wrapper">
		<?php
			if (isset($_SESSION['userID'])) {
				echo '<h2>Mano paskyra</h2>';
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$role = $_SESSION['userRole'];
				echo '<p>Prisijungta kaip '.$name.' '.$surname.'. Rolė: '.$role.'</p>';
				echo '<form class="settings-form" action="editAccount.php">
					<button type="submit" name="adminEditAccount">Redaguoti paskyrą</button>
				</form>';
				if ($_SESSION['userRole'] == 'Guest') {
					echo '<form class="settings-form" action="viewDocuments.php">
						<button type="submit" name="viewDocuments">Peržiūrėti dokumentus</button>
					</form>';
					echo '<form class="settings-form" action="viewUnverifiedReservations.php">
						<button type="submit" name="viewUnverifiedReservations">Peržiūrėti nepatvirtintas rezervacijas</button>
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
