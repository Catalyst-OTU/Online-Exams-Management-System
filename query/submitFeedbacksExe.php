<?php 
	session_start();
 include("../conn.php");
 extract($_POST);


$exmneSess = $_SESSION['studentSession']['user_id'];

 $selMyFeedbacks = $conn->query("SELECT * FROM feedbacks WHERE user_id='$exmneSess' ");

 if($selMyFeedbacks->rowCount() >= 3)
 {
 	$res = array("res" => "limit");
 }
 else
 {
 	$date = date("F d, Y");
 	$insFedd = $conn->query("INSERT INTO feedbacks(user_id,exmne_as,feedbacks,date) VALUES('$exmneSess','$asMe','$myFeedbacks','$date') ");

 	if($insFedd)
 	{
 		$res = array("res" => "success");
 	}
 	else
 	{
 		$res = array("res" => "failed");
 	}
 }


 echo json_encode($res);
 ?>