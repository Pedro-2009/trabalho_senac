<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['delete_id'])) {
    header("Location: ../login.php");
    exit;
}

$logged_id = $_SESSION['user_id'];
$logged_level = $_SESSION['access_level'];
$delete_id = intval($_POST['delete_id']);

if ($delete_id == $logged_id) {
    die("Você não pode excluir a si mesmo.");
}

$stmt = $conexao->prepare("SELECT access_level FROM users WHERE id = ?");
$stmt->bind_param("i", $delete_id);
$stmt->execute();
$stmt->bind_result($target_level);
$stmt->fetch();
$stmt->close();

if (!$target_level) {
    die("Usuário não encontrado.");
}

$can_delete = false;

if ($logged_level === 'admin') {
    $can_delete = true; 
} elseif ($logged_level === 'funcionario') {
    if ($target_level !== 'admin') {
        $can_delete = true;
    }
}

if (!$can_delete) {
    die("Você não tem permissão para excluir este usuário.");
}

$stmt = $conexao->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $delete_id);
$stmt->execute();
$stmt->close();

header("Location: ../add/liste.php");
exit;
