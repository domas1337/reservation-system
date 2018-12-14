<?php
	include_once 'header.php';
?>
<section class="main-container">
	<div class="main-wrapper">
		<?php
			if ($_SESSION['userRole'] == 'Admin') {

				include_once 'includes/dbh.inc.php';

				echo '<h2>Edit account</h2>';

				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$role = $_SESSION['userRole'];
				echo '<p>Logged in as '.$name.' '.$surname.'. Role: '.$role.'</p>';

				$id = $_GET['id'];

				//Template
				$sql = "SELECT * FROM users WHERE userID=?";
				//Initiate statement
				$stmt = mysqli_stmt_init($conn);
				//Prepare the prepared statement
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					echo "SQL error at adminEditAccount userID check";
					header("Location: adminEditAccount.php?edit=error");
					exit();
				}
				//Bind parameters to the palceholder
				mysqli_stmt_bind_param($stmt, 's', $id);
				//Run parameters inside databse
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				if (mysqli_num_rows($result) == 0) 
					header("Location: ../adminEditAccount.php?edit=notFound");
				else if ($row = mysqli_fetch_assoc($result)) {
					if ($row['roleName'] == 'Guest')
						$guest = 'selected';
					else
						$guest = '';
					if ($row['roleName'] == 'Junior')
						$junior = 'selected';
					else
						$junior = '';
					if ($row['roleName'] == 'Controller')
						$controller = 'selected';
					else
						$controller = '';
					if ($row['roleName'] == 'Admin')
						$admin = 'selected';
					else
						$admin = '';
					if ($row['enabled'] == 1) 
						$enabled = 'selected';
					else
						$enabled = '';
					if ($row['enabled'] == 0) 
						$disabled = 'selected';
					else
						$disabled = '';
					echo '<form class="signup-form" action="includes/adminEditAccount.inc.php?id='.$id.'" method="POST">
						<input type="text" name="userName" placeholder="Name" value="'.$row['userName'].'">
						<input type="text" name="userSurname" placeholder="Surname" value="'.$row['userSurname'].'">
						<input type="text" name="userEmail" placeholder="E-mail" value="'.$row['userEmail'].'">
						<input type="text" name="userPhoneNumber" placeholder="(+123) 123 12345" value="'.$row['userPhoneNumber'].'">
						<input type="password" name="userNewPassword" placeholder="New password (Optional)">
						<select name="userRole">
							<option value="Guest"'.$guest.'>Guest</option>
							<option value="Junior"'.$junior.'>Junior</option>
							<option value="Controller"'.$controller.'>Controller</option>
							<option value="Admin"'.$admin.'>Admin</option>
						</select>
						<select name="enabled">
							<option value="Enabled"'.$enabled.'>Enabled</option>
							<option value="Disabled"'.$disabled.'>Disabled</option>
						</select>
						<button type="submit" name="submit">Confirm</button>
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