<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema - Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style2.css">
</head>
<body>

<div class="container mt-5 text-center">

    <h2>Bem-vindo ao Sistema</h2>

    <?php if(isset($_SESSION['user_id'])): ?>
        <p>Olá, <b><?= htmlspecialchars($_SESSION['user_name'] ?? $_SESSION['username'] ?? '') ?></b>!</p>

        <div class="d-flex justify-content-center flex-wrap gap-2 mb-3">
            <a href="users/profile.php" class="btn btn-success">Meu Perfil</a>

            <?php if(in_array($_SESSION['access_level'], ['admin', 'funcionario'])): ?>
                <a href="users/liste.php" class="btn btn-warning">Gerenciar Usuários</a>
            <?php endif; ?>

            <a href="logout.php" class="btn btn-danger">Sair</a>
        </div>

    <?php else: ?>
        <div class="d-flex justify-content-center flex-wrap gap-2">
            <a href="login.php" class="btn btn-success">Entrar</a>
            <a href="register.php" class="btn btn-secondary shadow-sm px-3 py-2" style="border-radius: 4px;"> Criar Conta</a>
        </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
