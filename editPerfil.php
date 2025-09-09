<?php
session_start();
require_once 'crud.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$nome = $_POST['nome'] ?? '';
$avatar = $_POST['avatar'] ?? '';
$bio = $_POST['bio'] ?? '';

if ($nome) {
    $stmt = $conn->prepare("UPDATE perfis SET nome = ?, avatar = ?, bio = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $nome, $avatar, $bio, $user_id);
    $stmt->execute();
    $stmt->close();
    header('Location: Pagina de perfil.php');
    exit;
} else {
    echo "Nome é obrigatório.";
}
?>
