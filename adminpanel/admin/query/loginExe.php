<?php 
session_start();
include("../../../conn.php");

extract($_POST);

try {
	// Use unified users table
	$stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = :username AND role = 'admin' LIMIT 1");
	$stmt->execute(['username' => $username]);
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($user && password_verify($pass, $user['password'])) {
		// Ensure password hash is up-to-date
		if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
			$newHash = password_hash($pass, PASSWORD_DEFAULT);
			$upd = $conn->prepare("UPDATE users SET password = :hash WHERE id = :id");
			$upd->execute(['hash' => $newHash, 'id' => $user['id']]);
		}
		$_SESSION['admin'] = array(
			'admin_id' => $user['id'],
			'adminnakalogin' => true
		);
		$_SESSION['user'] = array(
			'id' => $user['id'],
			'role' => 'admin',
			'username' => $username
		);
		$res = array("res" => "success");
	} else if ($user && ($user['password'] === $pass || md5($pass) === $user['password'])) {
		// Legacy plaintext or md5 stored in users: rehash and proceed
		$newHash = password_hash($pass, PASSWORD_DEFAULT);
		$upd = $conn->prepare("UPDATE users SET password = :hash WHERE id = :id");
		$upd->execute(['hash' => $newHash, 'id' => $user['id']]);
		$_SESSION['admin'] = array(
			'admin_id' => $user['id'],
			'adminnakalogin' => true
		);
		$_SESSION['user'] = array(
			'id' => $user['id'],
			'role' => 'admin',
			'username' => $username
		);
		$res = array("res" => "success");
	} else {
		$res = array("res" => "invalid");
	}
} catch (Exception $e) {
	$res = array("res" => "invalid");
}

echo json_encode($res);
?>