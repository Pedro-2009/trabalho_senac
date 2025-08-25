<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">

    <title>Sistema - Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body class="container mt-5">

<h2>Bem-vindo ao Sistema</h2>

<?php if(isset($_SESSION['user_id'])): ?>
    <p>Olá, <b><?= $_SESSION['user_name'] ?></b>!</p>
    <a href="users/profile.php" class="btn btn-primary">Meu Perfil</a>
    <?php if($_SESSION['access_level'] === 'admin'): ?>
        <a href="users/liste.php" class="btn btn-warning">Gerenciar Usuários</a>
    <?php endif; ?>
    <a href="logout.php" class="btn btn-danger">Sair</a>
<?php else: ?>
    <a href="login.php" class="btn btn-success">Entrar</a>
    <a href="register.php" class="btn btn-secondary btn-sm">Criar Conta</a>
<?php endif; ?>

</body>
</html>