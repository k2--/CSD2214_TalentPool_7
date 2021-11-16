<?php
	include_once 'dbconfig.php';
	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$email = trim(htmlspecialchars($_POST["email"]));
		$jobid = htmlspecialchars($_POST["jobid"]);
		// Check connection
		if (!$conn->connect_error) {
			$sql = "SELECT * FROM tbl_applicants WHERE jobid = ? AND email = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("is", $jobid, $email);
			$result = $stmt->execute();
			$stmt->store_result();
			$num_rows = $stmt->num_rows();
			if($num_rows >0){ 
				echo "true";
			}	
			$stmt->close();
		}else{
			echo $conn->connect_error;
		}
		$conn->close();
	}	
?>