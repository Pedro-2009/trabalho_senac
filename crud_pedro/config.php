<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "trabalho_senac";

$conexao = new mysqli($host, $user, $pass, $db);

if($conexao->connect_error){
    die("Erro de conexão: " . $conexao->connect_error);
}
?>