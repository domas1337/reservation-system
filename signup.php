<?php
	include_once 'header.php';
?>

<section class="main-container">
	<div class="main-wrapper">
		<?php
			if (isset($_SESSION['userID']))
				echo '<h2>You already have an account</h2>';
			else {
				echo '<h2>Sign up</h2>';
				echo '<form class="signup-form" action="includes/signup.inc.php" method="POST">
					<input type="text" name="userName" placeholder="First name">
					<input type="text" name="userSurname" placeholder="Surname">
					<input type="text" name="userEmail" placeholder="E-mail">
					<input type="password" name="userPassword" placeholder="Password">
					<input type="text" name="userPhoneNumber" placeholder="(+123) 123 12345">
					<button type="submit" name="submit">Confirm</button>
				</form>';
			}
		?>
	</div>
</section>
<?php
	include_once 'footer.php';
?>