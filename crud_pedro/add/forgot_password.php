<?php
session_start();
include '../config.php';
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password=? WHERE email=?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ss", $new_password, $email);

    if($stmt->execute()){
        $_SESSION['msg'] = "Senha atualizada! Faça login.";
        header("Location: ../login.php");
        exit;
    } else {
        $_SESSION['msg'] = "Erro ao atualizar senha.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Esqueci a senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/style5.css">
</head>
    

<body class="bg-dark d-flex align-items-center justify-content-center vh-100">
    
<div class="card shadow p-4" style="width: 25rem;">
    <h3 class="text-center mb-3">Redefinir Senha</h3>
    <form method="POST">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nova Senha</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <br>
        <button class="btn btn-warning w-100" type="submit">Atualizar Senha</button>
        <br><br>
    </form>
    <a href="../login.php"><button class="btn btn-success w-100" >Voltar</button></a>
</div>
</body>
</html>