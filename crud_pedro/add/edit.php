<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['access_level'], ['admin', 'funcionario', 'usuario'])) {
    die("Acesso negado.");
}

$logged_id    = $_SESSION['user_id'];
$logged_level = $_SESSION['access_level'];


if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
} else {
    $user_id = $logged_id;
}


$stmt = $conexao->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("Usuário não encontrado.");
}

$can_edit = false;


if ($logged_level === 'admin') {
    if ($user['access_level'] !== 'admin') {
        
        $can_edit = true;
    } elseif ($user['id'] == $logged_id) {
        
        $can_edit = true;
    }
} elseif ($logged_level === 'funcionario') {
    
    if ($user['access_level'] !== 'admin') {
        $can_edit = true;
    }
} elseif ($logged_level === 'usuario') {
   
    if ($user['access_level'] !== 'admin') {
        $can_edit = true;
    }
}


if (!$can_edit) {
    die("Você não tem permissão para editar este usuário.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);

    
    if ($logged_level === 'admin') {
        $stmt = $conexao->prepare("UPDATE users SET name = ?, username = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $username, $email, $user_id);
    } else {
     
        $stmt = $conexao->prepare("UPDATE users SET name = ?, username = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $username, $email, $user_id);
    }

    $stmt->execute();
    $stmt->close();

    if ($logged_level === 'admin' || $logged_level === 'funcionario') {
        header("Location: liste.php");
    } else {
        header("Location: ../index.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style_edit.css">
</head>
<body class="bg-dark">
    <?php include '../inc/header.php'; ?>
<div class="container mt-5">
    <h3 class="mb-3">Editar Usuário</h3>

    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Usuário</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-success">Salvar Alterações</button>
        <a href="../index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include '../inc/footer.php'; ?> 

</body>
</html>
