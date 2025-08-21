<?php
include("../../conn.php");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['student_id']) || !isset($_GET['exam_id'])) {
    die("Invalid request.");
}

$student_id = (int) $_GET['student_id'];
$exam_id = (int) $_GET['exam_id'];

// Fetch student info
$studentStmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$studentStmt->execute([$student_id]);
$student = $studentStmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    die("Student not found.");
}

// Fetch exam info
$examStmt = $conn->prepare("SELECT * FROM exams WHERE id = ?");
$examStmt->execute([$exam_id]);
$exam = $examStmt->fetch(PDO::FETCH_ASSOC);

if (!$exam) {
    die("Exam not found.");
}

// Calculate score
$scoreStmt = $conn->prepare("
    SELECT COUNT(*) as score 
    FROM exam_questions eq 
    INNER JOIN questions q ON q.id = eq.question_id 
    INNER JOIN responses r ON r.question_id = eq.question_id 
    AND r.answer = q.exam_answer 
    WHERE r.user_id = ? AND r.exam_id = ? AND eq.exam_id = ?
");
$scoreStmt->execute([$student_id, $exam_id, $exam_id]);
$selScore = $scoreStmt->fetch(PDO::FETCH_ASSOC);

$score = $selScore ? (int) $selScore['score'] : 0;

// Get total number of questions
$totalStmt = $conn->prepare("SELECT COUNT(*) as cnt FROM exam_questions WHERE exam_id = ?");
$totalStmt->execute([$exam_id]);
$totalQ = $totalStmt->fetch(PDO::FETCH_ASSOC)['cnt'];

$percentage = $totalQ > 0 ? round(($score / $totalQ) * 100, 2) : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Result</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .container { width: 80%; margin: auto; border: 2px solid #000; padding: 20px; }
        h2, h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; padding: 10px; text-align: center; }
        .btn-print { margin-top: 20px; text-align: center; }
        .btn-print button { padding: 10px 20px; font-size: 16px; cursor: pointer; }
    </style>
</head>
<body onload="window.print()">

<div class="container">
    <h2>Exam Result Slip</h2>
    <h3><?= htmlspecialchars($exam['title']); ?></h3>
    <hr>
    <p><strong>Student Name:</strong> <?= htmlspecialchars($student['fullname']); ?></p>
    <p><strong>Exam:</strong> <?= htmlspecialchars($exam['title']); ?></p>
    <p><strong>Score:</strong> <?= $score . " / " . $totalQ; ?></p>
    <p><strong>Percentage:</strong> <?= $percentage; ?>%</p>

    <div class="btn-print">
        <button onclick="window.print()">Print</button>
    </div>
</div>

</body>
</html>
