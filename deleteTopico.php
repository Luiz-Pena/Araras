<?php
session_start();
require_once 'crud.php'; // Conecta ao banco e define $conn

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
        $conn->begin_transaction();

        try {
            // Exclui curtidas relacionadas ao tópico
            $stmt1 = $conn->prepare("DELETE FROM curtidas_topicos WHERE topico_id = ?");
            $stmt1->bind_param("i", $topico_id);
            $stmt1->execute();
            $stmt1->close();

            // Exclui respostas relacionadas ao tópico
            $stmt2 = $conn->prepare("DELETE FROM respostas WHERE topico_id = ?");
            $stmt2->bind_param("i", $topico_id);
            $stmt2->execute();
            $stmt2->close();

            // Exclui o tópico
            $stmt3 = $conn->prepare("DELETE FROM topicos WHERE id = ?");
            $stmt3->bind_param("i", $topico_id);
            $stmt3->execute();
            $stmt3->close();

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
        }
    }
}

header('Location: Pagina de perfil.php');
exit;
