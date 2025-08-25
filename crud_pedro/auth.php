<?php
session_start();
include 'config.php';

$login = $_POST['login'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username=? OR email=? LIMIT 1";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("ss", $login, $login);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $user = $result->fetch_assoc();
    if(password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['access_level'] = $user['access_level'];
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['msg'] = "Senha incorreta.";
    }
} else {
    $_SESSION['msg'] = "Usuário não encontrado.";
}

header("Location: login.php");