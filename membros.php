<?php
session_start();
include 'crud.php';
include 'ultimosEventos.php';
include 'topicosRecentes.php';

// Consulta SQL para buscar todos os membros e a contagem de tópicos
// A consulta une as tabelas `perfis` e `topicos` para contar os posts de cada usuário
$sql = "SELECT 
            p.user_id, 
            p.nome, 
            p.avatar, 
            p.created_at,
            COUNT(t.id) AS contagem_topicos
        FROM perfis p
        LEFT JOIN topicos t ON p.user_id = t.user_id
        GROUP BY p.user_id, p.nome, p.avatar, p.created_at
        ORDER BY p.created_at DESC";
        
$result = $conn->query($sql);

$membros = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $membros[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membros - Fórum Araras</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">

    <link rel="icon" href="92020.png" type="image/png">
</head>

<body>
    <?php
        include 'cabeçalho.php';
        renderHeader('eventos');
    ?>

    <div class="container conteudo-pagina">
        <main class="conteudo-principal">
            <div class="topicos-cabecalho">
                <h2>Membros do Fórum</h2>
            </div>
            <div class="lista-membros">
                <?php if (!empty($membros)): ?>
                    <?php foreach ($membros as $membro): ?>
                        <div class="cartao-topico">
                            <div class="topico-autor-avatar">
                                <a href="Pagina de perfil.php?id=<?= htmlspecialchars($membro['user_id']) ?>">
                                    <img src="<?= htmlspecialchars($membro['avatar'] ?? 'avatar_padrao.jpg') ?>" alt="Avatar de <?= htmlspecialchars($membro['nome']) ?>" class="rounded-circle">
                                </a>
                            </div>
                            <div class="topico-detalhes">
                                <h3 class="topico-titulo">
                                    <a href="Pagina de perfil.php?id=<?= htmlspecialchars($membro['user_id']) ?>">
                                        <?= htmlspecialchars($membro['nome']) ?>
                                    </a>
                                </h3>
                                <div class="topico-info">
                                    <span>Membro desde: <?= date('d \d\e F, Y', strtotime($membro['created_at'])) ?></span>
                                    <span class="topico-estatisticas">
                                        <?= htmlspecialchars($membro['contagem_topicos']) ?> Tópicos
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum membro encontrado.</p>
                <?php endif; ?>
            </div>
        </main>

        <aside class="barra-lateral">
            <?php
                renderEventosCaixa($conn);
                renderizarCategorias($conn);
            ?>
        </aside>
    </div>

    <div class="modal fade" id="modalLogin" tabindex="-1" aria-labelledby="modalLoginLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="formLogin">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLoginLabel">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="loginUsuario" class="form-label">Usuário</label>
                        <input type="text" class="form-control" id="loginUsuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="loginSenha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="loginSenha" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>