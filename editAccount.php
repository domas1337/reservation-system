<?php
	include_once 'header.php';
?>
<section class="main-container">
	<div class="main-wrapper">
		<?php
			if (isset($_SESSION['userID'])) {

				echo '<h2>Edit account</h2>';
				$id = $_SESSION['userID'];
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$email = $_SESSION['userEmail'];
				$phoneNumber = $_SESSION['userPhoneNumber'];
				$role = $_SESSION['userRole'];
				echo '<p>Logged in as '.$name.' '.$surname.'. Role: '.$role.'</p>';
				echo '<form class="signup-form" action="includes/editAccount.inc.php" method="POST">
					<input type="text" name="userName" placeholder="Name" value="'.$name.'">
					<input type="text" name="userSurname" placeholder="Surname" value="'.$surname.'">
					<input type="text" name="userEmail" placeholder="E-mail" value="'.$email.'">
					<input type="text" name="userPhoneNumber" placeholder="(+123) 123 12345" value="'.$phoneNumber.'">
					<input type="password" name="userNewPassword" placeholder="New password (Optional)">
					<input type="password" name="userPassword" placeholder="Current password">
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