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
<form method="POST">
    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Usuário</label>
        <input type="text" name="username" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Senha</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Nível de Acesso</label>
        <select name="access_level" class="form-control" required>
            <option value="usuario">Usuário</option>
            <option value="funcionario">Funcionário</option>
            <option value="admin">Administrador</option>
        </select>
    </div>

    <button class="btn btn-success" type="submit">Criar</button>
    <a href="list.php" class="btn btn-secondary">Voltar</a>
</form>

