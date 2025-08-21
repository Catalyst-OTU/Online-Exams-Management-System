<?php 
 include("../../../conn.php");
 
 extract($_POST);

 $stmt = $conn->prepare("UPDATE exams SET title = :title, start_time = :start_time, duration = :duration, description = :description WHERE id = :id");
 $ok = $stmt->execute([
 	'title' => $examTitle,
 	'start_time' => date('Y-m-d H:i:s'),
 	'duration' => (int)$examLimit,
 	'description' => $examDesc,
 	'id' => $examId
 ]);

 if($ok)
 {
   $res = array("res" => "success", "msg" => $examTitle);
 }
 else
 {
   $res = array("res" => "failed");
 }

 echo json_encode($res);
 ?>