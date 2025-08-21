<?php 
 include("../../../conn.php");

extract($_POST);

$question = trim($question);
$choice_A = trim($choice_A);
$choice_B = trim($choice_B);
$choice_C = trim($choice_C);
$choice_D = trim($choice_D);
$correctAnswer = trim($correctAnswer);

try {
	$conn->beginTransaction();
	// 1) Does the question already exist globally (questions.exam_question is UNIQUE)?
	$selQ = $conn->prepare("SELECT id FROM questions WHERE exam_question = :q LIMIT 1");
	$selQ->execute(['q' => $question]);
	$questionId = null;
	if ($row = $selQ->fetch(PDO::FETCH_ASSOC)) {
		$questionId = (int)$row['id'];
	} else {
		// Insert new question
		$insQ = $conn->prepare("INSERT INTO questions (exam_id, exam_question, exam_ch1, exam_ch2, exam_ch3, exam_ch4, exam_answer) VALUES (:exam_id,:q,:ch1,:ch2,:ch3,:ch4,:ans)");
		$ok = $insQ->execute([
			'exam_id' => $examId,
			'q' => $question,
			'ch1' => $choice_A,
			'ch2' => $choice_B,
			'ch3' => $choice_C,
			'ch4' => $choice_D,
			'ans' => $correctAnswer
		]);
		if (!$ok) {
			$conn->rollBack();
			$res = array("res" => "failed");
			echo json_encode($res);
			exit;
		}
		$questionId = (int)$conn->lastInsertId();
	}

	// 2) Check if this exam already has this question linked
	$selLink = $conn->prepare("SELECT 1 FROM exam_questions WHERE exam_id = :exam AND question_id = :qid LIMIT 1");
	$selLink->execute(['exam' => $examId, 'qid' => $questionId]);
	if ($selLink->rowCount() > 0) {
		$conn->commit();
		$res = array("res" => "exist", "msg" => $question);
		echo json_encode($res);
		exit;
	}

	// 3) Link question to exam
	$link = $conn->prepare("INSERT INTO exam_questions (exam_id, question_id) VALUES (:exam, :qid)");
	$ok = $link->execute(['exam' => $examId, 'qid' => $questionId]);
	if ($ok) {
		$conn->commit();
		$res = array("res" => "success", "msg" => $question);
	} else {
		$conn->rollBack();
		$res = array("res" => "failed");
	}
} catch (Exception $e) {
	if ($conn->inTransaction()) { $conn->rollBack(); }
	$res = array("res" => "failed");
}

echo json_encode($res);
?>