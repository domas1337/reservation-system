<?php
	include_once 'header.php';
?>

<section class="main-container">
	<div class="main-wrapper">
		<h2>Documents</h2>
		<?php
			if (isset($_SESSION['userID'])) {
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$role = $_SESSION['userRole'];
				echo '<p>Logged in as '.$name.' '.$surname.'. Role: '.$role.'</p>';

				include_once 'includes/dbh.inc.php';

				//Created a template
				$sql = "SELECT * FROM documents WHERE userID=?";
				//Create a prepared statement
				$stmt = mysqli_stmt_init($conn);
				//Prepare the prepared statement
				if (!mysqli_stmt_prepare($stmt, $sql)) {
					echo "SQL error at header.php userID check";
					header("Location: index.php?check=error");
					exit();
				}
				//Bind parameters to the placeholder
				$id = $_SESSION['userID'];
				mysqli_stmt_bind_param($stmt, 's', $id);
				//Run parameters inside databse
				mysqli_stmt_execute($stmt);
				//Get query result
				$result = mysqli_stmt_get_result($stmt);
				if (mysqli_num_rows($result) > 0) {
					echo '<table class="documents-table" name="documents">
						<tr class="header" name="header">
							<th>RoomID</th>
							<th>CheckIn</th>
							<th>CheckOut</th>
							<th>Price</th>
					</tr>';
					while ($row = $result->fetch_assoc()) {
						echo '<tr class="documents-row" data-href="selectedReservation.php?id='.$row['roomID'].'">
								<th>'.$row['roomID'].'</th>
								<th>'.$row['checkIn'].'</th>
								<th>'.$row['checkOut'].'</th>
								<th>'.$row['price'].'</th>
						</tr>';
					}
				} else
					echo '<p>No documents</p>';
			} else
				header("Location: index.php");
		?>
	</div>
</section>
<?php
	include_once 'footer.php';
?>