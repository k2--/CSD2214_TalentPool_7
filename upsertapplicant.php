<?php
include_once 'dbconfig.php';
$txt_appid = 0;
$txt_jobid = 0;
$txt_fname = '';
$txt_lname = '';
$txt_email = '';
$txt_phone = '';
$txt_status = '';
$txt_availability = '';
$txt_skill1 = '';
$txt_skill1year = 0;
$txt_skill2 = '';
$txt_skill2year = 0;
$txt_skill3 = '';
$txt_skill3year = 0;
$msg ='';

if ($_SERVER["REQUEST_METHOD"] == "POST") {	
	// get the post records
	if(isset($_POST['txt_appid'])){
		$txt_appid = trim(htmlspecialchars($_POST['txt_appid']));
	}
	if(isset($_POST['txt_jobid'])){
		$txt_jobid = trim(htmlspecialchars($_POST['txt_jobid']));
	}
	if(isset($_POST['txt_fname'])){
		$txt_fname = trim(htmlspecialchars($_POST['txt_fname']));
	}
	if(isset($_POST['txt_lname'])){
		$txt_lname = trim(htmlspecialchars($_POST['txt_lname']));
	}
	if(isset($_POST['txt_email'])){
		$txt_email = trim(htmlspecialchars($_POST['txt_email']));
	}
	if(isset($_POST['txt_phone'])){
		$txt_phone = trim(htmlspecialchars($_POST['txt_phone']));
	}
	if(isset($_POST['txt_status'])){
		$txt_status = trim(htmlspecialchars($_POST['txt_status']));
	}
	if(isset($_POST['txt_availability'])){
		$txt_availability = trim(htmlspecialchars($_POST['txt_availability']));
	}
	if(isset($_POST['txt_skill1'])){
		$txt_skill1 = trim(htmlspecialchars($_POST['txt_skill1']));
	}
	if(isset($_POST['txt_skill1year'])){
		$txt_skill1year = trim(htmlspecialchars($_POST['txt_skill1year']));
	}
	if(isset($_POST['txt_skill2'])){
		$txt_skill2 = trim(htmlspecialchars($_POST['txt_skill2']));
	}
	if(isset($_POST['txt_skill2year'])){
		$txt_skill2year = trim(htmlspecialchars($_POST['txt_skill2year']));
	}
	if(isset($_POST['txt_skill3'])){
		$txt_skill3 = trim(htmlspecialchars($_POST['txt_skill3']));
	}
	if(isset($_POST['txt_skill3year'])){
		$txt_skill3year = trim(htmlspecialchars($_POST['txt_skill3year']));
	}
	
	// Check if file exist
	if(isset($_FILES['file_resume']) || $txt_appid == 0){
		// Check connection
		if (!$conn->connect_error) {
			
			$file = "#";
			$file_type = "";
			$filepath ="Resumes/";
			$moved = true;
			if ($txt_appid == 0){
				// setup for move to resume folder 
				$file = rand(100,10000000)."-".trim($_FILES['file_resume']['name']);
				$file_loc = $_FILES['file_resume']['tmp_name'];
				$file_type = $_FILES['file_resume']['type'];
				$filepath = $filepath.$file;
				$moved = move_uploaded_file($file_loc, $filepath);
			}
			
			// if file moved successful attempt db write
			if($moved){	
				// prep SQL query
				$sql = 
				"INSERT INTO tbl_applicants (
					appId, jobId, firstname, lastname, email, phone, status, 
					availability, resumename, resumefiletype, skill1, skill1year, 
					skill2, skill2year, skill3, skill3year) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
				ON DUPLICATE KEY UPDATE
				jobId = ?, firstname = ?, lastname = ?, email = ?, phone = ?, 
				status = ?, availability = ?, skill1 = ?, skill1year = ?, skill2 = ?, 
				skill2year = ?, skill3 = ?, skill3year  = ?;";
				
				$stmt = $conn->prepare($sql);
				$stmt->bind_param("sssssssssssssssssssssssssssss",
					$txt_appid, $txt_jobid, $txt_fname, $txt_lname, $txt_email, 
					$txt_phone, $txt_status, $txt_availability, $filepath, $file_type, 
					$txt_skill1, $txt_skill1year, $txt_skill2, $txt_skill2year, 
					$txt_skill3, $txt_skill3year, $txt_jobid, $txt_fname, $txt_lname, 
					$txt_email, $txt_phone, $txt_status, $txt_availability, 
					$txt_skill1, $txt_skill1year, $txt_skill2, $txt_skill2year, 
					$txt_skill3, $txt_skill3year
					);

				// execute query & perform action based on result 
				if ($stmt->execute()) {
					$msg = "success";
				}else {
					// if failed remove file
					unlink($filepath);
					$msg ='Error: ' . $conn->error;
				}
				$stmt->close();
			}else{
				$msg ='Error while uploading file';
			}
		}else{
			$msg = 'Error: DB Connection failed: ' . $conn->connect_error;
		}
	}else{
		$msg = 'Error: No File Uploaded';
	}
	$conn->close();
	echo $msg;
}
?>