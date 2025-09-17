<?php
// topico.php
session_start();
require_once 'crud.php'; // Certifique-se de que este arquivo conecta ao banco de dados.

// 1. Obter o ID do tópico da URL
$topico_id = $_GET['id'] ?? null;

if (!$topico_id) {
    die("ID do tópico não especificado.");
}

// 2. Buscar dados do tópico
$stmt = $conn->prepare("SELECT 
    t.titulo, t.conteudo, t.midia, t.created_at, p.nome AS autor_nome, p.avatar AS autor_avatar, t.user_id
    FROM topicos t 
    LEFT JOIN perfis p ON t.user_id = p.user_id
    WHERE t.id = ?");

$stmt->bind_param("i", $topico_id);
$stmt->execute();
$topico_result = $stmt->get_result();
$topico = $topico_result->fetch_assoc();
$stmt->close();

if (!$topico) {
    die("Tópico não encontrado.");
}

// 3. Buscar comentários (respostas) do tópico
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
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($topico['titulo']) ?> - Fórum Araras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <a href="index.php"> Voltar para o Início</a>
        
        <div class="card p-4">
            <div class="d-flex align-items-center mb-3">
                <img src="<?= htmlspecialchars($topico['autor_avatar'] ?? 'avatar_padrao.jpg') ?>" alt="Avatar do autor" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                <div>
                    <h1 class="card-title"><?= htmlspecialchars($topico['titulo']) ?></h1>
                    <small class="text-muted">Por <a href="perfil.php?id=<?= $topico['user_id'] ?>"><?= htmlspecialchars($topico['autor_nome'] ?? 'Usuário Removido') ?></a>, em <?= date('d/m/Y H:i', strtotime($topico['created_at'])) ?></small>
                </div>
            </div>
            
            <hr>
            
            <p class="card-text fs-5">
                <?= nl2br(htmlspecialchars($topico['conteudo'])) ?>
            </p>
            
            <?php if (!empty($topico['midia'])): ?>
                <img src="<?= htmlspecialchars($topico['midia']) ?>" class="img-fluid mt-3" alt="Mídia do tópico">
            <?php endif; ?>
        </div>

        <div class="mt-5">
            <h3>Comentários (<?= count($comentarios) ?>)</h3>
            <hr>

<div class="d-flex align-items-center justify-content-between mb-3">
    <?php
    $curtidas = 0;
    $stmt_curtidas = $conn->prepare("SELECT COUNT(*) FROM curtidas_topicos WHERE topico_id = ?");
    $stmt_curtidas->bind_param("i", $topico_id);
    $stmt_curtidas->execute();
    $stmt_curtidas->bind_result($curtidas);
    $stmt_curtidas->fetch();
    $stmt_curtidas->close();
    ?>
    <span class="text-muted"><?= htmlspecialchars($curtidas) ?> Curtidas</span>

    <?php if (isset($_SESSION['user_id'])): ?>
        <?php
        $ja_curtiu = false;
        $stmt_check = $conn->prepare("SELECT 1 FROM curtidas_topicos WHERE user_id = ? AND topico_id = ?");
        $stmt_check->bind_param("ii", $_SESSION['user_id'], $topico_id);
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows > 0) {
            $ja_curtiu = true;
        }
        $stmt_check->close();
        ?>
        <form action="curtir_topico.php" method="post">
            <input type="hidden" name="topico_id" value="<?= htmlspecialchars($topico_id) ?>">
            <?php if ($ja_curtiu): ?>
                <button type="submit" name="acao" value="descurtir" class="btn btn-danger">Descurtir</button>
            <?php else: ?>
                <button type="submit" name="acao" value="curtir" class="btn btn-primary">Curtir</button>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>

<hr>
            
            <?php if (!empty($comentarios)): ?>
                <?php foreach ($comentarios as $comentario): ?>
                    <div class="d-flex mb-3">
                        <img src="<?= htmlspecialchars($comentario['autor_avatar'] ?? 'avatar_padrao.jpg') ?>" alt="Avatar do autor" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                        <div class="card p-3 flex-grow-1">
                            <strong><?= htmlspecialchars($comentario['autor_nome'] ?? 'Usuário Removido') ?></strong>
                            <p class="mt-1 mb-1"><?= nl2br(htmlspecialchars($comentario['conteudo'])) ?></p>
                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($comentario['created_at'])) ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum comentário ainda.</p>
            <?php endif; ?>
        </div>
        
        <?php if (isset($_SESSION['user_id'])): ?>
    <div class="card p-4 mt-4">
        <h4>Adicionar Comentário</h4>
            <form action="adicionar_comentario.php" method="POST">
                <input type="hidden" name="topico_id" value="<?= htmlspecialchars($topico_id) ?>">
                    <div class="mb-3">
                        <textarea class="form-control" name="conteudo" rows="3" required placeholder="Escreva seu comentário..."></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="url" class="form-control" name="midia" placeholder="Opcional: URL de imagem/vídeo">
                    </div>
                    <button type="submit" class="btn btn-primary">Publicar Comentário</button>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-info mt-4">
                <a href="login.php">Faça login</a> para adicionar um comentário.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>