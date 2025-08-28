<?php
session_start();
include '../config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}


if ($_SESSION['access_level'] === 'admin' && isset($_GET['id'])) {
    $id = intval($_GET['id']); 
} else {

    $id = $_SESSION['user_id'];
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name     = $_POST['name'];
    $username = $_POST['username'];
    $email    = $_POST['email'];

    $sql = "UPDATE users SET name=?, username=?, email=?, modified=NOW() WHERE id=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssi", $name, $username, $email, $id);

    if($stmt->execute()){
        $_SESSION['msg'] = "Perfil atualizado!";
    
        if ($id == $_SESSION['user_id']) {
            $_SESSION['user_name'] = $name;
        }
        header("Location: profile.php");
        exit;
    } else {
        $_SESSION['msg'] = "Erro: ".$stmt->error;
    }
}

// Buscar dados do usuário que será editado
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    $_SESSION['msg'] = "Usuário não encontrado.";
    header("Location: profile.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="container mt-5">

<h3>Editar Perfil</h3>

<form method="POST">
    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Usuário</label>
        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>
    <button class="btn btn-success" type="submit">Salvar</button>
    <a href="profile.php" class="btn btn-secondary">Cancelar</a>
</form>

</body>
</html>
