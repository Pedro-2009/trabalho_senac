<?php
session_start();
include '../config.php';

// Verifica se está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$logged_id = $_SESSION['user_id'];
$logged_level = $_SESSION['access_level'] ?? '';

// Permite só admin e funcionario acessarem
if (!in_array($logged_level, ['admin', 'funcionario'])) {
    die("Acesso negado: você não tem permissão para acessar esta página.");
}

// Se recebeu POST para alterar nível de usuário
if (isset($_POST['access_level'], $_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);
    $new_level = $_POST['access_level'];

    // Não pode alterar o próprio nível
    if ($user_id != $logged_id) {

        // Busca nível do usuário que vai ser alterado
        $stmt = $conexao->prepare("SELECT access_level FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($target_level);
        $stmt->fetch();
        $stmt->close();

        $can_update = false;

        if ($logged_level === 'admin') {
            $can_update = true; // admin pode alterar qualquer nível
        } elseif ($logged_level === 'funcionario') {
            // funcionario não pode alterar admins
            if ($target_level !== 'admin') {
                $can_update = true;
            }
        }

        if ($can_update) {
            $stmt = $conexao->prepare("UPDATE users SET access_level = ? WHERE id = ?");
            $stmt->bind_param("si", $new_level, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Busca lista de usuários
$sql = "SELECT * FROM users ORDER BY id DESC";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Lista de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Lista de Usuários</h3>

        <?php if ($logged_level === 'admin'): ?>
            <a href="create.php" class="btn btn-primary">+ Criar Novo Usuário</a>
        <?php endif; ?>
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
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td>
                            <?php if ($row['id'] != $logged_id): ?>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="user_id" value="<?= $row['id'] ?>" />
                                    <select name="access_level" class="form-select form-select-sm" onchange="this.form.submit()"
                                        <?= ($logged_level === 'funcionario' && $row['access_level'] === 'admin') ? 'disabled' : '' ?>>
                                        <option value="usuario" <?= $row['access_level'] === 'usuario' ? 'selected' : '' ?>>Usuário</option>
                                        <option value="funcionario" <?= $row['access_level'] === 'funcionario' ? 'selected' : '' ?>>Funcionário</option>
                                        <option value="admin" <?= $row['access_level'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                </form>
                            <?php else: ?>
                                <?= htmlspecialchars($row['access_level']) ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- Botão editar -->
                            <?php if (
                                $logged_level === 'admin' ||
                                ($logged_level === 'funcionario' && $row['access_level'] !== 'admin' && $row['id'] != $logged_id)
                            ): ?>
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning mb-1">Editar</a>
                            <?php else: ?>
                                <button class="btn btn-sm btn-warning mb-1" disabled>Editar</button>
                            <?php endif; ?>

                            <!-- Botão excluir -->
                            <?php if (
                                $row['id'] != $logged_id &&
                                (
                                    $logged_level === 'admin' ||
                                    ($logged_level === 'funcionario' && $row['access_level'] !== 'admin')
                                )
                            ): ?>
                                <form method="POST" action="delete.php" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
                                    <input type="hidden" name="delete_id" value="<?= $row['id'] ?>" />
                                    <button type="submit" class="btn btn-sm btn-danger mb-1">Excluir</button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-sm btn-danger mb-1" disabled>Excluir</button>
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
