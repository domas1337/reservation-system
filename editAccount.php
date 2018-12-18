<?php
	include_once 'header.php';
?>
<section class="main-container">
	<div class="main-wrapper">
		<?php
			if (isset($_SESSION['userID'])) {

				echo '<h2>Redaguoti paskyrą</h2>';
				$id = $_SESSION['userID'];
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$email = $_SESSION['userEmail'];
				$phoneNumber = $_SESSION['userPhoneNumber'];
				$role = $_SESSION['userRole'];
				echo '<p>Prisijungta kaip '.$name.' '.$surname.'. Rolė: '.$role.'</p>';
				echo '<form class="signup-form" action="includes/editAccount.inc.php" method="POST">
					<input type="text" name="userName" placeholder="Vardas" value="'.$name.'">
					<input type="text" name="userSurname" placeholder="Pavardė" value="'.$surname.'">
					<input type="text" name="userEmail" placeholder="Elektroninis paštas" value="'.$email.'">
					<input type="text" name="userPhoneNumber" placeholder="(+370) 623 12345" value="'.$phoneNumber.'">
					<input type="password" name="userNewPassword" placeholder="Naujas slaptažodis (Neprivaloma)">
					<input type="password" name="userPassword" placeholder="Dabartinis slaptažodis">
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
