<?php 
 include("../../../conn.php");


extract($_POST);

$delExam = $conn->prepare("DELETE FROM exams WHERE id = :id");
$ok = $delExam->execute(['id' => $id]);
if($ok)
{
	$res = array("res" => "success");
}
else
{
	$res = array("res" => "failed");
}


	echo json_encode($res);
 ?>