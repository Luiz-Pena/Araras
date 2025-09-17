<?php
// curtir_topico.php
session_start();
require_once 'crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['topico_id']) && isset($_SESSION['user_id'])) {
    $topico_id = $_POST['topico_id'];
    $user_id = $_SESSION['user_id'];
    $acao = $_POST['acao'];

    if ($acao === 'curtir') {
        $stmt = $conn->prepare("INSERT INTO curtidas_topicos (user_id, topico_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $topico_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($acao === 'descurtir') {
        $stmt = $conn->prepare("DELETE FROM curtidas_topicos WHERE user_id = ? AND topico_id = ?");
        $stmt->bind_param("ii", $user_id, $topico_id);
        $stmt->execute();
        $stmt->close();
    }
    
    // Redireciona de volta para a página do tópico
    header("Location: topico.php?id=" . $topico_id);
    exit;

} else {
    // Redireciona para a página inicial se a requisição for inválida
    header("Location: index.php");
    exit;
}
?>