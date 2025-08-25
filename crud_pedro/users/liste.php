<?php
session_start();
include '../config.php';

// Verifica se o usuário é admin
if(!isset($_SESSION['user_id']) || $_SESSION['access_level'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

// Alterar nível de acesso via POST
if(isset($_POST['access_level'], $_POST['user_id'])){
    $user_id = intval($_POST['user_id']);
    $new_level = $_POST['access_level'];
    if($user_id != $_SESSION['user_id']){ 
        $stmt = $conexao->prepare("UPDATE users SET access_level=? WHERE id=?");
        $stmt->bind_param("si", $new_level, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Buscar usuários
$sql = "SELECT * FROM users ORDER BY id DESC";
$result = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Lista de Usuários</h3>
        <a href="create.php" class="btn btn-primary">+ Criar Novo Usuário</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Usuário</th>
                    <th>Email</th>
                    <th>Nível</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td>
                        <?php if($row['id'] != $_SESSION['user_id']): ?>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                                <select name="access_level" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="usuario" <?= $row['access_level'] === 'usuario' ? 'selected' : '' ?>>Usuário</option>
                                    <option value="funcionario" <?= $row['access_level'] === 'funcionario' ? 'selected' : '' ?>>Funcionário</option>
                                    <option value="admin" <?= $row['access_level'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </form>
                        <?php else: ?>
                            <?= $row['access_level'] ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mb-1">Editar</a>

                        <?php if($row['id'] != $_SESSION['user_id']): ?>
                            <form method="POST" action="delete.php" class="d-inline" 
                                  onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
                                <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger mb-1">Excluir</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <a href="../index.php" class="btn btn-secondary mt-3">Voltar</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
