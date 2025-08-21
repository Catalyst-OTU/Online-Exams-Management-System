<?php 
header('Content-Type: application/json');
ob_clean(); // clear any whitespace or warnings
session_start();
include("../conn.php");
extract($_POST);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $loginEmail = isset($email) ? trim($email) : '';
    $loginPass  = isset($password) ? $password : '';

    if ($loginEmail === '' || $loginPass === '') {
        $res = array("res" => "invalid");
    } else {
        $stmt = $conn->prepare("SELECT id, email, password, status FROM students WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $loginEmail]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student && ($student['status'] === 'active' || $student['status'] === '' || $student['status'] === null)) {
            if (password_verify($loginPass, $student['password'])) {
                // password is hashed and correct
                $_SESSION['studentSession'] = array(
                    'user_id' => $student['id'],
                    'studentnakalogin' => true
                );
                $_SESSION['user'] = array(
                    'id' => $student['id'],
                    'role' => 'student',
                    'username' => $student['email']
                );
                $res = array("res" => "success");
            } else if ($student['password'] === $loginPass) {
                // migrate plaintext to hash
                $newHash = password_hash($loginPass, PASSWORD_DEFAULT);
                $upd = $conn->prepare("UPDATE students SET password = :hash WHERE id = :id");
                $upd->execute(['hash' => $newHash, 'id' => $student['id']]);
                $_SESSION['studentSession'] = array(
                    'user_id' => $student['id'],
                    'studentnakalogin' => true
                );
                $_SESSION['user'] = array(
                    'id' => $student['id'],
                    'role' => 'student',
                    'username' => $student['email']
                );
                $res = array("res" => "success");
            } else {
                $res = array("res" => "invalid");
            }
        } else {
            $res = array("res" => "invalid");
        }
    }
} catch (Exception $e) {
    $res = array("res" => "invalid");
}

echo json_encode($res);
?>
