

<?php
session_start();
include 'config.php';



if(isset($_SESSION['user_id'])){
    header("Location: index.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $login = $_POST['login'];
    $password = $_POST['password'];

    $stmt = $conexao->prepare("SELECT * FROM users WHERE username=? OR email=? LIMIT 1");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['access_level'] = $user['access_level'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Senha incorreta!";
        }
    } else {
        $error = "Usuário ou email não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body class="bg-light">


<div class="d-flex justify-content-start mb-3">
   <a href="index.php"><button type="button" class="btn btn-secondary">Voltar</button></a> 
</div>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-sm p-4" style="max-width: 350px; width: 100%; border-radius: 12px;">
        <h3 class="text-center mb-4">Login no Sistema</h3>

        <?php if(isset($error)){ ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php } ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username ou Email</label>
                <input type="text" name="login" class="form-control rounded"  required>
            </div>

            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="password" class="form-control rounded"  required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3 rounded">Entrar</button>
        </form>

        <div class="text-center">
            <a href="register.php" class="text-primary text-decoration-none me-2" onmouseover="this.style.color='red'" onmouseout="this.style.color='#0d6efd'">Criar Conta</a>
            <a href="users/forgot_password.php" class="text-primary text-decoration-none ms-2" onmouseover="this.style.color='red'" onmouseout="this.style.color='#0d6efd'">Esqueci a Senha</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
