<?php 
 include("../../../conn.php");

 extract($_POST);

$course_name = strtoupper(trim($course_name));

try {
	// Check existence (case-insensitive due to uppercasing)
	$selCourse = $conn->prepare("SELECT 1 FROM course WHERE name = :name LIMIT 1");
	$selCourse->execute(['name' => $course_name]);
	if($selCourse->rowCount() > 0)
	{
		$res = array("res" => "exist", "course_name" => $course_name);
	}
	else
	{
		// Attempt insert
		$insCourse = $conn->prepare("INSERT INTO course(name) VALUES(:name)");
		$ok = $insCourse->execute(['name' => $course_name]);
		if($ok)
		{
			$res = array("res" => "success", "course_name" => $course_name);
		}
		else
		{
			$res = array("res" => "failed", "course_name" => $course_name);
		}
	}
} catch (Exception $e) {
	$res = array("res" => "failed", "course_name" => $course_name);
}

echo json_encode($res);
?>