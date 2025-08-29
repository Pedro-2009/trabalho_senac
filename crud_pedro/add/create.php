<?php
session_start();
include '../config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['access_level'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name     = $_POST['name'];
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $access_level = $_POST['access_level'];

    $sql = "INSERT INTO users (name, username, email, password, access_level) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("sssss", $name, $username, $email, $password, $access_level);

    if($stmt->execute()){
        $_SESSION['msg'] = "Usuário criado com sucesso!";
        header("Location: liste.php");
        exit;
    } else {
        $_SESSION['msg'] = "Erro: ".$stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style6.css">
</head>
<body class="bg-dark">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <?php if(isset($_SESSION['msg'])): ?>
                <div class="alert alert-info">
                    <?= $_SESSION['msg']; unset($_SESSION['msg']); ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-lg">
                <div class="card-header bg-secunday text-white text-center">
                    <h4>Criar Novo Usuário</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Usuário</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nível de Acesso</label>
                            <select name="access_level" class="form-select" required>
                                <option value="usuario">Usuário</option>
                                <option value="funcionario">Funcionário</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="liste.php" class="btn btn-secondary">Voltar</a>
                           <a href="liste.php"> <button class="btn btn-success" type="submit">Criar</button></a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
