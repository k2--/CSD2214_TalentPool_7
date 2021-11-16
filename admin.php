<?php
include_once 'dbconfig.php';
$flt_availability = "";
$flt_skills = "";
$flt_status = "";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$flt_availability = trim(htmlspecialchars($_POST["flt_availability"]));
	$flt_skills = trim(htmlspecialchars($_POST["flt_skills"]));
	$flt_status = trim(htmlspecialchars($_POST["flt_status"]));
}
?>

<!--CSD2214 Optional Asgmt Job Search-Kadeem View Page-->
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>View Applicants Page</title>
	<style>
		table {
		  border-collapse: collapse;
		  border: 1px solid #ddd;
		  width:95%;
		}
		th{
			text-align: left;
		}
		tr{
			border-bottom: 1px solid #ddd;
		}
		tr:hover{
			background-color: #f1f1f1;
		}
		td{
			padding-left: 3px;
			padding-right: 3px;
		}
		h1, h3{
			margin: 10px;
			color: blue;
		}
		ul, ol {
			margin-block: 0px;
			padding-left: 20px;
		}
		
		input.form, select.form{
			margin-left: 10px;
			margin-bottom: 5px;
		}
		
		label.form{
			display: inline-block;
			width: 100px;
			text-align: right;
		}
		select.form{
			display: inline-block;
			width: 170px;
		}
		.icon-delete{
			width:20px;
			height:20px;
		}
		div.form{
			display: inline-block;
		}
	</style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
	<script>
		"use strict";
		function resetForm() {
			$("#flt_skills").val("");
			$("#flt_availability").val("");
			$("#flt_status").val("");
		}
		
		$(document).ready(function(){
			//resetForm();
			$("#btn_reset").click(function(){resetForm()});
			// Setup - add a text input to each footer cell
			$('#tbl_applicants tfoot th').each( function () {
				var title = $('#tbl_applicants thead th').eq( $(this).index() ).text();
				if(title =="Job ID" || title =="First Name" || title =="Last Name" || title =="Top Skills"){
					$(this).html( '<input type="text" placeholder="Search '+title+'" />' );
				}
			});
			var table = $('#tbl_applicants').DataTable();
			table.columns().eq( 0 ).each( function ( colIdx ) {
				$( 'input', table.column( colIdx ).footer() ).keyup(function () {
				table
				.column( colIdx )
				.search( this.value )
				.draw();
				});
			});
			
			$('#tbl_applicants tbody').on( 'click', 'img.icon-delete', function () {
				var appid = $(this).attr("id")
				var deleted = $.post('deleteapplicant.php',
					{appid: appid}, 
					function(data){
						if($.trim(data) == 'success'){											
							true;
						}else{
							false;
						}
					});
				if( deleted){
					table
						.row( $(this).parents('tr') )
						.remove()
						.draw();
				}
			});			
		});
	</script>
