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

				echo '<form class="signup-form" action="includes/adminAddAccount.inc.php" method="POST">
					<input type="text" name="userName" placeholder="First name">
					<input type="text" name="userSurname" placeholder="Surname">
					<input type="text" name="userEmail" placeholder="E-mail">
					<input type="password" name="userPassword" placeholder="Password">
					<input type="text" name="userPhoneNumber" placeholder="(+123) 123 12345">
					<select name="userRole">
						<option value="Guest">Guest</option>
						<option value="Junior">Junior</option>
						<option value="Controller">Controller</option>
						<option value="Admin">Admin</option>
					</select>
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