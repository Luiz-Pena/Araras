<?php
// adicionar_comentario.php
session_start();
require_once 'crud.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die("Você precisa estar logado para comentar.");
    }
    
    // Obtenha todos os dados do formulário
    $conteudo = $conn->real_escape_string($_POST['conteudo']);
    $topico_id = $_POST['topico_id'];
    $user_id = $_SESSION['user_id'];
    $midia = $_POST['midia'] ?? null; // Obtém a URL da mídia, se houver
    
    // Prepare a query de inserção para incluir a coluna 'midia'
    $stmt = $conn->prepare("INSERT INTO respostas (conteudo, user_id, topico_id, midia) VALUES (?, ?, ?, ?)");
    
    // Verifique se a mídia está vazia e ajuste os parâmetros
    if ($midia) {
        $stmt->bind_param("siis", $conteudo, $user_id, $topico_id, $midia);
    } else {
        $stmt->bind_param("sii", $conteudo, $user_id, $topico_id);
    }

    if ($stmt->execute()) {
        header("Location: topico.php?id=" . $topico_id);
        exit;
    } else {
        echo "Erro ao adicionar comentário: " . $conn->error;
    }
    $stmt->close();
} else {
    header("Location: index.php");
    exit;
}
?>