<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="liste.php">Entrada de usuarios</a>
    <div class="d-flex align-items-center">
        <span class="navbar-text text-light me-3">
            Logado como: <strong><?= $_SESSION['access_level'] ?? 'Convidado' ?></strong>
        </span>
        <a href="users/logout.php" class="btn btn-sm btn-danger">Sair</a>
    </div>
</nav>

<div class="container mt-4">
