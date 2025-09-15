<?php
session_start();
require_once 'crud.php'; // Conexão com o banco

if (!isset($_SESSION['user_id']) || !isset($_POST['seguindo_id']) || !isset($_POST['acao'])) {
    header('Location: index.php');
    exit;
}

$seguidor_id = $_SESSION['user_id'];
$seguindo_id = intval($_POST['seguindo_id']);
$acao = $_POST['acao'];

if ($seguidor_id == $seguindo_id) {
    header('Location: Pagina de perfil.php?user_id=' . $seguindo_id);
    exit;
}

if ($acao === 'seguir') {
    $stmt = $conn->prepare("INSERT IGNORE INTO seguir (seguidor_id, seguindo_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $seguidor_id, $seguindo_id);
    $stmt->execute();
    $stmt->close();
} elseif ($acao === 'deixar_de_seguir') {
    $stmt = $conn->prepare("DELETE FROM seguir WHERE seguidor_id = ? AND seguindo_id = ?");
    $stmt->bind_param("ii", $seguidor_id, $seguindo_id);
    $stmt->execute();
    $stmt->close();
}

header('Location: Pagina de perfil.php?user_id=' . $seguindo_id);
exit;
