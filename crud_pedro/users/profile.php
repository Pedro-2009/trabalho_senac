
<?php
session_start();
include '../config.php';


if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

$id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark container mt-5">

<h3 class="bg-light">Meu Perfil</h3>
<table class=" btn-secondary table table-bordered">
    <tr><th>Nome</th><td><?= $user['name'] ?></td></tr>
    <tr><th>Usuário</th><td><?= $user['username'] ?></td></tr>
    <tr><th>Email</th><td><?= $user['email'] ?></td></tr>
    <tr><th>Nível de Acesso</th><td><?= ucfirst($user['access_level']) ?></td></tr>
    <tr><th>Criado em</th><td><?= $user['created'] ?></td></tr>
</table>

<a href="edit.php" class="btn btn-warning">Editar Perfil</a>
<a href="forgot_password.php" class="btn btn-info">Alterar Senha</a>
<a href="../index.php" class="btn btn-secondary">Voltar</a>

</body>
</html>