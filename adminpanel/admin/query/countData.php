<?php 

// Count All Course
$selCourse = $conn->query("SELECT COUNT(*) as totCourse FROM course ")->fetch(PDO::FETCH_ASSOC);


// Count All Exam
$selExam = $conn->query("SELECT COUNT(*) as totExam FROM exams ")->fetch(PDO::FETCH_ASSOC);

// Count All Student
$selStudent = $conn->query("SELECT COUNT(*) as totStudent FROM students ")->fetch(PDO::FETCH_ASSOC);
 ?>