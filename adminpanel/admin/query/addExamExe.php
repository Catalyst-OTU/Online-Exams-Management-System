<?php 
 include("../../../conn.php");

 extract($_POST);

 // Prevent duplicate by title
 $selExam = $conn->prepare("SELECT 1 FROM exams WHERE title = :title LIMIT 1");
 $selExam->execute(['title' => $examTitle]);

 if($courseSelected == "0")
 {
 	$res = array("res" => "noSelectedCourse");
 }
 else if($timeLimit == "0")
 {
 	$res = array("res" => "noSelectedTime");
 }
 else if($selExam->rowCount() > 0)
 {
	$res = array("res" => "exist", "examTitle" => $examTitle);
 }
 else
 {
	$stmt = $conn->prepare("INSERT INTO exams (title,start_time,duration,description,course_id) VALUES (:title,:start_time,:duration,:description,:course_id)");
	$ok = $stmt->execute([
		'title' => $examTitle,
		'start_time' => date('Y-m-d H:i:s'),
		'duration' => (int)$timeLimit,
		'description' => $examDesc,
		'course_id' => (int)$courseSelected
	]);
	if($ok)
	{
		$res = array("res" => "success", "examTitle" => $examTitle);
	}
	else
	{
		$res = array("res" => "failed", "examTitle" => $examTitle);
	}
 }

 echo json_encode($res);
 ?>