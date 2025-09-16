<?php
// fetch_topico.php

header('Content-Type: application/json');

include 'crud.php';

// Get the topic ID from the URL
$topico_id = $_GET['id'] ?? null;

$response = [
    'success' => false,
    'data' => null,
    'error' => ''
];

if (!$topico_id) {
    $response['error'] = 'ID do tópico não especificado.';
    echo json_encode($response);
    exit;
}

// Prepare and execute a query to get the topic details
$stmt = $conn->prepare("SELECT 
    t.titulo, t.conteudo, t.midia, t.created_at, p.nome AS autor_nome, p.avatar AS autor_avatar
    FROM topicos t 
    LEFT JOIN perfis p ON t.user_id = p.user_id
    WHERE t.id = ?");
$stmt->bind_param("i", $topico_id);
$stmt->execute();
$topico_result = $stmt->get_result();
$topico = $topico_result->fetch_assoc();
$stmt->close();

if (!$topico) {
    $response['error'] = 'Tópico não encontrado.';
    echo json_encode($response);
    exit;
}

// Prepare and execute a query to get all comments for the topic
$stmt = $conn->prepare("SELECT 
    r.conteudo, r.created_at, p.nome AS autor_nome, p.avatar AS autor_avatar
    FROM respostas r 
    LEFT JOIN perfis p ON r.user_id = p.user_id 
    WHERE r.topico_id = ? 
    ORDER BY r.created_at ASC");
$stmt->bind_param("i", $topico_id);
$stmt->execute();
$comentarios_result = $stmt->get_result();

$comentarios = [];
while ($row = $comentarios_result->fetch_assoc()) {
    $comentarios[] = $row;
}
$stmt->close();

$response['success'] = true;
$response['data'] = [
    'topico' => $topico,
    'comentarios' => $comentarios
];

echo json_encode($response);
$conn->close();
?>