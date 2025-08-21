<?php 
include("../../../conn.php");

extract($_POST);

try {
	if($gender == "0") {
		$res = array("res" => "noGender");
	} else if($course_id == "0") {   // ✅ use $course_id
		$res = array("res" => "noCourse");
	} else if($year_level == "0") {
		$res = array("res" => "noLevel");
	} else {
		$chkEmail = $conn->prepare("SELECT 1 FROM students WHERE email = :email LIMIT 1");
		$chkEmail->execute(['email' => $email]);
		if ($chkEmail->rowCount() > 0) {
			$res = array("res" => "emailExist", "msg" => $email);
		} else {
			$passwordHash = password_hash($password, PASSWORD_DEFAULT);
			$ins = $conn->prepare("INSERT INTO students(fullname,course_id,gender,birthdate,year_level,email,password,status,date_registered) 
								   VALUES(:fullname,:course_id,:gender,:birthdate,:year_level,:email,:password,:status,NOW())");
			$ok = $ins->execute([
				'fullname' => $fullname,
				'course_id' => $course_id,   // ✅ fixed
				'gender' => $gender,
				'birthdate' => $birthdate,   // ✅ match form name (not $bdate)
				'year_level' => $year_level,
				'email' => $email,
				'password' => $passwordHash,
				'status' => 'active'
			]);
			if($ok) {
				$res = array("res" => "success", "msg" => $email);
			} else {
				$res = array("res" => "failed");
			}
		}
	}
} catch (Exception $e) {
	$res = array("res" => "failed", "error" => $e->getMessage()); // ✅ helpful debugging
}

echo json_encode($res);
?>
