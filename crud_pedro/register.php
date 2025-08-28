<?php
session_start();
include 'config.php';


$result = $conexao->query("SELECT COUNT(*) AS total_admin FROM users WHERE access_level='admin'");
$row = $result->fetch_assoc();
$total_admin = $row['total_admin'] ?? 0;

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $name     = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password_raw = trim($_POST['password']);

    if(empty($name) || empty($username) || empty($email) || empty($password_raw)){
        $_SESSION['msg'] = "Todos os campos são obrigatórios!";
        header("Location: cadastro.php");
        exit;
    }


    $password = password_hash($password_raw, PASSWORD_DEFAULT);

  
    if(isset($_POST['access_level'])){
        $selected_level = $_POST['access_level'];

        if($selected_level === 'admin'){
            if($total_admin < 5 || (isset($_SESSION['access_level']) && $_SESSION['access_level'] === 'admin')){
                $access_level = 'admin';
            } else {
                $access_level = 'usuario'; 
            }
        } elseif($selected_level === 'funcionario'){
            $access_level = 'funcionario';
        } else {
            $access_level = 'usuario';
        }
    } else {
        $access_level = 'usuario';
    }

    $sql = "INSERT INTO users (name, username, email, password, access_level) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);

    if(!$stmt){
        $_SESSION['msg'] = "Erro na preparação da query: " . $conexao->error;
        header("Location: cadastro.php");
        exit;
    }

    $stmt->bind_param("sssss", $name, $username, $email, $password, $access_level);

    if($stmt->execute()){
        $_SESSION['msg'] = "Conta criada com sucesso!";
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['msg'] = "Erro: " . $stmt->error;
        header("Location: cadastro.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <div class="d-flex justify-content-start mb-3">
   <a href="index.php"><button type="button" class="btn btn-secondary">Voltar</button></a> 
</div>

<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 450px;">
        <h3 class="card-title text-center mb-4">Cadastro de Usuário</h3>

        <?php if(isset($_SESSION['msg'])): ?>
            <div class="alert alert-info text-center"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
        <?php endif; ?>

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

            <?php if($total_admin < 5 || (isset($_SESSION['access_level']) && $_SESSION['access_level'] === 'admin')): ?>
            <div class="mb-3">
                <label class="form-label">Nível de Acesso</label>
                <select name="access_level" class="form-select">
                    <option value="usuario">Usuário</option>
                    <option value="funcionario">Funcionário</option>
                    <?php if($total_admin < 5 || (isset($_SESSION['access_level']) && $_SESSION['access_level'] === 'admin')): ?>
                        <option value="admin">Administrador</option>
                    <?php endif; ?>
                </select>
            </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary w-100 mb-2">Cadastrar</button>
            <a href="login.php" class="btn btn-secondary w-100">Voltar para Login</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
