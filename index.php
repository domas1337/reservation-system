<?php
	include_once 'header.php';
?>

<section class="main-container">
	<div class="main-wrapper">
		<h2>Pagrindinis</h2>
		<?php
			if (isset($_SESSION['userID'])) {
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$role = $_SESSION['userRole'];
				echo '<p>Prisijungta kaip '.$name.' '.$surname.'. Rolė: '.$role.'</p>';
			}
		?>
	</div>
</section>
<?php
	include_once 'footer.php';
?>
