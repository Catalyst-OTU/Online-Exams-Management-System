<?php 
$exmneId = $_SESSION['studentSession']['user_id'];

// Fetch student info from students table
$selStudent = $conn->prepare("SELECT id, fullname, email, course_id FROM students WHERE id = :id LIMIT 1");
$selStudent->execute(['id' => $exmneId]);
$selExmneeData = $selStudent->fetch(PDO::FETCH_ASSOC);

// Load exams for the student's course only (fallback to all if column missing)
try {
    $selExamStmt = $conn->prepare("
    SELECT * FROM exams 
    WHERE course_id = :course_id 
      AND id NOT IN (
        SELECT exam_id FROM responses WHERE user_id = :exmneId
      )
    ORDER BY id DESC
");
$selExamStmt->execute([
    'course_id' => $selExmneeData['course_id'],
    'exmneId'   => $exmneId
]);
$selExam = $selExamStmt;

} catch (Exception $e) {
    // Fallback: list all exams if schema lacks course_id
    $selExam = $conn->query("SELECT * FROM exams ORDER BY id DESC ");
}

?>