<?php
    include 'crud.php';
    include 'ultimosEventos.php';
    include 'topicosRecentes.php';

    session_start();

    // Verifique se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tituloTopico'])) {
    if (!isset($_SESSION['user_id'])) {
        // Redireciona se o usuário não estiver logado
        header("Location: Pagina de login.php");
        exit;
    }
    
    // Obtenha os dados do formulário
    $titulo = $conn->real_escape_string($_POST['tituloTopico']);
    $conteudo = $conn->real_escape_string($_POST['conteudoTopico']);
    $categoria_nome = $conn->real_escape_string($_POST['categoriaTopico']);
    $midia = $_POST['midiaTopico'] ?? null;
    $user_id = $_SESSION['user_id'];
    
    // Adicione a verificação de segurança aqui
    $stmt_user = $conn->prepare("SELECT id FROM usuarios WHERE id = ?");
    $stmt_user->bind_param("i", $user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows === 0) {
        // Redireciona se o ID do usuário na sessão é inválido
        header("Location: Pagina de login.php");
        exit;
    }
    $stmt_user->close();
    
    // Obtenha o ID da categoria a partir do nome
    $sql_categoria = "SELECT id FROM categorias WHERE nome = '$categoria_nome'";
    $result_categoria = $conn->query($sql_categoria);
    
    if ($result_categoria && $result_categoria->num_rows > 0) {
        $row = $result_categoria->fetch_assoc();
        $categoria_id = $row['id'];
        
        // Prepare a query de inserção
        $sql = "INSERT INTO topicos (titulo, conteudo, user_id, categoria_id, midia) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt_topico = $conn->prepare($sql);
        $stmt_topico->bind_param("ssiis", $titulo, $conteudo, $user_id, $categoria_id, $midia);
        
        if ($stmt_topico->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "Erro ao criar tópico: " . $conn->error;
        }
        $stmt_topico->close();
    } else {
        echo "Erro: Categoria não encontrada.";
    }
}

function renderizarOpcoesCategorias($conn) {
    $sql = "SELECT id, nome FROM categorias ORDER BY nome ASC";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($row['nome']) . '">' . htmlspecialchars($row['nome']) . '</option>';
        }
    } else {
        echo '<option value="">Nenhuma categoria encontrada</option>';
    }
}

function buscarTopicosRecentes($conn) {
    $topicos = [];
    $sql = "SELECT 
                t.id, 
                t.titulo, 
                t.created_at, 
                t.conteudo,
                t.user_id,  
                p.nome AS autor_nome,
                p.avatar AS autor_avatar,
                c.nome AS categoria_nome,
                (SELECT COUNT(*) FROM respostas WHERE topico_id = t.id) AS contagem_respostas
            FROM topicos t
            LEFT JOIN perfis p ON t.user_id = p.user_id
            LEFT JOIN categorias c ON t.categoria_id = c.id
            ORDER BY t.created_at DESC
            LIMIT 10"; 

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $topicos[] = $row;
        }
    }
    return $topicos;
}

$topicosRecentes = buscarTopicosRecentes($conn);
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fórum Araras</title>

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
        <h2>Tópicos Recentes</h2>
        <button class="botao botao-primario btn" data-bs-toggle="modal" data-bs-target="#modalNovoTopico">Criar Novo Tópico</button>
    </div>

    <div class="lista-topicos" id="listaTopicos">
    <?php if (!empty($topicosRecentes)): ?>
        <?php foreach ($topicosRecentes as $topico): ?>
            <div class="cartao-topico">
                <div class="topico-autor-avatar">
                    <a href="Pagina de perfil.php?id=<?= htmlspecialchars($topico['user_id']) ?>">
                        <img src="<?= htmlspecialchars($topico['autor_avatar'] ?? 'avatar_padrao.jpg') ?>" alt="Avatar do autor" class="rounded-circle">
                    </a>
                </div>
                <div class="topico-detalhes">
                    <h3 class="topico-titulo"><a href="topico.php?id=<?= htmlspecialchars($topico['id']) ?>"><?= htmlspecialchars($topico['titulo']) ?></a></h3><div class="topico-info">
                        Iniciado por 
                        <a href="Pagina de perfil.php?id=<?= htmlspecialchars($topico['user_id']) ?>" class="nome-autor">
                            <?= htmlspecialchars($topico['autor_nome'] ?? 'Usuário Removido') ?>
                        </a> 
                        em <a href="categorias.php?curso=<?= urlencode($topico['categoria_nome']) ?>" class="topico-categoria"><?= htmlspecialchars($topico['categoria_nome']) ?></a>
                        <span class="topico-estatisticas">
                            <?= htmlspecialchars($topico['contagem_respostas']) ?> Respostas
                        </span>
                    </div>
                </div>
                <div class="topico-ultima-postagem">
                    <span>Última resposta</span>
                    <span><?= date('d/m/Y H:i', strtotime($topico['created_at'])) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhum tópico encontrado.</p>
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

    <div class="modal fade" id="modalNovoTopico" tabindex="-1" aria-labelledby="modalNovoTopicoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="formNovoTopico" method="post" action="index.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNovoTopicoLabel">Criar Novo Tópico</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tituloTopico" class="form-label">Título</label>
                        <input type="text" class="form-control" id="tituloTopico" name="tituloTopico" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoriaTopico" class="form-label">Categoria</label>
                        <select class="form-select" id="categoriaTopico" name="categoriaTopico" required>
                            <?php renderizarOpcoesCategorias($conn); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="conteudoTopico" class="form-label">Conteúdo</label>
                        <textarea class="form-control" id="conteudoTopico" name="conteudoTopico" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="midiaTopico" class="form-label">Mídia (URL)</label>
                        <input type="url" class="form-control" id="midiaTopico" name="midiaTopico" placeholder="Opcional: URL de imagem/vídeo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Publicar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous">
    </script>

        
</body>

</html>