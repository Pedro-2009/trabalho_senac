<?php
session_start();
include '../config.php';

// Só admin e funcionario podem acessar a edição
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['access_level'], ['admin', 'funcionario'])) {
    die("Acesso negado.");
}

$logged_id = $_SESSION['user_id'];
$logged_level = $_SESSION['access_level'];

// Verifica se id existe
if (!isset($_GET['id'])) {
    die("Usuário não especificado.");
}

$user_id = intval($_GET['id']);

// Busca dados do usuário a ser editado
$stmt = $conexao->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("Usuário não encontrado.");
}

// Permissões para editar
$can_edit = false;

if ($logged_level === 'admin') {
    $can_edit = true; // Admin pode editar qualquer usuário
} elseif ($logged_level === 'funcionario') {
    // Funcionario não pode editar admin e não pode editar a si mesmo
    if ($user['access_level'] !== 'admin' && $user['id'] != $logged_id) {
        $can_edit = true;
    }
}

if (!$can_edit) {
    die("Você não tem permissão para editar este usuário.");
}

// Se enviou POST, atualiza dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    $stmt = $conexao->prepare("UPDATE users SET name = ?, username = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $username, $email, $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: liste.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3>Editar Usuário</h3>

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
        <a href="liste.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
