<?php 
 include("../../../conn.php");


extract($_POST);

$id = isset($id) ? (int)$id : 0;
if ($id <= 0) {
	$res = array("res" => "failed");
	echo json_encode($res);
	exit;
}

$stmt = $conn->prepare("DELETE FROM course WHERE id = :id LIMIT 1");
$ok = $stmt->execute(['id' => $id]);

if($ok && $stmt->rowCount() === 1)
{
	$res = array("res" => "success");
}
else
{
	$res = array("res" => "failed");
}



echo json_encode($res);
?>