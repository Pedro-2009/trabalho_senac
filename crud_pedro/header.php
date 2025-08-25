<?php if(isset($_SESSION['user_name'])): ?>
<li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
    <?php echo $_SESSION['user_name']; ?>
  </a>
  <ul class="dropdown-menu dropdown-menu-end">
    <li><a class="dropdown-item" href="users/profile.php">Perfil</a></li>
    <li><a class="dropdown-item" href="users/edit.php">Editar Perfil</a></li>
    <li><a class="dropdown-item" href="users/forgot_password.php">Alterar Senha</a></li>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item text-danger" href="logout.php">Sair</a></li>
  </ul>
</li>
<?php endif; ?>