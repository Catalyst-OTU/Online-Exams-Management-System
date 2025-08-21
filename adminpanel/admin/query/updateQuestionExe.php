<?php
 include("../../../conn.php");
 extract($_POST);

try {
$stmt = $conn->prepare("UPDATE questions 
    SET exam_question = :q, 
        exam_ch1 = :ch1, 
        exam_ch2 = :ch2, 
        exam_ch3 = :ch3, 
        exam_ch4 = :ch4, 
        exam_answer = :ans 
    WHERE id = :id");

$ok = $stmt->execute([
		'q' => $question,
		'ch1' => $exam_ch1,
		'ch2' => $exam_ch2,
		'ch3' => $exam_ch3,
		'ch4' => $exam_ch4,
		'ans' => $exam_answer,
		'id' => $question_id
	]);
	if ($ok) {
		$res = array("res" => "success");
	} else {
		$res = array("res" => "failed");
	}
} catch (Exception $e) {
	$res = array("res" => "failed");
}

 echo json_encode($res);	
?>