<?php
session_start();
require_once 'crud.php'; // Certifique-se que conecta ao banco e define $conn

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['topico_id'])) {
    $topico_id = intval($_POST['topico_id']);

    // Verifica se o tópico pertence ao usuário logado
    $stmt = $conn->prepare("SELECT user_id FROM topicos WHERE id = ?");
    $stmt->bind_param("i", $topico_id);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if ($user_id == $_SESSION['user_id']) {
        // Exclui curtidas e respostas relacionadas ao tópico
        $conn->prepare("DELETE FROM curtidas_topicos WHERE topico_id = ?")->bind_param("i", $topico_id)->execute();
        $conn->prepare("DELETE FROM respostas WHERE topico_id = ?")->bind_param("i", $topico_id)->execute();

        // Exclui o tópico
        $stmt = $conn->prepare("DELETE FROM topicos WHERE id = ?");
        $stmt->bind_param("i", $topico_id);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: Pagina de perfil.php');
exit;
