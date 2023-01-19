<?php

session_start();
include("functions.php");



// var_dump($_POST);
// exit();

// データ受け取り
$username = $_POST["username"];
$password = $_POST["password"];
$email = $_POST["email"];

// DB接続
$pdo = connect_to_db();


// SQL実行
$sql = 'SELECT * FROM users_table WHERE username=:username AND email=:email AND password=:password AND deleted_at IS NULL';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':password', $password, PDO::PARAM_STR);

try {
    $status = $stmt->execute();
} catch (PDOException $e) {
    echo json_encode(["sql error" => "{$e->getMessage()}"]);
    exit();
}


// ユーザ有無で条件分岐

$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo "<p>ログイン情報に誤りがあります</p>";
    echo "<a href=todo_login.php>ログイン</a>";
    exit();
} else {
    $_SESSION = array();
    $_SESSION['session_id'] = session_id();
    $_SESSION['is_admin'] = $user['is_admin'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    header("Location:todo_read.php");
    exit();
}