</head>
<body>
	<main>
	<h1>View Application for Job Posting</h1>
	<br>
	<!-- Filter Form Start-->
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" name="searchbar" id="searchbar">
		<h3>Filter</h3>
		<div class='form'>
			<label class='form' for="flt_skills">Skills:</label>
			<select class='form' id="flt_skills" name="flt_skills" maxlength="30" value=<?php echo $flt_skills?>>
				<option value="">All</option>
			<?php 
			$skills_sql="SELECT skill1 as skill FROM tbl_applicants UNION SELECT skill2 as skill FROM tbl_applicants UNION SELECT skill3 as skill FROM tbl_applicants";
			$skills_stmt = $conn->prepare($skills_sql);
			$skills_stmt->execute();
			$skills_result = $skills_stmt->get_result();
			$skills_num_rows = $skills_stmt->num_rows();
			while($row=$skills_result->fetch_assoc()){
				if($flt_skills == $row['skill']){
					echo "<option selected>{$row['skill']}</option>";
				}else{
					echo "<option>{$row['skill']}</option>";
				}
			}			
			$skills_stmt->close();
			?>
			</select>
		</div>
		<div class='form'>
			<label class='form' for="flt_availability">Availability:</label>
			<select class='form' id="flt_availability" name="flt_availability" maxlength="30">
				<option value="">All</option>
				<option value="F/T" <?php if($flt_availability == 'F/T'){echo("selected");}?> >Full-Time</option>
				<option value="P/T" <?php if($flt_availability == 'P/T'){echo("selected");}?> >Part-Time</option>
			</select>
		</div>
		<div class='form'>
			<label class='form' for="flt_status">Status:</label>
			<select class='form' id="flt_status" name="flt_status" maxlength="30">
				<option value="">All</option>
				<option <?php if($flt_status == "International Student"){echo("selected");}?> >International Student</option>
				<option <?php if($flt_status == "Graduate"){echo("selected");}?> >Graduate</option>
				<option <?php if($flt_status == "Student"){echo("selected");}?> >Student</option>
				<option <?php if($flt_status == "Permanent Resident"){echo("selected");}?> >Permanent Resident</option>
			</select>
		</div>
		<br>
		<input class='form' type="submit" id="btn_submit" value="Apply Filter">
		<input class='form' type="button" id="btn_reset" value="Clear Selections">
	</form>
	<!-- Filter Form End-->
	<!-- Applied filter list -->
	Applied Filters:
	<?php
		echo "<ul>";
		if ($flt_availability != ""){
			echo "<li>Availability = '{$flt_availability}'</li>";
		}
		if ($flt_status <> ""){
			echo "<li>Status = '{$flt_status}'</li>";
		}
		if ($flt_skills <> ""){
			echo "<li>Skills = '{$flt_skills}'</li>";
		}
		echo "</ul>";
	?>
	<br>
	
	<!-- Applicant Datatable Start-->
	<table id="tbl_applicants">
	<thead>
		<tr>
			<th></th>
			<th>Job ID </th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Email Address</th>
			<th>Phone #</th>
			<th>Status</th>
			<th>Availability</th>
			<th>Top Skills</th>
			<th>Resume</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</tfoot>
	<tbody id='tbod_applicants'>
    <?php	
		$tbl_sql="SELECT * FROM tbl_applicants 
				WHERE (skill1 = ? OR skill2 = ? OR skill3 = ? OR '' = ?)
				AND (availability = ? OR '' = ?)
				AND (status = ? OR '' = ?)" ;
		$tbl_stmt = $conn->prepare($tbl_sql);
		echo $conn->error;
				
		$tbl_stmt->bind_param("ssssssss", $flt_skills, $flt_skills, $flt_skills, $flt_skills, $flt_availability, $flt_availability, $flt_status, $flt_status);
		$tbl_stmt->execute();
		$tbl_result = $tbl_stmt->get_result();
		$tbl_num_rows = 0;
		while($row=$tbl_result->fetch_assoc()){
			echo "<tr'>";
			echo "<td><div>";
			echo "<img id='{$row['appId']}' class='icon-delete' src='img/delete_icon.png' alt=''/>";
			//echo "<img onclick='editApplicant({$row['appId']});' class='img_icon' src='img/edit_icon.png' alt=''/>";
			echo "</div></td>";
			echo "<td>{$row['jobid']}</td>";
			echo "<td class='searchable'>{$row['firstname']}</td>";
			echo "<td class='searchable'>{$row['lastname']}</td>";
			echo "<td>{$row['email']}</td>";
			echo "<td>{$row['phone']}</td>";
			echo "<td class='searchable'>{$row['status']}</td>";
			echo "<td>{$row['availability']}</td>";
			echo "<td class='searchable'><ol>";
			echo "	<li>{$row['skill1']} ({$row['skill1year']} years)</li>";
			echo "	<li>{$row['skill2']} ({$row['skill2year']} years)</li>";
			echo "	<li>{$row['skill3']} ({$row['skill3year']} years)</li>";
			echo "</ol></td>";
			echo "<td><a href='{$row['resumename']}' target='_blank'>view</a></td>";
			echo "</tr>";
			$tbl_num_rows++;
		}
		$tbl_stmt->close();
		$conn->close();
	?>
	</tbody>
    </table>
	<!-- Applicant Datatable Start-->
	</main>
</body>
</html>		