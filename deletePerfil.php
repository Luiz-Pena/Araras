<?php
session_start();
require_once 'crud.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Exclui respostas do usuário
$stmt = $conn->prepare("DELETE FROM respostas WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Exclui tópicos do usuário
$stmt = $conn->prepare("DELETE FROM topicos WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Exclui seguidores do usuário (onde ele é seguido)
$stmt = $conn->prepare("DELETE FROM seguir WHERE seguindo_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Exclui seguidores do usuário (onde ele é seguidor)
$stmt = $conn->prepare("DELETE FROM seguir WHERE seguidor_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Exclui perfil
$stmt = $conn->prepare("DELETE FROM perfis WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Exclui usuário
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Encerra sessão e redireciona
session_destroy();
header('Location: index.php');
exit;
?>
