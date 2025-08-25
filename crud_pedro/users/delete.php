<?php
session_start();
include '../config.php';


if(!isset($_SESSION['user_id']) || $_SESSION['access_level'] !== 'admin'){
    header("Location: ../login.php");
    exit;
}

if(isset($_POST['delete_id'])){
    $delete_id = intval($_POST['delete_id']);

 
    if($delete_id != $_SESSION['user_id']){
        $stmt = $conexao->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
    } 
}


header("Location: liste.php");
exit;
?>