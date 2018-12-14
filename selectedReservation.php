<?php
	include_once 'header.php';
?>

<section class="main-container">
	<div class="main-wrapper">
		<h2>Selected Reservation</h2>
		<?php
			if (isset($_SESSION['userID'])) {
				$name =  $_SESSION['userName'];
				$surname = $_SESSION['userSurname'];
				$role = $_SESSION['userRole'];
				echo '<p>Logged in as '.$name.' '.$surname.'. Role: '.$role.'</p>';
			}
			include_once 'includes/dbh.inc.php';

			$id = $_GET['id'];

			//Created a template
			$sql = "SELECT * FROM rooms WHERE roomID = ?";
			//Create a prepared statement
			$stmt = mysqli_stmt_init($conn);
			//Prepare the prepared statement
			if (!mysqli_stmt_prepare($stmt, $sql)) {
				echo "SQL error at selectedReservation roomID check";
				header("Location: reserve.php?select=error");
				exit();
			}
			//Bind parameters to the palceholder
			mysqli_stmt_bind_param($stmt, 's', $id);
			//Run parameters inside databse
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			if ($row = $result->fetch_assoc()) {
				echo '<form class="reservation-form" action="includes/requestReserve.inc.php?id='.$id.'" method="POST">
					<p>Room Name</p>
					<input type="text" name="roomName" placeholder="Room Name" value="'.$row['roomName'].'" readonly="readonly">
					<p>Room Size</p>
					<input type="text" name="roomSize" placeholder="Room Size" value="'.$row['roomSize'].'" readonly="readonly">
					<p>Room Price (€'.$row['roomPrice'].' per night)</p>
					<input type="text" name="roomPrice" placeholder="Room Price" value="€'.$row['roomPrice'].'" readonly="readonly">
					<p>Check-In Date</p>
					<input type="date" id="checkIn" name="checkIn" value="'.date('Y-m-d').'">
					<p>Check-Out Date</p>
					<input type="date" id="checkOut" name="checkOut" value="'.date("Y-m-d", strtotime('tomorrow')).'">
					<img src="'.$row['roomPictureURL'].'">
					<button type="submit" name="submit">Reserve</button>
				</form>'
				;
			}

		?>
	</div>
</section>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script>
function updatePrice(checkOut, checkIn) {
	today = new Date();
	checkIn = new Date(checkIn);
	checkOut = new Date(checkOut);
	if (checkIn<checkOut && (checkIn.getFullYear()>=today.getFullYear() && checkIn.getMonth()>=today.getMonth() && checkIn.getDate()>=today.getDate())) {
		utcIn = Date.UTC(checkIn.getFullYear(), checkIn.getMonth(), checkIn.getDate());
		utcOut = Date.UTC(checkOut.getUTCFullYear(), checkOut.getMonth(), checkOut.getDate());
		nights = Math.floor((utcOut-utcIn)/(1000*60*60*24));
		price = <?php echo $row['roomPrice']; ?>;
		$('input[name=roomPrice]').val('€'+price*nights);
	} else
		$('input[name=roomPrice]').val('Wrong check-in/out dates');
}
$('#checkOut').change(function() {
    var checkOut = $("#checkOut").val();
    var checkIn = $("#checkIn").val();
    updatePrice(checkOut, checkIn);
});
$('#checkIn').change(function() {
    var checkOut = $("#checkOut").val();
    var checkIn = $("#checkIn").val();
    updatePrice(checkOut, checkIn);
});
</script>
<?php
	include_once 'footer.php';
?>