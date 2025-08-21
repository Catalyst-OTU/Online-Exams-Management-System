<?php
session_start(); 
include("../conn.php");

$exam_id = $_POST['exam_id'];
$user_id = $_SESSION['studentSession']['user_id'];

// Check if user already took this exam
$checkTaken = $conn->prepare("SELECT * FROM responses WHERE exam_id = :exam_id AND user_id = :user_id");
$checkTaken->execute(['exam_id' => $exam_id, 'user_id' => $user_id]);

if ($checkTaken->rowCount() > 0) {
    echo json_encode(["res" => "alreadyTaken"]);
    exit();
}

try {
    $conn->beginTransaction();

    // Delete existing responses for this exam+user
    $del = $conn->prepare("DELETE FROM responses WHERE exam_id = :exam_id AND user_id = :user_id");
    $del->execute(['exam_id' => $exam_id, 'user_id' => $user_id]);

    // Insert new responses
    $ins = $conn->prepare("INSERT INTO responses (exam_id, user_id, question_id, answer) 
                           VALUES (:exam_id, :user_id, :question_id, :answer)");

    foreach ($_POST['answer'] as $questionId => $value) {
        $answerText = reset($value);
        if (!empty($answerText)) {
            $ins->execute([
                'exam_id' => $exam_id,
                'user_id' => $user_id,
                'question_id' => $questionId,
                'answer' => $answerText
            ]);
        }
    }

    $scoreQuery = $conn->prepare("
        SELECT r.answer AS student_answer, q.exam_answer AS correct_answer
        FROM responses r
        JOIN questions q ON r.question_id = q.id
        WHERE r.exam_id = :exam_id AND r.user_id = :user_id
    ");
    $scoreQuery->execute(['exam_id' => $exam_id, 'user_id' => $user_id]);
    
    $correct = 0;
    $total = $scoreQuery->rowCount();

    while ($row = $scoreQuery->fetch(PDO::FETCH_ASSOC)) {
        if (strcasecmp(trim($row['student_answer']), trim($row['correct_answer'])) === 0) {
            $correct++;
        }
    }

    $score = $total > 0 ? ($correct / $total) * 100 : 0;

    $insResult = $conn->prepare("INSERT INTO results (user_id, exam_id, score) 
                                 VALUES (:user_id, :exam_id, :score)");
    $insResult->execute([
        'user_id' => $user_id,
        'exam_id' => $exam_id,
        'score'   => $score
    ]);

    $conn->commit();
    echo json_encode(["res" => "success", "score" => $score]);

} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode(["res" => "failed", "error" => $e->getMessage()]);
}

?>
