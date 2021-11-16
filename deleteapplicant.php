<?php
include_once 'dbconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {	
	
	$appid=htmlspecialchars($_POST["appid"]);
	if (!$conn->connect_error) {
		$sltsql = "SELECT * FROM tbl_applicants WHERE appid = ?";
		$sltstmt = $conn->prepare($sltsql);
		$sltstmt->bind_param("s", $appid);
		$sltstmt->execute();
		$sltresult = $sltstmt->get_result();
		if($row = $sltresult->fetch_assoc()){			
			$filepath = $row['resumename'];
			unlink($filepath);
			$dltsql = "Delete FROM tbl_applicants WHERE appid = ?";
			$dltstmt = $conn->prepare($dltsql);
			$dltstmt->bind_param("s", $appid);
			$dltstmt->execute(); 
			$dltstmt->close();
			echo "success";
		}
		$sltstmt->close();
	}
}
$conn->close();
?>