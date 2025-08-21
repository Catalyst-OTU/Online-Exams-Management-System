<?php
include("../../../conn.php");
extract($_POST);

try {
	$params = [
		'fullname' => $fullname,
		'course_id' => $course_id,
		'gender' => $gender,
		'birthdate' => $birthdate,
		'year_level' => $year_level,
		'email' => $email,
		'status' => $status,
		'id' => $id
	];

	if (isset($password) && $password !== '') {
		$params['password'] = password_hash($password, PASSWORD_DEFAULT);
		$sql = "UPDATE students SET fullname=:fullname, course_id=:course_id, gender=:gender, birthdate=:birthdate, year_level=:year_level, email=:email, password=:password, status=:status WHERE id=:id";
	} else {
		$sql = "UPDATE students SET fullname=:fullname, course_id=:course_id, gender=:gender, birthdate=:birthdate, year_level=:year_level, email=:email, status=:status WHERE id=:id";
	}

	$stmt = $conn->prepare($sql);
	if ($stmt->execute($params)) {
		$res = array("res" => "success", "exFullname" => $fullname);
	} else {
		$res = array("res" => "failed");
	}
} catch (Exception $e) {
	$res = array("res" => "failed");
}

echo json_encode($res);	
?>